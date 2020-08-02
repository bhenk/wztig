<?php
namespace gitzw\templates\views;

/** @var mixed $this */
// displays full name of, and a link to a domain.
?>
<div class="name-blok">
	<a href="/<?php echo $this->getFullNamePath() ?>"><?php echo $this->getFullName() ?></a>
</div>
<div class="at-blok">
	<span>&nbsp;= @&nbsp;</span>
	<a href="/<?php echo $this->getFullNamePath() ?>">gitzw.art/<?php echo $this->getName() ?></a>
</div>