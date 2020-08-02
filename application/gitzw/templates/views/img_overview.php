<?php
namespace gitzw\templates\views;

/** @var mixed $this */
?>

<div class="img-data-container">
<div class="img-container">
<img src="<?php $this->getImageFile('_t') ?>"/>
</div>
<div class="img_data">
<div class="table-data">
<div><!-- row -->
<div> </div><div><?php $this->getTitle() ?></div>
</div><!-- end row -->
<div><!-- row -->
<div>m</div><div><?php $this->getTechnique() ?></div>
</div><!-- end row -->
<div><!-- row -->
<div>d</div><div><?php $this->getDimensions() ?></div>
</div><!-- end row -->
<div><!-- row -->
<div>y</div><div><?php $this->getDate() ?></div>
</div><!-- end row -->
</div>
</div>
</div>