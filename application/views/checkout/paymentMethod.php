
<!-- =============================================== -->
<!-- (HTML_Fragment) View: Checkout (Payment Method) -->

<div id="wowza_checkout_container_div">
	
	<?php
	echo $cartHtml;
	?>
	
	<div id="wowza_checkout_form_div">
		
		<?php
		echo $checkoutNavHtml;
		?>
		<br />
		<br />
		
		<h3>Payment Details</h3>

		<div class="linkDark checkout_div_padding">
			
			<b>Please Select a Payment Method:</b>
			<br />
			<br />

			<?php
			// Form - Checkout
			// Render the form element.
			// Add a hidden CSFR prevention field.
			echo form_open('checkout/confirmOrderDetails');
			// <form> ...


			
		</div>

		<br />
		
    

		<?php
		
		echo "<br />";

		// Button to Checkout
		
		$attribs = array
		(
			'name'	=> 'confirmOrderDetails',

			'id'	=> 'paymentMethod_submit',

			'value' => 'Next - Confirm Order Details',

			'style' => 'font-size:20px; text-align:center',
		);

		echo form_submit($attribs);
		
		echo "<br />";
		
		echo "<br />";
		?>

	</form>

	</div>
	
</div>
<!-- (END HTML_Fragment) View: Checkout (Payment Method) -->


