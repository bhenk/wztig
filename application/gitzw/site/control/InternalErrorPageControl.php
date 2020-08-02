<?php
namespace gitzw\site\control;

use gitzw\GZ;
use Exception;
use gitzw\site\logging\Log;

class InternalErrorPageControl extends DefaultPageControl {
    
    private $actualLink;
    private $exception;
    
    function __construct(Exception $e) {
        $this->setContentFile(GZ::TEMPLATES.'/err500.php');
        $this->exception = $e;
        header("HTTP/1.0 500 Internal Serever Error");
        $this->setFooter(FALSE);
        $this->setTitle('500 Internal Server Error');
        if (isset($_SERVER['REQUEST_URI'])) {
            $this->actualLink = (isset($_SERVER['HTTPS']) &&
                $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
                "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        } else {
            $this->actualLink = '';
        }
        
        $this->addStylesheet('/css/error.min.css');
        Log::log()->info(__METHOD__);
    }
    
    protected function renderActualLink() {
        echo $this->actualLink;
    }
    
    protected function renderTrace() {
        if (GZ::SHOW_TRACE) {
            echo self::printException($this->exception);
        }
    }
    
    private static function printException(Exception $e) {
        return str_replace("\n", "<br/>\n", $e->__toString());
    }
}

