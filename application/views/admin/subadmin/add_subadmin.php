<section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Add Subadmin
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                <form class="cmxform form-horizontal " name="addsubadmin" id="addsubadmin" method="post" enctype="multipart/form-data"  action="<?php echo site_url("control").'/manage-subadmin/add'; ?>">
                                    <div class="form-group ">
                                        <label for="firstname" class="control-label col-lg-3">First name:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" required maxlength="20" id="firstname" name="firstname" value="" type="text"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group ">
                                        <label for="lastname" class="control-label col-lg-3">Last name:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" required  maxlength="20" id="lastname" name="lastname" value="" type="text"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group ">
                                        <label for="a_email" class="control-label col-lg-3">Email:</label>
                                        <div class="col-lg-6">
                                            <input type="hidden" id="hdn_email" name="hdn_email" value=''>
                                            <input class=" form-control" required  maxlength="40" id="a_email" name="a_email" value="" type="email" onkeyup="email_check();"/>
                                            <div class="error_a_email" id="error_a_email" style="color: #B94A48;"></div>
                                        </div>
                                    </div>
                               
						    <div class="form-group last">
								<label class="control-label col-md-3">Profile Image</label>
								<div class="col-md-9">
									<div class="fileupload fileupload-new" data-provides="fileupload">
		
									<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
									<div>
										   <span class="btn btn-white btn-file">
										   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Upload your image</span>
										   <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
										   <input type="file" class="default" required name="subadmin_image" id="subadmin_image" />
										   </span>
																		   
								<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
										<label  id="img_error" class="error" style="display: none;" for="flag">Please upload an image</label>
										<label  id="image_error" class="error" style="display: none;" for="flag">Please upload a valid image</label>
									</div>
									</div>
								</div>
							</div>
                                    
                                    <div class="form-group ">
                                        <label for="user_name" class="control-label col-lg-3">Username:</label>
                                        <div class="col-lg-6">
                                            <input type="hidden" id="u_hdn" name="u_hdn" value=''>
                                            <input class=" form-control" required maxlength="20" id="user_name" name="user_name" value="" type="text" onkeyup="username_check();"/>
                                            <div class="username_error" id="username_error" style="color: #B94A48;"></div>
                                         </div>
                                    </div>
					
                                    <div class="form-group ">
                                            <label for="password" class="control-label col-lg-3">Password:</label>
                                            <div class="col-lg-6">
                                                <input class=" form-control" required maxlength="20" id="password" name="password" value="" type="password"/>
                                            </div>
                                     </div>
				   
                                    <div class="form-group ">
                                        <label for="password" class="control-label col-lg-3">Confirm Password:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" required maxlength="20" id="cpassword" name="cpassword" value="" type="password"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group ">
                                        <label for="status" class="control-label col-lg-3">Status:</label>
                                        <div class="col-lg-6">
                                            	<select class="form-control" style="width: 300px" id="status" name="status">							                        <option value="1">Active</option>
                                                    <option value="0">Inactive</option>							
                                        	</select>
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
									?>
												<div class="tree-folder">
													<div class="tree-folder-header">
														<i class="fa fa-arrow-circle-right" style="vertical-align: baseline;"></i>
														<div class="tree-folder-name">	
															<input id="management_<?php echo $user_menu['id'];?>" name="management[]" value="<?php echo $user_menu['id'];?>" type="checkbox"
															
															<?php if(isset($user_menu['is_default']) && $user_menu['is_default'] =='1'){ ?>onclick="return false;" checked <?php }else{ ?>onclick="check_all_boxes('<?php echo $user_menu['id'];?>','menu','')" <?php } ?> >
															&nbsp; &nbsp;<?php echo ucfirst($user_menu['title']) ;?>
														</div>
													</div>
													<?php
														$ci=& get_instance();
														$this->mongo_db->where(array('menu_type'=>'0','is_subadmin'=>'1','status'=>'1','parent_id'=> (string)$user_menu['id']));
		                                                                                                $this->mongo_db->order_by(array('title'=>'asc'));
														$all_sub_menus = $this->mongo_db->get('menus');
														//$all_sub_menus = $ci->db->where('parent_id',$user_menu['id'])->where('status',1)->get('menu')->result_array();
														
														if(!empty($all_sub_menus)){
													?>
															<div class="tree-folder-content" id="sub_tree<?php echo $user_menu['id'];?>">
																<?php
																	foreach($all_sub_menus as $sub_menu)
																	{
																?>
																		<div class="tree-folder" style="display: block;">
																			<div class="tree-folder-header">
																				<i class="fa fa-arrow-circle-right" style="vertical-align: super;"></i>
																				<div class="tree-folder-name">
																					<input id="submenu_<?php echo $sub_menu['id'];?>" name="submenu[]" value="<?php echo $sub_menu['id'];?>"  style="width: 20px;display: inline-block;vertical-align: middle;" type="checkbox" onclick="check_all_boxes('<?php echo $sub_menu['id']?>','item','<?php echo $user_menu['id'];?>')">																					
																					&nbsp; &nbsp;<?php echo ucfirst($sub_menu['title']) ;?>																		 </div>
																			</div>
																			<?php
																				$menu_permission = explode(',', $sub_menu['menu_permission']);
																				if(!empty($menu_permission)){ ?>
																				<div class="tree-folder-content" name="tree-folder_<?php echo $user_menu['id'];?>"  id="sub_tree_items<?php echo $sub_menu['id'];?>">
																					<div class="tree-item hide" id="menu_p_<?php echo $sub_menu['id']; ?>">
																					<?php
																						echo '<i class="tree-dot"></i>';
																						foreach($menu_permission as $permission)
																						{
																							if($permission == 0) $permiss = "Listing";
																							if($permission == 1) $permiss = "Add";
																							if($permission == 2) $permiss = "Edit";
																							if($permission == 3) $permiss = "Delete";
																				?>
																						    <input id="management_<?php echo $sub_menu['id'].$permission; ?>" onclick="return false;" onkeydown="return false;"  name="permissions[<?php echo $sub_menu['id'] ;?>][]" value="<?php echo $permission;?>" style="width: 20px;display: inline-block;vertical-align: middle;"  type="checkbox"  >
																							&nbsp; &nbsp;<?php echo ucfirst($permiss) ;?>
																					<?php
																						}
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
												 
										<!--<div class="error_fname" id="error_sname" style="color: #B94A48;"></div>
										<input type="hidden" name="sub_id" value="<?php //if(isset($subadmin_name[0]['id'])){ echo $subadmin_name[0]['id']; } ?>">-->
									
						   </div>
						</div>
				    </div>
								
								<div class="form-group">
									   <div class="col-lg-offset-3 col-lg-6">
										  <button class="btn btn-primary" type="submit" id="add">Add</button>
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
<script type="text/javascript">
		function checkbox_submenu(v,p,l)
		{      
				 if($("#management_"+p ).prop( "checked" )==true)
				{
						//alert(p);
		          $( "#management_"+l ).prop('checked', true);
				  $( "#submenu_"+v ).prop('checked', true);	
				}
				 if($("#management_"+l ).prop( "checked" )==false)		
                 {
						
						
				 }
        }
		
	   function check_all_boxes(id_tree,type,admin)
	   {
			 if ( type == "menu" ) {
				      

                                     $("#management_"+id_tree).show().children().show();
				    $("#sub_tree"+id_tree).find('input[type=checkbox]').each(function () {
						  if($( "#management_"+id_tree ).prop( "checked" )==true)
						  {
								this.checked = true;
						  }else{
								this.checked = false;
						  }
				    });
				    //$("#management_"+id_tree).show().children().show();
				    $("#sub_tree_items"+id_tree).show();
			 }
			 if ( type == "item" ) {
				
				$( "#management_"+admin).prop('checked', true);
				$("#sub_tree_items"+id_tree).show();
				    $("#sub_tree_items"+id_tree).find('input[type=checkbox]').each(function () {
						  if($( "#submenu_"+id_tree ).prop( "checked" )==true)
						  {
								this.checked = true;
						  }else{
								this.checked = false;
						  }
				    });
				    //$("#sub_tree_items"+id_tree).hide();
			 }
			  if ( type == "sub_item" ) {
				   // alert(id);
				    $( "#submenu_"+id ).prop('checked', true);
						  
			 }
	   }
	   
	   $(document).ready(function()
	   {
		 jQuery.validator.addMethod("regex", function(value, element, param) {
              if(value.search(/\S/) != -1)
               return value.match(new RegExp("." + param + "$"));
              else
              return true;
           
           });
		
		   $("#addsubadmin").validate({
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
							 required: true,
							 minlength: 6,
						  },
				cpassword: {
							 required: true,
							 minlength: 6,
							 equalTo:  "#password",
						  },
				 subadmin_image:  {
									 required: true,
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
				 password:  {
								required: "Please provide a password",
								minlength: "Your password must be at least 6 characters long",
						  },
							 
				 cpassword:  {
								required: "Please provide a password again",
								minlength: "Your password must be at least 6 characters long",
								equalTo: "Please enter the same password as above",
						  },			
							 
				 subadmin_image:  {
								required: 'Please upload an image',
								accept:    'Please provide a valid image (JPG,JPEG,BMP,GIF,PDF,PNG)',
						  }
			 }
		   });
	   });

	function username_check()
    {
			var user_name= $("#user_name").val();
			if(user_name.search(/\S/)!=-1)
			{
				
				var dataString = "user_name=" + user_name;
                $.ajax({
				type: "POST",
				url: "<?php echo base_url('control/user_name_chk'); ?>",
				data: dataString,
				cache: false,
				success: function(data)
                {
                    if (data=='yes')
                    {
                    $("#username_error").css({ 'display': "block","color":"#b94a48;" });
                    document.getElementById("username_error").innerHTML='This Username already exists';
                    document.getElementById("u_hdn").value='no';
                    }if(data=='no')
                    {
                        $("#username_error").css({ 'display': "block","color":"#48B963" });
                        document.getElementById("username_error").innerHTML='This username is available';
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
<link href="<?php echo base_url();?>assets/admin/js/iCheck/skins/minimal/minimal.css" rel="stylesheet">

<!-- For Tree -->
<script src="<?php echo base_url();?>assets/admin/js/tree.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/fuelux/js/tree.min.js"></script>
<link href="<?php echo base_url();?>assets/admin/js/fuelux/css/tree-style.css" rel="stylesheet">
<script>
	jQuery(document).ready(function() {
        TreeView.init();
    });
</script>

	
