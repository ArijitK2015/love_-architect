<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  my account info.
|-->
	<script>
		function change_dimention(args) {
			var ex_width = parseInt($("#width").val()), ex_height = parseInt($("#height").val()), ex_depth = parseInt($("#depth").val());
			
			if (parseInt(args) == 0) {
				if(ex_width 	== 0) $("#width").val('');
				if(ex_height 	== 0) $("#height").val('');
				if(ex_depth 	== 0) $("#depth").val('');
				
				$("#admin_dimentions").show();
			}
			else{
				if(ex_width 	== '') $("#width").val('0');
				if(ex_height 	== '') $("#height").val('0');
				if(ex_depth 	== '') $("#depth").val('0');
				
				$("#admin_dimentions").hide();
			}
		}
	</script>
	<section id="main-content">
		<section class="wrapper">
			<?php
				//flash messages
				$flash_message=$this->session->flashdata('flash_message');
				if(isset($flash_message))
				{
					if($flash_message == 'already_exist')
					{
						echo '<div class="alert alert-error">';
						echo '<i class="icon-remove-sign"></i><strong>Error!</strong>This size info already exist please add different one.';
						echo '</div>';
					}
				}
			?>
	
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
							Edit sizes
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " id="user_info" method="post" action="<?php echo base_url(); ?>Manage_sizes_controller/updt" enctype="multipart/form-data">
								<input type="hidden" name="edit_size_id" id="edit_size_id" value="<?php echo isset($sizes_details[0]['_id']) ? strval($sizes_details[0]['_id']) : '0'; ?>" />
									
									<div class="form-group ">
										<label for="Title" class="control-label col-lg-3">Title</label>
										<div class="col-lg-6">
											<input class="form-control " id="title" name="title" autocomplete="off" maxlength="255" value="<?php echo isset($sizes_details[0]['title']) ? $sizes_details[0]['title'] : ''; ?>" type="text" placeholder="" required>
										</div>
									</div>
									
									<div class="form-group hide">
										<label for="Title" class="control-label col-lg-3">Help text</label>
										<div class="col-lg-6">
											<textarea class="form-control " id="help_txt" name="help_txt" autocomplete="off" type="text" placeholder="Details about this size" ><?php echo isset($sizes_details[0]['help_txt']) ? $sizes_details[0]['help_txt'] : ''; ?></textarea>
										</div>
									</div>
									
									<?php $show_style = (isset($sizes_details[0]['enter_dimention']) && ($sizes_details[0]['enter_dimention'] == 0)) ? '' : 'style="display: none"'; ?>
									
									<div class="form-group ">
										<label for="Title" class="control-label col-lg-3">Dimention type</label>
										<div class="col-lg-6">
											<div class="radio col-sm-6">
												<label>
													<input id="dtype1" <?php echo (isset($sizes_details[0]['enter_dimention']) && ($sizes_details[0]['enter_dimention'] == 0)) ? 'checked' : ''; ?> name="enter_dimention" onclick="change_dimention(this.value)" autocomplete="off" value="0" type="radio" />
													Fixed dimention
												</label>
											</div>
											
											<div class="radio col-sm-6">
												<label>
													<input id="dtype2" <?php echo (isset($sizes_details[0]['enter_dimention']) && ($sizes_details[0]['enter_dimention'] == 1)) ? 'checked' : ''; ?> name="enter_dimention" onclick="change_dimention(this.value)" autocomplete="off" value="1" type="radio"  >
													User given dimention
												</label>
											</div>
										</div>
									</div>
									
									<div id="admin_dimentions" <?php echo $show_style; ?>>
										<div class="form-group ">
											<label for="Width" class="control-label col-lg-3">Width</label>
											<div class="col-lg-6">
												<input class="form-control " id="width" name="width" autocomplete="off" maxlength="255" value="<?php echo (isset($sizes_details[0]['width']) && (!empty($sizes_details[0]['width']))) ? $sizes_details[0]['width'] : '0'; ?>" type="text" placeholder="integer or float" required>
											</div>
										</div>
										
										<div class="form-group ">
											<label for="Height" class="control-label col-lg-3">Height</label>
											<div class="col-lg-6">
												<input class="form-control " id="height" name="height" autocomplete="off" maxlength="255" value="<?php echo (isset($sizes_details[0]['height']) && (!empty($sizes_details[0]['height']))) ? $sizes_details[0]['height'] : '0'; ?>" type="text" placeholder="integer or float" required>
											</div>
										</div>
										
										<div class="form-group ">
											<label for="Depth" class="control-label col-lg-3">Depth</label>
											<div class="col-lg-6">
												<input class="form-control " id="depth" name="depth" autocomplete="off" maxlength="255" value="<?php echo (isset($sizes_details[0]['depth'])  && (!empty($sizes_details[0]['depth']))) ? $sizes_details[0]['depth'] : '0'; ?>" type="text" placeholder="integer or float" required>
											</div>
										</div>
										
										<div class="form-group" style="display: none;">
											<label for="Weight" class="control-label col-lg-3">Weight</label>
											<div class="col-lg-6">
												<input class="form-control " id="weight" name="weight" autocomplete="off" maxlength="255" value="<?php echo (isset($sizes_details[0]['weight'])  && (!empty($sizes_details[0]['weight']))) ? $sizes_details[0]['weight'] : '0'; ?>" type="text" placeholder="">
											</div>
										</div>
										
										<div class="form-group " style="display: none;">
											<label for="Depth" class="control-label col-lg-3">Is fragile</label>
											<div class="col-lg-6">
													
												<div class="radio col-sm-6">
													<label>
														<input id="is_fragile1" name="is_fragile" autocomplete="off" value="1" type="radio" <?php echo (isset($sizes_details[0]['is_fragile'])  && ($sizes_details[0]['is_fragile'] == 1)) ? 'checked' : ''; ?>>
														Yes
													</label>
												</div>
													
												<div class="radio col-sm-6">
													<label>
														<input id="is_fragile2" name="is_fragile" autocomplete="off" value="0" type="radio" <?php echo (isset($sizes_details[0]['is_fragile'])  && ($sizes_details[0]['is_fragile'] == 0)) ? 'checked' : ''; ?>>
														No
													</label>
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-group ">
										<label for="Status" class="control-label col-lg-3">Status</label>
										<div class="col-lg-6">
											<select class="form-control" name="status" id="status">
												<option value="1" <?php echo (isset($sizes_details[0]['status']) && ($sizes_details[0]['status'] == 1)) ? 'selected' : ''; ?>>Active</option>
												<option value="0" <?php echo (isset($sizes_details[0]['status']) && ($sizes_details[0]['status'] == 0)) ? 'selected' : ''; ?>>Inactive</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-sizes';">Cancel</button>
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
			$("#user_info").validate({
				rules: {
					width: 	{
								required: true,
								positivenumber: true
							},
					height: 	{
								required: true,
								positivenumber: true
							},
					depth: 	{
								required: true,
								positivenumber: true
							},
				},
				messages: {
					width:    {
								required: 'This field is required.',
								positivenumber: "Please enter positive value."
							},
					height:   {
								required: 'This field is required.',
								positivenumber: "Please enter positive value."
							},
					depth:    {
								required: 'This field is required.',
								positivenumber: "Please enter positive value."
							},
				},
			});
		})
	</script>