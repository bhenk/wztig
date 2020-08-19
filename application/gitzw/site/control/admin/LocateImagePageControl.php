<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\data\Site;
use gitzw\site\model\SiteResources;
use gitzw\site\data\Security;

/**
 * 
 */
class LocateImagePageControl extends DefaultPageControl {
	
	private $path = array();
	
	protected $representation;
	protected $imageFile;
	protected $action;
	
	protected $locate = 'new_resource';
	protected $submitType = '';
	protected $existingId = '';
	
	protected $visart = '';
	protected $activity = '';
	protected $category = '';
	protected $year = '';
	
// 	protected $sub = NULL;
// 	protected $cat = NULL;
// 	protected $yea = NULL;
	
	protected $existingIdError = FALSE;
	protected $visartError = FALSE;
	protected $activityError = FALSE;
	protected $categoryError = FALSE;
	protected $yearError = FALSE;
	
	protected $msg = '';
	
	function __construct(array $path) {
		$this->path = $path;
		$this->setContentFile(GZ::TEMPLATES.'/admin/locate-image.php');
		$this->setMenuManager(new AdminMenuManager());
		$this->addStylesheet('/css/form.css');
		
		//$this->var = SiteResources::get()->getChildByName('var')->getChildByName($path[3]);
		$this->representation = implode('/', array_slice($path, 3));
		$this->imgFile = GZ::DATA.'/images/'.$this->representation;
		$this->action = '/'.implode('/', array_slice($path, 1));
	}
	
	public function renderPage() {
		$renderPage = FALSE;
		if (Site::get()->requestMethod() == 'GET') {
			$renderPage = $this->handleGet();
		} elseif (Site::get()->requestMethod() == 'POST') {
			$renderPage = $this->handlePost();
		}
		if ($renderPage) {
			parent::renderPage();
		}
	}
	
	private function handleGet() : bool {
		return TRUE;
	}
	
	private function handlePost() : bool {
		if (empty($_POST)) {
			$input = json_decode(file_get_contents('php://input'), true);
			if ($input['reason'] == 'select_changed') {
				$this->handleSelectChanged(Security::cleanInput($input));
				return false;
			}
			$data = Security::cleanInput($input);
		} else {
			$data = Security::cleanInput($_POST);
		}
		$this->locate = $data['locate'] ?? '';
		$this->existingId = $data['exist_id'] ?? '';
		$this->visart = $data['visart'];
		$this->activity = $data['activity'] ?? '';
		$this->category = $data['category'] ?? '';
		$this->year = $data['year'] ?? '';
		return $this->handleButton();
	}
	
	private function handleSelectChanged(array $data) {
		$tree = SiteResources::get()->getTree($data, false);
		header('Content-Type: application/json');
		echo json_encode($tree);
	}
	
	private function handleButton() : bool {
		if ($this->locate == 'existing') {
			return $this->handleButtonExistingId();
		} else {
			return $this->handleButtonNewId();
		}
	}
	
	private function handleButtonExistingId() : bool {
		if (empty ($this->existingId)) {
			$this->existingIdError = TRUE;
			$this->msg = 'Please fill in an existing resource id';
			return TRUE;
		} else {
			echo '<script>alert("@Todo check if resource id is correct");';
			return TRUE;
		}
	}
	
	private function handleButtonNewId() : bool {
		if (empty($this->year)) {
			$this->yearError = TRUE;
			$this->msg = 'Please fill in an existing year';
		}
		if (empty($this->category)) {
			$this->categoryError = TRUE;
			$this->msg = 'Please fill in an existing category';
		}
		if (empty($this->activity)) {
			$this->activityError = TRUE;
			$this->msg = 'Please fill in an existing activity';
		}
		if (empty($this->visart)) {
			$this->visartError = TRUE;
			$this->msg = 'Please fill in an existing name';
		}
		if ($this->yearError or $this->categoryError or $this->activityError or $this->visartError) {
			return TRUE;
		} else {
			$yea = SiteResources::get()->getDescendant(['var', $this->visart, $this->activity, $this->category, $this->year]);
			$resource = $yea->addResource();
			$resource->addRepresentation($this->representation);
			$resourceId = $resource->getLongId();
			Site::get()->redirect('/admin/edit-resource/'.$resourceId);
			return FALSE;
		}
	}
	
	/**
	 * Called by template upon first load.
	 * @return string
	 */
	protected function getJsonForSelects() : string {
		return json_encode(SiteResources::get()->getTree(null, false));
	}
}

