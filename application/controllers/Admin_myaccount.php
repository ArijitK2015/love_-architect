<?php
class Admin_myaccount extends MY_Controller {

		
	public function __construct()
	{
		parent::__construct();
		
	}
		
	/**
	 * Load the main view with all the current model model's data.
	* @return void
	*/
	public function index()
	{
		$data['data']					= $this->data;
			
		$user_id 						= $merchant_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
			
		if(!empty($this->cmp_auth_id))
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		else
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			
		$data['data']['setting_data'] 	= $admin_details;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		$data['data']['myaccount_data'] 	= $admin_details;
			
		$all_existing_menus 			= array();
		$data['data']['all_existing_menus']= $all_existing_menus;
			
		$data['data']['all_cars_details'] 	= array();
		$data['data']['all_zip_filter'] 	= array();
			
			
			
		//for is fixed 1 which means mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' => 'merchant'));
		$this->mongo_db->where(array('is_fixed' => '1'));
		$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
		$all_fixed_fields 				= $this->mongo_db->get('form_fields');
			
			
		$data['data']['all_fields_fixed']			= $all_fixed_fields;
			
		//for other fields which is not mandatory fields
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('form_type' 	=> 'merchant'));
		$this->mongo_db->where(array('is_fixed' 	=> '0'));
		$this->mongo_db->order_by(array('is_fixed' 	=> 'desc', '_id' => 'asc'));
		$all_nonfixed_fields 					= $this->mongo_db->get('form_fields');
		$data['data']['all_non_fixed']			= $all_nonfixed_fields;
			
			
		//for customer details array
		if($merchant_id !='')
		{
			$this->mongo_db->select('*');
			$this->mongo_db->where(array('_id' 	=> $merchant_id));
			$this->mongo_db->order_by(array('is_fixed' => 'desc', '_id' => 'asc'));
			$merchant_details 					= $this->mongo_db->get('site_users');
			$data['data']['merchant_details']		= $merchant_details;
		}
			
			
		if(!empty($this->cmp_auth_id))	
			$data['view_link'] = 'admin/admin_myaccount_page';
		else
			$data['view_link'] = 'admin/myaccount_page';
			
		$this->load->view('includes/template', $data);
	}
		
		
	public function updt()
	{
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		$myaccount_data			= $this->myaccount_model->get_account_data($user_id);
		$old_img 					= (isset($myaccount_data[0]['profile_iamge'])) ? $myaccount_data[0]['profile_iamge'] : '';
		
		$this->load->library('form_validation');
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
				//form validation
				$this->form_validation->set_rules('first_name', 'First name', 'trim|required');
				$this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
				$this->form_validation->set_rules('email_addres', 'Email Address', 'trim|required|valid_email');
				$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a><strong>', '</strong></div>');
			
			
			if ($this->form_validation->run())
			{
				$this->mongo_db->where(array('email_addres' => trim($this->input->post('email_addres'))))->where_ne('id', $user_id);
				$chek_exist = $this->mongo_db->count('membership');
				
				if($chek_exist == 0)
				{
					$data_to_store = array(
						'first_name' 		=> $this->input->post('first_name'),
						'last_name' 		=> $this->input->post('last_name'),
						'email_addres' 	=> $this->input->post('email_addres'),
						'address' 		=> $this->input->post('address'),
						'search_type'		=> $this->input->post('search_type'),
						'zip_code'		=> $this->input->post('zip_code'),
						'search_radious' 	=> $this->input->post('rad_miles'),
					);
					
					if(isset($_FILES['profile_image']['name']) && (!empty($_FILES['profile_image']['name'])))
					{
						$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/subadmin_image/';
						$filename 			= substr($_FILES['profile_image']['name'],strripos($_FILES['profile_image']['name'],'.'));
						$s					= time().$filename;
						$fileNormal 			= $DIR_IMG_NORMAL.$s;
						$file 				= $_FILES['profile_image']['tmp_name'];
						list($width, $height) 	= getimagesize($file);
						$result 				= move_uploaded_file($file, $fileNormal);
						
						if($result)
						{
							if($old_img){
								@unlink(FILEUPLOADPATH.'assets/uploads/subadmin_image/'.$old_img);
								@unlink(FILEUPLOADPATH.'assets/uploads/subadmin_image/thumb/'.$old_img);
							}
							
							$srcPath		= FILEUPLOADPATH.'assets/uploads/subadmin_image/'.$s;
							$destPath1 	= FILEUPLOADPATH.'assets/uploads/subadmin_image/thumb/'.$s;
							$destWidth1	= 500;
							$destHeight1	= 500;
							
							$this->imagethumb->thumbnail_new($destPath1, $srcPath, $destWidth1, $destHeight1);
							$image_name	= $s;
							
							$data_to_store['profile_image'] = $image_name;
						}
					}
					
					//echo '<pre>'; print_r($data_to_store); echo '</pre>'; die;
					
					//if the insert has returned true then we show the flash message
					$update = $this->myaccount_model->update_account($data_to_store, $user_id);
					
					if($update){
						$this->session->set_flashdata('flash_message', 'info_updated');
					}else{
						$this->session->set_flashdata('flash_message', 'info_not_updated');
					}
				}
				else{
					$this->session->set_flashdata('flash_message', 'email_error');
				}
			}
		}
		
		//settings data 
		redirect('control/myaccount');
	}
		
		
	public function update_merchant()
	{
		$user_id 				= ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		$data_to_store 		= $data_to_store_old	= array();
			
			
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $merchant_id=='')
		{
			$merchant_id 		= ($this->input->post('merchants_id') && $this->input->post('merchants_id') != '') ? strval($this->input->post('merchants_id')) : $user_id;
				
			//Getting all driver details before update
			$this->mongo_db->where(array('_id' => $merchant_id));
			$merchant_details 	= $this->mongo_db->get('site_users');
				
			$old_image 		= (isset($merchant_details[0]['site_logo'])) ? $merchant_details[0]['site_logo'] : '';
			$fixed_fields 		= $this->input->post('fixed_fields');
			$fixed_fields 		= ($fixed_fields) ? $fixed_fields : array();
			$extra_fields 		= $this->input->post('extra_fields');
			$extra_fields 		= ($extra_fields) ? $extra_fields : array();
				
				
			if(!empty($fixed_fields))
			{
				foreach($fixed_fields as $f => $field)
				{
					if(!isset($data_to_store[$f]))
					{
						if(!isset($data_to_store[$f]))
						{
							$data_to_store[$f] 		= (is_array($field)) ? (object)($field) :strval($field);
								
							//for storing the country code and name
							if(isset($data_to_store[$f]->lat) && isset($data_to_store[$f]->long))
							{
								$end_country_data 				= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$data_to_store[$f]->lat.'&lng='.$data_to_store[$f]->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
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
						else
						{
							$data_to_store[$f.'_1'] 				= (is_array($field)) ? (object)($field) :strval($field);
							
							//for storing the country code and name
							if(isset($data_to_store[$f.'_1']->lat) && isset($data_to_store[$f.'_1']->long))
							{
								$end_country_data 				= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$data_to_store[$f.'_1']->lat.'&lng='.$data_to_store[$f.'_1']->long.'&username='.$this->geonames_username.'&type=JSON', false, $context));
								$end_country_data_arr 			= json_decode($end_country_data);
								$end_country_name 				= isset($end_country_data_arr->countryName) ? $end_country_data_arr->countryName : '';
								$end_country_code 				= isset($end_country_data_arr->countryCode) ? $end_country_data_arr->countryCode : '';
								$data_to_store[$f.'_1']->lat 		= (float)$data_to_store[$f.'_1']->lat;
								$data_to_store[$f.'_1']->lat_str 	= strval($data_to_store[$f.'_1']->lat);
								$data_to_store[$f.'_1']->long 	= (float)$data_to_store[$f.'_1']->long;
								$data_to_store[$f.'_1']->long_str 	= strval($data_to_store[$f.'_1']->long);
								$data_to_store[$f.'_1']->country 	= $end_country_name;
								$data_to_store[$f.'_1']->country_code = $end_country_code;
								
							}
						}
					}
				}
			}
				
			if(isset($data_to_store['password']) && trim($data_to_store['password'])!='')
			{
				//Encrype the password with blowfish password encryption
				$enc_password 						= crypt($data_to_store['password'], $this->password_salt);
				$data_to_store['password'] 			= $enc_password;
				$data_to_store['password_salt'] 		= $this->password_salt;
			}
			else unset($data_to_store['password']);
				
				
			$data_to_store['admin_status']			= ($this->input->post('admin_status')!='') ? $this->input->post('admin_status') : '1';
			$data_to_store['user_timezone']			= ($this->input->post('user_timezone')!='') ? $this->input->post('user_timezone') : $this->system_timezone;
			$data_to_store['meta_keywords']			= ($this->input->post('meta_keywords')!='') ? $this->input->post('meta_keywords') : '';
			$data_to_store['meta_description']			= ($this->input->post('meta_description')!='') ? $this->input->post('meta_description') : '';
			$data_to_store['platform_fee']			= ($this->input->post('platform_fee')!='') ? $this->input->post('platform_fee') : '';
			//$data_to_store['stripe_pay_type']			= ($this->input->post('stripe_pay_type')!='') ? $this->input->post('stripe_pay_type') : '';
			//$data_to_store['stripe_live_secret_key']	= ($this->input->post('stripe_live_secret_key')!='') ? $this->input->post('stripe_live_secret_key') : '';
			//$data_to_store['stripe_live_public_key']	= ($this->input->post('stripe_live_public_key')!='') ? $this->input->post('stripe_live_public_key') : '';
			//$data_to_store['stripe_sandbox_secret_key']	= ($this->input->post('stripe_sandbox_secret_key')!='') ? $this->input->post('stripe_sandbox_secret_key') : '';
			//$data_to_store['stripe_sandbox_public_key']	= ($this->input->post('stripe_sandbox_public_key')!='') ? $this->input->post('stripe_sandbox_public_key') : '';
				
				
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
							if($email['_id']!=$merchant_id)
							{
								$this->session->set_flashdata('flash_message', 'email_error');
								redirect('control/myaccount');
							}
						}
						
					}
				}
				else
				{
					$this->session->set_flashdata('flash_message', 'reg_error');
					redirect('control/myaccount');
				}
			}
			
			
			if($do_insert)
			{
				//$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
				
				$this->mongo_db->where(array('_id' 	=> $merchant_id));
				$this->mongo_db->set($data_to_store);
				$insert = $this->mongo_db->update('site_users');
			
				if(isset($insert))
				{
					//Upload site fabicon logo image	
					if(isset($_FILES['site_fabicon']['name']) && !empty($_FILES['site_fabicon']['name']))
					{
						//get old logo icon
						$old_icon 		= (isset($merchant_details[0]['site_fabicon'])) ? $merchant_details[0]['site_fabicon'] : '';
							
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
							
							$this->mongo_db->where(array('_id' => $merchant_id));
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
					
					$this->mongo_db->where(array('subadmin_id' 	=> $merchant_id));
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
							
							$this->mongo_db->where(array('_id' => $merchant_id));
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
							$this->mongo_db->where(array('_id' => $merchant_id));
							$this->mongo_db->set($data_to_store_file);
							$this->mongo_db->update('site_users');
						}
					}
					
				}
					
					$this->session->set_flashdata('flash_message', 'option_updated');
					redirect('control/myaccount');
			} 
			else
			{
				$this->session->set_flashdata('flash_message', 'error_option_update');
				redirect('control/myaccount');
			}
		
				
			redirect('control/admin-dashboard');
		}
		
		if(!isset($data['data']['merchant_details']))
		{
			redirect('control/admin-dashboard');
		}
			
			
		$data['view_link'] = 'admin/merchants/edit_merchants';
		$this->load->view('includes/template', $data);
	}
	

}