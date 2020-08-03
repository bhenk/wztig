<?php
namespace gitzw\templates\admin;

use gitzw\site\model\SiteResources;

function renderSite() {
	$site = SiteResources::getSite();
	$site->loadChildren();
	$site->loadResources();
	return json_encode($site);
}

/** @var mixed $this */
?>
<h1>Visual ARtists</h1>

<div>
	<button type="button" class="collapse">Collapse to level 4</button>
	<button type="button" class="maxlvl">Show up to level 5</button>
	<button type="button" class="expand">Expand all</button>
</div>

<div id="json"></div>
<script>

var jsonObj = <?php echo renderSite(); ?>;

var jsonViewer = new JSONViewer();
document.querySelector("#json").appendChild(jsonViewer.getContainer());
jsonViewer.showJSON(jsonObj, null, 4);

var collapseBtn = document.querySelector("button.collapse");
var maxlvlBtn = document.querySelector("button.maxlvl");
var expandBtn = document.querySelector("button.expand");

collapseBtn.addEventListener("click", function() {
	jsonViewer.showJSON(jsonObj, null, 4);
});

maxlvlBtn.addEventListener("click", function() {
	jsonViewer.showJSON(jsonObj, 5);
});

expandBtn.addEventListener("click", function() {
	jsonViewer.showJSON(jsonObj, null);
});
</script>


