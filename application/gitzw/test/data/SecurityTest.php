<?php
namespace gitzw\test\data;

use PHPUnit\Framework\TestCase;
use gitzw\site\data\Security;
use function PHPUnit\Framework\assertSame;

require_once __DIR__.'/../../GZ.php';

class SecurityTest extends TestCase {
    
    public function testLoad() {
        $sec1 = Security::get();
        //echo PHP_EOL;
        //echo json_encode($sec1, JSON_PRETTY_PRINT);
        $sec2 = Security::get();
        assertSame($sec1, $sec2);
    }
}

