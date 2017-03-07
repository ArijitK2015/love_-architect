	<link href="<?php echo assets_url('site/css/jquery.mCustomScrollbar.min.css') ?>" rel="stylesheet">
		
	<script src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>
	<script src="<?php echo assets_url('site/js/additional-methods.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.mCustomScrollbar.min.js') ?>"></script>
		
		
	<!--Add scripts and css for mobile no verifications-->
	<link rel="stylesheet" href="<?php echo assets_url('site/intl-tel-input/css/intlTelInput.css') ?>" />
	<script src="<?php echo assets_url('site/intl-tel-input/js/intlTelInput.js') ?>"></script>
		
	<?php
		$flash_message 	= $this->session->flashdata('flash_message');
		$flash_message_cont = $this->session->flashdata('flash_message_cont');
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
		$flash_message 	= $this->session->flashdata('flash_message');
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
	?>
		
	<style>
		.intl-tel-input.allow-dropdown.separate-dial-code.iti-sdc-2 .selected-flag{
			width: 70px;
		}
		.intl-tel-input.allow-dropdown.separate-dial-code .selected-dial-code{
			padding-left: 5px;
		}
		.intl-tel-input .selected-flag .iti-arrow{
			right: 10px;
		}
		.intl-tel-input.allow-dropdown.separate-dial-code.iti-sdc-2 input, .intl-tel-input.allow-dropdown.separate-dial-code.iti-sdc-2 input[type=text], .intl-tel-input.allow-dropdown.separate-dial-code.iti-sdc-2 input[type=tel]{
			padding-left: 90px;
		}
		.intl-tel-input.allow-dropdown input, .intl-tel-input.allow-dropdown input[type=text], .intl-tel-input.allow-dropdown input[type=tel]{
			padding-right: 0;
		}
	</style>
		
	<script>	
		var is_addr_populate = 0;
		var show_addr_id 	= ''; var address_put_arr = [];
			
		var  letter_weight 	= '0.99',
			small_parcel 	= '9.99',
			medium_parcel 	= '29.99',
			large_parcel 	= '49.99';
			
			
		$(document).ready(function() {
			var flash_msg = '<?php echo $flash_message ?>', error_msg = '';
			if (flash_msg != '') {
				if (flash_msg == 'user_det_error') 		error_msg = '<span class="error">User details not found. Please try again with different user.</span>';
				else if (flash_msg == 'user_status_failed') 	error_msg = '<span class="error">User is not activated yet. Please activate and try again.</span>';
				//else if (flash_msg == 'user_not_exist') 	error_msg = '<span class="error">Invalid credentials. Please try again.</span>';
				else if (flash_msg == 'error') 			error_msg = '<span class="error">Error occured. Please try again.</span>';
				else if (flash_msg == 'email_exist') 		error_msg = '<span class="error">Email-id already exist. Please try again with another id.</span>';
				else if (flash_msg == 'reg_error') 		error_msg = '<span class="error">Failded to register. Please try again.</span>';
				else if (flash_msg == 'reg_success') 		error_msg = '<span class="success">Registration successful.</span>';
				else if (flash_msg == 'user_not_exist') 	error_msg = '<span class="error">User does not exist. To register please fill up the details.</span>';
					
				else if (flash_msg == 'email_blank') 		error_msg = '<span class="error">Please enter email id and try again.</span>';
				else if (flash_msg == 'user_job_error') 	error_msg = '<span class="error">Failed to process. Please try again.</span>';
				else if (flash_msg == 'user_job_success') 	error_msg = '<span class="success">Successfully registered and job added. Please login to view the job.</span>';
					
				if (error_msg != '') {
					$("#error_msg").html(error_msg);
					$("#error-section").show();
				}
			}
				
			setTimeout(function(){
				$("#error-section").hide();
				$("#error_msg").html('');
			}, 5000);
			
			$('.infoI, .close-container, .close-btn').on('click', function(){	
				$('body').toggleClass('info-popup-active');
			});
			
			$(".custom-scrollbar").mCustomScrollbar({
				scrollButtons:{
				    enable:true
				}
			});
			
			$('.close-content').on('click', function(e){
				e.stopPropagation();
			});
		});
			
		function accept_quote(args, price, cmp_name) {
			$("#uber_rush_accpt").html('ACCEPTED');
			$("#uber_rush_quote_id").val(args);
			$("#uber_rush_quote_price").val(price);
			$("#api_type_cmp").val(cmp_name);
			$("#uber_rush_quote_error").html('');
		}
			
		function update_message(is_marketplace)
		{
			var delivery_option = $('input[name=delivery_option]:checked').val();
				
			if(delivery_option == '1')
			{
				if (is_marketplace == '1')
					$("#option_type_error").html(string_escape('<?php echo (isset($pages_help_contents['on_demand_opt_unavil']) && !empty($pages_help_contents['on_demand_opt_unavil'])) ? addslashes($pages_help_contents['on_demand_opt_unavil']) : addslashes('This option is not available for On Demand, please go back and select Marketplace Quote for this type of delivery.') ?>'));
				else
					$("#option_type_error").html('');
			}
		}
			
		function next_form(completed_form_stat)
		{
			var choosed_del_opt = $("#delivery_option").val();
				
			if (completed_form_stat == 0)
			{
				$("#tab_1").addClass("inp-tab");
				$("#tab_2").addClass("inp-tab");
					
				$("#step_1").show();
				$("#step_2").hide();
				$("#step_3").hide();
					
				$("#class_step_1").addClass("active");
				$("#class_step_2").removeClass("active");
				$("#class_step_3").removeClass("active");
			}
			else if (completed_form_stat == 1) {
					
				var pick_up_addr 		= $("#pickup_address").val();
				var pick_up_addr_valid 	= parseInt($("#is_pick_valid").val());
				var drop_of_addr 		= $("#dropoff_address").val();
				var drop_of_addr_valid 	= parseInt($("#is_drop_valid").val());
				var delivery_option 	= $('input[name=delivery_option]:checked').val();
				
					
				if (pick_up_addr == '' || drop_of_addr == ''){
					//console.log('arijit 01');
					$("#uber_rush_error").html(string_escape('<?php echo (isset($pages_help_contents['location_error']) && (!empty($pages_help_contents['location_error']))) ? addslashes($pages_help_contents['location_error']) : addslashes('Please enter a Pickup and Dropoff address before proceeding, or click register if you\'re not ready to start you\'re first shipment.') ?>'));
					
					return false;
				}
				else $("#uber_rush_error").html('');
					
				var pick_up_addr_valid 	= parseInt($("#is_pick_valid").val());
				var drop_of_addr_valid 	= parseInt($("#is_drop_valid").val());
				var is_uber_rush_ok 	= parseInt($("#is_uber_rush_ok").val());
					
				console.log(delivery_option);
					
				if(typeof(delivery_option) == "undefined"){
					//console.log('arijit 1');
					$("#delivery_option_error").html('Please choose a delivery option.');
					return false;
				}
				else{
					//console.log('arijit 2');
					$("#delivery_option_error").html('');
						
					if (delivery_option == '2')	is_uber_rush_ok = 1;
						
					if (is_uber_rush_ok == 1) {
							
						$("#uber_rush_error").html('');
						
						var checked_val 	= $('input[name=delivery_option]:checked').val();
							
						if (checked_val == '') $("#delivery_option_error").html('Please choose a delivery option to proceed.');
						else{
							
							$("#step_1_stat").val(1);
							$("#tab_3").attr('style', '');
							
							$("#tab_1").addClass("inp-tab");
							$("#tab_2").addClass("inp-tab");
								
							$("#step_1").hide();
							$("#step_2").show();
							$("#step_3").hide();
								
							$("#class_step_1").removeClass("active");
							$("#class_step_2").addClass("active");
							$("#class_step_3").removeClass("active");
						}
					}
					else
						$("#uber_rush_error").html(string_escape('<?php echo (isset($pages_help_contents['location_error']) && (!empty($pages_help_contents['location_error']))) ? addslashes($pages_help_contents['location_error']) : addslashes('Please enter a Pickup and Dropoff address before proceeding, or click register if you\'re not ready to start you\'re first shipment.') ?>'));
				}
			}
			else if (completed_form_stat == 2) {
					
				var step_1_status 	= $("#step_1_stat").val();
				var delivery_option = $('input[name=delivery_option]:checked').val();
				var checked_val 	= $('input[name=choose-delivery]:checked').val();
				var delivery_type 	= $('input[name=choose-delivery]:checked').val();
				var is_onlymarket 	= $('input[name=choose-delivery]:checked').attr('only_marketplace');
				var weight_val		= $("input[name=choose-delivery]:checked").attr('this_weight_val');
					
				//console.log('arojit: '+weight_val);	
					
				if (checked_val == '') $("#option_type_error").html('Please select a delivery option.');
				else{
					if(delivery_option == '1')
					{
						if (is_onlymarket == '1'){
							$("#option_type_error").html(string_escape('<?php echo (isset($pages_help_contents['on_demand_opt_unavil']) && !empty($pages_help_contents['on_demand_opt_unavil'])) ? addslashes($pages_help_contents['on_demand_opt_unavil']) : addslashes('This option is not available for On Demand, please go back and select Marketplace Quote for this type of delivery.') ?>'));
						}
						else{
							
							$("#weight").val(weight_val);
								
							$("#option_type_error").html('');
								
							$("#step_2_stat").val(1);
							
							$("#tab_3").addClass("inp-tab");
							$("#tab_3").attr('style', '');
							
							$("#step_1").hide();
							$("#step_2").hide();
							$("#step_3").show();
								
							$("#class_step_1").removeClass("active");
							$("#class_step_2").removeClass("active");
							$("#class_step_3").addClass("active");
						}
					}
					else{
						$("#step_2_stat").val(1);
						
						$("#tab_3").addClass("inp-tab");
						$("#tab_3").attr('style', '');
						
						$("#step_1").hide();
						$("#step_2").hide();
						$("#step_3").show();
							
						$("#class_step_1").removeClass("active");
						$("#class_step_2").removeClass("active");
						$("#class_step_3").addClass("active");
					}
				}
			}
			else if (completed_form_stat == 3) {
					
				var delivery_option 	= parseInt($('input[name=delivery_option]:checked').val());
				console.log(delivery_option);
				var uber_rush_quote_id 	= $("#uber_rush_quote_id").val();
					
				if ((delivery_option == 1) && (uber_rush_quote_id == ''))  $("#uber_rush_quote_error").html('Please accept any one of the quote.');
				else
				{
					$("#step_3_stat").val(1);
					
					$("#uber_rush_quote_error").html('');
					$("#btn-list").click();
				}
			}
		}
			
			
		$(document).ready(function(){
			//var choose_delivery 	= parseInt($('input[name=choose-delivery]:checked').val());
			//console.log(choose_delivery);
			//$("input[name='choose-delivery']").change(choose_del_opt(choose_delivery));
				
			$.validator.addMethod("positivenumber", function (value, element, options){
				var bothEmpty = false;
				var data_value = parseFloat(value);
				if (data_value >= 0) bothEmpty = true;
				return bothEmpty;
			},"Please enter positive value.");
			
			$("#customer-signup-form").validate({
				rules: {
					// no quoting necessary
					weight	: {
								required: true,
								positivenumber: true
					},
					first_name: 	"required",
					last_name: 	"required",
					email: 	{
								required: true,
								email: true
					},
					password: {
								required: true,
								minlength: 6
					},
					confirm_password: {
								required: true,
								minlength: 6,
								equalTo: "#password"
					},
					mobile_no: {
								required: true,
								positivenumber: true
					},
					agree: 		"required",
				},
				messages: {
					weight	: {
								required: "Please enter your weight.",
								positivenumber: "Please enter only positive number."
					},
					first_name:    {
								required: "Please enter your first name."
							},
					last_name: 	{
								required: "Please enter your last name."
							},
					email:    {
								required: 'Please enter your email id.',
								email: 	"Please enter proper email id."
							},
					password:   {
								required:  'Enter your password.',
								minlength: "Please enter minimum 6 cheracter."
							},
					confirm_password:   {
								required:  'Enter your password again.',
								minlength: "Please enter minimum 6 cheracter.",
								equalTo:   "Both password need to be same."
							},
					mobile_no: {
								required: "Please enter your mobile number.",
								positivenumber: "Please enter only positive number."
					},
				},
				submitHandler: function(form) {
					console.log('validated');
					form.submit();
				}	
			});
		});
			
		function choose_del_opt(args) {
			$("#choose_del_opt").val(args);
		}
			
		function get_current_latlng(args) {
			show_addr_id = args;
			if (navigator.geolocation){
				navigator.geolocation.getCurrentPosition(showPosition);
			}
			else{
				var error_msg = "Geolocation is not supported by this browser.";
				if (error_msg != '') {
					$("#error_msg").html(error_msg);
					$("#error-section").show();
				}
			}
		}
			
		function showPosition(position)
		{ 
			location.latitude	= position.coords.latitude;
			location.longitude	= position.coords.longitude;
			
			var geocoder 		= new google.maps.Geocoder();
			var latLng 		= new google.maps.LatLng(location.latitude, location.longitude);
		
			if (geocoder) {
				geocoder.geocode({ 'latLng': latLng}, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK)
					{
						if (show_addr_id == 'pickup_address') $("#is_pick_valid").val('1');
						else $("#is_drop_valid").val('1');
						
						$('#'+show_addr_id).val(results[0].formatted_address);
						if(typeof(results[0].geometry.location) != "undefined")
							srch_lat = results[0].geometry.location.lat();
					
						if(typeof(results[0].geometry.location) != "undefined")
							srch_lon = results[0].geometry.location.lng();
						
						$('#'+show_addr_id+'_lat').val(srch_lat);
						$('#'+show_addr_id+'_lng').val(srch_lon);
						
					}
					else {
						if (show_addr_id == 'pickup_address') $("#is_pick_valid").val('1');
						else $("#is_drop_valid").val('1');
						
						var error_msg = "Geolocation is not supported by this browser.";
						if (error_msg != '') {
							$("#error_msg").html(error_msg);
							$("#error-section").show();
						}
					}
				}); //geocoder.geocode()
			}      
		} //showPosition
		
		function check_type_det(args) {
			
			//console.log(args + 'hi');
			
			var pick_up_addr_valid 	= parseInt($("#is_pick_valid").val());
			var drop_of_addr_valid 	= parseInt($("#is_drop_valid").val());
			var delivery_option 	= $('input[name=delivery_option]:checked').val();
			    delivery_option 	= (delivery_option == "undefined")		? '' : delivery_option;
			
			var is_uber_rush_ok 	= parseInt($("#is_uber_rush_ok").val());
			
			if(args == 'demand') {
				
				if ((pick_up_addr_valid != 1) || (drop_of_addr_valid != 1))
					$("#uber_rush_error").html(string_escape('<?php echo (isset($pages_help_contents['location_error']) && (!empty($pages_help_contents['location_error']))) ? addslashes($pages_help_contents['location_error']) : addslashes('Please enter a Pickup and Dropoff address before proceeding, or click register if you\'re not ready to start you\'re first shipment.'); ?>'));
					
				else if ($('#delivery_option_1').hasClass( "has_graybox" ) && (delivery_option == '')) $("#uber_rush_error").html(string_escape('<?php echo (isset($pages_help_contents['location_error']) && (!empty($pages_help_contents['location_error']))) ? addslashes($pages_help_contents['location_error']) : addslashes('Please enter a Pickup and Dropoff address before proceeding, or click register if you\'re not ready to start you\'re first shipment.') ?>'));
					
				else if (is_uber_rush_ok == 0 ){
					$("#info_title").html('Message');	
					$("#info_content_det").html(string_escape('<?php echo (isset($pages_help_contents['on_demand_error']) && (!empty($pages_help_contents['on_demand_error']))) ? addslashes($pages_help_contents['on_demand_error']) : addslashes('Unfortunately OnDemand is not available in  your area right now, please try a marketplace quote or come back soon, we are adding new locations everyday') ?>'))
						
					$('body').toggleClass('info-popup-active');
				}
			}
				
			if(args == 'quote') {
				
				if ((pick_up_addr_valid != 1) || (drop_of_addr_valid != 1)) 
					$("#uber_rush_error").html(string_escape('<?php echo (isset($pages_help_contents['location_error']) && (!empty($pages_help_contents['location_error']))) ? addslashes($pages_help_contents['location_error']) : addslashes('Please enter a Pickup and Dropoff address before proceeding, or click register if you\'re not ready to start you\'re first shipment.') ?>'));
					
				else if ($('#delivery_option_2').hasClass( "has_graybox" ) && (delivery_option == '')) $("#uber_rush_error").html(string_escape('<?php echo (isset($pages_help_contents['location_error']) && (!empty($pages_help_contents['location_error']))) ? addslashes($pages_help_contents['location_error']) : addslashes('Please enter a Pickup and Dropoff address before proceeding, or click register if you\'re not ready to start you\'re first shipment.') ?>'));
					
				else $("#uber_rush_error").html('');
			}
		}
			
		$(document).ready(function() {
			//Initilize custom scroll bar
			
		});
	</script>

	<div class="sidebar-menu">
		<?php
			if(!empty($site_logo))
				echo '<a data-ajax="false" href="'.base_url().'" class="logo map-logo"><img src="'.assets_url('uploads/merchant_images/thumb/'.$site_logo).'" alt="logo" /></a>';
			else
				echo '<a data-ajax="false" href="'.base_url().'" class="logo map-logo"><img src="'.assets_url('site/images/'.$default_site_logo).'" alt="logo" /></a>';
		?>
			
		<ul>
			<?php
				if(isset($user_id) && $user_id != '')
				{
					if(isset($users_all_menus) && !empty($users_all_menus))
					{
						foreach($users_all_menus as $menu)
							echo '<li><a href="'.base_url().$menu['url'].'" data-ajax="false">'.$menu['title'].'</a></li>';
					}
				}
				else
				{
			?>
					<li><a data-ajax="false" href="<?php echo base_url().'login' ?>">Login</a></li>
					<li><a data-ajax="false" href="<?php echo base_url().'sign-up/customer' ?>">Register</a></li>
					<li><a data-ajax="false" href="<?php echo base_url().'help' ?>">Help</a></li>
					<li><a data-ajax="false" href="<?php echo base_url().'about' ?>">About</a></li>
			<?php
				}
			?>
		</ul>
	</div>
	<div class="menu-overlay"></div>
	<!-- login screen -->
	
	<div data-role="page" id="signupPage" class="main-page map-page">
		<div class="close-container help-info-popup overlay-popup">
			<div class="close-content">
				<h3 id="info_title"> Terms & Conditions </h3>
				<div class="close-para">
					<div id="info_content">
						<div class="terms-scroll custom-scrollbar">
							<div class="terms-content" id="info_content_det" style="background: #fff;"> <?php echo str_replace('\n', '<br>', $terms_condition) ?> </div>
						</div>
					</div>
				</div>
				<div class="close-footer"><a href="javascript:void(0)" class="close-btn">Close</a></div>
			</div>
		</div>
			
		<div data-role="main" class="ui-content login-content homepage-content">
			<div class="homepage-header new-sign-up">
				<a href="javascript:void(0)" class="menu-strap">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="20.281" height="14.813" viewBox="0 0 20.281 14.813">
						<path d="M19.413,14.821 L0.862,14.821 C0.384,14.821 -0.007,14.402 -0.007,13.891 C-0.007,13.380 0.384,12.962 0.862,12.962 L19.413,12.962 C19.891,12.962 20.283,13.380 20.283,13.891 C20.283,14.402 19.891,14.821 19.413,14.821 ZM0.862,6.485 L15.724,6.485 C16.202,6.485 16.593,6.903 16.593,7.414 C16.593,7.926 16.202,8.344 15.724,8.344 L0.862,8.344 C0.384,8.344 -0.007,7.926 -0.007,7.414 C-0.007,6.903 0.384,6.485 0.862,6.485 ZM18.491,1.867 L0.862,1.867 C0.384,1.867 -0.007,1.449 -0.007,0.938 C-0.007,0.426 0.384,0.008 0.862,0.008 L18.491,0.008 C18.969,0.008 19.360,0.426 19.360,0.938 C19.360,1.449 18.969,1.867 18.491,1.867 Z" class="cls-1"/>
					</svg>
				</a>
				
				<?php
					if(!empty($site_logo))
						echo '<a data-ajax="false" href="'.base_url().'" class="logo map-logo"><img src="'.assets_url('uploads/merchant_images/thumb/'.$site_logo).'" alt="logo" /></a>';
					else
						echo '<a data-ajax="false" href="'.base_url().'" class="logo map-logo"><img src="'.assets_url('site/images/'.$default_site_logo).'" alt="logo" /></a>';
				?>
				<div class="auth_ico">
					<!--<a data-ajax="false" href="<?php echo base_url().'login' ?>"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
					<a data-ajax="false" href="<?php echo base_url().'sign-up/customer' ?>"><i class="fa fa-user-plus" aria-hidden="true"></i></a>-->
					<a data-ajax="false" href="<?php echo base_url().'login' ?>">Login</a>
					<a data-ajax="false" href="<?php echo base_url().'sign-up/customer' ?>">Register</a>
				</div>
			</div>
		
			<div class="ship-wrap">
				<div class="get-shipping">
					<div class="get-shipping-left">
						<div class="get-shipping-icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="28.5" height="29.187" viewBox="0 0 28.5 29.187">
								<path d="M12.526,1.581 C12.530,-0.575 15.789,-0.575 15.787,1.643 L15.787,10.691 L28.493,18.305 L28.493,21.648 L15.849,17.499 L15.849,24.263 L18.775,26.540 L18.775,29.185 L14.264,27.788 L9.753,29.185 L9.753,26.540 L12.648,24.263 L12.648,17.499 L0.002,21.648 L0.002,18.305 L12.526,10.691 L12.526,1.581 Z" class="cls-1"/>
							</svg>
						</div>
						<div class="get-shipping-icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="25" height="31.219" viewBox="0 0 25 31.219">
								<path d="M22.597,11.440 C22.409,9.580 20.841,8.130 18.930,8.130 L6.065,8.130 C4.154,8.130 2.584,9.580 2.396,11.440 L2.120,15.759 L0.006,17.044 L0.011,2.284 C0.011,1.022 1.032,-0.002 2.296,-0.002 L22.699,-0.002 C23.960,-0.002 24.984,1.022 24.984,2.284 L24.988,17.044 L22.875,15.759 L22.597,11.440 ZM3.528,11.603 C3.635,10.299 4.730,9.273 6.065,9.273 L18.930,9.273 C20.263,9.273 21.359,10.299 21.465,11.603 L21.785,16.428 L24.992,18.361 L24.984,26.498 L23.554,26.498 L23.557,29.161 C23.557,30.305 22.629,31.234 21.484,31.234 C20.339,31.234 19.412,30.305 19.412,29.161 L19.415,26.498 L5.582,26.498 L5.584,29.161 C5.584,30.305 4.656,31.234 3.510,31.234 C2.365,31.234 1.437,30.305 1.437,29.161 L1.439,26.498 L0.009,26.498 L0.004,18.361 L3.208,16.428 L3.528,11.603 ZM21.537,22.906 C22.496,22.906 23.274,22.129 23.274,21.170 C23.274,20.212 22.496,19.433 21.537,19.433 C20.577,19.433 19.801,20.212 19.801,21.170 C19.801,22.129 20.577,22.906 21.537,22.906 ZM6.774,23.774 L18.198,23.774 L18.198,19.390 C18.198,18.957 17.847,18.606 17.415,18.606 L7.561,18.606 C7.126,18.606 6.774,18.957 6.774,19.390 L6.774,23.774 ZM20.558,16.377 L20.246,11.704 C20.190,11.026 19.622,10.494 18.930,10.494 L6.065,10.494 C5.372,10.494 4.804,11.026 4.749,11.704 L4.437,16.377 L20.558,16.377 ZM1.721,21.170 C1.721,22.129 2.499,22.906 3.457,22.906 C4.416,22.906 5.194,22.129 5.194,21.170 C5.194,20.212 4.416,19.433 3.457,19.433 C2.499,19.433 1.721,20.212 1.721,21.170 Z" class="cls-1"/>
							</svg>
						</div>
						<div class="get-shipping-icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="28.094" height="31.219" viewBox="0 0 28.094 31.219">
								<path d="M24.988,31.232 L24.988,22.204 C25.992,19.906 27.036,17.567 27.971,15.210 C28.120,14.834 28.135,14.564 27.945,14.114 C27.577,13.251 26.055,12.934 25.117,12.436 L25.117,6.850 L21.788,6.850 L21.788,3.890 L16.974,3.890 L16.974,-0.012 L11.085,-0.012 L11.085,3.890 L6.268,3.890 L6.268,6.850 L2.941,6.850 L2.941,12.436 C2.002,12.934 0.482,13.251 0.114,14.114 C-0.075,14.564 -0.063,14.834 0.088,15.210 C1.022,17.567 2.066,19.906 3.068,22.204 L3.068,31.216 L24.988,31.232 ZM22.570,11.462 L14.028,8.041 L5.488,11.462 L5.488,9.243 L8.874,9.243 L8.874,6.353 L19.185,6.353 L19.185,9.243 L22.570,9.243 L22.570,11.462 Z" class="cls-1"/>
							</svg>
						</div>
					</div>
					<div class="get-shipping-right">
						<h3>Get shipping</h3>
						<p>in three simple steps</p>
					</div>
				</div>
	
				<ul class="step-nav">
					<li id="class_step_1" class="active"><a class="" id="tab_1" data-ajax="false" onclick="next_form('0')" href="javascript:void(0)"><span class="step-nav-text">1</span><span class="help-text"><img src="<?php echo assets_url() ?>site/images/get-started-text.png" alt="get-started-text" /></span></a></li>
					<li id="class_step_2"><a class="" id="tab_2" data-ajax="false" href="javascript:void(0)" onclick="next_form('1')" disabled="disabled"><span class="help-text closer-help-text"><img src="<?php echo assets_url() ?>site/images/closer-img.png" alt="closer-img" /></span><span class="step-nav-text">2</span></a></li>
					<li id="class_step_3"><a style="cursor: default; pointer-events: none;" class="" id="tab_3" onclick="next_form('2')" data-ajax="false" href="javascript:void(0)" disabled="disabled"><span class="step-nav-text">3</span></a></li>
				</ul>
				
				<form id="customer-signup-form" name="customer-signup-form" action="<?php echo base_url().'customer-signup-submit' ?>" method="post" enctype="multipart/form-data" data-ajax="false">
					<input type="hidden" name="current_form_stat" id="current_form_stat" value="1" />
					<input type="hidden" name="step_1_stat" id="step_1_stat" value="0" />
					<input type="hidden" name="step_2_stat" id="step_2_stat" value="0" />
					<input type="hidden" name="step_3_stat" id="step_3_stat" value="0" />
					<input type="hidden" name="is_uber_rush_ok" id="is_uber_rush_ok" value="0" />
					<input type="hidden" name="cmp_auth_id" id="cmp_auth_id" value="<?php echo isset($cmp_auth_no) ? $cmp_auth_no : ''; ?>" />
						
					<input type="hidden" name="distance_val" id="distance_val" value="0" />
						
					<div class="setup-content" id="step_1">
						<div class="step-form">
							<div class="delivery-form">
								<div class="signup-row">
									<input type="text" placeholder="Pickup Address" class="form-controls inp-address" id="pickup_address" name="pickup_address" data-role="none" required>
									<input type="hidden" name="pickup_address_lat" 	id="pickup_address_lat" 	value="" />
									<input type="hidden" name="pickup_address_lng" 	id="pickup_address_lng" 	value="" />
									<input type="hidden" name="is_pick_valid" 		id="is_pick_valid" 		value="0" />
										
									<input type="hidden" name="pick_address_1" 		id="pick_address_1" 	value="" />
									<input type="hidden" name="pick_address_2" 		id="pick_address_2" 	value="" />
									<input type="hidden" name="pick_city" 			id="pick_city" 		value="" />
									<input type="hidden" name="pick_state" 			id="pick_state" 		value="" />
									<input type="hidden" name="pick_code" 			id="pick_code" 		value="" />
									<input type="hidden" name="pick_country" 		id="pick_country" 		value="" />
										
									<a data-ajax="false" href="javascript:void(0);" class="signup-inp-ico ui-link" onclick="get_current_latlng('pickup_address')" style="cursor: pointer; pointer-events: painted;">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
											<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"></path>
										</svg>
									</a>
									<label id="pickup_address-error" class="error" for="pickup_address"></label>
								</div>
								
								<div class="signup-row">
									<input type="text" placeholder="Dropoff Address" class="form-controls inp-address" data-role="none" id="dropoff_address" name="dropoff_address" required>
									<input type="hidden" name="dropoff_address_lat" 	id="dropoff_address_lat" 	value="" />
									<input type="hidden" name="dropoff_address_lng" 	id="dropoff_address_lng" 	value="" />
									<input type="hidden" name="is_drop_valid" 		id="is_drop_valid" 			value="0" />
										
									<input type="hidden" name="dropoff_address_1" 	id="dropoff_address_1" 		value="" />
									<input type="hidden" name="dropoff_address_2" 	id="dropoff_address_2" 		value="" />
									<input type="hidden" name="dropoff_city" 		id="dropoff_city" 			value="" />
									<input type="hidden" name="dropoff_state" 		id="dropoff_state" 			value="" />
									<input type="hidden" name="dropoff_code" 		id="dropoff_code" 			value="" />
									<input type="hidden" name="dropoff_country" 		id="dropoff_country" 		value="" />
										
									<a data-ajax="false" href="javascript:void(0);" class="signup-inp-ico ui-link"  onclick="get_current_latlng('dropoff_address')" style="cursor: pointer; pointer-events: painted;">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
											<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"></path>
										</svg>
									</a>
									<label id="dropoff_address-error" class="error" for="dropoff_address"></label>
								</div>
								<p id="uber_rush_error" class="error"></p>
							</div>
						</div>
			
						<div class="delivery-options">
							<h3>Delivery Options</h3>
							<div class="choose-delivery">
								<input type="radio" id="demand" disabled name="delivery_option" data-role="none" value="1" required/>
								<label for="demand" class="delivery-box has_graybox" data-role="none" onclick="check_type_det('demand')" id="delivery_option_1">
									<h4>ON DEMAND</h4>
									<h5>Ready to ship immediately?</h5>
									<p>On demand allows standard shipment in major metropolitan areas</p>
								</label>
								<input type="radio" id="quote" name="delivery_option" value="2" onclick="check_type_det('quote')" required data-role="none" />
								<label for="quote" class="delivery-box  has_graybox" data-role="none"  id="delivery_option_2">
									<h4>MARKETPLACE QUOTE</h4>
									<h5>Get the best price</h5>
									<p>for any shipping job, anywhere at anytime</p>
								</label>
							</div>
							<p id="delivery_option_error" class="error"></p>
						</div>
						
						<div class="pg-next-btn">
							<input type="button" value="NEXT" id="next_btn_2" class="delivery-submit next_btn" data-role="none" onclick="next_form('1')" />
							<span id="ajax-loading" style="display: none;"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i></span>
						</div>
					</div>
						
						
					<div class="setup-content" id="step_2" style="display: none;">
						<div class="step-form">
							<div class="delivery-options sending-options">
								<h3 id="">Delivery Options</h3>
								<div class="choose-delivery">
									<input type="hidden" name="choose_del_opt" id="choose_del_opt" value="0" />
									<?php
										if(isset($sizes_details) && !empty($sizes_details))
										{
											foreach($sizes_details as $s => $size)
											{
													
												$size_id 		= (isset($size['_id'])) 		? strval($size['_id']) 	: '';
												$size_title 	= (isset($size['title'])) 	? strval($size['title']) : '';
												$size_image 	= (isset($size['image'])) 	? strval($size['image']) : '';
												$only_market	= (isset($size['only_marketplace'])) 	? strval($size['only_marketplace']) : '';
												$weight_val	= (isset($size['weight'])) 	? strval($size['weight']) : '';
												$checked		= ($s == 0) ? 'checked' : '';
													
												echo '<input onclick="update_message('.$only_market.')" only_marketplace="'.$only_market.'" this_weight_val="'.$weight_val.'" type="radio" id="d'.$s.'" name="choose-delivery" value="'.$size_id.'" data-role="none" '.$checked.' />
													<label for="d'.$s.'" class="delivery-box" data-role="none">
														<div class="sending-ico"><img src="'.assets_url().'site/images/'.$size_image.'" alt="sending-ico-1" /></div>
														<div class="sending-text">
															<span>'.ucwords($size_title).'</span>
														</div>
													</label>';
											}
										}
									?>
										
									<p id="option_type_error" class="error"></p>
								</div>
										
								<div class="signup-row other-textbox" style="display: none;">
									<input type="text" placeholder="Weight" id="weight" name="weight" class="form-controls" data-role="none" />
								</div>
								<div class="signup-row other-textbox"><p id="weight_error" class="error"></p></div>
									
								<div class="guranteed-row all-country-div" style="display: none;">
									<span>Is Fragile</span>
									<input id="is_fragile1" name="is_fragile" autocomplete="off" value="1" type="radio"  data-role="none" />
									<label for="is_fragile1">Yes</label>
										
									<input id="is_fragile2" name="is_fragile" autocomplete="off" value="0" type="radio" checked data-role="none" />
									<label for="is_fragile2">No</label>
								</div>
							</div>
						</div>
						<input type="button" value="NEXT" id="next_btn_2" class="delivery-submit next_btn" data-role="none" onclick="next_form('2')" />
					</div>
						
					<div class="setup-content" id="step_3" style="display: none;">
						<input type="hidden" name="uber_rush_quote_id" 	id="uber_rush_quote_id" 		value="" />
						<input type="hidden" name="uber_rush_quote_price" id="uber_rush_quote_price" 	value="" />
						<input type="hidden" name="api_type_cmp" 		id="api_type_cmp" 	value="" />
						
						<div class="step-form">
							<div class="delivery-options accept-table-options">
								<h3 id="uber-rush-quotes-id" style="display: none;">Top three quotes</h3>
								<div class="accept-table" id="uber-rush-quotes-list">
									<!--<div class="accept-table-row">
										<div class="accept-table-cell">
											<figure><img src="<?php echo assets_url() ?>site/images/red-car.png" alt="red-car" /></figure>
											<big>$295</big>
										</div>
										<div class="accept-table-cell">
											<a href="javascript:void(0)" class="accept-anc">ACCEPT</a>
										</div>
									</div>
									<div class="accept-table-row">
										<div class="accept-table-cell">
											<figure><img src="<?php echo assets_url() ?>site/images/red-car.png" alt="red-car" /></figure>
											<big>$295</big>
										</div>
										<div class="accept-table-cell">
											<a href="javascript:void(0)" class="accept-anc">ACCEPT</a>
										</div>
									</div>
									<div class="accept-table-row">
										<div class="accept-table-cell">
											<figure><img src="<?php echo assets_url() ?>site/images/red-car.png" alt="red-car" /></figure>
											<big>$295</big>
										</div>
										<div class="accept-table-cell">
											<a href="javascript:void(0)" class="accept-anc">ACCEPT</a>
										</div>
									</div>-->
								</div>
								<p id="uber_rush_quote_error" class="error"></p>
							</div>
						</div>
						<div class="delivery-options accept-form">
							<div class="delivery-form">
								<div class="signup-row">
									<input type="text" placeholder="First Name" id="first_name" name="first_name" class="form-controls" data-role="none" required>
								</div>
								<div class="signup-row">
									<input type="text" placeholder="Last Name" id="last_name" name="last_name" class="form-controls" data-role="none" required>
								</div>
								<div class="signup-row">
									<input type="email" placeholder="Email" id="email" name="email" class="form-controls" data-role="none" required>
								</div>
								<div class="signup-row">
									<input type="password" placeholder="Password" id="password" name="password" class="form-controls" data-role="none" required>
								</div>
								<div class="signup-row">
									<input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" class="form-controls" data-role="none" required>
								</div>
								<div class="signup-row">
									<input type="hidden" name="country_code" id="country_code" value="US" />
									<input type="hidden" name="phone_code" id="phone_code" value="1" />
									<input name="mobile_no" value="+1" id="mobile_no" type="tel" placeholder="Phone Number" minlength="10" maxlength="15" autocomplete="off" class="background-input" data-role="none" />
									<label id="mobile_no-error" class="error" for="mobile_no"></label>
								</div>
									
								<div class="signup-row agree-terms">
									<input type="checkbox" name="agree" id="agree" checked id="terms" id="terms[]" value="1" data-role="none" required/>
									<label for="terms" class="infoI">Agree <a target="_blank" href="javascript:void(0)" >TERMS AND CONDITION</a></label>
								</div>
							</div>
							<input type="button" value="NEXT" id="next_btn_2" class="delivery-submit next_btn" data-role="none" onclick="next_form('3')" />
							<button id="btn-list" style="display: none;" type="submit">&nbsp;</button>
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

	<?php
		//Google api Key is important and we are using the key stored in database
		if(isset($settings[0]['google_map_api_key']) && !empty($settings[0]['google_map_api_key']))
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key='.$settings[0]['google_map_api_key'].'&libraries=geometry,places"></script>';
		else
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry,places"></script>';
	?>
	<script>
		//This function convert urlencoded strings to normal strings in jquery
		function string_escape(args) {
			try{
				fixedstring = decodeURIComponent(escape(args));
				fixedstring = decodeURIComponent((fixedstring+'').replace(/\+/g, '%20'));
			}
			catch(e){ fixedstring=args; }
			
			return fixedstring;
		}
			
		$("#mobile_no").intlTelInput({
			// allowDropdown: false,
			// autoHideDialCode: false,
			// autoPlaceholder: false,
			// dropdownContainer: "body",
			// excludeCountries: ["us"],
			// geoIpLookup: function(callback) {
			//   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			//     var countryCode = (resp && resp.country) ? resp.country : "";
			//     callback(countryCode);
			//   });
			// },
			// initialCountry: auto,
			// nationalMode: false,
			// numberType: "MOBILE",
			// onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			preferredCountries: ['us'],
			separateDialCode: true,
			utilsScript: main_base_url+"assets/site/intl-tel-input/js/utils.js"
		});
			
		$("#mobile_no").on("countrychange", function(e, countryData) {
			
			var country_phone_code 	= (typeof(countryData.dialCode) != "undefined") 	? countryData.dialCode 	: '0';
			var country_code 		= (typeof(countryData.iso2) != "undefined") 		? countryData.iso2 		: '0';
			
			if (country_phone_code != '') $("#phone_code").val(country_phone_code);
			if (country_code != '') 		$("#country_code").val(country_code);
		});
			
		$( ".inp-address" ).each(function() {
			var id = $(this).attr('id');
			var k  = new google.maps.places.SearchBox(this);
				
			google.maps.event.addListener(k, "places_changed", function() {
				var e = place = k.getPlaces();
				var srch_lat  = srch_lon = '';
				if (e.length > 0) {
					//console.log(place[0]);
					var set_id			= (id == 'pickup_address') 	? 'pick_' : 'dropoff_';
						
					if (set_id == 'pick_') 		$("#is_pick_valid").val('1');
					if (set_id == 'dropoff_') 	$("#is_drop_valid").val('1');
						
					var addr_components 	= place[0].address_components;
					var formatter_addr 		= place[0].formatted_address;
						
					for(i = 0; i < addr_components.length; i++)
					{
						var contet_type 	= addr_components[i].types[0];
						var contet_value 	= addr_components[i].long_name;
						var contet_sort 	= addr_components[i].short_name;
						
							
						$('#'+set_id+'address_1').val(formatter_addr);
						if(contet_type == 'administrative_area_level_2') 	$('#'+set_id+'city').val(contet_value);
						if(contet_type == 'administrative_area_level_1') 	$('#'+set_id+'state').val(contet_sort);
						if(contet_type == 'postal_code') 				$('#'+set_id+'code').val(contet_value);
						if(contet_type == 'country') 					$('#'+set_id+'country').val(contet_sort);
					}
						
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lat = place[0].geometry.location.lat();
							
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lon = place[0].geometry.location.lng();
							
					$('#'+id+'_lat').val(srch_lat);
					$('#'+id+'_lng').val(srch_lon);
						
					//Check for uber api
					//if (set_id == 'dropoff_')
					{
						var pick_up_addr_valid 	= parseInt($("#is_pick_valid").val());
						var drop_of_addr_valid 	= parseInt($("#is_drop_valid").val());
							
						if (pick_up_addr_valid == 1 && drop_of_addr_valid == 1) {
							
							$("#delivery_option_2").removeClass('has_graybox');
								
							var pick_up_addr	= $("#pickup_address").val();
							var drop_of_addr	= $("#dropoff_address").val();
							var pick_addr 		= $("#pick_address_1").val();
							var pick_city 		= $("#pick_city").val();
							var pick_state 	= $("#pick_state").val();
							var pick_code 		= $("#pick_code").val();
							var pick_country 	= $("#pick_country").val();
							var pick_lat 		= $("#pickup_address_lat").val();
							var pick_lng 		= $("#pickup_address_lng").val();
							var dropoff_addr 	= $("#dropoff_address_1").val();
							var dropoff_city 	= $("#dropoff_city").val();
							var dropoff_state 	= $("#dropoff_state").val();
							var dropoff_code 	= $("#dropoff_code").val();
							var dropoff_country = $("#dropoff_country").val();
							var dropoff_lat 	= $("#dropoff_address_lat").val();
							var dropoff_lng 	= $("#dropoff_address_lng").val();
							var is_uber_rush_ok = parseInt($("#is_uber_rush_ok").val());
								
							var dataString 	= "pick_up_addr=" + pick_up_addr + "&drop_of_addr=" + drop_of_addr + '&pick_address_1=' + pick_addr + '&pick_city=' + pick_city + '&pick_state=' + pick_state + '&pick_code=' + pick_code + '&pick_country=' + pick_country + '&pick_lat=' + pick_lat + '&pick_lng=' + pick_lng + '&dropoff_address_1=' + dropoff_addr + '&dropoff_city=' + dropoff_city + '&dropoff_state=' + dropoff_state + '&dropoff_code=' + dropoff_code + '&dropoff_country=' + dropoff_country + '&dropoff_lat=' + dropoff_lat + '&dropoff_lng=' + dropoff_lng;
								
							if (is_uber_rush_ok == 0) {
									
								$("#ajax-loading").show();
								$("#next_btn_2").attr('style', 	'cursor: default; opacity: 0.5;');
								$("#next_btn_2").attr('disabled', 	'disabled');
									
								$.ajax({
									type: 	"GET",
									url: 	"<?php echo base_url().'uber_rush_api_check'; ?>",
									data: 	dataString,
									cache: 	false,
									success: 	function(data){
										var json_data 	= jQuery.parseJSON(data);
										var meta 		= (typeof(json_data.meta) != "undefined") 	? json_data.meta 	: '';
										var errors 	= (typeof(json_data.errors) != "undefined") 	? json_data.errors 	: [];
										var quotes 	= (typeof(json_data.quotes) != "undefined") 	? json_data.quotes 	: [];
											
										if (errors.length > 0) {
											//$("#uber_rush_error").html('Unfortunately On Demand is not available in your area right now, please try a marketplace quote or come back soon, we are adding new locations everyday.');
											//$('body').toggleClass('info-popup-active');
											$("#is_uber_rush_ok").val('0');
										}
										else if(quotes.length > 0){
												
											$("#uber_rush_error").html('');
												
											var quote_html = '';
											for(q = 0; q < quotes.length; q++ )
											{
												quote_html = quote_html +  '<div class="accept-table-row">'
																		+'<div class="accept-table-cell">'
																			+'<figure><img src="<?php echo main_base_url() ?>thumb.php?width=60&height=60&img=<?php echo assets_url() ?>site/images/uber_rush.jpg" alt="red-car" /></figure>'
																			+'<big>$'+quotes[q].price_formated+'</big>'
																		+'</div>'
																		+'<div class="accept-table-cell">'
																			+'<a id="uber_rush_accpt" href="javascript:void(0)" onclick="accept_quote(\''+quotes[q].quote_id+'\', \''+quotes[q].fee+'\', \'uber\')" class="accept-anc">ACCEPT</a>'
																		+'</div>'
																	+'</div>';
												
												if (q == 0) break;
											}
												
											quote_html = quote_html + '<div class="accept-table-row">'
																	+'<div class="accept-table-cell">'
																		+'<figure><img src="<?php echo assets_url() ?>site/images/Postmates.gif" alt="red-car" /></figure>'
																		
																	+'</div>'
																	+'<div class="accept-table-cell">'
																		+'<a href="javascript:void(0)" class="accept-anc">coming soon</a>'
																	+'</div>'
																+'</div>';
											
											quote_html = quote_html + '<div class="accept-table-row">'
																		+'<div class="accept-table-cell">'
																		+'<figure><img src="<?php echo assets_url() ?>site/images/dhl-logo.svg" alt="red-car" /></figure>'
																		
																	+'</div>'
																	+'<div class="accept-table-cell">'
																		+'<a href="javascript:void(0)" class="accept-anc">coming soon</a>'
																	+'</div>'
																+'</div>';
											
											
											$("#uber-rush-quotes-id").show();					
											$("#uber-rush-quotes-list").html(quote_html);	
												
												
											$("#delivery_option_1").attr('class', 'delivery-box');
											$("#delivery_option_2").attr('class', 'delivery-box');
												
											$("#demand").removeAttr('disabled');
											//$("#quote").removeAttr('disabled');
											$("#is_uber_rush_ok").val('1');
										}
										else $("#is_uber_rush_ok").val('0');
											
										$("#ajax-loading").hide();
										$("#next_btn_2").attr('style', 	'');
										$("#next_btn_2").removeAttr('disabled');
									}
								}); 	
							}
						}
							
						var pick_search_lat = $('#pickup_address_lat').val(), 	pick_search_lng = $('#pickup_address_lng').val();
						var drop_search_lat = $('#dropoff_address_lat').val(), drop_search_lng = $('#dropoff_address_lng').val();
							
						p1 = new google.maps.LatLng(pick_search_lat, pick_search_lng), p2 = new google.maps.LatLng(drop_search_lat, drop_search_lng);
							
						calcDistance(p1, p2);
					}
						
				}
				else{
					$("#is_pick_valid").val('0');
					$("#is_drop_valid").val('0');
				}
			})
		});
			
		//calculates distance between two points in km's
		function calcDistance(p1, p2) {
			var 	distance 		= (google.maps.geometry.spherical.computeDistanceBetween(p1, p2));
			var 	proximitymiles = (distance * 0.000621371192).toFixed(2);
				
			$('#distance_val').val(proximitymiles);
		}
	</script>