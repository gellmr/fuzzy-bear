
<!-- =========================================== -->
<!-- (HTML_Fragment) View: ForgotPassword (Index Page) -->

<div id="forgotPasswordDiv">
	

	<br />
	<br />
	
	
	<p>
		Forgot your password? No worries.
		
		<br />
		<br />

		We can send you a password reset.
		
		<br />
		<br />
		
	</p>
	
	
	<?php
	// 
	//
	//
	echo validation_errors();
	//
	// ------------------------------------------
	//
	echo form_open('sent_password_reset');
		//
		// --------------
		//  TOP OF FORM
		?>

		<div class="rego_fields">

			Username or Email Address<br />

			<?php
			$attribs = array
			(
				'name'		=> 'input_email',
				'id'		=> 'input_field_email',
				'size'		=> '35',
				'maxlength'	=> $max_name_len,
				'value'		=> $knownEmail
			);
			echo form_input($attribs);
			
			
			$attribs = array
			(
				'name'	=> 'submit',
				'id'	=> 'submit_reset_pw',
				'value' => 'Reset My Password'
			);
			echo form_submit($attribs);
			?>
		</div>
	
	
		<?php
		//
		// BOTTOM OF FORM
		// --------------
		//
	echo '</form>';
	//
	// ------------------------------------------
	//
	?>
	
	<br />
	
	<span class="small_Text">
		If you provide an email address, it must be the <b>same one</b> that you gave us when you registered. <br />
		We will send you a SINGLE USE "transaction token", which you can use to set a new password.<br />
		Note - your account will be made inaccessible until you have finished resetting your password.<br />
		The transaction token will expire after fifteen minutes.<br />
		If the token expires before you have finished resetting your password, you will need to click "Reset My Password" again, to get a new token.
	</span>
	
	<br />
	<br />
	<br />
	<br />
	
</div>


<!-- (END HTML_Fragment) View: ForgotPassword (Index Page) -->

