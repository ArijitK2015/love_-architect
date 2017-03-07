<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for login.
|--> 
<!DOCTYPE html>
  <?php
		$do_hide1 = (isset($new_pass) && ($new_pass == 1)) ? 'style="display: none"' : '';
		$do_hide2 = (isset($new_pass) && ($new_pass == 1)) ? '' : 'style="display: none"';
		
		$flash_message = $this->session->flashdata('flash_message');
		$flash_message_cont = $this->session->flashdata('flash_message_cont');
		if(isset($site_fabicon) && !empty($site_fabicon))
		{
			$favicon_img =$site_fabicon;
		}
		else
		{
			$favicon_img	= (isset($settings[0]['site_fabicon'])) 	? $settings[0]['site_fabicon'] : 'favicon.ico';
		}
		
  ?>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo (isset($pdesc) && $pdesc!='') ? ucfirst($pdesc) : $settings[0]['meta_description']; ?>">
	<meta name="keywords" 	content="<?php echo (isset($pkeys) && $pkeys!='') ? ucfirst($pkeys) : $settings[0]['meta_keywords']; ?>">
    <meta name="author" content="Esolz technologies">
    <!--<link rel="shortcut icon" href="images/favicon.png">-->

	<title> <?php echo (isset($ptitle) && ($ptitle != '')) ? ucfirst($ptitle) : ucfirst($settings[0]['site_name']).' - Admin'; ?> </title>
    <!--<title><?php echo (isset($settings[0]['site_name'])) ? ucfirst($settings[0]['site_name']) : ''; ?> </title>-->
	
	<!--Site logo-->
	<link rel="icon" href="<?php echo assets_url();?>site/images/<?php echo $favicon_img; ?>" type="image/x-icon">
		
    <!--Core CSS -->
    <link href="<?php echo assets_url();?>admin/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo assets_url();?>admin/css/bootstrap-reset.css" rel="stylesheet">
    <link href="<?php echo assets_url();?>admin/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="<?php echo assets_url();?>admin/css/style.css" rel="stylesheet">
    <link href="<?php echo assets_url();?>admin/css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
   
	<script src="<?php echo assets_url();?>admin/js/jquery.js"></script>
	<script src="<?php echo assets_url();?>admin/bs3/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo assets_url();?>admin/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<script src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>
	<script>
		var new_pass = '<?php echo $new_pass; ?>';
		$(document).ready(function() {
			var flash_msg = '<?php echo $flash_message ?>', error_msg_cont = '<?php echo $flash_message_cont; ?>',  error_msg = '';
			if (flash_msg != '') {
				if (flash_msg 		== 'forget_pass_error') 	error_msg = '<span class="error">Sorry, No user found associated with this email id. Please try again with actual one.</span>';
				else if (flash_msg 	== 'forget_pass_error_user_adact') error_msg = '<span class="error">Sorry, This user is deactivated or blocked by admin. Please contact administrator for further access.</span>';
				else if (flash_msg 	== 'forget_pass_error_user_dact')  error_msg = '<span class="error">Sorry, This user has closed the account.</span>';
				else if (flash_msg 	== 'forget_pass_verify_code_error')error_msg = '<span class="error">Sorry, Wrong verification code. Please try again.</span>';
				else if (flash_msg 	== 'pass_updated_error')			error_msg = '<span class="error">Sorry, Failed to update the password. Please try again.</span>';
				
				if (error_msg != '') {
					$("#error_msg").html(error_msg);
					$("#error-section").show();
				}
				
				if (error_msg_cont != '') $("#email").val(error_msg_cont);
			}
			
			setTimeout(function(){
				$("#error-section").hide();
				$("#error_msg").html('');
			}, 5000);
			
			$("#forgot-pass").validate();
		});
		
		function do_signup(args) {
			var reg_type = '';
			var els = document.getElementsByName('selectProfile');
			for (var i = 0; i < els.length; i++)
				if ( els[i].checked ) reg_type = els[i].value;
			
			if (reg_type != '')
				window.location = '<?php echo base_url().'sign-up/' ?>'+reg_type;
		}
		
		function do_clear_fld(args, fld_id) {
			var place_txt = '';
			if (fld_id == 'email')  place_txt = 'info@hotcargo.com';
			else if (fld_id == 'new_password') 	place_txt = 'Enter New Password';
			else if (fld_id == 'confirm_password') 	place_txt = 'Enter Password Again';
			
			if (args == 1)	$('#'+fld_id).attr('placeholder', '');
			else			$('#'+fld_id).attr('placeholder', place_txt);
		}
	</script>
<style>
 .logo_admin{
  
	padding: 25px 0;
	margin: 0 auto;
	width: 170px;
	display: block;
  
 }
 .form_marg_tp{
	margin-top: 40px;
 }  
</style>
</head>
  <body class="login-body">
    <div class="container">
	<?php
	//echo "<pre>";
	//print_r($site_logo);die;
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
	?>
	<?php
		if(!empty($site_logo))
		{
	  ?>
			<a href="<?php echo base_url(); ?>control"><img class="logo_admin" src="<?php echo assets_url('uploads/merchant_images/thumb/'.$site_logo);?>" alt="logo" /></a>
		<?php
		}
		else
		{
		?>
			<a href="<?php echo base_url(); ?>control"><img class="logo_admin" src="<?php echo assets_url('site/images/'.$default_site_logo);?>" alt="logo" /></a>
	<?php
		}
		
	?>
			
	<div id="forgotpassword" <?php echo $do_hide1 ?> >
		<form id="forgot-pass" class="form-signin form_marg_tp" method="post" action="<?php echo base_url(); ?>control/admin-forgot-password">
<!--<form id="sign_in" class="form-signin" method="post" action="" onsubmit="return user_check()">-->
		  <input type="hidden" name="action_type" id="action_type" value="forgot_password" />
		  <input type="hidden" name="cmp_auth_id" id="cmp_auth_id" value="<?php echo isset($cmp_auth_id) ? $cmp_auth_id : ''; ?>" />
        <h2 class="form-signin-heading">Forgot Password ?</h2>
		<?php
		  //flash messages
		  //if(isset($message_error)){
		  //	$flash_message=$message_error;
		  //}
		  $flash_message=$this->session->flashdata('flash_message');
		  if(isset($flash_message)){
	  
			  if($flash_message == 'forget_pass_success'){
			  echo'<div class="alert alert-success">';
			  echo'<strong>Success!</strong>&nbsp;Please check your email to change the password. ';        
			  echo'</div>';
		  
			  }
			  if($flash_message == 'forget_pass_error_user_dact'){
			  echo'<div class="alert alert-danger">';
			  echo'<strong>Error!</strong>&nbsp;Invalid user. ';        
			  echo'</div>';
		  
			  }
			  if($flash_message == 'forget_pass_verify_code_error'){
			  echo'<div class="alert alert-danger">';
			  echo'<strong>Error!</strong>&nbsp;Wrong verification code. Please try again. ';        
			  echo'</div>';
		  
			  }
				  
		  }
	  ?>
        <div class="login-wrap">
            <div class="user-login-info">
                <input type="text" class="form-control" name="email" id="email" value="" placeholder="info@hotcargo.com" required>
				<label id="email-error" class="error" for="email" style="color: #FF0000;"></label>
            </div>
            <button class="btn btn-lg btn-login btn-block" type="submit">Validate Email Id</button>
        </div>
	  
      </form>
	</div>
	
	  <div id="validatepassword" <?php echo $do_hide2 ?> >
		<form id="login-form" class="form-signin form_marg_tp" method="post" action="<?php echo base_url().'control/admin-update-password'; ?>" method="post" onsubmit="return check_pass();">
<!--<form id="sign_in" class="form-signin" method="post" action="" onsubmit="return user_check()">-->
		  <input type="hidden" name="action_type" id="action_type" value="validate_password" />
		  <input type="hidden" name="user_id" 	id="user_id" 		value="<?php echo isset($user_id) ? $user_id : '0'; ?>" />
		  <input type="hidden" name="cmp_auth_id" id="cmp_auth_id" value="<?php echo isset($cmp_auth_id) ? $cmp_auth_id : ''; ?>" />
        <h2 class="form-signin-heading">Forgot Password ?</h2>
		<?php
		  //flash messages
		  //if(isset($message_error)){
		  //	$flash_message=$message_error;
		  //}
		  $flash_message=$this->session->flashdata('flash_message');
		  if(isset($flash_message)){
	  
			  if($flash_message == 'forget_pass_success'){
			  echo'<div class="alert alert-success">';
			  echo'<strong>Success!</strong>&nbsp;Please check your email to change the password. ';        
			  echo'</div>';
		  
			  }
			  if($flash_message == 'forget_pass_error_user_dact'){
			  echo'<div class="alert alert-danger">';
			  echo'<strong>Error!</strong>&nbsp;Invalid user. ';        
			  echo'</div>';
		  
			  }
			  if($flash_message == 'forget_pass_verify_code_error'){
			  echo'<div class="alert alert-danger">';
			  echo'<strong>Error!</strong>&nbsp;Wrong verification code. Please try again. ';        
			  echo'</div>';
		  
			  }
			  if($flash_message == 'pass_updated_error'){
			  echo'<div class="alert alert-danger">';
			  echo'<strong>Error!</strong>&nbsp;Failed to update the password. Please try again.';        
			  echo'</div>';
		  
			  }
				  
		  }
	  ?>
        <div class="login-wrap">
            <div class="user-login-info">
				<div>
				  <label>Verify Code</label>
				  <input type="text" name="verify_code" id="verify_code" readonly="readonly" autocomplete="off" class="form-control required" value="<?php echo isset($verify_code) ? $verify_code : ''; ?>" placeholder="Enter verification code" data-role="none" onclick="do_clear_fld(1, 'verify_code')" onblur="do_clear_fld(0, 'verify_code')" />
				</div>
				<div>
				  <label>New Password</label>
				  <input type="password" name="new_password" id="new_password" autocomplete="off" class="form-control required" value="" placeholder="Enter new password" data-role="none" onclick="do_clear_fld(1, 'new_password')" onblur="do_clear_fld(0, 'new_password')" />
				  <label id="new_password-error" class="error" for="new_password" style="font-weight: 400;color: red;"></label>
				</div>
				<div>
				  <label>Confirm Password</label>
				  <input type="password" name="confirm_password" id="confirm_password" autocomplete="off" class="form-control required" value="" placeholder="Enter password again" data-role="none" onclick="do_clear_fld(1, 'confirm_password')" onblur="do_clear_fld(0, 'confirm_password')" />
				  <label id="confirm_password-error" class="error" for="confirm_password" style="font-weight: 400;color: red;"></label>
				</div>
            </div>
            <button class="btn btn-lg btn-login btn-block" type="submit">Submit</button>
        </div>
	  
      </form>
	</div>

		<div class="page_load" style="display:none">Page rendered in <strong>{elapsed_time}</strong> seconds.</div>
	</div>
<script>
  
  function check_pass() {
	
	var new_pass = $('#new_password').val();
	var confirm_password = $('#confirm_password').val();
	
	$('#new_password-error').hide();
	$('#confirm_password-error').hide();
	if (new_pass =='') {
		$('#new_password-error').html('Please Enter New Password');
		$('#new_password-error').show();
		return false;
	}
	if (new_pass =='') {
		$('#new_password-error').html('Please Enter New Password');
		$('#new_password-error').show();
		return false;
	}
	if (confirm_password =='') {
		$('#confirm_password-error').html('Please Enter Confirm Password');
		$('#confirm_password-error').show();
		return false;
	}
	if (new_pass != confirm_password) {
		$('#confirm_password-error').html('New and confirm password should be same');
		$('#confirm_password-error').show();
		return false;
	}
	
  }
  
</script>
  </body>
</html>
