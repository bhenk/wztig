<?php
namespace gitzw\site\model;

use JsonSerializable;
use gitzw\GZ;
use gitzw\site\data\Site;

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
 *		"ordinal": 3,
 *		"representations" : {}
 *	}
 * </pre>
 *
 */
class Resource implements iViewRender, JsonSerializable {
	
	const ART_MEDIA = [
			'acrylic'=>'aat:300015058',
			'canvas'=>'aat:300014078',
			'cardboard'=>'aat:300014224',
			'charcoal'=>'aat:300022414',
			'crayon'=>'aat:300022415',
			'ink'=>'aat:300015012',
			'oil'=>'aat:300015050',
			'paper'=>'aat:300014109',
			'pastel'=>'aat:300122621',
			'pencil'=>'aat:300410335',
			'watercolour'=>'aat:300015045',
			'wood'=>'aat:300011914',			
	];
	
	const ART_SURFACE = [
			'canvas'=>'aat:300014078',
			'cardboard'=>'aat:300014224',
			'paper'=>'aat:300014109',
			'wood'=>'aat:300011914'
	];
	
	const ART_MEDIUM = [
			'acrylic'=>'aat:300015058',
			'cardboard'=>'aat:300014224',
			'charcoal'=>'aat:300022414',
			'crayon'=>'aat:300022415',
			'ink'=>'aat:300015012',
			'oil'=>'aat:300015050',
			'pastel'=>'aat:300122621',
			'pencil'=>'aat:300410335',
			'watercolour'=>'aat:300015045',
	];
	
	const ADDITIONAL_TYPES = [
			'None'=>null,
			'Acrylic Painting'=>'aat:300181918',
			'Oil Painting'=>'aat:300033799',
			'Drypoint'=>'aat:300041349',
			'Drawing'=>'aat:300033973',
			'Poster'=>'aat:300027221',
			'Collage'=>'aat:300033963',
			'Assemblage'=>'aat:300047194',
			'Lithography'=>'aat:300041379'
	];
    
    const KEY_TITLES = 'titles';
    const KEY_PREFERRED_LANGUAGE = 'preferred_language';
    const KEY_MEDIA = "media";
    const KEY_WIDTH = 'width';
    const KEY_HEIGHT = 'height';
    const KEY_DEPTH = 'depth';
    const KEY_DATE = 'date';
    const KEY_HIDDEN = 'hidden';
    const KEY_ORDINAL = 'ordinal';
    const KEY_REPRESENTATIONS = 'representations';
    
    const SD_ADDITIONAL_TYPES = 'sd_additional_types';
    
    const NO_TITLES = ['nl'=>'geen titel', 'en'=>'no title'];
    
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
    private $ordinal = 0;
    private $representations = array();
    
    private $sdAdditionalTypes;

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
        $this->ordinal = $data[self::KEY_ORDINAL] ?? -1;
        $reparr = $data[self::KEY_REPRESENTATIONS] ?? array();
        foreach ($reparr as $key=>$repdata) {
        	$this->representations[$key] = new Representation($this, $key, $repdata);
        }
        $this->sdAdditionalTypes = $data[self::SD_ADDITIONAL_TYPES] ?? [];
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
        	self::KEY_ORDINAL=>$this->ordinal,
            self::KEY_REPRESENTATIONS=>$this->representations,
        	self::SD_ADDITIONAL_TYPES=>$this->sdAdditionalTypes	
        ];
    }
    
    public function getId() : string {
    	return $this->id;
    }
    
    public function moveTo(ResourceContainer $parent, string $shortId) {
    	$this->parent = $parent;
    	$this->id = $shortId;
    }
    
    public function getLongId() : string {
        return $this->parent->getIdPath().'.'.$this->id;
    }
    
    public function getFullId() : string {
    	return GZ::SD_PREFIX.$this->getLongId();
    }
    
    public function getFullUrl() {
    	return Site::get()->hostName().$this->getResourcePath();
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
    
    public function getHtmlTitle() : string {
    	$title = $this->titles[$this->preferredLanguage];
    	if (empty($title)) $title = self::NO_TITLES[$this->preferredLanguage];
    	$otherLanguage = $this->preferredLanguage == 'nl' ? 'en' : 'nl';
    	$otherTitle = $this->titles[$otherLanguage];
    	if (empty($otherTitle)) return $title;
    	return $title.' ('.$otherTitle.')';
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
    	if ($this->height <= 0 or $this->width <= 0) {
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
    
    public function getOrdinal() : int {
    	return $this->ordinal;
    }
    
    public function setOrdinal(int $ordinal) {
    	$this->ordinal = $ordinal;
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
    
    public function getPublicRepresentationsOrdered() : array {
    	$arr = [];
    	foreach ($this->getRepresentations('ordinal') as $rep) {
    		$arr[] = $rep;
    	}
    	return $arr;
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

    public function getParent() : ?Path {
        return $this->parent;
    }
    
    public function getStructuredData(string $imageId = null) { 
    	$names = array_values(array_diff($this->titles, ['']));
    	if (count($names) <= 1) $names = $names[0];
    	if (is_null($imageId)) {
    		$imageId = $this->getRepresentation()->getFullId();
    	}
    	$material = [];
    	foreach ($this->getSdMaterial() as $key) {
    		$material[] = $key;
    		$material[] = self::ART_MEDIA[$key];
    	}
    	$additionalTypes = [];
    	foreach ($this->sdAdditionalTypes as $value) {
    		$additionalTypes[] = $value;
    		$additionalTypes[] = self::ADDITIONAL_TYPES[$value];
    	}
    	$creator = $this->getCreator();
    	return array_filter([
    			"@type"=>"VisualArtwork",
    			"@id"=>$this->getFullId(),
    			"additionalType"=>$additionalTypes,
    			"url"=>'https://gitzw.art'.$this->getResourcePath(),
    			"name"=>$names,
    			"image"=>$imageId,
    			"material"=>$material,
    			"width"=>$this->getWidth().' cm',
    			"height"=>$this->getHeight().' cm',
    			"dateCreated"=>$this->getDateCreated(),
    			"creator"=>$creator->getSdShort(),
    			"copyrightHolder"=>$creator->getFullId(),
    			"license"=>'https://creativecommons.org/licenses/by-nc-nd/4.0/'
    	]);
    }
    
    public function getSdAdditionalTypes() : array {
    	return $this->sdAdditionalTypes;
    }
    
    public function setSdAdditionalTypes(array $sdAdditionalTypes) {
    	$this->sdAdditionalTypes = $sdAdditionalTypes;
    }
    
    public function getAdditionalTypeCodes() {
    	$additionalTypes = [];
    	foreach ($this->sdAdditionalTypes as $value) {
    		$additionalTypes[] = self::ADDITIONAL_TYPES[$value];
    	}
    	return $additionalTypes;
    }
    
    public function getSdMaterial() : array {
    	return array_values(array_intersect(array_map('trim', 
    			explode(' ', str_replace(',', ' ', strtolower($this->media)))), array_keys(self::ART_MEDIA)));
    }
    
    public function getArtMedium() : array {
    	return array_values(array_intersect(array_map('trim',
    			explode(' ', str_replace(',', ' ', strtolower($this->media)))), array_keys(self::ART_MEDIUM)));
    }
    
    public function getArtworkSurface() : array {
    	return array_values(array_intersect(array_map('trim',
    			explode(' ', str_replace(',', ' ', strtolower($this->media)))), array_keys(self::ART_SURFACE)));
    }
    
    public function getMaterialTypeCodes() {
    	$materialTypes = [];
    	foreach ($this->getSdMaterial() as $value) {
    		$materialTypes[] = self::ART_MEDIA[$value];
    	}
    	return $materialTypes;
    }
    
    public function getArtMediumCodes() {
    	$codes = [];
    	foreach ($this->getArtMedium() as $value) {
    		$codes[] = self::ART_MEDIUM[$value];
    	}
    	return $codes;
    }
    
    public function getArtworkSurfaceCodes() {
    	$codes = [];
    	foreach ($this->getArtworkSurface() as $value) {
    		$codes[] = self::ART_SURFACE[$value];
    	}
    	return $codes;
    }
    
    public function getDateCreated() : ?string {
    	$dateCreated = implode('-', array_map(function($x) {
    		return sprintf('%02d', $x);
    	}, array_filter(Search::dateParse($this->date))));
    	if (strpos($this->date, '?') > 0) $dateCreated = null;
    	return $dateCreated;
    }
    
    public function getCreator() {
    	return $this->getParent()->getParentByNature('var');
    }
    
    public function render($template, array $args=NULL) {
        require($template);
    }
    

}

