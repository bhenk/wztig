<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\logging\Log;
use gitzw\site\model\Path;
use gitzw\site\data\ImageData;
use gitzw\site\data\Site;
use gitzw\site\control\menu\MenuManager;

class FrontPageControl extends DefaultPageControl {
    
    const IMG_WIDTH = 1200;
    const IMG_HEIGHT = 1000;
    const PROP_IMG_FRONT = 'img_front';
    
    private Path $segment;
    private array $path;
    
    function __construct(Path $segment, array $path) {
        $this->segment = $segment;
        $this->path = $path;
        $this->setTitle($this->segment->getFullName());
        $this->setContentFile(GZ::TEMPLATES.'/front.php');
        if (count($this->path) > 2) {
            $this->setCanonicalURI(Site::get()->
                redirectLocation($this->segment->getFullNamePath()));
        }
        $this->constructMenu();
        Log::log()->info(__METHOD__);
    }
    
    private function constructMenu() {
    	$work = $this->segment->getChildByName('work');
    	$manager = new MenuManager();
    	foreach($work->getChildren() as $cat) {
    		$item = $manager->addItem($cat->getName());
    		foreach ($cat->getChildren() as $year) {
    			$item->addSub($year->getFullName(), $year->getResourcePath());
    		}
    	}
    	$this->setMenuManager($manager);
    }
    
    protected function renderImage() {
        $frontImages = $this->segment->getProps()[self::PROP_IMG_FRONT];
        if (empty($frontImages)) {
            return;
        }
        $image = $frontImages[rand(0, count($frontImages) -1)];
        $imgFile = GZ::DATA.'/images/'.$image;
        $id = new ImageData($imgFile);
        echo $id->getImgTag(self::IMG_WIDTH, self::IMG_HEIGHT, 'random image');
    }
    
    protected function renderNameBlock() {
        $this->segment->render(GZ::TEMPLATES . '/views/simpel_domain_view.php');
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

