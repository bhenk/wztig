<?php namespace gitzw\test;





use gitzw\GZ;
use gitzw\site\data\ImageData;
use gitzw\site\data\Site;

//use gitzwart\GZ;

require_once __DIR__.'/../GZ.php';

Site::reset(new class() extends Site {
	public function documentRoot() : string {
		return GZ::ROOT.DIRECTORY_SEPARATOR.'test-data/public_html';
	}
});

$imgFile = GZ::DATA.'/images/hnq/2020/_DSC0429_00006.jpg';

$id = new ImageData($imgFile);
echo $id->getFilename('_f');