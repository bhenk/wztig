<?php
namespace gitzw\templates\admin;

/** @var mixed $this */
?>
<div class="var-name"><?php echo $this->getVarName(); ?></div>

<div class="code-button"><span>data&nbsp;&nbsp;&#x25BC;</span></div>
<pre class="code"><?php echo $this->getVarData(); ?></pre>