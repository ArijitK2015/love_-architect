<?php
//echo "<pre>";
//print_r($data);die;
?>
<style>
	.description-text{
		display: block;
		width: 100%;
		padding: 6px 12px;
		font-size: 14px;
		line-height: 1.428571429;
		color: #3e3e3e;
		background-color: #fff;
		background-image: none;
		border: 1px solid #c5c5c5;
		border-radius: 4px;
		-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	}
		
</style>
<?php
		$active_section='';
		if(trim($this->uri->segment(3))!='')
		{
			         
		    $active_section=$this->uri->segment(3);
		    if($active_section!='job_info' &&  $active_section!='quote_tab' &&  $active_section!='leg_tab' &&  $active_section!='job_payment_tab' && $active_section!='job_activity_tab'){ $active_section=''; }
		}
	?>
	
	<section id="main-content">
		<section class="wrapper">
			
			<?php
  
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
			
			
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading"> Edit Job Details </header>
						<div class="panel-body">
							<div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
											<li role="presentation" class="<?php if($active_section=='job_info' || $active_section==''){ echo 'active' ;} ?>"><a href="#job_info" id="home-tab" role="tab" data-toggle="tab" aria-expanded="false">Job details</a></li>
											<li role="presentation" class="<?php if($active_section=='quote_tab'){ echo 'active' ;} ?>"><a href="#quote_tab" role="tab" id="quote-tab" data-toggle="tab" aria-expanded="false">Quotes details <span class="badge bg-success"><?php echo count($quotes_details) ?></span></a></li>
											<li role="presentation" class="<?php if($active_section=='quote_tab'){ echo 'active' ;} ?>"><a href="#leg_tab" role="tab" id="leg-tab" data-toggle="tab" aria-expanded="false">Legs details <span class="badge bg-success"><?php echo count($leg_details) ?></span></a></li>
											<li role="presentation" class="<?php if($active_section=='job_payment_tab'){ echo 'active' ;} ?>"><a href="#job_payment_tab" role="tab" id="job-payment-tab" data-toggle="tab" aria-expanded="false">Job payment</a></li>
											<li role="presentation" class="<?php if($active_section=='job_activity_tab'){ echo 'active' ;} ?>"><a href="#job_activity_tab" role="tab" id="job-activity-tab" data-toggle="tab" aria-expanded="false">Job Activities</a></li>
                                        </ul>
										
                                    <div id="myTabContent" class="tab-content">
									  <div role="tabpanel" style="padding-top: 20px;" class="tab-pane <?php if($active_section=='job_info' || $active_section==''){ echo 'active' ;} ?>" id="job_info" aria-labelledby="home-tab">
										<div class="form">
											<form class="cmxform form-horizontal " name="event" id="event" method="post" action="<?php echo base_url(); ?>control/manage-jobs/edit" enctype="multipart/form-data">
								                <input type="hidden" name="page_unique_id" id="page_unique_id" value="<?php echo $jobs_details[0]['_id']; ?>" />
												
												<div class="form-group ">
														<label for="description" class="control-label col-lg-3">Description :</label>
														<div class="col-lg-6">
															<p class="description-text"><?php echo (isset($jobs_details[0]['description'])) ? htmlentities(trim($jobs_details[0]['description'])) : 'No description'; ?></p>
														</div>
													</div>
													
													<?php
														$user_image = (isset($jobs_details[0]['image']) && $jobs_details[0]['image']!= '') ? assets_url().'uploads/job_images/thumb/'.$jobs_details[0]['image'] : assets_url().'admin/images/no_images.png';
													?>
													<div class="form-group">
															<label class="control-label col-md-3">Image :</label>
															<div class="col-md-9">
																<div class="fileupload fileupload-new" data-provides="fileupload">
																	<div class="fileupload-preview thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
																		<img src="<?php echo $user_image; ?>" alt="">
																		</div>
																	<div>
																		
																	</div>
																</div>
															</div>
													</div>
													
												<div class="form-group ">
													<label for="pickup_address" class="control-label col-lg-3">Pickup address :</label>
													<div class="col-lg-6">
														<input class="form-control inp-address" id="pickup_address" name="pickup_address" autocomplete="off" value="<?php echo (isset($jobs_details[0]['pickup_address']['address'])) ? htmlentities(trim($jobs_details[0]['pickup_address']['address'])) : ''; ?>" type="text" readonly/>
														
													</div>
												</div>

												   
												<div class="form-group ">
													<label for="drop_address" class="control-label col-lg-3">Delivery address :</label>
													<div class="col-lg-6">
														<input class="form-control inp-address" id="drop_address" name="drop_address" autocomplete="off" value="<?php echo (isset($jobs_details[0]['drop_address']['address'])) ? htmlentities(trim($jobs_details[0]['drop_address']['address'])) : ''; ?>" type="text" readonly/>
														
													</div>
												</div>
												
												<div class="form-group ">
													<label for="delivery_method" class="control-label col-lg-3">Delivery method :</label>
													<div class="col-lg-6">
															<?php
															$delivery_method = 'N/A';
															if(isset($jobs_details[0]['deliver_method']) && $jobs_details[0]['deliver_method'] == 'deliver_by') { $delivery_method = 'Delivery By';};
															if(isset($jobs_details[0]['deliver_method']) && $jobs_details[0]['deliver_method'] == 'send_by') { $delivery_method = 'Pickup By';};
															if(isset($jobs_details[0]['deliver_method']) && $jobs_details[0]['deliver_method'] == 'flexible') { $delivery_method = 'Flexible';};
															if(isset($jobs_details[0]['deliver_method']) && $jobs_details[0]['deliver_method'] == 'urgent') { $delivery_method = 'Urgent';};
															{
																	
															}?>	
															
														<input class="form-control inp-address" id="delivery_method" name="delivery_method" autocomplete="off" value="<?php echo $delivery_method; ?>" type="text" readonly/>
													</div>
												</div>
												
												<div class="form-group ">
													<label for="deliver_date" class="control-label col-lg-3">Delivery date :</label>
													<div class="col-lg-6">
														<input class="form-control" id="deliver_date" name="deliver_date" autocomplete="off" value="<?php echo (isset($jobs_details[0]['delivery_date'])) ? $jobs_details[0]['delivery_date'] : ''; ?>" type="text" readonly/>
														
													</div>
												</div>
												
												<div class="form-group ">
													<label for="deliver_date" class="control-label col-lg-3">Cargo Value :</label>
													<div class="col-lg-6">
														<input class="form-control" id="cargo_value" name="cargo_value" autocomplete="off" value="$<?php echo (isset($jobs_details[0]['cargo_value'])) ? $jobs_details[0]['cargo_value'] : '0'; ?>" type="text" readonly/>
														
													</div>
												</div>
												
												
												<div class="form-group ">
													<label for="size_type" class="control-label col-lg-3">Size :</label>
													<div class="col-lg-6">
														
														<input class="form-control" id="size_type" name="size_type" autocomplete="off" value="<?php echo (isset($jobs_details['sizes_list']) && trim($jobs_details['sizes_list'])!='') ? $jobs_details['sizes_list'] :'N/A' ; ?>" type="text" readonly/>
													</div>
												</div>
												
												<div class="form-group ">
													<label for="deliver_date" class="control-label col-lg-3">Dimension : <p style="font-size: 13px;">(height x width x depth)</p></label>
													<div class="col-lg-6">
														<input class="form-control" id="cargo_value" name="cargo_value" autocomplete="off" value="<?php echo (isset($jobs_details[0]['size']['width']) && isset($jobs_details[0]['size']['height']) && isset($jobs_details[0]['size']['depth'])) ? number_format($jobs_details[0]['size']['height'],2,'.','')." x ".number_format($jobs_details[0]['size']['width'],2,'.','')." x ".number_format($jobs_details[0]['size']['depth'],2,'.','') : '0.00 x 0.00 x 0.00'; ?>" type="text" readonly/>
														
													</div>
												</div>
												
												<div class="form-group ">
													<label for="type" class="control-label col-lg-3">Type :</label>
													<div class="col-lg-6">
														<input class="form-control" id="type" name="type" autocomplete="off" value="<?php echo (isset($jobs_details['types_list']) && trim($jobs_details['types_list'])!='') ? $jobs_details['types_list'] : 'N/A'; ?>" type="text" readonly/>
													</div>
												</div>
												
												<div class="form-group ">
													<label for="special" class="control-label col-lg-3">Special :</label>
													<div class="col-lg-6">
														<input class="form-control" id="special" name="special" autocomplete="off" value="<?php echo (isset($jobs_details['special_list']) && trim($jobs_details['special_list'])!='') ? $jobs_details['special_list'] : 'N/A'; ?>" type="text" readonly/>
													</div>
												</div>
												
												<div class="form-group ">
													<label for="deliver_date" class="control-label col-lg-3">Weight :</label>
													<div class="col-lg-6">
														<input class="form-control" id="weight" name="weight" autocomplete="off" value="<?php echo (isset($jobs_details[0]['weight'])) ? $jobs_details[0]['weight'] : ''; ?>" type="text" readonly/>
														
													</div>
												</div>
												
												<div class="form-group ">
													<label for="special" class="control-label col-lg-3">Is guaranteed :</label>
													<div class="col-lg-6">
														<input class="form-control" id="special" name="special" autocomplete="off" value="<?php echo (isset($jobs_details[0]['is_gurrented']) && $jobs_details[0]['is_gurrented'] == "1") ? 'Yes' : 'No'; ?>" type="text" readonly/>
													</div>
												</div>
												<div class="form-group ">
													<label for="special" class="control-label col-lg-3">Is insured :</label>
													<div class="col-lg-6">
														<input class="form-control" id="special" name="special" autocomplete="off" value="<?php echo (isset($jobs_details[0]['is_insured']) && $jobs_details[0]['is_insured'] == "1") ? 'Yes' : 'No'; ?>" type="text" readonly/>
													</div>
												</div>
												<div class="form-group ">
													<label for="special" class="control-label col-lg-3">Job status :</label>
													<div class="col-lg-6">
														<input class="form-control" id="special" name="special" autocomplete="off" value="<?php echo (isset($jobs_details[0]['job_status']) && $jobs_details[0]['job_status'] == "1") ? 'Approved' : 'Not approved yet'; ?>" type="text" readonly/>
													</div>
												</div>
												<div class="form-group ">
													<label for="deliver_method" class="control-label col-lg-3">Status :</label>
													<div class="col-lg-6">
														<select name="status" id="status" class="form-control">
															<option value="1" <?php echo (isset($jobs_details[0]['status']) && $jobs_details[0]['status'] == '1') ? 'selected' : ''; ?>>Active</option>
															<option value="0" <?php echo (isset($jobs_details[0]['status']) && $jobs_details[0]['status'] == '0') ? 'selected' : ''; ?>>Inactive</option>
														</select>
													</div>
												</div>
													 
												   <div class="form-group">
													   
													   <div class="col-lg-offset-3 col-lg-6">
														   <button class="btn btn-primary" type="submit">Save</button>
														   <button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-jobs';">Cancel</button>
													   </div>
												   </div>
											   
											   </form>
											</div>
										</div>
									
								<!--	quote tab	-->
								<div role="tabpanel" class="tab-pane <?php if($active_section=='profile_photo' ){ echo 'active' ;} ?>" id="quote_tab" aria-labelledby="quote-tab">
									          <div class="panel-body">
                                                	<div class="form">
											              
														<form class="cmxform form-horizontal " id="editusers" name="editusers"  method="post"  enctype="multipart/form-data" action="<?php echo base_url().'control/manage-customer/profilephoto'; ?>" >
														
										<!--			Table					-->
												<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
														<!-- <table class="table table-bordered table-striped table-condensed">-->
														<table  class="table table-bordered table-striped table-condensed" id="">
														<thead>
																<tr>
																		<th>User</th>
																		<th>User image</th>
																		<th>Pickup address</th>
																		<th>Drop address</th>
																		<th>Price</th>
																		<th>Added on</th>
																		<th>Status</th>
																</tr>
														</thead>
														<tbody>
																		<?php
																			if(count($quotes_details)>0)
																			{
																				
																				foreach($quotes_details as $quotes)
																				{
																					
																					//$job_id    	= strval($jobs['_id']);
																					$client_f_name = (isset($quotes['user_details']['first_name'])) ?$quotes['user_details']['first_name'] : '';
																					$client_l_name = (isset($quotes['user_details']['last_name']) && $quotes['user_details']['last_name']!='') ?$quotes['user_details']['last_name'] : '';
																					$client_name   = (strlen($client_f_name." ".$client_l_name)>50) ? mb_substr($client_f_name." ".$client_l_name,0,50,'UTF-8')."..." : $client_f_name." ".$client_l_name;
																					
																					$profile_image = (isset($quotes['user_details']['profile_image'])) ?$quotes['user_details']['profile_image'] : '';
																					$pick_up_address_db = (isset($quotes['start_location']['address'])) ?$quotes['start_location']['address'] : '';
																					$pick_up_address   = (strlen($pick_up_address_db)>100) ? mb_substr($pick_up_address_db,0,100,'UTF-8')."..." : $pick_up_address_db;
																					$drop_address_db = (isset($quotes['end_location']['address'])) ? $quotes['end_location']['address'] : '';
																					$drop_address   = (strlen($drop_address_db)>100) ? mb_substr($drop_address_db,0,100,'UTF-8')."..." : $drop_address_db;
																					
																					$added_on		= (isset($quotes['added_on'])) ? date('Y-m-d',strtotime($quotes['added_on'])) : '';
																					$quotes_price		= (isset($quotes['job_price'])) ? $quotes['job_price'] : '0';
																					$quote_status		= (isset($quotes['request_status']) && $quotes['request_status'] == '1') ? 'Approved' : 'Not approved yet';
																					
																			?>
																					<tr>
																						<td><?php echo ucwords($client_name);?></td>
																						
																						<?php if(isset($profile_image) && ($profile_image!='')){?>
																						<td><img  width="100px" src="<?php echo assets_url().'uploads/user_images/thumb/'. $profile_image; ?>"></td>
																						<?php }else{?> <td><img  width="100px" src="<?php echo assets_url().'site/images/user-image.png'; ?>"></td>
																						<?php }?>
																						<td><?php echo $pick_up_address;?></td>
																						<td><?php echo $drop_address;?></td>
																						<td><?php echo "$".$quotes_price;?></td>
																						<td><?php echo $added_on;?></td>
																						<td><?php echo $quote_status;?></td>
																						<!--<td><a href="javascript:void(0)" onclick="set_page_id('<?php echo $job_id; ?>');"><i class="fa fa-pencil"></i></a></td>-->
																						<!--<td><a href="javascript:void(0)" onclick="remove_main('<?php echo base_url().'Data_form_controller/remove_all_cat_field/'.addslashes($driver['id']) ?>')"><i class="fa fa-trash-o"></i></a></td>-->
																					</tr>
																			<?php
																				}
																			}
																			else
																			{
																			?>
																				<tr>
																					<td colspan="7" style="text-align: center;" class="center"><?php echo 'No result found...'; ?></td>
																				</tr>
																		<?php
																			}
																		?>
																		</tbody>
																</table>
														</div>  
											            </form>
												</div>
										</div>

                                   </div>
							
								<!--		Leg tab				-->
								
												<div role="tabpanel" class="tab-pane <?php if($active_section=='profile_photo' ){ echo 'active' ;} ?>" id="leg_tab" aria-labelledby="leg-tab">
														<div class="panel-body">
															  <div class="form">
																	
																  <form class="cmxform form-horizontal " id="editusers" name="editusers"  method="post"  enctype="multipart/form-data" action="<?php echo base_url().'control/manage-customer/profilephoto'; ?>" >
																  
												  <!--			Table					-->
														  <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
																  <!-- <table class="table table-bordered table-striped table-condensed">-->
																  <table  class="table table-bordered table-striped table-condensed" id="">
																  <thead>
																		  <tr>
																				  <th>User</th>
																				  <th>User image</th>
																				  <th>Pickup address</th>
																				  <th>Pickup date</th>
																				  <th>Drop address</th>
																				  <th>Drop date</th>
																				  <th>Price</th>
																				  <th>Added on</th>
																				  <th>Status</th>
																		  </tr>
																  </thead>
																  <tbody>
																				  <?php
																					  if(count($leg_details)>0)
																					  {
																						  
																						  foreach($leg_details as $legs)
																						  {
																							  
																							  //$job_id    	= strval($jobs['_id']);
																							  $client_f_name = (isset($legs['user_details']['first_name'])) ?$legs['user_details']['first_name'] : '';
																							  $client_l_name = (isset($legs['user_details']['last_name']) && $legs['user_details']['last_name']!='') ?$legs['user_details']['last_name'] : '';
																							  $client_name   = (strlen($client_f_name." ".$client_l_name)>50) ? mb_substr($client_f_name." ".$client_l_name,0,50,'UTF-8')."..." : $client_f_name." ".$client_l_name;
																							  $profile_image_leg = (isset($legs['user_details']['profile_image'])) ?$legs['user_details']['profile_image'] : '';
																							  
																							  $pick_up_address_leg_db = (isset($legs['start_location']['address'])) ?$legs['start_location']['address'] : '';
																							  $pick_up_address_leg   = (strlen($pick_up_address_leg_db)>100) ? mb_substr($pick_up_address_leg_db,0,100,'UTF-8')."..." : $pick_up_address_leg_db;
																							  $drop_address_db_leg = (isset($legs['end_location']['address'])) ? $legs['end_location']['address'] : '';
																							  $drop_address_leg   = (strlen($drop_address_db_leg)>100) ? mb_substr($drop_address_db_leg,0,100,'UTF-8')."..." : $drop_address_db_leg;
																							  
																							  $added_on		= (isset($legs['added_on'])) ? date('Y-m-d',strtotime($legs['added_on'])) : '';
																							  $quotes_price_leg		= (isset($legs['job_price'])) ? $legs['job_price'] : '0';
																							  $leg_status		= (isset($legs['request_status']) && $legs['request_status'] == '1') ? 'Approved' : 'Not approved yet';
																							  $pick_up_date		= (isset($legs['pickup_date'])) ? date('Y-m-d',strtotime($legs['pickup_date'])) : '';
																							  $drop_date		= (isset($legs['drop_date'])) ? date('Y-m-d',strtotime($legs['drop_date'])) : '';
																							  
																					  ?>
																							  <tr>
																								  <td><?php echo ucwords($client_name);?></td>
																								  
																								  <?php if(isset($profile_image_leg) && ($profile_image_leg!='')){?>
																						<td><img  width="100px" src="<?php echo assets_url().'uploads/user_images/thumb/'. $profile_image_leg; ?>"></td>
																						<?php }else{?> <td><img  width="100px" src="<?php echo assets_url().'site/images/user-image.png'; ?>"></td>
																						<?php }?>
																								  <td><?php echo $pick_up_address_leg;?></td>
																								  <td><?php echo $pick_up_date;?></td>
																								  <td><?php echo $drop_address_leg;?></td>
																								  <td><?php echo $drop_date;?></td>
																								  <td><?php echo "$".$quotes_price_leg;?></td>
																								  <td><?php echo $added_on;?></td>
																								  <td><?php echo $leg_status;?></td>
																							  </tr>
																					  <?php
																						  }
																					  }
																					  else
																					  {
																					  ?>
																						  <tr>
																							  <td colspan="9" style="text-align: center;" class="center"><?php echo 'No result found...'; ?></td>
																						  </tr>
																				  <?php
																					  }
																				  ?>
																				  </tbody>
																		  </table>
																  </div>  
																  </form>
														  </div>
												  </div>
		  
											 </div>
						
										<!--	Job payment			-->
										<div role="tabpanel" class="tab-pane <?php if($active_section=='profile_photo' ){ echo 'active' ;} ?>" id="job_payment_tab" aria-labelledby="job-payment-tab">
														<div class="panel-body">
															  <div class="form">
																	
																  <form class="cmxform form-horizontal " id="editusers" name="editusers"  method="post"  enctype="multipart/form-data" action="<?php echo base_url().'control/manage-customer/profilephoto'; ?>" >
																  
												  <!--			Table					-->
														  <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
																  <!-- <table class="table table-bordered table-striped table-condensed">-->
																  <table  class="table table-bordered table-striped table-condensed" id="">
																  <thead>
																		  <tr>
																				  <th>User</th>
																				  <th>Payment info</th>
																				  <th>Amount</th>
																				  <th>Date</th>
																				  <th>Status</th>
																		  </tr>
																  </thead>
																  <tbody>
																				  <?php
																					  if(count($job_payment_details)>0)
																					  {
																						  
																						  foreach($job_payment_details as $payments)
																						  {
																							  
																							  $payment_type = 'N/A';
																							  $client_f_name = (isset($payments['user_details']['first_name'])) ?$payments['user_details']['first_name'] : '';
																							  $client_l_name = (isset($payments['user_details']['last_name']) && $payments['user_details']['last_name']!='') ?$payments['user_details']['last_name'] : '';
																							  $client_name   = (strlen($client_f_name." ".$client_l_name)>50) ? mb_substr($client_f_name." ".$client_l_name,0,50,'UTF-8')."..." : $client_f_name." ".$client_l_name;
																							  $profile_image_leg = (isset($payments['user_details']['profile_image'])) ?$payments['user_details']['profile_image'] : '';
																							  
																							  if(isset($payments['payment_type']) && $payments['payment_type'] == 'credit_card') { $payment_type = 'Credit card';}
																							  if(isset($payments['payment_type']) && $payments['payment_type'] == 'wire') { $payment_type = 'Wire (-2.5%)';}
																							  if(isset($payments['payment_type']) && $payments['payment_type'] == 'invoice_1') { $payment_type = '30 Day Invoice (+2.5%)';}
																							  if(isset($payments['payment_type']) && $payments['payment_type'] == 'invoice_2') { $payment_type = '60 Day Invoice (+5%)';}
																							  if(isset($payments['payment_type']) && $payments['payment_type'] == 'invoice_3') { $payment_type = '90 Day Invoice (10%)';}
																							  
																							  $card_name = (isset($payments['card_brand']) && $payments['card_brand'] != '') ? $payments['card_brand'] : 'N/A';
																							  $card_number = (isset($payments['card_last_digits']) && $payments['card_last_digits'] != '') ? "xxxx-xxxx-xxxx-".$payments['card_last_digits'] : 'N/A';
																							  $total_price = (isset($job_payment_approve_det[0]['total_price']) && $job_payment_approve_det[0]['total_price'] != '') ? $job_payment_approve_det[0]['total_price'] : '0';
																							  $amount_paid = (isset($payments['amount']) && $payments['amount'] != '') ? $payments['amount'] : '0';
																							  $refunded_amount = (isset($job_payment_approve_det[0]['refundable_amount']) && $job_payment_approve_det[0]['refundable_amount'] != '') ? $job_payment_approve_det[0]['refundable_amount'] : '0';
																							  $deducted_amount = (isset($job_payment_approve_det[0]['deduction_amount']) && $job_payment_approve_det[0]['deduction_amount'] != '') ? $job_payment_approve_det[0]['deduction_amount'] : '0';
																							  //$currency = (isset($payments['currency']) && $payments['currency'] != '') ? $payments['currency'] : 'usd';
																							  $pay_date		= (isset($payments['bill_date'])) ? date('Y-m-d',strtotime($payments['bill_date'])) : '';
																							  $pay_status		= (isset($payments['payment_status']) && $payments['payment_status'] == '1') ? 'Paid' : 'Not paid';
																							  
																					  ?>
																							  <tr>
																								  <td> <?php if(isset($profile_image_leg) && ($profile_image_leg!='')){?>
																						<img  width="100px" src="<?php echo assets_url().'uploads/user_images/thumb/'. $profile_image_leg; ?>">
																						<?php }else{?> <img  width="100px" src="<?php echo assets_url().'site/images/user-image.png'; ?>">
																						<?php }?>
																						<?php echo ucwords($client_name);?></td>
																								  <td>Payment type : <?php echo $payment_type;?>
																								  <?php if($payment_type == 'Credit card'){ ?>
																								  <br>
																								      Credit card type : <?php echo $card_name;?>
																								  <br>
																								      Credit card number : <?php echo $card_number;?>
																									  <?php }?>
																								  </td>
																								  <td>Total amount : <?php echo "$".$total_price;?>
																								  <br>
																								  <br>
																								      Amount paid : <?php echo "$".$amount_paid;?>
																								  <br>
																								  <br>
																								      Deducted amount : <?php echo "$".$deducted_amount;?>
																								  <br>
																								  <br>
																								      Refundable amount : <?php echo "$".$refunded_amount;?>
																								 
																								  </td>
																								  <td><?php echo $pay_date;?></td>
																								  <td><?php echo $pay_status;?></td>
																								  
																							  </tr>
																					  <?php
																						  }
																					  }
																					  else
																					  {
																					  ?>
																						  <tr>
																							  <td colspan="5" style="text-align: center;" class="center"><?php echo 'No result found...'; ?></td>
																						  </tr>
																				  <?php
																					  }
																				  ?>
																				  </tbody>
																		  </table>
																  </div>  
																  </form>
														  </div>
												  </div>
		  
											 </div>
				
										<!--	Job activity			-->
										<div role="tabpanel" class="tab-pane <?php if($active_section=='profile_photo' ){ echo 'active' ;} ?>" id="job_activity_tab" aria-labelledby="job-activity-tab">
														<div class="panel-body">
															  <div class="form">
																	
																  <form class="cmxform form-horizontal " id="editusers" name="editusers"  method="post"  enctype="multipart/form-data" action="<?php echo base_url().'control/manage-customer/profilephoto'; ?>" >
																  
												  <!--			Table					-->
														  <div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
																  <!-- <table class="table table-bordered table-striped table-condensed">-->
																  <table  class="table table-bordered table-striped table-condensed" id="">
																  <thead>
																		  <tr>
																				  <th>User</th>
																				  <th>Event type</th>
																				  <th>Description</th>
																				  <th>Amount</th>
																				  <th>Address</th>
																				  <th>Event date</th>
																		  </tr>
																  </thead>
																  <tbody>
																				  <?php
																					  if(count($activity_details)>0)
																					  {
																						  
																						  foreach($activity_details as $activities)
																						  {
																							  
																				    $event_status		=  '';
																					$client_f_name = (isset($activities['user_details']['first_name'])) ?$activities['user_details']['first_name'] : '';
																					$client_l_name = (isset($activities['user_details']['last_name']) && $activities['user_details']['last_name']!='') ?$activities['user_details']['last_name'] : '';
																					$client_name   = (strlen($client_f_name." ".$client_l_name)>50) ? mb_substr($client_f_name." ".$client_l_name,0,50,'UTF-8')."..." : $client_f_name." ".$client_l_name;
																					
																					$profile_image_activity = (isset($activities['user_details']['profile_image'])) ?$activities['user_details']['profile_image'] : '';
																					$address_db = (isset($activities['event_address']['address'])) ?$activities['event_address']['address'] : '';
																					$address   = (strlen($address_db)>100) ? mb_substr($address_db,0,100,'UTF-8')."..." : $address_db;
																					$activity_description_db = (isset($activities['activity_details'])) ?$activities['activity_details'] : '';
																				    $activity_description   = (strlen($activity_description_db)>100) ? mb_substr($activity_description_db,0,100,'UTF-8')."..." : $activity_description_db;
																					
																					$added_on		= (isset($activities['added_on'])) ? date('Y-m-d',strtotime($activities['added_on'])) : '';
																				
																				if(isset($activities['event_type']) && $activities['event_type']=='order_started') { $event_status = "Order has started"; }
																				if(isset($activities['event_type']) && $activities['event_type']=='quote_accepted') { $event_status = "Job quote/leg is accepted"; }
																					
																				if(isset($activities['event_type']) && $activities['event_type']=='pickup') { $event_status = "Pickup"; }
																				if(isset($activities['event_type']) && $activities['event_type']=='damage') { $event_status = "Damage"; }
																				if(isset($activities['event_type']) && $activities['event_type']=='delay') { $event_status = "Delay"; }
																				if(isset($activities['event_type']) && $activities['event_type']=='delivery_progress') { $event_status = "Delivery In Progress"; }
																				if(isset($activities['event_type']) && $activities['event_type']=='quality_inspec') { $event_status = "Quality Inspection"; }
																				if(isset($activities['event_type']) && $activities['event_type']=='delivered') { $event_status = "Delivered"; }
																				if(isset($activities['event_type']) && $activities['event_type']=='update_location') { $event_status = "Current Location"; }
																				
																					$event_amount	= (isset($activities['event_cost']) && $activities['event_cost'] > 0) ? number_format($activities['event_cost'], 2) : '0';
																					
																							  
																					  ?>
																							  <tr>
																								  <td> <?php if(isset($profile_image_activity) && ($profile_image_activity!='')){?>
																						<img  width="100px" src="<?php echo assets_url().'uploads/user_images/thumb/'. $profile_image_activity; ?>">
																						<?php }else{?> <img  width="100px" src="<?php echo assets_url().'site/images/user-image.png'; ?>">
																						<?php }?>
																						<?php echo ucwords($client_name);?></td>
																								  <td><?php echo $event_status;?>
																								  <td><?php echo $activity_description;?>
																								  </td>
																								  <td><?php echo "$".$event_amount;?>
																								  </td>
																								  <td><?php echo $address;?></td>
																								  <td><?php echo $added_on;?></td>
																							  </tr>
																					  <?php
																						  }
																					  }
																					  else
																					  {
																					  ?>
																						  <tr>
																							  <td colspan="6" style="text-align: center;" class="center"><?php echo 'No result found...'; ?></td>
																						  </tr>
																				  <?php
																					  }
																				  ?>
																				  </tbody>
																		  </table>
																  </div>  
																  </form>
														  </div>
												  </div>
		  
											 </div>

				                </div>
									
								
					</section>
				</div>
			</div>
		</section>
	</section>
			
