<?php
namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\data\Security;
use gitzw\site\data\Site;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\SiteResources;


class MoveResourcePageControl  extends DefaultPageControl {
	
	protected $resource;
	protected $action;
	
	protected $visart = '';
	protected $activity = '';
	protected $category = '';
	protected $year = '';
	
	protected $visartError = FALSE;
	protected $activityError = FALSE;
	protected $categoryError = FALSE;
	protected $yearError = FALSE;
	
	protected $msg = '';
	
	private $fromEditPage;
	
	
	function __construct(array $path, bool $fromEditPage = false) {
		$resourceId = $path[3]; // hnq.work.draw.2020.0002
		if (is_null($resourceId)) {
			throw new NotFoundException('unknown resource');
		}
		$group = SiteResources::get()->getChildByName('var');
		$this->resource = $group->getResource($resourceId);
		if (is_null($this->resource)) {
			throw new NotFoundException('Unknown resource id: '.$resourceId);
		}
		$this->fromEditPage = $fromEditPage;
		
		$this->setTemplate(self::COLUMN_3);
		$this->setContentFile(GZ::TEMPLATES.'/admin/move-resource.php');
		$this->setMenuManager(new AdminMenuManager());
		$this->addStylesheet('/css/form.css');
		
		$this->action = '/admin/move-resource/'.$this->resource->getLongId();
	}
	
	public function renderPage() {
		$renderPage = false;
		if ($this->fromEditPage) {
			$renderPage = true;
		} else {
			$renderPage = $this->handlePost();
		}
		if ($renderPage) {
			parent::renderPage();
		}
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
			$currentContainer = $this->resource->getParent();
			$newContainer = SiteResources::get()->getDescendant(['var', $this->visart, $this->activity, $this->category, $this->year]);
			$currentContainer->removeResource($this->resource->getId());
			$resource = $newContainer->addExistingResource($this->resource);
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
		$data = [];
		$values = explode('.', $this->resource->getLongId());
		$data['visart'] = $values[0];
		$data['activity'] = $values[1];
		$data['category'] = $values[2];
		$data['year'] = $values[3];
		return json_encode(SiteResources::get()->getTree($data, false));
	}
}

