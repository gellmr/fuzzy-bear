<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



/**
 * MVC Controller - Fuzzy Bear - Debug
 * 
 * This class provides convenience methods for enabling / disabling the in-page debug information.
 * 
 * 
 * Its features should be DISABLED via config/routes.php to prevent usage by regular users.
 */
class Debug extends MY_Controller
{
	
	
	/**
	 * Default ctor.
	 */
	public function __construct()
	{
		parent::__construct();   // Invoke parent ctor
	}
	
	
	
	
	/**
	 * For convenience only. This function should be disabled via config/routes.php
	 * 
	 * Usage: in the browser, type the follow URLs to toggle the display of debug info.
	 * 
	 *		http://localhost/CI_fuzzyBear/index.php/debug/show
	 */
	public function show()
	{
		$this->session->set_flashdata('GLOBAL_DEBUG', 'show');
		
		$this->index();
	}
	
	
	
	
	/**
	 * For convenience only. This function should be disabled via config/routes.php
	 * 
	 * Usage: in the browser, type the follow URLs to toggle the display of debug info.
	 * 
	 *		http://localhost/CI_fuzzyBear/index.php/debug/hide
	 */
	public function hide()
	{
		$this->session->set_flashdata('GLOBAL_DEBUG', 'hide');
		
		$this->index();
	}
	
	
	
	
	
	public function index()
	{
		redirect("store", 'refresh'); // show the store page.
	}
	
}

?>
