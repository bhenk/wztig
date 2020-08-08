<?php
namespace gitzw\templates\admin;

/** @var mixed $this */
use gitzw\site\data\ImageData;

const IMG_WIDTH = 500;
const IMG_HEIGHT = 500;

?>

<h1 class="gitzw">Locate image <small><?php echo $this->representation; ?></small></h1>


<div class="img-data-container">
	<div class="img-container">
		<?php 
		$id = new ImageData($this->imgFile);
		echo $id->getImgTag(IMG_WIDTH, IMG_HEIGHT, $this->representation, 'maxheight');
		?>
	</div>
	<!-- pseude element .img_data:before -->
	<div class="img_data">
		<div class="container">
		  <form id="location" action="<?php echo $this->action; ?>" method="post">
		  	<fieldset>
		  	 	<input type="radio" id="existing" onclick="setExisting(true, true)" name="locate" value="existing"
		  	 		<?php echo $this->locate == 'existing' ? 'checked="checked"' : ''; ?>>
		  	 	<label for="existing">Existing resource</label>
			    <div class="formrw">
			      <div class="form-25">
			        <label<?php echo $this->existingIdError ? ' class="error"' : ''; ?> for="exist_id">resource id</label>
			      </div>
			      <div class="form-75">
			        <input type="text" id="exist_id" name="exist_id" 
			        	placeholder="<?php echo $this->var->getName(); ?>.work...">
			      </div>		      
			    </div>
		    </fieldset>
			
			<!-- New resource -->
		    <fieldset>
		  	 	<input type="radio" id="new_resource" onclick="setExisting(false, true)" name="locate" value="new_resource" 
		  	 		<?php echo $this->locate == 'new_resource' ? 'checked="checked"' : ''; ?>>
		  	 	<label for="new_resource">New resource</label>
		  	 	
			    <div class="formrw">
			      <div class="form-25">
			        <label<?php echo $this->subjectError ? ' class="error"' : ''; ?> for="category">Subject</label>
			      </div>
			      <div class="form-75">
			        <select class="smallinput" id="subject" name="subject" onchange="submitLocation()">
			         	<?php foreach ($this->var->getChildren() as $child) { ?>
			         		<option value="<?php echo $child->getName(); ?>"
			         		<?php echo $this->subject == $child->getName() ? ' selected' : '';?>
			         		><?php echo $child->getFullName(); ?></option>
						<?php } ?>
			        </select>
			      </div>
			    </div>
			    
			    <div class="formrw">
			      <div class="form-25">
			        <label<?php echo $this->categoryError ? ' class="error"' : ''; ?> for="category">Category</label>
			      </div>
			      <div class="form-75">
			        <select class="smallinput" id="category" name="category" onchange="submitLocation()">
			        	<?php if (isset($this->sub)) {
				        	foreach ($this->sub->getChildren() as $child) { ?>
			        			<option value="<?php echo $child->getName(); ?>"
			        			<?php echo $this->category == $child->getName() ? ' selected' : '';?>
			        			><?php echo $child->getFullName(); ?></option>
				        	<?php } 
			        	} ?>
			        </select>
			      </div>
			    </div>
			    
			    <div class="formrw">
			      <div class="form-25">
			        <label<?php echo $this->yearError ? ' class="error"' : ''; ?> for="year">Year</label>
			      </div>
			      <div class="form-75">
			        <select class="smallinput" id="year" name="year">
			        	<?php if (isset($this->cat)) {
			        	    foreach ($this->cat->getChildren() as $child) { ?>
			        			<option value="<?php echo $child->getName(); ?>"
			        			<?php echo $this->year == $child->getName() ? ' selected' : '';?>
			        			><?php echo $child->getFullName(); ?></option>
			        		<?php } 
			        	} ?>
			        </select>
			      </div>
			    </div>
			   </fieldset>
			   <input type="hidden" id="submit_type" name="submit_type" value="button">
			   
			<div class=formrw><label><?php echo $this->msg; ?></label></div>
		    <div class="formrw">
		      <input type="submit" value="Next &#9656;">
		    </div>
		    
		  </form>
		  
		</div>
	</div>
	
</div>

<script>
function setExisting(state, resubmit) {
	document.getElementById('exist_id').disabled = !state;
	document.getElementById('subject').disabled = state;
	document.getElementById('category').disabled = state;
	document.getElementById('year').disabled = state;
	if (resubmit) {
		submitLocation();
	}
}

function submitLocation() {
	document.getElementById("submit_type").value = "script"; 
	document.getElementById("location").submit(); 
}

window.onload=setExisting(<?php echo $this->locate == 'existing' ? 'true' : 'false'; ?>, false);
</script>

