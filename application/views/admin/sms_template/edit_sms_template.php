<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  static pages.
|-->

	<section id="main-content">
		<section class="wrapper">
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
                             Updating SMS Template
							<div class="alert alert-error">
								<i class="fa fa-exclamation-circle"></i>
									Please don't change any text which is in third brackets i.e [NAME],[EMAIL],[PASSWORD],[SITE_NAME] etc.
							</div>
                        </header>
						<div class="panel-body">
							<div class="form">

								<form class="cmxform form-horizontal " id="editPage" method="post" action="<?php echo base_url().'control/sms-template/edit'; ?>">
									<input type="hidden" name="edit_id" id="edit_id" value="<?php echo strval($page_content[0]['_id']); ?>">
									
									<div class="form-group ">
										<label for="firstname" class="control-label col-lg-3">SMS subject:</label>
										<div class="col-lg-6">
											<input class=" form-control" id="sms_subject" name="sms_subject" value="<?php echo $page_content[0]['sms_subject']; ?>" type="text" required/>

										</div>
									</div>
									
									<div class="form-group ">
										<label for="ccomment" class="control-label col-lg-3">SMS content:</label>
										<div class="col-lg-6">
											<?php
											$sms_temp_msg	= $page_content[0]['sms_template'];
											?>
											<textarea class="form-control" id="sms_template" name="sms_template" required><?php echo $sms_temp_msg; ?></textarea>
											
										</div>
										<?php echo isset($page_content[0]['content_def']) ? $page_content[0]['content_def'] : ''; ?>
									</div>
	  
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/sms-template';">Cancel</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</section>
				</div>
			</div>
		</section>
	</section>
	
	<script>
//		$(document).ready(function(){
//			$("#editPage").validate({
//				ignore: [],
//				rules: {
//                email_template: {
//						required: function() 
//						{
//							CKEDITOR.instances.email_template.updateElement();
//						}
//                    }
//                },
//				messages: {
//                    email_template:{
//                        required:"Please enter email content"
//                    }
//				}
//				
//			});
//		})
	</script>