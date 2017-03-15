<?php
class Subadmin extends MY_Controller {

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
		
		
		
		
		$data['data']['subadmin'] 		=  $this->Subadmin_model->get_subadmin();
		$data['view_link'] 				= 'admin/subadmin/index';
		$this->load->view('includes/template', $data);
	}
    
    
	function user_name_chk()
	{
		$data['data']					= $this->data;
		//getting dealers id
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		
		if(!empty($this->cmp_auth_id))
			$admin_details 			= $this->myaccount_model->get_account_data($user_id, 1);
		else
			$admin_details 			= $this->myaccount_model->get_account_data($user_id);
				
		$data['data']['setting_data'] 	= $admin_details;
        
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{ 
			$new			= $this->input->post('new_user_name');
			$old			= $this->input->post('h_user_name');
			$user_name	= $this->input->post('user_name');
			$email		= $this->input->post('email');
			
			if($email	!= '')
			{
				$chk	= $this->Subadmin_model->email_check($email);
				echo $chk;  
			}
			if($user_name	!= '')
			{
				$chk	= $this->Subadmin_model->new_user_name($user_name);
				echo $chk;
			}
			if($new !='' && $old != '')
			{
				$chk	= $this->Subadmin_model->chk_user_name($new,$old);
				echo $chk;
			}
		}
	}
    
	public function add()
	{
		$data['data']					= $this->data;
		//getting dealers id
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;

		
		$this->mongo_db->where(array('menu_type'=>'0','is_subadmin'=>'1','status'=>'1','parent_id'=>'0'));
		$this->mongo_db->order_by(array('title'=>'asc'));
		
		$data['userdata']=$fetch = $this->mongo_db->get('menus');
		//echo "<pre>"; print_r($fetch); die;
		
		
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$first_name 			= trim($this->input->post('firstname'));
			$last_name 			= trim($this->input->post('lastname'));
			$email				= trim($this->input->post('a_email'));
			$user_name 			= trim($this->input->post('user_name'));
			$password =$user_password= trim($this->input->post('password'));
			
			$user_det_salt =         $this->password_salt;
			$password=$enc_password 	= crypt($password, $user_det_salt);
			
			
			$menu_permission 		= $this->input->post('management');
			$sub_menu_permission 	= $this->input->post('submenu');
			$status				= $this->input->post('status');
			
			$image_name			= "";
			//echo "<pre>";
			//print_r($menu_permission);
			//print_r($sub_menu_permission);
			//echo "</pre>"; die;
			
			
			
			$data_to_store = array(
								'is_sub_admin' 	=> '1',
								'first_name' 		=> $first_name,
								'last_name' 		=> $last_name,
								'email_addres' 	=> $email,
								
								'user_name' 		=> $user_name,
								'pass_word' 		=> $password,
								'password_salt'     => $user_det_salt,
 								'status' 			=> $status,
							);
			
			//echo "<pre>";print_r($data_to_store); die;
			$res1=$this->common_model->get('membership',array('*'),array('email_addres'=>(string)$email));
			$res2=$this->common_model->get('membership',array('*'),array('user_name'=>(string)$user_name));
			if(count($res1)>0  || count($res2)>0)
			{
				$this->session->set_flashdata('flash_message', 'subadmin_not_added');
				 redirect('control/manage-subadmin');
				
			}
			
				
			$var 				= 'subadmin_image';
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
					
					
					$DIR_IMG_NORMAL 		= FILEUPLOADPATH.'assets/uploads/subadmin_image/';
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
						$srcPath		= FILEUPLOADPATH.'assets/uploads/subadmin_image/'.$s;
						$destPath 	= FILEUPLOADPATH.'assets/uploads/subadmin_image/thumb/'.$s;
						$destWidth	= 100;
						$destHeight	= 100;
						$this->imagethumb->thumbnail_new($destPath, $srcPath, $destWidth, $destHeight);
						$image_name	= $s;
						$data_to_store['profile_image'] = $image_name;
							
						//echo "<pre>";print_r($data_to_store); die;	
							
							
							//$insert = $this->Subadmin_model->add_subadmin($data_to_store);
							$insert = $this->common_model->add('membership',$data_to_store);
							//echo $insert; die;
							
							if($insert)
							{
								
								if(count($menu_permission)>0)
								{
								foreach($menu_permission as $k=>$permission)
								{
									 $permission_arr 			=  "";
									$data_to_store_permission	= array(
														'user_id' 		=> (string)$insert,
														'menu_id' 		=> (string)$permission,
														'menu_elements' 	=> $permission_arr,
														'status' 			=> "1",
													);
									
									//echo "<pre>";print_r($data_to_store_permission);die;
									$this->common_model->add('user_permission',$data_to_store_permission);
								}
								}
								
								if(count($sub_menu_permission)>0)
								{
									foreach($sub_menu_permission as $key=>$sub_permission)
									{
										$permission_str			=  "";
										$data_to_store_permission	= array(
															'user_id' 		=> (string)$insert,
															'menu_id' 		=> (string)$sub_permission,
															'menu_elements' 	=> $permission_str,
															'status' 			=> "1",
														);
										$this->common_model->add('user_permission',$data_to_store_permission);
									}
							    }
								//exit;
							        $setting_det = $this->common_model->get('settings');
									$site_name   = isset($setting_det[0]['site_name']) ? $setting_det[0]['site_name'] : '';
									$site_logo_img   = isset($setting_det[0]['site_logo']) ? $setting_det[0]['site_logo'] : '';
									
									$site_logo = '<img src="'.base_url().'assets/site/images/'.$site_logo_img.'" >';
									
									$reciever_name = $to_subadmin= ucfirst($first_name).' '.ucfirst($last_name);
									$user_type = 'subadmin';
									
									$email_template_reg = $this->common_model->get('email_templates',array('*'),array('_id'=>'58c231ad4a768931828e7ced'));
									
									$message_subject=$email_subject = isset($email_template_reg[0]['email_subject']) ? $email_template_reg[0]['email_subject'] : '';
									$article_body  = isset($email_template_reg[0]['email_template']) ? $email_template_reg[0]['email_template'] : '';
									
									$body =$message_content = str_replace(array ('[SITE_LOGO]','[NAME]','[USER_TYPE]','[SITE_NAME]','[USERNAME]','[PASSWORD]' ), array ( $site_logo,$reciever_name,$user_type,$site_name,$user_name,$user_password ), $article_body);
									
									$this->User_email_model->send_email($email, $message_subject, $message_content, '', '', '', $to_subadmin);
							        $this->session->set_flashdata('flash_message', 'subadmin_added');
								
								redirect('control/manage-subadmin');
							}
							else
							{
								$this->session->set_flashdata('flash_message', 'subadmin_not_added');
							}
					}
					else
					{
						$this->session->set_flashdata('flash_message', 'subadmin_not_added');
					}
				}
			}
			   redirect('control/manage-subadmin');
		}
        
            $data['view_link'] = 'admin/subadmin/add_subadmin';
            $this->load->view('includes/template', $data);
    }
	
	public function update()
	{
		$data['data']					= $this->data;
		//getting dealers id
		$user_id 						= $dealer_id = ($this->session->userdata('user_id_lovearchitect')) ?  $this->session->userdata('user_id_lovearchitect') : 0;
		
		
		
		$id 				= $this->uri->segment(4);
		$data['subadmin'] =$membership_det	= $this->common_model->get('membership',array('*'),array('_id'=>$id));
		if(count($membership_det)==0)
		{
			redirect('control/manage-subadmin');
			
		}
		
		
		//$data['userdata'] 	= $this->common_model->get('menu',array('*'),array('parent_id' => 0,'menu_type'=>0),null,null,null,null,'title','asc');
		//$data['userdata'] =$this->db->where('id !=',4)->where('id !=',10)->where('parent_id',0)->where('menu_type',0)->order_by('title','asc')->get('menu')->result_array();
		$this->mongo_db->where(array('menu_type'=>'0','is_subadmin'=>'1','status'=>'1','parent_id'=>'0'));
		$this->mongo_db->order_by(array('title'=>'asc'));
		
		$data['userdata']=$fetch = $this->mongo_db->get('menus');
		
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$first_name 			= trim($this->input->post('f_name'));
			$last_name 			= trim($this->input->post('l_name'));
			$email				= trim($this->input->post('a_email'));
			$user_name 			= trim($this->input->post('u_name'));
			$password 			= trim($this->input->post('password'));
			$menu_permission 		= $this->input->post('management');
			$sub_menu_permission 	= $this->input->post('submenu');
			$status				= $this->input->post('status');
			$var 				= 'subadmin_image';
			$image_name 			=  (isset($data['subadmin'][0]['profile_image'])) ? $data['subadmin'][0]['profile_image'] : "";
			// echo "<pre>";
			//print_r($menu_permission);
			//print_r($sub_menu_permission);
			//echo "</pre>";
			//die;
			
			$this->mongo_db->where(array('email_addres'=>$email))->where_ne('_id',(string)$id);
			$res1=$this->mongo_db->get('membership');
			
			$this->mongo_db->where(array('user_name'=>(string)$user_name))->where_ne('_id',(string)$id);
			$res2=$this->mongo_db->get('membership');
			if(count($res1)>0  || count($res2)>0)
			{
				$this->session->set_flashdata('flash_message', 'subadmin_not_updated');
				 redirect('control/manage-subadmin');
				
			}
			
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
					$DIR_IMG_NORMAL 	= FILEUPLOADPATH.'/assets/uploads/subadmin_image/';
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
							if(file_exists(realpath('subadmin_image/'.$old_image)))
							{
								@unlink(realpath('subadmin_image/'.$old_image));
							}
							if(file_exists(realpath('subadmin_image/thumb/'.$old_image)))
							{
								@unlink(realpath('subadmin_image/thumb/'.$old_image));
							}
						}
						
						$srcPath		= FILEUPLOADPATH.'assets/uploads/subadmin_image/'.$s;
						$destPath1 	= FILEUPLOADPATH.'assets/uploads/subadmin_image/thumb/'.$s;
						$destWidth1	= 100;
						$destHeight1	= 100;
						$this->imagethumb->thumbnail_new($destPath1, $srcPath, $destWidth1, $destHeight1);
						$image_name	= $s;
					}
				}
			}
			  
			$data_to_store	= array(
						   'is_sub_admin' 	=> '1',
						   'first_name' 	=> $first_name,
						   'last_name' 	=> $last_name,
						   'email_addres' 	=> $email,
						   'profile_image' 	=> $image_name,
						   'user_name' 	=> $user_name,
						   
						   'status' 		=> $status,
						);
			if($password!="")
			{
				$user_det_salt = $this->Users_model->get_user_salt('', $user_name);
				$user_det_salt = ($user_det_salt) ? $user_det_salt : $this->password_salt;
				$password=$enc_password 	= crypt($password, $user_det_salt);
				
				$data_to_store['pass_word']	=  $password;
				$data_to_store['password_salt']	=  $user_det_salt;
			}
				 // echo "<pre>";echo $id;print_r($data_to_store);die;
			$update_subadmin	= $this->common_model->update('membership',$data_to_store,array('_id'=>(string)$id));	
			if($update_subadmin)
			{
				if($this->common_model->delete('user_permission',array('user_id'=>$id)) ==TRUE )
				//echo "Deleted";
				//
				//die;
				if(count($menu_permission)>0)
				{
				foreach($menu_permission as $k=>$permission)
				{
					 $permission_arr 			=  "";
					//echo $permission_arr;die;
					
					$data_to_store_permission	= array(
										'user_id' 		=> (string)$id,
										'menu_id' 		=> (string)$permission,
										'menu_elements' 	=> $permission_arr,
										'status' 			=> '1',
									);
					$this->common_model->add('user_permission',$data_to_store_permission);
				}
				}
				
				if(count($sub_menu_permission)>0)
				{
					foreach($sub_menu_permission as $key => $sub_permission)
					{
						$permission_str			= "";
						$data_to_store_permission_sub	= array(
											'user_id' 		=> (string)$id,
											'menu_id' 		=> (string)$sub_permission,
											'menu_elements' 	=> $permission_str,
											'status' 			=> "1",
										);
						$this->common_model->add('user_permission',$data_to_store_permission_sub);
					}
			    }
					$this->session->set_flashdata('flash_message', 'subadmin_updated');
			}
			else{
				$this->session->set_flashdata('flash_message', 'subadmin_not_updated');
			}
			  
				redirect('control/manage-subadmin');
		  }
		
		$data['view_link'] = 'admin/subadmin/edit_subadmin';
		$this->load->view('includes/template', $data);
	}
	
	public function delete()
	{
		
		
		$id = $user_id=$this->uri->segment(4);
		$profile_image=$this->common_model->get('membership',array('*'),array('_id'=>$id));
		if(isset($profile_image) && count($profile_image)>0)
		{
			if(file_exists(realpath('subadmin_image/'.$profile_image[0]['profile_image'])))
			{
			unlink(realpath('subadmin_image/'.$profile_image[0]['profile_image']));
			}
			if(file_exists(realpath('subadmin_image/thumb/'.$profile_image[0]['profile_image'])))
			{
			unlink(realpath('subadmin_image/thumb/'.$profile_image[0]['profile_image']));
			}
		}
        if($this->common_model->delete('membership',array('_id'=>$id)) == TRUE && $this->common_model->delete('user_permission',array('user_id'=>$user_id)) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'subadmin_deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'subadmin_not_deleted');
		}
			redirect('control/manage-subadmin');
	}
	
	public function change_status()
	{
		$user_object_id = $this->input->post('id');
		
		$this->mongo_db->where(array('_id'=>(string)$user_object_id));
		$res=$this->mongo_db->get('membership');
		
		$current_status = isset($res[0]['status']) ? $res[0]['status'] : '';
		
		if($current_status !="")
		{	$new_status= ($current_status=='1')? '0' : '1'; 
			//echo $current_status;
			$data_to_update= array(
								   'status' => $new_status
								   );
			if($this->common_model->update('membership',$data_to_update,array('_id'=>(string)$user_object_id)) == TRUE)
			{
				echo $new_status;
			}
		}
		
	}
	
	
}
?>