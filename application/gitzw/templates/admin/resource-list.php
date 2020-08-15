<?php
namespace gitzw\templates\admin;


/** @var mixed $this */
?>

<h1 class="gitzw">List resources</h1>

<button onclick="sendRequest()">Continue</button>

<div style="height: 400px"> </div>

<?php $this->pager->render(); ?>

<script>
function getPayload() {
	return {
		visart: "<?php echo $this->visart; ?>",
		subject: "<?php echo $this->subject; ?>",
		category: "<?php echo $this->category; ?>",
		year: "<?php echo $this->year; ?>"
	};
}
</script>
