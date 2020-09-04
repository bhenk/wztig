<?php
namespace gitzw\test\data;

use PHPUnit\Framework\TestCase;
use gitzw\GZ;
use gitzw\site\data\JsonData;

require_once __DIR__.'/../../GZ.php';

/**
 * Path test case.
 */
class JsonDataTest extends TestCase {

//     public function testLoadFileThatDoesNotExist() {
//         $jsonData = new class() extends JsonData {
            
//             public function getFile() : string {
//                 return GZ::ROOT.DIRECTORY_SEPARATOR.'test-data/foo.json';
//             }
            
//             public function jsonSerialize() {}
        
//         };
//         $this->expectWarning();
//         $jsonData->load();
//     }
    
    public function testLoadFileThatDoesExist() {
        $testFile = GZ::ROOT.DIRECTORY_SEPARATOR.'test-data/resources.json';
        $this->assertTrue(file_exists($testFile),
            'The test file "'.$testFile.'" does not exist');
        $jsonData = new class($testFile) extends JsonData {
            
            private $file;
            
            function __construct(string $file) {
                $this->file = $file;
            }
            
            public function getFile() : string {
                return $this->file;
            }
            
            public function jsonSerialize() {}
        
        };
        $arr = $jsonData->load();
        $this->assertIsArray($arr);
        $this->assertArrayHasKey('name', $arr);
        $this->assertArrayHasKey('full_name', $arr);
        $this->assertArrayHasKey('children', $arr);
        $children = $arr['children'];
        $this->assertEquals('var', $children[0]);
        $this->assertEquals('act', $children[1]);
    }
    
    public function testPersist() {
        $dumpFile = GZ::ROOT.DIRECTORY_SEPARATOR.'test-dump/jsondatatest_file.json';
        if (file_exists($dumpFile)) {
            $this->assertTrue(unlink($dumpFile), 'Could not remove test file "'.$dumpFile.'"');
        }
        
        $arr = array('a'=>'alpha', 'b'=>'beta');
        
        $jsonData = new class($dumpFile, $arr) extends JsonData {
            
            private $file;
            private $array;
            
            function __construct($file, $array) {
                $this->file = $file;
                $this->array = $array;
            }
            public function jsonSerialize() {
                return $this->array;
            }
        
            public function getFile(): string {
                return $this->file;
            } 
            
        };
        
        $bytes = $jsonData->persist();
        $this->assertEquals(37, $bytes);
        $this->assertTrue(file_exists($dumpFile));
    }
}
