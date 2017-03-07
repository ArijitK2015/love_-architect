<?php
class Manage_jobs_controller extends CI_Controller {
		
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
		
	/**
	* Responsable for auto load the model
	* @return void
	*/
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
			
		$this->data['cmp_details']		= isset($this->cmp_details[0]) ? $this->cmp_details[0] : array();
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
		$user_id 						= $merchant_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
			
		if(!empty($this->cmp_auth_id))
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		else
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			
		$data['data']['setting_data'] 	= $admin_details;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
			
		//jobs data 
		$data['data']['myaccount_data'] 	= $admin_details;
			
		$all_contents					= array();
			
		if(!empty($this->cmp_auth_id))
		{
			//get all users of this merchant
			$this->mongo_db->where(array('merchant_id' => $this->cmp_auth_id));
			$all_cmp_users 			= $this->mongo_db->get('site_users');
			$all_cmp_ids				= array();
				
			if(!empty($all_cmp_users))
			{
				foreach($all_cmp_users as $k => $cmp_user)	$all_cmp_ids[] = (isset($cmp_user['_id']))	? strval($cmp_user['_id'])	: '';
				
				//get all jobs from these users of this merchant
				$this->mongo_db->where_in('user_id', 	$all_cmp_ids);
				$all_contents 				= $this->mongo_db->get('jobs');
			}
			
			
				
			foreach($all_contents as $key => $rows)
			{
				$user_id 				= (isset($rows['user_id']))	? strval($rows['user_id']) : '';
				$this->mongo_db->where(array('_id' => $user_id));
				$cmp_user_det 			= $this->mongo_db->get('site_users');
					
				$all_contents[$key]['user_details']	= isset($cmp_user_det[0]) ? $cmp_user_det[0] : array();
			}
				
		}
		else
		{
			$all_contents 				= $this->mongo_db->get('jobs');
				
			foreach($all_contents as $key => $rows)
			{
				$job_id    	= strval($rows['_id']);
				$client_id 	= (isset($rows['user_id'])) ? $rows['user_id'] : 0;
				
				//to get client details
				$this->mongo_db->where(array('_id' => $client_id));
				$client_details = $this->mongo_db->get('site_users');
				
				$all_contents[$key]['user_details']				= isset($client_details[0]) ? $client_details[0] : array();
				
			}
		}

			
		$data['data']['all_contents'] 	= $all_contents;
		
		$data['view_link'] = 'admin/jobs/index';
		$this->load->view('includes/template', $data);

	}//index

	/**
	* Update item by his id
	* @return void
	*/
	//Function to update existing form fields and add new ones if added
	public function updt()
	{
		$data['data']					= $this->data;
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$all_existing_menus 			= array();
		$data['data']['all_existing_menus']= $all_existing_menus;
		$id 							= ($this->input->post('job_id') && $this->input->post('job_id')!='') ? strval($this->input->post('job_id')) :'';
		
		
		
		
		//for job details array
		if($id !='')
		{
			$this->mongo_db->select('*');
			$this->mongo_db->where(array('_id' 	=> $id));
			$jobs_details 					= $this->mongo_db->get('jobs');
			$data['data']['jobs_details']	=	$jobs_details;
			
			$size_type = (isset($jobs_details[0]['size_type']) && $jobs_details[0]['size_type']!='') ? $jobs_details[0]['size_type'] : '';
			$special = (isset($jobs_details[0]['special']) && $jobs_details[0]['special']!='') ? $jobs_details[0]['special'] : '';
			
			$type = (isset($jobs_details[0]['type']) && $jobs_details[0]['type']!='') ? $jobs_details[0]['type'] : '';
			
			//Getting all sizes
			if($size_type!='' && $size_type!='SIZE')
			{
				$this->mongo_db->where(array('status' => '1','_id' =>$size_type));
			}
			$sizes_list 	= $this->mongo_db->get('sizes');
			
			//Getting all fields info contetns
			$this->mongo_db->where(array('page_title' => 'job'));
			$help_contents_list 	= $this->mongo_db->get('pages_help_contents');
			
			//Getting all specials
			if($special!='')
			{
				$this->mongo_db->where(array('status' => '1' ,'_id' =>$special));
			}
			
				$special_list 	= $this->mongo_db->get('special');
			
			//Getting all types
			if($type!='')
			{
				$this->mongo_db->where(array('status' => '1' ,'_id' =>$type));
			}
			
			$types_list 	= $this->mongo_db->get('types');
			
			$data['data']['jobs_details']['sizes_list']				= (isset($sizes_list[0]['title']) && $size_type!='') ? $sizes_list[0]['title'] : '';
			$data['data']['jobs_details']['special_list']			= (isset($special_list[0]['title']) && $special!='') ? $special_list[0]['title'] : '';
			$data['data']['jobs_details']['types_list']				= (isset($types_list[0]['title']) && $type!='') ? $types_list[0]['title'] : '';
			
			
			//Getting all quotes info contetns
			$this->mongo_db->where(array('type' => '1','job_id'=>$id));
			$all_quotes 	= $this->mongo_db->get('job_quotes_legs');
			
			foreach($all_quotes as $key1 => $quote_rows)
			{
				
				$client_id_quotes 	= (isset($quote_rows['user_id'])) ? $quote_rows['user_id'] : 0;
				
				//to get client details
				$this->mongo_db->where(array('_id' => $client_id_quotes));
				$client_details_quotes = $this->mongo_db->get('site_users');
				
				$all_quotes[$key1]['user_details']				= isset($client_details_quotes[0]) ? $client_details_quotes[0] : array();
				
			}
			$data['data']['quotes_details'] = $all_quotes;
			
			
			//Getting all legs info contetns
			$this->mongo_db->where(array('type' => '2','job_id'=>$id));
			$all_legs 	= $this->mongo_db->get('job_quotes_legs');
			
			foreach($all_legs as $key2 => $legs_rows)
			{
				
				$client_id_leg 	= (isset($legs_rows['user_id'])) ? $legs_rows['user_id'] : 0;
				
				//to get client details
				$this->mongo_db->where(array('_id' => $client_id_leg));
				$client_details_legs = $this->mongo_db->get('site_users');
				
				$all_legs[$key2]['user_details']				= isset($client_details_legs[0]) ? $client_details_legs[0] : array();
				
			}
			
			$data['data']['leg_details'] = $all_legs;
			
			
			//GETTING ALL PAYMENT INFO CONTETNS
			$this->mongo_db->where(array('job_id'=>$id));
			$all_payments 	= $this->mongo_db->get('job_approved_quotes');
			$all_payments_details = array();
			if(count($all_payments)>0)
			{
				$payment_id		= $all_payments[0]['payment_id'];
				
				//get payment details
				$this->mongo_db->where(array('_id'=>$payment_id));
				$all_payments_details 	= $this->mongo_db->get('user_payments');
				if(count($all_payments_details)>0)
				{
					foreach($all_payments_details as $key3 => $payments)
					{
						$client_id_payment 	= (isset($payments['user_id'])) ? $payments['user_id'] : 0;
						
						//to get client details
						$this->mongo_db->where(array('_id' => $client_id_payment));
						$client_details_payments = $this->mongo_db->get('site_users');
						
						$all_payments_details[$key3]['user_details']		= isset($client_details_payments[0]) ? $client_details_payments[0] : array();
					}
				}
			}
			$data['data']['job_payment_approve_det'] = $all_payments;
			$data['data']['job_payment_details']	= $all_payments_details;
			//END OF PAYMENT DETAILS
			
			//Getting all activities info contents
			$this->mongo_db->where(array('job_id'=>$id));
			$all_activities 	= $this->mongo_db->get('job_events');
			
			foreach($all_activities as $key4 => $activity_rows)
			{
				
				$client_id_activity 	= (isset($activity_rows['user_id'])) ? $activity_rows['user_id'] : 0;
				
				//to get client details
				$this->mongo_db->where(array('_id' => $client_id_activity));
				$client_details_activity = $this->mongo_db->get('site_users');
				
				$all_activities[$key4]['user_details']				= isset($client_details_activity[0]) ? $client_details_activity[0] : array();
				
			}
			$data['data']['activity_details'] = $all_activities;
		}
		
		//print_r($all_fixed_fields);die;
		$data_to_store = $data_to_store_old = array();
		
		
		
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $id=='')
		{
			$page_form_id 							= ($this->input->post('page_unique_id') && $this->input->post('page_unique_id')!='') ? strval($this->input->post('page_unique_id')) :'';
			
		
			$data_to_store 			= array();
		
			$data_to_store['status']			= $this->input->post('status');
			
			//echo "<pre>";
			//print_r($data_to_store);die;
			$this->mongo_db->where(array('_id' 	=> $page_form_id));
			$this->mongo_db->set($data_to_store);
			$insert = $this->mongo_db->update('jobs');
			
			if($insert)
			{
				//$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
					
					$this->session->set_flashdata('flash_message', 'option_updated');
					redirect('control/manage-jobs');
			} 
			else
			{
					$this->session->set_flashdata('flash_message', 'error_option_update');
					redirect('control/manage-jobs');
			}
			
		}
		
		if(!isset($data['data']['jobs_details']))
		{
			redirect('control/manage-jobs');
		}
		
		
		$data['view_link'] = 'admin/jobs/edit_jobs';
		$this->load->view('includes/template', $data);
	}
    
    
    

	/**
	* Delete static page contents
	* @return void
	*/
	//public function delete()
	//{
	//	//Get logged in session admin id
	//	$user_id 						= ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
	//	$setting_data 					= $this->myaccount_model->get_account_data($user_id);
	//	$data['data']['setting_data'] 	= $setting_data;
	//	$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
	//	$data['data']['dealer_id'] 		= $user_id;
	//	
	//	//getting all admin data 
	//	$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
	//	
	//	
	//	//Get requested id to remove
	//	$field_id 					= $this->uri->segment(4);
	//	
	//	//deleting query
	//	$this->mongo_db->where(array('_id' => $field_id));
	//	if($this->mongo_db->delete('static_contents')){
	//		$this->session->set_flashdata('flash_message', 'delete_success');
	//		redirect('control/static-contents');
	//	}
	//	else{
	//		$this->session->set_flashdata('flash_message', 'delete_failed');
	//		redirect('control/static-contents');
	//	}
	//}//Delete

	
	//public function update_all()
	//{
	//	$edit_contents 	= $this->mongo_db->get('site_users');
	//	if(!empty($edit_contents))
	//	{
	//		foreach($edit_contents as $content)
	//		{
	//			$user_id 						= strval($content['_id']);
	//			$data_to_store['profile_image']	= '1467807003.jpg';
	//			
	//			$this->mongo_db->where(array('_id' => strval($user_id)));
	//			$this->mongo_db->set($data_to_store);
	//			$this->mongo_db->update('site_users');
	//		}
	//	}
	//}
	

}