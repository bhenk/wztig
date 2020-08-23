<?php
namespace gitzw\templates\frame;
/** @var mixed $this */ 
?>
<div class="button-panel">
	
	<div class="svg-icon">
		<a href="/search">
			<svg width="48" height="30" xmlns="http://www.w3.org/2000/svg">
			 <!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->
			 <g>
			  <title>search</title>
			  <rect fill="none" id="canvas_background" height="32" width="50" y="-1" x="-1"/>
			  <g display="none" overflow="visible" y="0" x="0" height="100%" width="100%" id="canvasGrid">
			   <rect fill="url(#gridpattern)" stroke-width="0" y="0" x="0" height="100%" width="100%"/>
			  </g>
			 </g>
			 <g>
			  <title>search</title>
			  <ellipse ry="10.34357" rx="10.62481" id="svg_1" cy="13.43753" cx="13.31269" stroke-width="3" stroke="gray" fill="#fff"/>
			  <line stroke-linecap="null" stroke-linejoin="null" id="svg_2" y2="26.59355" x2="39.93722" y1="18.28119" x1="23.43751" fill-opacity="null" stroke-opacity="null" stroke-width="4.5" stroke="gray" fill="none"/>
			 </g>
			</svg>
		</a>
	</div>
	
	<div style="display: inline-block; white-space: nowrap;">
		<div class="svg-icon">
			<a href="javascript:changeBackgroundColor(+17)">
				<svg width="48" height="30" xmlns="http://www.w3.org/2000/svg">
				 <!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->
				 <g>
				  <title>background</title>
				  <rect fill="none" id="canvas_background" height="32" width="50" y="-1" x="-1"/>
				  <g display="none" overflow="visible" y="0" x="0" height="100%" width="100%" id="canvasGrid">
				   <rect fill="url(#gridpattern)" stroke-width="0" y="0" x="0" height="100%" width="100%"/>
				  </g>
				 </g>
				 <g>
				  <title>change background hue: lighter</title>
				  <rect id="svg_1" height="18.24995" width="30.62491" y="5.93753" x="8.1563" stroke-width="3" stroke="gray" fill="#fff"/>
				 </g>
				</svg>
			</a>
		</div>
		
		<div class="svg-icon">
			<a href="javascript:changeBackgroundColor(-17)">
				<svg width="48" height="30" xmlns="http://www.w3.org/2000/svg">
				 <!-- Created with Method Draw - http://github.com/duopixel/Method-Draw/ -->
				 <g>
				  <title>background</title>
				  <rect fill="none" id="canvas_background" height="32" width="50" y="-1" x="-1"/>
				  <g display="none" overflow="visible" y="0" x="0" height="100%" width="100%" id="canvasGrid">
				   <rect fill="url(#gridpattern)" stroke-width="0" y="0" x="0" height="100%" width="100%"/>
				  </g>
				 </g>
				 <g>
				  <title>change background hue: darker</title>
				  <rect id="svg_1" height="18.24995" width="30.62491" y="5.93753" x="8.1563" stroke-width="3" stroke="gray" fill="#000"/>
				 </g>
				</svg>
			</a>
		</div>
	</div>
</div>
<script>
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
</script>

