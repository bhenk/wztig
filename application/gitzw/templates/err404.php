<?php
namespace gitzw\templates;

/** @var mixed $this */
?>

<div class="error-head"><h1>404 Not Found</h1></div>

Unknown resource indicated by the url

<div class="error-link">
 <?php $this->renderActualLink() ?>
</div>

<div class="file-info">
 <?php $this->renderFileTrace() ?>
</div>