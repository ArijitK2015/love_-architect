<section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
								Edit User
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                <form class="cmxform form-horizontal " name="edituser" id="edituser" method="post" enctype="multipart/form-data" action="<?php echo site_url("control").'/manage-users/edit/'.$this->uri->segment(4) ; ?>">
                                   
                                <div class="form-group ">
                                    <label for="f_name" class="control-label col-lg-3">First Name:</label>
                                    <div class="col-lg-6">
                                        <input class=" form-control" id="firstname" required name="firstname" value="<?php echo isset($user_det[0]['first_name']) ? $user_det[0]['first_name'] :'' ; ?>" type="text"/>
                                     </div>
                                </div>
                                
                                <div class="form-group ">
                                    <label for="l_name" class="control-label col-lg-3">Last Name:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="lastname" required name="lastname" value="<?php echo isset($user_det[0]['last_name']) ? $user_det[0]['last_name'] :''; ?>" type="text"/>
                                        </div>
                                </div>
                                                    
                                <div class="form-group ">
                                        <label for="a_email" class="control-label col-lg-3">Email:</label>
                                        <div class="col-lg-6">
                                           <input type="hidden" id="hdn_email" name="hdn_email" value=''>
                                            <input class=" form-control" required maxlength="40" id="a_email" name="a_email" value="<?php echo isset($user_det[0]['email']) ? $user_det[0]['email'] : '' ;?> " type="email" onkeyup="email_check();" onblur="email_check();" />
                                            <div class="error_a_email" id="error_a_email" style="color: #B94A48;"></div>
                                        </div>
                                </div>
                                
								<div class="form-group last">
								    <label class="control-label col-md-3">Profile Image</label>
										<div class="col-md-9">
											<div class="fileupload" data-provides="fileupload">
												<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
												<?php  $profile_image = isset($user_det[0]['profile_image']) ? $user_det[0]['profile_image'] : '';
												if($profile_image !='')
												{
												?>
												 <img src="<?php echo base_url();?>assets/uploads/user_images/thumb/<?php echo $profile_image;?>">
												<?php
												}
												?>
												</div>
											
											<div>
												<span class="btn btn-white btn-file">
												<span><i class="fa fa-paper-clip"></i> Upload Profile image</span>
												<input type="file" class="default" name="user_image" id="user_image" />
												</span>
																				
													 <label  id="img_error" class="error" style="display: none;" for="profile_image">Please upload an image</label>
													 <label  id="image_error" class="error" style="display: none;" for="profile_image">Please upload an valid image</label>
											</div>
											</div>
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
                                                <option value="1" <?php if(isset($user_det[0]['status']) && $user_det[0]['status']=='1'){?> selected="selected"  <?php }?>>Active</option>
                                                <option value="0" <?php if(isset($user_det[0]['status']) && $user_det[0]['status']=='0'){?> selected="selected"  <?php }?>>Inactive</option>
                                            </select>
                                            <div id='error_message'></div>
                                        </div>
                                </div>
				
				
			
				<div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-6">
                                        <button class="btn btn-primary" type="submit">Save</button>
                                        <button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-users';">Cancel</button>
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
	 
		$(document).ready(function()
		{
			jQuery.validator.addMethod("regex", function(value, element, param)
		        {
			if(value.search(/\S/) != -1)
			 return value.match(new RegExp("." + param + "$"));
			else
			return true;
		     
		        });
			
			$("#edituser").validate({
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
			
						  password: {
									  minlength: 6
								},
						  cpassword: {
									   minlength: 6,
									   equalTo:  "#password"
								},
						  user_image:  {
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
				
					   //a_email: 'Please enter your email-id',
					   password:  {
								    minlength: "Your password must be at least 5 characters long"
								},
					   cpassword:  {
								    minlength: "Your password must be at least 5 characters long",
								    equalTo: "Please enter the same password as above"
								},			
					   user_image: {
								    //required: 'Please upload an image',
								    accept:    'Please provide a valid image (JPG,JPEG,BMP,GIF,PDF,PNG)',
								}
				    },
				     submitHandler: function(form) {
			 
					var hdn_email = $('#hdn_email').val();
					$("#error_a_email").css({ 'display': "none","color":"#b94a48" });
					       if(hdn_email=="no")
					       {    $("#error_a_email").show(); 
						   $("#error_a_email").html("This Email id already exists");   
					       }
					       else
					       {
						    $("#edituser").submit();	
						       
					       }
			          }
				    
			});
		});

    
    function email_check()
    {
	var user_id = '<?php echo strval($this->uri->segment(4));  ?>';
        var email=$("#a_email").val();
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if(email.search(/\S/)!=-1)
        {
            if(ck_email.test(email))
            {
                   
                    $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('control/manage-users/user_name_chk'); ?>",
                    data: {user_id: user_id,email: email},
                    cache: false,
                    success: function(data)
                    {  //alert(data);
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