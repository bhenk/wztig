<?php

namespace gitzw\site\model;

use gitzw\site\ext\Shuffler;

class ResourceContainer extends Path {
	
	const KEY_RESOURCES = 'resources';
	
	private $resources = array();
	private $publicResourceCount = -1;
	
	public function loadResources() {
		if (!$this->resourcesLoaded) {
			$arr = $this->load();
			if (array_key_exists(self::KEY_RESOURCES, $arr)) {
				foreach ($arr[self::KEY_RESOURCES] as $id=>$data) {
					$this->resources[$id] = new Resource($id, $data, $this);
				}
			}
			parent::loadResources();
		}
	}
	
	public function collectRepresentations(array &$stack) {
		parent::collectRepresentations($stack);
		foreach ($this->resources as $resource) {
			foreach($resource->getRepresentations() as $rep) {
				$stack[] = $rep->getLocation();
			}
		}
	}
	
	public function getResources() : array {
		$this->loadResources();
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
	
	public function getResourceByShortId(string $rid) {
		$this->loadResources();
		return $this->resources[$rid];
	}
	
	public function getPublicResources() : array {
		$this->loadResources();
		$pr = $this->resources;
		// filter
		return $pr;
	}
	
	public function getPublicResourcesReversed() : array {
		return array_reverse($this->getPublicResources());
	}
	
	public function getPublicResourcesShuffled() : array {
		$prs = $this->getPublicResources();
		shuffle($prs);
		return $prs;
	}
	
	public function getPubResourcesRandomized(string $seedString = NULL) : array {
		return Shuffler::fisherYatesShuffle($this->getPublicResources(), $seedString);
	}
	
	public function getPublicResourceCount() : int {
		if ($this->publicResourceCount < 0) {
			$this->publicResourceCount = count($this->getPublicResources());
		}
		return $this->publicResourceCount;
	}
	
	
	public function jsonSerialize() {
		$this->loadResources();
		$jsonArray = parent::jsonSerialize();
		$jsonArray[self::KEY_RESOURCES] = $this->resources;
		return $jsonArray;
	}
	
	public function jsonSerializeFlat() : array {
		$this->loadResources();
		$jsonArray = parent::jsonSerializeFlat();
		$jsonArray[self::KEY_RESOURCES] = $this->resources;
		return $jsonArray;
	}
}

