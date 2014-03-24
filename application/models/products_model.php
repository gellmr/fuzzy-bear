


<?php

/**
 * -----------------------------------------------------------------------
 * products_model
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
class Products_model extends CI_Model
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
	 * Get all products.
	 * 
	 * @return type
	 */
	public function get_products()
	{
		// Get 10 product records.
		// 
		// Perform SELECT * FROM as2product

		$result = $this->db->get('as2product');

		//return $result->result_array();
		
		return $result;
	}
	
	
	
	
	/**
	 * Get a page of records, eg get_page_of_products(75, 25) retrieves records 25 to 100
	 * 
	 * @param integer $limit eg 75
	 * 
	 * @param integer $offset eg 25
	 * 
	 * @return query object, eg the result of executing 'SELECT * FROM mytable LIMIT 75, 25'
	 */
	public function get_page_of_products($limit, $offset)
	{
		$query = $this->db->get('as2product', $limit, $offset);
		
		return $query;
	}
	
	
	
	

	/**
	 * Fetch the details of the given product.
	 * 
	 * @param int $id The item number we are looking for.
	 * @return array $row_array An array of row values, from the as2product database table.
	 */
	public function get_productById($id)
	{
		$sql = "SELECT * FROM as2product WHERE ItemNo = ?";

		$query = $this->db->query($sql, array($id));

		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		return false;
	}

	
	
	
	
	
	public function get_count($keyword)
	{
		
		$this->db->or_like('ProductName',			$keyword);
		
		$this->db->or_like('ProductDescription',	$keyword);
		
		$this->db->or_like('imgURL',				$keyword);
		
		$this->db->from('as2product');
		
		return $this->db->count_all_results(); // Produces an integer, like 17
	}
	
	
	
	
	/**
	 * Find all products which relate to the given keyword.
	 * 
	 * @param string $keyword The search string entered by the user. We will try to match against ProductName, ProductDescription, and imgURL fields.
	 * 
	 * @param integer $offset eg 25
	 * 
	 * @param integer $limit eg 75
	 * 
	 * @return array $result An array of row values, from the as2product database table.
	 */
	public function search_products($keyword, $offset, $limit)
	{
		$this->db->or_like('ProductName',			$keyword);
		
		$this->db->or_like('ProductDescription',	$keyword);
		
		$this->db->or_like('imgURL',				$keyword);
		
		$result = $this->db->get('as2product', $limit, $offset);
		
//		if ($offset && $limit)
//		{
//			$result = $this->db->get('AS2Product', $limit, $offset);
//		}
//		else
//		{
//			$result = $this->db->get('AS2Product');
//		}
		
//		$sql = "SELECT * FROM AS2Product";
//
//		if	($keyword)
//		{
//			$sql	.=	" WHERE ";
//
//			$sql	.=	"(";
//
//			$sql	.=	"ProductName REGEXP '$keyword'";
//
//			$sql	.=	" OR ";
//
//			$sql	.=	"ProductDescription REGEXP '$keyword'";
//
//			$sql	.=	" OR ";
//
//			$sql	.=	"imgURL REGEXP '$keyword'";
//
//			$sql	.=	")";
//		}
//
//		$sql	.=	" LIMIT $offset, $limit";
//
//		$sql .= ";";	// Append semicolon to the query.
//		
//		$result = $this->db->query($sql);

		if ($result->num_rows() > 0)
		{
			return $result;
		}
		return false;
	}
}
?>