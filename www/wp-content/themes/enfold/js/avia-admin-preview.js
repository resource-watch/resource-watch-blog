(function($)
{	
    'use strict';
	
    $(document).ready(function()
    {
    	$('body').on('click', 'a, input.button, button, submit', function(e)
    	{
    		e.preventDefault();
    	});
    });    

})( jQuery );


