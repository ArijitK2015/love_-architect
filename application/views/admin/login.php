<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for login.
|--> 
	<?php
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
		<script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		<script src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>
			
		<script>
			function check_signup(args) {
					
				var username = $("#user_name").val();
				var password = $("#password").val();
					
				if(username.search(/\S/) == -1){
					$("#user_name-error").html('Please enter the username.');
					return false;
				}
				else $("#user_name-error").html('');
					
				if(password.search(/\S/) == -1){
					$("#password-error").html('Please enter the password.');
					return false;
				}
				else{
					if (password.length < 6){
						$("#password-error").html('Please enter minimum six digits.');
						return false;
					}
					else $("#password-error").html('');
				}
					
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
					
				$default_site_logo 		= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
					
				if(!empty($site_logo)) { ?>
					<a href="<?php echo base_url(); ?>control"><img class="logo_admin" src="<?php echo assets_url('uploads/merchant_images/thumb/'.$site_logo);?>" alt="logo" /></a>
				<?php } else { ?>
					<a href="<?php echo base_url(); ?>control"><img class="logo_admin" src="<?php echo assets_url('site/images/'.$default_site_logo);?>" alt="logo" /></a>
				<?php } ?>
				
			<div id="sign_in_div">
				<form class="form-signin form_marg_tp" onsubmit="check_signup()" method="post" action="<?php echo base_url(); ?>control/login/validate_credentials">
					<!--<form id="sign_in" class="form-signin" method="post" action="" onsubmit="return user_check()">-->
					<h2 class="form-signin-heading">sign in now</h2>
					<?php
						$flash_message	= $this->session->flashdata('flash_message');
							
						if(isset($flash_message)){
								
							if($flash_message == 'not_valid'){
								echo'<div class="alert alert-error">';
								echo'<strong>Error!</strong>&nbsp;Please enter correct Username and Password. ';        
								echo'</div>';
							}
							if($flash_message == 'pass_updated'){
								echo'<div class="alert alert-success">';
								echo'<strong>Success!</strong>&nbsp;Your password has successfully changed. ';        
								echo'</div>';
							}
						}
					?>
						
					<div class="login-wrap">
						<div class="user-login-info">
							<div id="otp_wrong" style="display: none">
								
							</div>
							<div id="pass_wrong" style="display: none">
								
							</div>
							<input type="text" class="form-control" name="user_name" id="user_name" placeholder="User ID" autofocus required>
							<label id="user_name-error" class="error" for="user_name" style="color: #FF0000;"></label>
							<input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
							<label id="password-error" class="error" for="password" style="color: #FF0000;"></label>
						</div>
						<?php if($is_merchant == '1') { ?>
							<a href="<?php echo base_url();?>control/admin-forgot-password">Forgot Password ?</a>
						<?php } ?>
						<button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
					</div>
					
					<!-- Modal -->
					<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Forgot Password ?</h4>
								</div>
								<div class="modal-body">
									<p>Enter your e-mail address below to reset your password.</p>
									<input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
								</div>
								<div class="modal-footer">
									<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
									<button class="btn btn-success" type="button">Submit</button>
								</div>
							</div>
						</div>
					</div>
					<!-- modal -->
				</form>
			</div>
			<div class="page_load" style="display:none">Page rendered in <strong>{elapsed_time}</strong> seconds.</div>
		</div>
			
		<div id="one_time_instant" style="display: none">
			<form   class="form-signin" method="post" action="<?php echo base_url(); ?>control/login/validate_credentials">
				<h2 class="form-signin-heading">sign in now</h2>
					
				<div class="login-wrap">
					<div class="user-login-info">
						<p>Please enter one time password</p>
						
						<input type="hidden" class="form-control" id="user_id" name="user_id" placeholder="" value="" >
						<input type="password" class="form-control" id="one_time_password" name="one_time_password" placeholder="One time password" autofocus>
						<a href="javascript:void(0);" onclick="resend_data();" name="id" id="id">
							Resend Code
						</a>
					</div>
					<button class="btn btn-lg btn-login btn-block" type="submit" >Validate</button>
				</div>
			</form>
		</div>
			
		<script>
				
			function user_check(){
				var user_name=document.getElementById('user_name').value;
				var password=document.getElementById('password').value;
				$.ajax({
					url: '<?php echo base_url().'user/pre_validation';?>',
					type: 'GET',
					cache: false,
					data: {
						username: user_name,
						password1:password
					},
					success: function(data){
						if (data > 0) {
							$("#sign_in_div").hide();
							$("#one_time_instant").show();
							$("#pass_wrong").hide();
							$('#user_id').val(data);
							$('#id').val(data);
						}
						else
						{
							$("#pass_wrong").show();
							$("#pass_wrong").html('Please enter correct Username and Password');
							//alert("Please enter correct Username and Password");
							$("#sign_in_div").show();
							$("#one_time_instant").hide();
						   
						}
					}
				});
					
				return false;
			}
				
			function   resend_data(){
				var id=document.getElementById('id').value;
					$.ajax({
					url: '<?php echo base_url().'user/pre_resend_code';?>',
					type: 'GET',
					cache: false,
					data: {
						users_id:id,
					},
					success: function(data){
						if (data > 0) {
							$("#sign_in_div").hide();
							$("#one_time_instant").show();
							$('#user_id').val(data);
							$('#id').val(data);
						}
						else
						{
							
							$("#otp_wrong").show();
							$("#otp_wrong").html('Please enter correct otp code');
							//alert("Please enter correct Username and Password");
							$("#sign_in_div").show();
							$("#one_time_instant").hide();
						   
						}
					}
				});
					
				return false;
			}
		</script>
	</body>
</html>
