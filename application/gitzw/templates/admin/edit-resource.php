<?php
namespace gitzw\templates\admin;

/** @var mixed $this */

use gitzw\site\data\ImageData;
use gitzw\GZ;

const IMG_WIDTH = 500;
const IMG_HEIGHT = 500;

?>

<h1 class="gitzw">Edit resource <small><?php echo $this->longId; ?></small></h1>

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
		        <label for="technique">Technique</label>
		      </div>
		      <div class="form-75">
		        <input type="text" id="technique" name="technique" 
		        	value="<?php echo $this->resource->getTechnique(); ?>" placeholder="Technique..">
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
		</div>
	</div>
</div>