scnShortcodeMeta = {
    attributes: [{
        label: "Table Generator",
        id: "content",
        controlType: "table-control"
    }],
    disablePreview: true,
    customMakeShortcode: function (dataset) {
        var data = dataset.data,
        	table = data.table,
        	html = data.html;
       
       var shortcode = '[avia_table]' + html + '[/avia_table]';
       
       
       return shortcode;
       
       
    }
};