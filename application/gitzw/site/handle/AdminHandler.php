<?php
namespace gitzw\site\handle;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\NotFoundPageControl;
use gitzw\site\control\admin\AdminMenuManager;
use gitzw\site\control\admin\ForcedExceptionPage;
use gitzw\site\logging\Log;
use gitzw\site\control\admin\LocateImagePageControl;

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
    		case 'scan-images':
    			$control = new DefaultPageControl(GZ::TEMPLATES.'/admin/scan-images.php');
    			$control->setMenuManager(new AdminMenuManager('/admin/scan-images'));
    			$control->renderPage();
    			Log::log()->info('end request handling admin/scan-images');
    			return;
    		case 'locate-image':
    			(new LocateImagePageControl($this->path))->renderPage();
    			Log::log()->info('end request handling admin/locate-image');
    			return;
    		case 'edit-image':
    			$control = new DefaultPageControl(GZ::TEMPLATES.'/admin/edit-image.php');
    			$control->setMenuManager(new AdminMenuManager());
    			$control->addStylesheet('/css/form.css');
    			$control->setPath($this->path);
    			$control->renderPage();
    			Log::log()->info('end request handling admin/edit-image');
    			return;
    		case 'raise-exception':
    			(new ForcedExceptionPage())->renderPage();
    			Log::log()->info('end request handling admin/raise-exception');
    			return;
    	}
    	(new NotFoundPageControl())->renderPage();
    	Log::log()->info('end request handling '.NotFoundPageControl::class);
    }
}

