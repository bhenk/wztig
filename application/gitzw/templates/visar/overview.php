<?php
namespace gitzw\templates\visar;

/** @var mixed $this */

echo $this->start;

$pr = $this->getPageResources();
echo "<br/>";

//var_dump($pr);
?>
<h1>foo bar</h1>

<?php 
if ($pr) {
	foreach ($pr as $resource) { ?>
		<div><?php echo $resource->getLongId(); ?></div>
	<?php } 
} ?>
