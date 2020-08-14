<?php

namespace gitzw\site\control\admin;

use gitzw\site\control\DefaultPageControl;
use gitzw\GZ;

class ResourceFilterPageControl extends DefaultPageControl {
	
	protected string $action;
	
	function __construct(array $path) {
		$this->setContentFile(GZ::TEMPLATES.'/admin/resource-filter-form.php');
		$this->setMenuManager(new AdminMenuManager());
		$this->addStylesheet('/css/form.css');
		$this->action = '/'.implode('/', array_slice($path, 1));
	}
}

