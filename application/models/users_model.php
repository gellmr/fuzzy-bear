
<?php

/**
 * -----------------------------------------------------------------------
 * users_model
 * -----------------------------------------------------------------------
 * 
 * CodeIgniter's "Active Record" is a Database Abstraction Layer which
 * 
 * does query sanitization for us. This means we can write our queries
 * 
 * in a DB Platform independent syntax. The queries are also
 * 
 * escaped automatically by the system.
 * 
 * -----------------------------------------------------------------------
 */
class Users_model extends CI_Model
{

	/**
	 * Default ctor
	 */
	public function __construct()
	{
		// Load the database library.

		$this->load->database();
	}

	
	
	
	/**
	 * Update a user record in the database.
	 * 
	 * @param object $user
	 */
	public function updateUserRecord($user)
	{
		$this->db->where('UserName', $user->UserName);
		
		$this->db->update('as2user', $user); 
	}
	
	
	
	

	/**
	 * Check if the given UserName exists.
	 * 
	 * @param string The UserName we are looking for.
	 * @return true if there is an existing record. False otherwise.
	 */
	public function userName_exists($arg)
	{
		$sql = "SELECT * FROM as2user WHERE UserName = ?";

		$query = $this->db->query($sql, array($arg));

		if ($query->num_rows() > 0)
		{
			return true;
		}
		return false;
	}

	

	/**
	 * Check if the given Email exists.
	 * 
	 * @param string The Email we are looking for.
	 * @return true if there is an existing record. False otherwise.
	 */
	public function email_exists($arg)
	{
		$sql = "SELECT * FROM as2user WHERE Email = ?";

		$query = $this->db->query($sql, array($arg));

		if ($query->num_rows() > 0)
		{
			return true;
		}
		return false;
	}

	
	
	
	/**
	 * Get the correct hash for the given username. Loads the user record from our database and returns the "CorrectHash" field.
	 * 
	 * @param string $uname The UserName of the user.
	 * 
	 * @return string (Format: algorithm:iterations:salt:hash)
	 * 
	 * This is the stored hash, eg "sha256:1000:EiRGHTIjVG79MZ9uqi6rvWenSNboduyJ:sMot/uO9KvDqsF1BOvubpT4cran6zOuP"
	 * 
	 * The algorithm will be something like 'sha256'
	 * 
	 * The iterations will be at least 1000. This is the number of times the SHA hash function was performed to generate the PBKDF2 hash value.

	 * The salt value will be a 32 character hexadecimal string, eg 'EiRGHTIjVG79MZ9uqi6rvWenSNboduyJ'
	 * 
	 * The hash value will be a 32 character hexadecimal string, eg 'sMot/uO9KvDqsF1BOvubpT4cran6zOuP'
	 */
	public function getCorrectHashByUname($uname)
	{
		$sql = "SELECT CorrectHash FROM as2user WHERE UserName = ?";

		$query = $this->db->query($sql, array($uname));

		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			
			return $row->CorrectHash;
		}
		return false;
	}
	
	
	
	/**
	 * Insert a user record into the database.
	 * 
	 * @param object $user
	 */
	public function createUser($user)
	{
		// INSERT record
		
		$data = array
		(
			'UserName'			=> $user->uname,			// user name
			
			'FirstName'			=> $user->fname,			// first name
			
			'LastName'			=> $user->lname,			// last name
			
			'isLoggedIn'		=> $user->isLoggedIn,		// true == The user is logged in.
						
			
			'ShippingAddress_line1'				=> $user->shippingAddress_line1,			// Where to deliver the package.
			
			'ShippingAddress_line2'				=> $user->shippingAddress_line2,			// Where to deliver the package.
			
			'ShippingAddress_city'				=> $user->shippingAddress_city,				// Where to deliver the package.
			
			'ShippingAddress_state'				=> $user->shippingAddress_state,			// Where to deliver the package.
			
			'ShippingAddress_postcode'			=> $user->shippingAddress_postcode,			// Where to deliver the package.
			
			'ShippingAddress_countryOrRegion'	=> $user->shippingAddress_countryOrRegion,	// Where to deliver the package.
			
			
			
			'HomePhone'			=> $user->home_phone,		// home phone
			
			'WorkPhone'			=> $user->work_phone,		// work phone
			
			'MobilePhone'		=> $user->mobile_phone,		// mobile phone
			
			'Email'				=> $user->email,			// email address
			
			
			/*
			 * algorithm:iterations:salt:hash
			 * 
			 * eg
			 * "sha256:1000:ea6c014dc72d6f8ccd1ed92a3662c0e44a8b291a964cf2f0:3d2eec4fe41c849b80c8d83662c0e44a8b291a964cf2f070"
			 * 
			 * Stored in a 109 character string.
			 * -----------------------------------------------------------------
			 * I think it only needs 77 chars because the actual stored values look like:
			 * 
			 * "sha256:1000:EiRGHTIjVG79MZ9uqi6rvWenSNboduyJ:sMot/uO9KvDqsF1BOvubpT4cran6zOuP"
			 * 6:4:32:32
			 * -----------------------------------------------------------------
			 */
			'CorrectHash'		=> $user->correctHash,		// 'algorithm:iterations:salt:hash'
			
			
//			/*
//			 * Salt value, unique to the password.
//			 * (32 character hexadecimal string, eg 'XK5+vxCV/TKibq7Ce1tU9uTmosxXJBwR')
//			 * Stored in database as binary(32)
//			 */
//			'Salt'				=> $user->salt,				
//			
//			/*
//			 * SHA256 salted hash of the user's password
//			 * (32 character hexadecimal string, eg 'XK5+vxCV/TKibq7Ce1tU9uTmosxXJBwR')
//			 * Stored in database as binary(32)
//			 */
//			'Password'			=> $user->password,
			
			/*
			 * "Customer" or "Staff"
			 */
			'Privilege'			=> $user->privilege
		);

		$this->db->insert('as2user', $data);
	}
	
	
	
	/**
	 * Get a user by uname.
	 * 
	 * @param string $uname the user name.
	 * @return row or false
	 */
	public function getUserByUNAME($uname)
	{
		$sql = "SELECT " .
				
				"UserName, FirstName, LastName, Email, " .
				
				"HomePhone, WorkPhone, MobilePhone, " .
				
				"BillingAddress_line1, BillingAddress_line2, BillingAddress_city, BillingAddress_state, BillingAddress_postcode, BillingAddress_countryOrRegion, " .
				
				"ShippingAddress_line1, ShippingAddress_line2, ShippingAddress_city, ShippingAddress_state, ShippingAddress_postcode, ShippingAddress_countryOrRegion " .
				
				"FROM as2user WHERE UserName = ?";

		$query = $this->db->query($sql, array($uname));

		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return false;
	}
	
	
	
	
	
	
	/**
	 * Check if the given username and password match a user record.
	 * 
	 * Return true if the lastactivity timestamp is still ok.
	 * 
	 * @param string $uname
	 * @param string $password
	 
	public function userName_and_password_valid($uname, $password)
	{
		$sql = "SELECT UserName, CorrectHash, LastActivity FROM as2user WHERE UserName = ?";

		$query = $this->db->query($sql, array($uname));

		if ($query->num_rows() > 0)
		{
			$lastActivity = $query->row()->LastActivity;
			
			$correctHash = $query->row()->CorrectHash;
			
			
			// TO DO -> return false if lastactivity is too old.
			// TO DO -> return false if lastactivity is too old.
			// TO DO -> return false if lastactivity is too old.
			// TO DO -> return false if lastactivity is too old.
			
			
			// ...$lastActivity is OK
			
			// Check the password is ok.

			$this->load->library('PBKDF2_hash');

			$SALT_IDX = PBKDF2_hash::getSaltIndex();
			
			
		
			// Extract the known salt from the correct hash value.

			$correctSalt = explode(':', $correctHash)[$SALT_IDX];

			$hashGuess = PBKDF2_hash::hashPassword_WithKnownSalt($givenPw, $correctSalt); // returns "algorithm:iterations:salt:hash"

			$attempt_summary = 'users_model.php<br />';

			$attempt_summary .= '  uname:			' . $uname  . '<br />';

			$attempt_summary .= '  input password:  ' . $password     . '<br />';

			$attempt_summary .= '  Hash Guess:      ' . $hashGuess    . '<br />';

			$attempt_summary .= '  DB->CorrectHash: ' . $correctHash  . '<br />';



			// If the given password hashes to the same value as "correct hash" then the login is successful.

			if ($correctHash && PBKDF2_hash::validate_password($password, $correctHash))
			{
				return true; // username, password, and $lastActivity ALL GOOD.
			}
		}
		return false; // No such user.
	}
	*/
	
	
}
