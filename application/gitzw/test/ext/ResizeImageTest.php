<?php 
namespace gitzw\test\ext;


// use PHPUnit\Framework\TestCase;
// use gitzwart\GZ;
// use gitzwart\site\ext\ResizeImage;
// use function PHPUnit\Framework\assertEquals;

// require_once __DIR__.'/../../GZ.php';

// class ResizeImageTest extends TestCase {
    
//     private $imgFile;
//     private $imgFile2;
//     private $dumpDir = GZ::ROOT.'/test-dump';
    
//     protected function setUp() : void {
//         $this->imgFile = GZ::publicDir().'/img/hnq/_DSC0533_00022.jpg';
//         //$this->$imgFile2 = GZ::publicDir().'/img/hnq/_DSC0429_00006.jpg';
//         $this->markTestSkipped('practicle test');
//     }
    
//     public function testGetSize() {
//         $ri = new ResizeImage($this->imgFile);
//         $wh = $ri->getSize();
//         assertEquals(3586, $wh['w']);
//         assertEquals(4721, $wh['h']);
//     }
    
//     // neemt de grootste van orig.w of orig.h en past die aan
//     public function testResizeToDefault() {
//         $imgDump = $this->dumpDir.'/default2.jpg';
//         if (file_exists($imgDump)) {
//             unlink($imgDump);
//         }
//         $ri = new ResizeImage($this->imgFile2);
//         $ri->resizeTo(1200, 1000);
//         $ri->saveImage($imgDump);
//     }
    
//     // vervormt tot exact.
//     public function testResizeToExact() {
//         $imgDump = $this->dumpDir.'/exact.jpg';
//         if (file_exists($imgDump)) {
//             unlink($imgDump);
//         }
//         $ri = new ResizeImage($this->imgFile);
//         $ri->resizeTo(100, 300, 'exact');
//         $ri->saveImage($imgDump);
//     }
    
//     public function testResizeToMaxwidth() {
//         $imgDump = $this->dumpDir.'/maxwidth.jpg';
//         if (file_exists($imgDump)) {
//             unlink($imgDump);
//         }
//         $ri = new ResizeImage($this->imgFile);
//         $ri->resizeTo(100, 300, 'maxwidth');
//         $ri->saveImage($imgDump);
//     }
    
//     public function testResizeToMaxheight() {
//         $imgDump = $this->dumpDir.'/maxheight.jpg';
//         if (file_exists($imgDump)) {
//             unlink($imgDump);
//         }
//         $ri = new ResizeImage($this->imgFile);
//         $ri->resizeTo(0, 300, 'maxheight');
//         $ri->saveImage($imgDump);
//     }
    
// }

