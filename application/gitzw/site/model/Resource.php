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
 *		"representations" : [
 *			"/img/hnq/2020/_DSC123.jpg",
 *			"http://somewhere.com/pics/abc372"
 *		],
 *      "preferred_representation" : 0
 *	}
 * </pre>
 *
 */
class Resource implements iViewRender, JsonSerializable {
    
    const KEY_TITLE = 'title';
    const KEY_PREFERRED_TITLE = 'preferred_title';
    const KEY_TECHNIQUE = "technique";
    const KEY_WIDTH = 'width';
    const KEY_HEIGHT = 'height';
    const KEY_DEPTH = 'depth';
    const KEY_DATE = 'date';
    const KEY_REPRESENTATIONS = 'representations';
    const KEY_PREFERRED_REPRESENTATION = 'preferred representation';
    
    private $id;
    private $parent;
    private $title = array();
    private $preferred_title;
    private $technique;
    private $width;
    private $height;
    private $depth;
    private $date;
    private $representations = array();
    private $preferredRepresentation;
    
    function __construct(string $id, array $data, Path $parent=NULL) {
        $this->id = $id;
        $this->parent = $parent;
        $this->title = $data[self::KEY_TITLE] ?? array();
        $this->preferred_title = $data[self::KEY_PREFERRED_TITLE] ?? 'en';
        $this->technique = $data[self::KEY_TECHNIQUE] ?? NULL;
        $this->width = $data[self::KEY_WIDTH] ?? 0;
        $this->height = $data[self::KEY_HEIGHT] ?? 0;
        $this->depth = $data[self::KEY_DEPTH] ?? 0;
        $this->date = $data[self::KEY_DATE] ?? '';
        $this->representations = $data[self::KEY_REPRESENTATIONS] ?? array();
        $this->preferredRepresentation = $data[self::KEY_PREFERRED_REPRESENTATION] ?? 0;
    }
    
    public function jsonSerialize() {
        return [self::KEY_TITLE=>$this->title,
            self::KEY_PREFERRED_TITLE=>$this->preferred_title,
            self::KEY_TECHNIQUE=>$this->technique,
            self::KEY_WIDTH=>$this->width,
            self::KEY_HEIGHT=>$this->height,
            self::KEY_DEPTH=>$this->depth,
            self::KEY_DATE=>$this->date,
            self::KEY_REPRESENTATIONS=>$this->representations,
            self::KEY_PREFERRED_REPRESENTATION=>$this->preferredRepresentation
        ];
    }
    
    public function getLongId() : string {
        return $this->parent->getIdPath().'.'.$this->id;
    }

    
    public function getPreferred_language() : string {
        return $this->preferred_title;
    }

    public function setPreferred_language(string $preferred_language) {
        $this->preferred_title = $preferred_language;
    }

    public function getTechnique() : string {
        return $this->technique;
    }

    public function setTechnique(string $technique) {
        $this->technique = $technique;
    }

    public function getWidth() : float {
        return $this->width;
    }

    public function setWidth(float $width) {
        $this->width = $width;
    }

    public function getHeight() : float {
        return $this->height;
    }

    public function setHeight(float $height) {
        $this->height = $height;
    }

    public function getDepth() : float {
        return $this->depth;
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
    
    public function getRepresentations() : array {
    	return $this->representations;
    }

    public function getPreferredRepresentation() : int {
        return $this->preferredRepresentation;
    }

    public function setPreferredRepresentation(int $preferredRepresentation) {
        $this->preferredRepresentation = $preferredRepresentation;
    }

    public function getId() : string {
        return $this->id;
    }

    public function getParent() : Path{
        return $this->parent;
    }

    public function render($template) {
        require($template);
    }
    
    

}

