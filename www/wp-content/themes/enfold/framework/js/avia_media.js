/**
 * This file holds the main javascript functions needed for avia-media uploads
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
	var avia_media = {
	
		aviaUseCustomEditor: false,
		aviaPostID: false,
		insertContainer : false,
		
		// bind the click event to all elements with the class avia_uploader 
		bind_click: function()
		{
			$('body').on('click', '.avia_uploader', function()
			{
				var title  = this.title,
					idBased = "";
				this.title = "";
				

				
				avia_media.aviaPostID = this.hash.substring(1);
				avia_media.aviaUseCustomEditor = true;
				avia_media.insertContainer  = $(this).parents('.avia_upload_container');
				
				//
				if(avia_media.insertContainer.is('.avia_advanced_upload'))
				{
					idBased = "&amp;avia_idbased="+ $('.avia_upload_input', avia_media.insertContainer).attr('name');
				}
				
				var label = $(this).parents('.avia_upload_container').find('.avia_upload_insert_label').trigger('change').val();
				var gallery_mode = $(this).parents('.avia_upload_container').find('.avia_gallery_mode');
				var gallery_mode_val = "";
				
				if(gallery_mode.length)
				{
					gallery_mode_val = '&amp;avia_gallery_mode='+gallery_mode.trigger('change').val();
				}
				
				tb_show( title, 'media-upload.php?post_id='+avia_media.aviaPostID+idBased+gallery_mode_val+'&amp;avia_label='+encodeURI(label)+"&amp;TB_iframe=true" );
				
				
				return false;
			});
		},
		
		// bind the click event of the remove image links to the removing function
		bind_remove: function()
		{
			$('body').on('click', '.avia_remove_image', function()
			{
				var container = $(this).parents('.avia_upload_container');
					
					container.find('.avia_upload_input').val('').trigger('change');
					container.find('.avia_preview_pic').hide(400, function(){ $(this).html("").css({display:"block"}); });
					
				return false;
			});
		},
		
		bind_blur: function()
		{
			$('body').on('blur change', '.avia_upload_input', function()
			{
				var input = $(this),
					value = input.val(),
					image = '<img src ="'+value+'" alt="" />',
					div = input.parents('.avia_upload_container:eq(0)').find('.avia_preview_pic');
					
					if(value != "")
					{
						div.html('<a href="#" class="avia_remove_image">×</a>' + image);
					}
					else
					{
						div.html("");
					}
			});
		},
		
		//changes the label of the "insert into post" button to better reflect the current action and hides the use as post-thumb button
		change_label: function()
		{	
			avia_media.idBasedUpload();
			
			var newlabel = $('.avia_insert_button_label').val();
			
			if(newlabel != "" && typeof newlabel == 'string')
			{				
				var savesendContainer = $(".savesend");
				
				if(savesendContainer.length > 0)
				{		
					$(".button", savesendContainer).val(newlabel);
					$(".wp-post-thumbnail", savesendContainer).css('display','none');	
				}
			}
		},
		
		//hijack the original uploader and replace it if a user clicks on one an avia_uploader
		hijack_uploader: function()
		{			
			window.original_send_to_editor = window.send_to_editor;
     		window.send_to_editor = function(html)
     		{     			
     			if(avia_media.aviaUseCustomEditor)
				{
					var container = avia_media.insertContainer,
						returned = $(html),
						img = returned.attr('src') || returned.find('img').attr('src') || returned.attr('href'),
						visualInsert = '';
					
					container.find('.avia_upload_input').val(img).trigger('change');
					
					if(img.match(/.jpg$|.jpeg$|.png$|.gif$/))
					{
						visualInsert = '<a href="#" class="avia_remove_image">×</a><img src="'+img+'" alt="" />';
					}
					else
					{
						visualInsert = '<a href="#" class="avia_remove_image">×</a><img src="'+avia_framework_globals.frameworkUrl+'images/icons/video.png" alt="" />';
					}
					
					container.find('.avia_preview_pic').html(visualInsert);
					
					tb_remove();
		     		avia_media.reset_uploader();
				}	
				else
				{
					window.original_send_to_editor(html);
				}
     		};
		},
		
		//id based advanced upload
		idBasedUpload: function()
		{
			var idbased = $('.avia_idbased');			
			
			if(idbased.length > 0)
			{
				idbased = idbased.val();
			
				var savesendContainer = $(".savesend"),
					insertInto = $(".button", savesendContainer).not('.del-attachment .button'),
					target =  $("input[name="+idbased+"]", parent.document),
					imageTarget = target.parents('.avia_advanced_upload:eq(0)').find('.avia_preview_pic'),
					filter = $("#filter"), 
					label = $(".avia_insert_button_label"),
					gallery_mode = $(".avia_gallery_mode_active");
					
					
					var gallery_form = $("#gallery-form, #file-form");
					if(gallery_form.length)
					{
						var ref_url = gallery_form.find("input[name=_wp_http_referer]").val(),
							form_url = gallery_form.attr('action'),
							new_url = "";
						
						ref_url = ref_url.replace(/.+media-upload\.php?/,'');
						form_url = form_url.replace(/media-upload\.php?.+/,'');
						new_url = form_url + "media-upload.php" + ref_url;
						
						gallery_form.attr('action', new_url);
					}
					
				if(gallery_mode.length)
				{
					gallery_mode = true;
					
					if($('#avia_update_gallery').length)
					{
						update_gal = $('#avia_update_gallery');
					}
					else
					{
					var save_all = $('#save-all, #save').not('.hidden'),
						save_single = $('#save'),
						update_gal = $('<input type="submit" name="avia_update_gallery" id="avia_update_gallery" class="button savebutton" value="...then close the window and update gallery preview" />');					update_gal.insertAfter(save_all);
						
						//update_gal.insertAfter(save_single);
					}
					
					$('.savesend .button').not('.del-attachment .button').remove();
					insertInto = $('.savesend .button, #insert-gallery, #avia_update_gallery').not('.del-attachment .button').attr('onmousedown',"");
					
					$('#gallery-settings').css({display:'none'});
					
					
				}
				else
				{
					gallery_mode = false;
				}
					
				//add the id based and the insert name field as a form input so it gets sent in case the user uses the search or filter functions
				if(filter.length)
				{
					//duplication check
					var filterInsert = filter.find("input[name=avia_idbased]"),
						labelInsert	 = filter.find("input[name=avia_label]"),
						galleryInsert= filter.find("input[name=avia_gallery_mode]");
					
					if(!filterInsert.length)
					{
						filter.prepend("<input type='hidden' name='avia_idbased' value='"+idbased+"'/>");
					}
					
					if(label.length && !labelInsert.length)
					{
						filter.prepend("<input type='hidden' name='avia_label' value='"+label.val()+"'/>");
					}
					
					if(gallery_mode && !galleryInsert.length)
					{
						filter.prepend("<input type='hidden' name='avia_gallery_mode' value='true'/>");
					}
					
				}
				
				if(gallery_mode)
				{ 
					insertInto.unbind('click').bind('click', function()
					{
						
						var attachment_id = post_id,
							newTarget = target.parents('.avia_control:eq(0)').find('.avia_thumbnail_container');
									
						$.ajax({
				 		  type: "POST",
				 		  url: window.ajaxurl,
				 		  data: "action=avia_ajax_get_gallery&attachment_id="+attachment_id,
				 		  success: function(msg)
				 		  {
				 		  	newTarget.html(msg);
				 		  	parent.tb_remove();
				 		  	avia_media.reset_uploader();
				 		  }
				 		});
				 		
				 		return false;
					});
				}
				else
				{
						insertInto.unbind('click').bind('click', function()
						{
							var attachment_id = this.name.replace(/send\[/,"").replace(/\]/,"");	
										
							$.ajax({
					 		  type: "POST",
					 		  url: window.ajaxurl,
					 		  data: "action=avia_ajax_get_image&attachment_id="+attachment_id,
					 		  success: function(msg)
					 		  {
					 		  	msg = $.trim(msg);
					 		  	if(msg.match(/^<img/)) //image insert
					 		  	{
					 		  		target.val(attachment_id);
					 		  		imageTarget.html('<a href="#" class="avia_remove_image">×</a>'+msg);
					 		  	}
					 		  	else //video insert
					 		  	{
					 		  		target.val(msg);
					 		  		visualInsert = '<a href="#" class="avia_remove_image">×</a><img src="'+avia_framework_globals.frameworkUrl+'images/icons/video.png" alt="" />';
					 		  		imageTarget.html(visualInsert);
					 		  	}
		
					 		  	parent.tb_remove();
					 		  	avia_media.reset_uploader();
					 		  }
					 		});
					 		
					 		return false;
						});
				
				}
				
					
			}
		},
		
		
		
		//reset values for the next upload
		reset_uploader: function()
		{
     		avia_media.aviaUseCustomEditor = false;
     		avia_media.aviaPostID = false;
     		avia_media.insertContainer = false;     		
		}
		
		
	};
	

	
	$(function()
	{
		$('#media-buttons a').click(avia_media.reset_uploader);
		avia_media.bind_click();
		avia_media.bind_blur();
		avia_media.bind_remove();
		avia_media.idBasedUpload();
		avia_media.hijack_uploader();
		avia_media.change_label();
		$('body').on('mouseenter', '.slidetoggle',avia_media.change_label);
 	});

	
})(jQuery);	 