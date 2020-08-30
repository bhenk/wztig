<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\control\menu\MenuManager;
use gitzw\site\data\ImageData;
use gitzw\site\data\Site;
use gitzw\site\logging\Log;
use gitzw\site\model\Visart;

class FrontPageControl extends VisartPageControl {
    
    const IMG_WIDTH = 1200;
    const IMG_HEIGHT = 1000;
    

    private $location;
    
    function __construct(Visart $visart, array $path) {
        parent::__construct($visart);
        $this->setTitle($this->visart->getFullName());
        $this->setContentFile(GZ::TEMPLATES.'/front.php');
        if (count($path) > 2) {
            $this->setCanonicalURI(Site::get()->
                redirectLocation($this->visart->getFullNamePath()));
        }
        $this->constructMenu();
        Log::log()->info(__METHOD__);
    }
    
    private function constructMenu() {
    	$work = $this->visart->getChildByName('work');
    	$manager = new MenuManager();
    	foreach($work->getChildren() as $cat) {
    		$item = $manager->addItem($cat->getName());
    		foreach ($cat->getChildren() as $year) {
    			$item->addSub($year->getFullName(), $year->getResourcePath().'/overview');
    		}
    	}
    	$this->setMenuManager($manager);
    }
    
    public function setLocation(string $location) {
    	$this->location = $location;
    }
    
    private function getLocation() : ?string {
    	if (isset($this->location)) {
    		return $this->location;
    	} else {
    		$frontImages = $this->visart->getFrontPageImages();
    		if (empty($frontImages)) {
    			return NULL;
    		}
    		return $frontImages[rand(0, count($frontImages) -1)];
    	}
    }
    
    protected function renderImage() {
        $image = $this->getLocation();
        if (is_null($image)) {
        	return;
        }
        $imgFile = GZ::DATA.'/images/'.$image;
        $id = new ImageData($imgFile);
        echo $id->getImgTag(self::IMG_WIDTH, self::IMG_HEIGHT, 'random image');
    }
    
    protected function renderNameBlock() {
        $this->visart->render(GZ::TEMPLATES . '/views/simpel_domain_view.php');
    }
    
    public function getStructuredData() {
    	return [
    			"@context"=>"http://schema.org",
    			"@graph"=>[$this->visart->getStructuredData()]
    	];
    }
    
}

