function copyPrevious(ele) {
	var petext = ele.previousElementSibling.innerHTML;
	var el = document.createElement('textarea');
	el.value = petext;
	el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
	document.body.appendChild(el);
	/*const selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false;*/
    el.select();
    el.setSelectionRange(0, 99999); /*For mobile devices*/
    document.execCommand('copy');
	document.body.removeChild(el);
	var eles = document.getElementsByClassName("copyprevious");
	for (i = 0; i < eles.length; i++) {
		eles[i].style.color = "inherit"
		eles[i].innerHTML = " &#9776; "
	}
	ele.style.color = "red";
	ele.innerHTML = " &#9788; ";
}