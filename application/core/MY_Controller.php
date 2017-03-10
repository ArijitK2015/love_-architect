<?php
class MY_Controller extends CI_Controller {
		
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
	var $site_fabicon		= '';
	var $cmp_auth_link_id 	= '';
	var $cmp_auth_no		= '';
	var $data				= array();
	var $class_name		= '';
	var $function_name		= '';
		
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Chngpassword_model');
		$this->load->model('myaccount_model');
		$this->load->model('common_model');
		$this->load->model('sitesetting_model');
		$this->load->model('Users_model');
		$this->load->model('myaccount_model');
		$this->load->model('email_template_model');
		$this->load->model('Subadmin_model');
		$this->load->model('Home_model');
		$this->load->model('User_email_model');
		$this->load->library('ImageThumb');
			
		$this->password_salt 	= $this->config->item('encryption_key');
		$this->password_salt 	= ($this->password_salt) ? $this->password_salt : '12345678';
			
		$settings_data 		= $this->sitesetting_model->get_settings();
		$this->system_timezone	= (isset($settings_data[0]['system_timezone'])) 	? $settings_data[0]['system_timezone'] 		: $this->system_timezone;
		$this->geonames_username = (isset($settings_data[0]['geonames_username'])) ? $settings_data[0]['geonames_username'] 	: $this->geonames_username;
			
		$this->settings		= (isset($settings_data[0])) 		? $settings_data[0] : '';
		$this->get_company_det 	= $this->uri->segment('1');
		$this->get_company_arr	= explode('-', $this->get_company_det);
			
		$this->cmp_auth_name 	= isset($this->get_company_arr[0]) ? $this->get_company_arr[0] : '';
		$this->cmp_auth_id 		= isset($this->get_company_arr[1]) ? $this->get_company_arr[1] : '';
			
		//Get company details
		$this->mongo_db->where(array('cmp_auth_id' => $this->cmp_auth_id));
		$cmp_details_act 		= $this->mongo_db->get('site_users');
		$this->cmp_details		= (isset($cmp_details_act[0]) && !empty($cmp_details_act[0])) ? $cmp_details_act[0] : array();
		
		if(!empty($this->cmp_details))
		{
			$this->cmp_id			= isset($this->cmp_details['_id']) 			? strval($this->cmp_details['_id']) 	: '';		
			$this->site_title 		= isset($this->cmp_details['site_title']) 		? $this->cmp_details['site_title'] 	: $this->settings['site_name'];
			$this->pdesc 			= isset($this->cmp_details['meta_description']) 	? $this->cmp_details['meta_description'] 		: $this->settings['meta_description'];
			$this->pkeys 			= isset($this->cmp_details['meta_keywords']) 	? $this->cmp_details['meta_keywords'] 	: $this->settings['meta_keywords'];
			$this->site_logo 		= isset($this->cmp_details['site_logo']) 		? $this->cmp_details['site_logo'] 		: '';
			$this->site_fabicon 		= isset($this->cmp_details['site_fabicon']) 	? $this->cmp_details['site_fabicon'] 		: '';
				
			if(!empty($this->cmp_auth_name) && !empty($this->cmp_auth_id))
				$this->cmp_auth_link_id	= $this->cmp_auth_name.'-'.$this->cmp_auth_id;
		}
		else
		{
			$this->cmp_auth_name = $this->cmp_auth_id =  $this->site_logo = $this->site_fabicon = '';
			
			$this->site_title 		= $this->settings['site_name'];
			$this->pdesc 			= $this->settings['meta_description'];
			$this->pkeys 			= $this->settings['meta_keywords'];
		}
			
		if(!empty($this->cmp_auth_link_id))
			$this->config->set_item('base_url', base_url().$this->cmp_auth_link_id) ;
			
		if(!empty($this->cmp_details))
			$this->data['is_merchant'] = '1';
		else
			$this->data['is_merchant'] = '0';	
			
		$this->class_name 		= $this->router->fetch_class();
		$this->function_name 	= $this->router->fetch_method();	
			
		//print($this->session->userdata('is_logged_in'));die;
		//if($this->session->userdata('is_logged_in'))
		//	redirect('control/admin-dashboard');
			
		$settings						= $this->sitesetting_model->get_settings();
		$this->data['settings'] 		= $this->sitesetting_model->get_settings();
			
		$this->data['cmp_auth_link']	= isset($this->cmp_auth_link_id) 	? $this->cmp_auth_link_id : '';
		$this->data['cmp_auth_name']	= isset($this->cmp_auth_name) 	? $this->cmp_auth_name 	 : '';
		$this->data['cmp_auth_id']		= isset($this->cmp_auth_id) 		? $this->cmp_auth_id 	 : '';
			
		$this->data['cmp_details']		= isset($this->cmp_details[0]) ? $this->cmp_details[0] : array();
		$this->data['ptitle']			= $this->site_title;
		$this->data['pdesc']			= $this->pdesc;
		$this->data['pkeys']			= $this->pkeys;
		$this->data['site_logo']		= $this->site_logo;
		$this->data['site_fabicon']		= $this->site_fabicon;
			
		$user_id 						= $merchant_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;	
			
		if(!empty($this->cmp_auth_id)){
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
			$this->data['admin_details']	= $admin_details;
		}
		else{
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			$this->data['admin_details']	= $admin_details;
		}
			
		if(!$this->session->userdata('is_logged_in')){
			$this->data['settings'] 		= $this->sitesetting_model->get_settings();
			$data					= $this->data;
			$this->load->view('admin/login', $data);	
		}
	}
}