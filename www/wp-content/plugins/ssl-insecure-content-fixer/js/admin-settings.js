/*!
SSL Insecure Content Fixer admin settings script
https://ssl.webaware.net.au/
*/

(function($) {

	$.ajax({
		url:		sslfix.ajax_url_ssl,
		data:		{ action: "sslfix-get-recommended" },
		dataType:	"json",
		method:		"GET",
		xhrFields:	{ withCredentials: true },
		success:	showRecommended
	});

	/**
	* show recommended settings
	* @param {Object} response
	*/
	function showRecommended(response) {
		if (response.recommended) {
			var label = $("label[for=" + response.recommended_element + "]");
			label.addClass("sslfix-recommended");
			label.html(label.html() + "<br/><span>" + sslfix.msg.recommended + "</span>");
		}
	}

	$.ajax({
		url:		sslfix.ajax_url_wp,
		data:		{ action: "sslfix-test-https" },
		dataType:	"json",
		method:		"GET",
		xhrFields:	{ withCredentials: true },
		success:	showHttpsDetected
	});

	/**
	* show whether HTTPS was detected correctly within WordPress
	* @param {Object} response
	*/
	function showHttpsDetected(response) {
		if (response.https) {
			$("#sslfix-https-detection").addClass("dashicons dashicons-" + response.https);
		}
	}

})(jQuery);
