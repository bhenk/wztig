<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\logging\Log;
use gitzw\site\model\Path;
use gitzw\site\model\Visart;

class OverviewPageControl extends VisartPageControl {
	
	const ITEMS_PER_PAGE = 2;
    
    private array $path;
    private Path $year;
    protected int $start;
    
    function __construct(Visart $visart, array $path) {
    	parent::__construct($visart);
        $this->path = $path;
        $work = $this->visart->getChildByFullNamePath($path[2]);
        $cat = $work->getChildByFullNamePath($path[3]);
        $this->year = $cat->getChildByFullNamePath($path[4]);
        // path[5] = 'overview'
        $this->start = intval($path[6]);
        $this->setTitle($this->visart->getFullName().' '.$cat->getFullName().
            ' '.$this->year->getFullName());
        $this->setContentFile(GZ::TEMPLATES.'/visar/overview.php');
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
    
    
    protected function getPageResources() {
    	if ($this->start >= count($this->year->getResources())) {
    		return FALSE;
    	} else {
    		return array_slice($this->year->getResources(), $this->start, self::ITEMS_PER_PAGE);
    	}
    }
    
    
}

