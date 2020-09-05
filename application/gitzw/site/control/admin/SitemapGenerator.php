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
	
	function __construct() {
		$this->sitemap = Site::get()->documentRoot().'/sitemap.xml';
		if ($this->sitemap == '/sitemap.xml') {
			$this->sitemap = GZ::ROOT.'/public_html/sitemap.xml';
		}
	}
	
	public function checkSitemap() {
		if (!file_exists($this->sitemap)) {
			echo 'the file '.$this->sitemap.' does not exist';
			return;
		}
		$count = 0;
		$failures = 0;
		$errors = [];
		echo 'Start check'.'<br/>'.PHP_EOL;
		$xr = new XMLReader();
		$xr->open($this->sitemap);
		$xr->next('urlset');
		while ($xr->read()) {
			if ($xr->name === 'loc' or $xr->name == 'image:loc') {
				$xr->read();
				$loc = $xr->value;
				$count++;
				
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
				echo $httpCode.' - '.$loc.'  ['.$count.']<br/>'.PHP_EOL;
				$xr->read();
			}
		}
		
		echo '---------------------------------------------------<br/>'.PHP_EOL;
		echo $failures.' failures on '.$count.' pages'.'<br/>'.PHP_EOL;
		echo '---------------------------------------------------<br/>'.PHP_EOL;
		foreach ($errors as $loc=>$code) {
			echo $code.' &lt;- '.$loc.'<br/>'.PHP_EOL;
		}
	}
	
	public function generateSitemap() {
		$this->createSitemap();
		Site::get()->redirect('/sitemap.xml');
	}
	
	public function createSitemap() {
		$xw = new XMLWriter();
		$xw->openUri($this->sitemap);
		$xw->setIndent(2);
		$xw->setIndentString(' ');
		$xw->startDocument('1.0', 'UTF-8');
		
		$xw->startElementNs(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$xw->writeAttributeNs('xmlns', 'image', null, 'http://www.google.com/schemas/sitemap-image/1.1');
 		
 		// homepage
 		$xw->startElement('url');
 		
 		$xw->startElement('loc');
 		$xw->text(Site::get()->hostName());
 		$xw->endElement();
 		
 		$xw->startElement('lastmod');
 		$xw->text(GZ::VERSION_DATE);
 		$xw->endElement();
 		
 		$xw->startElement('changefreq');
 		$xw->text('monthly');
 		$xw->endElement();
 		
 		$xw->endElement();
 		// end homepage
 		
 		// searchpage
 		$xw->startElement('url');
 		
 		$xw->startElement('loc');
 		$xw->text(Site::get()->hostName().'/search');
 		$xw->endElement();
 		
 		$xw->startElement('changefreq');
 		$xw->text('allways');
 		$xw->endElement();
 		
 		$xw->endElement();
 		// end searchpage
 		
 		$sr = SiteResources::get();
 		
 		foreach ($sr->getVisarts() as $artist) {
 			// personal home pages
 			$xw->startElement('url');
 			
 			$xw->startElement('loc');
 			$xw->text(Site::get()->hostName().$artist->getResourcePath());
 			$xw->endElement();
 			
 			$xw->startElement('lastmod');
 			$xw->text(GZ::VERSION_DATE);
 			$xw->endElement();
 			
 			$xw->endElement();
 			
 			$work = $artist->getChildByName('work');
 			foreach ($work->getchildren() as $activity) {
 				foreach ($activity->getChildren() as $year) {
 					
 					// overview pages per year
 					$total = $year->getPublicResourceCount();
 					$ipp = OverviewPageControl::ITEMS_PER_PAGE;
 					for ($i = 0; $i < $total; $i += $ipp) {
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text(Site::get()->hostName().$year->getResourcePath().'/overview/chrono/'.$i);
 						$xw->endElement();
 						
 						$xw->startElement('lastmod');
 						$xw->text(GZ::VERSION_DATE);
 						$xw->endElement();
 						
 						$xw->endElement();
 					}
 					// end overview pages per year
 					
 					$representations = [];
 					foreach ($year->getPublicResourcesOrdered() as $resource) {
 						
 						// public resource page
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text($resource->getFullUrl());
 						$xw->endElement();
 						
 						$xw->startElement('lastmod');
 						$xw->text(GZ::VERSION_DATE);
 						$xw->endElement();
 						
 						$xw->startElement('priority');
 						$xw->text('0.8');
 						$xw->endElement();
 						
 						// image
 						$xw->startElement('image:image');
 						
 						$xw->startElement('image:loc');
 						$xw->text($resource->getRepresentation()->getDefaultURL());
 						$xw->endElement();
 						
 						$xw->startElement('image:caption');
 						$xw->text($resource->getCreator()->getFullName().' - '.$resource->getHtmlTitle().' - '.$resource->getMedia());
 						$xw->endElement();
 						
 						$xw->startElement('image:license');
 						$xw->text('https://creativecommons.org/licenses/by-nc-nd/4.0/');
 						$xw->endElement();
 						
 						$xw->endElement();
 						// end image
 						
 						$xw->endElement();
 						
 						// json
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text($resource->getFullUrl().'.json');
 						$xw->endElement();
 						
 						$xw->endElement();
 						
 						// rdf
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text($resource->getFullUrl().'.rdf');
 						$xw->endElement();
 						
 						$xw->endElement();
 						
 						// n3
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text($resource->getFullUrl().'.n3');
 						$xw->endElement();
 						
 						$xw->endElement();
 						
 						// nt
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text($resource->getFullUrl().'.nt');
 						$xw->endElement();
 						
 						$xw->endElement();
 						
 						
 						foreach ($resource->getPublicRepresentationsOrdered() as $rep) {
 							$representations[] = $rep->getLocation();
 						}	
 					}
 					
					// unique representations
 					foreach (array_unique($representations) as $rep) {
 						// exif page
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text(Site::get()->hostName().'/exif-data/'.$rep);
 						$xw->endElement();
 						
 						$xw->startElement('priority');
 						$xw->text('0.3');
 						$xw->endElement();
 						
 						$xw->endElement();
 						
 						// zoom page
 						$xw->startElement('url');
 						
 						$xw->startElement('loc');
 						$xw->text(Site::get()->hostName().'/zoom/'.$rep);
 						$xw->endElement();
 						
 						$xw->endElement();
 						
 					}
 				}
 			}
 			
 		}
		
		$xw->endElement();
		$xw->endDocument();
		$xw->flush();
	}
}

