/* 
	this prevents dom flickering for elements hidden with js, needs to be outside of dom.ready event.also adds several extra classes for better browser support 
	this is a separate file that needs to be loaded at the top of the page. other js functions are loaded before the closing body tag to make the site render faster
*/
"use strict"

var avia_is_mobile = false;
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && 'ontouchstart' in document.documentElement)
{
	avia_is_mobile = true;
	document.documentElement.className += ' avia_mobile ';
}
else
{
	document.documentElement.className += ' avia_desktop ';
}
document.documentElement.className += ' js_active ';

(function(){
	//set transform property
    var prefix = ['-webkit-','-moz-', '-ms-', ""], transform = "";
    for (var i in prefix)
    { 
    	// http://artsy.github.io/blog/2012/10/18/so-you-want-to-do-a-css3-3d-transform/
    	if(prefix[i]+'transform' in document.documentElement.style) 
    	{ document.documentElement.className += " avia_transform "; transform = prefix[i]+'transform'}
    	if(prefix[i]+'perspective' in document.documentElement.style) document.documentElement.className += " avia_transform3d "; 
	}
	
	//set parallax position to prevent jump at pageload
	if(typeof document.getElementsByClassName == 'function' && typeof document.documentElement.getBoundingClientRect == "function" && avia_is_mobile == false)
	{
		if(transform  && window.innerHeight > 0)
		{
			setTimeout(function(){
				var y = 0, offsets = {}, transY = 0, parallax = document.getElementsByClassName("av-parallax"),
				winTop = window.pageYOffset || document.documentElement.scrollTop;
				
				for (y = 0; y < parallax.length; y++) {
					parallax[y].style.top = "0px";
					offsets	= parallax[y].getBoundingClientRect();
					transY	= Math.ceil( (window.innerHeight + winTop - offsets.top) * 0.3 );
				    parallax[y].style[transform] = "translate(0px, "+transY+"px)";
				    parallax[y].style.top = "auto";
				    parallax[y].className += ' enabled-parallax ';
				}
			}, 50);
		}
	}
})();
