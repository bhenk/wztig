<?php
namespace gitzw\templates\views;

use gitzw\site\data\Site;
use gitzw\site\data\ImageData;
use gitzw\site\data\Security;
use gitzw\site\data\Aat;

/** @var mixed $this */

?>
<div class="resource-view">
	<div class="resource">
		<img src="<?php echo $this->imgData['location']; ?>" 
			alt="main representation of <?php echo $this->resource->getLongId(); ?>">

		<?php if ($this->hasPrevious()) { ?>
			<div class="lrpage-prev"><a href="<?php echo $this->previousUrl(); ?>"><span title="next">&#9664;</span></a></div>
		<?php } ?>
		<?php if ($this->hasNext()) { ?>
			<div class="lrpage-next"><a href="<?php echo $this->nextUrl(); ?>"><span title="previous" >&#9654;</span></a></div>
		<?php } ?>
	</div>
	<div class="subscript"><?php echo $this->resource->getSubscript(); ?></div>
	
	<div class="button-rect">
		<a href="<?php echo '/zoom/'.$this->mainRepresentation->getLocation(); ?>"><button class="small-button">zoom</button></a>
		<span></span>
		<a href="<?php echo '/exif-data/'.$this->mainRepresentation->getLocation(); ?>"><button class="small-button">exif</button></a>
		<span></span>
		<?php if (Security::get()->hasAccess()) { ?>
			<a href="<?php echo '/admin/edit-resource/'.$this->resource->getLongId(); ?>"><button class="small-button">edit</button></a>
			<span></span>
		<?php } ?>
		<span><?php echo $this->resource->getOrdinal(); ?></span>
		<span><?php echo $this->resource->hasFrontPage() ? ' &#9635 ' : ''; ?></span>
		
		<div class="representation-id">
			<span><?php echo $this->mainRepresentation->getLocation(); ?></span>
			<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
		</div>
	</div>
	
	<h1 class="resource-id">
		<span><?php echo $this->resource->getLongId(); ?></span>
		<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
	</h1>
	
	<?php foreach ($this->getRepresentations() as $id=>$rep) { 
		$ida = new ImageData(null, $id);
		?>
		<div class="resource">
		
		<?php echo $ida->getImgTag(600, 500, $id); ?>
		</div>
		<div class="subscript"><?php echo $rep->getDescription(); ?></div>
		
		<div class="button-rect">
			<a href="<?php echo '/zoom/'.$id; ?>"><button class="small-button">zoom</button></a>
			<span> </span>
			<a href="<?php echo '/exif-data/'.$id; ?>"><button class="small-button">exif</button></a>
			<span> </span>
			<div class="representation-id">
				<span><?php echo $rep->getLocation(); ?></span>
				<span title="copy id" class="copyprevious" onclick="copyPrevious(this)"> &#9776; </span>
			</div>
		</div>
		<div>
			
		</div>
	<?php } ?>
	
	<!-- meta data -->
	<div class="table-data" style="font-size: smaller;">
		<div>
			<div>about:</div>
			<div><a class="ld-link" href="<?php echo $this->resource->getFullId(); ?>">
				<?php echo $this->resource->getFullId(); ?></a>
			</div>
		</div>
		<div>
			<div>url:</div>
			<div><a class="incognito" href="<?php echo $this->resource->getFullUrl(); ?>">
				<?php echo $this->resource->getFullUrl(); ?></a>
			</div>
		</div>
		<!-- div>
			<div>image:</div>
			<div><a class="incognito" href="<?php echo $this->imgData['location']; ?>">
				<?php echo Site::get()->hostName().$this->imgData['location']; ?></a></div>
		</div -->
		<div>
			<div>title (nl):</div>
			<div><?php echo $this->resource->getTitles()['nl']; ?></div>
		</div>
		<div>
			<div>title (en):</div>
			<div><?php echo $this->resource->getTitles()['en']; ?></div>
		</div>
		
		<?php $types = $this->resource->getAdditionalTypeCodes(); 
			$left = 'type:'; 
			foreach($types as $term) {
				$fullterm = str_replace('aat:', Aat::AAT_URL, $term); ?>
		<div>
			<div><?php echo $left; $left = ''; ?></div>
			<div>
				<a class="ld-link" href="<?php echo $fullterm; ?>"><?php echo $fullterm; ?></a>
				&nbsp; <?php echo Aat::get()->getHtmlLabels($term); ?>
			</div>
		</div>
		<?php } ?>
		
		<?php $mats = $this->resource->getMaterialTypeCodes(); 
			$left = 'media:'; 
			foreach ($mats as $term) {
			$fullterm = str_replace('aat:', Aat::AAT_URL, $term); ?>
		<div>
			<div><?php echo $left; $left = ''; ?></div>
			<div>
				<a class="ld-link" href="<?php echo $fullterm; ?>"><?php echo $fullterm; ?></a>
				&nbsp; <?php echo Aat::get()->getHtmlLabels($term); ?>
			</div>
		</div>
		<?php } ?>
		
		<div>
			<div>width:</div>
			<div><?php echo $this->resource->getWidth(); ?> cm.</div>
		</div>
		<div>
			<div>height:</div>
			<div><?php echo $this->resource->getHeight(); ?> cm.</div>
		</div>
		
		<div>
			<div>date:</div>
			<div><?php echo $this->resource->getDateCreated(); ?></div>
		</div>
		
		<div>
			<?php $creatorId = $this->resource->getCreator()->getFullId(); ?>
			<div>creator:</div>
			<div>
				<a class="ld-link" href="<?php echo $creatorId; ?>"><?php echo $creatorId; ?></a>
			</div>
		</div>
	</div>
	
	
	
	<div class="ld-panel">
		<a href="<?php echo $this->resource->getResourcePath().'.json'?>"><button class="small-button">json-ld</button></a>
		<span> </span>
		<a href="<?php echo $this->resource->getResourcePath().'.rdf'?>"><button class="small-button">rdf</button></a>
		<span> </span>
		<a href="<?php echo $this->resource->getResourcePath().'.ttl'?>"><button class="small-button">n3/turtle</button></a>
		<span> </span>
		<a href="<?php echo $this->resource->getResourcePath().'.nt'?>"><button class="small-button">ntriples</button></a>
		
	</div>
	
	<div class="license">
		<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank">
			<img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" />
		</a>
	</div>
	
</div>
