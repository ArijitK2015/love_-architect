<?php
class Admin_chngpassword extends MY_Controller {

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
 
		$user_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		$setting_data 					= $this->myaccount_model->get_account_data((string)$user_id);
		
		$data['data']['setting_data'] 	= $setting_data;
		$data['data']['settings'] 		= $this->sitesetting_model->get_settings();
 
		$data['view_link'] = 'admin/chngpassword_page';
		$this->load->view('includes/template', $data);
 
	}//index
	
	function __encrip_password($password) {
	    return md5($password);
	}
	
	public function updt()
	{
		$this->load->library('form_validation');
		//if save button was clicked, get the data sent via post
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$user_id = $this->session->userdata('user_id_lovearchitect');
			
			//form validation
			$this->form_validation->set_rules('old_pass', 'Old Password', 'trim|required|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('npass', 'New Password', 'trim|required|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('cpass', 'Confirm Password', 'trim|required|matches[npass]');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">x</a><strong>', '</strong></div>');
		
			if ($this->form_validation->run())
			{
				$old_pass			= $this->input->post('old_pass');
				$this->mongo_db->where(array('id' => (string)$user_id, 'pass_word' => $old_pass));
				$check_pass_exist 	= $this->mongo_db->count('membership');
				
				if($check_pass_exist > 0)
				{
					$data_to_store = array('pass_word' => $this->input->post('cpass'));
					if($this->Chngpassword_model->update_password($data_to_store, (string)$user_id) == TRUE){
						$this->session->set_flashdata('flash_message', 'pwd_updated');
					}else{
						$this->session->set_flashdata('flash_message', 'pwd_not_updated');
					}
				}else
				{
					$this->session->set_flashdata('flash_message', 'wrong_password');				
				}
				
				redirect('control/change-password');
			}
			else
			{
				redirect('control/change-password');
			}
		}
	}

}