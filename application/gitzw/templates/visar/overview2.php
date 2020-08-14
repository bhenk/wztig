<?php
namespace gitzw\templates\visar;

/** @var mixed $this */
use gitzw\GZ;

?>

<?php 
$pr = $this->getPageResources();
if ($pr) {
	foreach ($pr as $resource) {
		$resource->render(GZ::TEMPLATES.'/visar/resource-heap.php');
	 } 
} 
$this->getPager()->render();
?>