<?php

namespace gitzw\site\model;

use gitzw\GZ;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImageInspector {
	
	public function listImages() {
		$temp = tmpfile();
		fwrite($temp, "var,path,size\n");
		foreach ($this->scanImages() as $name=>$images) {
			foreach($images as $image) {
				$size = filesize(GZ::DATA.'/images/'.$image);
				fwrite($temp, $name.','.$image.','.$size."\n");
			}
		}
		fflush($temp);
		$file = stream_get_meta_data($temp)['uri'];
		header("Content-type: text/csv");
		header("Content-disposition: attachment; filename = list-images.csv");
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		fclose($temp);
	}
	
	/**
	 * Get images per var that are not yet referenced by resources of the var.
	 *
	 * @return array
	 */
	public function imageDiff() : array {
		$arr = array();
		$resourceImages = SiteResources::get()->getResourceImages();
		foreach ($this->scanImages() as $name=>$images) {
			$arr[$name] = array_diff($images, $resourceImages[$name]);
		}
		return $arr;
	}
	
	/**
	 * Scan all data/images directories and list all relative paths of images per var.
	 * 
	 * @return array all images in data/images
	 */
	public function scanImages() : array {
		$arr = array();
		$imageDir = GZ::DATA.'/images/';
		$vars = SiteResources::get()->getSegment(['var']);
		foreach ($vars->getChildren() as $var) {
			$arr[$var->getName()] = $this->getImages($imageDir.$var->getName(), $imageDir);
		}
		return $arr;
	}
	
	/**
	 * Walks directories recursively in search for images.
	 * 
	 * @param string $dir where to start the search 
	 * @param string $baseDir what part of the absolute path should be truncated to get relative pathes (default '')
	 * @return array array with pathes
	 */
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
	
	public function deleteImage(string $filename) : bool {
		return unlink(GZ::DATA.'/images/'.$filename);
	}
	
	
}

