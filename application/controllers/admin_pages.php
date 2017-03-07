<?php
class Admin_pages extends MY_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/pages';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model');
	$this->load->model('sitesetting_model');

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

        //all the posts sent by the view
        $search_string = $this->input->post('search_string');        
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type');

	$admin_pagination = $this->sitesetting_model->get_admin_pagination();

        //pagination settings
        $config['per_page'] = $admin_pagination;

        $config['base_url'] = base_url().'control/pages';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
	$config['prev_link'] = 'Prev';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['next_link'] = 'Next';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

        //if order type was changed
        if($order_type){
            $filter_session_data['order_type'] = $order_type;
        }
        else{
            //we have something stored in the session? 
            if($this->session->userdata('order_type')){
                $order_type = $this->session->userdata('order_type');    
            }else{
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';    
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;        


        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        if($search_string !== false && $order !== false || $this->uri->segment(3) == true){ 
           
            /*
            The comments here are the same for line 79 until 99

            if post is not null, we store it in session data array
            if is null, we use the session data already stored
            we save order into the the var to load the view with the param already selected       
            */
            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            if(isset($filter_session_data)){
              $this->session->set_userdata($filter_session_data);    
            }
            
            //fetch sql data into arrays
            $data['data']['count_pages']= $this->page_model->count_pages($search_string, $order);
            $config['total_rows'] = $data['data']['count_pages'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['data']['pages'] = $this->page_model->get_pages($search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['data']['pages'] = $this->page_model->get_pages($search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['data']['pages'] = $this->page_model->get_pages('', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['data']['pages'] = $this->page_model->get_pages('', '', $order_type, $config['per_page'],$limit_end);        
                }
            }

        }
	else{

            //clean filter data inside section
            //$filter_session_data['manufacture_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['data']['count_pages']= $this->page_model->count_pages();
            $data['data']['pages'] = $this->page_model->get_pages('', '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['data']['count_pages'];

        }
         
        //initializate the panination helper 
        $this->pagination->initialize($config);
	
	 $data['data']['langs']= $this->page_model->getall_langs();

        //load the view
        $data['view_link'] = 'admin/pages/list';
        $this->load->view('includes/template', $data);
	//$this->load->view('includes/header');  
	//$this->load->view('admin/pages/list',$data);
	//$this->load->view('includes/footer');  

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
	//$lang_fetch=$this->page_model->fetch_all_langs();
	//echo "<pre>";print_r($lang_fetch);exit;
	
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
	    /*
            //form validation
            $this->form_validation->set_rules('page_title', 'Page Title', 'required|trim');
	    $this->form_validation->set_rules('page_content', 'Page Content', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
		*/
                $title_alias = str_replace(" ","-",$this->input->post('page_title'));
		$title_alias = strtolower($title_alias);
                $data_to_store = array(
                    'page_title' => $this->input->post('page_title'),
		    'page_alias' => $title_alias,
		    'meta_tag' => $this->input->post('page_tag'),
		    'meta_keywords' => $this->input->post('page_key'),
		     'page_alias' => $this->input->post('page_alias'),
		    'page_content' => $this->input->post('page_content'),
		    //'status' => $this->input->post('status'),
		    'meta_description'=>$this->input->post('meta_description')
                );
                //if the insert has returned true then we show the flash message
		$last_insert=$this->page_model->store_page($data_to_store);
		
		$lang_fetch=$this->page_model->fetch_all_langs();
		
		foreach($lang_fetch as $val)
		{
		    $title_arr		= array(
							'langid' 		=> $val['id'],
							'contentid' 	=> $last_insert,
							'reftable' 	=> 'pages' ,
							'reffield'	=> 'page_title' ,
							'value' 		=> ''
				     );
		    $content_arr	= array(
							'langid'	 	=> $val['id'],
							'contentid' 	=> $last_insert,
							'reftable' 	=> 'pages' ,
							'reffield' 	=> 'page_content' ,
							'value' 		=> ''
				     );
		    
		    $this->page_model->insert_lang_content($title_arr,'lang_content');
		    $this->page_model->insert_lang_content($content_arr,'lang_content');
		}
		
		
                if($last_insert){
                    $this->session->set_flashdata('flash_message', 'pages_inserted');
                }else{
                    $this->session->set_flashdata('flash_message', 'pages_not_inserted');
                }
		redirect('control/pages');

            //}

        }
        //load the view
        $data['view_link'] = 'admin/pages/add';
        $this->load->view('includes/template', $data);  
    }


    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //product id 
        $id = $this->uri->segment(4);
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('page_title', 'Page Title', 'required|trim');
	    $this->form_validation->set_rules('page_content', 'Page Content', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    		$title_alias = str_replace(" ","-",$this->input->post('page_title'));
		$title_alias = strtolower($title_alias);
                $data_to_store = array(
                    'page_title' => $this->input->post('page_title'),
		    //'title_alias' => $title_alias,
		    'meta_tag' => $this->input->post('page_tag'),
		    'meta_keywords' => $this->input->post('page_key'),
		    'meta_description'=>$this->input->post('meta_description'),
		     'page_alias' => $this->input->post('page_alias'),
		    'page_content' => $this->input->post('page_content')
		    //'status' => $this->input->post('status')
                );
                //if the insert has returned true then we show the flash message
                if($this->page_model->update_page($id, $data_to_store) == TRUE){
                    $this->session->set_flashdata('flash_message', 'pages_updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'pages_not_updated');
                }
                //redirect('admin/pages/update/'.$id.'');
		redirect('control/pages');
            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //product data 
        $data['data']['page'] = $this->page_model->get_page_by_id($id);
        
	 $data['view_link'] = 'admin/pages/edit';
        $this->load->view('includes/template', $data); 
	
	//$this->load->view('includes/header');  
	//$this->load->view('admin/pages/edit',$data);
	//$this->load->view('includes/footer');

    }//update
    
    
    
    public function updatelang()
    {
        //product id 
        $id = $this->uri->segment(5);
	$lang_id = $this->uri->segment(4);
	$table_name='pages';
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
          
		$title=$this->input->post('page_title');
		$title_id=$this->input->post('title_id');
		
		
		$content=$this->input->post('page_content');
		$content_id=$this->input->post('content_id');
		
		    $title_arr=array(
				     'langid' => $lang_id,
				     'contentid' => $id,
				     'reftable' => 'pages' ,
				     'reffield' => 'page_title' ,
				     'value' =>  $title
				     );
		    $content_arr=array(
				    'langid' => $lang_id,
				     'contentid' => $id,
				     'reftable' => 'pages' ,
				     'reffield' => 'page_content' ,
				     'value' =>  $content
				     );
		
               
	        if($title_id!='') 
	         {
		    $this->page_model->update_page_lang($title, $title_id);
		 }else{
		     $this->page_model->insert_lang_content($title_arr,'lang_content');
		 }
		 
		 if($content_id!='')
		  {
		    $this->page_model->update_page_lang($content, $content_id);
		  }else {
		     $this->page_model->insert_lang_content($content_arr,'lang_content');
		  }
	        
		
	       
	        $this->session->set_flashdata('flash_message', 'pages_updated');
		
                //if the insert has returned true then we show the flash message
                //if($this->page_model->update_page_lang($title, $title_id) == TRUE){
                //    $this->session->set_flashdata('flash_message', 'pages_updated');
                //}else{
                //    $this->session->set_flashdata('flash_message', 'pages_not_updated');
                //}
                //redirect('admin/pages/update/'.$id.'');
		redirect('control/pages');

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //product data 
        $data['page'] = $this->page_model->get_langcontent($id,$lang_id,$table_name);
       // echo "<pre>";print_r($data['page']);exit;
	
	
	 $data['view_link'] = 'admin/pages/edit_language';
        $this->load->view('includes/template', $data); 
	

    }//update
    
    

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        //product id 
        $id = $this->uri->segment(4);
        $add = $this->page_model->delete_page($id);
	if($add == TRUE){
	    $this->session->set_flashdata('flash_message', 'page_deleted');
	}else{
	    $this->session->set_flashdata('flash_message', 'page-not_deleted');
	}
        redirect('control/pages');
    }//edit


}