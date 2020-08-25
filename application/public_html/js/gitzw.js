/* nav-menu */

function menuButtons() {
	
	var dropdown = document.getElementsByClassName("dropdown-btn");
	var i;

	for (i = 0; i < dropdown.length; i++) {
	  dropdown[i].addEventListener("click", function() {
	  
	  	/* neutralize all other buttons */
	  	var dropdowns = document.getElementsByClassName("dropdown-btn");
		var i;
	  	for (i = 0; i < dropdowns.length; i++) {
	  		if (!this.isSameNode(dropdowns[i])) {
	      		dropdowns[i].classList.remove("active");
	      		var dropdownContent = dropdowns[i].nextElementSibling;
	      		dropdownContent.style.display = "none";
	      	}
	  	}
	  	
		/* toggle state of calling button */
	  	this.classList.toggle("active");
	  	
	  	var dropdownContent = this.nextElementSibling;
	  	if (dropdownContent.style.display === "block") {
	  		dropdownContent.style.display = "none";
	  	} else {
	  		dropdownContent.style.display = "block";
	  	}
	  });	
	}
}


/* end nav-menu */

/* button panel functions */

function changeBackgroundColor(n) {
	let bc = document.documentElement.style.getPropertyValue("--bg-color");
	if (bc == "" & n > 0) {
		bc = "rgb(238, 238, 238)";
	} else if (bc == "" & n < 0) {
		bc = "rgb(255, 255, 255)";
	}
	let rgb = parseRGB(bc);
	rgb = changeRGB(rgb, n);
	bc = "rgb(" + rgb[0] + ", " + rgb[1] + ", " + rgb[2] + ")"  
	
	let cc = "rgb(127, 127, 127)";
	if (rgb[1] > 127 & rgb[1] < 205) {
		cc = "rgb(55, 55, 55)";
	} else if (rgb[1] < 128) {
		cc = "rgb(200, 200, 200)";
	}
	
	document.documentElement.style.setProperty("--bg-color", bc);
	document.documentElement.style.setProperty("--contrast-color", cc);
	document.cookie = "bgrgb=" + bc + "; path=/";
	document.cookie = "ccrgb=" + cc + "; path=/"; 
}

function parseRGB(rgb) {
	let sep = rgb.indexOf(",") > -1 ? "," : " ";
	rgb = rgb.substr(4).split(")")[0].split(sep);
	let r = parseInt(rgb[0]),
      	g = parseInt(rgb[1]),
      	b = parseInt(rgb[2]);
    return [r, g, b];
}

function changeRGB(rgb, n) {
	for(i=0; i < rgb.length; i++) {
		if ((rgb[i] + n) > 255) {
			rgb[i] = 255;
		} else if ((rgb[i] + n) < 0) {
			rgb[i] = 0;
		} else {
			rgb[i] = rgb[i] + n;
		}
	}
	return rgb;
}

function continueBackgroundColor() {
	let bc = getCookie("bgrgb");
	if (bc != "") {
		document.documentElement.style.setProperty("--bg-color", bc);
	}
	let cc = getCookie("ccrgb");
	if (cc != "") {
		document.documentElement.style.setProperty("--contrast-color", cc);
	}
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

window.onload = continueBackgroundColor();

/* end button panel functions */

/* collapse - hide and display blocks */
function collapse() {
	var dropdown = document.getElementsByClassName("collapse-button");
	var i;
	
	for (i = 0; i < dropdown.length; i++) {
		
		var dropdownContent = dropdown[i].nextElementSibling;
		if (dropdownContent.classList.contains("open")) {
			dropdown[i].classList.toggle("active");
			dropdownContent.style.display = "block";
		}
		
		dropdown[i].addEventListener("click", function() {
			
			/* toggle state of calling button */
	  		this.classList.toggle("active");
	
			var dropdownContent = this.nextElementSibling;
		  	if (dropdownContent.style.display === "block") {
		  		dropdownContent.style.display = "none";
		  	} else {
		  		dropdownContent.style.display = "block";
		  	}
		});
	}
}

/* end collapse - hide and display blocks */

/* copy previous */

function copyPrevious(ele) {
	var petext = ele.previousElementSibling.innerHTML;
	var el = document.createElement('textarea');
	el.value = petext;
	el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
	document.body.appendChild(el);
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

/* end copy previous */

/* paging. sends a payload with paging parameters, updates the window. */

function sendPagingRequest(startitem, url) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.open();
			document.write(this.responseText);
			document.close();
			window.scrollTo(0, 0); 
		}
	};
	xhttp.open("POST", url, true);
	xhttp.setRequestHeader("Content-type", "application/json");
	data = JSON.stringify({
		paging : {
			start : startitem
		},
		payload : getPagingPayload()
		});
	xhttp.send(data);
}

/* update the search form */

function updateForm(url) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			setSelectors(this.responseText);
		}
	};
	xhttp.open("POST", url, true);
	xhttp.setRequestHeader("Content-type", "application/json");
	data = JSON.stringify({
		reason: "select_changed",
		visart : document.getElementById('visart').value,
		activity: document.getElementById('activity').value,
		category: document.getElementById('category').value,
		year: document.getElementById('year').value
	});
	xhttp.send(data);
}

function setSelectors(json) {
	var data = JSON.parse(json);
	for (var x of Object.keys(data)) {		
		var select = document.getElementById(x);
		removeOptions(select);
		for (var o of Object.keys(data[x])) {			
			var opt = document.createElement('option');
			opt.appendChild( document.createTextNode(data[x][o]["fullname"]) );
			opt.value = o;
			opt.selected = data[x][o]["selected"];
			select.appendChild(opt);
		}
	}	
}

function removeOptions(selectElement) {
   var i, L = selectElement.options.length - 1;
   for(i = L; i >= 0; i--) {
      selectElement.remove(i);
   }
}

/* end update search form */
