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
				$("#except_credit_div").show();
				$("#percent_div").show();
				
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
							Add Payment type
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " id="user_info" method="post" action="<?php echo base_url(); ?>Manage_payment_type_controller/add_payment_types" enctype="multipart/form-data">
								<input type="hidden" name="field_count" id="field_count" value="1" />
									
									<div class="form-group ">
										<label for="Title" class="control-label col-lg-3">Title :</label>
										<div class="col-lg-6">
											<input class="form-control " id="title" name="title" autocomplete="off" value="" type="text" placeholder="" required>
										</div>
									</div>
									
									<div class="form-group ">
										<label for="Title" class="control-label col-lg-3">Payment type :</label>
										<div class="col-lg-6">
											<input class="" id="pay_type_1" name="pay_type" autocomplete="off" value="1" type="radio" onclick="open_other_section(this.value)" required>  Credit Card
											<br>
											<input class="" id="pay_type_2" name="pay_type" autocomplete="off" value="2" type="radio" onclick="open_other_section(this.value)" required>  Wire
											<br>
											<input class="" id="pay_type_3" name="pay_type" autocomplete="off" value="3" type="radio" onclick="open_other_section(this.value)" required>  Invoice
											<br>
											<br>
											<label for="pay_type" class="error" style="display: none;">This field is required.</label>
										</div>
									</div>
									
									<div id="except_credit_div" style="display: none;">
										<div class="form-group">
											<label for="percent_type" class="control-label col-lg-3">Type Of Percentage</label>
											<div class="col-lg-6">
												<select class="form-control" name="percent_type" id="percent_type">
													<option value="1">Extra Percentage</option>
													<option value="0">Less Percentage</option>
												</select>
											</div>
										</div>
									
											<div class="form-group" id="percent_div">
												<label for="Title" class="control-label col-lg-3">Percent :</label>
												<div class="col-lg-6">
													<div class="input-group">
														<input class="form-control filterme" id="percent" name="percent" autocomplete="off" value="" type="text" placeholder="" aria-describedby="basic-addon2">
														<span class="input-group-addon" id="basic-addon2">%</span>
													</div>
												</div>
											</div>
											
											<div class="form-group" id="max_days_div">
												<label for="Title" class="control-label col-lg-3">Payment Maximum days:</label>
												<div class="col-lg-6">
													<input class="form-control" id="max_days" name="max_days" autocomplete="off" value="" type="text" placeholder="">
												</div>
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
			$("#user_info").validate({
				
					//rules: {
					//	extra_percent: 	{
					//				required: false,
					//				positivenumber: true
					//			},
					//	extra_percent: 	{
					//				required: false,
					//				positivenumber: true
					//			},
					//	
					//},
					//messages: {
					//	event_cost:    {
					//				required: 'This field is required.',
					//				//positivenumber: "Please enter positive value."
					//			},
					//	
					//}
				
				});
		})
	</script>