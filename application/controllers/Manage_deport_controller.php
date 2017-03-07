<?php
class Manage_depot_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		//Loading all necessary models
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->library('ImageThumb');
		
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
		$this->mongo_db->where(array('user_type' => 'broker'));
		$all_details 					= $this->mongo_db->get('site_users');
		//$all_details 	= $this->mongo_db->get('form_fields');
		
		//echo '<pre>'; print_r($all_details); echo '</pre>'; die;
		
		$data['data']['all_broker']		= $all_details;

		//$data['data']['all_forms']		= array();
		
		$data['view_link'] = 'admin/brokers/index';
		
		
		$this->load->view('includes/template', $data);
	}//index
	
	public function add_broker()
	{
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
					if(!isset($data_to_store[$f]))
						$data_to_store[$f] 		= (is_array($field)) ? json_encode($field) :strval($field);
					else
						$data_to_store[$f.'_1'] = (is_array($field)) ? json_encode($field) :strval($field);
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
		
		$data_to_store['added_on']		= strval(date('Y-m-d H:i:s'));
		$data_to_store['admin_status']	= strval(1);
		$data_to_store['status']			= strval(1);
		
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
			$insert 	= $this->mongo_db->insert('site_users', $data_to_store);
		
			if($insert)
			{
				$to_up_files = array();
				
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
		
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
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
		$user_id = $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
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
							$data_to_store[$f] 		= (is_array($field)) ? json_encode($field) :strval($field);
						else
							$data_to_store[$f.'_1'] 	= (is_array($field)) ? json_encode($field) :strval($field);
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
							if($email['_id']!=$broker_form_id)
							{
								$this->session->set_flashdata('flash_message', 'email_error');
								redirect('control/manage-brokers');
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
				$this->mongo_db->update('site_users');
			
				if(isset($insert))
				{
					$to_up_files = array();
					
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