/*global jQuery:false*/
/*global tinymce:false*/
/*global tg_names:false*/
/*global tg_sc_title:false*/
/*global tg_sc_tooltip:false*/

(function($) {
	
	"use strict";
	
	$(document).ready(function() {
	
		if (typeof(tinymce) !== 'undefined') {
		
			tinymce.PluginManager.add('the_grid', function(editor) {
	
				var sh_tag = 'the_grid';
				var height = (tg_names.indexOf('tg-sc-button') == -1) ? 360 : 170;
				//add popup
				editor.addCommand('the_grid_panel_popup', function() {
					//setup defaults
					editor.windowManager.open({
						title      : tg_sc_title,
						fixedWidth : false,
						width      : 650,
						height     : height,
						popup_css  : false,
						resizable  : false,
						inline     : false,
						autoScroll : false,
						id         : 'tg-shortcode-panel',
						body: [
							{
								type   : 'container',
                    			name   : 'name',
								html   : tg_names
							}
						],
						onsubmit: function( e ) {
							var shortcode_str = '[' + sh_tag + ' name="'+$('.tg-grid-shortcode-value').val()+'"]';
							//insert shortcode to tinymce
							editor.insertContent(shortcode_str);
						}
					});
				});
			
				//add button
				editor.addButton('the_grid', {
					tooltip : tg_sc_tooltip,
					icon    : 'tg-metabox-icon',
					onclick : function() {
						editor.execCommand('the_grid_panel_popup','',{
							name   : '',
						});
					}
				});
			
				//open popup on placeholder double click
				editor.on('DblClick',function(e) {
					if (e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-the_grid_panel') > -1) {
						editor.execCommand('the_grid_panel_popup','',{
							name: name
						});
					}
				});
				
			});
		
		}
	
	});

})(jQuery);