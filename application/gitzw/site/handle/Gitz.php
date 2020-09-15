<?php
namespace gitzw\site\handle;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\HomePageControl;
use gitzw\site\control\InternalErrorPageControl;
use gitzw\site\control\LoginControl;
use gitzw\site\control\NotFoundPageControl;
use gitzw\site\control\SearchPageControl;
use gitzw\site\control\ZoomControl;
use gitzw\site\data\Security;
use gitzw\site\data\Site;
use gitzw\site\ext\TinyHtmlMinifier;
use gitzw\site\logging\Log;
use gitzw\site\logging\Req;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\SiteResources;
use Exception;


class Gitz {
	
	private static $instance;
	
	public static function get() : Gitz {
		if (is_null(self::$instance)) {
			self::$instance = new Gitz();
		}
		return self::$instance;
	}
	
	private $structuredData;
	
	private function __construct() {}
    
    public function handleRequestURI() {
    	$path = preg_replace('/[^0-9a-zA-Z\/._ +]/', '-', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->handleRequest(explode('/', $path));
    }

    public function handleRequest(array $path) {
        Req::log()->info('');
        Log::log()->info('======================== start request handling', $path);
        if (GZ::MINIFY_HTML) {
            ob_start([$this, 'sanitize_output']);
        } else {
            ob_start([$this, 'insertStructuredData']);
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
                    Site::get()->redirect('/img/favicon/favicon.ico');
                    return;
                case 'search':
                	(new SearchPageControl($path))->renderPage();
                	Log::log()->info('end request handling '.SearchPageControl::class);
                	return;
                case 'zoom':
                	(new ZoomControl($path))->renderPage();
                	Log::log()->info('end request handling '.ZoomControl::class);
                	return;
                case 'exif-data':
                	$control = new DefaultPageControl(GZ::TEMPLATES.'/frame/exif-data.php');
                	$control->setTemplate(DefaultPageControl::COLUMN_3);
                	$control->setTitle('exif data');
                	$control->renderPage();
                	Log::log()->info('end request handling /exif-data');
                	return;
                case 'gendan':
                    echo Site::get()->clientIp();
                    Log::log()->info('end request handling gendan');
                    return;
                case 'sitemap':
                	require_once GZ::TEMPLATES.'/test/sitemap.php';
                	return;
            }
            
            /////// redirect? //////
            $site = SiteResources::get();
            $cannonicalPath = $site->getCannonicalPath($path, TRUE);
            if ($cannonicalPath and count(array_diff_assoc($path, $cannonicalPath)) > 0) {
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
            
        } catch (NotFoundException $e) {
        	(new NotFoundPageControl($e))->renderPage();
            return;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    
    private function handleException(?Exception $e) {
        Log::log()->error('Catch all', array('exception' => $e));
        ob_end_clean();
        (new InternalErrorPageControl($e))->renderPage();
        Log::log()->info('end request handling '.InternalErrorPageControl::class);
    }
    
    private function sanitize_output($buffer) {
    	
    	$search = array(
    			'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
    			'/[^\S ]+\</s',     // strip whitespaces before tags, except space
    			'/(\s)+/s',         // shorten multiple whitespace sequences
    			'/<!--(.|\s)*?-->/' // Remove HTML comments
    	);
    	
    	$replace = array(
    			'>',
    			'<',
    			'\\1',
    			''
    	);
    	
    	$buffer = preg_replace($search, $replace, $buffer);
    	
    	return $this->insertStructuredData($buffer);
    }
    
    private function insertStructuredData($buffer) {
    	if (isset($this->structuredData)) {
    		$pos = strpos($buffer, '</head>');
    		$json = "\n".'<script type="application/ld+json">'."\n";
			$json .= $this->structuredData;
			$json .= "\n".'</script>'."\n";
    		$buffer = substr_replace($buffer, $json, $pos, 0);
    	}
    	return $buffer;
    }
    
    public function setStructuredData($data) {
    	$this->structuredData = $data;
    }
    
    
    /**
     * Not handling scripts properly.
     * 
     * @param string $buffer
     * @return string
     */
    static function minifyHtml(string $buffer) : string {
    	$minifier = new TinyHTMLMinifier();
    	return $minifier->minify($buffer);
    }
    
    
    
}

