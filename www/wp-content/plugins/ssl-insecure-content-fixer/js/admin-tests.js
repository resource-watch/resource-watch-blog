/*!
SSL Insecure Content Fixer admin tests script
https://ssl.webaware.net.au/
*/

(function($) {

	$.ajax({
		url:		sslfix.ajax_url_ssl,
		data:		{ action: "sslfix-environment" },
		dataType:	"json",
		method:		"GET",
		xhrFields:	{ withCredentials: true },
		error:		showError,
		success:	showResults
	});

	/**
	* show test results
	* @param {Object} response
	*/
	function showResults(response) {
		if (response.ssl) {
			switch (response.detect) {

				case "HTTPS":
				case "port":
					showHidden("#sslfix-normal");
					break;

				case "HTTP_X_FORWARDED_PROTO":
					showHidden("#sslfix-HTTP_X_FORWARDED_PROTO");
					break;

				case "HTTP_X_FORWARDED_SSL":
					showHidden("#sslfix-HTTP_X_FORWARDED_SSL");
					break;

				case "HTTP_CLOUDFRONT_FORWARDED_PROTO":
					showHidden("#sslfix-HTTP_CLOUDFRONT_FORWARDED_PROTO");
					break;

				case "HTTP_X_ARR_SSL":
					showHidden("#sslfix-HTTP_X_ARR_SSL");
					break;

				case "HTTP_X_FORWARDED_SCHEME":
					showHidden("#sslfix-HTTP_X_FORWARDED_SCHEME");
					break;

				case "HTTP_CF_VISITOR":
					showHidden("#sslfix-HTTP_CF_VISITOR");
					break;

			}
		}
		else {
			showHidden("#sslfix-detect_fail");
		}

		hideVisible("#sslfix-loading");
		showHidden("#sslfix-test-result-head");
		showHidden("#sslfix-environment");
		$("#sslfix-environment pre").text(response.env);
	}

	/**
	* show test error
	* @param {Object} xhr
	* @param {String} status
	* @param {String} errmsg
	*/
	function showError(xhr, status, errmsg) {
		hideVisible("#sslfix-loading");
		showHidden("#sslfix-test-result-head");
		showHidden("#sslfix-environment");
		$("#sslfix-environment pre").text(status + "\n" + errmsg);
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

	/**
	* show hidden element, with accessibility cues
	* @param {String} selector
	*/
	function showHidden(selector) {
		$(selector).attr("aria-hidden", "false").show();
	}

	/**
	* hide visible element, with accessibility cues
	* @param {String} selector
	*/
	function hideVisible(selector) {
		$(selector).attr("aria-hidden", "true").hide();
	}

})(jQuery);
