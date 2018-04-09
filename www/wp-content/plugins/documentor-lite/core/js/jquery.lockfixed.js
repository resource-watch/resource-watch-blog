/*!
 * jQuery lockfixed plugin
 * http://www.directlyrics.com/code/lockfixed/
 *
 * Copyright 2012 Yvo Schaap
 * Released under the MIT license
 * http://www.directlyrics.com/code/lockfixed/license.txt
 *
 * Date: Sun Feb 9 2014 12:00:01 GMT
 */
(function($, undefined){
	$.extend({
		/**
		 * Lockfixed initiated
		 * @param {Element} el - a jquery element, DOM node or selector string
		 * @param {Object} config - offset - forcemargin
		 */
		"lockfixed": function(el, config){
			if (config && config.offset) {
				config.offset.bottom = parseInt(config.offset.bottom,10);
				config.offset.top = parseInt(config.offset.top,10);
			}else{
				config.offset = {bottom: 100, top: 0};	
			}
			var el = $(el);
			if(el && el.offset()){
				var el_position = el.css("position"),
					el_margin_top = parseInt(el.css("marginTop"),10),
					el_position_top = el.css("top"),
					el_top = el.offset().top,
					pos_not_fixed = false;
				
				/* 
				 * We prefer feature testing, too much hassle for the upside 
				 * while prettier to use position: fixed (less jitter when scrolling)
				 * iOS 5+ + Android has fixed support, but issue with toggeling between fixed and not and zoomed view
				 */
				/*if (config.forcemargin === true || navigator.userAgent.match(/\bMSIE (4|5|6)\./) || navigator.userAgent.match(/\bOS ([0-9])_/) || navigator.userAgent.match(/\bAndroid ([0-9])\./i)){
					pos_not_fixed = true;
				}*/

				/*
				// adds throttle to position calc; modern browsers should handle resize event fine
				$(window).bind('scroll resize orientationchange load lockfixed:pageupdate',el,function(e){

					window.setTimeout(function(){
						$(document).trigger('lockfixed:pageupdate:async');
					});			
				});
				*/

				$(window).bind('scroll resize orientationchange load lockfixed:pageupdate',el,function(e){
					// if we have a input focus don't change this (for smaller screens)
					if(pos_not_fixed && document.activeElement && document.activeElement.nodeName === "INPUT"){
						return;	
					}

					var top = 0,
						el_height = el.outerHeight(),
						el_width = el.outerWidth(),
						//el_width = (el.parents('.documentor-wrap').outerWidth()-el.parents('.documentor-wrap:first').find('.doc-sec-container').outerWidth())-40;
						max_height = $(document).height() - config.offset.bottom,
						scroll_top = $(window).scrollTop();
 					
					// if element is not currently fixed position, reset measurements ( this handles DOM changes in dynamic pages )
					if (el.css("position") !== "fixed" && !pos_not_fixed) {
						el_top = el.offset().top;
						el_position_top = el.css("top");
					}
					if (scroll_top >= (el_top-(el_margin_top ? el_margin_top : 0)-config.offset.top)){

						if(max_height < (scroll_top + el_height + el_margin_top + config.offset.top)){
							top = (scroll_top + el_height + el_margin_top + config.offset.top) - max_height;
						}else{
							top = 0;	
						}

						if (pos_not_fixed){
							el.css({'marginTop': (parseInt(scroll_top - el_top - top,10) + (2 * config.offset.top))+'px'});
						}else{
							el.addClass('doc-menufixed');
							el.css({'position': 'fixed','top':(config.offset.top-top)+'px','width':el_width +"px"});
						}
					}else{
						el.removeClass('doc-menufixed');
						el.css({'position': el_position,'top': el_position_top, 'width':el_width +"px", 'marginTop': (el_margin_top && !pos_not_fixed ? el_margin_top : 0)+"px"});
					}
				});
				$(window).bind('resize',el,function(e){
					var elpos = el.css('position');
					el.css('position','relative');
					el.css({'position':elpos});
					var wrapper = el.parents('.documentor-wrap:first');
					var seccontainer = el.parents('.documentor-wrap:first').find('.doc-sec-container');
					var menu = el.parents('.documentor-wrap:first').find(".doc-menuright.doc-menufixed");
					var menuright = el.parents('.documentor-wrap:first').find(".doc-menuright");
					var el_width = (wrapper.outerWidth()-seccontainer.outerWidth())-40;
					el.css({'width':el_width+'px'});
					if( menu.length > 0 ) {
						var mleft = wrapper.outerWidth()-menu.outerWidth();
						menu.css('margin-left',mleft+'px');
					} else {
						menuright.css('margin-left','0px');
					}
				});
			}
		}
	});
})(jQuery);
