<?php
namespace gitzw\test\model;

use PHPUnit\Framework\TestCase;
use gitzw\site\model\Resource;
use gitzw\site\model\SiteResources;
use function PHPUnit\Framework\assertEquals;

require_once __DIR__.'/../../GZ.php';

class ResourceTest extends TestCase {

    private $resource;
    
    protected function setUp(): void {
        $root = SiteResources::getSite();
        $hnq = $root->getSegment(['var', 'hnq']);
        $hnq->loadChildren();
        $y2020 = $hnq->getSegment(['work', 'draw', '2020']);
        $y2020->loadResources();
        $this->resource = array_values($y2020->getResources())[0];
    }
    
    private function get() : Resource {
        return $this->resource;
    }
    
    public function testCreate() {
        assertEquals('0001', $this->get()->getId());
    }
    
    public function testGetLongId() {
        assertEquals('hnq.work.draw.2020.0001', $this->get()->getLongId());
    }
}

