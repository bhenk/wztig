<?php

namespace gitzw\site\model;

use gitzw\GZ;

class PathFactory {
	
	public static function create(string $name, int $depth, Path $parent) {
		if (is_null($parent)) {
			$directory = GZ::DATA;
		} else {
			$directory = $parent->getDirectory().DIRECTORY_SEPARATOR.$parent->getName();
		}
		$dataFile = $directory.DIRECTORY_SEPARATOR.$name.'.json';
		$arr = json_decode(file_get_contents($dataFile), TRUE);
		$nature = $arr[PATH::KEY_NATURE] ?? NULL;
		if (is_null($nature)) {
			return new Path($name, $depth, $parent, $arr);
		}
		switch ($nature) {
		    case 'var':
		    	return new Visart($name, $depth, $parent, $arr);
		    	break;
		    case 'year':
		    	return new ResourceContainer($name, $depth, $parent, $arr);
		    	break;
		    default :
		    	return new Path($name, $depth, $parent, $arr);
		}
		
	}
}

