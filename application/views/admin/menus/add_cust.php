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
	
			<script>
				function add_form()
				{
					var cur_count 	= parseInt($('#field_count').val());
					cur_count 	= cur_count + 1;
					
					var html = '<div class="form-group" id="form_id_'+cur_count+'">'
								+'<label for="lastname" class="control-label col-lg-3">Field</label>'
								+'<div class="col-lg-6">'
									+'<div class="clearfix">'
										+'<div class="input-group col-sm-9" style="float: left">'
											+'<input class="form-control" data-rule-required="true" name="field[]" value="" id="field'+cur_count+'" aria-describedby="basic-addon2" type="text" />'
											+'<input type="hidden" name="field_option_count['+cur_count+']" id="field_option_count_'+cur_count+'" value="0" />'
											+'<input type="hidden" name="field_type[]" id="field_type_'+cur_count+'" value="0" />'
											+'<div class="input-group-btn">'
												+'<button style="border-radius: 0 4px 4px 0;" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="fld_type_name_'+cur_count+'">Field type <span class="caret"></span></button>'
												+'<ul class="dropdown-menu dropdown-menu-right">'
													+'<li><a href="javascript:void(0)" onclick="change_field_type(1, '+cur_count+', \'Text\')">Text</a></li>'
													//+'<li><a href="javascript:void(0)" onclick="change_field_type(2, '+cur_count+', \'Textarea\')">Textarea</a></li>'
													//+'<li><a href="javascript:void(0)" onclick="change_field_type(3, '+cur_count+', \'Dropdown\')">Dropdown</a></li>'
													//+'<li><a href="javascript:void(0)" onclick="change_field_type(4, '+cur_count+', \'Checkbox\')">Checkbox</a></li>'
													//+'<li><a href="javascript:void(0)" onclick="change_field_type(5, '+cur_count+', \'Radio\')">Radio</a></li>'
													+'<li><a href="javascript:void(0)" onclick="change_field_type(6, '+cur_count+', \'File\')">File</a></li>'
												+'</ul>'
											+'</div>'
										+'</div>'
										+'<div class="col-sm-1"><input type="checkbox" class="form-control" name="field_option_check['+cur_count+']" id="field_option_check_'+cur_count+'" value="1" /></div>'
										+'<div class="col-sm-2"><button class="btn btn-danger" type="button" onclick="remove_form('+cur_count+')">Delete</button></div>'
										+'<label for="field'+cur_count+'" class="error"></label>'
									+'</div>'
									+'<div id="fld_type_option_'+cur_count+'" style="padding: 10px 0;"></div>'
								+'</div>'
							+'</div>';
					
					$("#all_fields").append(html);
					$("#field_count").val(cur_count);
				}
				
				function remove_form(args) {
					var cur_count 	= parseInt($('#field_count').val());
					cur_count 	= cur_count - 1;
					
					$("#form_id_"+args).remove();
					$("#field_count").val(cur_count);
				}
				
				function change_field_type(type_no, id_no, fld_name)
				{
					var html 		= '';
					
					if (fld_name != '') 
						$("#fld_type_name_"+id_no).html(fld_name+' <span class="caret"></span>');
					
					if (type_no == 1)
						$("#field_type_"+id_no).val('text');
					else if (type_no == 6)
						$("#field_type_"+id_no).val('file');
					
					//if (type_no == 3 || type_no == 4 || type_no == 5) {
					//	var opt_count 	= parseInt($('#field_option_count_'+id_no).val());
					//	opt_count 	= opt_count + 1;
					//	
					//	html = 	'<div class="form-group">'
					//				+'<div class="col-lg-6">'
					//					+'<input class=" form-control" required name="feld_option_val['+id_no+'][]" id="feld_option_val_'+id_no+'_'+opt_count+'" value="" type="text" />'
					//				+'</div>'
					//				+'<div class="col-sm-2"><button class="btn btn-success" type="button" onclick="add_field_option('+id_no+')">Add option</button></div>'
					//			+'</div>';
					//	
					//	var current_cont = $("#fld_type_option_"+id_no).html();
					//	
					//	if(current_cont == ''){
					//		$('#field_option_count_'+id_no).val(opt_count)
					//		$("#fld_type_option_"+id_no).append(html);
					//	}
					//}
					//else{
					//	$("#fld_type_option_"+id_no).html('');
					//	$('#field_option_count_'+id_no).val(0)
					//}
				}
				
				
				function add_field_option(args) {
					var opt_count 	= parseInt($('#field_option_count_'+args).val());
					opt_count 	= opt_count + 1;
					
					html = 	'<div class="form-group">'
								+'<div class="col-lg-6">'
									+'<input class=" form-control" required name="feld_option_val['+args+'][]" id="feld_option_val_'+args+'_'+opt_count+'" value="" type="text" />'
								+'</div>'
								+'<div class="col-sm-2"><button class="btn btn-warning" type="button" onclick="remove_field_option('+args+', '+opt_count+')">Remove</button></div>'
							+'</div>';
							
					$('#field_option_count_'+args).val(opt_count)
					$("#fld_type_option_"+args).append(html);
				}
				
				function show_reg_type(arg)
				{
					if (arg != '') {
						//$("#customer_reg_div").show();
						$("#extra_fields").show();
					}
					else{
						//$("#customer_reg_div").hide();
						$("#extra_fields").hide();
					}
				}
				
			</script>
	
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
							Add Customer
						</header>
						<div class="panel-body">
							<div class="form">
								<!--<form class="cmxform form-horizontal " id="myinfo" method="post" action="<?php echo base_url(); ?>Data_form_controller/add_forms" enctype="multipart/form-data">-->
								<form class="cmxform form-horizontal " id="myinfo" method="post" action="" enctype="multipart/form-data">
								<input type="hidden" name="field_count" id="field_count" value="1" />
									<?php //echo '<pre>'; print_r($all_fields_fixed); echo '</pre>';
									
									//For all fixed mandatory field
										if(count($all_fields_fixed)>0)
										{
											$i=1;
											foreach($all_fields_fixed as $fixed)
											{
												
												if(isset($fixed['field_name']))
												{
													$field_name=ucfirst(str_replace('_',' ',$fixed['field_name']));
												}
												else
												{
													$field_name="Field ".$i;
												}
									?>
									

										
										<div class="form-group ">
											<label for="firstname" class="control-label col-lg-3"><?php echo $field_name; ?></label>
											<div class="col-lg-6">
												<input class="form-control" id="<?php echo $fixed['field_name']; ?>" name="<?php echo isset($fixed['field_name'])?$fixed['field_name']:''; ?>" value="" type="<?php echo isset($fixed['field_type'])?$fixed['field_type']:''; ?>" required/>
												<!--<input id="field_type_2" name="field_type['customer'][]" value="password" type="hidden" />-->
											</div>
										</div>
										
									<?php
											$i++;
											}
										}
										//END
									?>
										
										
									<?php //echo '<pre>'; print_r($all_fields_fixed); echo '</pre>';
										
										//For other non mandatory field
										if(count($all_non_fixed)>0)
										{
											$j=1;
											foreach($all_non_fixed as $non_fixed)
											{
												
												if(isset($non_fixed['field_name']))
												{
													$field_name_nonfixed=ucfirst(str_replace('_',' ',$non_fixed['field_name']));
												}
												else
												{
													$field_name_nonfixed="Field ".$j;
												}
									?>
									

										
										<div class="form-group ">
											<label for="firstname" class="control-label col-lg-3"><?php echo $field_name_nonfixed; ?></label>
											<div class="col-lg-6">
												<input class="form-control" id="<?php echo $non_fixed['field_name']; ?>" name="<?php echo isset($non_fixed['field_name'])?$fixed['field_name']:''; ?>" value="" type="<?php echo isset($non_fixed['field_type'])?$non_fixed['field_type']:''; ?>" <?php echo (isset($non_fixed['is_checked']) && $non_fixed['is_checked']==1)?'required':''; ?>/>
												<!--<input id="field_type_2" name="field_type['customer'][]" value="password" type="hidden" />-->
											</div>
										</div>
										
									<?php
											$j++;
											}
										}
									//END
									?>
										

									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-customers';">Cancel</button>
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