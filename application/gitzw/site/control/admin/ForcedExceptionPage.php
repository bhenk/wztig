<?php
namespace gitzw\site\control\admin;

use gitzw\site\control\DefaultPageControl;
use gitzw\site\logging\Log;
use Exception;

class ForcedExceptionPage extends DefaultPageControl {
    
    function __construct() {
        Log::log()->info(__METHOD__);
    }
    
    
    protected function renderFooter() {
        Log::log()->error('Going to raise a forced Exception');
        throw new Exception('virtual exception');
    }
}

