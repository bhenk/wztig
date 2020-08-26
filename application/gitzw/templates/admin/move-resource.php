<?php
namespace gitzw\templates\admin;

use gitzw\site\data\ImageData;

/** @var mixed $this */

const IMG_WIDTH = 500;
const IMG_HEIGHT = 500;

$url = "'".$this->action."'";

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
				
				<div class=formrw><label><?php echo $this->msg; ?></label></div>
			    <div class="formrw">
			      <input type="submit" value="Move &#9656;">
			    </div>
			
			</form>
		</div>
	</div>

</div>
<script>
window.onload = setSelectors('<?php echo $this->getJsonForSelects(); ?>');
</script>