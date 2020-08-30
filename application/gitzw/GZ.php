<?php
namespace gitzw;

use Monolog\Logger;
use ErrorException;
/*
 * General configuration.
 */


/* /domains/gitzw.art */
/* eclipse/wztig/application */
/* /var/www */
defined("GZ_ROOT")
    or define("GZ_ROOT", realpath(dirname(__DIR__)));

/*
 * autoload
 */
spl_autoload_register(function($para) {
    $path = GZ_ROOT.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $para).'.php';
    if (file_exists($path)) {
        include $path;
        return TRUE;
    }
});
    
/* /domains/vendor */
/* eclipse/wztig/vendor */
/* /var/vendor */
require_once(dirname(GZ_ROOT).'/vendor/autoload.php');

// catch error_reporting and throw Exceptions instead
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});
        
/**
 * Provides global constants.
 *
 */
abstract class GZ {
	
	const VERSION = 'RC_0.0.4';
	
	const VERSION_DATE = '2020-08-30';
    
    /**
     * Root of the site.
     * 
     * /domains/gitzw.art <br/>
     * eclipse/wztig/application <br/>
     * /var/www 
     */
    const ROOT = GZ_ROOT;
    
    /**
     * The data directory.
     * 
     * /domains/gitzw.art/data <br/>
     * eclipse/wztig/application/data <br/>
     * /var/www/data 
     */
    const DATA = GZ_ROOT.DIRECTORY_SEPARATOR.'data';
    
    /**
     * The log directory.
     *
     * /domains/gitzw.art/logs <br/>
     * eclipse/wztig/application/logs <br/>
     * /var/www/logs
     */
    const LOG_DIRECTORY = GZ_ROOT.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'gitzw';
    
    /**
     * The source directory.
     * 
     * /domains/gitzw.art/gitzw <br/>
     * eclipse/wztig/application/gitzw <br/>
     * /var/www/gitzw 
     */
    const GITZWART = GZ_ROOT.DIRECTORY_SEPARATOR.'gitzw';
    
    /**
     * The templates directory.
     *
     * /domains/gitzw.art/gitzw/templates <br/>
     * eclipse/wztig/application/gitzw/templates <br/>
     * /var/www/gitzw/templates
     */
    const SCRIPTS = self::GITZWART.DIRECTORY_SEPARATOR.'js';
    
    
    /**
     * The templates directory.
     * 
     * /domains/gitzw.art/gitzw/templates <br/>
     * eclipse/wztig/application/gitzw/templates <br/>
     * /var/www/gitzw/templates 
     */
    const TEMPLATES = self::GITZWART.DIRECTORY_SEPARATOR.'templates';
    
    /**
     * Minify html output.
     * 
     * TRUE on production!
     */
    const MINIFY_HTML = TRUE;
    
    /**
     * Show stack traces.
     * 
     * FALSE on production!
     */
    const SHOW_TRACE = TRUE;
    
    /**
     * The log level.
     */
    const LOG_LEVEL = Logger::INFO;
    
    /**
     * Output for logging.
     */
    const LOG_OUTPUT = GZ::LOG_DIRECTORY . '/log.log';
    
}

?>