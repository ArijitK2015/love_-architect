<!--|
| Copyright © 2015 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for change passwords.
|--> 
<section id="main-content">
        <section class="wrapper">

		<?php
			//flash messages
			$flash_message=$this->session->flashdata('flash_message');
			if(isset($flash_message)){
		
			if($flash_message == 'pwd_updated')
			{
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Password has been successfully updated.';
				echo '</div>';
			
			}
			if($flash_message == 'pwd_not_updated'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
				echo'</div>';
		
			}
		
			if($flash_message == 'error'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> . Please try again.';        
				echo'</div>';
		
			}
			if($flash_message == 'wrong_password'){
				echo'<div class="alert alert-danger">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> . Old password is wrong.';        
				echo'</div>';
			}
				
			}
		?>
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Change Password
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                <form class="cmxform form-horizontal " id="chngPass_new" method="post" action="<?php echo base_url(); ?>control/change-password/updt">
								   <div class="form-group ">
                                        <label for="firstname" class="control-label col-lg-3">Old Password:</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" autocomplete="off" required type="password" name="old_pass" id="old_pass" />
                                        </div>
                                    </div>
								
                                    <div class="form-group ">
                                        <label for="firstname" class="control-label col-lg-3">New Password:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" autocomplete="off" required type="password" name="npass" id="npass" />
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="lastname" class="control-label col-lg-3">Confirm password:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" autocomplete="off" required type="password" name="cpass" id="cpass" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-offset-3 col-lg-6">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                            <button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/dashboard';">Cancel</button>
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
		$(document).ready(function(){
			$("#chngPass_new").validate({
				rules: {
					old_pass:
					{
						required: true,
					},
					npass:   {
								required: true,
								minlength: 6
							  },
					cpass:      {
								required: true,
								minlength: 6,
								equalTo:  "#npass"
							  }
				},
				messages: {
					old_pass:
					{
						required:'Please enter your old password',
					},
					npass:    {
								required: "Please provide a password",
								minlength: "Your password must be at least 6 characters long"
							},
					cpass:      {
								required: "Please provide a password again",
								minlength: "Your password must be at least 6 characters long",
								equalTo: "Please enter the same password as above"
							  }
				}
			});
		})
	</script>