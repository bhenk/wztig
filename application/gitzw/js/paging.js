function sendPagingRequest(startitem) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.open();
			document.write(this.responseText);
			document.close();
			window.scrollTo(0, 0); 
		}
	};
	xhttp.open("POST", "<?php echo $this->getBaseUrl(); ?>", true);
	xhttp.setRequestHeader("Content-type", "application/json");
	data = JSON.stringify({
		paging : {
			start : startitem
		},
		payload : getPagingPayload()
		});
	xhttp.send(data);
}