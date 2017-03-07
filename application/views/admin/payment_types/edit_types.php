<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  my account info.
|-->
	<script>
		function open_other_section(pay_type) {
			
			var percent_type 	= $('#percent_type').val();
			if (pay_type!='1')
			{
				//to open extra percent or less percent section
				$("#percent_div").show();
				$("#except_credit_div").show();
				
				//to ope maximum days section for Invoice type of payment
				if(pay_type=='3')
				{
					$("#max_days_div").show();
				}
				else
				{
					$("#max_days_div").hide();
				}
			}
			else
			{
				$("#except_credit_div").hide();
				
			}
			
		}
		
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
							Edit Payment type
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " id="user_info" method="post" action="<?php echo base_url(); ?>Manage_payment_type_controller/updt" enctype="multipart/form-data">
								<input type="hidden" name="edit_type_id" id="edit_type_id" value="<?php echo isset($type_details[0]['_id']) ? strval($type_details[0]['_id']) : '0'; ?>" />
									
									<div class="form-group ">
										<label for="Title" class="control-label col-lg-3">Title</label>
										<div class="col-lg-6">
											<input class="form-control " id="title" name="title" autocomplete="off" value="<?php echo isset($type_details[0]['title']) ? $type_details[0]['title'] : ''; ?>" type="text" placeholder="" required>
										</div>
									</div>
									
									<div class="form-group ">
										<label for="pay_type" class="control-label col-lg-3">Payment type :</label>
										<div class="col-lg-6">
											
											<input class="" id="pay_type_1" name="pay_type" autocomplete="off" value="1" type="radio" onclick="open_other_section(this.value)" <?php echo (isset($type_details[0]['pay_type']) && $type_details[0]['pay_type']=='1') ? 'checked' : ''; ?> required>  Credit Card
											<br>
											<input class="" id="pay_type_2" name="pay_type" autocomplete="off" value="2" type="radio" onclick="open_other_section(this.value)" <?php echo (isset($type_details[0]['pay_type']) && $type_details[0]['pay_type']=='2') ? 'checked' : ''; ?> required>  Wire
											<br>
											<input class="" id="pay_type_3" name="pay_type" autocomplete="off" value="3" type="radio" onclick="open_other_section(this.value)" <?php echo (isset($type_details[0]['pay_type']) && $type_details[0]['pay_type']=='3') ? 'checked' : ''; ?> required>  Invoice
											<br>
										</div>
									</div>
									
									
									<div id="except_credit_div" <?php if(isset($type_details[0]['pay_type']) && $type_details[0]['pay_type']=='1'){ ?> style="display: none;"<?php } ?>>
										<div class="form-group">
											<label for="percent_type" class="control-label col-lg-3">Type Of Percentage</label>
											
											<div class="col-lg-6">
												<select class="form-control" name="percent_type" id="percent_type">
													<option value="1" <?php echo ($type_details[0]['extra_percent']>0) ? 'selected' : '' ?>>Extra Percentage</option>
													<option value="0" <?php echo ($type_details[0]['reduct_percent']>0) ? 'selected' : '' ?>>Less Percentage</option>
												</select>
											</div>
										</div>
									
											<div class="form-group" id="percent_div">
												<label for="Title" class="control-label col-lg-3">Percent :</label>
												<div class="col-lg-6">
													<?php
														$percent_value = '0';
														if($type_details[0]['extra_percent']>0)
														{
															$percent_value	= $type_details[0]['extra_percent'];
														}
														elseif($type_details[0]['reduct_percent']>0)
														{
															$percent_value	= $type_details[0]['reduct_percent'];
														}
														
													?>
													<div class="input-group">
														<input class="form-control filterme" id="percent" name="percent" autocomplete="off" value="<?php echo $percent_value; ?>" type="text" placeholder="" aria-describedby="basic-addon2">
														<span class="input-group-addon" id="basic-addon2">%</span>
													</div>
												</div>
											</div>
											
											<div class="form-group" id="max_days_div"  <?php if(isset($type_details[0]['pay_type']) && $type_details[0]['pay_type']!='3'){ ?> style="display: none;"<?php } ?>>
												<label for="Title" class="control-label col-lg-3">Payment Maximum days:</label>
												<div class="col-lg-6">
													<input class="form-control" id="max_days" name="max_days" autocomplete="off" value="<?php echo isset($type_details[0]['max_days']) ? $type_details[0]['max_days'] : ''; ?>" type="text" placeholder="">
												</div>
											</div>
										</div>

									<div class="form-group ">
										<label for="Status" class="control-label col-lg-3">Status</label>
										<div class="col-lg-6">
											<select class="form-control" name="status" id="status">
												<option value="1" <?php echo (isset($type_details[0]['status']) && ($type_details[0]['status'] == 1)) ? 'selected' : ''; ?>>Active</option>
												<option value="0" <?php echo (isset($type_details[0]['status']) && ($type_details[0]['status'] == 0)) ? 'selected' : ''; ?>>Inactive</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-lg-offset-3 col-lg-6">
											<button class="btn btn-primary" type="submit">Save</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-payment-types';">Cancel</button>
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
		
		 $('.filterme').keypress(function(eve) {
					if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0) ) {
					eve.preventDefault();
			}
			   
				// this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
				 $('.filterme').keyup(function(eve) {
				  if($(this).val().indexOf('.') == 0) {    $(this).val($(this).val().substring(1));
				  }
				 });
		});
			
		$('input[name="max_days"]').keyup(function(e){
			if (/\D/g.test(this.value))
			{
			  // Filter non-digits from input value.
			  this.value = this.value.replace(/\D/g, '');
			}
		  });
		
		$(document).ready(function(){
			$("#user_info").validate();
		})
	</script>