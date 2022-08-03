<?php

namespace gitzw\site\control\admin;

use gitzw\GZ;
use gitzw\site\control\DefaultPageControl;
#use gitzw\site\data\Security;
use gitzw\site\data\Site;
use gitzw\site\model\NotFoundException;
use gitzw\site\model\SiteResources;

class EditResourcePageControl extends DefaultPageControl {
	
	protected $resource;
	protected $longId;
	protected $action;
	
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
		$this->setPath($path);
	}
	
	public function renderPage() {
		if (Site::get()->requestMethod() == 'POST') {
			$this->handlePost();
		} else {
			parent::renderPage();
		}
	}
	
	private function handlePost() {
		$data = $_POST; #Security::cleanInput($_POST);
		$this->resource->setTitle($data['title_nl'], 'nl');
		$this->resource->setTitle($data['title_en'], 'en');
		$this->resource->setPreferredLanguage($data['preferred_language']);
		$this->resource->setMedia($data['media']);
		$w = floatval($data['width']);
		$this->resource->setWidth($w <= 0 ? -1 : $w);
		$h = floatval($data['height']);
		$this->resource->setHeight($h <= 0 ? -1 : $h);
		$d = floatval($data['depth']);
		$this->resource->setDepth($d <= 0 ? -1 : $d);
		$this->resource->setDate($data['date']);
		$this->resource->setHidden($data['rhidden'] == 'rhidden');
		$o = intval($data['ordinal']);
		$this->resource->setOrdinal($o <= 0 ? -1 : $o);
		foreach (array_values($this->resource->getRepresentations()) as $rep) {
			$rep->setPreferred($data['preferred_representation'] == $rep->getLocation());
			$rep->setOrdinal($data[$rep->getName().'+ordinal'] ?? 0);
			$rep->setFrontPage($data[$rep->getName().'+frontpage'] == 'frontpage');
			$rep->setHidden($data[$rep->getName().'+hidden'] == 'hidden');
			$rep->setDescription($data[$rep->getName().'+desc']);
			if ($data[$rep->getName().'+remove'] == 'remove') {
				$this->resource->removeRepresentation($rep->getLocation());
			}		
		}
		$addTypes = [];
		$addTypes[] = $data['sdadditionaltype1'] == 'None' ? null : $data['sdadditionaltype1'];
		$addTypes[] = $data['sdadditionaltype2'] == 'None' ? null : $data['sdadditionaltype2'];
		$this->resource->setSdAdditionalTypes(array_filter($addTypes));
		
		$addRepr = $data['add_repr'] ?? '';
		if (!empty($addRepr) and file_exists(GZ::DATA.'/images/'.$addRepr)) {
			$this->resource->addRepresentation($addRepr);
		} elseif (!empty($addRepr) and !file_exists(GZ::DATA.'/images/'.$addRepr)) {
			echo '<script>alert("the file '.GZ::DATA.'/images/'.$addRepr.' does not exist")</script>';
		}
		
		$this->resource->getParent()->persist();
		
		if ($data['rmove'] == 'rmove') {
			(new MoveResourcePageControl($this->path, true))->renderPage();
			return;
		}
		parent::renderPage();
	}
	
}

