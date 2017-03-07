<?php
class Article_controller extends CI_Controller {

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
	var $site_name			= '';
	var $client_secret		= '';
	var $client_id			= '';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Home_model');
		$this->load->model('sitesetting_model');
		$this->load->model('Users_model');
		$this->load->model('User_email_model');
		$this->load->library('ImageThumb');
		
		$this->password_salt 	= $this->config->item('encryption_key');
		$this->password_salt 	= ($this->password_salt) ? $this->password_salt : '12345678';
		
		$settings_data 		= $this->sitesetting_model->get_settings();
		$this->system_timezone	= (isset($settings_data[0]['system_timezone'])) 	? $settings_data[0]['system_timezone'] 		: $this->system_timezone;
		$this->geonames_username = (isset($settings_data[0]['geonames_username'])) ? $settings_data[0]['geonames_username'] 	: $this->geonames_username;
		
		$this->linkedinApiKey	= (isset($settings_data[0]['linkedinApiKey'])) 	? $settings_data[0]['linkedinApiKey'] 		: '';
		$this->linkedinApiSecret = (isset($settings_data[0]['linkedinApiSecret'])) ? $settings_data[0]['linkedinApiSecret'] 	: '';
		
		$this->settings		= (isset($settings_data[0])) 		? $settings_data[0] : '';
		$this->get_company_det 	= $this->uri->segment('1');
		$this->get_company_arr	= explode('-', $this->get_company_det);
		
		$this->cmp_auth_name 	= isset($this->get_company_arr[0]) ? $this->get_company_arr[0] : '';
		$this->cmp_auth_id 		= isset($this->get_company_arr[1]) ? $this->get_company_arr[1] : '';
		
		//Get company details
		$this->mongo_db->where(array('cmp_auth_id' => $this->cmp_auth_id));
		$cmp_details_act 		= $this->mongo_db->get('site_users');
		$this->cmp_details		= (isset($cmp_details_act) && !empty($cmp_details_act)) ? $cmp_details_act : array();
		
		$this->client_secret	= (isset($settings_data[0]['client_secret'])) 	? $settings_data[0]['client_secret'] 	: '';
		$this->client_id		= (isset($settings_data[0]['client_id'])) 		? $settings_data[0]['client_id'] 		: '';
		
		$this->site_name		= (isset($settings_data[0]['site_name'])) 		? $settings_data[0]['site_name'] 		: '';
		
		if(!empty($this->cmp_details))
		{
			$this->cmp_id			= isset($this->cmp_details['_id']) 		? strval($this->cmp_details['_id']) 	: '';		
			$this->site_title 		= isset($this->cmp_details['site_title']) 	? $this->cmp_details['site_title'] 	: $this->settings['site_name'];
			$this->pdesc 			= isset($this->cmp_details['site_meta']) 	? $this->cmp_details['site_meta'] 		: $this->settings['meta_description'];
			$this->pkeys 			= isset($this->cmp_details['site_keyword']) 	? $this->cmp_details['site_keyword'] 	: $this->settings['meta_keywords'];
			$this->site_logo 		= isset($this->cmp_details['site_logo']) 	? $this->cmp_details['site_logo'] 		: '';
			
			if(!empty($this->cmp_auth_name) && !empty($this->cmp_auth_id))
				$this->cmp_auth_link_id	= $this->cmp_auth_name.'-'.$this->cmp_auth_id;
		}
		else
		{
			$this->cmp_auth_name = $this->cmp_auth_id =  $this->site_logo = '';
			
			$this->site_title 		= $this->settings['site_name'];
			$this->pdesc 			= $this->settings['meta_description'];
			$this->pkeys 			= $this->settings['meta_keywords'];
		}
		
		if(!empty($this->cmp_auth_link_id))
			$this->config->set_item('base_url', base_url().$this->cmp_auth_link_id) ;
		
		//if($this->session->userdata('site_is_logged_in'))
		//	redirect('dashboard');
			
		$settings					= $this->sitesetting_model->get_settings();
		$this->data['settings'] 		= $this->sitesetting_model->get_settings();
		
		$this->data['cmp_auth_link']	= isset($this->cmp_auth_link_id) 	? $this->cmp_auth_link_id : '';
		$this->data['cmp_auth_name']	= isset($this->cmp_auth_name) 	? $this->cmp_auth_name 	 : '';
		$this->data['cmp_auth_id']	= isset($this->cmp_auth_id) 		? $this->cmp_auth_id 	 : '';
		
		$this->data['cmp_details']	= isset($this->cmp_details[0]) ? $this->cmp_details[0] : array();
		$this->data['ptitle']		= $this->site_title;
		$this->data['pdesc']		= $this->pdesc;
		$this->data['pkeys']		= $this->pkeys;
		$this->data['site_logo']		= $this->site_logo;
	}
		
	/**
	* Load the main view with all the current model model's data.
	* @return void
	*/
	public function index($id=0)
	{
		$cmp_auth_no 					= isset($this->cmp_details[0]['cmp_auth_id']) ? $this->cmp_details[0]['cmp_auth_id'] : '';
		$cmp_auth_name 				= isset($this->cmp_details[0]['name'])  	 ? $this->cmp_details[0]['name'] 		: '';
		
		$site_name 					= (isset($data['data']['settings'][0]['site_name'])) ? $data['data']['settings'][0]['site_name']  : 'New site';
		
		$user_id 						= $this->session->userdata('site_user_objId_hotcargo');
		$user_type 					= $this->session->userdata('site_user_type_hotcargo');
		$this->data['user_id']			= $user_id;
	
		$this->mongo_db->where(array('menu_type' => '1', 'status' => '1', 'menu_location' => '1'));
		$this->mongo_db->order_by(array('ord_id' => 'asc'));
	
		$users_all_menus 				= $this->mongo_db->get('menus');
		$this->data['users_all_menus']	= (isset($users_all_menus)) ? $users_all_menus : array();
		
		$article_alies 				= $id;
		
		$data['data']					= $this->data;
		
		if($article_alies)
		{
			if($cmp_auth_no)
				$this->mongo_db->where(array( 'page_alias' => $article_alies, 'merchant_id' => $cmp_auth_no, 'status' => "1"));
			else
				$this->mongo_db->where(array( 'page_alias' => $article_alies, 'merchant_id' => '', 'status' => "1"));
				
			$all_details 				= $this->mongo_db->get('static_contents');
				
			if(!empty($all_details))	
				$data['data']['all_details']	= $all_details;
			else
			{
				$this->mongo_db->where(array( 'page_alias' => $article_alies, 'merchant_id' => '', 'status' => "1"));
				$all_details 				= $this->mongo_db->get('static_contents');
				$data['data']['all_details']	= $all_details;
			}
			
			$data['ptitle'] 			= (isset($all_details[0]['page_title'])) ? ucwords($all_details[0]['page_title']).' - '.ucfirst($site_name) : ucfirst($site_name);
			$data['view_link'] 			= 'site/article/index';
			$this->load->view('includes/template_site', $data);
		}
		else
			redirect('');
	}
}
?>