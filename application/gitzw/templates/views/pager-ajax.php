<?php
namespace gitzw\templates\views;

use gitzw\GZ;

/** @var mixed $this */
?>

<div class="paging">
	<span class="paging-link"<?php echo $this->getLeftArrowStyle(); ?> 
		onclick="sendPagingRequest(<?php echo $this->getLeftArrowOnClick(); ?>)">&nbsp;&#9664;&nbsp;</span>
	
	<?php foreach($this->getPagelinks() as $link) { 
		if ($link[3] < 0) {
		?>
		<span><?php echo $link[0]; ?></span>	
		<?php } else {
		?>
		<span class="paging-link<?php echo $link[2] ? ' selected' : ''; ?>" 
			onclick="sendPagingRequest(<?php echo $link[3]; ?>)"><?php echo $link[0]; ?></span>	
	<?php } 
	} ?>
	
	<span class="paging-link"<?php echo $this->getRightArrowStyle(); ?> 
		onclick="sendPagingRequest(<?php echo $this->getRightArrowOnClick(); ?>)">&nbsp;&#9654;&nbsp;</span>
</div>

<script>
<?php require_once GZ::SCRIPTS.'/paging.js'; ?>
</script>