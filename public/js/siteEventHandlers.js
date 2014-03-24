

/**
 * Event handlers which can be used by any page.
 */






$( document ).ready(function(){
    
	var date = new Date;
	
	var seconds = date.getSeconds();
	var minutes = date.getMinutes();
	var hour = date.getHours();

	console.log("Store Page loaded. Time of Day == " + hour + ':' + minutes + ' and ' + seconds + " seconds");
	
	
	// Login Panel links
	
	$('.login_panel_button.linkDark').hover(focus_link, blur_link);
	
	$('.login_panel_button.linkDark').focus(focus_link);
	
	$('.login_panel_button.linkDark').blur(blur_link);
	
	
	// Ribbon links
	
	$('.ribbonLink.linkDark').hover(focus_link, blur_link);
	
	$('.ribbonLink.linkDark').focus(focus_link);
	
	$('.ribbonLink.linkDark').blur(blur_link);
	
	
	// Pagination links
	
	$('.linkDark.pagination_links').hover(focus_link, blur_link);
	
	$('.linkDark.pagination_links').focus(focus_link);
	
	$('.linkDark.pagination_links').blur(blur_link);
	
	
	// Products Per Page links
	
	$('.prodPerPage_links.linkDark').hover(focus_link, blur_link);
	
	$('.prodPerPage_links.linkDark').focus(focus_link);
	
	$('.prodPerPage_links.linkDark').blur(blur_link);
	
});





function focus_link()
{
	console.log("focus");
	$(this).addClass('linkFocused');
}

function blur_link()
{
	console.log("blur");
	$(this).removeClass('linkFocused');
}





/**
 * This handler captures the ENTER keypress event before it can be propagated to the other elements of the DOM.
 * This helps us disable the automatic form submission which normally occurs when the user presses ENTER.
 */
$(document).keypress(function(e){
		if (e.keyCode == 13)
		{
			//alert("captured ENTER keypress event");
			
			console.log("(document.keypress) captured ENTER keypress event");
			
			
			
			/*
			 * This is probably not the best way to trigger
			 * events. I should attach event handlers to them..
			 */ 
			$('.qty_inputClass').blur();
			
			$('.qty_inputClassCART').blur();
			
			//$('#inputField_uname').blur();
			
			//$('#input_field_emailAddr').blur();
			
			
			return false; // terminate the event.
		}
	}
);

