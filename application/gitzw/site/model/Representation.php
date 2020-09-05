<?php

namespace gitzw\site\model;

use JsonSerializable;
use gitzw\site\data\Site;
use gitzw\GZ;

class Representation implements JsonSerializable, iViewRender {
	
	const KEY_ORDINAL = 'ordinal';
	const KEY_DESCRIPTION = 'description';
	const KEY_HIDDEN = 'hidden';
	const KEY_PREFERRED = 'preferred';
	
	private $location;
	private $ordinal = 0;
	private $description = '';
	private $hidden = FALSE;
	private $preferred = FALSE;
	private $parent;
	
	function __construct(Resource $parent, string $location, array $data=[]) {
		$this->parent = $parent;
		$this->location = $location;
		$this->ordinal = $data[self::KEY_ORDINAL] ?? 0;
		$this->description = $data[self::KEY_DESCRIPTION] ?? '';
		$this->hidden = $data[self::KEY_HIDDEN] ?? FALSE;
		$this->preferred = $data[self::KEY_PREFERRED] ?? FALSE;
	}
	
	public function jsonSerialize() {
		return [
			self::KEY_ORDINAL=>$this->ordinal,
			self::KEY_DESCRIPTION=>$this->description,
			self::KEY_HIDDEN=>$this->hidden,
			self::KEY_PREFERRED=>$this->preferred
		];
	}

	public function getLocation() : string {
		return $this->location;
	}
	
	public function getFullId() : string {
		return GZ::SD_PREFIX.'#img.'.str_replace('/', '.', $this->getLocation());
	}
	
	public function getDefaultURL(): string {
		// http://localhost:8080/img/derived/hnq/2020/_DSC0735_01_1200_1000_d.jpg
		$pi = pathinfo($this->location);
		return Site::get()->hostName().'/img/derived/'.$pi['dirname'].'/'.$pi['filename'].'_1200_1000_d.'.$pi['extension'];
	}

	public function getOrdinal() : int {
		return $this->ordinal;
	}

	public function getDescription() : string {
		return $this->description;
	}

	public function getHidden() : bool {
		return $this->hidden;
	}

	public function getPreferred() : bool {
		return $this->preferred;
	}

	public function getParent() : Resource {
		return $this->parent;
	}

	public function setOrdinal(int $ordinal) {
		$this->ordinal = $ordinal;
	}

	public function setDescription(string $description) {
		$this->description = $description;
	}

	public function setHidden(bool $hidden) {
		$this->hidden = $hidden;
	}

	public function setPreferred(bool $preferred) {
		$this->preferred = $preferred;
	}
	public function render(string $template, array $args=NULL) {
		require $template;
	}

	public function getName() {
		return str_replace('.', ';', $this->location);
	}
	
	public function getLocationAsPath() {
		return str_replace('/', '+', $this->location);
	}
	
	public function getVar() : Visart {
		return $this->getParent()->getParent()->getParentByNature('var');
	}
	
	public function isFrontPage() {
		$var = $this->getVar();
		if (!is_null($var)) {
			return $var->hasFrontpageImage($this->location);
		}
		return FALSE;
	}
	
	public function setfrontPage(bool $frontPage) {
		if ($this->isFrontPage() and !$frontPage) {
			$this->getVar()->removeFrontPageImage($this->getLocation());
		} elseif (!$this->isFrontPage() and $frontPage) {
			$this->getVar()->addFrontPageImage($this->location, $this->parent->getLongId());
		}
	}

	
}

