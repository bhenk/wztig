<?php

namespace gitzw\site\model;

class Search {
	
	public static function contains($needle, $haystack) : bool {
		return strpos($haystack, $needle) !== false;
	}
	
	public static function wordCount(string $s, string $t) : int {
		$words = explode(' ', strtolower($s));
		$target = ' '.strtolower($t).' ';
		$r = 0;
		foreach ($words as $word) {
			$x = strlen($word);
			$r += 3 * $x * (substr_count($target, ' '.$word.' '));
			$r += 2 * $x * (substr_count($target, ' '.$word));
			$r += 2 * $x * (substr_count($target, $word.' '));
			$r += 1 * $x * (substr_count($target, $word));
			$r += strlen($target) - levenshtein($word, $target);
		}
		return $r;
	}
	
}

