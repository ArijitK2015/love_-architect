<?php
class Chngpassword_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
	public function __construct()
	{
	    
	}

	/**
	* Update password
	* @param array $data - associative array with data to store
	* @return boolean
	*/
	function update_password($data, $id=0)
	{
		$id = ($id) ? $id : $this->session->userdata('user_id_hotcargo');
		$this->mongo_db->where(array('id' => $id));
		$this->mongo_db->set($data);
		$get = $this->mongo_db->update('membership');
	
		return true;
		
	}
	
	public function check_password($old_pass,$user_id)
	{
		$this->mongo_db->where(array('id' => $user_id, 'pass_word' => $old_pass));
		$chek_exist = $this->mongo_db->count('membership');
	}
	
 
}
?>