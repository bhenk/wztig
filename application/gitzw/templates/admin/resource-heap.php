<?php
namespace gitzw\templates\admin;
/**
 * resource-heap.php called from
 * 		templates/visar/overview2.php
 * 
 * for updating ordinal of resources.
 */
/** @var mixed $this */

use gitzw\site\data\ImageData;
use gitzw\GZ;

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
		<a href="<?php echo $this->getResourcePath(); ?>"><?php  
			echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $this->getLongId(), 'maxheight');
			?></a>
		<div class="subscript"><?php echo $title; ?>&nbsp;</div>
	</div>
	<div class="ordinal">
		<input type="number" id="ordinal_<?php echo $this->getId(); ?>" 
			name="ordinal_<?php echo $this->getId() ?>"
			value="<?php echo $this->getOrdinal(); ?>"
			min="-1" max="1000" step="1" size="3"
			onchange="setOrdinal(this, '<?php echo $this->getId(); ?>')">
	</div>
</div>
<script><?php require_once GZ::SCRIPTS.'/resource-ordinal.min.js'; ?></script>


