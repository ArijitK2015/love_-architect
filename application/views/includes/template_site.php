<?php

	$ci = &get_instance();
	$ci->load->model('sitesetting_model');
	
	$header['settings'] =$ci->sitesetting_model->get_settings();
	
	if(isset($data))
	{
		$this->load->view('includes/site/header', $data);
		$this->load->view($view_link, $data);
	}
	else
	{
		$this->load->view('includes/site/header', $header);
		$this->load->view($view_link);
	}
	
	
	$this->load->view('includes/site/footer');

?>