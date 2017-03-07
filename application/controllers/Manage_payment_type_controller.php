<?php
class Manage_payment_type_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		//Loading all necessary models
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->model('Url_generator_model');
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
		$all_details 					= $this->mongo_db->get('payment_types');
		
		$data['data']['all_types']		= $all_details;

		$data['view_link'] = 'admin/payment_types/index';
		
		
		$this->load->view('includes/template', $data);
	}//index
	
	public function add_payment_types()
	{
		$user_id 					= ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		//Get last user id
		
		$data_to_store['title']			= strval($this->input->post('title'));
		$data_to_store['sort_code']		= $this->Url_generator_model->title_generate($data_to_store['title']);
		$data_to_store['pay_type']		= strval($this->input->post('pay_type'));
		
		$data_to_store['extra_percent']			= '0';
		$data_to_store['reduct_percent']		= '0';
		$data_to_store['max_days']				= '0';
		
		if($data_to_store['pay_type']!='1')
		{
			$data_to_store['extra_percent']			= ($this->input->post('percent')!='' && $this->input->post('percent_type')=='1') ? strval($this->input->post('percent')) : '0';
			$data_to_store['reduct_percent']		= ($this->input->post('percent')!='' && $this->input->post('percent_type')=='0') ? strval($this->input->post('percent')) : '0';
			$data_to_store['max_days']				= ($this->input->post('max_days')!='' && $data_to_store['pay_type']=='3') ? strval($this->input->post('max_days')) : '0';
		}
		//echo $this->input->post('reduct_percent');
		//echo "asdasd".$this->input->post('percent_type');
		//echo 'check1'.$data_to_store['extra_percent'];
		//echo 'check2'.$data_to_store['reduct_percent'];
		//echo 'check3'.$data_to_store['max_days'];
		//die;
		$data_to_store['status']			= strval($this->input->post('status'));
		
		$insert	= $this->mongo_db->insert('payment_types', $data_to_store);
			
		if($insert)
		{
			$this->session->set_flashdata('flash_message', 'insert_success');
			redirect('control/manage-payment-types');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'insert_failed');
			redirect('control/manage-payment-types');
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
		
		$data['view_link'] = 'admin/payment_types/add_types';
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
			$data_to_store['sort_code']		= $this->Url_generator_model->title_generate($data_to_store['title']);
			$data_to_store['pay_type']		= strval($this->input->post('pay_type'));
			
			$data_to_store['extra_percent']			= '0';
			$data_to_store['reduct_percent']		= '0';
			$data_to_store['max_days']				= '0';
			
			
			if($data_to_store['pay_type']!='1')
			{
				$data_to_store['extra_percent']			= ($this->input->post('percent')!='' && $this->input->post('percent_type')=='1') ? strval($this->input->post('percent')) : '0';
				$data_to_store['reduct_percent']		= ($this->input->post('percent')!='' && $this->input->post('percent_type')=='0') ? strval($this->input->post('percent')) : '0';
				$data_to_store['max_days']				= ($this->input->post('max_days')!='' && $data_to_store['pay_type']=='3') ? strval($this->input->post('max_days')) : '0';
			}
			
		//echo $this->input->post('reduct_percent');
		//echo "asdasd".$this->input->post('percent_type');
		//echo 'check1'.$data_to_store['extra_percent'];
		//echo 'check2'.$data_to_store['reduct_percent'];
		//echo 'check3'.$data_to_store['max_days'];
		//die;
		
		$data_to_store['status']			= strval($this->input->post('status'));
		
		//$insert	= $this->mongo_db->insert('payment_types', $data_to_store);
		
			
		
			$this->mongo_db->where(array('_id' => $types_form_id));
			$this->mongo_db->set($data_to_store);
			//$this->mongo_db->update('types');
		
			if($this->mongo_db->update('payment_types'))
			{
				$this->session->set_flashdata('flash_message', 'type_updated');
				redirect('control/manage-payment-types');
			} 
			else
			{
				$this->session->set_flashdata('flash_message', 'error_option_update');
				redirect('control/manage-payment-types');
			}
		}
		else{
			$types_id 					= ($this->input->post('type_id') && $this->input->post('type_id')!='') ? strval($this->input->post('type_id')) :'';
			if(empty($types_id))
				redirect('control/manage-payment-types');
			
			$this->mongo_db->where(array('_id' => $types_id));
			$data['data']['type_details'] 	= $this->mongo_db->get('payment_types'); 
		}
		
		$data['view_link'] = 'admin/payment_types/edit_types';
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
		if($this->mongo_db->delete('payment_types')){
			$this->session->set_flashdata('flash_message', 'delete_success');
			redirect('control/manage-payment-types');
		}
		else{
			$this->session->set_flashdata('flash_message', 'delete_failed');
			redirect('control/manage-payment-types');
		}
	}

}