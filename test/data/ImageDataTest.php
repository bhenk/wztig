<?php
namespace gitzw\test\data;

use PHPUnit\Framework\TestCase;
use gitzw\GZ;
use gitzw\site\data\ImageData;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertEquals;
use gitzw\site\data\Site;

require_once __DIR__.'/../../GZ.php';

class ImageDataTest extends TestCase {
    
    const testImg = GZ::DATA.'/images/hnq/2020/_DSC0429_00006.jpg';
    
    private $id;
    
    protected function setUp() : void {
        Site::reset(new class() extends Site {
            public function documentRoot() : string {
                return GZ::ROOT.DIRECTORY_SEPARATOR.'test-data/public_html';
            }
        });
        $this->id = new ImageData(self::testImg);
    }
    
    protected function tearDown() : void {
        Site::reset();
    }
    
    public function testGetMediaType() {
        assertEquals('image/jpeg', $this->id->getMediaType());
    }
    
    public function testGetSize() {
        $size = $this->id->getSize();
        assertTrue($size['height'] > 0);
        assertTrue($size['width'] > 0);
    }
    
    public function testGetFileName() {
        $expected = GZ::ROOT.'/test-data/public_html/img/derived/hnq/2020/_DSC0429_00006_f.jpg';
        //echo "\n".$expected;
        //echo "\n".$this->id->getFileName('_f');
        assertEquals($expected, $this->id->getFilename('_f'));
    }
    
    public function testGetImgLocation() {
        $expected = '/img/derived/hnq/2020/_DSC0429_00006_f.jpg';
        assertEquals($expected, $this->id->getImgLocation('_f'));
    }
    
    public function testResizeDefault() {
        $expected = GZ::ROOT.DIRECTORY_SEPARATOR.
        	'test-data/public_html/img/derived/hnq/2020/_DSC0429_00006_1200_1000_d.jpg';
        if (file_exists($expected)) {
            unlink($expected);
        }
        $data = $this->id->resize(1200, 1000);
      
        assertEquals($expected, $data['filename']);
        assertEquals('/img/derived/hnq/2020/_DSC0429_00006_1200_1000_d.jpg', $data['location']);
        assertEquals(1200, $data['size'][0]);
        assertEquals(615, $data['size'][1]);
        assertEquals('width="1200" height="615"', $data['size'][3]);
        assertEquals('image/jpeg', $data['size']['mime']);
    }
    
    public function testGetImgTag() {
        $tag = $this->id->getImgTag(1200, 1000, 'test');
        $expected = '<img src="/img/derived/hnq/2020/_DSC0429_00006_1200_1000_d.jpg" alt="test">';
        assertEquals($expected, $tag);
    }
}

