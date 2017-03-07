	<?php
		//For first time we need to show the email section for validation and second portion we need to show the other part.
		$do_hide1 = (isset($new_pass) && ($new_pass == 1)) ? 'style="display: none"' : '';
		$do_hide2 = (isset($new_pass) && ($new_pass == 1)) ? '' : 'style="display: none"';
		
		$flash_message = $this->session->flashdata('flash_message');
		$flash_message_cont = $this->session->flashdata('flash_message_cont');
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
	?>
	
	<!-- Adding validation js -->
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
	
	<!-- login screen -->
	<div data-role="page">
		<div data-role="main" class="ui-content login-content">
			<div id="forgotpassword" <?php echo $do_hide1 ?> >
				<a data-ajax="false" href="<?php echo base_url() ?>" class="cancel-signup ui-link">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="18.531" height="18.469" viewBox="0 0 18.531 18.469">
						<path d="M10.950,9.191 L18.215,16.449 C18.653,16.887 18.628,17.627 18.160,18.095 C17.692,18.563 16.951,18.588 16.513,18.150 L9.248,10.892 L1.996,18.137 C1.560,18.572 0.824,18.547 0.359,18.082 C-0.106,17.617 -0.131,16.882 0.304,16.447 L7.556,9.202 L0.370,2.023 C-0.068,1.586 -0.043,0.845 0.425,0.377 C0.893,-0.091 1.635,-0.115 2.073,0.322 L9.258,7.501 L16.344,0.422 C16.779,-0.012 17.516,0.012 17.981,0.477 C18.446,0.942 18.471,1.678 18.036,2.112 L10.950,9.191 Z" class="cls-1"></path>
					</svg>
				</a>
				
				<?php
					if(!empty($site_logo))
						echo '<a data-ajax="false" href="'.base_url().'" class="logo"><img src="'.assets_url('uploads/merchant_images/thumb/'.$site_logo).'" alt="logo" /></a>';
					else
						echo '<a data-ajax="false" href="'.base_url().'" class="logo"><img src="'.assets_url('site/images/'.$default_site_logo).'" alt="logo" /></a>';
				?>
				<!--<a data-ajax="false" href="<?php echo base_url(); ?>" class="logo"><img src="<?php echo base_url(); ?>assets/site/images/logo.png" alt="logo" /></a>-->
				
				<form id="forgot-pass" action="<?php echo base_url().'forgot-password'; ?>" method="post" data-ajax="false">
					<input type="hidden" name="action_type" id="action_type" value="forgot_password" />
					<input type="hidden" name="cmp_auth_id" id="cmp_auth_id" value="<?php echo isset($cmp_auth_id) ? $cmp_auth_id : ''; ?>" />
					<div class="select-profile">
						<h3>Forgot Password</h3>
					</div>
					<div class="login-form">
						<div class="login-form-row input-row">
							<label>Email</label>
							<input type="email" name="email" id="email" autocomplete="off" class="login-controls required email" value="" placeholder="info@hotcargo.com" onclick="do_clear_fld(1, 'email')" onblur="do_clear_fld(0, 'email')" data-role="none" />
						</div>
						<label id="email-error" class="error" for="email"></label>
					
						<div class="login-form-row submit-row">
							<input type="submit" value="Validate Email Id" class="submit-btn" data-role="none" />
						</div>
					</div>
				</form>
			</div>
		
			<div id="validatepassword" <?php echo $do_hide2 ?>>
				<?php
					if(!empty($site_logo))
						echo '<a data-ajax="false" href="'.base_url().'" class="logo"><img src="'.assets_url('uploads/merchant_images/thumb/'.$site_logo).'" alt="logo" /></a>';
					else
						echo '<a data-ajax="false" href="'.base_url().'" class="logo"><img src="'.assets_url('site/images/'.$default_site_logo).'" alt="logo" /></a>';
				?>
				<form id="login-form" action="<?php echo base_url().'update-password'; ?>" method="post" data-ajax="false">
					
					<input type="hidden" name="action_type" id="action_type" value="validate_password" />
					<input type="hidden" name="user_id" 	id="user_id" 		value="<?php echo isset($user_id) ? $user_id : '0'; ?>" />
					<input type="hidden" name="cmp_auth_id" id="cmp_auth_id" value="<?php echo isset($cmp_auth_id) ? $cmp_auth_id : ''; ?>" />
				
					<div class="login-form">
						<div class="login-form-row input-row">
							<label>Verify code</label>
							<input type="text" name="verify_code" id="verify_code" readonly="readonly" autocomplete="off" class="login-controls required" value="<?php echo isset($verify_code) ? $verify_code : ''; ?>" placeholder="Enter verification code" data-role="none" onclick="do_clear_fld(1, 'verify_code')" onblur="do_clear_fld(0, 'verify_code')" />
						</div>
						<label id="new_password-error" class="error" for="new_password"></label>
						
						<div class="login-form-row input-row">
							<label>New Password</label>
							<input type="password" name="new_password" id="new_password" autocomplete="off" class="login-controls required" value="" placeholder="Enter new password" data-role="none" onclick="do_clear_fld(1, 'new_password')" onblur="do_clear_fld(0, 'new_password')" />
						</div>
						<label id="new_password-error" class="error" for="new_password"></label>
						
						<div class="login-form-row input-row">
							<label>Confirm Password</label>
							<input type="password" name="confirm_password" id="confirm_password" autocomplete="off" class="login-controls required" value="" placeholder="Enter password again" data-role="none" onclick="do_clear_fld(1, 'confirm_password')" onblur="do_clear_fld(0, 'confirm_password')" />
						</div>
						<label id="confirm_password-error" class="error" for="confirm_password"></label>
					
						<div class="login-form-row submit-row">
							<input type="submit" value="Submit" class="submit-btn" data-role="none" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="error-section" class="ui-loader ui-corner-all ui-body-a ui-loader-verbose ui-loader-textonly hide">
		<span class="ui-icon-loading"></span>
		<h1 id="error_msg">Error Loading Page</h1>
	</div>
