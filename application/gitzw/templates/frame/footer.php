<?php 
namespace gitzw\templates\frame;
/** @var mixed $this */ 
?>
<div class="footer">
	<p>
		<?php echo $this->getCopyRight(); ?>
		&nbsp;info&nbsp;at&nbsp;gitzw.art
		<?php echo $this->getClientIp(); ?>
		<?php echo $this->getActiveUser(); ?>
		<?php echo $this->getUserLink(); ?>
		<?php echo $this->getAdminLink(); ?>
		
	</p>
	
	
</div>


