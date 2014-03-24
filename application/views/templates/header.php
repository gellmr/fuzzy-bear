
<!-- ==================================== -->
<!-- (HTML_Fragment) View: Store (Header) -->

<?php

// WINDOWS STYLE PATHNAMES USE A BACKSLASH
//include_once getcwd() . '\application\libraries\util.php'; // not sure if this is a good way to include my util class.

// UNIX STYLE PATHNAMES USE A FORWARD SLASH
include_once getcwd() . '/application/libraries/util.php'; // not sure if this is a good way to include my util class.

?>



<!DOCTYPE html>

<html lang = "en" xmlns="http://www.w3.org/1999/xhtml">

	<head>

		<meta name = "description"	content = "Fuzzy Bear Electronics - Web Site" />

		<meta name = "keywords"		content = "HTML, CSS, XML, JavaScript" />

		<meta name = "author"		content = "Michael Gell, December 2012" />

		<meta charset = "UTF-8" />

		<?php

		// link to CSS files.
		
		if (isset($css_array) && count($css_array) > 0)
		{
			foreach ($css_array as $css_file)
			{
				echo link_tag('public/css/'.$css_file);
			}
		}
		
		// link to JavaScript files.
		
		if (isset($js_array) && count($js_array) > 0)
		{
			foreach ($js_array as $js_file)
			{
				echo '<script src="' . base_url() . 'public/js/' . $js_file . '"></script>';
			}
		}
		?>

		<title>Fuzzy Bear Electronics</title>

	</head>

	
	
	
	<body>

		
		<h3>Fuzzy Bear Electronics</h3>
		
		
		
		<div id='disclaimer'>
			This is not a real site.<br />I'm just demonstrating my amazing web application skills.
		</div>
		
		
		
		<div id="login_panel_div">
			
			<div id="loginStatus" class="small_Text">
				<?php
				// Thin grey strip across the top of the login panel (Top right of page)
				// 
				// CURRENTLY LOGGED IN AS BLAH
				if
				(
					isset($currentUser) && $currentUser != $NO_USER
						
					&&
						
					isset($login_status) && $login_status == $LOGGED_IN
				)
				{
					// Logged in as blah.
					
					echo "Currently logged in as $currentUser";
				}
				else
				{
					// NOT LOGGED IN.
					
					echo "Not currently logged in.";
				}
				//echo " $page_title";
				?>
			</div>
			
			
			<div id="login_panel_all_buttons">
				
				<?php
				
				function echo_anchor($controller, $title, $attribs, $current_page, $css_class, $divId='')
				{
					if ($current_page == $title)
					{
						// No anchor. Already on this page.
						
						echo util::bright_PNG_anchor($title, $css_class, $divId);
					}
					else
					{
						// Yes anchor. We are not on this page.
						
						echo util::dark_PNG_anchor($controller, $title, $attribs, $css_class, $divId);
					}
				}
				
				
				
				// Display all the items in the login panel (top right of screen)
				//
				if (isset($login_panel_contents))
				{
					foreach ($login_panel_contents as $index => $value)
					{
						
						// -------------------------------------------------
						// -------------------------------------------------
						// -------------------------------------------------
						// -------------------------------------------------
						
						if ($value == 'forgot_pw')
						{
							
							/**
							 * Link - I forgot my password
							 * 
							 * THIS IS DISPLAYED IF WE ARE LOGGED OUT.
							 */
							
							$cssClass = "login_panel_button forgot_button";
							
							echo_anchor("forgotPassword", "I forgot my password", 'title="I forgot my password"', $page_title, $cssClass);
							
						}
						elseif ($value == 'register')
						{
							/**
							 * Link - Register
							 * 
							 * THIS IS DISPLAYED IF WE ARE LOGGED OUT.
							 */
							
							$cssClass = "login_panel_button register_button";
							
							echo_anchor("register", "Register", 'title="Register"', $page_title, $cssClass);
							
						}
						elseif ($value == 'login')
						{
							/**
							 * Link - Login
							 * 
							 * THIS IS DISPLAYED IF WE ARE LOGGED OUT.
							 */
							
							$cssClass = "login_panel_button login_button";
							
							echo echo_anchor("login", "Login", 'title="Login"', $page_title, $cssClass); // allow user to login
							
							
							// -------------------------------------------------
							// -------------------------------------------------
							// -------------------------------------------------
							// -------------------------------------------------
							
						}
						elseif ($value == 'my_account')
						{
							/**
							 * Link - My Account
							 * 
							 * ONLY DISPLAYED IF WE ARE LOGGED IN.
							 */
							
							$div_id = "myAccount_button";
							
							$cssClass = "login_panel_button";
							
							echo echo_anchor("myAccount", "My Account", 'title="My Account"', $page_title, $cssClass, $div_id);
							
						}
						elseif ($value == 'logout')
						{
							/**
							 * Link - Logout
							 * 
							 * ONLY DISPLAYED IF WE ARE LOGGED IN.
							 */
							
							$cssClass = "login_panel_button login_button";
							
							echo echo_anchor("logout", "Logout", 'title="Logout"', $page_title, $cssClass); // allow user to logout
							
							
							// -------------------------------------------------
							// -------------------------------------------------
							// -------------------------------------------------
							// -------------------------------------------------
						}
					}
				}
				
				?>
					
			</div>
		</div>
		
		
		<?php if($showDebug && $globalShowDebug) { ?>
			
			<div id="debugPanel">
				<?php
				if(isset($debugText))
				{
					echo $debugText;
				}
				else
				{
					echo "debug panel";
				}
				?>
			</div>
			
		<?php } ?>
		
		<!-- (END HTML_Fragment) View: Store (Header) -->
