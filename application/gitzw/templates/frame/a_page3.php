<?php 
namespace gitzw\templates\frame;
/** @var mixed $this */ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php $this->renderTitle(); ?></title>
<?php $this->renderMetaDescription(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
<link rel="stylesheet" href="/css/normalize.css">
<link rel="stylesheet" href="/css/frame.css">
<?php $this->renderStylesheets(); ?>
<script type="text/javascript" src="/js/gitzw.js"></script>
<?php $this->renderScriptLinks(); ?>
<?php $this->renderCanonicalURI(); ?>
</head>
<body>
	<div id="page-container">
		<div id="content-wrap">
			<div class="row">
				<!-- 1st column -->
	 			<div class="col-3 col-s-3 place"></div>
				<div class="col-3 col-s-3 menu">
					<?php $this->renderLogo(); ?>
					<?php $this->renderNavigation(); ?>
					<?php $this->renderButtonPanel(); ?>
				</div>
				<!-- end 1st column -->
				<!-- 2nd column -->
				<div class="col-7 col-s-9">	
					<?php $this->renderContent(); ?>	
				</div>
				<!-- end 2nd column -->
				<!-- 3th column -->
				<div class="col-2 col-s-12">
					<?php $this->renderThirdColumn(); ?>
				</div>
				<!-- end 3th column -->
			</div>
		</div>
		<?php $this->endRenderContent(); ?>
		<?php $this->renderStructuredData(); ?>
		<footer id="footer">
			<?php $this->renderFooter(); ?>
		</footer>
	</div>
</body>
</html>
