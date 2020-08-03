<?php
namespace gitzw\site\handle;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\NotFoundPageControl;
use gitzw\site\control\admin\AdminMenuManager;
use gitzw\site\logging\Log;

class AdminHandler {
    
    private $path;
    
    function __construct(array $path) {
        $this->path = $path;
        Log::log()->info(__METHOD__);
    }
    
    // /admin/*
    public function handleRequest() {
    	if (empty($this->path[2])) {
    		$control = new DefaultPageControl(GZ::TEMPLATES.'/admin/admin.php');
    		$control->setMenuManager(new AdminMenuManager('/admin'));
    		$control->renderPage();
    		Log::log()->info('end request handling admin');
    		return;
    	}
    	switch ($this->path[2]) {
    		case 'server':
    			$control = new DefaultPageControl(GZ::TEMPLATES.'/admin/server.php');
    			$control->setMenuManager(new AdminMenuManager('/admin/server'));
    			$control->renderPage();
    			Log::log()->info('end request handling admin/server');
    			return;
    		case 'resources':
    			$control = new DefaultPageControl(GZ::TEMPLATES.'/admin/resources.php');
    			$control->setMenuManager(new AdminMenuManager('/admin/resources'));
    			$control->addStylesheet('/css/js/json-viewer.css');
    			$control->addScriptLink('/js/json-viewer.js');
    			$control->renderPage();
    			Log::log()->info('end request handling admin/resources');
    			return;
    	}
    	(new NotFoundPageControl())->renderPage();
    	Log::log()->info('end request handling '.NotFoundPageControl::class);
    }
}

