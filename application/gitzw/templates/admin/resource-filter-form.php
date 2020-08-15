<?php
namespace gitzw\templates\admin;

use gitzw\site\model\SiteResources;

/** @var mixed $this */

$site = SiteResources::get();
?>

<h1 class="gitzw">Search resources</h1>

<div class="container larger">
	<form action="<?php echo $this->action; ?>" method="post">
		
		<div class="formrw">
			<div class="form-25">
				<label for="visart">Visart</label>
			</div>
			<div class="form-75">
				<select class="smallinput" id="visart" name="visart">
					<option value="all"<?php echo $this->visart == 'all' ? ' selected' : ''; ?>>all</option>
		         	<?php foreach ($site->getVisartNames() as $name) { ?>
		         		<option value="<?php echo $name; ?>"
		         		<?php echo $this->visart == $name ? ' selected' : ''; ?>
		         		><?php echo $name; ?></option>
					<?php } ?>
		        </select>
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="subject">Subject</label>
			</div>
			<div class="form-75">
				<select class="smallinput" id="subject" name="subject">
					<option value="all"<?php echo $this->subject == 'all' ? ' selected' : ''; ?>>all</option>
		         	<?php foreach ($site->getSubjectNames() as $name) { ?>
		         		<option value="<?php echo $name; ?>"
		         		<?php echo $this->subject == $name ? ' selected' : ''; ?>
		         		><?php echo $name; ?></option>
					<?php } ?>
		        </select>
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="category">Category</label>
			</div>
			<div class="form-75">
				<select class="smallinput" id="category" name="category">
					<option value="all"<?php echo $this->category == 'all' ? ' selected' : ''; ?>>all</option>
		         	<?php foreach ($site->getCategoryNames() as $name) { ?>
		         		<option value="<?php echo $name; ?>"
		         		<?php echo $this->category == $name ? ' selected' : ''; ?>
		         		><?php echo $name; ?></option>
					<?php } ?>
		        </select>
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="year">Year</label>
			</div>
			<div class="form-75">
				<select class="smallinput" id="year" name="year">
					<option value="all"<?php echo $this->year == 'all' ? ' selected' : ''; ?>>all</option>
		         	<?php foreach ($site->getYearNames() as $name) { ?>
		         		<option value="<?php echo $name; ?>"
		         		<?php echo $this->year == $name ? ' selected' : ''; ?>
		         		><?php echo $name; ?></option>
					<?php } ?>
		        </select>
			</div>
		</div>
		
		<div class="formrw">
 	      <input type="submit" value="Search">	
	    </div>
	    
	</form>
</div>
<div style="height: 400px"> </div>


