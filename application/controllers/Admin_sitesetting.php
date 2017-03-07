<?php
class Admin_sitesetting extends CI_Controller {

	public $system_timezone		= "UTC";
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		//Getting site settings data
		$settings_data = $this->sitesetting_model->get_settings();
		$this->system_timezone = (isset($settings_data[0]['system_timezone'])) ? $settings_data[0]['system_timezone'] : $this->system_timezone;
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
		$user_id = ($this->session->userdata('user_id')) ?  $this->session->userdata('user_id') : 0;
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['system_timezone'] 	= $this->system_timezone;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		
		$data['view_link'] = 'admin/sitesetting_page';
		$this->load->view('includes/template', $data);
	}//index
	
	public function updt()
	{
		$this->load->library('form_validation');
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			//form validation
			$this->form_validation->set_rules('site_name', 'Website Name', 'trim|required');
			$this->form_validation->set_rules('system_email', 'System Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('admin_pagination', 'Records per page of Admin', 'trim|required');
			$this->form_validation->set_rules('site_pagination', 'Records per page of Site', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a><strong>', '</strong></div>');
		
			if ($this->form_validation->run())
			{
				$data_id 		= ($this->input->post('site_id')) ? $this->input->post('site_id') : '';
				
				$data_to_store = array(
					'site_name' 				=> ($this->input->post('site_name')) 		? $this->input->post('site_name')  	: '',
					'system_email' 			=> ($this->input->post('system_email')) 	? $this->input->post('system_email') 	: '',
					'admin_pagination' 			=> ($this->input->post('admin_pagination')) 	? $this->input->post('admin_pagination') : '',
					'site_pagination' 			=> ($this->input->post('site_pagination')) 	? $this->input->post('site_pagination') : '',
					'platform_fee' 			=> ($this->input->post('platform_fee')) 	? $this->input->post('platform_fee') : '',
					'meta_keywords' 			=> ($this->input->post('meta_keywords')) 	? $this->input->post('meta_keywords') : '',
					'meta_description' 			=> ($this->input->post('meta_description')) 	? $this->input->post('meta_description') : '',
					'system_timezone' 			=> ($this->input->post('server_timezone')) 	? $this->input->post('server_timezone') : '',
					
					'facebook' 				=> ($this->input->post('facebook')) 		? $this->input->post('facebook') : '',
					'twitter' 				=> ($this->input->post('twitter')) 		? $this->input->post('twitter') : '',
					'smtp_server' 				=> ($this->input->post('smtp_server')) 		? $this->input->post('smtp_server') : '',
					'smtp_port' 				=> ($this->input->post('smtp_port')) 		? $this->input->post('smtp_port') : '',
					'smtp_username' 			=> ($this->input->post('smtp_username')) 	? $this->input->post('smtp_username') : '',
					'smtp_password' 			=> ($this->input->post('smtp_password')) 	? $this->input->post('smtp_password') : '',
					
					'google_map_api_key' 		=> ($this->input->post('google_map_api_key')) 	? $this->input->post('google_map_api_key') : '',
					'stripe_pay_type' 			=> ($this->input->post('stripe_pay_type')) 		? $this->input->post('stripe_pay_type') : '',
					'stripe_live_secret_key' 	=> ($this->input->post('stripe_live_secret_key')) ? $this->input->post('stripe_live_secret_key') : '',
					'stripe_live_public_key' 	=> ($this->input->post('stripe_live_public_key')) ? $this->input->post('stripe_live_public_key') : '',
					'stripe_sandbox_secret_key' 	=> ($this->input->post('stripe_sandbox_secret_key')) ? $this->input->post('stripe_sandbox_secret_key') : '',
					'stripe_sandbox_public_key' 	=> ($this->input->post('stripe_sandbox_public_key')) ? $this->input->post('stripe_sandbox_public_key') : '',
					
					'twilio_AccountSid' 		=> ($this->input->post('twilio_AccountSid')) 	? $this->input->post('twilio_AccountSid') : '',
					'twilio_AuthToken' 			=> ($this->input->post('twilio_AuthToken')) 		? $this->input->post('twilio_AuthToken') : '',
					'twilio_mobile_no' 			=> ($this->input->post('twilio_mobile_no')) 		? $this->input->post('twilio_mobile_no') : '',
					
					'linkedinApiKey' 			=> ($this->input->post('linkedinApiKey')) 		? $this->input->post('linkedinApiKey') : '',
					'linkedinApiSecret' 		=> ($this->input->post('linkedinApiSecret')) 	? $this->input->post('linkedinApiSecret') : ''
				);
				
				
				if($this->sitesetting_model->update_settings($data_to_store, $data_id) == TRUE){
						
					//Upload site logo image	
					if(isset($_FILES['site_logo']['name']) && !empty($_FILES['site_logo']['name']))
					{
						//get old logo icon
						$old_icon				= (isset($settings_data[0]['site_logo'])) 	? $settings_data[0]['site_logo'] : '';
							
						$file_type 			= (isset($_FILES['site_logo']['type'])) 	? explode('/', $_FILES['site_logo']['type']) : array();
						$file_type_det 		= (isset($file_type[0])) ? $file_type[0] 	: '';
						
						$filename 			= (isset($_FILES['site_logo']['name'])) 	? substr($_FILES['site_logo']['name'], strripos($_FILES['site_logo']['name'],'.')) : '';
						$s					= time().$filename;
						$file 				= $_FILES['site_logo']['tmp_name'];
							
							
							
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
							$data_to_store_img['site_logo'] 	= $image_name;
							
							$this->mongo_db->where(array('_id' => $data_id));
							$this->mongo_db->set($data_to_store_img);
							$this->mongo_db->update('settings');
						}
					}
					
					
					//Upload site fabicon logo image	
					if(isset($_FILES['site_fabicon']['name']) && !empty($_FILES['site_fabicon']['name']))
					{
						//get old logo icon
						$old_icon				= (isset($settings_data[0]['site_fabicon'])) 	? $settings_data[0]['site_fabicon'] : '';
							
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
							
							$this->mongo_db->where(array('_id' => $data_id));
							$this->mongo_db->set($data_to_store_img);
							$this->mongo_db->update('settings');
						}
					}
					
					$this->session->set_flashdata('flash_message', 'site_updated');
				}else{
					$this->session->set_flashdata('flash_message', 'site_not_updated');
				}
			}
		}
		
		redirect('control/sitesetting');
	}
}