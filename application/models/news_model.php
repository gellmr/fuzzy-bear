


<?php


		/*
		-----------------------------------------------------------
							News_model
		-----------------------------------------------------------
		CodeIgniter's "Active Record" is a Database Abstraction Layer which

		query sanitization for us. This means we can write our queries

		in a DB Platform independent syntax. The queries are also

		escaped automatically by the system.
		-----------------------------------------------------------
		*/
class News_model extends CI_Model
{



			/**
			Ctor
			*/
	public function __construct()
	{
		// Load the database library.
		
		$this->load->database();
	}
	
	
	
	

			/**
			Get all of our posts from our database
			
			@param $slug	The news item to get.
			Pass the value of the news item you want to get, or FALSE to get all news records.
			*/
	public function get_news($slug = FALSE)
	{
		
		if ($slug === FALSE)
		{
			// Get all news records.
			
			// Perform SELECT * FROM news
			
			$query = $this->db->get('news');
			
			return $query->result_array();
		}
		
		
		// Get a news item by its slug.
		
		// 	Perform SELECT * FROM news WHERE slug >= $slug
		
		// 	Note, $slug is sanitized automatically by Active Record.
		
		$query = $this->db->get_where
		(
			'news',
			
			array('slug' => $slug)
		);
		
		return $query->row_array();
	}
	
	
	
	
	
	
	
	
	
			/**
			Create a News Item
			*/
	public function set_news()
	{
		$this->load->helper('url');
	
		/*
		Strip down the string passed in.
		Replace all spaces with dashes (-)
		Make sure everything is lowercase.
		This results in a nice slug such as "psionic_dolphins"
		that can be included in a url, eg
		"http://localhost/CodeIgniter_2.1.3/index.php/news/psionic_dolphins"
		*/
		$slug = url_title
		(
			$this->input->post('title'),
			'dash',
			TRUE
		);
		
		/*
		Each key (title/slug/text)
		corresponds to a column in our 'news' table of the database.
		The post() method (from the Input Library) makes sure the data
		is sanitized. The Input Library is loaded by default.
		*/
		$data = array
		(
			'title' => $this->input->post('title'),
			'slug'  => $slug,
			'text'  => $this->input->post('text')
		);
		
		return $this->db->insert('news', $data);
	}
	
	
	
}