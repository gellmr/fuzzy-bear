
<!-- ============================================= -->
<!-- (HTML_Fragment)     views/myAccount/index.php -->


<div id="myAccount_div">

	<br />
	<br />
	
	<h3>
		My Account Details
	</h3>
	
	
	<?php
	
	// Message (first run / error / successfully updated)
	
	if (isset($submissionMessage) && strlen($submissionMessage) > 0)
	{
		$div_id = '';
		
		if (isset($valid_message_divId))
		{
			$div_id = 'id="'.$valid_message_divId.'"';
		}
		?>
	
		<div class="rego_area" <?php echo $div_id; ?>>
			<?php
				echo $submissionMessage;
			?>
			<br />
			<br />
		</div>
	
		<br />
	
		<?php
	}
	
	// Display validation errors

	if (isset($validationErrors) && strlen($validationErrors) > 0)
	{
		?>

		<!--
		<div class="linkDark friendly_ErrorText small_Text">
		-->
		
		<div class="rego_area friendly_ErrorText small_Text" id="validationMessage">

			<?php

			echo $validationErrors;

			?>

		</div>

		<br />

		<?php
	}
	
	
	//
	//
	//
	//
	// 
	// -------------------------------------------------------------------------
	//									FORM
	// -------------------------------------------------------------------------
	
	echo form_open('myAccount'); // Controller to invoke when we submit the form
	    //
		//
		//
		?>
		<div class="rego_area">
			<?php
			//
			// ----------------------------------
			//				USER NAME
			// ----------------------------------
			?>

			<div id="div_uname" class="rego_fields nameInputField">

				User Name:
				
				<span id="userNameValue">
					<?php echo $currentUser; ?>
				</span>
				
				<br />

			</div> <br />

			<?php
			//
			//
			//
			//
			//
			//
			// ----------------------------------
			//				PASSWORD CHANGE
			// ----------------------------------
			?>

			<div id="div_password" class="rego_fields">

				<?php
				echo anchor("notImplementedYet", "I want to change my password", 'title="Change password"');
				?>

				<br />

			</div> <br />

			<?php
			//
			//
			//
			// ----------------------------------
			//				FIRST NAME
			// ----------------------------------
			?>

			<div id="div_fname" class="rego_fields nameInputField">

				First Name

				<div class="basic_details_left_align">

					<?php

					$fieldId = 'input_fname';

					$attribs = array
					(
						'name'		=> $fieldId,

						'id'		=> $fieldId,

						'maxlength'	=> $max_name_len,

						'value'		=> set_value($fieldId, $input_fname)

						//'onblur'	=> "checkNameString(this);"
					);

					echo form_input($attribs);

					?>

				</div>

			</div> <br />

			<?php
			//
			//
			//
			//
			//
			//
			// ----------------------------------
			//				LAST NAME
			// ----------------------------------
			?>

			<div id="div_lname" class="rego_fields nameInputField">

				Last Name

				<div class="basic_details_left_align">

					<?php

					$fieldId = 'input_lname';

					$attribs = array
					(
						'name'		=> $fieldId,

						'id'		=> $fieldId,

						'maxlength'	=> $max_name_len,

						'value'		=> set_value($fieldId, $input_lname)

						//'onblur'	=> "checkNameString(this);"
					);

					echo form_input($attribs);

					?>

				</div>

			</div> <br />

			<?php
			//
			//
			//
			//
			//
			//
			// ----------------------------------
			//				EMAIL ADDRESS
			// ----------------------------------
			?>

			<div id="div_emailAddr" class="rego_fields">

				Email Address

				<div class="basic_details_left_align">

					<?php

					$fieldId = 'input_emailAddr';

					$attribs = array
					(
						'name'	=> $fieldId,

						'id'	=> $fieldId,

						'size'	=> '48',				// the physical length of the input field.

						'maxlength'	=> $email_max_len,	// the number of chars you can type into the field.

						'value' => set_value($fieldId, $input_emailAddr)

						//'onblur'	=> "checkEmailAvail(this);"
					);

					echo form_input($attribs);

					?>

				</div>

			</div> <br />

			<?php
			//
			//
			//
			//
			//
			//
			// ----------------------------------
			//				HOME PHONE
			// ----------------------------------
			?>

			<div id="div_homePhone" class="rego_fields">

				Home Phone

				<div class="basic_details_left_align">

					<?php

					$fieldId = 'input_homePhone';

					$attribs = array
					(
						'name'	=> $fieldId,

						'id'	=> $fieldId,

						'size'	=> '48',				// the physical length of the input field.

						'maxlength'	=> $phone_max_len,	// the number of chars you can type into the field.

						'value' => set_value($fieldId, $input_homePhone)
					);

					echo form_input($attribs);

					?>

				</div>

			</div> <br />

			<?php
			//
			//
			// ----------------------------------
			//				WORK PHONE
			// ----------------------------------
			?>

			<div id="div_workPhone" class="rego_fields">

				Work Phone

				<div class="basic_details_left_align">

					<?php

					$fieldId = 'input_workPhone';

					$attribs = array
					(
						'name'	=> $fieldId,

						'id'	=> $fieldId,

						'size'	=> '48',				// the physical length of the input field.

						'maxlength'	=> $phone_max_len,	// the number of chars you can type into the field.

						'value' => set_value($fieldId, $input_workPhone)
					);

					echo form_input($attribs);

					?>

				</div>

			</div> <br />

			<?php
			//
			//
			// ----------------------------------
			//				MOBILE PHONE
			// ----------------------------------
			?>

			<div id="div_mobilePhone" class="rego_fields">

				Mobile Phone

				<div class="basic_details_left_align">

					<?php

					$fieldId = 'input_mobPhone';
					
					$attribs = array
					(
						'name'	=> $fieldId,

						'id'	=> $fieldId,

						'size'	=> '48',				// the physical length of the input field.

						'maxlength'	=> $phone_max_len,	// the number of chars you can type into the field.

						'value' => set_value($fieldId, $input_mobPhone)
					);

					echo form_input($attribs);

					?>

				</div>

			</div> <br />

		</div> <!-- rego_area -->
	
	
		<br />
		<br />
	
		
		
		
		<div class="rego_area">

			<?php
			//
			//
			// ----------------------------------
			//				SHIPPING ADDRESS
			// ----------------------------------
			?>

			<div id="div_shippingAddress" class="rego_fields">

				<b>Shipping Address</b>
				<br />
				<br />
				<br />

				<div class="left_aligned_input_fields">

					<?php

					$labelText = "Address Line 1";

					$fieldId = "input_shippingAddress_line1";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $address_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_shippingAddress_line1)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";



					$labelText = "Address Line 2";

					$fieldId = "input_shippingAddress_line2";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $address_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_shippingAddress_line2)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "City";

					$fieldId = "input_shippingAddress_city";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $city_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_shippingAddress_city)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "State";

					$fieldId = "input_shippingAddress_state";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $state_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_shippingAddress_state)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "Post Code";

					$fieldId = "input_shippingAddress_postcode";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $postcode_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_shippingAddress_postcode)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "Country or Region";

					$fieldId = "input_shippingAddress_countryOrRegion";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $countryOrRegion_max_len, // the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_shippingAddress_countryOrRegion)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";

					?>

				</div>

			</div> <br />



		</div> <!-- rego_area -->
	
		<br />
		<br />
	
	
		
		
		
		<div class="rego_area">

			<?php
			//
			//
			// ----------------------------------
			//				BILLING ADDRESS
			// ----------------------------------
			?>

			<div id="div_billingAddress" class="rego_fields">

				<b>Billing Address</b>
				<br />
				<br />
				<br />
				
				<div class="left_aligned_input_fields">

					<?php


					$labelText = "Address Line 1";

					$fieldId = "input_billingAddress_line1";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $address_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_billingAddress_line1)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "Address Line 2";

					$fieldId = "input_billingAddress_line2";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $address_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_billingAddress_line2)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "City";

					$fieldId = "input_billingAddress_city";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $city_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_billingAddress_city)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "State";

					$fieldId = "input_billingAddress_state";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $state_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_billingAddress_state)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "Post Code";

					$fieldId = "input_billingAddress_postcode";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $postcode_max_len,	// the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_billingAddress_postcode)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";




					$labelText = "Country or Region";

					$fieldId = "input_billingAddress_countryOrRegion";

					echo '<div class="fieldLabel">' . $labelText;

						$attribs = array
						(
							'name'		=> $fieldId,

							'id'		=> $fieldId,

							'size'		=> $address_field_size,	// the physical length of the input field.

							'maxlength'	=> $countryOrRegion_max_len, // the number of chars you can type into the field.

							'value' => set_value($fieldId, $input_billingAddress_countryOrRegion)
						);

						echo '<div class="field_input">' . form_input($attribs) . '</div>';

					echo "</div><br />";

					?>

				</div>

			</div>
			
			<br />

		</div>  <!-- rego_area -->
		
		<br />
		
		<br />
		
		<?php
		
		$attribs = array
		(
			'name'	=> 'submit',

			'id'	=> 'myAccount_submit',

			'value' => 'Update My Details'
		);

		echo form_submit($attribs);
		?>
		
		<br />
		
		<br />
		
		<br />
		
		<?php
		//
		//
	echo "</form>";
	// -------------------------------------------------------------------------
	//								END FORM
	// -------------------------------------------------------------------------
	//
	//
	//
	?>
	
</div>



<!-- (END HTML_Fragment) views/myAccount/index.php -->


