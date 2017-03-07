<?php
class Myaccount_model extends CI_Model {
 
	/**
	* Responsable for auto load the database
	* @return void
	*/
	public function __construct()
	{
	    //$this->load->database();
	}
 
	/**
	* Update password
	* @param array $data - associative array with data to store
	* @return boolean
	*/
	function update_account($data,$id=0)
	{
		$id = ($id) ? $id : $this->session->userdata('user_id_lovearchitect');
		$this->mongo_db->where(array('id' => $id));
		$this->mongo_db->set($data);
		$get = $this->mongo_db->update('membership');
		
		return ($get) ?  true : false;
	}
	
	public function get_account_data($id = 0, $is_merchant = 0)
	{
		$id 				= ($id) ? $id : $this->session->userdata('user_id_lovearchitect');
			
		if($is_merchant)
		{
			//get merchant details
			$this->mongo_db->where(array('_id' => $id));
			$all_details 	= $this->mongo_db->get('site_users');
		}
		else{
			$this->mongo_db->where(array('id' => (string)$id));
			$all_details 	= $this->mongo_db->get('membership');
		}
			
			
		return $all_details; 
	}
	
	public function check_category($title,$id)
	{
		$id 	= ($id) ? $id : $this->session->userdata('user_id_lovearchitect');
		$this->mongo_db->select('*');
		$this->mongo_db->from('category');
		$this->mongo_db->where('title', $title);
		$this->mongo_db->where('id!=', $id);
		$query = $this->mongo_db->get();
		return $query->result_array(); 
	}
}
?>