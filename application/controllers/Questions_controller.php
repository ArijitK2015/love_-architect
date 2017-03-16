<?php
class Questions_controller extends MY_Controller {

	
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
		$user_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 1;
		
		//if(!empty($this->cmp_auth_id))
		//	$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		//else
		//	$admin_details 			= $this->myaccount_model->get_account_data($user_id);
			
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
			
		//settings data 
		$data['data']['myaccount_data'] 	= $this->myaccount_model->get_account_data($user_id);
		//echo "hello";die;
		//$this->mongo_db->where(array('status' => '1'));
		$this->mongo_db->where(array('is_deleted' => '0'));
		$this->mongo_db->order_by(array('title' => 'asc'));
			
		$all_contents 					= $this->mongo_db->get('questions');
		
		foreach($all_contents as $k=>$v)
		{
			$category_id= isset($v['category_id']) ? $v['category_id'] :'';
			$category_det = $this->common_model->get('categories',array('*'),array('_id'=>(string)$category_id,"is_deleted" => "0"));
			
			
			
			$all_contents[$k]['category_title'] = isset($category_det[0]['title']) ? $category_det[0]['title'] : '';
			
		}
		//echo "<pre>";print_r($all_contents);die;
		
		
		$data['data']['all_contents'] 	= $all_contents;
		
		$data['view_link'] = 'admin/questions/index';
		$this->load->view('includes/template', $data);

	}//index

	public function add()
	{
		$data['data']					= $this->data;
		$user_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['data']['myaccount_data'] 	= $this->myaccount_model->get_account_data($user_id);
		
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			//form validation
			//$this->form_validation->set_rules('page_title', 'Page Title', 'required|trim');
			//$this->form_validation->set_rules('page_content', 'Page Content', 'required');
			//$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
			//
			////if the form has passed through the validation
			//if ($this->form_validation->run())
			//{
			
			
			
			
				$data_to_store = array(
					            
								'category_id' 		=> strval($this->input->post('category_id')),
								'title' 		=> strval($this->input->post('ques_title')),
								'ans_type' 	=> strval($this->input->post('type_id')),
								'is_required' => strval($this->input->post('is_required')),
								'added_on'		=> date("Y-m-d H:i:s"),
								'status' 			=> strval($this->input->post('status')),
								"is_deleted" => "0"
							);
				
				$insert 	= $this->mongo_db->insert('questions', $data_to_store);
		
				if($insert)
				{
					if($this->input->post('type_id') == 1 )
					{
						$data_to_store_qu_ans = array(
												  'question_id' => (string)$insert,
												  'category_id' 		=> strval($this->input->post('category_id')),
												  'ans_type'    => strval($this->input->post('type_id')),
												  'title'       => strval($this->input->post('option_title_txt')), 
												  'score'       => strval($this->input->post('option_val_txt')),
												  "is_deleted" => "0"
												  );
					    $insert_question_answers 	= $this->mongo_db->insert('question_answers', $data_to_store_qu_ans);
						
					}
					else
					{
						$option_title = $this->input->post('option_title');
			            $option_value = $this->input->post('option_val');
						
					foreach($option_title as $k=>$v)
					{
						$data_to_store_qu_ans = array(
												  'question_id' => (string)$insert,
												  'category_id' 		=> strval($this->input->post('category_id')),
												  'ans_type'    => strval($this->input->post('type_id')),
												  'title'       => strval($v), 
												  'score'       => strval($option_value[$k]),
												  "is_deleted" => "0"
												  );
					    $insert_question_answers 	= $this->mongo_db->insert('question_answers', $data_to_store_qu_ans);
						
					}
					}
					
					
					
					$this->session->set_flashdata('flash_message', 'insert_success');
				}
				else
					$this->session->set_flashdata('flash_message', 'insert_failed');
				
				redirect('control/manage-questions');
			//}
		}
		$data['category_det'] = $this->common_model->get('categories');
		
		
		$data['view_link'] = 'admin/questions/add_question';
		$this->load->view('includes/template', $data);
	}

	/**
	* Update item by his id
	* @return void
	*/
	public function updt()
	{
		$data['data']					= $this->data;
		//product id 
		$id =$edit_id= $this->uri->segment(4);
			
		$user_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 1;
			
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
			
		//settings data 
		$data['data']['myaccount_data'] 	= $this->myaccount_model->get_account_data($user_id);
			
		
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $edit_id != '')
		{
			   $data_to_store = array(
					            
								'category_id' 		=> strval($this->input->post('category_id')),
								'title' 		=> strval($this->input->post('ques_title')),
								'ans_type' 	=> strval($this->input->post('type_id')),
								'is_required' => strval($this->input->post('is_required')),
								
								'status' 			=> strval($this->input->post('status'))
							);
				
				$update 	= $this->common_model->update('questions', $data_to_store,array('_id'=>(string)$id));
			
			
			    if($update)
				{
					// $this->common_model->delete('question_answers',array('question_id'=>(string)$id));
					$this->common_model->update('question_answers',array("is_deleted" => "1"),array('question_id'=>(string)$id));
					
					if($this->input->post('type_id') == 1 )
					{
						$data_to_store_qu_ans = array(
												  'question_id' => (string)$id,
												  'category_id' 		=> strval($this->input->post('category_id')),
												  'ans_type'    => strval($this->input->post('type_id')),
												  'title'       => strval($this->input->post('option_title_txt')), 
												  'score'       => strval($this->input->post('option_val_txt')),
												  "is_deleted" => "0"
												  );
					    $insert_question_answers 	= $this->mongo_db->insert('question_answers', $data_to_store_qu_ans);
						
					}
					else
					{
						$option_title = $this->input->post('option_title');
			            $option_value = $this->input->post('option_val');
						
						foreach($option_title as $k=>$v)
						{
							$data_to_store_qu_ans = array(
													  'question_id' => (string)$id,
													  'category_id' 		=> strval($this->input->post('category_id')),
													  'ans_type'    => strval($this->input->post('type_id')),
													  'title'       => strval($v), 
													  'score'       => strval($option_value[$k]),
													  "is_deleted" => "0"
													  );
							$insert_question_answers 	= $this->mongo_db->insert('question_answers', $data_to_store_qu_ans);
							
						}
					}
					
					
					
					$this->session->set_flashdata('flash_message', 'success_option_update');
					redirect('control/manage-questions');
				}
			    else
		        {			$this->session->set_flashdata('flash_message', 'error_option_update');
				
				   redirect('control/manage-questions');
				}
				
		
			
			//if the insert has returned true then we show the flash message
			
			
			redirect('control/manage-questions');
		}
		
		
		$data['category_det'] = $this->common_model->get('categories');
		
		$this->mongo_db->where(array('_id' => (string)$id));
		$edit_contents 				= $this->mongo_db->get('questions');
		if(count($edit_contents)==0)
		{
			redirect('control/manage-questions');
		}
		
		
		$question_id = isset($edit_contents[0]['_id']) ? $edit_contents[0]['_id'] : '';
		$ans_type_id = isset($edit_contents[0]['ans_type']) ? $edit_contents[0]['ans_type'] : '';
		
		$data['question_det'] 	= $edit_contents;
		
		$data['ans_type_id'] 	= $ans_type_id;
		$data['question_ans_det'] = $this->common_model->get('question_answers',array('*'),array('ans_type'=>$ans_type_id,'question_id'=>(string)$question_id,"is_deleted" => "0"));
		//echo "<pre>";print_r($data['question_ans_det']);die;
		
		$data['view_link'] 				= 'admin/questions/edit_question';
		$this->load->view('includes/template', $data); 
	}//update
    
    
    

	/**
	* Delete static page contents
	* @return void
	*/
	public function delete()
	{
		$data['data']					= $this->data;
		//Get logged in session admin id
		
		
		
		//Get requested id to remove
		$field_id 					= $this->uri->segment(4);
		
		//deleting query
	
			
		if($this->common_model->update('questions',array("is_deleted" => "1"),array('_id' => $field_id)) ==TRUE && $this->common_model->update('question_answers',array("is_deleted" => "1"),array('question_id'=>(string)$field_id)) == TRUE)	
		{	$this->session->set_flashdata('flash_message', 'delete_success');
			redirect('control/manage-questions');
		}
		else{
			$this->session->set_flashdata('flash_message', 'delete_failed');
			redirect('control/manage-questions');
		}
	}//Delete

	
	public function update_all()
	{
		$edit_contents 	= $this->mongo_db->get('site_users');
		if(!empty($edit_contents))
		{
			foreach($edit_contents as $content)
			{
				$user_id 						= strval($content['_id']);
				$data_to_store['profile_image']	= '1467807003.jpg';
				
				$this->mongo_db->where(array('_id' => strval($user_id)));
				$this->mongo_db->set($data_to_store);
				$this->mongo_db->update('site_users');
			}
		}
	}

}