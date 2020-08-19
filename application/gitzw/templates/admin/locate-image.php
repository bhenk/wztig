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
						<select class="mediuminput" id="visart" name="visart" onchange="updateForm()"></select>
					</div>
				</div>
				
				<div class="formrw">
					<div class="form-25">
						<label<?php echo $this->activityError ? ' class="error"' : ''; ?> for="activity">Activity</label>
					</div>
					<div class="form-75">
						<select class="mediuminput" id="activity" name="activity" onchange="updateForm()"> </select>
					</div>
				</div>
				
				<div class="formrw">
					<div class="form-25">
						<label<?php echo $this->categoryError ? ' class="error"' : ''; ?> for="category">Category</label>
					</div>
					<div class="form-75">
						<select class="mediuminput" id="category" name="category" onchange="updateForm()"></select>
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

// function submitLocation() {
// 	document.getElementById("submit_type").value = "script"; 
// 	document.getElementById("location").submit(); 
// }

// window.onload=setExisting(<?php echo $this->locate == 'existing' ? 'true' : 'false'; ?>, false);

function updateForm() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			setSelectors(this.responseText);
		}
	};
	xhttp.open("POST", "<?php echo $this->action; ?>", true);
	xhttp.setRequestHeader("Content-type", "application/json");
	data = JSON.stringify({
		reason: "select_changed",
		visart : document.getElementById('visart').value,
		activity: document.getElementById('activity').value,
		category: document.getElementById('category').value,
		year: document.getElementById('year').value
	});
	xhttp.send(data);
}

function setSelectors(json) {
	var data = JSON.parse(json);
	for (var x of Object.keys(data)) {		
		var select = document.getElementById(x);
		removeOptions(select);
		for (var o of Object.keys(data[x])) {			
			var opt = document.createElement('option');
			opt.appendChild( document.createTextNode(data[x][o]["fullname"]) );
			opt.value = o;
			opt.selected = data[x][o]["selected"];
			select.appendChild(opt);
		}
	}	
}

function removeOptions(selectElement) {
   var i, L = selectElement.options.length - 1;
   for(i = L; i >= 0; i--) {
      selectElement.remove(i);
   }
}

window.onload = setSelectors('<?php echo $this->getJsonForSelects(); ?>');
</script>

