<?php namespace gitzw\test;

require_once __DIR__.'/../GZ.php';

// use gitzw\GZ;

$a = ['a', 'b', 'c'];

// $file = GZ::DATA.'/images//hnq/2020/_DSC0514_00019.jpg';

// $exif = exif_read_data($file, 0, TRUE);
// foreach ($exif as $key => $section) {
// 	foreach ($section as $name => $val) {
// 		echo "$key.$name: $val\n";
// 	}
// }
   
$b = array_slice($a, 2, 100);
var_dump($b);