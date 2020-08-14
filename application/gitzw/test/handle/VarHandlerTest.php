<?php 
namespace gitzw\test\handle;

use PHPUnit\Framework\TestCase;
use gitzw\site\handle\VarHandler;
use gitzw\site\model\SiteResources;
use function PHPUnit\Framework\assertEquals;

require_once __DIR__.'/../../GZ.php';

/**
 * VarHandler test case.
 */
class VarHandlerTest extends TestCase {

    private function getHandler(array $path) : VarHandler {
        $site = SiteResources::get();
        $segment1 = urldecode($path[1]);
        $resourcePath = $site->getByPathSegment($segment1, 1);
        return new VarHandler($path, $resourcePath);
    }
    
    public function testHandleRequest() {
        
    }
}

