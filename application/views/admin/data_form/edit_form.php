<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  my account info.
|-->
	<?php //echo '<pre>'; print_r($all_details) ;?>
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
											+'<input class="form-control" required name="field[]" value="" id="field'+cur_count+'" aria-describedby="basic-addon2" type="text" />'
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
										+'<div class="col-sm-1"><input type="checkbox" class="form-control" name="new_field_option_check['+cur_count+']" id="field_option_check_'+cur_count+'" value="1" /></div>'
										+'<div class="col-sm-2"><button class="btn btn-danger" type="button" onclick="remove_form('+cur_count+')">Delete</button></div>'
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
				
				function change_field_type(type_no, id_no, fld_name, ty)
				{
					var html 		= '';
					
					if (ty == 0) {
						if (fld_name != '') 
							$("#fld_type_name_old_"+id_no).html(fld_name+' <span class="caret"></span>');
						
						if (type_no == 1)
							$("#old_field_type_"+id_no).val('text');
						else if (type_no == 6)
							$("#old_field_type_"+id_no).val('file');
					}
					else{
						if (fld_name != '') 
							$("#fld_type_name_"+id_no).html(fld_name+' <span class="caret"></span>');
						
						if (type_no == 1)
							$("#field_type_"+id_no).val('text');
						else if (type_no == 6)
							$("#field_type_"+id_no).val('file');
					}
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
				
				function remove_form_exist(args, db_id) {
					var r = confirm('Confrim to remove this field.')
					if(r === true)
					{
						var ValueToPass 	= "?form_id="+db_id;
						var urlpass		= '<?php echo base_url(); ?>Data_form_controller/remove_form_field'+ValueToPass;
						
						$.ajax({ 
							type: "POST",
							url: urlpass,
							cache: false,
							success: function(data1){
								var data = parseInt(data1);
								if(data != "")
								{
									if (data == '1')
										$("#ex_flds_"+args).remove();
									else
										alert('Failed to remove. Please try again.');
								}
							}
						});
					}
				}
				
			</script>
			
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
							Add form
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " id="myinfo" method="post" action="<?php echo base_url(); ?>Data_form_controller/updt" enctype="multipart/form-data">
								<input type="hidden" name="field_count" id="field_count" value="<?php echo count($all_details)+1 ?>" />
									
									<div class="form-group ">
										<label for="firstname" class="control-label col-lg-3">Form Type</label>
										<div class="col-lg-6">
											<select name="form_type" id="form_type" readonly="true" style="cursor: default; pointer-events: none;" class="form-control" onchange="show_reg_type(this.value)">
												<option value="">Select</option>
												<option value="customer" <?php echo ($form_id == 'customer') ? 'selected' : '' ?>>Customer registration</option>
												<option value="driver" <?php echo ($form_id == 'driver') ? 'selected' : '' ?>>Driver registration</option>
												<option value="broker" <?php echo ($form_id == 'broker') ? 'selected' : '' ?>>Broker registration</option>
												<option value="fleet" <?php echo ($form_id == 'fleet') ? 'selected' : '' ?>>Fleet registration</option>
												<option value="depot" <?php echo ($form_id == 'depot') ? 'selected' : '' ?>>Depot registration</option>
											</select>
										</div>
									</div>
									
									<?php
										if(!empty($all_details))
										{
											foreach($all_details as $k => $field)
											{
									?>
												<div class="form-group" id="ex_flds_<?php echo $k+1 ?>">
													<label for="lastname" class="control-label col-lg-3">Field</label>
													<div class="col-lg-6">
														<div class="clearfix">
															<div class="input-group col-sm-9" style="float: left">
																<input class="form-control" required name="old_field[]" id="old_field<?php echo $k+1 ?>" value="<?php echo (isset($field['label_name']) && (!empty($field['label_name']))) ? $field['label_name'] : '' ?>" aria-describedby="basic-addon2" type="text" />
																<input type="hidden" name="old_field_option_count[]" id="old_field_option_count_<?php echo $k+1 ?>" value="0" />
																<input id="old_field_type_<?php echo $k+1 ?>" name="old_field_type[]" value="<?php echo (isset($field['field_type']) && (!empty($field['field_type']))) ? $field['field_type'] : 'text' ?>" type="hidden" />
																<input id="old_field_id_<?php echo $k+1 ?>" name="old_field_id[]" value="<?php echo (isset($field['_id']) && (!empty($field['_id']))) ? $field['_id'] : '' ?>" type="hidden" />
																
																<div class="input-group-btn">
																	<button type="button" style="border-radius: 0 4px 4px 0;" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="fld_type_name_old_<?php echo $k+1 ?>"><?php echo (isset($field['field_type']) && (!empty($field['field_type']))) ? ucfirst($field['field_type']) : 'Field type' ?> <span class="caret"></span></button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<li><a href="javascript:void(0)" onclick="change_field_type(1, <?php echo $k+1 ?>, 'Text', 0)">Text</a></li>
																		<li><a href="javascript:void(0)" onclick="change_field_type(6, <?php echo $k+1 ?>, 'File', 0)">File</a></li>
																	</ul>
																</div>
															</div>
															
															
															<div class="col-sm-1">
																<input type="checkbox" class="form-control" name="field_option_check[<?php echo $k ?>]" id="old_field_option_check_<?php echo $k+1 ?>" value="1" <?php echo (isset($field['is_required']) && ($field['is_required'] == '1')) ? 'checked' : ''; ?> />
															</div>
															
															<?php //if($k > 0){ ?>
																<div class="col-sm-2"><button onclick="remove_form_exist('<?php echo $k+1 ?>', '<?php echo addslashes($field['field_name']) ?>')" class="btn btn-danger" type="button">Remove</button></div>
															<?php // } ?>
														</div>
														<label for="old_field<?php echo $k+1 ?>" class="error"></label>
													</div>
												</div>
									<?php
											}
										}
									?>
									
									<div class="form-group">
										<label for="lastname" class="control-label col-lg-3">Field</label>
										<div class="col-lg-6">
											<div class="clearfix">
												<div class="input-group col-sm-9" style="float: left">
													<input class="form-control" <?php echo (count($all_details) == 0) ? 'required' : '' ?> name="field[]" id="field<?php echo count($all_details) + 1 ?>" value="" aria-describedby="basic-addon2" type="text" />
													<input type="hidden" name="field_option_count[]" id="field_option_count_<?php echo count($all_details) + 1 ?>" value="0" />
													<input id="field_type_<?php echo count($all_details) + 1 ?>" name="field_type[]" value="text" type="hidden" />
													
													<div class="input-group-btn">
														<button type="button" style="border-radius: 0 4px 4px 0;" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="fld_type_name_<?php echo count($all_details) + 1 ?>">Field type <span class="caret"></span></button>
														<ul class="dropdown-menu dropdown-menu-right">
															<li><a href="javascript:void(0)" onclick="change_field_type(1, <?php echo count($all_details)+1 ?>, 'Text')">Text</a></li>
															<li><a href="javascript:void(0)" onclick="change_field_type(6, <?php echo count($all_details)+1 ?>, 'File')">File</a></li>
														</ul>
													</div>
												</div>
												<div class="col-sm-1">
													<input type="checkbox" class="form-control" name="new_field_option_check[<?php echo count($all_details)+1; ?>]" id="field_option_check_<?php echo count($all_details) + 1 ?>" value="1" />
												</div>
												<div class="col-sm-2"><button class="btn btn-success" type="button" onclick="add_form()">Add field</button></div>
											</div>
											<label for="field_option_count_<?php echo count($all_details) + 1 ?>" class="error"></label>
											<div id="fld_type_option_<?php echo count($all_details) ?>" style="padding: 10px 0;"></div>
										</div>
									</div>
								    
									<div id="all_fields">
										
									</div>
									
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/data-forms';">Cancel</button>
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