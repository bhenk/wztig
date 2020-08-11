<?php
namespace gitzw\site\handle;

use gitzw\site\logging\Log;
use gitzw\site\model\Path;
use gitzw\site\control\visar\FrontPageControl;
use gitzw\site\data\Site;
use gitzw\site\control\visar\OverviewPageControl;

class VarHandler {
    
    private $path;
    private $firstSegment;
    
    function __construct(array $path, Path $firstSegment) {
        $this->path = $path;
        $this->firstSegment = $firstSegment;
        Log::log()->info(__METHOD__);
    }

    public function handleRequest() {
        if (empty($this->path[2])) {
            (new FrontPageControl($this->firstSegment, $this->path))->renderPage();
            return;
        }
        switch ($this->path[2]) {
            case 'work' :
                $this->handleWork();
                return;
            case 'blogs' :
                $this->handleBlogs();
                return;
        }
        // never reached with SiteResources->getCannonicalPath, $keepRest=FALSE
        Site::get()->redirect($this->firstSegment->getFullNamePath());
    }
    
    // handles /var/work....
    public function handleWork() {
        if (empty($this->path[3])) {
            // /var/work
            (new FrontPageControl($this->firstSegment, $this->path))->renderPage();
            return;
        }
        if (empty($this->path[4])) {
            // /var/work/cat
            (new FrontPageControl($this->firstSegment, $this->path))->renderPage();
            return;
        }
        if (empty($this->path[5])) {
        	// var/work/cat/year
        	(new FrontPageControl($this->firstSegment, $this->path))->renderPage();
        	return;
        }
        if ($this->path[5] == 'overview') {
	        // /var/work/cat/year
	        (new OverviewPageControl($this->firstSegment, $this->path))->renderPage();
	        return;
        }
        echo implode('->', $this->path);
    }
    
    public function handleBlogs() {
        // voorlopig:
        Site::get()->redirect($this->firstSegment->getFullNamePath());
    }
}

