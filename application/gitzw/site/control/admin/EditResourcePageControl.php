<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\SiteResources;

class EditResourcePageControl extends DefaultPageControl {
	
	protected $resource;
	protected $longId;
	protected $action;
	
	function __construct(array $path) {
		$resourceId = $path[3]; // hnq.work.draw.2020.0002
		if (is_null($resourceId)) {
			echo '@Todo: find some way to collect a resource id.';
		}
		
		$this->setContentFile(GZ::TEMPLATES.'/admin/edit-resource.php');
		$this->setMenuManager(new AdminMenuManager());
		$this->addStylesheet('/css/form.css');
		
		$group = SiteResources::getSite()->getChildByName('var');
		$this->resource = $group->getResource($resourceId);
		if (is_null($this->resource)) {
			throw new NotFoundException('Unknown resource id: '.$resourceId);
		}
		$this->longId = $this->resource->getLongId();
		$this->action = '/'.implode('/', array_slice($path, 1));
	}
}

