<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\menu\Pager;
use gitzw\site\data\Site;
use gitzw\site\model\SiteResources;

class ResourceListPageControl extends DefaultPageControl {
	
	protected string $action;
	protected $visart = 'all';
	protected $activity = 'all';
	protected $category = 'all';
	protected $year = 'all';
	
	protected $resources = array();
	
	protected $pager;
	protected $itemCount;
	private int $start = 0;
	private $itemsPerPage = 4;
	
	function __construct(array $path) {
		$this->setMenuManager(new AdminMenuManager('/admin/list-resources'));
		$this->addStylesheet('/css/form.css');
		$this->action = '/'.implode('/', array_slice($path, 1));
	}
	
	public function renderPage() {
		if (Site::get()->requestMethod() == 'GET') {
			$this->setContentFile(GZ::TEMPLATES.'/admin/resource-filter-form.php');
			parent::renderPage();
		} else {
			$this->setContentFile(GZ::TEMPLATES.'/admin/resource-list.php');
			$this->handlePost();
		}
	}
	
	private function handlePost() {
		if (empty($_POST)) {
			$input = json_decode(file_get_contents('php://input'), true);
			$data = $input['payload'];
			$paging = $input['paging'];
			$this->start = max(0, intval($paging['start']));
		} else {
			$data = $_POST;
		}
		$this->visart = $data['visart'];
		$this->activity = $data['activity'];
		$this->category = $data['category'];
		$this->year = $data['year'];
		
		$query = ['', $this->visart, $this->activity, $this->category, $this->year];
		$this->resources = SiteResources::get()->listResources($query);
		$this->itemCount = count($this->resources);
		
		$this->pager = new Pager($this->start, $this->itemsPerPage, $this->itemCount, $this->action);
		$this->pager->setTemplate(Pager::AJAX_TEMPLATE);
		
		parent::renderPage();
	}
	
	protected function getPageResources() {
		if ($this->start >= $this->itemCount) {
			return [];
		} else {
			return array_slice($this->resources, $this->start, $this->itemsPerPage);
		}
	}    
	
}

