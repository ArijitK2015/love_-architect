<?php

	$ci = &get_instance();
	$ci->load->model('sitesetting_model');
	$header['settings'] =$ci->sitesetting_model->get_settings();
	
	if(isset($data))
	{
		$this->load->view('includes/admin/header',$data);
		$this->load->view($view_link, $data);
	}
	else
	{
		$this->load->view('includes/admin/header',$header);
		$this->load->view($view_link);
	}
	
	//if($view_link != 'admin/dashboard_page')
	//{
		$this->load->view('includes/admin/footer');
	//}


?>