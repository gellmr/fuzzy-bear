<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Login page.
 */
class Login extends MY_Controller
{

	
	
	
	/**
	  Default ctor.
	 */
	public function __construct()
	{
		parent::__construct();  // Invoke parent ctor
		
		$this->load->model('users_model');  // Load MVC model.
	}
	
	
	
	
	
	
	
	/**
	 * Login page
	 * 
	 *	Either the user has clicked "Login" and we need to serve the login form,
	 * 
	 *	or:
	 * 
	 *	The user has submitted the login form and we need to authenticate them.
	 */
	public function index()
	{
		
		
		
		// Set the form validation rules.
		
		
		// User Name
		
		$this->form_validation->set_rules
		(
			'input_uname', 'User Name',

			"required|xss_clean"
		);
		
		
		// Password
		
		$this->form_validation->set_rules
		(
			'input_password', 'Password',

			"required|xss_clean"
		);
		
		
		
		
		
		// Load some helper functions for doing password hash.
		
		$this->load->library('PBKDF2_hash');
		
		$input_uname = '';
		
		$givenPw = '';
		
		$correctHash = false;
		
		$SALT_IDX = PBKDF2_hash::getSaltIndex();
		
		try
		{
			// Get the submitted Username from the POST vars.
			
			$input_uname = $this->getPostVar_Safely('input_uname');					 // FIX THIS 
			
			
			// Load the correct hash from the database, for this user record.
			
			// The "correct hash" is the stored hash value of the password.
			
			$correctHash = $this->users_model->getCorrectHashByUname($input_uname);
			
			
			try
			{
				if (!$correctHash)
				{
					throw new Exception("no such user");
				}
				
				
				// Get the password that has just been submitted. (from our POST vars.)
			
				$givenPw = $this->getPostVar_Safely('input_password');					 // FIX THIS 
				
				
				// Extract the known salt from the correct hash value.

				$exploded_params = explode(':', $correctHash);
				
				if (!isset($exploded_params) || ! $exploded_params)
				{
					throw new Exception("Could not get hash params from database!");
				}
				
				$correctSalt = $exploded_params[$SALT_IDX];

				$hashGuess = PBKDF2_hash::hashPassword_WithKnownSalt($givenPw, $correctSalt); // returns "algorithm:iterations:salt:hash"

				$attempt_summary = 'LOGIN.PHP<br />';
				
				$attempt_summary .= '  input_uname:     ' . $input_uname  . '<br />';

				$attempt_summary .= '  input_password:  ' . $givenPw      . '<br />';

				$attempt_summary .= '  Hash Guess:      ' . $hashGuess    . '<br />';

				$attempt_summary .= '  DB->CorrectHash: ' . $correctHash  . '<br />';
				
				
				
				// If the given password hashes to the same value as "correct hash" then the login is successful.
				
				if
				(
					$this->form_validation->run() == TRUE
					&&
					$correctHash
					&&
					PBKDF2_hash::validate_password(set_value('input_password'), $correctHash)
				)
				{
					// Validation successful. Log the user in.

					// First, trigger the IMMEDIATE expiry of any existing "password reset" tokens, for this user.
					
					// Done.
					
					// Now let the user be logged in.
					
					$this->saveCurrentUNameToSessCookie($input_uname);			// Save username to session cookie.

					$this->login_user_WriteToSessCookie();						// Set the current user's session to "logged in".

					$this->logged_in_successfully_showView($attempt_summary);	// Login successful. Display a view.
				}
				else
				{
					// Failed validation.

					$attempt_summary .= "<br />Failed validation";
					
					$this->showLoginPage($attempt_summary, true);
				}
			}
			catch (Exception $e)
			{
				// We are missing a vital POST variable. (PASSWORD)
				
				$this->showLoginPage("Got input_uname: $input_uname<br />no POST var: 'input_password'<br />", true);
			}
		}
		catch (Exception $e)
		{
			// We are missing a vital POST variable. (USER NAME)
			
			$this->showLoginPage("no POST var: 'input_uname'<br />FIRST LOAD", false);
		}
		
		// Finished serving the request.
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * Show the HTML view for when the user successfully logs in.
	 * 
	 * @param (string) $login_debug_string  Debug string, describes the login attempt.
	 */
	private function logged_in_successfully_showView($login_debug_string)
	{		
		$data = $this->init_ViewData();

		array_push($data['css_array'], "login.css"); // Add our desired css rules.

		$data['debugText'] .= $this->getDebugStatement(); // provides a string of html containing the data that we are trying to debug.

		if ($login_debug_string)
		{
			$data['debugText'] .= "<br />" . $login_debug_string;
		}
		
		$data['page_title'] = "Login";
		
		$data['showDebug'] = true;

		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);

		$this->load->view('login/success', $data); // Successfully logged in.

		$this->load->view('templates/footer');
	}
	
	
	
	
	/**
	 * Show the HTML view for the user Login page.
	 * 
	 * @param (string) $error_string A message describing why the previous login attempt was not successful. (False) if we are loading the login page for the first time.
	 * 
	 * @param (boolean) $previousFail True if we want to display a "failed validation" message.
	 */
	private function showLoginPage($error_string, $previousFail)
	{
		$data = $this->init_ViewData();

		array_push($data['css_array'], "login.css"); // Add our desired css rules.

		$data['debugText'] .= $this->getDebugStatement(); // provides a string of html containing the data that we are trying to debug.

		if ($error_string)
		{
			$data['debugText'] .= "<br />ERROR: " . $error_string;
		}
		
		$data['showDebug'] = true;
		
		if ($previousFail)
		{
			$data['failedPreviously'] = 'true';
		}
		
		$data['page_title'] = "Login";
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);

		$this->load->view('login/index', $data); // Show the login page.

		$this->load->view('templates/footer');
	}
	
}

/* End of file login.php */
	