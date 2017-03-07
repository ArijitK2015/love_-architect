
<script src="<?php echo assets_url() ?>site/js/jquery.validate.min.js"></script>
<script src="<?php echo assets_url() ?>site/js/additional-methods.min.js"></script>
	
<script>
	
	$.validator.addMethod("positivenumber", function (value, element, options){
		var bothEmpty = false;
		var data_value = parseFloat(value);
		if (data_value >= 0) bothEmpty = true;
		return bothEmpty;
	},"Please enter positive value.");
	
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
		init();
		
		
		$("#add_activity").validate({
			rules: {
				event_cost: {
							required: false
				},
				event_address: {
							required: false
				},
				event_cost: 	{
							required: false,
							//positivenumber: true
						},
				uploadFile: 	{
							//required: true,
							accept: "image/*"
						}
			},
			messages: {
				event_cost: {
					required: 'This field is required.',
				},
				event_address:  {
					required: 'This field is required.',
				},
				event_cost:    {
							required: 'This field is required.',
							//positivenumber: "Please enter positive value."
						},
				uploadFile: 	{
							//required: 'Please upload job image.',
							accept: "Please upload jpg or png image only."
						}
			},
			submitHandler: function(form) {
				
				var  event_cost = $("#event_cost").val();
				if (event_cost != '') {
					if(parseFloat(event_cost) > 0)
						$("#event_cost-error").html('Please enter positive value.');
					else
						$("#event_cost-error").html('');
				}
				
				if ($('#event_address').val() != '') {
					
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
				else form.submit();
			}
		});
		
		$('.infoI, .close-container, .close-btn').on('click', function(){
			
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
		var text = document.getElementById('activity_details');
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
	
	function change_upload(file_obj,sec_name)
	{
		  $('#uploadFile-error').hide();
		  var img_html = ''; 
		  //Get count of selected files
		  var countFiles = $(file_obj)[0].files.length;
		  var imgPath = $(file_obj)[0].value;
		  var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
		  
		  var hid_cnt = $('#hid_cnt').val();
		  var new_cnt = parseInt(hid_cnt)+1;
			
		  if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
		    
			var html_input = '<input data-role="none" id="uploadFile" name="uploadFile" type="file" onchange="change_upload(this,\'prev_img\')"/>';
			    $('#uploadFile').attr('name', 'event_img[]');
			    $('#uploadFile').attr('id', 'uploadFile'+new_cnt);
			    $('#prepDiv').prepend(html_input);
			    
			    $('#hid_cnt').val(new_cnt);
			    
			    var reader = new FileReader();
			    reader.onload = function (e) {
				img_html     = '<div class="uploaded-img-each" id="image_div_each_'+new_cnt+'">'
								  +'<input type="checkbox" id="upload_img_'+new_cnt+'" name="upload_img_'+new_cnt+'" data-role="none"/>'
									  +'<label for="upload_img_'+new_cnt+'"><img style="height: 60px;width: 60px;" src="'+e.target.result+'" alt="upload" /></label>'
									  +'<div class="delete-upload">'
									  +'<a data-ajax="false" href="javascript:void(0);" onclick="delete_img('+new_cnt+')">'
										  +'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="16.688" height="21.25" viewBox="0 0 16.688 21.25">'
											  +'<path d="M16.666,2.647 C16.643,3.005 16.335,3.277 15.976,3.254 L0.612,2.302 C0.254,2.279 -0.018,1.972 0.004,1.614 L0.024,1.298 C0.046,0.940 0.354,0.668 0.713,0.691 L6.526,1.051 C6.535,0.908 6.535,0.806 6.554,0.504 C6.573,0.202 6.834,-0.027 7.136,-0.008 L9.790,0.156 C10.092,0.174 10.322,0.435 10.304,0.736 C10.285,1.038 10.279,1.093 10.267,1.283 L16.077,1.643 C16.435,1.666 16.708,1.974 16.685,2.331 L16.666,2.647 ZM1.083,4.009 L8.314,4.009 L8.443,4.009 L15.675,4.009 C16.015,4.009 16.291,4.284 16.291,4.623 L13.819,20.623 C13.819,20.963 13.543,21.238 13.203,21.238 L8.443,21.238 L8.314,21.238 L3.554,21.238 C3.214,21.238 2.938,20.963 2.938,20.623 L0.466,4.623 C0.466,4.284 0.742,4.009 1.083,4.009 ZM11.317,18.948 L11.563,18.978 C11.901,19.019 12.208,18.779 12.249,18.442 L13.625,7.178 C13.666,6.841 13.426,6.534 13.088,6.493 L12.842,6.463 C12.505,6.422 12.197,6.662 12.156,6.999 L10.780,18.264 C10.739,18.601 10.980,18.907 11.317,18.948 ZM7.639,18.395 C7.639,18.734 7.915,19.009 8.255,19.009 L8.502,19.009 C8.843,19.009 9.118,18.734 9.118,18.395 L9.118,7.047 C9.118,6.707 8.843,6.432 8.502,6.432 L8.255,6.432 C7.915,6.432 7.639,6.707 7.639,7.047 L7.639,18.395 ZM4.537,18.442 C4.578,18.779 4.885,19.019 5.223,18.978 L5.468,18.948 C5.806,18.907 6.046,18.601 6.005,18.264 L4.629,6.999 C4.588,6.662 4.281,6.422 3.943,6.463 L3.698,6.493 C3.360,6.534 3.119,6.841 3.161,7.178 L4.537,18.442 Z" class="cls-1"/>'
										  +'</svg>'
									  +'</div>'
								  +'</div>';

				'<img id="img_'+new_cnt+'" class="thumb-image" src="'+e.target.result+'"/>' ;
				$("#images_div").append(img_html);
			    }
			    reader.readAsDataURL($(file_obj)[0].files[0]);
			    
			    //html_input ='<input id="fileUpload" multiple="multiple" type="file"/>';        
				  
		    
		  }
		  else
		  {
		    $('#uploadFile-error').show();
		    $('#uploadFile-error').html('Please upload jpg or png image only.');
		  }
	}
		
	function delete_img(div_id) {
		
		$('#image_div_each_'+div_id).hide();
		$("#uploadFile"+div_id).remove();
		
	}
	
</script>	
	
	<div data-role="page" id="signupPage">
		<div data-role="main" class="ui-content notif-map-content">
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

<!--				<div class="popup-wrap">
					<div class="user-top">
						<div class="user-img"><img src="<?php echo base_url(); ?>assets/site/images/user-img.jpg" alt="user-img" /></div>
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
-->			</div>
			<a data-ajax="false" href="<?php echo (!empty($cmp_auth_id)) ? base_url().'job-activities/'.$job_id.'/'.$this->uri->segment(4) : base_url().'job-activities/'.$job_id.'/'.$this->uri->segment(3); ?>" class="cancel-signup">
				<img src="<?php echo assets_url() ?>site/images/cross.png" alt="cross" />
			</a>
			 
			<div class="signup-top"><span>Add New Event</span></div>
			
			<form name="add_activity" id="add_activity" action="<?php echo base_url().'submit-activity' ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="job_id" id="job_id" value="<?php echo isset($job_id) ? $job_id : ''; ?>" />
				<input type="hidden" name="hid_cnt" id="hid_cnt" value="1">
				<div class="new-event-form">
					<div class="new-event-upload">
						<div class="upload-file" id="prepDiv">
							<input type="file" data-role="none" name="uploadFile" id="uploadFile" onchange="change_upload(this,'prev_img')"/>
							<label for="uploadFile">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="34.625" height="29.562" viewBox="0 0 34.625 29.562">
									<path d="M31.080,29.570 L3.528,29.570 C1.577,29.570 -0.004,27.978 -0.004,26.013 L-0.004,7.040 C-0.004,5.075 1.577,3.482 3.528,3.482 L8.532,3.482 C10.092,1.893 11.256,-0.016 13.183,-0.016 L21.425,-0.016 C23.349,-0.016 24.685,2.060 26.077,3.482 L31.080,3.482 C33.031,3.482 34.613,5.075 34.613,7.040 L34.613,26.013 C34.613,27.978 33.031,29.570 31.080,29.570 ZM17.304,8.107 C12.687,8.107 8.945,11.877 8.945,16.527 C8.945,21.176 12.687,24.946 17.304,24.946 C21.921,24.946 25.664,21.176 25.664,16.527 C25.664,11.877 21.921,8.107 17.304,8.107 ZM17.304,22.100 C14.248,22.100 11.770,19.604 11.770,16.527 C11.770,13.448 14.248,10.953 17.304,10.953 C20.361,10.953 22.838,13.448 22.838,16.527 C22.838,19.604 20.361,22.100 17.304,22.100 Z" class="cls-1"/>
								</svg>
							</label>
						</div>
						<div class="uploaded-img vertical-upload-scroll" id="images_div">
							
							<label style="color: red;" id="uploadFile-error" class="error" for="uploadFile"></label>
						</div>
						<div class="event-form-inputs">
							<div class="event-form-row event-type-row">
								<div class="selectForm">
									<a info-cont="<?php echo isset($help_contents_list['event_type']) ? $help_contents_list['event_type'] : ''; ?>" data-ajax="false" href="javascript:void(0)" class="infoI">
										<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
											<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"></path>
										</svg>
									</a>
									<select name="event_type" id="event_type" required>
										<option value="">Event Type</option>
										<option value="pickup">Pickup</option>
										<option value="damage">Damage</option>
										<option value="delay">Delay</option>
										<option value="update_location">Current Location</option>
										<option value="delivery_progress">Delivery In Progress</option>
										<option value="quality_inspec">Quality Inspection</option>
										<option value="delivered">Delivered</option>
									</select>
									<label id="event_type-error" class="error" for="event_type"></label>
								</div>
							</div>
							<div class="event-form-row signup-row description-row add-job">
								<a info-cont="<?php echo isset($help_contents_list['description']) ? $help_contents_list['description'] : ''; ?>" data-ajax="false" href="javascript:void(0)" class="infoI">
									<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
										<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"></path>
									</svg>
								</a>
								<label>Description</label>
								
								<textarea data-role="none" style="width: 100%; height: 150px;" name="activity_details" id="activity_details" class="form-controls custom-scrollbar" required></textarea>
							
								<label style="color: red;" id="activity_details-error" class="error" for="activity_details"></label>
							</div>
							<div class="event-form-row signup-row doller-sign">
								<a info-cont="<?php echo isset($help_contents_list['event_cost']) ? $help_contents_list['event_cost'] : ''; ?>" data-ajax="false" href="javascript:void(0)" class="infoI">
									<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
										<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"></path>
									</svg>
								</a>
								<span class="doller">$</span>
								<input type="text" style="padding-left: 15px;" placeholder="Event Cost" name="event_cost" id="event_cost" data-role="none" class="form-controls" />
								<label style="color: red;" id="event_cost-error" class="error" for="event_cost"></label>
							</div>
							<div class="event-form-row signup-row location-row">
								<a info-cont="<?php echo isset($help_contents_list['event_location']) ? $help_contents_list['event_location'] : ''; ?>" data-ajax="false" href="javascript:void(0)" class="infoI">
									<svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 20 42">
										<path d="M17.500,-0.000 C7.835,-0.000 -0.000,7.836 -0.000,17.500 C-0.000,27.166 7.835,35.000 17.500,35.000 C27.165,35.000 35.000,27.166 35.000,17.500 C35.000,7.836 27.165,-0.000 17.500,-0.000 ZM20.611,27.328 C20.611,29.000 19.226,30.355 17.516,30.355 C15.806,30.355 14.420,29.000 14.420,27.328 L14.420,15.736 C14.420,14.062 15.806,12.711 17.516,12.711 C19.226,12.711 20.611,14.062 20.611,15.736 L20.611,27.328 ZM17.499,10.963 C15.716,10.963 14.269,9.547 14.269,7.804 C14.269,6.060 15.716,4.644 17.499,4.644 C19.285,4.644 20.731,6.060 20.731,7.804 C20.731,9.547 19.285,10.963 17.499,10.963 Z" class="cls-info"></path>
									</svg>
								</a>
								<input type="text" placeholder="Event Location" data-role="none" class="form-controls required inp-address" name="event_address" id="event_address"/>
								<input type="hidden" name="event_address_lat" 	id="event_address_lat" 	value="" />
								<input type="hidden" name="event_address_lng" 	id="event_address_lng" 	value="" />
								<input type="hidden" name="is_drop_valid" 		id="is_drop_valid" 		value="0" />
								<a href="javascript:void(0)" data-ajax="false" onclick="get_current_latlng('event_address')" style="cursor: pointer; pointer-events: painted;" data-ajax="false" class="signup-inp-ico">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.5" height="15.469" viewBox="0 0 15.5 15.469">
										<path d="M7.118,15.464 L5.494,9.991 L0.014,8.368 L15.488,0.007 L7.118,15.464 Z" class="cls-1"/>
									</svg>
								</a>
								<label style="color: red;" id="event_address-error" class="error" for="event_address"></label>
							</div>
							<div class="event-form-row signup-row event-submit-row">
								<a href="<?php echo (!empty($cmp_auth_id)) ? base_url().'job-activities/'.$job_id.'/'.$this->uri->segment(4) : base_url().'job-activities/'.$job_id.'/'.$this->uri->segment(3); ?>" class="choose-country-btn event-cancel" data-role="none" data-ajax="false">Cancel</a>
								<button type="submit" class="submit-leg submit-event" data-role="none">Submit Event</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php
		//Google api Key is important and we are using the key stored in database
		if(isset($settings[0]['google_map_api_key']) && !empty($settings[0]['google_map_api_key']))
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key='.$settings[0]['google_map_api_key'].'&libraries=geometry,places"></script>';
		else
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry,places"></script>';
	?>
	<script>
		
		$( ".inp-address" ).each(function() {
			var id = $(this).attr('id');
			var k  = new google.maps.places.SearchBox(this);
			
			google.maps.event.addListener(k, "places_changed", function() {
				var e = place = k.getPlaces();
				var srch_lat  = srch_lon = '';
				if (e.length > 0) {
					
					 $("#is_drop_valid").val('1');
					
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lat = place[0].geometry.location.lat();
					
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lon = place[0].geometry.location.lng();
						
					$('#'+id+'_lat').val(srch_lat);
					$('#'+id+'_lng').val(srch_lon);
					
				}
				else{
					$("#is_drop_valid").val('0');
				}
			})
		});
	</script>
