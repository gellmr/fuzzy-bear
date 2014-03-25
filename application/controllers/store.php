<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


// WINDOWS uses back-slashes
//include_once getcwd() . '\application\libraries\util.php'; // not sure if this is a good way to include my util class.


// UNIX uses forward-slashes
include_once getcwd() . '/application/libraries/util.php'; // not sure if this is a good way to include my util class.




/**
 * MVC Controller - Fuzzy Bear - Store
 * 
 * This class uses the products_model to retreive records from the database.
 */
class Store extends MY_Controller
{
  
  
  
  // Products per page
  
  
  private $PRODUCTS_PER_PAGE_FEW = 3;   // option (Products per page)
  
  private $PRODUCTS_PER_PAGE_MID = 5;   // option (Products per page)
  
  private $PRODUCTS_PER_PAGE_MAX = 10;  // option (Products per page)
  
  private $DEFAULT_PRODUCTS_PER_PAGE = 5; // default option (Products per page)
  
  
  
  
  
  // Pagination
  
  
  
  private $m_memberDebugString = '';
  
  private $FILTER_AGAINST_XSS = true;
  
  
  
  // VERY IMPORTANT -> see documentation/store_variables_dependencies.jpg
  
  private $m_keyword;
  private $m_productsPerPage;
  private $m_productCountTotal;
  private $m_paginationLinksOnScreen = 5; // The number of pagination buttons we can fit on the screen.
  private $m_maxPage;
  private $m_shift;
  private $m_pagination_first = 1;      // The starting index of the pagination buttons, eg page 1
  private $m_pagination_last = 5;     // The last index of the pagination buttons, eg 5
  private $m_currentPage;
  private $m_limit;
  private $m_offset;
  private $m_matchingProducts;
  private $m_productCount_thisPage;   // Result of query by keyword, with current offset and limit.  Eg, get the first page of records matching "wire"
  
  
  
  /**
   * Default ctor.
   */
  public function __construct()
  {
    parent::__construct();   // Invoke parent ctor
    
    $this->load->helper('security');
    
    $this->DEFAULT_PRODUCTS_PER_PAGE = $this->PRODUCTS_PER_PAGE_MID;
    
    $this->INCART_ICON_WIDTH = '60';
    
    
    
    //    First, we initialise all these variable in the constructor.
    
    //    (Later during execution) if we change one, then we need to RECALCULATE all the variables that follow current page.
    
    //    eg current page is changed during the execution of $this->index()
    
    //    When this happens, we need to recalculate the value of (limit, offset, matching products, product count this page)
    
    //    For a diagram of these dependencies, see:  documentation/store_variables_dependencies.jpg
    
    //    It is essential that we calculate them in the correct order.
    
    
    
    $this->m_keyword = $this->get_keyword();
    
    $this->m_productsPerPage = $this->get_productsPerPage();
    
    $this->m_productCountTotal = $this->products_model->get_count($this->m_keyword);
    
    $this->m_maxPage = $this->calculate_maxPage();
    
    $this->get_pagination_vars();
    
    $this->m_currentPage = $this->get_currentPage();
    
    $this->m_limit = $this->calculate_limit();
    
    $this->m_offset = $this->calculate_offset();
    
    $this->m_matchingProducts = $this->get_matchingProducts();
    
    $this->m_productCount_thisPage = $this->calculate_productCountThisPage();
    
    
    // POST and session data has been loaded.
  }

  
  
  
  
  
  
  
  
  
  
  /**
   * Check if there is a POST var.
   * 
   * If so, overwrite the session var.
   * 
   * Otherwise, use the existing session var.
   * 
   * Otherwise, use the default keyword.
   * 
   * @return string
   */
  private function get_keyword()
  {
    // Try the POST var.
    
    $is_new_keyword = $this->input->post('is_new_keyword', $this->FILTER_AGAINST_XSS);  // Eg, 'yes'   or  "yes'; DROP TABLE as2_user--"
    
    if ($is_new_keyword == 'yes')
    {
      $keyword = $this->input->post('keyword', $this->FILTER_AGAINST_XSS);  // Eg, 'alligator clips'   or  "apple'; DROP TABLE as2_user--"
    }
    
    
    if (!isset($keyword)) // The POST var may give us an empty search string. This is valid.
    {
      // no POST var is available.
      
      // Try the session var.
      
      $keyword = $this->session->userdata('store_search_keyword');
      
      if (!isset($keyword)) // The session var may contain an empty search string. This is valid.
      {
        // no session var is available.

        // Use default value.

        $keyword = ''; // empty search string... gives us unfiltered search results.
      }
    }
    
    // From here, we assume $keyword has been sanitized.

    $this->session->set_userdata('store_search_keyword', $keyword); // save the keyword to the user session.
    
    $this->m_keyword = $keyword;
    
    return $keyword;
  }
  
  
  
  
  
  
  /**
   * Get the session var.
   * 
   * Otherwise, use the default value.
   * 
   * @return integer
   */
  private function get_productsPerPage()
  {
    // Try the session var.

    $products_per_page = $this->session->userdata('store_products_per_page');

    if (!isset($products_per_page) || ! $products_per_page)
    {
      // no session var is available.

      // Use default value.

      $products_per_page = $this->DEFAULT_PRODUCTS_PER_PAGE;
    }
      
    $this->session->set_userdata('store_products_per_page', $products_per_page);
    
    $this->m_productsPerPage = $products_per_page;
    
    return $products_per_page;
  }
  
  
  
  
  
  
  /**
   * Calculate the max page number
   * 
   * @return integer
   */
  private function calculate_maxPage()
  {
    // Init max_page
    
    $this->m_maxPage = ceil($this->m_productCountTotal / $this->m_productsPerPage );
    
    return $this->m_maxPage;
  }
  
  
  
  
  
  /**
   * Get the pagination vars (eg pagination_first, pagination_last) from the session cookie.
   * 
   * If session vars not set, init them.
   * 
   * Save values to member variables:
   * 
   *    $this->pagination_first
   * 
   *    $this->pagination_last
   */
  private function get_pagination_vars()
  {
    $this->m_shift = ($this->m_paginationLinksOnScreen - 1); // eg 5 - 1 == 4
    
    if (! $this->session->userdata('pagination_first'))
    {
      $this->session->set_userdata('pagination_first', 1);
    }
    $this->m_pagination_first = $this->session->userdata('pagination_first');
    
    
    if (! $this->session->userdata('pagination_last'))
    {
      $this->session->set_userdata('pagination_last', $this->m_pagination_first + $this->m_shift);
    }
    $this->m_pagination_last = $this->session->userdata('pagination_last');
    
    /*
    Potential scenario:
    
    There are 30 products. The user is on page 10, with 3 products per page.

    Product   1 2 3    4 5 6    7 8 9    10 11 12    13 14 15    16 17 18    19 20 21    22 23 24    25 26 27    28 29 30
    Page    1        2        3        4           5           6           7           8           9           10
                                                             ^                                               ^
                                                             pagination_first                                pagination_last
    
    -----------------------------------
    currentPage:         10
    productsPerPage:      3
    offset:              27
    limit:                3
    max_page:            10

    productCount_thisPage:   3
    productCount_total:     30

    pagination_first:           6
    pagination_last:           10
    pagination_links_onScreen:  5
    -----------------------------------
    (all correct)
    
    
    The user clicks "I want 10 products per page"

    Product   1 2 3 4 5 6 7 8 9 10      11 12 13 14 15 16 17 18 19 20       21 22 23 24 25 26 27 28 29 30
    Page    1                         2                                   3                                4    5    6    7    8    9    10
                                                                                                                   ^                    ^
                                                                                                                   pagination_first     pagination_last
                                                                                                                                        currentPage
    We adjust our pagination variables and get the following situation:
    ------------------------------------------------------------------------
    currentPage:        3   (good)
    productsPerPage:   10   (good)
    offset:            20   (good)
    limit:             10   (good)
    max_page:           3   (good)

    productCount_thisPage:   10   (good)
    productCount_total:      30   (good)

    pagination_first:           6   (incorrect)
    pagination_last:           10   (incorrect)
    pagination_links_onScreen:  5   (good)
    ------------------------------------------------------------------------

    In this situation, we need to correct our pagination variables.
    */
    if ($this->m_pagination_first > $this->m_maxPage)
    {
      $this->m_pagination_last = $this->m_maxPage;
      
      $this->m_pagination_first = $this->m_pagination_last - $this->m_shift;
      
      if ($this->m_pagination_first < 1)
      {
        $this->m_pagination_first = 1;
      }
      /*
      -------------------------------------------------------------------------------------------------------
      Product   1 2 3 4 5 6 7 8 9 10      11 12 13 14 15 16 17 18 19 20       21 22 23 24 25 26 27 28 29 30
      Page    1                         2                                   3
                  ^                                                             ^                    
                        pagination_first                                              pagination_last
                                                                                      currentPage
      -------------------------------------------------------------------------------------------------------
      */
    }
    
    
    
    
    
    // If we can only fit 5 buttons on screen at a time, which buttons do we show?
    // 
    // Eg, show buttons   3, 4, 5, 6, 7
    //                    ^           ^
    //                    first       last
    //
    // This helps us show the correct pagination links.
    //
    // eg "Page: (previous)(3)(4)(5)(6)(7)(next)"
    //                            ^
    //                            CURRENT PAGE
    
    
    
    $this->m_pagination_last = $this->m_pagination_first + $this->m_shift; // eg 3 + 4 == 7   ( 3, 4, 5, 6, 7 )
    
    
    
    
    /*
    Potential Scenario:
    -------------------------------------------------------------------------------------------------------
    Product   1 2 3 4 5 6 7 8 9 10      11 12 13 14 15 16 17 18 19 20       21 22 23 24 25 26 27 28 29 30
    Page    1                         2                                   3                                         4     5
          ^                                                             ^                                               ^                    
          pagination_first                                              ^                                               pagination_last
                                          currentPage == max_page
    -------------------------------------------------------------------------------------------------------
    */
    
    if ($this->m_pagination_last > $this->m_maxPage)
    {
      $this->m_pagination_last = $this->m_maxPage;
    }
    
    $this->save_pagination_vars();
  }
  
  
  
  
  
  
  /**
   * Save the member variables:
   * 
   *    $this->pagination_first
   * 
   *    $this->pagination_last
   * 
   * ...to the session cookie.
   */
  private function save_pagination_vars()
  {
    $this->session->set_userdata('pagination_first', $this->m_pagination_first);
    $this->session->set_userdata('pagination_last',  $this->m_pagination_last);
  }
  
  
  
  
  
  
  
  /**
   * Get the session var.
   * 
   * Otherwise, use the default value.
   * 
   * @return integer
   */
  private function get_currentPage()
  {
    $page = $this->session->userdata('store_current_page');
    
    if (!isset($page) || ! $page)
    {
      // No session var. Use default.
      
      $page = 1;
    }
    
    if ($page > $this->m_maxPage)
    {
      $page = $this->m_maxPage;
    }
    
    $this->session->set_userdata('store_current_page', $page);
    
    $this->m_currentPage = $page;
    
    return $this->m_currentPage;
  }
  
  
  
  
  /**
   * @return integer LIMIT for selecting from the database.
   */
  private function calculate_limit()
  {
    $this->m_limit = $this->m_productsPerPage;
    
    return $this->m_limit;
  }
  
  
  
  
  /**
   * @return integer OFFSET for selecting from the database.
   */
  private function calculate_offset()
  {
    $this->m_offset = ($this->m_currentPage * $this->m_productsPerPage) - $this->m_productsPerPage; // eg (5 * 5) - 5 == 20
    
    if ($this->m_offset < 0)
    {
      $this->m_offset = 0;
    }
    
    return $this->m_offset;
  }
  
  
  
  
  
  /**
   * Perform the database select, using keyword, offset and limit.
   * 
   * @return query result
   */
  private function get_matchingProducts()
  {   
    // Pagination
    // Show?   no           no            yes               yes                yes                yes              yes              no                ...   no
    //                                                                         CURRENT PAGE
    //       1 2 3 4 5    6 7 8 9 10    11 12 13 14 15    16 17 18 19 20     21 22 23 24 25     26 27 28 29 30   31 32 33 34 35   36 37 38 39 40    ...   196 197 198 199 200
    //       ^            ^             ^                 ^                  ^                  ^             
    //       page1        page2         page3             page4              page5              page6            page7            page8             ...   page40
    //       
    // offset  0            5             10                15                 20                 25               30               35                ...   195
    // 
    // limit   5          5           5                 5                5                5                5                5                 ...   5
    //
    
    
    // Get the current page of products. $keyword is used as search criteria. If it is blank, all records within the limit are returned.
    
    $this->m_matchingProducts = $this->products_model->search_products($this->m_keyword, $this->m_offset, $this->m_limit); // find all product rows, where a keyword matches some field.
    
    
//    if ($this->m_keyword)
//    {
//      $this->m_matchingProducts = $this->products_model->search_products($this->m_keyword, false, false); // find all product rows, where a keyword matches some field. No offset or limit.
//    }
//    else
//    {
//      $this->m_matchingProducts = $this->products_model->search_products($this->m_keyword, $this->m_offset, $this->m_limit); // find all product rows, where a keyword matches some field.
//    }
    
    
    if
    (
      isset($this->m_matchingProducts)
      
      &&
        
      ! is_null($this->m_matchingProducts)
      
      &&
        
      $this->m_matchingProducts 
    )
    {
      try
      {
        $this->m_matchingProducts = $this->m_matchingProducts->result_array(); // extract array from query result.
      }
      catch(Exception $ex)
      {
        $this->m_matchingProducts = array(); // no results.
      }
    }
    
    return $this->m_matchingProducts;
  }
  
  
  
  
  
  /**
   * Determine if there are any products on this page.
   */
  private function calculate_productCountThisPage()
  {
    if
    (
      isset($this->m_matchingProducts)
      
      &&
        
      ! is_null($this->m_matchingProducts)
      
      &&
        
      is_array($this->m_matchingProducts)
    )
    {
      // there is at least one product on this page.
      
      $this->m_productCount_thisPage = count($this->m_matchingProducts);
    }
    else
    {
      // There are no products on this page.
      
      $this->m_productCount_thisPage = 0;
    }
    
    return $this->m_productCount_thisPage;
  }
  
  
  
  
  
  
  
  /**
   * Called by the user to change what pagination buttons are available.
   * 
   * Eg when you click the "left" and "right" arrows in the "Page: 1,2,3,4,5" div
   * 
   *    "http://localhost/CI_fuzzyBear/index.php/store/pagination/left"
   * 
   *    "http://localhost/CI_fuzzyBear/index.php/store/pagination/right"
   * 
   *    "http://localhost/CI_fuzzyBear/index.php/store/pagination/reset"
   * 
   * @param string $direction 'left', 'right' or 'reset'  (These are the only options! (Enforced by routes.php))
   */
  public function pagination($direction = 'none')
  {
    //    1  2  3  4  5  6  7  8  9  10    (eg max_page == 10)
    //          ^           ^
    //          First       Last
    
    if($direction == 'left')
    {
      $this->m_pagination_first = $this->m_pagination_first - $this->m_shift; // eg 3 - 4 == -1
      
      //    -1  0  1  2  3  4  5  6  7  8  9  10
      //     ^  .  .  .  .           ^
      //     First                   Last
      
      if ($this->m_pagination_first < 1)
      {
        $this->m_pagination_first = 1;
      }
      
      $this->m_pagination_last = $this->m_pagination_first + $this->m_shift; // eg 1 + 4 == 5
      
      //    1  2  3  4  5  6  7  8  9  10
      //    ^           ^
      //    First       Last
      
      
      // Move the current page along
      
      if ($this->m_currentPage > $this->m_pagination_first && $this->m_currentPage <= $this->m_pagination_last)
      {
        $this->m_currentPage -= 1; // move left.
      }
      else
      {
        $this->m_currentPage = $this->m_pagination_last;
      }
      
      
      
      
      
    }
    elseif($direction == 'right')
    {
      $this->m_pagination_last = $this->m_pagination_last + $this->m_shift; // eg 7 + 4 == 11
      
      //    1  2  3  4  5  6  7  8  9  10  11    (eg max_page == 10)
      //          ^           .  .  .  .   ^
      //          First                    Last
      
      if ($this->m_pagination_last > $this->m_maxPage)
      {
        $this->m_pagination_last = $this->m_maxPage;
      }
      
      $this->m_pagination_first = $this->m_pagination_last - $this->m_shift; // eg 10 - 4 == 6
      
      //    1  2  3  4  5  6  7  8  9  10
      //                   ^           ^
      //                   First       Last
      
      // Move the current page along
      
      if ($this->m_currentPage >= $this->m_pagination_first && $this->m_currentPage < $this->m_pagination_last)
      {
        $this->m_currentPage += 1; // move right.
      }
      else
      {
        $this->m_currentPage = $this->m_pagination_first;
      }
      
      
      
    }
    else
    {
      // reset
      
      $this->m_pagination_first = 1;
      
      $this->m_pagination_last = $this->m_pagination_first + $this->m_shift;
      
      if ($this->m_pagination_last > $this->m_maxPage)
      {
        $this->m_pagination_last = $this->m_maxPage;
      }
      $this->m_currentPage = 1;
    }
    
    $this->session->set_userdata('store_current_page', $this->m_currentPage);
    
    $this->save_pagination_vars();
    
    $this->get_pagination_vars(); // ensure our pagination vars are valid. Correct them if necessary.
    
    
    // RECALCULATE THE VARIABLES THAT ARE DEPENDANT ON OUR PAGINATION VARS.
    
    $this->m_currentPage = $this->get_currentPage();
    
    $this->m_limit = $this->calculate_limit();
    
    $this->m_offset = $this->calculate_offset();
    
    $this->m_matchingProducts = $this->get_matchingProducts();
    
    $this->m_productCount_thisPage = $this->calculate_productCountThisPage();
    
    // FINISHED RECALCULATION.
    
    
    
    
    $this->serveStore(); // serve the current page.
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  /**
   *    Called when the user navigates to...
   * 
   *    http://localhost/CodeIgniter_2.1.3/index.php/store/productsPerPage/someNumber
   */
  public function productsPerPage($products_per_page)
  {
    
    // Adjust the number of products shown per page.
    
    if (isset($products_per_page) && $products_per_page)
    {
      $this->m_productsPerPage = $products_per_page;
      
      $this->session->set_userdata('store_products_per_page', $this->m_productsPerPage);
    }
    
    
    
    // RECALCULATE THE VARIABLES THAT ARE DEPENDANT ON OUR PAGINATION VARS.
    
    $this->m_productCountTotal = $this->products_model->get_count($this->m_keyword);
    
    $this->m_maxPage = $this->calculate_maxPage();
    
    $this->get_pagination_vars();
    
    $this->m_currentPage = $this->get_currentPage();
    
    $this->m_limit = $this->calculate_limit();
    
    $this->m_offset = $this->calculate_offset();
    
    $this->m_matchingProducts = $this->get_matchingProducts();
    
    $this->m_productCount_thisPage = $this->calculate_productCountThisPage();
    
    // FINISHED RECALCULATION.
    
    
    
    $this->serveStore(); // serve the current page.
  }
  
  
  
  
  
  
  
  
  
  
  
  /**
   *    Called when the user navigates to...
   * 
   *    http://localhost/CodeIgniter_2.1.3/index.php/store/
   */
  public function index($page = NULL)
  {   
    if
    (
      ! is_null($page)
        
      &&
        
      isset($page)
        
      &&
        
      $page
        
      &&
        
      ($page > 0)
    )
    {
      // User has specified the page they want.
      
      if ($page > $this->m_maxPage)
      {
        $page = $this->m_maxPage;
      }
      
      $this->m_currentPage = $page;
      
      $this->session->set_userdata('store_current_page', $page);
    }
    else
    {
      // User has not specified a page.
      
      // Check if we have a session var.
      
      if ($this->session->userdata('store_current_page'))
      {
        // Got a session var.
        
        $this->m_currentPage = $this->session->userdata('store_current_page');
      }
      else
      {
        // No session var.
        
        // Just use the first page.
        
        $this->m_currentPage = $this->m_pagination_first;
      }
    }
    
    
    
    // RECALCULATE THE VARIABLES THAT ARE DEPENDANT ON OUR PAGINATION VARS.
    
    $this->m_limit = $this->calculate_limit();
    
    $this->m_offset = $this->calculate_offset();
    
    $this->m_matchingProducts = $this->get_matchingProducts();
    
    $this->m_productCount_thisPage = $this->calculate_productCountThisPage();
    
    // FINISHED RECALCULATION.
    
    
    
    
    $this->serveStore(); // serve the current page.
    
  }

  
  
  
  
  
  
  
  
  
  /**
   * We have finished manipulating our data and are ready to render the full html page.
   * 
   * Send an HTML string to standard output.
   */
  private function serveStore()
  {
    
    // REVISE
    // REVISE
    // REVISE
    // 
    // Example values so far...
    // ---------------------------------------------------------------------------------------------------------------------------
    // $this->m_productCountTotal           200   Eg 200 records match the keyword "wire"
    // $this->max_page                       40   We need 40 pages (at 5 products per page)
    // 
    // $current_page                          5   We are on page 5 of 40
    // $products_per_page                     5   The user wants to see 5 products per page.
    // 
    // $this->pagination_first                3   We are showing links for pages (3 4 5 6 7)
    // $this->pagination_last                 7   We are showing links for pages (3 4 5 6 7)
    // $this->pagination_links_onScreen       5   We are showing links for pages (3 4 5 6 7)
    // 
    // 
    //                                            1 2 3 4 5    6 7 8 9 10    11 12 13 14 15    16 17 18 19 20    21 22 23 24 25
    //                                            page 1       page 2        page 3            page 4            ^       
    //                                                                                                           |
    // $offset                               20   eg (5 * 5) - 5 == 20           page 5 will start here -------->o
    // 
    // $limit                                 5   Eg products per page
    // ---------------------------------------------------------------------------------------------------------------------------
    
    // So we fetch products ( 21, 22, 23, 24, 25 ) from the database.
    // 
    // And we display pages (3)(4)(5)(6)(7)
    
    
    
    
    
    
    $data = $this->init_ViewData();
    
    array_push($data['js_array'], "storeEventHandlers.js");
    
    array_push($data['css_array'], "store.css");
    
    
    
    
    $data['storeProducts_html'] = '';
    
    $data['storeProducts_html'] .= $this->buildProductsTable($data['cart']);
    
    $data['prodCount'] = $this->m_productCountTotal;
    
    $data['key_word_value'] = $this->m_keyword;
    
    $data['page_title'] = "Store";
    
    
    
    // Pass data to the view(s).

    $data['debugText'] .= $this->getDebugStatement();

    $data['showDebug'] = true;
    
    
    
    // Load the views.
    
    $this->load->view('templates/header', $data);

    $this->load->view('templates/ribbon', $data);

    $this->load->view('store/index', $data);

    // $this->load->view('templates/footer'); // This renders too early and gets put in the wrong part of the dom.
    
    // Reply has been sent to browser. Finished serving this request.
  }
  
  
  
  
  protected function getDebugStatement()
  {
    $str = parent::getDebugStatement();
    
    $str .= 'max_page: ' . $this->m_maxPage . '<br />';
    
    $str .= '<br />';
    
    $str .= 'productCount_thisPage: ' . $this->m_productCount_thisPage . '<br />';
    
    $str .= 'productCount_total:    ' . $this->m_productCountTotal . '<br />';
    
    $str .= '<br />';
    
    $str .= 'pagination_first:          ' . $this->m_pagination_first . '<br />';
    
    $str .= 'pagination_last:           ' . $this->m_pagination_last . '<br />';
    
    $str .= 'pagination_links_onScreen: ' . $this->m_paginationLinksOnScreen . '<br />';
    
    $str .= '<br />';
    
    $str .= $this->m_memberDebugString;
    
    return $str;
  }
  
  
  
  
  
  /**
   * Returns an html string (div) to display above (and below) the store products table.
   * 
   * This div has links for navigating through the multiple pages of search results.
   */
  private function getPaginationDiv()
  {
    $startIdx = $this->m_pagination_first; // eg 1
    
    $endIdx = $this->m_pagination_last; // eg 5
    
    
    
    $need_prevBtn = false;
    
    if ($this->m_pagination_first > 1)
    {
      $need_prevBtn = true;
    }
    
    
    $need_nextBtn = false;
    
    if ($this->m_pagination_last < $this->m_maxPage)
    {
      $need_nextBtn = true;
    }
    
    
    // ---------------------------------------------------------------------
    
    $html = '';
    
    $html .= '<div class="row">'; // paginationHeader
    
      // =================================================================
    
      $html .= '<div class="col-xs-6">'; // pagination div

        // -----------------------------------------------------------------

        $html .= '<span class="pagination_links" id="yourPage">Page '.$this->m_currentPage.' of '.$this->m_maxPage.': </span>';

        // -----------------------------------------------------------------

        // LEFT ARROW

        $arrow_img = img('/public/images/no_arrow.png');

        $arrow_anchor = '';

        $arrow_id = 'no_arrow';

        $prev_url = '#';

        if ($need_prevBtn)
        {
          $prev_url = 'store/pagination/left';
          $arrow_id = 'left_arrow';
        }
        
        $html .= anchor(

          $prev_url,

          '...',

          'class="btn btn-primary" title="(more results)"'
        );

        // -----------------------------------------------------------------

        // PAGINATION LINKS

        for ($p = $startIdx; $p <= $endIdx; $p ++)
        {
          if ($p == $this->m_currentPage)
          {
            // No anchor. We are already on this page.

            $html .= anchor('#', $p, 'class="btn btn-primary pagination_links');
          }
          else
          {
            // Yes anchor. We are not on this page.

            $html .= anchor(

              'store/'.$p,      // uri

              $p,               // text

              'class="btn btn-primary pagination_links" title="Page '.$p.'"'  // attribs
            );
          }
        }

        // -----------------------------------------------------------------

        // RIGHT ARROW

        $arrow_img = img('/public/images/no_arrow.png');

        $arrow_anchor = '';

        $arrow_id = 'no_arrow';
        $next_url = '#';

        if ($need_nextBtn)
        {
          $next_url = 'store/pagination/right';
          $arrow_id = 'right_arrow';
        }

        // Next page of pagination links

        $html .= anchor(

          $next_url,

          '...',

          'class="btn btn-primary" title="(more results)"'
        );

        // -----------------------------------------------------------------

      $html .= '</div>';  // pagination div

      // ---------------------------------------------------------------------



      // Choose sumber of products per page.

      $css = "btn btn-default prodPerPage_links";

      $html .= '<div class="row">';

      $html .=   '<div class="col-xs-12">Products per page:</div>';

      $html .=   '<div class="col-xs-6" id="store_productsPerPage">';

      $html .=     '<span class="fontSize14">';

      if ($this->m_productsPerPage == $this->PRODUCTS_PER_PAGE_FEW)
      {
        $html .=        util::bright_PNG_anchor($this->PRODUCTS_PER_PAGE_FEW, $css);

        $html .=    ' ' . util::dark_PNG_anchor('store/productsPerPage/'.$this->PRODUCTS_PER_PAGE_MID , $this->PRODUCTS_PER_PAGE_MID, 'title="Show '.$this->PRODUCTS_PER_PAGE_MID.' products per page"', $css);

        $html .=    ' ' . util::dark_PNG_anchor('store/productsPerPage/'.$this->PRODUCTS_PER_PAGE_MAX , $this->PRODUCTS_PER_PAGE_MAX, 'title="Show '.$this->PRODUCTS_PER_PAGE_MAX.' products per page"', $css);
      }
      elseif ($this->m_productsPerPage == $this->PRODUCTS_PER_PAGE_MID)
      {
        $html .=        util::dark_PNG_anchor('store/productsPerPage/'.$this->PRODUCTS_PER_PAGE_FEW , $this->PRODUCTS_PER_PAGE_FEW, 'title="Show '.$this->PRODUCTS_PER_PAGE_FEW.' products per page"', $css);

        $html .=    ' ' . util::bright_PNG_anchor($this->PRODUCTS_PER_PAGE_MID, $css);

        $html .=    ' ' . util::dark_PNG_anchor('store/productsPerPage/'.$this->PRODUCTS_PER_PAGE_MAX , $this->PRODUCTS_PER_PAGE_MAX, 'title="Show '.$this->PRODUCTS_PER_PAGE_MAX.' products per page"', $css);
      }
      elseif ($this->m_productsPerPage == $this->PRODUCTS_PER_PAGE_MAX)
      {
        $html .=        util::dark_PNG_anchor('store/productsPerPage/'.$this->PRODUCTS_PER_PAGE_FEW , $this->PRODUCTS_PER_PAGE_FEW, 'title="Show '.$this->PRODUCTS_PER_PAGE_FEW.' products per page"', $css);

        $html .=    ' ' . util::dark_PNG_anchor('store/productsPerPage/'.$this->PRODUCTS_PER_PAGE_MID , $this->PRODUCTS_PER_PAGE_MID, 'title="Show '.$this->PRODUCTS_PER_PAGE_MID.' products per page"', $css);

        $html .=    ' ' . util::bright_PNG_anchor($this->PRODUCTS_PER_PAGE_MAX, $css);
      }
      $html .=     '</span>';

      $html .=   '</div>';
      
      $html .= '</div>';
    
      // =================================================================
    
    $html .= '</div>'; // paginationHeader
    
    return $html;
  }
  
  
  
  
  
  
  
  
  
  
  
  /**
   * Get the html string for the online store product table header.
   */
  private function getProductTableHeader()
  {
    /*
     * Store table header. Looks like this...
     * 
     *   -------------------------------------------------------------------------------------------------------
     *   |   ItemNo   |   Product   |   Unit Price   |   Available   |   Description   |   Quantity to Order   |
     *   -------------------------------------------------------------------------------------------------------
     * 
     */
    
    $html = "";
    
    $html .= "<!-- ================================================ -->";
    $html .= "<!-- (HTML_Fragment) (View: Store) productTableHeader -->";
    
    
    $html .=  "<td>";
    $html .=    '<div class="onlineStore_ItemNoColumn colheading" title="Sort by Item Number">';
              $attribs = array
              (
                'name'    => 'Button_sortBy_ItemNo',
                'class'   => 'sortBy_buttons',
                'id'    => 'sortBy_ItemNo',
                'value'   => 'ItemNo',
                'type'    => 'button'
              );
    
    $html .=      form_input($attribs);
    $html .=    '</div>';
    $html .=  '</td>';
    
    
    $html .=  "<td>";
    $html .=    '<div class="onlineStore_ProductColumn colheading" title="Sort by Product">';
  
              $attribs = array
              (
                'name'    => 'Button_sortBy_Product',
                'class'   => 'sortBy_buttons',
                'id'    => 'sortBy_Product',
                'value'   => 'Product',
                'type'    => 'button'
              );
    
    $html .=      form_input($attribs);
    $html .=    '</div>';
    $html .=  '</td>';
        
    $html .=  "<td>";
    $html .=    '<div class ="onlineStore_UnitPriceColumn colheading" title="Sort by Unit Price">';
              $attribs = array
              (
                'name'    => 'Button_sortBy_UnitPrice',
                'class'   => 'sortBy_buttons',
                'id'    => 'sortBy_UnitPrice',
                'value'   => 'Unit Price',
                'type'    => 'button'
              );
    $html .=      form_input($attribs);
    $html .=    '</div>';
    $html .=  '</td>';
        
    $html .=  "<td>";
    $html .=    '<div class="onlineStore_QtyInStockColumn colheading" title="Sort by Quantity in Stock">';
              $attribs = array
              (
                'name'    => 'Button_sortBy_QuantityInStock',
                'class'   => 'sortBy_buttons',
                'id'    => 'sortBy_QuantityInStock',
                'value'   => 'Available',
                'type'    => 'button'
              );
    $html .=      form_input($attribs);
    $html .=    '</div>';
    $html .=  '</td>';
        
    $html .=  "<td>";
    $html .=    '<div class="onlineStore_DescriptionColumn colheading" title="Sort by Description">';
              $attribs = array
              (
                'name'    => 'Button_sortBy_Description',
                'class'   => 'sortBy_buttons',
                'id'    => 'sortBy_Description',
                'value'   => 'Description',
                'type'    => 'button'
              );
    $html .=      form_input($attribs);
    $html .=    '</div>';
    $html .=  '</td>';
        
    $html .=  "<td>";
    $html .=    '<div class="onlineStore_QtyToOrderColumn colheading" title="Sort by Quantity in Cart">';
              $attribs = array
              (
                'name'    => 'Button_sortBy_QuantitytoOrder',
                'class'   => 'sortBy_buttons',
                'id'    => 'sortBy_QuantitytoOrder',
                'value'   => 'Quantity to Order',
                'type'    => 'button'
              );
    $html .=      form_input($attribs);
    $html .=    '</div>';
    $html .=  '</td>';
    $html .= '</tr>';
    
    return $html;
  }
  
  
  
  
  
  
  
  
  
  
  /**
   * Given a query result array of products, iterate through them and build the online store product table body, for the current page.
   * 
   * @param array $products
   * 
   * @param array $cart The cart we loaded from our encrypted session cookie.
   * 
   * @return string The html string for the current page of the online store products table.
   */
  private function buildProductsTable($cart)
  {
    
    //  Construct a new products table, based on the search results.
    // 
    //  If this was ebay, we would have to perform xss filtering to make sure we didn't send malicious content.
    // 
    //  But its an online store so we assume our database doesn't have malicious product records. Eg, description field with nasty javascript.
    // 
    //  The paranoid developer may want to do some XSS filtering here before the output is rendered to DOM and sent as AJAX.
    
    $html = "";
    
    $html .= validation_errors();
    
    $html .= $this->getPaginationDiv();
    
    $html .= '<div class="row">';

    $html .=   '<div class="col-xs-12">';
    
    //----------------------------------------------------------------------
  
    //$html .= form_open('checkout'); // the controller to invoke when the form is submitted.
  
    $html .= '<table id="onlineStoreTable" border="1">';

    $html .= $this->getProductTableHeader();

    try
    {
      
      
      if (!isset($this->m_matchingProducts) || is_null($this->m_matchingProducts) || ! $this->m_matchingProducts)
      {
        throw new Exception("Your search returned no results."); // query result array UNAVAILABLE
      }
      
      
      if (is_array($this->m_matchingProducts) && count($this->m_matchingProducts) == 0)
      {
        throw new Exception("query result array is EMPTY ");
      }
      
      
      
      foreach($this->m_matchingProducts as $prod)
      {
        // Quantity to Order

        $qty_in_cart = 0;

        if (isset($cart[$prod['ItemNo']]))
        {
          // This item is already in the user's cart.

          $qty_in_cart = $cart[$prod['ItemNo']]['qty'];
        }

        $qtyFieldAttribs = array
        (
          'name'    => 'quantity',
          'class'   => 'qty_inputClass',
          'id'    => 'qty_input_' . $prod['ItemNo'], // eg "qty_input_1" for rainbow wire.
          'value'   => $qty_in_cart,
          'maxlength' => $this->MAX_QTY_STRING_LENGTH,
          'onchange'  => 'updateCartItemQty(this);'
        );

        $inlineStyle_incartIcon = 'style="display:none;"';

        if ($qty_in_cart > 0)
        {
          $inlineStyle_incartIcon = 'style="display:block;"';
        }

        // "IN CART" image

        $image_properties = array
        (
          'src'   => $this->PATH_TO_IMAGES . 'inCart60x60.png',
          'alt'   => 'This item is in your cart',
          'width'   => $this->INCART_ICON_WIDTH,
          'height'  => $this->INCART_ICON_WIDTH
        );

        $html .=
        "<tr>" .
          "<td>" .
            '<div class="onlineStore_ItemNoColumn">' .
              $prod['ItemNo'] .
            '</div>' .
          "</td>" .

          "<td>" .
            '<div class="onlineStore_ProductColumn">' .
              $prod['ProductName'] .
              "<br />" .
              img($this->PATH_TO_IMAGES.$this->PRODUCT_THUMBS . $prod['imgURL']) .
            '</div>' .
          "</td>" .

          "<td>" .
            '<div class="onlineStore_UnitPriceColumn">' .
              "$ " . $prod['UnitPrice'] .
            '</div>' .
          "</td>" .

          "<!-- CostFromSupplier was here -->" .

          "<td>" .
            '<div class="onlineStore_QtyInStockColumn">' .
              $prod['QuantityInStock'] .
            '</div>' .
          "</td>" .

          "<td>" .
            '<div class="onlineStore_DescriptionColumn">' .
              '<div class="description_cell">' .
                $prod['ProductDescription'] .
              '</div>' .
            '</div>' .
          "</td>" .

          "<td>" .

            '<div class="quantity_cell">' .

              form_label('Quantity:', 'quantity') .

              '<br />' .

              form_input($qtyFieldAttribs) .

              '<div '.$inlineStyle_incartIcon.' id="img_inCart_prod'.$prod['ItemNo'].'" class="img_inCart">' .
                img($image_properties) .
              '</div>' .

            '</div>' .

          '</td>' .

        '</tr>';
      }

      // Button to Checkout

      $submitBtnAttribs = array
      (
        'name'    => 'submit',
        'id'    => 'store_submit',
        'value'   => 'Go to Checkout'
      );

      $html .=  '<tr class="noBorder">';

      $html .=    "<td></td>";

      $html .=    "<td></td>";

      $html .=    "<td></td>";

      $html .=    "<td></td>";

      $html .=    "<td></td>";

      $html .=    "<td>";

      $html .=      form_submit($submitBtnAttribs);

      $html .=      '<br />';

      $html .=      '<br />';

      $html .=    "</td>";

      $html .=  '</tr>';

      $html .= '</table';
      
      //$html .= form_close();
        
      //------------------------------------------------------------------
    }
    catch(Exception $ex)
    {
      return '<table id="onlineStoreTable" border="1">'.$ex->getMessage().'</table>';
    }

    $html .=   '</div>'; // col

    $html .= '</div>';   // row

    return $html;

  }
  
  
  
  
  
  
  /**
   * The user has sent us an AJAX (POST) message to search the store
   */
  public function search()
  {
    // (keyword) and (matching products) have already been fetched during ctor.
    
    $data = $this->init_ViewData();
    
    $htmlTable = $this->buildProductsTable($data['cart']);
    
//    $summary = '';
//    
//    if ($this->m_productCountTotal > 0)
//    {
//      foreach($this->m_matchingProducts as $prod)
//      {
//        $summary .= '<tr><td>Product</td><td>'.$prod['ProductName'].'</td></tr>';
//      }
//    }
//    
//    $htmlTable =
//        
//    '<table border="1">' .
//
//    '<tr><td>Keyword</td><td>'.$this->m_keyword.'</td></tr>' .
//
//    '<tr><td>product Count Total</td><td>'.$this->m_productCountTotal.'</td></tr>' .
//
//    $summary .
//
//    '</table>';
    
    
    Reply::init(); // For sending ajax reply (json)
    
    Reply::setJsonArg('prodCountTotal', $this->m_productCountTotal);
    
    Reply::addHTML($htmlTable);

    Reply::writeLine_debug($this->getDebugStatement());
    
    echo Reply::value();
  }
  
  
  
  
  
  
  
  
}