/**
 * This file holds the main javascript functions needed for the option pages. also holds the alert plugin to notify users
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */
 
jQuery(function($) {
    
    $('#avia_options_page').avia_framework_option_pages();
    $('#avia_options_page').avia_create_option_navigation();
    $('#avia_options_page .avia_tab_container').avia_media_advanced_plugin();
    $('body').avia_popups();
	$('body').trigger('avia_options_page_loaded');
  });



(function($)
{
	$.fn.avia_create_option_navigation = function(single_page) 
	{
		return this.each(function()
		{
			if(!$('#avia_options_page').length) return;
		
			var container = $(this),
				innerContainer =  $('.avia_options_container',container),
				headContainer = $('.avia_section_header',container),
				sidebar = $('.avia_sidebar_content'),
				urlHash = window.location.hash.replace(/^\#goto_/,"avia_"),
				hashActive = $('.avia_subpage_container', container).filter('[id="'+urlHash+'"]');	
			
	
			headContainer.each(function()
			{
				var heading = $(this),
					subContainer = heading.parent('.avia_subpage_container'),
					hashtarget	= subContainer.attr('id').replace(/^\avia_/,"goto_");
					
					if(hashActive.length)
					{
						if(subContainer.is('#'+urlHash))
						{
							heading.addClass('avia_active_nav');
							$('.avia_subpage_container').removeClass('avia_active_container');
							subContainer.addClass('avia_active_container');
						}
					}
					else
					{
						if(subContainer.is(':visible'))
						{
							heading.addClass('avia_active_nav');
						}
					}
					
					
					heading.clone(false)
						   .appendTo(sidebar)
						   .css({display:'block'})
						   .addClass(hashtarget)
						   .click(function()
						   {
						   		if(!subContainer.is(':visible'))
						   		{
						   			$('.avia_subpage_container').removeClass('avia_active_container');
						   			subContainer.addClass('avia_active_container');
						   			$('.avia_active_nav').removeClass('avia_active_nav');
						   			$(this).addClass('avia_active_nav');
						   		}
						   });
				});
				
				
				innerContainer.find('a[href*="goto_"]').on('click', function()
				{
					$(this.hash.replace("#",".")).trigger('click');
					return false;
				});
				

		});
		
		
		
	}
})(jQuery);	





(function($)
{
	$.fn.avia_framework_option_pages = function(variables) 
	{
		return this.each(function()
		{
			//gather form data
			var container = $(this);
			if(container.length != 1) return;
			
			var saveButtons = $('.avia_submit', this),
				resetButtons = $('.avia_reset', this),
				importButton = $('.avia_import_button', this),
				importParentSettingsButton = $('.avia_import_parent_button', this),
				hiddenDataContainer = $('#avia_hidden_data', this),
				saveData = {
								container: 		$(this),
								ajaxUrl :		$('input[name=admin_ajax_url]', hiddenDataContainer).val(),
								prefix :		$('input[name=avia_options_prefix]', hiddenDataContainer).val(),
								optionSlug :	$('input[name=avia_options_page_slug]', hiddenDataContainer).val(),
								action :		$('input[name=action]', hiddenDataContainer).val(),
								actionReset :	$('input[name=resetaction]', hiddenDataContainer).val(),
								nonce  :		$('input[name=avia-nonce]', hiddenDataContainer).val(),
								nonceReset  :	$('input[name=avia-nonce-reset]', hiddenDataContainer).val(),
								nonceImport  :	$('input[name=avia-nonce-import]', container).val(),
								nonceImportParent  :	$('input[name=avia-nonce-import-parent]', container).val(),
								ref	   :		$('input[name=_wp_http_referer]', hiddenDataContainer).val(),
								first_call:		$('input[name=avia_options_first_call]', hiddenDataContainer),
								saveButtons: 	saveButtons
							 };

						
			//bind actions:
			saveButtons.bind('click', {set: saveData}, methods.save); 		//saves the current form
			resetButtons.bind('click', {set: saveData}, methods.reset); 	//resets the option page
			importButton.bind('click', {set: saveData}, methods.do_import); //imports dummy daa
			importParentSettingsButton.bind('click', {set: saveData}, methods.do_parent_import); //imports parent theme data
			//
			//add "form listener"
			methods.activateSaveButton(container);
			
			//sidebar toggle
			methods.sidebarToggle(container);
			
			//default saving to database on first call
			if(saveData.first_call.length > 0)
			{
				setTimeout(function(){ methods.save(saveData, true); }, 1000);
			}
			
		});
	};
	
	var	methods = {
				
		/**
		 * adds the functionality for the sidebar toggle on the left of the option pages
		 */
		 
		sidebarToggle: function(container)
		{
			var button = $('.avia_shop_option_link', container),
				wrapContainer = $('.avia_options_page_inner', container),
				allSubContainer = $('.avia_subpage_container', container);
				value = button.text();
				
				button.click(function()
				{
					if(wrapContainer.is('.avia_sidebar_active'))
					{
						wrapContainer.removeClass('avia_sidebar_active');
						button.html('[-]');
					}
					else
					{
						wrapContainer.addClass('avia_sidebar_active');
						button.html(value);
					}
					
					return false;
				});
		
		},
		
		
		
		/**
		 * Save Buttons are not active by default. They get active when the user changes an option 
		 */
		 
		activateSaveButton: function(container)
		{	
			
			var saveButton = $('.avia_header .avia_button_inactive, .avia_footer .avia_button_inactive'),
				elements = $('input, select, textarea', container).not('.avia_button_inactive').not('.avia_dont_activate_save_buttons');
				
				//bind click events
				elements.bind('keydown change', function(){ saveButton.removeClass('avia_button_inactive');});
				$('.avia_clone_set, .avia_remove_set, .avia_dynamical_add_elements').bind('click', function(){ saveButton.removeClass('avia_button_inactive'); });
		},
		
		/**
		 *  SAVE: gather all form data and convert it to a single string, then send that string via ajax request to the admin-ajax.php file
		 *  
		 */
 
		save: function(passed, hiddensave)
		{
			if(typeof hiddensave == 'undefined') hiddensave = false;
		
			var me = hiddensave == true ? passed : passed.data.set,
				buttonClicked = $(this),		//button that was clicked
				elements	= $('input:text, input:hidden, input:radio:checked, input:checkbox, select, textarea','.avia_options_container'), //elements with values
				dataString = "";		// data string passed to the ajax script
			
			//if no options have changed do not save
			if(buttonClicked.is('.avia_button_inactive') && !hiddensave) return false;
			
			
			 
			elements.each(function()
			{
				var currentElement = $(this),					//form element we are currently iterating
					value = currentElement.val(),				//field value
					name = currentElement.attr('name');			//field name
				
				if(name != '')
				{
					//special case for inputs:checkbox set their value to empty if they are not checked
					if(currentElement.is('input:checkbox') && !currentElement.is('input:checked')) { value = "disabled"; }
						
					dataString  += "&" + name + "=" + encodeURIComponent(value);
				}
			});
			
			dataString = dataString.substr(1);
			///////// end of building the data string /////////
			
			
			//sort order for dynamic elements
			var dynamicOrder = "",
				dynamicElements = $('.avia_section, .avia_set').not(".avia_single_set .avia_section"),
				id_order_string = "";
				
			if(dynamicElements.length && $('.avia_row').length)
			{
				
				dynamicElements.each(function()
				{
					id_order_string = this.id.replace(/^avia_/,'').replace(/-__-0$/,'');
					dynamicOrder += id_order_string + '-__-';
				});
			}
			  
			
			
			//sends the request. calls the the wp_ajax_avia_ajax_save_options_page php function
			$.ajax({
					type: "POST",
					url: me.ajaxUrl,
					data: 
					{
						action: me.action,
						_wpnonce: me.nonce,
						_wp_http_referer: me.ref,
						prefix: me.prefix,
						slug: me.optionSlug,
						data: dataString,
						dynamicOrder: dynamicOrder
						
					},
					beforeSend: function()
					{
						if(hiddensave) return;
					
						//show loader
						 $('.avia_header .avia_loading, .avia_footer .avia_loading',  me.container).css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
						
						//set buttons to inactive
						me.saveButtons.addClass('avia_button_inactive');
					},
					error: function()
					{
						if(hiddensave) return;
					
						//allow saving again
						$('body').avia_alert({the_class:'error', text:'Saving didnt work! <br/> Please reload the page and try again', show:4500});
						me.saveButtons.removeClass('avia_button_inactive');
					},
					success: function(response)
					{
						if(hiddensave) return;
					
						//reset the input elements that tell the php script to clone or remove
						if(response.match('avia_save'))
						{
							$('body').avia_alert();
						}
						else
						{
							var answer = "";
							
							if(response.length > 3)
							{
								answer = 'Saving didnt work! <br/>The script returned the following error: <br/><br/>'+response;
							}
							else
							{
								answer = 'Saving didnt work! <br/> Please reload the page and try again';
							}
							
							$('body').avia_alert({the_class:'error', text: answer , show:4500});
							me.saveButtons.removeClass('avia_button_inactive');
						}
						
					},
					complete: function(response)
					{	
						if(hiddensave) return;
					
						$('.avia_loading',  me.container).fadeOut();
						
					}
				});
			
			return false;
		},
		
		do_parent_import: function(passed)
		{
			var button = $(this),
				me = passed.data.set,
				waitLabel = $('.avia_import_parent_wait', me.container),
				answer = "",
				activate = true;
								
			
			if(button.is('.avia_button_inactive')) return false;
			
			activate = confirm('Importing the Parent Theme Settings will overwrite your current Settings. Proceed anyways?')
			if(activate == false) return false;
			
			$.ajax({
						type: "POST",
						url: me.ajaxUrl,
						data: 
						{
							action: 'avia_ajax_import_parent_settings',
							_wpnonce: me.nonceImportParent,
							_wp_http_referer: me.ref
						},
						beforeSend: function()
						{
							//show loader
							$('.avia_import_loading_parent',  me.container).css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
							button.addClass('avia_button_inactive');
							waitLabel.slideDown();
						},
						error: function()
						{
							//script error occured
							$('body').avia_alert({	the_class:'error', 
													text:'Importing didnt work! <br/> You might want to try reloading the page and then try again', 
													show:4500});
							button.removeClass('avia_button_inactive');
							
						},
						success: function(response)
						{
							if(response.match('avia_import'))
							{
								var resultcontainer = $('.avia_import_result_parent', me.container);
								//resultcontainer.css('display','none').html(response).slideDown();
								$('body').avia_alert({text: 'Alright!<br/>Import worked out, no problems whatsoever. <br/>The page will now be reloaded to reflect the changes'}, function()
								{
									window.location.hash = "#wpwrap";
						 			window.location.reload(true);
								});
								
							}
							else
							{
								button.removeClass('avia_button_inactive');
								//script was called but aborted before finishing import
								$('body').avia_alert({	the_class:'error', 
														text:'Importing didnt work! <br/> You might want to try reloading the page and then try again <br/> (The script returned the following message: <br/><br/>'+response+')', 
														show:4500});
							}
						},
						complete: function(response)
						{	
							$('.avia_import_loading_parent',  me.container).fadeOut();
							waitLabel.slideUp();
						}
					});
					
			return false;
		},
		
		/**
		 * Start Importing the wordpress dummy content if a user clicks this button
		 */
		do_import: function(passed)
		{
			
			var button = $(this),
				me = passed.data.set,
				container = button.parents('.avia_section').eq(0),
				waitLabel = $('.avia_import_wait', container),
				answer = "",
				activate = true,
				message = "Importing the dummy data will overwrite your current Theme Option settings and delete any custom Templates you have built with the template Builder. Proceed anyways?";
			
			
								
			if(button.is('.avia_button_inactive')) return false;
			if(button.is('.avia_import_image')) message = "Importing the dummy data will overwrite your current Theme Option settings. Proceed anyways?";
			
			
			activate = confirm(message);
			if(activate == false) return false;
			
			$.ajax({
						type: "POST",
						url: me.ajaxUrl,
						data: 
						{
							action: 'avia_ajax_import_data',
							_wpnonce: me.nonceImport,
							_wp_http_referer: me.ref,
							files: button.data('files')
						},
						beforeSend: function()
						{
							//show loader
							$('.avia_import_loading',  container).css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
							button.addClass('avia_button_inactive');
							waitLabel.slideDown();
						},
						error: function()
						{
							//script error occured
							$('body').avia_alert({	the_class:'error', 
													text:'Importing didnt work! <br/> You might want to try reloading the page and then try again', 
													show:4500});
							button.removeClass('avia_button_inactive');
							
						},
						success: function(response)
						{
							if(response.match('avia_import'))
							{
								response = response.replace('avia_import','')
												   .replace('<p>Remember to update the passwords and roles of imported users.</p>','');
								
								var resultcontainer = $('.avia_import_result', me.container);
								//resultcontainer.css('display','none').html(response).slideDown();
								$('body').avia_alert({text: 'Alright sparky!<br/>Import worked out, no problems whatsoever. <br/>The page will now be reloaded to reflect the changes'}, function()
								{
									window.location.hash = "#wpwrap";
						 			window.location.reload(true);
								});
								
							}
							else
							{
								button.removeClass('avia_button_inactive');
								//script was called but aborted before finishing import
								$('body').avia_alert({	the_class:'error', 
														text:'Importing didnt work! <br/> You might want to try reloading the page and then try again <br/> (The script returned the following message: <br/><br/>'+response+')', 
														show:4500});
							}
						},
						complete: function(response)
						{	
							$('.avia_import_loading',  container).fadeOut();
							waitLabel.slideUp();
						}
					});
					
			return false;
		},
		
		
		
		
		
		
		/**
		 *  reset all options by removing the database set that saves them
		 */
		
		reset: function(passed)
		{
			var me = passed.data.set,
				answer = confirm("This will delete every theme setting made so far and revert the theme option pages to factory settings. \nDo you really want to do that? ");
			
			if(answer)
			{
				$.ajax({
						type: "POST",
						url: me.ajaxUrl,
						data: 
						{
							action: me.actionReset,
							_wpnonce: me.nonceReset,
							_wp_http_referer: me.ref
						},
						beforeSend: function()
						{
							//show loader
							$('.avia_header .avia_loading, .avia_footer .avia_loading',  me.container).css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
						},
						error: function()
						{
							//allow saving again
							$('body').avia_alert({the_class:'error', text:'Resetting didnt work! <br/> Please wait a few seconds and try again', show:4500});
							
						},
						success: function(response)
						{
							if(response.match('avia_reset'))
							{
								window.location.hash = "#wpwrap";
						 		window.location.reload(true);
							}
							else
							{	
								var answer = "";
								
								if(response.length > 3)
								{
									answer = 'Resetting didnt work! <br/>The script returned the following error: <br/><br/>'+response;
								}
								else
								{
									answer = 'Resetting didnt work! <br/> Please wait a few seconds and try again';
								}
							
								$('body').avia_alert({	the_class:'error', 
														text: answer, 
														show:4500});
							}
						
						},
						complete: function(response)
						{	
							$('.avia_loading',  me.container).fadeOut();
						}
					});
			}
			
			return false;
		}
		
		
		 
		
		
		
		// end save method
	};
	
	
})(jQuery);	 




(function($)
{
	$.fn.avia_alert = function(variables, callback) 
	{
		var defaults = 
		{
			the_class: 'success',		//success, alert
			text:  'Alright sparky!<br/>All Options saved, no problems whatsoever.',
			show:	2200
		};
		
		var options = $.extend(defaults, variables);
		
		return this.each(function()
		{
			var container = $(this),
				notification = $('<div/>').addClass('avia_notification avia_notification_'+options.the_class)
										  .css('opacity',0)
										  .html('<span class="avia_notification_icon"></span><div>'+options.text+'</div>')
										  .appendTo(container);
										  
				notification.animate({opacity:0.9}, function()
				{
					notification.delay(options.show).fadeOut(function()
					{
						notification.remove();
						if(typeof callback == 'function') callback();
					});
				});
		});
	};
})(jQuery);	



(function($)
{
	$.fn.avia_popups = function(variables, callback) 
	{
		var defaults = 
		{
			template: '<div class="avia-popup {extra_class}"><div class="avia-popup-inner"><a href="#" class="popup-close script-close-avia-popup">×</a>{content}</div></div><div class="avia-popup-backdrop"></div>',
			selector: '*[data-avia-popup], .av-modal-image'
		};
		
		var options  = $.extend(defaults, variables),
			_self	 = this,
			_body	 = $('body'),
			popup_open = false,
			$template = $();
		
		_self.on('click', options.selector, function()
		{
			var current  		= $(this),
				templateName 	= current.data('avia-popup'),
				template		= "",
				extra_class		= "";
				
				if( current.is('.av-modal-image') ) 
				{
					template = "<img src='" + this.href + "' alt='' title='' class='av-modal-popup-image' />";
					extra_class = "av-modal-window-autoposition";
				}
				else
				{
					template = $('#'+ templateName).html();
				}
				
				options.template = options.template.replace('{content}', template);
				options.template = options.template.replace('{extra_class}', extra_class);
				
				$template = $(options.template).appendTo(_body);
				popup_open = true;
				return false;
		});
		
		
		_self.on('click', '.script-close-avia-popup, .avia-popup-backdrop', function()
		{
			popup_open = false;
			$('.avia-popup-backdrop, .avia-popup').remove();
			return false;
		});
		
		_self.on('keydown', function(e)
		{
			if (popup_open == true && e.keyCode == 27)
			{ 
				popup_open = false;
				$('.avia-popup-backdrop, .avia-popup').remove();
				return false;
			}
		});
		

	};
	
	

	
	
	
})(jQuery);	

