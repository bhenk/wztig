<?php

namespace gitzw\site\model;

class Search {
	
	
	public static function dateParse(string $date) : array {
		$dp = array();
		// 20200228 02-02-2020 02-2020 2020 2020-02 2020-02-02
		// len 8        10        7      4     7       10
		$x = strlen(trim($date));
		
		$d = explode('-', $date);
		if (count($d) < 2) $d = explode('/', $date);
		
		if (count($d) == 1 and $x == 8) {
			$dp['y'] = intval(substr($date, 0, 4));
			$dp['m'] = intval(substr($date, 4, 2));
			$dp['d'] = intval(substr($date, 6, 2));
		} elseif (count($d) == 1 and $x == 6) {
			$dp['y'] = intval(substr($date, 0, 4));
			$dp['m'] = intval(substr($date, 4, 2));
			$dp['d'] = null;
		} elseif (count($d) == 1 and $x == 4) {
			$dp['y'] = intval($date);
			$dp['m'] = null;
			$dp['d'] = null;
		} elseif (count($d) == 1)	{
			$dp['y'] = intval($d[0]);
			$dp['m'] = null;
			$dp['d'] = null;
		} elseif (count($d) == 2 and strlen($d[0]) == 4) {
			$dp['y'] = intval($d[0]);
			$dp['m'] = intval($d[1]);
			$dp['d'] = null;
		} elseif (count($d) == 2 and strlen($d[1]) == 4) {
			$dp['y'] = intval($d[1]);
			$dp['m'] = intval($d[0]);
			$dp['d'] = null;
		} elseif (count($d) == 3 and strlen($d[0]) == 4) {
			$dp['y'] = intval($d[0]);
			$dp['m'] = intval($d[1]);
			$dp['d'] = intval($d[2]);
		}  elseif (count($d) == 3 and strlen($d[2]) == 4) {
			$dp['y'] = intval($d[2]);
			$dp['m'] = intval($d[1]);
			$dp['d'] = intval($d[0]);
		}
		return $dp;
	}
	
	public static function dateParse2(string $date) : array {
		$dp = array();
		// 20200228 02-02-2020 02-2020 2020 2020-02 2020-02-02
		// len 8        10        7      4     7       10
		$x = strlen(trim($date));
		
		$d = explode('-', $date);
		if (count($d) < 2) $d = explode('/', $date);
		
		if (count($d) == 1 and $x == 8) {
			$dp['y'] = substr($date, 0, 4);
			$dp['m'] = substr($date, 4, 2);
			$dp['d'] = substr($date, 6, 2);
		} elseif (count($d) == 1 and $x == 6) {
			$dp['y'] = substr($date, 0, 4);
			$dp['m'] = substr($date, 4, 2);
			$dp['d'] = '??';
		} elseif (count($d) == 1 and $x == 4) {
			$dp['y'] = $date;
			$dp['m'] = '??';
			$dp['d'] = '??';
		} elseif (count($d) == 1)	{
			$dp['y'] = $d[0];
			$dp['m'] = '??';
			$dp['d'] = '??';
		} elseif (count($d) == 2 and strlen($d[0]) == 4) {
			$dp['y'] = $d[0];
			$dp['m'] = $d[1];
			$dp['d'] = '??';
		} elseif (count($d) == 2 and strlen($d[1]) == 4) {
			$dp['y'] = $d[1];
			$dp['m'] = $d[0];
			$dp['d'] = '??';
		} elseif (count($d) == 3 and strlen($d[0]) == 4) {
			$dp['y'] = $d[0];
			$dp['m'] = $d[1];
			$dp['d'] = $d[2];
		}  elseif (count($d) == 3 and strlen($d[2]) == 4) {
			$dp['y'] = $d[2];
			$dp['m'] = $d[1];
			$dp['d'] = $d[0];
		}
		return $dp;
	}
	
	public static function makeEquivalent($dateString1, $dateString2) {
		$d1 = implode('-', self::dateParse2($dateString1));
		$d2 = implode('-', self::dateParse2($dateString2));
		for ($i=0; $i < strlen($d1); $i++) {
			if ($d1[$i] == '?') $d2[$i] = '?';
		}
		for ($i=0; $i < strlen($d2); $i++) {
			if ($d2[$i] == '?') $d1[$i] = '?';
		}
		return [$d1, $d2];
	}
	
	public static function inspectDates($dateString1, $dateString2) : int {
		if (empty($dateString1) or empty($dateString2)) return 0;
		$dd = self::makeEquivalent($dateString1, $dateString2);
		$d1 = self::dateParse($dd[0]);
		$d2 = self::dateParse($dd[1]);
		$result = -1;
		if (isset($d1['y']) and $d1['y'] == $d2['y']) {
			$result += 10;
			if (isset($d1['m']) and $d1['m'] == $d2['m']) {
				$result += 10;
				if (isset($d1['d']) and $d1['d'] == $d2['d']) {
					$result += 10;
				}
			}
		}
		return $result;
	}
	
	public static function uniDate($dateString, $r) {
		$d = self::dateParse(trim(preg_replace('[\?]', $r, $dateString)));
		$y = isset($d['y']) ? $d['y'] : $r.$r.$r.$r;
		$m = isset($d['m']) ? sprintf('%02d', $d['m']) : $r.$r;
		$x = isset($d['d']) ? sprintf('%02d', $d['d']) : $r.$r;
		return intval($y.$m.$x);
	}
	
	public static function contains($needle, $haystack) : bool {
		return strpos($haystack, $needle) !== false;
	}
	
	public static function wordCount(string $s, ?string $t) : int {
		if (is_null($t)) return 0;
		$words = explode(' ', strtolower($s));
		$target = ' '.strtolower($t).' ';
		$r = 0;
		foreach ($words as $word) {
			if (!empty($word)) {
				$x = strlen($word);
				$r += 3 * $x * (substr_count($target, ' '.$word.' '));
				$r += 2 * $x * (substr_count($target, ' '.$word));
				$r += 2 * $x * (substr_count($target, $word.' '));
				$r += 2 * $x * (substr_count($target, $word));
				$r += (strlen($target) - levenshtein($word, $target)) * 5;
			}
		}
		return self::FACTOR_W * $r;
	}
	
	const FIELDS = array(
			'title_en', 'title_nl', 'media', 'width', 'height', 'date',
			'rishidden', 'risnothidden', 'risfrontpage', 'risnotfrontpage', 'longid'
	);
	const FACTOR_D = 30;
	const FACTOR_W = 1;
	const FACTOR_Y = 4;
	
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
		$result = 0;
		$result += $this->inspectTitleEn($r);
		$result += $this->inspectTitleNl($r);
		$result += $this->inspectMedia($r);
		
		$w = $this->inspectWidth($r);
		if ($w < 0) {
			return -1;
		}
		$result += $w;
		
		$h = $this->inspectHeight($r);
		if ($h < 0) {
			return -1;
		}
		$result += $h;
		
		$h = $this->inspectHiddenState($r);
		if ($h < 0) {
			return -1;
		}
		$result += $h;
		
		$h = $this->inspectFrontPageState($r);
		if ($h < 0) {
			return -1;
		}
		$result += $h;
		
		$h = $this->inspectLongId($r);
		if ($h < 0) {
			return -1;
		}
		$result += $h;
		
		$h = $this->inspectDate($r);
		if ($h < 0) {
			return -1;
		}
		$result += $h;
		
		return $result;
	}
	
	private function inspectTitleEn(Resource $r) : float {
		$s = $this->data['title_en'];
		if (empty(trim($s))) {
			return 0;
		}
		return self::wordCount($s, $r->getTitles()['en']);
	}
	
	private function inspectTitleNl(Resource $r) : float {
		$s = $this->data['title_nl'];
		if (empty(trim($s))) {
			return 0;
		}
		return self::wordCount($s, $r->getTitles()['nl']);
	}
	
	private function inspectMedia(Resource $r) : float {
		$s = $this->data['media'];
		if (empty(trim($s))) {
			return 0;
		}
		return self::wordCount($s, $r->getMedia());
	}
	
	private function inspectWidth(Resource $r) : float {
		$s = $this->data['width'];
		if (empty(trim($s))) {
			return 0;
		}
		$w = $r->getWidth();
		$width = floatval(preg_replace('/[^0-9.]/', '', $s));
		if (substr($s, 0, 4) == '&gt;' and $w > $width) {
			return max(1, (1 / (1 + abs($w - $width))) * self::FACTOR_D);
		}
		if (substr($s, 0, 4) == '&gt;' and $w <= $width) {
			return -1;
		}
		if (substr($s, 0, 4) == '&lt;' and $w < $width) {
			return max(1, (1 / (1 + abs($w - $width))) * self::FACTOR_D);
		}
		if (substr($s, 0, 4) == '&lt;' and $w >= $width) {
			return -1;
		}
		return max(1, (1 / (1 + abs($w - $width))) * self::FACTOR_D);
	}
	
	private function inspectHeight(Resource $r) : float {
		$s = $this->data['height'];
		if (empty(trim($s))) {
			return 0;
		}
		$h = $r->getHeight();
		$height = floatval(preg_replace('/[^0-9.]/', '', $s));
		if (substr($s, 0, 4) == '&gt;' and $h > $height) {
			return max(1, (1 / (1 + abs($h - $height))) * self::FACTOR_D);
		}
		if (substr($s, 0, 4) == '&gt;' and $h <= $height) {
			return -1;
		}
		if (substr($s, 0, 4) == '$lt;' and $h < $height) {
			return max(1, (1 / (1 + abs($h - $height))) * self::FACTOR_D);
		}
		if (substr($s, 0, 4) == '&lt;' and $h >= $height) {
			return -1;
		}
		return max(1, (1 / (1 + abs($h - $height))) * self::FACTOR_D);
	}
	
	private function inspectDate(Resource $r) : float {
		$s = $this->data['date'];
		$result = 0;
		if (substr($s, 0, 4) == '&gt;') {
			$d1 = self::uniDate(substr($s, 4), '9');
			$d2 = self::uniDate($r->getDate(), '0');
			if ($d2 < $d1) return -1;
		} elseif (substr($s, 0, 4) == '&lt;') {
			$d1 = self::uniDate(substr($s, 4), '0');
			$d2 = self::uniDate($r->getDate(), '9');
			if ($d2 > $d1) return -1;
		} else {
			$result += self::inspectDates($s, $r->getDate());
		}
		return $result * self::FACTOR_Y;
	}
	
	private function inspectHiddenState(Resource $r) : float {
		if ($this->data['rishidden'] == 'rishidden' and !$r->getHidden()) {
			return -1;
		} elseif($this->data['risnothidden'] == 'risnothidden' and $r->getHidden()) {
			return -1;
		} 
		return 0;
	}
	
	private function inspectFrontPageState(Resource $r) : float {
		if ($this->data['risfrontpage'] == 'risfrontpage' and !$r->hasFrontPage()) {
			return -1;
		} elseif($this->data['risnotfrontpage'] == 'risnotfrontpage' and $r->hasFrontPage()) {
			return -1;
		} 
		return 0;
	}
	
	private function inspectLongId(Resource $r) : float {
		$s = $this->data['longid'];
		if (empty(trim($s))) {
			return 0;
		}
		if ($s != $r->getLongId()) {
			return -1;
		}
		return 0;
	}
	
}

