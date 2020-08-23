<?php
namespace gitzw\templates\visar;

/** @var mixed $this */

use gitzw\site\data\ImageData;


const SMALL_IMG_WIDTH = 250;
const SMALL_IMG_HEIGHT = 250;

$representation = $this->getRepresentation();
if (isset($representation)) {
	$location = $representation->getLocation();
	$title = $representation->getParent()->getDisplayTitle();
} else {
	$location = '';
	$title = '';
}

$ida = new ImageData(NULL, $location);


?>
<div class="heap">
	<div class="img-container">
		<a href="<?php echo $this->getResourcePath(); ?>">
			<?php  
			echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $this->getLongId(), 'maxheight');
			?>
		</a>
		<div class="subscript"><?php echo $title; ?>&nbsp;</div>
	</div>
</div>
