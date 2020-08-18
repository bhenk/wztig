<?php
namespace gitzw\templates\admin;

use gitzw\GZ;

/** @var mixed $this */
/** @var mixed $width */
/** @var mixed $height */
/** @var mixed $type */
const SMALL_IMG_WIDTH = 200;
const SMALL_IMG_HEIGHT = 200;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('/[^0-9a-zA-Z\/._ ]/', '-', urldecode($path));
$path = explode('/', $path);
$location = str_replace(' ', '/', $path[3]);
$file = GZ::DATA.'/images/'.$location;
$exif = exif_read_data($file, 0, TRUE);

?>
<h1 class="gitzw"><small>Exif data <?php echo $file; ?></small></h1>
<?php 
// $ida = new ImageData($file);
// echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $location, 'maxheight');
$image = exif_thumbnail($file, $width, $height, $type);
echo "<img  width='$width' height='$height' src='data:image/gif;base64,".base64_encode($image)."'>";

foreach ($exif as $key => $section) { ?>
<h3 class="collapse-button"><?php echo $key; ?></h3>
	<div class="collapsable">
		<div class="table-data">
			<?php foreach ($section as $name=>$val) { ?>
				<div>
					<div><?php echo $name; ?></div>
					<div><?php echo $val; ?></div>
				</div>
			<?php } ?>
		</div>
	</div>
<?php }
?>

<script>
<?php require_once GZ::SCRIPTS.'/collapse.js' ?>
</script>