<?php
namespace gitzw\templates;

/** @var mixed $this */
?>

<div class="error-head"><h1>500 Internal Server Error</h1></div>

<div class="error-link">
 <?php $this->renderActualLink() ?>
</div>

The request could not be handled due to an error.

<div class="file-info">
 <?php $this->renderTrace() ?>
</div>