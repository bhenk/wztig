<?php
namespace gitzw\test\model;

use PHPUnit\Framework\TestCase;
use gitzw\site\model\SiteResources;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertEquals;

require_once __DIR__.'/../../GZ.php';



/**
 * SiteResources test case.
 */
class SiteResourcesTest extends TestCase {
	
	protected $site;

	protected function setUp() : void {
		SiteResources::reset();
		$this->site = SiteResources::get();
	}
    
    
    public function testGetCannonicalPath1() {
        assertEquals(['', ''], 
        	$this->site->getCannonicalPath(['', '']), "1");
    }
        
    public function testGetCannonicalPath2() {
        assertEquals(['', 'foo'],
        	$this->site->getCannonicalPath(['', 'foo']), "2");
    }
    
    public function testGetCannonicalPath3() {
        assertEquals(['', 'henk-van-den-berg'], 
        	$this->site->getCannonicalPath(['', 'hnq']), "3");
	}
   
	public function testGetCannonicalPath4() {
        assertEquals(['', 'henk-van-den-berg'], 
        	$this->site->getCannonicalPath(['', 'Henk&van^den#Berg']), "4");
	}
    
	public function testGetCannonicalPath5() {
        assertEquals(['', 'henk-van-den-berg'], 
        	$this->site->getCannonicalPath(['', 'hnq', 'ding']), "5");
	}
    
	public function testGetCannonicalPath6() {
        assertEquals(['', 'henk-van-den-berg', 'work'],
        	$this->site->getCannonicalPath(['', 'hnq', 'Work']), "6");
	}
    
	public function testGetCannonicalPath7() {
        assertEquals(['', 'henk-van-den-berg', 'work'],
        	$this->site->getCannonicalPath(['', 'hnq', 'WORK', 'foo']), "7");
	}
    
	public function testGetCannonicalPath8() {
        assertEquals(['', 'henk-van-den-berg', 'work', 'drawing'],
        	$this->site->getCannonicalPath(['', 'hnq', 'WORK', 'draw']), "8");
	}
    
	public function testGetCannonicalPath9() {
        assertEquals(['', 'henk-van-den-berg', 'work', 'drawing'],
        	$this->site->getCannonicalPath(['', 'hnq', 'WORK', 'draw', 'bar']), "9");
	}
    
	public function testGetCannonicalPath10() {
        assertEquals(['', 'henk-van-den-berg', 'work', 'drawing'],
        	$this->site->getCannonicalPath(['', 'hnq', 'WORK', 'draw', 'foo', 'BAR', 'baZ']), "10");
    }

}

