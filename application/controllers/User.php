<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
class User extends MY_Controller {

	/**
	* Check if the user is logged in, if he's not, 
	* send him to the login page
	* @return void
	*/
		
	public function __construct()
	{
		parent::__construct();
	}
		
	function index()
	{
			
	}

	/**
	* encript the password 
	* @return mixed
	*/	
	function __encrip_password($password) {
	    return $this->encryption->encrypt($password);
	}	

	/**
	* check the username and the password with the database
	* @return void
	*/
	
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
			$count = 0;
			if(!empty($cmp_auth_id))
			{
				$this->mongo_db->where(array('cmp_auth_id' => $cmp_auth_id));
				$this->mongo_db->where(array('user_type' => 'merchant'));
			
				$this->mongo_db->where(array('email' => $email_id));
				$count = $this->mongo_db->count('site_users');
			}
			if($count>0)
			{
				
				$this->mongo_db->where(array('cmp_auth_id' => $cmp_auth_id));
				$this->mongo_db->where(array('user_type' => 'merchant'));
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
					$link 			= base_url().'control/admin-validate-password/'.$new_pass_val;
					
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
					
				}
				else
				{
					$this->session->set_flashdata('flash_message_cont', $email_id);
					$this->session->set_flashdata('flash_message', 'forget_pass_error_user_dact');
					
					redirect('control/admin-forgot-password');
				}
			}
			else{
				$this->session->set_flashdata('flash_message_cont', $email_id);
				$this->session->set_flashdata('flash_message', 'forget_pass_error_user_dact');
				
				redirect('control/admin-forgot-password');
			}
		}
		
		
		if(!empty($this->cmp_details))
			$this->data['is_merchant'] = '1';
		else
			$this->data['is_merchant'] = '0';
		
		$this->data['new_pass'] 	= '0';	
		$this->data['site_name'] 	= $this->Users_model->get_site_name();
		
		$this->data['settings'] 		= $this->sitesetting_model->get_settings();
		$data						= $this->data;
		$this->load->view('admin/admin_forget_password', $data);	
		
	}

	public function validate_password()
	{
		
		$cmp_auth_no 				= isset($this->cmp_details[0]['cmp_auth_id']) ? $this->cmp_details[0]['cmp_auth_id'] : '';
		$cmp_auth_name 				= isset($this->cmp_details[0]['name'])  	 ? $this->cmp_details[0]['name'] 		: '';
		
		$this->data['cmp_auth_link_id']	= (!empty($cmp_auth_name) && !empty($cmp_auth_no)) ? $cmp_auth_name.'-'.$cmp_auth_no.'/' : '';
		
		$settings 	= $this->sitesetting_model->get_settings();
		$sitename 	= (isset($settings[0]['site_name'])) ? $settings[0]['site_name'] : '';
		
		if(!empty($this->cmp_details))
			$validate_id	= $this->uri->segment(4);
		else
			$validate_id	= $this->uri->segment(3);
		
		$this->data['verify_code'] 	= $validate_id;
		
		$user_details= '';
		if(!empty($this->cmp_auth_id))
		{
				$this->mongo_db->where(array('cmp_auth_id' => $this->cmp_auth_id));
				$this->mongo_db->where(array('user_type' => 'merchant'));
				$this->mongo_db->where(array('verify_pass_code' => $validate_id));
				
			$user_det_arr 	= $this->mongo_db->get('site_users');
			$user_details	= isset($user_det_arr[0]) ? $user_det_arr[0] : '';
		}
		
		
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
			redirect('control/admin-forgot-password');
		}
		
		$this->data['settings'] 		= $this->sitesetting_model->get_settings();
		$data						= $this->data;
		$this->load->view('admin/admin_forget_password', $data);
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
			
			$this->mongo_db->where(array('user_type' => 'merchant','verify_pass_code' => $verify_code, '_id' => $user_id));
			$user_det_arr 	= $this->mongo_db->get('site_users');
			$user_details	= isset($user_det_arr[0]) ? $user_det_arr[0] : '';
			
			if(!empty($user_details))
			{
				$data_to_store 	= array('password' => $enc_password, 'password_salt' => $this->password_salt,'forgot_pass' => '0', 'verify_pass_code' => '');
				
				//Updating user with new data
				$this->mongo_db->where(array('_id' => $user_id));
				$this->mongo_db->set($data_to_store);
				$this->mongo_db->update('site_users');
				
				//storing password to membership table
				$data_to_store_membership 	= array('pass_word' => $enc_password, 'password_salt' => $this->password_salt);
				
				$this->mongo_db->where(array('subadmin_id' => $user_id));
				$this->mongo_db->set($data_to_store_membership);
				$this->mongo_db->update('membership');
				
				$this->session->set_flashdata('flash_message', 'pass_updated');
				redirect('control/login');
				
			}
			else{
				$this->session->set_flashdata('flash_message', 'pass_updated_error');
				redirect('control/admin-forgot-password');
			}
		}
		else
			redirect('control/login');
	}

	function validate_credentials()
	{
		$user_name 		= $this->input->post('user_name');
		$password 		= $this->input->post('password');
			
		//MERCHANT WISE LOGIN
			
		//first we have to get the user password salt
		if(!empty($this->cmp_details))
		{
			$user_det_salt = $this->Users_model->get_user_salt('', $user_name);
			$user_det_salt = ($user_det_salt) ? $user_det_salt : $this->password_salt;
			$enc_password 	= crypt($password, $user_det_salt);
			
			$membership_details 	= $this->common_model->get('membership', array(), array('email_addres' => $user_name,'pass_word' => $enc_password,'cmp_auth_id' => $this->cmp_auth_id,'is_sub_admin' => '1'));
				
			$user_id 			= isset($membership_details[0]['subadmin_id']) ? $membership_details[0]['subadmin_id'] : '';
				
			//echo '<pre>'; print_r($membership_details); echo $user_id; echo '</pre>'; die;	
				
			if($user_id != '')
			{
				//get merchant details
				$this->mongo_db->where(array('_id' => $user_id));
				$user_details 	= $this->mongo_db->get('site_users');
					
				//$company_name 	= isset($user_details[0]['company_name']) 	? $user_details[0]['company_name'] : '';
				$site_title 	= isset($user_details[0]['site_title']) 	? $user_details[0]['site_title'] : '';
					
				$user_data 	= array(
								'admin_login_session' 	=> 1,
								'user_id_hotcargo' 		=> $user_id,
								'user_name_hotcargo' 	=> $site_title,
								'is_merchant' 			=> 1,
								'is_superadmin' 		=> 0,
								'is_logged_in' 		=> true
							);
					
				$this->session->set_userdata($user_data);
				redirect('control/admin-dashboard');
			}
			else
			{
				$this->session->set_flashdata('flash_message', 'not_valid');
				redirect('control/login');
			}	
		}
		else{
			$user_id 			= $this->Users_model->validate($user_name, $password);
				
			if($user_id!=0)
			{
				$users_details = $this->common_model->get('membership', array(), array('id' => $user_id));
					
				$data 		= array(
								'admin_login_session' 	=> 1,
								'user_id_hotcargo' 		=> $user_id,
								'user_name_hotcargo' 	=> $users_details[0]['user_name'],
								'is_merchant' 			=> 0,
								'is_superadmin' 		=> 1,
								'is_logged_in' 		=> true
							);
					
				$this->session->set_userdata($data);
				redirect('control/admin-dashboard');	
			}
			else{
				
				$this->session->set_flashdata('flash_message', 'not_valid');
				redirect('control/login');
			}
		}
	}
	
	function pre_validation()
	{
		//$data1 = array(
		//		'password1' => '',
		//		'user_name' => '',
		//	);
	
		$user_name =$this->input->get('username');
		$password =$this->__encrip_password($this->input->get('password1'));
		//$user_name 		= ($this->input->get('username')!='')?$this->input->get('username'):$_SESSION['user_name'];
		//$password 		= (($this->__encrip_password($this->input->get('password1')))!='')?$this->__encrip_password($this->input->get('password1')):$_SESSION['password1'];
		$is_valid 		= $this->Users_model->validate($user_name, $password);
		$data['data_result']=$is_valid;
		
		if($is_valid)
		{
			
			
			$users_details = $this->common_model->get('membership',array('*'),array('id'=>$is_valid));
			//$users_details = $this->common_model->get('membership',array('*'),array('status'=>'Y','id'=>$is_valid));
																		 
			$phone_number=isset($users_details[0]['phone_number']) ? $users_details[0]['phone_number'] :'';
			$country_id=isset($users_details[0]['country_id']) ? $users_details[0]['country_id'] :'';
			$otp 	= mt_rand(100000,999999);
			$data 	= array('otp_code' =>'123456');
			$update 	= $this->Users_model->update_otp($is_valid, $data);
			//$send_code 	= $this->Users_model->send_sms_code($otp, $phone_number,$country_id);
			
			
			echo $is_valid;
		}
		else
			echo 0;
		
		die;
	}
	function pre_resend_code()
	{
		$id= $this->input->get('users_id');
		$users_details = $this->common_model->get('membership',array('*'),array('id'=>$id));
		if(count($users_details)>0)
		{
			$phone_number=isset($users_details[0]['phone_number']) ? $users_details[0]['phone_number'] :'';
			$country_id=isset($users_details[0]['country_id']) ? $users_details[0]['country_id'] :'';
			$otp 	= mt_rand(100000,999999);
			$data 	= array('otp_code' =>'123456');
			$update 	= $this->Users_model->update_otp($id, $data);
			//$send_code 	= $this->Users_model->send_sms_code($otp, $phone_number,$country_id);
			echo $id;
		}
		else
			echo 0;
		
		die;
	}
	
	
	/**
	* The method just loads the signup view
	* @return void
	*/
	function signup()
	{
		$this->load->view('admin/signup_form');	
	}
	

    /**
    * Create new user and store it in the database
    * @return void
    */	
	function create_member()
	{
		$this->load->library('form_validation');
		
		// field name, error message, validation rules
		$this->form_validation->set_rules('first_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/signup_form');
		}
		
		else
		{		
			if($query = $this->Users_model->create_member())
			{
				$this->load->view('admin/signup_successful');			
			}
			else
			{
				$this->load->view('admin/signup_form');			
			}
		}
		
	}
	
	/**
    * Destroy the session, and logout the user.
    * @return void
    */		
	function logout()
	{
		$this->session->sess_destroy();
		redirect('control');
	}
	
	function ckeditor_fileupload()
	{    
		$uploads 			= $_FILES['upload'];
		$CKEditorFuncNum 	= $this->input->get("CKEditorFuncNum");
	 
		// $file = System.IO.Path.GetFileName(uploads.FileName);
		//$file=$uploads['name'].time();
	   
		// uploads.SaveAs(context.Server.MapPath(".") + "\\Images\\"+ file);
		
		$filename 		= substr($uploads['name'],strripos($uploads['name'],'.'));
		$DIR_IMG_NORMAL	= FILEUPLOADPATH.'assets/uploads/editor_photos/';			   
			$s			= time().$filename;	
			$fileNormal 	= $DIR_IMG_NORMAL.$s;
			$file 		= $uploads['tmp_name'];
		$result			= move_uploaded_file($file, $fileNormal);
		
		if($result)
		{
			list($width, $height, $type, $attr) = getimagesize($fileNormal);
			
			$fileWidth 		= $width;
			$fileHeight 		= $height;
			$fileType 		= $type;
			//check image exif
			$orientation 		= 0;
			if( function_exists( 'exif_read_data' ))
			{
				$exif 		= exif_read_data(FILEUPLOADPATH.'assets/uploads/editor_photos/'.$upload_data['file_name']);
				if(!empty($exif)) $orientation 	= (isset($exif['Orientation'])) ? $exif['Orientation'] : 0;
			}
			
			//echo 'ori: '.$orientation; die;
			if($orientation > 0)
			{
				//Now Fix the Orientation
				switch($orientation) {
					case 3:
						$image_p = $this->image_resize->open( FILEUPLOADPATH.'assets/uploads/editor_photos/'.$upload_data['file_name'] , $fileType );
						$image_p = imagerotate($image_p, 180, 0);
						break;
					case 6:
						$image_p = $this->image_resize->open( FILEUPLOADPATH.'assets/uploads/editor_photos/'.$upload_data['file_name'] , $fileType );
						$image_p = imagerotate($image_p, -90, 0);
						break;
					case 8:
						$image_p = $this->image_resize->open( FILEUPLOADPATH.'assets/uploads/editor_photos/'.$upload_data['file_name'] , $fileType );
						$image_p = imagerotate($image_p, 90, 0);
						break;
				}
				
				// Set output quality
				$imgQuality	= 80;
				if( $fileWidth < 200 || $fileHeight < 200 )
					$imgQuality = 99;
				
				$pngQuality 	= ($imgQuality - 100) / 11.111111;
				$pngQuality 	= round(abs($pngQuality));
				
				// Test if type is png
				if( $fileType == 'image/png' || $fileType == 'image/x-png' )
				{
					imagepng($image_p, FILEUPLOADPATH.'assets/uploads/editor_photos/'.$upload_data['file_name'], $pngQuality);
				}
				elseif ( $fileType == 'image/gif')
				{
					imagegif( $image_p, FILEUPLOADPATH.'assets/uploads/editor_photos/'.$upload_data['file_name']);
				}
				else
				{
					imagejpeg($image_p, FILEUPLOADPATH.'assets/uploads/editor_photos/'.$upload_data['file_name']);
				}
			}
			
			$url 			= base_url().'/assets/uploads/editor_photos/'.$s;  
			
			if($result)
			{
				echo "<script>window.parent.CKEDITOR.tools.callFunction(
				".$CKEditorFuncNum.", \"" .$url. "\");</script>";
		   
			}
		}
	}
}