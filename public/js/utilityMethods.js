




/**
 * Shows an alert message.
 * 
 * Sometimes we get a php error embedded in our json reply and we don't see it unless we print to console or show an alert.
 * This method provides the convenience of disabling the alert message (in one place, for all json calls)
 * 
 * @param {string} httpResponseText The http response text (contains JSON reply) may contain php error messages that we want to display.
 * 
 */
function debug_json_alert(httpResponseText)
{
	// Comment this out when everything works.
	
	// alert(httpResponseText);
	
	console.log('DEBUG_JSON_ALERT: ' + httpResponseText);
}




/**
 * Used to strip away the unwanted garbage added by my hosting provider.
 * They insert wierd tracking tags in my json reply, so i have to filter it out here.
 * 
 * @param {string} responseText The http response text, eg the json reply.
 * 
 * @returns {string} the response text, with everything removed after the <!-- Hosting24 Analytics -->
 */
function remove_analytics_junk(responseText)
{
	var startOfJunk = responseText.indexOf("<!-- Hosting24");

	if(startOfJunk !== -1)
	{
		// strip out the junk added by my hosting provider.

		//alert('startOfJunk: ' + startOfJunk);

		//alert('CHOPPED: ' + http.responseText.substring(0, startOfJunk));

		var goodStuff = responseText.substring(0, startOfJunk);

		return goodStuff;
	}
	return responseText;
}







/**
 * Get an http object for an ajax request, via POST.
 * 
 * @param {string} url
 * 
 * @returns {http object} you must set the onreadystatechange function and call send()
 */
function open_http_ajax_post(url)
{
	var http = getXMLHttpRequest();

	http.open("POST", url, true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	return http;
}





/**
 *Safe method to get an xhr object in case our client uses Internet Explorer.
 */
function getXMLHttpRequest()
{
	if (window.XMLHttpRequest)
	{
		// Not using Internet Explorer

		return new window.XMLHttpRequest;
	}
	else
	{
		try
		{
			// Using Internet Explorer

			return new ActiveXObject("MSXML2.XMLHTTP.3.0");
		}
		catch (ex)
		{
			// Failed

			return null;
		}
	}
}





		/**
		 * Use javascript to submit an http POST message to the given url.
		 * This will direct the browser to a new page.
		 * 
		 * Usage:
		 * post_to_url('http://example.com/', {'name':'mike', 'email':'mike@gell.com'});
		 * 
		 * Hooray stack overflow!
		 * http://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
		 * 
		 * 
		 * @param {string} the target url
		 * @param {array of name:value pairs} params 
		 * @returns {void}
		 */
function post_to_url(url, params)
{
    var form = document.createElement("form");
	
    form.setAttribute("method", "post");
	
    form.setAttribute("action", url);

    for(var key in params)
	{
		// proceed if the params object has key
        if(params.hasOwnProperty(key))
		{
            var hiddenField = document.createElement("input");
			
			hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = params[key];
			
//			hiddenField.setAttribute("type", "hidden");
//			hiddenField.setAttribute("name", key);
//			hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
		}
    }

    document.body.appendChild(form);
	
    form.submit();
}








//
//
//
//function appendDebugStr(str)
//{
//	var existingStr = $("#debugConsole").html();
//
//	var result = existingStr.match(/<br ?>/gi);		// match break tags
//
//	var len = result.length;
//
//	if (len > 50)
//	{
//		$("#debugConsole").html
//				(
//						str.substring(len - 25, len)		// chop back to 25 lines
//						);
//	}
//
//	//			$("#debugConsole").html
//	//			(
//	//						existingStr
//	//						+
//	//						"<br />-----------------------<br />"
//	//						+
//	//						str
//	//			);
//
//	$("#debugConsole").html(str);
//}
//
//function getCookie(requestedName)
//{
//	var i;
//	var name;
//	var value;
//	var ARRcookies = document.cookie.split(";");
//
//	for (i = 0; i < ARRcookies.length; i++)
//	{
//		name = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
//
//		value = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
//
//		/*
//		 Javascript provides the 'g' modifier for regex, which means "perform a global match"
//		 
//		 (This means - find all matches, rather than stopping after the first match).
//		 
//		 Because we use the 'g' modifier, the following operation continues until
//		 
//		 all leading AND trailing whitespace has been removed.
//		 */
//
//		name = name.replace(/^\s+|\s+$/g, ""); // trim any leading OR trailing whitespace
//
//		if (name == requestedName)
//		{
//			return unescape(value);
//		}
//	}
//}
//
//
//
//function setCookie(c_name, value, daysToKeepCookie)
//{
//	var expiryDate = new Date();
//
//	expiryDate.setDate(expiryDate.getDate() + daysToKeepCookie);
//
//	var c_value = escape(value) + ((daysToKeepCookie == null) ? "" : "; expires=" + expiryDate.toUTCString());
//
//	document.cookie = c_name + "=" + c_value;
//}
//
//
//function cookieExpireAfter_Days()
//{
//	return 2;
//}