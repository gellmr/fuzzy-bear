<?php if (!defined('BASEPATH')) exit('No direct script access allowed');




/**
 * MVC Controller - Fuzzy Bear - Cart
 */
class Cart extends MY_Controller
{

	/**
	 * Default ctor.
	 */
	public function __construct()
	{
		parent::__construct();  // Invoke parent ctor
	}
	
	
	
	
	

	/**
	 * Called when the user navigates to...
	 * http://localhost/CodeIgniter_2.1.3/index.php/cart
	 */
	public function index()
	{
		$data = $this->init_ViewData();

		array_push($data['js_array'], "cartEventHandlers.js");
		
		array_push($data['css_array'], "cart.css");
		
		$data['grandTot'] = $this->calculateGrandTotal($data['cart']);
		
		$data['mode'] = 'detail'; // (detailed view of cart) OR (summary view of cart)
		
		$data['page_title'] = 'My Cart';

		$data['debugText'] .= $this->getDebugStatement();
		
		$data['showDebug'] = true;
		
		$this->load->view('templates/header', $data);

		$this->load->view('templates/ribbon', $data);

		$this->load->view('cart/index', $data);

		$this->load->view('templates/footer');
	}

	
	
	
	/**
	 * The user has sent us an AJAX (POST) message to remove an item from the cart.
	 */
	public function removeItemById()
	{
		$cartItemId = $this->input->post('productID');	// The product ID, eg "qty_input_1"

		$cartItemId = $this->stripProductIdFromFieldName($cartItemId); // eg "1"
		
		$session_cart = $this->getCartFromSessionCookie(); // get the cart from the encrypted session cookie.
		
		$session_cart = $this->removeProductFromCart($cartItemId, $session_cart); // modifies the cart passed in.
		
		$this->saveCartToSessionCookie($session_cart); // save the cart to the encrypted session cookie.
		
		$newTot = $this->calculateGrandTotal($session_cart); // Calculate the updated grand total.
		
		$id_DOM_element_to_remove = "#cart_table_row_prod$cartItemId";
		
		$lines = count($session_cart);
		
		$items = $this->countAllItemsInCart($session_cart);
		
		$empty = "false";
		
		$updatedCartSummaryHTML = "<h3>Your Cart</h3>($lines Product Lines, $items items in total)";
		
		if ($lines == 0)
		{
			$updatedCartSummaryHTML = "<h3>Your cart is empty.</h3>";
			
			$empty = "true";
		}
		
		Reply::init(); // For sending ajax reply (json)
		
		Reply::writeLine_debug("<p>result: success (remove $id_DOM_element_to_remove)</p>");

		Reply::setJsonArg("result", "success");
		
		Reply::setJsonArg("newGrandTotal", $newTot);
		
		Reply::setJsonArg("cartIsEmpty", $empty);
		
		Reply::setJsonArg("updatedCartSummary", $updatedCartSummaryHTML);
		
		Reply::setJsonArg("row_to_remove", $id_DOM_element_to_remove);

		echo Reply::value();
	}
	
	
	
	
}