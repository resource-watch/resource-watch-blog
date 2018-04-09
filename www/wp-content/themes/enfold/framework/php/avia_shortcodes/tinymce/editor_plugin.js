(function () {
    tinymce.create("tinymce.plugins.ShortcodeNinjaPlugin", {
        init: function (editor, e) {
        	
        	
        	var _self = this;
        
        	editor.addButton( 'scn_button', {
        		type: 'menubutton',
        		text: "",
                title : "Insert Theme Shortcode",
                icons : 'scn_button',
            	menu: _self.createMenuValues(editor)
           });
        	
        
            editor.addCommand("scnOpenDialog", function (a, c) {
            
                scnSelectedShortcodeType = c.identifier;
                jQuery.get(e + "/dialog.php", function (b) {
                    jQuery("#scn-dialog").remove();
                    jQuery("body").append(b);
                    jQuery("#scn-dialog").hide();
                    var f = jQuery(window).width();
                    b = jQuery(window).height();
                    f = 720 < f ? 720 : f;
                    f -= 80;
                    b -= 84;
                    tb_show("Insert Shortcode", "#TB_inline?width=" + f + "&height=" + b + "&inlineId=scn-dialog");
                    jQuery("#scn-options h3:first").text("Customize the " + c.title + " Shortcode")
                })
            });
            
        },
        
        control_by_key: function(passed_key, final_options)
        {
        	var shortcodes = false, key;
        	if(avia_framework_globals && avia_framework_globals.shortcodes) shortcodes = avia_framework_globals.shortcodes;
        
	        if(shortcodes)
			{	
				for (key in shortcodes)
				{	
					if( passed_key == false && typeof shortcodes[key] == 'string')
					{
						final_options.push({text: shortcodes[key].charAt(0).toUpperCase() + shortcodes[key].slice(1) , shortcode: shortcodes[key].toLowerCase().replace(/ /,'_')});
						// a.addWithDialog(b, shortcodes[key].charAt(0).toUpperCase() + shortcodes[key].slice(1), shortcodes[key].toLowerCase().replace(/ /,'_'));
					} 
					else if(key == passed_key )
					{
						for (sub_key in shortcodes[key])
						{
							final_options.push({text: shortcodes[key][sub_key].charAt(0).toUpperCase() + shortcodes[key][sub_key].slice(1) , shortcode: sub_key});
							// a.addWithDialog(b, shortcodes[key][sub_key].charAt(0).toUpperCase() + shortcodes[key][sub_key].slice(1), sub_key);
						}	
					}
				}
            }
        },
        
        createMenuValues: function()
        {
        	var _self 			= this,
        		shortcodes		= false,
        		final_options 	= [], 
        		remove 			= {}
        	
        	if(avia_framework_globals && avia_framework_globals.shortcodes) 
            {
            	shortcodes = avia_framework_globals.shortcodes;
            	
            	if(typeof avia_framework_globals.shortcodes.remove != 'undefined')
            	{
                	remove = avia_framework_globals.shortcodes.remove;
            	}
            }
        	
        	
        	final_options.push({text: 'Button', shortcode: 'button'});
        	final_options.push({text: 'Icon link', shortcode: 'ilink'});
        	_self.control_by_key('inline', final_options);
        	
        	final_options.push({text: "Quote", shortcode: 'quote'});
            final_options.push({text: "Info Box", shortcode: 'box'});
            final_options.push({text: "Icon Box", shortcode: 'iconbox'});
            _self.control_by_key("small_box", final_options); 
        	
        	final_options.push({text: "Column Layout", shortcode:"column"});
			final_options.push({text: "Content Slider",shortcode: "slider"});
			final_options.push({text: "Toggles",shortcode: "toggle"});
			final_options.push({text: "Tabbed Content",shortcode: "tab"});
			_self.control_by_key( "content", final_options); 
        	
        	var dividers = {text: 'Dividers', menu: []};
        	dividers.menu.push({av_type: 'insert', text: "Horizontal Rule", shortcode: "<br>[hr] <br>"});
            dividers.menu.push({av_type: 'insert', text: "Horizontal Rule with top link", shortcode: "<br>[hr top] <br>"});
            dividers.menu.push({av_type: 'insert', text: "Whitespace", shortcode: "<br>[hr_invisible] <br>"});
        	final_options.push(dividers);
        	
        	var dropcaps = {text: 'Dropcaps', menu: []};
        	dropcaps.menu.push({av_type: 'insert', text: "Dropcap Style 1 (Big Letter)", shortcode: "[dropcap1]A[/dropcap1]"});
            dropcaps.menu.push({av_type: 'insert', text: "Dropcap Style 2 (Colored Background)", shortcode: "[dropcap2]A[/dropcap2]"});
            dropcaps.menu.push({av_type: 'insert', text: "Dropcap Style 3 (Dark Background)", shortcode: "[dropcap3]A[/dropcap3]"});
        	final_options.push(dropcaps);
        	
        	var widgets = {text: 'Widgets', menu: []};
            widgets.menu.push({text: "Latest Posts", shortcode: "latest_posts"});
            if(!shortcodes || (typeof remove.portfolio == 'undefined'))  widgets.menu.push({text: "Latest Portfolio entries", shortcode: "latest_portfolio"});
            _self.control_by_key( "widgets", widgets);
        	final_options.push(widgets);
        	
        	
        	_self.control_by_key( false, final_options);
        	
        	//add the onclick event programmaticaly
        	for(var key in final_options)
        	{
        		if(typeof final_options[key].menu != "undefined")
        		{
        			for(var subkey in final_options[key].menu)
		        	{	
		        		if(typeof final_options[key].menu[subkey].av_type != "undefined" && final_options[key].menu[subkey].av_type == "insert")
		        		{
		        			final_options[key].menu[subkey].onclick = _self.addImmediate;
		        		}
		        		else
		        		{
		        			final_options[key].menu[subkey].onclick = _self.addWithDialog;
		        		}
		        	}
        		}
        		else
        		{
        			if(typeof final_options[key].av_type != "undefined" && final_options[key].av_type == "insert")
	        		{
	        			final_options[key].onclick = _self.addImmediate;
	        		}
	        		else
	        		{
	        			final_options[key].onclick = _self.addWithDialog;
	        		}
        		}
        	}
        	
        	
        	return final_options;
        },
        
        addImmediate: function () {
           
            var shortcode = this.settings.shortcode;
            
        	tinyMCE.activeEditor.execCommand("mceInsertContent", false, shortcode);
             
        },
        addWithDialog: function () {
            
        	var shortcode 	= this.settings.shortcode,
        		title		= this.settings.text;
            
            tinyMCE.activeEditor.execCommand("scnOpenDialog", false, {
                title: title,
                identifier: shortcode
            });
              
        },
        getInfo: function () {
            return {
                longname: "Shortcode Ninja plugin",
                author: "VisualShortcodes.com (modified by Kriesi)",
                authorurl: "http://visualshortcodes.com",
                infourl: "http://visualshortcodes.com/shortcode-ninja",
                version: "1.0"
            }
        }
    });
    tinymce.PluginManager.add("ShortcodeNinjaPlugin", tinymce.plugins.ShortcodeNinjaPlugin)
})();