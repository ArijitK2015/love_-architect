<?php
class Menu_controller extends CI_Controller {

   
	public function __construct()
	{
		parent::__construct();
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->model('User_email_model');
		$this->load->library('ImageThumb');
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
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$this->mongo_db->where(array('menu_type' => '0'));
		$all_admin_menus 				= $this->mongo_db->get('menus');
		
		$this->mongo_db->where(array('menu_type' => '1'));
		$all_site_menus 				= $this->mongo_db->get('menus');
		
		$data['data']['all_admin_menus']	= $all_admin_menus;
		$data['data']['all_site_menus']	= $all_site_menus;

		//$data['data']['all_forms']		= array();
		
		$data['view_link'] = 'admin/menus/index';
		$this->load->view('includes/template', $data);
	}
	
	public function show_menus()
	{
		$menu_type = $this->uri->segment(3);
		$user_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		if($menu_type 		== 'admin')	$this->mongo_db->where(array('menu_type' => '0'));
		elseif($menu_type 	== 'site')	$this->mongo_db->where(array('menu_type' => '1'));
		$all_menus 					= $this->mongo_db->get('menus');
		
		$data['data']['all_menus']		= $all_menus;

		//$data['data']['all_forms']		= array();
		
		$data['view_link'] = 'admin/menus/show_menus';
		$this->load->view('includes/template', $data);
	}
}
?>