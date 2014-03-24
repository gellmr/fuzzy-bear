

/**
 * Event handlers for the customer rego page.
 */




var uname_min_length = 0;

var phone_min_length = 8;





$( document ).ready(function(){
    
	var date = new Date;
	
	var seconds = date.getSeconds();
	var minutes = date.getMinutes();
	var hour = date.getHours();

	console.log("Customer Rego Page loaded. Time of Day == " + hour + ':' + minutes + ' and ' + seconds + " seconds");
	
//	$('#input_fname').keypress(function(e) {
//		if (e.which == 13) {
//			
//			$(this).blur();
//		}
//		else
//		{
//			console.log(e.which);
//		}
//	});

	$('#input_fname').keypress(captureEnterKeypress);
	
	$('#input_lname').keypress(captureEnterKeypress);
	
	$('#input_uname').keypress(captureEnterKeypress);
	
	$('#input_emailAddr').keypress(captureEnterKeypress);
	
	
//	$('#input_homePhone').keypress(checkPhoneNumber);
//	
//	$('#input_workPhone').keypress(checkPhoneNumber);
//	
//	$('#input_mobPhone').keypress(checkPhoneNumber);
	
	
	$('#input_homePhone').bind('change keyup', checkPhoneNumber);
	
	$('#input_workPhone').bind('change keyup', checkPhoneNumber);
	
	$('#input_mobPhone').bind('change keyup', checkPhoneNumber);
	
	$('#input_password').bind('change keyup', validatePassword);
	
	
	
});



/**
 * Let the user know if their password is too short, has the wrong characters, or equals the example.
 * 
 * @returns {undefined}
 */
function validatePassword()
{
	// Does the current password equal the example password?
	
	var thisVal = String($('#input_password').val());
	
	var exampleVal = $.trim(String($('#examplePw').text()));
	
	if ((thisVal) == (exampleVal))
	{
		// Matches the example password.
		
		$('#examplePw_report').text("Sorry, you can't use our example password.");
		
		$('#examplePw_report').attr('class', 'ajax_report_div friendly_ErrorText small_Text');
	}
	else
	{
		// Bad idea - show the user what password they have typed.
		
		//$('#examplePw_report').attr('class', 'ajax_report_div small_Text');

		//$('#examplePw_report').text(thisVal + ' ' + exampleVal);
		
		// To do - describe how good the password is, and whether it meets our criteria. Also enforce the stuff on server side.
		
		$('#examplePw_report').text('');
	}
	
	//alert("You entered " + $(this).val() + " ...can't use " + $('#examplePw').text());
}





/**
 * Capture the ENTER keypress event and trigger $(this).blur()
 * 
 * @param {keypress event} e
 * @returns {undefined}
 */
function captureEnterKeypress(e)
{
	if (e.which == 13)
	{
		$(this).blur();
	}
	else
	{
		//console.log(e.which);
	}
};



function checkPhoneNumber(e)
{
	if (e.which == 13)
	{
		$(this).blur();
		
		return false;
	}
	
	if (typeof $(this) === "undefined")
	{
		return false;
	}
	
	// Allow ASCII code for NUMBERS, SPACE and the MINUS/ADDITION symbols.
	
	
	var id = $(this).attr('id');
	
//	if (typeof id === "undefined")
//	{
//		return false;
//	}
	
	var phoneStr = $('#' + id).val();
	
	var errCss = 'friendly_ErrorText';
	
	var isok = 'invalid';
	
	
	// The phone number may lead and trail with spaces.
	// The phone number may have a + or - at the start.
	// The phone number can contain spaces.
	// The phone number must have at least one numeric digit.
	// The phone number can have any combination of digits and spaces.
	// The phone number cannot have alphabetical characters.
	
	var re_numeric_spacesOK = /^\s*[+-]?\s*[0-9|\s]{8,20}\s*$/; // true if the string is a number with optional spaces.

	// The phone number cannot be just zeroes.
	
	var re_allZeroes = /^0+$/i; // true if the phone number is just zeroes. (i) = ignore case
		
	if( ! re_allZeroes.test( phoneStr ) && re_numeric_spacesOK.test( phoneStr ))
	{
		isok = 'VALID';
		
		errCss = 'green_available';
	}
	
	var n = Number(e.which);
	
	var errField = $('#errMsg_' + id);

	errField.html(isok);
	
	var classAttrs = 'ajax_report_div '+errCss+' small_Text';
	
	errField.attr('class', errCss);

	if
	(
		((n >= 48) && (n <= 57)) // 0..9
		||
		(n == 32) // space
		||
		(n == 43) // '+'
		||
		(n == 45) // '-'
	)
	{
		console.log(n + ' id: ' + id + ' str:' + phoneStr + ' ok:' + isok);

		if ($(this).val().length >= phone_min_length - 1)
		{
			$('#errLink_' + $(this).attr('id')).remove();
		}

		$('#please_give_phone').remove();

		$('#errLink_input_phones').remove();
	}
	else
	{
		console.log(n + ' blocked    id: ' + id + ' ' + phoneStr + ' ' + isok);
		
		return false;
	}
}





/**
 * NEED TO TRY AND CAPTURE THIS BEFORE document.keypress does.
 * IF SUCCESSFUL, GENERALISE and apply to other elements which should trigger their own blur() on ENTER keypress.
 * 
 * Capture keypress: ENTER key.
 
$("#inputField_uname").keypress(function(e){
		if (e.keyCode == 13)
		{
			console.log("(#inputField_uname.keypress) captured ENTER keypress event");
			
			this.blur();
			
			return false; // terminate the event.
		}
	}
);
*/




/**
 * NEED TO TRY AND CAPTURE THIS BEFORE document.keypress does.
 * IF SUCCESSFUL, GENERALISE and apply to other elements which should trigger their own blur() on ENTER keypress.
 * 
 * Capture keypress: ENTER key.
 
$("#input_emailAddr").keypress(function(e){
		if (e.keyCode == 13)
		{
			console.log("(#input_emailAddr.keypress) captured ENTER keypress event");
			
			this.blur();
			
			return false; // terminate the event.
		}
	}
);
*/







function focusField(fieldName)
{
	// alert("focusField   " + fieldName);
	
	$('#' + fieldName).focus();
	
}








/**
 * Failed validation of username.
 * 
 * @param string reason
 * @returns void
 */
function fail_userName(reason)
{
	if (reason == "unavail")
	{
		$("#avail_uname_report").html("<p>This name is NOT available.</p>");
	}
	else
	{
		$("#avail_uname_report").html("<p>Must be at least " + uname_min_length + " characters.</p>");
	}
	
	//$("#avail_uname_report").attr('class', 'rego_fields ajax_report_div red_unavail');
	
	$("#avail_uname_report").attr('class', 'ajax_report_div friendly_ErrorText');
}






/**
 * Called when we need to check the availability of a user name.
 */
function checkUserNameAvail(minLength)
{
	uname_min_length = minLength;
	
	var uname = document.getElementById('input_uname').value;
	
	if (uname.length < uname_min_length)
	{
		fail_userName();
		
		return false;
	}
	
	var params = "uname=" + uname;
	
	//var http = open_http_ajax_post("http://localhost/CI_fuzzyBear/index.php/register/checkUnameAvail");

	var http = open_http_ajax_post("http://shielded-spire.comoj.com/index.php/register/checkUnameAvail");

	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			var $showDebugInfo = true;
			
			
			
			if ($showDebugInfo)
			{
				var responseLength = http.responseText.length;
				
				if (responseLength > 300)
				{
					$("#debugPanel").html
					(
						"<p>response length: " + responseLength + "</p>" + http.responseText + "<br /><br />"
					);
				}
				else
				{
					debug_json_alert(http.responseText);
				}
			}
			
			var jsonObj = jQuery.parseJSON(remove_analytics_junk(http.responseText)); // strip out the junk added by my hosting provider.

			if ($showDebugInfo)
			{
				$("#debugPanel").html(jsonObj.debugString);
			}
			
			if (jsonObj.result == "ok")
			{
				$("#avail_uname_report").html("<p>This name is available.</p>");
				
				//$("#avail_uname_report").attr('class', 'rego_fields ajax_report_div green_available');
				
				$("#avail_uname_report").attr('class', 'ajax_report_div green_available');
				
				$("#errLink_input_uname").remove();
			}
			else
			{
				fail_userName("unavail");
			}
		}
	}
	
	http.send(params);
}



















/**
 * Check if the fname or lname field has a string that's long enough.
 * 
 * @param {form field} field
 * @returns {void}
 */
function checkNameString(field)
{
	var fieldName = field.name;
	
	var fieldVal = $('#' + field.id).val();
	
	// alert("fieldName: " + fieldName + " value: " + fieldVal);
	
	var params = "name=" + fieldVal;
	
	//var http = open_http_ajax_post("http://localhost/CI_fuzzyBear/index.php/register/check_nameString_Ok");
	
	var http = open_http_ajax_post("http://shielded-spire.comoj.com/index.php/register/check_nameString_Ok");
	
	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			var $showDebugInfo = true;
			
			if ($showDebugInfo)
			{
				var responseLength = http.responseText.length;
				
				if (responseLength > 300)
				{
					$("#debugPanel").html
					(
						"<p>response length: " + responseLength + "</p>" + http.responseText + "<br /><br />"
					);
				}
				else
				{
					debug_json_alert(http.responseText);
				}
			}
			
			var jsonObj = jQuery.parseJSON(remove_analytics_junk(http.responseText)); // strip out the junk added by my hosting provider.
			
			if ($showDebugInfo)
			{
				$("#debugPanel").html(jsonObj.debugString);
			}
			
			
			if (jsonObj.result == "ok")
			{
				// name is ok.
				
				var someHtml = 'Name is valid';
				someHtml = "<p>" + someHtml + "</p>";
				
				//var classAttrs = 'rego_fields ajax_report_div green_available small_Text';
				
				var classAttrs = 'ajax_report_div green_available small_Text';
				
				if (fieldName == 'input_fname')
				{
					// first name
					$("#fname_is_ok_report").html(someHtml);
					$("#fname_is_ok_report").attr('class', classAttrs);
					$("#errLink_input_fname").remove();
				}
				else
				{
					// last name
					$("#lname_is_ok_report").html(someHtml);
					$("#lname_is_ok_report").attr('class', classAttrs);
					$("#errLink_input_lname").remove();
				}
			}
			else
			{
				// names are of improper length.
				
				var someHtml = 'Name must be at least ' + jsonObj.min + ' character(s) long, but less than ' + jsonObj.max;
				someHtml = "<p>" + someHtml + "</p>";
				
				//var classAttrs = 'rego_fields ajax_report_div red_unavail small_Text';
				
				var classAttrs = 'ajax_report_div friendly_ErrorText small_Text';
				
				if (fieldName == 'input_fname')
				{
					$("#fname_is_ok_report").html(someHtml);
					$("#fname_is_ok_report").attr('class',classAttrs);
				}
				else
				{
					$("#lname_is_ok_report").html(someHtml);
					$("#lname_is_ok_report").attr('class',classAttrs);
				}
			}
		}
	}
	http.send(params);
}










/**
 * Check if the given email address field contains a value that is already taken.
 * 
 * @param {form field} field
 * @returns {void}
 */
function checkEmailAvail(field)
{
	var params = "email=" + field.value;
	
	// var http = open_http_ajax_post("http://localhost/CI_fuzzyBear/index.php/register/checkEmailAvail");

	var http = open_http_ajax_post("http://shielded-spire.comoj.com/index.php/register/checkEmailAvail");
	
	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			var $showDebugInfo = true;
			
			if ($showDebugInfo)
			{
				var responseLength = http.responseText.length;
				
				if (responseLength > 300)
				{
					$("#debugPanel").html
					(
						"<p>response length: " + responseLength + "</p>" + http.responseText + "<br /><br />"
					);
				}
				else
				{
					debug_json_alert(http.responseText);
				}
			}
			
			var jsonObj = jQuery.parseJSON(remove_analytics_junk(http.responseText)); // strip out the junk added by my hosting provider.

			if ($showDebugInfo)
			{
				$("#debugPanel").html(jsonObj.debugString);
			}
			
			if (jsonObj.result == "ok")
			{
				$("#avail_email_report").html("<p>This email address is fine.</p>");
				$("#avail_email_report").attr('class','ajax_report_div green_available');
				
				$("#errLink_input_emailAddr").remove();
			}
			else if(jsonObj.result == "invalidEmail")
			{
				$("#avail_email_report").html("<p>Please provide a valid email address.</p>");
				$("#avail_email_report").attr('class','ajax_report_div friendly_ErrorText');
			}
			else
			{
				$("#avail_email_report").html("<p>This email is already associated with an existing account.</p>");
				$("#avail_email_report").attr('class','ajax_report_div friendly_ErrorText');
			}
		}
	}
	http.send(params);
}





