<?php
namespace gitzw\site\handle;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\NotFoundPageControl;
use gitzw\site\control\admin\AdminMenuManager;
use gitzw\site\control\admin\EditResourcePageControl;
use gitzw\site\control\admin\ForcedExceptionPage;
use gitzw\site\control\admin\LocateImagePageControl;
use gitzw\site\control\admin\MoveResourcePageControl;
use gitzw\site\control\admin\SitemapGenerator;
use gitzw\site\control\visar\FrontPageControl;
use gitzw\site\logging\Log;
use gitzw\site\model\ImageInspector;
use gitzw\site\model\SiteResources;

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
    		case 'list-images':
    			(new ImageInspector())->listImages();
    			Log::log()->info('end request handling admin/list-images');
    			return;
    		case 'create-sitemap':
    			(new SitemapGenerator())->generateSitemap();
    			Log::log()->info('end request handling admin/create-sitemap');
    			return;
    		case 'check-sitemap':
    			(new SitemapGenerator())->checkSitemap();
    			Log::log()->info('end request handling admin/check-sitemap');
    			return;
    		case 'scan-images':
    			$control = new DefaultPageControl(GZ::TEMPLATES.'/admin/scan-images.php');
    			$control->setTemplate(DefaultPageControl::COLUMN_3);
    			$control->setMenuManager(new AdminMenuManager('/admin/scan-images'));
    			$control->renderPage();
    			Log::log()->info('end request handling admin/scan-images');
    			return;
    		case 'locate-image':
    			(new LocateImagePageControl($this->path))->renderPage();
    			Log::log()->info('end request handling admin/locate-image');
    			return;
    		case 'edit-resource':
    			(new EditResourcePageControl($this->path))->renderPage();
    			Log::log()->info('end request handling admin/edit-resource');
    			return;
    		case 'move-resource':
    			(new MoveResourcePageControl($this->path))->renderPage();
    			Log::log()->info('end request handling admin/move-resource');
    			return;
    		case 'front-page':
    			$var = SiteResources::get()->getChildByName('var')->getChildByName($this->path[3]);
    			$location = str_replace('+', '/', $this->path[4]);
    			$control = new FrontPageControl($var, []);
    			$control->setLocation($location);
    			$control->renderPage();
    			Log::log()->info('end request handling admin/front-page');
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

