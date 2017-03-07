<?php
class Manage_special_controller extends CI_Controller {

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
		$all_details 					= $this->mongo_db->get('special');
		
		$data['data']['all_special']		= $all_details;

		$data['view_link'] = 'admin/special/index';
		
		
		$this->load->view('includes/template', $data);
	}//index
	
	public function add_special()
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
		
		$insert	= $this->mongo_db->insert('special', $data_to_store);
			
		if($insert)
		{
			$this->session->set_flashdata('flash_message', 'insert_success');
			redirect('control/manage-special');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'insert_failed');
			redirect('control/manage-special');
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
		
		$data['view_link'] = 'admin/special/add_special';
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
		
		echo $special_form_id 				= ($this->input->post('edit_special_id') && $this->input->post('edit_special_id')!='') ? strval($this->input->post('edit_special_id')) :'';
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $special_form_id != '')
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
		
			$this->mongo_db->where(array('_id' => $special_form_id));
			$this->mongo_db->set($data_to_store);
			//$this->mongo_db->update('special');
		
			if($this->mongo_db->update('special'))
			{
				$this->session->set_flashdata('flash_message', 'special_updated');
				redirect('control/manage-special');
			} 
			else
			{
				$this->session->set_flashdata('flash_message', 'special_update_failed');
				redirect('control/manage-special');
			}
		}
		else{
			$special_id 					= ($this->input->post('special_id') && $this->input->post('special_id')!='') ? strval($this->input->post('special_id')) :'';
			if(empty($special_id))
				redirect('control/manage-special');
			
			$this->mongo_db->where(array('_id' => $special_id));
			$data['data']['special_details'] 	= $this->mongo_db->get('special'); 
		}
		
		$data['view_link'] = 'admin/special/edit_special';
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
		$field_id 					= $this->uri->segment(4);
		
		//deleting query
		$this->mongo_db->where(array('_id' => $field_id));
		if($this->mongo_db->delete('special')){
			$this->session->set_flashdata('flash_message', 'delete_success');
			redirect('control/manage-special');
		}
		else{
			$this->session->set_flashdata('flash_message', 'delete_failed');
			redirect('control/manage-special');
		}
	}

}