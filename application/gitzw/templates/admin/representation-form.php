<?php
namespace gitzw\templates\admin;

/** @var mixed $this */

use gitzw\site\data\ImageData;

const SMALL_IMG_WIDTH = 180;
const SMALL_IMG_HEIGHT = 180;

?>
<hr/>
<div class="formrw">
	<div class="img-data-container">
		<div class="img-container">
	    	<?php $ida = new ImageData(NULL, $this->getLocation());
	    	echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $this->getLocation(), 'maxheight');?>
		</div>
		<div class="img_data2">
			
			<div class="smallformrow"><?php echo $this->getLocation(); ?></div>
			<div class="smallformrow">type: <?php echo $ida->getMediaType(); ?></div>
			<div class="smallformrow">size: <?php echo $ida->getSize()['width']; ?> x <?php echo $ida->getSize()['height']; ?> px.</div>
			
			<div class="smallformrow">
				<input type="radio" id="<?php echo $this->getName().'1'; ?>" name="preferred_representation" 
					value="<?php echo $this->getLocation(); ?>"
					<?php echo $this->getPreferred() ? 'checked="checked"' : ''; ?>>
				<label for="<?php echo $this->getName().'1'; ?>">Preferred</label>
			</div>
			
			<div class="smallformrow">
				<input type="checkbox" id="<?php echo $this->getName().'2'; ?>" 
					name="<?php echo $this->getName().'+hidden'; ?>" value="hidden" 
					<?php echo $this->getHidden() ? ' checked' : ''; ?>>
	  			<label for="<?php echo $this->getName().'2'; ?>">Hidden</label>
	  		</div>
	  		
	  		<div class="smallformrow">
				<label for="<?php echo $this->getName().'0'; ?>">Ordinal</label>
				<input type="number" id="<?php echo $this->getName().'0'; ?>" 
					name="<?php echo $this->getName().'+ordinal'?>" 
					value="<?php echo $this->getOrdinal(); ?>" 
					min="0" max="1000" step="1" 
					value="<?php echo $this->getOrdinal(); ?>" size="3">
			</div>
			
			
			<div class="smallformrow">
				<div class="twrap">
				<textarea id="<?php echo $this->getName().'3'?>"
					rows="3" 
					name="<?php echo $this->getName().'+desc'?>"
					><?php echo $this->getDescription(); ?></textarea>
				</div>
	  		</div>  
	  		
	  		<div class="smallformrow">
				<input type="checkbox" id="<?php echo $this->getName().'42'; ?>" 
					name="<?php echo $this->getName().'+remove'; ?>" value="remove">
	  			<label for="<?php echo $this->getName().'42'; ?>">Remove</label>
	  		</div>		
	  		
		</div>
	</div>
</div>