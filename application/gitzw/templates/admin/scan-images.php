<?php
namespace gitzw\templates\admin;
/**
 * requires collapse, copy-previous from gitzw.js
 */
use gitzw\site\data\Security;
use gitzw\site\model\ImageInspector;
use gitzw\GZ;
use gitzw\site\data\ImageData;

const IMG_WIDTH = 200;
const IMG_HEIGHT = 200;
/** @var mixed $this */

$url = "'/admin/scan-images'";
$ii = new ImageInspector();

if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {
	$input = json_decode(file_get_contents('php://input'), true);
	$data = Security::cleanInput($input);
	$filename = $data['remove'];
	$deleted = $ii->deleteImage($filename);
	if ($deleted) {
	?>
	<script>alert('<?php echo $filename; ?> has been removed');</script>
	<?php } else { ?>
	<script>alert('<?php echo $filename; ?> could not be removed');</script>
	<?php }
}
?>


<h1>Scan images</h1>

<?php 
foreach ($ii->imageDiff() as $name=>$images) {
?>
<h2 class="collapse-button"> <?php echo $name; ?> <small>: <?php echo count($images); ?> loose images</small></h2>
	<div class="collapsable open">
		<?php 
		foreach ($images as $image) {
			$img = "'".$image."'";
			?>
				<div id="<?php echo $image; ?>">
					<span class="image-container">
					<a title="create resource" class="incognito" href="/admin/locate-image/<?php echo $image; ?>">
					<?php 
					$imgFile = GZ::DATA.'/images/'.$image;
					$ida = new ImageData($imgFile);
					echo $ida->getImgTag(IMG_WIDTH, IMG_HEIGHT, $image, 'maxheight');
					?>
					</a>
					</span>
					
					<small><?php echo $image; ?></small>
					<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
					
					<input type="checkbox" id="cb<?php echo $image ?>" 
						name="cb<?php echo $image ?>"  value="cb<?php echo $image ?>"
						onclick="realy(<?php echo $img.', '.$url; ?>, this)">
		  			<label for=cb<?php echo $image ?>>Delete</label>
					
				</div>
			<?php 
		}
		?>
	</div>
<?php 
}
?>
<script>
collapse();

function realy(image, url, ele) {
	if (confirm('Delete ' + image + ' permanently?')) {
		sendRemoveImage(image, url)
	} else {
		ele.checked = false;
	}
}

function sendRemoveImage(image, url) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.open();
			document.write(this.responseText);
			document.close();
			window.scrollTo(0, 0); 
		}
	};
	xhttp.open("DELETE", url, true);
	xhttp.setRequestHeader("Content-type", "application/json");
	data = JSON.stringify({
		remove : image
		});
	xhttp.send(data);
}

</script>