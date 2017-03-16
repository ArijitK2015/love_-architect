<?php
class Manage_users extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		// loading models
	
		
	}

	public function index()
	{
		$data['data']					= $this->data;
		//getting dealers id
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		
		if(!empty($this->cmp_auth_id))
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		else
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
				
		$data['data']['setting_data'] 	= $admin_details;
		
		
		
		
		$data['data']['user_det'] 		= $this->common_model->get('users',array('*'),array('user_deleted'=>'0'));
		$data['view_link'] 				= 'admin/users/index';
		$this->load->view('includes/template', $data);
	}
    
    
	function user_name_chk()
	{
		$user_id = $this->input->post('user_id');
        $user_email = $this->input->post('email');
        
        if($user_id !='' && $user_email !='')
        {  // echo 'edit'.$user_id.$user_email;
            $this->mongo_db->where(array('email'=>(string)$user_email))->where_ne('_id',(string)$user_id);
			$res=$this->mongo_db->get('users');
            
            if(count($res)>0)
            {
                echo "yes";
            }
            else
            {
                echo "no";
            }
        }
        else
        {   // echo 'add'.$user_email;
            $this->mongo_db->where(array('email'=>$user_email));
			$res=$this->mongo_db->get('users');
            
            if(count($res)>0)
            {
                echo "yes";
            }
            else
            {
                echo "no";
            }  
            
        }
	}
    
	public function add()
	{
		$data['data']					= $this->data;
		//getting dealers id
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;

		
		
		//echo "<pre>"; print_r($fetch); die;
		
		
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$first_name 			= trim($this->input->post('firstname'));
			$last_name 			= trim($this->input->post('lastname'));
			$email	=$user_email= trim($this->input->post('a_email'));
			
			$password =$user_password= trim($this->input->post('password'));
			
			$user_det_salt =         $this->password_salt;
			$password=$enc_password 	= crypt($password, $user_det_salt);
			$status				= $this->input->post('status');
			
			$image_name			= "";
			//echo "<pre>";
			//print_r($menu_permission);
			//print_r($sub_menu_permission);
			//echo "</pre>"; die;
			
			
			
			$data_to_store = array(
								
								'first_name' 		=> $first_name,
								'last_name' 		=> $last_name,
								'email' 	        => (string)$email,
								'alias'             => $first_name.time().rand(0,20),
								
								'pass_word' 		=> $password,
								'password_salt'     => $user_det_salt,
                                'verification_code' => '',
                               
                                
                                'regdate'           => date('Y-m-d H:i:s'),
                                'social_status'     => "0",
                                'fbid'              => "",
                                'twit_id'           => "",
                                'user_deleted'      => "0",
                               
 								'status' 			=> $status,
							);
			
			
            
			$res1=$this->common_model->get('users',array('*'),array('email'=>(string)$email));
			
			if(count($res1)>0)
			{
				$this->session->set_flashdata('flash_message', 'email_exist');
				 redirect('control/manage-users');
				
			}
			
			//echo "<pre>";print_r($data_to_store); die;	
			$var 				= 'user_image';
		    if($_FILES[$var]["name"]!='')
			{
		    
			 if (($_FILES[$var]["type"] == "image/jpeg") ||
				($_FILES[$var]["type"] == "image/JPEG") ||
				($_FILES[$var]["type"] == "image/jpg") ||
				($_FILES[$var]["type"] == "image/JPG") ||
				($_FILES[$var]["type"] == "image/gif") ||
				($_FILES[$var]["type"] == "image/GIF") ||
				($_FILES[$var]["type"] == "image/png") ||
				($_FILES[$var]["type"] == "image/PNG"))
				{
					
					
					$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/user_images/';
					$filename 			= substr($_FILES[$var]['name'],strripos($_FILES[$var]['name'],'.'));
					$s					= time().rand(0,20).$filename;
					$fileNormal 			= $DIR_IMG_NORMAL.$s;
					$file 				= $_FILES[$var]['tmp_name'];
					list($width, $height) 	= getimagesize($file);
					//echo $fileNormal.'--?'.$file; die;
					$result 				= move_uploaded_file($file, $fileNormal);
					//echo "<pre>";print_r($data_to_store); die;
					
					if($result)
					{
						$srcPath		= FILEUPLOADPATH.'assets/uploads/user_images/'.$s;
						$destPath 	= FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$s;
						$destWidth	= 100;
						$destHeight	= 100;
						$this->imagethumb->thumbnail_new($destPath, $srcPath, $destWidth, $destHeight);
						$image_name	= $s;
						$data_to_store['profile_image'] = $image_name;
							
						//echo "<pre>";print_r($data_to_store); die;	
							
							
							//$insert = $this->Subadmin_model->add_subadmin($data_to_store);
							$insert = $this->common_model->add('users',$data_to_store);
							//echo $insert; die;
							
							if($insert)
							{
								
								//exit;
							        $setting_det = $this->common_model->get('settings');
									$site_name   = isset($setting_det[0]['site_name']) ? $setting_det[0]['site_name'] : '';
									$site_logo_img   = isset($setting_det[0]['site_logo']) ? $setting_det[0]['site_logo'] : '';
									
									$site_logo = '<img src="'.base_url().'assets/site/images/'.$site_logo_img.'" >';
									
									$reciever_name = $to_subadmin= ucfirst($first_name).' '.ucfirst($last_name);
									$user_type = 'user';
									
									$email_template_reg = $this->common_model->get('email_templates',array('*'),array('_id'=>'58c231ad4a768931828e7ced'));
									
									$message_subject=$email_subject = isset($email_template_reg[0]['email_subject']) ? $email_template_reg[0]['email_subject'] : '';
									$article_body  = isset($email_template_reg[0]['email_template']) ? $email_template_reg[0]['email_template'] : '';
									
									$body =$message_content = str_replace(array ('[SITE_LOGO]','[NAME]','[USER_TYPE]','[SITE_NAME]','[USERNAME]','[PASSWORD]' ), array ( $site_logo,$reciever_name,$user_type,$site_name,$email,$user_password ), $article_body);
									
									$this->User_email_model->send_email($email, $message_subject, $message_content, '', '', '', $to_subadmin);
							        $this->session->set_flashdata('flash_message', 'user_added');
								
								redirect('control/manage-users');
							}
							else
							{
								$this->session->set_flashdata('flash_message', 'user_not_added');
							}
					}
					else
					{
						$this->session->set_flashdata('flash_message', 'user_not_added');
					}
				}
			}
			   redirect('control/manage-users');
		}
        
            $data['view_link'] = 'admin/users/add_user';
            $this->load->view('includes/template', $data);
    }
	
	public function update()
	{
		$data['data']					= $this->data;
		//getting dealers id
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		
		
		
		$id 				= $this->uri->segment(4);
		$data['user_det'] =$user_det	= $this->common_model->get('users',array('*'),array('_id'=>$id));
		if(count($user_det)==0)
		{
			redirect('control/manage-users');
			
		}
		
		
		//$data['userdata'] 	= $this->common_model->get('menu',array('*'),array('parent_id' => 0,'menu_type'=>0),null,null,null,null,'title','asc');
		//$data['userdata'] =$this->db->where('id !=',4)->where('id !=',10)->where('parent_id',0)->where('menu_type',0)->order_by('title','asc')->get('menu')->result_array();
		
		
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$first_name 		= trim($this->input->post('firstname'));
			$last_name 			= trim($this->input->post('lastname'));
			$email	            = trim($this->input->post('a_email'));
			
			$password           = trim($this->input->post('password'));
			
			$user_det_salt =         $this->password_salt;
			
			$status				= $this->input->post('status');
            
            
			$var 				= 'user_image';
			$image_name 			=  (isset($data['subadmin'][0]['profile_image'])) ? $data['subadmin'][0]['profile_image'] : "";
			// echo "<pre>";
			//print_r($menu_permission);
			//print_r($sub_menu_permission);
			//echo "</pre>";
			//die;
			
			$this->mongo_db->where(array('email_addres'=>$email))->where_ne('_id',(string)$id);
			$res1=$this->mongo_db->get('users');
			
			
			if(count($res1)>0 )
			{
				$this->session->set_flashdata('flash_message', 'email_exist');
				 redirect('control/manage-users');
				
			}
			
            
            $data_to_store = array(
								
								'first_name' 		=> $first_name,
								'last_name' 		=> $last_name,
								'email' 	        => (string)$email,
								
 								'status' 			=> $status,
							);
            
			//echo "<pre>";print_r($res1); print_r($res2);die;
			
			
			
			if($_FILES[$var]["name"]!='')
			{
				if ( ($_FILES[$var]["type"] 	== "image/jpeg") ||
				    ($_FILES[$var]["type"] 	== "image/JPEG") ||
				    ($_FILES[$var]["type"] 	== "image/jpg") ||
				    ($_FILES[$var]["type"] 	== "image/JPG") ||
				    ($_FILES[$var]["type"] 	== "image/gif") ||
				    ($_FILES[$var]["type"] 	== "image/GIF") ||
				    ($_FILES[$var]["type"] 	== "image/png") ||
				    ($_FILES[$var]["type"] 	== "image/PNG") )
				{
					$DIR_IMG_NORMAL 	= FILEUPLOADPATH.'/assets/uploads/user_images/';
					$filename 		= substr($_FILES[$var]['name'],strripos($_FILES[$var]['name'],'.'));
					$s				= time().rand(0,20).$filename;	
					$fileNormal 		= $DIR_IMG_NORMAL.$s;
					$file 			= $_FILES[$var]['tmp_name'];
					
					list($width, $height) 	= getimagesize($file);
					$result 				= move_uploaded_file($file, $fileNormal);
					if($result)
					{
						$old_image = (isset($data['subadmin'][0]['profile_image'])) ? $data['subadmin'][0]['profile_image'] : "";
						if($old_image!='')
						{
							if(file_exists(realpath('user_image/'.$old_image)))
							{
								@unlink(realpath('user_image/'.$old_image));
							}
							if(file_exists(realpath('user_image/thumb/'.$old_image)))
							{
								@unlink(realpath('user_image/thumb/'.$old_image));
							}
						}
						
						$srcPath		= FILEUPLOADPATH.'assets/uploads/user_images/'.$s;
						$destPath1 	= FILEUPLOADPATH.'assets/uploads/user_images/thumb/'.$s;
						$destWidth1	= 100;
						$destHeight1	= 100;
						$this->imagethumb->thumbnail_new($destPath1, $srcPath, $destWidth1, $destHeight1);
						$image_name	= $s;
                        $data_to_store['profile_image'] = $image_name;
					}
				}
			}
		
			if($password!="")
			{
				$user_det_salt = isset($user_det[0]['password_salt']) ? $user_det[0]['password_salt'] : '';
				
                $user_det_salt = ($user_det_salt) ? $user_det_salt : $this->password_salt;
				$password=$enc_password 	= crypt($password, $user_det_salt);
				
				$data_to_store['pass_word']	=  $password;
				$data_to_store['password_salt']	=  $user_det_salt;
			}
				 // echo "<pre>";echo $id;print_r($data_to_store);die;
			$update_subadmin	= $this->common_model->update('users',$data_to_store,array('_id'=>(string)$id));	
			if($update_subadmin)
			{
				
					$this->session->set_flashdata('flash_message', 'user_updated');
			}
			else{
				$this->session->set_flashdata('flash_message', 'user_not_updated');
			}
			  
				redirect('control/manage-users');
		}
		
		$data['view_link'] = 'admin/users/edit_user';
		$this->load->view('includes/template', $data);
	}
	
	public function delete()
	{
		
		
		$id = $user_id=$this->uri->segment(4);
		$profile_image=$this->common_model->get('users',array('*'),array('_id'=>(string)$id));
		if(isset($profile_image) && count($profile_image)>0)
		{
			if(file_exists(realpath('user_image/'.$profile_image[0]['profile_image'])))
			{
			unlink(realpath('user_image/'.$profile_image[0]['profile_image']));
			}
			if(file_exists(realpath('user_image/thumb/'.$profile_image[0]['profile_image'])))
			{
			unlink(realpath('user_image/thumb/'.$profile_image[0]['profile_image']));
			}
		}
        if($this->common_model->update('users',array('user_deleted'=>'1'),array('_id'=>(string)$id)) == TRUE )
		{
			$this->session->set_flashdata('flash_message', 'user_deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'user_not_deleted');
		}
			redirect('control/manage-users');
	}
	
	public function change_status()
	{
		$user_object_id = $this->input->post('id');
		
		$this->mongo_db->where(array('_id'=>(string)$user_object_id));
		$res=$this->mongo_db->get('users');
		
		$current_status = isset($res[0]['status']) ? $res[0]['status'] : '';
		
		if($current_status !="")
		{	$new_status= ($current_status=='1')? '0' : '1'; 
			//echo $current_status;
			$data_to_update= array(
								   'status' => $new_status
								   );
			if($this->common_model->update('users',$data_to_update,array('_id'=>(string)$user_object_id)) == TRUE)
			{
				echo $new_status;
			}
		}
		
	}
	
	
}
?>