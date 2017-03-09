<section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
								Edit Subadmin
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                <form class="cmxform form-horizontal " name="editsubadmin" id="editsubadmin" method="post" enctype="multipart/form-data" action="<?php echo site_url("control").'/manage-subadmin/edit/'.$this->uri->segment(4) ; ?>">
                                   
                                <div class="form-group ">
                                    <label for="f_name" class="control-label col-lg-3">First Name:</label>
                                    <div class="col-lg-6">
                                        <input class=" form-control" id="f_name" required name="f_name" value="<?php echo isset($subadmin[0]['first_name']) ? $subadmin[0]['first_name'] :'' ; ?>" type="text"/>
                                     </div>
                                </div>
                                
                                <div class="form-group ">
                                    <label for="l_name" class="control-label col-lg-3">Last Name:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="l_name" required name="l_name" value="<?php echo isset($subadmin[0]['last_name']) ? $subadmin[0]['last_name'] :''; ?>" type="text"/>
                                        </div>
                                </div>
                                                    
                                <div class="form-group ">
                                        <label for="a_email" class="control-label col-lg-3">Email:</label>
                                        <div class="col-lg-6">
                                            <input type="hidden" id="hdn_email" name="hdn_email" value=''>
                                            <input class=" form-control" required maxlength="40" id="a_email" name="a_email" value="<?php echo isset($subadmin[0]['email_addres']) ? $subadmin[0]['email_addres'] : '' ;?> " type="email" />
                                            <div class="error_a_email" id="error_a_email" style="color: #B94A48;"></div>
                                        </div>
                                </div>
                                
								<div class="form-group last">
								    <label class="control-label col-md-3">Profile Image</label>
										<div class="col-md-9">
											<div class="fileupload" data-provides="fileupload">
												<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
												<?php  $profile_image = isset($subadmin[0]['profile_image']) ? $subadmin[0]['profile_image'] : '';
												if($profile_image !='')
												{
												?>
												 <img src="<?php echo base_url();?>assets/uploads/subadmin_image/<?php echo $profile_image;?>">
												<?php
												}
												?>
												</div>
											
											<div>
												<span class="btn btn-white btn-file">
												<span><i class="fa fa-paper-clip"></i> Upload Profile image</span>
												<input type="file" class="default" name="subadmin_image" id="subadmin_image" />
												</span>
																				
													 <label  id="img_error" class="error" style="display: none;" for="profile_image">Please upload an image</label>
													 <label  id="image_error" class="error" style="display: none;" for="profile_image">Please upload an valid image</label>
											</div>
											</div>
										 </div>
								</div>
								
								<div class="form-group ">
                                    <label for="u_name" class="control-label col-lg-3">Username:</label>
                                    <div class="col-lg-6">
                                        <input type="hidden" id="u_hdn" name="u_hdn" value=''>
                                        <input type="hidden" id="h_user_name" name="h_user_name" value='<?php echo  isset($subadmin[0]['user_name']) ? $subadmin[0]['user_name'] : '' ; ?>'>
                                        <input class=" form-control" id="u_name" required name="u_name" value="<?php echo isset($subadmin[0]['user_name']) ? $subadmin[0]['user_name'] : ''; ?>" type="text" />
                                        <div class="username_error" id="username_error" style="color: #B94A48;"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
										<label for="short_desc" class="control-label col-lg-3">Password</label>
											<div class="col-lg-6">
												<div class="input-append">
												<input  value="" name="password" maxlength="20" id="password" class="form-control" type="password">
													<!--<span class=" input-group-btn add-on">
													  <button class="btn btn-white" id="btn_pass" type="button" style="padding: 5px; margin-left:54px; color:#666; " onclick="show_pass( 'password', 'btn_pass' )">Change</button>
													</span>-->
												</div>
											</div>
								</div>
                                
								<div class="form-group" id="cpass">
									   <label for="password" class="control-label col-lg-3">Confirm Password:</label>
									   <div class="col-lg-6">
										  <input class=" form-control"  maxlength="20" id="cpassword" name="cpassword" value="" type="password"/>
									   </div>
								</div>
                               
                                <div class="form-group ">
                                    <label for="ccomment" class="control-label col-lg-3">Status:</label>
                                        <div class="col-lg-6">
                                            <select class="form-control valid" style="width: 300px" id="status" name="status">
                                                <option value="1" <?php if(isset($subadmin[0]['status']) && $subadmin[0]['status']=='1'){?> selected="selected"  <?php }?>>Active</option>
                                                <option value="0" <?php if(isset($subadmin[0]['status']) && $subadmin[0]['status']=='0'){?> selected="selected"  <?php }?>>Inactive</option>
                                            </select>
                                            <div id='error_message'></div>
                                        </div>
                                </div>
				
				
			<div class="form-group ">
				<label for="ccomment" class="control-label col-lg-3">Manage Permissions:</label>
						<div class="col-lg-6">
				    <?php
				    $all_manage_arr=array();
				    if(isset($userdata) && $userdata!="")
				    {
						  echo '<div class="tree tree-plus-minus">';
						  foreach($userdata as $user_menu)
						  {
								$id 					= $this->uri->segment(4);
								$subadmin_permission 		= $this->common_model->get('user_permission',array('*'),array('user_id'=>$id,'menu_id'=>$user_menu['id']));
								
								$subadmin_permission_add 	= array();
								
								if(isset($subadmin_permission) && (count($subadmin_permission) > 0))
								{
										$subadmin_permission_add = $subadmin_permission[0]['menu_elements'];
										$subadmin_permission_add = explode(',',$subadmin_permission_add);
								}
								?>	
												<div class="tree-folder">
													<div class="tree-folder-header">
														<i class="fa fa-arrow-circle-right" style="vertical-align: baseline;"></i>
														<div class="tree-folder-name">
																
															<input id="management_<?php echo $user_menu['id'];?>" name="management[]" value="<?php echo $user_menu['id'];?>" style="width: 20px;display: inline-block;vertical-align: middle;" type="checkbox" <?php if(isset($subadmin_permission) && (count($subadmin_permission) > 0) ){echo "checked";};?>
										<?php if(isset($user_menu['is_default']) && $user_menu['is_default'] =='1'){ ?>onclick="return false;" checked <?php }else{ ?>onclick="check_all_boxes('<?php echo $user_menu['id'];?>','menu','')" <?php } ?> ><?php echo $user_menu['title'] ;?>
														</div>
													</div>
													<?php
														//$ci=& get_instance();
														//$all_sub_menus = $ci->db->where('parent_id',$user_menu['id'])->where('status',1)->get('menu')->result_array();
														$this->mongo_db->where(array('menu_type'=>'0','is_subadmin'=>'1','status'=>'1','parent_id'=> (string)$user_menu['id']));
		                                                                                                $this->mongo_db->order_by(array('title'=>'asc'));
														$all_sub_menus = $this->mongo_db->get('menus');
														
														if(!empty($all_sub_menus))
														{
														?>
															<div class="tree-folder-content" id="sub_tree<?php echo $user_menu['id'];?>">
																<?php
																	foreach($all_sub_menus as $sub_menu)
																	{
																	   $submenu = $this->common_model->get('user_permission',array('*'),array('user_id'=>$id,'menu_id'=>$sub_menu['id']));
																	   
																	
																	   
																	   $submenu_permission_add =array();
																	   if(isset($submenu) && (count($submenu) > 0))
																	   {
																	   $submenu_permission_add = $submenu[0]['menu_elements'];
																	   $submenu_permission_add = explode(',',$submenu_permission_add);
																	   }
																	?>
																		<div class="tree-folder" style="display: block;">
																			<div class="tree-folder-header">
																				<i class="fa fa-arrow-circle-right" style="vertical-align: super;"></i>
																				<div class="tree-folder-name">
																						
																				<input id="submenu_<?php echo $sub_menu['id'];?>" name="submenu[]" value="<?php echo $sub_menu['id'];?>" style="width: 20px;display: inline-block;vertical-align: middle;" type="checkbox" <?php  if(isset($submenu) && (count($submenu) > 0)){echo "checked";}?>
 onclick="check_all_boxes('<?php echo $sub_menu['id']?>','item','<?php echo $user_menu['id'];?>')" ><?php echo $sub_menu['title'] ;?>		
																				</div>
																			</div>
																			<?php
																			 $menu_permission = explode(',', $sub_menu['menu_permission']);
																			 if(!empty($menu_permission))
																			 { ?>
																			 <div class="tree-folder-content" id="sub_tree_items<?php echo $sub_menu['id'];?>" style="display:none">
																				 <div class="tree-folder-header">
																				 <?php
																				echo '<div class="tree-folder-name">';
																				foreach($menu_permission as $permissions)
																				{
																				  $permiss = '';
																				  
																				  if($permissions == 0)
																						$permiss = "Listing";
																				  if($permissions == 1)
																						$permiss = "Add";
																				  if($permissions == 2)
																						$permiss = "Edit";
																				  if($permissions == 3)
																						$permiss = "Delete";
																					?>
																				<input id="permissions_<?php echo $permissions."_".$sub_menu['id']; ?>" name="permissions[<?php echo $sub_menu['id'];?>][]" style="width: 20px;display: inline-block;vertical-align: middle;" value="<?php echo $permissions ;?>" type="checkbox"   <?php if(in_array($permissions,$submenu_permission_add)){echo "checked";}?>   onclick="return false;" onkeydown="return false;"><?php echo $permiss; 
																				}
																				echo "</div>";
																				?>
																				 </div>
																			 </div>
																	 <?php	} ?>
																		</div>
															<?php	} ?>
															</div>
												<?php	} ?>
												</div>
									<?php
											}
										}
									?>
						   </div>
						</div>
				    </div>	
						  <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-6">
                                        <button class="btn btn-primary" type="submit">Save</button>
                                        <button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-subadmin';">Cancel</button>
                                    </div>
                                </div>
                                </form>
						    </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
  <script>
	   function check_all_boxes(id_tree,type,id)
	   {
			 if ( type == "menu" ) {
				//if (id_tree==1 || id_tree==2 || id_tree==3)
				/*if (id_tree==1 )
				{
                                    if( $( "#management_"+id_tree ).prop( "checked" )==false)
				   {
					$( "#management_"+id_tree ).prop( "checked",true);	
						
				   }
				   
                                   }
				else{*/
				$("#sub_tree"+id_tree).find('input[type=checkbox]').each(function () {
						  if($( "#management_"+id_tree ).prop( "checked" )==true)
						  {
								this.checked = true;
						  }else{
								this.checked = false;
						  }
				    });
			    //}
			 }
			 if ( type == "item" ) {
				
				    $( "#management_"+id).prop('checked', true);
					$( "#sub_tree_items"+id_tree).show();
						  if($( "#submenu_"+id_tree ).prop( "checked" )==true)
						  {   
								$( "#permissions_0_"+id_tree ).prop('checked', true);
								$( "#permissions_1_"+id_tree ).prop('checked', true);
								$( "#permissions_2_"+id_tree ).prop('checked', true);
								$( "#permissions_3_"+id_tree ).prop('checked', true);
						  }else{
								$( "#permissions_0_"+id_tree ).prop('checked', false);
								$( "#permissions_1_"+id_tree ).prop('checked', false);
								$( "#permissions_2_"+id_tree ).prop('checked', false);
								$( "#permissions_3_"+id_tree ).prop('checked', false);
						  }
						  $( "#sub_tree_items"+id_tree).hide();
			 }
			 if ( type == "sub_item" ) {
				  // alert(id);
				    $( "#submenu_"+id ).prop('checked', true);
						  
			 }
	   }
	   function show_pass(args,args1)
	   {
			 $('#'+args).removeAttr('readonly');
			 $('#'+args1).remove();
			 $("#cpass").show();
        }
		$(document).ready(function()
		{
			jQuery.validator.addMethod("regex", function(value, element, param)
		        {
			if(value.search(/\S/) != -1)
			 return value.match(new RegExp("." + param + "$"));
			else
			return true;
		     
		        });
			
			$("#editsubadmin").validate({
			rules:
			{
			         firstname: {  required: true,
				     regex: "[a-zA-Z ]",
				 },
				 lastname:  {  required: true,
				                regex: "[a-zA-Z ]",   
				 },
				 a_email:  {
						required: true,
						email: true,
						  },
				user_name: {  required: true, },
						  password: {
									  minlength: 6
								},
						  cpassword: {
									   minlength: 6,
									   equalTo:  "#password"
								},
						  subadmin_image:  {
											   //required: true,
											   accept: "image/*"
									   }
				    },
				    messages:
				    {
					  firstname: {   required: 'Please enter first name',
				                regex:  'Please enter valid name',
				 },
				 lastname:   {   required: 'Please enter last name',
				                 regex:  'Please enter valid name',
				 },
				 a_email: {    required:'Please enter email-id',
				               email: 'Please enter a valid email',
				           },
				user_name: {  required: 'Please enter username', },
					   //a_email: 'Please enter your email-id',
					   password:  {
								    minlength: "Your password must be at least 5 characters long"
								},
					   cpassword:  {
								    minlength: "Your password must be at least 5 characters long",
								    equalTo: "Please enter the same password as above"
								},			
					   subadmin_image: {
								    //required: 'Please upload an image',
								    accept:    'Please provide a valid image (JPG,JPEG,BMP,GIF,PDF,PNG)',
								}
				    }
			});
		})
	function username_check(){
	   var new_user_name= $("#u_name").val();
	   var h_user_name=$("#h_user_name").val();
	   if(new_user_name.search(/\S/)!=-1)
	   {
		   var dataString = "new_user_name=" + new_user_name+'&h_user_name='+h_user_name;
			 $.ajax({
				    type: "POST",
				    url: "<?php echo base_url('control/user_name_chk'); ?>",
				    data: dataString,
				    cache: false,
				    success: function(data){
					 
				    if ( data == 'yes' ) {
						  $("#username_error").css({ 'display': "block","color":"#b94a48;" });
						  document.getElementById("username_error").innerHTML='This Username is not available';
						  document.getElementById("u_hdn").value='no';
					    }
				    if( data=='no' ){
						  $("#username_error").css({ 'display': "block","color":"#48B963" });
						  document.getElementById("username_error").innerHTML='This username available';
						  document.getElementById("u_hdn").value='yes';
				    }
				 }
			 });
	   }
	   else 
	   {
			document.getElementById("username_error").innerHTML=''; 
	   }
	}
    
    function email_check()
    {
        var email=$("#a_email").val();
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if(email.search(/\S/)!=-1)
        {
            if(ck_email.test(email))
            {
                    var dataString = "email=" + email;
                    $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('control/user_name_chk'); ?>",
                    data: dataString,
                    cache: false,
                    success: function(data)
                    {
                            if (data=='yes')
                            {
                                $("#error_a_email").css({ 'display': "block","color":"#b94a48;" });
                                document.getElementById("error_a_email").innerHTML='This Email id already exists';
                                document.getElementById("hdn_email").value='no';
                            }
                            if(data=='no')
                            {
                                $("#error_a_email").css({ 'display': "block","color":"#48B963" });
                                document.getElementById("error_a_email").innerHTML='This email id is available';
                                document.getElementById("hdn_email").value='yes';
                            }
                    }
                    });
            }
            else
            {
                    document.getElementById("error_a_email").innerHTML=''; 
            }
        }
        else 
        {
               document.getElementById("error_a_email").innerHTML=''; 
        }
	}	
</script>
<!-- For Tree -->
<script src="<?php echo base_url();?>assets/admin/js/tree.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/fuelux/js/tree.min.js"></script>
<link href="<?php echo base_url();?>assets/admin/js/fuelux/css/tree-style.css" rel="stylesheet">
<script>
	jQuery(document).ready(function() {
        TreeView.init();
    });
</script>