
<!-- ========================================= -->
<!-- (HTML_Fragment) View: Checkout (Shipping) -->


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
		
		<h3>Shipping Information</h3>

		
		
		
		<?php
		
		// Display validation errors

		if (isset($validationErrors) && strlen($validationErrors) > 0)
		{
			?>
		
			<div class="linkDark friendly_ErrorText small_Text checkout_div_padding">
				
				<?php

				echo $validationErrors;

				?>
				
			</div>
		
			<br />
			
			<?php
		}
		?>





		
		<div class="linkDark checkout_div_padding">
			
			<h4>Please Select a Shipping Method:</h4>

			<?php

			// Form - Checkout
			// Render the form element.
			// Add a hidden CSFR prevention field.

			echo form_open('checkout/paymentMethod');

			// <form> ...



			echo form_label('Regular:', 'Regular');

			$data = array
			(
				'name'		=> 'shippingMethodRadioGroup',

				'id'		=> 'Regular',

				'value'		=> 'accept',

				'checked'	=> TRUE,

				'style'		=> 'margin:10px',

			);

			echo form_radio($data);

			echo "<br />";





			echo form_label('Express:', 'Express');

			$data = array
			(
				'name'		=> 'shippingMethodRadioGroup',

				'id'		=> 'Express',

				'value'		=> 'accept',

				'checked'	=> FALSE,

				'style'		=> 'margin:10px',
			);
			echo form_radio($data);

			echo "<br />";

			echo "<br />";






			
			

			
			echo "<h4>Shipping Address:</h4>";


			echo form_label('Street Address', 'input_shippingAddress_line1');

			echo "<br />";

			$fieldId = "input_shippingAddress_line1";
			
			$dbValue = $input_shippingAddress_line1;
			
			$postVal = set_value($fieldId, $dbValue);
			
			$fieldVal = $postVal ? $postVal : $dbValue;
			
			$attribs = array
			(
				'name'		=> $fieldId,

				'id'		=> $fieldId,

				'size'		=> $address_field_size,	// the physical length of the input field.

				'maxlength'	=> $address_max_len,	// the number of chars you can type into the field.

				'value'     => $fieldVal
			);

			echo form_input($attribs);

			echo "<br />";




			$fieldId = "input_shippingAddress_line2";
			
			$dbValue = $input_shippingAddress_line2;
			
			$postVal = set_value($fieldId, $dbValue);
			
			$fieldVal = $postVal ? $postVal : $dbValue;

			$attribs = array
			(
				'name'		=> $fieldId,

				'id'		=> $fieldId,

				'size'		=> $address_field_size,	// the physical length of the input field.

				'maxlength'	=> $address_max_len,	// the number of chars you can type into the field.

				'value' => $fieldVal
			);

			echo form_input($attribs);

			echo "<br />";

			echo "<br />";






			$input_class = 'offset_addyFields';


			echo form_label('City', 'address_city');

			$fieldId = "input_shippingAddress_city";
			
			$dbValue = $input_shippingAddress_city;
			
			$postVal = set_value($fieldId, $dbValue);
			
			$fieldVal = $postVal ? $postVal : $dbValue;

			$attribs = array
			(
				'name'		=> $fieldId,

				'id'		=> $fieldId,

				'size'		=> $m_smallAddress_field_size,	// the physical length of the input field.

				'maxlength'	=> $city_max_len,	// the number of chars you can type into the field.

				'value' => $fieldVal,

				'class'	=> $input_class
			);

			echo form_input($attribs);

			echo "<br />";

			echo "<br />";






			echo form_label('State:', 'address_state');

			$fieldId = "input_shippingAddress_state";
			
			$dbValue = $input_shippingAddress_state;
			
			$postVal = set_value($fieldId, $dbValue);
			
			$fieldVal = $postVal ? $postVal : $dbValue;

			$attribs = array
			(
				'name'		=> $fieldId,

				'id'		=> $fieldId,

				'size'		=> $m_smallAddress_field_size,	// the physical length of the input field.

				'maxlength'	=> $state_max_len,	// the number of chars you can type into the field.

				'value' => $fieldVal,

				'class'	=> $input_class
			);

			echo form_input($attribs);

			echo "<br />";

			echo "<br />";






			echo form_label('Post Code:', 'address_postcode');

			$fieldId = "input_shippingAddress_postcode";
			
			$dbValue = $input_shippingAddress_postcode;
			
			$postVal = set_value($fieldId, $dbValue);
			
			$fieldVal = $postVal ? $postVal : $dbValue;

			$attribs = array
			(
				'name'		=> $fieldId,

				'id'		=> $fieldId,

				'size'		=> $m_smallAddress_field_size,	// the physical length of the input field.

				'maxlength'	=> $postcode_max_len,	// the number of chars you can type into the field.

				'value' => $fieldVal,

				'class'	=> $input_class
			);

			echo form_input($attribs);

			echo "<br />";

			echo "<br />";





			echo form_label('Country:', 'address_country');

			$fieldId = "input_shippingAddress_countryOrRegion";
			
			$dbValue = $input_shippingAddress_countryOrRegion;
			
			$postVal = set_value($fieldId, $dbValue);
			
			$fieldVal = $postVal ? $postVal : $dbValue;

			$attribs = array
			(
				'name'		=> $fieldId,

				'id'		=> $fieldId,

				'size'		=> $m_smallAddress_field_size,	// the physical length of the input field.

				'maxlength'	=> $countryOrRegion_max_len, // the number of chars you can type into the field.

				'value' => $fieldVal,

				'class'	=> $input_class
			);

			echo form_input($attribs);

			echo "<br />";

			echo "<br />";

			?>
			
		</div>

		<br />

		<?php

		// Button to Checkout
		
		$attribs = array
		(
			'name'	=> 'paymentMethod',

			'id'	=> 'shippingMethod_submit',

			'value' => 'Next - Payment Method',

			'style' => 'font-size:20px; text-align:center',
		);

		echo form_submit($attribs);

		echo "<br />";

		echo "<br />";

		?>

	</form>

	</div>

</div>

<!-- (END HTML_Fragment) View: Checkout (Shipping) -->


