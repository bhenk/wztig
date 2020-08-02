<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\logging\Log;
use gitzw\site\model\Path;

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
        $this->addStylesheet('/css/nav-menu.css');
        Log::log()->info(__METHOD__);
    }
    
    protected function renderNavigation() {
        (new MenuManager($this->segment, $this->path))->renderMenu();
        $this->addScript(GZ::TEMPLATES . '/frame/nav-menu.min.js');
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

