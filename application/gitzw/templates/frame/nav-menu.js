/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content */

var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  
  	/* neutralize all other buttons */
  	var dropdowns = document.getElementsByClassName("dropdown-btn");
	var i;
  	for (i = 0; i < dropdowns.length; i++) {
  		if (!this.isSameNode(dropdowns[i])) {
      		dropdowns[i].classList.remove("active");
      		var dropdownContent = dropdowns[i].nextElementSibling;
      		dropdownContent.style.display = "none";
      	}
  	}
  	
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