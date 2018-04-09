jQuery(document).ready(function ($) {

	// If force inline SVG option is active then add class
	if ( ForceInlineSVGActive === 'true' ) {

		// Find all SVG inside img and add class if it hasn't got it
		jQuery('img').each(function() {

			// Pick only those with the extension we want
			if ( jQuery(this).attr('src').match(/\.(svg)/) ) {

				// Add our class name
				if ( !jQuery(this).hasClass(cssTarget.ForceInlineSVG) ) {
					jQuery(this).addClass(cssTarget.ForceInlineSVG);
				}
			}
		});
	}

	// Polyfill to support all ye old browsers
	// delete when not needed in the future
	if (!String.prototype.endsWith) {
		String.prototype.endsWith = function(searchString, position) {
			var subjectString = this.toString();
			if (typeof position !== 'number' || !isFinite(position) || Math.floor(position) !== position || position > subjectString.length) {
				position = subjectString.length;
			}
			position -= searchString.length;
			var lastIndex = subjectString.lastIndexOf(searchString, position);
			return lastIndex !== -1 && lastIndex === position;
		};
	} // end polyfill

	// Another snippet to support IE11
	String.prototype.endsWith = function(pattern) {
		var d = this.length - pattern.length;
		return d >= 0 && this.lastIndexOf(pattern) === d;
	};
	// End snippet to support IE11

	// Check to see if user set alternate class
	if ( ForceInlineSVGActive === 'true' ) {
		var target  = ( cssTarget.Bodhi !== 'img.' ? cssTarget.Bodhi : 'img.style-svg' );
	} else {
		var target  = ( cssTarget !== 'img.' ? cssTarget : 'img.style-svg' );
	}

	$(target).each(function(index){
		var $img = jQuery(this);
		var imgID = $img.attr('id');
		var imgClass = $img.attr('class');
		var imgURL = $img.attr('src');

		// Set svg size to the original img size
		// var imgWidth = $img.attr('width');
		// var imgHeight = $img.attr('height');

		if (!imgURL.endsWith('svg')) {
			return;
		}

		$.get(imgURL, function(data) {

			// Get the SVG tag, ignore the rest
			var $svg = $(data).find('svg');

			var svgID = $svg.attr('id');

			// Add replaced image's ID to the new SVG if necessary
			if(typeof imgID === 'undefined') {
			    if(typeof svgID === 'undefined') {
					imgID = 'svg-replaced-'+index;
					$svg = $svg.attr('id', imgID);
				} else {
					imgID = svgID;
				}
			} else {
				$svg = $svg.attr('id', imgID);
			}

			// Add replaced image's classes to the new SVG
			if(typeof imgClass !== 'undefined') {
			    $svg = $svg.attr('class', imgClass+' replaced-svg svg-replaced-'+index);
			}

			// Remove any invalid XML tags as per http://validator.w3.org
			$svg = $svg.removeAttr('xmlns:a');

			// Add size attributes
			// $svg = $svg.attr('width', imgWidth);
			// $svg = $svg.attr('height', imgHeight);

			// Replace image with new SVG
			$img.replaceWith($svg);

			$(document).trigger('svg.loaded', [imgID]);

		}, 'xml');
	});

});
