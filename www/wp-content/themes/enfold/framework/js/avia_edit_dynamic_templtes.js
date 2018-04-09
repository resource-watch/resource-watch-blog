/**
 * This file holds the main javascript functions needed to edit dynamic option pages on the fly and also add elements to these dynamic option pages
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.1
 * @package 	AviaFramework
 */
 


jQuery(function($) { $('.avia_sortable').avia_edit_dynamic_templates(); });



(function($)
{
	avia_framework_globals.avia_ajax_action = false;

	$.fn.avia_edit_dynamic_templates = function(variables) 
	{
		return this.each(function()
		{
			//gather form data
			var container = $(this);
			if(container.length != 1) return;
			
			container.sortable({
				
				handle: '.avia-row-portlet-header',
				cancel: 'a',
				items: '.avia_row',
				update: function(event, ui) 
				{
					$('.avia_button_inactive').removeClass('avia_button_inactive');
				}

			});
			
			//disable text selection in the header	
			$( ".avia-row-portlet-header" ).disableSelection();	
			
			$('.avia-item-edit', container).live('click', function()
			{
				var edit_link = $(this),
					container = edit_link.parents('.avia_row:eq(0)'),
					content = $('.avia-portlet-content', container);
				
				if(content.is(':visible'))
				{
					content.slideUp(200);
				}
				else
				{
					content.slideDown(200);
				}
				
				return false;
				
			});
			
			

		});
	}
	
})(jQuery);	 


