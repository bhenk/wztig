<?php
namespace gitzw\site\model;

use JsonSerializable;

/**
 * 
 * <pre>
 * "0001" : {
 *		"title" : {
 *			"en" : "no title",
 *			"nl" : "geen titel"
 *		},
 *		"preferred_title" : "nl",
 *		"technique" : "pencil, charcoal and acrylic on paper",
 *		"width" : 20.5,
 *		"height" : 33,
 *		"depth" : 0,
 *		"date" : "2020-02",
 *		"representations" : {}
 *	}
 * </pre>
 *
 */
class Resource implements iViewRender, JsonSerializable {
    
    const KEY_TITLES = 'titles';
    const KEY_PREFERRED_LANGUAGE = 'preferred_language';
    const KEY_TECHNIQUE = "technique";
    const KEY_WIDTH = 'width';
    const KEY_HEIGHT = 'height';
    const KEY_DEPTH = 'depth';
    const KEY_DATE = 'date';
    const KEY_REPRESENTATIONS = 'representations';
    
    private $id;
    private $parent;
    private $titles = array();
    private $preferredLanguage;
    private $technique;
    private $width;
    private $height;
    private $depth;
    private $date;
    private $representations = array();

	function __construct(string $id, array $data, Path $parent=NULL) {
        $this->id = $id;
        $this->parent = $parent;
        $this->titles = $data[self::KEY_TITLES] ?? array();
        $this->preferredLanguage = $data[self::KEY_PREFERRED_LANGUAGE] ?? 'en';
        $this->technique = $data[self::KEY_TECHNIQUE] ?? '';
        $this->width = $data[self::KEY_WIDTH] ?? -1;
        $this->height = $data[self::KEY_HEIGHT] ?? -1;
        $this->depth = $data[self::KEY_DEPTH] ?? -1;
        $this->date = $data[self::KEY_DATE] ?? '';
        $reparr = $data[self::KEY_REPRESENTATIONS] ?? array();
        foreach ($reparr as $key=>$data) {
        	$this->representations[$key] = new Representation($this, $key, $data);
        }
    }
    
    public function jsonSerialize() {
        return [self::KEY_TITLES=>$this->titles,
            self::KEY_PREFERRED_LANGUAGE=>$this->preferredLanguage,
            self::KEY_TECHNIQUE=>$this->technique,
            self::KEY_WIDTH=>$this->width,
            self::KEY_HEIGHT=>$this->height,
            self::KEY_DEPTH=>$this->depth,
            self::KEY_DATE=>$this->date,
            self::KEY_REPRESENTATIONS=>$this->representations,
        ];
    }
    
    public function getLongId() : string {
        return $this->parent->getIdPath().'.'.$this->id;
    }
    
    public function getTitles() : array {
    	return $this->titles;
    }
    
    public function setTitle(string $value, string $language) {
    	$this->titles[$language] = $value;
    }
    
    public function getPreferredLanguage() : string {
    	return $this->preferredLanguage;
    }
    
    public function setPreferredLanguage(string $preferredLanguage) {
    	$this->preferredLanguage = $preferredLanguage;
    }

    public function getTechnique() : string {
        return $this->technique;
    }

    public function setTechnique(string $technique) {
        $this->technique = $technique;
    }

    public function getWidth() : ?float {
    	return $this->width < 0 ? NULL : $this->width;
    }

    public function setWidth(float $width) {
        $this->width = $width;
    }

    public function getHeight() : ?float {
    	return $this->height < 0 ? NULL : $this->height;
    }

    public function setHeight(float $height) {
        $this->height = $height;
    }

    public function getDepth() : ?float {
    	return $this->depth < 0 ? NULL : $this->depth;
    }

    public function setDepth(float $depth) {
        $this->depth = $depth;
    }

    public function getDate() : string {
        return $this->date;
    }

    public function setDate(string $date) {
        $this->date = $date;
    }
    
    public function getRepresentations(string $orderBy=NULL) : array {
    	if (is_null($orderBy)) {
    		return $this->representations;
    	} else if ($orderBy == 'ordinal') {
    		$vals = array_values($this->representations);
    		usort($vals, function($a, $b) {
    			return $a->getOrdinal() <=> $b->getOrdinal();
    		});
    		return $vals;
    	}
    }
    
    public function addRepresentation(string $location) {
    	$this->representations[$location] = new Representation($this, $location);
    	$this->parent->persist();
    }
    
    public function removeRepresentation(string $location) {
    	unset($this->representations[$location]);
    }
    
    public function getRepresentation() : ?Representation {
    	foreach (array_values($this->representations) as $representation) {
    		if ($representation->getPreferred() == TRUE) {
    			return $representation;
    		}
    	}
    	return array_values($this->representations)[0];
    }

    public function getId() : string {
        return $this->id;
    }

    public function getParent() : ?Path {
        return $this->parent;
    }

    public function render($template) {
        require($template);
    }
    
    

}

