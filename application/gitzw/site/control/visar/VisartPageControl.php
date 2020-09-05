<?php

namespace gitzw\site\control\visar;

use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\model\SiteResources;
use gitzw\site\model\Visart;

class VisartPageControl extends DefaultPageControl {
	
	const IMG_WIDTH = 1200;
	const IMG_HEIGHT = 1000;
	
	protected $visart;
	
	function __construct(?Visart $visart=null, array $path=[]) {
		if (is_null($visart)) {
			if (isset($path[1])) {
				$visart = SiteResources::get()->getByPathSegment($path[1], 1);
			}
		}
		$this->visart = $visart;
		$this->setPath($path);
	}
	
	protected function constructMenu(bool $includeVarHome=true) {
		$work = $this->visart->getChildByName('work');
		$manager = new MenuManager();
		foreach($work->getChildren() as $cat) {
			$catSelected = $cat->getFullNamePath() == $this->path[3];
			$item = $manager->addItem($cat->getName(), NULL, $catSelected);
			foreach ($cat->getChildren() as $year) {
				if ($year->getPublicResourceCount() > 0) {
					$selected = $year->getFullNamePath() == $this->path[4] and $catSelected;
					$item->addSub($year->getFullName(), $year->getResourcePath().'/overview', $selected);
				}
			}
		}
		if ($includeVarHome) {
			$manager->addItem($this->visart->getName(), $this->visart->getResourcePath());
		}
		$this->setMenuManager($manager);
	}
	
	
	protected function getCopyRight() : string {
		if (isset($this->visart)) {
			$copyRightStart = $this->visart->getCopyrightStart();
			if (isset($copyRightStart)) {
				$copyRightStart .= '&nbsp;-&nbsp;'.date('Y');
			}
			return '&#169;'.$copyRightStart.'&nbsp;'.
					str_replace(' ', '&nbsp;', $this->visart->getFullName()).' &nbsp;&bull;';
		} else {
			return '&#169;gitzw.art &nbsp;&bull;';
		}
	}
}


