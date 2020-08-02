<?php
namespace gitzw\site\handle;

use gitzw\site\logging\Log;
use gitzw\site\control\admin\AdminPageControl;

class AdminHandler {
    
    private $path;
    
    function __construct(array $path) {
        $this->path = $path;
        Log::log()->info(__METHOD__);
    }
    
    public function handleRequest() {
        if (empty($this->path[2])) {
            (new AdminPageControl())->renderPage();
            return;
        }
    }
}

