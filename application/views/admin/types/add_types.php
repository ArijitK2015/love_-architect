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
						echo '<i class="icon-remove-sign"></i><strong>Error!</strong>This type info already exist please add different one.';
						echo '</div>';
					}
				}
			?>
	
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
							Add types
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " id="user_info" method="post" action="<?php echo base_url(); ?>Manage_types_controller/add_types" enctype="multipart/form-data">
								<input type="hidden" name="field_count" id="field_count" value="1" />
									
									<div class="form-group ">
										<label for="Title" class="control-label col-lg-3">Title</label>
										<div class="col-lg-6">
											<input class="form-control " id="title" name="title" autocomplete="off" maxlength="255" value="" type="text" placeholder="" required>
										</div>
									</div>
									
									<div class="form-group hide">
										<label for="Title" class="control-label col-lg-3">Help text</label>
										<div class="col-lg-6">
											<textarea class="form-control " id="help_txt" name="help_txt" autocomplete="off" value="" type="text" placeholder="Details about this type" ></textarea>
										</div>
									</div>
									
									<div class="form-group ">
										<label for="Status" class="control-label col-lg-3">Status</label>
										<div class="col-lg-6">
											<select class="form-control" name="status" id="status">
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-types';">Cancel</button>
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
			$("#user_info").validate();
		})
	</script>