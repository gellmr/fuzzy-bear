<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Log out and display the "logged out" page.
 */
class Logout extends MY_Controller
{

	
	
	
	/**
	  Default ctor.
	 */
	public function __construct()
	{
		parent::__construct();  // Invoke parent ctor
		
		// $this->load->model('users_model');  // Load MVC model.
	}
	
	
	
	
	
	
	
	/**
	 * Logout page
	 */
	public function index()
	{
		
		// Log out
		
		$this->logout_user_WriteToSessCookie();		// Set the current user's session to "logged out".

		$data['login_status'] = $this->getLoginStatusFromSessCookie();	// Get the "login status" (which we just set to "logged out")

		$data['currentUser'] = $this->getCurrentUNameFromSessCookie(); // eg none
		
		
		
		// Data for passing to the views...
		
		$data = $this->init_ViewData();
		
		$data['debugText'] .= $this->getDebugStatement(); // provides a string of html containing the data that we are trying to debug.
		
		$data['showDebug'] = true;
		
		
		$data['page_title'] = "Logout";

		
		
		// Show the Rego Success page.

		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);

		$this->load->view('logged_out/index');

		$this->load->view('templates/footer');
	}
	
	
	
}

/* End of file logout.php */
	