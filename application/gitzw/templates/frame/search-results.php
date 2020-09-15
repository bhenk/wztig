<?php
namespace gitzw\templates\frame;

use gitzw\GZ;
/** @var mixed $this */
$url = "'".$this->action."'";
?>

<div class="gitzw collapse-button"><b>
  &bull; found <?php echo $this->itemCount; ?></b></div>
<div class="collapsable<?php echo $this->start == 0 ? ' open' : ''; ?>">
	<div class="search-dashboard">
		<div class="searchquery">
			<?php if ($this->visart != 'all') { ?><span id="visart">&bull; name: <?php echo $this->fullNames['visart']; ?></span><?php } ?>
			<?php if ($this->activity != 'all') { ?><span id="activity">&bull; activity: <?php echo $this->fullNames['activity']; ?></span><?php } ?>
			<?php if ($this->category != 'all') { ?><span id="category">&bull; category: <?php echo $this->fullNames['category']; ?></span><?php } ?>
			<?php if ($this->year != 'all') { ?><span id="year">&bull; year: <?php echo $this->fullNames['year']; ?></span><?php } ?>
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
		<div title="recycle search query" class="searchbutton" onclick="sendPagingRequest('form', <?php echo $url; ?>)">
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
<div class="license">
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank">
		<img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" />
	</a>
</div>
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

collapse();
</script>
