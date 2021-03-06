<?php
namespace gitzw\templates\admin;

/** @var mixed $this */

use gitzw\site\data\ImageData;
use gitzw\site\model\Resource;
use gitzw\GZ;

const IMG_WIDTH = 500;
const IMG_HEIGHT = 500;

?>

<h1 class="gitzw">Edit resource <small><?php echo $this->longId; ?></small>
	<span title="copy longId to clipboard" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
</h1>

<div class="img-data-container">
	<div class="img-container">
		<?php 
		$representation = $this->resource->getRepresentation();
		$dataImg = is_null($representation) ? '' : $representation->getLocation();
		$idata = new ImageData(NULL, $dataImg);
		echo $idata->getImgTag(IMG_WIDTH, IMG_HEIGHT, 'representation of '.$this->longId, 'maxheight');
		?>
	</div>
	<!-- pseude element .img_data:before -->
	<div class="img_data">
		<div class="container">
		  <form action="<?php echo $this->action; ?>" method="post">
		  	
		    <div class="formrw">
		      <div class="form-25">
		      	<input type="radio" id="nl_title" name="preferred_language" value="nl"
		      	<?php echo $this->resource->getPreferredLanguage() == 'nl' ? 'checked="checked"' : ''; ?>>
		        <label for="nl_title">nl&nbsp;</label>
		        <label for="title_nl">Title</label>
		      </div>
		      <div class="form-75">
		        <input type="text" id="title_nl" name="title_nl" 
		        	value="<?php echo $this->resource->getTitles()['nl'] ?? ''; ?>" placeholder="Titel..">
		      </div>		      
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		      	<input type="radio" id="en_title" name="preferred_language" value="en"
		      	<?php echo $this->resource->getPreferredLanguage() == 'en' ? 'checked="checked"' : ''; ?>>
		        <label for="en_title">en</label>
		        <label for="title_en">Title</label>
		      </div>
		      <div class="form-75">
		        <input type="text" id="title_en" name="title_en" 
		        	value="<?php echo $this->resource->getTitles()['en'] ?? ''; ?>" placeholder="Title..">
		      </div>		      
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label for="media">Media</label>
		      </div>
		      <div class="form-75">
		        <input type="text" id="media" name="media" 
		        	value="<?php echo $this->resource->getMedia(); ?>" placeholder="Media..">
		      </div>
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label for="width">Width</label>
		      </div>
		      <div class="form-75">
		        <input class="smallinput" type="text" id="width" name="width" 
		        	value="<?php echo $this->resource->getWidth(); ?>" placeholder="Width..">
		      </div>
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label for="width">Height</label>
		      </div>
		      <div class="form-75">
		        <input class="smallinput" type="text" id="height" name="height" 
		        	value="<?php echo $this->resource->getHeight(); ?>" placeholder="Height..">
		      </div>
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label for="width">Depth</label>
		      </div>
		      <div class="form-75">
		        <input class="smallinput" type="text" id="depth" name="depth" 
		        	value="<?php echo $this->resource->getDepth(); ?>" placeholder="Depth..">
		      </div>
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label for="date">Date</label>
		      </div>
		      <div class="form-75">
		        <input class="smallinput" type="text" id="date" name="date" 
		        	value="<?php echo $this->resource->getDate(); ?>" placeholder="Creation date..">
		      </div>
		    </div>
		    
		    <div class="formrw">
		    	<div class="form-25">
		    		<label for="ordinal">Ordinal</label>
		    	</div>
		    	<div class="form-75">
		    		<input type="number" id="ordinal" name="ordinal"
		    			value="<?php echo $this->resource->getOrdinal(); ?>" 
		    			min="-1" max="1000" step="1" size="3">
		    	</div>
		    </div>
		    
		    <div class="formrw">
		    	<input type="checkbox" id="rhidden" 
					name="rhidden" value="rhidden" 
					<?php echo $this->resource->getHidden() ? ' checked' : ''; ?>>
		    	<label for="hidden">Hide resource</label>
		    </div>
		    
		    <div class="formrw">
		    	<input type="checkbox" id="rmove" name="rmove" value="rmove">
		    	<label for="hidden">Move resource</label>
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label><b>Structured data</b></label>
		      </div>
		    </div>
		    
		    <div class="formrw">
		    	<div class="form-25">
		    		<label for="sdadditionaltype1">SD Additional Type</label>
		    	</div>
		    	<div class="form-75">
		    		<select class="mediuminput" id="sdadditionaltype1" name="sdadditionaltype1">
		    			 <?php foreach (array_keys(Resource::ADDITIONAL_TYPES) as $key) { ?>
		    			 	<option value="<?php echo $key; ?>"
		    			 	<?php echo $key == $this->resource->getSdAdditionalTypes()[0] ? 'selected' : ''; ?>>
		    			 	<?php echo $key; ?></option>
		    			 <?php } ?>
		    		</select>
		    	</div>
		    </div>
		    
		    <div class="formrw">
		    	<div class="form-25">
		    		<label for="sdadditionaltype2">SD Additional Type</label>
		    	</div>
		    	<div class="form-75">
		    		<select class="mediuminput" id="sdadditionaltype2" name="sdadditionaltype2">
		    			 <?php foreach (array_keys(Resource::ADDITIONAL_TYPES) as $key) { ?>
		    			 	<option value="<?php echo $key; ?>"
		    			 	<?php echo $key == $this->resource->getSdAdditionalTypes()[1] ? 'selected' : ''; ?>>
		    			 	<?php echo $key; ?></option>
		    			 <?php } ?>
		    		</select>
		    	</div>
		    </div>
		    
		    <!-- div class="formrw">
		    	<div class="form-25">
		    		<label for="sdmaterial">SD material</label>
		    	</div>
		    	<div class="form-75">
		    		<input type="text" id="sdmaterial" name="sdmaterial" 
		        		value="<?php echo $this->resource->getSdMaterial(); ?>" 
		        		placeholder="Oil, Acrylic, Pencil, DryPoint, Mixed Media, Watercolour, Lithograph, Pastel..">
		    	</div>
		    </div -->
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label><b>Representations</b></label>
		      </div>
		    </div>
		    
		    <?php foreach($this->resource->getRepresentations('ordinal') as $representation) { 
		    	$representation->render(GZ::TEMPLATES.'/admin/representation-form.php');
		    } ?>
		    
		    <hr/>
		    <div class="formrw">
		    	<div class="form-25">
		        	<label for="add_representation">+ repr..</label>
		      	</div>
		      	<div class="form-75">
			        <input type="text" id="add_representation" name="add_repr" 
			        	value="" placeholder="Add representation..">
			     </div>
		    </div>
		    
		    <div class="formrw">
		      <input type="submit" value="Submit">
		    </div>
		  </form>
		  <a class="incognito" href="<?php echo '/'.str_replace('.', '/', $this->resource->getLongId())?>">&#9654; <?php echo $this->resource->getLongId(); ?></a>
		</div>
	</div>
</div>

