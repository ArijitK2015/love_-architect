<?php
class Contactsetting_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
	public function __construct()
	{
	    
	}
	
	public function get_settings($id)
	{
		//$id = '1';
		$id 	= ($id) ? $id : $this->session->userdata('user_id_hotcargo');
		$this->mongo_db->where(array('id' => $id));
		$all_details 	= $this->mongo_db->get('contact_settings');
		
		return $all_details;
	}
	/**
    * Update password
    * @param array $data - associative array with data to store
    * @return boolean
    */
	function update_settings($data, $id=0)
	{
		$id = ($id) ? $id : $this->session->userdata('user_id_hotcargo');
		$this->mongo_db->where(array('id' => $id));
		$this->mongo_db->set($data);
		$get = $this->mongo_db->update('contact_settings');
		return true;
	}
 
}
?>