<?php

namespace gitzw\site\model;


class Visart extends Path {
	
	const PROP_KEY_IMAGE_FRONT = 'img_front';
	const PROP_KEY_COPYRIGHT_START = 'copyright_start';
	const PROP_KEY_SAME_AS_URLS = 'sameAs';
	
	
	public function addFrontPageImage(string $location, string $resourceId) {
		if (!$this->props[self::PROP_KEY_IMAGE_FRONT][$location]) {
			$this->props[self::PROP_KEY_IMAGE_FRONT][$location] = $resourceId;
			$this->persistFlat();
		}
	}
	
	public function removeFrontPageImage(string $location) {
		$val = $this->props[self::PROP_KEY_IMAGE_FRONT][$location];
		if ($val) {
			unset($this->props[self::PROP_KEY_IMAGE_FRONT][$location]);
			$this->persistFlat();
		}
	}
	
	public function getFrontPageImages() : array {		
		return $this->props[self::PROP_KEY_IMAGE_FRONT] ?? array();
	}
	
	public function hasFrontpageImage(string $location) {
		return $this->getFrontPageImages()[$location];
	}
	
	public function getCopyrightStart() {
		return $this->props[self::PROP_KEY_COPYRIGHT_START];
	}
	
	public function getStructuredData() {
		return [
				"@type"=>"Person",
				"@id"=>"http://gitzw.art/".$this->name,
				"url"=>"https://gitzw.art".$this->getResourcePath(),
				"name"=>$this->fullName,
				"sameAs"=>$this->props[self::PROP_KEY_SAME_AS_URLS]
		];
	}
}

