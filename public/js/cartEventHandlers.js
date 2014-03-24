

/**
 * Event handlers for the cart.
 */


/**
 * CART
 * 
 * Called when the user clicks one of the "Remove from Cart" buttons.
 */
function removeCartItem(field)
{
	//var url = "http://localhost/CI_fuzzyBear/index.php/cart/removeItemById";

	var url = "http://shielded-spire.comoj.com/index.php/cart/removeItemById";
	
	var params = "productID=" + field.id; // TEST THIS FOR VULNERABILITIES! Need CI to sanitize this before processing.

	var http = getXMLHttpRequest();

	http.open("POST", url, true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			var $showDebugInfo = false;
			
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

			$("#YourCartHas_NProducts").html(jsonObj.updatedCartSummary);
			
			$(jsonObj.row_to_remove).remove(); // remove the specified DOM element.
			
			if (jsonObj.cartIsEmpty == "true")
			{
				$("#cartTable").remove();
				
				$("#grandTotalContainer").remove();
			}
			else
			{
				$("#grandTotalValue").html(jsonObj.newGrandTotal);
			}
		}
	}
	
	http.send(params);
}










/**
 * BRAND NEW FUNCTION - NOT TESTED YET !
 * 
 * CART
 * 
 * Called when the user changes the quantity for an item listed in the CART.
 */
function updateCartItemQty(field)
{

	// var url = "http://localhost/CI_fuzzyBear/index.php/cart/updateQty";
	
	var url = "http://shielded-spire.comoj.com/index.php/cart/updateQty";



	var params = "productID=" + field.id + "&qty=" + field.value; // TEST THIS FOR VULNERABILITIES! Need CI to sanitize this before processing.

	var http = getXMLHttpRequest();

	http.open("POST", url, true);

	var myField = field;

	//Send the proper header information along with the request

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	//http.setRequestHeader("Content-length", params.length);			// "unsafe header"

	//http.setRequestHeader("Connection", "close");						// "unsafe header"


	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			var $showDebugInfo = false;

			if ($showDebugInfo)
			{
				if (http.responseText.length > 300)
				{
					$("#debugPanel").html("<p>response length: " + http.responseText.length + "</p>" + http.responseText + "<br /><br />");
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
			
			$("#grandTotalValue").html(jsonObj.newGrandTotal);

			$(jsonObj.subTot_id_toChange).html(jsonObj.newSubTotal);

			$("#qty_input_" + jsonObj.productID).val(jsonObj.newQty);
			
			$("#YourCartHas_NProducts").html(jsonObj.updatedCartSummary);
		}
	}

	//alert("params: " + params);

	http.send(params);
}