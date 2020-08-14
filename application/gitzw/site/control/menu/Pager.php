<?php

namespace gitzw\site\control\menu;

use gitzw\GZ;
use gitzw\site\model\iViewRender;

class Pager implements iViewRender {
	
	const DEFAULT_TEMPLATE = GZ::TEMPLATES.'/views/pager.php';
	
	private int $start;
	private int $itemsPerPage;
	private int $itemCount;
	private int $pagesInview;
	private string $baseurl;
	private string $seed;
	private string $template;
	
	function __construct(int $start, int $itemsPerPage, int $itemCount, string $baseurl, string $seed, int $pagesInView=2) {
		$this->start = $start;
		$this->itemsPerPage = $itemsPerPage;
		$this->itemCount = $itemCount;
		$this->baseurl = $baseurl;
		$this->seed = $seed;
		$this->pagesInview = $pagesInView;
	}
	
	public function render($template=NULL) {
		if (is_null($template)) {
			$template = self::DEFAULT_TEMPLATE;
		}
		require $template;
	}
	
	private function getLink($startItem) {
		return $this->baseurl.'/'.$this->seed.'/'.$startItem.'/'.$this->itemsPerPage;
	}
	
	protected function getChronoLink() {
		return $this->baseurl.'/chrono/'.$this->start.'/'.$this->itemsPerPage;
	}
	
	protected function getChronoStyle() {
		if ($this->seed == 'chrono') {
			return ' style="visibility: hidden;"';
		} else {
			return '';
		}
	}
	
	protected function getLeftArrowLink() {
		$startItem = max(0, $this->start - $this->itemsPerPage);
		return $this->getLink($startItem);
	}
	
	protected function getLeftArrowStyle() {
		if ($this->start <= 0) {
			return ' style="visibility: hidden;"';
		} else {
			return '';
		}
	}
	
	protected function getRightArrowLink() {
		$startItem = $this->start + $this->itemsPerPage;
		return $this->getLink($startItem);
	}
	
	protected function getRightArrowStyle() {
		if (($this->start + $this->itemsPerPage) >= $this->itemCount) {
			return ' style="visibility: hidden;"';
		} else {
			return '';
		}
	}
	
	protected function getPagelinks() : array {
		if ($this->itemCount <= $this->itemsPerPage) {
			return [];
		}
		
		$links = array();
		$pageCount = ceil($this->itemCount / $this->itemsPerPage);
		
		$startItem = 0;
		$link = [1,
				$this->getLink($startItem),
				$this->start == $startItem
		];
		$links[] = $link;
		
		if ($pageCount == 1) {
			return $links;
		}
		
		$page = ceil($this->start / $this->itemsPerPage) + 1;
		
		$begin = max(2, $page - $this->pagesInview);
		$end = min($pageCount, $page + ($this->pagesInview + 1));
		
		if ($begin > 2) {
			$links[] = ['...', '#', FALSE];
		}
		
		for ($i = $begin; $i < $end; $i++) {
			$startItem = ($i - 1) * $this->itemsPerPage;
			$link = [$i,
					$this->getLink($startItem),
					$this->start == $startItem
			];
			$links[] = $link;
		}
		
		if ($end < $pageCount) {
			$links[] = ['...', '#', FALSE];
		}
		
		$startItem = ($pageCount - 1) * $this->itemsPerPage;
		$link = [$pageCount,
				$this->getLink($startItem),
				$this->start == $startItem
		];
		$links[] = $link;
		
		return $links;
	}
	
	
}

