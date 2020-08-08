<?php
namespace gitzw\site\handle;

use gitzw\GZ;
use gitzw\site\control\HomePageControl;
use gitzw\site\control\InternalErrorPageControl;
use gitzw\site\control\LoginControl;
use gitzw\site\control\NotFoundPageControl;
use gitzw\site\data\Security;
use gitzw\site\data\Site;
use gitzw\site\ext\TinyHtmlMinifier;
use gitzw\site\logging\Log;
use gitzw\site\logging\Req;
use gitzw\site\model\SiteResources;
use Exception;


class Gitz {
    
    public function handleRequestURI() {
    	$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = preg_replace('/[^0-9a-zA-Z\/._]/', '-', urldecode($path));
        $this->handleRequest(explode('/', $path));
    }

    public function handleRequest(array $path) {
        Req::log()->info('');
        Log::log()->info('start request handling', $path);
        if (GZ::MINIFY_HTML) {
            ob_start("self::minifyHtml");
        } else {
            ob_start();
        }
        
        try {
            if (Security::get()->canLogin()) {
                // send session cookie
                session_start();
            }
            //////// simple and obvious cases //////
            switch ($path[1]) {
                case '':
                    (new HomePageControl())->renderPage();
                    Log::log()->info('end request handling '.HomePageControl::class);
                    return;
                case 'favicon.ico':
                    Site::get()->redirect('/img/favicon/favicon-32x32.png');
                    return;
                case 'gendan':
                    echo Site::get()->clientIp();
                    Log::log()->info('end request handling gendan');
                    return;
            }
            
            /////// redirect? //////
            $site = SiteResources::getSite();
            $cannonicalPath = $site->getCannonicalPath($path);
            if (count(array_diff_assoc($path, $cannonicalPath)) > 0) {
                $location = Site::get()->redirect($cannonicalPath);
                Log::log()->info('redirecting to '.$location);
                return;
            }
            
            ///// first segment //////
            $firstSegment = $site->getFirstSegment($path);
            if (isset($firstSegment)) {
                // we have a cannonical path
                switch ($firstSegment->getRequestHandler()) {
                    case 'var':
                        (new VarHandler($path, $firstSegment))->handleRequest();
                        Log::log()->info('end request handling '.VarHandler::class);
                        return;
                }
            }
            
            // Restricted content
            if (Security::get()->canLogin()) {
                switch ($path[1]) {
                    case 'login':
                        (new LoginControl())->renderPage();
                        Log::log()->info('end request handling '.LoginControl::class);
                        return;
                    case 'logout':
                        Security::get()->endSession();
                        Site::get()->redirect('');
                        Log::log()->info('end request handling logout');
                        return;
                }                
            } 
            
            if (Security::get()->hasAccess() and $path[1] == 'admin') {
            	(new AdminHandler($path))->handleRequest();
                return;
            }
            
            (new NotFoundPageControl())->renderPage();
            Log::log()->info('end request handling '.NotFoundPageControl::class);
            
        } catch (Exception $e) {
            self::handleException($e);
        }
    }
    
    static function minifyHtml($buffer) {
        $minifier = new TinyHTMLMinifier();
        return $minifier->minify($buffer);
    }
    
    private static function handleException(?Exception $e) {
        Log::log()->error('Catch all', array('exception' => $e));
        ob_end_clean();
        (new InternalErrorPageControl($e))->renderPage();
        Log::log()->info('end request handling '.InternalErrorPageControl::class);
    }
    
    
}

