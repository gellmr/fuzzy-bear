<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * MyAccount
 */
class MyAccount extends MY_Controller
{

	protected $m_userRecord;
	
	
	/**
	  Default ctor.
	 */
	public function __construct()
	{
		parent::__construct();  // Invoke parent ctor
		
		$this->load->model('users_model');  // Load MVC model.
		
		$this->validationRules();
	}
	
	
	
	
	private function validationRules()
	{
		// Form validation rules
		
		$this->load->library('form_validation');
		
		
		
		// First Name
		
		$this->form_validation->set_rules
		(
			'input_fname', 'First Name',

			"trim|required|min_length[$this->m_min_name_len]|max_length[$this->m_max_name_len]|xss_clean"
		);
		
		// Last Name
		
		$this->form_validation->set_rules
		(
			'input_lname', 'Last Name',

			"trim|required|min_length[$this->m_min_name_len]|max_length[$this->m_max_name_len]|xss_clean"
		);
		
		// Email Address
		
		$this->form_validation->set_rules
		(
			'input_emailAddr', 'Email Address',

			"trim|required|min_length[$this->m_email_min_len]|max_length[$this->m_email_max_len]|xss_clean"
		);
		
		// Home Phone
		
		$this->form_validation->set_rules
		(
			'input_homePhone', 'Home Phone',

			"trim|required|min_length[$this->m_phone_min_len]|max_length[$this->m_phone_max_len]|xss_clean"
		);
		
		// Work Phone
		
		$this->form_validation->set_rules
		(
			'input_workPhone', 'Work Phone',

			"trim|required|min_length[$this->m_phone_min_len]|max_length[$this->m_phone_max_len]|xss_clean"
		);
		
		// Mobile Phone
		
		$this->form_validation->set_rules
		(
			'input_mobPhone', 'Mobile Phone',

			"trim|required|min_length[$this->m_phone_min_len]|max_length[$this->m_phone_max_len]|xss_clean"
		);
		
		
		
		
		
		// Shipping Address
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_line1', 'Street Address Line 1 (Shipping)',

			"trim|required|min_length[$this->m_address_min_len]|max_length[$this->m_address_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_line2', 'Street Address Line 2 (Shipping)',

			"trim|min_length[$this->m_address_min_len]|max_length[$this->m_address_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_city', 'Street Address - City (Shipping)',

			"trim|required|min_length[$this->m_address_min_len]|max_length[$this->m_city_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_state', 'Street Address - State (Shipping)',

			"trim|required|min_length[$this->m_state_min_len]|max_length[$this->m_state_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_postcode', 'Street Address - Post Code (Shipping)',

			"trim|required|min_length[$this->m_postcode_min_len]|max_length[$this->m_postcode_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_countryOrRegion', 'Street Address - Country (Shipping)',

			"trim|required|min_length[$this->m_countryOrRegion_min_len]|max_length[$this->m_countryOrRegion_max_len]|xss_clean"
		);
		
		
		
		
		// Billing Address
		
		$this->form_validation->set_rules
		(
			'input_billingAddress_line1', 'Street Address Line 1 (Billing)',

			"trim|required|min_length[$this->m_address_min_len]|max_length[$this->m_address_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_billingAddress_line2', 'Street Address Line 2 (Billing)',

			"trim|min_length[$this->m_address_min_len]|max_length[$this->m_address_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_billingAddress_city', 'Street Address - City (Billing)',

			"trim|required|min_length[$this->m_address_min_len]|max_length[$this->m_city_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_billingAddress_state', 'Street Address - State (Billing)',

			"trim|required|min_length[$this->m_state_min_len]|max_length[$this->m_state_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_billingAddress_postcode', 'Street Address - Post Code (Billing)',

			"trim|required|min_length[$this->m_postcode_min_len]|max_length[$this->m_postcode_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_billingAddress_countryOrRegion', 'Street Address - Country (Billing)',

			"trim|required|min_length[$this->m_countryOrRegion_min_len]|max_length[$this->m_countryOrRegion_max_len]|xss_clean"
		);
	}
	
	
	
	
	
	
	
	
	/**
	 * Called when a (logged in) user clicks the "My Account" link in top right corner (the login panel)
	 */
	public function index()
	{
		// CHECK THE ENCRYPTED SESSION COOKIE, to make sure the user login is still valid.
		
		if ($this->user_sessCookie_loginIsValid())
		{
			// USER SEEMS TO BE LOGGED IN.
			
			$this->serveMyAccountPage();
			
		}
		else
		{
			// Serve an error page.
			
			$this->logout_user_WriteToSessCookie();
			
			$this->accessDenied();
		}
	}
	
	
	
	
	private function append_Debug(&$outStr, $postField, $postValue, $dbField, $dbValue)
	{
		$truncAfter = 45;
		
		//$len = strlen($postField . $postValue);
		
		$post_display = $postField . ' ' . $postValue .= '                              ';
		
		$post_display = substr($post_display, 0, $truncAfter);
		
		$outStr .= 'POST '.$post_display . ' (DB ' . $dbField . ') ' . $dbValue . '<br />';
	}
	
	
	
	
	
	
	/**
	 * Allow access to the MY ACCOUNT page
	 */
	private function serveMyAccountPage()
	{
		$data = $this->init_ViewData();
		
		array_push($data['css_array'], "myAccount.css");
		
		
		if ($this->form_validation->run())
		{
			// Account details accepted.
			
			$this->m_transactionType = $this->TRANSACTION_TYPE_ALLOW_WRITE;

			$data['submissionMessage'] = 'Your account details were successfully updated.';
			
			$data['valid_message_divId'] = 'validationSuccess'; // setting this causes a div to appear green (eg success)
		}
		else
		{
			// First run, or invalid details.

			if (!empty($_POST))
			{
				// Invalid details.

				$this->m_transactionType = $this->TRANSACTION_TYPE_DENY_WRITE;
				
				$data['submissionMessage'] = 'Sorry, you entered some invalid details.';
			}
			else
			{
				// First run.

				$this->m_transactionType = $this->TRANSACTION_TYPE_ALLOW_READ;
				
				$data['submissionMessage'] = 'Please edit your details below.';
			}
			
		}
		
		
		
		// Look up the user record.
		
		$this->m_userRecord = $this->get_user_record();
		
		$err_report_string = '';
		
		if ($this->input->post())
		{
			$err_report_string .= 'There are POST vars. we can assume this is a form submission.<br />';
			$err_report_string .= 'Write the POST vars to the database.<br />';
			$err_report_string .= '<br />';
		}
		else
		{
			$err_report_string .= 'There are no POST vars. We can assume the user is requesting the form for the first time.<br />';
			$err_report_string .= 'Serve the database record.<br />';
			$err_report_string .= '<br />';
		}
		
		$fieldId = 'input_fname';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->FirstName);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'FirstName', $this->m_userRecord->FirstName);
		
		$fieldId = 'input_lname';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->LastName);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'LastName', $this->m_userRecord->LastName);
		
		$fieldId = 'input_emailAddr';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->Email);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'Email', $this->m_userRecord->Email);
		
		$fieldId = 'input_homePhone';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->HomePhone);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'HomePhone', $this->m_userRecord->HomePhone);
		
		$fieldId = 'input_workPhone';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->WorkPhone);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'WorkPhone', $this->m_userRecord->WorkPhone);
		
		$fieldId = 'input_mobPhone';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->MobilePhone);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'MobilePhone', $this->m_userRecord->MobilePhone);
		
		$err_report_string .= '<br />';
		
		
		
		$fieldId = 'input_shippingAddress_line1';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_line1);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'ShippingAddress_line1', $this->m_userRecord->ShippingAddress_line1);
		
		$fieldId = 'input_shippingAddress_line2';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_line2);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'ShippingAddress_line2', $this->m_userRecord->ShippingAddress_line2);
		
		$fieldId = 'input_shippingAddress_city';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_city);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'ShippingAddress_city', $this->m_userRecord->ShippingAddress_city);
		
		$fieldId = 'input_shippingAddress_state';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_state);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'ShippingAddress_state', $this->m_userRecord->ShippingAddress_state);
		
		$fieldId = 'input_shippingAddress_postcode';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_postcode);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'ShippingAddress_postcode', $this->m_userRecord->ShippingAddress_postcode);
		
		$fieldId = 'input_shippingAddress_countryOrRegion';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_countryOrRegion);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'ShippingAddress_countryOrRegion', $this->m_userRecord->ShippingAddress_countryOrRegion);
		
		
		$err_report_string .= '<br />';
		
		
		$fieldId = 'input_billingAddress_line1';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->BillingAddress_line1);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'BillingAddress_line1', $this->m_userRecord->BillingAddress_line1);
		
		$fieldId = 'input_billingAddress_line2';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->BillingAddress_line2);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'BillingAddress_line2', $this->m_userRecord->BillingAddress_line2);
		
		$fieldId = 'input_billingAddress_city';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->BillingAddress_city);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'BillingAddress_city', $this->m_userRecord->BillingAddress_city);
		
		$fieldId = 'input_billingAddress_state';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->BillingAddress_state);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'BillingAddress_state', $this->m_userRecord->BillingAddress_state);
		
		$fieldId = 'input_billingAddress_postcode';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->BillingAddress_postcode);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'BillingAddress_postcode', $this->m_userRecord->BillingAddress_postcode);
		
		$fieldId = 'input_billingAddress_countryOrRegion';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->BillingAddress_countryOrRegion);
		$this->append_Debug($err_report_string, $fieldId, $data[$fieldId], 'BillingAddress_countryOrRegion', $this->m_userRecord->BillingAddress_countryOrRegion);
		
		
		
		// Write user record back to the database.
		
		if (isset($this->m_userRecord->UserName))
		{
			$this->users_model->updateUserRecord($this->m_userRecord);
		}
		
		
		$data['validationErrors'] = validation_errors();
		
		
		$data['debugText'] .= $this->getDebugStatement();
		
		$data['debugText'] .= '<br />' . $err_report_string;
		
		$data['page_title'] = "My Account";
		
		$data['showDebug'] = true;
		
		
		
		// Load views...
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);
		
		$this->load->view('myAccount/index', $data);

		$this->load->view('templates/footer');
	}
	
	
	
	
	
	
	
	/**
	 * USER IS NOT GRANTED ACCESS
	 */
	private function accessDenied()
	{
		
		// Data for passing to the views...
		
		$data = $this->init_ViewData();
		
		array_push($data['css_array'], "myAccount.css");
		
		$data['debugText'] .= $this->getDebugStatement(); // provides a string of html containing the data that we are trying to debug.
		
		$data['showDebug'] = true;
		
		
		// Look up the user record.
		
		$uname = $data['currentUser'];
		
		if ($uname != $this->getCurrentUserName_NONE())
		{
			$data['userRecord'] = $this->users_model->getUserByUNAME($uname);
		}
		
		$data['debugText'] .= '$uname: ' . $uname;
		
		$data['page_title'] = "My Account";
		
		
		
		// Load views...
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);
		
		$this->load->view('myAccount/accessDenied', $data);

		$this->load->view('templates/footer');
	}
	
	
	
	
}

/* End of file myAccount.php */
	