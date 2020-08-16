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
			if (!empty($word)) {
				$x = strlen($word);
				$r += 3 * $x * (substr_count($target, ' '.$word.' '));
				$r += 2 * $x * (substr_count($target, ' '.$word));
				$r += 2 * $x * (substr_count($target, $word.' '));
				$r += 1 * $x * (substr_count($target, $word));
				$r += strlen($target) - levenshtein($word, $target);
			}
		}
		return self::FACTOR_W * $r;
	}
	
	const FIELDS = array('title_en', 'title_nl', 'technique', 'width', 'height');
	const FACTOR_D = 30;
	const FACTOR_W = 1;
	
	private $data;
	
	function __construct(array $data) {
		$this->data = $data;
	}
	
	public function isRelevant() : bool {
		foreach (self::FIELDS as $field) {
			if (!empty(trim($this->data[$field]))) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public function getQuery() : array {
		$q = [];
		foreach (self::FIELDS as $field) {
			if (!empty(trim($this->data[$field]))) {
				$q[$field] = $this->data[$field];
			}
		}
		return $q;
	}
	
	public function inspect(Resource $r) : float {
		$result = 0.3;
		$result += $this->inspectTitleEn($this->data['title_en'], $r);
		$result += $this->inspectTitleNl($this->data['title_nl'], $r);
		$result += $this->inspectTechnique($this->data['technique'], $r);
		
		$w = $this->inspectWidth($this->data['width'], $r);
		if ($w == 0) {
			return 0;
		}
		$result += $w;
		
		$h = $this->inspectHeight($this->data['height'], $r);
		if ($h == 0) {
			return 0;
		}
		$result += $h;
		
		return $result;
	}
	
	private function inspectTitleEn(String $s, Resource $r) : float {
		if (empty(trim($s))) {
			return 0.2;
		}
		return self::wordCount($s, $r->getTitles()['en']);
	}
	
	private function inspectTitleNl(String $s, Resource $r) : float {
		if (empty(trim($s))) {
			return 0.2;
		}
		return self::wordCount($s, $r->getTitles()['nl']);
	}
	
	private function inspectTechnique(String $s, Resource $r) : float {
		if (empty(trim($s))) {
			return 0.2;
		}
		return self::wordCount($s, $r->getTechnique());
	}
	
	private function inspectWidth(String $s, Resource $r) : float {
		if (empty(trim($s)) or $r->getWidth() < 0) {
			return 0.2;
		}
		$width = floatval(preg_replace('/[^0-9.]/', '', $s));
		if (substr($s, 0, 1) == '>' and $r->getwidth() > $width) {
			return max(1, (1 / (1 + abs($r->getWidth() - $width))) * self::FACTOR_D);
		}
		if (substr($s, 0, 1) == '>' and $r->getwidth() <= $width) {
			return 0;
		}
		if (substr($s, 0, 1) == '<' and $r->getwidth() < $width) {
			return max(1, (1 / (1 + abs($r->getWidth() - $width))) * self::FACTOR_D);
		}
		if (substr($s, 0, 1) == '<' and $r->getwidth() >= $width) {
			return 0;
		}
		return max(1, (1 / (1 + abs($r->getWidth() - $width))) * self::FACTOR_D);
	}
	
	private function inspectHeight(String $s, Resource $r) : float {
		$h = $r->getHeight();
		if (empty(trim($s)) or $h < 0) {
			return 0.2;
		}
		$height = floatval(preg_replace('/[^0-9.]/', '', $s));
		if (substr($s, 0, 1) == '>' and $h > $height) {
			return max(1, (1 / (1 + abs($h - $height))) * self::FACTOR_D);
		}
		if (substr($s, 0, 1) == '>' and $h <= $height) {
			return 0;
		}
		if (substr($s, 0, 1) == '<' and $h < $height) {
			return max(1, (1 / (1 + abs($h - $height))) * self::FACTOR_D);
		}
		if (substr($s, 0, 1) == '<' and $h >= $height) {
			return 0;
		}
		return max(1, (1 / (1 + abs($h - $height))) * self::FACTOR_D);
	}
	
}

