<?php
class Admin_email_template extends MY_Controller {

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
		$data['info'] = $this->email_template_model->get_tempalte_info();
	
		$data['view_link'] = 'admin/email_template/email_template_manage';
		
		$this->load->view('includes/template', $data);
	}

	function delete()
	{
		$id = $this->uri->segment(4);
		$this->newsletter->delete_email($id);
		redirect('control/newsletters');
	}
    
    public function add()
    {
       
        $tablename='email_template';
        
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data_to_store = array(
                    'email_subject' => $this->input->post('subject'),
		    'email_template' => $this->input->post('email_content')
                    
                );
            
            $last_insert=$this->email_template_model->store_template($data_to_store,$tablename);
        }
        
        //load the view
            $data['view_link'] = 'admin/template/add_template';
            $this->load->view('includes/template', $data);  
    }
        
        
       public function updt()
	{
		//product id 
		$id = $this->input->post('page_id');
		
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
					'email_subject' => $this->input->post('email_subject'),
					'email_template' => $this->input->post('email_template')
				);
			
			$this->mongo_db->where(array('_id' => strval($edit_id)));
			$this->mongo_db->set($data_to_store);
			
			//if the insert has returned true then we show the flash message
			if($this->mongo_db->update('email_templates'))
			    $this->session->set_flashdata('flash_message', 'pages_updated');
			else
			    $this->session->set_flashdata('flash_message', 'pages_not_updated');
			
			redirect('control/email-template');
		}
		
		$this->mongo_db->where(array('_id' => $id));
		$edit_contents 				= $this->mongo_db->get('email_templates');
		$data['data']['page_content'] 	= $edit_contents;
		if(count($edit_contents)==0)
		{
			redirect('control/email-template');
		}
		$data['view_link'] 				= 'admin/email_template/edit_email_template';
		$this->load->view('includes/template', $data); 
	}//update
    
    
    
    
    
    public function modify()
    {
        
        
        $id = $this->uri->segment(4);
        $tablename='email_template';
        
        
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $data_to_store = array(
				    'email_title' => $this->input->post('edit_title'),
                    'email_subject' => $this->input->post('edit_subject'),
					'email_template' => $this->input->post('edit_email_content')
                    
                );
            
            
           if($this->email_template_model->update_template($id, $data_to_store,$tablename) == TRUE)
           {
                    $this->session->set_flashdata('flash_message', 'pages_updated');
            }else
            {
                    $this->session->set_flashdata('flash_message', 'pages_not_updated');
            }
                //redirect('admin/pages/update/'.$id.'');
		redirect('control/email_template');
 
        }
        
        
        
    }
    
    
}
?>