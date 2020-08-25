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
<script type="text/javascript" src="/js/zoom.min.js"></script>
</head>
<body>

<div class="img-zoom-container">		
  	<div id="resultimg" class="img-zoom-result"></div>
</div>

<div id="img-panel" class="img-panel">
	<img id="srcimg" src="<?php echo $data1['location']; ?>" width="<?php echo round($data1['size'][0] / 15); ?>" 
		height="<?php echo round($data1['size'][1] / 15); ?>" alt="<?php echo $this->imgId; ?>">
</div>

<div class="slider-wrap" title="magnification factor">
	<input class="slider" id="slider" type="range" min="-15" max="-3" value="-9" onchange="change(this)">
	<!-- initial value of magnification is set in css on zoom.css .img-zoom-lens width and height -->	
</div>	


<script>
var change = imageZoom("srcimg", "resultimg");
dragPanel();
</script>

</body>
</html>