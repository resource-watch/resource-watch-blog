scnShortcodeMeta={
	attributes:[
		{
			label:"Slides",
			id:"content",
			controlType:"tab-control"
		},
		{
		label:"Autorotation",
		id:"rotation",
		help:"Let the slides change automatically after X seconds. X is the number you choose here", 
		controlType:"select-control", 
		selectValues:['', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '30', '40', '30', '60'],
		defaultValue: '', 
		defaultText: 'no autorotation'
    }
		],
		disablePreview:true,
		customMakeShortcode: function(b){
				
			var a=b.data;
			var tabTitles = new Array();
			
			if(!a)return"";
			
			var c=a.content;
			var r= ' '+b.rotation;
			
			var g = ''; // The shortcode.
			
			for ( var i = 0; i < a.numTabs; i++ ) {
			
				var currentField = 'tle_' + ( i + 1 );

				if ( b[currentField] == '' ) {
				
					tabTitles.push( 'Slide ' + ( i + 1 ) );
				
				} else {
				
					var currentTitle = b[currentField];
					
					currentTitle = currentTitle.replace( /"/gi, "'" );
					
					tabTitles.push( currentTitle );
				
				} // End IF Statement
			
			} // End FOR Loop
			
			g += '[slideshow'+r+']<br/><br/>';
			
			for ( var t in tabTitles ) {
			
				g += '[slide title="' + tabTitles[t] + '"]' + tabTitles[t] + ' content goes here.[/slide] <br/><br/>';
			
			} // End FOR Loop

			g += '[/slideshow]';

			return g
		
		}
};