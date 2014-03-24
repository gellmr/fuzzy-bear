
<!-- ====================================================== -->
<!-- (HTML_Fragment)   views/checkout/pleaseLoginFirst      -->

<div id="wowza_checkout_container_div">
	
	<?php
	echo $cartHtml;
	?>
	
	<div id="wowza_checkout_form_div">
	<!--div id="pleaseLoginFirst_div"-->
	

		<p>
			You need to <?php echo anchor("login", "login", "title=login");?> before you can place an order.

			<br />
			<br />

			If you have not yet registered, please <?php echo anchor("register", "create an account", "title=create an account");?>

			<br />
			<br />

		</p>

	</div>
	
</div>

<!-- (END OF HTML_Fragment) views/checkout/pleaseLoginFirst -->
<!-- ====================================================== -->
