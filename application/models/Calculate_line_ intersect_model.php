<?php

class Check_line_intersect_model extends CI_Model {

	/**
	* Responsable for auto load the database
	* @return void
	*/
	public function __construct()
	{
		
	}
	
	function form_types_det($type="")
	{
		$all_details = array();
		
		if(!empty($type))
		{
			$this->mongo_db->where(array('form_type' => $type, 'status' => "1", "is_fixed" => "1"));
			$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
			$all_details_fixed 	= $this->mongo_db->get('form_fields');
			
			$this->mongo_db->where(array('form_type' => $type, 'status' => "1", "is_fixed" => "0"));
			$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
			$all_details_vari 	= $this->mongo_db->get('form_fields');
			
			$all_details['fixed_contents']	= $all_details_fixed;
			$all_details['vari_contents']		= $all_details_vari;
			
			return $all_details;
		}
		else
			return array();
	}
}