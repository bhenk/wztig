<?php
namespace gitzw\templates\admin;

/** @var mixed $this */
?>
<h1><?php echo $this->getUserName(); ?></h1>

<h2>Visual ARtists</h2>

<?php $this->renderVarViews(); ?>


