function ScnColumnMaker(h, i, f) {
    this.parentControl = h;
    var d = this;
    this.width = 250;
    this.maxColumns = i;
    this.buttonsControl = this.textControl = this.selectControl = null;
    this.init = function () {
        this.buildSelectControl();
        this.buildColumnButtons(0);
        this.buildTextControl()
    };
    this.getTotalColumns = function () {
        return Number(d.selectControl.find("option:selected").val())
    };
    this.buildSelectControl = function () {
        this.selectControl = jQuery("<select></select>").attr("id", "scn-column-select").attr("style", "width:" + this.width + "px").addClass(f ? f : "");
        var a = jQuery("<option></option>").attr("value", "select").attr("selected", "selected").text("Number of columns...");
        a.appendTo(this.selectControl);
        for (var b = 2; b <= this.maxColumns; b++) {
            a = jQuery("<option></option>").attr("value", b).text(b + " columns");
            a.appendTo(this.selectControl)
        }
        this.selectControl.change(function (c) {
            (c = d.getTotalColumns()) && d.buildColumnButtons(c)
        });
        this.parentControl.append(this.selectControl)
    };
    this.buildTextControl = function () {
        var a = jQuery("<div>").attr("style", "position: relative;margin-top: 5px; width: " + this.width + "px;");
        a.appendTo(this.parentControl);
        this.textControl = jQuery("<div>&nbsp;</div>").attr("id", "scn-column-text").attr("style", "width: " + (this.width - 50) + "px");
        a.append(this.textControl);
        var b = jQuery("<input>").attr("type", "button").attr("style", "width: 40px;position:absolute;right: 0px;bottom: -2px;font-size: 22px; border:none;background:none;").attr("value", "\u232b");
        a.append(b);
        b.click(function () {
            d.deleteColumnButtonClicked()
        })
    };
    this.buildColumnButtons = function (a) {
        if (this.buttonsControl) {
            this.buttonsControl.html("");
            this.textControl.html("&nbsp;")
        } else {
            this.buttonsControl = jQuery("<div></div>").attr("id", "scn-column-buttons");
            this.parentControl.append(this.buttonsControl);
            jQuery('<div style="clear:both"></div>').appendTo(this.parentControl)
        }
        for (var b = 1; b < a; b++) {
            var c = jQuery("<input>").attr("type", "button").attr("value", b + "/" + a).attr("name", b).attr("style", "width:" + Math.floor(this.width * (b / a)) + "px").addClass("column-button").addClass("rounded5p");
            c.click(function (e) {
                d.columnButtonClicked(e)
            });
            this.buttonsControl.append(c)
        }
    };
    this.deleteColumnButtonClicked = function () {
        var a = jQuery.trim(this.textControl.text()),
            b = a.lastIndexOf("|");
        a = b != -1 ? jQuery.trim(a.substring(0, b)) : "&nbsp;";
        this.textControl.html(a);
        this.updateColumnButtonsState()
    };
    this.columnButtonClicked = function (a) {
        var b = Number(a.target.name);
        if (b) {
            a = "";
            for (var c = 0; c < b; c++) a += "x";
            b = jQuery.trim(this.textControl.text());
            if (b.length > 0) a = " | " + a;
            this.textControl.text(b + a);
            this.updateColumnButtonsState()
        }
    };
    this.updateColumnButtonsState = function () {
        var a = this.getTotalColumns();
        if (a) {
            var b = this.countCurrentColumns(),
                c = a - b;
            this.buttonsControl.find("input").each(function (e, g) {
                e >= c ? jQuery(g).attr("disabled", "disabled") : jQuery(g).removeAttr("disabled")
            })
        }
    };
    this.countCurrentColumns = function () {
        for (var a = this.textControl.text(), b = 0, c = 0; c < a.length; c++) a.charAt(c) == "x" && b++;
        return b
    };
    this.init()
};