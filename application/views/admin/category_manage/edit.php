<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  static pages.
|-->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/ckeditor/ckeditor.js"></script>
	<section id="main-content">
		<section class="wrapper">
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
                             Updating Category
							<!--<div class="alert alert-error">
								<i class="fa fa-exclamation-circle"></i>
									Please don't change any text which is in third brackets i.e [NAME],[EMAIL],[PASSWORD],[SITE_NAME] etc.
							</div>-->
                        </header>
						<div class="panel-body">
							<div class="form">

								<form class="cmxform form-horizontal " id="editPage" method="post" action="<?php echo base_url().'control/category-manage/edit'; ?>">
									<input type="hidden" name="edit_id" id="edit_id" value="<?php echo strval($page_content[0]['_id']); ?>">
									
									<div class="form-group ">
										<label for="firstname" class="control-label col-lg-3">Category title:</label>
										<div class="col-lg-6">
											<input class=" form-control" id="title" name="title" value="<?php echo isset($page_content[0]['title']) ? $page_content[0]['title'] :''; ?>" type="text" required/>

										</div>
									</div>
									
									<div class="form-group ">
										<label for="ccomment" class="control-label col-lg-3">Details:</label>
										<div class="col-lg-6">
											<?php
											$category_det	= isset($page_content[0]['details']) ? $page_content[0]['details'] : '';
												
											?>
											<textarea class="form-control" id="details" name="details"><?php echo $category_det; ?></textarea>
											<script>CKEDITOR.replace('details');</script>
											<label for="details" class="error"></label>
										</div>
										
									</div>
	                                <div class="form-group ">
                                       <label for="ccomment" class="control-label col-lg-3">Status:</label>
                                        <div class="col-lg-6">
                                            <select class="form-control valid" style="width: 300px" id="status" name="status">
                                                <option value="1" <?php if(isset($page_content[0]['status']) && $page_content[0]['status']=='1'){?> selected="selected"  <?php }?>>Active</option>
                                                <option value="0" <?php if(isset($page_content[0]['status']) && $page_content[0]['status']=='0'){?> selected="selected"  <?php }?>>Inactive</option>
                                            </select>
                                            <div id='error_message'></div>
                                        </div>
                                    </div>
									
									
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/category-manage';">Cancel</button>
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
		$(document).ready(function(){
			$("#editPage").validate({
				ignore: [],
				rules: {
				title: "required",	
                details: {
						required: function() 
						{
							CKEDITOR.instances.details.updateElement();
						}
                    }
                },
				messages: {
					title: "Please enter category title",	
                    details:{
                        required:"Please enter category details"
                    }
				}
				
			});
		});
	</script>