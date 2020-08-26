<?php
namespace gitzw\templates\admin;

/** @var mixed $this */
use gitzw\site\data\ImageData;

const IMG_WIDTH = 500;
const IMG_HEIGHT = 500;

$url = "'".$this->action."'";
?>

<h1 class="gitzw">Locate image <small><?php echo $this->representation; ?></small></h1>


<div class="img-data-container">
	<div class="img-container">
		<?php 
		$id = new ImageData($this->imgFile);
		echo $id->getImgTag(IMG_WIDTH, IMG_HEIGHT, $this->representation, 'maxheight');
		?>
	</div>

	<div class="img_data">
		<div class="container">
		  <form id="location" action="<?php echo $this->action; ?>" method="post">
		  	<fieldset>
		  	 	<input type="radio" id="existing" onclick="setExisting(true)" name="locate" value="existing"
		  	 		<?php echo $this->locate == 'existing' ? 'checked="checked"' : ''; ?>>
		  	 	<label for="existing">Existing resource</label>
			    <div class="formrw">
			      <div class="form-25">
			        <label<?php echo $this->existingIdError ? ' class="error"' : ''; ?> for="exist_id">resource id</label>
			      </div>
			      <div class="form-75">
			        <input type="text" id="exist_id" name="exist_id" 
			        	placeholder="name.work...">
			      </div>		      
			    </div>
		    </fieldset>
			
			<!-- New resource -->
		    <fieldset>
		  	 	<input type="radio" id="new_resource" onclick="setExisting(false)" name="locate" value="new_resource" 
		  	 		<?php echo $this->locate == 'new_resource' ? 'checked="checked"' : ''; ?>>
		  	 	<label for="new_resource">New resource</label>
		  	 	
		  	 	<div class="formrw">
					<div class="form-25">
						<label<?php echo $this->visartError ? ' class="error"' : ''; ?> for="visart">Names</label>
					</div>
					<div class="form-75">
						<select class="mediuminput" id="visart" name="visart" onchange="updateForm(<?php echo $url; ?>)"></select>
					</div>
				</div>
				
				<div class="formrw">
					<div class="form-25">
						<label<?php echo $this->activityError ? ' class="error"' : ''; ?> for="activity">Activity</label>
					</div>
					<div class="form-75">
						<select class="mediuminput" id="activity" name="activity" onchange="updateForm(<?php echo $url; ?>)"> </select>
					</div>
				</div>
				
				<div class="formrw">
					<div class="form-25">
						<label<?php echo $this->categoryError ? ' class="error"' : ''; ?> for="category">Category</label>
					</div>
					<div class="form-75">
						<select class="mediuminput" id="category" name="category" onchange="updateForm(<?php echo $url; ?>)"></select>
					</div>
				</div>
				
				<div class="formrw">
					<div class="form-25">
						<label<?php echo $this->yearError ? ' class="error"' : ''; ?> for="year">Year</label>
					</div>
					<div class="form-75">
						<select class="mediuminput" id="year" name="year"></select>
					</div>
				</div>
			    
			   </fieldset>
			   
			<div class=formrw><label><?php echo $this->msg; ?></label></div>
		    <div class="formrw">
		      <input type="submit" value="Next &#9656;">
		    </div>
		    
		  </form>
		  
		</div>
	</div>
	
</div>

<script>
function setExisting(state) {
	document.getElementById('exist_id').disabled = !state;
	document.getElementById('visart').disabled = state;
	document.getElementById('activity').disabled = state;
	document.getElementById('category').disabled = state;
	document.getElementById('year').disabled = state;
}

window.onload = setSelectors('<?php echo $this->getJsonForSelects(); ?>');
</script>

