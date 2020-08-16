<?php
namespace gitzw\templates\admin;

use gitzw\GZ;
/** @var mixed $this */
?>

<h1 class="gitzw collapse-button">Search <small>
  &bull; found <?php echo $this->itemCount; ?> resources</small></h1>
<div class="collapsable<?php echo $this->start == 0 ? ' open' : ''; ?>">
	<div class="search-dashboard">
		<div class="searchquery">
			<span>&bull; visart: <?php echo $this->visart; ?></span>
			<span>&bull; activity: <?php echo $this->activity; ?></span>
			<span>&bull; category: <?php echo $this->category; ?></span>
			<span>&bull; year: <?php echo $this->year; ?></span>
			<?php if (!empty($this->title_en)) { ?><div><span>&bull; title en: <?php echo $this->title_en?></span></div> <?php } ?>
			<?php if (!empty($this->title_nl)) { ?><div><span>&bull; title nl: <?php echo $this->title_nl?></span></div> <?php } ?>
			<?php if (!empty($this->technique)) { ?><div><span>&bull; technique: <?php echo $this->technique?></span></div> <?php } ?>
			<?php if (!empty($this->width)) { ?><div><span>&bull; width: <?php echo $this->width?></span></div> <?php } ?>
			<?php if (!empty($this->height)) { ?><div><span>&bull; height: <?php echo $this->height?></span></div> <?php } ?>
		</div>
		<div class="searchbutton" onclick="sendPagingRequest('form')">
			&#9851;
		</div>
	</div>
</div>

<?php foreach ($this->getPageResources() as $result) { ?>
	<div class="indicator-wrapper">
  		<div class="relevance-indicator" style="width:<?php echo ($result[0]/$this->resultFactor)*100; ?>%"
  		><?php echo $result[0]; ?></div>
	</div> 
	<?php $result[1]->render(GZ::TEMPLATES.'/admin/resource-ov.php'); ?>
	
<?php }

$this->pager->render(); 
?>
<script>
function getPagingPayload() {
	return {
		visart: "<?php echo $this->visart; ?>",
		activity: "<?php echo $this->activity; ?>",
		category: "<?php echo $this->category; ?>",
		year: "<?php echo $this->year; ?>",
		title_en: "<?php echo $this->title_en; ?>",
		title_nl: "<?php echo $this->title_nl; ?>",
		technique: "<?php echo $this->technique; ?>",
		width: "<?php echo $this->width; ?>",
		height: "<?php echo $this->height; ?>"
	};
}
</script>
