<?php
class Manage_merchant_controller extends CI_Controller {

	public $geonames_username	= "arijit2016";
	public $system_timezone		= "UTC";
	public $site_name			= "Hotcargo - Anything, Anywhere, Anytime freight and cargo shipping.";
	public $site_logo			= "";
	
	public function __construct()
	{
		parent::__construct();
		
		//Loading all necessary models
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->model('User_email_model');
		$this->load->model('Url_generator_model');
		$this->load->library('ImageThumb');
		
		$this->password_salt = $this->config->item('encryption_key');
		$this->password_salt = ($this->password_salt) ? $this->password_salt : '12345678';
		
		//Getting site settings data
		$settings_data = $this->sitesetting_model->get_settings();
		$this->geonames_username = (isset($settings_data[0]['geonames_username'])) ? $settings_data[0]['geonames_username'] : $this->geonames_username;
		$this->system_timezone = (isset($settings_data[0]['system_timezone'])) ? $settings_data[0]['system_timezone'] : $this->system_timezone;
		$this->site_name = (isset($settings_data[0]['site_name'])) ? $settings_data[0]['site_name'] : $this->site_name;
		$this->site_logo = (isset($settings_data[0]['site_logo'])) ? $settings_data[0]['site_logo'] : $this->site_logo;
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
		
		//$this->mongo_db->select(array('_id', 'form_type'));
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('user_type' => 'merchant'));
		$all_details 					= $this->mongo_db->get('site_users');
		//$all_details 	= $this->mongo_db->get('form_fields');
		
		//echo '<pre>'; print_r($all_details); echo '</pre>'; die;
		
		$data['data']['all_merchant']		= $all_details;

		//$data['data']['all_forms']		= array();
		
		$data['view_link'] = 'admin/merchants/index';
		
		
		$this->load->view('includes/template', $data);
	}//index
	
	public function add_merchant()
	{
		$user_id 			= $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		//Get last user id
		//$this->mongo_db->order_by(array('_id' => 'desc'));
		//$this->mongo_db->limit(1);
		//$get_last_id_arr 			= $this->mongo_db->get('site_users');
		//$get_last_id 				= (isset($get_last_id_arr[0]['id'])) ? $get_last_id_arr[0]['id'] : 1;
		
		$reg_type 				= strval('merchant');
		$data_to_store 			= array();
		
		$data_to_store['user_type']	= strval($reg_type);
		//$data_to_store['id']		= strval($get_last_id + 1);
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
			
		$enc_password	= '';
		if(isset($data_to_store['password']))
		{
			//Encrype the password with blowfish password encryption
			$enc_password 				= crypt($data_to_store['password'], $this->password_salt);
			$data_to_store['password'] 	= $enc_password;
		}
		
		$data_to_store['password_salt'] 	= $this->password_salt;
		$data_to_store['added_on']		= strval(date('Y-m-d H:i:s'));
		$data_to_store['admin_status']	= ($this->input->post('admin_status')!='') ? $this->input->post('admin_status') : '1';
		$data_to_store['user_timezone']	= ($this->input->post('user_timezone')!='') ? $this->input->post('user_timezone') : $this->system_timezone;
		$data_to_store['meta_keywords']	= ($this->input->post('meta_keywords')!='') ? $this->input->post('meta_keywords') : '';
		$data_to_store['meta_description']	= ($this->input->post('meta_description')!='') ? $this->input->post('meta_description') : '';
		$data_to_store['stripe_pay_type']	= ($this->input->post('stripe_pay_type')!='') ? $this->input->post('stripe_pay_type') : '';
		$data_to_store['stripe_live_secret_key']	= ($this->input->post('stripe_live_secret_key')!='') ? $this->input->post('stripe_live_secret_key') : '';
		$data_to_store['stripe_live_public_key']	= ($this->input->post('stripe_live_public_key')!='') ? $this->input->post('stripe_live_public_key') : '';
		$data_to_store['stripe_sandbox_secret_key']	= ($this->input->post('stripe_sandbox_secret_key')!='') ? $this->input->post('stripe_sandbox_secret_key') : '';
		$data_to_store['stripe_sandbox_public_key']	= ($this->input->post('stripe_sandbox_public_key')!='') ? $this->input->post('stripe_sandbox_public_key') : '';
		$data_to_store['platform_fee']	= ($this->input->post('platform_fee')!='') ? $this->input->post('platform_fee') : '';
		$data_to_store['cmp_auth_id']		= strval($this->common_model->generate_unique_code('10','site_users','cmp_auth_id'));
		$data_to_store['merchant_id']		= '';
		$data_to_store['system_timezone']	= $this->system_timezone;
		$data_to_store['status']			= strval(1);
		$data_to_store['name']			= "merchant-".$data_to_store['cmp_auth_id'];
			
			
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
				$this->mongo_db->where(array('email' => $email_id));
				$count = $this->mongo_db->count('site_users');
				
				if($count > 0)
				{
					$this->session->set_flashdata('flash_message', 'email_error');
					redirect('control/manage-merchants');
				}
			}
			else
			{
				$this->session->set_flashdata('flash_message', 'reg_error');
				redirect('control/manage-merchants');
			}
		}
			
			
		if($do_insert)
		{
			$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
				
			if($insert)
			{
				//Upload site fabicon logo image	
				if(isset($_FILES['site_fabicon']['name']) && !empty($_FILES['site_fabicon']['name']))
				{
					//get old logo icon
					//$old_icon				= (isset($settings_data[0]['site_fabicon'])) 	? $settings_data[0]['site_fabicon'] : '';
						
					$file_type 			= (isset($_FILES['site_fabicon']['type'])) 	? explode('/', $_FILES['site_fabicon']['type']) : array();
					$file_type_det 		= (isset($file_type[0])) ? $file_type[0] 	: '';
					
					$filename 			= (isset($_FILES['site_fabicon']['name'])) 	? substr($_FILES['site_fabicon']['name'], strripos($_FILES['site_fabicon']['name'],'.')) : '';
					$s					= time().$filename;
					$file 				= $_FILES['site_fabicon']['tmp_name'];
						
						
						
					$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/site/images/';
					$fileNormal 			= $DIR_IMG_NORMAL.$s;
					//echo $file."asd".$fileNormal;die;
					$result 				= move_uploaded_file($file, $fileNormal);
					
					if($result)
					{	
						//$srcPath		= FILEUPLOADPATH.'assets/site/images/'.$s;
						//$destPath1 		= FILEUPLOADPATH.'assets/site/images/thumb/'.$s;
						//$destWidth1		= 500;
						//$destHeight1		= 500;
						//$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
						
						$image_name		= $s;
						$data_to_store_img['site_fabicon'] 	= $image_name;
						
						$this->mongo_db->where(array('_id' => $insert));
						$this->mongo_db->set($data_to_store_img);
						$this->mongo_db->update('site_users');
					}
				}
				
				//Inserting dat into membership table
				if(isset($data_to_store['password']))
					$data_to_store_admin['pass_word'] 		= $enc_password;
				else
					$data_to_store_admin['pass_word'] 		= '';
					
				$data_to_store_admin['password_salt'] 		= $this->password_salt;
				$data_to_store_admin['email_addres']		= isset($data_to_store['email']) ? $data_to_store['email'] : '';
				$data_to_store_admin['is_sub_admin']		= strval(1);
				$data_to_store_admin['subadmin_id']		= strval($insert);
				//$data_to_store_admin['first_name']		= 'Merchant';
				//$data_to_store_admin['last_name']		= 'Administrator';
				$data_to_store_admin['cmp_auth_id']		= $data_to_store['cmp_auth_id'];
				
				$insert_to_admin 	= $this->mongo_db->insert('membership', $data_to_store_admin);
				//END
					
				//Inserting data into user permission table for menus
				$this->mongo_db->where(array('is_merchant' => '1'));
				$merchant_menus 	= $this->mongo_db->get('menus');
					
				if(!empty($merchant_menus))
				{
					foreach($merchant_menus as $m => $merchant_me)
						$data_to_store_user_permission['menu_ids'][]	= isset($merchant_me['_id'])	? strval($merchant_me['_id'])   : '';
				}
				else	$data_to_store_user_permission['menu_ids']	= array();
					
				$data_to_store_user_permission['user_id'] 		= strval($insert);
				//$data_to_store_user_permission['menu_ids'] 	= array('576ce206ee32e15e494792f1','578ce09e3668dc07d09e685d','5773afe5cff2932bc25c1d10','577b5c09b21239f70180c638','577bafc3b21239f70180c639','577cd121b21239f70180c63a','577f4998b21239f70180c63b','57c3d681b21239f70180c640','578dd6a34489fb551e78fc70');
				$insert_to_admin 	= $this->mongo_db->insert('user_permission', $data_to_store_user_permission);
					
					
				//Inserting data into user static content table	
				$data_store_static['page_title']				= 'Terms and conditions';
				$data_store_static['meta_tag']				= 'terms and conditions';
				$data_store_static['merchant_id']				= $data_to_store['cmp_auth_id'];
				$data_store_static['meta_keywords']			= 'terms';
				$data_store_static['meta_description']			= '';
				$data_store_static['page_alias']				= 'terms-conditions';
				$data_store_static['page_content']				= '';
				$data_store_static['status']					= '1';
					
				$insert_static 							= $this->mongo_db->insert('static_contents', $data_store_static);
					
					
					
					
				//END
				$to_up_files 				= array();
					
				//upload fixed field profile image
				if(isset($_FILES['fixed_fields']['name']['site_logo']) && !empty($_FILES['fixed_fields']['name']['site_logo']))
				{
					$file_type 			= (isset($_FILES['fixed_fields']['type']['site_logo'])) ? explode('/', $_FILES['fixed_fields']['type']['site_logo']) : array();
					$file_type_det 		= (isset($file_type[0])) ? $file_type[0] : '';
						
					$filename 			= (isset($_FILES['fixed_fields']['name']['site_logo'])) ? substr($_FILES['fixed_fields']['name']['site_logo'],strripos($_FILES['fixed_fields']['name']['site_logo'],'.')) : '';
					$s					= time().$filename;
					$file 				= $_FILES['fixed_fields']['tmp_name']['site_logo'];
						
					$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/merchant_images/';
					$fileNormal 			= $DIR_IMG_NORMAL.$s;
					$result 				= move_uploaded_file($file, $fileNormal);
						
					if($result)
					{
						$srcPath			= FILEUPLOADPATH.'assets/uploads/merchant_images/'.$s;
						$destPath1 		= FILEUPLOADPATH.'assets/uploads/merchant_images/thumb/'.$s;
						$destWidth1		= 500;
						$destHeight1		= 500;
						$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
						$image_name		= $s;
							
						$data_to_store_img['site_logo'] = $image_name;
							
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
							$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/merchant_images/';
							$fileNormal 			= $DIR_IMG_NORMAL.$s;
							$result 				= move_uploaded_file($file, $fileNormal);
							
							if($result)
							{
								$srcPath		= FILEUPLOADPATH.'assets/uploads/merchant_images/'.$s;
								$destPath1 	= FILEUPLOADPATH.'assets/uploads/merchant_images/thumb/'.$s;
								$destWidth1	= 500;
								$destHeight1	= 500;
								$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
								$image_name	= $s;
								
								$data_to_store_file[$ufld] = $image_name;
							}
						}
						else
						{
							$DIR_IMG_NORMAL 	= FILEUPLOADPATH.'assets/uploads/merchant_files/';
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
				
					////for sending email to user for registration
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
						$replace 		= array(base_url().'assets/site/images/'.$this->site_logo, $to_name, 'Merchant'."<p>Site Url : ".base_url().$data_to_store['name']."</p>", $this->site_name);
						
						$email_temp_msg= isset($email_temp['email_template']) 	? $email_temp['email_template'] : '';
						$email_temp_msg= str_replace($search, $replace, $email_temp_msg);
						
						$email_temp_sub= isset($email_temp['email_subject']) 	? $email_temp['email_subject'] : '';
						
						if($user_email_id) $this->User_email_model->send_email($user_email_id, $email_temp_sub, $email_temp_msg, '', '', '', $to_name);
						
					}
					//END
				
					$this->session->set_flashdata('flash_message', 'reg_success');
					redirect('control/manage-merchants');
				}
		} 
		else
		{
			$this->session->set_flashdata('flash_message', 'reg_error');
			redirect('control/manage-merchants');
		}
	
			
		redirect('control/manage-merchants');
	}
	
	
	public function add(){
		
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
		$this->mongo_db->where(array('form_type' => 'merchant'));
		$this->mongo_db->where(array('is_fixed' => '1'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_fixed_fields 					= $this->mongo_db->get('form_fields');
		$data['data']['all_fields_fixed']	=	$all_fixed_fields;
		
		//for other fields which is not mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'merchant'));
		$this->mongo_db->where(array('is_fixed' => '0'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_nonfixed_fields 					= $this->mongo_db->get('form_fields');
		$data['data']['all_non_fixed']		= $all_nonfixed_fields;
		//echo "<pre>";
		//print_r($all_nonfixed_fields);die;
		
		$data['view_link'] = 'admin/merchants/add_merchants';
		$this->load->view('includes/template', $data);
		
	}
	
	//Function to update existing form fields and add new ones if added
	public function updt()
	{
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
		$merchant_id 					= ($this->input->post('merchants_id') && $this->input->post('merchants_id')!='') ? strval($this->input->post('merchants_id')) :'';
			
			
		//for is fixed 1 which means mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'merchant'));
		$this->mongo_db->where(array('is_fixed' => '1'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_fixed_fields 				= $this->mongo_db->get('form_fields');
		$data['data']['all_fields_fixed']	=	$all_fixed_fields;
			
		//for other fields which is not mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'merchant'));
		$this->mongo_db->where(array('is_fixed' => '0'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_nonfixed_fields 			= $this->mongo_db->get('form_fields');
		$data['data']['all_non_fixed']	= $all_nonfixed_fields;
			
			
			
			
		//for customer details array
		if($merchant_id !='')
		{
			$this->mongo_db->select('*');
			$this->mongo_db->where(array('_id' 	=> $merchant_id));
			$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
			$merchant_details 				= $this->mongo_db->get('site_users');
			$data['data']['merchant_details']	=	$merchant_details;
		}
			
		//print_r($all_fixed_fields);die;
		$data_to_store = $data_to_store_old = array();
			
			
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $merchant_id=='')
		{
			$merchant_form_id 			= ($this->input->post('merchant_unique_id') && $this->input->post('merchant_unique_id')!='') ? strval($this->input->post('merchant_unique_id')) :'';
			$this->mongo_db->order_by(array('_id' => 'desc'));
			$this->mongo_db->limit(1);
			$get_last_id_arr 			= $this->mongo_db->get('site_users');
				
			$data_to_store 			= array();
				
			//Getting all driver details before update
			$this->mongo_db->where(array('_id' => $merchant_form_id));
			$merchant_details 	= $this->mongo_db->get('site_users');
				
			$old_image 		= (isset($merchant_details[0]['site_logo'])) ? $merchant_details[0]['site_logo'] : '';
				
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
			$data_to_store['meta_keywords']	= ($this->input->post('meta_keywords')!='') ? $this->input->post('meta_keywords') : '';
			$data_to_store['meta_description']	= ($this->input->post('meta_description')!='') ? $this->input->post('meta_description') : '';
			$data_to_store['platform_fee']	= ($this->input->post('platform_fee')!='') ? $this->input->post('platform_fee') : '';
			$data_to_store['stripe_pay_type']	= ($this->input->post('stripe_pay_type')!='') ? $this->input->post('stripe_pay_type') : '';
		$data_to_store['stripe_live_secret_key']	= ($this->input->post('stripe_live_secret_key')!='') ? $this->input->post('stripe_live_secret_key') : '';
		$data_to_store['stripe_live_public_key']	= ($this->input->post('stripe_live_public_key')!='') ? $this->input->post('stripe_live_public_key') : '';
		$data_to_store['stripe_sandbox_secret_key']	= ($this->input->post('stripe_sandbox_secret_key')!='') ? $this->input->post('stripe_sandbox_secret_key') : '';
		$data_to_store['stripe_sandbox_public_key']	= ($this->input->post('stripe_sandbox_public_key')!='') ? $this->input->post('stripe_sandbox_public_key') : '';
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
					$this->mongo_db->where(array('email' => $email_id));
					$all_email=$this->mongo_db->get('site_users');
					
					if(count($all_email) > 0)
					{
						foreach($all_email as $email)
						{
							if($email['_id'] != $merchant_form_id)
							{
								$this->session->set_flashdata('flash_message', 'email_error');
								redirect('control/manage-merchants');
							}
						}
						
					}
				}
				else
				{
					$this->session->set_flashdata('flash_message', 'reg_error');
					redirect('control/manage-merchants');
				}
			}
				
				
			if($do_insert)
			{
				//$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
					
				$this->mongo_db->where(array('_id' 	=> $merchant_form_id));
				$this->mongo_db->set($data_to_store);
				$insert 		= $this->mongo_db->update('site_users');
					
				if(isset($insert))
				{
					//update the member
					$this->mongo_db->where(array('_id' 	=> $merchant_form_id));
					$merchant_date 					= $this->mongo_db->update('site_users');
					
					//Inserting dat into membership table
					$this->mongo_db->where(array('subadmin_id' 		=> $merchant_form_id));
					$count_user_membership 						= $this->mongo_db->count('membership');
					if($count_user_membership == 0)
					{
						$data_to_store_admin['pass_word'] 			= isset($merchant_date[0]['password'])		? $merchant_date[0]['password'] 	: '';
						$data_to_store_admin['password_salt'] 		= $this->password_salt;
						$data_to_store_admin['email_addres']		= isset($data_to_store['email']) 			? $data_to_store['email'] 		: '';
						$data_to_store_admin['is_sub_admin']		= strval(1);
						$data_to_store_admin['subadmin_id']		= strval($merchant_form_id);
						$data_to_store_admin['cmp_auth_id']		= isset($merchant_date[0]['cmp_auth_id'])	? $merchant_date[0]['cmp_auth_id'] : '';
							
						$insert_to_admin_membership 				= $this->mongo_db->insert('membership', $data_to_store_admin);
					}
						
					//Updating data into user permission table for menus
					$this->mongo_db->where(array('user_id' 	=> $merchant_form_id));
					$count_user_permission = $this->mongo_db->count('site_users');
						
					if($count_user_permission == 0)
					{
						//Inserting data into user permission table for menus
						//$data_to_store_user_permission['user_id'] 		= strval($merchant_form_id);
						//$data_to_store_user_permission['menu_ids'] 	= array('576ce206ee32e15e494792f1','578ce09e3668dc07d09e685d','5773afe5cff2932bc25c1d10','577b5c09b21239f70180c638','577bafc3b21239f70180c639','577cd121b21239f70180c63a','577f4998b21239f70180c63b','57c3d681b21239f70180c640','578dd6a34489fb551e78fc70');
						//$insert_to_admin 							= $this->mongo_db->insert('user_permission', $data_to_store_user_permission);
							
						//Inserting data into user permission table for menus
						$this->mongo_db->where(array('is_merchant' => '1'));
						$merchant_menus 	= $this->mongo_db->get('menus');
							
						if(!empty($merchant_menus))
						{
							foreach($merchant_menus as $m => $merchant_me)
								$data_to_store_user_permission['menu_ids'][]	= isset($merchant_me['_id'])	? strval($merchant_me['_id'])   : '';
						}
						else	$data_to_store_user_permission['menu_ids']	= array();
							
						$data_to_store_user_permission['user_id'] 		= strval($insert);
						$insert_to_admin 							= $this->mongo_db->insert('user_permission', $data_to_store_user_permission);
					}
						
					//Upload site fabicon logo image	
					if(isset($_FILES['site_fabicon']['name']) && !empty($_FILES['site_fabicon']['name']))
					{
						//get old logo icon
						$old_icon 			= (isset($merchant_details[0]['site_fabicon'])) ? $merchant_details[0]['site_fabicon'] : '';
						$file_type 			= (isset($_FILES['site_fabicon']['type'])) 	? explode('/', $_FILES['site_fabicon']['type']) : array();
						$file_type_det 		= (isset($file_type[0])) ? $file_type[0] 	: '';
						$filename 			= (isset($_FILES['site_fabicon']['name'])) 	? substr($_FILES['site_fabicon']['name'], strripos($_FILES['site_fabicon']['name'],'.')) : '';
						$s					= time().$filename;
						$file 				= $_FILES['site_fabicon']['tmp_name'];
							
							
							
						$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/site/images/';
						$fileNormal 			= $DIR_IMG_NORMAL.$s;
						$result 				= move_uploaded_file($file, $fileNormal);
							
						if($result)
						{
							if(!empty($old_icon))
							{
								$old_icon_link = FILEUPLOADPATH.'assets/site/images/'.$old_icon;
								@unlink($old_icon_link);
								
								//$old_icon_thumb_link = FILEUPLOADPATH.'assets/site/images/thumb/'.$old_icon;
								//@unlink($old_icon_thumb_link);
							}
								
							//$srcPath		= FILEUPLOADPATH.'assets/site/images/'.$s;
							//$destPath1 		= FILEUPLOADPATH.'assets/site/images/thumb/'.$s;
							//$destWidth1		= 500;
							//$destHeight1		= 500;
							//$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
							
							$image_name		= $s;
							$data_to_store_img['site_fabicon'] 	= $image_name;
							
							$this->mongo_db->where(array('_id' => $merchant_form_id));
							$this->mongo_db->set($data_to_store_img);
							$this->mongo_db->update('site_users');
						}
					}
					
					//Inserting dat into membership table
					if(isset($data_to_store['password']) && trim($data_to_store['password'])!='')
					{
						//Encrype the password with blowfish password encryption
						$data_to_store_admin['pass_word'] 		= $enc_password;
						$data_to_store_admin['password_salt'] 		= $this->password_salt;
					}
					if(isset($data_to_store['email']))
						$data_to_store_admin['email_addres']		= $data_to_store['email'];
					
					$this->mongo_db->where(array('subadmin_id' 	=> $merchant_form_id));
					$this->mongo_db->set($data_to_store_admin);
					$insert = $this->mongo_db->update('membership');
					//END
					$to_up_files = array();
					
					//upload fixed field profile image
					if(isset($_FILES['fixed_fields']['name']['site_logo']) && !empty($_FILES['fixed_fields']['name']['site_logo']))
					{
						$file_type 			= (isset($_FILES['fixed_fields']['type']['site_logo'])) ? explode('/', $_FILES['fixed_fields']['type']['site_logo']) : array();
						$file_type_det 		= (isset($file_type[0])) ? $file_type[0] : '';
						
						$filename 			= (isset($_FILES['fixed_fields']['name']['site_logo'])) ? substr($_FILES['fixed_fields']['name']['site_logo'],strripos($_FILES['fixed_fields']['name']['site_logo'],'.')) : '';
						$s					= time().$filename;
						$file 				= $_FILES['fixed_fields']['tmp_name']['site_logo'];
						
						$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/merchant_images/';
						$fileNormal 			= $DIR_IMG_NORMAL.$s;
						$result 				= move_uploaded_file($file, $fileNormal);
						
						if($result)
						{
							if($old_image)
							{
								@unlink(FILEUPLOADPATH.'assets/uploads/merchant_images/'.$old_image);
								@unlink(FILEUPLOADPATH.'assets/uploads/merchant_images/thumb/'.$old_image);
							}
							
							$srcPath			= FILEUPLOADPATH.'assets/uploads/merchant_images/'.$s;
							$destPath1 		= FILEUPLOADPATH.'assets/uploads/merchant_images/thumb/'.$s;
							$destWidth1		= 500;
							$destHeight1		= 500;
							$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
							$image_name		= $s;
							
							$data_to_store_img['site_logo'] = $image_name;
							
							$this->mongo_db->where(array('_id' => $merchant_form_id));
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
								$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/merchant_images/';
								$fileNormal 			= $DIR_IMG_NORMAL.$s;
								$result 				= move_uploaded_file($file, $fileNormal);
								
								if($result)
								{
									$old_data 	= (isset($merchant_details[0][$ufld])) ? $merchant_details[0][$ufld] : '';
									if($old_data)
									{
										@unlink(FILEUPLOADPATH.'assets/uploads/merchant_images/'.$old_data);
										@unlink(FILEUPLOADPATH.'assets/uploads/merchant_images/thumb/'.$old_data);
									}
									
									$srcPath		= FILEUPLOADPATH.'assets/uploads/merchant_images/'.$s;
									$destPath1 	= FILEUPLOADPATH.'assets/uploads/merchant_images/thumb/'.$s;
									$destWidth1	= 500;
									$destHeight1	= 500;
									$this->imagethumb->resizeProportional($destPath1, $srcPath, $destWidth1, $destHeight1);
									$image_name	= $s;
									
									$data_to_store_file[$ufld] = $image_name;
								}
							}
							else
							{
								$DIR_IMG_NORMAL 	= FILEUPLOADPATH.'assets/uploads/merchant_files/';
								$fileNormal 		= $DIR_IMG_NORMAL.$s;
								$result 			= move_uploaded_file($file, $fileNormal);
								
								if($result){
									$old_data 	= (isset($merchant_details[0][$ufld])) ? $merchant_details[0][$ufld] : '';
									if($old_data)
										@unlink(FILEUPLOADPATH.'assets/uploads/merchant_files/'.$old_data);
									
									$data_to_store_file[$ufld] = $s;
								}
							}
						}
						
						if(!empty($data_to_store_file))
						{
							$this->mongo_db->where(array('_id' => $merchant_form_id));
							$this->mongo_db->set($data_to_store_file);
							$this->mongo_db->update('site_users');
						}
					}
					
				}
					
					$this->session->set_flashdata('flash_message', 'option_updated');
					redirect('control/manage-merchants');
			} 
			else
			{
				$this->session->set_flashdata('flash_message', 'error_option_update');
				redirect('control/manage-merchants');
			}
		
				
			redirect('control/manage-merchants');
		}
		
		if(!isset($data['data']['merchant_details']))
		{
			redirect('control/manage-merchants');
		}
		
		
		$data['view_link'] = 'admin/merchants/edit_merchants';
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
	
	//public function check_string()
	//{
	//	$this->common_model->generate_unique_code('10','site_users','cmp_auth_id');
	//}

}