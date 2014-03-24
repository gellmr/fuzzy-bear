<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Customer Rego Form
 */
class Register extends MY_Controller
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
	 * Customer rego form.
	 */
	public function index()
	{
		
		
		
		$data = array();
		
		$data['gotPhone'] = false; // Did the user provide at least one phone number?
		
		$data['examplePw'] = '**SUPERmario^N64'; // Displayed on the rego form to help the user understand the password requirements. Not allowed to use this password.
		
		$data['debugText'] = "Ph:<br />";
		
		
		
		// Form validation rules
		

		// User Name ... TO DO: validate to ensure it doesn't equal $this->getCurrentUserName_NONE()
		
		$this->form_validation->set_rules
		(
			'input_uname', 'User Name',

			"trim|required|min_length[$this->m_min_username_len]|max_length[$this->m_max_name_len]|xss_clean|is_unique[as2user.UserName]"
		);

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

			"trim|required|valid_email|is_unique[as2user.Email]"
		);
		
		
		
		
		
		
		// Must provide at least one phone number
		
		// Home Phone - not required
		
		$this->form_validation->set_rules
		(
			'input_homePhone', 'Home Phone',

			"trim|min_length[$this->m_phone_min_len]|max_length[$this->m_phone_max_len]|xss_clean"
		);

		// Work Phone - not required
		
		$this->form_validation->set_rules
		(
			'input_workPhone', 'Work Phone',

			"trim|min_length[$this->m_phone_min_len]|max_length[$this->m_phone_max_len]|xss_clean"
		);

		// Mobile Phone - not required
		
		$this->form_validation->set_rules
		(
			'input_mobPhone', 'Mobile Phone',

			"trim|min_length[$this->m_phone_min_len]|max_length[$this->m_phone_max_len]|xss_clean"
		);
		
		
		
		

		
		
		
		
		
		
		
		/*
		Password - please invent a password, which you will use to log in. 

		You need to include at least one uppercase letter, lowercase letter, a number, and a symbol. 

		Your password should be more than 16 characters long.
		(The longer the better) 

		It cannot be your user name. 

		Eg, **SUPERmario^N64

		And it cannot be the example password
		
		TEST CASES
		 * ---------------------------------------------------------------------
		 *	INPUT				RESULT		WHY
		 * ---------------------------------------------------------------------
		 *	**SUPERmario^N64	fail		matches the example password
		 * ---------------------------------------------------------------------
		 * 
		 *	a					fail		too short
		 *	aaaaaaaaaaaaaaa		fail		too short
		 * 
		 * -------------------------------------------------------------------------------------------
		 *									lowercase	uppercase	numbers		symbols		length
		 * -------------------------------------------------------------------------------------------
		 *						fail		0			0			0			 0			0
		 * -------------------------------------------------------------------------------------------
		 *	a					fail		1			0			0			 0			0
		 *	A					fail		0			1			0			 0			0
		 *	9					fail		0			0			1			 0			0
		 *	^					fail		0			0			0			 1			0
		 * -------------------------------------------------------------------------------------------
		 * -------------------------------------------------------------------------------------------
		 *	AAAAAAAAAAAAAA9^	fail		0			1			1			 1			1
		 *	aaaaaaaaaaaaaa9^	fail		1			0			1			 1			1
		 *	aaaaaaaaaaAAAAA^ 	fail		1			1			0			 1			1
		 *	aaaaaaaaaaAAAAA9	fail		1			1			1			 0			1
		 *	aaaaaaaaaaAAAA9^	true		1			1			1			 1			1
		 * -------------------------------------------------------------------------------------------
		 * -------------------------------------------------------------------------------------------
		 * -------------------------------------------------------------------------------------------
		 *	aaaaaaaaaaaaaaA		fail		1			1			0			 0			0
		 *	aaaaaaaaaaaaaa9		fail		1			0			1			 0			0
		 *	aaaaaaaaaaaaaa^		fail		1			0			0			 1			0
		 *	aaaaaaaaaaaaaaaa	fail		1			0			0			 0			1
		 * -------------------------------------------------------------------------------------------
		 *	aA					fail		1			1			0			 0			0
		 *	9A					fail		0			1			1			 0			0
		 *	^A					fail		0			1			0			 1			0
		 *	AAAAAAAAAAAAAAAA	fail		0			1			0			 0			1
		 * -------------------------------------------------------------------------------------------
		 *	a9					fail		1			0			1			 0			0
		 *	A9					fail		0			1			1			 0			0
		 *	9^					fail		0			0			1			 1			0
		 *	9999999999999999	fail		0			0			1			 0			1
		 * -------------------------------------------------------------------------------------------
		 *	a^					fail		1			0			0			 1			0
		 *	A^					fail		0			1			0			 1			0
		 *	9^					fail		0			0			1			 1			0
		 *	^^^^^^^^^^^^^^^^	fail		0			0			0			 1			1
		 * -------------------------------------------------------------------------------------------
		 *	aaaaaaaaaaaaaaaa	fail		1			0			0			 0			1
		 *	AAAAAAAAAAAAAAAA	fail		0			1			0			 0			1
		 *	9999999999999999	fail		0			0			1			 0			1
		 *	^^^^^^^^^^^^^^^^	fail		0			0			0			 1			1
		 * '                '	fail		0			0			0			 0			1
		 * -------------------------------------------------------------------------------------------
		 *  (INCOMPLETE) ...Need to write a script to generate the full set of test cases.
		 * -------------------------------------------------------------------------------------------
		*/
		
		
		
		
		/**
		 --------------------------------------------------------------------------------------------------------------------
			see
			http://stackoverflow.com/questions/6029193/regex-for-at-least-8-upper-and-lowernumbers-or-other-non-alphabetic
		 
			(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[^a-zA-Z]).{16,}
		 
			Explained:
		 
			(?=.*?[a-z]) //lookahead, there has to be a lower case alphabetic char
		  
			(?=.*?[A-Z]) //lookahead, there has to be a upper case alphabetic char
		 
			(?=.*?[^a-zA-Z]) //lookahead, there has to be a non-alphabetic char
		 
			.{16,} // any character at least 8 times
		 --------------------------------------------------------------------------------------------------------------------
		 */
		
		
		// The given password must NOT MATCH the example password.
		
		$examplePw = preg_quote('**SUPERmario^N64'); // the example password. Escape any regex reserved words, to match this literal.
		
		$exclude_examplePw    = "(?!$examplePw)";		// negative lookahead
		
		$look_ahead_lowercase = '(?=.*?[a-z])';			// lookahead
		
		$look_ahead_uppercase = '(?=.*?[A-Z])';			// lookahead
		
		$look_ahead_numeric   = '(?=.*?[0-9])';			// lookahead
		
		$look_ahead_symbol    = '(?=.*?[^a-zA-Z0-9])';	// lookahead
		
		$enforce_length       = '.{16,}';				// minimum length
		
		$passwordRegex = '/' . $exclude_examplePw . $look_ahead_lowercase . $look_ahead_uppercase . $look_ahead_numeric . $look_ahead_symbol . $enforce_length . '/';
		
		
		
		$this->form_validation->set_rules
		(
			'input_password', 'Password',

			"trim|required|min_length[$this->m_pass_min_len]|max_length[$this->m_pass_max_len]|xss_clean|matches[input_confirmPassword]|regex_match[$passwordRegex]"
		);

		// Confirm Password
		
		$this->form_validation->set_rules
		(
			'input_confirmPassword', 'Password Confirmation',

			"trim|required|min_length[$this->m_pass_min_len]|max_length[$this->m_pass_max_len]|xss_clean"
		);
		
		
		
		
		
		
		
		
		
		
		
		
		// Home Phone
		
		try
		{
			$homePhone = $this->getPostVar_Safely('input_homePhone');
			
			if ($this->validPhoneNumber($homePhone))
			{
				$data['gotPhone'] = true;
			}
			throw new Exception();
		}
		catch (Exception $e)
		{
			$data['debugText'] .= "no home phone<br />"; // $e->getMessage();
		}
		
		
		
		// Work Phone
		
		try
		{
			$workPhone = $this->getPostVar_Safely('input_workPhone');
			
			if ($this->validPhoneNumber($workPhone))
			{
				$data['gotPhone'] = true;
			}
			throw new Exception();
		}
		catch (Exception $e)
		{
			$data['debugText'] .= "no work phone<br />"; // $e->getMessage();
		}
		
		
		
		// Mobile Phone
		
		try
		{
			$mobPhone = $this->getPostVar_Safely('input_mobPhone');
			
			if ($this->validPhoneNumber($mobPhone))
			{
				$data['gotPhone'] = true;
			}
			throw new Exception();
		}
		catch (Exception $e)
		{
			$data['debugText'] .= "no mob phone<br />"; // $e->getMessage();
		}
		
		
		
		
		
		
		
		// Messy... to be fixed.
		
		$email_exists_in_database = false;
		
		if (isset($_POST['input_emailAddr']))
		{
			// $priorEmailVal = $_POST['input_emailAddr'];  // FIX THIS
			
			$priorEmailVal = $this->security->xss_clean($_POST['input_emailAddr']);
			
			$email_exists_in_database = $this->users_model->email_exists
			(
				$priorEmailVal
			);
		}
		
		$data['email_exists'] = $email_exists_in_database;
		
		
		
		
		
		// See if the form submission is valid.
		
		if
		(
			$this->form_validation->run() == TRUE
				
			&&
				
			$data['gotPhone'] == TRUE
				
			&&
				
			!($email_exists_in_database) // The user has provided a valid email address that does not yet exist in our database.
			
			&&
				
			($data['debugText'] .= "<br />(Rego form validation: pass)<br />")
			
			&&
				
			$data['examplePw'] != $this->getPostVar_Safely('input_password') // FIX THIS
			
			//&&
			//false // Uncomment this to prevent customers from registering.
		)
		{
			// CUSTOMER REGO FORM == VALID.
			
			$customer = $this->createCustomerRecord();	// CREATE CUSTOMER RECORD
			
			$this->login_user_WriteToSessCookie();		// Set the user session to "logged in"
			
			$this->loadView_regoSuccess($data['debugText']); // Show the Rego Success page.
		}
		else
		{
			$this->loadView_rego($data); // First load ...OR Failed validation.
		}
		
		// Finished serving this request.
	}
	
	
	
	
	/**
	 * @return array The data to send to the views.
	 */
	private function getRegoViewData()
	{
		$data = $this->init_ViewData();
				
		array_push($data['js_array'], "customerRegistration.js");
		
		array_push($data['css_array'], "register.css");
		
		$data['debugText'] .= $this->getDebugStatement();
		
		$data['showDebug'] = true;
		
		return $data;
	}
	
	
	
	
	
	/**
	 * Show the rego success page.
	 * 
	 * @param (string) $err_string An optional Message to append to debug text.
	 */
	private function loadView_regoSuccess($err_string)
	{
		$data = $this->getRegoViewData();

		if ($err_string)
		{
			$data['debugText'] .= "<br />" . $err_string;
		}
		
		$data['debugText'] .= "REGO SUCCESSFUL";
		
		$data['page_title'] = "Register";
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);

		$this->load->view('register/rego_success');

		$this->load->view('templates/footer');
	}
	
	
	
	/**
	 * Show the rego form.
	 * 
	 * @param (array) $viewData Array of values to be merged with the view data.
	 * 
	 * See http://www.php.net/manual/en/function.array-merge.php
	 */
	private function loadView_rego($viewData)
	{
		$debugStr = $viewData['debugText'];
		
		/**
		 * arrResult = array_merge(arrOld, arrNew); // This appends arrNew to arrOld. Note: arrOld['blah'] will bew overwritten by arrNew['blah']
		 */
		$data = array_merge( $viewData, $this->getRegoViewData());

		$data['debugText'] .= "<br />" . $debugStr;
		
		$data['debugText'] .= "<br />FIRST LOAD (or) FAILED VALIDATION";
		
		$data['page_title'] = "Register";
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);

		$this->load->view('register/index', $data);
			
		$this->load->view('templates/footer');
	}
	
	
	
	
	
	
	
	/**
	 * Trim, clean and prep the data for insertion to database.
	 * 
	 * Convert password into salted md5 hash, or something.
	 * 
	 * @return stdClass (Customer record)
	 */
	private function createCustomerRecord()
	{
		
		
		
		
		// CREATE CUSTOMER RECORD HERE.  I should create a customer class.

		$customer = new stdClass;

		$customer->uname = set_value('input_uname');		// user name

		$customer->fname = set_value('input_fname');		// first name

		$customer->lname = set_value('input_lname');		// last name

		$customer->isLoggedIn = false;						// Start as LOGGED OUT

		
		
		

		// These address values are NOT REQUIRED during customer registration.

		// The customer must provide (shipping) address values before we can accept their ORDER.

		// I WANT TO WRITE NULL VALUES TO THE DATABASE FIELDS
		

		$customer->shippingAddress_line1 = NULL;			// Where to deliver the package.

		$customer->shippingAddress_line2 = NULL;			// Where to deliver the package.

		$customer->shippingAddress_city = NULL;				// Where to deliver the package.

		$customer->shippingAddress_state = NULL;			// Where to deliver the package.

		$customer->shippingAddress_postcode = NULL;			// Where to deliver the package.

		$customer->shippingAddress_countryOrRegion = NULL;	// Where to deliver the package.

		
		
		
		
		
		
		
		
		
		
		// I THINK THERE ARE SOME PROBLEMS HERE WITH THE PHONE NUMBER INPUT and saving to database.
		// I WANT IT TO WRITE NULL VALUE TO THE PHONE FIELDS WHEN THE USER LEAVES THEM BLANK.
		
		
		// home phone
		
		$homePh = set_value('input_homePhone');
		
		if ($homePh === false || strlen((string)$homePh) == 0)
		{
			$homePh = null;
		}
		else
		{
			$homePh = (string)$homePh;
		}
		
		$customer->home_phone = $homePh;
		
		
		
		
		// work phone
		
		$workPh = set_value('input_workPhone');
		
		if ($workPh === false || strlen((string)$workPh) == 0)
		{
			$workPh = null;
		}
		else
		{
			$workPh = (string)$workPh;
		}
		
		$customer->work_phone = $workPh;
		
		
		
		
		// mobile phone
		
		$mobilePh = set_value('input_mobPhone');
		
		if ($mobilePh === false || strlen((string)$mobilePh) == 0)
		{
			$mobilePh = null;
		}
		else
		{
			$mobilePh = (string)$mobilePh;
		}
		
		$customer->mobile_phone = $mobilePh;
		
		

		
		
		
		
		// email address
		
		$customer->email = set_value('input_emailAddr');		


		
		

		// VULNERABILITY --- This should be stored as a salted hash, externally to the database.
		
		// See http://crackstation.net/hashing-security.htm
		// 
		// Rainbow tables that can crack any md5 hash of a password up to 8 characters long exist
		
		/*
		 * Lookup tables and rainbow tables only work because each password is hashed the exact same way.
		 * If two users have the same password, they'll have the same password hashes.
		 * We can prevent these attacks by randomizing each hash,
		 * so that when the same password is hashed twice, the hashes are not the same.
		 */
		
		// But what if the attacker downloads the database and gains access to the salt?
		
		/*
		 * See http://stackoverflow.com/questions/1219899/where-do-you-store-your-salt-strings
		 * 
		 * There's no real point in storing salts in a separate file as long as they're on a per-user basis.
		 * The point of the salt is simply to make it so that one rainbow table can't break every password in the DB.
		 */
		
		
		// READ THIS
		/*
		 *		See http://crackstation.net/hashing-security.htm
		 * 
		 * 
		 * THEN, Finish Reading THIS
		 * 
		 *		http://ruby.railstutorial.org/chapters/modeling-and-viewing-users-two#sec%3asecure_passwords
		 */
		
		// $salt = get 64 random bytes. The salt should be at least as long as the hash output (SHA256 outputs 32 bytes)
		
		$rawPw = set_value('input_password');
		
		
		///$this->load->helper('security'); // Need this so we can create hash values.
		
		// $hashedPassword = do_hash($rawPw); // SHA1 via the codeigniter framework.
		
		
		/**
		 * SHA256 via the public domain code from crackstation.
		 * 
		 * returns algorithm:iterations:salt:hash
		 * 
		 * eg
		 * "sha256:1000:ea6c014dc72d6f8ccd1ed92a3662c0e44a8b291a964cf2f0:3d2eec4fe41c849b80c8d83662c0e44a8b291a964cf2f070"
		 * 
		 * My database stores this as a 109 character string.
		 */
		
		$this->load->library('PBKDF2_hash');
		
		$customer->correctHash = PBKDF2_hash::create_hash($rawPw);
		
		
		
//		$hash_and_salt = PBKDF2_hash::create_hash($rawPw);
//		
//		$hash_and_salt = explode(":", $hash_and_salt);
//		
//		// A salt value that is unique to the password.
//		
//		$customer->salt = $hash_and_salt[2];		// (32 character hexadecimal string, eg 'XK5+vxCV/TKibq7Ce1tU9uTmosxXJBwR')
//		
//		// SHA256 hash
//		
//		$customer->password = $hash_and_salt[3];	// (32 character hexadecimal string, eg 'XK5+vxCV/TKibq7Ce1tU9uTmosxXJBwR')
		


		// Customer / Staff

		$customer->privilege = "Customer";



		// CREATE RECORD.

		$this->users_model->createUser($customer);
		
		
		
		$this->saveCurrentUNameToSessCookie($customer->uname); // Save the user name to the session cookie.  I SHOULD SAVE THE WHOLE OBJECT.
		
		return $customer;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Returns true if the given string is a valid phone number.
	 * 
	 * @param string $num
	 * @return boolean true if valid phone number
	 */
	private function validPhoneNumber($num)
	{
		if ($num === false)
		{
			return false; // not valid
		}
		
		$num = (string)$num; // cast to string
		
		
		// Regex
		
		$re_allZeroes = "/^0+$/i"; // true if the phone number is just zeroes. (i) = ignore case
		
		// 987 
		
		//$re_numeric_spacesOK = "/^\d+[\d\s]*$/"; // true if the string is a number with optional spaces.
		
		$re_numeric_spacesOK = "/^\s*[+-]?\s*[0-9|\s]{8,20}\s*$/"; // true if the string is a number with optional spaces.
				
		// string length
		
		$len = strlen($num);
		
		if
		(
			$len >= $this->m_phone_min_len
			&&
			$len <= $this->m_phone_max_len
			&&
			!preg_match($re_allZeroes, $num)		// cannot be "all zeroes"
			&&
			preg_match($re_numeric_spacesOK, $num)	// number with optional spaces.
		)
		{
			return true; // valid phone number
		}
		
		return false; // not valid
	}
	
	
	
	
	
	
	
	
	
	/**
	 * Called by the customer rego form,
	 * when we are checking the availability of a username.
	 */
	public function checkUnameAvail()
	{
		$someUserName = $this->input->post('uname');
		
		/*
		 * --------------------------------------------------
		 * Try some sql injection attacks on my own database.
		 * SUCCESSFULLY BLOCKED BY CODEIGNITER:
		 * $someUserName = "gelly11'; UPDATE as2user SET LastName=JanusHacker WHERE UserName=bard99;";
		 * --------------------------------------------------
		 */
		
		
		// Assume we have sanitized the string.
		
		// Check if the username exists in the model.
		
		$alreadyExists = $this->users_model->userName_exists($someUserName);
		
		Reply::init(); // For sending ajax reply (json)
		
		if ($alreadyExists)
		{
			Reply::writeLine_debug("<p>username already exists: $someUserName</p>");
			
			Reply::setJsonArg("result", "fail");
		}
		else
		{
			Reply::writeLine_debug("<p>username is ok: $someUserName</p>");
			
			Reply::setJsonArg("result", "ok");
		}
		echo Reply::value();
	}
	
	
	
	
	
	
	
	
	/**
	 * Ajax method - check if the given name string is long enough.
	 */
	public function check_nameString_Ok()
	{
		$name = $this->input->post('name');
		
		Reply::init(); // For sending ajax reply (json)
		
		$debugLen = strlen($name);
		
		if
		(
			strlen($name) >= $this->m_min_name_len
			&&
			strlen($name) <= $this->m_max_name_len
		)
		{
			Reply::writeLine_debug("<p>That's a nice name. length == $debugLen</p>");

			Reply::setJsonArg("result", "ok");
		}
		else
		{
			Reply::writeLine_debug("<p>Name has improper length $debugLen</p>");
			
			Reply::setJsonArg("result", "fail");
				
			Reply::setJsonArg("min", $this->m_min_name_len);
				
			Reply::setJsonArg("max", $this->m_max_name_len);
		}
		echo Reply::value();
	}
	
	
	
	

	
	
	
	/**
	 * Called by the customer rego form,
	 * when we are checking the availability of an email address.
	 */
	public function checkEmailAvail()
	{
		$this->load->helper('email');
		
		$someEmail = $this->input->post('email');
		
		// Assume we have sanitized the string.
		
		
		// Save the email string to our session cookie.
		
		$this->save_CustomerEmailAddress_ToSessionCookie($someEmail); // save the given value to our encrypted session cookie.
	
		
		
		// Check if the email exists in the model.
		
		$alreadyExists = $this->users_model->email_exists($someEmail);
		
		Reply::init(); // For sending ajax reply (json)
		
		if ($alreadyExists)
		{
			Reply::writeLine_debug("<p>email already exists: $someEmail</p>");

			Reply::setJsonArg("result", "fail");
		}
		else
		{
			if (valid_email($someEmail))
			{
				Reply::writeLine_debug("<p>email is ok: $someEmail</p>");
			
				Reply::setJsonArg("result", "ok");
			}
			else
			{
				Reply::writeLine_debug("<p>email not valid: $someEmail</p>");
			
				Reply::setJsonArg("result", "invalidEmail");
			}
		}
		echo Reply::value();
	}
	
	
	
}

/* End of file register.php */