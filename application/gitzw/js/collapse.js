
/* hide and display blocks */
var dropdown = document.getElementsByClassName("collapse-button");
var i;

for (i = 0; i < dropdown.length; i++) {
	
	var dropdownContent = dropdown[i].nextElementSibling;
	if (dropdownContent.classList.contains("open")) {
		dropdown[i].classList.toggle("active");
		dropdownContent.style.display = "block";
	}
	
	dropdown[i].addEventListener("click", function() {
		
		/* toggle state of calling button */
  		this.classList.toggle("active");

		var dropdownContent = this.nextElementSibling;
	  	if (dropdownContent.style.display === "block") {
	  		dropdownContent.style.display = "none";
	  	} else {
	  		dropdownContent.style.display = "block";
	  	}
	});
}