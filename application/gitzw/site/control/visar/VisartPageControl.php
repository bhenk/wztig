<?php

namespace gitzw\site\control\visar;

use gitzw\site\control\DefaultPageControl;
use gitzw\site\model\Visart;

class VisartPageControl extends DefaultPageControl {
	
	protected Visart $visart;
	
	function __construct(Visart $visart) {
		$this->visart = $visart;
	}
	
	protected function getCopyRight() : string {
		$copyRightStart = $this->visart->getCopyrightStart();
		if (isset($copyRightStart)) {
			$copyRightStart .= '&nbsp;-&nbsp;'.date('Y');
		}
		return '&#169;'.$copyRightStart.'&nbsp;'.
				str_replace(' ', '&nbsp;', $this->visart->getFullName()).' &nbsp;&bull;';
	}
}


