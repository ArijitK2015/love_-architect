<?php
class Home_controllers extends CI_Controller {
		
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
	var $site_favicon		= '';
	var $cmp_auth_link_id 	= '';
	var $cmp_auth_no		= '';
	var $data				= array();
		
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
		$this->cmp_details		= (isset($cmp_details_act[0]) && !empty($cmp_details_act[0])) ? $cmp_details_act[0] : array();
			
		if(!empty($this->cmp_details))
		{
			$this->cmp_id			= isset($this->cmp_details['_id']) 		? strval($this->cmp_details['_id']) 	: '';		
			$this->site_title 		= isset($this->cmp_details['site_title']) 	? $this->cmp_details['site_title'] 	: $this->settings['site_name'];
			$this->pdesc 			= isset($this->cmp_details['site_meta']) 	? $this->cmp_details['site_meta'] 		: $this->settings['meta_description'];
			$this->pkeys 			= isset($this->cmp_details['site_keyword']) 	? $this->cmp_details['site_keyword'] 	: $this->settings['meta_keywords'];
			$this->site_logo 		= isset($this->cmp_details['site_logo']) 	? $this->cmp_details['site_logo'] 		: '';
			$this->site_favicon		= isset($this->cmp_details['site_fabicon']) 	? $this->cmp_details['site_fabicon'] 	: '';
			
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
		$this->data['site_fav_icon']		= $this->site_favicon;
			
		//if($this->session->userdata('site_is_logged_in'))
		//	redirect('dashboard');
	}
		
		
	public function index()
	{
		$cmp_auth_no 						= isset($this->cmp_details[0]['cmp_auth_id']) ? $this->cmp_details[0]['cmp_auth_id'] : '';
		$cmp_auth_name 					= isset($this->cmp_details[0]['name'])  	 ? $this->cmp_details[0]['name'] 		: '';
			
		$cookie_email 						= $this->input->cookie('email_id',true);
		$cookie_pass 						= $this->input->cookie('password',true);
		$cookie_rem 						= $this->input->cookie('do_remember',true);
		$cookie_user_type 					= $this->input->cookie('user_type',true);
			
		$this->data['cookie_email']			= $cookie_email;
		$this->data['cookie_pass']			= $cookie_pass;
		$this->data['cookie_rem']			= $cookie_rem;
		$this->data['cookie_user_type']		= $cookie_user_type;
			
		//Get all forl type fields
		$customer_types_det 				= $this->Home_model->form_types_det('customer');
		$driver_types_det 					= $this->Home_model->form_types_det('driver');
		$broker_types_det 					= $this->Home_model->form_types_det('broker');
		$fleet_types_det 					= $this->Home_model->form_types_det('fleet');
		$depot_types_det 					= $this->Home_model->form_types_det('depot');
			
		//echo '<pre>'; print_r($customer_types_det); echo '</pre>';
			
		$this->data['customer_types_det'] 		= $customer_types_det;
		$this->data['driver_types_det'] 		= $driver_types_det;
		$this->data['broker_types_det'] 		= $broker_types_det;
		$this->data['fleet_types_det'] 		= $fleet_types_det;
		$this->data['depot_types_det'] 		= $depot_types_det;
			
		//echo '<pre>'; print_r($data); die;
			
		$data['data']						= $this->data;
		$data['view_link'] 					= 'site/home/index';
		$this->load->view('includes/template_site', $data);
	}
	
	public function change_all_pass()
	{
		$collection 	= $this->mongo_db->get('site_users');
		if(!empty($collection))
		{
			foreach($collection as $coc)
			{
				$uid 		= (isset($coc['_id'])) ? strval($coc['_id']) : '';
				$passowrd 	= (isset($coc['password'])) ? $coc['password'] : '';
				$enc_password 	= crypt($passowrd, $this->password_salt);
				
				$data_to_store = array('password_salt' => $this->password_salt);
					
				//Updating user with new data
				if($uid != '' && $passowrd != '')
				{
					$this->mongo_db->where(array('_id' => $uid));
					$this->mongo_db->set($data_to_store);
					$this->mongo_db->update('site_users');
				}
			}
		}
	}
	
	public function custom_login_validate()
	{
		$email 		= $this->input->get('email');
		
		$this->mongo_db->where(array('email' => $email));
		$user_details 	= $this->mongo_db->get('site_users'); 
		$user_timezone = (isset($_COOKIE['user_timezone']) && $_COOKIE['user_timezone']!='') ? $_COOKIE['user_timezone'] : $this->system_timezone;
		if(is_array($user_details))
		{
			$data 	= array(
						'user_timezone'			=> $user_timezone,
						'site_user_id_hotcargo' 		=> $user_details[0]['id'],
						'site_user_objId_hotcargo' 	=> strval($user_details[0]['_id']),
						'site_user_type_hotcargo' 	=> $user_details[0]['user_type'],
						'site_user_name_hotcargo' 	=> ucfirst($user_details[0]['first_name'].' '.$user_details[0]['last_name']),
						'site_is_logged_in' 		=> true
					);
			
			$this->session->set_userdata($data);
		}
		
		redirect('dashboard');
	}
	
	public function login_validate()
	{
		$profile_type 	= $this->input->post('selectProfile');
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');
		$do_remember 	= $this->input->post('do_remember');
			
		//first we have to get the user password salt
		$user_det_salt = $this->Users_model->get_user_salt('', $email);
		$user_det_salt = ($user_det_salt) ? $user_det_salt : $this->password_salt;
		$enc_password 	= crypt($password, $user_det_salt);
			
		$user_timezone = (isset($_COOKIE['user_timezone']) && $_COOKIE['user_timezone']!='') ? $_COOKIE['user_timezone'] : $this->system_timezone;
			
		$user_details 	= $this->Users_model->validate_user('', $email, $enc_password, $this->cmp_auth_id);
			
		if(is_array($user_details)){
			$data 	= array(
						'user_timezone'			=> $user_timezone,
						'site_user_id_hotcargo' 		=> $user_details[0]['id'],
						'site_user_objId_hotcargo' 	=> strval($user_details[0]['_id']),
						'site_user_type_hotcargo' 	=> $user_details[0]['user_type'],
						'site_user_name_hotcargo' 	=> ucfirst($user_details[0]['first_name'].' '.$user_details[0]['last_name']),
						'site_is_logged_in' 		=> true
					);
				
			$this->session->set_userdata($data);
				
			//data to store in cookie
			if($do_remember == 1)
			{
				$cookie1 = array(
					'name'   => 'email_id',
					'value'  => $email,
					'expire' => '86500'
				);
					
				$cookie2 = array(
					'name'   => 'password',
					'value'  => $password,
					'expire' => '86500'
				);
					
				$cookie3 = array(
					'name'   => 'do_remember',
					'value'  => $do_remember,
					'expire' => '86500'
				);
					
				$cookie4 = array(
					'name'   => 'user_type',
					'value'  => $profile_type,
					'expire' => '86500'
				);
					
				$this->input->set_cookie($cookie1); $this->input->set_cookie($cookie2); $this->input->set_cookie($cookie3); $this->input->set_cookie($cookie4);
			}
			else{
				delete_cookie('email_id'); delete_cookie('password'); delete_cookie('do_remember'); delete_cookie('user_type');
			}
				
			$this->session->set_flashdata('flash_message', 'register_success');
			redirect('dashboard');
		}
		elseif($user_details == 3){
			$this->session->set_flashdata('flash_message_cont', $email);
			$this->session->set_flashdata('flash_message', 'user_det_error');
			redirect('');
		}
		elseif($user_details == 2){
			$this->session->set_flashdata('flash_message_cont', $email);
			$this->session->set_flashdata('flash_message', 'user_status_failed');
			redirect('');
		}
		elseif($user_details == 0){
			$this->session->set_flashdata('flash_message_cont', $email);
			$this->session->set_flashdata('flash_message', 'user_not_exist');
			redirect('');
		}
		else{
			$this->session->set_flashdata('flash_message_cont', $email);
			$this->session->set_flashdata('flash_message', 'error');
			redirect('');
		}
	}
		
	public function register()
	{
		if(!empty($this->cmp_details))
			$eg_type 	= $this->uri->segment(3);
		else
			$eg_type 	= $this->uri->segment(2);
		
		$this->data['reg_type']			= $eg_type;
		
		$this->data['settings'] 		= $this->sitesetting_model->get_settings();
		//$site_name 					= (isset($this->data['settings'][0]['site_name'])) ? $this->data['settings'][0]['site_name']  : '';
		if(!empty($this->site_title))
			$site_name	= $this->site_title;
		else
			$site_name 	= $this->data['settings'][0]['site_name'];
		
		
		if($eg_type)
		{
			//Get all forl type fields
			$reg_types_det 				= $this->Home_model->form_types_det($eg_type);
			$this->data['reg_types_det'] 	= $reg_types_det;
			
			if(!empty($this->cmp_details))
				$reg_types_det 				= $this->uri->segment(3);
			else
				$reg_types_det 				= $this->uri->segment(2);
			
			$this->data['ptitle'] 		= ($site_name) ? $reg_types_det.' sign up - '.ucfirst($site_name) : 'Sign up';
			$data['data']				= $this->data;
			
			$data['view_link'] 				= 'site/home/signup_page';
			$this->load->view('includes/template_site', $data);
		}
		else
			redirect('');
	}
		
	public function signup_validate()
	{
		//Get last user id
		$this->mongo_db->order_by(array('_id' => 'desc'));
		$this->mongo_db->limit(1);
		$get_last_id_arr 			= $this->mongo_db->get('site_users');
		$get_last_id 				= (isset($get_last_id_arr[0]['id'])) ? $get_last_id_arr[0]['id'] : 1;
		
		$reg_type 				= $this->input->post('reg_type');
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
				if(!isset($data_to_store[$f])){
					$data_to_store[$f] 		= (is_array($field)) ? (object)($field) :strval($field);
					
					//for storing the country code and name
					if(isset($data_to_store[$f]->lat) && isset($data_to_store[$f]->long))
					{
						$end_country_data 				= (@file_get_contents('http://ws.geonames.org/countryCode?lat='.$data_to_store[$f]->lat.'&lng='.$data_to_store[$f]->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
						$end_country_data_arr 			= json_decode($end_country_data);
						
						$end_country_name 				= isset($end_country_data_arr->countryName) ? $end_country_data_arr->countryName : '';
						$end_country_code 				= isset($end_country_data_arr->countryCode) ? $end_country_data_arr->countryCode : '';
						
						$data_to_store[$f]->lat 			= (float)$data_to_store[$f]->lat;
						$data_to_store[$f]->lat_str 		= strval($data_to_store[$f]->lat);
						$data_to_store[$f]->long 		= (float)$data_to_store[$f]->long;
						$data_to_store[$f]->long_str 		= strval($data_to_store[$f]->long);
						$data_to_store[$f]->country 		= $end_country_name;
						$data_to_store[$f]->country_code 	= $end_country_code;
					}
				}
				else{
					$data_to_store[$f.'_1'] 	= (is_array($field)) ? (object)($field) :strval($field);
					
					$end_country_data 					= (@file_get_contents('http://ws.geonames.org/countryCode?lat='.$data_to_store[$f.'_1']->lat.'&lng='.$data_to_store[$f.'_1']->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
					$end_country_data_arr 				= json_decode($end_country_data);
					$end_country_name 					= isset($end_country_data_arr->countryName) ? $end_country_data_arr->countryName : '';
					$end_country_code 					= isset($end_country_data_arr->countryCode) ? $end_country_data_arr->countryCode : '';
					
					$data_to_store[$f.'_1']->lat 			= (float)$data_to_store[$f.'_1']->lat;
					$data_to_store[$f.'_1']->lat_str 		= strval($data_to_store[$f.'_1']->lat);
					$data_to_store[$f.'_1']->long 		= (float)$data_to_store[$f.'_1']->long;
					$data_to_store[$f.'_1']->long_str 		= strval($data_to_store[$f.'_1']->long);
					$data_to_store[$f.'_1']->country 		= $end_country_name;
					$data_to_store[$f.'_1']->country_code 	= $end_country_code;
				}
			}
		}
			
		//echo "<pre>";
		//print_r($extra_fields);die;
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
			
		$user_timezone 				= (isset($_COOKIE['user_timezone']) && $_COOKIE['user_timezone']!='') ? $_COOKIE['user_timezone'] : $this->system_timezone;
			
		$user_phone					= $this->input->post('mobile_no');
		$country_code					= $this->input->post('country_code');
		$phone_code					= $this->input->post('phone_code');
		$merchant_id					= ($this->input->post('cmp_auth_id')) ? $this->input->post('cmp_auth_id') : '';	
			
		$data_to_store['user_phone']		= $user_phone;
		$data_to_store['country_code']	= $country_code;
		$data_to_store['phone_code'] 		= $phone_code;
			
		$data_to_store['user_timezone']	= $user_timezone;
		$data_to_store['system_timezone']	=  $this->system_timezone;
		$data_to_store['password_salt'] 	= $this->password_salt;
		$data_to_store['forgot_pass']		= '0';
		$data_to_store['verify_pass_code']	= '';
		$data_to_store['added_on']		= strval(date('Y-m-d H:i:s'));
		$data_to_store['system_timezone']	= $this->system_timezone;
		$data_to_store['admin_status']	= strval(1);
		$data_to_store['status']			= strval(1);
		$data_to_store['merchant_id'] 	= (!empty($merchant_id)) 		? $merchant_id : '';
			
		//check for email as it should be unique for each users and can not be same
		$do_have_email = (isset($fixed_fields['email'])) ? 1 : '0';
		$do_insert 	= 1;
		
		if($do_have_email)
		{
			$email_id = (isset($fixed_fields['email'])) ? $fixed_fields['email'] : '';
			if($email_id)
			{
				if(!empty($this->cmp_details) && !empty($this->cmp_auth_id))
				{
					//check for similar emial
					$this->mongo_db->where(array('email' => $email_id,'merchant_id' => $this->cmp_auth_id));
					$count = $this->mongo_db->count('site_users');
				}
				else
				{
					//check for similar emial
					$this->mongo_db->where(array('email' => $email_id));
					$count = $this->mongo_db->count('site_users');
				}
				
				if($count > 0)
				{
					$this->session->set_flashdata('flash_message', 'email_exist');
					redirect('');
				}
			}
			else
			{
				$this->session->set_flashdata('flash_message', 'reg_error');
				redirect('');
			}
		}
		else{
			$this->session->set_flashdata('flash_message', 'email_blank');
			redirect('');
		}
		
		if($do_insert)
		{
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
				
				//upload extra field files
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
					$replace 		= array(base_url().'assets/site/images/logo.png', $to_name, ucfirst($data_to_store['user_type']), $this->site_name);
					
					$email_temp_msg= isset($email_temp['email_template']) 	? $email_temp['email_template'] : '';
					$email_temp_msg= str_replace($search, $replace, $email_temp_msg);
					
					$email_temp_sub= isset($email_temp['email_subject']) 	? $email_temp['email_subject'] : '';
					
					if($user_email_id) $this->User_email_model->send_email($user_email_id, $email_temp_sub, $email_temp_msg, '', '', '', $to_name);
					
				}
				//END
				
				$this->session->set_flashdata('flash_message', 'reg_success');
				redirect('');
			}
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'reg_error');
			redirect('');
		}
	}
	
	public function linkdin_validate()
	{
		$profile_type 	= $this->uri->segment(2);
		$profile_type 	= ($profile_type) ? $profile_type : 'customer';
		
		$baseURL 			= base_url();
		$callbackURL 		= base_url().'linkdin-auth';
		$linkedinApiKey 	= $this->linkedinApiKey;
		$linkedinApiSecret 	= $this->linkedinApiSecret;
		$linkedinScope 	= 'r_basicprofile r_emailaddress';
		
		//include_once(FILEUPLOADPATH."assets/linkdin/config.php");
		include_once(FILEUPLOADPATH."assets/linkdin/LinkedIn/http.php");
		include_once(FILEUPLOADPATH."assets/linkdin/LinkedIn/oauth_client.php");
		
		if (isset($_GET["oauth_problem"]) && $_GET["oauth_problem"] <> "") {
			// in case if user cancel the login. redirect back to home page.
			
			$this->session->set_flashdata('flash_message', 'auth_error');
			$this->session->set_flashdata('flash_message_cont', $_GET["oauth_problem"]);
			redirect('');
			exit;
		}
		
		$client 				= new oauth_client_class;
		$client->debug 		= false;
		$client->debug_http 	= true;
		$client->redirect_uri 	= $callbackURL;
		
		$client->client_id 		= $linkedinApiKey;
		$application_line 		= __LINE__;
		$client->client_secret 	= $linkedinApiSecret;
		
		if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
			die('Please go to LinkedIn Apps page https://www.linkedin.com/secure/developer?newapp= , '.
					'create an application, and in the line '.$application_line.
					' set the client_id to Consumer key and client_secret with Consumer secret. '.
					'The Callback URL must be '.$client->redirect_uri).' Make sure you enable the '.
					'necessary permissions to execute the API calls your application needs.';
		
		/* API permissions
		 */
		$client->scope 		= $linkedinScope;
		if (($success = $client->Initialize())) {
			if (($success = $client->Process())) {
				if (strlen($client->authorization_error)) {
					$client->error = $client->authorization_error;
					$success = false;
				} elseif (strlen($client->access_token)) {
					$success = $client->CallAPI(
								'http://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name)', 
								'GET', array(
									'format'=>'json'
								), array('FailOnAccessError'=>true), $user);
				}
			}
			
			$success = $client->Finalize($success);
		}
		
		if ($client->exit){ exit; }
		
		if ($success) {
			
			//echo '<pre>'; print_r($user); echo '</pre>'; die;
			
			$email_id 		= (isset($user->emailAddress)) 	? $user->emailAddress 	: '';
			$firstName 		= (isset($user->firstName)) 		? $user->firstName 		: '';
			$lastName 		= (isset($user->lastName)) 		? $user->lastName 		: '';
			$formattedName 	= (isset($user->formattedName)) 	? $user->formattedName 	: '';
			$id 				= (isset($user->id)) 			? $user->id 			: '';
			$publicProfileUrl 	= (isset($user->publicProfileUrl)) ? $user->publicProfileUrl: '';
			$location 		= (isset($user->location)) 		? $user->location		: '';
			$location 		= (is_object($location)) 		? json_encode($location) : $location;
			
			if(!empty($email_id))
			{
				$user_details 		= $this->Users_model->validate_user_linkedin('', $email_id, $id);
				
				//echo '<pre>'; print_r($user_details); die;
				
				if(is_array($user_details)){
					$data 		= array(
									'site_user_id_hotcargo' 		=> $user_details[0]['id'],
									'site_user_objId_hotcargo' 	=> $user_details[0]['_id'],
									'site_user_name_hotcargo' 	=> ucfirst($user_details[0]['first_name'].' '.$user_details[0]['last_name']),
									'site_is_logged_in' 		=> true
								);
					
					$this->session->set_userdata($data);
					
					$this->session->set_flashdata('flash_message', 'register_success');
					redirect('dashboard');
				}
				elseif($user_details == 3){
					$this->session->set_flashdata('flash_message', 'user_det_error');
					redirect('');
				}
				elseif($user_details == 2){
					$this->session->set_flashdata('flash_message', 'user_status_failed');
					redirect('');
				}
				elseif($user_details == 0){
					$this->session->set_flashdata('flash_message', 'user_not_exist');
					//redirect('');
					
					$html 	='<form name="submit_linkedin_det" id="submit_linkedin_det" action="'.base_url().'sign-up/'.$profile_type.'" method="post" />
								<input type="hidden" name="first_name" 	id="first_name" 	value="'.$firstName.'" />
								<input type="hidden" name="last_name" 	id="last_name" 	value="'.$lastName.'" />
								<input type="hidden" name="ful_name" 	id="ful_name" 		value="'.$formattedName.'" />
								<input type="hidden" name="email" 		id="email" 		value="'.$email_id.'" />
									
								<input type="hidden" name="linkedin_id" id="linkedin_id" 	value="'.$id.'" />
								<input type="hidden" name="location" 	id="location" 		value="'.($location).'" />
							  </form>
							  
							  <script>document.getElementById("submit_linkedin_det").submit();</script>';
					echo $html;
				}
				else{
					$this->session->set_flashdata('flash_message', 'error');
					redirect('');
				}
			}
		} else {
			$this->session->set_flashdata('flash_message', 'auth_error');
			$this->session->set_flashdata('flash_message_cont', $client->error);
			redirect('');
			exit;
		}
	}
	
	public function forgot_password()
	{
		//$enc_password = crypt('12345678', $this->password_salt);
		//echo 'enc pass: '.$enc_password.' Password salt: '.$this->password_salt.'<br>';
		//
		//if (hash_equals($enc_password, crypt('12345678', $this->password_salt))) {
		//	echo "Password verified!";
		//}
		//else	 echo 'not verified';
		
		//print_r($user_details);
		
		
		$cmp_auth_no 					= isset($this->cmp_details[0]['cmp_auth_id']) ? $this->cmp_details[0]['cmp_auth_id'] : '';
		$cmp_auth_name 				= isset($this->cmp_details[0]['name'])  	 ? $this->cmp_details[0]['name'] 		: '';
		
		$this->data['cmp_auth_link_id']	= (!empty($cmp_auth_name) && !empty($cmp_auth_no)) ? $cmp_auth_name.'-'.$cmp_auth_no.'/' : '';
		
		if(!empty($this->site_title))
			$sitename	= $this->site_title;
		else
			$sitename 	= $this->data['settings'][0]['site_name'];
		
		
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
		
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$email_id 	= $this->input->post('email');
			$cmp_auth_id 	= $this->input->post('cmp_auth_id');
			
			if(!empty($cmp_auth_id))
				$this->mongo_db->where(array('merchant_id' => $cmp_auth_id));
			
			$this->mongo_db->where(array('email' => $email_id));
			$count = $this->mongo_db->count('site_users');
			
			if($count)
			{
				if(!empty($cmp_auth_id))
					$this->mongo_db->where(array('merchant_id' => $cmp_auth_id));
					
				$this->mongo_db->where(array('email' => $email_id));
				$user_details_arr 	= $this->mongo_db->get('site_users');
				$user_details		= isset($user_details_arr[0]) ? $user_details_arr[0] : '';
				
				$user_id 			= (isset($user_details['_id'])) 	? strval($user_details['_id']) : '';
				$user_status 		= (isset($user_details['status'])) ? $user_details['status'] : '0';
				
				if(!empty($user_id) && ($user_status == 1))
				{
					$new_pass_val 		= rand().'-'.time();
					$data_to_store 	= array('forgot_pass' => '1', 'verify_pass_code' => $new_pass_val);
					
					//Updating user with new data
					$this->mongo_db->where(array('_id' => $user_id));
					$this->mongo_db->set($data_to_store);
					$this->mongo_db->update('site_users');
					
					//Geting user email id and name
					$user_email_id 	= isset($user_details['email']) ? $user_details['email'] : '';
					$to_name 			= isset($user_details['first_name']) ? ucwords($user_details['first_name'].' '.$user_details['last_name']) : '';
					if(!empty($this->data['site_logo']))
					{
						$site_logo_path  =	assets_url('uploads/merchant_images/thumb/'.$this->data['site_logo']);
					}
					else
					{
						$site_logo_path  =	assets_url('site/images/'.$default_site_logo);
					}	
				
					//Geting the email template
					$this->mongo_db->where(array('email_title' => 'forgot_password'));
					$email_temp_arr 	= $this->mongo_db->get('email_templates');
					$email_temp		= isset($email_temp_arr[0]) ? $email_temp_arr[0] : '';
					
					//The change password link
					$link 			= base_url().'validate-password/'.$new_pass_val;
					
					if(!empty($email_temp))
					{
						$search 		= array('[SITE_LOGO]', '[NAME]', '[LINK]', '[SITE_NAME]');
						$replace 		= array($site_logo_path, $to_name, $link, $sitename);
						
						$email_temp_msg= isset($email_temp['email_template']) 	? $email_temp['email_template'] : '';
						$email_temp_msg= str_replace($search, $replace, $email_temp_msg);
						
						$email_temp_sub= isset($email_temp['email_subject']) 	? $email_temp['email_subject'] : '';
						
						
						if($user_email_id) $this->User_email_model->send_email($user_email_id, $email_temp_sub, $email_temp_msg, '', '', '', $to_name);
					}
					
					$this->session->set_flashdata('flash_message_cont', $email_id);
					$this->session->set_flashdata('flash_message', 'forget_pass_success');
					
					redirect('');
				}
				elseif($user_status == 2)
				{
					$this->session->set_flashdata('flash_message_cont', $email_id);
					$this->session->set_flashdata('flash_message', 'forget_pass_error_user_adact');
					
					redirect('forgot-password');
				}
				else
				{
					$this->session->set_flashdata('flash_message_cont', $email_id);
					$this->session->set_flashdata('flash_message', 'forget_pass_error_user_dact');
					
					redirect('forgot-password');
				}
			}
			else{
				$this->session->set_flashdata('flash_message_cont', $email_id);
				$this->session->set_flashdata('flash_message', 'forget_pass_error');
				
				redirect('forgot-password');
			}
		}
			
		$this->data['ptitle'] 		= ($sitename) ? 'Forgot Password - '.ucfirst($sitename) : 'Forgot password';
		$this->data['new_pass'] 		= '0';
		$data['data']				= $this->data;
		$data['view_link'] 			= 'site/home/forgot_password';
		$this->load->view('includes/template_site', $data);
	}
		
	public function validate_password()
	{
			
		$cmp_auth_no 					= isset($this->cmp_details[0]['cmp_auth_id']) ? $this->cmp_details[0]['cmp_auth_id'] : '';
		$cmp_auth_name 				= isset($this->cmp_details[0]['name'])  	 ? $this->cmp_details[0]['name'] 		: '';
			
		$this->data['cmp_auth_link_id']	= (!empty($cmp_auth_name) && !empty($cmp_auth_no)) ? $cmp_auth_name.'-'.$cmp_auth_no.'/' : '';
			
		$settings 	= $this->sitesetting_model->get_settings();
		$sitename 	= (isset($settings[0]['site_name'])) ? $settings[0]['site_name'] : '';
			
		if(!empty($this->cmp_details))
			$validate_id	= $this->uri->segment(3);
		else
			$validate_id	= $this->uri->segment(2);
			
		$this->data['verify_code'] 	= $validate_id;
			
		if(!empty($this->cmp_auth_id))
				$this->mongo_db->where(array('merchant_id' => $this->cmp_auth_id));
				
		$this->mongo_db->where(array('verify_pass_code' => $validate_id));
		$user_det_arr 	= $this->mongo_db->get('site_users');
		$user_details	= isset($user_det_arr[0]) ? $user_det_arr[0] : '';
			
		$this->data['new_pass'] 	= '0';
		$this->data['user_id'] 	= '';
			
		//echo "<pre>";
		//print_r($user_details);die;
		if(!empty($user_details))
		{
			$this->data['new_pass'] 	= '1';
			$this->data['user_id'] 	= isset($user_details['_id']) ? strval($user_details['_id']) : '0';
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'forget_pass_verify_code_error');
			redirect('forgot-password');
		}
			
		$data['data']			= $this->data;
		$data['view_link'] 		= 'site/home/forgot_password';
		$this->load->view('includes/template_site', $data);
	}
		
		
	public function update_user_mer()
	{
		$user_det_arr 	= $this->mongo_db->get('site_users');
		if(!empty($user_det_arr))
		{
			foreach($user_det_arr as $user)
			{
				$data_to_store				= array();
				$id 						= (isset($user['_id'])) 			? strval($user['_id']) : '';
					
				$merchant_id				= (isset($user['merchant_id']))	? $user['merchant_id'] : '';
				$data_to_store['merchant_id']	= $merchant_id;
					
				$this->mongo_db->where(array('_id' => $id));
				$this->mongo_db->set($data_to_store);
				$get 					= $this->mongo_db->update('site_users');
			}
		}
	}
		
		
		
	public function change_pass_verify()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$user_id 		= $this->input->post('user_id');
			$verify_code 	= $this->input->post('verify_code');
			$new_pass_val 	= $this->input->post('new_password');
			$cnf_pass_val 	= $this->input->post('confirm_password');
			
			//Encrype the password with blowfish password encryption
			$enc_password = crypt($new_pass_val, $this->password_salt);
			
			$this->mongo_db->where(array('verify_pass_code' => $verify_code, '_id' => $user_id));
			$user_det_arr 	= $this->mongo_db->get('site_users');
			$user_details	= isset($user_det_arr[0]) ? $user_det_arr[0] : '';
			
			if(!empty($user_details))
			{
				$data_to_store 	= array('password' => $enc_password, 'password_salt' => $this->password_salt,'forgot_pass' => '0', 'verify_pass_code' => '');
				
				//Updating user with new data
				$this->mongo_db->where(array('_id' => $user_id));
				$this->mongo_db->set($data_to_store);
				$this->mongo_db->update('site_users');
				
				$this->session->set_flashdata('flash_message', 'pass_updated');
				redirect('');
				
			}
			else{
				$this->session->set_flashdata('flash_message', 'pass_updated_error');
				redirect('validate-password/'.$verify_code);
			}
		}
		else
			redirect('');
	}
	
	
	public function zappier_job_json()
	{
		$username			= $password = '';
		$user_details		= array();
		$is_authenticated	= '0';	
		$job_info 		= array();		
			
		$error_message['message']	= 'Authorization failed. Please send username and password as basic auth.';	
			
		foreach (getallheaders() as $name => $value)
		{
			if($name == 'Authorization')
			{
				$upexp			= trim(str_replace('Basic', '', $value));
				$decode			= base64_decode($upexp);
				 
				if($decode!='')
				{
					$exp			= explode(':', $decode);
					$username		= isset($exp[0]) 	? $exp[0] : '';
					$password		= isset($exp[1]) 	? $exp[1] : '';
					
					if(!empty($username) && !empty($password))
					{
						$user_det_salt = $this->Users_model->get_user_salt('', $username);
						$user_det_salt = ($user_det_salt) 	? $user_det_salt : $this->password_salt;
						$enc_password 	= crypt($password, $user_det_salt);
							
						$user_details 	= $this->Users_model->validate_user('', $username, $enc_password, '', '0');
						
						if(is_array($user_details) && !empty($user_details))	
							$is_authenticated 			= 1;
						else
							$error_message['message']	= 'Authorization failed. User not found';
					}
				}
			} 
		}
			
			
		if($is_authenticated)
		{
			$user_id		= isset($user_details[0]['_id'])	? strval($user_details[0]['_id']) : '';
				
			$today 		= date('Y-m-d');	
				
			//get last job details
			$this->mongo_db->where(array('user_id' => $user_id));
			$this->mongo_db->like('added_on', $today);
			$job_det_q	= $this->mongo_db->get('jobs');
				
				
			$i = 0;	
			if(!empty($job_det_q))
			{
				foreach($job_det_q as $j => $lists)
				{
					if(!isset($lists['is_zap_send']))
					{
						if(isset($lists['user_id'])){
							$this->mongo_db->where(array('_id' => strval($lists['user_id'])));
							$job_user_data 	= $this->mongo_db->get('site_users');
							
							//Getting user rating
							$this->mongo_db->where(array('user_id' => strval($lists['user_id']), 'status' => "1"));
							$job_user_rating 	= $this->mongo_db->get('user_rating');
						}
							
						//size details
						if(isset($lists['size_type']) && !empty($lists['size_type']) && ($lists['size_type'] != 'SIZE'))
						{
							$this->mongo_db->where(array('_id' => strval($lists['size_type']), 'status' => "1"));
							$size_details 	= $this->mongo_db->get('sizes');
						}
						
						//type details
						if(isset($lists['type']) && !empty($lists['type']) && ($lists['size_type'] != 'TYPE'))
						{
							$this->mongo_db->where(array('_id' => strval($lists['type']), 'status' => "1"));
							$type_details 	= $this->mongo_db->get('type');
						}
						
						//special details
						if(isset($lists['special']) && !empty($lists['special']) && ($lists['size_type'] != 'SPECIAL'))
						{
							$this->mongo_db->where(array('_id' => strval($lists['special']), 'status' => "1"));
							$special_details 	= $this->mongo_db->get('special');
						}
							
							
						//get the job info
						$job_id						= strval($lists['_id']);
						$job_info[$i]['id']				= strval($lists['_id']);
						$job_info[$i]['job_description']	= (isset($lists['description'])) 				? $lists['description'] 			: '';
						$job_info[$i]['pickup_address']	= (isset($lists['pickup_address']['address'])) 	? $lists['pickup_address']['address'] : '';
						$job_info[$i]['drop_address']		= (isset($lists['drop_address']['address'])) 	? $lists['drop_address']['address'] : '';
						$job_info[$i]['distance']		= (isset($lists['distance'])) 				? $lists['distance'] 			: '';
						$job_info[$i]['distance_type']	= (isset($lists['distance_type'])) 			? $lists['distance_type'] 		: '';
						$job_info[$i]['delivery_date']	= (isset($lists['delivery_date'])) 			? date('dS M Y', strtotime($lists['delivery_date'])) : '';
						$job_info[$i]['size_type']		= (isset($size_details[0]['title'])) 			? $size_details[0]['title'] 		: '';
						$job_info[$i]['containt_type']	= (isset($type_details[0]['title'])) 			? $type_details[0]['title'] 		: '';
						$job_info[$i]['special']			= (isset($special_details[0]['title'])) 		? $special_details[0]['title'] 	: '';
						$job_info[$i]['weight']			= (isset($lists['weight'])) 					? $lists['weight'] 				: '';
						$job_info[$i]['cargo_value']		= (isset($lists['cargo_value'])) 				? $lists['cargo_value'] 			: '';
						$job_info[$i]['max_job_price']	= (isset($lists['max_job_price'])) 			? $lists['max_job_price'] 		: '';
						$job_info[$i]['is_guarantee']		= (isset($lists['is_gurrented']) && ($lists['is_gurrented'] == '1')) 		? 'Yes' 	: 'No';
						$job_info[$i]['is_insured']		= (isset($lists['is_insured']) && ($lists['is_insured'] == '1')) 		? 'Yes' 	: 'No';
							
							
						//update the jobs with status
						$data_to_update				= array();
						$data_to_update['is_zap_send']	= '1';
							
						$this->mongo_db->where(array('_id' => $job_id));
						$this->mongo_db->set($data_to_update);
						$this->mongo_db->update('jobs');
						
						$i++;
					}
				}
			}
		}
			
			
			
		if($is_authenticated){
			header("HTTP/1.1 200 OK");
			echo (!empty($job_info))	? json_encode($job_info) : '';
		}
		else{
			header("HTTP/1.1 403 Not Found");    
			echo json_encode($error_message);
		}
	}
		
		
		
		
	public function zappier_job_events_json()
	{
		$username			= $password = '';
		$user_details		= array();
		$is_authenticated	= '0';	
		$job_info 		= array();		
			
		$error_message['message']	= 'Authorization failed. Please send username and password as basic auth.';	
			
		foreach (getallheaders() as $name => $value)
		{
			if($name == 'Authorization')
			{
				$upexp			= trim(str_replace('Basic', '', $value));
				$decode			= base64_decode($upexp);
				 
				if($decode!='')
				{
					$exp			= explode(':', $decode);
					$username		= isset($exp[0]) 	? $exp[0] : '';
					$password		= isset($exp[1]) 	? $exp[1] : '';
					
					if(!empty($username) && !empty($password))
					{
						$user_det_salt = $this->Users_model->get_user_salt('', $username);
						$user_det_salt = ($user_det_salt) 	? $user_det_salt : $this->password_salt;
						$enc_password 	= crypt($password, $user_det_salt);
							
						$user_details 	= $this->Users_model->validate_user('', $username, $enc_password, '', '0');
						
						if(is_array($user_details) && !empty($user_details))	
							$is_authenticated 			= 1;
						else
							$error_message['message']	= 'Authorization failed. User not found';
					}
				}
			} 
		}
			
			
		if($is_authenticated)
		{
			$user_id		= isset($user_details[0]['_id'])	? strval($user_details[0]['_id']) : '';
			$today 		= date('Y-m-d');	
				
			//get last job details
			$this->mongo_db->where(array('user_id' => $user_id));
			$this->mongo_db->like('added_on', $today);
			$job_det_q	= $this->mongo_db->get('jobs');
				
			$i 			= 0;
				
			$event_det_arr	= array(
								'pickup' 			=> 'Job pick up initiate',
								'damage' 			=> 'Item is damaged.',
								'delay' 			=> 'Job delivery is delayed.',
								'update_location' 	=> 'Job current locations updated.',
								'delivery_progress'	=> 'Job delivery progress update.',
								'quality_inspec'	=> 'Items quality inspection.',
								'delivered'		=> 'Job is delivered.'
							);
				
			if(!empty($job_det_q))
			{
				foreach($job_det_q as $j => $lists)
				{
					if(!isset($lists['is_zap_send']))
					{
						$job_id 				= (isset($lists['_id']))	? strval($lists['_id']) : '';
						
						//get job events details
						$this->mongo_db->where(array('job_id' => $job_id, 'status' => "1"));
						$job_events_details		= $this->mongo_db->get('job_events');
							
							
						if(!empty($job_events_details))
						{
							foreach($job_events_details as $j => $job_event)
							{
								
								//get the job info	
								$job_id						= strval($lists['_id']);	
								$job_info[$i]['id']				= strval($lists['_id']);
								$job_info[$i]['job_description']	= (isset($lists['description']))		? $lists['description']		: '';
								
								$event_type					= (isset($job_event['event_type']))	? $job_event['event_type']	: '';
								$event_type_msg				= ucwords(str_replace('_', ' ', $event_type));
									
								$job_info[$i]['event_type']		= isset($event_det_arr[$event_type])	? $event_det_arr[$event_type] : $event_type_msg;
									
								$job_info[$i]['activity_details']	= (isset($job_event['activity_details']))		? $job_event['activity_details']	: '';
								$job_info[$i]['job_location']		= (isset($job_event['event_address']['address']))	? $job_event['event_address']['address']		: '';
								$job_info[$i]['location_lat']		= (isset($job_event['event_address']['lat_str']))	? $job_event['event_address']['lat_str']		: '';
								$job_info[$i]['location_lon']		= (isset($job_event['event_address']['long_str']))? $job_event['event_address']['long_str']		 : '';
								$job_info[$i]['country']			= (isset($job_event['event_address']['country']))	? $job_event['event_address']['country']		: '';
								$job_info[$i]['event_cost']		= (isset($job_event['event_cost']))	? '$'.$job_event['event_cost']	: '$0.00';
								
								
								$i++;
							}
						}
							
							
						////update the jobs with status
						//$data_to_update				= array();
						//$data_to_update['is_zap_send']	= '1';
						//	
						//$this->mongo_db->where(array('_id' => $job_id));
						//$this->mongo_db->set($data_to_update);
						//$this->mongo_db->update('jobs');
						//	
						//$i++;
					}
				}
			}
		}
			
			
			
		if($is_authenticated){
			header("HTTP/1.1 200 OK");
			echo (!empty($job_info))	? json_encode($job_info) : '';
		}
		else{
			header("HTTP/1.1 403 Not Found");    
			echo json_encode($error_message);
		}
	}	
		
}
?>