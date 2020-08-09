<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\data\Site;
use gitzw\site\model\SiteResources;

/**
 * 
 */
class LocateImagePageControl extends DefaultPageControl {
	
	private $path = array();
	
	protected $var;
	protected $representation;
	protected $imageFile;
	protected $action;
	
	protected $locate = 'new_resource';
	protected $submitType = '';
	protected $existingId = '';
	
	protected $subject = '';
	protected $category = '';
	protected $year = '';
	
	protected $sub = NULL;
	protected $cat = NULL;
	protected $yea = NULL;
	
	protected $existingIdError = FALSE;
	protected $subjectError = FALSE;
	protected $categoryError = FALSE;
	protected $yearError = FALSE;
	
	protected $msg = '';
	
	function __construct(array $path) {
		$this->path = $path;
		$this->setContentFile(GZ::TEMPLATES.'/admin/locate-image.php');
		$this->setMenuManager(new AdminMenuManager());
		$this->addStylesheet('/css/form.css');
		
		$this->var = SiteResources::getSite()->getChildByName('var')->getChildByName($path[3]);
		//$this->var->loadChildren();
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
		// also at GET
		$this->sub = NULL;
		$this->subject = '';
		$this->cat = NULL;
		$this->category = '';
		$this->yea = NULL;
		$this->year = '';
		
		$this->sub = $this->var->getChildByPosition(0);
		if (isset($this->sub)) {
			$this->subject = $this->sub->getName();
			$this->cat = $this->sub->getChildByPosition(0);
			if (isset($this->cat)) {
				$this->category = $this->cat->getName();
				$this->yea = $this->cat->getChildByPosition(0);
				if (isset($this->yea)) {
					$this->year = $this->yea->getName();
				}
			}
		}
		return TRUE;
	}
	
	private function handleLevel1() {
		// subject is set
		$this->cat = NULL;
		$this->category = '';
		$this->yea = NULL;
		$this->year = '';
		
		$this->cat = $this->sub->getChildByPosition(0);
		if (isset($this->cat)) {
			$this->category = $this->cat->getName();
			$this->yea = $this->cat->getChildByPosition(0);
			if (isset($this->yea)) {
				$this->year = $this->yea->getName();
			}
		}
	}
	
	private function handleLevel2() {
		// category is set
		$this->yea = NULL;
		$this->year = '';
		
		$this->yea = $this->cat->getChildByPosition(0);
		if (isset($this->yea)) {
			$this->year = $this->yea->getName();
		}
	}
	
	private function handlePost() : bool {
		$this->submitType = $_POST['submit_type'] ?? '';
		$this->locate = $_POST['locate'] ?? '';
		$this->existingId = $_POST['exist_id'] ?? '';
		$this->subject = $_POST['subject'] ?? '';
		$this->category = $_POST['category'] ?? '';
		$this->year = $_POST['year'] ?? '';
		if ($this->submitType == 'script') {
			return $this->handleScript();
		} else {
			return $this->handleButton();
		}
	}
	
	private function handleScript() : bool {
		if ($this->locate == 'existing') {
			return $this->handleScriptExistingId();
		} else {
			return $this->handleScriptNewId();
		}
	}
	
	private function handleScriptExistingId() : bool {
		return TRUE;
	}
	
	private function handleScriptNewId() : bool {
		$this->sub = $this->var->getChildByName($this->subject);
		if (is_null($this->sub)) {
			$this->handleGet();
		} else {
			$this->subject = $this->sub->getName();
			$this->cat = $this->sub->getChildByName($this->category);
			if (is_null($this->cat)) {
				$this->handleLevel1();
			} else {
				$this->category = $this->cat->getName();
				$this->yea = $this->cat->getChildByName($this->year);
				if (is_null($this->yea)) {
					$this->handleLevel2();
				} else {
					$this->year = $this->yea->getName();
				}
			}			
		}
		return TRUE;
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
			echo '@Todo check if resource id is correct';
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
		if (empty($this->subject)) {
			$this->subjectError = TRUE;
			$this->msg = 'Please fill in an existing subject';
		}
		if ($this->yearError or $this->categoryError or $this->subjectError) {
			return TRUE;
		} else {
			echo '@Todo assign resource id and redirect to edit resource page';
			$yea = $this->var->getDescendant([$this->subject, $this->category, $this->year]);
			$resource = $yea->addResource();
			$resource->addRepresentation($this->representation);
			$resourceId = $resource->getLongId();
			Site::get()->redirect('/admin/edit-resource/'.$resourceId);
			return FALSE;
		}
	}
}

