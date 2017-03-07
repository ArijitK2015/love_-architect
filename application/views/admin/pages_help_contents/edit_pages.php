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
	</style>
	
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
							Update <?php echo (isset($page_details[0]) && count($page_details[0]>0) && $page_details[0]['page_title']!='') ? ucfirst(htmlentities($page_details[0]['page_title'])) : ''; ?>
						</header>
						<div class="panel-body">
							<div class="form">
								<!--<form class="cmxform form-horizontal " id="myinfo" method="post" action="<?php echo base_url(); ?>Data_form_controller/add_forms" enctype="multipart/form-data">-->
								<form class="cmxform form-horizontal " id="user_info" method="post" action="<?php echo base_url(); ?>control/pages-help-contents/edit" enctype="multipart/form-data">
								<input type="hidden" name="page_unique_id" id="page_unique_id" value="<?php echo $page_details[0]['_id']; ?>" />
									<?php
										if(count($page_details[0])>0)
										{
											$i=1;
											foreach($page_details[0] as $key => $pages)
											{
												$extra_check 	= $extra_class = '';
												$field_name 	= $field_id = (isset($key) && (!empty($key))) ? ($key) : 'field[]';
												$value 		= (isset($pages) && (!empty($pages))) ? ($pages) : '';
											
												if($key!='page_title' && $key!='_id')
												{
									?>
													
													<div class="form-group ">
														<label for="<?php echo $field_id; ?>" class="control-label col-lg-3"><?php echo ucfirst(htmlentities(str_replace('_', ' ', $key))); ?></label>
														<div class="col-lg-6">
															<textarea class="form-control id="<?php echo $field_id; ?>" name="fixed_fields[<?php echo $field_name;?>]" autocomplete="off" placeholder="" required/><?php echo htmlentities($value); ?></textarea>
														</div>
													</div>
									<?php
												}
											}
										}
										//END
									?>
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/pages-help-contents';">Cancel</button>
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
	