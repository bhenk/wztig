<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\logging\Log;
use gitzw\site\model\Path;
use gitzw\site\model\Visart;
use gitzw\site\model\ResourceContainer;

class OverviewPageControl extends VisartPageControl {
	
	const ITEMS_PER_PAGE = 40;
    
    private array $path;
    private ResourceContainer $year;
    private int $start;
    
    function __construct(Visart $visart, array $path) {
    	parent::__construct($visart);
        $this->path = $path;
        $work = $this->visart->getChildByFullNamePath($path[2]);
        $cat = $work->getChildByFullNamePath($path[3]);
        $this->year = $cat->getChildByFullNamePath($path[4]);
        // path[5] = 'overview'
        $this->start = max(0, intval($path[6]));
        $this->setTitle($this->visart->getFullName().' '.$cat->getFullName().
            ' '.$this->year->getFullName());
        $this->setContentFile(GZ::TEMPLATES.'/visar/overview2.php');
        $this->constructMenu($work);
        Log::log()->info(__METHOD__);
    }
    
    private function constructMenu(Path $work) {
    	$manager = new MenuManager();
    	foreach($work->getChildren() as $cat) {
    		$catSelected = $cat->getFullNamePath() == $this->path[3];
    		$item = $manager->addItem($cat->getName(), NULL, $catSelected);
    		foreach ($cat->getChildren() as $year) {
    			$selected = $year->getFullNamePath() == $this->path[4] and $catSelected;
    			$item->addSub($year->getFullName(), $year->getResourcePath().'/overview', $selected);
    		}
    	}
		$manager->addItem($this->visart->getName(), $this->visart->getResourcePath());
    	$this->setMenuManager($manager);
    }
    
    protected function getLeftArrowLink() {
    	$page = max(0, $this->start - self::ITEMS_PER_PAGE);
    	return $this->year->getResourcePath().'/overview/'.$page;
    }
    
    protected function getLeftArrowStyle() {
    	if ($this->start <= 0) {
    		return ' style="visibility: hidden;"';
    	} else {
    		return '';
    	}
    }
    
    protected function getRightArrowLink() {
    	$page = $this->start + self::ITEMS_PER_PAGE;
    	return $this->year->getResourcePath().'/overview/'.$page;
    }
    
    protected function getRightArrowStyle() {
    	if (($this->start + self::ITEMS_PER_PAGE) >= $this->year->getPublicResourceCount()) {
    		return ' style="visibility: hidden;"';
    	} else {
    		return '';
    	}
    }
    
    protected function getHeading() {
    	return $this->year->getIdPath();
    }
    
    
    protected function getPageResources() {
    	if ($this->start >= $this->year->getPublicResourceCount()) {
    		return FALSE;
    	} else {
    		return array_slice($this->year->getPublicResourcesShuffled(), $this->start, self::ITEMS_PER_PAGE);
    	}
    }
    
    
}

