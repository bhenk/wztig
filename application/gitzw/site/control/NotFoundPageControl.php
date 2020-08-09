<?php
namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\data\Site;
use gitzw\site\logging\Log;
use Exception;

class NotFoundPageControl extends DefaultPageControl {
    
    private $actualLink;
    private $fileTrace;
    private $exception;
    
    function __construct(Exception $ex = Null) {
    	$this->exception = $ex;
        $this->setContentFile(GZ::TEMPLATES.'/err404.php');
        header("HTTP/1.0 404 Not Found");
        $this->setFooter(FALSE);
        $this->setTitle('404 not found on gitzw.art');
        $this->actualLink = Site::get()->actualLink();
        
        $this->fileTrace = 'no_file_info';
        $backtrace = debug_backtrace();
        if (!empty($backtrace[0]) && is_array($backtrace[0])) {
            $this->fileTrace = $backtrace[0]['file'] . ":" . $backtrace[0]['line'];
        }
        
        $this->addStylesheet('/css/error.css');
        Log::log()->info(__METHOD__);
    }
    
    protected function renderActualLink() {
        echo $this->actualLink;
    }
    
    protected function getMessage() {
    	if (isset($this->exception)) {
    		echo 'message: '.$this->exception->getMessage();
    		if (GZ::SHOW_TRACE) {
    			echo "<br/><br/><b>".get_class($this->exception)."</b><br/>";
    			echo "<br/>source : ".$this->exception->getFile().' ('.$this->exception->getLine().')'
;    			echo "<br/>stacktrace: <br/>";
    			echo str_replace("\n", "<br/>", $this->exception->getTraceAsString());
    		}
    	}
    }
    
    protected function renderFileTrace() {
        if (GZ::SHOW_TRACE) {
            echo 'called by: ' . $this->fileTrace;
        }
    }
}