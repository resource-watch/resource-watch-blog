(function($) {
				
	"use strict";

	$(document).ready(function(){ 
	
		$(document).on('click', '.to-post-like:not(".to-post-like-unactive")', function(e){
			
			e.preventDefault();
			
			var $heart  = $(this),
				post_id = $heart.data('post-id'),
				like_nb = parseInt($heart.find('.to-like-count').text());
			
			$heart.addClass('heart-pulse');

			$.ajax({
				type : 'post',
				url  : to_like_post.url,
				data : {
					nonce   : to_like_post.nonce,
					action  : 'to_like_post',
					post_id : post_id,
					like_nb : like_nb
				},
				context : $heart,
				success : function(data) {
					if (data) {
						$heart = $(this);
						$heart.attr('title', data.title);
						$heart.find('.to-like-count').text(data.count);
						$heart.removeClass(data.remove_class+' heart-pulse').addClass(data.add_class);
					}
				}
			});
			
			return false;
			
		});
		
	});

})(jQuery);