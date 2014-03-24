<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
  MVC Controller - Fuzzy Bear - Checkout
 */
class Checkout extends MY_Controller
{

	protected $CHKOUT_STG_1_SHIPPING		= 'shippingMethod';
	protected $CHKOUT_STG_2_PAYMENT			= 'paymentMethod';
	protected $CHKOUT_STG_3_CONFIRM_ORDER	= 'confirmOrderDetails';
	protected $CHKOUT_STG_4_SUCCESS			= 'success';
	
	protected $m_data = array();
	
	private $m_userRecord;
	
	
	/**
	 *	Default ctor.
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
		
		$this->form_validation->set_rules
		(
			'shippingMethodRadioGroup', 'Shipping Method',

			"required"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_line1', 'Street Address Line 1',

			"trim|required|min_length[$this->m_address_min_len]|max_length[$this->m_address_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_line2', 'Street Address Line 2',

			"trim|min_length[$this->m_address_min_len]|max_length[$this->m_address_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_city', 'Street Address - City',

			"trim|required|min_length[$this->m_address_min_len]|max_length[$this->m_city_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_state', 'Street Address - State',

			"trim|required|min_length[$this->m_state_min_len]|max_length[$this->m_state_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_postcode', 'Street Address - Post Code',

			"trim|required|min_length[$this->m_postcode_min_len]|max_length[$this->m_postcode_max_len]|xss_clean"
		);
		
		$this->form_validation->set_rules
		(
			'input_shippingAddress_countryOrRegion', 'Street Address - Country',

			"trim|required|min_length[$this->m_countryOrRegion_min_len]|max_length[$this->m_countryOrRegion_max_len]|xss_clean"
		);
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 *	Called when the user navigates to...
	 * 
	 *	http://localhost/CodeIgniter_2.1.3/index.php/checkout
	 */
	public function index()
	{
		$this->servePage($this->CHKOUT_STG_1_SHIPPING);
	}
	
	
	
	/**
	 *	Called when the user navigates to...
	 * 
	 *	http://localhost/CodeIgniter_2.1.3/index.php/checkout/shippingMethod
	 */
	public function shippingMethod()
	{
		$this->servePage($this->CHKOUT_STG_1_SHIPPING);
	}
	
	
	/**
	 *	Called when the user navigates to...
	 * 
	 *	http://localhost/CodeIgniter_2.1.3/index.php/checkout/paymentMethod
	 */
	public function paymentMethod()
	{
		// User is trying to submit the shipping details.
		
		if ($this->form_validation->run())
		{
			// Shipping details accepted.
			
			$this->servePage($this->CHKOUT_STG_2_PAYMENT);
		}
		else
		{
			// Sorry, please enter your shipping details again.
			
			//$this->servePage($this->CHKOUT_STG_1_SHIPPING);
			
			$this->session->set_flashdata('errors', validation_errors()); // store errors so we can display them after the redirect.
			
			redirect("checkout/shippingMethod", 'refresh'); // results in a new server request?
		}
	}
	
	
	/**
	 *	Called when the user navigates to...
	 * 
	 *	http://localhost/CodeIgniter_2.1.3/index.php/checkout/confirmOrderDetails
	 */
	public function confirmOrderDetails()
	{
		$this->servePage($this->CHKOUT_STG_3_CONFIRM_ORDER);
	}
	
	
	/**
	 *	Called when the user navigates to...
	 * 
	 *	http://localhost/CodeIgniter_2.1.3/index.php/checkout/success
	 */
	public function success()
	{
		$this->servePage($this->CHKOUT_STG_4_SUCCESS);
	}
	
	
	
	
	
	
	/**
	 * @return string "ok" if the user has cart contents. "empty" if the user has an empty cart.
	 */
	private function check_cart_ok()
	{
		$data = $this->init_ViewData();
		
		if (! isset($data['cart']) || count($data['cart']) == 0)
		{
			return 'empty';
		}
		
		return 'ok';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	private function get_script_for_stage($stage)
	{
		if($stage == $this->CHKOUT_STG_1_SHIPPING)
		{
			return "checkout_shipping.js";
		}
		elseif($stage == $this->CHKOUT_STG_2_PAYMENT)
		{
			return "checkout_payment.js";
		}
		elseif($stage == $this->CHKOUT_STG_3_CONFIRM_ORDER)
		{
			return "checkout_confirmOrderDetails.js";
		}
	}
	
	
	
	
	
	
	
	
	public function pre_checkout_logic($current_stage)
	{
		$data = $this->init_ViewData();
		
		array_push($data['css_array'], "checkout.css");
		
		array_push($data['js_array'], "cartEventHandlers.js");
		
		array_push($data['js_array'], "checkout.js");
		
		array_push($data['js_array'], $this->get_script_for_stage($current_stage));
		
		
		$data['showDebug'] = true;
		
		
		if (empty($_POST))
		{
			// Post vars are empty. Allow the user to read
			
			$this->m_transactionType = $this->TRANSACTION_TYPE_ALLOW_READ;
		}
		else
		{
			
			// There are post vars. Allow the user to write to the database.
			
			$this->m_transactionType = $this->TRANSACTION_TYPE_ALLOW_WRITE;
		}
		
		
		
		$data['mode'] = 'checkout'; // ("detailed" or "summary" or "checkout")
		
		$data['page_title'] = "Checkout";
		
		
		$data['grandTot'] = $this->calculateGrandTotal($data['cart']);
		
		$data['cartHtml'] = $this->load->view('cart/index', $data, true);
		
		

		// Variable to help us know if the cart is empty.
		
		unset($data['cartEmpty']);
		
		if (! isset($data['cart']) || count($data['cart']) == 0)
		{
			$data['cartEmpty'] = 'empty';
		}
		
		
		// Init the checkout process, if necessary.
		
		$data['checkout_stage'] = $current_stage;

		$data['CHKOUT_STG_1_SHIPPING'] = $this->CHKOUT_STG_1_SHIPPING;

		$data['CHKOUT_STG_2_PAYMENT'] = $this->CHKOUT_STG_2_PAYMENT;

		$data['CHKOUT_STG_3_CONFIRM_ORDER'] = $this->CHKOUT_STG_3_CONFIRM_ORDER;

		$data['CHKOUT_STG_4_SUCCESS'] = $this->CHKOUT_STG_4_SUCCESS;
		
		
		// Get the form values. Update our user object if there is new post data. Use existing database values if there is no post data. Use empty strings if there is absolutely no data available.
		
		$fieldId = 'input_shippingAddress_line1';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_line1);
		
		$fieldId = 'input_shippingAddress_line2';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_line2);
		
		$fieldId = 'input_shippingAddress_city';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_city);
		
		$fieldId = 'input_shippingAddress_state';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_state);
		
		$fieldId = 'input_shippingAddress_postcode';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_postcode);
		
		$fieldId = 'input_shippingAddress_countryOrRegion';
		$data[$fieldId] = $this->getPostVar_orDataBaseField_orDefault($fieldId, $this->m_userRecord->ShippingAddress_countryOrRegion);
		
		// Write user record back to the database.
		
		if (isset($this->m_userRecord->UserName))
		{
			$this->users_model->updateUserRecord($this->m_userRecord);
		}
		
		
		$data['debugText'] .= $this->getDebugStatement();
		
		$this->m_data = $data; // save to member var.
		
		return $data;
	}
	
	
	
	
	
	
	
	
	public function servePage($desired_stage)
	{
		$got_cart = $this->check_cart_ok();
		
		$user_record = $this->get_user_record();
		
		
		
		//if ($got_cart == 'ok' && isset($user_record))
		
		if(isset($user_record) && $got_cart == 'ok')
		{
			// Got cart and user record.
			
			// User is logged in.
			
			$this->m_userRecord = $user_record;
			
			
			$data = $this->pre_checkout_logic($desired_stage); // also sets $this->m_data
			
			$data['userRecord'] = $user_record;
			
			$data['validationErrors'] = $this->session->flashdata('errors');
			

			$this->load->view('templates/header', $data);

			$this->load->view('templates/ribbon', $data);

			$data['checkoutNavHtml'] = $this->load->view('checkout/navigation', $data, true);
			
			$this->load->view('checkout/'.$data['checkout_stage'], $data);
			
			//$this->load->view('templates/footer'); // This has been REMOVED because it displays in the wrong place.
		}
		else
		{
			// User record not found -OR- user not logged in.
			
			
			// Go back to stage 1.
			
			$desired_stage = $this->CHKOUT_STG_1_SHIPPING;
			
			$data = $this->pre_checkout_logic($desired_stage); // also sets $this->m_data
			
			
			
			if ($got_cart == 'empty')
			{
				// CART IS EMPTY. CANNOT CHECKOUT.

				$this->load->view('templates/header', $data);

				$this->load->view('templates/ribbon', $data);

				$this->load->view('checkout/cartIsEmpty', $data);

				$this->load->view('templates/footer');
				
			}
			else
			{
				// got cart. Need to log in.
				
				$data['mode'] = 'summary';
				
				if (!isset($user_record))
				{
					$data['debugText'] .= 'NO USER RECORD<br />';
				}
				
				
		
				array_push($data['css_array'], "checkout_pleaseLoginFirst.css");
		
				$this->load->view('templates/header', $data);

				$this->load->view('templates/ribbon', $data);

				$this->load->view('checkout/pleaseLoginFirst', $data);

				$this->load->view('templates/footer');
			}
		}
	}
	
	
	
}