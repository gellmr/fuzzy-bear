<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class NotImplementedYet extends MY_Controller
{

	
	
	
	/**
	  Default ctor.
	 */
	public function __construct()
	{
		parent::__construct();  // Invoke parent ctor
	}
	
	
	
	
	public function index()
	{
		$data = $this->init_ViewData();
		
		$data['page_title'] = "NotImplementedYet";
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);

		$this->load->view('templates/notImplementedYet', $data);

		$this->load->view('templates/footer');
	}
}


/* End of file notImplementedYet.php */
	