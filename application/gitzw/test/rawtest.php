<?php namespace gitzw\test;







//use gitzwart\GZ;

require_once __DIR__.'/../GZ.php';

$a = ['a'=>'z/b/c', 'b'=>'b/d/x', 'c'=>'c/p/o'];
$b = array_values($a);

usort($b, function($x, $y) {
	echo $x.PHP_EOL;
	return $x <=> $y;
});

var_dump($a);
var_dump($b);
