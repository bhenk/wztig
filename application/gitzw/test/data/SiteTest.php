<?php
namespace gitzw\test\data;

require_once __DIR__.'/../../GZ.php';

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use gitzw\site\data\Site;

class SiteTest extends TestCase {
    
    public function testRedirectLocation() {
        assertEquals('http://no_host', Site::get()->redirectLocation(''), '1');
        assertEquals('http://no_host', Site::get()->redirectLocation(['']), '2');
        assertEquals('http://no_host', Site::get()->redirectLocation(['', '']), '3');
        
        assertEquals('http://no_host/foo', Site::get()->redirectLocation('foo'), '4');
        assertEquals('http://no_host/foo/bar', Site::get()->redirectLocation('foo/bar'), '5');
        assertEquals('http://no_host/foo/bar', Site::get()->redirectLocation('foo/bar/'), '6');
        
        assertEquals('http://no_host', Site::get()->redirectLocation(['', '/']), '7');
        assertEquals('http://no_host/foo/bar', Site::get()->redirectLocation(['', 'foo', 'bar']), '8');
    }
}

