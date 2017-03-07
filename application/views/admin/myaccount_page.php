<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  my account info.
|-->
	<script>
		function check_rad(args) {
			if (args == 1) {
				$("#radious_dic").show();
				$("#radious_zip").show();
			}
			else if (args == 2) {
				$("#radious_dic").hide();
				$("#radious_zip").hide();
			}
			else{
				$("#radious_dic").hide();
				$("#radious_zip").hide();
			}
		}
	</script>
	<style>
		ul#ui-id-1 {
			height: 300px !important;
			overflow-y: auto !important;
		}
	</style>
	<?php //$ci = &get_instance();
	
	$google_map_api_key = isset($settings[0]['google_map_api_key']) ? $settings[0]['google_map_api_key'] : '';
	
	
	?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key; ?>&libraries=places"
        ></script>
	
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
							Personal info
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " id="myinfo" method="post" action="<?php echo base_url(); ?>control/myaccount/updt" enctype="multipart/form-data">
									<div class="form-group ">
										<label for="firstname" class="control-label col-lg-3">Firstname</label>
										<div class="col-lg-6">
											<input class=" form-control" required name="first_name" id="first_name" value="<?php echo htmlentities($myaccount_data[0]['first_name']); ?>" type="text" />
										</div>
									</div>
									<div class="form-group ">
										<label for="lastname" class="control-label col-lg-3">Lastname</label>
										<div class="col-lg-6">
											<input class=" form-control" required name="last_name" id="last_name" value="<?php echo htmlentities($myaccount_data[0]['last_name']); ?>" type="text" />
										</div>
									</div>
								    
									<div class="form-group ">
										<label for="email" class="control-label col-lg-3">Email</label>
										<div class="col-lg-6">
											<input class="form-control" name="email_addres" id="email_addres" value="<?php echo htmlentities($myaccount_data[0]['email_addres']); ?>" type="text" />
										</div>
									</div>

									<div class="form-group ">
										<label for="email" class="control-label col-lg-3">Address</label>
										<div class="col-lg-6">
											<input class="form-control"  placeholder="" name="address" id="address" value="<?php echo htmlentities($myaccount_data[0]['address']); ?>" type="text" />
										</div>
										<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>-->
										<script>
											var x = document.getElementById("address");
											var k = new google.maps.places.SearchBox(x);
											
										</script>
									</div>


									
									<div class="form-group">
										<label class="control-label col-md-3">Profile Image</label>
										<div class="col-md-9">
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="fileupload-preview thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
												<?php
														if (isset($myaccount_data[0]['profile_image']) && (!empty($myaccount_data[0]['profile_image'])))
														{
															echo ($myaccount_data[0]['is_sub_admin'] == 2) ? '<img src="'.base_url().'assets/uploads/dealer_image/thumb/'.$myaccount_data[0]['profile_image'].'" alt="" />' : '<img src="'.base_url().'assets/uploads/subadmin_image/thumb/'.$myaccount_data[0]['profile_image'].'" alt="" />';
														}
												?>
												</div>
												<div>
													<span class="btn btn-white btn-file">
														<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Upload your image</span>
														<span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
														<input type="file" class="default" name="profile_image" id="profile_image">
													</span>
													<a href="javascript:void(0)" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
													<label id="img_error" class="error" style="display: none;" for="flag">Please upload an image</label>
													<label id="image_error" class="error" style="display: none;" for="flag">Please upload a valid image</label>
												</div>
											</div>
										</div>
									</div>
									
									<?php
										
										if(isset($myaccount_data[0]['is_sub_admin']) && ( $myaccount_data[0]['is_sub_admin'] == 2)){
										
										$display_rad 	= (isset($myaccount_data[0]['search_type']) && ($myaccount_data[0]['search_type'] == 1)) ? '' : 'display:none';
										$sep_p 		= (isset($myaccount_data[0]['search_type']) && ($myaccount_data[0]['search_type'] == 1)) ? 'selected' : '';
										$sep_p1 		= (isset($myaccount_data[0]['search_type']) && ($myaccount_data[0]['search_type'] == 2)) ? 'selected' : '';
										
										
									?>
										
										<div class="form-group ">
											<label for="email" class="control-label col-lg-3">Search type</label>
											<div class="col-lg-6">
												<select name="search_type" id="search_type" class="form-control" onchange="check_rad(this.value)">
													<option value="">Select</option>
													<option value="1" <?php echo $sep_p  ?>>Radius search</option>
													<option value="2" <?php echo $sep_p1 ?>>Everywhere in US</option>
												</select>
											</div>
										</div>
										
										<div class="form-group "id="radious_zip" style="<?php echo $display_rad ?>">
											<label for="email" class="control-label col-lg-3">Zip code</label>
											<div class="col-lg-6">
												<input class="form-control" name="zip_code" id="zip_code" value="<?php echo (isset($myaccount_data[0]['zip_code'])) ? $myaccount_data[0]['zip_code'] : '' ?>" type="text" />
											</div>
											
										</div>
										
										<div class="form-group " id="radious_dic" style="<?php echo $display_rad ?>">
											<label for="email" class="control-label col-lg-3">Radius miles</label>
											<div class="col-lg-6">
												<div class="input-group">
													<input class="form-control" placeholder="" required name="rad_miles" id="rad_miles" value="<?php echo (isset($myaccount_data[0]['search_radious'])) ? $myaccount_data[0]['search_radious'] : '' ?>" type="text" />
													<span class="input-group-addon" id="basic-addon2">miles</span>
												</div>
											</div>
										</div>
										
									<?php	} ?>
									
									
									
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/dashboard';">Cancel</button>
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
			$("#myinfo").validate({
				rules: {
					first_name: 'required',
					last_name:   'required',
					email_addres:      {
								required: true,
								email: true
							  },
					profile_image: {
						accept: "image/*"
					},
					phone_number:'required',
				},
				messages: {
					first_name: 'Please enter your first name',
					last_name:   'Please enter your last name',
					email_addres:      {
								required: 'Please enter a email address',
								email:    'Please enter a valid email address'
							  },
					profile_image: {
						accept: "Please provide a valid image (JPG,JPEG,BMP,GIF,PDF,PNG)"
					},
					phone_number:'please enter the mobile number',
				}
			});
		})
	</script>
	<script>
		function country_list(str) {
		$('#country_div').show();
		$('#country_id').val(str);
	}
	
	

	</script>
	
