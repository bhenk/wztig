<?php
namespace gitzw\templates\views;

use gitzw\site\data\ImageData;
use gitzw\GZ;
use gitzw\site\data\Security;

/** @var mixed $this */

?>
<div class="resource-view">
	<div class="resource">
		<img src="<?php echo $this->imgData['location']; ?>" 
			alt="main representation of <?php echo $this->getResource()->getLongId(); ?>">

		<?php if ($this->hasPrevious()) { ?>
			<div class="lrpage-prev"><a href="<?php echo $this->previousUrl(); ?>"><span title="next">&#9664;</span></a></div>
		<?php } ?>
		<?php if ($this->hasNext()) { ?>
			<div class="lrpage-next"><a href="<?php echo $this->nextUrl(); ?>"><span title="previous" >&#9654;</span></a></div>
		<?php } ?>
	</div>
	<div class="subscript"><?php echo $this->getResource()->getSubscript(); ?></div>
	
	<div class="button-rect">
		<a href="<?php echo '/zoom/'.$this->mainRepresentation->getLocation(); ?>">
			<?php require GZ::TEMPLATES.'/svg/zoom.svg'; ?></a><span> </span>
		<a href="<?php echo '/exif-data/'.$this->mainRepresentation->getLocation(); ?>">
			<?php require GZ::TEMPLATES.'/svg/exif.svg'; ?></a><span> </span>
		<?php if (Security::get()->hasAccess()) { ?>
			<a href="<?php echo '/admin/edit-resource/'.$this->getResource()->getLongId(); ?>">
				<?php require GZ::TEMPLATES.'/svg/edit.svg'; ?></a><span> </span>
		<?php } ?>
		<span><?php echo $this->getResource()->getOrdinal(); ?></span>
		<span><?php echo $this->getResource()->hasFrontPage() ? ' &#9635 ' : ''; ?></span>
		
		<div class="representation-id">
			<span><?php echo $this->mainRepresentation->getLocation(); ?></span>
			<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
		</div>
	</div>
	
	<h1 class="resource-id">
		<span><?php echo $this->getResource()->getLongId(); ?></span>
		<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
	</h1>
	
	<?php foreach ($this->getRepresentations() as $id=>$rep) { 
		$ida = new ImageData(null, $id);
		?>
		<div class="resource">
		
		<?php echo $ida->getImgTag(600, 500, $id); ?>
		</div>
		<div class="subscript"><?php echo $rep->getDescription(); ?></div>
		
		<div class="button-rect">
			<a href="<?php echo '/zoom/'.$id; ?>">
				<?php require GZ::TEMPLATES.'/svg/zoom.svg'; ?></a>
			<a href="<?php echo '/exif-data/'.$id; ?>">
				<?php require GZ::TEMPLATES.'/svg/exif.svg'; ?></a>
			<div class="representation-id">
				<span><?php echo $rep->getLocation(); ?></span>
				<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
			</div>
		</div>
		<div>
			
		</div>
	<?php } ?>
	
	
</div>
