
(function($)
{	
    "use strict";
	
	$(document).ready(function()
	{	   
		/**
		 * Remove Themes duplicated language switcher flags from Burger menu
		 *		- exist in secondary menu
		 *		- exist beside search icon
		 */
		$('body').on( 'avia_burger_list_created', '.av-burger-menu-main a', function(){
			var s = $(this);
			
				//	allow DOM to build
			setTimeout(function(){
				var switchers = s.closest('.avia-menu.av-main-nav-wrap').find('.av-burger-overlay').find('.language_flag');
				switchers.each( function(){
							$(this).closest('li').remove();
					});
				}, 200);
		});
	});
	
	
})( jQuery );
