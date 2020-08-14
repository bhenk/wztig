<?php

namespace gitzw\test\model;

require_once __DIR__.'/../../GZ.php';

use PHPUnit\Framework\TestCase;
use gitzw\site\model\SiteResources;
use gitzw\site\model\Visart;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;

class VisartTest extends TestCase {
	
	public function testAddRemoveFrontPageImage() {
		$hnq = SiteResources::get()->getDescendant(['var', 'hnq']);
		$hnq->addFrontPageImage('foo');
		assertTrue(in_array('foo', $hnq->getProps()[Visart::PROP_KEY_IMAGE_FRONT]));
		$hnq->removeFrontPageImage('foo');
		assertFalse(in_array('foo', $hnq->getProps()[Visart::PROP_KEY_IMAGE_FRONT]));
	}
}

