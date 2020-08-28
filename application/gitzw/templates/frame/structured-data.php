<?php
namespace gitzw\templates\frame;

/** @var mixed $this */
?>
<div class="structured-data">
	<div class="collapse-button json-head">SD</div>
	<div class="collapsable">
		<code>
		<?php echo str_replace(["\n", "\t", "  "], ["<br/>", "&nbsp;&nbsp;", "&nbsp;&nbsp;"], $this->structuredData); ?>
		</code>
	</div>
</div>
<script>collapse();</script>