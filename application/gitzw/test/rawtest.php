<?php namespace gitzw\test;







//use gitzwart\GZ;

require_once __DIR__.'/../GZ.php';

$a = ['a/b/c', 'b/d/x', 'c/p/o'];

echo json_encode($a, JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
