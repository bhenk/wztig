<?php
namespace gitzw\site\logging;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\WebProcessor;
use gitzw\GZ;

class Req {

    private static $logger;

    public static function log() : Logger {
        if (is_null(self::$logger)) {
            $output = "%datetime% %extra%\n";
            $handler = new RotatingFileHandler(GZ::LOG_DIRECTORY . '/req.log', 5, Logger::INFO);
            $handler->setFilenameFormat('{filename}-{date}', 'Y-m');
            $formatter = new LineFormatter($output);
            $handler->setFormatter($formatter);
            self::$logger = new Logger('req');
            self::$logger->pushHandler($handler);
            self::$logger->pushProcessor(function ($record) {
                $browser = isset($_SERVER['HTTP_USER_AGENT']) ? 
                    $_SERVER['HTTP_USER_AGENT'] : 'unknown';
                $record['extra']['browser'] = $browser;
                return $record;
            });
            self::$logger->pushProcessor(new WebProcessor());
        }
        return self::$logger;
    }
}

