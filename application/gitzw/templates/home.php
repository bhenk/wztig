<?php
namespace gitzw\templates;

/** @var mixed $this */
?>
<div class="name-blok">
&nbsp;
</div>

<?php $this->renderHomeContent() ?>

<div class="structured-data">
	<div class="collapse-button json-head">gitzw.art</div>
	<div class="collapsable">
		<code>
		<?php echo str_replace(["\n", "  "], ["<br/>", "&nbsp;&nbsp;"], json_encode($this->getStructuredData(), JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES)); ?>
		</code>
	</div>
</div>
<script>collapse();</script>