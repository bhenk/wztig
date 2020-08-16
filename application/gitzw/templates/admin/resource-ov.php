<?php
namespace gitzw\templates\admin;

/** @var mixed $this */

use gitzw\site\data\ImageData;

const SMALL_IMG_WIDTH = 100;
const SMALL_IMG_HEIGHT = 150;

?>
<div class="img-data-container">
	<div class="img-container">
		<a href="/admin/edit-resource/<?php echo $this->getLongId(); ?>">
    	<?php 
    	$representation = $this->getRepresentation();
    	if (isset($representation)) {
    		$location = $representation->getLocation();
    	} else {
    		$location = '';
    	}
    	$ida = new ImageData(NULL, $location);
    	echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $this->getLongId(), 'maxheight');
	    ?>
	    </a>
	</div>
	<div class="img_data">
		
		<div class="table-data">
			<div>
				<div>&nbsp;</div><div><?php echo $this->getDisplayTitle(); ?></div>
			</div>
			<div>
				<div>m</div><div><?php echo $this->getTechnique(); ?></div>
			</div>
			<div>
				<div>d</div><div><?php echo $this->getDimensions(); ?></div>
			</div>
			<div>
				<div>t</div><div><?php echo $this->getDate(); ?></div>
			</div>
		</div>
	</div>
</div>
