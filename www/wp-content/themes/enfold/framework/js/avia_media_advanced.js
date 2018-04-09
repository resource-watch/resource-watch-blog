/**
 * This file holds the main javascript functions needed for new version of the avia-media uploads with improved sorting options
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.7
 * @package 	AviaFramework
 */
 

(function($)
{
	var avia_media_advanced = {
		
		//get the window containing the iframe
		window: parent || top,
		
		//bind click event to the upload button, which sets all variables and opens thickbox
		bind_click: function()
		{
			$('body').on('click', '.avia_gallery_delete_all', function()
			{
				var element 		= $(this),
					container		= element.parents('.avia_gallery_upload_container:eq(0)'),
					insertContainer = container.find('.avia_sortable_gallery_container');
					
					insertContainer.html("");
					return false;
			});

			
			
			$('body').on('click', '.avia_gallery_uploader', function()
			{
				//collect current link properties
				var element 		= $(this),
					container		= element.parents('.avia_gallery_upload_container:eq(0)'),
					set				= element.parents('.avia_set:eq(0)'),
					insertContainer = container.find('.avia_sortable_gallery_container'),
					title  			= this.title,
					attach_to_post 	= element.data('attach-to-post'),
					label 		   	= element.data('label'),
					elementSlug		= element.data('real-id'),
					overwrite		= element.data('overwrite'),
					video			= element.data('video-insert'),
					value_field		= set.find('.avia_gallery_image_value'),
					image_field		= value_field.parents('.avia_gallery_image:eq(0)'),
					meta_active    	= $('input[name=meta_active]', '#avia_hidden_data'),
					page_context 	= 'options_page';
					
					if(meta_active.length) page_context = 'metabox';
				

				
				//uri string to open in thickbox
				var uri_string  = 'media-upload.php?post_id='+attach_to_post;
					uri_string += '&amp;avia_gallery_active=true';
		if(video)	uri_string += '&amp;tab='+video+'&amp;height=300';
					uri_string += '&amp;avia_gallery_label='+encodeURI(label),
					uri_string += '&amp;TB_iframe=true';
					
		if(video) $('body').addClass(video + '_height_mod height_mod_active');
				
				//set global object so iframe javascript is able to access values
				avia_media_advanced.window.avia_framework_globals.gallery_editor = 
				{
					element: 		element,
					container: 		insertContainer,
					value_field:	value_field,
					elementSlug: 	elementSlug,
					attach_to_post: attach_to_post,
					page_context: 	page_context,
					overwrite: 		overwrite,
					value_field:	value_field,
					image_field:	image_field,
					label:			label,
					video:			video,
					_wpnonce: 		$('input[name=avia-nonce]').val(),
					_wp_http_referer: $('input[name=_wp_http_referer]').val(),
				};
				
				//remove thickbox title
				title = "";
				
				//open thickbox
				tb_show( title, uri_string);
				return false;
			});
			
			//if inside the iframe add extra class for video mods
			if($('body', parent.document).is('.height_mod_active'))
			{
				$('body').addClass('iframe_height_mod_active');
			}
			
			
		},
		
		
		// bind click event to the custom insert buttons
		insert_click: function()
		{
			
			$('body').on('click', '.avia_send_to_gallery', function()
			{
				var link 			= $(this),
					attachment_id 	= link.data('attachment-id'),
					loading			= link.next('.avia_gallery_loading').css({visibility:'visible'})
																		.removeClass('avia_loading_done avia_loading_error');
				
				
				if(avia_media_advanced.window.avia_framework_globals.gallery_editor &&
				   avia_media_advanced.window.avia_framework_globals.gallery_editor.overwrite)
				{	
					avia_media_advanced.replace_image(attachment_id, loading);
				}
				else
				{
					avia_media_advanced.request_image(attachment_id, loading);
				}
				return false;
			});
		},
		
		
		//bind click event to the insert video button
		insert_inline_content_click: function(button, content_field, close)
		{
			button = $(button)
			content_field = $(content_field);
			var loading = $('<div class="avia_gallery_loading"></div>').insertAfter(button);
								
			
			button.click(function()
			{
				var content = content_field.val();
				if(content != "")
				{
					var data = {};
					data.std = {slideshow_image: '', slideshow_video: encodeURIComponent(content)}; 
					loading.css('visibility','visible');
					avia_media_advanced.request_image(content, loading, data, close);
				}
				
				return false;
			});
		},
		
		open_close_click: function()
		{
			$('body').on('click', '.open_set', function()
			{
				var el = $(this),
					parent = el.parents('.avia_set:eq(0)');
					
				if(!parent.is('.required_ready'))
				{
					parent.addClass('required_ready');
					$('.avia_required_container', parent).avia_form_requirement();
				}
				
				if(parent.is('.set_is_open'))
				{
					parent.removeClass('set_is_open');
					el.text(el.data('openset'));
				}
				else
				{
					parent.addClass('set_is_open');
					el.text(el.data('closedset'));
				}
				
				return false;
			});
		},
		
		//performs a check for the gallery settings
		get_gallery_settings: function()
		{
			if( avia_media_advanced.window.avia_framework_globals.gallery_editor )
			{
				return avia_media_advanced.window.avia_framework_globals.gallery_editor;
			}
			//else
			return false;
		},
		
		//clears gallery settings
		clear_gallery: function()
		{
			avia_media_advanced.window.avia_framework_globals.gallery_editor = false;
		},
		
		//clone the insert button and display it at the top of each item
		clone_insert_button: function()
		{
			if(!avia_media_advanced.window.avia_framework_globals.gallery_editor) return false;
			var container 	= $('#media-upload');
			
			if(container.length)
			{
				var media_items = container.find('.media-item').not('.button_cloned');
				media_items.each(function()
				{
					var current 	= $(this).addClass('button_cloned'),
						filename 	= current.find('.filename'),
						button 		= current.find('.avia_send_to_gallery').clone(true).prependTo(filename),
						loading		= $('<div class="avia_gallery_loading"></div>').insertAfter(button);
	
				});
			}
			
		},
		
		// attaches an add all button to the gallery tab
		add_insert_all_button: function()
		{
			var gallery 	= $('#gallery-form'),
				gallery_data = avia_media_advanced.window.avia_framework_globals.gallery_editor;
			
			if(gallery.length && gallery_data && !gallery_data.overwrite)
			{
			var submit = $('.ml-submit:eq(0)'),
				update_gal = $('<input type="submit" id="avia_insert_all" class="button savebutton" value="Add all Images to Slideshow"/>'),
				loading 	= $('<div class="avia_gallery_loading avia_gallery_loading_all"></div>')		
							
				update_gal.appendTo(submit).bind('click', function()
				{
					var data = {};
					data.activate_filter = 'avia_ajax_fetch_all';
					loading.css({visibility:'visible'}).removeClass('avia_loading_done avia_loading_error');
					avia_media_advanced.request_image( '' ,loading, data);
					return false;
				});
				
				loading.appendTo(submit);
			}
		},
		
		//overwrite send to editor function
		overwrite_default_uploader: function()
		{
			window.orig_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html)
     		{     
     			
     			//check if we are using the gallery uploader		
				if(avia_media_advanced.get_gallery_settings() == false)
				{
					window.orig_send_to_editor(html);
				}
				else
				{
					avia_media_advanced.clear_gallery();
					avia_media_advanced.window.tb_remove();
				}
     		};
		},
		
		//activates item sorting
		sortable: function()
		{
			$( ".avia_handle" ).disableSelection();	
		
			$('.avia_sortable_gallery_container').sortable({
				
				handle: '.avia_handle',
				cancel: 'a',
				items: '.avia_row',
				update: function(event, ui) 
				{
					//recalculate the id indices that are used for form elements and divs
					var pass = {currentSet: ui.item, container: ui.item, setsToCount: false, detach: ".ui-sortable"};
					avia_recalcIds(pass);
				}

			});
		
		},
		
		//adds input fields to the filter form so the get string is built correctly when user performs a search
		modify_filter_url: function()
		{
			var filter = $("#filter"),
				gallery_data = avia_media_advanced.window.avia_framework_globals.gallery_editor;

			if(filter.length)
			{
				//duplication check
				var labelInsert	 = filter.find("input[name=avia_gallery_label]"),
					galleryInsert= filter.find("input[name=avia_gallery_active]");
				
				if(gallery_data && gallery_data.label && !labelInsert.length)
				{
					filter.prepend("<input type='hidden' name='avia_gallery_label' value='"+gallery_data.label+"'/>");
				}
				
				if(gallery_data && !galleryInsert.length)
				{
					filter.prepend("<input type='hidden' name='avia_gallery_active' value='true'/>");
				}
			}
		},
		
		activate_tabs: function(scope)
		{	
			if(!scope || scope == 'undefined') scope = '.avia_sortable_gallery_container';
			var container = $(scope);
			
			container.each(function()
			{
				var current_container = $(this),
					sets = current_container.find('.avia_set'),
					prepend_modified = false;
					
				if(!sets.length)  { sets = current_container.filter('.avia_set');  }
				if(container.parents('#avia_options_page').length > 0)
				{
					prepend_modified = true;
				}
				
				sets.each(function()
				{
					var current_set = $(this),
						tabs 		= current_set.find('.avia_tab'),
						title_group = $('<div class="tab-title-container"></div>');
						
						
						if(!prepend_modified)
						{
							title_group.prependTo(current_set.find('.avia_visual_set:eq(0)'));
						}
						else
						{
							title_group.prependTo(current_set);
						}
						
						
						
						tabs.each(function(i){
						
							var current_tab = $(this),
								title		= current_tab.data('group-name')
								active 		= 'avia_active_tab_title';
								
							if(i != 0) { current_tab.css({display:'none'}); active = "";}
							
							
							$('<a href="#" class="tab-title '+active+'">'+title+'</a>').appendTo(title_group).click(function(){
								
								var _self = $(this);
								
								tabs.css({display:'none'}).removeClass('avia_active_tab');
								current_tab.css({display:'block'}).addClass('avia_active_tab');
								
								title_group.find('a').removeClass('avia_active_tab_title');
								_self.addClass('avia_active_tab_title');
								
								var option_page = current_tab.parents('.avia_subpage_container').eq(0);
								
								if(option_page.length)
								{
									if(current_tab.data('av_set_global_tab_active'))
									{
										option_page.attr('data-av_set_global_tab_active', current_tab.data('av_set_global_tab_active'));
									}
									else
									{
										option_page.attr('data-av_set_global_tab_active', "");
									}
								}
								
								return false;
							});
							
						});
						
					});	 
				});
		},
		
		
		//overwrites the thickbox closing function so it also unsets the gallery vars
		overwrite_thickbox_close:function()
		{			
			window.orig_tb_remove = window.tb_remove;
			
			window.tb_remove = function()
			{
				var gallery_data = avia_media_advanced.window.avia_framework_globals.gallery_editor;
				
				if(gallery_data)
				{
					if(gallery_data.video) 
					{
						var tb = $('#TB_window', parent.document);
						tb.height(tb.height());
						tb.css({overflow:'hidden', top:tb.css('top'), marginTop: tb.css('marginTop')})
						$('body').removeClass(gallery_data.video + '_height_mod height_mod_active');
					}
					avia_media_advanced.clear_gallery();
				}
				
				window.orig_tb_remove();
			}
		},
		
		//replaces a image and closes the thickbox
		replace_image: function(attachment_id, loading)
		{	
			var gallery_data = avia_media_advanced.window.avia_framework_globals.gallery_editor;
						
			$.ajax({
	 		  type: "POST",
	 		  url: window.ajaxurl,
	 		  data: "action=avia_ajax_get_image&attachment_id="+attachment_id,
	 		  success: function(msg)
	 		  {
	 		  	loading.addClass('avia_loading_done');
	 		  	gallery_data.value_field.val(attachment_id);
	 		  	msg = $.trim(msg);
	 		  	if(msg.match(/^<img/)) //image insert
	 		  	{	
					gallery_data.image_field.find('a').html(msg);
	 		  	}
	 		  	else //video insert
	 		  	{
					gallery_data.image_field.find('a').html('<img src="'+avia_framework_globals.frameworkUrl+'images/icons/video.png" alt="" />');
	 		  	}
	 		  	
	 		  	//reset
	 		  	avia_media_advanced.window.tb_remove();
	 		  }
	 		});
		},
		
		//request an image and adds a new set
		request_image: function(attachment_id, loading, data_passed, close)
		{
			var gallery_data = avia_media_advanced.window.avia_framework_globals.gallery_editor;
			
			if(!gallery_data) 
			{
				loading.addClass('avia_loading_error');
				return;
			}
			
			var data = 
			{
				method:			'add',
				action: 		'avia_ajax_modify_set',
				elementSlug: 	gallery_data.elementSlug,
				context: 		gallery_data.page_context,
				apply_all:		gallery_data.attach_to_post,
				std:			{slideshow_image: attachment_id},
				_wpnonce: 		gallery_data._wpnonce,
				_wp_http_referer: gallery_data._wp_http_referer,
				ajax_decode:	true
			};
			
			var data = $.extend(data, data_passed);
			
			
			
			$.ajax({
				type: "POST",
				url: window.ajaxurl,
				data: data,
				success: function(response)
				{
					loading.addClass('avia_loading_done');
					var save_result = response.match(/\{avia_ajax_element\}(.+|\s+)\{\/avia_ajax_element\}/),
						pass = {};
						
					if(save_result != null)
					{	
						//add new set to the dom
						var newSet = $(save_result[1]);
						
						newSet.appendTo(gallery_data.container);
						
						if(newSet.length < 5)
						{
							newSet.css('display','none').slideDown(400);
						}
							
						//recalculate the id indices that are used for form elements and divs
						var single_set = newSet.filter(':eq(0)');
						pass = {currentSet: single_set, container: single_set, setsToCount: false};
						avia_recalcIds(pass);
						
						//bind events to the created container elements
						newSet.avia_event_binding();
						
							
						
						//in case the script returns other output tell the user
						if(save_result[0] != response)
						{
							response = response.replace(save_result[0],'');
							$('body').avia_alert({the_class:'error', 
							text:'Adding of element successful but the script generated unexpected output: <br/><br/> '+response, show:6000});	
						}
						else
						{
							if(close == true) setTimeout( avia_media_advanced.window.tb_remove, 500);
						}
						
					}
				}
			});
		}
		
		
		
	};
	

	
	$(function()
	{
		//overwrite the send_to_editor function
		//avia_media_advanced.overwrite_default_uploader(); //currently not necessary!
		
		//overwrite the thickbox close function
		avia_media_advanced.overwrite_thickbox_close();
		
		//sorting possible
		if($.fn.sortable)
		{
			avia_media_advanced.sortable();
		}
		
		//modify search in case user uses filter
		avia_media_advanced.modify_filter_url();
		
		//bind click on upload button
		avia_media_advanced.bind_click();
		
		//bind click on upload button
		avia_media_advanced.insert_click();
		
		//bind click on upload button
		avia_media_advanced.insert_inline_content_click('#avia_insert_video', '#src', true);
		
		//bind click on upload button
		avia_media_advanced.open_close_click();
		
		//activate tabs + tab functionality that is made out of visual groups
		avia_media_advanced.activate_tabs();
		
		//clone the insert button
		avia_media_advanced.clone_insert_button();
		$('body').on('mouseenter', '.media-item',avia_media_advanced.clone_insert_button);


		//adds an insert all button 
		avia_media_advanced.add_insert_all_button();
	
 	});
 	
 	
 	$.fn.avia_media_advanced_plugin = function(variables) 
	{	
		avia_media_advanced.activate_tabs(this);
	};


})(jQuery);	 

function avia_log(text) 
{
	var ios = navigator.userAgent.toLowerCase().match(/(iphone|ipod|ipad)/);
	( (window.console && console.log && !ios) || (window.opera && opera.postError && !ios) ||  avia_text_log).call(this, text);
	
	function avia_text_log(text)
	{
		var logfield = jQuery('.avia_logfield');
		if(!logfield.length) logfield = jQuery('<pre class="avia_logfield"></pre>').appendTo('body').css({	zIndex:100000, 
																											padding:"20px", 
																											backgroundColor:"#ffffff", 
																											position:"fixed", 
																											top:0, right:0, 
																											width:"300px",
																											borderColor:"#cccccc",
																											borderWidth:"1px",
																											borderStyle:'solid',
																											height:"600px",
																											overflow:'scroll',
																											display:'block',
																											zoom:1
																											});
		var val = logfield.html();
		var text = avia_get_object(text);
		logfield.html(val + "\n<br/>" + text);
	}
	
	function avia_get_object(obj)
	{
		var sendreturn = obj;
		
		if(typeof obj == 'object' || typeof obj == 'array')
		{
			for (i in obj)
			{
				sendreturn += "'"+i+"': "+obj[i] + "<br/>";
			}
		}
		
		return sendreturn;
	}
}

