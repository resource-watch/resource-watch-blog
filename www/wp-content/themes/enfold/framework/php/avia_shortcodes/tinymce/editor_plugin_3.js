(function () {
    tinymce.create("tinymce.plugins.ShortcodeNinjaPlugin", {
        init: function (d, e) {
            d.addCommand("scnOpenDialog", function (a, c) {
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
            d.onNodeChange.add(function (a, c) {
                c.setDisabled("scn_button", a.selection.getContent().length > 0)
            })
        },
        
        control_by_key: function(passed_key, a, b)
        {
        	var shortcodes = false, key;
        	if(avia_framework_globals && avia_framework_globals.shortcodes) shortcodes = avia_framework_globals.shortcodes;
        
	        if(shortcodes)
			{	
				for (key in shortcodes)
				{	
					if( passed_key == false && typeof shortcodes[key] == 'string')
					{
						a.addWithDialog(b, shortcodes[key].charAt(0).toUpperCase() + shortcodes[key].slice(1), shortcodes[key].toLowerCase().replace(/ /,'_'));
					} 
					else if(key == passed_key )
					{
						for (sub_key in shortcodes[key])
						{
							a.addWithDialog(b, shortcodes[key][sub_key].charAt(0).toUpperCase() + shortcodes[key][sub_key].slice(1), sub_key);
						}	
					}
				}
            }
        },
        
        createControl: function (d, e) {
            if (d == "scn_button") {
                d = e.createMenuButton("scn_button", {
                    title: "Insert Shortcode",
                    image: avia_framework_globals.frameworkUrl + "php/avia_shortcodes/tinymce/img/icon.png",
                    icons: false
                });
                var a = this;
                var shortcodes = false, key, remove = {};
                if(avia_framework_globals && avia_framework_globals.shortcodes) 
                {
                	shortcodes = avia_framework_globals.shortcodes;
                	
                	if(typeof avia_framework_globals.shortcodes.remove != 'undefined')
                	{
	                	remove = avia_framework_globals.shortcodes.remove;
                	}
                }
                
                d.onRenderMenu.add(function (c, b) {
                    a.addWithDialog(b, "Button", "button");
                    a.addWithDialog(b, "Icon link", "ilink");
                    a.control_by_key(  "inline", a , b); 
                    
                    b.addSeparator();
                    a.addWithDialog(b, "Quote", "quote");
                    a.addWithDialog(b, "Info Box", "box");
                    a.addWithDialog(b, "Icon Box", "iconbox");
                    a.control_by_key(  "small_box", a , b); 
                    
                  	//a.addWithDialog(b, "Related Posts", "related");
                    b.addSeparator();
                    a.addWithDialog(b, "Column Layout", "column");
					a.addWithDialog(b, "Content Slider","slider");
					a.addWithDialog(b, "Toggles","toggle");
					a.addWithDialog(b, "Tabbed Content","tab");
					a.control_by_key(  "content", a , b); 
					
					//a.addWithDialog(b, "Tab Layout","tab");
                    b.addSeparator();
                    c = b.addMenu({
                        title: "Dividers"
                    });
                    a.addImmediate(c, "Horizontal Rule", "<br>[hr] <br>");
                    a.addImmediate(c, "Horizontal Rule with top link", "<br>[hr top] <br>");
                    a.addImmediate(c, "Whitespace", "<br>[hr_invisible] <br>");
                    
                    c = b.addMenu({
                        title: "Dropcaps"
                    });
                    a.addImmediate(c, "Dropcap Style 1 (Big Letter)", "[dropcap1]A[/dropcap1]");
                    a.addImmediate(c, "Dropcap Style 2 (Colored Background)", "[dropcap2]A[/dropcap2]");
                    a.addImmediate(c, "Dropcap Style 3 (Dark Background)", "[dropcap3]A[/dropcap3]");
                    b.addSeparator();
                    c = b.addMenu({
                        title: "Widgets"
                    });
					a.addWithDialog(c, "Latest Posts",	"latest_posts");
					if(!shortcodes || (typeof remove.portfolio == 'undefined')) a.addWithDialog(c, "Latest Portfolio entries",	"latest_portfolio");
					a.control_by_key(  "widget", a , c); 
										
					b.addSeparator();
					a.control_by_key(  false, a , b); 
                    
                    
                 
                    
                    /*
                    c = b.addMenu({
                        title: "Social Buttons"
                    });
                    a.addWithDialog(c, "Twitter", "twitter");
                    a.addWithDialog(c, "Tweetmeme", "tweetmeme");
                    a.addWithDialog(c, "Digg", "digg");
                    a.addWithDialog(c, "Like on Facebook", "fblike");
                    */
      
                });
                return d
            }
            return null
        },
        addImmediate: function (d, e, a) {
            d.add({
                title: e,
                onclick: function () {
                    tinyMCE.activeEditor.execCommand("mceInsertContent", false, a)
                }
            })
        },
        addWithDialog: function (d, e, a) {
            d.add({
                title: e,
                onclick: function () {
                    tinyMCE.activeEditor.execCommand("scnOpenDialog", false, {
                        title: e,
                        identifier: a
                    })
                }
            })
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