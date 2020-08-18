<?php
namespace gitzw\templates\frame;

use gitzw\site\model\SiteResources;

/** @var mixed $this */

$site = SiteResources::get();
?>

<h1 class="gitzw">Search</h1>

<div class="container large">
	<form action="<?php echo $this->action; ?>" method="post">
		
		<div class="formrw">
			<div class="form-25">
				<label for="visart">Visual artist</label>
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
				<label for="subject">Activity</label>
			</div>
			<div class="form-75">
				<select class="smallinput" id="activity" name="activity">
					<option value="all"<?php echo $this->activity == 'all' ? ' selected' : ''; ?>>all</option>
		         	<?php foreach ($site->getActivitytNames() as $name) { ?>
		         		<option value="<?php echo $name; ?>"
		         		<?php echo $this->activity == $name ? ' selected' : ''; ?>
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
			<div class="form-25">
				<label for="title_en">Title en</label>
			</div>
			<div class="form-75">
				<input type="text" id="title_en" name="title_en" value="<?php echo $this->title_en; ?>">
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="title_nl">Title nl</label>
			</div>
			<div class="form-75">
				<input type="text" id="title_nl" name="title_nl" value="<?php echo $this->title_nl; ?>">
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="media">Media</label>
			</div>
			<div class="form-75">
				<input type="text" id="media" name="media" value="<?php echo $this->media; ?>">
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="width">Width</label>
			</div>
			<div class="form-75">
				<input class="smallinput" type="text" id="width" name="width" value="<?php echo $this->width; ?>">
				[&lt;] [&gt;] [d | d.d]
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="height">Height</label>
			</div>
			<div class="form-75">
				<input class="smallinput" type="text" id="height" name="height" value="<?php echo $this->height; ?>">
				[&lt;] [&gt;] [d | d.d]
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="date">Date</label>
			</div>
			<div class="form-75">
				<input class="smallinput" type="text" id="date" name="date" value="<?php echo $this->date; ?>">
				[&lt;] [&gt;] [yyyy | yyyy-mm | yyyy-mm-dd] (/==-) [(y | m | d) == ?]
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label>Hidden state</label>
			</div>
			<div class="form-75">
				<div>		
					<input type="checkbox" id="rishidden" 
						name="rishidden" value="rishidden" 
						<?php echo $this->rIsHidden ? ' checked' : ''; ?>>
		  			<label for="rishidden">Hidden</label>
	  			</div>
	  			<div>
		  			<input type="checkbox" id="risnothidden" 
						name="risnothidden" value="risnothidden" 
						<?php echo $this->rIsNotHidden ? ' checked' : ''; ?>>
		  			<label for="risnothidden">Not hidden</label> 
		  		</div> 			
	  		</div>
	  	</div>
	  	
	  	<div class="formrw">
			<div class="form-25">
				<label>Front page state</label>
			</div>
			<div class="form-75">
				<div>
		  			<input type="checkbox" id="risfrontpage" 
						name="risfrontpage" value="risfrontpage" 
						<?php echo $this->rIsFrontPage ? ' checked' : ''; ?>>
		  			<label for="risfrontpage">Front page</label>
		  		</div>
	  			<div>
		  			<input type="checkbox" id="risnotfrontpage" 
						name="risnotfrontpage" value="risnotfrontpage" 
						<?php echo $this->rIsNotFrontPage ? ' checked' : ''; ?>>
		  			<label for="risnotfrontpage">Not front page</label>
		  		</div>	
	  		</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="longid">Id</label>
			</div>
			<div class="form-75">
				<input type="text" id="longid" name="longid" value="<?php echo $this->longId; ?>">
			</div>
		</div>
		
		<div class="formrw">
 	      <input type="submit" value="Search">	
	    </div>
	    
	</form>
</div>
<div style="height: 600px"> </div>


