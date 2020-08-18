<?php
namespace gitzw\templates\frame;

use gitzw\GZ;
/** @var mixed $this */
?>

<div class="gitzw collapse-button"><b>
  &bull; found <?php echo $this->itemCount; ?> resource<?php echo $this->itemCount == 1 ? '' : 's'; ?></b></div>
<div class="collapsable<?php echo $this->start == 0 ? ' open' : ''; ?>">
	<div class="search-dashboard">
		<div class="searchquery">
			<span>&bull; visual artist: <?php echo $this->visart; ?></span>
			<span>&bull; activity: <?php echo $this->activity; ?></span>
			<span>&bull; category: <?php echo $this->category; ?></span>
			<span>&bull; year: <?php echo $this->year; ?></span>
			<?php if (!empty($this->title_en)) { ?><div><span>&bull; title en: <?php echo $this->title_en; ?></span></div> <?php } ?>
			<?php if (!empty($this->title_nl)) { ?><div><span>&bull; title nl: <?php echo $this->title_nl; ?></span></div> <?php } ?>
			<?php if (!empty($this->media)) { ?><div><span>&bull; media: <?php echo $this->media; ?></span></div> <?php } ?>
			<div>
			<?php if (!empty($this->width)) { ?><span>&bull; width: <?php echo $this->width; ?></span> <?php } ?>
			<?php if (!empty($this->height)) { ?><span>&bull; height: <?php echo $this->height; ?></span> <?php } ?>
			<?php if ($this->rIsHidden) { ?><span>&bull; hidden</span><?php }?>
			<?php if ($this->rIsNotHidden) { ?><span>&bull; not hidden</span><?php }?>
			<?php if ($this->rIsFrontPage) { ?><span>&bull; front page</span><?php }?>
			<?php if ($this->rIsNotFrontPage) { ?><span>&bull; not front page</span><?php }?>
			</div>
			<?php if (!empty($this->date)) { ?><div><span>&bull; date: <?php echo $this->date; ?></span></div> <?php } ?>
			<?php if (!empty($this->longId)) { ?><div><span>&bull; id: <?php echo $this->longId; ?></span></div> <?php } ?>
		</div>
		<div class="searchbutton" onclick="sendPagingRequest('form')">
			&#9851;
		</div>
	</div>
</div>
<?php 
$this->pager->render();
?>


<?php foreach ($this->getPageResources() as $result) { ?>
	<div class="indicator-wrapper">
  		<div class="relevance-indicator" style="width:<?php echo ($result[0]/$this->resultFactor)*100; ?>%"
  		><?php echo $result[0]; ?></div>
	</div> 
	<?php $result[1]->render(GZ::TEMPLATES.'/views/resource-ov.php'); ?>
	
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
		media: "<?php echo $this->media; ?>",
		width: "<?php echo $this->width; ?>",
		height: "<?php echo $this->height; ?>",
		date: "<?php echo $this->date; ?>",
		rishidden: "<?php echo $this->rIsHidden ? 'rishidden' : ''; ?>",
		risnothidden: "<?php echo $this->rIsNotHidden ? 'risnothidden' : ''; ?>",
		risfrontpage: "<?php echo $this->rIsFrontPage ? 'risfrontpage' : ''; ?>",
		risnotfrontpage: "<?php echo $this->rIsNotFrontPage ? 'risnotfrontpage' : ''; ?>",
		longid: "<?php echo $this->longId; ?>"
	};
}
</script>
