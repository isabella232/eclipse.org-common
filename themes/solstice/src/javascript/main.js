// main.js
(function($, document) {
	
	$(document).ready(function() {
		var href_hash = window.location.hash;
		// Add a class if right column is non-existant.
		if ($("#rightcolumn").length == 0) {
			$("#midcolumn").attr("class", "no-right-sidebar");
			if (href_hash) {
				window.location.hash = href_hash;
			}
		}
		// add a class if left column is non-existant.
		if ($("#leftcol").length == 0) {
			$("#midcolumn").attr("class", "no-left-nav");
			if (href_hash) {
				window.location.hash = href_hash;
			}
		}	
	});
	
	// This code will prevent unexpected menu close when
	// using some components (like accordion, forms, etc).
	$(document).on("click", ".yamm .dropdown-menu", function(e) {
	  e.stopPropagation()
	});

	// scroll button.
	$(window).on("load resize scroll", function(){
		if ($(window).width() < 1270){
			$('.scrollup').hide();
			return false;
		}
		if ($(this).scrollTop() > 100) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	});

	// scroll back to the top of the page.
	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});

})(jQuery, document);