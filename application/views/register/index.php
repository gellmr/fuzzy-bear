
<!-- =========================================== -->
<!-- (HTML_Fragment) View: Register (Index Page) -->

<div id="registerDiv">

	
	<br />
	<h3 class="rego_prompts"> New Customer Registration</h3>
		
	<?php
	//
	//
	//
	echo '<div id="errorSummary" class="friendly_ErrorText rego_prompts">';
		//
		//
		//
		/////echo validation_errors();
		
		
		

				
				
		if (!empty($_POST))
		{
			// Form has been previously submitted...
			
			function generate_error_link($fieldName)
			{
				$err = form_error($fieldName);

				$linkId = "id=\"errLink_$fieldName\"";
				
				$anchorName = "anchor_$fieldName";
				
				$scriptAttr = "onclick=\"focusField('" . $fieldName . "')\"";
				
				if (strlen($err) > 0)
				{
					echo "<a class=\"friendly_ErrorText\" $linkId $scriptAttr href=\"#$anchorName\">$err</a>";
				}
			}

			generate_error_link('input_fname');
			
			generate_error_link('input_lname');
			
			generate_error_link('input_uname');
			
			
			generate_error_link('input_emailAddr');
			
			
			generate_error_link('input_homePhone');
			
			generate_error_link('input_workPhone');
			
			generate_error_link('input_mobPhone');
			
			
			if($gotPhone == false)
			{
				$scriptAttr = "onclick=\"focusField('input_homePhone')\"";
				
				echo '<a id="errLink_input_phones" class="friendly_ErrorText" '.$scriptAttr.' href="#anchor_input_mobPhone">Please provide at least one phone number</a>';
			}
			
			generate_error_link('input_password');
			
			generate_error_link('input_confirmPassword');

		}
		//
		//
		//
	echo '</div>';
	//
	//
	//
	//
	// When we submit the rego form we are taken to a success page.
	//
	echo form_open('register');
		/*
		 * 
		 * 
		 * Data passed from the controller...
		 * 
			$min_name_len = 5;
			$max_name_len = 12;

			$phone_min_len = 8;
			$phone_max_len = 9;

			$pass_min_len = 12;
			$pass_max_len = 32;
		 * 
		 * 
		 */
		?>
	
		
			
			
		<!--div id="div_name_fields" class="rego_fields">
		</div-->
		
		<?php

		echo '<a name="anchor_input_fname"></a>';
		
		echo '<a name="anchor_input_lname"></a>';
		
		?>
		
		<div id="div_FL_names" class="rego_fields rego_area">

			<div id="div_fname" class="rego_fields nameInputField">

				<?php
				
				$fieldName = 'input_fname';
				
				echo 'First Name <br/>';
				
				$attribs = array
				(
					'name'		=> $fieldName,
					'id'		=> $fieldName,
//					'id'		=> 'input_field_fname',
					'maxlength'	=> $max_name_len,
					'value'		=> set_value($fieldName, 'Your first name'),
					'onblur'	=> "checkNameString(this);"
				);
				
				echo form_input($attribs);
				
				?>

				<span id="fname_is_ok_report" class="ajax_report_div friendly_ErrorText small_Text">
					
					<?php
					
					$formErr = form_error($fieldName);

					if (strlen($formErr) > 0)
					{
						echo $formErr;
					}
					else
					{
						echo "<p><br/></p>";
					}
					
					?>
					
				</span>

			</div>
			
			
			

			<div id="div_lname" class="rego_fields nameInputField">

				<!-- uses absolute pos -->

				Last Name
				<br/>
				<?php
				$attribs = array
				(
					'name'		=> 'input_lname',
//					'id'		=> 'input_field_lname',
					'id'		=> 'input_lname',
					'maxlength'	=> $max_name_len,
					'value'		=> set_value('input_lname', 'Your last name'),
					'onblur'	=> "checkNameString(this);"
				);
				echo form_input($attribs);
				?>

				<span id="lname_is_ok_report" class="ajax_report_div friendly_ErrorText small_Text">
					<?php
					$formErr = form_error('input_lname');

					if (strlen($formErr) > 0)
					{
						echo $formErr;
					}
					else
					{
						echo "<p><br/></p>";
					}
					?>
				</span>

			</div>


		</div>
	
		<br />
		
		
		
		
		
		
	
		<?php
		echo '<a name="anchor_input_uname"></a>';
		?>
		
		<div id="div_uname" class="rego_fields rego_area">

			<p class="rego_prompts">
				User Name
			</p>

			<div class="rego_fields">
				<?php

				// UserName input

				$attribs = array
				(
					'name'		=> 'input_uname',
//					'id'		=> 'inputField_uname',
					'id'		=> 'input_uname',
					'maxlength'	=> $max_name_len,
					'value'		=> '',
					'onblur'	=> "checkUserNameAvail($min_username_len);",
					'value'		=> set_value('input_uname', '')
				);
				echo form_input($attribs);

				// CheckAvailability button

				$attribs = array
				(
					'name'		=> 'Check if this user name is available.',
					'content'	=> "Check Availability",
					'class'		=> "checkAvailButton",
					'onclick'	=> "checkUserNameAvail($min_username_len);",
				);
				echo form_button($attribs);
				?>
				
				<span id="avail_uname_report" class="ajax_report_div friendly_ErrorText">
					<?php
					$formErr = form_error('input_uname');
					
					if (strlen($formErr) > 0)
					{
						echo $formErr;
					}
					else
					{
						echo "<p><br/></p>";
					}
					?>
				</span>
			</div>
		</div>
	
	
	
	
	
		<br/>
		
		
		
		
		
		<?php
		echo '<a name="anchor_input_emailAddr"></a>';
		?>
		<div id="div_emailAddr" class="rego_fields rego_area">
			
			<p class="rego_prompts">
				Email Address - we need this in order to send you<br />confirmation that your order has shipped.
			</p>
			
			<div id="div_email" class="rego_fields">
				<?php
				
				$attribs = array
				(
					'name'	=> 'input_emailAddr',
					'id'	=> 'input_emailAddr',
					'size'	=> '48',
					'maxlength'	=> $email_max_len,
					'value' => set_value('input_emailAddr', ''),
					'onblur'	=> "checkEmailAvail(this);"
				);
				echo form_input($attribs);
				?>
				
				<!--span id="avail_email_report" class="rego_fields ajax_report_div friendly_ErrorText"-->
				<span id="avail_email_report" class="ajax_report_div friendly_ErrorText">
					<?php
					$formErr = form_error('input_emailAddr');

					$alreadyTaken = "<p>There is already an account registered under this email.<br />Have you ".anchor("forgotPassword", "forgotten your password", 'title="Reset Password"')." ?</p>";
					
					if (strlen($formErr) > 0)
					{
						// The user previously submitted an email address that was already taken.
						
						echo $alreadyTaken;
					}
					else
					{
						// no form errors detected.
						
						if ($email_exists == true)
						{
							// The user previously submitted an email address that was already taken.

							echo $alreadyTaken;
						}
						else
						{
							// Seems ok.

							echo "<p><br/></p>"; // no error.
						}
					}
					?>
				</span>
				
			</div>
		</div>
	
	
	
	
	
	
	
		<br/>
		
		
		
		
		
		
		<?php
		echo '<a name="anchor_input_homePhone"></a>';
		echo '<a name="anchor_input_workPhone"></a>';
		echo '<a name="anchor_input_mobPhone"></a>';
		?>
		<div id="div_phoneNumbers" class="rego_fields rego_area">
			
			<p class="rego_prompts">
				Phone Number - please provide one or more phone numbers,<br />so we can contact you in the case of any issues regarding your order.
			</p>
			
			<div id="div_homePhone" class="rego_fields">
				Home Phone
				<br/>
				<?php
				$attribs = array
				(
					'name'	=> 'input_homePhone',
					'id'	=> 'input_homePhone',
					'maxlength'	=> $phone_max_len,
//					'id'	=> 'input_homePhone',
					'value' => set_value('input_homePhone', '')
//					'onblur' => 'checkPhoneNumber(this)'
				);
				echo form_input($attribs);
				?>
				<span id="errMsg_input_homePhone"></span>
			</div>


			<div id="div_workPhone" class="rego_fields">
				Work Phone
				<br/>
				<?php
				$attribs = array
				(
					'name'	=> 'input_workPhone',
					'id'	=> 'input_workPhone',
					'maxlength'	=> $phone_max_len,
//					'id'	=> 'input_workPhone',
					'value' => set_value('input_workPhone', '')
//					'onblur' => 'checkPhoneNumber(this)'
				);
				echo form_input($attribs);
				?>
				<span id="errMsg_input_workPhone"></span>
			</div>

			<div id="div_mobPhone" class="rego_fields">
				Mobile Phone
				<br/>
				<?php
				$attribs = array
				(
					'name'	=> 'input_mobPhone',
					'id'	=> 'input_mobPhone',
					'maxlength'	=> $phone_max_len,
//					'id'	=> 'input_mobPhone',
					'value' => set_value('input_mobPhone', '')
//					'onblur' => 'checkPhoneNumber(this)'
				);
				echo form_input($attribs);
				?>
				<span id="errMsg_input_mobPhone"></span>
				
				<span id="please_give_phone" class="ajax_report_div friendly_ErrorText">
					<?php
					if (!empty($_POST))
					{
						// Form has been previously submitted...

						if($gotPhone == false)
						{
							echo "<p>Please provide at least one phone number.</p>";
						}
					}
					?>
				</span>
				
			</div>
			
		</div>
		
		
		
		
	
		
		<br/>
		
		
		
		
		
		
		<?php
		echo '<a name="anchor_input_password"></a>';
		echo '<a name="anchor_input_confirmPassword"></a>';
		?>
		<div id="div_password_cont" class="rego_fields rego_area">
			<p class="rego_prompts">
				Password - please invent a password, which you will use to log in.
				<br />
				<br />
				You need to include at least one uppercase letter, lowercase letter, a number, and a symbol.
				<br />
				<br />
				Your password should be more than <?php echo $pass_min_len; ?> characters long.<br />
				(The longer the better)
				<br />
				<br />
				It cannot be your user name.
				<br />
				<br />
				Eg, <span id="examplePw" class="small_Text">
					<?php echo $examplePw; ?>
				</span>
			</p>
			<div id="div_password" class="rego_fields">
				<?php
				$attribs = array
				(
					'name'	=> 'input_password',
					'id'	=> 'input_password',
//					'id'	=> 'input_password',
					'size'	=> $pass_max_len,
					'maxlength'	=> $pass_max_len,
					'value' => set_value('input_password', '')
				);
				echo form_password($attribs);
				?>
				
				<span id="examplePw_report" class="ajax_report_div friendly_ErrorText small_Text">
					<?php
					// echo "Your password cannot be " . $examplePw;
					?>
				</span>
			</div>
			
			<p class="rego_prompts">
				Retype Password - Please type it again, to be sure.
			</p>
			<div id="div_confirmPassword" class="rego_fields">
				<?php
				$attribs = array
				(
					'name'	=> 'input_confirmPassword',
					'id'	=> 'input_confirmPassword',
//					'id'	=> 'input_confirmPassword',
					'size'	=> $pass_max_len,
					'maxlength'	=> $pass_max_len,
					'value' => set_value('input_confirmPassword', '')
				);
				echo form_password($attribs);
				?>
			</div>
		</div>
		
		
	
		<div id="div_submit" class="rego_fields">
			<br/>
			<?php
			$attribs = array
			(
				'name'	=> 'submit',
				'id'	=> 'register_submit',
				'value' => 'Create My Account'
			);
			echo form_submit($attribs);
			?>
		</div>
	
	
	
		<?php
		//
		//
		//
		?>
	</form>
	<?php
	//
	//
	// ------------------------------------------
	?>
	
	
	
	<br />
</div>



<!-- (END HTML_Fragment) View: Register (Index Page) -->


