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

<div class="license">
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank">
		<img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" />
	</a>
</div>