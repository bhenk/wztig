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

   
    /**
     * Tests SiteResources::get()
     */
    public function testGetSite() {
        SiteResources::reset();
        $site = SiteResources::getSite();
        assertInstanceOf(SiteResources::class, $site);
        assertEquals('resources', $site->getName());
        assertEquals(1, $site->getMaxOrdinal());
    }
    
    
    public function testGetCannonicalPath() {
        SiteResources::reset();
        $site = SiteResources::getSite();
        assertEquals(['', ''], 
            $site->getCannonicalPath(['', '']), "1");
        
        assertEquals(['', 'foo'],
            $site->getCannonicalPath(['', 'foo']), "2");
        
        assertEquals(['', 'henk-van-den-berg'], 
            $site->getCannonicalPath(['', 'hnq']), "3");
        
        assertEquals(['', 'henk-van-den-berg'], 
            $site->getCannonicalPath(['', 'Henk&van^den#Berg']), "4");
        
        assertEquals(['', 'henk-van-den-berg'], 
            $site->getCannonicalPath(['', 'hnq', 'foo']), "5");
        
        assertEquals(['', 'henk-van-den-berg', 'work'],
            $site->getCannonicalPath(['', 'hnq', 'Work']), "6");
        
        assertEquals(['', 'henk-van-den-berg', 'work'],
            $site->getCannonicalPath(['', 'hnq', 'WORK', 'foo']), "7");
        
        assertEquals(['', 'henk-van-den-berg', 'work', 'drawing'],
            $site->getCannonicalPath(['', 'hnq', 'WORK', 'draw']), "8");
        
        assertEquals(['', 'henk-van-den-berg', 'work', 'drawing'],
            $site->getCannonicalPath(['', 'hnq', 'WORK', 'draw', 'bar']), "9");
        
        assertEquals(['', 'henk-van-den-berg', 'work', 'drawing'],
            $site->getCannonicalPath(['', 'hnq', 'WORK', 'draw', 'foo', 'BAR', 'baZ']), "10");
    }

}

