	<script src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>
	<?php
		$flash_message 	= $this->session->flashdata('flash_message');
		$flash_message_cont = $this->session->flashdata('flash_message_cont');
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
	?>	
	<script>
		$(document).ready(function() {
			var flash_msg = '<?php echo $flash_message ?>', error_msg = '', error_msg_cont = '<?php echo $flash_message_cont; ?>';
			if (flash_msg != '') {
				if (flash_msg == 'user_det_error') 		error_msg = '<span class="error">User details not found. Please try again with different user.</span>';
				else if (flash_msg == 'user_status_failed') 	error_msg = '<span class="error">User is not activated yet. Please activate and try again.</span>';
				else if (flash_msg == 'user_not_exist') 	error_msg = '<span class="error">Invalid credentials. Please try again.</span>';
				else if (flash_msg == 'error') 			error_msg = '<span class="error">Error occured. Please try again.</span>';
				
				else if (flash_msg == 'email_exist') 		error_msg = '<span class="error">Email-id already exist. Please try again with another id.</span>';
				else if (flash_msg == 'reg_error') 		error_msg = '<span class="error">Failded to register. Please try again.</span>';
				else if (flash_msg == 'reg_success') 		error_msg = '<span class="success">Registration successful.</span>';
				else if (flash_msg == 'forget_pass_success') error_msg = '<span class="success">We have sent you an email containing the link to change the password.</span>';
				else if (flash_msg == 'pass_updated') 		error_msg = '<span class="success">Password successfully updated.</span>';
				
				if (error_msg != '') {
					$("#error_msg").html(error_msg);
					$("#error-section").show();
				}
			}
			
			setTimeout(function(){
				$("#error-section").hide();
				$("#error_msg").html('');
			}, 5000);
			
			if (error_msg_cont != '') $("#email").val(error_msg_cont);
			
			$("#login-form").validate();
			$("#signup-form").validate();
		});
		
		function show_password(args) {
			var cur_pass_type = $('#password').attr('type');
			console.log(cur_pass_type);
			if (cur_pass_type == 'text') {
				$('#password').attr('type', 'password');
				$("#pass_view").html('<i class="fa fa-eye" aria-hidden="true"></i>');
			}
			else{
				$('#password').attr('type', 'text');
				$("#pass_view").html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
			}
		}
		
		function show_password_reg(args, show_type) {
			var cur_pass_type = $('#password_'+show_type).attr('type');
			console.log(cur_pass_type);
			if (cur_pass_type == 'text') {
				$('#password_'+show_type).attr('type', 'password');
				$("#password_"+show_type+'_show').html('<i class="fa fa-eye" aria-hidden="true"></i>');
			}
			else{
				$('#password_'+show_type).attr('type', 'text');
				$("#password_"+show_type+'_show').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
			}
		}
		
		function do_signup(args, cmp_id) {
			var reg_type = '';
			var els = document.getElementsByName('selectProfile');
			for (var i = 0; i < els.length; i++)
				if ( els[i].checked ) reg_type = els[i].value;
			
			if (reg_type != '')
				window.location = '<?php echo base_url().'sign-up/' ?>'+reg_type;
		}
		
		function linkedin_redirect(args) {
			var reg_type = '';
			var els = document.getElementsByName('selectProfile');
			
			for (var i = 0; i < els.length; i++)
				if ( els[i].checked ) reg_type = els[i].value;
			
			if (reg_type != '')
				window.location = '<?php echo base_url().'linkdin-auth/' ?>'+reg_type;
		}
		
		function do_clear_fld(args, fld_id) {
			var place_txt = '';
			if (fld_id == 'email')  place_txt = 'info@hotcargo.com';
			else if (fld_id == 'password') place_txt = '******';
			
			if (args == 1)	$('#'+fld_id).attr('placeholder', '');
			else			$('#'+fld_id).attr('placeholder', place_txt);
		}
	</script>
	
	<!-- login screen -->
	<div data-role="page" id="loginPage">
		<div data-role="main" class="ui-content login-content">
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
			
			<form id="login-form" action="<?php echo base_url().'login-validate'; ?>" method="post" data-ajax="false">
				<input type="hidden" name="action_type" id="action_type" value="login" />
				<input type="hidden" name="cmp_auth_id" id="cmp_auth_id" value="<?php echo isset($cmp_auth_id) ? $cmp_auth_id : ''; ?>" />
			
				<div class="select-profile">
					<h3>Select Your Profile</h3>
					<div class="select-profile-check">
						<input type="radio" name="selectProfile" value="customer" id="customer" data-role="none" checked/>
						<label for="customer">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="53" height="49" viewBox="0 0 53 49">
								<path d="M41.206,48.856 C41.067,48.950 40.909,48.995 40.753,48.995 C40.489,48.995 40.231,48.867 40.073,48.630 C39.823,48.252 39.924,47.742 40.299,47.490 C47.236,42.828 51.377,35.044 51.377,26.666 C51.377,12.868 40.222,1.643 26.509,1.643 C12.796,1.643 1.640,12.868 1.640,26.666 C1.640,35.044 5.782,42.829 12.719,47.490 C13.094,47.742 13.195,48.252 12.945,48.630 C12.694,49.007 12.186,49.108 11.812,48.856 C4.420,43.890 0.006,35.595 0.006,26.666 C0.006,11.962 11.895,-0.001 26.509,-0.001 C41.122,-0.001 53.011,11.962 53.011,26.666 C53.011,35.594 48.598,43.890 41.206,48.856 ZM20.304,15.321 C20.304,11.873 23.082,9.078 26.509,9.078 C29.936,9.078 32.714,11.873 32.714,15.321 C32.714,18.770 29.936,21.565 26.509,21.565 C23.082,21.565 20.304,18.770 20.304,15.321 ZM18.074,35.812 L19.769,43.357 C16.539,42.383 13.315,41.266 12.827,40.776 C11.872,39.816 13.304,28.289 13.782,26.848 C14.404,24.970 22.851,23.006 22.851,23.006 C22.851,25.888 24.241,27.281 26.509,27.513 C28.778,27.281 30.167,25.888 30.167,23.006 C30.167,23.006 38.613,24.970 39.236,26.848 C39.713,28.289 41.145,39.816 40.190,40.776 C39.673,41.297 36.070,42.522 32.651,43.536 L34.923,35.815 C35.526,35.754 35.998,35.248 35.998,34.626 C35.998,33.962 35.464,33.425 34.805,33.425 L27.039,33.425 C27.019,33.402 27.001,33.379 26.978,33.359 L22.118,29.123 C21.818,28.862 21.367,28.895 21.107,29.195 C20.848,29.496 20.881,29.950 21.180,30.212 L24.866,33.425 L18.212,33.425 C17.553,33.425 17.019,33.962 17.019,34.626 C17.019,35.241 17.481,35.742 18.074,35.812 ZM21.217,36.396 C21.597,36.289 21.992,36.516 22.097,36.900 L23.683,42.714 C23.788,43.098 23.563,43.495 23.182,43.600 C23.119,43.617 23.055,43.626 22.992,43.626 C22.677,43.626 22.389,43.416 22.302,43.096 L20.716,37.282 C20.611,36.898 20.835,36.502 21.217,36.396 ZM26.068,34.713 C26.068,34.468 26.266,34.270 26.509,34.270 C26.751,34.270 26.949,34.468 26.949,34.713 C26.949,34.957 26.751,35.156 26.509,35.156 C26.266,35.156 26.068,34.957 26.068,34.713 ZM26.261,36.264 C26.657,36.264 26.977,36.586 26.977,36.983 L26.977,42.695 C26.977,43.092 26.657,43.415 26.261,43.415 C25.866,43.415 25.545,43.092 25.545,42.695 L25.545,36.983 C25.545,36.586 25.866,36.264 26.261,36.264 ZM30.452,36.900 C30.557,36.516 30.949,36.289 31.332,36.396 C31.714,36.502 31.938,36.898 31.833,37.282 L30.247,43.096 C30.160,43.416 29.871,43.626 29.557,43.626 C29.494,43.626 29.431,43.617 29.367,43.600 C28.985,43.495 28.762,43.098 28.866,42.714 L30.452,36.900 Z" class="cls-1"/>
							</svg>
							<span>Customer</span>
						</label>
						<input type="radio" name="selectProfile" value="driver" id="driver" data-role="none" <?php echo (isset($cookie_user_type) && $cookie_user_type == 'driver') ? 'checked' : ''; ?>/>
						<label for="driver">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="52.156" height="47.968" viewBox="0 0 52.156 47.968">
								<path d="M40.537,47.814 C40.400,47.905 40.245,47.949 40.091,47.949 C39.831,47.949 39.577,47.823 39.422,47.592 C39.176,47.222 39.275,46.723 39.644,46.476 C46.472,41.916 50.548,34.300 50.548,26.103 C50.548,12.604 39.568,1.622 26.071,1.622 C12.574,1.622 1.594,12.604 1.594,26.103 C1.594,34.300 5.670,41.917 12.499,46.476 C12.868,46.723 12.967,47.222 12.721,47.592 C12.474,47.961 11.974,48.060 11.606,47.814 C4.330,42.955 -0.014,34.839 -0.014,26.103 C-0.014,11.717 11.688,0.014 26.071,0.014 C40.455,0.014 52.156,11.717 52.156,26.103 C52.156,34.838 47.813,42.955 40.537,47.814 ZM20.238,14.856 C20.238,11.634 22.850,9.023 26.071,9.023 C29.292,9.023 31.903,11.634 31.903,14.856 C31.903,18.079 29.292,20.690 26.071,20.690 C22.850,20.690 20.238,18.079 20.238,14.856 ZM22.632,22.036 C22.632,24.729 23.939,26.030 26.071,26.248 C28.204,26.030 29.509,24.729 29.509,22.036 C29.509,22.036 37.449,23.871 38.034,25.626 C38.483,26.972 39.828,37.742 38.931,38.640 C38.575,38.995 36.669,39.703 34.415,40.429 C34.680,39.593 34.825,38.706 34.825,37.785 C34.825,32.957 30.897,29.029 26.071,29.029 C21.244,29.029 17.316,32.957 17.316,37.785 C17.316,38.706 17.462,39.593 17.727,40.429 C15.472,39.703 13.566,38.995 13.211,38.640 C12.313,37.742 13.659,26.972 14.108,25.626 C14.693,23.871 22.632,22.036 22.632,22.036 ZM26.071,30.376 C30.156,30.376 33.480,33.699 33.480,37.785 C33.480,38.916 33.217,39.985 32.761,40.944 C30.058,41.765 27.272,42.503 26.222,42.650 C26.222,42.659 26.222,42.670 26.222,42.678 C26.180,42.678 26.129,42.675 26.071,42.669 C26.012,42.675 25.961,42.678 25.920,42.678 C25.920,42.670 25.920,42.659 25.920,42.650 C24.870,42.503 22.083,41.765 19.380,40.944 C18.925,39.985 18.663,38.916 18.663,37.785 C18.663,33.699 21.986,30.376 26.071,30.376 Z" class="cls-1"/>
							</svg>
							<span>Driver</span>
						</label>
						<input type="radio" name="selectProfile" value="broker" id="broker" data-role="none" <?php echo (isset($cookie_user_type) && $cookie_user_type == 'broker') ? 'checked' : ''; ?>/>
						<label for="broker">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="52.188" height="47.968" viewBox="0 0 52.188 47.968">
								<path d="M40.562,47.814 C40.425,47.905 40.270,47.949 40.116,47.949 C39.856,47.949 39.602,47.823 39.447,47.592 C39.200,47.222 39.300,46.723 39.669,46.476 C46.496,41.916 50.573,34.300 50.573,26.103 C50.573,12.604 39.593,1.622 26.096,1.622 C12.599,1.622 1.619,12.604 1.619,26.103 C1.619,34.300 5.695,41.917 12.523,46.476 C12.892,46.723 12.992,47.222 12.745,47.592 C12.499,47.961 11.999,48.060 11.630,47.814 C4.355,42.955 0.011,34.839 0.011,26.103 C0.011,11.717 11.713,0.014 26.096,0.014 C40.480,0.014 52.181,11.717 52.181,26.103 C52.181,34.838 47.838,42.955 40.562,47.814 ZM20.170,14.935 C20.170,11.663 22.822,9.010 26.094,9.010 C29.366,9.010 32.019,11.663 32.019,14.935 C32.019,18.208 29.366,20.861 26.094,20.861 C22.822,20.861 20.170,18.208 20.170,14.935 ZM13.943,25.876 C14.538,24.092 22.602,22.229 22.602,22.229 C22.926,25.145 23.481,28.518 24.593,30.226 L25.370,26.032 C25.370,25.442 25.694,24.963 26.094,24.963 C26.495,24.963 26.820,25.442 26.820,26.032 L27.596,30.227 C28.709,28.519 29.264,25.145 29.588,22.229 C29.588,22.229 37.652,24.092 38.247,25.876 C38.703,27.243 40.070,38.183 39.158,39.094 C38.296,39.956 28.465,42.858 26.248,43.168 C26.248,43.177 26.248,43.188 26.248,43.197 C26.206,43.197 26.155,43.193 26.095,43.188 C26.035,43.193 25.983,43.197 25.942,43.197 C25.942,43.188 25.942,43.177 25.942,43.168 C23.725,42.858 13.894,39.956 13.032,39.094 C12.120,38.183 13.487,27.243 13.943,25.876 Z" class="cls-1"/>
							</svg>
							<span>Broker</span>
						</label>
						<input type="radio" name="selectProfile" value="fleet" id="fleet" data-role="none" <?php echo (isset($cookie_user_type) && $cookie_user_type == 'fleet') ? 'checked' : ''; ?>/>
						<label for="fleet">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="52.063" height="47.875" viewBox="0 0 52.063 47.875">
								<path d="M40.468,47.716 C40.332,47.807 40.177,47.851 40.023,47.851 C39.764,47.851 39.510,47.726 39.356,47.494 C39.109,47.125 39.208,46.627 39.577,46.381 C46.392,41.827 50.461,34.223 50.461,26.038 C50.461,12.559 39.501,1.593 26.029,1.593 C12.557,1.593 1.596,12.559 1.596,26.038 C1.596,34.223 5.665,41.828 12.482,46.381 C12.849,46.627 12.949,47.125 12.703,47.494 C12.456,47.863 11.958,47.962 11.590,47.716 C4.328,42.864 -0.009,34.761 -0.009,26.038 C-0.009,11.673 11.672,-0.013 26.029,-0.013 C40.386,-0.013 52.066,11.673 52.066,26.038 C52.066,34.760 47.731,42.864 40.468,47.716 ZM32.920,17.758 L19.137,17.758 C17.090,17.758 15.408,19.313 15.207,21.308 L14.911,25.938 L12.646,27.317 L12.652,11.490 C12.652,10.137 13.745,9.039 15.099,9.039 L36.957,9.039 C38.309,9.039 39.406,10.137 39.406,11.490 L39.410,27.317 L37.147,25.938 L36.849,21.308 C36.647,19.313 34.967,17.758 32.920,17.758 ZM12.650,37.453 L12.643,28.728 L16.076,26.655 L16.419,21.483 C16.534,20.083 17.707,18.984 19.137,18.984 L32.920,18.984 C34.348,18.984 35.522,20.083 35.636,21.483 L35.978,26.655 L39.414,28.728 L39.406,37.453 L37.874,37.453 L37.877,40.307 C37.877,41.534 36.883,42.530 35.656,42.530 C34.430,42.530 33.436,41.534 33.436,40.307 L33.439,37.453 L18.620,37.453 L18.622,40.307 C18.622,41.534 17.628,42.530 16.400,42.530 C15.173,42.530 14.180,41.534 14.180,40.307 L14.182,37.453 L12.650,37.453 ZM35.713,33.601 C36.740,33.601 37.574,32.768 37.574,31.740 C37.574,30.712 36.740,29.877 35.713,29.877 C34.685,29.877 33.853,30.712 33.853,31.740 C33.853,32.768 34.685,33.601 35.713,33.601 ZM19.897,34.532 L32.136,34.532 L32.136,29.831 C32.136,29.367 31.760,28.990 31.297,28.990 L20.740,28.990 C20.274,28.990 19.897,29.367 19.897,29.831 L19.897,34.532 ZM34.664,26.601 L34.330,21.591 C34.270,20.864 33.662,20.293 32.920,20.293 L19.137,20.293 C18.395,20.293 17.787,20.864 17.727,21.591 L17.393,26.601 L34.664,26.601 ZM16.344,33.601 C17.371,33.601 18.204,32.768 18.204,31.740 C18.204,30.712 17.371,29.877 16.344,29.877 C15.316,29.877 14.484,30.712 14.484,31.740 C14.484,32.768 15.316,33.601 16.344,33.601 Z" class="cls-1"/>
							</svg>
							<span>Fleet</span>
						</label>
						<input type="radio" name="selectProfile" value="depot" id="depot" data-role="none" <?php echo (isset($cookie_user_type) && $cookie_user_type == 'depot') ? 'checked' : ''; ?>/>
						<label for="depot">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="52.156" height="47.968" viewBox="0 0 52.156 47.968">
								<path d="M40.541,47.814 C40.404,47.905 40.249,47.949 40.096,47.949 C39.836,47.949 39.582,47.823 39.427,47.592 C39.180,47.222 39.279,46.723 39.649,46.476 C46.476,41.916 50.553,34.300 50.553,26.103 C50.553,12.604 39.572,1.622 26.076,1.622 C12.578,1.622 1.598,12.604 1.598,26.103 C1.598,34.300 5.674,41.917 12.503,46.476 C12.872,46.723 12.971,47.222 12.725,47.592 C12.478,47.961 11.979,48.060 11.610,47.814 C4.334,42.955 -0.010,34.839 -0.010,26.103 C-0.010,11.717 11.692,0.014 26.076,0.014 C40.459,0.014 52.161,11.717 52.161,26.103 C52.161,34.838 47.817,42.955 40.541,47.814 ZM9.643,25.521 C9.438,25.012 9.563,24.430 9.958,24.050 L25.144,9.429 C25.403,9.179 25.739,9.053 26.075,9.053 C26.411,9.053 26.747,9.179 27.007,9.429 L42.193,24.050 C42.588,24.430 42.713,25.012 42.508,25.521 C42.303,26.029 41.809,26.362 41.261,26.362 L38.437,26.362 L38.437,41.243 C38.437,41.985 37.835,42.587 37.093,42.587 L26.075,42.587 L15.057,42.587 C14.315,42.587 13.713,41.985 13.713,41.243 L13.713,26.362 L10.889,26.362 C10.341,26.362 9.848,26.029 9.643,25.521 ZM25.776,39.513 L33.489,39.496 L33.472,31.759 L25.758,31.777 L25.776,39.513 ZM25.757,30.784 L30.901,30.772 L30.889,25.627 L25.744,25.638 L25.757,30.784 ZM24.955,28.236 L20.774,28.227 L20.765,32.409 L24.946,32.419 L24.955,28.236 ZM24.929,39.513 L24.944,33.226 L18.675,33.212 L18.661,39.500 L24.929,39.513 Z" class="cls-1"/>
							</svg>
							<span>Depot</span>
						</label>
					</div>
				</div>
				<div class="login-form">
					<div class="login-form-row input-row">
						<label>Email</label>
						<input type="email" name="email" id="email" autocomplete="off" class="login-controls required email" value="<?php echo (isset($cookie_email)) ? $cookie_email : ''; ?>" placeholder="info@hotcargo.com" onclick="do_clear_fld(1, 'email')" onblur="do_clear_fld(0, 'email')" data-role="none" />
					</div>
					<label id="email-error" class="error" for="email"></label>
					<div class="login-form-row input-row">
						<label>Password</label>
						<div class="password-field">
							<input type="password" minlength="6" onclick="do_clear_fld(1, 'password')" onblur="do_clear_fld(0, 'password')" name="password" value="<?php echo (isset($cookie_pass)) ? $cookie_pass : ''; ?>" id="password" autocomplete="off" class="login-controls required" placeholder="*****" data-role="none" />
							<a id="pass_view" href="javascript:void(0)" onclick="show_password()"><i class="fa fa-eye" aria-hidden="true"></i></a>
						</div>
					</div>
					<label id="password-error" class="error" for="password"></label>
					<div class="login-form-row input-row">
						<div class="signup-row guranteed-row">
							<input type="checkbox" <?php echo (isset($cookie_rem) && ($cookie_rem == 1)) ? 'checked' : ''; ?> name="do_remember" class="rem_check" value="1" id="do_remember" data-role="none" />
							<label class="remember-sec" for="do_remember">Remember Me</label>
						</div>
						<p class="dont-have-acc"><a data-ajax="false" href="<?php echo base_url().'forgot-password' ?>">Forgot password</a></p>
					</div>
					<div class="login-form-row submit-row">
						<input type="submit" value="Sign In" class="submit-btn" data-role="none" />
					</div>
				</div>
			</form>
			
			<div class="social-login">
				<a data-role="none" href="javascript:void(0)" onclick="linkedin_redirect('linkdin-auth')" data-ajax="false" class="linkdin-btn"><span><img src="<?php echo assets_url('site/images/linkdin-ico.png'); ?>" alt="linkdin-ico" /></span>Sign Up With Linkedin</a>
				<p class="dont-have-acc" id="sign_up_lk">Don’t Have An Account? <a data-role="none" href="javascript:void(0)" onclick="do_signup(1, '<?php echo $cmp_auth_link; ?>')">Sign Up</a></p>
			</div>
		</div>
	</div>
	
	<div id="error-section" class="ui-loader ui-corner-all ui-body-a ui-loader-verbose ui-loader-textonly hide">
		<span class="ui-icon-loading"></span>
		<h1 id="error_msg">Error Loading Page</h1>
	</div>
