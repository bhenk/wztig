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
    
    public function getVisarts() : array {
    	return $this->getChildByName('var')->getChildren();
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
    
    public function getActivities(string $varName) {
    	if ($varName == 'all') {
    		$acts = array();
    		foreach($this->getChildByName('var')->getChildren() as $visart) {
    			foreach ($visart->getChildren() as $activity) {
    				$acts[] = $activity;
    			}
    		}
    		return $acts;
    	} else {
    		return $this->getDescendant(['var', $varName])->getChildren();
    	}
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
    
    public function getCategories(array $parents) {
    	
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
    
    public function getTree(?array $data, bool $includeAllOption=true) : array {
    	if ($includeAllOption) {
    		$visart = ['all'=>['fullname'=>'all', 'selected'=>$data['visart'] == 'all' ? 'selected' : '']];
    		$activity = ['all'=>['fullname'=>'all', 'selected'=>$data['activity'] == 'all' ? 'selected' : '']];
    		$category = ['all'=>['fullname'=>'all', 'selected'=>$data['category'] == 'all' ? 'selected' : '']];
    		$year = ['all'=>['fullname'=>'all', 'selected'=>$data['year'] == 'all' ? 'selected' : '']];
    	} else {
    		$visart = [];
    		$activity = [];
    		$category = [];
    		$year = [];
    	}
		
    	$followFirst = empty($data);
    	
    	$kids = $this->getDescendantChildren(['var']);
    	if ($followFirst) {
    		$data = [];
    		$first = reset($kids);
    		$data['visart'] = isset($first) ? $first->getName() : '';
    	}
    	foreach ($kids as $c) {
    		$visart[$c->getName()] = ['fullname'=>$c->getFullName(), 'selected'=>$data['visart'] == $c->getName() ? 'selected' : ''];
    	}
    	
    	$kids = $this->getDescendantChildren(['var', $data['visart']]);
    	if ($followFirst) {
    		$first = reset($kids);
    		$data['activity'] = isset($first) ? $first->getName() : '';
    	}
    	foreach ($kids as $c) {
    		$activity[$c->getName()] = ['fullname'=>$c->getFullName(), 'selected'=>$data['activity'] == $c->getName() ? 'selected' : ''];
    	}
    	
    	$kids = $this->getDescendantChildren(['var', $data['visart'], $data['activity']]);
    	if ($followFirst) {
    		$first = reset($kids);
    		$data['category'] = isset($first) ? $first->getName() : '';
    	}
    	foreach ($kids as $c) {
    		$category[$c->getName()] = ['fullname'=>$c->getFullName(), 'selected'=>$data['category'] == $c->getName() ? 'selected' : ''];
    	}
    	
    	$kids = $this->getDescendantChildren(['var', $data['visart'], $data['activity'], $data['category']]);
    	foreach ($kids as $c) {
    		$year[$c->getName()] = ['fullname'=>$c->getFullName(), 'selected'=>$data['year'] == $c->getName() ? 'selected' : ''];
    	}
    	krsort($year, SORT_STRING);
    	
    	$tree = [
    			'visart'=>$visart,
    			'activity'=>$activity,
    			'category'=>$category,
    			'year'=>$year
    	];
    	
    	return $tree;
    }
    
    
}

