<?php

namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\Path;
use gitzw\site\model\Representation;
use gitzw\site\model\Resource;
use gitzw\site\model\SiteResources;
use gitzw\site\model\Visart;


class ResourcePageControl extends VisartPageControl {

	private $resource;
	private $nextResource;
	private $previousResource;
	
	function __construct(Visart $visart, array $path) {
		parent::__construct($visart);
		$work = $this->visart->getChildByFullNamePath($path[2]);
		$this->resource = SiteResources::get()->getResourceByFullNamePath($path);
		if (is_null($this->resource)) {
			throw new NotFoundException('resource does not exist.');
		}
		$this->nextResource = $this->resource->getParent()->nextPublicResource($this->resource->getId());
		$this->previousResource = $this->resource->getParent()->previousPublicResource($this->resource->getId());
		$this->setContentFile(GZ::TEMPLATES.'/views/resource-view.php');
		$this->constructMenu($work, $path);
	}
	
	private function constructMenu(Path $work, array $path) {
		$manager = new MenuManager();
		foreach($work->getChildren() as $cat) {
			$catSelected = $cat->getFullNamePath() == $path[3];
			$item = $manager->addItem($cat->getName(), NULL, $catSelected);
			foreach ($cat->getChildren() as $year) {
				$selected = $year->getFullNamePath() == $path[4] and $catSelected;
				$item->addSub($year->getFullName(), $year->getResourcePath().'/overview', $selected);
			}
		}
		$manager->addItem($this->visart->getName(), $this->visart->getResourcePath());
		$this->setMenuManager($manager);
	}
		
	protected function getResource() : Resource {
		return $this->resource;
	}
	
	protected function getRepresentation() : Representation {
		return $this->getResource()->getRepresentation();
	}
	
	protected function getImagePath() : string {
		return GZ::DATA.'/images/'.$this->getRepresentation()->getLocation();
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
}

