<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * "Forgot My Password" Form
 */
class ForgotPassword extends MY_Controller
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
	 * Called when we serve:
	 * 
	 * http://localhost/CI_fuzzyBear/index.php/forgotPassword
	 */
	public function index()
	{
		
		// Data for passing to the views...
		
		$data = $this->init_ViewData();
		
		$data['showDebug'] = true;
		
		$data['debugText'] .= $this->getDebugStatement(); // provides a string of html containing the data that we are trying to debug.
		
		

		// Get the customer's email address from the session cookie.
		
		$data['knownEmail'] = "none";
		
		$string = $this->load_CustomerEmailAddress_FromSessionCookie();
		
		if ($string)
		{
			$data['knownEmail'] = $string;
		}
		
		
		$data['max_name_len'] = $this->m_max_name_len;
		
		
		$data['page_title'] = "I forgot my password";
		
		
		// Load views...
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);
		
		$this->load->view('forgotPassword/index', $data);

		$this->load->view('templates/footer');
	}
	
	
	
	/**
	 * Get the email message that is sent to users when they request a password reset.
	 * 
	 * @return string
	 */
	private function getPasswordReset_emailMessage()
	{
		$string = 'Hi, you are receiving this email because a \"password reset request\" has been made regarding the Fuzzy Bear user account registered to this email address.</br ><br /> If you did not request this, please _do something_.<br /><br />If you did request the password reset, please use the following temporary password to login and update your Fuzzy Bear account details. You can choose a different password that is easier to remember.<br/><br />Thanks!<br /><br />The Fuzzy Bear team.';
		
		return $string;
	}
	
	
	
	
	
	
	/**
	 * Called when the user submits the form to reset their password.
	 * 
	 * http://localhost/CI_fuzzyBear/index.php/sent_password_reset
	 * 
	 * READ THIS
	 * 
	 * https://crackstation.net/hashing-security.htm
	 * 
	 * See
	 * How should I allow users to reset their password when they forget it?
	 * 
	 */
	public function sent_password_reset()
	{
		$data = $this->init_ViewData();
		
		// First, trigger the IMMEDIATE expiry of any existing "password reset" tokens, for this user.
		
		

		// Get the customer's email address from the session cookie.
		
		$data['knownEmail'] = "none";
		
		$string = $this->load_CustomerEmailAddress_FromSessionCookie();
		
		if ($string)
		{
			$data['knownEmail'] = $string;
		}
		
		
		
		
		// FIX THIS WHEN YOU HAVE A LINUX BOX TO HOST SITE.
		
		// Send the "password reset" email.
		
		$this->load->library('email');

		$config['protocol'] = 'smtp';

		//$config['smtp_host'] = 'smtp.gmail.com';
		
		$config['charset'] = 'iso-8859-1';
		
		$config['wordwrap'] = TRUE;

		$this->email->initialize($config);

		$this->email->from('gellmr@gmail.com', 'Fuzzy Bear Online Electronics Store');
		
		$this->email->to($data['knownEmail']);

		$this->email->subject('Password Reset');
		
		$message = $this->getPasswordReset_emailMessage();
		
		$this->email->message($message);
		
		$data['debugString'] = false;
		
		if ( ! $this->email->send())
		{
			$data['debugString'] = $this->email->print_debugger(); // Returns a string containing any server messages, the email headers, and the email messsage. Useful for debugging.
		}
		
		
		
		
		// Load views...
		
		$data['page_title'] = 'Remind Password';
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);
		
		// $this->load->view('forgotPassword/sent_password_reset', $data);
		
		$this->load->view('templates/notImplementedYet', $data);

		$this->load->view('templates/footer');
	}
}
	

/* End of file forgotPassword.php */