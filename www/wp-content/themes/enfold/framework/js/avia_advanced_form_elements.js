/**
 * This file holds the main javascript functions needed to clone option groups and improve those form elements with
 * js behaviour
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */

var avia_callback = avia_callback || {};


jQuery(function($) {

	$('body').avia_event_listener();
    $('.avia_set').avia_clone_sets();
    $('.avia_required_container').not('.avia_delay_required .avia_required_container').avia_form_requirement();
    $('.avia_target_value').avia_target();
    $('.avia_link_controller').avia_prefill_options();
    $('.avia_onchange').avia_on_change();
    $('.avia_styling_wizard').avia_styling_wizard();
    $('.avia_verify_button').avia_verify_input();
    
    

    //unify select dropdowns
    $('body').on('change', '.avia_select_unify select', function()
    {
    	var el = $(this);
    	el.next('.avia_select_fake_val').text(el.find('option:selected').text());
    });
    
    $('.avia_select_unify select').not('.avia_multiple_select select').each(function()
    {
    	var el = $(this);
    	el.css('opacity',0).next('.avia_select_fake_val').text(el.find('option:selected').text());
    });
    
    var firstcall = $('input[name=avia_options_first_call]', '#avia_hidden_data');
    if(firstcall.length)
    {
    	//activate color scheme
    	$('.avia_link_controller_active').trigger('click');
    }
    
  });





/************************************************************************
Callback functions called by php strings
*************************************************************************/

var av_backend_maps_loaded, gm_authFailure;

(function($)
{
	avia_callback.gmaps_values = {callback: false, value: false};
	
	avia_callback.av_maps_js_api_check = function(value, callback)
	{
								//	this is only a fallback setting 
		var src			= 'https://maps.googleapis.com/maps/api/js?v=3.30&callback=av_backend_maps_loaded&key=' + value;
		
		if( 'undefined' != typeof avia_framework_globals.gmap_backend_maps_loaded && avia_framework_globals.gmap_backend_maps_loaded != '' )
		{
			src = avia_framework_globals.gmap_backend_maps_loaded + '&key=' + value;
		}		

		var	script 		= document.createElement('script');
			script.type = 'text/javascript';	
			script.src 	= src;
			
			avia_callback.gmaps_values = {
				
				callback: callback,
				value: value
			};
			
			//find the current google maps link and remove it, then append the new one
			$('script[src*="maps.google"]').remove();
			google.maps = false;
			
			document.body.appendChild(script);
	};
	
	
	av_backend_maps_loaded = function()
	{
		var valid_key 	= 0;
		var addressGeo 	= 'Stephansplatz 1 Vienna 1010 Austria';
		var geocoder 	= new google.maps.Geocoder();
		
	   	geocoder.geocode( { 'address': addressGeo}, function(results, status)
        {
			if (status === google.maps.GeocoderStatus.OK)
			{
				valid_key = true;
            }
			else if (status === google.maps.GeocoderStatus.REQUEST_DENIED)
			{
				//alert( 'ERROR: Access denied' );
			}
			else
			{
				//alert( 'ERROR: Error occured accessing the API.' );
			}
			
			avia_callback.gmaps_values.callback.call(this, valid_key);
		});
	};
	
	gm_authFailure = function()
	{	
		if(avia_callback.gmaps_values.callback)
		{
			avia_callback.gmaps_values.callback.call(this, false);
		}
	};
	
})(jQuery);	







/************************************************************************
avia_target

verifies an input field by calling a user defined ajax function
*************************************************************************/
(function($)
{
	$.fn.avia_verify_input = function(variables) 
	{
		var button = $(this), testing = false;
		
		button.on('click', function()
		{
			var clicked   		= $(this),
			container 			= clicked.parents('.avia_verification_field'),
			input				= container.find('.avia_verify_input input'),
			answer				= container.find('.av-verification-result'),
			value				= "",
			action				= clicked.data('av-verification-callback'),
			js_callback_action	= clicked.data('av-verification-callback-javascript'),
			nonce				= $('#avia_hidden_data input[name=avia-nonce]').val(),
			ref 				= $('#avia_hidden_data input[name=_wp_http_referer]').val(),
			loader				= $('.avia_header .avia_loading, .avia_footer .avia_loading'),
			js_callback_value 	= false;
			
			if(testing) return false;
			
			var server_callback = function(js_value_passed)
			{
				
						//send ajax request to the ajax-admin.php script	
						$.ajax({
								type: "POST",
								url: window.ajaxurl,
								data: 
								{
									action: 'avia_ajax_verify_input',
									key: input.attr('id'),
									avia_ajax: true,
									value: value,
									js_value: js_value_passed,
									callback: action,
									_wpnonce: nonce,
									_wp_http_referer: ref
									
								},
								beforeSend: function()
								{
									//show loader
									 loader.css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
									 clicked.addClass('avia_button_inactive');
									 testing = true;
								},
								error: function()
								{
									answer.html('Could not connect to the internet. Please reload the page and try again');
								},
								success: function(response)
								{
									if(response.indexOf('avia_trigger_save') !== -1)
									{
										$('.avia_submit:eq(0)').trigger('click');
										response = response.replace('avia_trigger_save', "");
									}
									
									answer.html(response);
									
								},
								complete: function(response)
								{	
									loader.fadeOut();
									clicked.removeClass('avia_button_inactive');
									testing = false;
								}
							});	
			}
			
			//start the validation
			value = input.val();
			
			if(window.avia_callback[js_callback_action])
			{
				loader.css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
				clicked.addClass('avia_button_inactive');
				
				window.avia_callback[js_callback_action].call(this, value, server_callback);
			}
			else
			{
				server_callback();
			}
			
			
			
			

			return false;
		});

	};
})(jQuery);	





/************************************************************************
avia_event_binding

event binding fake plugin to circumvent event cloning problems with external plugins
*************************************************************************/

(function($)
{
	$.fn.avia_event_binding = function(variables) 
	{		
		return this.each(function()
		{		
			if(window.parent && window.parent.document && variables != 'skip')
			{
				parent.jQuery(window.parent.document.body).trigger('avia_event_binding',[this]);
				return;
			}
			
			var container = $(this);
			
			if($.fn.avia_media_advanced_plugin)		container.avia_media_advanced_plugin();
			if($.fn.avia_color_picker_activation) 	container.avia_color_picker_activation();
			if($.fn.avia_clone_sets) 				container.avia_clone_sets();
			if($.fn.avia_form_requirement) 			$('.avia_required_container', container).not('.avia_delay_required .avia_required_container').avia_form_requirement();
			if($.fn.avia_target) 					$('.avia_target_value', container).avia_target();
			if($.fn.avia_prefill_options) 			$('.avia_link_controller', container).avia_prefill_options();
			if($.fn.avia_on_change) 				$('.avia_on_change', container).avia_on_change();
			
			var saveButton = $('.avia_submit'),
				elements = $('input, select, textarea', container).not('.avia_button_inactive');
			elements.bind('keydown change', function(){saveButton.removeClass('avia_button_inactive'); });
			$('.avia_clone_set, .avia_remove_set, .avia_dynamical_add_elements', container).bind('click', function(){ saveButton.removeClass('avia_button_inactive'); });
			$('.avia_select_unify select').not('.avia_multiple_select select').css('opacity',0);
			
		});
	};
})(jQuery);	


//event binding helper when executing events from an iframe
(function($)
{
	$.fn.avia_event_listener = function(variables) 
	{	
		this.bind('avia_event_binding', function(event, element)
		{
			parent.jQuery(element).avia_event_binding('skip');
		});
	};
})(jQuery);

/************************************************************************

Styling WIzard function

*************************************************************************/
(function($)
{
	var methods = {
	
		insertEL: function(event)
		{
			var _self 	= event.data.self,
				value	= _self.insertVal.val(),
				tmpl	= "";
				
			if(!value) return false;	
			_self.insertVal.val('').trigger('change');
			
			tmpl = $(_self.container.find('#avia-tmpl-wizard-' + value).html());
			tmpl.css({display:'none'}).prependTo(_self.insertContainer).slideDown();
			
			//activate color picker
			tmpl.find('.av-wizard-subcontainer-colorpicker').avia_color_picker_activation();
			
			//activate change method so 
			tmpl.find('input, select, textarea').bind('keydown change', function(){_self.saveButton.removeClass('avia_button_inactive'); });
			
			methods.recalc(_self.insertContainer);
			return false;
		},
		
		deleteEL: function(event)
		{
			var _self 	 = event.data.self,
				 current = $(this).parents('.av-wizard-element:eq(0)');
			
			current.slideUp(function()
			{
				current.remove();
				methods.recalc(_self.insertContainer);
			});
			
			//removes the inactive state from save button, so we can save the new form if no other action was performed
			_self.insertVal.trigger('change');
			
			return false;
		},
		
		recalc: function(container)
		{
			var sets = container.find('.av-wizard-element');
			
			sets.each(function(i)
			{
				var current = $(this), replaceName = current.find('[data-recalc]');
				
				replaceName.each(function()
				{
					var replaceName = $(this), replaceVal = replaceName.data('recalc').replace("{counter}", i);
					replaceName.attr( 'name' , replaceVal);
				});
			});
		}
	};


	$.fn.avia_styling_wizard = function(variables) 
	{	
		return this.each(function()
		{
			var _self = {};
			_self.container 		= $(this);
			_self.insertContainer	= _self.container.find('.av-wizard-element-container');
			_self.insertBtn			= _self.container.find('.add_wizard_el_button');
			_self.insertVal			= _self.container.find('.add_wizard_el');
			_self.saveButton 		= $('.avia_submit');
			//bind events
			_self.insertBtn.on('click', {self: _self}, methods.insertEL);
			_self.container.on('click', '.avia_remove_wizard_set' , {self: _self} , methods.deleteEL);
			
			
		});
	};
})(jQuery);	





/************************************************************************
avia_on_change function

execute a function after change event was fired
*************************************************************************/
(function($)
{
	$.fn.avia_on_change = function(variables) 
	{	
		return this.each(function()
		{
			var item 	= $(this),
				event 	= item.data('avia-onchange');	
			
			
			//available functions
			var methods = 
			{
				avia_add_google_font: function()
				{
				
					var current 	= $(this),
						value 		= current.val();
						
					if(!value) return;
						
					var cssValue 	= value.replace(/ /g, "+", value),
						parentItem 	= current.parents('.avia_control:eq(0)'),
						cssRule 	= parentItem.find('.webfont_'+this.id).remove(),
						insert		= "";
					
					if(value.indexOf("-websave") != -1)
					{
						value = value.replace(/-websave/g, "", value),
						value = value.replace(/-/g, "", value)
						insert = '<style type="text/css">.webfont_'+this.id+'{font-family:'+value.replace(/:(\d+)$/,"")+';}</style>';
					}
					else
					{
						insert = '<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family='+cssValue+'" /> <style type="text/css">.webfont_'+this.id+'{font-family:'+value.replace(/:(.+)$/,"")+';}</style>';
					}
					
					cssRule = $('<div class="webfont_'+this.id+'">'+insert+'</div>');
					cssRule.appendTo(parentItem);
				}
			};
			
			
			item.bind('change', methods[event]).trigger('change');
		});
	};
})(jQuery);	


/************************************************************************
avia_prefill_options

sets element to certain values when a controll element is clicked
*************************************************************************/
(function($)
{
	$.fn.avia_prefill_options = function(variables) 
	{	
		return this.each(function()
		{
			var item = $(this),
				siblings = item.parents('.avia_section:eq(0)').find('.avia_link_controller'),
				htmlData = item.data(),
				i = "";
				
				
				var methods = {
				
					apply: function()
					{
						siblings.removeClass('avia_link_controller_active');
						item.addClass('avia_link_controller_active');
						
						for (i in htmlData)
						{
							if(typeof htmlData[i] == "string" || typeof htmlData[i] == "number" || typeof htmlData[i] == "boolean")
							{
								var selector = i.replace( /([A-Z])/g, "-$1" ).toLowerCase();
								
								var el = $('#'+selector);
								if(el.length)
								{
									if(el.is('input[type=text]') || el.is('input[type=hidden]') || el.is('select'))
									{
										if(htmlData[i] != "" && el.is('[data-baseurl]'))
										{
											htmlData[i] = htmlData[i].replace(el.data('baseurl'), '{{AVIA_BASE_URL}}');
										}
										el.val(htmlData[i]).trigger('change');
									}
								}
							}
						}
						
						return false;
					}
				};
								
				
			item.bind('click', methods.apply );
			
			
				
		});
	};
})(jQuery);	


/************************************************************************
avia_target

injects data into a target field, based on type of data providing element
*************************************************************************/
(function($)
{
	$.fn.avia_target = function(variables) 
	{
		return this.each(function()
		{
			var item = $(this),
				container = item.parents('.avia_section:eq(0)'),
				prefix = container.find('[data-baseurl]'),
				baseurl = "",
				monitorItem = "",
				execute = "",
				values = item.val().split('::'),
				targetContainer = $('#avia_'+values[0]);
				
			if(!targetContainer.length)	targetContainer = $(values[0]);
				
			var target = $(values[1], targetContainer),
				changeProperty = values[2];	
				
				if(prefix.length) baseurl = prefix.data('baseurl');
				
				var methods = {
				
					apply: function()
					{
						var the_value = monitorItem.val(), hiddenItem = false, property = [], name = monitorItem.attr('id');
						
						if(container.is('.avia_checkbox') && !monitorItem.is(':checked'))
						{
							the_value = "";
						}	
						
						if(the_value != "" && the_value != null && baseurl) { the_value = the_value.replace('{{AVIA_BASE_URL}}', baseurl); }
						
						if(changeProperty.indexOf(',') >= 0 ) 
						{
							property = changeProperty.split(',');
						}
						else
						{
							property = [changeProperty];
						}
						
						for( var i in property)
						{
							if(container.css("display") != "block") {the_value = ""; hiddenItem = true;};
							switch(property[i])
							{
								case 'background-color': target.css({'background-color':the_value}); break;
								case 'background-image': if(!hiddenItem) target.css({'background-image':"url(" + the_value + ")"}); break;
								case 'border-color': target.css({'border-color':the_value}); break;
								case 'color': target.css({'color':the_value}); break;
								case 'set_class': target.attr({'class':the_value}); break;
								case 'set_data' : target.attr('data-' + name, the_value); break;
								case 'set_id': target.attr({'id':the_value.replace(/\./,'-')}); break;
								case 'set_id_single': target.attr({ 'id':the_value.split(" ")[0] }); break;
								case 'set_active': if(the_value != "") { target.addClass('av-active'); } else { target.removeClass('av-active'); } break;
								case 'width': target.css({'width':the_value + "%"}); break;
								default: 
								var fill_in = {};
								fill_in[property[i]] = the_value;
								target.css(fill_in); 
								break;
							}
						}
						
						
					}
				};
								
				
				if(container.is('.avia_select') || container.is('.avia_select_sidebar'))
				{
					monitorItem = container.find('select');
				}
				
				if(container.is('.avia_colorpicker'))
				{
					monitorItem = container.find('.avia_color_picker');
				}
				
				if(container.is('.avia_upload'))
				{
					monitorItem = container.find('.avia_upload_input');
				}
				
				if(container.is('.avia_checkbox'))
				{
					monitorItem = container.find('input[type=checkbox]');
				}
				
				
				
				if(typeof monitorItem != "string")
				{
					monitorItem.bind('change', function()
					{
						methods.apply();
					});				
				}
				
				setTimeout(function(){ methods.apply(); },200);
		});
	};
})(jQuery);	



/************************************************************************
avia_form_requirement

creates dependencies between various form elements: 
divs with elements get hidden or shown depending on the value of other elements
*************************************************************************/

(function($)
{
	$.fn.avia_form_requirement = function(variables) 
	{	
		return this.each(function()
		{
			var container = $(this),
				basicData = { 
							el: container,
							elHeight : container.css({display:"block", height:"auto"}).height(),
							elPadd : { top: container.css("paddingTop"), bottom: container.css("paddingBottom")  },
							required : $('.avia_required', this).val().split('::')
						};
				
				var base_id = $('.avia_required', this).parents('.avia_section:eq(0)').attr('id');
				
				//exception for visual groups
				if(typeof base_id != 'string') base_id = $('.avia_required', this).parents('.avia_visual_set:eq(0)').attr('id');
				
				var unique_event_id = base_id.split('-__-');
				
				if(typeof unique_event_id[1] != 'undefined') 
				{
					unique_event_id = unique_event_id[unique_event_id.length-1];
				}
				else
				{
					unique_event_id = unique_event_id[0];
				}
				
				container.css({display:'none'});
				
				//find the next sibling that has the desired class on our option page
				var elementToWatchWrapper = container.siblings('div[id$='+basicData.required[0]+']');
				
				
				// if we couldn find one check if we are inside a metabox panel by search for the ".inside" parent div
				if(elementToWatchWrapper.length == 0) elementToWatchWrapper = container.parents('.inside').find('div[id$='+basicData.required[0]+']');
				

				// bind the event and set the current state of visibility
				var elementToWatch = $(':input[name$='+basicData.required[0]+']', elementToWatchWrapper);
				
				//if we couldnt find the elment to watch we might need to search on the whole page, it could be outside of the group as a "global" setting
				if(elementToWatch.length == 0) elementToWatch = $(':input[name$='+basicData.required[0]+']');
				
				if(container.is('.inactive_visible'))
				{
					$('<div class="avia_inactive_overlay"><span>'+container.data('group-inactive')+'</span></div>').appendTo(container);
				}
				
				//set current state:
				if(elementToWatch.is(':checkbox'))
				{	
					if((elementToWatch.attr('checked') && basicData.required[1]) || (!elementToWatch.attr('checked') && !basicData.required[1]) ) 
					{ 
						if(container.is('.inactive_visible'))
						{
							container.addClass('avia_visible');
						}
						else
						{
							container.css({display:'block'}); 
						}
					}
				}
				else
				{
					var array_check = false;
					if( basicData.required[1].indexOf( '{contains_array}' ) !== -1 )
					{
						var to_check = basicData.required[1].replace('{contains_array}','').split(';');
						$.each( to_check, function( i, val ) {
									if( elementToWatch.val().indexOf( val ) !== -1 )
									{
										array_check = true;
										return false;
									}
								});
					}
					
					if(elementToWatch.val() == basicData.required[1] || 
					  (elementToWatch.val() != "" && basicData.required[1] == "{true}") || (elementToWatch.val() == "" && basicData.required[1] == "{false}") ||
					  (basicData.required[1].indexOf('{contains}') !== -1 && elementToWatch.val().indexOf(basicData.required[1].replace('{contains}','')) !== -1) ||
					  (basicData.required[1].indexOf('{higher_than}') !== -1 && parseInt(elementToWatch.val()) >= parseInt((basicData.required[1].replace('{higher_than}','')))) ||
					  array_check
					) 
					{ 
						if(container.is('.inactive_visible'))
						{
							container.addClass('avia_visible');
						}
						else
						{
							container.css({display:'block'}); 
						}
					}
				}
				
		
				
				//bind change event for future state changes
				elementToWatch.bind('change', {set: basicData}, methods.change);
						
		});
	};
	
	

	var methods = 
	{
		change: function(passed)
		{
			
			
			var data 		= passed.data.set,
				elToCheck 	= $(this),
				elVal		= elToCheck.val(),
				array_check = false;
			
			if(elToCheck.is(':checkbox')) elVal = "";
			
			if( data.required[1].indexOf( '{contains_array}' ) !== -1 )
			{
				var to_check = data.required[1].replace('{contains_array}','').split(';');
				$.each( to_check, function( i, val ) {
						if( elVal.indexOf( val ) !== -1 )
						{
							array_check = true;
							return false;
						}
					});
			}
					
			if(elVal == data.required[1] ||
				(elVal != "" && data.required[1] == "{true}") || (elVal == "" && data.required[1] == "{false}") ||
				(elToCheck.is(':checkbox') && (elToCheck.attr('checked') && data.required[1] || !elToCheck.attr('checked') && !data.required[1])) ||
				(data.required[1].indexOf('{contains}') !== -1 && elVal.indexOf(data.required[1].replace('{contains}','')) !== -1) ||
				(data.required[1].indexOf('{higher_than}') !== -1 && parseInt(elVal) >= parseInt((data.required[1].replace('{higher_than}','')))) ||
				array_check
			)
			{
				if(data.el.is('.inactive_visible'))
				{
					data.el.addClass('avia_visible');
					return;
				}
				
				
				if(data.el.css('display') == 'none')
				{
					
					if(data.elHeight == 0)
					{
						data.elHeight = data.el.css({visibility:"hidden", position:'absolute'}).height();
					}
				
					data.el.css( {height:0, opacity:0, overflow:"hidden", display:"block", paddingBottom:0, paddingTop:0, visibility:"visible", position:'relative'}).animate(
							{height: data.elHeight, opacity:1, paddingTop: data.elPadd.top, paddingBottom: data.elPadd.bottom}, function()
							{
								data.el.css({overflow:"visible", height:"auto"});
							});
				}
			}
			else
			{
									
				if(data.el.is('.inactive_visible'))
				{
					data.el.removeClass('avia_visible');
					return;
				}
				
				if(data.el.css('display') == 'block')
				{
					if(data.el.is('.set_blank_on_hide')) { var blank_el = data.el.find('.set_blank_on_hide'); blank_el.val("").trigger('change'); }
					data.el.css({overflow:"hidden"}).animate({height:0, opacity:0, paddingBottom:0, paddingTop:0}, function()
					{
						data.el.css({display:"none", overflow:"visible", height:"auto"});
						
					});
				}
			}
		}
	};
	
})(jQuery);	










/************************************************************************
avia_clone_sets: function to modify sets: add them, remove them and recalculate set ids
*************************************************************************/



(function($)
{
	$.fn.avia_clone_sets = function(variables) 
	{
		return this.each(function()
		{
			//gather form data
			var container = $(this);
			
			if(container.length != 1) return;
			
			var hiddenDataContainer = $('#avia_hidden_data'),
				saveData = {
							container    : 	container,
							createButton : 	$('.avia_clone_set', this),
							removeButton : 	$('.avia_remove_set', this),
							nonce: 			$('input[name=avia-nonce]', hiddenDataContainer).val(),
							ajaxUrl: 		$('input[name=admin_ajax_url]', hiddenDataContainer).val(),
							ref: 			$('input[name=_wp_http_referer]', hiddenDataContainer).val(),
							prefix :		$('input[name=avia_options_prefix]', hiddenDataContainer).val(),
							meta_active:	$('input[name=meta_active]', hiddenDataContainer)
							};
			
			
			//bind actions:
			saveData.createButton.unbind('click').bind('click', {set: saveData}, methods.add); 	//creates a new set
			saveData.removeButton.unbind('click').bind('click', {set: saveData}, methods.remove); 	//remove a  set
			
			
		});
	};
	
	var currentlyModifying = false,
	 	methods = {
	
	
		/**
		 *  This functions adds a new dataset
		 *  Based on the link that was clicked the script checks the containing set contaienr and extracts the id (optionSlug) from that
		 *  container. It then sends an ajax request to the admin-ajax.php script which executes the avia_ajax_modify_set php function 
		 *  The php function searches for an option array that is identical to the optionSlug in the options array and returns the html code
		 *  for this. The script then inserts that code and shows it, then functionallity gets applied
		 */
 
		add: function(passed)
		{
			//security check to prevent ajax request problems: only modify one set at a time
			if(currentlyModifying) return false;
			currentlyModifying = true;
		
			//get the current button, the container to clone and extract the id from that container
			var data = passed.data.set,
				currentButton = $(this),
				loadingIcon = currentButton.prev('.avia_clone_loading'),
				cloneContainer = currentButton.parents('.avia_set:eq(0)'),
				parentCloneContainer = currentButton.parents('.avia_set:eq(1)'),
				elementSlug = cloneContainer.attr('id');
			
			if(parentCloneContainer.length == 1)
			{
				var removeString = parentCloneContainer.attr('id');
				
				elementSlug = elementSlug.replace(removeString+'-__-','').replace(/-__-\d+/,'');
			}
			else
			{
				elementSlug = elementSlug.replace('avia_','').replace(/-__-\d+/,'');
			}
			
			
			//check if its a meta page:
			var page_context = 'options_page';
			if(data.meta_active.length) page_context = 'metabox';
			
			
			//send ajax request to the ajax-admin.php script	
			$.ajax({
					type: "POST",
					url: data.ajaxUrl,
					data: 
					{
						action: 'avia_ajax_modify_set',
						method: 'add',
						elementSlug: elementSlug,
						context: page_context,
						_wpnonce: $('input[name=avia-nonce]').val(),
						_wp_http_referer: $('input[name=_wp_http_referer]').val(),
						
					},
					beforeSend: function()
					{
						loadingIcon.fadeIn(300);
					},
					error: function()
					{
						$('body').avia_alert({the_class:'error', text:'Couldnt connect to your Server <br/> Please wait a few seconds and try again', show:4500});
						loadingIcon.fadeOut(300);
					},
					success: function(response)
					{
						var save_result = response.match(/\{avia_ajax_element\}(.+|\s+)\{\/avia_ajax_element\}/);
						
						if(save_result != null)
						{	

							//add new set to the dom
							var newSet = $(save_result[1]).css('display','none');
							
							methods.setBlank(newSet);
							newSet.insertAfter(cloneContainer).slideDown(400, function()
							{
								//recalculate the id indices that are used for form elements and divs
								data.currentSet = newSet;
								methods.recalcIds(data);
								
								//bind events to the created container elements
								newSet.avia_event_binding();
							});
							
							
							//in case the script returns other output tell the user
							if(save_result[0] != response)
							{
								response = response.replace(save_result[0],'');
								$('body').avia_alert({the_class:'error', 
								text:'Adding of element successful but the script generated unexpected output: <br/><br/> '+response, show:6000});	
							}
							
						}

					},
					complete: function(response)
					{	
						loadingIcon.fadeOut(300);
						currentlyModifying = false;
					}
				});		

			return false;
		},
		
		remove: function(passed)
		{
			//security check to prevent ajax request problems: only modify one set at a time
			if(currentlyModifying) return false;
			currentlyModifying = true;

			var data = passed.data.set,
				currentButton = $(this),
				singleSet = currentButton.parents('.avia_set:eq(0)'),
				id = singleSet.attr('id').replace(/-__-\d+$/,'-__-');
				
				data.setsToCount = singleSet.siblings('.avia_set').filter('[id*='+id+']');
				
				if(data.setsToCount.length || data.removeButton.is('.remove_all_allowed'))
				{
					data.currentSet = data.setsToCount.filter(':eq(0)');
					
					singleSet.slideUp(400, function()
					{
						singleSet.remove();
						methods.recalcIds(data);
						currentlyModifying = false;	
					});
				
				}
				else
				{
					methods.setBlank(singleSet);
					data.setsToCount = false;
					currentlyModifying = false;	
					
				}
					
			return false;
		},
		/************************************************************************
		empty all elements within a container. usually called if an element is the last one to delete
		*************************************************************************/		
		setBlank: function(container)
		{
			$('input:text, input:hidden, textarea', container).not('.avia_upload_insert_label, .avia_required').val('').trigger('change');						
			$('input:checkbox, input:radio, select', container).removeAttr("checked").removeAttr("selected").trigger('change');
			$('.avia_preview_pic, .avia_color_picker_div', container).html("").css({backgroundColor:'transparent'});
		},


		recalcIds: function(data)
		{
			avia_recalcIds(data);
		}
		

	};
	
	
		/************************************************************************
		recalculate ids whenever an element is added or deleted
		*************************************************************************/
	
	    
	    avia_recalcIds = function(data)
		{	
			//if no element group was passed create one
			//(no elements are passed on delete, we need to pass the group when we delete since the set isnt available any more)
			if(!data.setsToCount)
			{					
				var id = data.container.attr('id').replace(/-__-\d+$/,'-__-');
				data.setsToCount = data.currentSet.siblings('.avia_set').filter('[id*='+id+']').add(data.currentSet);
			}
			
			//check if we got a parent group
			var parentGroup = data.currentSet.parents('.avia_set:eq(0)'),
				newId = "",
				detatch_element,
				detatch_parent;

			if(typeof data.detach == 'string')
			{
				detatch_element	= data.currentSet.parents(data.detach+':eq(0)');
				detatch_parent	= detatch_element.parent();
				detatch_element.detach();
			}
			
			//if we got a parent group calculate the string that needs to be prepended to all siblings based on that parent
			//otherwise the current group is the highest within the dom and needs to be used as string base	
			if(parentGroup.length == 1)
			{
				newId = data.currentSet.attr('id').replace('avia_','');
				newId = parentGroup.attr('id') +'-__-'+ newId.replace(/\d+$/,'');
			}
			else
			{	
				if(data.currentSet.attr('id'))
				{
					newId = data.currentSet.attr('id').replace(/\d+$/,'');
				}
			}
			
			/**
			 *  
			 *  iterate over all sets that are siblings of the newly added set to recalculate the ids and names of the elements within
			 *  First we modify the set id, based on that id we dig deeper into the dom and whenever a nested set is encountered
			 *  the base string to modify the names and ids of the elements within this set is changed. The id gets always changed.
			 *  If the id ends with -__-(int)  we know that a subset container gets modified and need to adjust the replacement pattern
			 *  The replacement pattern for form elements is: "id of parent element + own id string" 
			 *  The replacement pattern for container is	: "String: "avia_" + id of parent element + own id" 
			 *
			 */
 			
			data.setsToCount.each(function(i)
			{
				var currentSet = $(this),
					elements = $('[id*=-__-], [name*=-__-]', this),
					setId = newId + i;
				
				//modify the highest set id as base for all elements within
				this.id = setId;
				
				
				//now modify all elements within the set
				elements.each(function()
				{
					
					var element = $(this),
						el_attr = this.id,
						parentSet = element.parents('.avia_set:eq(0)'),
						replacementString = parentSet[0].id.replace('avia_','');
						
						//checks if id is found that ends with -__-(element_name)									
						var match = el_attr.match(/[a-z0-9](-__-[-_a-zA-Z0-9]+-__-\d+)$/);
						
						if(match == null)
						{
							var myRegexp = /.+-__-([-_a-zA-Z0-9]+)$/;
							match = myRegexp.exec(el_attr);
							
							id_string = replacementString + '-__-' + match[1];
							
							if(this.name)
							{
								this.name = id_string;
							}
							else
							{
								id_string = 'avia_' +id_string;
							}
							this.id = id_string;
							
						}
						else //else we got an element with -__-(int), therefore we need to modify a subset container
						{
							el_attr_array = match[1];
							this.id = 'avia_' + replacementString + el_attr_array;
						}
					
				});
			});
			
			//delte the setsToCount global for all future iterations
			data.setsToCount = "";
			
			if(typeof detatch_element != "undefined" && detatch_element.length)
			{
				detatch_element.prependTo(detatch_parent);
			}
			
			
			return;			
		} //end recalcids

	
})(jQuery);	





/*instant editor*/

(function($)
{
	$.fn.avia_instant_editor = function(passed_options) 
	{	
		"use strict";
		
		var win			= $(window),
			editing		= false,
			defaults	= {
				
				'class': 'avia_default_instant_editor',
				elements: 'td',
    			input: {avia_text:'textarea'},
    			output:'',
    			start:'click',
    			appendTo: false,
    			special_buttons: false
			
			},
			methods		= {
			
				bind_event: function(container)
				{
					var options = container.options;
					
					//remove all previous bindings
					container.off(".avia_instant_edit", options.elements);
					
					//add new bindings
					container.on(options.start+".avia_instant_edit", options.elements, function(e){methods.show_editor(this, container, e); });
					
					//add new bindings
					container.on("click.avia_instant_edit", '.avia_editor_button', function(e){methods.insert_button_shortcode(this, container, e); });
					
					//bind tabing
					win.off('.avia_instant_edit_keyup').on("keyup.avia_instant_edit_keyup", function(e){ methods.key_binds(this, container, e); });
				},
				
				key_binds: function(current, container, event)
				{
					var options 	= container.options;
					
						
					switch(event.keyCode)
					{
						case 9: 

						if(editing && editing.length)
						{
							var elements	= container.find(options.elements),
								index		= elements.index(editing),
								direction	= event.shiftKey ? -1 : 1,
								next		= elements.filter(':eq('+(index + direction)+')');
							
							if(!next.length)
							{
								if(direction == 1)
								{
									next = elements.filter(':eq(0)');
								}
								else
								{
									next = elements.filter(':last');
								}
							}
							
							next.trigger('click');
							event.stopPropagation();
							event.preventDefault();
							editing = next;
						}
						
						
						
						break; //tab
					}
				},
				
				insert_button_shortcode: function(current, container, event)
				{
					event.stopPropagation();
					event.preventDefault();
					
					var button 		= current.hash.substring(1),
						shortcode	= container.options.special_buttons[button].code,
						target		= $(current).parents('.avia_instant_editor:eq(0)').find(':input:eq(0)'),
						htmlVal		= target.val();
					
					if(htmlVal == 'Edit')
					{
						target.val(shortcode);
					}
					else
					{
						target.val(htmlVal + shortcode);
					}	 
				},
				
				show_editor: function(current, container, event)
				{
					event.stopPropagation();
					
					var currentEl = $(current);
					
					if(!currentEl.is('.avia_active_editor')) 
					{
						var	html_value	= currentEl.html(),
							form		= methods.form_builder(container, html_value);
						
						
						currentEl.addClass('avia_active_editor').html(form);
						currentEl.find(':input:eq(0)').focus().select();
						win.trigger('click');
						methods.closeListener(container, currentEl);
						
						editing = currentEl;
					}
				},
				
				close_editor: function(container, close_element)
				{
					if(container.options.output == "")
					{
						var key, 
						input = container.options.input,
						output = "";
					
						for(key in input)
						{
							output += close_element.find(input[key]).val();
						}
						
						output = output.replace(/\n/g,"</br>");
						
						close_element.html(output).removeClass('avia_active_editor');
						win.unbind('.avia_instant_edit');
						editing = false;
					}
				
				},
				
				form_builder: function(container, html_value )
				{
					var key, 
						input = container.options.input,
						form = "<div class='avia_instant_editor "+container.options['class']+"'>";
					
					form += methods.button_builder(container);
										
					for(key in input)
					{
						form += methods[input[key]].call(this, key, html_value);
					}
					
					form += "</div>";
					
					return form;
				},
				
				button_builder: function(container)
				{
					var buttons = container.options.special_buttons,
						key, form = "";
					
					for(key in buttons)
					{
						form += "<a href='#"+key+"' class='avia_editor_button "+key+"'>"+buttons[key].label+"</a>";
					}
					
					return form;
				},
				
				textarea: function(the_class, html_value)
				{
					html_value = html_value.replace(/<\/br>/g,"\n").replace(/<br>/g,"\n");
					return "<textarea class='"+the_class+"'>"+html_value+"</textarea>";
				},
				
				input: function(the_class, html_value)
				{
					return "<input class='"+the_class+"' value='"+html_value+"' />";
				},
				
				closeListener: function(container, close_element)
				{
					win.unbind('.avia_instant_edit').bind(container.options.start+".avia_instant_edit", function(event)
					{
						if(close_element.get(0) != event.target /* && close_element.find(event.target).length == 0 */) //2nd if clause caused problems in FF and Opera so it was removed
						{
							methods.close_editor(container, close_element);
						}
						
					});
				}
			};
		
	
		return this.each(function()
		{
			var container = $(this);
			
			container.options	= $.extend({}, defaults, passed_options);
			
			methods.bind_event(container);
			
		});
	};
})(jQuery);	











