/**
 *  Advanced iframe external workaround file v7.5.x free 
 *  Created: PARAM_TIMESTAMP  
*/ 

var domain_PARAM_ID = 'PLUGIN_URL'; // Check if this is your wordpress directory of ai
 
 // Variables are checked with typeof before because this enables that the user can
// define this values before and after including this file and they don't have to set 
// them at all if not needed.
if (typeof iframe_id === 'undefined') {
    var iframe_id_PARAM_ID = 'PARAM_ID';
}  else {
/* jshint ignore:start */
    iframe_id_PARAM_ID = iframe_id;
/* jshint ignore:end */
}
if (typeof updateIframeHeight === 'undefined') {
    var updateIframeHeight = 'PARAM_ENABLE_EXTERNAL_HEIGHT_WORKAROUND';
} 
if (typeof keepOverflowHidden === 'undefined') {
    var keepOverflowHidden = 'PARAM_KEEP_OVERFLOW_HIDDEN';
}
if (typeof hide_page_until_loaded_external === 'undefined') {
    var hide_page_until_loaded_external = 'PARAM_HIDE_PAGE_UNTIL_LOADED_EXTERNAL';
}
       
// load jQuery if not available   TODO - use the one from wordpress!
window.jQuery || document.write('<script src="PARAM_JQUERY_PATH"></script>');

/**
 * The function creates a hidden iframe and determines the height of the 
 * current page. This is then set as height parameter for the iframe 
 * which triggers the resize function in the parent.  
 */ 
function aiExecuteWorkaround_PARAM_ID() {
    if (window!==window.top) { /* I'm in a frame! */ 

      if (updateIframeHeight === 'true') { 
        // add the iframe dynamically
        var url = domain_PARAM_ID+'/js/iframe_height.html';
        var empty_url = 'about:blank';
        var newElementStr = '<iframe id="ai_hidden_iframe_PARAM_ID" style="display:none;clear:both" width="0" height="0" src="';
        newElementStr += empty_url +'">Iframes not supported.</iframe>';
        var newElement = aiCreate(newElementStr);
        document.body.appendChild(newElement);
             
        // add a wrapper div below the body to measure - if you remove this you have to measure the height of the body! 
        // See below for this solution. The wrapper is only created if needed
        createAiWrapperDiv();
        
        // remove any margin,padding from the body because each browser handles this differently
        // Overflow hidden is used to avoid scrollbars that can be shown for a milisecond
        aiAddCss('body {margin:0px;padding:0px;overflow:hidden;}');
        
        // get the height of the element right below the body - Using this solution allows that the iframe shrinks also.
        var wrapperElement = document.body.children[0];
        var newHeight = parseInt(wrapperElement.offsetHeight,10);
    
        //  Get the height from the body. The problem with this solution is that an iframe can not shrink anymore.
        //  remove everything from createAiWrapperDiv() until here for the alternative solution. 
        //  var newHeight = Math.max(document.body.scrollHeight, document.body.offsetHeight,
        //    document.documentElement.scrollHeight, document.documentElement.offsetHeight);  
        //  This is the width - need to detect a change of the iframe width at a browser resize!
        var iframeWidth = getIframeWidth();
    
        var iframe = document.getElementById('ai_hidden_iframe_PARAM_ID');
        // 4 pixels extra are needed because of IE! (2 for Chrome)
        // If you still have scrollbars add a little bit more offset.
        var url_str = url + '?height=' + (newHeight + 4) + '&width=' + iframeWidth + '&id=' + iframe_id_PARAM_ID; 
        iframe.src = url_str; 
        
        // set overflow to visible again.
        if (keepOverflowHidden === 'false') {
            window.setTimeout(removeOverflowHidden,500);
        }
      } else if (hide_page_until_loaded_external === 'true') {  // only one iframe is rendered - if auto height is disabled still the parent has to be informed to show the iframe ;).
        // add the iframe dynamically
        var urlShow = domain_PARAM_ID + '/js/iframe_show.html?id='+ iframe_id_PARAM_ID;
        var newElementStrShow = '<iframe id="ai_hidden_iframe_show_PARAM_ID" style="display:none;" width="0" height="0" src="';
        newElementStrShow += urlShow+'">Iframes not supported.</iframe>';
        var newElementShow = aiCreate(newElementStrShow);
        document.body.appendChild(newElementShow);
      }
      // In case html was hidden. 
      document.documentElement.style.visibility = 'visible';   
    }
}

/**
 *  Remove the overflow:hidden from the body which
 *  what avoiding scrollbars during resize. 
 */ 
function removeOverflowHidden() {
    document.body.style.overflow='auto';
}

/**
 *  Gets the text length from text nodes. For other nodes a dummy length is returned
 *  browser do add empty text nodes between elements which should return a length
 *  of 0 because they should not be counted. 
 */ 
function getTextLength( obj ) {
    var value = obj.textContent ? obj.textContent : 'NO_TEXT';
    return value.trim().length;
} 

/**
 * Creates a wrapper div if needed. 
 * It is not created if the body has only one single div below the body.
 * childNdes.length has to be > 2 because the iframe is already attached!    
 */ 
function createAiWrapperDiv() {
    var countElements = 0;
    // Count tags which are not empty text nodes, no script and no iframe tags
    // because only if we have more than 1 of this tags a wrapper div is needed
    for (var i = 0; i < document.body.childNodes.length; ++i) {
       var nodeName = document.body.childNodes[i].nodeName.toLowerCase(); 
       var nodeLength = getTextLength(document.body.childNodes[i]); 
       if ( nodeLength !== 0 && nodeName !== 'script' && nodeName !== 'iframe') {
           countElements++;  
       }
    }
    if (countElements > 1) {
      var div = document.createElement('div');
  	  div.id = 'ai_wrapper_div';
    	// Move the body's children into this wrapper
    	while (document.body.firstChild) {
    		div.appendChild(document.body.firstChild);
    	}
    	// Append the wrapper to the body
    	document.body.appendChild(div);
      
      // set the style
      div.style.cssText = 'margin:0px;padding:0px;border: none;';
    }
}

/**
 *  Creates a new dom fragment from a string
 */ 
function aiCreate(htmlStr) {
    var frag = document.createDocumentFragment(),
    temp = document.createElement('div');
    temp.innerHTML = htmlStr;
    while (temp.firstChild) {
        frag.appendChild(temp.firstChild);
    }
    return frag;
}
function getIframeWidth() { 
  var wrapperElement = document.body.children[0];
  var newWidthRaw =  Math.max(wrapperElement.scrollWidth, wrapperElement.offsetWidth, 
                              wrapperElement.scrollWidth, wrapperElement.offsetWidth);
  return parseInt(newWidthRaw,10);
} 

/**
 *  Adds a css style to the head 
 */         
function aiAddCss(cssCode) {
    var styleElement = document.createElement('style');
    styleElement.type = 'text/css';
    if (styleElement.styleSheet) {
      styleElement.styleSheet.cssText = cssCode;
    } else {
      styleElement.appendChild(document.createTextNode(cssCode));
    }
    document.getElementsByTagName('head')[0].appendChild(styleElement);
}

if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  };
}

/**
 * Helper function without jQuery to add a onload event 
 * even if there is already one attached. 
 */ 
function addOnloadEvent(fnc){
  if ( typeof window.addEventListener !== 'undefined' ) {
    window.addEventListener( 'load', fnc, false );
  } else if ( typeof window.attachEvent !== 'undefined' ) {
    window.attachEvent( 'onload', fnc );
  }
  else {
    if ( window.onload != null ) {
      var oldOnload = window.onload;
      window.onload = function ( e ) {
        oldOnload( e );
        window[fnc]();
      };
    }
    else {
      window.onload = fnc;
    }
  }
}

// add the aiUpdateIframeHeight to the onload of the site.
addOnloadEvent(aiExecuteWorkaround_PARAM_ID);