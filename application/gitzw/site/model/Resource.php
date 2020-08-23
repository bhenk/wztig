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
 *		"media" : "pencil, charcoal and acrylic on paper",
 *		"width" : 20.5,
 *		"height" : 33,
 *		"depth" : 0,
 *		"date" : "2020-02",
 *		"hidden" : false,
 *		"representations" : {}
 *	}
 * </pre>
 *
 */
class Resource implements iViewRender, JsonSerializable {
    
    const KEY_TITLES = 'titles';
    const KEY_PREFERRED_LANGUAGE = 'preferred_language';
    const KEY_MEDIA = "media";
    const KEY_WIDTH = 'width';
    const KEY_HEIGHT = 'height';
    const KEY_DEPTH = 'depth';
    const KEY_DATE = 'date';
    const KEY_HIDDEN = 'hidden';
    const KEY_REPRESENTATIONS = 'representations';
    
    private $id;
    private $parent;
    private $titles = array();
    private $preferredLanguage;
    private $media;
    private $width;
    private $height;
    private $depth;
    private $date;
    private $hidden = FALSE;
    private $representations = array();

	function __construct(string $id, array $data, Path $parent=NULL) {
        $this->id = $id;
        $this->parent = $parent;
        $this->titles = $data[self::KEY_TITLES] ?? array();
        $this->preferredLanguage = $data[self::KEY_PREFERRED_LANGUAGE] ?? 'en';
        $this->media = $data[self::KEY_MEDIA] ?? '';
        $this->width = $data[self::KEY_WIDTH] ?? -1;
        $this->height = $data[self::KEY_HEIGHT] ?? -1;
        $this->depth = $data[self::KEY_DEPTH] ?? -1;
        $this->date = $data[self::KEY_DATE] ?? '';
        $this->hidden = $data[self::KEY_HIDDEN] ?? FALSE;
        $reparr = $data[self::KEY_REPRESENTATIONS] ?? array();
        foreach ($reparr as $key=>$data) {
        	$this->representations[$key] = new Representation($this, $key, $data);
        }
    }
    
    public function jsonSerialize() {
        return [self::KEY_TITLES=>$this->titles,
            self::KEY_PREFERRED_LANGUAGE=>$this->preferredLanguage,
        	self::KEY_MEDIA=>$this->media,
            self::KEY_WIDTH=>$this->width,
            self::KEY_HEIGHT=>$this->height,
            self::KEY_DEPTH=>$this->depth,
            self::KEY_DATE=>$this->date,
        	self::KEY_HIDDEN=>$this->hidden,
            self::KEY_REPRESENTATIONS=>$this->representations,
        ];
    }
    
    public function getLongId() : string {
        return $this->parent->getIdPath().'.'.$this->id;
    }
    
    /**
     * Get the URL of this resource.
     * 
     * @return string
     */
    public function getResourcePath() :string {
    	return $this->parent->getResourcePath().'/'.$this->id;
    }
    
    public function getSubscript() {
    	$bull = '&nbsp;&nbsp;<wbr>&bull;&nbsp;&nbsp;';
    	$subscript = '';
    	if ($this->hasTitle()) {
    		$subscript .= $this->getDisplayTitle().$bull;
    	} else {
    		$subscript .= '<span lang="nl">geen titel</span> (no title)'.$bull;
    	}
    	if (!empty($this->media)) $subscript .= $this->media.$bull;
    	$dimensions = $this->getDimensions();
    	if (!empty($dimensions)) $subscript .= $dimensions.$bull;
    	if (!empty($this->date)) $subscript .= $this->date;
    	return $subscript;
    }
    
    public function getTitles() : array {
    	return $this->titles;
    }
    
    public function hasTitle() : bool {
    	foreach (array_values($this->titles) as $title) {
    		if (!empty($title)) return true;
    	}
    	return false;
    }
    
    public function getDisplayTitle() {
    	return $this->createDisplayTitle($this->preferredLanguage);
    }
    
    private function createDisplayTitle($lang) : string {
    	$others = $this->titles;
    	unset($others[$lang]);
    	$displayTitle = '<span lang="'.$lang.'">'.$this->titles[$lang].'</span>';
    	foreach($others as $lan=>$title) {
    		if (!empty($title)) {
    			$displayTitle .= ' (<span lang="'.$lan.'">'.$title.'</span>) ';
    		}
    	}
    	return $displayTitle;
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

    public function getMedia() : string {
        return $this->media;
    }

    public function setMedia(string $media) {
    	$this->media = $media;
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
    
    public function getDimensions() {
    	if ($this->height == 0 or $this->width == 0) {
    		return '';
    	}
    	$hc = $this->height;
    	$wc = $this->width;
    	$hi = $hc/2.54;
    	$wi = $wc/2.54;
    	return $wc . ' x ' . $hc . ' cm. [w x h] ' . number_format($wi, 1) . ' x ' .number_format($hi, 1) . ' in.';
    }

    public function getDate() : string {
        return $this->date;
    }

    public function setDate(string $date) {
        $this->date = $date;
    }
    
    public function getHidden() : bool {
    	return $this->hidden;
    }
    
    public function setHidden(bool $hidden) {
    	$this->hidden = $hidden;
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
    		if ($representation->getPreferred()) {
    			return $representation;
    		}
    	}
    	return array_values($this->representations)[0];
    }
    
    public function hasPreferredRepresentation() : bool {
    	foreach (array_values($this->representations) as $representation) {
    		if ($representation->getPreferred()) {
    			return TRUE;
    		}
    	}
    	return FALSE;
    }
    
    public function hasFrontPage() : bool {
    	foreach (array_values($this->representations) as $representation) {
    		if ($representation->isFrontPage()) {
    			return TRUE;
    		}
    	}
    	return FALSE;
    }

    public function getId() : string {
        return $this->id;
    }

    public function getParent() : ?Path {
        return $this->parent;
    }

    public function render($template, array $args=NULL) {
        require($template);
    }
    
    

}

