<?php
namespace gitzw\templates\visar;

/** @var mixed $this */
use gitzw\GZ;

?>

<div class="left-right-arrows">
<a class="left-arrow"<?php echo $this->getLeftArrowStyle(); ?> href="<?php echo $this->getLeftArrowLink(); ?>">&nbsp;&#9664;&nbsp;</a>
<!-- ?php echo $this->getHeading(); ? -->
<a class="right-arrow"<?php echo $this->getRightArrowStyle(); ?> href="<?php echo $this->getRightArrowLink(); ?>">&nbsp;&#9654;&nbsp;</a>
</div>

<?php 
$pr = $this->getPageResources();
if ($pr) {
	foreach ($pr as $resource) {
		$resource->render(GZ::TEMPLATES.'/visar/resource-heap.php');
	 } 
} ?>


<div class="left-right-arrows">
<a class="left-arrow"<?php echo $this->getLeftArrowStyle(); ?> href="<?php echo $this->getLeftArrowLink(); ?>">&nbsp;&#9664;&nbsp;</a>
<!-- ?php echo $this->getHeading(); ? -->
<a class="right-arrow"<?php echo $this->getRightArrowStyle(); ?> href="<?php echo $this->getRightArrowLink(); ?>">&nbsp;&#9654;&nbsp;</a>
</div>
