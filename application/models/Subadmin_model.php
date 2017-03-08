<?php
class Subadmin_model extends CI_Model
{
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
       // $this->load->database();
	}
	
	public function get_subadmin_by_id($id)
	{
		
		$this->mongo_db->where('membership.is_sub_admin',$id);
        $query = $this->mongo_db->get('membership');
        return $query->result_array();
    }
	
	public function get_subadmin()
    {
		
        $this->mongo_db->where('membership.is_sub_admin','1');
		$fetch = $this->mongo_db->get('membership');
		
		return $fetch;
    }
    
    public function add_subadmin($data_to_store)
    {
        $insert = $this->mongo_db->insert('membership', $data_to_store);
		$insert_id = $this->mongo_db->insert_id();
		return $insert_id;
    }
    
    public function chk_user_name($new,$old)
    {
       
        $this->mongo_db->where(array('user_name'=>(string)$new));
        $this->mongo_db->where_ne('user_name', (string)$old);
		$chk=$this->mongo_db->count('membership');
        if($chk > 0)
        {
           return "yes";
        }
        else
        {
            return "no";
        }
   }
   
    public function new_user_name($user_name)
    {
       
         $this->mongo_db->where(array('user_name'=>(string)$user_name));
		$check=$this->mongo_db->count('membership');
		
		
        if($check > 0)
        {
           return "yes";
        }
        else
        {
            return "no";
        }
   }
   
   public function email_check($email)
   {
	    $this->mongo_db->where(array('email_addres'=>(string)$email));
		$check=$this->mongo_db->count('membership');
	 
        if($check > 0)
        {
           return "yes";
        }
        else
		{
            return "no";
        }
   }
        
}
?>