<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



/*
 * PBKDF2 Hashing Algorithm from http://crackstation.net/hashing-security.htm
 * 
 * 
 * Password hashing with PBKDF2.
 * 
 * Author: havoc AT defuse.ca
 * 
 * www: https://defuse.ca/php-pbkdf2.htm
 */


// These constants may be changed without breaking existing hashes.
define("PBKDF2_HASH_ALGORITHM", "sha256");
define("PBKDF2_ITERATIONS", 1000);
define("PBKDF2_SALT_BYTE_SIZE", 24);	// This results in a 32 character hexadecimal string, eg 'XK5+vxCV/TKibq7Ce1tU9uTmosxXJBwR'
define("PBKDF2_HASH_BYTE_SIZE", 24);	// This results in a 32 character hexadecimal string, eg '6gbHHsacJxZEIw38di+ABBQTt6qOByag'

define("HASH_SECTIONS", 4);
define("HASH_ALGORITHM_INDEX", 0);
define("HASH_ITERATION_INDEX", 1);
define("HASH_SALT_INDEX", 2);
define("HASH_PBKDF2_INDEX", 3);

	


class PBKDF2_hash
{
	
	
	
	
	
	/**
	 * Given a password, generate a random salt, and hash.
	 * 
	 * @param  string $password     The password to be hashed.
	 * 
	 * @return string               Format: algorithm:iterations:salt:hash
	 */
	public static function create_hash($password)
	{
		$salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
		
		return self::makeHash($password, $salt);
	}
	
	
	
	
	


	/**
	 * Given a password (and its known salt) generate the corresponding hash.
	 * 
	 * @param  string $password     The password to be hashed.
	 * 
	 * @param  string $knownSalt    The salt to be used while hashing.
	 * 
	 * @return string               Format: algorithm:iterations:salt:hash
	 */
	public static function hashPassword_WithKnownSalt($password, $knownSalt)
	{
		return self::makeHash($password, $knownSalt);
	}
	
	
	
	
	
	
	
	
	
	/**
	 * Private function to generate the hash value for a given password, if we know its salt.
	 * 
	 * @param  string $password   The password to be hashed.
	 * 
	 * @param  string $salt       The salt to be used while hashing.
	 * 
	 * @return string             Format: algorithm:iterations:salt:hash
	 */
	private static function makeHash($password, $salt)
	{
		return PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" .  $salt . ":" . base64_encode
		(
			self::pbkdf2
			(
				PBKDF2_HASH_ALGORITHM,
				$password,
				$salt,
				PBKDF2_ITERATIONS,
				PBKDF2_HASH_BYTE_SIZE,
				true
			)
		);
	}
	
	
	
	
	
	
	
	
	/**
	 * Check a given password against a known hash value (from the database).
	 * 
	 * @param  string    $givenPassword eg "gellsome"
	 * 
	 * @param  string    $correct_hash format: algorithm:iterations:salt:hash  This is the stored hash value from our database, eg "sha256:1000:EiRGHTIjVG79MZ9uqi6rvWenSNboduyJ:sMot/uO9KvDqsF1BOvubpT4cran6zOuP"
	 * 
	 * @return boolean   true if $password can be hashed to match $correct_hash (using the salt value in $correct_hash)
	 */
	public static function validate_password($givenPassword, $correct_hash)
	{
		$correct_array = explode(":", $correct_hash);
		
		if(count($correct_array) < HASH_SECTIONS)
		{
			return false; // input is bad.
		}
		
		$algorithm = $correct_array[HASH_ALGORITHM_INDEX];
		
		$correctHashValue = base64_decode($correct_array[HASH_PBKDF2_INDEX]);
		
		$correctSalt = $correct_array[HASH_SALT_INDEX];
		
		$iterations = (int)$correct_array[HASH_ITERATION_INDEX];
		
		
		// Calculated the user's hash value guess
		
		$guessedHashValue = self::pbkdf2
		(
			$algorithm,					// Hash algorithm to use.

			$givenPassword,				// The password to be hashed.

			$correctSalt,				// A salt that is unique to the password

			$iterations,				// The number of times we want to hash the value.

			strlen($correctHashValue),	// Length of the derived key in bytes

			true						// Return a raw binary value.
		);
		
		// Compare the given hash with the stored hash.
		
		$user_authenticated = self::slow_equals
		(
			$correctHashValue, $guessedHashValue
		);
		
		return $user_authenticated; // true if the user gave us the right password.
	}



	
	/**
	 * Compares two strings $a and $b in length-constant time.
	 * 
	 * @param string $a
	 * 
	 * @param string $b
	 * 
	 * @return boolean (true if the strings match)
	 */
	public static function slow_equals($a, $b)
	{
		$diff = strlen($a) ^ strlen($b);
		for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
		{
			$diff |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $diff === 0;
	}


	
	
	
	

	
	/**
	 * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
	 * 
	 * Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
	 * 
	 * This implementation of PBKDF2 was originally created by https://defuse.ca
	 * 
	 * With improvements by http://www.variations-of-shadow.com
	 * 
	 * @param type $algorithm	The hash algorithm to use. Recommended: SHA256
	 * 
	 * @param type $password	The password.
	 * 
	 * @param type $salt		A salt that is unique to the password.
	 * 
	 * @param type $count		Iteration count. Higher is better, but slower. Recommended: At least 1000.
	 * 
	 * @param type $key_length	The length of the derived key in bytes.
	 * 
	 * @param type $raw_output	If true, the key is returned in raw binary format. Hex encoded otherwise.
	 * 
	 * @return type				A $key_length-byte key derived from the password and salt.
	 */
	private static function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
	{
		$algorithm = strtolower($algorithm);
		if(!in_array($algorithm, hash_algos(), true))
			die('PBKDF2 ERROR: Invalid hash algorithm.');
		if($count <= 0 || $key_length <= 0)
			die('PBKDF2 ERROR: Invalid parameters.');

		$hash_length = strlen(hash($algorithm, "", true));
		$block_count = ceil($key_length / $hash_length);

		$output = "";
		for($i = 1; $i <= $block_count; $i++) {
			// $i encoded as 4 bytes, big endian.
			$last = $salt . pack("N", $i);
			// first iteration
			$last = $xorsum = hash_hmac($algorithm, $last, $password, true);
			// perform the other $count - 1 iterations
			for ($j = 1; $j < $count; $j++) {
				$xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
			}
			$output .= $xorsum;
		}

		if($raw_output)
			return substr($output, 0, $key_length);
		else
			return bin2hex(substr($output, 0, $key_length));
	}


	
	
	
	/**
	 * Return the constant we are using as the salt index, eg 2 for "algorithm:iterations:salt:hash"
	 * @return int value
	 */
	public static function getSaltIndex()
	{
		return HASH_SALT_INDEX;
	}
}

/* End of file PBKDF2_hash.php */
?>