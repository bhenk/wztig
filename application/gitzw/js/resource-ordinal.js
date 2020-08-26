/* sets ordinal on resources. called from 
	gitzw\templates\admin\resource-heap
*/

/* resource-ordinal.js > resource-ordinal.min.js */

function setOrdinal(ele, id) {
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "<?php echo $_SERVER['REQUEST_URI']; ?>", true);
	xhttp.setRequestHeader("Content-type", "application/json");
	data = JSON.stringify({
		shortid: id,
		ordinal: ele.value
	});
	xhttp.send(data);
}
