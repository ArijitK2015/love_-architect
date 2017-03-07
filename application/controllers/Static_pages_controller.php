<?php
class Static_pages_controller extends CI_Controller {

	/**
	* Responsable for auto load the model
	* @return void
	*/
	
	var $password_salt 		= '12345678';
	var $system_timezone 	= 'UTC';
	var $geonames_username	= "arijit2016";
	var $linkedinApiKey		= '';
	var $linkedinApiSecret 	= '';
		
	var $get_company_det 	= '';
	var $get_company_arr 	= '';
	var $cmp_auth_name		= '';
	var $cmp_auth_id		= '';
	var $cmp_details 		= '';
	var $cmp_id			= '';
	var $settings			= '';
	var $site_title 		= '';
	var $pdesc			= '';
	var $pkeys			= '';
	var $site_logo			= '';
	var $cmp_auth_link_id 	= '';
	var $cmp_auth_no		= '';
	var $data				= array();
	
	public function __construct()
	{
		parent::__construct();
		//Loading all necessary models
		
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->library('ImageThumb');
		$this->load->library('form_validation');
		$this->load->library('encryption');
		$this->load->model('Home_model');
		$this->load->model('sitesetting_model');
		$this->load->model('Users_model');
		$this->load->library('ImageThumb');
			
		$this->load->model('Check_line_intersect_model');
		$this->load->model('Check_map_lines_model');
		$this->load->model('User_email_model');
		$this->load->model('User_notifications_model');
			
		//Getting site settings data
		$settings_data 				= $this->sitesetting_model->get_settings();
		$this->extra_percent_charge 		= (isset($settings_data[0]['refundable_deposit_percent'])) ? $settings_data[0]['refundable_deposit_percent'] : $this->extra_percent_charge;
		$this->limit_page 				=  (isset($settings_data[0]['site_pagination'])) ? $settings_data[0]['site_pagination'] : $this->limit_page;
			
		//Added php gzip compression
		$controller_name 				= $this->router->fetch_class();
		$function_name 				= $this->router->fetch_method();
			
		$this->password_salt 			= $this->config->item('encryption_key');
		$this->password_salt 			= ($this->password_salt) ? $this->password_salt : '12345678';
			
		$this->system_timezone			= (isset($settings_data[0]['system_timezone'])) 	? $settings_data[0]['system_timezone'] 		: $this->system_timezone;
		$this->geonames_username 		= (isset($settings_data[0]['geonames_username'])) ? $settings_data[0]['geonames_username'] 	: $this->geonames_username;
			
		$this->linkedinApiKey			= (isset($settings_data[0]['linkedinApiKey'])) 	? $settings_data[0]['linkedinApiKey'] 		: '';
		$this->linkedinApiSecret 		= (isset($settings_data[0]['linkedinApiSecret'])) ? $settings_data[0]['linkedinApiSecret'] 	: '';
			
		$this->settings				= (isset($settings_data[0])) 		? $settings_data[0] : '';
		$this->get_company_det 			= $this->uri->segment('1');
		$this->get_company_arr			= explode('-', $this->get_company_det);
			
		$this->cmp_auth_name 			= isset($this->get_company_arr[0]) ? $this->get_company_arr[0] : '';
		$this->cmp_auth_id 				= isset($this->get_company_arr[1]) ? $this->get_company_arr[1] : '';
			
		//Get company details
		$this->mongo_db->where(array('cmp_auth_id' => $this->cmp_auth_id));
		$cmp_details_act 				= $this->mongo_db->get('site_users');
		$this->cmp_details				= (isset($cmp_details_act[0]) && !empty($cmp_details_act[0])) ? $cmp_details_act[0] : array();
			
		if(!empty($this->cmp_details))
		{
			$this->cmp_id				= isset($this->cmp_details['_id']) 		? strval($this->cmp_details['_id']) 	: '';		
			$this->site_title 			= isset($this->cmp_details['site_title']) 	? $this->cmp_details['site_title'] 	: $this->settings['site_name'];
			$this->pdesc 			= isset($this->cmp_details['meta_description']) 	? $this->cmp_details['meta_description'] 		: $this->settings['meta_description'];
			$this->pkeys 			= isset($this->cmp_details['meta_keywords']) 	? $this->cmp_details['meta_keywords'] 	: $this->settings['meta_keywords'];
			$this->site_logo 			= isset($this->cmp_details['site_logo']) 	? $this->cmp_details['site_logo'] 		: '';
				
			if(!empty($this->cmp_auth_name) && !empty($this->cmp_auth_id))
				$this->cmp_auth_link_id	= $this->cmp_auth_name.'-'.$this->cmp_auth_id;
		}
		else
		{
			$this->cmp_auth_name 		= $this->cmp_auth_id =  $this->site_logo = '';
			$this->site_title 			= $this->settings['site_name'];
			$this->pdesc 				= $this->settings['meta_description'];
			$this->pkeys 				= $this->settings['meta_keywords'];
		}
			
		if(!empty($this->cmp_auth_link_id))
			$this->config->set_item('base_url', base_url().$this->cmp_auth_link_id) ;
				
		$settings						= $this->sitesetting_model->get_settings();
		$this->data['settings'] 			= $this->sitesetting_model->get_settings();
			
		$this->data['cmp_auth_link']		= isset($this->cmp_auth_link_id) 	? $this->cmp_auth_link_id : '';
		$this->data['cmp_auth_name']		= isset($this->cmp_auth_name) 	? $this->cmp_auth_name 	 : '';
		$this->data['cmp_auth_id']		= isset($this->cmp_auth_id) 		? $this->cmp_auth_id 	 : '';
			
		$this->data['cmp_details']		= isset($this->cmp_details[0]) ? $this->cmp_details[0] : array();
		$this->data['ptitle']			= $this->site_title;
		$this->data['pdesc']			= $this->pdesc;
		$this->data['pkeys']			= $this->pkeys;
		$this->data['site_logo']			= $this->site_logo;
			
		$user_id 						= $merchant_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;	
			
		if(!empty($this->cmp_auth_id)){
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
			$this->data['admin_details']	= $admin_details;
		}
		else{
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			$this->data['admin_details']	= $admin_details;
		}
			
		//if admin is not logged in redirect to login page and this should at the end of construct as base url is change before it.
		if(!$this->session->userdata('is_logged_in')){
			redirect('control');
		}
	}
	
	/**
	* Load the main view with all the current model model's data.
	* @return void
	*/
	public function index()
	{
		$data['data']					= $this->data;
		$user_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		if(!empty($this->cmp_auth_id))
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		else
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			
		$data['data']['setting_data'] 	= $admin_details;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
			
		//jobs data 
		$data['data']['myaccount_data'] 	= $admin_details;
		
		if(!empty($this->cmp_auth_id))
		{
			$this->mongo_db->where(array('merchant_id'=>$this->cmp_auth_id));
			//$this->mongo_db->where_ne('merchant_id','1234');
		}
		else
			$this->mongo_db->where(array('merchant_id' => ''));
			
		$all_contents 					= $this->mongo_db->get('static_contents');
		$data['data']['all_contents'] 	= $all_contents;
		
		$data['view_link'] = 'admin/static_pages/index';
		$this->load->view('includes/template', $data);

	}//index

	public function add()
	{
		$data['data']					= $this->data;
		$user_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['data']['myaccount_data'] 	= $this->myaccount_model->get_account_data($user_id);
		
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			//form validation
			//$this->form_validation->set_rules('page_title', 'Page Title', 'required|trim');
			//$this->form_validation->set_rules('page_content', 'Page Content', 'required');
			//$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
			//
			////if the form has passed through the validation
			//if ($this->form_validation->run())
			//{
				$title_alias 	= str_replace(" ","-",$this->input->post('page_title'));
				$title_alias 	= strtolower($title_alias);
				$data_to_store = array(
								'page_title' 		=> strval($this->input->post('page_title')),
								'meta_tag' 		=> strval($this->input->post('page_tag')),
								'meta_keywords' 	=> strval($this->input->post('page_key')),
								'meta_description'	=> strval($this->input->post('meta_description')),
								'page_alias' 		=> strval($this->input->post('page_alias')),
								'page_content' 	=> strval($this->input->post('page_content')),
								'merchant_id'		=> strval($this->input->post('cmp_auth_id')),
								'status' 			=> strval('1')
							);
				
				$insert 	= $this->mongo_db->insert('static_contents', $data_to_store);
		
				if($insert)
					$this->session->set_flashdata('flash_message', 'insert_success');
				else
					$this->session->set_flashdata('flash_message', 'insert_failed');
				
				redirect('control/static-contents');
			//}
		}
		
		$data['view_link'] = 'admin/static_pages/add_content';
		$this->load->view('includes/template', $data);
	}

	/**
	* Update item by his id
	* @return void
	*/
	public function updt()
	{
		$data['data']					= $this->data;
		//product id 
		$id = $this->input->post('type_id');
			
		$user_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
			
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
			
		//settings data 
		$data['data']['myaccount_data'] 	= $this->myaccount_model->get_account_data($user_id);
			
		$edit_id 						= $this->input->post('edit_id');
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $edit_id != '')
		{
			$title_alias = str_replace(" ","-",$this->input->post('page_title'));
			$title_alias = strtolower($title_alias);
			
			$data_to_store = array(
					'page_title' 		=> strval($this->input->post('page_title')),
					'meta_tag' 		=> strval($this->input->post('page_tag')),
					'meta_keywords' 	=> strval($this->input->post('page_key')),
					'meta_description'	=> strval($this->input->post('meta_description')),
					'page_alias' 		=> strval($this->input->post('page_alias')),
					'page_content' 	=> strval($this->input->post('page_content')),
					'merchant_id'		=> strval($this->input->post('cmp_auth_id')),
					'status' 			=> strval('1')
				);
				
			$this->mongo_db->where(array('_id' => strval($edit_id)));
			$this->mongo_db->set($data_to_store);
			
			//if the insert has returned true then we show the flash message
			if($this->mongo_db->update('static_contents'))
			    $this->session->set_flashdata('flash_message', 'pages_updated');
			else
			    $this->session->set_flashdata('flash_message', 'pages_not_updated');
			
			redirect('control/static-contents');
		}
		
		
		$this->mongo_db->where(array('_id' => $id));
		$edit_contents 				= $this->mongo_db->get('static_contents');
		$data['data']['page_content'] 	= $edit_contents;
		
		if(count($edit_contents)==0)
		{
			redirect('control/static-contents');
		}
		$data['view_link'] 				= 'admin/static_pages/edit_content';
		$this->load->view('includes/template', $data); 
	}//update
    
    
    

	/**
	* Delete static page contents
	* @return void
	*/
	public function delete()
	{
		$data['data']					= $this->data;
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
		if($this->mongo_db->delete('static_contents')){
			$this->session->set_flashdata('flash_message', 'delete_success');
			redirect('control/static-contents');
		}
		else{
			$this->session->set_flashdata('flash_message', 'delete_failed');
			redirect('control/static-contents');
		}
	}//Delete

	
	public function update_all()
	{
		$edit_contents 	= $this->mongo_db->get('site_users');
		if(!empty($edit_contents))
		{
			foreach($edit_contents as $content)
			{
				$user_id 						= strval($content['_id']);
				$data_to_store['profile_image']	= '1467807003.jpg';
				
				$this->mongo_db->where(array('_id' => strval($user_id)));
				$this->mongo_db->set($data_to_store);
				$this->mongo_db->update('site_users');
			}
		}
	}

}