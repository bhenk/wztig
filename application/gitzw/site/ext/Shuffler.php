<?php

namespace gitzw\site\ext;

class Shuffler {
	
	
	/**
	 * fisherYatesShuffle
	 * 
	 * @param array $items
	 * @param ?string $seedString
	 */
	public static function fisherYatesShuffle(array $any, string $seedString=NULL) : array {
		$items = array_values($any);
		if (is_null($seedString)) {
			$s = rand(0, intval(self::permutations(count($items))));
			$seedString = number_format($s, 0, '.', '');
		}
		$seed = intval($seedString);
		
		@mt_srand($seed);
		for ($i = count($items) - 1; $i > 0; $i--)
		{
			$j = @mt_rand(0, $i);
			$tmp = $items[$i];
			$items[$i] = $items[$j];
			$items[$j] = $tmp;
		}
		$seedString = number_format($seed, 0, '.', '');
		return [$seedString, $items];
	}
	
// 	public static function permutations(int $n) : string {
// 		$n = min(170, $n);
// 		$x = strval($n);
// 		while ($n > 1) {
// 			--$n;
// 			$x = bcmul($x, strval($n));
// 		}
// 		return $x;
// 	}
	
	public static function permutations(int $n) : string {
		$n = min(170, $n);
		$x = $n;
		while ($n > 1) {
			--$n;
			$x = $x * $n;
		}
		return number_format($x, 0, '.', '');;
	}
}

