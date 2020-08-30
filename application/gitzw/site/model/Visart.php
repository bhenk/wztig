<?php

namespace gitzw\site\model;


class Visart extends Path {
	
	const PROP_KEY_IMAGE_FRONT = 'img_front';
	const PROP_KEY_COPYRIGHT_START = 'copyright_start';
	
	
	public function addFrontPageImage(string $location) {
		if (!array_search($location, $this->props[self::PROP_KEY_IMAGE_FRONT])) {
			$this->props[self::PROP_KEY_IMAGE_FRONT][] = $location;
			$this->persistFlat();
		}
	}
	
	public function removeFrontPageImage(string $location) {
		$key = array_search($location, $this->props[self::PROP_KEY_IMAGE_FRONT]);
		if ($key) {
			unset($this->props[self::PROP_KEY_IMAGE_FRONT][$key]);
			$this->persistFlat();
		}
	}
	
	public function getFrontPageImages() : array {		
		return $this->props[self::PROP_KEY_IMAGE_FRONT] ?? array();
	}
	
	public function hasFrontpageImage(string $location) {
		return array_search($location, $this->getFrontPageImages());
	}
	
	public function getCopyrightStart() {
		return $this->props[self::PROP_KEY_COPYRIGHT_START];
	}
	
	public function getStructuredData() {
		return [
				"@type"=>"Person",
				"@id"=>"https://gitzw.art/".$this->name,
				"url"=>"https://gitzw.art".$this->getResourcePath(),
				"name"=>$this->fullName
		];
	}
}

