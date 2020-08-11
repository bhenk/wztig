<?php
namespace gitzw\test\model;

use PHPUnit\Framework\TestCase;
use gitzw\GZ;
use gitzw\site\model\Path;
use Exception;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertStringStartsWith;
use function PHPUnit\Framework\assertStringEndsWith;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertEmpty;

require_once __DIR__.'/../../GZ.php';

/**
 * Path test case.
 */
class PathTest extends TestCase {
    
    
    private $root;
    
    protected function setUp(): void {
        $this->root = $this->getRootPath('resources');
    }
    
    protected function path() : Path {
        return $this->root;
    }
    
    protected function getRootPath(string $name) : Path {
        $root = new class($name) extends Path {
            
            function __construct($name) {
                parent::__construct($name);
            }
            
            public function getDirectory(): string {
                return GZ::ROOT.DIRECTORY_SEPARATOR.'test-data';
            }
            
        };
        return $root;
    }
    
    public function testConstructor() {
        $p = $this->path();
        assertEquals('resources', $p->getName());
        assertEquals('var', $p->getChildByName('var')->getName());
        assertEquals('hnq', $p->getChildByName('var')->getChildByName('hnq')->getName());
    }
    
    public function testConstructorWithWrongName() {
        $this->expectException(Exception::class);
        $this->getRootPath('corrupt-resources');
    }
    
    public function testGetDirectory() {
        $hnq = $this->path()->getChildByName('var')->getChildByName('hnq');
        $hnq_dir = $hnq->getDirectory();
        assertStringStartsWith('/', $hnq_dir, 'Path is not absolute');
        assertStringEndsWith('/test-data/resources/var', $hnq_dir);
    }

    public function testGetFile() {
        $hnq = $this->path()->getChildByName('var')->getChildByName('hnq');
        $hnq_file = $hnq->getFile();
        assertStringStartsWith('/', $hnq_file, 'Path is not absolute');
        assertStringEndsWith('/test-data/resources/var/hnq.json', $hnq_file);
    }
    
    public function testGetFullNamePath() {
        $hnq = $this->path()->getChildByName('var')->getChildByName('hnq');
        assertEquals('henk-van-den-berg', $hnq->getFullNamePath());
    }
    
    public function testGetResourcePath() {
        $hnq = $this->path()->getChildByName('var')->getChildByName('hnq');
        $draw = $hnq->getChildByName('work')->getChildByName('draw');
        assertEquals('/henk-van-den-berg/work/drawing', $draw->getResourcePath());
        
        assertEquals('', $this->path()->getResourcePath());
        assertEquals('', $this->path()->getChildByName('var')->getResourcePath());
        assertEquals('/henk-van-den-berg', $hnq->getResourcePath());
    }
    
//     public function testEchoResourcePaths() {
//         echo PHP_EOL;
//         $this->path()->echoResourcePaths();
        
//         $hnq = $this->path()->getChildByName('var')->getChildByName('hnq');
//         $work = $hnq->getChildByName('work');
//         echo PHP_EOL;
//         $work->echoResourcePaths();
//     }

    public function testGetOrdinal() {
        assertEquals(0, $this->path()->getOrdinal());
        assertEquals(0, $this->path()->getChildByName('var')->getOrdinal());
        assertEquals(1, $this->path()->getChildByName('var')->getChildByName('hnq')->getOrdinal());
        assertEquals(2, $this->path()->getChildByName('var')->getChildByName('hnq')
            ->getChildByName('work')->getOrdinal());
    }
    
    public function testGetMaxOrdinal() {
        assertEquals(4, $this->path()->getMaxOrdinal());
    }
    
    public function testIsNickName() {
        $hnq = $this->path()->getChildByName('var')->getChildByName('hnq');
        assertTrue($hnq->isNickName('hnq'));
        assertTrue($hnq->isNickName('henk-van-den-berg'));
        assertTrue($hnq->isNickName('Henk-van-den-Berg'));
        assertTrue($hnq->isNickName('henk_van*den berg'));
        assertFalse($this->path()->isNickName('resources'));
    }
    
    public function testGetByPathSegment() {
        assertEquals('hnq', $this->path()->getByPathSegment('hnq', 1)->getName());
        assertEquals('hnq', $this->path()->
            getByPathSegment('henk-van-den-berg', 1)->getName());
        assertEquals('hnq', $this->path()->
            getByPathSegment('Henk+van^den&Berg', 1)->getName());
        assertEquals('ahf', $this->path()->getByPathSegment('ahf', 1)->getName());
        assertEquals('ahf', $this->path()->
            getByPathSegment('anna-hilfaren', 1)->getName());
        assertNull($this->path()->getByPathSegment('hmm', 1));
        
        assertEquals('work', $this->path()->getByPathSegment('work', 2)->getName());
        assertEquals('draw', $this->path()->getByPathSegment('drawing', 3)->getName());
        assertNull($this->path()->getByPathSegment('drawing', 2));
        assertNull($this->path()->getByPathSegment('drawing', 4));        
    }
    
    public function getByPathSegmentDownStream() {
        $hnq = $this->path()->getByPathSegment('hnq', 1);
        assertEquals('hnq', $hnq->getName());
        $work = $hnq->getByPathSegment('work', 2);
        assertEquals('work', $work->getName());
        
        $work = $hnq->getByPathSegment('WORK', 2);
        assertEquals('work', $work->getName());
    }
    
    public function testGetRequestHandler() {
        assertNull($this->path()->getRequestHandler());
        assertEquals('var', $this->path()->getChildByName('var')->getRequestHandler(), 'var is wrong');
        assertEquals('var', $this->path()->getChildByName('var')->getChildByName('ahf')
            ->getRequestHandler());
    }
    
//     public function testNeedsRedirect() {
//         $p = $this->path();
//         assertFalse($p->needsRedirect(array('', '')));
//         assertFalse($p->getChildByName('var')->needsRedirect(['', '']));
//         assertFalse($p->getChildByName('var')->getChildByName('hnq')->needsRedirect(['', 'henk-van-den-berg']));
//         assertTrue($p->getChildByName('var')->getChildByName('hnq')->needsRedirect(['', 'henk-van-den+berg']));
//         $work = $p->getChildByName('var')->getChildByName('hnq')->getChildByName('work');
//         assertFalse($work->needsRedirect(['', '', 'work']));
//         assertTrue($work->needsRedirect(['', '', '']));
//         assertTrue($work->needsRedirect(['', '']));
//     }

    public function testGetProps() {
        $p = $this->path();
        $hnq = $p->getChildByName('var')->getChildByName('hnq');
        assertEmpty($p->getProps());
        $imgs = $hnq->getProps()['img_front'];
        assertEquals(4, count($imgs));
    }
    
    public function testPersist() {
        $dumpFile = GZ::ROOT.DIRECTORY_SEPARATOR.'test-dump/pathtest.json';
        $p = $this->path();
        $bytes = file_put_contents($dumpFile, json_encode($p, JSON_PRETTY_PRINT), LOCK_EX);
        assertTrue(file_exists($dumpFile));
        assertTrue($bytes > 0);
    }
    
    
 
}

