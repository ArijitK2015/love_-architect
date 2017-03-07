<?php
class Sitesetting_model extends CI_Model {
 
		/**
		* Responsable for auto load the database
		* @return void
		*/
		public function __construct()
		{
			
		}
		 
		public function get_settings()
		{
			$collection 	= $this->mongo_db->get('settings');
			return $collection; 
		}
	 
		 public function get_admin_pagination()
		 {
			 $id = '1';
			 $this->db->select('admin_pagination');
			 $this->db->from('settings');
			 $this->db->where('id', $id);
			 $query = $this->db->get();
			 $ret = $query->row();
			 return $ret->admin_pagination;
		 }
	 
		/**
		* Update password
		* @param array $data - associative array with data to store
		* @return boolean
		*/
		function update_settings($data, $id)
		{
			$id 	= ($id) ? $id : 1;
			$this->mongo_db->where(array('_id' => $id));
			$this->mongo_db->set($data);
			$get = $this->mongo_db->update('settings');
			return true;
		}
		
		public function get_site_pagination()
		{
			$id = '1';
			$this->db->select('site_pagination');
			$this->db->from('settings');
			$this->db->where('id', $id);
			$query = $this->db->get();
			$ret = $query->row();
			return $ret->site_pagination;
		}
		 
		 //used for various data fetching
		 function global_fetchvalue($table, $select, $where=null, $order_in=null, $order_by=null, $from=null, $perpage=null, $join=null, $group_by=null, $return_count = 0)
		 {
			 //echo '<pre>: '.$join.' '.$perpage;
			 
			 $this->db->select($select);
			 $this->db->from($table);
			 
			 if($where!='')
			 {
				 $this->db->where($where);
			 }
			 
			 if(!empty($join) && ($join != null)){
				 foreach ($join as $key => $value){
					 $this->db->join($key,$value);
				 }
			 }
			 
			 if($group_by!='')
			 {
				 $this->db->group_by($group_by);
			 }
			 
			 if($order_in!='' && $order_by!='')
			 {
				 $this->db->order_by($order_in,$order_by);
			 }
			    
			 if(is_numeric($from) &&  is_numeric($perpage)){
				 //echo 'arijit';
				 
				 $this->db->limit($perpage, $from);    
			 }
			    
			 $query 		= $this->db->get();
			 
			 if($return_count == 1)
				 return $result	= $query->num_rows();
			 else
				 return $result	= $query->result_array();
		}
		
		//for various data update
		function global_update($table,$data,$where=null)
		{
			if($where!='')
			{
			 $this->db->where($where);
			}
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
		
		//for various data add
		function global_add($table,$data)
		{
			$insert = $this->db->insert($table, $data);
			return $insert;
		}
		
		// for various data delete
		function global_delete($table,$where)
		{  
			$this->db->where($where);
			$this->db->delete($table);
			//echo $this->db->last_query();
			//exit;
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