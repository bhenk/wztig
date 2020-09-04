<?php namespace gitzw\test\handle;

// use PHPUnit\Framework\TestCase;
// use gitzwart\site\logging\Log;
// use gitzwart\site\handle\Gitz;
// use gitzwart\site\control\ForcedExceptionPage;
// use gitzwart\site\control\InternalErrorPageControl;
// use gitzwart\site\control\NotFoundPageControl;
// use gitzwart\site\control\HomePageControl;
// use function PHPUnit\Framework\assertTrue;

// require_once __DIR__.'/../../GZ.php';

// /**
//  * Gitz test case.
//  */
// class GitzTest extends TestCase {

//     /**
//      *
//      * @var Gitz
//      */
//     private $gitz;

//     /**
//      * Prepares the environment before running a test.
//      */
//     protected function setUp() : void {
//         parent::setUp();
//         $this->gitz = new Gitz();
//     }

//     /**
//      * Cleans up the environment after running a test.
//      */
//     protected function tearDown() : void {
//         $this->gitz = NULL;
//         parent::tearDown();
//     }

//     public function testHomepage() {
//         $this->handleControlFlow(array('', ''), HomePageControl::class);
//     }
    
//     public function testNotFoundPage() {
//         $this->handleControlFlow(array('', 'not-a-resource'), NotFoundPageControl::class);
//     }
    
//     public function testInternalErrorPage() {
//         $this->setOutputCallback(function() {});
//         $path = array('', 'raise-Exception');
//         $this->handleRequest($path, array(
//             self::startRequest($path),
//             self::construct(ForcedExceptionPage::class),
//             self::renderPage(ForcedExceptionPage::class),
//             'Raising forced exception',
//             self::construct(InternalErrorPageControl::class),
//             self::renderPage(InternalErrorPageControl::class),
//             self::endPage(InternalErrorPageControl::class),
//             self::endRequest(InternalErrorPageControl::class)
//         ), TRUE);
//     }
    
//     public function testRedirectFoo() {
//         $path = ['', 'hnq', 'Foo'];
//         $this->handleRequest($path, array(
//             self::startRequest($path),
//             'redirecting to http://no_host/henk-van-den-berg/Foo'
//         ));
//     }
    
//     public function testRedirectFooBar() {
//         $path = ['', 'hnq', 'Work', 'DRAW', 'Foo', 'Bar'];
//         $this->handleRequest($path, array(
//             self::startRequest($path),
//             'redirecting to http://no_host/henk-van-den-berg/work/drawing/Foo/Bar'
//         ));
//     }
    
//     /**
//      * Test a direct Gitz->Control flow request.
//      * 
//      * @param array $path the resource path
//      * @param string $class the control class
//      */
//     private function handleControlFlow(array $path, string $class) {
//         $this->handleRequest($path, array(
//             self::startRequest($path),
//             self::construct($class),
//             self::renderPage($class),
//             self::endPage($class),
//             self::endRequest($class)
//         ));
//     }
    
//     /**
//      * Test the flow of a request, given a resource path and the expected log output.
//      * 
//      * The boolean parameter expectErrors instructs whether the log output may not or must
//      * contain the string "log.ERROR:".
//      * 
//      * @param array $path the resource path
//      * @param array $expected the expected log output statements in expected order
//      * @param bool $expectErrors are errors expected, default FALSE
//      */
//     private function handleRequest(array $path, array $expected, bool $expectErrors=FALSE) {
//         $fp = fopen('php://memory', 'rw');
//         Log::reset($fp, 0, TRUE);
        
//         $this->gitz->handleRequest($path);
        
//         rewind($fp);
//         $output = stream_get_contents($fp);
//         Log::reset();
//         //Log::reset('php://stdout', 0); Log::log()->debug($output);
        
//         if ($expectErrors) {
//             $this->assertStringContainsString('log.ERROR:', $output, 'no log.ERROR: in output');
//         } else {
//             $this->assertStringNotContainsString('log.ERROR:', $output, 'log.ERROR: in output');
//         }
//         foreach($expected as $crumb) {
//             $pos = strpos($output, $crumb);
//             if ($pos === false) {
//                 $this->fail('missing "'.$crumb.'"');
//             }
//             $output = substr($output, $pos + strlen($crumb));
//         }
//     }
    
//     private static function startRequest(array $path) {
//         // start request handling ["","raise-Exception"]
//         return 'start request handling ["'.implode('","', $path).'"]';
//     }
    
//     private static function construct(string $class) {
//         // gitzwart\site\control\ForcedExceptionPage::__construct
//         return $class.'::__construct';
//     }
    
//     private static function renderPage(string $class) {
//         // gitzwart\site\control\ForcedExceptionPage->gitzwart\site\control\DefaultPageControl::renderPage
//         return $class.'->gitzwart\site\control\DefaultPageControl::renderPage';
//     }
    
//     private static function endPage(string $class) {
//         // gitzwart\site\control\InternalErrorPageControl->gitzwart\site\control\DefaultPageControl::endPage
//         return $class.'->gitzwart\site\control\DefaultPageControl::endPage';
//     }
    
//     private static function endRequest(string $class) {
//         // end request handling gitzwart\site\control\InternalErrorPageControl
//         return 'end request handling '.$class;
//     }

// }

