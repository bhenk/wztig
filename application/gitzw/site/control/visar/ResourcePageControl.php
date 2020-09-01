<?php

namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\data\ImageData;
use gitzw\site\data\Site;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\Path;
use gitzw\site\model\Representation;
use gitzw\site\model\Resource;
use gitzw\site\model\SiteResources;
use gitzw\site\model\Visart;


class ResourcePageControl extends VisartPageControl {
	
	const IMG_WIDTH = 1200;
	const IMG_HEIGHT = 1000;

	private $resource;
	private $nextResource;
	private $previousResource;
	protected $mainRepresentation;
	//private $ida;
	protected $imgData;
	
	function __construct(Visart $visart, array $path) {
		parent::__construct($visart);
		$work = $this->visart->getChildByFullNamePath($path[2]);
		$this->resource = SiteResources::get()->getResourceByFullNamePath($path);
		if (is_null($this->resource)) {
			throw new NotFoundException('resource does not exist.');
		}
		$this->nextResource = $this->resource->getParent()->nextPublicResource($this->resource->getId());
		$this->previousResource = $this->resource->getParent()->previousPublicResource($this->resource->getId());
		$this->mainRepresentation = $this->resource->getRepresentation();
		$ida = new ImageData($this->getImagePath());
		$this->imgData = $ida->resize(self::IMG_WIDTH, self::IMG_HEIGHT);
		
		$this->setTitle($visart->getFullName().' - '.$this->resource->getLongId());
		$this->setContentFile(GZ::TEMPLATES.'/views/resource-view.php');
		$this->constructMenu($work, $path);
	}
	
	private function constructMenu(Path $work, array $path) {
		$manager = new MenuManager();
		foreach($work->getChildren() as $cat) {
			$catSelected = $cat->getFullNamePath() == $path[3];
			$item = $manager->addItem($cat->getName(), NULL, $catSelected);
			foreach ($cat->getChildren() as $year) {
				if ($year->getPublicResourceCount() > 0) {
					$selected = $year->getFullNamePath() == $path[4] and $catSelected;
					$item->addSub($year->getFullName(), $year->getResourcePath().'/overview', $selected);
				}
			}
		}
		$manager->addItem($this->visart->getName(), $this->visart->getResourcePath());
		$this->setMenuManager($manager);
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
	
	public function getStructuredData() {
		$imgURL = Site::get()->hostName().$this->imgData['location'];
		$sdResource = $this->resource->getStructuredData($imgURL);
		return [
				"@context"=>"http://schema.org",
				"@graph"=>[$sdResource,
						$this->getWebPageSD()
				]
		];
	}
	
	private function getWebPageSD() {
		$links = [];
		if ($this->hasNext()) $links[] = Site::get()->hostName().$this->nextResource->getResourcePath();
		if ($this->hasPrevious()) $links[] = Site::get()->hostName().$this->previousResource->getResourcePath();
		return [
				"@type"=>"WebPage",
				"@id"=>"https://gitzw.art".$this->resource->getResourcePath(),
				"url"=>"https://gitzw.art".$this->resource->getResourcePath(),
				"mainEntity"=>[
				"@id"=>"https://gitzw.art/".$this->resource->getLongId()
				],
				"relatedLink"=>$links
		];
	}
}

