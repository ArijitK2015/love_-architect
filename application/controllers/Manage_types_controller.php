<?php
class Manage_types_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		//Loading all necessary models
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->library('ImageThumb');
		
		//if admin user logged out redirecting to login page
		if(!$this->session->userdata('is_logged_in')){
			redirect('control/login');
		}
	}
 
	/**
	* Load the main view with all the current model model's data.
	* @return void
	*/
	public function index()
	{
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		
		$this->mongo_db->select('*');
		$this->mongo_db->order_by(array('_id' => 'asc'));
		$all_details 					= $this->mongo_db->get('types');
		
		$data['data']['all_types']		= $all_details;

		$data['view_link'] = 'admin/types/index';
		
		
		$this->load->view('includes/template', $data);
	}//index
	
	public function add_types()
	{
		$user_id 					= ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		//Get last user id
		
		$data_to_store['title']			= strval($this->input->post('title'));
		//$data_to_store['enter_dimention']	= strval($this->input->post('enter_dimention'));
		//$data_to_store['width']			= strval($this->input->post('width'));
		//$data_to_store['height']			= strval($this->input->post('height'));
		//$data_to_store['depth']			= strval($this->input->post('depth'));
		//$data_to_store['weight']			= strval($this->input->post('weight'));
		$data_to_store['help_txt']		= strval($this->input->post('help_txt'));
		$data_to_store['added_on']		= strval(date('Y-m-d H:i:s'));
		$data_to_store['status']			= strval(1);
		
		$insert	= $this->mongo_db->insert('types', $data_to_store);
			
		if($insert)
		{
			$this->session->set_flashdata('flash_message', 'insert_success');
			redirect('control/manage-types');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'insert_failed');
			redirect('control/manage-types');
		}
	}
	
	
	public function add()
	{
		
		$user_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$data['view_link'] = 'admin/types/add_types';
		$this->load->view('includes/template', $data);
		
	}
	
	//Function to update existing form fields and add new ones if added
	public function updt()
	{
		$user_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$types_form_id 				= ($this->input->post('edit_type_id') && $this->input->post('edit_type_id')!='') ? strval($this->input->post('edit_type_id')) :'';
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $types_form_id != '')
		{
			$data_to_store		= array();

			$data_to_store['title']			= strval($this->input->post('title'));
			//$data_to_store['enter_dimention']	= strval($this->input->post('enter_dimention'));
			//$data_to_store['width']			= strval($this->input->post('width'));
			//$data_to_store['height']			= strval($this->input->post('height'));
			//$data_to_store['depth']			= strval($this->input->post('depth'));
			//$data_to_store['weight']			= strval($this->input->post('weight'));
			$data_to_store['help_txt']		= strval($this->input->post('help_txt'));
			$data_to_store['status']			= strval($this->input->post('status'));
		
			//echo '<pre>'; print_r($data_to_store); die;
		
			$this->mongo_db->where(array('_id' => $types_form_id));
			$this->mongo_db->set($data_to_store);
			//$this->mongo_db->update('types');
		
			if($this->mongo_db->update('types'))
			{
				$this->session->set_flashdata('flash_message', 'type_updated');
				redirect('control/manage-types');
			} 
			else
			{
				$this->session->set_flashdata('flash_message', 'type_update_failed');
				redirect('control/manage-types');
			}
		}
		else{
			$types_id 					= ($this->input->post('type_id') && $this->input->post('type_id')!='') ? strval($this->input->post('type_id')) :'';
			if(empty($types_id))
				redirect('control/manage-types');
			
			$this->mongo_db->where(array('_id' => $types_id));
			$data['data']['type_details'] 	= $this->mongo_db->get('types'); 
		}
		
		$data['view_link'] = 'admin/types/edit_types';
		$this->load->view('includes/template', $data);
	}
	
	public function delete()
	{
		//Get logged in session admin id
		$user_id 						= ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//getting all admin data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		
		//Get requested id to remove
		echo $field_id 					= $this->uri->segment(4);
		
		//deleting query
		$this->mongo_db->where(array('_id' => $field_id));
		if($this->mongo_db->delete('types')){
			$this->session->set_flashdata('flash_message', 'delete_success');
			redirect('control/manage-types');
		}
		else{
			$this->session->set_flashdata('flash_message', 'delete_failed');
			redirect('control/manage-types');
		}
	}

}