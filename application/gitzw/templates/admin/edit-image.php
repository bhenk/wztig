<?php
namespace gitzw\templates\admin;

/** @var mixed $this */

use gitzw\site\data\ImageData;
use gitzw\GZ;

const IMG_WIDTH = 500;
const IMG_HEIGHT = 600;

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
	<div class="img_data">
		bla bla bla bl a lbla bla bla bla bla bla bla bla bla bla bl a lbla bla bla bla bla bla bla 
		<form action="<?php echo $action; ?>" method="post">
			<div class="table-data">
				<div>
					<div><label for="title_nl">title (nl)</label></div>
					<div><input type="text" id="title_nl" name="title_nl" class="form-control" value="<?php echo $title_nl; ?>"></div>
				</div>
				<div>
					<div><label for="title_en">title (en)</label></div>
					<div><input size="60" type="text" id="title_en" name="title_en" class="form-control" value="<?php echo $title_en; ?>"></div>
				</div>
				<div>
					<div><label for="preferred_title">preferred title</label></div>
					<div>
						<select id="preferred_title" name="preferred_title" class="form-control">
						  <option value="nl" <?php echo $preferred_title == 'nl' ? 'selected' : ''; ?>>nl</option>
						  <option value="en" <?php echo $preferred_title == 'en' ? 'selected' : ''; ?>>en</option>
						</select>
					</div>
				</div>
				<div>
					<div><label for="technique">technique</label></div>
					<div><input type="text" id="technique" name="technique" class="form-control" value="<?php echo $technique ?>"></div>
				</div>
			</div>
		</form>
		<?php echo $action; ?>
	</div>
</div>