<?php
namespace gitzw\site\model;

use gitzw\GZ;
use gitzw\site\data\JsonData;
use gitzw\site\logging\Log;
use Exception;

/**
 * A representation of a (segment of a) resource path. With this representation a resource path 
 * is mimicked in a corresponding directory structure on the file system. Json files in this
 * directory structure hold information on the correponding segment. <code>Path</code> is the 
 * instantiation of such a json file. A <code>Path</code> instance holds therefore information on
 * a node in a resource path and can provide information to and influence the behaviour of
 * the application.
 * 
 * @author ecco
 *
 */
class Path extends JsonData implements iViewRender {
    
    /**
     * Cache for instantiated paths.
     * @var array
     */
    private static $PATHS = array();
    
    /**
     * Get a root path by name.
     * 
     * @param string $name the name of a root segment.
     * 
     * @return Path the root path of the given name and all its children. An empty structure if name
     *  is not a root path.
     */
    public static function get(?string $name) : Path {
        if (!array_key_exists($name, self::$PATHS)) {
            $path = new Path($name);
            self::$PATHS[$name] = $path;
        }
        return self::$PATHS[$name];
    }
    
    const KEY_NAME = 'name';
    const KEY_FULL_NAME = 'full_name'; 
    const KEY_NATURE = 'nature';
    const KEY_CHILDREN = 'children' ;
    const KEY_PATH_SEGMENT = 'path_segment';
    const KEY_REQUESTHANDLER = 'request_handler';
    const KEY_PROPS = 'props';
    const KEY_RESOURCES = 'resources';
    
    private $parent;
    private $name;
    private $fullName;
    private $nature;
    private $children = array();
    private $pathSegment = TRUE;
    private $requestHandler;
    private $props = array();
    private $resources = array();
    private $childrenLoaded = FALSE;
    private $resourcesLoaded = FALSE;
    
    /**
     * Construct a path. Recursively walks the directory structure. The depth of the structure that
     * will be loaded can be controlled with the <code>$depth</code> parameter. 
     * 
     * @param string $name the name of the path segment
     * @param int $depth how many children must be loaded
     * @param Path $parent the parent of this path segment
     */
    function __construct(string $name, int $depth=512, Path $parent=NULL) {
        $this->name = $name;
        $this->parent = $parent;
        $arr = $this->load();
        if ($this->name != $arr[self::KEY_NAME]) {
            $msg = 'name inconsistency: my name="'.$this->name.
            '" name in file="'.$arr[self::KEY_NAME].
            '" file="'.$this->getFile().'"';
            Log::log()->error($msg);
            throw new \Exception($msg);
        }
        $this->fullName = $arr[self::KEY_FULL_NAME];
        $this->nature = $arr[self::KEY_NATURE] ?? NULL;
        $this->pathSegment = $arr[self::KEY_PATH_SEGMENT] ?? TRUE;
        $this->requestHandler = $arr[self::KEY_REQUESTHANDLER] ?? NULL;
        $this->props = $arr[self::KEY_PROPS] ?? array();
        if ($depth > 0 and array_key_exists(self::KEY_CHILDREN, $arr)) {
            foreach ($arr[self::KEY_CHILDREN] as $childName) {
                $this->children[$childName] = new Path($childName, --$depth, $this);
            }
        }
    }
    
    /**
     * Load all of the children of this path segment and propagate this command along the line of its descendants.
     */
    public function loadChildren() {
    	if (!$this->childrenLoaded) {
	        $arr = $this->load();
	        if (array_key_exists(self::KEY_CHILDREN, $arr)) {
	            foreach ($arr[self::KEY_CHILDREN] as $childName) {
	                $this->children[$childName] = new Path($childName, 512, $this);
	            }
	        }
	        $this->childrenLoaded = TRUE;
    	}
    }
    
	/**
	 * Load all of the resources of this path segment and propagate this command along the line of its descendants.
	 * 
	 */
    public function loadResources() {
    	if (!$this->resourcesLoaded) {
	        $arr = $this->load();
	        if (array_key_exists(self::KEY_RESOURCES, $arr)) {
	            foreach ($arr[self::KEY_RESOURCES] as $id=>$data) {
	                $this->resources[$id] = new Resource($id, $data, $this);
	            }
	        }
	        foreach (array_values($this->children) as $child) {
	            $child->loadResources();
	        }
	        $this->resourcesLoaded = TRUE;
    	}
    }
    
    /**
     * Get the nature of this path segment.
     * 
     * @return string|NULL
     */
    public function getNature() : ?string {
        return $this->nature;
    }
    
    public function getProps() : array {
        return $this->props;
    }
    
    public function getResources() : array {
        return $this->resources;
    }
    
    public function addResource() : Resource {
    	$resourceId = $this->getNextResourceId();
    	$resource = new Resource($resourceId, [], $this);
    	$this->resources[$resourceId] = $resource;
    	$this->persist();
    	return $resource;
    }
    
    public function getNextResourceId() : string {
    	$this->loadResources();
    	$rid = -1;
    	foreach (array_keys($this->resources) as $key) {
    		$rid = max($rid, intval($key));
    	}
    	return sprintf("%'.04d", $rid + 1);
    }
    
    public function getIdPath() : string {
    	if (is_null($this->parent)) {
    		return '';
    	} elseif (!$this->pathSegment) {
    		return $this->parent->getIdPath();
    	}
    	$parentId = $this->parent->getIdPath();
    	if ($parentId == '') {
    		return $this->name;
    	} else {
    		return $parentId.'.'.$this->name;
    	}
    }
    
    /**
     * Get the absolute path on the filesystem of the physical directory where this path segment
     * description (the json representation) is stored. This is the parent of the directory that is
     * described by this path segment.
     * 
     * @return string absolute path on the filesystem of the physical directory
     */
    public function getDirectory() : string {
        if (is_null($this->parent)) {
            return GZ::DATA;
        } else {
            return $this->parent->getDirectory().DIRECTORY_SEPARATOR.$this->parent->name;
        }
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \gitzw\site\data\JsonData::getFile()
     */
    public function getFile() : string {
        return $this->getDirectory().DIRECTORY_SEPARATOR.$this->name.'.json';
    }
    
    /**
     * Get an array(childname=>Child) of the children of this Path.
     * 
     * @return array
     */
    public function getChildren() : array {
    	$this->loadChildren();
        return $this->children;
    }
    
    /**
     * Get the name of this path.
     * 
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }
    
    /**
     * Is this path a segement of a resource path.
     * 
     * @return bool
     */
    public function isPathSegment() : bool {
        return $this->pathSegment;
    }
    
    /**
     * Get the full name of this path.
     * 
     * @return string
     */
    public function getFullName() : string {
        return $this->fullName;
    }
    
    /**
     * Get the full name of this path qualified as url path segment.
     * 
     * @return string
     */
    public function getFullNamePath() : string {
        return str_replace('?', 'x', str_replace(' ', '-', $this->fullName));
    }
    
    /**
     * Get the url path up to and including this path segment.
     * 
     * @return string
     */
    public function getResourcePath() : string {
        if (is_null($this->parent)) {
            return '';
        } elseif (!$this->pathSegment) {
            return $this->parent->getResourcePath();
        }  
        $parentPath = $this->parent->getResourcePath();
        if (substr($parentPath, -1) == '/') {
            return $parentPath.$this->getFullNamePath();
        } else {
            return $parentPath.'/'.$this->getFullNamePath();
        }
    }
    
    
    public function getSegment(array $names) {
    	$this->loadChildren();
        $name = array_shift($names);
        if (is_null($name)) {
            return $this;
        } else {
            $child = $this->children[$name];
            if (is_null($child)) {
                throw new Exception($this->name.' has no child '.$name);
            }
            return $this->children[$name]->getSegment($names);
        }
    }
    
    
    /**
     * Get the position of this path segment in the normal resource url.
     * 
     * @return int position of this path segment in the url
     */
    public function getOrdinal() : int {
        if (is_null($this->parent)) {
            return 0;
        } elseif (!$this->pathSegment) {
            return $this->parent->getOrdinal();
        } else {
            return $this->parent->getOrdinal() + 1;
        }
    }
    
    
    public function getMaxOrdinal() : int {
    	$this->loadChildren();
        $max = $this->getOrdinal();
        foreach ($this->children as $child) {
            $max = max($child->getMaxOrdinal(), $max);
        }
        return $max;
    }
    
    /**
     * Get a child by name.
     * 
     * @param string $name
     * @return Path|NULL
     */
    public function getChildByName(string $name) : ?Path {
    	$this->loadChildren();
        return $this->children[$name];
    }
    
    /**
     * Get a child by full name path i.e. url segment.
     * 
     * @param string $fullNamePath
     * @return Path|NULL
     */
    public function getChildByFullNamePath(string $fullNamePath) : ?Path {
    	$this->loadChildren();
        foreach ($this->children as $child) {
            if ($child->getFullNamePath() == $fullNamePath) {
                return $child;
            }
        }
        return NULL;
    }
    
    /**
     * Get child by position on the child list.
     * 
     * @param int $pos
     * @return Path|NULL
     */
    public function getChildByPosition(int $pos=0) : ?Path {
    	$this->loadChildren();
        return array_values($this->children)[$pos];
    }
    
    /**
     * Get this path segment or a descendant of this path segment allong the line of $segmentNames.
     * Will return null if descendant is not in line.
     * Will return $this if $segmentNames is an empty array.
     * 
     * @param array $segmentNames
     * @return Path|NULL
     */
    public function getDescendant(array $segmentNames) : ?Path {
    	$this->loadChildren();
    	$this->loadResources();
    	if (count($segmentNames) == 0) {
    		return $this;
    	}
    	$next = array_shift($segmentNames);
    	$child = $this->getChildByName($next);
    	if (isset($child)) {
    		return $child->getDescendant($segmentNames);
    	} else {
    		return NULL;
    	}
    }
    
    public function getResource(string $resourceId) : ?Resource {
    	$rex = explode('.', $resourceId);
    	$segmentNames = array_slice($rex, 0, -1);
    	$rid = array_slice($rex, -1, 1)[0];
    	$path = $this->getDescendant($segmentNames);
    	if (isset($path)) {
    		return $path->getResourceByShortId($rid);
    	} else {
    		return NULL;
    	}
    }
    
    public function getResourceByShortId(string $rid) {
    	$this->loadResources();
    	return $this->resources[$rid];
    }
    
    /**
     * Is the given $segment a nickname for this path segment.
     * 
     * @param string $segment resource path segment under test 
     * @return bool TRUE if $segment is a nickname, FALSE otherwise
     */
    public function isNickName(string $segment) : bool {
        if ($this->isPathSegment()) {
            switch (strtolower(preg_replace('/[^0-9a-zA-Z]/', ' ', $segment))) {
                case $this->name:
                case $this->getFullName():
                    return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * Get a path in the lineage of this path that is a resource path segment at position $ordinal 
     * and listens to the (nick)name $segment.
     * 
     * @param string $segment resource path segment
     * @param int $ordinal position in the resource path
     * @return Path|NULL
     */
    public function getByPathSegment(string $segment, int $ordinal) : ?Path {
    	$this->loadChildren();
        foreach ($this->children as $child) {
            if ($child->getOrdinal() == $ordinal and $child->isNickName($segment)) {
                return $child;
            }
            if ($this->getOrdinal() < $ordinal) {
                $cc = $child->getByPathSegment($segment, $ordinal);
                if (isset($cc)) {
                    return $cc;
                }
            }
        }
        return NULL;
    }
    
    /**
     * Get the short name of the requestHandler for this resource path segment.
     * 
     * @return string|NULL
     */
    public function getRequestHandler() : ?string {
        if (isset($this->requestHandler)) {
            return $this->requestHandler;
        } elseif (is_null($this->parent)) {
            return null;
        } else {
            return $this->parent->getRequestHandler();
        }
    }
    
    /**
     * Collect all representations of all resources of this path segment and its descendants.
     * 
     * @param array $stack 
     */
    public function collectRepresentations(array &$stack) {
    	$this->loadChildren();
    	$this->loadResources();
    	foreach ($this->resources as $resource) {
    		foreach($resource->getRepresentations() as $rep) {
    			$stack[] = $rep;
    		}
    	}
    	foreach ($this->children as $child) {
    		$child->collectRepresentations($stack);
    	}
    }
    
    public function echoResourcePaths() {
        $resourcePath = $this->getResourcePath();
        if ($resourcePath != '') {
            echo $resourcePath.PHP_EOL;
        }
        foreach($this->children as $child) {
            $child->echoResourcePaths();
        }
    }
    
    public function jsonSerialize() {
        return [self::KEY_NAME=>$this->name,
            self::KEY_FULL_NAME=>$this->fullName,
            self::KEY_NATURE=>$this->nature,
            self::KEY_CHILDREN=>$this->children,
            self::KEY_PATH_SEGMENT=>$this->pathSegment,
            self::KEY_REQUESTHANDLER=>$this->requestHandler,
            self::KEY_PROPS=>$this->props,
            self::KEY_RESOURCES=>$this->resources
        ];       
    }
    
    public function render($template) {
        require($template);
    }
}

