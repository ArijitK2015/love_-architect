<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  my account info.
|-->
	<style>
		ul#ui-id-1 {
			height: 300px !important;
			overflow-y: auto !important;
		}
		.flag-container{
			left: 0 !important;
		}
	</style>
	
	<!--Add scripts and css for mobile no verifications-->
	<link rel="stylesheet" href="<?php echo assets_url('site/intl-tel-input/css/intlTelInput.css') ?>" />
	<script src="<?php echo assets_url('site/intl-tel-input/js/intlTelInput.js') ?>"></script>
	
	<section id="main-content">
		<section class="wrapper">
			<?php
				//flash messages
				$flash_message=$this->session->flashdata('flash_message');
				if(isset($flash_message))
				{
					if($flash_message == 'info_updated')
					{
						echo '<div class="alert alert-success">';
						echo '<i class="icon-ok-sign"></i><strong>Success!</strong>Info has been successfully updated.';
						echo '</div>';
					}
					if($flash_message == 'info_not_updated'){
						echo'<div class="alert alert-error">';
						echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
						echo'</div>';
					}
					
					if($flash_message == 'email_error'){
						echo'<div class="alert alert-error">';
						echo'<i class="icon-remove-sign"></i><strong>Error!</strong>. Email id already exist. Please try with different one.';        
						echo'</div>';
					}
					
					if($flash_message == 'error'){
						echo'<div class="alert alert-error">';
						echo'<i class="icon-remove-sign"></i><strong>Error!</strong> . Please try again.';        
						echo'</div>';
					}
					
					if($flash_message == 'info_not_updated_county'){
						echo'<div class="alert alert-error">';
						echo'<i class="icon-remove-sign"></i><strong>Error!</strong>. No county for this zip code. Please try again.';        
						echo'</div>';
					}
				}
				
				
			?>
	
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
							Update Driver
						</header>
						<div class="panel-body">
							<div class="form">
								<!--<form class="cmxform form-horizontal " id="myinfo" method="post" action="<?php echo base_url(); ?>Data_form_controller/add_forms" enctype="multipart/form-data">-->
								<form class="cmxform form-horizontal " id="user_info" method="post" action="<?php echo base_url(); ?>control/manage-drivers/edit" enctype="multipart/form-data">
								<input type="hidden" name="driver_unique_id" id="driver_unique_id" value="<?php echo $driver_details[0]['_id']; ?>" />
									<?php
									
										if(count($all_fields_fixed)>0)
										{
											$i=1;
											foreach($all_fields_fixed as $fixed)
											{
												$extra_check 	= $extra_class = '';
												$label_name 	= (isset($fixed['label_name']) && (!empty($fixed['label_name']))) ? ucfirst($fixed['label_name']) : 'Field';
												$field_name 	= $field_id = (isset($fixed['field_name']) && (!empty($fixed['field_name']))) ? ($fixed['field_name']) : 'field[]';
												$field_type 	= (isset($fixed['field_type']) && (!empty($fixed['field_type']))) ?  ($fixed['field_type']) : 'text';
												
												
												$extra_check 	= ($field_type == 'address') ? '<a class="signup-inp-ico" href="javascript:void()" onclick="get_current_latlng(\''.$field_id.'\')">
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
																<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"/>
															</svg>
														</a>' : $extra_check;
												$extra_class 	= ($field_type == 'address') ? 'inp-address' : '';
												$extra_check 	= ($field_type == 'password') ? '<a style="color: #a2a1b8" class="signup-inp-ico" id="'.$field_name.'_customer_show" href="javascript:void(0)" onclick="show_password_reg(\''.$field_name.'\', \'customer\')"><i class="fa fa-eye" aria-hidden="true"></i></a>' : $extra_check;
												
												$field_id 	= ($field_type == 'password') ? $field_id.'_customer' : $field_id;
												$is_required 	= (isset($fixed['is_required']) && ($fixed['is_required'] == "1")) ?  'required' : '';
												$min_length 	= (($field_type == 'password')) ? 'minlength="6" maxlength="20"' : ' maxlength="255"';
												$min_length 	= ($field_type == 'address') ? '' : $min_length;
												
												//if($field_type == 'address') { echo '<script>address_put_arr.push("'.$field_id.'");</script>'; }
												
												$value 		= ($this->input->post($field_name)) ? $this->input->post($field_name) : '';
											
												//print_r($driver_details[0]);
												if (array_key_exists($field_name,$driver_details[0]))
												{
													$value = $driver_details[0][$field_name];
												}
												else
												{
													$value = '';
												}
												
												$lat_str  = (is_array($value) && isset($value['lat_str'])) ? $value['lat_str'] : '';
												$long_str = (is_array($value) && isset($value['long_str'])) ? $value['long_str'] : '';
												
												//Only for address fields
												$addr_lat_long 	= ($field_type == 'address') ? '<input type="hidden" id="'.$field_id.'_lat" name="fixed_fields['.$field_name.'][lat]'.'" value="'.$lat_str.'" /><input type="hidden" id="'.$field_id.'_long" name="fixed_fields['.$field_name.'][long]'.'" value="'.$long_str.'" />' : '';
												$field_name 	= ($field_type == 'address') ? $field_name.'][address' : $field_name;
										
												//print_r($value);
												//$addr_value_arr = ($field_type == 'address') ? json_decode($value) : $value;
												
												
												$final_value = (is_array($value)) ? $value['address'] : $value;$addr_value_arr;
												
												if($field_type == 'radio' || $field_type == 'checkbox')
												{
													$value_chk_radio=$final_value;
													$option_types 	= (isset($fixed['option_details']) && (!empty($fixed['option_details']))) ? ($fixed['option_details']) : array();
													$option_types 	= !is_array($option_types) ? json_decode($option_types) : $option_types;
													
													if(!empty($option_types))
													{
														echo '<div class="form-group">';
															echo '<label for="firstname" class="control-label col-lg-3">'.ucfirst(htmlentities($label_name)).'</label>';
															foreach($option_types as $op => $option)
															{
																$value 	= (isset($option['value'])) ? $option['value'] : '';
																$name 	= (isset($option['name'])) ? $option['name'] : '';
																$is_checked = ($value_chk_radio == $value) ? 'checked' : '';
																
																echo '<div class="col-lg-2"><input type="'.$field_type.'" name="fixed_fields['.$field_name.']" id="'.$field_id.'_'.$op.'" '.$is_required.' value="'.$value.'" data-role="none" '.$is_checked.'/> &nbsp'.ucfirst($name).'</div>';
															}
															
														echo '</div>';
													}
													
												}
												elseif($field_type == 'image')
												{
													$user_image = ($value != '') ? assets_url().'uploads/user_images/thumb/'.$value : assets_url().'site/images/transparent.png';
													
													echo '<div class="form-group">
															<label class="control-label col-md-3">'.ucfirst(htmlentities($label_name)).'</label>
															<div class="col-md-9">
																<div class="fileupload fileupload-new" data-provides="fileupload">
																	<div class="fileupload-preview thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
																		<img src="'.$user_image.'" alt="">
																		</div>
																	<div>
																		<span class="btn btn-white btn-file">
																			<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Upload your image</span>
																			<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
																			<input type="file" class="default" name="fixed_fields['.$field_name.']" id="'.$field_id.'" accept="image/*">
																		</span>
																		<a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
																		<label id="img_error" class="error" style="display: none;" for="flag">Please upload an image</label>
																		<label id="image_error" class="error" style="display: none;" for="flag">Please upload a valid image</label>
																	</div>
																</div>
															</div>
														</div>';
												}
												else
												{
									?>
									

										
										<div class="form-group ">
											<label for="firstname" class="control-label col-lg-3"><?php echo ucfirst(htmlentities($label_name)); ?></label>
											<div class="col-lg-6">
												<input class="form-control <?php echo $extra_class; ?>" id="<?php echo $field_id; ?>" name="fixed_fields[<?php echo $field_name;?>]" autocomplete="off" <?php echo $min_length; ?> value="<?php echo ($field_type != 'password') ? htmlentities($final_value): ''; ?>" type="<?php echo $field_type; ?>" placeholder="" <?php echo ($field_type != 'password')? $is_required :''; ?>/>
												<?php													
																echo $addr_lat_long;
												?>
												<!--<input id="field_type_2" name="field_type['customer'][]" value="password" type="hidden" />-->
											</div>
										</div>
										
									<?php
												}
											}
										}
											
											
											
										$final_country_code			= isset($driver_details[0]['country_code']) ? $driver_details[0]['country_code'] : '';
										$final_phone_code			= isset($driver_details[0]['phone_code']) ? $driver_details[0]['phone_code'] : '';
										$final_phone_no			= isset($driver_details[0]['mobile_no']) ? $driver_details[0]['mobile_no'] : '';
										$final_phone_no			= (!empty($final_phone_no)) ? '+'.$final_phone_code.$final_phone_no : '';
										
										
										echo '<div class="form-group">'.
												'<label for="firstname" class="control-label col-lg-3">Phone No</label>'.
												'<div class="col-lg-6">'.
													'<input type="hidden" name="fixed_fields[country_code]" id="country_code" value="'.$final_country_code.'" />'.
													'<input type="hidden" name="fixed_fields[phone_code]" id="phone_code" value="'.$final_phone_code.'" />'.
													'<input name="fixed_fields[mobile_no]" value="'.$final_phone_no.'" id="mobile_no" type="tel" placeholder="Phone Number" minlength="10" maxlength="15" autocomplete="off" required class="form-control " data-role="none" />'.
												'</div>'.
												'<label id="mobile_no-error" class="error" for="mobile_no"></label>'.
											'</div>';
										
										
										//END
									?>
										
										
									<?php //echo '<pre>'; print_r($all_fields_fixed); echo '</pre>';
										
										//For other non mandatory field
										if(count($all_non_fixed)>0)
										{
											$j=1;
											foreach($all_non_fixed as $non_fixed)
											{
												
												$extra_check 	= '';
												$label_name 	= (isset($non_fixed['label_name']) && (!empty($non_fixed['label_name']))) ? ucfirst($non_fixed['label_name']) : 'Field';
												$field_name 	= $field_id = (isset($non_fixed['field_name']) && (!empty($non_fixed['field_name']))) ? ($non_fixed['field_name']) : 'field[]';
												$field_type 	= (isset($non_fixed['field_type']) && (!empty($non_fixed['field_type']))) ?  ($non_fixed['field_type']) : 'text';
												$is_required 	= (isset($non_fixed['is_required']) && ($non_fixed['is_required'] == "1")) ?  'required' : '';
												
												if (array_key_exists($field_name,$driver_details[0]))
												{
													$value = $driver_details[0][$field_name];
												}
												else
												{
													$value = '';
												}
									?>
									
										
										<div class="form-group ">
											<label for="firstname" class="control-label col-lg-3"><?php echo ucfirst(htmlentities($label_name)); ?></label>
											<div class="col-lg-6">
												<input class="form-control" id="<?php echo $field_id; ?>" name="extra_fields[<?php echo $field_name;?>]" value="<?php echo htmlentities($value); ?>" type="<?php echo $field_type; ?>" <?php echo $is_required; ?>/>
												<!--<input id="field_type_2" name="field_type['customer'][]" value="password" type="hidden" />-->
											</div>
										</div>
										
									<?php
											
											}
										}
									//END
									?>
									
									<div class="form-group ">
											<label for="admin_status" class="control-label col-lg-3">User timezone </label>
											<div class="col-lg-6">
												<select class="form-control" name="user_timezone" id="user_timezone" required>
													<option value="">Select</option>
													<?php
														$tzlists = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
														if(!empty($tzlists))
														{
															$user_timezone	= (isset($driver_details[0]['user_timezone'])) ? $driver_details[0]['user_timezone'] : $system_timezone;
															foreach($tzlists as $tzk=>$timezones)
															{
													?>
																<option value="<?php echo $timezones;?>" <?php echo ($user_timezone == $timezones) ? 'selected' :'';?>><?php echo $timezones;?></option>
													<?php
															}
														}
													?>
												</select>
											</div>
									</div>
									
									<div class="form-group ">
											<label for="admin_status" class="control-label col-lg-3">Status </label>
											<div class="col-lg-6">
												<select class="form-control" name="admin_status" id="admin_status">
													<option value="1" <?php echo (isset($driver_details[0]['admin_status']) && $driver_details[0]['admin_status'] == '1') ? 'selected' : ''; ?>>Active</option>
													<option value="0" <?php echo (isset($driver_details[0]['admin_status']) && $driver_details[0]['admin_status'] == '0') ? 'selected' : ''; ?>>Inactive</option>
												</select>
											</div>
									</div>	

									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-drivers';">Cancel</button>
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
			
			
			$("#user_info").validate({
				//rules: {
				//	first_name: 'required',
				//	last_name:   'required',
				//	email_addres:      {
				//				required: true,
				//				email: true
				//			  },
				//	profile_image: {
				//		accept: "image/*"
				//	},
				//	phone_number:'required',
				//},
				//messages: {
				//	first_name: 'Please enter your first name',
				//	last_name:   'Please enter your last name',
				//	email_addres:      {
				//				required: 'Please enter a email address',
				//				email:    'Please enter a valid email address'
				//			  },
				//	profile_image: {
				//		accept: "Please provide a valid image (JPG,JPEG,BMP,GIF,PDF,PNG)"
				//	},
				//	phone_number:'please enter the mobile number',
				//}
				
			});
		})
	</script>
	<script>
		function country_list(str) {
		$('#country_div').show();
		$('#country_id').val(str);
	}
	</script>
	
	<?php if(isset($settings[0]['google_map_api_key']) && !empty($settings[0]['google_map_api_key'])) { ?>
		<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $settings[0]['google_map_api_key']; ?>&libraries=places"></script>
	<?php } else { ?>
		<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=&libraries=places"></script>
	<?php  } ?>
	<script>
		//var input 		= document.getElementById('company_address');
		//var searchBox 		= new google.maps.places.SearchBox(input);
		
		$( ".inp-address" ).each(function() {
			var id = $(this).attr('id');
			var k  = new google.maps.places.SearchBox(this);
			
			google.maps.event.addListener(k, "places_changed", function() {
				var e = place = k.getPlaces();
				var srch_lat  = srch_lon = '';
				if (e.length > 0) {
					
					$("#"+id+"_is_proper").val(1);
					
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lat = place[0].geometry.location.lat();
					
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lon = place[0].geometry.location.lng();
						
						$('#'+id+'_lat').val(srch_lat);;
						$('#'+id+'_long').val(srch_lon);
				}
				else
					$("#"+id+"_is_proper").val(0);
				//e.length > 0 && v.panTo(e[0].geometry.location)
			})
		});
	</script>