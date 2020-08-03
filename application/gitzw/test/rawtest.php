<?php namespace gitzw\test;





use gitzw\site\model\ImageInspector;


//use gitzwart\GZ;

require_once __DIR__.'/../GZ.php';


$ii = new ImageInspector();
var_dump($ii->imageDiff());

// $it = new RecursiveDirectoryIterator(dirname(__DIR__));
// foreach (new RecursiveIteratorIterator($it) as $file) {
// 	if ($file->getExtension() == 'php') {
// 		echo substr($file.PHP_EOL, strlen('/Users/ecco/git2/wztig/application/'));
// 	}
// }

// $arr = array();
// $arr['foo'] = ['bar', 'baz'];
// var_dump($arr);

// var_dump(SiteResources::getSite()->getResourceImages());

// $stack = ['a', 'b', 'c'];
// array_push($stack, ['b', 'd']);
// var_dump($stack);