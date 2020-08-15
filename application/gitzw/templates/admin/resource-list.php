<?php
namespace gitzw\templates\admin;


/** @var mixed $this */
?>

<h1 class="gitzw">List resources <small>  &bull; found: <?php echo $this->itemCount; ?> resources</small></h1>

<?php foreach ($this->getPageResources() as $resource) { ?>
	<div><?php echo $resource->getLongId(); ?></div>
<?php } ?>

<?php $this->pager->render(); ?>
<script>
function getPagingPayload() {
	return {
		visart: "<?php echo $this->visart; ?>",
		activity: "<?php echo $this->activity; ?>",
		category: "<?php echo $this->category; ?>",
		year: "<?php echo $this->year; ?>"
	};
}
</script>
