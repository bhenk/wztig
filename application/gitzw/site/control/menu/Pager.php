<?php

namespace gitzw\site\control\menu;

use gitzw\GZ;

class Pager {
	
	const DEFAULT_TEMPLATE = GZ::TEMPLATES.'/views/pager.php';
	const AJAX_TEMPLATE = GZ::TEMPLATES.'/views/pager-ajax.php';
	
	private int $start;
	private int $itemsPerPage;
	private int $itemCount;
	private int $pagesInview;
	private string $baseurl;
	private $seed;
	private $template;
	
	function __construct(int $start, int $itemsPerPage, int $itemCount, string $baseurl, string $seed=NULL, int $pagesInView=2) {
		$this->start = $start;
		$this->itemsPerPage = $itemsPerPage;
		$this->itemCount = $itemCount;
		$this->baseurl = $baseurl;
		$this->seed = $seed;
		$this->pagesInview = $pagesInView;
	}
	
	public function setTemplate(string $template) {
		$this->template = $template;
	}
	
	public function render() {
		if (is_null($this->template)) {
			$this->template = self::DEFAULT_TEMPLATE;
		}
		require $this->template;
	}
	
	protected function getBaseUrl() : string {
		return $this->baseurl;
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
	
	protected function getLeftArrowOnClick() {
		return max(0, $this->start - $this->itemsPerPage);
	}
	
	protected function getLeftArrowLink() {
		$startItem = $this->getLeftArrowOnClick();
		return $this->getLink($startItem);
	}
	
	protected function getLeftArrowStyle() {
		if ($this->start <= 0) {
			return ' style="visibility: hidden;"';
		} else {
			return '';
		}
	}
	
	protected function getRightArrowOnClick() {
		return $this->start + $this->itemsPerPage;
	}
	
	protected function getRightArrowLink() {
		$startItem = $this->getRightArrowOnClick();
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
				$this->start == $startItem,
				$startItem
		];
		$links[] = $link;
		
		if ($pageCount == 1) {
			return $links;
		}
		
		$page = ceil($this->start / $this->itemsPerPage) + 1;
		
		$begin = max(2, $page - $this->pagesInview);
		$end = min($pageCount, $page + ($this->pagesInview + 1));
		
		if ($begin > 2) {
			$links[] = ['...', '#', FALSE, -1];
		}
		
		for ($i = $begin; $i < $end; $i++) {
			$startItem = ($i - 1) * $this->itemsPerPage;
			$link = [$i,
					$this->getLink($startItem),
					$this->start == $startItem,
					$startItem
			];
			$links[] = $link;
		}
		
		if ($end < $pageCount) {
			$links[] = ['...', '#', FALSE, -1];
		}
		
		$startItem = ($pageCount - 1) * $this->itemsPerPage;
		$link = [$pageCount,
				$this->getLink($startItem),
				$this->start == $startItem,
				$startItem
		];
		$links[] = $link;
		
		return $links;
	}
	
	
}

