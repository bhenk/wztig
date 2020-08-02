<?php
namespace gitzw\site\data;


use gitzw\GZ;
use Exception;
use function PHPUnit\Framework\isInstanceOf;
use function PHPUnit\Framework\stringStartsWith;

class Site {
    
    private static $instance;
    
    public static function reset(Site $instance=NULL) : ?Site {
        $previous = self::$instance;
        self::$instance = $instance;
        return $previous;
    }
    
    public static function get() : Site {
        if (is_null(self::$instance)) {
            self::$instance = new Site();
        }
        return self::$instance;
    }
    
    public function documentRoot() : string {
        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            return $_SERVER['DOCUMENT_ROOT'];
        } elseif (file_exists(GZ::GZ_ROOT.DIRECTORY_SEPARATOR.'public_html')) {
            return GZ::GZ_ROOT.DIRECTORY_SEPARATOR.'public_html';
        } else {
            throw new Exception('Cannot find DOCUMENT_ROOT');
        }
    }
    
    public function hostName() : string {
        if (isset($_SERVER['HTTP_HOST'])) {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            .'://'.$_SERVER['HTTP_HOST'];
        } else {
            return 'http://no_host';
        }
    }
    
    public function requestMethod() : string {
        return isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : '';
    }
    
    public function actualLink() {
        $request = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'unknown';
        return $this->hostName().$request;
    }
    
    public function phpAuthDigest() : string {
        return isset($_SERVER['PHP_AUTH_DIGEST']) ? $_SERVER['PHP_AUTH_DIGEST'] : '';
    }
    
    public function clientIp() : string {
        $ip_address = '0.0.0.0';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from remote address
        elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return  $ip_address;
    }
    
    public function redirectLocation($path) : string {
        if (is_array($path)) {
            $path = implode('/', $path);
        }
        if (substr($path, -1) === '/') {
            $path = substr($path, 0, strlen($path) - 1);
        }
        if (!(substr($path, 0, 1) === '/')) {
            $path = '/'.$path;
        }
        if ($path === '/') {
            $path = '';
        }
        return $this->hostName().$path;
    }
    
    public function redirect($path) : string {
        $location = $this->redirectLocation($path);
        header("Location: ".$location, TRUE, 301);
        return $location;
    }
    
}

