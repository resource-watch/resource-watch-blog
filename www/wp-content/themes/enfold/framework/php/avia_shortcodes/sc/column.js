scnShortcodeMeta = {
    attributes: [{
        label: "Columns",
        id: "content",
        controlType: "column-control"
    }],
    disablePreview: true,
    customMakeShortcode: function (b) {
        var a = b.data;
        if (!a) return "";
        b = a.numColumns;
        var c = a.content;
        a = ["0", "one", "two", "three", "four", "five"];
        var x = ["0", "0", "half", "third", "fourth", "fifth"];
        var f = x[b];
        //f += "col_";
        c = c.split("|");
        var g = "";
        for (var h in c) {
            var d = jQuery.trim(c[h]);
            if (d.length > 0) {
                var e = a[d.length] +'_'+f ;
                if (b == 4 && d.length == 2) e = "one_half";
                
                var z = e;
                if (h == 0) e += " first";
                g += "[" + e + "]Content for " + d.length + "/" + b + " Column here[/" + z + "] <br/><br/>"
            }
        }
        return g
    }
};