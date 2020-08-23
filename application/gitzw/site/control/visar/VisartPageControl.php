<?php

namespace gitzw\site\control\visar;

use gitzw\site\control\DefaultPageControl;
use gitzw\site\model\SiteResources;
use gitzw\site\model\Visart;

class VisartPageControl extends DefaultPageControl {
	
	protected ?Visart $visart;
	
	function __construct(?Visart $visart=null, array $path=[]) {
		if (is_null($visart)) {
			if (isset($path[1])) {
				$visart = SiteResources::get()->getByPathSegment($path[1], 1);
			}
		}
		$this->visart = $visart;
	}
	
	
	protected function getCopyRight() : string {
		if (isset($this->visart)) {
			$copyRightStart = $this->visart->getCopyrightStart();
			if (isset($copyRightStart)) {
				$copyRightStart .= '&nbsp;-&nbsp;'.date('Y');
			}
			return '&#169;'.$copyRightStart.'&nbsp;'.
					str_replace(' ', '&nbsp;', $this->visart->getFullName()).' &nbsp;&bull;';
		} else {
			return '&#169;gitzw.art &nbsp;&bull;';
		}
	}
}


