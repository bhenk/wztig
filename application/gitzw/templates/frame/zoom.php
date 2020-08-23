<?php 
namespace gitzw\templates\frame;
/** @var mixed $this */ 

const IMG_WIDTH = 4500;
const IMG_HEIGHT = 4500;

$data1 = $this->getLocationData(IMG_WIDTH, IMG_HEIGHT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $this->getTitle(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
<link rel="stylesheet" href="/css/normalize.css">
<link rel="stylesheet" href="/css/zoom.css">
</head>
<body>

<div class="img-zoom-container">
	<div id="img-panel" class="img-panel">
		<img id="srcimg" src="<?php echo $data1['location']; ?>" width="<?php echo round($data1['size'][0] / 15); ?>" 
			height="<?php echo round($data1['size'][1] / 15); ?>" alt="<?php echo $this->imgId; ?>">
	</div>
		
  	<div id="resultimg" class="img-zoom-result"></div>
</div>

<div class="slider-wrap" title="magnification factor">
	<input id="slider" type="range" min="-15" max="-3" value="-7" onchange="change(this)">	
</div>	


<script>

function imageZoom(imgID, resultID) {
  var img, lens, result, cx, cy;
  img = document.getElementById(imgID);
  result = document.getElementById(resultID);
  
  /* Create lens: */
  lens = document.createElement("DIV");
  lens.setAttribute("class", "img-zoom-lens");
  lens.setAttribute("id", "zoom-lens");
  
  /* Insert lens: */
  img.parentElement.insertBefore(lens, img);
  
  /* Calculate the ratio between result DIV and lens: */
  cx = result.offsetWidth / lens.offsetWidth;
  cy = result.offsetHeight / lens.offsetHeight;
  
  
  /* Set background properties for the result DIV */
  result.style.backgroundImage = "url('" + img.src + "')";
  result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
  
  /* Execute a function when someone moves the cursor over the image, or the lens: */
  lens.addEventListener("mousemove", moveLens);
  img.addEventListener("mousemove", moveLens);
  
  /* And also for touch screens: */
  lens.addEventListener("touchmove", moveLens);
  img.addEventListener("touchmove", moveLens);
  
  function moveLens(e) {
    var pos, x, y;
    /* Prevent any other actions that may occur when moving over the image */
    e.preventDefault();
    /* Get the cursor's x and y positions: */
    pos = getCursorPos(e);
    /* Calculate the position of the lens: */
    x = pos.x - (lens.offsetWidth / 2);
    y = pos.y - (lens.offsetHeight / 2);
    /* Prevent the lens from being positioned outside the image: */
    if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
    if (x < 0) {x = 0;}
    if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
    if (y < 0) {y = 0;}
    /* Set the position of the lens: */
    lens.style.left = x + "px";
    lens.style.top = y + "px";
    /* Display what the lens "sees": */
    result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
  }
  function getCursorPos(e) {
    var a, x = 0, y = 0;
    e = e || window.event;
    /* Get the x and y positions of the image: */
    a = img.getBoundingClientRect();
    /* Calculate the cursor's x and y coordinates, relative to the image: */
    x = e.pageX - a.left;
    y = e.pageY - a.top;
    /* Consider any page scrolling: */
    x = x - window.pageXOffset;
    y = y - window.pageYOffset;
    return {x : x, y : y};
  }
  
  function changeMag(ele) {
	lens.style.width = Math.abs(ele.value) + "vw";
	lens.style.height = Math.abs(ele.value) + "vh";
	if (lens.offsetWidth > img.width) {
		let w = lens.offsetWidth * (img.width / lens.offsetWidth);
		let h = lens.offsetHeight * (img.width / lens.offsetWidth);
		lens.style.width = w + "px";
		lens.style.height = h + "px";
	}
	if (lens.offsetHeight > img.height) {
		let w = lens.offsetWidth * (img.height / lens.offsetHeight);
		let h = lens.offsetHeight * (img.height / lens.offsetHeight);
		lens.style.width = w + "px";
		lens.style.height = h + "px";
	}
	
	cx = result.offsetWidth / lens.offsetWidth;
  	cy = result.offsetHeight / lens.offsetHeight;
  	result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
  }
  
  return changeMag;
}

var change = imageZoom("srcimg", "resultimg");


function dragPanel() {

	var mousePosition;
	var offset = [0,0];
	var div;
	var isDown = false;
	
	div = document.getElementById("img-panel");
// 	div.style.position = "absolute";
// 	div.style.left = "0px";
// 	div.style.top = "0px";
	
	div.addEventListener('mousedown', function(e) {
	    isDown = true;
	    offset = [
	        div.offsetLeft - e.clientX,
	        div.offsetTop - e.clientY
	    ];
	}, true);
	
	document.addEventListener('mouseup', function() {
	    isDown = false;
	}, true);
	
	document.addEventListener('mousemove', function(event) {
	    event.preventDefault();
	    if (isDown) {
	        mousePosition = {
	
	            x : event.clientX,
	            y : event.clientY
	
	        };
	        div.style.left = (mousePosition.x + offset[0]) + 'px';
	        div.style.top  = (mousePosition.y + offset[1]) + 'px';
	    }
	}, true);
	
}

dragPanel();

</script>

</body>
</html>