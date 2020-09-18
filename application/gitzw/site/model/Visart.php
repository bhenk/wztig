<?php

namespace gitzw\site\model;


use gitzw\GZ;

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
	
	public function getFullId() {
		return GZ::SD_PREFIX.$this->name;
	}
	
	public function getStructuredData() {
		return [
				"@type"=>"Person",
				"@id"=>$this->getFullId(),
				"url"=>"https://gitzw.art".$this->getResourcePath(),
				"name"=>$this->fullName,
				"description"=>$this->getMetaDescription(),
				"sameAs"=>$this->props[self::PROP_KEY_SAME_AS_URLS]
		];
	}
	
	public function getSdShort() {
		return [
				"@type"=>"Person",
				"@id"=>$this->getFullId(),
				"url"=>"https://gitzw.art".$this->getResourcePath(),
				"name"=>$this->fullName,
		];
	}
	
	public function getActivityFullNames() : array {
		$names = [];
		foreach ($this->getChildByName('work')->getChildren() as $activity) {
			$names[] = $activity->getFullName();
		}
		return $names;
	}
	
	public function getMetaDescription() {
		$activities = implode(', ', $this->getActivityFullNames());
		$last = strrpos($activities, ', ');
		$activities = substr_replace($activities, ' and ', $last, 2);
		$start = $this->getProps()[self::PROP_KEY_COPYRIGHT_START];
		return $this->fullName.' works with '.$activities.' and is showing works from the period '.$start.' to '.date('Y').' on gitzw.art fine art.';
	}
}

