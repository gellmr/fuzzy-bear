
<!-- ================================================= -->
<!-- (HTML_Fragment)     views/checkout/navigation.php -->

<div id="checkout_navigation_div">
	
	<?php
	$brightCss = "linkBright checkout_naviation";
	$darkCss = "linkDark checkout_naviation";
	?>
	
	<?php
	if ($checkout_stage == $CHKOUT_STG_1_SHIPPING)
	{
		echo "<div class=\"".$brightCss." nav_shipping\">";
		echo anchor("checkout/shippingMethod", "Step 1:<br />Shipping Method", 'title="Shipping Method"');
		echo "</div>";
		
		echo "<div class=\"".$darkCss." nav_payment\">";
		echo "Step 2:<br />Payment Method";
		echo "</div>";
		
		echo "<div class=\"".$darkCss." nav_confirm\">";
		echo "Step 3:<br />Confirm Order Details";
		echo "</div>";
	}
	elseif($checkout_stage == $CHKOUT_STG_2_PAYMENT)
	{
		echo "<div class=\"".$darkCss." nav_shipping\">";
		echo anchor("checkout/shippingMethod", "Step 1:<br />Shipping Method", 'title="Shipping Method"');
		echo "</div>";
		
		echo "<div class=\"".$brightCss." nav_payment\">";
		echo anchor("checkout/paymentMethod", "Step 2:<br />Payment Method", 'title="Payment Method"');
		echo "</div>";
		
		echo "<div class=\"".$darkCss." nav_confirm\">";
		echo "Step 3:<br />Confirm Order Details";
		echo "</div>";
	}
	elseif($checkout_stage == $CHKOUT_STG_3_CONFIRM_ORDER)
	{
		echo "<div class=\"".$darkCss." nav_shipping\">";
		echo anchor("checkout/shippingMethod", "Step 1:<br />Shipping Method", 'title="Shipping Method"');
		echo "</div>";
		
		echo "<div class=\"".$darkCss." nav_payment\">";
		echo anchor("checkout/paymentMethod", "Step 2:<br />Payment Method", 'title="Payment Method"');
		echo "</div>";
		
		echo "<div class=\"".$brightCss." nav_confirm\">";
		echo anchor("checkout/confirmOrderDetails", "Step 3:<br />Confirm Order Details", 'title="Confirm Order Details"');
		echo "</div>";
	}
	?>
	
</div>


<!-- (END HTML_Fragment) views/checkout/navigation.php -->

