<?php
namespace gitzw\templates\views;

/** @var mixed $this */
use gitzw\GZ;
use gitzw\site\data\ImageData;

const SMALL_IMG_WIDTH = 100;
const SMALL_IMG_HEIGHT = 150;

?>
<div class="img-data-container">
	<div class="img-container">
		<a href="<?php echo $this->getResourcePath(); ?>">
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
				<div>t</div><div><?php echo $this->getDisplayTitle(); ?></div>
			</div>
			<div>
				<div>m</div><div><?php echo $this->getMedia(); ?></div>
			</div>
			<div>
				<div>s</div><div><?php echo $this->getDimensions(); ?></div>
			</div>
			<div>
				<div>d</div><div><?php echo $this->getDate(); ?></div>
			</div>
			<div>
				<div>h</div><div><?php echo $this->getHidden() ? 'hidden' : ''; ?></div>
			</div>
			<div>
				<div>f</div><div><?php echo $this->hasFrontPage() ? 'front page' : ''; ?></div>
			</div>
			<div>
				<div>i</div>
				<div>
					<span><?php echo $this->getLongId(); ?></span>
					<span title="copy id to clipboard" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
				</div>
			</div>
		</div>
	</div>
</div>
<script><?php require_once GZ::SCRIPTS.'/copy-previous.js'; ?></script>

