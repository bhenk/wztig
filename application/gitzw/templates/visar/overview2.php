<?php
namespace gitzw\templates\visar;
/**
 * overview2.php called from
 * 		gitzw\site\control\visar\OverviewPageControl.php
 */
/** @var mixed $this */
use gitzw\GZ;

$template = GZ::TEMPLATES.'/visar/resource-heap.php';
if ($this->state == 'adm') $template = GZ::TEMPLATES.'/admin/resource-heap.php';

?>

<?php 
$pr = $this->getPageResources();
if ($pr) {
	foreach ($pr as $resource) {
		$resource->render($template);
	 } 
} 

$this->getPager()->render();
?>