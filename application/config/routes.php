<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|



$route['(:any)'] = 'pages/view/$1';



$route['news/(:any)'] = 'news/view/$1';

$route['news'] = 'news';

$route['(:any)'] = 'pages/view/$1';
*/



		/*
		Set the default controller.
		
		
		
			If the client's browser ask for "http://localhost/CodeIgniter_2.1.3/index.php"
			Then we call view() in
				C:/wamp/www/CodeIgniter_2.1.3/application/controllers/pages.php
		
		$route['default_controller'] = 'pages/view';

		
		
			If the client's browser ask for "http://localhost/CodeIgniter_2.1.3/index.php"
			Then we call index() in
				C:/wamp/www/CodeIgniter_2.1.3/application/controllers/welcome.php
		
		$route['default_controller'] = 'welcome';
		*/
	
	///// $route['default_controller'] = 'welcome';		// = 'controllerClassName/functionToCall'

	
	//$route['news/create'] = 'news/create';		// For creating news items.
	
	//$route['news/(:any)'] = 'news/view/$1';		// For viewing news items.
	
	

	
	
	
	// Login

	$route['login'] = 'login';
	

	
	// User wants to log out

	$route['logout'] = 'logout';


	
	// myAccount details page.

	$route['myAccount'] = 'myAccount';
	
	
	
	
	// Forgot Password

	$route['forgotPassword'] = 'forgotPassword';
	
	$route['sent_password_reset'] = 'forgotPassword/sent_password_reset';
	
	$route['forgotPassword/sent_password_reset'] = 'forgotPassword/sent_password_reset';
	
	
					
	
	// Register

	$route['register'] = 'register';

	$route['register/check_nameString_Ok'] = 'register/check_nameString_Ok';
	
	$route['register/checkUnameAvail'] = 'register/checkUnameAvail';

	$route['register/checkEmailAvail'] = 'register/checkEmailAvail';
	

	
	// Checkout
	
	$route['checkout/shippingMethod'] = 'checkout/shippingMethod';
	
	$route['checkout/paymentMethod'] = 'checkout/paymentMethod';
	
	$route['checkout/confirmOrderDetails'] = 'checkout/confirmOrderDetails';
	
	$route['checkout/success'] = 'checkout/success';
	
	$route['checkout'] = 'checkout';
	
	
	
	

	// DEBUG COMMANDS (These would be removed in a real website!!!)

	$route['debug/show'] = 'debug/show';
	
	$route['debug/hide'] = 'debug/hide';
	
	
	
	
	
	// Store
	
	$route['store/updateQty'] = 'store/updateQty';
	
	$route['store/search'] = 'store/search';
	
	$route['store/(:num)'] = 'store/index/$1';
	
	$route['store/productsPerPage/(:num)'] = 'store/productsPerPage/$1';
	
	$route['store/pagination/left'] = 'store/pagination/left';
	
	$route['store/pagination/right'] = 'store/pagination/right';
	
	$route['store/pagination/reset'] = 'store/pagination/reset';
	
	$route['store'] = 'store';
	
	
	// Cart
	
	$route['cart/removeItemById'] = 'cart/removeItemById';
	
	$route['cart/updateQty'] = 'cart/updateQty';
	
	$route['cart'] = 'cart';
	

	
	// Placeholder page for features not yet implemented.
	
	$route['notImplementedYet'] = 'notImplementedYet';
	
	
	
	// Default
	
	$route['(:any)'] = 'store/$1';
	
	$route['default_controller'] = 'store';
	
	
//$route['404_override'] = '';



/* End of file routes.php */
/* Location: ./application/config/routes.php */