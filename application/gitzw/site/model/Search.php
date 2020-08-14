<?php

namespace gitzw\site\model;

class Search {
	
	public function contains($needle, $haystack) : bool {
		return strpos($haystack, $needle) !== false;
	}
	
	public function searchTitles(string $s) {
		
	}
}

