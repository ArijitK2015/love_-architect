	
	<link rel="stylesheet" href="<?php echo assets_url('site/bootstrap-fileupload/bootstrap-fileupload.css') ?>" />
	<script src="<?php echo assets_url('site/bootstrap-fileupload/bootstrap-fileupload.js') ?>"></script>

	<script src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>
	<script src="<?php echo assets_url('site/js/additional-methods.min.js') ?>"></script>
	
	
	<script>
		var p1 = p2 = '';
		var show_addr_id = srch_lat = srch_lng = '';
		
		$(document).ready(function() {
			init();
			
			$.validator.addMethod("positivenumber", function (value, element, options){
				var bothEmpty = false;
				var data_value = parseFloat(value);
				if (data_value >= 0) bothEmpty = true;
				return bothEmpty;
			},"Please enter positive value.");
			
			$("#datepicker").datepicker({minDate : 'today'});
	
			$('.select-date-row a.dropdownA').on('click', function(){
				$('.date-input').trigger('focus');
			});
		 
			$('.infoI, .close-container, .close-btn, .new-info-button' ).on('click', function(){
				//var info_title 	= $(this).attr('info-cont');
				var info_content 	= $(this).attr('info-cont');
				if (info_content != '' && info_content != 'undefined') $("#info_content").html(info_content);
				else $("#info_content").html('');
				
				$('body').toggleClass('info-popup-active');
			});
		 
			$('.close-content').on('click', function(e){
				e.stopPropagation();
			});
		});
	
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
						
						if (show_addr_id == 'drop_address') {
							var pick_search_lat = $('#pickup_address_lat').val(), 	pick_search_lng = $('#pickup_address_lng').val();
							var drop_search_lat = srch_lat, 					drop_search_lng = srch_lon;
							
							p1 = new google.maps.LatLng(pick_search_lat, pick_search_lng), p2 = new google.maps.LatLng(drop_search_lat, drop_search_lng);
							
							calcDistance(p1, p2);
						}
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
	
		$(document).ready(function(){
			$("#add_job_form").validate({
				rules: {
					cargo_value: 	{
								required: true,
								positivenumber: true
							},
					job_image: 	{
								//required: true,
								accept: "image/*"
							},
					max_job_price: 	{
								required: true,
								positivenumber: true
							},
					weight: 	{
								required: true,
								positivenumber: true
							}
				},
				messages: {
					cargo_value:    {
								required: 'This field is required.',
								positivenumber: "Please enter positive value."
							},
					job_image: 	{
								//required: 'Please upload job image.',
								accept: "Please upload jpg or png image only."
							},
					max_job_price:    {
								required: 'This field is required.',
								positivenumber: "Please enter positive value."
							},
					weight:   {
								required: 'This field is required.',
								positivenumber: "Please enter positive value."
							}
				},
				submitHandler: function(form) {
					var  is_pickup_valid = $("#is_pick_valid").val(),
						is_drop_valid	= $("#is_drop_valid").val();
						
					if (is_pickup_valid == 0){
						$("#pickup_address").focus();
						$("#pickup_address-error").html('Please enter a valid address.');
						$("#pickup_address-error").show();
					}
					else if (is_drop_valid == 0){
						$("#drop_address").focus();
						$("#drop_address-error").html('Please enter a valid address.');
						$("#drop_address-error").show();
					}
					else form.submit();
				}
			});
		});
		
		function change_size_type(args) {
			
			var selObj 	= document.getElementById('size_type');
			var selIndex 	= selObj.selectedIndex;
			var sec_html 	= selObj.options[selIndex].text;
			
			console.log('arijit: '+sec_html);
			if (sec_html == 'Enter Dimensions') $("#size_grid_sec").show();
			else $("#size_grid_sec").hide();
		}
		
		var observe;
		if (window.attachEvent) {
			observe = function (element, event, handler) {
				element.attachEvent('on'+event, handler);
			};
		}
		else {
			observe = function (element, event, handler) {
				element.addEventListener(event, handler, false);
			};
		}
		
		//This function is for the description section for dynamic height based on the contents
		function init () {
			var text = document.getElementById('description');
			function resize () {
				text.style.height = 'auto';
				text.style.height = text.scrollHeight+'px';
			}
			/* 0-timeout to get the already changed text */
			function delayedResize () {
				window.setTimeout(resize, 0);
			}
			
			observe(text, 'change',  resize);
			observe(text, 'cut',     delayedResize);
			observe(text, 'paste',   delayedResize);
			observe(text, 'drop',    delayedResize);
			observe(text, 'keydown', delayedResize);
		 
			text.focus();
			text.select();
			resize();
		}
		
	</script>
	<!-- login screen -->
	<div data-role="page" id="signupPage">
		<div data-role="main" class="ui-content login-content">
		
		<div class="close-container">
			<div class="close-content">
				<h3 id="info_title">Infotmation</h3>
				<div class="close-para">
					<p id="info_content">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
				</div>
				<div class="close-footer">
					<a href="javascript:void(0)" class="close-btn">Close</a>
				</div>
			</div>
		</div>
			
		<a data-ajax="false" href="<?php echo base_url().'dashboard' ?>" class="cancel-signup">
			<img src="<?php echo assets_url('site/images/cross.png') ?>" alt="cross" />
		</a>
			
		<div class="signup-top"><span>Add New Job</span></div>
				
			<div class="register-form">
				<form name="add_job_form" id="add_job_form" action="<?php echo base_url().'submit-job' ?>" enctype="multipart/form-data" data-ajax="false" method="post">
					<input type="hidden" name="cmp_auth_id" id="cmp_auth_id" value="<?php echo $cmp_auth_id; ?>" />
					<input type="hidden" name="add_job_pay" id="add_job_pay" value="<?php echo (isset($req_job_details['add_job_pay'])) 	? $req_job_details['add_job_pay'] 	: '0' ?>" />
						
					<input type="hidden" name="uber_rush_quote_id" 	id="uber_rush_quote_id" 		value="<?php echo (isset($req_job_details['uber_rush_quote_id'])) 	? $req_job_details['uber_rush_quote_id']  : '' ?>" />
					<input type="hidden" name="uber_rush_quote_price" id="uber_rush_quote_price" 	value="<?php echo (isset($req_job_details['uber_rush_quote_price'])) 	   ? $req_job_details['uber_rush_quote_price'] 	: '' ?>" />
					
					<input type="hidden" name="api_type_cmp" 		id="api_type_cmp" 			value="<?php echo (isset($req_job_details['api_type_cmp'])) 	? $req_job_details['api_type_cmp'] 	: '' ?>" />
						
					<div class="signup-row description-row add-job">
						<a info-cont="<?php echo isset($help_contents_list['description']) ? $help_contents_list['description'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
								<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
							</svg>
						</a>
						<textarea id="description" name="description" placeholder="Description" class="form-controls desc-controls required" data-role="none"><?php echo (isset($req_job_details['description'])) ? $req_job_details['description'] : '' ?></textarea>
							
						<div class="fileupload fileupload-new upload-img">
							<div class="fileupload-preview thumbnail">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="12.813" viewBox="0 0 48 41">
									<path class="cls-1" d="M1021,352.2a7.726,7.726,0,1,0,7.67,7.726A7.695,7.695,0,0,0,1021,352.2Zm19.1-10.357h-6.93c-1.93-1.971-3.79-4.849-6.46-4.849h-11.43c-2.67,0-4.28,2.646-6.45,4.849h-6.93a4.916,4.916,0,0,0-4.9,4.932v26.3a4.916,4.916,0,0,0,4.9,4.932h38.2a4.914,4.914,0,0,0,4.9-4.932v-26.3A4.914,4.914,0,0,0,1040.1,341.842ZM1021,371.6a11.672,11.672,0,1,1,11.59-11.671A11.631,11.631,0,0,1,1021,371.6Z" transform="translate(-997 -337)"/>
								</svg>
								<img id="show_prev_img" src="<?php echo assets_url('site/images/transparent.png') ?>" alt="" />
							</div>
							<div class="up_file_job">
								<span class="btn btn-white btn-file">
									<span class="fileupload-new"><i class="fa fa-paper-clip"></i></span>
									<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
									<input type="file" class="default" name="job_image" id="job_image" data-role="none">
								</span>
								<a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
							</div>
						</div>
						<div class="ui-grid-a">
							<label id="description-error" class="error ui-block-a" for="description"></label>
							<label id="job_image-error" for="job_image" class="error ui-block-b"></label>
						</div>
					</div>
						
					<div class="signup-row">
						<div class="position-relative">
							<a info-cont="<?php echo isset($help_contents_list['pickup_address']) ? $help_contents_list['pickup_address'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
									<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
								</svg>
							</a>
								
							<input type="text" 	 name="pickup_address" 		id="pickup_address" placeholder="Pickup Address" class="form-controls required inp-address" data-role="none" value="<?php echo (isset($req_job_details['pickup_address'])) 	? $req_job_details['pickup_address'] 	: '' ?>" />
							<input type="hidden" name="pickup_address_lat" 	id="pickup_address_lat" 	value="<?php echo (isset($req_job_details['pickup_address_lat'])) 	? $req_job_details['pickup_address_lat'] 	: '' ?>" />
							<input type="hidden" name="pickup_address_lng" 	id="pickup_address_lng" 	value="<?php echo (isset($req_job_details['pickup_address_lng'])) 	? $req_job_details['pickup_address_lng'] 	: '' ?>" />
							<input type="hidden" name="is_pick_valid" 		id="is_pick_valid" 		value="<?php echo (isset($req_job_details['is_pick_valid'])) 	? $req_job_details['is_pick_valid'] 	: '0' ?>" />
								
							<a onclick="get_current_latlng('pickup_address')" href="javascript:void(0)" data-ajax="false" style="cursor: pointer; pointer-events: painted;" class="signup-inp-ico">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
									<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"/>
								</svg>
							</a>
						</div>
						<label id="pickup_address-error" class="error" for="pickup_address"></label>
					</div>
					<div class="signup-row">
						<div class="position-relative">
							<a info-cont="<?php echo isset($help_contents_list['drop_address']) ? $help_contents_list['drop_address'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
									<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
								</svg>
							</a>
							<input name="drop_address" 	id="drop_address" type="text" placeholder="Drop Address" class="form-controls required inp-address" data-role="none" value="<?php echo (isset($req_job_details['drop_address'])) 	? $req_job_details['drop_address'] 	: '' ?>" />
							<input type="hidden" name="drop_address_lat" 	id="drop_address_lat" 	value="<?php echo (isset($req_job_details['drop_address_lat'])) 	? $req_job_details['drop_address_lat'] 	: '' ?>" />
							<input type="hidden" name="drop_address_lng" 	id="drop_address_lng" 	value="<?php echo (isset($req_job_details['drop_address_lng'])) 	? $req_job_details['drop_address_lng'] 	: '' ?>" />
							<input type="hidden" name="is_drop_valid" 		id="is_drop_valid" 		value="<?php echo (isset($req_job_details['is_drop_valid'])) 	? $req_job_details['is_drop_valid'] 	: '0' ?>" />
								
							<a href="javascript:void(0)" data-ajax="false" onclick="get_current_latlng('drop_address')" style="cursor: pointer; pointer-events: painted;" data-ajax="false" class="signup-inp-ico">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
									<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"/>
								</svg>
							</a>
						</div>
						<label id="drop_address-error" class="error" for="drop_address"></label>
					</div>
					<input type="hidden" name="distance_val" id="distance_val" value="<?php echo (isset($req_job_details['distance_val'])) 	? $req_job_details['distance_val'] 	: '' ?>" />
					<div class="distM"><span id="pick_drop_distance"></span></div>
						
					<div class="signup-row select-date-row smallFont">
						<a info-cont="<?php echo isset($help_contents_list['deliver_method']) ? $help_contents_list['deliver_method'] : ''; ?>" href="javascript:void(0)" class="infoI">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
								<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
							</svg>
						</a>
							
						<div class="selectForm">
							<select name="deliver_method" id="deliver_method">
								<option value="deliver_by">Delivery By</option>
								<option value="send_by">Pickup By</option>
								<option value="flexible">Flexible</option>
								<option value="urgent">Urgent</option>
							</select>
							<a href="javascript:void(0)" class="dropdownA ui-link">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
									<path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"></path>
								</svg>  
							</a>
						</div>
							
						<div class="dateSelect">
							<input data-role="date" id="datepicker" required name="deliver_date" type="text" readonly="readonly" class="date-input" />
							<label id="datepicker-error" class="error" for="datepicker"></label>
						</div>
							
						<a href="javascript:void(0)" class="dropdownA">
							<svg xmlns="http://www.w3.org/2000/svg" width="29" height="18" viewBox="0 0 46 36">
								<path d="M45.029,0.004 L0.003,0.004 L0.003,3.003 L0.003,9.003 L0.003,36.000 L48.031,36.000 L48.031,0.004 L45.029,0.004 ZM18.013,15.002 L12.010,15.002 L12.010,9.003 L18.013,9.003 L18.013,15.002 ZM21.015,9.003 L27.019,9.003 L27.019,15.002 L21.015,15.002 L21.015,9.003 ZM18.013,18.002 L18.013,24.001 L12.010,24.001 L12.010,18.002 L18.013,18.002 ZM21.015,18.002 L27.019,18.002 L27.019,24.001 L21.015,24.001 L21.015,18.002 ZM30.020,18.002 L36.024,18.002 L36.024,24.001 L30.020,24.001 L30.020,18.002 ZM30.020,15.002 L30.020,9.003 L36.024,9.003 L36.024,15.002 L30.020,15.002 ZM3.005,9.003 L9.008,9.003 L9.008,15.002 L3.005,15.002 L3.005,9.003 ZM3.005,18.002 L9.008,18.002 L9.008,24.001 L3.005,24.001 L3.005,18.002 ZM3.005,33.000 L3.005,27.001 L9.008,27.001 L9.008,33.000 L3.005,33.000 ZM12.010,33.000 L12.010,27.001 L18.013,27.001 L18.013,33.000 L12.010,33.000 ZM21.015,33.000 L21.015,27.001 L27.019,27.001 L27.019,33.000 L21.015,33.000 ZM30.020,33.000 L30.020,27.001 L36.024,27.001 L36.024,33.000 L30.020,33.000 ZM45.029,33.000 L39.026,33.000 L39.026,27.001 L45.029,27.001 L45.029,33.000 ZM45.029,24.001 L39.026,24.001 L39.026,18.002 L45.029,18.002 L45.029,24.001 ZM45.029,15.002 L39.026,15.002 L39.026,9.003 L42.027,9.003 L45.029,9.003 L45.029,15.002 Z" class="calNew"/>
							</svg>	
						</a>
					</div>
						
					<div class="signup-row smallFont doller-sign">
						<a info-cont="<?php echo isset($help_contents_list['cargo_value']) ? $help_contents_list['cargo_value'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
								<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
							</svg>
						</a>
						<span class="doller">$</span>
						<input name="cargo_value" id="cargo_value" type="text" required placeholder="Cargo Value" class="form-controls" data-role="none" />
					</div>
						
					<div class="signup-row">
						<div class="position-relative">
							<a info-cont="<?php echo isset($help_contents_list['size_type']) ? $help_contents_list['size_type'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
									<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
								</svg>
							</a>
								
							<div class="selectForm">
								<select name="size_type" id="size_type" required class="required" onchange="change_size_type(this)" >
									<option value="">Size</option>
									<?php
											
										if(!empty($sizes_list))
										{
											foreach($sizes_list as $size){
												$size_id 	= (isset($req_job_details['size_type'])) 	? $req_job_details['size_type'] : '';
												$sec		= ($size_id == strval($size['_id'])) ?  'selected' : '';	
												
												echo '<option '.$sec.' value="'.strval($size['_id']).'" enter_dimention="'.$size['enter_dimention'].'" help_txt="'.htmlentities($size['help_txt']).'">'.htmlentities($size['title']).'</option>';
											}
										}
									?>
								</select>
							</div>
								
							<a href="javascript:void(0)" data-ajax="false" class="dropdownA">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
								    <path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"/>
								</svg>	
							</a>
						</div>
						<label id="size_type-error" class="error" for="size_type"></label>
					</div>
						
					<div class="signup-row ui-grid-b hide" id="size_grid_sec">
						<div class="ui-block-a"><input name="size_width" id="size_width" type="text" required placeholder="Width" class="form-controls" data-role="none" /></div>
						<div class="ui-block-b"><input name="size_length" id="size_length" type="text" required placeholder="Length" class="form-controls" data-role="none" /></div>
						<div class="ui-block-c"><input name="size_height" id="size_height" type="text" required placeholder="Height" class="form-controls" data-role="none" /></div>	
					</div>
						
					<div class="signup-row">
						<div class="position-relative">
							<a info-cont="<?php echo isset($help_contents_list['type']) ? $help_contents_list['type'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
									<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
								</svg>
							</a>
								
							<div class="selectForm">
								<select name="type" id="type" required class="required">
									<option value="">Type</option>
									<?php
										if(!empty($types_list))
										{
											foreach($types_list as $type)
												echo '<option value="'.strval($type['_id']).'" help_txt="'.htmlentities($type['help_txt']).'">'.htmlentities($type['title']).'</option>';
										}
									?>
								</select>
							</div>
								
							<a href="javascript:void(0)" data-ajax="false" class="dropdownA">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
									<path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"/>
								</svg>	
							</a>
						</div>
						<label id="type-error" class="error" for="type"></label>
					</div>
						
					<div class="signup-row">
						<div class="position-relative">
							<a info-cont="<?php echo isset($help_contents_list['special']) ? $help_contents_list['special'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
									<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
								</svg>
							</a>
								
							<div class="selectForm">
								<select name="special" id="special">
									<option value="">Special</option>
									<?php
										if(!empty($special_list))
										{
											foreach($special_list as $special)
												echo '<option value="'.strval($special['_id']).'" help_txt="'.htmlentities($special['help_txt']).'">'.htmlentities($special['title']).'</option>';
										}
									?>
								</select>
							</div>
								
							<a href="javascript:void(0)" data-ajax="false" class="dropdownA">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
									<path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"/>
								</svg>	
							</a>
						</div>
						<label id="special-error" class="error" for="special"></label>
					</div>
						
					<div class="signup-row">
						<div class="position-relative">
							<a info-cont="<?php echo isset($help_contents_list['weight']) ? $help_contents_list['weight'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
									<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
								</svg>
							</a>
							<input name="weight" id="weight" type="text" placeholder="Weight" class="form-controls required positivenumber" data-role="none" />
							<a href="javascript:void(0)" class="dropdownA weightDrop">lb</a>
						</div>
						<label id="weight-error" class="error" for="weight"></label>
					</div>
						
					<div class="signup-row smallFont hide">
						<a info-cont="<?php echo isset($help_contents_list['max_job_price']) ? $help_contents_list['max_job_price'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
								<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
							</svg>
						</a>
						<input name="max_job_price" id="max_job_price" value="0" type="text" placeholder="Maximum Job Price" class="form-controls positivenumber" data-role="none" />
					</div>
						
					<div class="signup-row guranteed-row">
						<a info-cont="<?php echo isset($help_contents_list['is_gurrented']) ? $help_contents_list['is_gurrented'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
								<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
							</svg>
						</a>
						<input id="is_gurrented" name="is_gurrented" type="checkbox" data-role="none" value="1" />
						<label for="is_gurrented">Guaranteed</label>
					</div>
						
					<div class="signup-row guranteed-row">
						<a info-cont="<?php echo isset($help_contents_list['is_insured']) ? $help_contents_list['is_insured'] : ''; ?>" href="javascript:void(0)" data-ajax="false" class="infoI">
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
								<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"/>
							</svg>
						</a>
						<input id="is_insured" name="is_insured" type="checkbox" data-role="none" value="1" />
						<label for="is_insured">Insured</label>
					</div>
						
					<div class="signup-row buttonList">
						<a data-ajax="false" href="<?php echo base_url().'dashboard' ?>">Cancel</a>
						<button id="btn-list" class="btnList" type="submit">List<br />Job</button>
						<a info-cont="<?php echo isset($help_contents_list['advanced_job_details']) ? $help_contents_list['advanced_job_details'] : ''; ?>" class="new-info-button" data-ajax="false" href="javascript:void(0)">Advanced</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- login screen -->
		
	<?php
		//Google api Key is important and we are using the key stored in database
		if(isset($settings['google_map_api_key']) && !empty($settings['google_map_api_key']))
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key='.$settings['google_map_api_key'].'&libraries=geometry,places"></script>';
		else
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry,places"></script>';
	?>
	
	<script>
		
		$(window).load(function(){ $('.fileupload').fileupload({'only_image' : 1}) });
		$( ".inp-address" ).each(function() {
			var id = $(this).attr('id');
			var k  = new google.maps.places.SearchBox(this);
			
			google.maps.event.addListener(k, "places_changed", function() {
				var e = place = k.getPlaces();
				var srch_lat  = srch_lon = '';
				if (e.length > 0) {
					if (id == 'pickup_address') $("#is_pick_valid").val('1');
					else $("#is_drop_valid").val('1');
					
					
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lat = place[0].geometry.location.lat();
					
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lon = place[0].geometry.location.lng();
					
					$('#'+id+'_lat').val(srch_lat);
					$('#'+id+'_lng').val(srch_lon);
					
					if (id == 'drop_address') {
						var pick_search_lat = $('#pickup_address_lat').val(), 	pick_search_lng = $('#pickup_address_lng').val();
						var drop_search_lat = srch_lat, 					drop_search_lng = srch_lon;
						
						p1 = new google.maps.LatLng(pick_search_lat, pick_search_lng), p2 = new google.maps.LatLng(drop_search_lat, drop_search_lng);
						
						calcDistance(p1, p2);
					}
				}
				else{
					if (id == 'pickup_address') $("#is_pick_valid").val('0');
					else $("#is_drop_valid").val('0');
				}
			})
		});
		
		//calculates distance between two points in km's
		function calcDistance(p1, p2) {
			var 	distance 		= (google.maps.geometry.spherical.computeDistanceBetween(p1, p2));
			var 	proximitymiles = (distance * 0.000621371192).toFixed(2);
			
			$('#distance_val').val(proximitymiles);
			$('#pick_drop_distance').html(proximitymiles+' miles');
		}
	</script>
	