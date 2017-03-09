<?php
class Common_model extends CI_Model {
 
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
	function add($table,$data)
	{
		$res=$this->mongo_db->insert($table, $data);
		
		$report = array();
		
		if($res)
		{
			return $res;
		}
		else
		{
		    return 0;
		}
	}
    
    function update($table,$data,$condition=array())
    {
	if(isset($condition))
	{
	    
		$this->mongo_db->where($condition);
	    
	}
	if(!empty($data))
	{
	    
		$this->mongo_db->set($data);
	    
	}
	
	$this->mongo_db->update($table);
	//echo $this->mongo_db->last_query();
	//exit;
	$report = array();
	if($report !== 0)
	{
	    return true;
	}else{
	    return false;
	}
    }
	

    
	public function get($table, $what=array(), $condition=array(), $limit_start=0, $limit_end=null, $group=null, $condition1=null, $order=array('id' => 'desc'), $join=null,$join_type=null, $condition_like=null)
	{
		if(!empty($what))
			$this->mongo_db->select($what);
		
		//Setting all conditions
		//if(isset($condition))
		//{
		//	foreach ($condition as $key => $value)
		//	{
		//		$this->mongo_db->where($key,$value);
		//	}
		//}
		
		if(!empty($condition))
			$this->mongo_db->where($condition);
		
		if(!empty($condition_like))
		{
			foreach ($condition_like as $key => $value)
				$this->mongo_db->like($key, $value);
		}
		
		//if(isset($join))
		//{
		//	foreach ($join as $key => $value)
		//	{
		//	$this->mongo_db->join($key,$value,$join_type[$key]);
		//	}
		//}
		
		if($limit_start != null)
			$this->mongo_db->limit($limit_start, $limit_end);    
		
		//Set the group 
		if($group != null)
			$this->mongo_db->group_by($group);
		
		
		if(!empty($order))
			$this->mongo_db->order_by($order);
		
		$result 	= $this->mongo_db->get($table);
		
		return $result; 
	}
    
    
    function count($table,$condition=null,$limit_start=null, $limit_end=null)
    {
        // count total category
        
        $this->mongo_db->select('*');
        $this->mongo_db->from($table);
	if(isset($condition))
	{
	    foreach ($condition as $key => $value){
		$this->mongo_db->where($key,$value);
	    }
	}
	if($limit_start != null)
	{
            $this->mongo_db->limit($limit_start, $limit_end);    
        }
        $query = $this->mongo_db->get();
	//echo $this->mongo_db->last_query();
        return $query->num_rows();        
    }
    
    
    function delete($table,$condition=array())
    {    
        if(!empty($condition))
		{
			
			$this->mongo_db->where($condition);
			
		}
//		if($delete_many)
//		{
//			 $this->mongo_db->delete_all($table);
//		}
//		else
//		{
//        $this->mongo_db->delete($table);
//		}
		
		$this->mongo_db->delete_all($table);
		return true;
		
    }
   function get_postcode_list()
	{
	    $this->mongo_db->select('*');
	    $this->mongo_db->from('post_code');
	    
	    $results = $this->mongo_db->get();
	    //echo $this->mongo_db->last_query();
	    return $results->result();	
	}
	
	function generate_unique_code($length = 10,$table = '',$field = '') {
		$alphabets = range('A','Z');
		$numbers = range('0','9');
		//$additional_characters = array('_','.');
		$final_array = array_merge($alphabets,$numbers);
			 
		$password = '';
	  
		while($length--) {
		  $key = array_rand($final_array);
		  $password .= $final_array[$key];
		}
	  
	  if(!empty($table) && !empty($field))
	  {
		$this->mongo_db->where(array($field =>$password));
		$results = $this->mongo_db->count($table);
		
		if($results>0)
			$this->generate_unique_code($length,$table,$field);
		else
			return $password;
	  }
  }
}
?>