<?php
class Data_form_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		//Loading all necessary models
		$this->load->model('myaccount_model');
		$this->load->model('sitesetting_model');
		$this->load->model('common_model');
		$this->load->model('Url_generator_model');
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
		$this->mongo_db->where(array('user_id' => $user_id));
		$all_details 					= $this->mongo_db->distinct('form_fields', 'form_type');
		//$all_details 	= $this->mongo_db->get('form_fields');
		
		//echo '<pre>'; print_r($all_details); echo '</pre>'; die;
		$all_details_count = array();
		if(!empty($all_details))
		{
			foreach($all_details as $k=>$det)
			{
				$this->mongo_db->where(array('form_type' => $det));
				$all_details_count[$det]['count'] 	= $this->mongo_db->count('form_fields');
			}
		}
		
		$data['data']['all_forms']		= $all_details;
		$data['data']['all_forms_count']	= $all_details_count;
		
		$all_existing_menus 			= array();
		$data['data']['all_existing_menus']= $all_existing_menus;
		
		$data['data']['all_cars_details'] 	= array();
		$data['data']['all_zip_filter'] 	= array();
		
		//$data['data']['all_forms']		= array();
		
		$data['view_link'] = 'admin/data_form/index';
		
		
		$this->load->view('includes/template', $data);
	}//index
	
	public function add_forms()
	{
		$user_id 			= $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		$data_to_store 	= array();
		
		$all_fields 		= $this->input->post('field');
		$all_field_types 	= $this->input->post('field_type');
		$field_option_check 	= $this->input->post('field_option_check');
		
		
		if(!empty($all_fields))
		{
			$this->mongo_db->order_by(array('id' => 'desc'));
			$this->mongo_db->limit(1);
			$get_last_id_arr 	= $this->mongo_db->get('form_fields');
			
			$get_last_id 		= (isset($get_last_id_arr[0]['id'])) ? $get_last_id_arr[0]['id'] : 1;
			
			//echo '<pre>'; print_r($get_last_id); die;
			
			foreach($all_fields as $k => $field)
			{
				//echo $k;
				//if($field 	== 'password') 			$fld_type = 'password';
				//elseif($field 	== 'email') 			$fld_type = 'email';
				//elseif($field 	== 'company_address') 	$fld_type = 'address';
				//elseif($field 	== 'delivery_address') 	$fld_type = 'address';
				//elseif($field 	== 'pickup_address') 	$fld_type = 'address';
				//elseif($field 	== 'home_address') 		$fld_type = 'address';
				//else								$fld_type = (isset($all_field_types[$k]) && (!empty($all_field_types[$k]))) ? $all_field_types[$k] : 'text';
				
				$fld_type = (isset($all_field_types[$k]) && (!empty($all_field_types[$k]))) ? $all_field_types[$k] : 'text';
				$chck_box = (isset($field_option_check[$k+1]) && (!empty($field_option_check[$k+1]))) ? $field_option_check[$k+1] : '0';
				
				
				$id=$get_last_id+1+$k;
				$data_to_store[$k]['id'] 			= (string)$id;
				$data_to_store[$k]['user_id'] 		= (string)$user_id;	
				$data_to_store[$k]['form_type']		= $this->input->post('form_type');
				$data_to_store[$k]['field_name'] 	= $this->Url_generator_model->title_generate(trim($field));
				$data_to_store[$k]['label_name'] 	= $field;
				$data_to_store[$k]['field_type'] 	= $fld_type;
				$data_to_store[$k]['is_required'] 	= $chck_box;
				$data_to_store[$k]['is_fixed'] 		= "0";
				$data_to_store[$k]['status'] 		= "1";
			}
		}
		

		if($this->mongo_db->batch_insert('form_fields', $data_to_store))
			$this->session->set_flashdata('flash_message', 'info_added');
		else
			$this->session->set_flashdata('flash_message', 'not_added');
			
		redirect('control/data-forms');
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
		
		$this->mongo_db->where(array('user_id' => $user_id));
		$this->mongo_db->where(array('is_fixed' => "0"));
		$all_details 					= $this->mongo_db->distinct('form_fields', 'form_type');
		//$all_details 	= $this->mongo_db->get('form_fields');
		
		//echo '<pre>'; print_r($all_details); echo '</pre>'; die;
		$all_details_count = array();
		if(!empty($all_details))
		{
			foreach($all_details as $k=>$det)
			{
				$this->mongo_db->where(array('form_type' => $det));
				$all_details_count[$det] 	= $this->mongo_db->count('form_fields');
			}
		}
		
		$data['data']['all_forms']		= $all_details;
		$data['data']['all_forms_count']	= $all_details_count;
		
		$data['view_link'] = 'admin/data_form/add_form';
		$this->load->view('includes/template', $data);
		
	}
	
	//Function to update existing form fields and add new ones if added
	public function updt()
	{
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_hotcargo')) ?  $this->session->userdata('user_id_hotcargo') : 1;
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['myaccount_data'] 			= $this->myaccount_model->get_account_data($user_id);
		
		$all_existing_menus 			= array();
		$data['data']['all_existing_menus']= $all_existing_menus;
		
		$form_id 							= $this->uri->segment(4); 
		
		$this->mongo_db->where(array('form_type' => $form_id, 'is_fixed' => '0'));
		//$this->mongo_db->where_ne('is_fixed', '1');
		$all_details 						= $this->mongo_db->get('form_fields');
		
		//print_r($all_details);die;
		$data['data']['form_id']		= $form_id;
		$data['data']['all_details']	= $all_details;
		
		$data_to_store = $data_to_store_old = array();
		
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$all_fields 			= $this->input->post('field');
			$all_field_types 		= $this->input->post('field_type');
			$field_option_check 	= $this->input->post('new_field_option_check');
			
			$all_old_fields 		= $this->input->post('old_field');
			$all_old_field_ids		= $this->input->post('old_field_id');
			$all_old_field_types 	= $this->input->post('old_field_type');
			$old_field_option_check 	= $this->input->post('field_option_check');
			
			//echo '<pre>'; print_r($all_old_fields);print_r($all_fields);print_r($old_field_option_check); print_r($all_old_field_ids); print_r($all_old_field_types);print_r($all_field_types); echo '</pre>'; die;
			
			//print_r($field_option_check);die;
			$this->mongo_db->order_by(array('id' => 'desc'));
			$this->mongo_db->limit(1);
			$get_last_id_arr 	= $this->mongo_db->get('form_fields');
			
			$get_last_id 		= (isset($get_last_id_arr[0]['id'])) ? $get_last_id_arr[0]['id'] : 1;
			
			//$old_field_count=count($all_old_fields);
			if(!empty($all_fields))
			{
				foreach($all_fields as $k => $field)
				{
					$data_to_store = array();
					
					if(!empty($field))
					{
						$old_field_count=count($all_old_fields);
						$data_to_store['id']			= (string)($get_last_id + $k + 1);
 						$data_to_store['user_id'] 		= (string)$user_id;	
						$data_to_store['form_type']		= $this->input->post('form_type');
						$data_to_store['field_name'] 		= $this->Url_generator_model->title_generate(trim($field));
						$data_to_store['label_name'] 		= $field;
						$data_to_store['field_type'] 		= (isset($all_field_types[$k]) && (!empty($all_field_types[$k]))) ? $all_field_types[$k] : 'text';
						//echo $k+$old_field_count+1;
						$data_to_store_old['is_required']	= (isset($field_option_check[$k+$old_field_count+1])) ? $field_option_check[$k+$old_field_count+1] : 0;die;
						$data_to_store['is_fixed'] 		= '0';
						$data_to_store['status'] 		= '1';
						
						$this->mongo_db->insert('form_fields', $data_to_store);
					}
				}
			}
			
			if(!empty($all_old_fields))
			{
				foreach($all_old_fields as $k1 => $field)
				{
					$data_to_store_old 				= array();
					
					$id 							= (isset($all_old_field_ids[$k1]) && (!empty($all_old_field_ids[$k1]))) ? $all_old_field_ids[$k1] : '0' ;	
					$data_to_store_old['field_name'] 	= $this->Url_generator_model->title_generate(trim($field));
					$data_to_store_old['label_name'] 	= $field;
					$data_to_store_old['field_type'] 	= (isset($all_old_field_types[$k1]) && (!empty($all_old_field_types[$k1]))) ? $all_old_field_types[$k1] : 'text';
					$data_to_store_old['is_required']	= (isset($old_field_option_check[$k1])) ? $old_field_option_check[$k1] : 0;
					
					//echo $id.'<pre>'; print_r($data_to_store_old); echo '</pre>';
					
					//$mongoID = new MongoID('4e519d5118617e88f27ea8cd');
					
					$this->mongo_db->where(array('_id' 	=> $all_old_field_ids[$k1]));
					$this->mongo_db->set($data_to_store_old);
					$this->mongo_db->update('form_fields');
				}
			}
			//die;
			//echo '<pre>'; print_r($data_to_store); echo '</pre>'; die;
			
			if(!empty($data_to_store))
			{
				//if($this->mongo_db->insert('form_fields', $data_to_store))
					$this->session->set_flashdata('flash_message', 'info_added');
				//else
				//	$this->session->set_flashdata('flash_message', 'not_added');
			}
			
			redirect('control/data-forms');
		}
		
		$data['view_link'] = 'admin/data_form/edit_form';
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