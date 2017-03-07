<?php

class Users_model extends CI_Model {

	/**
	* Responsable for auto load the database
	* @return void
	*/
	public function __construct()
	{
		//$this->load->database();
		$this->load->library('mongo_db');
	}
	
	/**
	* Validate the login's data with the database
	* @param string $user_name
	* @param string $password
	* @return void
	*/
	function all_users()
	{
		//$this->mongo_db->select('*');
		//$this->mongo_db->from('users');
		//  
		//$query = $this->mongo_db->get();
		//return $query->result_array();
		
		//connect to mongodb collection (i.e., table) named as ‘surfinme_index’
		$collection 	= $this->mongo_db->db->selectCollection('settings');
       	//selecting records from the collection - surfinme_index
       	$result		= $collection->find();
		foreach($result as $data) 
		{  
			//display the records  
			var_dump($data);
		} 
		
	}
	
	function validate($user_name='', $password='', $user_id=0)
	{
		$this->mongo_db->where(array('user_name' => $user_name, 'pass_word' => $password));
		$membership_det = $this->mongo_db->get('membership');
		
		if(count($membership_det) > 0)
		{
			$all_details 	= $membership_det;
			
			$user_id 		= (isset($all_details[0]['id'])) ? $all_details[0]['id'] : 0; // setting userid in session
			return $user_id;
		}
		else
			return 0;
	}
	
	public function get_site_name()
	{
		$collection 	= $this->mongo_db->get('settings');
		$site_name 	= (isset($collection[0]['site_name'])) ? $collection[0]['site_name'] : '';
		return 		$site_name;
	}
	
	public function get_user_salt($profile_type = '', $email = '')
	{
		$this->mongo_db->where(array('email' => $email));
		$all_details 	= $this->mongo_db->get('site_users');
		
		if(!empty($all_details))
		{
			$current_user_salt 	= (isset($all_details[0]['password_salt'])) ? $all_details[0]['password_salt'] : '';
			return $current_user_salt; 
		}
		else return '';
	}
	
	//validate user details
	public function validate_user($profile_type = '', $email = '', $password = '',$merchant_id='', $do_merchant = 1)
	{
		if($profile_type != '')
		{
			(($do_merchant)) ? $this->mongo_db->where(array('user_type' => $profile_type, 'email' => $email, 'password' => $password,'merchant_id' => $merchant_id)) : $this->mongo_db->where(array('user_type' => $profile_type, 'email' => $email, 'password' => $password));
			//$this->mongo_db->where(array('user_type' => $profile_type, 'email' => $email, 'password' => $password,'merchant_id' => $merchant_id)); 
		}
		else
		{
			(($do_merchant)) ? $this->mongo_db->where(array('email' => $email, 'password' => $password, 'merchant_id' => $merchant_id)) : $this->mongo_db->where(array('email' => $email, 'password' => $password));
			//$this->mongo_db->where(array('email' => $email, 'password' => $password, 'merchant_id' => $merchant_id));
		}
		
		$count 	= $this->mongo_db->count('site_users');
		
		if($count > 0)
		{
			if(($do_merchant))
				$this->mongo_db->where(array('email' => $email, 'password' => $password, 'merchant_id' => $merchant_id));
			else 				 $this->mongo_db->where(array('email' => $email, 'password' => $password));
			
			$all_details 	= $this->mongo_db->get('site_users');
			
			if(!empty($all_details))
			{
				$current_user_status 		= (isset($all_details[0]['status'])) ? $all_details[0]['status'] : 0;
				
				return ($current_user_status == 1) ? $all_details : 2; // 2 - user is not activated yet
			}
			else
				return 3;  // 3 - user details not found
		}
		else
			return 0; // 0 - user not found
	}
	
	public function validate_user_linkedin($profile_type = '', $email = '', $linked_id = '')
	{
		if($profile_type != '') 	$this->mongo_db->where(array('user_type' => $profile_type, 'email' => $email, 'linkedin_id' => $linked_id));
		//else 				$this->mongo_db->where(array('email' => $email, 'linkedin_id' => $linked_id));
		else 				$this->mongo_db->where(array('email' => $email));
		
		$count 	= $this->mongo_db->count('site_users');
		
		if($count > 0)
		{
			//Check if this user have a linked in id
			$this->mongo_db->where(array('email' => $email));
			$all_details 		= $this->mongo_db->get('site_users');
			$user_linkedin_id 	= (isset($all_details[0]['linkedin_id'])) ? $all_details[0]['linkedin_id'] : '';
			
			if($user_linkedin_id ==  $linked_id)
			{
				if(!empty($all_details))
				{
					$current_user_status 		= (isset($all_details[0]['status'])) ? $all_details[0]['status'] : 0;
					
					return ($current_user_status == 1) ? $all_details : 2; // 2 - user is not activated yet
				}
				else
					return 3;  // 3 - user details not found
			}
			elseif($user_linkedin_id == '')
			{
				if(!empty($all_details))
				{
					$user_id 				= (isset($all_details[0]['_id'])) 		? strval($all_details[0]['status']) : '';
					$current_user_status	= (isset($all_details[0]['status'])) 	? $all_details[0]['status'] : 0;
					
					if($current_user_status == 1)
					{
						$data_to_update['linkedin_id']	= $linked_id;
						
						//Updating user with new data
						$this->mongo_db->where(array('_id' => $user_id));
						$this->mongo_db->set($data_to_update);
						$this->mongo_db->update('site_users');
						
						return $all_details;
					}
					else
						return 2; // 2 - user is not activated yet
				}
				else
					return 3;  // 3 - user details not found
			}
			else
				return 3;  // 3 - user details not found
		}
		else
			return 0; // 0 - user not found
	}
	
    /**
    * Serialize the session data stored in the database, 
    * store it in a new array and return it to the controller 
    * @return array
    */
	function get_db_session_data()
	{
		$query = $this->mongo_db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}
	
    /**
    * Store the new user's data into the database
    * @return boolean - check the insert
    */	
	function create_member()
	{

		$this->mongo_db->where('user_name', $this->input->post('username'));
		$query = $this->mongo_db->get('membership');

        if($query->num_rows > 0){
        	echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>';
			  echo "Username already taken";	
			echo '</strong></div>';
		}else{

			$new_member_insert_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email_addres' => $this->input->post('email_address'),			
				'user_name' => $this->input->post('username'),
				'pass_word' => md5($this->input->post('password'))						
			);
			$insert = $this->mongo_db->insert('membership', $new_member_insert_data);
			return $insert;
		}
	      
	}//create_member
	
	function update_otp($id,$data)
	{
		$this->mongo_db->where('id',$id);
		$up_data1=$this->mongo_db->update('membership',$data);
		return $up_data1;
	}
	function valid($user_id, $otp)
	{
		$this->mongo_db->select('*');
		$this->mongo_db->from('membership');
		$this->mongo_db->where('id', $user_id);
		$this->mongo_db->where('otp_code', $otp);
		$query = $this->mongo_db->get();
		return $query->result_array();
		
	}
	function send_sms_code($otp, $phone_number,$country_id)
	{
		
	}
}