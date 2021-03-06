<?php

namespace gitzw\site\model;

use gitzw\site\ext\Shuffler;

class ResourceContainer extends Path {
	
	const KEY_RESOURCES = 'resources';
	const PROP_KEY_PUB_RESOURCE_COUNT = 'pub_resource_count';
	
	private $resources = array();
	
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
	
	public function collectResources(array &$stack, array $query, $callback=NULL) {
		$o = $this->getOrdinal();
		if ($query[$o] == 'all' or $query[$o] == $this->name) {
			$this->loadResources();
			foreach ($this->resources as $resource) {
				if (!is_null($callback)) {
					$relevance = call_user_func($callback, $resource);
					$stack[] = [$relevance, $resource];
				} else {
					$stack[] = [1.1, $resource];
				}
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
	
	public function addExistingResource(Resource $r) : Resource {
		$shortId = $this->getNextResourceId();
		$r->moveTo($this, $shortId);
		$this->resources[$shortId] = $r;
		$this->persist();
		return $r;
	}
	
	public function removeResource(string $shortId) : bool {
		$this->loadResources();
		$success = array_key_exists($shortId, $this->resources);
		if ($success) {
			unset($this->resources[$shortId]);
			$this->persist();
		}
		return $success;
	}
	
	/**
	 * Get the next open resource id.
	 * 
	 * @return string a 4-digit string
	 */
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
		foreach ($pr as $id=>$resource) {
			if ($resource->getHidden()) {
				unset($pr[$id]);
			}
		}
		return $pr;
	}
	
	public function getPublicResourcesOrdered() {
		$ordered = $this->getPublicResources();
		uasort($ordered, function($a, $b) {
			return $b->getOrdinal() <=> $a->getOrdinal();
		});
		return $ordered;
	}
	
	public function getPublicResourcesReversed() : array {
		return array_reverse($this->getPublicResources());
	}
	
	public function getPubResourcesRandomized(string $seedString = NULL) : array {
		return Shuffler::fisherYatesShuffle($this->getPublicResources(), $seedString);
	}
	
	public function getResourcesOrdered() : array {
		$this->loadResources();
		$ordered = $this->resources;
		uasort($ordered, function($a, $b) {
			return $a->getOrdinal() <=> $b->getOrdinal();
		});
		return $ordered;
	}
	
	public function getPublicResourceCount() : int {
		return $this->props[self::PROP_KEY_PUB_RESOURCE_COUNT];
	}
	
	public function nextPublicResource(string $idCurrent) : ?Resource {
		$pubResources = $this->getPublicResourcesOrdered();
		$keys = array_keys($pubResources);
		$next = $pubResources[$keys[array_search($idCurrent, $keys) + 1]];
		if (is_null($next)) {
			$sib = $this->parent->nextSibling($this->name);
			if (isset($sib)) {
				$next = $sib->getFirstResource();
			}
		}
		return $next;
	}
	
	public function previousPublicResource(string $idCurrent) : ?Resource {
		$pubResources = $this->getPublicResourcesOrdered();
		$keys = array_keys($pubResources);
		$prev = $pubResources[$keys[array_search($idCurrent, $keys) - 1]];
		if (is_null($prev)) {
			$sib = $this->parent->previousSibling($this->name);
			if (isset($sib)) {
				$prev = $sib->getLastResource();
			}
		}
		return $prev;
	}
	
	public function getFirstResource() : ?Resource {
		$pubResources = $this->getPublicResourcesOrdered();
		$first = array_values($pubResources)[0];
		if (is_null($first)) {
			$sib = $this->parent->nextSibling($this->name);
			if (isset($sib)) {
				$first = $sib->getFirstResource();
			}
		}
		return $first;
	}
	
	public function getLastResource() {
		$pubResources = $this->getPublicResourcesOrdered();
		$last = array_values($pubResources)[count($pubResources) - 1];
		if (is_null($last)) {
			$sib = $this->parent->previousSibling($this->name);
			if (isset($sib)) {
				$last = $sib->getLastResource();
			}
		}
		return $last;
	}
	
	public function jsonSerialize() {
		$this->loadResources();
		$this->props[self::PROP_KEY_PUB_RESOURCE_COUNT] = count($this->getPublicResources());
		$jsonArray = parent::jsonSerialize();
		$jsonArray[self::KEY_RESOURCES] = $this->resources;
		return $jsonArray;
	}
	
	public function jsonSerializeFlat() : array {
		$this->loadResources();
		$this->props[self::PROP_KEY_PUB_RESOURCE_COUNT] = count($this->getPublicResources());
		$jsonArray = parent::jsonSerializeFlat();
		$jsonArray[self::KEY_RESOURCES] = $this->resources;
		return $jsonArray;
	}
}

