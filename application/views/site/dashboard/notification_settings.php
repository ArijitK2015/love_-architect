<!-- login screen -->
	<script src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>
	
	<!--Add scripts and css for mobile no verifications-->
	<link rel="stylesheet" href="<?php echo assets_url('site/intl-tel-input/css/intlTelInput.css') ?>" />
	<script src="<?php echo assets_url('site/intl-tel-input/js/intlTelInput.js') ?>"></script>
	
	<script>
		$(document).ready(function() {
			var is_mobile_ok = 0;
			
			$.validator.addMethod("positivenumber", function (value, element, options){
				var bothEmpty = false;
				var data_value = parseFloat(value);
				if (data_value >= 0) bothEmpty = true;
				return bothEmpty;
			},"Please enter positive value.");
			
			$("#add_notification_form").validate({
				submitHandler: function(form) {
					var do_have_mobile 	= parseInt($("#do_have_mobile").val());
					var mobile_num 	= $("#mobile_no").val();
					
					if (do_have_mobile > 0) {
						
						if (mobile_num == ''){ $("#mobile_no-error").html('Please enter proper mobile number without counry code.'); $("#mobile_no-error").show(); }
						else if (mobile_num != ''){
							
							if (parseFloat(mobile_num) > 0) form.submit();
							else{
								$("#mobile_no-error").html('Please enter proper mobile number without counry code.');
								$("#mobile_no-error").show();
							}
						}
						else form.submit();
					}
					else form.submit();
				}
			});
		});
		
		function open_the_notification_det(args) {
			if   ($("."+args).is(":visible") == true){ $("."+args).hide(); $("#small-icon").show(); }
			else{ $("."+args).show();  $("#small-icon").hide(); }
		}
		
		function do_have_mobile_fnc(args) {
			var do_have_mobile = parseInt($("#do_have_mobile").val());
			if(document.getElementById(args).checked) 
				do_have_mobile = do_have_mobile + 1;
			else
				do_have_mobile = (do_have_mobile > 0) ? do_have_mobile - 1 : 0;
			
			$("#do_have_mobile").val(do_have_mobile)
		}
	</script>
	
	<?php
		$do_have_mobile 	 = '0';
		if(isset($user_settings['new_jobs_notification']['sms']) && ($user_settings['new_jobs_notification']['sms'] == 1))
			$do_have_mobile = $do_have_mobile+1;
		if(isset($user_settings['update_jobs_notification']['sms']) && ($user_settings['update_jobs_notification']['sms'] == 1))
			$do_have_mobile = $do_have_mobile+1;
		if(isset($user_settings['quote_submit_jobs_notification']['sms']) && ($user_settings['quote_submit_jobs_notification']['sms'] == 1))
			$do_have_mobile = $do_have_mobile+1;
		if(isset($user_settings['quote_accept_jobs_notification']['sms']) && ($user_settings['quote_accept_jobs_notification']['sms'] == 1))
			$do_have_mobile = $do_have_mobile+1;
		if(isset($user_settings['exception_reports_notification']['sms']) && ($user_settings['exception_reports_notification']['sms'] == 1))
			$do_have_mobile = $do_have_mobile+1;
		if(isset($user_settings['tracking_updates_notification']['sms']) && ($user_settings['tracking_updates_notification']['sms'] == 1))
			$do_have_mobile = $do_have_mobile+1;
		if(isset($user_settings['marketing_emails_notification']['sms']) && ($user_settings['marketing_emails_notification']['sms'] == 1))
			$do_have_mobile = $do_have_mobile+1;
	?>
	
	<div data-role="page" id="signupPage">
		<div data-role="main" class="ui-content notif-map-content">
			<div class="close-container">
				<div class="popup-wrap">
					<div class="user-top">
						<div class="user-img"><img src="<?php echo assets_url('site/images/user-img.jpg') ?>" alt="user-img" /></div>
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
			</div>
			<a href="<?php echo base_url() ?>" data-ajax="false" class="cancel-signup"> <img src="<?php echo assets_url('site/images/cross.png') ?>" alt="cross" /> </a>
			<div class="signup-top">
			    <span>Map and Notifications</span>
			</div>
			
			<form name="add_notification_form" id="add_notification_form" action="<?php echo base_url().'update-notifications' ?>" enctype="multipart/form-data" data-ajax="false" method="post">
				<input type="hidden" name="do_have_mobile" id="do_have_mobile" value="<?php echo $do_have_mobile; ?>" />
				
				<div class="each-block">
					<h3><span>You can choose the specific countries you want to see on the map and receive notifications about. You will see any job that has a start or end point in the countries you select, by default you will see all jobs and receive notifications about all jobs.</span></h3>
					<span class="h3-info">
						<a href="javascript:void(0)" id="small-icon" data-ajax="false" onclick="open_the_notification_det('block-notification')">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="17" height="17.031" viewBox="0 0 17 17.031">
								<path d="M8.502,0.002 C3.802,0.002 -0.009,3.812 -0.009,8.512 C-0.009,13.212 3.802,17.022 8.502,17.022 C13.202,17.022 17.012,13.212 17.012,8.512 C17.012,3.812 13.202,0.002 8.502,0.002 ZM10.015,13.291 C10.015,14.104 9.341,14.763 8.509,14.763 C7.678,14.763 7.004,14.104 7.004,13.291 L7.004,7.654 C7.004,6.840 7.678,6.183 8.509,6.183 C9.341,6.183 10.015,6.840 10.015,7.654 L10.015,13.291 ZM8.502,5.333 C7.634,5.333 6.930,4.644 6.930,3.797 C6.930,2.949 7.634,2.261 8.502,2.261 C9.370,2.261 10.073,2.949 10.073,3.797 C10.073,4.644 9.370,5.333 8.502,5.333 Z" class="cls-1"/>
							</svg>
						</a>
					</span>
					
					<div class="block-notification hide" id="huge-icon">
						<a href="javascript:void(0)" id="small-icon" data-ajax="false" onclick="open_the_notification_det('block-notification')">
							<span class="notif-ico">
							    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="23.343" height="23.344" viewBox="0 0 23.343 23.344">
								   <path d="M11.658,-0.009 C5.212,-0.009 -0.014,5.216 -0.014,11.661 C-0.014,18.107 5.212,23.332 11.658,23.332 C18.104,23.332 23.329,18.107 23.329,11.661 C23.329,5.216 18.104,-0.009 11.658,-0.009 ZM13.733,18.215 C13.733,19.330 12.809,20.234 11.668,20.234 C10.528,20.234 9.604,19.330 9.604,18.215 L9.604,10.485 C9.604,9.369 10.528,8.468 11.668,8.468 C12.809,8.468 13.733,9.369 13.733,10.485 L13.733,18.215 ZM11.657,7.302 C10.468,7.302 9.503,6.357 9.503,5.196 C9.503,4.032 10.468,3.088 11.657,3.088 C12.848,3.088 13.813,4.032 13.813,5.196 C13.813,6.357 12.848,7.302 11.657,7.302 Z" class="cls-1"/>
							    </svg>
							</span>
							<p>You will see any job that has a start or end point in the countries you select, by default you will see all jobs and receive notifications about all.</p>
						</a>
					</div>
					
					<div class="guranteed-row all-country-div">
						<input type="checkbox" id="allCountry" name="is_all_country" value="1" <?php echo (isset($user_job_countries['is_all_countries']) && ($user_job_countries['is_all_countries'] == 1)) ? 'checked' : ''; ?> data-role="none" />
						<label for="allCountry">All Countries</label>
					</div>
					<div class="choose-country-btn-div">
						<a href="<?php echo base_url().'notifications-countries' ?>" class="choose-country-btn" data-ajax="false" data-role="none">Choose Countries</a>
					</div>
					
					<span style="<?php echo (isset($user_job_countries['countries']) && count($user_job_countries['countries']) == 0) ? 'display: none' : ''; ?>" class="country-selected"><?php echo (isset($user_job_countries['countries'])) ? (count($user_job_countries['countries']) > 1) ? (count($user_job_countries['countries'])).' countries' : (count($user_job_countries['countries'])).' country' : '0 countries';  ?> selected</span>
				</div>
			
				<div class="each-block each-block-no-margin">
					<h3><span>Notifications</span></h3>
					<div class="notif-table">
						<div class="notif-tr">
							<div class="notif-th">  Action </div>
							<div class="notif-th">  Email </div>
							<div class="notif-th">  Text Message </div>
						</div>
						<div class="notif-tr">
							<div class="notif-td">  New Jobs </div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="new_jobs[0]" id="new_jobs_email" <?php echo (isset($user_settings['new_jobs_notification']['email']) && $user_settings['new_jobs_notification']['email'] == 1) ? 'checked' : ''; ?> value="1" />
								<label for="new_jobs_email"></label>
							</div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="new_jobs[1]" 	id="new_jobs_sms" <?php echo (isset($user_settings['new_jobs_notification']['sms']) && $user_settings['new_jobs_notification']['sms'] == 1) ? 'checked' : ''; ?> onchange="do_have_mobile_fnc('new_jobs_sms')" value="1" />
								<label for="new_jobs_sms"></label>
							</div>
						</div>
						<div class="notif-tr">
							<div class="notif-td"> Updated Jobs </div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="update_jobs[0]" 	id="update_jobs_email" 	<?php echo (isset($user_settings['update_jobs_notification']['email']) && $user_settings['update_jobs_notification']['email'] == 1) ? 'checked' : ''; ?> value="1" />
								<label for="update_jobs_email"></label>
							</div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="update_jobs[1]" 	id="update_jobs_sms" 	<?php echo (isset($user_settings['update_jobs_notification']['sms']) && $user_settings['update_jobs_notification']['sms'] == 1) ? 'checked' : ''; ?> onchange="do_have_mobile_fnc('update_jobs_sms')" value="1" />
								<label for="update_jobs_sms"></label>
							</div>
						</div>
						<div class="notif-tr">
							<div class="notif-td"> Quote Submission </div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="quote_submit_jobs[0]" 	id="quote_submit_jobs_email" 	<?php echo (isset($user_settings['quote_submit_jobs_notification']['email']) && $user_settings['quote_submit_jobs_notification']['email'] == 1) ? 'checked' : ''; ?> value="1" />
								<label for="quote_submit_jobs_email"></label>
							</div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="quote_submit_jobs[1]" 	id="quote_submit_jobs_sms" <?php echo (isset($user_settings['quote_submit_jobs_notification']['sms']) && $user_settings['quote_submit_jobs_notification']['sms'] == 1) ? 'checked' : ''; ?>  onchange="do_have_mobile_fnc('quote_submit_jobs_sms')" value="1" />
								<label for="quote_submit_jobs_sms"></label>
							</div>
						</div>
						<div class="notif-tr">
							<div class="notif-td"> Quote Acceptance </div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="quote_accept_jobs[0]" 	id="quote_accept_jobs_email" 	<?php echo (isset($user_settings['quote_accept_jobs_notification']['email']) && $user_settings['quote_accept_jobs_notification']['email'] == 1) ? 'checked' : ''; ?> value="1" />
								<label for="quote_accept_jobs_email"></label>
							</div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="quote_accept_jobs[1]" 	id="quote_accept_jobs_sms" <?php echo (isset($user_settings['quote_accept_jobs_notification']['sms']) && $user_settings['quote_accept_jobs_notification']['sms'] == 1) ? 'checked' : ''; ?>  onchange="do_have_mobile_fnc('quote_accept_jobs_sms')" value="1" />
								<label for="quote_accept_jobs_sms"></label>
							</div>
						</div>
						<div class="notif-tr">
							<div class="notif-td"> Exception Reports </div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="exception_reports[0]" 	id="exception_reports_email" 	<?php echo (isset($user_settings['exception_reports_notification']['email']) && $user_settings['exception_reports_notification']['email'] == 1) ? 'checked' : ''; ?> value="1" />
								<label for="exception_reports_email"></label>
							</div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="exception_reports[1]" 	id="exception_reports_sms" <?php echo (isset($user_settings['exception_reports_notification']['sms']) && $user_settings['exception_reports_notification']['sms'] == 1) ? 'checked' : ''; ?>  onchange="do_have_mobile_fnc('exception_reports_sms')" value="1" />
								<label for="exception_reports_sms"></label>
							</div>
						</div>
						<div class="notif-tr">
							<div class="notif-td"> Tracking Updates </div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="tracking_updates[0]" 	id="tracking_updates_email" 	<?php echo (isset($user_settings['tracking_updates_notification']['email']) && $user_settings['tracking_updates_notification']['email'] == 1) ? 'checked' : ''; ?> value="1" />
								<label for="tracking_updates_email"></label>
							</div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="tracking_updates[1]" 	id="tracking_updates_sms"  <?php echo (isset($user_settings['tracking_updates_notification']['sms']) && $user_settings['tracking_updates_notification']['sms'] == 1) ? 'checked' : ''; ?>  onchange="do_have_mobile_fnc('tracking_updates_sms')" value="1" />
								<label for="tracking_updates_sms"></label>
							</div>
						</div>
						<div class="notif-tr">
							<div class="notif-td"> Marketing Emails </div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="marketing_emails[0]" 	id="marketing_emails_email" 	<?php echo (isset($user_settings['marketing_emails_notification']['email']) && $user_settings['marketing_emails_notification']['email'] == 1) ? 'checked' : ''; ?> value="1" />
								<label for="marketing_emails_email"></label>
							</div>
							<div class="notif-td guranteed-row">
								<input type="checkbox" data-role="none" name="marketing_emails[1]" 	id="marketing_emails_sms" <?php echo (isset($user_settings['marketing_emails_notification']['sms']) && $user_settings['marketing_emails_notification']['sms'] == 1) ? 'checked' : ''; ?>  onchange="do_have_mobile_fnc('marketing_emails_sms')"	value="1" />
								<label for="marketing_emails_sms"></label>
							</div>
						</div>
					</div>
					
					<h3><span>Send text messages to this number</span></h3>
					<div class="input-phone-number">
						<input type="hidden" name="country_code" id="country_code" value="<?php echo (isset($myaccount_data['country_code'])) ? $myaccount_data['country_code'] : 'US'; ?>" />
						<input type="hidden" name="phone_code" id="phone_code" value="<?php echo (isset($myaccount_data['phone_code'])) ? $myaccount_data['phone_code'] : '1'; ?>" />
						<input name="mobile_no" value="<?php echo (isset($myaccount_data['user_phone']) && isset($myaccount_data['phone_code']) && (!empty($myaccount_data['user_phone']))) ? '+'.$myaccount_data['phone_code'].$myaccount_data['user_phone'] : '+1'; ?>" id="mobile_no" type="tel" placeholder="Phone Number" minlength="10" maxlength="15" autocomplete="off" class="background-input" data-role="none" />
					</div>	
					<label id="mobile_no-error" class="error" for="mobile_no"></label>
				</div>
				<div class="notif-submit">
					<input type="submit" class="submit-leg" data-role="none" value="DONE" />
				</div>
			</form>
		</div>
	</div>
	
	<script>
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
	</script>
