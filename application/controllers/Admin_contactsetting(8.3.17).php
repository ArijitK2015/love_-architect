<?php
class Admin_contactsetting extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contactsetting_model');
		$this->load->model('sitesetting_model');
		$this->load->model('myaccount_model');
		$this->load->model('common_model');
		if(!$this->session->userdata('is_logged_in')){
			redirect('control/login');
		}
		$this->load->model('contactsetting_model');
	}
 
	/**
	* Load the main view with all the current model model's data.
	* @return void
	*/
	public function index()
	{
		//settings data
		$user_id 						= ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		$setting_data 					= $this->myaccount_model->get_account_data($user_id);
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
		$data['data']['contact_settings'] 	= $this->contactsetting_model->get_settings($user_id);
		
		$data['view_link'] = 'admin/contactsetting_page';
		$this->load->view('includes/template', $data);
	}
	
	public function updt()
	{
		$user_id 						= ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		
		$this->load->library('form_validation');
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			//form validation
			$this->form_validation->set_rules('contact_email', 'Contact Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('ph_no', 'Phone no', 'trim|required');
			$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
			//$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a><strong>', '</strong></div>');
		  
			if ($this->form_validation->run())
			{
				$data_to_store = array(
					'contact_email' 	=> $this->input->post('contact_email'),
					'ph_no' 			=> $this->input->post('ph_no'),
					'fax' 			=> $this->input->post('fax'),
					//'address' 		=> $this->input->post('address'),
				);
				
				if($this->contactsetting_model->update_settings($data_to_store, $user_id) == TRUE){
					$this->session->set_flashdata('flash_message', 'contact_updated');
				}else{
					$this->session->set_flashdata('flash_message', 'contact_not_updated');
				}
			}
			else{
				$this->session->set_flashdata('flash_message', 'contact_not_updated');
			}
		}
	    
		redirect('control/contactsetting');
	}
}