/*global vc:false*/

if(typeof(window.InlineShortcodeView) !== 'undefined'){

	window.InlineShortcodeView_the_grid = window.InlineShortcodeView.extend({
		
		render: function() {
			window.InlineShortcodeView_the_grid.__super__.render.call(this);
			//var current_grid = jQuery(this.$el.find('.tg-grid-holder'));
			//var grid_id = jQuery(this.$el.find('.tg-grid-wrapper')).attr('id');
			vc.frame_window.vc_iframe.addActivity(function(){
				// destroy all grid media before to prevent ajax issue
				this.jQuery.TG_media_destroy();
				// init all grid media to handle youtube/vimeo/soundclound/hosted video audo
				this.jQuery.TG_media_init();
				// build the grid
				this.jQuery('.tg-grid-holder').The_Grid();
			});		
			return this;
		},
		/*update: function() {
			window.InlineShortcodeView_the_grid.__super__.update.call(this);
			return this;
		},
		edit: function(e) {
			window.InlineShortcodeView_the_grid.__super__.update.call(this);
			return this;
		}*/
		
	});
	
}