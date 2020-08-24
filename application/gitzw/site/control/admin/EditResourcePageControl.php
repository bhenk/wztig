<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
use gitzw\site\data\Site;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\Resource;
use gitzw\site\model\SiteResources;

class EditResourcePageControl extends DefaultPageControl {
	
	protected $resource;
	protected $longId;
	protected $action;
	private $path;
	
	function __construct(array $path) {
		$resourceId = $path[3]; // hnq.work.draw.2020.0002
		if (is_null($resourceId)) {
			throw new NotFoundException('unknown resource');
		}
		
		$this->setContentFile(GZ::TEMPLATES.'/admin/edit-resource.php');
		$this->setMenuManager(new AdminMenuManager());
		$this->addStylesheet('/css/form.css');
		
		$group = SiteResources::get()->getChildByName('var');
		$this->resource = $group->getResource($resourceId);
		if (is_null($this->resource)) {
			throw new NotFoundException('Unknown resource id: '.$resourceId);
		}
		$this->longId = $this->resource->getLongId();
		$this->action = '/'.implode('/', array_slice($path, 1));
		$this->path = $path;
	}
	
	private function getResource() : Resource {
		return $this->resource;
	}
	
	public function renderPage() {
		if (Site::get()->requestMethod() == 'POST') {
			$this->handlePost();
		} else {
			parent::renderPage();
		}
	}
	
	private function handlePost() {
		$this->getResource()->setTitle($_POST['title_nl'], 'nl');
		$this->getResource()->setTitle($_POST['title_en'], 'en');
		$this->getResource()->setPreferredLanguage($_POST['preferred_language']);
		$this->getResource()->setMedia($_POST['media']);
		$w = floatval($_POST['width']);
		$this->getResource()->setWidth($w <= 0 ? -1 : $w);
		$h = floatval($_POST['height']);
		$this->getResource()->setHeight($h <= 0 ? -1 : $h);
		$d = floatval($_POST['depth']);
		$this->getResource()->setDepth($d <= 0 ? -1 : $d);
		$this->getResource()->setDate($_POST['date']);
		$this->getResource()->setHidden($_POST['rhidden'] == 'rhidden');
		foreach (array_values($this->getResource()->getRepresentations()) as $rep) {
			$rep->setPreferred($_POST['preferred_representation'] == $rep->getLocation());
			$rep->setOrdinal($_POST[$rep->getName().'+ordinal'] ?? 0);
			$rep->setFrontPage($_POST[$rep->getName().'+frontpage'] == 'frontpage');
			$rep->setHidden($_POST[$rep->getName().'+hidden'] == 'hidden');
			$rep->setDescription($_POST[$rep->getName().'+desc']);
			if ($_POST[$rep->getName().'+remove'] == 'remove') {
				$this->getResource()->removeRepresentation($rep->getLocation());
			}
			
		}
		$addRepr = $_POST['add_repr'] ?? '';
		if (!empty($addRepr) and file_exists(GZ::DATA.'/images/'.$addRepr)) {
			$this->getResource()->addRepresentation($addRepr);
		} elseif (!empty($addRepr) and !file_exists(GZ::DATA.'/images/'.$addRepr)) {
			echo '<script>alert("the file '.GZ::DATA.'/images/'.$addRepr.' does not exist")</script>';
		}
		
		$this->getResource()->getParent()->persist();
		
		if ($_POST['rmove'] == 'rmove') {
			(new MoveResourcePageControl($this->path, true))->renderPage();
			return;
		}
		parent::renderPage();
	}
	
}

