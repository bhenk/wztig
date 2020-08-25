<?php
namespace gitzw\templates\frame;

/** @var mixed $this */
$url = "'".$this->action."'";
?>

<h1 class="gitzw byellow">Find</h1>

<div class="container large">
	<form action="<?php echo $this->action; ?>" method="post">
		
		<div class="formrw">
			<div class="form-25">
				<label for="visart">Names</label>
			</div>
			<div class="form-75">
				<select class="mediuminput" id="visart" name="visart" onchange="updateForm(<?php echo $url; ?>)"></select>
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="activity">Activity</label>
			</div>
			<div class="form-75">
				<select class="mediuminput" id="activity" name="activity" onchange="updateForm(<?php echo $url; ?>)"> </select>
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="category">Category</label>
			</div>
			<div class="form-75">
				<select class="mediuminput" id="category" name="category" onchange="updateForm(<?php echo $url; ?>)"></select>
			</div>
		</div>
		
		<div class="formrw">
			<div class="form-25">
				<label for="year">Year</label>
			</div>
			<div class="form-75">
				<select class="mediuminput" id="year" name="year"></select>
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
				[&lt;] [&gt;] yyyy[-mm[-dd]], [ymd] | ?, - | /
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

<script>
window.onload = setSelectors('<?php echo $this->getJsonForSelects(); ?>');
</script>


