<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\menu\Pager;
use gitzw\site\data\Site;
use gitzw\site\model\Search;
use gitzw\site\model\SiteResources;

class ResourceListPageControl extends DefaultPageControl {
	
	protected string $action;
	protected $visart = 'all';
	protected $activity = 'all';
	protected $category = 'all';
	protected $year = 'all';
	protected $title_en;
	protected $title_nl;
	protected $technique;
	protected $width;
	protected $height;
	
	protected $results = array();
	
	protected $pager;
	protected $itemCount;
	protected $resultFactor;
	protected $start = 0;
	private $itemsPerPage = 5;
	
	function __construct(array $path) {
		$this->setMenuManager(new AdminMenuManager('/admin/find-resources'));
		$this->addStylesheet('/css/form.css');
		$this->addScript(GZ::SCRIPTS.'/collapse.js');
		$this->action = '/'.implode('/', array_slice($path, 1));
	}
	
	public function renderPage() {
		if (Site::get()->requestMethod() == 'GET') {
			$this->setContentFile(GZ::TEMPLATES.'/admin/resource-filter-form.php');
			parent::renderPage();
		} else {
			$this->handlePost();
		}
	}
	
	private function handlePost() {
		$showForm = FALSE;
		if (empty($_POST)) {
			$input = json_decode(file_get_contents('php://input'), true);
			$data = $input['payload'];
			$paging = $input['paging'];
			$this->start = max(0, intval($paging['start']));
			if ($paging['start'] === 'form') {
				$showForm = TRUE;
			}
		} else {
			$data = $_POST;
		}
		$this->visart = $data['visart'];
		$this->activity = $data['activity'];
		$this->category = $data['category'];
		$this->year = $data['year'];
		$this->title_en = $data['title_en'];
		$this->title_nl = $data['title_nl'];
		$this->technique = $data['technique'];
		$this->width = $data['width'];
		$this->height = $data['height'];
		
		if ($showForm == TRUE) {
			$this->setContentFile(GZ::TEMPLATES.'/admin/resource-filter-form.php');
			parent::renderPage();
			return;
		}
		
		$this->setContentFile(GZ::TEMPLATES.'/admin/resource-list.php');
		$query = ['', $this->visart, $this->activity, $this->category, $this->year];
		$search = new Search($data);
		if ($search->isRelevant()) {
			$callback = [$search, 'inspect'];
		} else {
			$callback = NULL;
		}
		$this->results = SiteResources::get()->listResources($query, $callback);
		$maxResult = 0;
		$this->results = array_filter($this->results, function($a) use (&$maxResult) {
			$maxResult = max($maxResult, $a[0]);
			return $a[0] > 1;
		});
		usort($this->results, function($a, $b) {
			return $b[0] <=> $a[0];
		});
		$this->itemCount = count($this->results);
		$this->resultFactor = 100;
		if ($maxResult > 100) {
			$this->resultFactor = $maxResult;
		}
		
		$this->pager = new Pager($this->start, $this->itemsPerPage, $this->itemCount, $this->action);
		$this->pager->setTemplate(Pager::AJAX_TEMPLATE);
		
		parent::renderPage();
	}
	
	protected function getPageResources() {
		if ($this->start >= $this->itemCount) {
			return [];
		} else {
			return array_slice($this->results, $this->start, $this->itemsPerPage);
		}
	}  
	
// 	protected function renderFooter() {
// 		print_r(file_get_contents('php://input'));
// 	}
	
}

