<?php
namespace gitzw\templates\views;

use gitzw\site\data\ImageData;
use gitzw\GZ;
use gitzw\site\data\Security;

/** @var mixed $this */

const IMG_WIDTH = 1200;
const IMG_HEIGHT = 1000;

$ida = new ImageData($this->getImagePath());
?>
<div class="resource-view">
	<div class="resource">
		
		<?php echo $ida->getImgTag(IMG_WIDTH, IMG_HEIGHT, $this->getResource()->getLongId()); ?>

		<?php if ($this->hasPrevious()) { ?>
			<div class="lrpage-prev"><a title="previous" href="<?php echo $this->previousUrl(); ?>"><span>&#9664;</span></a></div>
		<?php } ?>
		<?php if ($this->hasNext()) { ?>
			<div class="lrpage-next"><a title="next" href="<?php echo $this->nextUrl(); ?>"><span>&#9654;</span></a></div>
		<?php } ?>
	</div>
	<div class="subscript"><?php echo $this->getResource()->getSubscript(); ?></div>
	
	<div class="button-rect">
		<a href="<?php echo '/zoom/'.$this->getResource()->getRepresentation()->getLocation(); ?>">
			<?php require GZ::TEMPLATES.'/svg/zoom.svg'; ?></a>
		<a href="<?php echo '/exif-data/'.$this->getResource()->getRepresentation()->getLocation(); ?>">
			<?php require GZ::TEMPLATES.'/svg/exif.svg'; ?></a>
		<?php if (Security::get()->hasAccess()) { ?>
			<a href="<?php echo '/admin/edit-resource/'.$this->getResource()->getLongId(); ?>">
				<?php require GZ::TEMPLATES.'/svg/edit.svg'; ?></a>
		<?php } ?>
	</div>
	
	<h1><?php echo $this->getResource()->getLongId(); ?></h1>
</div>

