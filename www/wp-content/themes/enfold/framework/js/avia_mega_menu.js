/**
 * This file holds the main javascript functions needed to improve the avia mega menu backend
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */

(function($)
{
	var avia_mega_menu = {

		recalcTimeout: false,

		// bind the click event to all elements with the class avia_uploader
		bind_click: function()
		{
			var megmenuActivator = '.menu-item-avia-megamenu,#menu-to-edit';

				$(document).on('click', megmenuActivator, function()
				{
					var checkbox = $(this),
						container = checkbox.parents('.menu-item:eq(0)');

					if(checkbox.is(':checked'))
					{
						container.addClass('avia_mega_active');
					}
					else
					{
						container.removeClass('avia_mega_active');
					}

					//check if anything in the dom needs to be changed to reflect the (de)activation of the mega menu
					avia_mega_menu.recalc();

				});
		},

		recalcInit: function()
		{
            $(document).on('mouseup', '.menu-item-bar', function(event, ui)
			{
				if(!$(event.target).is('a'))
				{
					clearTimeout(avia_mega_menu.recalcTimeout);
					avia_mega_menu.recalcTimeout = setTimeout(avia_mega_menu.recalc, 500);
				}
			});
		},


		recalc : function()
		{
			var menuItems = $('.menu-item','#menu-to-edit');

			menuItems.each(function(i)
			{
				var item = $(this),
					megaMenuCheckbox = $('.menu-item-avia-megamenu', this);

				if(!item.is('.menu-item-depth-0'))
				{
					var checkItem = menuItems.filter(':eq('+(i-1)+')');
					if(checkItem.is('.avia_mega_active'))
					{
						item.addClass('avia_mega_active');
						megaMenuCheckbox.attr('checked','checked');
					}
					else
					{
						item.removeClass('avia_mega_active');
						megaMenuCheckbox.attr('checked','');
					}
				}





			});

		},

		//clone of the jqery menu-item function that calls a different ajax admin action so we can insert our own walker
		addItemToMenu : function(menuItem, processMethod, callback) {
			var menu = $('#menu').val(),
				nonce = $('#menu-settings-column-nonce').val();

			processMethod = processMethod || function(){};
			callback = callback || function(){};

			params = {
				'action': 'avia_ajax_switch_menu_walker',
				'menu': menu,
				'menu-settings-column-nonce': nonce,
				'menu-item': menuItem
			};

			$.post( ajaxurl, params, function(menuMarkup) {
				var ins = $('#menu-instructions');
				processMethod(menuMarkup, params);
				if( ! ins.hasClass('menu-instructions-inactive') && ins.siblings().length )
					ins.addClass('menu-instructions-inactive');
				callback();
			});
		}

};



	$(function()
	{
		avia_mega_menu.bind_click();
		avia_mega_menu.recalcInit();
		avia_mega_menu.recalc();
		if(typeof wpNavMenu != 'undefined'){ wpNavMenu.addItemToMenu = avia_mega_menu.addItemToMenu; }
 	});


})(jQuery);