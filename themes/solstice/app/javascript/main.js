// main.js
(function($, document) {
	// This code will prevent unexpected menu close when 
	// using some components (like accordion, forms, etc).
	$(document).on("click", ".yamm .dropdown-menu", function(e) {
	  e.stopPropagation()
	})	
	
	$(document).ready(function() {
		// Add a class if right column is non-existant.
		if($("#rightcolumn").length == 0) {
			$("#midcolumn").attr("class", "no-right-sidebar");	
		}
		// add a class if left column is non-existant.
		if($("#aside-left-nav").length == 0) {
			$("#midcolumn").attr("class", "no-left-nav");	
		}
	});
	
	
})(jQuery, document);