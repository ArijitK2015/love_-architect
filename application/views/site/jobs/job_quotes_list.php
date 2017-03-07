	<?php
			
		$default_site_logo 			= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
		$success 					= $error = $pay_amount = '';
		$stripe_type 				= (isset($settings['stripe_pay_type'])) ? $settings['stripe_pay_type'] : 2;
		$stripe_secret_key 			= $stripe_public_key = $user_stripe_id = $user_stripe_card_id = $user_card_status = $card_user_name = '';
		$card_last_digits			= $card_brand = $exp_year = $exp_month = $cvv_code = ''; $user_has_acard = 0;
			
		if($stripe_type == 1){
			$stripe_secret_key 		= (isset($settings['stripe_live_secret_key'])) ? $settings['stripe_live_secret_key'] : '';
			$stripe_public_key 		= (isset($settings['stripe_live_public_key'])) ? $settings['stripe_live_public_key'] : '';
		}
		else{
			$stripe_secret_key 		= (isset($settings['stripe_sandbox_secret_key'])) ? $settings['stripe_sandbox_secret_key'] : '';
			$stripe_public_key 		= (isset($settings['stripe_sandbox_public_key'])) ? $settings['stripe_sandbox_public_key'] : '';
		}
			
		$card_user_name = $user_stripe_id = $user_stripe_card_id = $user_card_status = $card_last_digits = $card_brand = $exp_year = $exp_month = $cvv_code = '';
		$user_has_acard = 0;
			
		if(!empty($user_stripe_data)){
			$card_user_name 		= (isset($user_stripe_data['name_on_card'])) 	? $user_stripe_data['name_on_card'] 	: '';
			$user_stripe_id		= (isset($user_stripe_data['stripe_id'])) 		? $user_stripe_data['stripe_id'] 		: '';
			$user_stripe_card_id	= (isset($user_stripe_data['card_id'])) 		? $user_stripe_data['card_id'] 		: '';
			$user_card_status		= (isset($user_stripe_data['card_status'])) 		? $user_stripe_data['card_status'] 	: '';
			$user_has_acard		= 1;
			
			$card_last_digits		= (isset($user_stripe_data['card_last_digits'])) 	? '********'.$user_stripe_data['card_last_digits'] : '';
			$card_brand			= (isset($user_stripe_data['card_brand'])) 		? $user_stripe_data['card_brand'] 		: '';
			$exp_year				= (isset($user_stripe_data['exp_year'])) 		? $user_stripe_data['exp_year'] 		: '';
			$exp_month			= (isset($user_stripe_data['exp_month'])) 		? $user_stripe_data['exp_month'] 		: '';
			$cvv_code				= (isset($user_stripe_data['cvv_code'])) 		? $user_stripe_data['cvv_code'] 		: '';
		}
			
		$flash_message 			= $this->session->flashdata('flash_message');
		$flash_message_cont 		= $this->session->flashdata('flash_message_cont');
		$flash_message_job_id 		= $this->session->flashdata('flash_message_job_id');
		$flash_message_job			= $this->session->flashdata('flash_message_job');
		$ondemand_job_id			= $this->session->flashdata('ondemand_job_id');
		
		//echo 'arijit: '.$flash_message.' 2: '.$flash_message_cont.' 3. '.$flash_message_job_id.' 4: '.$flash_message_job;
		//die;
	?>
	
	<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.touchSwipe.min.js'); ?>"></script>
	
	<script>
		var job_id 	= '<?php echo $job_id; ?>';
		var user_id 	= '<?php echo $user_id; ?>';
		var flash_msg	= '';
	</script>
		
	<link href="<?php echo assets_url('site/css/jquery.mCustomScrollbar.min.css') ?>" rel="stylesheet">
		
	<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.mCustomScrollbar.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>	
		
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript" src="<?php echo assets_url('site/js/bootstrapValidator-min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.creditCardValidator.js'); ?>"></script>	
		
	<script>
		var  job_det 			= {},
			job_quote_leg_det 	= {},
			user_det 			= {},
			user_id 			= '',
			user_name 		= '',
			user_image 		= '',
			job_quotes 		= '',
			job_prices 		= '',
			job_prices_arr		= '',
			job_quote_dates 	= '',
			quote_user_det 	= '',
			quote_user_rating 	= '',
			job_prices_extra 	= '',
			job_prices_extra_arr= '',
			job_total_prices 	= '',
			job_total_prices_arr= '',
			all_job_quotes_html = '',
			all_job_legs_html 	= '',
			all_quote_ids 		= [],
			only_legs 		= 0;
			
		var  card_user_name 	= '<?php echo $card_user_name; ?>',
			user_stripe_id		= '<?php echo $user_stripe_id; ?>',
			user_stripe_card_id	= '<?php echo $user_stripe_card_id; ?>',
			user_card_status	= '<?php echo $user_card_status; ?>',
			user_has_acard 	= '<?php echo $user_has_acard; ?>',
			card_last_digits 	= '<?php echo $card_last_digits; ?>',
			card_brand		= '<?php echo $card_brand; ?>',
			exp_year			= '<?php echo $exp_year; ?>',
			exp_month			= '<?php echo $exp_month; ?>',
			cvv_code			= '<?php echo $cvv_code; ?>';
	</script>
		
	<script type="text/javascript" src="<?php echo assets_url('site/js/pages/dashboard-index.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo assets_url('site/js/pages/my-job-qoutes.js'); ?>"></script>
		
	<script>
		// this identifies your website in the createToken call below
		Stripe.setPublishableKey('<?php echo $stripe_public_key; ?>');

		function stripeResponseHandler(status, response) {
			//console.log('arijit check valid');
			
			if (response.error) {
				var  error_type 	= response.error.type,
					error_message 	= response.error.message;
				
				$("#payment-card-error").html(error_message);
				$('#submit-pay').removeAttr("disabled");
				$("#pay_loading").hide();
				
			} else {
				$('#submit-pay').attr("disabled");
				var form$ = $("#payment-form");
				// token contains id, last4, and card type
				var token = response['id'];
				// insert the token into the form so it gets submitted to the server
				form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
				// and submit
				form$.get(0).submit();
			}
		}
			
		function close_overlay(args) {
			$(".overlay-popup").hide();
			$('body').removeClass('info-popup-active');
		}
	</script>
		
	<!--Default page loader section-->
	<div id="loading-filter-background" style="display: block;">
		<div id="loading-filter-image">
			<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
		</div>
		<div class="loading-text">Loading</div>
	</div>
		
		
	<div data-role="page" id="signupPage">
		<div data-role="main" class="ui-content notif-map-content">
			<!-- <div class="close-container">
					<div class="popup-wrap">
						<div class="user-top">
							<div class="user-img"><img src="<?php echo assets_url('site/images/user-img.jpg'); ?>" alt="user-img" /></div>
							<h3>David James</h3>
						</div>
						<div class="popup-form">
							<div class="terms">
								<div class="terms-scroll custom-scrollbar">
									<div class="terms-content">
										<p class="big-text">408 Miles - Fort Lauderdale <br />
										Deliver By: 1st June 2016 <br />
										12 Tonnes / 3 Pallets <br />
										ForkLift Required</p>
									</div>
								</div>
							</div>
							<div class="popup-btns">
								<input type="button" value="SEND MESSAGE" class="submit-leg" data-role="none" />
								<input type="button" value="SUBMIT LEG" class="submit-leg" data-role="none" />
								<input type="button" value="SUBMIT QUOTE" class="submit-leg" data-role="none" />
							</div>
						</div>
					</div>
			</div> -->
				
			<div class="close-container help-info-popup overlay-popup">
				<div class="close-content">
					<h3 id="info_title">Infotmation</h3>
					<div class="close-para">
						<p id="info_content">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
					</div>
					<div class="close-footer">
						<a href="javascript:void(0)" onclick="close_overlay('overlay-popup')" class="close-btn-right">Close</a>
					</div>
				</div>
			</div>
				
			<div id="popup_cont">
				<div class="close-container overflow_content">
					<div class="close-content transparent">
						<div class="popup-wrap">
							<input type="hidden" name="now_show" id="now_show" value="" />
							<div class="close-footer">
								<a href="javascript:void(0)" class="close-btn popup-close"><img src="<?php echo assets_url('site/images/cross.png') ?>" alt="cross"></a>
							</div>
							<div class="user-top" id="user_img">
								<div class="user-img"><?php echo '<img id="job-user-image" src="'.assets_url('site/images/user-image.png').'" alt="user-img">'; ?></div>
								<h3 id="job-user-name"></h3>
							</div>
							<div class="popup-form" id="loading_content">
								<div class="loading-class">
									<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
								</div>
								<div class="loading-text-new">Loading</div>
							</div>
							<div class="popup-form hide" id="main_cont">
								
							</div>
							
							<div class="popup-form hide" id="leg_cont">
								<form name="leg_job_form" id="leg_job_form" data-ajax="false" action="" method="post">
									
									<input type="hidden" name="leg_job_id" id="leg_job_id" value="" />
									<input type="hidden" name="leg_user_id" id="leg_user_id" value="" />
									<input type="hidden" name="submit_type" id="submit_type" value="2" />
									
									<div id="leg_cont_show">
										<div class="signup-row">
											<div class="popup-form-box popup-form-left">
												<div class="selectForm">
													<select name="pick_addr_id" id="pick_addr_id" onchange="choosed_pickdrop_det(this.value, 'leg_pickup_addr')">
														<option>Address</option>
													</select>
													<a href="javascript:void(0)" class="dropdownA ui-link">
														<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
															<path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"></path>
														</svg>
													</a>
												</div>
											</div>
											<div class="popup-form-box popup-form-right">
												<input type="text" name="leg_start" id="leg_start" placeholder="Date" class="form-controls date-picker" data-role="none" readonly="readonly" />
												<a href="javascript:void(0)" class="dropdownA ui-link">
													<svg xmlns="http://www.w3.org/2000/svg" width="29" height="18" viewBox="0 0 46 36">
														<path d="M45.029,0.004 L0.003,0.004 L0.003,3.003 L0.003,9.003 L0.003,36.000 L48.031,36.000 L48.031,0.004 L45.029,0.004 ZM18.013,15.002 L12.010,15.002 L12.010,9.003 L18.013,9.003 L18.013,15.002 ZM21.015,9.003 L27.019,9.003 L27.019,15.002 L21.015,15.002 L21.015,9.003 ZM18.013,18.002 L18.013,24.001 L12.010,24.001 L12.010,18.002 L18.013,18.002 ZM21.015,18.002 L27.019,18.002 L27.019,24.001 L21.015,24.001 L21.015,18.002 ZM30.020,18.002 L36.024,18.002 L36.024,24.001 L30.020,24.001 L30.020,18.002 ZM30.020,15.002 L30.020,9.003 L36.024,9.003 L36.024,15.002 L30.020,15.002 ZM3.005,9.003 L9.008,9.003 L9.008,15.002 L3.005,15.002 L3.005,9.003 ZM3.005,18.002 L9.008,18.002 L9.008,24.001 L3.005,24.001 L3.005,18.002 ZM3.005,33.000 L3.005,27.001 L9.008,27.001 L9.008,33.000 L3.005,33.000 ZM12.010,33.000 L12.010,27.001 L18.013,27.001 L18.013,33.000 L12.010,33.000 ZM21.015,33.000 L21.015,27.001 L27.019,27.001 L27.019,33.000 L21.015,33.000 ZM30.020,33.000 L30.020,27.001 L36.024,27.001 L36.024,33.000 L30.020,33.000 ZM45.029,33.000 L39.026,33.000 L39.026,27.001 L45.029,27.001 L45.029,33.000 ZM45.029,24.001 L39.026,24.001 L39.026,18.002 L45.029,18.002 L45.029,24.001 ZM45.029,15.002 L39.026,15.002 L39.026,9.003 L42.027,9.003 L45.029,9.003 L45.029,15.002 Z" class="calNew"></path>
													</svg>	
												</a>
											</div>
											<label id="leg_start-error" class="error" for="leg_start"></label>
										</div>
										<div class="signup-row big-font first-sign-up-row"  id="leg_pickup_addr_div">
											<input type="text" name="leg_pickup_addr" id="leg_pickup_addr" placeholder="Pickup Address" class="form-controls inp-address" data-role="none" />
											<input type="hidden" name="leg_pickup_addr_lat" id="leg_pickup_addr_lat" data-role="none" />
											<input type="hidden" name="leg_pickup_addr_long" id="leg_pickup_addr_long" data-role="none" />
											
											<a href="javascript:void(0)" onclick="get_current_latlng('leg_pickup_addr')" class="signup-inp-ico">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
													<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"/>
												</svg>
											</a>
										</div>
										<div class="signup-row">
											<div class="popup-form-box popup-form-left">
												<div class="selectForm">
													<select name="drop_addr_id" id="drop_addr_id" onchange="choosed_pickdrop_det(this.value, 'leg_drop_addr')">
														<option>Address</option>
													</select>
													<a href="javascript:void(0)" class="dropdownA ui-link">
														<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
															<path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"></path>
														</svg>
													</a>
												</div>
											</div>
											<div class="popup-form-box popup-form-right">
												<input type="text" name="leg_end" id="leg_end" placeholder="Date" class="form-controls date-picker" data-role="none" readonly="readonly" />
												<a href="javascript:void(0)" class="dropdownA ui-link">
													<svg xmlns="http://www.w3.org/2000/svg" width="29" height="18" viewBox="0 0 46 36">
														<path d="M45.029,0.004 L0.003,0.004 L0.003,3.003 L0.003,9.003 L0.003,36.000 L48.031,36.000 L48.031,0.004 L45.029,0.004 ZM18.013,15.002 L12.010,15.002 L12.010,9.003 L18.013,9.003 L18.013,15.002 ZM21.015,9.003 L27.019,9.003 L27.019,15.002 L21.015,15.002 L21.015,9.003 ZM18.013,18.002 L18.013,24.001 L12.010,24.001 L12.010,18.002 L18.013,18.002 ZM21.015,18.002 L27.019,18.002 L27.019,24.001 L21.015,24.001 L21.015,18.002 ZM30.020,18.002 L36.024,18.002 L36.024,24.001 L30.020,24.001 L30.020,18.002 ZM30.020,15.002 L30.020,9.003 L36.024,9.003 L36.024,15.002 L30.020,15.002 ZM3.005,9.003 L9.008,9.003 L9.008,15.002 L3.005,15.002 L3.005,9.003 ZM3.005,18.002 L9.008,18.002 L9.008,24.001 L3.005,24.001 L3.005,18.002 ZM3.005,33.000 L3.005,27.001 L9.008,27.001 L9.008,33.000 L3.005,33.000 ZM12.010,33.000 L12.010,27.001 L18.013,27.001 L18.013,33.000 L12.010,33.000 ZM21.015,33.000 L21.015,27.001 L27.019,27.001 L27.019,33.000 L21.015,33.000 ZM30.020,33.000 L30.020,27.001 L36.024,27.001 L36.024,33.000 L30.020,33.000 ZM45.029,33.000 L39.026,33.000 L39.026,27.001 L45.029,27.001 L45.029,33.000 ZM45.029,24.001 L39.026,24.001 L39.026,18.002 L45.029,18.002 L45.029,24.001 ZM45.029,15.002 L39.026,15.002 L39.026,9.003 L42.027,9.003 L45.029,9.003 L45.029,15.002 Z" class="calNew"></path>
													</svg>	
												</a>
											</div>
											<label id="leg_end-error" class="error" for="leg_end"></label>
										</div>
										<div class="signup-row big-font" id="leg_drop_addr_div">
											<input type="text" name="leg_drop_addr" id="leg_drop_addr" placeholder="Drop Address" class="form-controls inp-address" data-role="none" />
											<input type="hidden" name="leg_drop_addr_lat" id="leg_drop_addr_lat" data-role="none" />
											<input type="hidden" name="leg_drop_addr_long" id="leg_drop_addr_long" data-role="none" />
											
											<a href="javascript:void(0)" onclick="get_current_latlng('leg_drop_addr')" class="signup-inp-ico">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
													<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"/>
												</svg>
											</a>
										</div>
									</div>
									
									<div id="leg_trms_show" class="hide">
										<div class="terms">
											<h3>Terms And Conditions</h3>
											<div class="terms-scroll custom-scrollbar">
												<div class="terms-content">
													<?php echo str_replace('\n', '<br>', $terms_conditions); ?>
												</div>
											</div>
										</div>
									</div>
									
									<div class="terms-anc">
										<a id="term_click" href="javascript:void(0)" onclick="show_terms('leg_trms_show', 'leg_cont_show')">See Terms &amp; Conditions</a>
									</div>
									<div class="quote-price">
										<label>Quote Price</label>
										<div class="quoted-input">
											<span>$</span>
											<input name="job_leg_price" id="job_leg_price" type="text" autocomplete="off" placeholder="0" value="" data-role="none">
										</div>
										<label id="job_leg_price-error" class="error" for="job_leg_price"></label>
									</div>
									<div class="agree-terms">
										<input name="leg_term_agree" id="leg_term_agree" type="checkbox" checked value="1" class="required" data-role="none" />
										<label for="leg_term_agree">Agree Terms &amp; Conditions</label>
									</div>
									<div class="popup-btns">
										<!--<input type="submit" value="SUBMIT LEG" class="submit-leg" data-role="none" />-->
										<button type="submit" id="submit-leg" class="submit-leg" data-role="none">Submit Leg <i id="leg_loading" class="fa fa-spinner fa-pulse fa-3x fa-fw hide"></i></button>
										<label id="leg-form-error" class="error"></label>
									</div>
								</form>
							</div>

							<div class="popup-form hide" id="quote_cont">
								<div class="terms">
									<h3>Terms And Conditions</h3>
									<div class="terms-scroll custom-scrollbar">
										<div class="terms-content">
											<?php echo str_replace('\n', '<br>', $terms_conditions); ?>
										</div>
									</div>
								</div>
								<form name="quote_job_form" id="quote_job_form" data-ajax="false" action="" method="post">
									<input type="hidden" name="quote_job_id" 	id="quote_job_id" value="" />
									<input type="hidden" name="quote_user_id" 	id="quote_user_id" value="" />
									<input type="hidden" name="submit_type" 	id="submit_type" value="1" />
									
									<div class="quote-price">
										<label>Quote Price</label>
										<div class="quoted-input">
											<span>$</span>
											<input name="job_price" id="job_price" type="text" autocomplete="off" placeholder="0" value="" data-role="none">
										</div>
										<label id="job_price-error" class="error" for="job_price"></label>
									</div>
									<div class="agree-terms">
										<input name="term_agree" id="term_agree" type="checkbox" checked value="1" class="required" data-role="none" />
										<label for="term_agree">Agree Terms &amp; Conditions</label>
									</div>
									<div class="popup-btns">
										<!--<input type="submit" value="SUBMIT QUOTE" id="submit-quote" class="submit-leg" data-role="none" />-->
										<button type="submit" id="submit-quote" class="submit-leg" data-role="none">Submit Quote <i id="quote_loading" class="fa fa-spinner fa-pulse fa-3x fa-fw hide"></i></button>
										<label id="quote-form-error" class="error"></label>
									</div>
								</form>
							</div>
							
							<div class="popup-form hide" id="msg_cont">
								
							</div>
							
							<div class="popup-form hide" id="job_quote_list_cont">
								<form name="payment-form" data-ajax="false" id="payment-form" action="<?php echo base_url().'make-payment' ?>" method="post">
									<input type="hidden" name="current_show_li" 		id="current_show_li" 	value="0" />
										
									<input type="hidden" name="total_quote_legs" 	id="total_quote_legs" 	value="" />
									<input type="hidden" name="current_quote_id" 	id="current_quote_id"	value="" />
									<input type="hidden" name="current_job_id" 		id="current_job_id" 	value="" />
										
									<input type="hidden" name="to_be_pay" 			id="to_be_pay" 		value="10.00" />
									<input type="hidden" name="to_be_refund" 		id="to_be_refund" 		value="0" />
										
									<input type="hidden" name="deduction_amount" 	id="deduction_amount" 	value="0" />
									<input type="hidden" name="deduction_percent" 	id="deduction_percent" 	value="" />
									<input type="hidden" name="extra_amount" 		id="extra_amount" 		value="0" />
									<input type="hidden" name="extra_percent" 		id="extra_percent" 		value="" />
									<input type="hidden" name="extra_days" 			id="extra_days" 		value="0" />
									<input type="hidden" name="pay_currency" 		id="pay_currency" 		value="usd" />
										
									<input type="hidden" name="current_stripe_id" 	id="current_stripe_id" 	value="<?php echo $user_stripe_id; ?>" />
									<input type="hidden" name="user_stripe_card_id" 	id="user_stripe_card_id" value="<?php echo $user_stripe_card_id; ?>" />
									<input type="hidden" name="user_card_status" 	id="user_card_status" 	value="<?php echo $user_card_status; ?>" />
										
									<input type="hidden" name="user_has_acard" 		id="user_has_acard" 	value="<?php echo $user_has_acard; ?>" />
									
									<ul class="pick-date">
										<li id="pickup_date">
											<span>Pick Up Date</span>
											<big>&nbsp;</big>
										</li>
										<li id="drop_date">
											<span>Delivery Date</span>
											<big>&nbsp;</big>
										</li>
									</ul>
								
									<div class="user-list custom-scrollbar" id="job_quote_leg_lists_outer">
										<ul id="job_quote_lists"></ul>
									</div>
								
									<div id="leg_trms_show1" class="hide">
										<div class="terms">
											<h3>Terms And Conditions</h3>
											<div class="terms-scroll custom-scrollbar">
												<div class="terms-content">
													<?php echo str_replace('\n', '<br>', $terms_conditions); ?>
												</div>
											</div>
										</div>
									</div>
								
									<ul class="calc-table">
										<li class="">
											<div class="calc-table-left">
												<span>
													<a info-cont="Refundable Deposit" href="javascript:void(0)" class="infoI">
														<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
															<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"></path>
														</svg>
													</a>
													<span id="refundable_dep">Refundable Deposit (20%)</span>
												</span>
											</div>
											<div class="calc-table-right" id="extra_job_price">$</div>
										</li>
										<li class="bold-style">
											<div class="calc-table-left">
												<span>
													<a info-cont="Total amount of the job." href="javascript:void(0)" class="infoI">
														<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
															<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"></path>
														</svg>
													</a>
													Total
												</span>
											</div>
											<div class="calc-table-right" id="total_job_price">$</div>
										</li>
										<li class="select-credit">
											<div class="calc-table-left">
												<span>
													<a info-cont="Payment methods you can use to pay." href="javascript:void(0)" class="infoI">
														<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
															<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"></path>
														</svg>
													</a>
													Financing
												</span>
											</div>
											<div class="calc-table-right">
												<div class="selectForm">
													<select name="payment_type" id="payment_type" onchange="show_hide_pay_sec(this.value, 'payment_section')">
														<?php
															if(!empty($payment_types))
															{
																foreach($payment_types as $payment)
																{
																	$pytitle 	= (isset($payment['title'])) ? $payment['title'] : '';
																	$extra_p = 0; $extra_t  = $extra_d = '';
																	if(isset($payment['extra_percent']) && !empty($payment['extra_percent'])){
																		$pytitle = $pytitle.' (+'.$payment['extra_percent'].'%)';
																		$extra_p = $payment['extra_percent'];
																		$extra_t = '+';
																		$extra_d = (isset($payment['max_days'])) ? $payment['max_days'] : '';
																	}
																	elseif(!empty($payment['reduct_percent'])){
																		$pytitle = $pytitle.' (-'.$payment['reduct_percent'].'%)';
																		$extra_p = $payment['reduct_percent'];
																		$extra_t = '-';
																		$extra_d = (isset($payment['max_days'])) ? $payment['max_days'] : '';
																	}
																	
																	echo '<option extra_p="'.$extra_p.'" extra_t="'.$extra_t.'" extra_d="'.$extra_d.'" value="'.$payment['sort_code'].'">'.ucwords($pytitle).'</option>';
																}
															}
														?>
													</select>
													<a href="javascript:void(0)" class="dropdownA ui-link no-width"></a>
												</div>
											</div>
										</li>
									</ul>
										
									<div class="agree-terms">
										<input type="checkbox" id="agree" name="agree" checked data-role="none" value="1" />
										<label for="agree">Agree Terms &amp; Conditions</label>
									</div>
										
									<div class="terms-anc calc-terms-anc">
										<a href="javascript:void(0)" onclick="show_terms('leg_trms_show1', 'job_quote_leg_lists_outer')">See Terms And Conditions</a>
									</div>
										
									<label id="agree-error" class="error"></label>
										
									<div class="popup-accpt-btns" id="accept-button">
										<button type="button" id="accept-button-btn" onclick="open_pay_sec('accept-button', 'payment_section')" class="submit-leg acc-btn" data-role="none">Accept</button>
										<input type="button" id="send-sms-btn" onclick="send_pop_msg()" value="Send Message" class="submit-leg hide" data-role="none" />
										<button type="button" id="accept-activity-btn" onclick="open_activity_sec('activity-button', 'activity_section')" class="submit-leg hide" data-role="none">View Activity</button>
									</div>
										
									<div id="payment_section" class="hide">
										<div class="">
											<div class="alert alert-danger" id="a_x200" style="display: none;">
												<strong>Error!</strong> <span class="payment-errors"></span>
											</div>
											<span class="payment-success">
											</span>
											
											<?php $current_class = ($user_has_acard) ? 'readonly="readonly"' : ''; ?>
											
											<fieldset>
												<?php
													if($current_class == 'readonly="readonly"')
													{
														echo '<div class="ui-grid-b">
																<div class="signup-row guranteed-row radio-row ui-block-a">
																	<input checked id="use_existing1" name="use_existing" onclick="change_pay_card_det(1)" type="radio" data-role="none" value="1">
																	<label for="use_existing1">Existing Card</label>
																 </div>
																 <div class="signup-row guranteed-row radio-row ui-block-b">
																	<input id="use_existing2" name="use_existing" onclick="change_pay_card_det(2)" type="radio" data-role="none" value="2">
																	<label for="use_existing2">New Card</label>
																 </div>
															</div>';
													}
												?>
												
												<!-- Card Holder Name -->
												<div class="signup-row smallFont">
													<input type="text" name="cardholdername" id="cardholdername" data-role="none" maxlength="70" placeholder="Card Holder Name"  value="<?php echo $card_user_name ?>" class="card-holder-name form-controls" <?php echo $current_class ?>>
												</div>
												
												<!-- Card Number -->
												<div class="signup-row smallFont">
													<input type="text" name="cardnumber" id="cardnumber" data-role="none" id="cardnumber" maxlength="19" placeholder="Card Number" value="<?php echo $card_last_digits ?>" class="card-number form-controls" <?php echo $current_class ?>>
												</div>
												
												<!-- Expiry-->
												<div class="signup-row selectForm ui-grid-b">
													<div class="selectForm ui-block-a year-month-drop">
														<select name="expirymonth" id="expirymonth" data-stripe="exp-month" <?php echo $current_class; ?> style="<?php echo ($current_class == 'readonly') ? ' pointer-events: none;' : ''; ?>">
															<option value="01" <?php echo ($exp_month == 1) ? 'selected' : ''; ?>>01</option>
															<option value="02" <?php echo ($exp_month == 2) ? 'selected' : ''; ?>>02</option>
															<option value="03" <?php echo ($exp_month == 3) ? 'selected' : ''; ?>>03</option>
															<option value="04" <?php echo ($exp_month == 4) ? 'selected' : ''; ?>>04</option>
															<option value="05" <?php echo ($exp_month == 5) ? 'selected' : ''; ?>>05</option>
															<option value="06" <?php echo ($exp_month == 6) ? 'selected' : ''; ?>>06</option>
															<option value="07" <?php echo ($exp_month == 7) ? 'selected' : ''; ?>>07</option>
															<option value="08" <?php echo ($exp_month == 8) ? 'selected' : ''; ?>>08</option>
															<option value="09" <?php echo ($exp_month == 9) ? 'selected' : ''; ?>>09</option>
															<option value="10" <?php echo ($exp_month == 10) ? 'selected' : ''; ?>>10</option>
															<option value="11" <?php echo ($exp_month == 11) ? 'selected' : ''; ?>>11</option>
															<option value="12" <?php echo ($exp_month == 12) ? 'selected' : ''; ?>>12</option>
														</select>
														<a href="javascript:void(0)" class="dropdownA ui-link"></a>
													</div>
													<div class="ui-block-b mdivider"><span> / </span></div>
													<div class="selectForm ui-block-c year-month-drop">
														<select name="expyear" id="expyear" data-stripe="exp-year" <?php echo $current_class; ?> style="<?php echo ($current_class == 'readonly') ? ' pointer-events: none;' : ''; ?>">
														</select>
														<a href="javascript:void(0)" class="dropdownA ui-link"></a>
													</div>
													<script type="text/javascript">
														var select = $("#expyear"),
														currentyear= '<?php echo $exp_year; ?>',
														year = new Date().getFullYear();
														
														for (var i = 0; i < 20; i++) {
															var selected = (currentyear == (i + year)) ? 'selected' : '';
															select.append($("<option "+selected+" value='"+(i + year)+"' "+(i === 0 ? "selected" : "")+">"+(i + year)+"</option>"));
														}
													</script> 
												</div>
												<label id="expirymonth-error" class="error" for="expirymonth"></label>
												
												<!-- CVV -->
												<div class="signup-row smallFont">
													<input type="text" name="cvv" id="cvv" data-role="none" id="cvv" placeholder="Cvv" maxlength="4" class="card-cvc form-controls"  <?php echo $current_class ?> value="<?php echo $cvv_code ?>">
												</div>
												  
												<!-- Important notice -->
												<div class="popup-accpt-btns">
													<!--<button class="submit-leg" id="submit-pay" data-role="none" type="submit">Pay</button>-->
													<button class="submit-leg" id="submit-pay" data-role="none" type="submit">Pay <i id="pay_loading" class="fa fa-spinner fa-pulse fa-3x fa-fw hide"></i></button>
													<p class="error" id="payment-card-error"></p>
													<p class="error" id="cardnumber-error"></p>
												</div>
											</fieldset>
										</div>
									</div>
									
									<div class="popup-accpt-bottom-btns">
										<a href="javascript:void(0)" id="decline_btn" onclick="decline_legs()" class="dec-ne-btn" data-role="none">Decline</a>
										<a href="javascript:void(0)" id="donext_btn" onclick="show_next_leg()" class="dec-ne-btn" data-role="none">Next</a>
										<a href="javascript:void(0)" class="strp-btn" data-role="none">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="27.719" height="14.594" viewBox="0 0 27.719 14.594">
												<path d="M26.506,8.264 L1.218,8.264 C0.547,8.264 0.002,7.720 0.002,7.049 C0.002,6.378 0.547,5.834 1.218,5.834 L26.506,5.834 C27.178,5.834 27.722,6.378 27.722,7.049 C27.722,7.720 27.178,8.264 26.506,8.264 ZM26.506,2.432 L1.218,2.432 C0.547,2.432 0.002,1.888 0.002,1.217 C0.002,0.546 0.547,0.002 1.218,0.002 L26.506,0.002 C27.178,0.002 27.722,0.546 27.722,1.217 C27.722,1.888 27.178,2.432 26.506,2.432 ZM1.218,12.152 L26.506,12.152 C27.178,12.152 27.722,12.695 27.722,13.366 C27.722,14.037 27.178,14.581 26.506,14.581 L1.218,14.581 C0.547,14.581 0.002,14.037 0.002,13.366 C0.002,12.695 0.547,12.152 1.218,12.152 Z" class="cls-1"/>
											</svg>
										</a>
									</div>
								</form>
								
								<form name="activity_job" data-ajax="false" id="activity_job" method="POST" action="<?php echo base_url(); ?>job-activities">
									<input type="hidden" name="job_id" id="job_id" value="" />
								</form>
							</div>
							
							<div class="popup-form hide" id="error_cont">
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<a data-ajax="false" href="<?php echo base_url().'my-jobs' ?>" class="cancel-signup">
				<img src="<?php echo assets_url(); ?>site/images/cross.png" alt="cross" />
			</a>
			<div class="signup-top">
				<span id="total_qoutes">Quotes (0)</span>
			</div>
				
			<div id="qoutes_result" class="quote-table">
				
			</div>
		</div>