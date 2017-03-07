<?php
class Manage_brokers_controller extends CI_Controller {

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
			
		$this->data['cmp_details']		= isset($this->cmp_details) ? $this->cmp_details : array();
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
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		if(!empty($this->cmp_auth_id))
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		else
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			
		$data['data']['setting_data'] 	= $admin_details;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
			
		//jobs data 
		$data['data']['myaccount_data'] 	= $admin_details;
		
		$all_details = array();
		
		//$this->mongo_db->select(array('_id', 'form_type'));
		$this->mongo_db->select('*');
		if(!empty($this->cmp_auth_id))
		{
			//get all users of this merchant
			$this->mongo_db->where(array('user_type' => 'broker','merchant_id' => $this->cmp_auth_id));
			$all_details 					= $this->mongo_db->get('site_users');	
		}
		else
		{
			$this->mongo_db->where(array('user_type' => 'broker',));
			$all_details 					= $this->mongo_db->get('site_users');
		}
		
		//$all_details 	= $this->mongo_db->get('form_fields');
		
		//echo '<pre>'; print_r($all_details); echo '</pre>'; die;
		
		$data['data']['all_broker']		= $all_details;

		//$data['data']['all_forms']		= array();
		
		$data['view_link'] = 'admin/brokers/index';
		
		
		$this->load->view('includes/template', $data);
	}//index
	
	public function add_broker()
	{
		$data['data']					= $this->data;
		$user_id 			= $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		//Get last user id
		$this->mongo_db->order_by(array('_id' => 'desc'));
		$this->mongo_db->limit(1);
		$get_last_id_arr 			= $this->mongo_db->get('site_users');
		$get_last_id 				= (isset($get_last_id_arr[0]['id'])) ? $get_last_id_arr[0]['id'] : 1;
		
		$reg_type 				= strval('broker');
		$data_to_store 			= array();
		
		$data_to_store['user_type']	= strval($reg_type);
		$data_to_store['id']		= strval($get_last_id + 1);
		//$data_to_store['linkedin_id']	= '';
		//$data_to_store['linkedin_location'] = '';
		
		$fixed_fields 				= $this->input->post('fixed_fields');
		
		$fixed_fields 				= ($fixed_fields) ? $fixed_fields : array();
		
		$extra_fields 				= $this->input->post('extra_fields');
		$extra_fields 				= ($extra_fields) ? $extra_fields : array();
		
		
		if(!empty($fixed_fields))
		{
			foreach($fixed_fields as $f => $field){
				if(!isset($data_to_store[$f]))
					{
						$data_to_store[$f] 		= (is_array($field)) ? (object)($field) :strval($field);
						
						//for storing the country code and name
						if(isset($data_to_store[$f]->lat) && isset($data_to_store[$f]->long))
						{
							$end_country_data 		= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$data_to_store[$f]->lat.'&lng='.$data_to_store[$f]->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
							$end_country_data_arr 	= json_decode($end_country_data);
							$end_country_name 		= isset($end_country_data_arr->countryName) ? $end_country_data_arr->countryName : '';
							$end_country_code 		= isset($end_country_data_arr->countryCode) ? $end_country_data_arr->countryCode : '';
							$data_to_store[$f]->lat 	=(float)$data_to_store[$f]->lat;
							$data_to_store[$f]->lat_str = strval($data_to_store[$f]->lat);
							$data_to_store[$f]->long 	=(float)$data_to_store[$f]->long;
							$data_to_store[$f]->long_str = strval($data_to_store[$f]->long);
							$data_to_store[$f]->country = $end_country_name;
							$data_to_store[$f]->country_code = $end_country_code;
							
						}
					}
					else
					{
						$data_to_store[$f.'_1'] = (is_array($field)) ? (object)($field) :strval($field);
						
						//for storing the country code and name
						if(isset($data_to_store[$f.'_1']->lat) && isset($data_to_store[$f.'_1']->long))
						{
							$end_country_data 		= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$data_to_store[$f.'_1']->lat.'&lng='.$data_to_store[$f.'_1']->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
							$end_country_data_arr 	= json_decode($end_country_data);
							$end_country_name 		= isset($end_country_data_arr->countryName) ? $end_country_data_arr->countryName : '';
							$end_country_code 		= isset($end_country_data_arr->countryCode) ? $end_country_data_arr->countryCode : '';
							$data_to_store[$f.'_1']->lat 	=(float)$data_to_store[$f.'_1']->lat;
							$data_to_store[$f.'_1']->lat_str = strval($data_to_store[$f.'_1']->lat);
							$data_to_store[$f.'_1']->long 	=(float)$data_to_store[$f.'_1']->long;
							$data_to_store[$f.'_1']->long_str = strval($data_to_store[$f.'_1']->long);
							$data_to_store[$f.'_1']->country = $end_country_name;
							$data_to_store[$f.'_1']->country_code = $end_country_code;
							
						}
					}
			}
		}
		
		//echo "<pre>";
		//print_r($data_to_store);die;
		if(!empty($extra_fields))
		{
			$start_pos 	= count($data_to_store);
			foreach($extra_fields as $ef => $efield){
				if(!isset($data_to_store[$ef]))
					$data_to_store[$ef] 		= strval($efield);
				else
					$data_to_store[$ef.'_1']		= strval($efield);
			}
		}
		
		if(isset($data_to_store['password']))
		{
			//Encrype the password with blowfish password encryption
			$enc_password 				= crypt($data_to_store['password'], $this->password_salt);
			$data_to_store['password'] 	= $enc_password;
		}
		
		$data_to_store['admin_status']	= ($this->input->post('admin_status')!='') ? $this->input->post('admin_status') : '1';
		$data_to_store['user_timezone']	= ($this->input->post('user_timezone')!='') ? $this->input->post('user_timezone') : $this->system_timezone;
		$data_to_store['system_timezone']	= $this->system_timezone;
		$data_to_store['password_salt'] 	= $this->password_salt;
		$data_to_store['forgot_pass']		= '0';
		$data_to_store['verify_pass_code']	= '';
		$data_to_store['added_on']		= strval(date('Y-m-d H:i:s'));
		$data_to_store['status']			= strval(1);
		$data_to_store['merchant_id'] 	= ($this->input->post('cmp_auth_id')) ? $this->input->post('cmp_auth_id') : '';
		//echo '<pre>'; print_r($_FILES); echo '</pre>';
		//echo '<pre>'; print_r($data_to_store); echo '</pre>'; die;
		
		//check for email as it should be unique for each users and can not be same
		$do_have_email = (isset($fixed_fields['email'])) ? 1 : '0';
		$do_insert 	= 1;
		
		if($do_have_email)
		{
			$email_id = (isset($fixed_fields['email'])) ? $fixed_fields['email'] : '';
			if($email_id)
			{
				//check for similar email
				if(!empty($this->cmp_details) && !empty($this->cmp_auth_id))
				{
					$this->mongo_db->where(array('email' => $email_id,'merchant_id' => $this->cmp_auth_id));
					$count = $this->mongo_db->count('site_users');
				}
				else
				{
					$this->mongo_db->where(array('email' => $email_id));
					$count = $this->mongo_db->count('site_users');
				}
				
				if($count > 0)
				{
					$this->session->set_flashdata('flash_message', 'email_error');
					redirect('control/manage-brokers');
				}
			}
			else
			{
				$this->session->set_flashdata('flash_message', 'reg_error');
				redirect('control/manage-brokers');
			}
		}
		
		
		if($do_insert)
		{
			if(isset($this->cmp_auth_id) && !empty($this->cmp_auth_id))
			{
				$data_to_store['merchant_id']	= $this->cmp_auth_id;
			}
			$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
		
			if($insert)
			{
				//Updating this user's job countries data
				$data_to_store_country['countries']		= array();
				$data_to_store_country['user_id']			= strval($insert);
				$data_to_store_country['is_all_countries']	= '1';
				
				$this->mongo_db->insert('user_job_countries', $data_to_store_country);
				
				$to_up_files = array();
				
				//upload fixed field profile image
				if(isset($_FILES['fixed_fields']['name']['profile_image']) && !empty($_FILES['fixed_fields']['name']['profile_image']))
				{
					$file_type 			= (isset($_FILES['fixed_fields']['type']['profile_image'])) ? explode('/', $_FILES['fixed_fields']['type']['profile_image']) : array();
					$file_type_det 		= (isset($file_type[0])) ? $file_type[0] : '';
					
					$filename 			= (isset($_FILES['fixed_fields']['name']['profile_image'])) ? substr($_FILES['fixed_fields']['name']['profile_image'],strripos($_FILES['fixed_fields']['name']['profile_image'],'.')) : '';
					$s					= time().$filename;
					$file 				= $_FILES['fixed_fields']['tmp_name']['profile_image'];
					
					$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/user_images/';
					$fileNormal 			= $DIR_IMG_NORMAL.$s;
					$result 				= move_uploaded_file($file, $fileNormal);
					
					if($result)
					{
						$srcPath			= FILEUPLOADPATH.'assets/uploads/user_images/'.$s;
						$destPath1 		= FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$s;
						$destWidth1		= 500;
						$destHeight1		= 500;
						$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
						$image_name		= $s;
						
						$data_to_store_img['profile_image'] = $image_name;
						
						$this->mongo_db->where(array('_id' => $insert));
						$this->mongo_db->set($data_to_store_img);
						$this->mongo_db->update('site_users');
					}
				}
				
				//upload extra field iles
				if(isset($_FILES['extra_fields']['name']) && !empty($_FILES['extra_fields']['name']))
				{
					foreach($_FILES['extra_fields']['name'] as $f => $file_name)
					{
						$to_up_files[$f]['name'] 	= $file_name;
						$to_up_files[$f]['type'] 	= $_FILES['extra_fields']['type'][$f];
						$to_up_files[$f]['tmp_name'] 	= $_FILES['extra_fields']['tmp_name'][$f];
						$to_up_files[$f]['error'] 	= $_FILES['extra_fields']['error'][$f];
						$to_up_files[$f]['size'] 	= $_FILES['extra_fields']['size'][$f];
					}
				}
				
				if(!empty($to_up_files))
				{
					$data_to_store_file = array();
					foreach($to_up_files as $ufld => $up_file)
					{
						$file_type 			= (isset($up_file['type'])) ? explode('/', $up_file['type']) : array();
						$file_type_det 		= (isset($file_type[0])) ? $file_type[0] : '';
						
						$filename 			= (isset($up_file['name'])) ? substr($up_file['name'],strripos($up_file['name'],'.')) : '';
						$s					= time().$filename;
						$file 				= $up_file['tmp_name'];
						
						if($file_type_det == 'image')
						{
							$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/user_images/';
							$fileNormal 			= $DIR_IMG_NORMAL.$s;
							$result 				= move_uploaded_file($file, $fileNormal);
							
							if($result)
							{
								$srcPath		= FILEUPLOADPATH.'assets/uploads/user_images/'.$s;
								$destPath1 	= FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$s;
								$destWidth1	= 500;
								$destHeight1	= 500;
								$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
								$image_name	= $s;
								
								$data_to_store_file[$ufld] = $image_name;
							}
						}
						else
						{
							$DIR_IMG_NORMAL 	= FILEUPLOADPATH.'assets/uploads/user_files/';
							$fileNormal 		= $DIR_IMG_NORMAL.$s;
							$result 			= move_uploaded_file($file, $fileNormal);
							
							if($result) $data_to_store_file[$ufld] = $s;
						}
					}
					
					if(!empty($data_to_store_file))
					{
						$this->mongo_db->where(array('_id' => $insert));
						$this->mongo_db->set($data_to_store_file);
						$this->mongo_db->update('site_users');
					}
				}
				
					//for sending email to user for registration
					$this->mongo_db->where(array('_id' => $insert));
					$job_user_details 	= $this->mongo_db->get('site_users');
					
					$user_email_id 	= isset($job_user_details[0]['email']) ? $job_user_details[0]['email'] : '';
					$to_name 			= isset($job_user_details[0]['first_name']) ? ucwords($job_user_details[0]['first_name'].' '.$job_user_details[0]['last_name']) : '';
					
					//for checking the reply id is parent id or not
					$this->mongo_db->where(array('email_title' => 'reg_success'));
					$email_temp_arr 	= $this->mongo_db->get('email_templates');
					$email_temp		= isset($email_temp_arr[0]) ? $email_temp_arr[0] : '';
					
					//Check for email settings 
					if(!empty($email_temp))
					{
						$search 		= array('[SITE_LOGO]', '[NAME]', '[USER_TYPE]', '[SITE_NAME]');
						$replace 		= array(base_url().'assets/site/images/logo.png', $to_name, 'Broker', $this->site_name);
						
						$email_temp_msg= isset($email_temp['email_template']) 	? $email_temp['email_template'] : '';
						$email_temp_msg= str_replace($search, $replace, $email_temp_msg);
						
						$email_temp_sub= isset($email_temp['email_subject']) 	? $email_temp['email_subject'] : '';
						
						if($user_email_id) $this->User_email_model->send_email($user_email_id, $email_temp_sub, $email_temp_msg, '', '', '', $to_name);
						
					}
					//END
				
					$this->session->set_flashdata('flash_message', 'reg_success');
					redirect('control/manage-brokers');
				}
		} 
		else
		{
			$this->session->set_flashdata('flash_message', 'reg_error');
			redirect('control/manage-brokers');
		}
	
			
		redirect('control/manage-brokers');
	}
	
	
	public function add(){
		
		$data['data']					= $this->data;
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['system_timezone'] 	= $this->system_timezone;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$all_existing_menus 			= array();
		$data['data']['all_existing_menus']= $all_existing_menus;
		
		//for is fixed 1 which means mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'broker'));
		$this->mongo_db->where(array('is_fixed' => '1'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_fixed_fields 					= $this->mongo_db->get('form_fields');
		$data['data']['all_fields_fixed']	=	$all_fixed_fields;
		
		//for other fields which is not mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'broker'));
		$this->mongo_db->where(array('is_fixed' => '0'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_nonfixed_fields 					= $this->mongo_db->get('form_fields');
		$data['data']['all_non_fixed']		= $all_nonfixed_fields;
		//echo "<pre>";
		//print_r($all_nonfixed_fields);die;
		
		$data['view_link'] = 'admin/brokers/add_brokers';
		$this->load->view('includes/template', $data);
		
	}
	
	//Function to update existing form fields and add new ones if added
	public function updt()
	{
		$data['data']					= $this->data;
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['system_timezone'] 	= $this->system_timezone;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$all_existing_menus 			= array();
		$data['data']['all_existing_menus']= $all_existing_menus;
		$broker_id 							= ($this->input->post('brokers_id') && $this->input->post('brokers_id')!='') ? strval($this->input->post('brokers_id')) :'';
		
		
		//for is fixed 1 which means mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'broker'));
		$this->mongo_db->where(array('is_fixed' => '1'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_fixed_fields 					= $this->mongo_db->get('form_fields');
		$data['data']['all_fields_fixed']	=	$all_fixed_fields;
		
		//for other fields which is not mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'broker'));
		$this->mongo_db->where(array('is_fixed' => '0'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_nonfixed_fields 					= $this->mongo_db->get('form_fields');
		$data['data']['all_non_fixed']		= $all_nonfixed_fields;
		
		
		
		
		//for customer details array
		if($broker_id !='')
		{
			$this->mongo_db->select('*');
			$this->mongo_db->where(array('_id' 	=> $broker_id));
			$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
			$broker_details 					= $this->mongo_db->get('site_users');
			$data['data']['broker_details']	=	$broker_details;
		}
		
		//print_r($all_fixed_fields);die;
		$data_to_store = $data_to_store_old = array();
		
		
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $broker_id=='')
		{
			$broker_form_id 							= ($this->input->post('broker_unique_id') && $this->input->post('broker_unique_id')!='') ? strval($this->input->post('broker_unique_id')) :'';
			$this->mongo_db->order_by(array('_id' => 'desc'));
			$this->mongo_db->limit(1);
			$get_last_id_arr 			= $this->mongo_db->get('site_users');
		
			$data_to_store 			= array();

			//Getting all driver details before update
			$this->mongo_db->where(array('_id' => $broker_form_id));
			$broker_details 	= $this->mongo_db->get('site_users');
			
			$old_image 		= (isset($broker_details[0]['profile_image'])) ? $broker_details[0]['profile_image'] : '';
			
			//$data_to_store['linkedin_id']	= '';
			//$data_to_store['linkedin_location'] = '';
			
			$fixed_fields 				= $this->input->post('fixed_fields');
			
			$fixed_fields 				= ($fixed_fields) ? $fixed_fields : array();
			
			$extra_fields 				= $this->input->post('extra_fields');
			$extra_fields 				= ($extra_fields) ? $extra_fields : array();
			
			
			if(!empty($fixed_fields))
			{
				foreach($fixed_fields as $f => $field){
					if(!isset($data_to_store[$f]))
						if(!isset($data_to_store[$f]))
						{
							$data_to_store[$f] 		= (is_array($field)) ? (object)($field) :strval($field);
							
							//for storing the country code and name
							if(isset($data_to_store[$f]->lat) && isset($data_to_store[$f]->long))
							{
								$end_country_data 		= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$data_to_store[$f]->lat.'&lng='.$data_to_store[$f]->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
								$end_country_data_arr 	= json_decode($end_country_data);
								$end_country_name 		= isset($end_country_data_arr->countryName) ? $end_country_data_arr->countryName : '';
								$end_country_code 		= isset($end_country_data_arr->countryCode) ? $end_country_data_arr->countryCode : '';
								$data_to_store[$f]->lat 	=(float)$data_to_store[$f]->lat;
								$data_to_store[$f]->lat_str = strval($data_to_store[$f]->lat);
								$data_to_store[$f]->long 	=(float)$data_to_store[$f]->long;
								$data_to_store[$f]->long_str = strval($data_to_store[$f]->long);
								$data_to_store[$f]->country = $end_country_name;
								$data_to_store[$f]->country_code = $end_country_code;
								
							}
						}
						else
						{
							$data_to_store[$f.'_1'] = (is_array($field)) ? (object)($field) :strval($field);
							
							//for storing the country code and name
							if(isset($data_to_store[$f.'_1']->lat) && isset($data_to_store[$f.'_1']->long))
							{
								$end_country_data 		= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$data_to_store[$f.'_1']->lat.'&lng='.$data_to_store[$f.'_1']->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
								$end_country_data_arr 	= json_decode($end_country_data);
								$end_country_name 		= isset($end_country_data_arr->countryName) ? $end_country_data_arr->countryName : '';
								$end_country_code 		= isset($end_country_data_arr->countryCode) ? $end_country_data_arr->countryCode : '';
								$data_to_store[$f.'_1']->lat 	=(float)$data_to_store[$f.'_1']->lat;
								$data_to_store[$f.'_1']->lat_str = strval($data_to_store[$f.'_1']->lat);
								$data_to_store[$f.'_1']->long 	=(float)$data_to_store[$f.'_1']->long;
								$data_to_store[$f.'_1']->long_str = strval($data_to_store[$f.'_1']->long);
								$data_to_store[$f.'_1']->country = $end_country_name;
								$data_to_store[$f.'_1']->country_code = $end_country_code;
								
							}
						}
				}
			}
			
			if(isset($data_to_store['password']) && trim($data_to_store['password'])!='')
			{
				//Encrype the password with blowfish password encryption
				$enc_password 				= crypt($data_to_store['password'], $this->password_salt);
				$data_to_store['password'] 	= $enc_password;
				$data_to_store['password_salt'] 	= $this->password_salt;
			}
			else
			{
				unset($data_to_store['password']);
			}
			
			$data_to_store['admin_status']	= ($this->input->post('admin_status')!='') ? $this->input->post('admin_status') : '1';
			$data_to_store['user_timezone']	= ($this->input->post('user_timezone')!='') ? $this->input->post('user_timezone') : $this->system_timezone;
			//echo "<pre>";
			//print_r($data_to_store);die;
			if(!empty($extra_fields))
			{
				$start_pos 	= count($data_to_store);
				foreach($extra_fields as $ef => $efield){
					if(!isset($data_to_store[$ef]))
						$data_to_store[$ef] 		= strval($efield);
					else
						$data_to_store[$ef.'_1']		= strval($efield);
				}
			}
			
			
			//echo '<pre>'; print_r($_FILES); echo '</pre>';
			//echo '<pre>'; print_r($data_to_store); echo '</pre>'; die;
			
			//check for email as it should be unique for each users and can not be same
			$do_have_email = (isset($fixed_fields['email'])) ? 1 : '0';
			$do_insert 	= 1;
			
			if($do_have_email)
			{
				$email_id = (isset($fixed_fields['email'])) ? $fixed_fields['email'] : '';
				if($email_id)
				{
					//echo $cust_form_id;
					//check for similar email
					if(!empty($this->cmp_details) && !empty($this->cmp_auth_id))
					{
						$this->mongo_db->where(array('email' => $email_id,'merchant_id' => $this->cmp_auth_id));
						$all_email=$this->mongo_db->get('site_users');
						
						if(count($all_email) > 0)
						{
							foreach($all_email as $email)
							{
								if($email['_id']!=$broker_form_id)
								{
									$this->session->set_flashdata('flash_message', 'email_error');
									redirect('control/manage-customers');
								}
							}
							
						}
					}
					else
					{
						$this->mongo_db->where(array('email' => $email_id));
						$all_email=$this->mongo_db->get('site_users');
						
						if(count($all_email) > 0)
						{
							foreach($all_email as $email)
							{
								if($email['_id']!=$broker_form_id)
								{
									$this->session->set_flashdata('flash_message', 'email_error');
									redirect('control/manage-customers');
								}
							}
							
						}
					}
				}
				else
				{
					$this->session->set_flashdata('flash_message', 'reg_error');
					redirect('control/manage-brokers');
				}
			}
			
			
			if($do_insert)
			{
				//$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
				
				$this->mongo_db->where(array('_id' 	=> $broker_form_id));
				$this->mongo_db->set($data_to_store);
				$insert = $this->mongo_db->update('site_users');
			
				if(isset($insert))
				{
					$to_up_files = array();
					
					//upload fixed field profile image
					if(isset($_FILES['fixed_fields']['name']['profile_image']) && !empty($_FILES['fixed_fields']['name']['profile_image']))
					{
						$file_type 			= (isset($_FILES['fixed_fields']['type']['profile_image'])) ? explode('/', $_FILES['fixed_fields']['type']['profile_image']) : array();
						$file_type_det 		= (isset($file_type[0])) ? $file_type[0] : '';
						
						$filename 			= (isset($_FILES['fixed_fields']['name']['profile_image'])) ? substr($_FILES['fixed_fields']['name']['profile_image'],strripos($_FILES['fixed_fields']['name']['profile_image'],'.')) : '';
						$s					= time().$filename;
						$file 				= $_FILES['fixed_fields']['tmp_name']['profile_image'];
						
						$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/user_images/';
						$fileNormal 			= $DIR_IMG_NORMAL.$s;
						$result 				= move_uploaded_file($file, $fileNormal);
						
						if($result)
						{
							if($old_image)
							{
								@unlink(FILEUPLOADPATH.'assets/uploads/user_images/'.$old_image);
								@unlink(FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$old_image);
							}
							
							$srcPath			= FILEUPLOADPATH.'assets/uploads/user_images/'.$s;
							$destPath1 		= FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$s;
							$destWidth1		= 500;
							$destHeight1		= 500;
							$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
							$image_name		= $s;
							
							$data_to_store_img['profile_image'] = $image_name;
							
							$this->mongo_db->where(array('_id' => $broker_form_id));
							$this->mongo_db->set($data_to_store_img);
							$this->mongo_db->update('site_users');
						}
					}
					
					//upload extra field iles
					if(isset($_FILES['extra_fields']['name']) && !empty($_FILES['extra_fields']['name']))
					{
						foreach($_FILES['extra_fields']['name'] as $f => $file_name)
						{
							$to_up_files[$f]['name'] 	= $file_name;
							$to_up_files[$f]['type'] 	= $_FILES['extra_fields']['type'][$f];
							$to_up_files[$f]['tmp_name'] 	= $_FILES['extra_fields']['tmp_name'][$f];
							$to_up_files[$f]['error'] 	= $_FILES['extra_fields']['error'][$f];
							$to_up_files[$f]['size'] 	= $_FILES['extra_fields']['size'][$f];
						}
					}
					
					if(!empty($to_up_files))
					{
						$data_to_store_file = array();
						foreach($to_up_files as $ufld => $up_file)
						{
							$file_type 			= (isset($up_file['type'])) ? explode('/', $up_file['type']) : array();
							$file_type_det 		= (isset($file_type[0])) ? $file_type[0] : '';
							
							$filename 			= (isset($up_file['name'])) ? substr($up_file['name'],strripos($up_file['name'],'.')) : '';
							$s					= time().$filename;
							$file 				= $up_file['tmp_name'];
							
							if($file_type_det == 'image')
							{
								$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/user_images/';
								$fileNormal 			= $DIR_IMG_NORMAL.$s;
								$result 				= move_uploaded_file($file, $fileNormal);
								
								if($result)
								{
									$old_data 	= (isset($broker_details[0][$ufld])) ? $broker_details[0][$ufld] : '';
									if($old_data)
									{
										@unlink(FILEUPLOADPATH.'assets/uploads/user_images/'.$old_data);
										@unlink(FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$old_data);
									}
									
									$srcPath		= FILEUPLOADPATH.'assets/uploads/user_images/'.$s;
									$destPath1 	= FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$s;
									$destWidth1	= 500;
									$destHeight1	= 500;
									$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
									$image_name	= $s;
									
									$data_to_store_file[$ufld] = $image_name;
								}
							}
							else
							{
								$DIR_IMG_NORMAL 	= FILEUPLOADPATH.'assets/uploads/user_files/';
								$fileNormal 		= $DIR_IMG_NORMAL.$s;
								$result 			= move_uploaded_file($file, $fileNormal);
								
								if($result){
									$old_data 	= (isset($broker_details[0][$ufld])) ? $broker_details[0][$ufld] : '';
									if($old_data)
										@unlink(FILEUPLOADPATH.'assets/uploads/user_files/'.$old_data);
									
									$data_to_store_file[$ufld] = $s;
								}
							}
						}
						
						if(!empty($data_to_store_file))
						{
							$this->mongo_db->where(array('_id' => $broker_form_id));
							$this->mongo_db->set($data_to_store_file);
							$this->mongo_db->update('site_users');
						}
					}
					
				}
					
					$this->session->set_flashdata('flash_message', 'option_updated');
					redirect('control/manage-brokers');
			} 
			else
			{
				$this->session->set_flashdata('flash_message', 'error_option_update');
				redirect('control/manage-brokers');
			}
		
				
			redirect('control/manage-brokers');
		}
		
		if(!isset($data['data']['broker_details']))
		{
			redirect('control/manage-brokers');
		}
		
		
		$data['view_link'] = 'admin/brokers/edit_brokers';
		$this->load->view('includes/template', $data);
	}
	
	//Function to delete all fields of a type
	public function remove_all_cat_field()
	{
		//Get logged in session admin id
		$user_id 						= ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//getting all admin data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		
		//Get requested form type from url segment position 3
		$field_id 					= $this->uri->segment(3);
		
		//deleting query
		$this->mongo_db->where(array('form_type' => $field_id));
		if($this->mongo_db->delete_all('form_fields'))
			$this->session->set_flashdata('flash_message', 'info_deleted');
		else
			$this->session->set_flashdata('flash_message', 'info_delete_failed');
			
		redirect('control/data-forms');
	}
	
	
	//Ajax function to - Remove existing form fields 
	public function remove_form_field()
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
		$field_id 					= $this->input->get('form_id');
		
		//deleting query
		$this->mongo_db->where(array('field_name' => $field_id));
		if($this->mongo_db->delete('form_fields'))
			echo 1;
		else
			echo 0;
	}

}