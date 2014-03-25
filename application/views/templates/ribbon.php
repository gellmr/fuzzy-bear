
<!-- ==================================== -->
<!-- (HTML_Fragment) View: Store (Ribbon) -->

<div class="row">
	
	<?php
	
	function echo_ribbon_link($controller, $title, $attribs, $current_pageTitle, $divclass='', $divid='')
	{
		// Put an anchor (unless we are already on the page.)
		
		if ($current_pageTitle == $title)
		{
			// No anchor. Already on the page.
			
			echo util::bright_PNG_anchor($title, $divclass, $divid);
		}
		else
		{
			// Yes anchor. Not already on the page. 
			
			echo util::dark_PNG_anchor($controller, $title, $attribs, $divclass, $divid);
		}
	}
	
	
	if (!isset($page_title))
	{
		$page_title = "";
	}
	
	?>
	
	<?php
	$div_class = "ribbonLink";
	$div_id = "storeLink";
	// echo_ribbon_link("store", "Store", 'title="Online Store"', $page_title, $div_class, $div_id);
  anchor("store", "Store", 'id="storeLink" class="ribbonLink" title="Online Store"');
	?>
	
	<?php
	$div_class = "ribbonLink";
	$div_id = "cartLink";
	echo_ribbon_link("cart", "My Cart", 'title="My Cart"', $page_title, $div_class, $div_id);
	?>

	<?php
	$div_class = "ribbonLink";
	$div_id = "checkoutLink";
	echo_ribbon_link("checkout/shippingMethod", "Checkout", 'title="Checkout"', $page_title, $div_class, $div_id);
	?>

	<?php
	$div_class = "ribbonLink";
	$div_id = "myOrderstLink";
	echo_ribbon_link("notImplementedYet", "My Orders", 'title="My Orders"', $page_title, $div_class, $div_id);
	?>
	

</div>

		
<!-- (END HTML_Fragment) View: Store (Ribbon) -->