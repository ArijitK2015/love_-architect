<?php
class Email_template_model extends CI_Model {
 
	/**
	* Responsable for auto load the database
	* @return void
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mongo_db');
	}
 
	/**
	* Get product by his is
	* @param int $product_id 
	* @return array
	*/
	public function get_tempalte_info()
	{
		$this->mongo_db->order_by(array('_id' => 'desc'));
		$details = $this->mongo_db->get('email_templates');
		
		return $details;
	}  
	
	public function get_email($email)
	{
		//$this->db->select('*');
		//$this->db->from('newsletter');
		$query = $this->db->get_where('newsletter',array('email'=>$email));
		return $query->result_array();
	}
	
	
	public function insert_email($email,$ip,$date)
	{
		$this->db->set('email', $email);
		$this->db->set('ip', $ip);
		$this->db->set('date', $date);
		$this->db->insert('newsletter');
	}
	
	public function update_email($data)
	{
		$this->db->where('id', $id);
		$this->db->update('newsletter', $data);
		return $query->result_array();
	}
	
	function delete_email($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('newsletter');
			 
		return true;
	}
	public function store_template($array,$table)
	{
		$this->db->insert($table, $array);
		$insert = $this->db->insert_id();
		return $insert;
	}
	
	function get_template_by_id($id,$table)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
	}
	
	function update_template($id, $data,$table)
	{
		$this->db->where('id', $id);
		$this->db->update($table, $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}
}
?>