<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MVC Controller - Fuzzy Bear - MY_Controller
 * 
 * This class provides some common methods for accessing the cart.
 */
class MY_Controller extends CI_Controller
{
  protected $GLOBAL_DEBUG = true;
  protected $WITH_XSS_FILTER = true;
  protected $m_min_username_len = 6;
  protected $m_min_name_len = 1;
  protected $m_max_name_len = 20;
  protected $m_max_search_string_len = 30;
  protected $m_phone_min_len = 8;
  protected $m_phone_max_len = 20;
    
  // READ THIS:    http://crackstation.net/hashing-security.htm
  protected $m_pass_min_len = 16; // Rainbow tables that can crack any md5 hash of a password up to 8 characters long exist
  protected $m_pass_max_len = 20;
  protected $m_email_min_len = 5;
  protected $m_email_max_len = 100;
  
  // ADDRESS INPUT FIELD (visual size) and (max length)
  protected $m_address_field_size = '32';
  protected $m_address_min_len = 3;
  protected $m_address_max_len = 200;
  protected $m_smallAddress_field_size = '15'; // used for city/state/postcode/country (visual size)
  
  protected $m_city_max_len = 100;
  protected $m_state_min_len = 1;
  protected $m_state_max_len = 20;
  protected $m_postcode_min_len = 4;
  protected $m_postcode_max_len = 10;
  protected $m_countryOrRegion_min_len = 3;
  protected $m_countryOrRegion_max_len = 100;
  
  protected $m_LOGGED_IN = 'in';
  protected $m_LOGGED_OUT = 'out';
  protected $PATH_TO_IMAGES = "public/images/";
  protected $PRODUCT_THUMBS = '120x120/';
    
  protected $m_transactionType;
  protected $TRANSACTION_TYPE_ALLOW_READ = 'ALLOW_READ';
  protected $TRANSACTION_TYPE_DENY_READ = 'DENY_READ';
  protected $TRANSACTION_TYPE_ALLOW_WRITE = 'ALLOW_WRITE';
  protected $TRANSACTION_TYPE_DENY_WRITE = 'DENY_WRITE';
  
  protected $MAX_QTY_STRING_LENGTH; // (int) - The maximum valid length of the qty input field.

  // Show or hide the debug information.

  private function determine_global_debug()
  {
    /**
     *  Check if we have been redirected from...
     * 
     *  'index.php/debug/show'
     * 
     *   --OR--
     * 
     *  'index.php/debug/hide'
     */
    
    $flashDat = $this->session->flashdata('GLOBAL_DEBUG'); // 'show' --OR-- 'hide'
    
    if (isset($flashDat) && ($flashDat) && ($flashDat == 'show' || $flashDat == 'hide')) {
      // Overwrite the session var.
      $this->session->set_userdata('GLOBAL_DEBUG', $flashDat);
    }
    
    
    // Get the session var.
    $sess_var = $this->session->userdata('GLOBAL_DEBUG');

    if (isset($sess_var) && $sess_var) {
      if ($sess_var == 'show') {
        $this->GLOBAL_DEBUG = true;
      }
      elseif($sess_var == 'hide') {
        $this->GLOBAL_DEBUG = false;
      }
    }
    else
    {
      // use the hard-coded default.
      $this->GLOBAL_DEBUG = false;
    }

    // Now we DEFINITELY have a value. Save it for next time.
    $this->session->set_userdata('GLOBAL_DEBUG', $this->GLOBAL_DEBUG);
  }
  
  // Default ctor.
  public function __construct()
  {
    parent::__construct();    // Invoke parent ctor
    
    // Allows us to use the CI encrypted session cookie.
    $this->load->library('session');

    $this->determine_global_debug();
    $this->load->model('products_model');  // Load MVC model.
    $this->m_transactionType = $this->TRANSACTION_TYPE_DENY_READ; // use default, until we can verify the user credentials.
    $this->MAX_QTY_STRING_LENGTH = 5;

    // Allows us use the img() function.
    // Allows us to call link_tag()
    $this->load->helper('html');

    // Form helper
    $this->load->helper('form');

    // Form validation helper
    $this->load->library('form_validation');

    // URL Helper
    // Allows us to call anchor()
    $this->load->helper('url');

    // Helps us with JSON replies.
    $this->load->library('reply');
        
    // Do we have a cookie value for logged in / out?
    if (!$this->getLoginStatusFromSessCookie()) {
      // No cookie value.
      $this->logout_user_WriteToSessCookie(); // NOT LOGGED IN
    }
  }
  
  /**
   * Get the named post variable. Use CodeIgniter XSS filtering.
   * Note - result will be unreliable if the POST variable stores 'false'
   * !!!! FIX this immediately. !!!
   * @param type $varname the POST var to retrieve.  (eg 'MyVar')
   * @return boolean (false on failure)
   */
  protected function getPostVar_Safely($varname) {
    if (isset($_POST[$varname])) {
      $str = $this->security->xss_clean($_POST[$varname]);
      if (strlen($str) > 0) {
        return $str;
      }
    }
    throw new Exception("missing post var: $varname");
  }
    
  /**
   * THIS SECTION IS INCOMPLETE.
   * THIS SECTION IS INCOMPLETE.
   * THIS SECTION IS INCOMPLETE.
   * 
   * Check the encrypted session cookie to make sure the username and login status are valid.
   * Also make sure the lastactivity timestamp is not too old.
   * @return true if the user's login state is valid.
   */
  protected function user_sessCookie_loginIsValid()
  {
    //return false; // to simulate an expired session cookie.
    $currentUser = $this->getCurrentUNameFromSessCookie();
    $loginStatus = $this->getLoginStatusFromSessCookie();
    if (
      $loginStatus == $this->m_LOGGED_IN
      //&&
      //$this->users_model->userName_and_password_valid($currentUser, $loginStatus)
    )
    {
      return true; // logged in and all good.
    }
    return false; // login state is not valid.
  }
  
  /**
   * Determine what will be displayed in the login panel (top right of page)
   * Usage:    $data['login_panel_contents'] = get_loginPanelContents();
   */
  protected function get_loginPanelContents()
  {
    $loginStatus = $this->getLoginStatusFromSessCookie();
    
    if( isset($loginStatus) && $loginStatus == $this->m_LOGGED_IN ) {
      // We are logged in.
      return array('my_account', 'logout');
    }
    else
    {
      // not logged in.
      return array('forgot_pw', 'register', 'login');
    }
  }
  
  /**
   * Init $data with all the common values we need in our views.
   * 
   * @return array
   */
  protected function init_ViewData()
  {
    // Both $globalShowDebug and $showDebug must be true, or no debug info will appear.
    // You can switch off debug info for the whole site by disabling $globalShowDebug
    // Alternatively, you can keep both enabled, and switch off $showDebug in the calling controller, to deactivate debug info for that controller.
    
    $data['globalShowDebug'] = $this->GLOBAL_DEBUG; // This value is AND'ed together with $showDebug to determine if we will show debug info in the page header div.
    $data['showDebug'] = true; // This can be disabled by the calling controller to switch off debug, even if globalShowDebug is true.
    $data['debugText'] = "";
    $data['NO_USER'] = $this->getCurrentUserName_NONE();
    $data['currentUser'] = $this->getCurrentUNameFromSessCookie();
    $data['max_len_qty_field'] = $this->MAX_QTY_STRING_LENGTH;
    
    $data['cartLinesCount'] = 0;
    $data['cartItemsTotal'] = 0;
    $data['cart'] = $this->getCartFromSessionCookie();
    
    if (isset($data['cart'])) {
      $data['cartLinesCount'] = count($data['cart']);
    }
    
    $data['cartItemsTotal'] = $this->countAllItemsInCart($data['cart']);
    
    $data['js_array'] = $this->getDefault_js_Array(); // the names of javascript files we will incorporate in the html page header.
    $data['css_array'] = $this->getDefault_css_Array(); // the names of css files we will incorporate in the html page header.
    
    $data['login_status'] = $this->getLoginStatusFromSessCookie(); // 'in' or 'out'
    $data['LOGGED_IN'] = $this->m_LOGGED_IN;
    $data['LOGGED_OUT'] = $this->m_LOGGED_OUT;
    
    // Determine what will be displayed in the login panel (top right of page)
    $data['login_panel_contents'] = $this->get_loginPanelContents();
    
    // Min / Max lengths for input fields.
    $data['min_username_len'] = $this->m_min_username_len;
    
    $data['min_name_len'] = $this->m_min_name_len;
    $data['max_name_len'] = $this->m_max_name_len;
    
    $data['max_search_string_len'] = $this->m_max_search_string_len;
    
    $data['phone_min_len'] = $this->m_phone_min_len;
    $data['phone_max_len'] = $this->m_phone_max_len;
    
    $data['pass_min_len'] = $this->m_pass_min_len;
    $data['pass_max_len'] = $this->m_pass_max_len;
    
    $data['email_min_len'] = $this->m_email_min_len;
    $data['email_max_len'] = $this->m_email_max_len;
    
    
    $data['address_max_len'] = $this->m_address_max_len;

    $data['address_field_size'] = $this->m_address_field_size;

    $data['m_smallAddress_field_size'] = $this->m_smallAddress_field_size;
        
    $data['city_max_len'] = $this->m_city_max_len;

    $data['state_max_len'] = $this->m_state_max_len;

    $data['postcode_max_len'] = $this->m_postcode_max_len;

    $data['countryOrRegion_max_len'] = $this->m_countryOrRegion_max_len;

    $data['PATH_TO_IMAGES'] = $this->PATH_TO_IMAGES;
    $data['PRODUCT_THUMBS'] = $this->PRODUCT_THUMBS;
    
    return $data;
  }

  /**
   * Try to retrieve the named post variable.
   * 
   * @param string $fieldId The name of the POST var we want.
   * @param string $dbaseField The database field to use if no POST var is available.
   * @return string The POST var, or the database field if no POST var was available, or an empty string if nothing was available.
   */
  protected function getPostVar_orDataBaseField_orDefault($fieldId, &$dbaseField)
  {
    if ($this->m_transactionType == $this->TRANSACTION_TYPE_ALLOW_WRITE)
    {
      // There are POST vars.
      // This is a form submission.
      // Write the POST vars to the database.
      $val = $this->input->post($fieldId, $this->WITH_XSS_FILTER); // returns false if the POST var is not available.

      if (! $val) {
        // There is no post value (happens when user sends an empty string, or does not send anything.)
        $val = ''; // The form will display an empty string.
      }
      
      // Overwrite the database value (happens later on)
      $dbaseField = $val; // This will be a value, or an empty string (if the user wants to write an empty string, eg shipping address line 2)
      return $val;
    }
    elseif($this->m_transactionType == $this->TRANSACTION_TYPE_ALLOW_READ)
    {
      // There are no POST vars.
      // The user is requesting the form for the first time.
      // Use the database record.
      $val = $dbaseField;
      
      // If there is no value, return an empty string.
      if ( ( is_array($val) && count($val) == 0 ) || strlen($val) == 0 ) {
        $val = ''; // no database field was available. The form will display an empty string.
      }
      return $val;
    }
    else
    {
      // Read / Write access is denied.
      return ''; // The form will display an empty string.
    }
  }
    
  /**
   * @return object user_record (or an unset var (if record not found -OR- user not logged in )
   * 
   * Usage:
   * 
   *    $user_record = $this->get_user_record();
   * 
   *    $user_record->UserName    = "gelly11";
   *    $user_record->FirstName   = "mike";
   *    $user_record->LastName    = "gell";
   *    $user_record->Email     = "mike@gell.com";
   *    $user_record->MobilePhone = "04 2046 3564";
   *    $user_record->Privilege   = "Customer";
   */
  protected function get_user_record()
  {
    // Look up the user record.
    $uname = $this->getCurrentUNameFromSessCookie();  // Get the user name from the session cookie.
    $user_record = null;
    
    if
    (
      // If the user is logged in, and their username has a value...
      $this->getLoginStatusFromSessCookie() == $this->m_LOGGED_IN
      &&
      $uname != $this->getCurrentUserName_NONE()
    )
    {
      // Look up the record for that user name.
      $user_record = $this->users_model->getUserByUNAME($uname);
    }

    return $user_record; // object or unset
  }
  
  /**
   * Get some general debug info. Call this just prior to sending data to the view.
   */
  protected function getDebugStatement()
  {
    $debugString = '';
    $debugString .= '<br />DEBUG STATEMENT<br />';
    $debugString .= "keyword:         " . $this->session->userdata('store_search_keyword') . '<br />'; // eg "wire"
    $debugString .= "currentUser:     " . $this->getCurrentUNameFromSessCookie() . '<br />';
    $debugString .= "login_status:    " . $this->getLoginStatusFromSessCookie() . '<br />';
    $debugString .= 'currentPage:     ' . $this->session->userdata('store_current_page') . '<br />'; // eg 2
    $debugString .= 'productsPerPage: ' . $this->session->userdata('store_products_per_page') . '<br />'; // eg 5
    
    $current_page = $this->session->userdata('store_current_page');       // eg 3
    $products_per_page = $this->session->userdata('store_products_per_page'); // eg 5
    $offset = ($current_page * $products_per_page) - $products_per_page; // eg (3 * 5) - 5 == 10
    $limit = $products_per_page; // eg 5
    
    $debugString .= 'offset:    ' . $offset . '<br />'; // eg 10
    $debugString .= 'limit:     ' . $limit . '<br />';    // eg 5
    
    // $debugString .= '<br />';
    return $debugString;
  }
  
  /**
   * Get the default javascript filenames to be included in the page header.
   * 
   * @return array
   */
  protected function getDefault_js_Array()
  {
    $jsArray = array(); // javascript files to include in the page header.
    array_push($jsArray, "jquery-1.8.3.min.js");
    array_push($jsArray, "bootstrap.min.js");
    array_push($jsArray, "siteEventHandlers.js");
    array_push($jsArray, "validationMethods.js");
    array_push($jsArray, "utilityMethods.js");
    return $jsArray;
  }
  
  /**
   * Get the default css filenames to be included in the page header.
   * 
   * @return array
   */
  protected function getDefault_css_Array()
  {
    $cssArray = array();
    array_push($cssArray, "bootstrap.min.css");
    array_push($cssArray, "site.css");
    return $cssArray;
  }
    
  /**
   * Get the total number of items in the cart.
   * 
   * If there are 2 cart lines: (oranges 4 , apples 7) ...then we have 11 items.
   * 
   * @param array $cart
   * @return int
   */
  protected function countAllItemsInCart($cart)
  {
    $itemCount = 0;
    
    foreach ($cart as $cartItem)
    {
      $itemCount = $itemCount + $cartItem['qty'];
    }
    
    return $itemCount;
  }
  
  
  
  
  


  /**
   * Given the field name, eg "qty_input_1"  extract the product ID, eg "1"
   * This function does not perform any sanitization.
   * 
   * @param type $fieldName
   * @return type
   */
  protected function stripProductIdFromFieldName($fieldName)
  {
    $splitRes = explode("_", $fieldName); // Split, eg "qty","input","1"

    $fieldName = array_pop($splitRes);    // The number only eg "1"
    
    return $fieldName;
  }

  

  
  
  /**
   * Given the cart, calculate the grand total of all products in the cart.
   * 
   * @param array $cart
   * @return int
   */
  protected function calculateGrandTotal($cart)
  {
    $tot = 0;
    
    if (!isset($cart) || $cart == false)
    {
      return $tot;
    }
    
    foreach ($cart as $cartItem)
    {
      $tot = $tot + $cartItem['qty'] * $cartItem['unitPrice'];
    }
    return $tot;
  }
  
  
  
  
  
  /**
   * Given the session cart, and a product ID, calculate the current subtotal cost of the ordered qty of that product.
   * Eg 3 apples @30 dollars each == 90 dollars.
   * 
   * @param array $cart
   * @param int $productID
   * @return number
   */
  protected function calculateSubTotalForProduct($cart, $productID)
  {
    $tot = 0;
    
    if (!isset($cart) || $cart == false)
    {
      return $tot;
    }
    
    $product = $cart[$productID];
    
    $tot = $product['unitPrice'] * $product['qty'];
    
    return $tot;
  }
  
  
  
  
  
  
  
  /**
   * Given the ID of a product in the cart, remove that product completely.
   * A product in the cart will have a known quantity, eg 3 apples.
   * When you REMOVE THE PRODUCT, all 3 apples are removed.
   * 
   * @param int $productID
   * @param array $cart The cart to be modified.
   * @return array The modified cart.
   */
  protected function removeProductFromCart($productID, $cart)
  {
    if ($cart == false)
    {
      // all done.
      
      return $cart;
    }
    
    // The cart is an array.
    // 
    // $cartItemN = $session_cart[n];
    //  
    //    $cartItemN['ProductID'];    //   => 333
    //    $cartItemN['ProductName'];    //   => "Apple"
    //    $cartItemN['qty'];        //   => 3
    //    $cartItemN['unitPrice'];    //   => 30
    
    unset($cart[$productID]); // all 3 apples are gone.
    
    return $cart;
  }
  



  
  /**
   * Get a string representing no current user.
   * 
   * @return string "none"
   */
  protected function getCurrentUserName_NONE()
  {
    return "none";
  }
  
  
  
  
  /**
   * Get the current user from the session cookie.
   * 
   * @return string value (or) $this->getCurrentUserName_NONE()
   */
  protected function getCurrentUNameFromSessCookie()
  {
    $user = $this->session->userdata('current_user'); // not sure if this automatically uses xss_clean()
    
    if (! $user )
    {
      $user = $this->getCurrentUserName_NONE();
    }
    
    return $user;
  }
  
  
  
  
  /**
   * Save the given user name to the session cookie.
   */
  protected function saveCurrentUNameToSessCookie($uname)
  {
    $this->session->set_userdata('current_user', $uname);
  }
  
  
  
  
  /**
   * Clear the cart (in session cookie)
   */
  protected function clearCart()
  {
    $session_cart = array();
    
    $this->session->set_userdata('cart', $session_cart);
  }
  
  

  
  
  
  /**
   * Get the cart from the session cookie. Create if necessary.
   * 
   * @return array The cart
   */
  protected function getCartFromSessionCookie()
  {
    $session_cart = $this->session->userdata('cart');

    if ($session_cart == false)
    {
      // Init cart.

      $session_cart = array();
    }

    return $session_cart;
  }
  
  
  
  

  /**
   * Save the cart to the session cookie.
   * 
   * @param array $session_cart (Cart to be Saved)
   */
  protected function saveCartToSessionCookie($session_cart)
  {
    $this->session->set_userdata('cart', $session_cart);
  }

  
  
  
  
  
  /**
   * Display the given cart array (as a debug string)
   * 
   * @param array $cart (The cart to be displayed.) If omitted, we just get the cart from the current session cookie.
   * @return string
   */
  protected function getCartAsDebugString($cart)
  {
    $str = "";

    if (!isset($cart))
    {
      $cart = $this->getCartFromSessionCookie();
    }

    foreach ($cart as $key => $cartItem)
    {
      $str .= "ProductID:   " . $cartItem['ProductID'] . "<br />";
      $str .= "ProductName: " . $cartItem['ProductName'] . "<br />";
      $str .= "qty:         " . $cartItem['qty'] . "<br />";
      $str .= "unitPrice:   " . $cartItem['unitPrice'] . "<br />";
      $str .= "<br />";

//      foreach ($value as  $key2 =>  $val2)
//      {
//        $str  .=  "(product $key) : qty $value <br />";
//      }
    }
    return $str;
  }

  
  
  
  /**
   * Save the given (customer) email address to the session cookie.
   * 
   * If the given string is not a valid email, we save an empty string instead.
   * 
   * @param string $email (email address to be Saved)
   */
  protected function save_CustomerEmailAddress_ToSessionCookie($email)
  {
    if (valid_email($email))
    {
      $this->session->set_userdata('customer_email_address', $email);
    }
    else
    {
      $this->session->set_userdata('customer_email_address', '');
    }
  }
  
  
  
  
  /**
   * Load the Email Address (of the current customer) from the session cookie.
   * 
   * @return string
   */
  protected function load_CustomerEmailAddress_FromSessionCookie()
  {
    return $this->session->userdata('customer_email_address');
  }
  
  
  
  
  
  
  
  
  /**
   * Ensure the given var is a sane positive integer
   * @param int $integerVal
   * @param int $maxStringLen
   * @return int
   */
  protected function sanitizeInteger($integerVal, $maxStringLen)
  {
    // Your sql attack is no laugh at me!
    
    $len = strlen($integerVal); // eg 5 (attacker might send longer, eg 7)

    // Enforce maximum string length.
    
    if ($len > $maxStringLen)
    {
      // left truncation, eg "1234500" becomes "34500"

      $integerVal = substr
      (
        $integerVal,
        $len - $maxStringLen, // start, eg 2
        $maxStringLen     // length eg 5
      );
    }

    // Enforce positive value.
    
    $integerVal = abs(intval($integerVal));
    
    return $integerVal;
  }
  
  
  
  
  
  /**
   * The user has sent us an AJAX (POST) message to adjust the qty of an item
   */
  public function updateQty()
  {
    $this->update_cart_item_quantity();
  }
  
  
  
  
  
  /**
   * The user has sent us an AJAX (POST) message to adjust the qty of an item
   */
  private function update_cart_item_quantity()
  {
    Reply::init(); // For sending ajax reply (json)
    
    // Validation for ID and Quantity integer values...
    // Make sure its a positive integer.
    // eg "-22a" becomes "22"
    // eg "1.9" becomes "1"
    // eg "1234500" becomes "34500"
    
    // product id
    $cartItemId = $this->input->post('productID');  // The product ID, eg "qty_input_1"
    
    // $cartItemId = "';";    // simulate a possible attack.
    $cartItemId = $this->stripProductIdFromFieldName($cartItemId); // eg "1"
    $cartItemId = $this->sanitizeInteger($cartItemId, $this->MAX_QTY_STRING_LENGTH);

    Reply::setJsonArg("productID", $cartItemId); // Don't do this. Reflection is bad. Just send "ok" / "failed"
        
    // new quantity
    $newQty = $this->input->post('qty');      // get the sanitized string.
    $newQty = $this->sanitizeInteger($newQty, $this->MAX_QTY_STRING_LENGTH);
    
    Reply::setJsonArg("newQty", $newQty); // Don't do this. Reflection is bad. Just send "ok" / "failed"

    // Here we assume CI has properly sanitized $cartItemId and $newQty
    $product = false;
    $cartEmpty = "false";
    $newGrandTot = 0;
    $newSubTot = 0;
    $product = $this->products_model->get_productById($cartItemId);
    $updatedCartSummaryHTML = "Summary goes here";
    
    if ($product === false) {
      $cartDebugString = "Something went horribly wrong.";
    }
    else
    {
      // Reply::writeLine_debug("<p>" . join("<br />", $product) . "</p>");
      $session_cart = $this->getCartFromSessionCookie(); // Get or create the cart.
      $key = 'id_' . $cartItemId;

      if (isset($session_cart[$key]) && is_array($session_cart[$key])) {
        $cartItem = $session_cart[$key]; // get existing cart item.
      }
      else
      {
        $cartItem = array(); // create new cart item.
      }

      //    $cartItem['ProductID'];     //   => 333
      //    $cartItem['ProductName'];   //   => "Apple"
      //    $cartItem['qty'];       //   => 3
      //    $cartItem['unitPrice'];     //   => 30

      $cartItem['ProductID']   = $cartItemId;
      $cartItem['ProductName'] = $product['ProductName'];
      $cartItem['qty']     = $newQty;
      $cartItem['unitPrice']   = $product['UnitPrice'];
      $cartItem['imgURL']    = $product['imgURL'];
      
      $session_cart[$cartItemId] = $cartItem;   // Update the cart.
      $this->saveCartToSessionCookie($session_cart);  // Save cart to session cookie.
      $cartDebugString = $this->getCartAsDebugString($session_cart);
      $newGrandTot = $this->calculateGrandTotal($session_cart);
      $newSubTot = $this->calculateSubTotalForProduct($session_cart, $cartItemId);
      $lines = count($session_cart);

      if ($lines == 0) {
        $cartEmpty = "true";
      }
      
      $items = $this->countAllItemsInCart($session_cart);
      $updatedCartSummaryHTML = "<h3>Your Cart</h3>($lines Product Lines, $items items in total)";
    }
    
    Reply::writeLine_debug("<p>" . $cartDebugString . "</p>");
    Reply::setJsonArg("newGrandTotal", $newGrandTot);
    Reply::setJsonArg("newSubTotal", $newSubTot);
    Reply::setJsonArg("subTot_id_toChange", "#subTot_prod$cartItemId");
    Reply::setJsonArg("updatedCartSummary", $updatedCartSummaryHTML);
    Reply::setJsonArg("cartIsEmpty", $cartEmpty);

    echo Reply::value();
  }
  
  
  
  
  
  /**
   * Retrieve the current user's session cookie status for "logged in"
   * 
   * @return (string) value either ($this->m_LOGGED_IN) or ($this->m_LOGGED_OUT)
   */
  protected function getLoginStatusFromSessCookie()
  {
    return $this->session->userdata('logged_in');
  }
  
  
  /**
   * Set the current user's session cookie status to ($this->m_LOGGED_IN)
   */
  protected function login_user_WriteToSessCookie()
  {
    $this->session->set_userdata('logged_in', $this->m_LOGGED_IN);
  }
  
  
  /**
   * Set the current user's session cookie status to ($this->m_LOGGED_OUT)
   * 
   * Also clears the current username from the session cookie.
   */
  protected function logout_user_WriteToSessCookie()
  {
    $this->clearCart();
    
    $this->session->set_userdata('logged_in', $this->m_LOGGED_OUT);
    
    $this->saveCurrentUNameToSessCookie($this->getCurrentUserName_NONE());
  }
}
?>

