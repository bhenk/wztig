<?php namespace gitzw\test;





use gitzw\site\model\ImageInspector;


//use gitzwart\GZ;

require_once __DIR__.'/../GZ.php';

$url='/admin/locate-image/hnq/2020/_DSC0533_00022.jpg?locate=new_resource&subject=work&year=2020';

var_dump(parse_url($url, PHP_URL_PATH));