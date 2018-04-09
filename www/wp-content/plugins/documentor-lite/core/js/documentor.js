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
					el.css('visibility', 'hidden');
					var top = 0,
						el_height = el.outerHeight(),
						el_width = el.outerWidth(),
						max_height = $(document).height() - config.offset.bottom,
						scroll_top = $(window).scrollTop();
 					
					// if element is not currently fixed position, reset measurements ( this handles DOM changes in dynamic pages )
					if (el.css("position") !== "fixed" && !pos_not_fixed) {
						el_top = el.offset().top;
						el_position_top = el.css("top");
					}
					if (scroll_top >= (el_top-(el_margin_top ? el_margin_top : 0)-config.offset.top)){
						if (scroll_top<el_height){
							top = 0;
						}
						else if(max_height < (scroll_top + el_height + el_margin_top + config.offset.top)){
							top = (scroll_top + el_height + el_margin_top + config.offset.top) - max_height;
						}else{
							top = 0;	
						}

						if (pos_not_fixed){
							el.css({'marginTop': (parseInt(scroll_top - el_top - top,10) + ( 2 * config.offset.top))+'px'});
						}else{
							el.addClass('doc-menufixed');
							el.css({'position': 'fixed','top':(config.offset.top-top)+'px','width':el_width +"px"});
						}
					}else{
						el.removeClass('doc-menufixed');
						el.css({'position': el_position,'top': el_position_top, 'width':el_width +"px", 'marginTop': (el_margin_top && !pos_not_fixed ? el_margin_top : 0)+"px"});
					}
					el.css('visibility', 'visible');
				});
				$(window).bind('resize load', el, function(e){
					el.css('visibility', 'hidden');
					var elpos = el.css('position');
					//el.css('position','relative');
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
					el.css('visibility', 'visible');
				});
			}
		}
	});
})(jQuery);
/*!***************************************************
 * mark.js v8.8.3
 * https://github.com/julmot/mark.js
 * Copyright (c) 2014–2017, Julian Motz
 * Released under the MIT license https://git.io/vwTVl
 *****************************************************/
"use strict";function _classCallCheck(a,b){if(!(a instanceof b))throw new TypeError("Cannot call a class as a function")}var _extends=Object.assign||function(a){for(var b=1;b<arguments.length;b++){var c=arguments[b];for(var d in c)Object.prototype.hasOwnProperty.call(c,d)&&(a[d]=c[d])}return a},_createClass=function(){function a(a,b){for(var c=0;c<b.length;c++){var d=b[c];d.enumerable=d.enumerable||!1,d.configurable=!0,"value"in d&&(d.writable=!0),Object.defineProperty(a,d.key,d)}}return function(b,c,d){return c&&a(b.prototype,c),d&&a(b,d),b}}(),_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(a){return typeof a}:function(a){return a&&"function"==typeof Symbol&&a.constructor===Symbol&&a!==Symbol.prototype?"symbol":typeof a};!function(a,b,c){"function"==typeof define&&define.amd?define(["jquery"],function(d){return a(b,c,d)}):"object"===("undefined"==typeof module?"undefined":_typeof(module))&&module.exports?module.exports=a(b,c,require("jquery")):a(b,c,jQuery)}(function(a,b,c){var d=function(){function c(b){_classCallCheck(this,c),this.ctx=b,this.ie=!1;var d=a.navigator.userAgent;(d.indexOf("MSIE")>-1||d.indexOf("Trident")>-1)&&(this.ie=!0)}return _createClass(c,[{key:"log",value:function a(b){var c=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"debug",a=this.opt.log;this.opt.debug&&"object"===("undefined"==typeof a?"undefined":_typeof(a))&&"function"==typeof a[c]&&a[c]("mark.js: "+b)}},{key:"escapeStr",value:function(a){return a.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&")}},{key:"createRegExp",value:function(a){return a=this.escapeStr(a),Object.keys(this.opt.synonyms).length&&(a=this.createSynonymsRegExp(a)),this.opt.ignoreJoiners&&(a=this.setupIgnoreJoinersRegExp(a)),this.opt.diacritics&&(a=this.createDiacriticsRegExp(a)),a=this.createMergedBlanksRegExp(a),this.opt.ignoreJoiners&&(a=this.createIgnoreJoinersRegExp(a)),a=this.createAccuracyRegExp(a)}},{key:"createSynonymsRegExp",value:function(a){var b=this.opt.synonyms,c=this.opt.caseSensitive?"":"i";for(var d in b)if(b.hasOwnProperty(d)){var e=b[d],f=this.escapeStr(d),g=this.escapeStr(e);""!==f&&""!==g&&(a=a.replace(new RegExp("("+f+"|"+g+")","gm"+c),"("+f+"|"+g+")"))}return a}},{key:"setupIgnoreJoinersRegExp",value:function(a){return a.replace(/[^(|)\\]/g,function(a,b,c){var d=c.charAt(b+1);return/[(|)\\]/.test(d)||""===d?a:a+"\0"})}},{key:"createIgnoreJoinersRegExp",value:function(a){return a.split("\0").join("[\\u00ad|\\u200b|\\u200c|\\u200d]?")}},{key:"createDiacriticsRegExp",value:function(a){var b=this.opt.caseSensitive?"":"i",c=this.opt.caseSensitive?["aàáâãäåāąă","AÀÁÂÃÄÅĀĄĂ","cçćč","CÇĆČ","dđď","DĐĎ","eèéêëěēę","EÈÉÊËĚĒĘ","iìíîïī","IÌÍÎÏĪ","lł","LŁ","nñňń","NÑŇŃ","oòóôõöøō","OÒÓÔÕÖØŌ","rř","RŘ","sšśșş","SŠŚȘŞ","tťțţ","TŤȚŢ","uùúûüůū","UÙÚÛÜŮŪ","yÿý","YŸÝ","zžżź","ZŽŻŹ"]:["aàáâãäåāąăAÀÁÂÃÄÅĀĄĂ","cçćčCÇĆČ","dđďDĐĎ","eèéêëěēęEÈÉÊËĚĒĘ","iìíîïīIÌÍÎÏĪ","lłLŁ","nñňńNÑŇŃ","oòóôõöøōOÒÓÔÕÖØŌ","rřRŘ","sšśșşSŠŚȘŞ","tťțţTŤȚŢ","uùúûüůūUÙÚÛÜŮŪ","yÿýYŸÝ","zžżźZŽŻŹ"],d=[];return a.split("").forEach(function(e){c.every(function(c){if(c.indexOf(e)!==-1){if(d.indexOf(c)>-1)return!1;a=a.replace(new RegExp("["+c+"]","gm"+b),"["+c+"]"),d.push(c)}return!0})}),a}},{key:"createMergedBlanksRegExp",value:function(a){return a.replace(/[\s]+/gim,"[\\s]+")}},{key:"createAccuracyRegExp",value:function(a){var b=this,c="!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~¡¿",d=this.opt.accuracy,e="string"==typeof d?d:d.value,f="string"==typeof d?[]:d.limiters,g="";switch(f.forEach(function(a){g+="|"+b.escapeStr(a)}),e){case"partially":default:return"()("+a+")";case"complementary":return g="\\s"+(g?g:this.escapeStr(c)),"()([^"+g+"]*"+a+"[^"+g+"]*)";case"exactly":return"(^|\\s"+g+")("+a+")(?=$|\\s"+g+")"}}},{key:"getSeparatedKeywords",value:function(a){var b=this,c=[];return a.forEach(function(a){b.opt.separateWordSearch?a.split(" ").forEach(function(a){a.trim()&&c.indexOf(a)===-1&&c.push(a)}):a.trim()&&c.indexOf(a)===-1&&c.push(a)}),{keywords:c.sort(function(a,b){return b.length-a.length}),length:c.length}}},{key:"getTextNodes",value:function(a){var b=this,c="",d=[];this.iterator.forEachNode(NodeFilter.SHOW_TEXT,function(a){d.push({start:c.length,end:(c+=a.textContent).length,node:a})},function(a){return b.matchesExclude(a.parentNode)?NodeFilter.FILTER_REJECT:NodeFilter.FILTER_ACCEPT},function(){a({value:c,nodes:d})})}},{key:"matchesExclude",value:function(a){return e.matches(a,this.opt.exclude.concat(["script","style","title","head","html"]))}},{key:"wrapRangeInTextNode",value:function(a,c,d){var e=this.opt.element?this.opt.element:"mark",f=a.splitText(c),g=f.splitText(d-c),h=b.createElement(e);return h.setAttribute("data-markjs","true"),this.opt.className&&h.setAttribute("class",this.opt.className),h.textContent=f.textContent,f.parentNode.replaceChild(h,f),g}},{key:"wrapRangeInMappedTextNode",value:function(a,b,c,d,e){var f=this;a.nodes.every(function(g,h){var i=a.nodes[h+1];if("undefined"==typeof i||i.start>b){var j=function(){if(!d(g.node))return{v:!1};var i=b-g.start,j=(c>g.end?g.end:c)-g.start,k=a.value.substr(0,g.start),l=a.value.substr(j+g.start);return g.node=f.wrapRangeInTextNode(g.node,i,j),a.value=k+l,a.nodes.forEach(function(b,c){c>=h&&(a.nodes[c].start>0&&c!==h&&(a.nodes[c].start-=j),a.nodes[c].end-=j)}),c-=j,e(g.node.previousSibling,g.start),c>g.end?void(b=g.end):{v:!1}}();if("object"===("undefined"==typeof j?"undefined":_typeof(j)))return j.v}return!0})}},{key:"wrapMatches",value:function(a,b,c,d,e){var f=this,g=0===b?0:b+1;this.getTextNodes(function(b){b.nodes.forEach(function(b){b=b.node;for(var e=void 0;null!==(e=a.exec(b.textContent))&&""!==e[g];)if(c(e[g],b)){var h=e.index;if(0!==g)for(var i=1;i<g;i++)h+=e[i].length;b=f.wrapRangeInTextNode(b,h,h+e[g].length),d(b.previousSibling),a.lastIndex=0}}),e()})}},{key:"wrapMatchesAcrossElements",value:function(a,b,c,d,e){var f=this,g=0===b?0:b+1;this.getTextNodes(function(b){for(var h=void 0;null!==(h=a.exec(b.value))&&""!==h[g];){var i=h.index;if(0!==g)for(var j=1;j<g;j++)i+=h[j].length;var k=i+h[g].length;f.wrapRangeInMappedTextNode(b,i,k,function(a){return c(h[g],a)},function(b,c){a.lastIndex=c,d(b)})}e()})}},{key:"unwrapMatches",value:function(a){for(var c=a.parentNode,d=b.createDocumentFragment();a.firstChild;)d.appendChild(a.removeChild(a.firstChild));c.replaceChild(d,a),this.ie?this.normalizeTextNode(c):c.normalize()}},{key:"normalizeTextNode",value:function(a){if(a){if(3===a.nodeType)for(;a.nextSibling&&3===a.nextSibling.nodeType;)a.nodeValue+=a.nextSibling.nodeValue,a.parentNode.removeChild(a.nextSibling);else this.normalizeTextNode(a.firstChild);this.normalizeTextNode(a.nextSibling)}}},{key:"markRegExp",value:function(a,b){var c=this;this.opt=b,this.log('Searching with expression "'+a+'"');var d=0,e="wrapMatches",f=function(a){d++,c.opt.each(a)};this.opt.acrossElements&&(e="wrapMatchesAcrossElements"),this[e](a,this.opt.ignoreGroups,function(a,b){return c.opt.filter(b,a,d)},f,function(){0===d&&c.opt.noMatch(a),c.opt.done(d)})}},{key:"mark",value:function(a,b){var c=this;this.opt=b;var d=0,e="wrapMatches",f=this.getSeparatedKeywords("string"==typeof a?[a]:a),g=f.keywords,h=f.length,i=this.opt.caseSensitive?"":"i",j=function a(b){var f=new RegExp(c.createRegExp(b),"gm"+i),j=0;c.log('Searching with expression "'+f+'"'),c[e](f,1,function(a,e){return c.opt.filter(e,b,d,j)},function(a){j++,d++,c.opt.each(a)},function(){0===j&&c.opt.noMatch(b),g[h-1]===b?c.opt.done(d):a(g[g.indexOf(b)+1])})};this.opt.acrossElements&&(e="wrapMatchesAcrossElements"),0===h?this.opt.done(d):j(g[0])}},{key:"unmark",value:function(a){var b=this;this.opt=a;var c=this.opt.element?this.opt.element:"*";c+="[data-markjs]",this.opt.className&&(c+="."+this.opt.className),this.log('Removal selector "'+c+'"'),this.iterator.forEachNode(NodeFilter.SHOW_ELEMENT,function(a){b.unwrapMatches(a)},function(a){var d=e.matches(a,c),f=b.matchesExclude(a);return!d||f?NodeFilter.FILTER_REJECT:NodeFilter.FILTER_ACCEPT},this.opt.done)}},{key:"opt",set:function(b){this._opt=_extends({},{element:"",className:"",exclude:[],iframes:!1,iframesTimeout:5e3,separateWordSearch:!0,diacritics:!0,synonyms:{},accuracy:"partially",acrossElements:!1,caseSensitive:!1,ignoreJoiners:!1,ignoreGroups:0,each:function(){},noMatch:function(){},filter:function(){return!0},done:function(){},debug:!1,log:a.console},b)},get:function(){return this._opt}},{key:"iterator",get:function(){return this._iterator||(this._iterator=new e(this.ctx,this.opt.iframes,this.opt.exclude,this.opt.iframesTimeout)),this._iterator}}]),c}(),e=function(){function a(b){var c=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],d=arguments.length>2&&void 0!==arguments[2]?arguments[2]:[],e=arguments.length>3&&void 0!==arguments[3]?arguments[3]:5e3;_classCallCheck(this,a),this.ctx=b,this.iframes=c,this.exclude=d,this.iframesTimeout=e}return _createClass(a,[{key:"getContexts",value:function(){var a=void 0,c=[];return a="undefined"!=typeof this.ctx&&this.ctx?NodeList.prototype.isPrototypeOf(this.ctx)?Array.prototype.slice.call(this.ctx):Array.isArray(this.ctx)?this.ctx:"string"==typeof this.ctx?Array.prototype.slice.call(b.querySelectorAll(this.ctx)):[this.ctx]:[],a.forEach(function(a){var b=c.filter(function(b){return b.contains(a)}).length>0;c.indexOf(a)!==-1||b||c.push(a)}),c}},{key:"getIframeContents",value:function(a,b){var c=arguments.length>2&&void 0!==arguments[2]?arguments[2]:function(){},d=void 0;try{var e=a.contentWindow;if(d=e.document,!e||!d)throw new Error("iframe inaccessible")}catch(a){c()}d&&b(d)}},{key:"isIframeBlank",value:function(a){var b="about:blank",c=a.getAttribute("src").trim(),d=a.contentWindow.location.href;return d===b&&c!==b&&c}},{key:"observeIframeLoad",value:function(a,b,c){var d=this,e=!1,f=null,g=function g(){if(!e){e=!0,clearTimeout(f);try{d.isIframeBlank(a)||(a.removeEventListener("load",g),d.getIframeContents(a,b,c))}catch(a){c()}}};a.addEventListener("load",g),f=setTimeout(g,this.iframesTimeout)}},{key:"onIframeReady",value:function(a,b,c){try{"complete"===a.contentWindow.document.readyState?this.isIframeBlank(a)?this.observeIframeLoad(a,b,c):this.getIframeContents(a,b,c):this.observeIframeLoad(a,b,c)}catch(a){c()}}},{key:"waitForIframes",value:function(a,b){var c=this,d=0;this.forEachIframe(a,function(){return!0},function(a){d++,c.waitForIframes(a.querySelector("html"),function(){--d||b()})},function(a){a||b()})}},{key:"forEachIframe",value:function(b,c,d){var e=this,f=arguments.length>3&&void 0!==arguments[3]?arguments[3]:function(){},g=b.querySelectorAll("iframe"),h=g.length,i=0;g=Array.prototype.slice.call(g);var j=function(){--h<=0&&f(i)};h||j(),g.forEach(function(b){a.matches(b,e.exclude)?j():e.onIframeReady(b,function(a){c(b)&&(i++,d(a)),j()},j)})}},{key:"createIterator",value:function(a,c,d){return b.createNodeIterator(a,c,d,!1)}},{key:"createInstanceOnIframe",value:function(b){return new a(b.querySelector("html"),this.iframes)}},{key:"compareNodeIframe",value:function(a,b,c){var d=a.compareDocumentPosition(c),e=Node.DOCUMENT_POSITION_PRECEDING;if(d&e){if(null===b)return!0;var f=b.compareDocumentPosition(c),g=Node.DOCUMENT_POSITION_FOLLOWING;if(f&g)return!0}return!1}},{key:"getIteratorNode",value:function(a){var b=a.previousNode(),c=void 0;return c=null===b?a.nextNode():a.nextNode()&&a.nextNode(),{prevNode:b,node:c}}},{key:"checkIframeFilter",value:function(a,b,c,d){var e=!1,f=!1;return d.forEach(function(a,b){a.val===c&&(e=b,f=a.handled)}),this.compareNodeIframe(a,b,c)?(e!==!1||f?e===!1||f||(d[e].handled=!0):d.push({val:c,handled:!0}),!0):(e===!1&&d.push({val:c,handled:!1}),!1)}},{key:"handleOpenIframes",value:function(a,b,c,d){var e=this;a.forEach(function(a){a.handled||e.getIframeContents(a.val,function(a){e.createInstanceOnIframe(a).forEachNode(b,c,d)})})}},{key:"iterateThroughNodes",value:function(a,b,c,d,e){for(var f=this,g=this.createIterator(b,a,d),h=[],i=[],j=void 0,k=void 0,l=function(){var a=f.getIteratorNode(g);return k=a.prevNode,j=a.node};l();)this.iframes&&this.forEachIframe(b,function(a){return f.checkIframeFilter(j,k,a,h)},function(b){f.createInstanceOnIframe(b).forEachNode(a,c,d)}),i.push(j);i.forEach(function(a){c(a)}),this.iframes&&this.handleOpenIframes(h,a,c,d),e()}},{key:"forEachNode",value:function(a,b,c){var d=this,e=arguments.length>3&&void 0!==arguments[3]?arguments[3]:function(){},f=this.getContexts(),g=f.length;g||e(),f.forEach(function(f){var h=function(){d.iterateThroughNodes(a,f,b,c,function(){--g<=0&&e()})};d.iframes?d.waitForIframes(f,h):h()})}}],[{key:"matches",value:function(a,b){var c="string"==typeof b?[b]:b,d=a.matches||a.matchesSelector||a.msMatchesSelector||a.mozMatchesSelector||a.oMatchesSelector||a.webkitMatchesSelector;if(d){var e=!1;return c.every(function(b){return!d.call(a,b)||(e=!0,!1)}),e}return!1}}]),a}();return c.fn.mark=function(a,b){return new d(this.get()).mark(a,b),this},c.fn.markRegExp=function(a,b){return new d(this.get()).markRegExp(a,b),this},c.fn.unmark=function(a){return new d(this.get()).unmark(a),this},c},window,document);
// leanModal v1.1 by Ray Stone - http://finelysliced.com.au
// Dual licensed under the MIT and GPL
(function($) {
    $.fn.extend({
        leanModal: function(options) {
            var defaults = {
                top: "15%",
                overlay: 0.5,
                closeButton: null
            };
            var overlay = $("<div id='lean_overlay'></div>");
            $("body").append(overlay);
            options = $.extend(defaults, options);
            return this.each(function() {
                var o = options;
                $(this).click(function(e) {
                    var modal_id = $(this).attr("href");
                    $("#lean_overlay").click(function() {
                        close_modal(modal_id)
                    });
                    $(o.closeButton).click(function() {
                        close_modal(modal_id)
                    });
                    $("#lean_overlay").css({
                        "display": "block",
                        opacity: 0
                    });
					$(modal_id).css({
                        "display": "block",
                        opacity: 0
                    });
					var modal_height = $(modal_id).outerHeight();
                    var modal_width = $(modal_id).outerWidth();
                    $("#lean_overlay").fadeTo(200, o.overlay);
                    $(modal_id).css({
                        "display": "block",
                        "position": "fixed",
                        "opacity": 0,
                        "z-index": 11000,
                        "left": 50 + "%",
                        "margin-left": -(modal_width / 2) + "px",
                        "top": o.top
                    });
                    $(modal_id).fadeTo(200, 1);
                    e.preventDefault()
                })
            });

            function close_modal(modal_id) {
                $("#lean_overlay").fadeOut(200);
                $(modal_id).css({
                    "display": "none"
                })
            }
        }
    })
})(jQuery);

/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.0
 *
 */
(function(f) {
    jQuery.fn.extend({
        slimScroll: function(h) {
            var a = f.extend({
                width: "auto",
                height: "250px",
                size: "7px",
                color: "#000",
                position: "right",
                distance: "0px",
                start: "top",
                opacity: 0.4,
                alwaysVisible: !1,
                disableFadeOut: !1,
                railVisible: !1,
                railColor: "#333",
                railOpacity: 0.2,
                railDraggable: !0,
                railClass: "slimScrollRail",
                barClass: "slimScrollBar",
                wrapperClass: "slimScrollDiv",
                allowPageScroll: !1,
                wheelStep: 20,
                touchScrollStep: 200,
                borderRadius: "7px",
                railBorderRadius: "7px"
            }, h);
            this.each(function() {
                function r(d) {
                    if (s) {
                        d = d ||
                            window.event;
                        var c = 0;
                        d.wheelDelta && (c = -d.wheelDelta / 120);
                        d.detail && (c = d.detail / 3);
                        f(d.target || d.srcTarget || d.srcElement).closest("." + a.wrapperClass).is(b.parent()) && m(c, !0);
                        d.preventDefault && !k && d.preventDefault();
                        k || (d.returnValue = !1)
                    }
                }

                function m(d, f, h) {
                    k = !1;
                    var e = d,
                        g = b.outerHeight() - c.outerHeight();
                    f && (e = parseInt(c.css("top")) + d * parseInt(a.wheelStep) / 100 * c.outerHeight(), e = Math.min(Math.max(e, 0), g), e = 0 < d ? Math.ceil(e) : Math.floor(e), c.css({
                        top: e + "px"
                    }));
                    l = parseInt(c.css("top")) / (b.outerHeight() - c.outerHeight());
                    e = l * (b[0].scrollHeight - b.outerHeight());
                    h && (e = d, d = e / b[0].scrollHeight * b.outerHeight(), d = Math.min(Math.max(d, 0), g), c.css({
                        top: d + "px"
                    }));
                    b.scrollTop(e);
                    b.trigger("slimscrolling", ~~e);
                    v();
                    p()
                }

                function C() {
                    window.addEventListener ? (this.addEventListener("DOMMouseScroll", r, !1), this.addEventListener("mousewheel", r, !1), this.addEventListener("MozMousePixelScroll", r, !1)) : document.attachEvent("onmousewheel", r)
                }

                function w() {
                    u = Math.max(b.outerHeight() / b[0].scrollHeight * b.outerHeight(), D);
                    c.css({
                        height: "20%"
                    });
                    var a = u == b.outerHeight() ? "none" : "block";
                    c.css({
                        display: a
                    })
                }

                function v() {
                    w();
                    clearTimeout(A);
                    l == ~~l ? (k = a.allowPageScroll, B != l && b.trigger("slimscroll", 0 == ~~l ? "top" : "bottom")) : k = !1;
                    B = l;
                    u >= b.outerHeight() ? k = !0 : (c.stop(!0, !0).fadeIn("fast"), a.railVisible && g.stop(!0, !0).fadeIn("fast"))
                }

                function p() {
                    a.alwaysVisible || (A = setTimeout(function() {
                        a.disableFadeOut && s || (x || y) || (c.fadeOut("slow"), g.fadeOut("slow"))
                    }, 1E3))
                }
                var s, x, y, A, z, u, l, B, D = 30,
                    k = !1,
                    b = f(this);
                if (b.parent().hasClass(a.wrapperClass)) {
                    var n = b.scrollTop(),
                        c = b.parent().find("." + a.barClass),
                        g = b.parent().find("." + a.railClass);
                    w();
                    if (f.isPlainObject(h)) {
                        if ("height" in h && "auto" == h.height) {
                            b.parent().css("height", "auto");
                            b.css("height", "auto");
                            var q = b.parent().parent().height();
                            b.parent().css("height", q);
                            b.css("height", q)
                        }
                        if ("scrollTo" in h) n = parseInt(a.scrollTo);
                        else if ("scrollBy" in h) n += parseInt(a.scrollBy);
                        else if ("destroy" in h) {
                            c.remove();
                            g.remove();
                            b.unwrap();
                            return
                        }
                        m(n, !1, !0)
                    }
                } else {
                    a.height = "auto" == a.height ? b.parent().height() : a.height;
                    n = f("<div></div>").addClass(a.wrapperClass).css({
                        position: "relative",
                        overflow: "hidden",
                        width: a.width,
                        height: a.height
                    });
                    b.css({
                        overflow: "hidden",
                        width: a.width,
                        height: "95%"
                        //height: a.height
                    });
                    var g = f("<div></div>").addClass(a.railClass).css({
                            width: a.size,
                            height: "100%",
                            position: "absolute",
                            top: 0,
                            display: a.alwaysVisible && a.railVisible ? "block" : "none",
                            "border-radius": a.railBorderRadius,
                            background: a.railColor,
                            opacity: a.railOpacity,
                            zIndex: 90
                        }),
                        c = f("<div></div>").addClass(a.barClass).css({
                            background: a.color,
                            width: a.size,
                            position: "absolute",
                            top: 0,
                            opacity: a.opacity,
                            display: a.alwaysVisible ?
                                "block" : "none",
                            "border-radius": a.borderRadius,
                            BorderRadius: a.borderRadius,
                            MozBorderRadius: a.borderRadius,
                            WebkitBorderRadius: a.borderRadius,
                            zIndex: 99
                        }),
                        q = "right" == a.position ? {
                            right: a.distance
                        } : {
                            left: a.distance
                        };
                    g.css(q);
                    c.css(q);
                    b.wrap(n);
                    b.parent().append(c);
                    b.parent().append(g);
                    a.railDraggable && c.bind("mousedown", function(a) {
                        var b = f(document);
                        y = !0;
                        t = parseFloat(c.css("top"));
                        pageY = a.pageY;
                        b.bind("mousemove.slimscroll", function(a) {
                            currTop = t + a.pageY - pageY;
                            c.css("top", currTop);
                            m(0, c.position().top, !1)
                        });
                        b.bind("mouseup.slimscroll", function(a) {
                            y = !1;
                            p();
                            b.unbind(".slimscroll")
                        });
                        return !1
                    }).bind("selectstart.slimscroll", function(a) {
                        a.stopPropagation();
                        a.preventDefault();
                        return !1
                    });
                    g.hover(function() {
                        v()
                    }, function() {
                        p()
                    });
                    c.hover(function() {
                        x = !0
                    }, function() {
                        x = !1
                    });
                    b.hover(function() {
                        s = !0;
                        v();
                        p()
                    }, function() {
                        s = !1;
                        p()
                    });
                    b.bind("touchstart", function(a, b) {
                        a.originalEvent.touches.length && (z = a.originalEvent.touches[0].pageY)
                    });
                    b.bind("touchmove", function(b) {
                        k || b.originalEvent.preventDefault();
                        b.originalEvent.touches.length &&
                            (m((z - b.originalEvent.touches[0].pageY) / a.touchScrollStep, !0), z = b.originalEvent.touches[0].pageY)
                    });
                    w();
                    "bottom" === a.start ? (c.css({
                        top: b.outerHeight() - c.outerHeight()
                    }), m(0, !0)) : "top" !== a.start && (m(f(a.start).position().top, null, !0), a.alwaysVisible || c.hide());
                    C()
                }
            });
            return this
        }
    });
    jQuery.fn.extend({
        slimscroll: jQuery.fn.slimScroll
    })
})(jQuery);

//scrollTo function
jQuery.docuScrollTo = jQuery.fn.docuScrollTo = function(x, y, options){
    if (!(this instanceof jQuery)) return jQuery.fn.docuScrollTo.apply(jQuery('html,body'), arguments);

    options = jQuery.extend({}, {
        gap: {
            x: 0,
            y: 0
        },
        animation: {
            easing: 'swing',
            duration: 1000,
            complete: jQuery.noop,
            step: jQuery.noop
        }
    }, options);

    return this.each(function(){
        var elem = jQuery(this);
		var menuTop = !isNaN(Number(options.menuTop)) ? ( Number(options.menuTop) + 12 ) : 12;
	elem.stop().animate({
            scrollLeft: !isNaN(Number(x)) ? x : jQuery(y).offset().left + options.gap.x,
            scrollTop: (!isNaN(Number(y)) ? y : jQuery(y).offset().top + options.gap.y) - menuTop 
	}, options.animation);
    });
};
//function to get current url parameter
function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    sPageURL = decodeURI(sPageURL);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
} 
//social share functions to get share count
// Facebook Shares Count
function docfacebookShares($URL) {
	if ( jQuery('#doc_fb_share').hasClass('doc-fb-share') ) {
		jQuery.getJSON('https://graph.facebook.com/?id=' + $URL, function (fbdata) {
			jQuery('#doc-fb-count').text( ReplaceNumberWithCommas(fbdata.shares || 0) );
		});
	} 
}
// Twitter Shares Count
function doctwitterShares($URL) {
	if ( jQuery('#doc_twitter_share').hasClass('doc-twitter-share') ) {
		jQuery.getJSON('https://cdn.api.twitter.com/1/urls/count.json?url=' + $URL + '&callback=?', function (twitdata) {
			jQuery('#doc-twitter-count').text( ReplaceNumberWithCommas(twitdata.count) );
		});
	} 
}
// Pinterest Shares Count
function docpinterestShares($URL) {
	if ( jQuery('#doc_pin_share').hasClass('doc-pin-share') ) {
		jQuery.getJSON('https://api.pinterest.com/v1/urls/count.json?url=' + $URL + '&callback=?', function (pindata) {
			jQuery('#doc-pin-count').text( ReplaceNumberWithCommas(pindata.count) );
		});
	} 
}
function ReplaceNumberWithCommas(shareNumber) {
	 if (shareNumber >= 1000000000) {
		return (shareNumber / 1000000000).toFixed(1).replace(/\.0$/, '') + 'G';
	 }
	 if (shareNumber >= 1000000) {
		return (shareNumber / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
	 }
	 if (shareNumber >= 1000) {
		return (shareNumber / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
	 }
	 return shareNumber;
}
;(function($){
	jQuery.fn.documentor=function(args){
		var defaults= {
			documentid	: '1',
			docid		: '1',
			animation	: '',
			indexformat	: '1',
			pformat     : 'decimal',
			cformat		: 'decimal',			
			secstyle	: '',
			actnavbg_default: '0',
			actnavbg_color	: '#f3b869',
			scrolling	: "1",
			skin		: "default",
			scrollBarSize	: "3",
			scrollBarColor	: "#F45349",
			scrollBarOpacity: "0.4",
			windowprint	: '0',
			menuTop: '0',
			zeroFooter: '',
			noResultsStr: "No results found!"
		}		
		var options=jQuery.extend({},defaults,args);		
		var documentHandle = options.docid;
		if(options.animation.length > 0 ) {
			wow = new WOW({
				boxClass:     "wow",      
				animateClass: "documentor-animated", 
				offset:       0,          
				mobile:       true,       
				live:         true        
			});
			wow.init();
		}

		if(options.indexformat == '1') {
			var countercss = '';			
			countercss = "#"+documentHandle+" .doc-menu ol.doc-list-front > li:before {content: counter(item,"+options.pformat+") \".\";counter-increment: item; "+options.secstyle+";}.doc-menu ol ol li:before {content: counter(item,"+options.pformat+")\".\"counters(childitem, \".\", "+options.cformat+") \".\";counter-increment: childitem;"+options.secstyle;
			if( options.skin == 'broad') {
				countercss = "#"+documentHandle+" .doc-menu ol, .doc-menu li{list-style: none;} #"+documentHandle+" .doc-menu ol.doc-list-front > li:before {content: counter(item,"+options.pformat+") \".\";counter-increment: item;"+options.secstyle+";}.doc-menu ol ol li:before {content: counter(item,"+options.pformat+")\".\"counters(childitem, \".\", "+options.cformat+") \".\";counter-increment: childitem;";
			}
			jQuery("head").append("<style type=\"text/css\"> #"+documentHandle+" .doc-menu ol.doc-list-front {counter-reset: item ;}.doc-menu ol ol {counter-reset: childitem;}#"+documentHandle+" ol.doc-menu {margin-top: 20px;}#"+documentHandle+" .doc-menu ol li {display: block;}"+countercss+"}</style>");
		} else {
			jQuery("head").append("<style type=\"text/css\">#"+documentHandle+" .doc-menu ol {list-style: none;}#"+documentHandle+" .doc-menu li {list-style: none;}</style>");
		}
		if(options.actnavbg_default != '1' && options.actnavbg_color.length > 0 && options.skin != 'broad' ) {
			jQuery("head").append("<style type=\"text/css\">#"+documentHandle+" .doc-menu ol > li.doc-acta{background-color: "+options.actnavbg_color+"}</style>");
		}
		if( options.fixmenu == 1 ) {
			jQuery(".document-wrapper").css("min-height",jQuery(window).height()+'px');
			var btm=0;
			if( options.zeroFooter.length > 0 ) btm = options.zeroFooter;
			else {
				var docEnd = jQuery("#"+documentHandle+"-end").position(); //cache the position
				btm = parseInt( document.body.clientHeight - docEnd.top );
			}
			jQuery.lockfixed("#"+documentHandle+" .doc-menu",{offset: {top: options.menuTop, bottom: btm}});
		}
		if( options.skin == 'broad' ) {
			jQuery("#"+documentHandle+" ol.doc-list-front li:first").addClass('doc-acta');
		}
			
		/* Call bindbehaviours on load */
		var cnxt=jQuery(this);
		bindsectionBehaviour(cnxt, options);
		
		/* Search in document */
		jQuery("#"+documentHandle+" .search-document").autocomplete({
			source: function(req, response){
				var keyword = jQuery("#"+documentHandle+" .search-document").val();
				var container = jQuery("#"+documentHandle+" .doc-sec-container");
				container.unmark({
					done: function() {
						container.mark( keyword );
					}
				});
				req['docid'] = options.documentid;
				jQuery.getJSON(DocAjax.docajaxurl+'?callback=?&action=doc_search_results', req, response);
			},
			response: function ( event, ui ) {
				if( ui.content.length <= 0 ){
					ui.content.push({
						label:options.noResultsStr, 
						value:""
					});
				}
			},
			select: function(event, ui) {
				var thref = ui.item.slug;
				jQuery("#"+documentHandle+" a[data-href='#"+thref+"']")[0].click();
				return false;
			},
			delay: 200,
			minLength: 3
		}).autocomplete( "widget" ).addClass( "doc-sautocomplete" );
		
		/**
		 * This part causes smooth scrolling using scrollto function
		*/
		if( jQuery("#"+documentHandle+" .doc-firstnext").length > 0 ) {
			var activea = jQuery("#"+documentHandle+" .doc-acta");
			var nextsecid = activea.nextAll().find('a:first').data("href");
			var nextsecname = 'Next';
			nextsecname = activea.nextAll().find('a:first').html();
			if( typeof nextsecid === 'undefined' ) {
				nextsecid = activea.parents('.doc-actli:last').nextAll().find('a:first').data("href");
				nextsecname = activea.parents('.doc-actli:last').nextAll().find('a:first').html();
			}
			if( typeof nextsecid === 'undefined' ) {
				nextsecid = '0';
			}
			jQuery("#"+documentHandle+" .doc-firstnext").attr('data-href',nextsecid);
			jQuery("#"+documentHandle+" .doc-firstnext").html(nextsecname+' &raquo;');
		}
		jQuery(this).find(".doc-menu a").not('.documentor-menu').click(function(evn) {
			evn.preventDefault();
			if( jQuery(this).attr('target') == "_blank" ) {
				window.open(jQuery(this).attr('href'), '_blank');
			} else {
				window.location = jQuery(this).attr('href');
			}
		});
		jQuery(this).find(".doc-menu a.documentor-menu").click(function(evn){
			if( typeof documentorPreview === 'undefined' && options.scrolling == "1" ){
				evn.preventDefault();
			}
			jQuery(this).parents('.doc-menu:first').find('a.documentor-menu, li.doc-actli').removeClass('doc-acta');
			jQuery(this).addClass('doc-acta');
			jQuery(this).parents('li.doc-actli:first').addClass('doc-acta');
			//for broad skin
			if( options.skin == 'broad' ) {
				if( options.togglechild == 1 ) {
					jQuery('.doc-menu li ol:not(:has(.doc-acta))').hide();
					jQuery(this).parents('.doc-actli:last').find('ol').show();
				}
				jQuery("#"+documentHandle+" .doc-menu li").removeClass('doc-acta');
				jQuery( "#"+documentHandle+" a.doc-acta" ).parents("li:last").addClass('doc-acta');
				/*var mwrapcnt = jQuery( this ).data('mwrapcnt');
				if( typeof mwrapcnt === 'undefined' ) {
					mwrapcnt = jQuery(this).parents("li.doc-actli:last").find("a").data('mwrapcnt');
				}
				if( typeof mwrapcnt !== 'undefined' ) {
					jQuery("#"+documentHandle+" .doc-sectionwrap").hide();
					jQuery("#"+documentHandle+" .doc-sectionwrap[data-wrapcnt="+mwrapcnt+"]").fadeIn( 400 );
				}*/
				
				var active_menu = jQuery( this ).data( 'href' );
				if( typeof active_menu === 'undefined' ) {
					active_menu = jQuery( this ).parents( 'li.doc-actli:last' ).find( 'a' ).data( 'href' );
				}
				active_menu=active_menu.replace( '#', '' );
				if( typeof active_menu !== 'undefined' ) {
					jQuery("#"+documentHandle+" .doc-sectionwrap").hide();
					jQuery("#"+documentHandle+" .doc-sectionwrap#"+active_menu+"_wrap" ).fadeIn( 400 );
				}
				
				var visiblemheight = jQuery("#"+documentHandle+" .doc-menu ol.doc-list-front").height();
				jQuery("#"+documentHandle+" .doc-sec-container").css('min-height',visiblemheight+'px');
			
			}
			
			/* Do not apply animation effect if click on menu item */
			//jQuery("#"+documentHandle).find(".documentor-section").css({"visibility":"visible","-webkit-animation":"none"});
			/**/
				
			if( typeof documentorPreview === 'undefined' && jQuery(this.hash).length > 0 && options.scrolling == "1" ) {
				var dstopts = {
					'menuTop': options.menuTop
				};
			 	jQuery('html,body').docuScrollTo( this.hash, this.hash, dstopts ); 
			}
		
		});
		
		//js
		jQuery(this).find(".doc-menu a.documentor-menu:first, .doc-menu li.doc-actli:first").addClass('doc-acta');
		
		/* if in url section is present - end */
		
		//v1.5 fix - Fix for Documentor Broad skin - Inter sections links not working
		jQuery("#"+documentHandle+".documentor-broad .doc-sec-container a").on( "click", function( event ){
			var link_hash = jQuery(this).attr('href').replace(/^.*?#/,'');
			if( link_hash.length>0 && jQuery("a.documentor-menu[data-href='#"+link_hash+"']").length>0 ){
				event.preventDefault();
				jQuery("a.documentor-menu[data-href='#"+link_hash+"']").trigger("click");
			}
		} );
		
		/* For broad skin - if link with hash value of section is opened in window */
		if( location.hash != "" && options.skin == 'broad' ) {
			var hashval = location.hash;
			jQuery("a.documentor-menu[data-href='"+hashval+"']").trigger("click");
		}     
			           
		/**
		 * This part handles the highlighting functionality.
		 */
		var aChildren = jQuery(this).find(".doc-menu li.doc-actli").children('a.documentor-menu'); // find the a children of the list items
		var aArray = []; // create the empty aArray
		for (var i=0; i < aChildren.length; i++) {    
			var aChild = aChildren[i];
			var ahref = jQuery(aChild).data('href');
			aArray.push(ahref);
		} // this for loop fills the aArray with attribute href values
		
		jQuery(window).scroll(function(){
			var window_top = jQuery(window).scrollTop() + 12; // the "12" should equal the margin-top value for nav.stick
			var windowPos = jQuery(window).scrollTop(); // get the offset of the window from the top of page
			var windowHeight = jQuery(window).height(); // get the height of the window
			var docHeight = jQuery(document).height();
	
			if(windowPos + windowHeight == docHeight) {
				if (!jQuery("#"+documentHandle+" .doc-menu li:last-child a").hasClass("doc-acta")) {
					var navActiveCurrent = jQuery("#"+documentHandle+" .doc-acta").data("href");
					jQuery("#"+documentHandle+" a[data-href='" + navActiveCurrent + "']").removeClass("doc-acta");
					jQuery("#"+documentHandle+" .doc-menu li:last-child a.documentor-menu").addClass("doc-acta");
				}
			}
			
			clearTimeout(jQuery.data(this, 'scrollTimer'));
			jQuery.data(this, 'scrollTimer', setTimeout(function() {
				// do something
				for (var i=0; i < aArray.length; i++) {
					if( jQuery(aArray[i]).length > 0 ) {
						var theID = aArray[i];
						var divPos = jQuery(theID).offset().top - (windowHeight*0.20); // get the offset of the div from the top of page
						var divHeight = jQuery(theID).outerHeight(true); // get the height of the div in question
						if (windowPos >= divPos && windowPos < (divPos + divHeight)) {
							var temp=jQuery("#"+documentHandle+" a.documentor-menu[data-href='" + theID + "']");
							if (!temp.hasClass("doc-acta")) {
								temp.addClass("doc-acta");
							}
						} else {
							jQuery("#"+documentHandle+" a[data-href='" + theID + "']").removeClass("doc-acta");
						}
					}
				}
				//commented one line v1.1
				/*if(jQuery("#"+documentHandle+" a.doc-acta").length<=0) {
					jQuery("#"+documentHandle+" .doc-menu a.documentor-menu:first").addClass("doc-acta");
				}*/
				jQuery("#"+documentHandle+" .doc-menu a.documentor-menu.doc-acta").parent('li').addClass("doc-acta");
				jQuery("#"+documentHandle+" .doc-menu a:not(.doc-acta)").parent('li').removeClass("doc-acta");
			}, 200));
			//right positioned menu
			jQuery(window).scroll(function(){
				if( jQuery("#"+documentHandle+" .doc-menuright.doc-menufixed").length > 0 ) {
					var mleft = jQuery("#"+documentHandle).outerWidth()-jQuery("#"+documentHandle+" .doc-menuright.doc-menufixed").outerWidth();
					jQuery("#"+documentHandle+" .doc-menuright.doc-menufixed").css('margin-left',mleft+'px');
				} else {
					jQuery("#"+documentHandle+" .doc-menuright").css('margin-left','0px');
				}
			});
		});

		/* Expand / collapse menus */
		jQuery("#"+documentHandle+" .doc-menu.toggle .doc-mtoggle").on('click', function() {
			jQuery(this).toggleClass('expand');
			jQuery(this).parent('.doc-actli').find('ol:first').slideToggle('slow');
		});
		//scroll bar js
		var windowHt=jQuery(window).height();
		var scrollBarHt = parseInt( (((windowHt - options.menuTop)/windowHt)*100) - 7 );
		 jQuery("#"+documentHandle+" .doc-menurelated").slimScroll({
			  size: options.scrollBarSize+'px', 
			  height: scrollBarHt+'%', 
			  color: options.scrollBarColor, 
			  opacity: options.scrollBarOpacity,
		});
		/*scrolltop*/
		jQuery(".scrollup").on('click', function() {
			var doctop = jQuery("#"+documentHandle).offset().top-50;
			jQuery("html, body").animate({scrollTop:doctop}, 600);
		});
		/*show scrolltop button*/
		jQuery("body").hover(function(){
			jQuery("#"+documentHandle+" .scrollup").stop(true,true).animate({'opacity':'0.8'},1000);
		},function() {
			jQuery("#"+documentHandle+" .scrollup").stop(true,true).animate({'opacity':'0'},1000);
		});	
		//print document
		jQuery("#"+documentHandle+" .documentor-topicons .doc-print").on('click', function(e) {
			if( options.windowprint == '1' ) {
				e.preventDefault();
				var printCSS=jQuery('<link rel="stylesheet" href="'+jQuery(this).data('printspath')+'" media="print" />').prependTo("head");
				printCSS.on('load', function(){
					jQuery("#"+documentHandle+" .documentor-section").each(function(i, elm){
						var st = jQuery(this).attr("style");	
						if( st !== undefined ) {					
							st=st.replace("hidden","visible");
							jQuery(this).attr("style", st);
						}
						else{
							jQuery(this).attr("style", "visibility: visible !important;");
						}
					});
					window.print();
					printCSS.remove();
				});
				return false;
			} else {
				jQuery("#"+documentHandle).find('iframe[src*="youtube.com"]').each(function() {
					var url = jQuery(this).attr("src");
					if( jQuery(this).parent().find('.doc-isrc').length <= 0 ) {
						jQuery(this).after( '<div class="doc-isrc">'+url+'</div>' );
					}
				}); 
				jQuery("#"+documentHandle).find('iframe[src*="youtube.com"]').addClass('doc-noprint');
				jQuery("#"+documentHandle).find('iframe[src*="vimeo.com"]').each(function() {
					var url = jQuery(this).attr("src");
					if( jQuery(this).parent().find('.doc-isrc').length <= 0 ) {
						jQuery(this).after( '<div class="doc-isrc">'+url+'</div>' );
					}
				});
				jQuery("#"+documentHandle).find('iframe[src*="vimeo.com"]').addClass('doc-noprint'); 
				jQuery("#"+documentHandle).find("object, embed, video, .wp-video").addClass('doc-noprint');
				jQuery("#"+documentHandle+" .documentor-section").each(function(i, elm){
					var st = jQuery(this).attr("style");
					if( st !== undefined ) {
						st=st.replace("hidden","visible");
						jQuery(this).attr("style", st);
					}
				});
				jQuery("#"+documentHandle).print({
					noPrintSelector : ".doc-noprint",
					wrapClass : ""
				});
			}
		});
		/* Call social sharing count functtions on load */
		if( options.socialshare == 1 && options.sharecount == 1 ) {
			var sharelink = jQuery("#"+documentHandle+" .doc-sharelink").data('sharelink');
			if( sharelink != '' ) {
				if( options.fbshare == 1 ) {
					docfacebookShares( sharelink );
				}
				if( options.twittershare == 1 ) {
					doctwitterShares( sharelink );
				}
				if( options.gplusshare == 1 ) {
					if ( jQuery('#doc_gplus_share').hasClass('doc-gplus-share') ) {
						// Google Plus Shares Count
						var googleplusShares = jQuery('#doc-gplus-count').data('gpluscnt');
						jQuery('#doc-gplus-count').text( ReplaceNumberWithCommas(googleplusShares) )
					}
				}
				if( options.pinshare == 1 ) {
					docpinterestShares( sharelink );
				}
			}
		}
	}
	/*bind behaviours at front end*/
	var bindsectionBehaviour = function(scope, options) {
		var documentHandle = options.docid;
		//apply leanmodal popup
		jQuery(".documentor-wrap").find("a[rel*=leanModal]").leanModal({ top : "15%", overlay : 0.4, closeButton: ".modal_close" });
		//print document section
		jQuery(".documentor-social .doc-print", scope).on('click', function(e) {
			if( options.windowprint == '1' ) {
				e.preventDefault();
				var printCSS=jQuery('<link rel="stylesheet" href="'+jQuery(this).data('printspath')+'" media="print" />').prependTo("head");
				printCSS.on('load', function(){
					jQuery("#"+documentHandle+" .documentor-section").each(function(i, elm){
						var st = jQuery(this).attr("style");	
						if( st !== undefined ) {					
							st=st.replace("hidden","visible");
							jQuery(this).attr("style", st);
						}
						else{
							jQuery(this).attr("style", "visibility: visible !important;");
						}
					});
					window.print();
					printCSS.remove();
				});
				return false;
			} else {
				var docsection = jQuery(this).parents('.documentor-section:first');
				docsection.find('iframe[src*="youtube.com"]').each(function() {
					var url = jQuery(this).attr("src");
					if( jQuery(this).parent().find('.doc-isrc').length <= 0 ) {
						jQuery(this).after( '<div class="doc-isrc">'+url+'</div>' );
					}
				}); 
				docsection.find('iframe[src*="youtube.com"]').addClass('doc-noprint');
				docsection.find('iframe[src*="vimeo.com"]').each(function() {
					var url = jQuery(this).attr("src");
					if( jQuery(this).parent().find('.doc-isrc').length <= 0 ) {
						jQuery(this).after( '<div class="doc-isrc">'+url+'</div>' );
					}
				});
				docsection.find('iframe[src*="vimeo.com"]').addClass('doc-noprint'); 
				docsection.find("object, embed, video, .wp-video").addClass('doc-noprint');
				docsection.print({
					noPrintSelector : ".doc-noprint",
					wrapClass	: "documentor-"+options.skin
				});
			}
		});
		/*next section*/
		jQuery('.doc-next, .doc-firstnext', scope).on('click', function(e) {
			var ahref = jQuery(this).data('href');
			jQuery(this).parents('.documentor-wrap:first').find('.doc-menu a[data-href="'+ahref+'"]').trigger('click');
		});
		/*previous section*/
		jQuery('.doc-prev', scope).on('click', function(e) {
			var ahref = jQuery(this).data('href');
			jQuery(this).parents('.documentor-wrap:first').find('.doc-menu a[data-href="'+ahref+'"]').trigger('click');
		});
		/*positive feedback*/
		jQuery( ".positive-feedback", scope ).click(function(e) {
			e.preventDefault();
			var secid = jQuery( this ).parents(".documentor-section:first").data('section-id');
			var docid = jQuery( this ).parents(".documentor-wrap:first").data('docid');
			var data = {
					'action': 'doc_positive_feedback',
					'secid': secid,
					'docid': docid
				};
			//display loader
			var msgArea=jQuery('.section-'+data['secid']+' .feedback-msg');
			msgArea.empty();
			var loaderGif=jQuery( '<div class="doc-loader"></div>' ).appendTo( msgArea );
			jQuery.post(DocAjax.docajaxurl, data, function(response) {
				response = response.replace(/^\s*[\r\n]/gm, "");
    				response = response.match(/!!START!!(.*[\s\S]*)!!END!!/)[1];
				var res = JSON.parse(response);
				loaderGif.remove();
				if( res['success'] == 1 ) {
					jQuery('.section-'+data['secid']).find(".feedback-msg").html("<div class='doc-success-msg'>"+res['msg']+"</div>");
					//if votecount present then increment total and positive vote count
					if( jQuery('.section-'+data['secid']).find('.doc-feedbackcnt .upvote').length > 0 ) {
						var upvotespan = jQuery('.section-'+data['secid']).find('.doc-feedbackcnt .upvote');
						var totalvotespan = jQuery('.section-'+data['secid']).find('.doc-feedbackcnt .totalvote');
						var upvotecnt = parseInt(upvotespan.html());
						var totalvotecnt = parseInt(totalvotespan.html());
						jQuery(upvotespan).html(upvotecnt+1);
						jQuery(totalvotespan).html(totalvotecnt+1);
					}
				} else {
					jQuery('.section-'+data['secid']).find(".feedback-msg").html(res['msg']);
				}
			});
		});
		/*negative feedback*/
		jQuery( ".negative-feedback", scope ).click(function(e) {
				e.preventDefault();
				var docid = jQuery( this ).parents(".documentor-wrap:first").data('docid');
				var secid = jQuery( this ).parents(".documentor-section:first").data('section-id');
				var data = {
					'action': 'doc_get_feedback_form',
					'docid': docid,
					'secid': secid
				};
				//display loader
				var msgArea=jQuery('.section-'+data['secid']+' .feedback-msg');
				msgArea.empty();
				var loaderGif=jQuery( '<div class="doc-loader"></div>' ).appendTo( '.section-'+data['secid']+' .feedback-msg' );
				jQuery.post(DocAjax.docajaxurl, data, function(response) {
					response = response.replace(/^\s*[\r\n]/gm, "");
  					response = response.match(/!!START!!(.*[\s\S]*)!!END!!/)[1];
					//check if message is returned
					var res = JSON.parse(response);
					loaderGif.remove();
					if( res['msgflag'] == 1 ) {
						jQuery('.section-'+data['secid']).find(".feedback-msg").html(res['text']);
						
					} else {
						jQuery('.section-'+data['secid']).find('.negative-feedbackform').html(res['text']).slideToggle("slow");
					}
				});
			});
		/*negative feedback form submit*/
		jQuery('.documentor-help', scope).on('click', '.docsubmit-nfeedback', function() {
			var submitbtn = jQuery( this ).attr('class');
			var data = {
					'action': 'doc_negative_feedback',
					'submitbtn': submitbtn
				};
				jQuery(this).parents('.documentor-nfeedback').serializeArray().map(function(item) {
						data[item.name] = item.value;
				});
			jQuery.post(DocAjax.docajaxurl, data, function(response) {
				jQuery('.section-'+data['secid']).find('.negative-feedbackform').slideUp("slow");
				response = response.replace(/^\s*[\r\n]/gm, "");
    				response = response.match(/!!START!!(.*[\s\S]*)!!END!!/)[1];
				var res = JSON.parse(response);
				if( res['success'] == 1 ) {
					jQuery('.section-'+data['secid']).find(".feedback-msg").html("<div class='doc-success-msg'>"+res['msg']+"</div>");
					//if votecount present then increment total vote count
					if( jQuery('.section-'+data['secid']).find('.doc-feedbackcnt .upvote').length > 0 ) {
						var totalvotespan = jQuery('.section-'+data['secid']).find('.doc-feedbackcnt .totalvote');
						var totalvotecnt = parseInt(totalvotespan.html());
						jQuery(totalvotespan).html(totalvotecnt+1);
					}	
				} else {
					jQuery('.section-'+data['secid']).find(".feedback-msg").html(res['msg']);
				}
			});
			return false;
		});
	}
})(jQuery);
