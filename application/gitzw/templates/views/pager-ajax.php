<?php
namespace gitzw\templates\views;

/** @var mixed $this */
?>

<div class="paging">
	<span class="paging-link"<?php echo $this->getLeftArrowStyle(); ?> 
		onclick="sendRequest(<?php echo $this->getLeftArrowOnClick(); ?>)">&nbsp;&#9664;&nbsp;</span>
	
	<?php foreach($this->getPagelinks() as $link) { 
		if ($link[3] < 0) {
		?>
		<span><?php echo $link[0]; ?></span>	
		<?php } else {
		?>
		<span class="paging-link<?php echo $link[2] ? ' selected' : ''; ?>" 
			onclick="sendRequest(<?php echo $link[3]; ?>)"><?php echo $link[0]; ?></span>	
	<?php } 
	} ?>
	
	<span class="paging-link"<?php echo $this->getRightArrowStyle(); ?> 
		onclick="sendRequest(<?php echo $this->getRightArrowOnClick(); ?>)">&nbsp;&#9654;&nbsp;</span>
</div>

<script>
function sendRequest(startitem) {
	if (startitem < 0) {
		return;
	}
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.open();
			document.write(this.responseText);
			document.close();
		}
	};
	xhttp.open("POST", "<?php echo $this->getBaseUrl(); ?>", true);
	xhttp.setRequestHeader("Content-type", "application/json");
	data = JSON.stringify({
		paging : {
			start : startitem
		},
		payload : getPayload()
		});
	xhttp.send(data);
}
</script>