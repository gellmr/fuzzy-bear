
<!-- ====================================================== -->
<!-- (HTML_Fragment)     views/checkout/confirmOrderDetails -->

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

		<h3>Your Order Details</h3>


		<?php
		// Form - Checkout
		// Render the form element.
		// Add a hidden CSFR prevention field.
		echo form_open('checkout/success');
		// <form> ...

		
		

		?>
		
		<div class="linkDark checkout_div_padding">
		
			<b>Shipping Method: </b> Regular ?

			<br />
			<br />

			<div class="small_Text">
				
				<b> Shipping Address:</b>
				
				<br />
				<br />

				<div class="confirm_summary_fields_offset">
					
					<b>Street Address</b>

					<span class="offset_addyFields">

						<?php echo $userRecord->ShippingAddress_line1; ?>

						<br />

						<?php echo $userRecord->ShippingAddress_line2; ?>

					</span>

					<br />
					<br />
					<br />
					
					<span><b>City</b></span>
						
					<span class="offset_addyFields">
						<?php echo $userRecord->ShippingAddress_city; ?>
					</span>

					<br />
					
					<b>State</b>
						
					<span class="offset_addyFields">
						<?php echo $userRecord->ShippingAddress_state; ?>
					</span>

					<br />
					
					<b>Post Code</b>
						
					<span class="offset_addyFields">
						<?php echo $userRecord->ShippingAddress_postcode; ?>
					</span>

					<br />
					
					<b>Country or Region</b>
						
					<span class="offset_addyFields">
						<?php echo $userRecord->ShippingAddress_countryOrRegion; ?>
					</span>

					<br />
					
				</div>
			</div>
		</div>
		
		<br />
		
		<?php



		
		







		echo "<br />";

		// Button to Checkout
		
		$attribs = array
		(
			'name'	=> 'confirm',

			'id'	=> 'confirmOrderDetails_submit',

			'value' => 'Place Order',

			'style' => 'font-size:20px; text-align:center',
		);

		echo form_submit($attribs);
		
		echo "<br />";
		
		echo "<br />";
		?>

	</form>

	</div>
	
</div>
<!-- (END HTML_Fragment) views/checkout/confirmOrderDetails -->


