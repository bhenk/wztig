<?php
namespace gitzw\templates\admin;

use gitzw\GZ;

/** @var mixed $this */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('/[^0-9a-zA-Z\/._ ]/', '-', urldecode($path));
$location = str_replace(' ', '/', $this->path[4]);
$file = GZ::DATA.'/images/'.$location;
$exif = exif_read_data($file, 0, TRUE);

foreach ($exif as $key => $section) { ?>
<h1 class="collapse-button"><?php echo $key; ?></h1>
	<div class="collapsable table-data">
		<?php foreach ($section as $name=>$val) { ?>
			<div>
				<div><?php echo $name; ?></div>
				<div><?php echo $val; ?></div>
			</div>
		<?php } ?>
	</div>
<?php }
?>

<script>
<?php require_once GZ::SCRIPTS.'/collapse.js' ?>
</script>