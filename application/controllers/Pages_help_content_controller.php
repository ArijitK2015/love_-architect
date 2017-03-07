<?php
class Pages_help_content_controller extends CI_Controller {

	/**
	* Responsable for auto load the model
	* @return void
	*/
	public function __construct()
	{
		parent::__construct();
		//Loading all necessary models
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->library('ImageThumb');
		$this->load->library('form_validation');
		
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
		$user_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['data']['myaccount_data'] 	= $this->myaccount_model->get_account_data($user_id);
		
		$all_contents 					= $this->mongo_db->get('pages_help_contents');
		$data['data']['all_contents'] 	= $all_contents;
		
		$data['view_link'] = 'admin/pages_help_contents/index';
		$this->load->view('includes/template', $data);

	}//index

	/**
	* Update item by his id
	* @return void
	*/
	//Function to update existing form fields and add new ones if added
	public function updt()
	{
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$all_existing_menus 			= array();
		$data['data']['all_existing_menus']= $all_existing_menus;
		$id 							= ($this->input->post('type_id') && $this->input->post('type_id')!='') ? strval($this->input->post('type_id')) :'';
		
		
		//for customer details array
		if($id !='')
		{
			$this->mongo_db->select('*');
			$this->mongo_db->where(array('_id' 	=> $id));
			$page_details 					= $this->mongo_db->get('pages_help_contents');
			$data['data']['page_details']	=	$page_details;
		}
		
		//print_r($all_fixed_fields);die;
		$data_to_store = $data_to_store_old = array();
		
		
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $id=='')
		{
			$page_form_id 							= ($this->input->post('page_unique_id') && $this->input->post('page_unique_id')!='') ? strval($this->input->post('page_unique_id')) :'';
			
		
			$data_to_store 			= array();

			//$data_to_store['linkedin_id']	= '';
			//$data_to_store['linkedin_location'] = '';
			
			$fixed_fields 				= $this->input->post('fixed_fields');
			
			$fixed_fields 				= ($fixed_fields) ? $fixed_fields : array();
			
	
			
			if(!empty($fixed_fields))
			{
				foreach($fixed_fields as $f => $field){
					if(!isset($data_to_store[$f]))
					$data_to_store[$f] 		= (is_array($field)) ? (object)($field) :strval($field);
				else
					$data_to_store[$f.'_1'] 	= (is_array($field)) ? (object)($field) :strval($field);
				}
			}
			
			
			
			//echo '<pre>'; print_r($_FILES); echo '</pre>';
			//echo '<pre>'; print_r($data_to_store); echo '</pre>'; die;
			
			
			
				$this->mongo_db->where(array('_id' 	=> $page_form_id));
				$this->mongo_db->set($data_to_store);
				$insert = $this->mongo_db->update('pages_help_contents');
			
			if($insert)
			{
				//$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
					
					$this->session->set_flashdata('flash_message', 'option_updated');
					redirect('control/pages-help-contents');
			} 
			else
			{
				$this->session->set_flashdata('flash_message', 'error_option_update');
				redirect('control/pages-help-contents');
			}
		
				
			redirect('control/pages-help-contents');
		}
		
		if(!isset($data['data']['page_details']))
		{
			redirect('control/pages-help-contents');
		}
		
		
		$data['view_link'] = 'admin/pages_help_contents/edit_pages';
		$this->load->view('includes/template', $data);
	}
    
    
    

	/**
	* Delete static page contents
	* @return void
	*/
	//public function delete()
	//{
	//	//Get logged in session admin id
	//	$user_id 						= ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
	//	$setting_data 					= $this->myaccount_model->get_account_data($user_id);
	//	$data['data']['setting_data'] 	= $setting_data;
	//	$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
	//	$data['data']['dealer_id'] 		= $user_id;
	//	
	//	//getting all admin data 
	//	$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
	//	
	//	
	//	//Get requested id to remove
	//	$field_id 					= $this->uri->segment(4);
	//	
	//	//deleting query
	//	$this->mongo_db->where(array('_id' => $field_id));
	//	if($this->mongo_db->delete('static_contents')){
	//		$this->session->set_flashdata('flash_message', 'delete_success');
	//		redirect('control/static-contents');
	//	}
	//	else{
	//		$this->session->set_flashdata('flash_message', 'delete_failed');
	//		redirect('control/static-contents');
	//	}
	//}//Delete

	
	//public function update_all()
	//{
	//	$edit_contents 	= $this->mongo_db->get('site_users');
	//	if(!empty($edit_contents))
	//	{
	//		foreach($edit_contents as $content)
	//		{
	//			$user_id 						= strval($content['_id']);
	//			$data_to_store['profile_image']	= '1467807003.jpg';
	//			
	//			$this->mongo_db->where(array('_id' => strval($user_id)));
	//			$this->mongo_db->set($data_to_store);
	//			$this->mongo_db->update('site_users');
	//		}
	//	}
	//}

}