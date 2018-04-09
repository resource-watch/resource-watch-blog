/*global jQuery:false*/

(function($) {
	
	"use strict";
	
	$(document).ready(function() {
		
		$(document).on('click', '.tg-list-item-wrapper[data-multi-select=""] .tg-list-item-holder li', function() {
			var $this = $(this);
			var value = $this.data('name');
			$('.tg-list-item-holder li').removeClass('selected');
			$this.addClass('selected');
			$this.closest('ul').next('input').val(value);
		});
		
		$(document).on('keyup','.tg-list-item-search', function() {
			var val = $(this).val();
			tg_search_grid(val);
		});
		
		function tg_search_grid(val) {
			$('.tg-list-item-holder li').each(function(index, element) {
				var $this = $(this);
				var grid = $this.text();
				if (grid.toLowerCase().indexOf(val) >= 0) {
                	$this.show();
				} else {
					$this.hide();
				}
            });
		}
	});

})(jQuery);
