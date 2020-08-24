<?php
namespace gitzw\templates\admin;

use gitzw\site\data\ImageData;

/** @var mixed $this */

const IMG_WIDTH = 500;
const IMG_HEIGHT = 500;

$representation = $this->resource->getRepresentation();
$ida = new ImageData(null, $representation->getLocation());
?>
<h1 class="gitzw">Move resource <small><?php echo $this->resource->getLongId(); ?></small></h1>

<div class="img-data-container">
	<div class="img-container">
		<?php 
		echo $ida->getImgTag(IMG_WIDTH, IMG_HEIGHT, $representation->getLocation(), 'maxheight');
		?>
	</div>
	
	<div class="img_data">
		<div class="container">
			<form id="move" action="<?php echo $this->action; ?>" method="post">
			
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
				
				<div class=formrw><label><?php echo $this->msg; ?></label></div>
			    <div class="formrw">
			      <input type="submit" value="Move &#9656;">
			    </div>
			
			</form>
		</div>
	</div>

</div>
<script>
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