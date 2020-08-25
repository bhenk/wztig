<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\control\menu\Pager;
use gitzw\site\data\Site;
use gitzw\site\logging\Log;
use gitzw\site\model\Path;
use gitzw\site\model\ResourceContainer;
use gitzw\site\model\Visart;
use gitzw\site\data\Security;

class OverviewPageControl extends VisartPageControl {
	
	const ITEMS_PER_PAGE = 15;
    
    //private array $path;
    private ResourceContainer $year;
    private int $start;
    private ?string $seedString;
    private int $itemsPerPage;
    private array $resources;
    private Pager $pager;
    
    protected $state = 'normal';
    
    function __construct(Visart $visart, array $path) {
    	parent::__construct($visart);
        $this->setPath($path);
        $work = $this->visart->getChildByFullNamePath($path[2]);
        $cat = $work->getChildByFullNamePath($path[3]);
        $this->year = $cat->getChildByFullNamePath($path[4]);
        // path[5] = 'overview'
        $this->seedString = $path[6];
        $this->start = max(0, intval($path[7]));
        $this->itemsPerPage = max(1, intval($path[8] ?? self::ITEMS_PER_PAGE));
		
        if ($this->seedString == 'adm' and Security::get()->hasAccess()) {
        	$this->resources = $this->year->getResourcesOrdered();
        	$this->state = 'adm';
        } elseif ($this->seedString == 'chrono') {
        	$this->resources = $this->year->getPublicResources();
        } else {
	        $pak = $this->year->getPubResourcesRandomized($this->seedString);
	        $this->seedString = $pak[0];
	        $this->resources = $pak[1];
        }
        
        $canStart = floor($this->start / self::ITEMS_PER_PAGE) * self::ITEMS_PER_PAGE;
        if ($this->seedString != 'chrono' or $this->start != $canStart) {
        	$this->setCanonicalURI(Site::get()->
        			redirectLocation($this->year->getResourcePath().'/overview/chrono/'.$canStart));
        }
        
        $this->setTitle($this->visart->getFullName().' '.$cat->getFullName().
            ' '.$this->year->getFullName());
        $this->setContentFile(GZ::TEMPLATES.'/visar/overview2.php');
        $this->constructMenu($work);
        $this->pager = new Pager($this->start, $this->itemsPerPage, $this->getItemCount(), $this->getLink(), $this->seedString);
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
    
    public function renderPage() {
    	if (Site::get()->requestMethod() == 'POST' and Security::get()->hasAccess()) {
    		$this->handlePost();
    		return;
    	}
    	parent::renderPage();
    }
    
    private function handlePost() {
    	$input = json_decode(file_get_contents('php://input'), true);
    	$data = Security::cleanInput($input);
    	$shortid = $data['shortid'];
    	$ordinal = intval($data['ordinal']);
    	foreach (array_values($this->resources) as $resource) {
    		if ($resource->getId() == $shortid) {
    			$resource->setOrdinal($ordinal);
    			$resource->getParent()->persist();
    			Log::log()->info('Updated resource ordinal. id='.$resource->getLongId(). ', value='.$ordinal);
    		}
    	}
    }
    
    private function getLink() : string {
    	return $this->year->getResourcePath().'/overview';
    }
    
    private function getItemCount() {
    	return count($this->resources);
    }
    
    protected function getPager() {
    	return $this->pager;
    }
    
    protected function getHeading() {
    	return $this->year->getIdPath();
    }
    
    
    protected function getPageResources() {
    	if ($this->start >= $this->getItemCount()) {
    		return FALSE;
    	} else {
    		return array_slice($this->resources, $this->start, $this->itemsPerPage);
    	}
    }
    
}

