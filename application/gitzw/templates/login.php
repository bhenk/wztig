<?php
namespace gitzw\templates;

/** @var mixed $this */
?>

<div class="login-wrapper">
	<h1>login</h1>
	<form action="/login" method="post">
		<div class="form-group <?php echo ($this->hasUserNameError() ? 'error' : ''); ?>">
			<label>Username</label> <input type="text" name="username" class="form-control"
				value="<?php echo $this->getUserName() ?>">
		</div>
		<div class="form-group <?php echo ($this->hasPasswordError()? 'error' : ''); ?>">
			<label>Password</label> <input type="password" name="password" class="form-control"> 
		</div>
		<div class="form-group"><span><?php echo $this->getMessage() ?></span></div>
		<div class="form-group">
			<input type="submit" class="btn" value="Login">
		</div>
	</form>
</div>
<div>&nbsp;</div>
