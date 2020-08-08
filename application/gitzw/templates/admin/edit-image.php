<?php
namespace gitzw\templates\admin;

/** @var mixed $this */

use gitzw\site\data\ImageData;
use gitzw\GZ;

const IMG_WIDTH = 500;
const IMG_HEIGHT = 500;

$path = $this->getPath();
// $var = $path[3];
$rep = implode('/', array_slice($path, 3));
$imgFile = GZ::DATA.'/images/'.$rep;
$action = implode('/', array_slice($path, 1));

$title_nl = '';
$title_en = '';
$preferred_title = '';
$technique = '';

?>

<h1 class="gitzw">Edit image <small><?php echo $rep; ?></small></h1>

<div class="img-data-container">
	<div class="img-container">
		<?php 
		$id = new ImageData($imgFile);
		echo $id->getImgTag(IMG_WIDTH, IMG_HEIGHT, $rep, 'maxheight');
		?>
	</div>
	<!-- pseude element .img_data:before -->
	<div class="img_data">
		<div class="container">
		  <form action="/action_page.php">
		  	
		    <div class="formrw">
		      <div class="form-25">
		        <label for="fname">First Name</label>
		      </div>
		      <div class="form-75">
		        <input type="text" id="fname" name="firstname" placeholder="Your name..">
		      </div>		      
		    </div>
		    
		    <div class="formrw">
		      <div class="form-25">
		        <label for="lname">Last Name</label>
		      </div>
		      <div class="form-75">
		        <input type="text" id="lname" name="lastname" placeholder="Your last name..">
		      </div>
		    </div>
		    <div class="formrw">
		      <div class="form-25">
		        <label for="country">Country</label>
		      </div>
		      <div class="form-75">
		        <select id="country" name="country">
		          <option value="australia">Australia</option>
		          <option value="canada">Canada</option>
		          <option value="usa">USA</option>
		        </select>
		      </div>
		    </div>
		    <div class="formrw">
		      <div class="form-25">
		        <label for="subject">Subject</label>
		      </div>
		      <div class="form-75">
		        <textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea>
		      </div>
		    </div>
		    <div class="formrw">
		      <input type="submit" value="Submit">
		    </div>
		  </form>
		</div>
	</div>
</div>