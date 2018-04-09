
(function($)
{
	"use strict";
	$.AviaModal.register_callback = $.AviaModal.register_callback || {};
	
   	$.AviaModal.register_callback.modal_start_sorting = function(passed_scope)
	{
		var _self 	= this,
			scope	= passed_scope || this.modal,
			target	= scope.find('.avia-modal-group'),
			params	= {
					handle: '.avia-attach-modal-element-move',
					items: '.avia-modal-group-element',
					placeholder: "avia-modal-group-element-highlight",
					tolerance: "pointer",
					forcePlaceholderSize:true,
					start: function( event, ui ) 
					{
						$('.avia-modal-group-element-highlight').height(ui.item.outerHeight()).width(ui.item.outerWidth());
					},
					update: function(event, ui) 
					{
						//obj.updateTextarea();
						ui.item.parents('.avia-modal-group:eq(0)').trigger('av-item-moved', [ui.item]);
						
						//trigger update for the live preview
						ui.item.find('textarea[data-name="text-shortcode"]').trigger('av-update-preview-instant');
					},
					stop: function( event, ui ) 
					{
						//obj.canvas.removeClass('avia-start-sorting');
					}
				};
			
			target.find('.avia-modal-group-element, .avia-insert-area').disableSelection();	
			target.sortable(params);
	}
	
	
	$.AviaModal.register_callback.modal_tab_functions = function(passed_scope)
	{
		var scope		= passed_scope || this.modal,
			is_tabs		= scope.find('.avia-tab-container').length;
		
		if(!is_tabs) return;
		
		var	wrap		= scope.find('.avia-modal-group-wrapper'),
			fakeContent = $('<div id="fakeTabContent" class="avia_textblock_style"></div>').appendTo(wrap),
			methods		= {
			
				bind_events: function()
				{
					wrap.on('update mouseenter', '.avia-modal-group-element', methods.update_fake);
					fakeContent.on('click', methods.route_fakeContent_click);
				},
				
				update_fake: function()
				{
					wrap.find('.avia-active').removeClass('avia-active');
					fakeContent.html($(this).addClass('avia-active').find('.avia_content_container').html());
				},
				
				route_fakeContent_click: function()
				{
					wrap.find('.avia-active .avia_title_container').trigger('click');
					return false;
				}
			
			};
			
		
		methods.bind_events();
		wrap.find('.avia-modal-group-element:first').trigger('update');
	}
	
	
	
	
	
	
		
})(jQuery);	 
