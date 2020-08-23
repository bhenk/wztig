<?php
namespace gitzw\templates\visar;

/** @var mixed $this */

use gitzw\site\data\ImageData;


const SMALL_IMG_WIDTH = 350;
const SMALL_IMG_HEIGHT = 350;

$representation = $this->getRepresentation();
if (isset($representation)) {
	$location = $representation->getLocation();
} else {
	$location = '';
}
$ida = new ImageData(NULL, $location);

?>
<div class="img-container">
<a href="<?php echo $this->getResourcePath(); ?>">
<?php  
echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $this->getLongId(), 'maxheight');
?>
</a>
</div>
