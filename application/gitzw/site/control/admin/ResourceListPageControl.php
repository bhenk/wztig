<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\control\menu\Pager;
use gitzw\site\data\Site;

class ResourceListPageControl extends DefaultPageControl {
	
	protected string $action;
	protected $visart = 'all';
	protected $subject = 'all';
	protected $category = 'all';
	protected $year = 'all';
	
	protected $pager;
	private int $start = 0;
	private $itemsPerPage = 4;
	private $itemCount = 106;
	private $seedString = 'nothing';
	
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
		$this->subject = $data['subject'];
		$this->category = $data['category'];
		$this->year = $data['year'];
		
		
		$this->pager = new Pager($this->start, $this->itemsPerPage, $this->itemCount, $this->action, $this->seedString);
		$this->pager->setTemplate(GZ::TEMPLATES.'/views/pager-ajax.php');
		
		parent::renderPage();
	}
	
	protected function renderFooter() {
		echo 'time='.time().'<br/>';
		echo 'visart='.$this->visart.'<br/>';
		echo 'year='.$this->year.'<br/>';
		echo 'start='.$this->start.'<br/>';
		print_r(file_get_contents('php://input'));
	}
	
	
}

