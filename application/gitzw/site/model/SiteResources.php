<?php
namespace gitzw\site\model;


class SiteResources extends Path {
    
    const RESOURCES_NAME = 'resources';
    
    private static $instance;
    
    public static function getSite() : SiteResources {
        if (is_null(self::$instance)) {
            self::$instance = new SiteResources(self::RESOURCES_NAME, 2);
        }
        return self::$instance;
    }
    
    public static function reset() {
        self::$instance = NULL;
    }
    
    
    private $firstSegment;
    
    
    public function getCannonicalPath(array $path, $keepRest=FALSE) : array {
        $current = $this->getFirstSegment($path);
        if (isset($current)) {
            $cp = array('');
            array_push($cp, $current->getFullNamePath());
            for ($i = 2; $i < count($path); $i++) {               
                $current = $current->getByPathSegment($path[$i], $i);
                if (is_null($current)) {
                    if ($keepRest) {
                        return array_merge($cp, array_slice($path, $i));
                    } else {
                        return $cp;
                    }
                }
                array_push($cp, $current->getFullNamePath());
            }
            return $cp;
        }
        return $path;
    }
    
    public function getFirstSegment(array $path) : ?Path {
        if (is_null($this->firstSegment)) {
            $this->firstSegment = $this->getByPathSegment($path[1], 1);
            if (isset($this->firstSegment)) {
                $this->firstSegment->loadChildren();
            }
        }
        return $this->firstSegment;   
    }
    
    public function getResourceImages() : array {
    	$arr = array();
    	$vars = SiteResources::getSite()->getSegment(['var']);
    	foreach ($vars->getChildren() as $var) {
    		$var->loadChildren();
    		$var->loadResources();
    		$stack = array();
    		$var->collectRepresentations($stack);
    		$arr[$var->getName()] = $stack;
    	}
    	return $arr;
    }
    
    
}

