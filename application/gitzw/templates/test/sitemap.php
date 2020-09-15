<?php
namespace gitzw\templates\test;

use gitzw\site\data\Site;

$sm = Site::get()->documentRoot().'/sitemap.xml';
ob_end_clean(); // see Gitz, ob_start([$this, 'sanitize_output']);
header("Content-type: application/xml");
header("Content-disposition: attachment; filename = sitemap.xml");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Content-Length: ' . filesize($sm));
readfile($sm);