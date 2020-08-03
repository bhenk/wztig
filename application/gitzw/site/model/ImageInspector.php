<?php

namespace gitzw\site\model;

use gitzw\GZ;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImageInspector {
	
	public function scanImages() : array {
		$arr = array();
		$imageDir = GZ::DATA.'/images/';
		$vars = SiteResources::getSite()->getSegment(['var']);
		foreach ($vars->getChildren() as $var) {
			$arr[$var->getName()] = $this->getImages($imageDir.$var->getName(), $imageDir);
		}
		return $arr;
	}
	
	public function getImages(string $dir, string $baseDir = '') : array {
		$arr = array();
		$front = strlen($baseDir);
		if (file_exists($dir)) {
			$it = new RecursiveDirectoryIterator($dir);
			foreach (new RecursiveIteratorIterator($it) as $file) {
				switch ($file->getExtension()) {
					case 'jpg':
					case 'jpeg':
					case 'png':
					case 'gif':
						$arr[] = substr($file, $front);
				}
			}
		}
		return $arr;
	}
	
	/**
	 * Get images per var that are not yet referenced by resources of the var.
	 * 
	 * @return array
	 */
	public function imageDiff() : array {
		$arr = array();
		$resourceImages = SiteResources::getSite()->getResourceImages();
		foreach ($this->scanImages() as $name=>$images) {
			$arr[$name] = array_diff($images, $resourceImages[$name]);
		}
		return $arr;
	}
	
}

