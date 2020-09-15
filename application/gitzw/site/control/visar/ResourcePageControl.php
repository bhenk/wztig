<?php

namespace gitzw\site\control\visar;

use EasyRdf\Graph;
use EasyRdf\RdfNamespace;
use gitzw\GZ;
use gitzw\site\data\Aat;
use gitzw\site\data\ImageData;
use gitzw\site\data\Site;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\Representation;
use gitzw\site\model\Resource;
use gitzw\site\model\SiteResources;
use gitzw\site\model\Visart;
use EasyRdf\Literal;


class ResourcePageControl extends VisartPageControl {

	protected $resource;
	private $nextResource;
	private $previousResource;
	protected $mainRepresentation;
	protected $imgData;
	
	private $format;
	
	function __construct(Visart $visart, array $path) {
		parent::__construct($visart, $path);
		$parts = explode('.', $path[5]);
		$this->format = $parts[1];
		$path[5] = $parts[0];
		$this->resource = SiteResources::get()->getResourceByFullNamePath($path);
		if (is_null($this->resource)) {
			throw new NotFoundException('resource does not exist.');
		}
		$this->nextResource = $this->resource->getParent()->nextPublicResource($this->resource->getId());
		$this->previousResource = $this->resource->getParent()->previousPublicResource($this->resource->getId());
		$this->mainRepresentation = $this->resource->getRepresentation();
		$ida = new ImageData($this->getImagePath());
		$this->imgData = $ida->resize(self::IMG_WIDTH, self::IMG_HEIGHT);
		
		$this->setTitle($this->resource->getHtmlTitle().' - '.$visart->getFullName().' - '
				.$this->resource->getLongId());
		
		$this->setMetaDescription(implode(' and ', $this->resource->getSdAdditionalTypes())
				.'. '.$this->resource->getMedia()
				.', ('.$this->resource->getDimensions()
				.') Gitzwart, fine art.');
		$this->setContentFile(GZ::TEMPLATES.'/views/resource-view.php');
		$this->constructMenu();
	}
	
	public function renderPage() {
		if (isset($this->format)) {
			$this->sendRdf();
			return;
		} else {
			parent::renderPage();
		}
	}
		
	protected function getResource() : Resource {
		return $this->resource;
	}
	
	protected function getRepresentation() : Representation {
		return $this->mainRepresentation;
	}
	
	protected function getImagePath() : string {
		return GZ::DATA.'/images/'.$this->mainRepresentation->getLocation();
	}
	
	protected function hasNext() : bool {
		return !is_null($this->nextResource);
	}
	
	protected function hasPrevious() : bool {
		return !is_null($this->previousResource);
	}
	
	protected function nextUrl() : ?string {
		return $this->hasNext() ? $this->nextResource->getResourcePath() : '';
	}
	
	protected function previousUrl() : ?string {
		return $this->hasPrevious() ? $this->previousResource->getResourcePath() : '';
	}
	
	protected function getRepresentations() {
		$reps = $this->resource->getRepresentations();
		unset($reps[$this->mainRepresentation->getLocation()]);
		return $reps;
	}
	
	/**
	 * Prevent parent from doing things.
	 * {@inheritDoc}
	 * @see \gitzw\site\control\DefaultPageControl::renderStructuredData()
	 */
	protected function renderStructuredData() {}
	
	public function getStructuredData() {
		$imgId = $this->resource->getRepresentation()->getFullId();
		$imgURL = Site::get()->hostName().$this->imgData['location'];
		$sdResource = $this->resource->getStructuredData($imgId);
		$sdImage = [
				"@type"=>"ImageObject",
				"@id"=>$imgId,
				"url"=>$imgURL,
				"copyrightHolder"=>$this->visart->getFullId()
		];
		return [
				"@context"=>["http://schema.org", 
						['aat'=>'http://vocab.getty.edu/aat/']],
				"@graph"=>[$sdResource,
						$sdImage,
						$this->getWebPageSD()
				]
		];
	}
	
	private function getWebPageSD() {
		$links = [];
		$links[] = Site::get()->hostName().'/zoom/'.$this->mainRepresentation->getLocation();
		if ($this->hasNext()) $links[] = Site::get()->hostName().$this->nextResource->getResourcePath();
		if ($this->hasPrevious()) $links[] = Site::get()->hostName().$this->previousResource->getResourcePath();
		return [
				"@type"=>"WebPage",
				"@id"=>"https://gitzw.art".$this->resource->getResourcePath(),
				"url"=>"https://gitzw.art".$this->resource->getResourcePath(),
				"mainEntity"=>[
				"@id"=>$this->resource->getFullId()
				],
				"relatedLink"=>$links
		];
	}
	
	private function sendRdf() {
		if ($this->format == 'jsonld' or $this->format == 'json') {
			$str = json_encode($this->getStructuredData(), JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
			$contentType = 'application/json';
			$ext = '.json';
		} else {
			$g = $this->createRdfGraph();
			if ($this->format == 'rdf') {
				$str = $g->serialise('rdf');
				$contentType = 'application/rdf+xml';
				$ext = '.rdf';
			} elseif ($this->format == 'n3') {
				$str = $g->serialise('n3');
				$contentType = 'text/n3';
				$ext = '.n3';
			} elseif ($this->format == 'turtle' or $this->format == 'ttl') {
				$str = $g->serialise('turtle');
				$contentType = 'text/turtle';
				$ext = '.ttl';
			} elseif ($this->format == 'ntriples' or $this->format == 'nt') {
				$str = $g->serialise('ntriples');
				$contentType = 'application/rdf-triples';
				$ext = '.nt';
			} else {
				throw new NotFoundException('unknown format: '.$this->format);
			}
		}
		ob_end_clean(); // see Gitz, ob_start([$this, 'sanitize_output']);
		header("Content-type: ".$contentType);
		header("Content-disposition: attachment; filename = ".$this->resource->getLongId().$ext);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Content-Length: ' .strlen($str));
		echo $str;
	}
	
	private function createRdfGraph() : Graph {
		RdfNamespace::set('aat', Aat::AAT_URL);
		RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
		RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
		RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
		RdfNamespace::set('gitz', 'http://gitzw.art/');
		RdfNamespace::set('schema', 'http://schema.org/');
		RdfNamespace::set('dct', 'http://purl.org/dc/terms/');
		RdfNamespace::set('qudt', 'http://qudt.org/schema/qudt/');
		RdfNamespace::set('unit', 'http://qudt.org/vocab/unit/');
		
		$g = new Graph();
		$res = $g->resource($this->resource->getFullId(), 'rdf:Description');
		$res->addResource('rdf:type', new \EasyRdf\Resource('schema:VisualArtwork'));
		foreach ($this->resource->getAdditionalTypeCodes() as $type) {
			$res->add('rdf:type', new \EasyRdf\Resource($type));
		}
		$res->add('schema:url', new \EasyRdf\Resource($this->resource->getFullUrl()));
		$res->add('schema:image', new \EasyRdf\Resource($this->resource->getRepresentation()->getFullId()));
		$res->addLiteral('rdfs:label', $this->resource->getTitles()['nl'], 'nl');
		$res->addLiteral('rdfs:label', $this->resource->getTitles()['en'], 'en');
		
		if ($this->resource->getWidth() > 0 and $this->resource->getHeight() > 0) {
			$bn = $g->newBNode();
			$bn->add('rdf:value', Literal::create($this->resource->getWidth(), null, 'xsd:decimal'));
			$bn->addResource('qudt:unit', new \EasyRdf\Resource('unit:CentiM'));
			$res->addResource('qudt:width', $bn);
			$bn = $g->newBNode();
			$bn->add('rdf:value', Literal::create($this->resource->getHeight(), null, 'xsd:decimal'));
			$bn->addResource('qudt:unit', new \EasyRdf\Resource('unit:CentiM'));
			$res->addResource('qudt:height', $bn);
		}
		
		foreach ($this->resource->getArtMediumCodes() as $medium) {
			$res->add('schema:artMedium', new \EasyRdf\Resource($medium));
		}
		
		foreach ($this->resource->getArtworkSurfaceCodes() as $surface) {
			$res->add('schema:artworkSurface', new \EasyRdf\Resource($surface));
		}
		$dateCreated = $this->resource->getDateCreated();
		if (isset($dateCreated)) {
			$res->add('dct:created', Literal::create($this->resource->getDateCreated(), null, 'xsd:dateTime'));
		}
		$res->add('dct:creator', new \EasyRdf\Resource($this->visart->getFullId()));
		$res->add('schema:copyrightHolder', new \EasyRdf\Resource($this->visart->getFullId()));
		$res->add('schema:license', new \EasyRdf\Resource('https://creativecommons.org/licenses/by-nc-nd/4.0/'));
		
		$img = $g->resource($this->resource->getRepresentation()->getFullId(), 'rdf:Description');
		$img->addResource('rdf:type', new \EasyRdf\Resource('schema:ImageObject'));
		$img->add('schema:url', new \EasyRdf\Resource($this->resource->getRepresentation()->getDefaultFullURL()));
		$img->add('schema:copyrightHolder', new \EasyRdf\Resource($this->visart->getFullId()));
		return $g;
	}
	
	
}

