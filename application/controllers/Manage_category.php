<?php
class Manage_category extends MY_Controller {
  
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
		$data['info'] = $this->common_model->get('categories');
	
	    //echo "<pre>"; print_r($data['info']);die;
	
		$data['view_link'] = 'admin/category_manage/list';
		
		$this->load->view('includes/template', $data);
	}

	
    
    public function add()
    {
       
        $tablename='categories';
        
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data_to_store = array(
					'title' => trim($this->input->post('title')),
					'details' => $this->input->post('details'),
					'added_on' => date('Y-m-d H:i:s'),
					'status'  => (string)$this->input->post('status'),
					
				);
            $add= $this->common_model->add('categories',$data_to_store);
           
		    if($add)
			{
			   $this->session->set_flashdata('flash_message', 'pages_inserted');	
			}
			else{
				$this->session->set_flashdata('flash_message', 'pages_not_inserted');
				
			}
			redirect('control/category-manage');
			
        }
        
        //load the view
            $data['view_link'] = 'admin/category_manage/add';
            $this->load->view('includes/template', $data);  
    }
        
        
       public function updt()
	{
		//product id 
		$id = $this->input->post('page_id');
      //echo $id;die;
		$user_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 1;
		
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['dealer_id'] 		= $user_id;
		
		//settings data 
		$data['data']['myaccount_data'] 	= $this->myaccount_model->get_account_data($user_id);
		
		$edit_id 						= $this->input->post('edit_id');
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $edit_id != '')
		{
			
			$data_to_store = array(
					'title' => trim($this->input->post('title')),
					'details' => $this->input->post('details'),
					'status'  => (string)$this->input->post('status'),
				);
			
			$this->mongo_db->where(array('_id' => strval($edit_id)));
			$this->mongo_db->set($data_to_store);
			
			//if the insert has returned true then we show the flash message
			if($this->mongo_db->update('categories'))
			    $this->session->set_flashdata('flash_message', 'pages_updated');
			else
			    $this->session->set_flashdata('flash_message', 'pages_not_updated');
			
			redirect('control/category-manage');
		}
		
		$this->mongo_db->where(array('_id' => $id));
		$edit_contents 				= $this->mongo_db->get('categories');
		$data['data']['page_content'] 	= $edit_contents;
		if(count($edit_contents)==0)
		{
			redirect('control/category-manage');
		}
		$data['view_link'] 				= 'admin/category_manage/edit';
		$this->load->view('includes/template', $data); 
	}//update
    
    
    
    
    
//    public function modify()
//    {
//        
//        
//        $id = $this->uri->segment(4);
//        $tablename='email_template';
//        
//        
//        if ($this->input->server('REQUEST_METHOD') === 'POST')
//        {
//            $data_to_store = array(
//				    'email_title' => $this->input->post('edit_title'),
//                    'email_subject' => $this->input->post('edit_subject'),
//					'email_template' => $this->input->post('edit_email_content')
//                    
//                );
//            
//            
//			if($this->email_template_model->update_template($id, $data_to_store,$tablename) == TRUE)
//			{
//                    $this->session->set_flashdata('flash_message', 'pages_updated');
//            }else
//            {
//                    $this->session->set_flashdata('flash_message', 'pages_not_updated');
//            }
//                //redirect('admin/pages/update/'.$id.'');
//		redirect('control/email_template');
// 
//        }
//        
//        
//        
//    }
	
	public function delete()
	{
		
		
		$id = $user_id=$this->uri->segment(4);
		
        if($this->common_model->delete('categories',array('_id'=>(string)$id)) == TRUE )
		{
			$this->session->set_flashdata('flash_message', 'pages_deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'pages_not_deleted');
		}
			redirect('control/category-manage');
	}
	
    
    
}
?>