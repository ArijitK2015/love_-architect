<section id="main-content">
        <section class="wrapper">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Add User
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                <form class="cmxform form-horizontal " name="adduser" id="adduser" method="post" enctype="multipart/form-data"  action="<?php echo site_url("control").'/manage-users/add'; ?>">
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
                                            <input class=" form-control" required  maxlength="40" id="a_email" name="a_email" value="" type="email" onkeyup="email_check();" onblur="email_check();" />
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
										   <input type="file" class="default" required name="user_image" id="user_image" />
										   </span>
																		   
								<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
										<label  id="img_error" class="error" style="display: none;" for="flag">Please upload an image</label>
										<label  id="image_error" class="error" style="display: none;" for="flag">Please upload a valid image</label>
									</div>
									</div>
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
									
						<div class="form-group">
							<div class="col-lg-offset-3 col-lg-6">
							       <button class="btn btn-primary" type="submit" id="add">Add</button>
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
<script type="text/javascript">
		
	   
	   $(document).ready(function()
	   {
		 jQuery.validator.addMethod("regex", function(value, element, param) {
              if(value.search(/\S/) != -1)
               return value.match(new RegExp("." + param + "$"));
              else
              return true;
           
           });
		
		   $("#adduser").validate({
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
							 required: true,
							 minlength: 6,
						  },
				cpassword: {
							 required: true,
							 minlength: 6,
							 equalTo:  "#password",
						  },
				 user_image:    {
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
				
				 password:  {
								required: "Please provide a password",
								minlength: "Your password must be at least 6 characters long",
						  },
							 
				 cpassword:  {
								required: "Please provide a password again",
								minlength: "Your password must be at least 6 characters long",
								equalTo: "Please enter the same password as above",
						  },			
							 
				 user_image:  {
								required: 'Please upload an image',
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
				else if(hdn_email=="yes")
			        {
				     $("#adduser").submit();	
					
				}
			 }
		   });
	   });


    
    function email_check()
    {
        var email=$("#a_email").val();
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if(email.search(/\S/)!=-1)
        {
            if(ck_email.test(email))
            {
                    //var dataString = "email=" + email;
                    $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('control/manage-users/user_name_chk'); ?>",
                    data: {email: email },
                    cache: false,
                    success: function(data)
                    {       //alert(data);
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

	
