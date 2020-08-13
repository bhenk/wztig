<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\data\Site;
use gitzw\site\logging\Log;
use gitzw\site\model\Path;
use gitzw\site\model\ResourceContainer;
use gitzw\site\model\Visart;

class OverviewPageControl extends VisartPageControl {
	
	const ITEMS_PER_PAGE = 10;
    
    private array $path;
    private ResourceContainer $year;
    private int $start;
    private ?string $seedString;
    private array $randomizedResources;
    
    function __construct(Visart $visart, array $path) {
    	parent::__construct($visart);
        $this->path = $path;
        $work = $this->visart->getChildByFullNamePath($path[2]);
        $cat = $work->getChildByFullNamePath($path[3]);
        $this->year = $cat->getChildByFullNamePath($path[4]);
        // path[5] = 'overview'
        $this->seedString = $path[6];
        $this->start = max(0, intval($path[7]));
        
        $pak = $this->year->getPubResourcesRandomized($this->seedString);
        $this->seedString = $pak[0];
        $this->randomizedResources = $pak[1];
        
        $this->setTitle($this->visart->getFullName().' '.$cat->getFullName().
            ' '.$this->year->getFullName());
        if (isset($path[6])) {
        	$this->setCanonicalURI(Site::get()->
        			redirectLocation($this->year->getResourcePath().'/overview'));
        }
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
    
    private function getLink($startItem) {
    	return $this->year->getResourcePath().'/overview/'.$this->seedString.'/'.$startItem;
    }
    
    protected function getLeftArrowLink() {
    	$startItem = max(0, $this->start - self::ITEMS_PER_PAGE);
    	return $this->getLink($startItem);
    }
    
    protected function getLeftArrowStyle() {
    	if ($this->start <= 0) {
    		return ' style="visibility: hidden;"';
    	} else {
    		return '';
    	}
    }
    
    protected function getRightArrowLink() {
    	$startItem = $this->start + self::ITEMS_PER_PAGE;
    	return $this->getLink($startItem);
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
    		return array_slice($this->randomizedResources, $this->start, self::ITEMS_PER_PAGE);
    	}
    }
    
    protected function getPagelinks() : array {
    	if ($this->year->getPublicResourceCount() <= self::ITEMS_PER_PAGE) {
    		return [];
    	}
    	
    	$links = array();
    	$pageCount = ceil($this->year->getPublicResourceCount() / self::ITEMS_PER_PAGE);
    	
    	$startItem = 0;
    	$link = [1,
    			$this->getLink($startItem),
    			$this->start == $startItem
    	];
    	$links[] = $link;
    	
    	if ($pageCount == 1) {
    		return $links;
    	}
    	
    	$page = ceil($this->start / self::ITEMS_PER_PAGE) + 1;
    	
    	$begin = max(2, $page - 2);
    	$end = min($pageCount, $page + 3);
    	
    	if ($begin > 2) {
    		$links[] = ['...', '#', FALSE];
    	}
    	
    	for ($i = $begin; $i < $end; $i++) {
    		$startItem = ($i - 1) * self::ITEMS_PER_PAGE;
    		$link = [$i,
    				$this->getLink($startItem),
    				$this->start == $startItem
    		];
    		$links[] = $link;
    	}
    	
    	if ($end < $pageCount) {
    		$links[] = ['...', '#', FALSE];
    	}
    	
    	$startItem = ($pageCount - 1) * self::ITEMS_PER_PAGE;
    	$link = [$pageCount,
    			$this->getLink($startItem),
    			$this->start == $startItem
    	];
    	$links[] = $link;
    	
    	return $links;
    }
    
    
}

