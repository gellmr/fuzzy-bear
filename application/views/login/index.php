
<!-- =========================================== -->
<!-- (HTML_Fragment) View: login (Index Page) -->

<div id="login_outer_div">
	<div id="login_div">

		<br />
		
		<?php
		if (isset($failedPreviously) && $failedPreviously)
		{
			?>
			<h4 class="login_prompts"> Sorry, your details did not match our records.</h4>
			
			Please try again.
			<br />
			<br />
			
			<?php
		}
		else
		{
			?>
			<h3 class="login_prompts"> Please provide your details below.</h3>
			<?php
		}
		?>

		<?php
		
		
		//
		//
		//
		//
		// ---------------------------------------------------------------------
		// The controller to invoke when we submit the form.
		//
		echo form_open('login');
			//
			//
			//
			//
			//
			//
			?>
			<div id="div_uname">
				<?php
				//
				//
				// USER NAME

				$fieldName = 'input_uname';

				echo 'Username <br/>';

				$attribs = array
				(
					'name'		=> $fieldName,
					'id'		=> $fieldName,
					'maxlength'	=> $max_name_len,
					'value'		=> ''
				);

				echo form_input($attribs);
				//
				//
				//
				?>
			</div>
			<?php
			//
			//
			//
			//
			//
			//
			?>
			<div id="div_password">
				<?php
				//
				//
				// PASSWORD

				echo 'Password <br/>';

				$attribs = array
				(
					'name'		=> 'input_password',
					'id'		=> 'input_password',
					'size'		=> $pass_max_len,
//					'maxlength'	=> $pass_max_len,  // WE DONT WANT TO GIVE THE ATTACKER THIS HINT. THE PASSWORD LENGTH SHOULD BE UNKNOWN
					'value'		=> ''
				);
				echo form_password($attribs);
				//
				//
				//
				?>
			</div>
			<?php
			//
			//
			//
			//
			//
			//
			?>
			<div id="div_submit">
				<br/>
				<?php
				//
				//
				// SUBMIT BUTTON

				$attribs = array
				(
					'name'	=> 'submit',
					'id'	=> 'login_submit',
					'value' => 'Login'
				);
				echo form_submit($attribs);
				?>
			</div>
			<?php
			//
			//
			//
			//
			//
			//
		echo '</form>';
		//
		//
		// End of form
		// -------------------------------------------------------------------------
		//
		//
		//
		?>
		<br />
		<br />
		<?php echo validation_errors(); ?>
	</div>
</div>

<!-- (END HTML_Fragment) View: login (Index Page) -->

