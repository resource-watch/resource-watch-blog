function scnSBTabMaker(h, i, f) {

    this.parentControl = h;
    var d = this;
    this.width = 250;
    this.maxTabs = i;
    this.buttonsControl = this.textControl = this.selectControl = null;
    this.init = function () {
    
        this.buildSelectControl();
        
    };
    
    this.getTotalTabs = function () {
        return Number(d.selectControl.find("option:selected").val())
    };
    
    this.buildSelectControl = function () {
    	// .attr("style", "width:" + this.width + "px")
        this.selectControl = jQuery("<select></select>").attr("id", "scn-tab-select").addClass(f ? f : "");
        var a = jQuery("<option></option>").attr("value", "select").attr("selected", "selected").text("Choose a number...");
        a.appendTo(this.selectControl);
        for (var b = 2; b <= this.maxTabs; b++) {
            a = jQuery("<option></option>").attr("value", b).text(b);
            a.appendTo(this.selectControl)
        }
        this.selectControl.change(function (c) {
            (c = d.getTotalTabs()) && d.buildTabButtons(c)
                        
            // Update the text in the appropriate span tag.
            var newText = jQuery(this).children('option:selected').text();
            
            jQuery(this).parents('.select_wrapper').find('span').text( newText );
            
            // jQuery( this ).parents( 'tr' ).hide();
        });
        
        this.parentControl.append(this.selectControl);
    };
    
    this.buildTextInputControl = function ( id ) {
    
    	var labelElement = '<label for="scn_tab_title">Title #' + id + '</label>';
    	var inputElement = '<input type="text" id="scn_tab_title_' + id.toString() + '" class="txt input-text" name="scn_tab_title" />';
    
        this.textInputControl = jQuery('<tr><th>' + labelElement + '</th><td>' + inputElement + '</td></tr>');
        this.parentControl.parents('tbody').append(this.textInputControl)
    };
    
    this.buildIconInputControl = function ( id ) {
    
    	var labelElement = '<label for="scn_tab_icon">Icon #' + id + '</label>';
    	var inputElement = '<select id="scn_tab_icon_' + id.toString() + '"name="scn_tab_icon" >'+this.iconselect+'</select>';
    
        this.textInputControl = jQuery('<tr><th>' + labelElement + '</th><td>' + inputElement + '</td></tr>');
        this.parentControl.parents('tbody').append(this.textInputControl)
    };
    
    this.selectvalues = function()
    {
    	this.iconselect = "<option value =''>No Icon</option>";
    	var x;
    	for (x in avia_framework_globals['iconbox_icons'])
    	{
		    this.iconselect += "<option value='" + avia_framework_globals['iconbox_icons'][x] + "'>" +avia_framework_globals['iconbox_icons'][x] + "</option>";
		}
    };
    
    this.buildTabButtons = function (a) {
        if (this.buttonsControl) {
            this.buttonsControl.html("");

        } else {

			// Wipe the slate clean when the number of tabs desired changes.
			jQuery('label[for="scn_tab_title"], label[for="scn_tab_icon"]').parents('tr').remove();
            this.parentControl.append(this.buttonsControl);

        }
                
         this.selectvalues();
        
        for (var b = 1; b <= a; b++) {
        
        	this.buildTextInputControl( b );
        	this.buildIconInputControl( b );
        }
    };
    
    this.updateTabButtonsState = function () {
        var a = this.getTotalTabs();
        if (a) {
            var b = this.countCurrentTabs(),
                c = a - b;
            this.buttonsControl.find("input").each(function (e, g) {
                e >= c ? jQuery(g).attr("disabled", "disabled") : jQuery(g).removeAttr("disabled")
            })
        }
    };
    
    this.countCurrentTabs = function () {
        for (var a = this.textControl.text(), b = 0, c = 0; c < a.length; c++) a.charAt(c) == "x" && b++;
        return b
    };
    
    this.init()
};