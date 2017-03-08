<?php
class Admin_dashboard extends CI_Controller {
 
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
			
		$this->load->model('common_model');
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->library('encryption');
		$this->load->model('Users_model');
		$this->load->model('common_model');
		$this->load->model('Home_model');
		$this->load->library('ImageThumb');
				
		$this->password_salt 			= $this->config->item('encryption_key');
		$this->password_salt 			= ($this->password_salt) ? $this->password_salt : '12345678';
		
		$settings_data 				= $this->sitesetting_model->get_settings();
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
			$this->pdesc 				= isset($this->cmp_details['meta_description']) 	? $this->cmp_details['meta_description'] 		: $this->settings['meta_description'];
			$this->pkeys 				= isset($this->cmp_details['meta_keywords']) 	? $this->cmp_details['meta_keywords'] 	: $this->settings['meta_keywords'];
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
		
		$user_id 						= $merchant_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 1;	
			
		if(!empty($this->cmp_auth_id)){
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
			$this->data['admin_details']	= $admin_details;
		}
		else{
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			$this->data['admin_details']	= $admin_details;
		}
		
		
		//get all session data if any	
		$user_session_data 				= $this->session->userdata();
		if(!empty($user_session_data))
		{
			$admin_login_session		= $this->session->userdata('admin_login_session');
			$logged_in_user_id			= $this->session->userdata('user_id_lovearchitect');
			$is_merchant				= $this->session->userdata('is_merchant');
			$is_superadmin				= $this->session->userdata('is_superadmin');
				
			if($admin_login_session)
			{
				//if user loged in as a merchant and try to access the superadmin content then restrict it
				if($is_merchant == 1 && empty($this->cmp_details)) 	{
					$this->session->sess_destroy();
					redirect('control');
				}
				//if superadmin is logged in and try to access the merchant content then restrict it 
				elseif($is_superadmin == 1 && !empty($this->cmp_details)){
					$this->session->sess_destroy();
					redirect('control');
				}
			}
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
		
		//getting dealers id
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		
		if(!empty($this->cmp_auth_id))
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		else
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
				
		$data['data']['setting_data'] 	= $admin_details;
		$data['data']['admin_id']		= $user_id;
		$data['data']['admin_details'] 	= $admin_details;
		$data['data']['profile_image'] 	= $admin_details;
			
		$data['view_link'] 				= 'admin/dashboard_page';
			
		$this->load->view('includes/template', $data);
	}
	
	public function aasort (&$array, $key) {
		$sorter	= array();
		$ret		= array();
		reset($array);
		foreach ($array as $ii => $va) {
			$sorter[$ii]	= $va[$key];
		}
		arsort($sorter);
		foreach ($sorter as $ii => $va) {
			$ret[$ii]		= $array[$ii];
		}
		$array			= $ret;
		
		return $array;
	}
	
	public function add_county_data()
	{
		//$user_id = ($this->session->userdata('user_id_dailycarlist')) ?  $this->session->userdata('user_id_dailycarlist') : 0;
		//$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		//$data['data']['setting_data'] 	= $setting_data;
		//$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		//
		//if(isset($_FILES['data_file']['name']))
		//{
		//	$fileNormal 	= $_FILES['data_file']['tmp_name'];
		//	
		//	$this->load->library('excel');	//load the excel library
		//	//$objPHPExcel 		= PHPExcel_IOFactory::load($file);	//read file from path
		//	$objPHPExcel 		= PHPExcel_IOFactory::load($fileNormal);
		//	$cell_collection 	= $objPHPExcel->getActiveSheet()->getCellCollection();	//get only the Cell Collection
		//	$i = 0;
		//	foreach ( $cell_collection as $k => $cell ) {
		//		
		//		$column 		= $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		//		$row 		= $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		//		$data_value 	= $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		//		//header will/should be in row 1 only. of course this can be modified to suit your need.
		//			
		//		if ( $row == 1 ) 
		//			$header[$row][$column] = $data_value;
		//		else {
		//			$i1=0;
		//			$arr_data[$row][$column] 	= $data_value;
		//			$total_data[$row][]			= $data_value;
		//		}
		//		$i++;
		//	}
		//	
		//	//$i=$i1=0;
		//	foreach($total_data as $d=>$data){
		//		
		//		$county_name_det = isset($data[0]) ? $data[0] : '';
		//		$county_name_det = str_replace('http://', '', $county_name_det);
		//		$county_name_det = str_replace('.craigslist.org/', '', $county_name_det);
		//		$county_name_det = trim($county_name_det);
		//		
		//		$zip_code 	  = isset($data[1]) ? $data[1] : '';
		//		
		//		$data_to_store['county_name'] =  $county_name_det;	
		//		$data_to_store['zip_code'] 	=  $zip_code;	
		//		$data_to_store['status'] 	=  1;
		//		
		//		$this->common_model->add('county_zip_list', $data_to_store);
		//	}
		//}
		//
		//$data['view_link'] = 'admin/county_list_add';
		//$this->load->view('includes/template', $data);
		
		$all_data = $this->db->order_by('id', 'desc')->get('data_details')->result_array();
		
		if(!empty($all_data)){
			
			foreach($all_data as $data){
				$data_to_save = array();
				$make_model = $data['searchcriteria'];
				$new_details = explode('+', $make_model);
				
				$make_year 	= isset($new_details[0]) ? $new_details[0] : '';
				$maker 		= isset($new_details[1]) ? $new_details[1] : '';
				$maker 		= ($maker == 'GTO') ? 'Ford' : $maker;
				$make_model 	= isset($new_details[2]) ? $new_details[2] : '';
				$make_model    = ($make_model == 'Convertible') ? '' : $make_model;
				
				$data_to_save['itemyear'] 	= $make_year;
				$data_to_save['itemmake'] 	= $maker;
				$data_to_save['itemmodel'] 	= $make_model;
				
				echo '<pre>'; print_r($data_to_save); echo '</pre>';
				
				//$this->common_model->update('data_details', $data_to_save, array('id', $data['id']));
				
				$this->db->where('id', $data['id']);
				$this->db->update('data_details', $data_to_save); 
				
			}
		}
	}
}