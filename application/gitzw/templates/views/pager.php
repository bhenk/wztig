<?php
namespace gitzw\templates\views;

/** @var mixed $this */
?>

<div class="paging">
	<a class="chrono"<?php echo $this->getChronoStyle(); ?>
		href="<?php echo $this->getChronoLink(); ?>">---</a>
	<a class="left-arrow"<?php echo $this->getLeftArrowStyle(); ?> 
		href="<?php echo $this->getLeftArrowLink(); ?>">&nbsp;&#9664;&nbsp;</a>
	
	<?php foreach($this->getPagelinks() as $link) { ?>
		<a <?php echo $link[2] ? 'class="selected" ' : ''; ?> 
			href="<?php echo $link[1]; ?>"><?php echo $link[0]; ?></a>	
	<?php } ?>
	
	<a class="right-arrow"<?php echo $this->getRightArrowStyle(); ?> 
		href="<?php echo $this->getRightArrowLink(); ?>">&nbsp;&#9654;&nbsp;</a>
</div>