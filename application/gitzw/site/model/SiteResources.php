<?php
namespace gitzw\site\model;

/**
 * A model of the site that corresponds to RESTful path segments.
 */
class SiteResources extends Path {
    
    const RESOURCES_NAME = 'resources';
    
    private static $instance;
    
    /**
     * Get the singleton instance of SiteRecources.
     * Path segments will be loaded up to a depth of 2 (i.e. Visarts).
     * 
     * @return SiteResources
     */
    public static function get() : SiteResources {
    	if (is_null(self::$instance)) {
    		self::$instance = new SiteResources(self::RESOURCES_NAME, 2);
    	}
    	return self::$instance;
    }
    
    /**
     * Reset the singleton instance of SiteResources and force reloading upon next get().
     */
    public static function reset() {
        self::$instance = NULL;
    }
    
    
    private $firstSegment;
    
    /**
     * Get the cannonical path (if any) that points to a site resource corresponding to the given $path.
     * 
     * @param array $path url path to inspect
     * @param boolean $keepRest keep segments of $path that are beyond the cannonical part (default False)
     * @return array cannonical path + [rest] or original path
     */
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
    
    /**
     * Get the path segment (visart) that corresponds to the string in $path[1] or null.
     * 
     * @param array $path the url path
     * @return Path|NULL
     */
    public function getFirstSegment(array $path) : ?Path {
        if (is_null($this->firstSegment)) {
            $this->firstSegment = $this->getByPathSegment($path[1], 1);
            if (isset($this->firstSegment)) {
                $this->firstSegment->loadChildren();
            }
        }
        return $this->firstSegment;   
    }
    
    /**
     * Get all representations of all resources arranged by visart.
     * 
     * @return array [visart.name=>[representations]]
     */
    public function getResourceImages() : array {
    	$arr = array();
    	$vars = $this->getSegment(['var']);
    	foreach ($vars->getChildren() as $var) {
    		$var->loadChildren();
    		$var->loadResources();
    		$stack = array();
    		$var->collectRepresentations($stack);
    		$arr[$var->getName()] = $stack;
    	}
    	return $arr;
    }
    
    public function getVisartNames() : array {
    	$names = array();
    	foreach($this->getChildByName('var')->getChildren() as $visart) {
    		$names[] = $visart->getName();
    	}
    	return array_unique($names, SORT_STRING);
    }
    
    public function getActivitytNames() : array {
    	$names = array();
    	foreach($this->getChildByName('var')->getChildren() as $visart) {
    		foreach ($visart->getChildren() as $activity) {
    			$names[] = $activity->getName();
    		}
    	}
    	return array_unique($names, SORT_STRING);
    }
    
    public function getCategoryNames() : array {
    	$names = array();
    	foreach($this->getChildByName('var')->getChildren() as $visart) {
    		foreach ($visart->getChildren() as $subject) {
    			foreach ($subject->getChildren() as $cat) {
    				$names[] = $cat->getName();
    			}
    		}
    	}
    	return array_unique($names, SORT_STRING);
    }
    
    public function getYearNames() : array {
    	$names = array();
    	foreach($this->getChildByName('var')->getChildren() as $visart) {
    		foreach ($visart->getChildren() as $subject) {
    			foreach ($subject->getChildren() as $cat) {
    				foreach ($cat->getChildren() as $year) {
    					$names[] = $year->getName();
    				}
    			}
    		}
    	}
    	return array_unique($names, SORT_NUMERIC);
    }
    
    public function listResources(array $query, $callback=NULL) {
    	$stack = array();
    	foreach ($this->getChildByName('var')->getChildren() as $child) {
    		$child->collectResources($stack, $query, $callback);
    	}
    	return $stack;
    }
    
    
}

