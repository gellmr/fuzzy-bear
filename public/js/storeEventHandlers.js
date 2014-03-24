

/**
 * Event handlers for the store.
 */







$( document ).ready(function(){
    
	var date = new Date;
	
	var seconds = date.getSeconds();
	var minutes = date.getMinutes();
	var hour = date.getHours();

	console.log("Store Page loaded. Time of Day == " + hour + ':' + minutes + ' and ' + seconds + " seconds");
	
	
	$('#input_searchBy_keyword').bind('change keyup', search_byKeyWord_click); // Perform search AS WE TYPE. Disable this if the site is under heavy traffic.
	
	$('#search_button').click(search_byKeyWord_click); // Perform a search when we click the "SEARCH" button.
	
	
//	$('.linkDark.pagination_links').hover(focus_pagination_link, blur_pagination_link);
//	
//	$('.linkDark.pagination_links').focus(focus_pagination_link);
//	
//	$('.linkDark.pagination_links').blur(blur_pagination_link);
	
});








/**
 * ONLINE STORE
 * 
 * Called when the user changes the quantity for an item listed in the ONLINE STORE.
 */
function updateCartItemQty(field)
{
	//var url = "http://localhost/CI_fuzzyBear/index.php/store/updateQty";

	var url = "http://shielded-spire.comoj.com/index.php/store/updateQty";
	
	var params = "productID=" + field.id + "&qty=" + field.value; // TEST THIS FOR VULNERABILITIES! Need CI to sanitize this before processing.

	var http = getXMLHttpRequest();

	http.open("POST", url, true);

	var myField = field;

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

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

			$("#qty_input_" + jsonObj.productID).val(jsonObj.newQty);
			
			
			
			// Show/Hide the "incart" icon for this item.
			
			$displayMode = 'none';
			
			if (jsonObj.newQty > 0)
			{
				$displayMode = "block";
			}
			$("#img_inCart_prod" + jsonObj.productID).css({"display": $displayMode});
			
			
			
			// Auto adjust text size if input is long.
			
			$("#qty_input_" + jsonObj.productID).css('font-size', 20);
			
			if (field.value.length > 3)
			{
				$("#qty_input_" + jsonObj.productID).css('font-size', 14);
			}
		}
	}

	http.send(params);
}







/**
 * Perform a search by keyword. The user has clicked the "SEARCH" button.
 * 
 * @param {type} field
 * @returns {undefined}
 */
function search_byKeyWord_click(field)
{
	var keyword = $('#input_searchBy_keyword').val();
	
	console.log(keyword);
	
	// var url = "http://localhost/CI_fuzzyBear/index.php/store/search";

	var url = "http://shielded-spire.comoj.com/index.php/store/search";

	var params = "keyword=" + keyword + '&is_new_keyword=yes'; // TEST THIS FOR VULNERABILITIES! Need CI to sanitize this before processing.
	
	var http = getXMLHttpRequest();

	http.open("POST", url, true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	http.onreadystatechange = function()
	{
		if (http.readyState == 4 && http.status == 200)
		{
			var $showDebugInfo = true;
			
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
				//$("#debugPanel").html(jsonObj.debugString + "<br />" + jsonObj.html);
			
				$("#debugPanel").html(jsonObj.debugString);
			}
			
			$('#totProducts').html('Total Products: ' + jsonObj.prodCountTotal);
			
			$("#storeProducts_placeholder").html(jsonObj.html);
		}
	}
	
	http.send(params);
}