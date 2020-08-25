<?php
namespace gitzw\templates\frame;

use gitzw\GZ;
use gitzw\site\model\NotFoundException;

/** @var mixed $this */
/** @var mixed $width */
/** @var mixed $height */
/** @var mixed $type */
const SMALL_IMG_WIDTH = 200;
const SMALL_IMG_HEIGHT = 200;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('/[^0-9a-zA-Z\/._ ]/', '-', urldecode($path));
$path = explode('/', $path);

$location = implode('/', array_slice($path, 2));
$file = GZ::DATA.'/images/'.$location;
if (!file_exists($file)) {
	throw new NotFoundException('unknown file');
}

$exif = exif_read_data($file, 0, TRUE);
if (!$exif) {
	throw new NotFoundException('unable to read exif');
}

?>
<h1 class="gitzw"><small>Exif data <?php echo $location; ?></small></h1>
<?php 
// $ida = new ImageData($file);
// echo $ida->getImgTag(SMALL_IMG_WIDTH, SMALL_IMG_HEIGHT, $location, 'maxheight');
$image = exif_thumbnail($file, $width, $height, $type);
echo "<img  width='$width' height='$height' src='data:image/gif;base64,".base64_encode($image)."'>";

foreach ($exif as $key => $section) { ?>
<h3 class="collapse-button"><?php echo $key; ?></h3>
	<div class="collapsable open">
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
collapse();
</script>