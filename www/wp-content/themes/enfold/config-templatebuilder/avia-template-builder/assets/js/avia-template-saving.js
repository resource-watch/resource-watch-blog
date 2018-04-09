(function($)
{
	"use strict";
	
	$.AviaElementBehavior = $.AviaElementBehavior || {};
    $.AviaElementBehavior.wp_save_template = function()
	{
	   this.container  = $('.avia-template-save-button-container');
	   this.toggle     = this.container.find('.open-template-button');
	   this.dropdown   = this.container.find('.avia-template-save-button-inner');
	   this.list       = this.dropdown.find('ul');
	   this.save_btn   = this.container.find('.save-template-button');
	   this.save_single_container = this.container.parents('.postbox');
	   this.savebox     = false;
	   this.bind_events();
	   this.template_val = "";
	}
	
	
    $.AviaElementBehavior.wp_save_template.prototype = 
    {
        bind_events: function()
        {
            var obj = this;
            
            this.list.on('click', 'span:not(.preloading)', function(e){ obj.delete_template(e); });
            this.list.on('click', 'a', function(e){ obj.fetch_template(e); });
            this.toggle.on('click', function(){ obj.toggle_dropdown(); });
            this.save_btn.on('click', function(){ obj.open_modal(); });
            this.save_single_container.on('click', '.avia-save-element', function(e){ obj.open_modal(e); });
            
            
            $('body').on('click', function(e){ obj.close_check(e); })
        },
    
        toggle_dropdown: function()
        {
            if(this.container.is('.avia-hidden-dropdown'))
            {
                this.container.removeClass('avia-hidden-dropdown');
                this.toggle.removeClass('av-template-added-highlight');
            }
            else
            {
                this.container.addClass('avia-hidden-dropdown');
            }
            
            return false;
        },
        
        close_check: function(event)
        {
            if(!this.container.is('.avia-hidden-dropdown') && $(event.target).parents('.avia-template-save-button-container:eq(0), .avia-modal:eq(0)').length == 0)
            {
                this.toggle_dropdown();
            }
        },
        
        open_modal: function(e)
        {
            $.avia_builder.updateTextarea(); // make sure the content is converted to avia shortcodes            
			
			this.textarea_value(e);
			
            if(this.template_val.indexOf('[') === -1)
            {
                this.toggle_dropdown();
                new $.AviaModalNotification({mode:'attention', msg:avia_template_save_L10n.no_content});
            }
            else
            {
                var title = avia_template_save_L10n.chose_name,
                	msg   = "<input name = 'avia-builder-template' type='text' class='avia-template-name-ajax' value='' maxlength='40' />" +
                            "<span class='avia-template-save-msg'>" + avia_template_save_L10n.save_msg + "</span>" +
                            "<span class='avia-template-save-chars'>" + avia_template_save_L10n.chars + ", A-Z, 0-9, -_</span>";
                
                if(typeof e != "undefined")
                {
	                title = avia_template_save_L10n.chose_save;
                }
                          
                this.savebox = new $.AviaModalNotification(
                {
                    mode:'attention', 
                    msg:msg, 
                    modal_title: title, 
                    button:'save',
                    scope: this,
                    on_save: this.try_save,
                    save_param: {event: e}
                });
            }
            
            return false
        },
        
        textarea_value: function(e)
        {
            this.template_val = "";

            /* fetchign the value like this returns code with p + br tags which we dont want
            if(typeof window.tinyMCE == 'undefined' || typeof window.tinyMCE.get('content') == 'undefined')
            {
                value = $.trim($('#content.wp-editor-area').val());
            }
            else
            {
                value = $.trim(window.tinyMCE.get('content').getContent({format:'raw'}));
            }
            */
            
            
            //store only a single el?
            if(typeof e != "undefined" && e.currentTarget.className == "avia-save-element")
			{
				this.template_val = $.trim($(e.currentTarget).parent('div').next('.avia_inner_shortcode').find('textarea').val());
			}
			else
			{
            	this.template_val = $.trim($('#content.wp-editor-area').val());
            }
            return this.template_val;
        },
        
        update_entry_list: function(name)
        {
            var obj = this;
            
            //remove the empty list
            this.list.find('.avia-no-template').slideUp(200, function()
            {
                $(this).remove();
            })
            
            //attach the new element, sort the list then show the item
            var newItem = $("<li><a href='#'>"+name+"</a><span class='avia-delete-template'></span></li>").css('display','none').appendTo(this.list),
                listitems = this.list.children('li').get();
                
                listitems.sort(function(a, b) 
                {
                   return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
                });
            
                $.each(listitems, function(idx, itm) { obj.list.append(itm); });
            
                newItem.slideDown();
        },
        
        insert_template: function(template)
        {
            $.avia_builder.sendToAdvancedEditor(template);
			$.avia_builder.updateTextarea();
			$.avia_builder.do_history_snapshot();
        },
        
        fetch_template: function(e)
        {
            e.preventDefault();
        
            var obj      = this,
                template = $(e.target),
                list     = template.parent(),
                del_btn  = list.find('span'),
                name     = template.text();
            
            $.ajax({
                    
					type: "POST",
					url: ajaxurl,
					data: 
					{
						action: 'avia_ajax_fetch_builder_template',
						templateName: name,
						avia_request: true,
						_ajax_nonce: $('#avia-loader-nonce').val()
					},
					beforeSend: function()
					{
				        del_btn.addClass('preloading');
					},
					error: function() // no connection
					{
						new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.ajax_error});
					},
					success: function(response)
					{
					    del_btn.removeClass('preloading');
					
						if(response == 0) // not logged in
						{
							new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.login_error});
						}
						else if(response == "-1") // nonce timeout
						{
						  new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.timeout});
						}
						else
						{
				            if(response.indexOf('avia_fetching_error') !== -1) //template not found
				            {
				                new $.AviaModalNotification({mode:'error', msg:avia_template_save_L10n.not_found});
							}
							else
							{
							     obj.insert_template(response);
							}
						}
					}
				});   
				                
        },
        
        delete_template: function(e)
        {
            e.preventDefault();
        
            var current  = $(e.target),
                template = current.prev('a'),
                list     = template.parent(),
                name     = template.text();
                
            $.ajax({
					type: "POST",
					url: ajaxurl,
					data: 
					{
						action: 'avia_ajax_delete_builder_template',
						post_id: avia_globals.post_id,
						templateName: name,
						avia_request: true,
						'avia-save-nonce': $('#avia-save-nonce').val(),
						_ajax_nonce: $('#avia-loader-nonce').val()
						
					},
					beforeSend: function()
					{
				        current.addClass('preloading');
					},
					error: function() // no connection
					{
						new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.ajax_error});
					},
					success: function(response)
					{
						if(response == 0) // not logged in
						{
							new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.login_error});
				            current.removeClass('preloading');
						}
						else if(response == "-1") // nonce timeout
						{
                            new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.timeout});
				            current.removeClass('preloading');
						}
						else
						{
				            if(response.indexOf('avia_template_deleted') !== -1) //template already in use
				            {
				                list.slideUp(300, function()
				                {
				                    list.remove();
				                });
							}
						}
					}
				}); 
				
        },
        
         
        try_save: function(values, event)
        {   	        
            var obj              = this,
                name             = values['avia-builder-template'],
                disallowed_chars = name.match(/[^a-zA-Z0-9-_ ]/),
                error            = false,
                save_msg_wrap    = this.savebox.modal.find('.avia-template-save-msg').text(avia_template_save_L10n.save_msg),
                footer           = this.savebox.modal.find('.avia-modal-inner-footer');
            
            this.savebox.modal.find('.avia-template-save-error').removeClass('avia-template-save-error');
            
            if(name.length < 3)
            {
                save_msg_wrap.addClass('avia-template-save-error');
                error = true;
            }
            
            if(disallowed_chars != null)
            {
                this.savebox.modal.find('.avia-template-save-chars').addClass('avia-template-save-error');
                error = true;
            }
            
            if(!error)
            {
                $.ajax({
					type: "POST",
					url: ajaxurl,
					data: 
					{
						action: 'avia_ajax_save_builder_template',
						post_id: avia_globals.post_id,
						templateName: name,
						avia_request: true,
						templateValue: obj.template_val,
						'avia-save-nonce': $('#avia-save-nonce').val(),
						_ajax_nonce: $('#avia-loader-nonce').val()
					},
					beforeSend: function()
					{
				        footer.addClass('preloading');
					},
					error: function() // no connection
					{
						$.AviaModal.openInstance[0].close();
						new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.ajax_error});
					},
					success: function(response)
					{
					    footer.removeClass('preloading');
					
						if(response == 0) // not logged in
						{
							$.AviaModal.openInstance[0].close();
							new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.login_error});
						}
						else if(response == "-1") // nonce timeout
						{
                            $.AviaModal.openInstance[0].close();
                            new $.AviaModalNotification({mode:'error', msg:avia_modal_L10n.timeout});
						}
						else
						{
				            if(response.indexOf('avia_template_saved') === -1) //template already in use
				            {
				                save_msg_wrap.addClass('avia-template-save-error').text(response);
				            }
							else //save success!
							{
				                obj.update_entry_list(name);
				                obj.savebox.close();
				                
				                //mark the template button if a single element was saved
				                if(typeof event != "undefined")
				                {
					                obj.toggle.addClass('av-template-added-highlight');
				                }
							}
						}
					}
				});    
            }
            return false;
        }
    }
 
	
})(jQuery);	 


