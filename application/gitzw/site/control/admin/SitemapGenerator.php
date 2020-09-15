<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\visar\OverviewPageControl;
use gitzw\site\data\Site;
use gitzw\site\model\SiteResources;
use XMLReader;
use XMLWriter;

class SitemapGenerator {
	
	private $sitemap;
	
	private $xw;
	private $hostName;
	private $lastMods;
	private $entrycount;
	private $imageCount;
	
	function __construct() {
		$this->sitemap = Site::get()->documentRoot().'/sitemap.xml';
		if ($this->sitemap == '/sitemap.xml') {
			$this->sitemap = GZ::ROOT.'/public_html/sitemap.xml';
		}
	}
	
	public function checkSitemap() {
		ob_end_clean(); // see Gitz, ob_start([$this, 'sanitize_output']);
		if (!file_exists($this->sitemap)) {
			echo 'the file '.$this->sitemap.' does not exist';
			return;
		}
		echo '<br/><br/><a href="/sitemap">sitemap</a><hr>';
		$this->entrycount = 0;
		$this->imageCount = 0;
		$failures = 0;
		$errors = [];
		echo 'Start check'.'<br/>'.PHP_EOL;
		$xr = new XMLReader();
		$xr->open($this->sitemap);
		$xr->next('urlset');
		while ($xr->read()) {
			if ($xr->name === 'loc') {
				$this->entrycount++;
			}
			if ($xr->name == 'image:loc') {
				$this->imageCount++;
			}
			if ($xr->name === 'loc' or $xr->name == 'image:loc') {
				$xr->read();
				$loc = $xr->value;
				
				$handle = curl_init();
				curl_setopt($handle, CURLOPT_URL, $loc);
				curl_setopt($handle, CURLOPT_HEADER  , true);  // we want headers
				curl_setopt($handle, CURLOPT_NOBODY  , true);  // we don't need body
				curl_setopt($handle, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($handle, CURLOPT_TIMEOUT,10);
				curl_setopt($handle, CURLOPT_MAXREDIRS, 10);
				curl_exec($handle);
				print curl_error($handle);
				$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
				if ($httpCode != 200) {
					$failures++;
					$errors[$loc] = $httpCode;
				}
				curl_close($handle);
				$this->report($loc, $httpCode);
				//echo $httpCode.' - '.$loc.'  ['.$count.']<br/>'.PHP_EOL;
				$xr->read();
			}
		}
		
		echo '---------------------------------------------------<br/>'.PHP_EOL;
		echo $failures.' failures on '.$this->entrycount.' pages and '
				.$this->imageCount.' images | <a href="/sitemap">download sitemap</a> |'.'<br/>'.PHP_EOL;
		echo '---------------------------------------------------<br/>'.PHP_EOL;
		foreach ($errors as $loc=>$code) {
			echo $code.' &lt;- '.$loc.'<br/>'.PHP_EOL;
		}
	}
	
	public function generateSitemap() {
		try {
			ob_end_clean(); // see Gitz, ob_start([$this, 'sanitize_output']);
			echo '<br/><br/><a href="/sitemap">sitemap</a><hr>';
			$this->createSitemap();	
		} catch (\Exception $e) {
			echo str_replace("\n", "<br/>\n", $e->__toString());
		}
	}
	
	public function createSitemap() {
		$filename = $this->sitemap.'.new';
		$this->entrycount = 0;
		$this->imageCount = 0;
		$this->lastMods = json_decode(file_get_contents($this->getLastModFilename()), TRUE);
		$this->hostName = Site::get()->hostName();
		if ($this->hostName == 'http://no_host') {
			$this->hostName = 'http://localhost:8080';
		}
		if ($this->hostName == 'http://localhost:8080') {
			$this->hostName = 'http://localhost';
		}
		$this->xw = new XMLWriter();
		$this->xw->openUri($filename);
		$this->xw->setIndent(true);
		$this->xw->setIndentString("   ");
		$this->xw->startDocument('1.0', 'UTF-8');
		
		$this->xw->startElementNs(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$this->xw->writeAttributeNs('xmlns', 'image', null, 'http://www.google.com/schemas/sitemap-image/1.1');
 		
 		// homepage
		$this->writeEntry('', ['changefreq'=>'monthly']);
 		// searchpage
		// $this->writeEntry('/search', ['changefreq'=>'always']);
		
 		$sr = SiteResources::get();
 		foreach ($sr->getVisarts() as $artist) {
 			// personal home pages
 			$this->writeEntry($artist->getResourcePath());
 			$work = $artist->getChildByName('work');
 			foreach ($work->getchildren() as $activity) {
 				foreach ($activity->getChildren() as $year) {
 					// overview pages per year
 					$total = $year->getPublicResourceCount();
 					$ipp = OverviewPageControl::ITEMS_PER_PAGE;
 					for ($i = 0; $i < $total; $i += $ipp) {
 						$this->writeEntry($year->getResourcePath().'/overview/chrono/'.$i);
 					}
 					$representations = [];
 					foreach ($year->getPublicResourcesOrdered() as $resource) {
 						// public resource page
 						$this->writeEntry($resource->getResourcePath(), [
 								'priority'=>'0.8',
 								'imgLoc'=>$resource->getRepresentation()->getDefaultURL(),
 								'imgCaption'=>$resource->getCreator()->getFullName().' - '.$resource->getHtmlTitle().' - '.$resource->getMedia()
 								]);
 						// json, rdf, n3, nt
 						$this->writeEntry($resource->getResourcePath().'.json');
 						$this->writeEntry($resource->getResourcePath().'.rdf');
 						$this->writeEntry($resource->getResourcePath().'.n3');
 						$this->writeEntry($resource->getResourcePath().'.nt'); 						
 						// collect representations of each resource
 						foreach ($resource->getPublicRepresentationsOrdered() as $rep) {
 							$representations[] = $rep->getLocation();
 						}	
 					}
					// unique representations
 					foreach (array_unique($representations) as $rep) {
 						// exif page
 						$this->writeEntry('/exif-data/'.$rep, ['priority'=>'0.2']);
 						// zoom page
 						// $this->writeEntry('/zoom/'.$rep);
 					}
 				}
 			}
 		}
		$this->xw->endElement();
		$this->xw->endDocument();
		$this->xw->flush();
		
		file_put_contents($this->getLastModFilename(),
				json_encode($this->lastMods, JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES), LOCK_EX);
		$success = rename($filename, $this->sitemap);
		echo '-------------------------------------------------------------------<br/>'.PHP_EOL;
		echo $success ? 'success: ' : 'failures: ';
		echo $this->entrycount.' pages and '
				.$this->imageCount.' images | <a href="/sitemap">download sitemap</a> |'.'<br/>'.PHP_EOL;
		echo '-------------------------------------------------------------------<br/>'.PHP_EOL;
		echo '<a href="/sitemap">sitemap</a>';
	}
	
	private function getLastModFilename() : string {
		return GZ::DATA.'/lastmods.json';
	}
	
	private function getLastMod(string $loc) : string {
		$prevSha1 = $this->lastMods[$loc]['sha1'];
		$currSha1 = $this->getSha1($loc);
		if ($currSha1 != $prevSha1) {
			$lastmod = date('Y-m-d');
			$this->lastMods[$loc]['lastMod'] = $lastmod;
			$this->lastMods[$loc]['sha1'] = $currSha1;
		} else {
			$lastmod = $this->lastMods[$loc]['lastMod'];
		}
		return $lastmod;
	}
	
	private function getSha1(string $loc) : string {
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $this->hostName.$loc);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($handle, CURLOPT_TIMEOUT,10);
		curl_setopt($handle, CURLOPT_MAXREDIRS, 10);
		curl_exec($handle);
		$error = curl_error($handle);
		if ($error != '') {
			throw new \Exception('error in url "'.$loc.'" '.$error);
		}
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if ($httpCode != 200) {
			throw new \Exception('wrong code for url "'.$loc.'" '.$httpCode);
		}
		$content = curl_multi_getcontent($handle);
		curl_close($handle);
		return sha1($content);
	}
	
	private function writeEntry(string $loc, array $options=[]) {
		$this->entrycount++;
		$lastMod = $this->getLastMod($loc);
		//echo $this->entrycount.' '.$this->hostName.$loc.PHP_EOL;
		$this->xw->startElement('url');
		
		$this->xw->startElement('loc');
		$this->xw->text($this->hostName.$loc);
		$this->xw->endElement();
		
		$this->xw->startElement('lastmod');
		$this->xw->text($lastMod);
		$this->xw->endElement();
		
		if (isset($options['changefreq'])) {
			$this->xw->startElement('changefreq');
			$this->xw->text($options['changeFreq']);
			$this->xw->endElement();
		}
			
		if (isset($options['priority'])) {
			$this->xw->startElement('priority');
			$this->xw->text($options['priority']);
			$this->xw->endElement();
		}
		
		if (isset($options['imgLoc'])) {
			$this->imageCount++;
			$this->xw->startElement('image:image');
			
			$this->xw->startElement('image:loc');
			$this->xw->text($this->hostName.$options['imgLoc']);
			$this->xw->endElement();
			
			$this->xw->startElement('image:caption');
			$this->xw->text($options['imgCaption']);
			$this->xw->endElement();
			
			$this->xw->startElement('image:license');
			$this->xw->text('https://creativecommons.org/licenses/by-nc-nd/4.0/');
			$this->xw->endElement();
			
			$this->xw->endElement();
		}
		
		$this->xw->endElement();
		$this->report($this->hostName.$loc, $lastMod);
	}
	
	private function report(string $loc, string $lastMod) {
		echo '<span style="position: absolute;left:10;top:10;z-index:'.$this->entrycount.';background:#FFF;">'
				.$this->entrycount.' '.$lastMod.' '.$loc.str_repeat('&nbsp;', 100).'</span>';
		echo '<span>'.$this->entrycount.' '.$lastMod.' '
				.'<a href="'.$loc.'">'.$loc.'</a>'
				.'</span><br/>';
		if (@ob_get_contents()) {
			@ob_end_flush();
		}
		flush();
	}
	
}

