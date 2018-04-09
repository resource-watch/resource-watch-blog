scnShortcodeMeta={
	attributes:[
		{
			label:"Username",
			id:"username",
			help: 'Enter the twitter username.',
			isRequired:true
		},
		{
			label:"Title",
			id:"title",
			help: 'Enter the title that should be displayed above the widget'
		},
		{
			label:"How many entries",
			id:"count",
			help:"How many entries do you want to display?", 
			controlType:"select-control", 
			selectValues:['1', '2', '3', '4', '5', '6', '7', '8','9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '30', '40', '50', '100'],
			defaultValue: '3', 
			defaultText: '3'
		},
		{
			label:"Exclude @replies?",
			id:"exclude_replies",
			controlType:"select-control", 
			selectValues:['yes', 'no'],
			defaultValue: 'yes', 
			defaultText: 'yes'
		},
		{
			label:"Display Time of tweet?",
			id:"time",
			controlType:"select-control", 
			selectValues:['yes', 'no'],
			defaultValue: 'yes', 
			defaultText: 'yes'
		},
		{
			label:"Display Avatar of User?",
			id:"display_image",
			controlType:"select-control", 
			selectValues:['yes', 'no'],
			defaultValue: 'yes', 
			defaultText: 'yes'
		},

		],
		disablePreview: true,
		defaultContent:"Kriesi",
		shortcode:"widget",
		customMakeShortcode: function (a) {
        
        var f = 'widget widget_name="avia_tweetbox" widget_class_name="tweetbox" ';
        
        for (var d in a) 
        {
            var g = a[d];
            if (g) {f += " " + d + '="' + g + '"'};
        }
        
       
        return "[" + f + "]";
    }};
