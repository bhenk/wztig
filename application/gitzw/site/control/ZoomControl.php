<?php

namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\data\ImageData;
use gitzw\site\model\NotFoundException;

/**
 * Takes the data id of an image and creates a zoom page.
 */
class ZoomControl {
	
	const IMG_WIDTH = 4500;
	const IMG_HEIGHT = 4500;
	
	protected $imgId;
	private ImageData $ida;
	private $maxHeight = self::IMG_HEIGHT;
	
	function __construct(array $path) {
		$this->imgId = implode('/', array_slice($path, 2));
		$this->ida = new ImageData(null, $this->imgId);
		if (!$this->ida->imgExists()) {
			throw new NotFoundException('image does not exists');
		}
		$h = $this->ida->getSize()['height'];
		if ($h < $this->maxHeight) {
			$this->maxHeight = $h;
		}
		ini_set('memory_limit','512M');
	}
	
	public function renderPage() {
		require_once GZ::TEMPLATES.'/frame/zoom.php';
	}
	
	protected function getTitle() : string {
		return 'zoom '.$this->imgId;
	}
	
	protected function getLocationData() {
		return $this->ida->resize(self::IMG_WIDTH, $this->maxHeight, 'maxheight');
	}
	
}

