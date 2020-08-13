<?php

namespace gitzw\test\model;

use PHPUnit\Framework\TestCase;
use gitzw\site\model\SiteResources;

require_once __DIR__.'/../../GZ.php';

class SiteResourcesTest2 extends TestCase {
	
	public function getInstance() {
		$site = SiteResources::getSite();
		var_dump(json_encode($site, JSON_PRETTY_PRINT));
	}
	
}

