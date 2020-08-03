<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\logging\Log;
use gitzw\site\model\Path;
use gitzw\site\control\menu\MenuManager;

class OverviewPageControl extends DefaultPageControl {
    
    private Path $segment;
    private array $path;    
    
    function __construct(Path $segment, array $path) {
        // /var/work/cat/year
        $this->segment = $segment;
        $this->path = $path;
        $work = $this->segment->getChildByFullNamePath($path[2]);
        $cat = $work->getChildByFullNamePath($path[3]);
        $year = $cat->getChildByFullNamePath($path[4]);
        $this->setTitle($this->segment->getFullName().' '.$cat->getFullName().
            ' '.$year->getFullName());
        $this->setContentFile(GZ::TEMPLATES.'/overview.php');
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
    			$item->addSub($year->getFullName(), $year->getResourcePath(), $selected);
    		}
    	}
		$manager->addItem($this->segment->getName(), $this->segment->getResourcePath());
    	$this->setMenuManager($manager);
    }
    
    protected function getCopyRight() : string {
        $copyRightStart = $this->segment->getProps()['copyright_start'];
        if (isset($copyRightStart)) {
            $copyRightStart .= '&nbsp;-&nbsp;'.date('Y');
        }
        return '&#169;'.$copyRightStart.'&nbsp;'.
            str_replace(' ', '&nbsp;', $this->segment->getFullName()).' &nbsp;&bull;';
    }
}

