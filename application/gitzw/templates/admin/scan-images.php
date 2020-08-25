<?php
namespace gitzw\templates\admin;
/**
 * requires collapse, copy-previous from gitzw.js
 */
use gitzw\site\data\ImageData;
use gitzw\site\model\ImageInspector;
use gitzw\GZ;

const IMG_WIDTH = 200;
const IMG_HEIGHT = 200;
/** @var mixed $this */
?>

<h1>Scan images</h1>

<?php 
$ii = new ImageInspector();
foreach ($ii->imageDiff() as $name=>$images) {
?>
<h2 class="collapse-button"> <?php echo $name; ?> <small>: <?php echo count($images); ?> loose images</small></h2>
	<div class="collapsable open">
		<?php 
		foreach ($images as $image) {
			?>
			<a title="create resource" class="incognito" href="/admin/locate-image/<?php echo $image; ?>">
			<span class="image-container">
			<?php 
			$imgFile = GZ::DATA.'/images/'.$image;
			$ida = new ImageData($imgFile);
			echo $ida->getImgTag(IMG_WIDTH, IMG_HEIGHT, $image, 'maxheight');
			?>
			</span>
			</a>
			<small><?php echo $image; ?></small>
			<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
			<?php 
		}
		?>
	</div>
<?php 
}
?>