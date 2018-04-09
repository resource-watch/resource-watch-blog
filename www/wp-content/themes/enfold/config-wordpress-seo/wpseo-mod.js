jQuery(document).ready(function($) {



function avia_yoast_edit_analysis()
{
	var builder = jQuery('#avia_builder:visible'),
		results = jQuery('#focuskwresults li:eq(3) span'),
		content = jQuery('#content');
		
	if(builder.length)
	{
		content.on('av_update', function()
		{
			content.trigger('focusout');
		});
	
		// results.removeClass('wrong').addClass('good');
		// results.html('Content Analysis disabled since it does not work with complex layout building tools <br/><small>(No worries though: Search engines won\'t have difficulties to fetch your content)</small>');
	}
}


/*activate functions*/
avia_yoast_edit_analysis();

});