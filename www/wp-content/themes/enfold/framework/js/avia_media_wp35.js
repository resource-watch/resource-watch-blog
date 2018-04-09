/**
 * This file holds the main javascript functions needed for new version of the avia-media uploads for wordpress version 3.5 and higher
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
	"use strict";
	$.AviaElementBehavior = $.AviaElementBehavior || {};
	$.AviaElementBehavior.wp_media = $.AviaElementBehavior.wp_media || [];
	
	$.AviaElementBehavior.wp_media_35 =  function()
	{
		var $body = $("body");
		
		$body.on('click', '.avia-media-35', $.AviaElementBehavior.wp_media_35_activate );
		$body.on('click', '.avia_uploader_35', $.AviaElementBehavior.wp_media_35_activate );
	};
	
	
	
	//intended for file upload
	$.AviaElementBehavior.wp_media_35_activate = function( event )
	{
		event.preventDefault();
		
		var clicked = $(this), 
			options = clicked.data(), 
			params  = {	
				frame:   options.frame,
				library: { type: options.type },
				button:  { text: options.button },
				className: options['class'],
				title: options.title
			};
		
		if (typeof options.state != "undefined" ) params.state = options.state;
		
		options.input_target = $('#'+options.target);
		
		// Create the media frame.
		var file_frame = wp.media(params);
		
		
		file_frame.states.add([
					// Main states.
					new wp.media.controller.Library({
						id:         'av_select_single_image',
						priority:   20,
						toolbar:    'select',
						filterable: 'uploaded',
						library:    wp.media.query( file_frame.options.library ),
						multiple:   false,
						editable:   true,
						displayUserSettings: false, 
						displaySettings: true,
						allowLocalEdits: true
						// AttachmentView: media.view.Attachment.Library
					}),
				]);
		
		
		file_frame.on( 'select update insert', function(){ $.AviaElementBehavior.wp_media_35_insert( file_frame , options); });
		
		//open the media frame
		file_frame.open();
	
	};
	
	//insert the url of the zip file
	$.AviaElementBehavior.wp_media_35_insert = function( file_frame , options )
	{
		var state		= file_frame.state(), 
			selection	= state.get('selection').first().toJSON(),
			value		= selection.id,
			fetch_val   = typeof options.fetch != 'undefined' ? fetch_val = options.fetch : false
		
		/*fetch custom val like url*/
		if(fetch_val)
		{
			value = state.get('selection').map( function( attachment ) 
			{
				var element = attachment.toJSON();
				
				if(fetch_val == 'url')
				{
					var display = state.display( attachment ).toJSON();
					
					if(element.sizes && element.sizes[display.size] && element.sizes[display.size].url)
					{
						return element.sizes[display.size].url;
					}
					else if (element.url)
					{
						return element.url;
					}
				}
			});
		}	
		
		//change the target input value
		options.input_target.val(value).trigger('change')
		
		//trigger event in case it is necessary (eg: uplaods)
		if(typeof options.trigger != "undefined")
		{
			$("body").trigger(options.trigger, [selection, options]);
		}
	}

	
	$(document).ready(function () 
	{
		$.AviaElementBehavior.wp_media_35();
		
		//fontello Zip file upload
		$("body").on('av_fontello_zip_insert', $.AviaElementBehavior.fontello_insert);
		
		//fontello font manager
		$("body").on('click', '.avia_iconfont_manager .avia-del-font', $.AviaElementBehavior.fontello_remove);

        //config file upload
        $("body").on('av_config_file_insert', $.AviaElementBehavior.config_file_insert);
	});



/************************************************************************
EXTRA FUNCTIONS, NOT NECESSARY FOR THE DEFAULT UPLOAD
*************************************************************************/
	
	$.AviaElementBehavior.fontello_insert = function(event, selection, options)
	{
		// clean the options field, we dont need to save a value
		options.input_target.val("");
		var manager = $('.avia_iconfont_manager');
		
		if(selection.subtype !== 'zip')
		{
			$('body').avia_alert({the_class:'error', text:'Please upload a valid ZIP file.<br/>You can create the file on Fontello.com'});
			return;
		}
		
		var loader = options.input_target.parents('.avia_control').eq(0).find('.avia_upload_loading');
		
		// send request to server to extract the zip file, re arrange the content and save a config file
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: 
			{
				action: 'avia_ajax_add_zipped_font',
				values: selection,
				avia_request: true,
				_wpnonce: $('input[name=avia-nonce]').val()
			},
			beforeSend: function()
			{
				loader.css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
			},
			error: function()
			{
				$('body').avia_alert({the_class:'error', text:'Couldn\'t add the font because the server didn’t respond.<br/>Please reload the page, then try again'});
			},
			success: function(response)
			{
				if(response.match(/avia_font_added/))
				{
					var font	 	= response.replace(/avia_font_added:/,''),
						existing	= manager.find('[data-font="'+font+'"]'),
						all_fonts	= manager.find('.avia-available-font'),
						template 	= manager.find('.avia-available-font:eq(0)').clone().wrap('<p>').parent().html().replace(/{font_name}/g, font);
						
						if(existing.length)
						{
							existing.removeClass('av-highlight');
							setTimeout(function(){ existing.addClass('av-highlight'); },10);
							
							if(all_fonts.index(existing) === 1)
							{
								var del = existing.find('.avia-def-font').removeClass('avia-def-font').addClass('avia-del-font').text('Delete')
							}
						}
						else
						{
							$(template).css({display:'none'}).appendTo(manager).slideDown(200);
						}
				}
				else
				{
					$('body').avia_alert({the_class:'error', show:6500 , text:'Couldn\'t add the font.<br/>The script returned the following error: '+"<br/><br/>"+response});
				}
				
				if(typeof console != 'undefined') console.log(response);
				
			},
			complete: function(response)
			{	
				loader.fadeOut();
			}
		});
	}

	$.AviaElementBehavior.fontello_remove = function(event)
	{
		event.preventDefault();
		var button 		= $(this),
			parent		= button.parents('.avia-available-font:eq(0)'),
			manager		= button.parents('.avia_iconfont_manager:eq(0)'),
			all_fonts	= manager.find('.avia-available-font'),
			del_font	= button.data('delete');
		
		var loader = button.parents('.avia_control').eq(0).find('.avia_upload_loading');
		
		// send request to server to remove the folder and the database entry
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: 
			{
				action: 'avia_ajax_remove_zipped_font',
				del_font: del_font,
				avia_request: true,
				_wpnonce: $('input[name=avia-nonce]').val()
			},
			beforeSend: function()
			{
				loader.css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
			},
			error: function()
			{
				$('body').avia_alert({the_class:'error', text:'Couldn\'t remove the font because the server didn’t respond.<br/>Please reload the page, then try again'});
			},
			success: function(response)
			{
				if(response.match(/avia_font_removed/))
				{
					if(all_fonts.index(parent) === 1)
					{
						var del = parent.find('.avia-del-font').removeClass('avia-del-font').addClass('avia-def-font').text('(Default Font)')
					}
					else
					{					
						parent.slideUp(200,function()
						{
							parent.remove();
						});
					}
				}
				else
				{
					$('body').avia_alert({the_class:'error', text:'Couldn\'t remove the font.<br/>Please reload the page, then try again'});
				}
				
				if(typeof console != 'undefined') console.log(response);
				
			},
			complete: function(response)
			{	
				loader.fadeOut();
			}
		});
	}



    $.AviaElementBehavior.config_file_insert = function(event, selection, options)
    {
        // clean the options field, we dont need to save a value
        options.input_target.val("");

        if(selection.subtype !== 'plain')
        {
            $('body').avia_alert({the_class:'error', text:'Please upload a valid config file.<br/>You can create the file by clicking on the "Export Theme Settings" button'});
            return;
        }
		

		var loader = options.input_target.parents('.avia_control').eq(0).find('.avia_upload_loading');
		
		
        // send request to server to extract the zip file, re arrange the content and save a config file
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data:
            {
                action: 'avia_ajax_import_config_file',
                values: selection,
                avia_request: true,
                _wpnonce: $('input[name=avia-nonce]').val()
            },
            beforeSend: function()
            {
                loader.css({opacity:0, display:"block", visibility:'visible'}).animate({opacity:1});
            },
            error: function()
            {
                $('body').avia_alert({the_class:'error', text:'Couldn\'t import the config because the server didn’t respond.<br/>Please reload the page, then try again'});
            },
            success: function(response)
            {
                if(response.match(/avia_config_file_imported/))
                {
					$('body').avia_alert({text: 'Alright sparky!<br/>Import worked out, no problems whatsoever. <br/>The page will now be reloaded to reflect the changes'}, function()
								{
									// window.location.hash = "#goto_importexport";
									window.location.hash = "";
						 			window.location.reload(true);
								});
                }
                else
                {
                    $('body').avia_alert({the_class:'error', show:6500 , text:'Couldn\'t import the font file.<br/>The script returned the following error: '+"<br/><br/>"+response});
                }

                if(typeof console != 'undefined') console.log(response);

            },
            complete: function(response)
            {
                loader.fadeOut();
            }
        });
    }











})(jQuery);	 
