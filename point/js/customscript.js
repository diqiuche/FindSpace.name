var $ = jQuery.noConflict();
/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function($) { 
	$('#navigation ul.sub-menu, #navigation ul.children').hide(); // hides the submenus in mobile menu too
	$('#navigation li').hover( 
		function() {
			$(this).children('ul.sub-menu, ul.children').slideDown('fast');
		}, 
		function() {
			$(this).children('ul.sub-menu, ul.children').hide();
		}
	);
});

/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
jQuery(function() {
	var pull 		= jQuery('#pull');
		menu 		= jQuery('nav > ul');
		menuHeight	= menu.height();
	jQuery(pull).on('click', function(e) {
		e.preventDefault();
		menu.slideToggle();
	});
});

/*----------------------------------------------------
/* Scroll to top footer link script
/*--------------------------------------------------*/
jQuery(document).ready(function(){
    jQuery('a[href=#top]').click(function(){
        jQuery('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });
jQuery(".togglec").hide();
	jQuery(".togglet").click(function(){
	jQuery(this).toggleClass("toggleta").next(".togglec").slideToggle("normal");
	   return true;
	});
});