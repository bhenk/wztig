<?php
namespace gitzw\site\control\visar;

use gitzw\GZ;
use gitzw\site\data\ImageData;
use gitzw\site\data\Site;
use gitzw\site\logging\Log;
use gitzw\site\model\Visart;
use gitzw\site\model\SiteResources;

class FrontPageControl extends VisartPageControl {
    
    private $location;
    
    function __construct(Visart $visart, array $path) {
        parent::__construct($visart);
        $this->setPath($path);
        $this->setTitle($this->visart->getFullName().' @ gitzw.art');
        $this->setContentFile(GZ::TEMPLATES.'/front.php');
        if (count($path) > 2) {
            $this->setCanonicalURI(Site::get()->
                redirectLocation($this->visart->getFullNamePath()));
        }
        $this->constructMenu(false);
        Log::log()->info(__METHOD__);
    }
    
    public function setLocation(string $location) {
    	$this->location = $location;
    }
    
    private function getFrontImage() : ?array {
    	if (isset($this->location)) {
    		return [$this->location, null];
    	} else {
    		$frontImages = $this->visart->getFrontPageImages();
    		if (empty($frontImages)) {
    			return NULL;
    		}
    		$key = array_rand($frontImages);
    		return [$key, $frontImages[$key]];
    	}
    }
    
    protected function renderImage() {
    	$fi = $this->getFrontImage();
    	if ($fi) {
	        $image = $fi[0];
	        $imgFile = GZ::DATA.'/images/'.$image;
	        $ida = new ImageData($imgFile);
	        $resourceLink = false;
	        if (isset($fi[1])) {
		        $rp = array_merge([''], explode('.', $fi[1]));
		        $resourceLink = implode('/', SiteResources::get()->getCannonicalPath($rp, true));
		        if ($resourceLink) {
		        	echo '<a href="'.$resourceLink.'" title="'.$fi[1].'">';
		        }
	        }
	        echo $ida->getImgTag(self::IMG_WIDTH, self::IMG_HEIGHT, 'random image');
	        if ($resourceLink) {
	        	echo '</a>';
	        }
    	}
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

