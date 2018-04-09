(function($)
{
	"use strict";
	
	$.AviaTooltip  =  function(options)
	{
	   var defaults = {
            delay: 1500,                //delay in ms until the tooltip appears
            delayOut: 300,             //delay in ms when instant showing should stop
            "class": "avia-tooltip",     //tooltip classname for css styling and alignment
            scope: "#avia_builder",    //area the tooltip should be applied to    						
            data:  "avia-tooltip",     //data attribute that contains the tooltip text
            attach:"element",          //either attach the tooltip to the "element" or "body" // todo: implement mouse, make sure that it doesnt overlap with screen borders
            event: 'mouseenter',       //mousenter and leave or click and leave
            position:'top'             //top or bottom 
        }
	   
        this.options = $.extend({}, defaults, options);
        this.body    = $('body');
        this.scope   = $(this.options.scope);
        this.tooltip = $('<div class="'+this.options['class']+'"><span class="avia-arrow-wrap"><span class="avia-arrow"></span></span></div>');
        this.inner   = $('<div class="inner_tooltip"></div>').prependTo(this.tooltip);
        this.open    = false;
        this.timer   = false;
        this.active  = false;
        
        this.bind_events();
	}
	
  $.AviaTooltip.openTTs = [];
  $.AviaTooltip.prototype = 
    {
        bind_events: function()
        {
            this.scope.on(this.options.event + ' mouseleave', '[data-'+this.options.data+']', $.proxy( this.start_countdown, this) );
            
            if(this.options.event != 'click')
            {
                this.scope.on('mouseleave', '[data-'+this.options.data+']', $.proxy( this.hide_tooltip, this) );
            }
            else
            {
                this.body.on('mousedown', $.proxy( this.hide_tooltip, this) );
            }
        },
        
        start_countdown: function(e)
        {
            clearTimeout(this.timer);

            if(e.type == this.options.event)
            {
                var delay = this.options.event == 'click' ? 0 : this.open ? 0 : this.options.delay;
            
                this.timer = setTimeout($.proxy( this.display_tooltip, this, e), delay);
            }
            else if(e.type == 'mouseleave')
            {
                this.timer = setTimeout($.proxy( this.stop_instant_open, this, e), this.options.delayOut);
            }
            e.preventDefault();
        },
        
        reset_countdown: function(e)
        {
            clearTimeout(this.timer);
            this.timer = false;
        },
        
        display_tooltip: function(e)
        {
            var target 	= this.options.event == "click" ? e.target : e.currentTarget,
            	element = $(target),
                text    = element.data(this.options.data),
                newTip  = element.data('avia-created-tooltip'),
                attach  = this.options.attach == 'element' ? element : this.body,
                offset  = this.options.attach == 'element' ? element.position() : element.offset();
            
            this.inner.html(text);  
            newTip = typeof newTip != 'undefined' ? $.AviaTooltip.openTTs[newTip] : this.tooltip.clone().appendTo(attach);
            this.open = true; 
            this.active = newTip; 
            
            if(newTip.is(':animated:visible') && e.type == 'click') return;
            
            
            var real_top  = offset.top - newTip.outerHeight(),
                real_left = (offset.left + (element.outerWidth() / 2)) - (newTip.outerWidth() / 2);
            
            if(this.options.position == 'bottom')
            {
                real_top = offset.top + element.outerHeight();
            }
            
            newTip.css({opacity:0, display:'block', top: real_top - 10, left: real_left }).stop().animate({top: real_top, opacity:1},200);
            $.AviaTooltip.openTTs.push(newTip);
            element.data('avia-created-tooltip', $.AviaTooltip.openTTs.length - 1);
            
        },
        
        hide_tooltip: function(e)
        {
            var element = $(e.currentTarget) , newTip, animateTo;
        
            if(this.options.event == 'click')
            {
                element = $(e.target);
            
                if(!element.is('.'+this.options['class']) && element.parents('.'+this.options['class']).length == 0)
                {
                    if(this.active.length) { newTip = this.active; this.active = false;}
                }
            }
            else
            {
                newTip = element.data('avia-created-tooltip');
                newTip = typeof newTip != 'undefined' ? $.AviaTooltip.openTTs[newTip] : false;
            }
        
            if(newTip)
            {
                animateTo = parseInt(newTip.css('top'),10) - 10;
                newTip.animate({top: animateTo, opacity:0},200, function()
                {
                    newTip.css({display:'none'}); 
                    
                });
            }
        },
        
        stop_instant_open: function(e)
        {
            this.open = false;
        }   
    }

        
    
})(jQuery);	 


