<?php
namespace gitzw\site\logging;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use gitzw\GZ;

class Log {
    
    private static $logger;
    private static $logOutput;
    private static $logLevel;
    private static $stream = FALSE;
    private static $maxLogFiles = 5;
    private static $maxErrFiles = 10;
    
    public static function log() : Logger {
        if (is_null(self::$logger)) {
            if (self::$stream) {
                $handler = new StreamHandler(self::getLogOutput(), self::getLogLevel());
            } else {
                $handler = new RotatingFileHandler(self::getLogOutput(), 
                    self::$maxLogFiles, self::getLogLevel());
            }
            
            $errorHandler = new RotatingFileHandler(GZ::LOG_DIRECTORY.'/err.log', 
                self::$maxErrFiles, Logger::ERROR);
            $formatter = new LineFormatter();
            $formatter->includeStacktraces(TRUE);
            $errorHandler->setFormatter($formatter);
            
            self::$logger = new Logger('log');
            self::$logger->pushHandler($handler);
            self::$logger->pushHandler($errorHandler);
            self::$logger->pushProcessor(new IntrospectionProcessor());
        }
        return self::$logger;
    }
    
    public static function setLog($output, $logLevel) {
        self::$logOutput = $output;
        self::$logLevel = $logLevel;
    }
    
    public static function reset($output=NULL, $logLevel=NULL, $stream=FALSE) {
        self::$logger = NULL;
        self::setLog($output, $logLevel);
        self::setStreaming($stream);
    }
    
    public static function setStreaming(bool $stream) {
        self::$stream = $stream;
    }
    
    private static function getLogOutput() {
        return isset(self::$logOutput) ? self::$logOutput : GZ::LOG_OUTPUT;
    }
    
    private static function getLogLevel() {
        return isset(self::$logLevel) ? self::$logLevel : GZ::LOG_LEVEL;
    }
}

