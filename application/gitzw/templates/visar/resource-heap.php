<?php
namespace gitzw\templates\visar;

/** @var mixed $this */

use gitzw\site\data\ImageData;
use gitzw\site\data\Security;

const SMALL_IMG_WIDTH = 350;
const SMALL_IMG_HEIGHT = 350;

$representation = $this->getRepresentation();
if (isset($representation)) {
	$location = $representation->getLocation();
} else {
	$location = '';
}
$ida = new ImageData(NULL, $location);

$hasAccess = Security::get()->hasAccess()
?>
<div class="img-container">
<?php  
if ($hasAccess) {
	echo '<a href="/admin/edit-resource/'.$this->getLongId().'">';
}
echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $this->getLongId(), 'maxheight');
if ($hasAccess) {
	echo '</a>';
}
?>
</div>
