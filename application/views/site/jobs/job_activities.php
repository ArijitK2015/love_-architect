<?php
		
		$flash_message 	= $this->session->flashdata('flash_message');
		$flash_message_cont = $this->session->flashdata('flash_message_cont');
	?>
	
<link href="<?php echo assets_url() ?>site/css/jquery.mCustomScrollbar.min.css" rel="stylesheet">
	
<script> var flash_msg = '<?php echo $flash_message ?>', error_msg = '', flash_message_cont = '<?php echo $flash_message_cont ?>'; </script>


<script type="text/javascript" src="<?php echo assets_url() ?>site/js/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url() ?>site/js/jquery.validate.min.js"></script>

	<?php
		function converToTz($time="", $toTz='', $fromTz='', $format='Y-m-d H:i:s')
		{
			//echo 'arijit: '.date_default_timezone_get().'<br>';
			//echo date('Y-m-d H:i:s').'<br>';
			// timezone by php friendly values
			$date = new DateTime($time, new DateTimeZone($fromTz));
			$date->setTimezone(new DateTimeZone($toTz));
			$time= $date->format($format);
			return $time;
		}
		
		$prev_url	=	'';
		$cross_redirect_url = base_url().'dashboard';
		
		//if(isset($_SERVER['HTTP_REFERER']))
		//{
		//	$prev_url= $_SERVER['HTTP_REFERER'];
		//}
		
		
	//	if($prev_url== base_url()."all-jobs")
	//    {
	//		$cross_redirect_url	=base_url().'all-jobs';
	//    }
	//	elseif($prev_url== base_url()."my-jobs")
	//	{
	//		$cross_redirect_url	=base_url().'my-jobs';
	//	}
	
	if(!empty($cmp_auth_id))
	{
		if(trim($this->uri->segment(4))=='all-jobs')
		{
		    $cross_redirect_url	= base_url().'all-jobs';
		}
		elseif(trim($this->uri->segment(4))=='my-jobs')
		{
			$cross_redirect_url	=base_url().'my-jobs';	
		}
	}
	else
	{
		if(trim($this->uri->segment(3))=='all-jobs')
		{
		    $cross_redirect_url	= base_url().'all-jobs';
		}
		elseif(trim($this->uri->segment(3))=='my-jobs')
		{
			$cross_redirect_url	=base_url().'my-jobs';	
		}
	}
	?>
	
	<script>
		var cross_redirect_url = '<?php echo (!empty($cmp_auth_id)) ? trim($this->uri->segment(4)) : trim($this->uri->segment(3)); ?>';
		var job_det_actual  = '',
			all_job_json	= '<?php echo $all_job_json; ?>',
			all_job_arr     = $.parseJSON(all_job_json);
		
		function add_activity(args, job_id) {
			window.location = base_url+'add-activity/'+job_id+'/'+cross_redirect_url;
		}
		
		//To open the message and leg pop up
		function open_message_popup(job_id) {
			//console.log(job_id);
			//console.log(all_job_arr);
			job_det_actual = (typeof(all_job_arr[job_id]) != "undefined") ? all_job_arr[job_id] : [];
			//console.log(job_det_actual);
			send_pop_msg(1);	
		}
	</script>
	<script type="text/javascript" src="<?php echo assets_url(); ?>site/js/pages/dashboard-index.js"></script>
	
	<div data-role="page" id="signupPage">
		<div data-role="main" class="ui-content notif-map-content">
			<div class="close-container help-info-popup overlay-popup">
				<div class="close-content">
					<h3 id="info_title">Infotmation</h3>
					<div class="close-para">
						<p id="info_content">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
					</div>
					<div class="close-footer">
						<a href="javascript:void(0)" onclick="close_overlay('overlay-popup')" class="close-btn-right">Close</a>
					</div>
				</div>
			</div>
			
			<div id="popup_cont">
				<div class="close-container overflow_content">
					<div class="close-content transparent">
						<div class="popup-wrap">
							<input type="hidden" name="now_show" id="now_show" value="" />
							<div class="close-footer">
								<a href="javascript:void(0)" class="close-btn popup-close"><img src="<?php echo assets_url() ?>site/images/cross.png" alt="cross"></a>
							</div>
							<div class="user-top" id="user_img">
								<div class="user-img"><?php echo '<img id="job-user-image" src="'.assets_url().'site/images/user-image.png" alt="user-img">'; ?></div>
								<h3 id="job-user-name"></h3>
							</div>
							<div class="popup-form" id="loading_content">
								<div class="loading-class">
									<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
								</div>
								<div class="loading-text-new">Loading</div>
							</div>
							<div class="popup-form hide" id="main_cont">
								
							</div>
							
							<div class="popup-form hide" id="leg_cont">
								<form name="leg_job_form" id="leg_job_form" data-ajax="false" action="" method="post">
									
									<input type="hidden" name="leg_job_id" id="leg_job_id" value="" />
									<input type="hidden" name="leg_user_id" id="leg_user_id" value="" />
									<input type="hidden" name="submit_type" id="submit_type" value="2" />
									
									<div id="leg_cont_show">
										<div class="signup-row">
											<div class="popup-form-box popup-form-left">
												<div class="selectForm">
													<select name="pick_addr_id" id="pick_addr_id" onchange="choosed_pickdrop_det(this.value, 'leg_pickup_addr')">
														<option>Address</option>
													</select>
													<a href="javascript:void(0)" class="dropdownA ui-link">
														<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
															<path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"></path>
														</svg>
													</a>
												</div>
											</div>
											<div class="popup-form-box popup-form-right">
												<input type="text" name="leg_start" id="leg_start" placeholder="Date" class="form-controls date-picker" data-role="none" readonly="readonly" />
												<a href="javascript:void(0)" class="dropdownA ui-link">
													<svg xmlns="http://www.w3.org/2000/svg" width="29" height="18" viewBox="0 0 46 36">
														<path d="M45.029,0.004 L0.003,0.004 L0.003,3.003 L0.003,9.003 L0.003,36.000 L48.031,36.000 L48.031,0.004 L45.029,0.004 ZM18.013,15.002 L12.010,15.002 L12.010,9.003 L18.013,9.003 L18.013,15.002 ZM21.015,9.003 L27.019,9.003 L27.019,15.002 L21.015,15.002 L21.015,9.003 ZM18.013,18.002 L18.013,24.001 L12.010,24.001 L12.010,18.002 L18.013,18.002 ZM21.015,18.002 L27.019,18.002 L27.019,24.001 L21.015,24.001 L21.015,18.002 ZM30.020,18.002 L36.024,18.002 L36.024,24.001 L30.020,24.001 L30.020,18.002 ZM30.020,15.002 L30.020,9.003 L36.024,9.003 L36.024,15.002 L30.020,15.002 ZM3.005,9.003 L9.008,9.003 L9.008,15.002 L3.005,15.002 L3.005,9.003 ZM3.005,18.002 L9.008,18.002 L9.008,24.001 L3.005,24.001 L3.005,18.002 ZM3.005,33.000 L3.005,27.001 L9.008,27.001 L9.008,33.000 L3.005,33.000 ZM12.010,33.000 L12.010,27.001 L18.013,27.001 L18.013,33.000 L12.010,33.000 ZM21.015,33.000 L21.015,27.001 L27.019,27.001 L27.019,33.000 L21.015,33.000 ZM30.020,33.000 L30.020,27.001 L36.024,27.001 L36.024,33.000 L30.020,33.000 ZM45.029,33.000 L39.026,33.000 L39.026,27.001 L45.029,27.001 L45.029,33.000 ZM45.029,24.001 L39.026,24.001 L39.026,18.002 L45.029,18.002 L45.029,24.001 ZM45.029,15.002 L39.026,15.002 L39.026,9.003 L42.027,9.003 L45.029,9.003 L45.029,15.002 Z" class="calNew"></path>
													</svg>	
												</a>
											</div>
											<label id="leg_start-error" class="error" for="leg_start"></label>
										</div>
										<div class="signup-row big-font first-sign-up-row"  id="leg_pickup_addr_div">
											<input type="text" name="leg_pickup_addr" id="leg_pickup_addr" placeholder="Pickup Address" class="form-controls inp-address" data-role="none" />
											<input type="hidden" name="leg_pickup_addr_lat" id="leg_pickup_addr_lat" data-role="none" />
											<input type="hidden" name="leg_pickup_addr_long" id="leg_pickup_addr_long" data-role="none" />
											
											<a href="javascript:void(0)" onclick="get_current_latlng('leg_pickup_addr')" class="signup-inp-ico">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
													<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"/>
												</svg>
											</a>
										</div>
										<div class="signup-row">
											<div class="popup-form-box popup-form-left">
												<div class="selectForm">
													<select name="drop_addr_id" id="drop_addr_id" onchange="choosed_pickdrop_det(this.value, 'leg_drop_addr')">
														<option>Address</option>
													</select>
													<a href="javascript:void(0)" class="dropdownA ui-link">
														<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 20 30">
															<path class="cls-d" d="M1023.98,1179l-12.02,21.01L999.942,1179h24.038Z" transform="translate(-999.938 -1179)"></path>
														</svg>
													</a>
												</div>
											</div>
											<div class="popup-form-box popup-form-right">
												<input type="text" name="leg_end" id="leg_end" placeholder="Date" class="form-controls date-picker" data-role="none" readonly="readonly" />
												<a href="javascript:void(0)" class="dropdownA ui-link">
													<svg xmlns="http://www.w3.org/2000/svg" width="29" height="18" viewBox="0 0 46 36">
														<path d="M45.029,0.004 L0.003,0.004 L0.003,3.003 L0.003,9.003 L0.003,36.000 L48.031,36.000 L48.031,0.004 L45.029,0.004 ZM18.013,15.002 L12.010,15.002 L12.010,9.003 L18.013,9.003 L18.013,15.002 ZM21.015,9.003 L27.019,9.003 L27.019,15.002 L21.015,15.002 L21.015,9.003 ZM18.013,18.002 L18.013,24.001 L12.010,24.001 L12.010,18.002 L18.013,18.002 ZM21.015,18.002 L27.019,18.002 L27.019,24.001 L21.015,24.001 L21.015,18.002 ZM30.020,18.002 L36.024,18.002 L36.024,24.001 L30.020,24.001 L30.020,18.002 ZM30.020,15.002 L30.020,9.003 L36.024,9.003 L36.024,15.002 L30.020,15.002 ZM3.005,9.003 L9.008,9.003 L9.008,15.002 L3.005,15.002 L3.005,9.003 ZM3.005,18.002 L9.008,18.002 L9.008,24.001 L3.005,24.001 L3.005,18.002 ZM3.005,33.000 L3.005,27.001 L9.008,27.001 L9.008,33.000 L3.005,33.000 ZM12.010,33.000 L12.010,27.001 L18.013,27.001 L18.013,33.000 L12.010,33.000 ZM21.015,33.000 L21.015,27.001 L27.019,27.001 L27.019,33.000 L21.015,33.000 ZM30.020,33.000 L30.020,27.001 L36.024,27.001 L36.024,33.000 L30.020,33.000 ZM45.029,33.000 L39.026,33.000 L39.026,27.001 L45.029,27.001 L45.029,33.000 ZM45.029,24.001 L39.026,24.001 L39.026,18.002 L45.029,18.002 L45.029,24.001 ZM45.029,15.002 L39.026,15.002 L39.026,9.003 L42.027,9.003 L45.029,9.003 L45.029,15.002 Z" class="calNew"></path>
													</svg>	
												</a>
											</div>
											<label id="leg_end-error" class="error" for="leg_end"></label>
										</div>
										<div class="signup-row big-font" id="leg_drop_addr_div">
											<input type="text" name="leg_drop_addr" id="leg_drop_addr" placeholder="Drop Address" class="form-controls inp-address" data-role="none" />
											<input type="hidden" name="leg_drop_addr_lat" id="leg_drop_addr_lat" data-role="none" />
											<input type="hidden" name="leg_drop_addr_long" id="leg_drop_addr_long" data-role="none" />
											
											<a href="javascript:void(0)" onclick="get_current_latlng('leg_drop_addr')" class="signup-inp-ico">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="15.437" height="15.438" viewBox="0 0 15.437 15.438">
													<path d="M7.088,15.425 L5.467,9.960 L-0.003,8.340 L15.444,-0.008 L7.088,15.425 Z" class="cls-1"/>
												</svg>
											</a>
										</div>
									</div>
									
									<div id="leg_trms_show" class="hide">
										<div class="terms">
											<h3>Terms And Conditions</h3>
											<div class="terms-scroll custom-scrollbar">
												<div class="terms-content">
													<?php echo str_replace('\n', '<br>', $terms_conditions); ?>
												</div>
											</div>
										</div>
									</div>
									
									<div class="terms-anc">
										<a id="term_click" href="javascript:void(0)" onclick="show_terms('leg_trms_show', 'leg_cont_show')">See Terms &amp; Conditions</a>
									</div>
									<div class="quote-price">
										<label>Quote Price</label>
										<div class="quoted-input">
											<span>$</span>
											<input name="job_leg_price" id="job_leg_price" type="text" autocomplete="off" placeholder="0" value="" data-role="none">
										</div>
										<label id="job_leg_price-error" class="error" for="job_leg_price"></label>
									</div>
									<div class="agree-terms">
										<input name="leg_term_agree" id="leg_term_agree" type="checkbox" checked value="1" class="required" data-role="none" />
										<label for="leg_term_agree">Agree Terms &amp; Conditions</label>
									</div>
									<div class="popup-btns">
										<!--<input type="submit" value="SUBMIT LEG" class="submit-leg" data-role="none" />-->
										<button type="submit" id="submit-leg" class="submit-leg" data-role="none">Submit Leg <i id="leg_loading" class="fa fa-spinner fa-pulse fa-3x fa-fw hide"></i></button>
										<label id="leg-form-error" class="error"></label>
									</div>
								</form>
							</div>

							<div class="popup-form hide" id="quote_cont">
								<div class="terms">
									<h3>Terms And Conditions</h3>
									<div class="terms-scroll custom-scrollbar">
										<div class="terms-content">
											<?php echo str_replace('\n', '<br>', $terms_conditions); ?>
										</div>
									</div>
								</div>
								<form name="quote_job_form" id="quote_job_form" data-ajax="false" action="" method="post">
									<input type="hidden" name="quote_job_id" 	id="quote_job_id" value="" />
									<input type="hidden" name="quote_user_id" 	id="quote_user_id" value="" />
									<input type="hidden" name="submit_type" 	id="submit_type" value="1" />
									
									<div class="quote-price">
										<label>Quote Price</label>
										<div class="quoted-input">
											<span>$</span>
											<input name="job_price" id="job_price" type="text" autocomplete="off" placeholder="0" value="" data-role="none">
										</div>
										<label id="job_price-error" class="error" for="job_price"></label>
									</div>
									<div class="agree-terms">
										<input name="term_agree" id="term_agree" type="checkbox" checked value="1" class="required" data-role="none" />
										<label for="term_agree">Agree Terms &amp; Conditions</label>
									</div>
									<div class="popup-btns">
										<!--<input type="submit" value="SUBMIT QUOTE" id="submit-quote" class="submit-leg" data-role="none" />-->
										<button type="submit" id="submit-quote" class="submit-leg" data-role="none">Submit Quote <i id="quote_loading" class="fa fa-spinner fa-pulse fa-3x fa-fw hide"></i></button>
										<label id="quote-form-error" class="error"></label>
									</div>
								</form>
							</div>
							
							<div class="popup-form hide" id="msg_cont">
							</div>
							
							
							<div class="popup-form hide" id="error_cont">
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			<a data-ajax="false" href="<?php echo $cross_redirect_url; ?>" class="cancel-signup">
				<img src="<?php echo assets_url(); ?>site/images/cross.png" alt="cross" />
			</a>
			 
			<div class="signup-top">
				<span>Active Job Details</span>
				<p>Partial Cost $<?php echo $partial_cost; ?></p>
			</div>
	 
			<div class="job-timeline">
				<ul>
					<?php
						$user_timezone 			= (isset($_COOKIE['user_timezone']) && $_COOKIE['user_timezone']!='') ? $_COOKIE['user_timezone'] : $this->system_timezone;
						if(!empty($job_activities))
						{
							foreach($job_activities as $k => $job_activity)
							{
								$event_post_timezone= isset($job_activity['system_timezone']) ? $job_activity['system_timezone'] : $system_timezone;
								
								$event_id			= strval($job_activity['_id']);
								
								$event_time_date 	= (isset($job_activity['added_on'])) ? $job_activity['added_on'] : '';
								//$event_time_date 	= (isset($job_activity['added_on'])) ? date('H:i a', strtotime($job_activity['added_on'])).' '.date('m/d/Y', strtotime($job_activity['added_on'])) : '';
								if($event_time_date){
									if($event_post_timezone != $user_timezone) $event_time_date = converToTz($event_time_date, $user_timezone, $event_post_timezone);
								}
								
								$event_time_date 	= ($event_time_date) ? date('h:i a', strtotime($event_time_date)).' '.date('m/d/Y', strtotime($event_time_date)) : '';
								
								$event_status		=  ''; $go_details = 0;
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='order_started') { $event_status = "Order has started"; }
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='quote_accepted') { $event_status = "Job quote/leg is accepted"; }
								
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='pickup') { $event_status = "Pickup"; $go_details = 1; }
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='damage') { $event_status = "Damage"; $go_details = 1; }
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='delay') { $event_status = "Delay"; $go_details = 1; }
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='delivery_progress') { $event_status = "Delivery In Progress"; $go_details = 1; }
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='quality_inspec') { $event_status = "Quality Inspection"; $go_details = 1; }
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='delivered') { $event_status = "Delivered"; $go_details = 1; }
								if(isset($job_activity['event_type']) && $job_activity['event_type']=='update_location') { $event_status = "Current Location"; $go_details = 1; }
								
								$event_amount		= (isset($job_activity['event_cost']) && $job_activity['event_cost'] > 0) ? number_format($job_activity['event_cost'], 2) : '0';
								$event_image		= (isset($job_activity['event_image']) && count($job_activity['event_image'])>0) ? $job_activity['event_image'][0] : '';
								if($job_activity['event_type'] == 'delivery_progress')
								{
					?>
									<li class="timeline-row big-green-round">
										<a data-ajax="false" href="<?php echo ($go_details == 1) ? base_url().'activity-details/'.$event_id : 'javascript:void(0)';?>">
											<div class="timeline-cell">
												<span class="timeline-round">
												<?php if($event_image!='') { ?>
													<img style="height: 30px;width: 30px;" src="<?php echo assets_url().'uploads/event_images/thumb/'.$event_image;?>" alt="timeline-round-img" />
												<?php } ?>
												</span>
											</div>
											<div class="timeline-cell"><?php echo ucfirst($event_status);?></div>
										</a>
									</li>
							<?php } else { ?>
									
									<li class="timeline-row white-bordered">
										<a data-ajax="false" href="<?php echo ($go_details == 1) ? base_url().'activity-details/'.$event_id: 'javascript:void(0)' ;?>">
											<div class="timeline-cell">
												<span class="time-small-text"><?php echo $event_time_date;?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span><?php echo ($event_amount > 0) ? "$".$event_amount : '';?>
												<span class="timeline-round">
												<?php if($event_image!='') { ?>
													<img style="height: 20px;width: 20px;" src="<?php echo assets_url().'uploads/event_images/thumb/'.$event_image;?>" alt="timeline-round-img" />
												<?php } ?>
												</span>
											</div>
											<div class="timeline-cell"><?php echo ucfirst($event_status);?></div>
										</a>
									</li>
						<?php
								}
							}
						}
						else
						{
						?>
								<li class="timeline-row white-bordered">
									<a href="javascript:void(0)">
										<div class="timeline-cell">No activity posted yet</div>
										<div class="timeline-cell">&nbsp;</div>
									</a>
								</li>
						<?php 		
						}
					?>
					
				</ul>
			</div>
	 
			<div class="timeline-submit">
				<form name="add_activity_job" data-ajax="false" id="add_activity_job" method="POST" action="<?php echo base_url(); ?>add-activity">
					<input type="hidden" name="job_id" id="job_id" value="<?php echo $job_id; ?>" />
				</form>
				
				<a href="javascript:void(0)" class="submit-leg chat-anc" onclick="open_message_popup('<?php echo $job_id; ?>')" data-role="none" data-ajax="false">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="30.031" height="25" viewBox="0 0 30.031 25">
						<path d="M28.582,18.585 L23.726,18.585 L23.726,25.009 L17.253,18.585 L12.549,18.585 C11.971,18.585 11.471,18.249 11.237,17.764 L13.905,15.117 L18.652,15.117 C20.218,15.117 21.492,13.852 21.492,12.297 L21.492,5.395 L28.582,5.395 C29.385,5.395 30.036,6.041 30.036,6.838 L30.036,17.141 C30.036,17.938 29.385,18.585 28.582,18.585 ZM18.109,13.202 L12.788,13.202 L6.315,19.626 L6.315,13.202 L1.459,13.202 C0.657,13.202 0.005,12.555 0.005,11.758 L0.005,1.456 C0.005,0.659 0.657,0.012 1.459,0.012 L18.109,0.012 C18.912,0.012 19.563,0.659 19.563,1.456 L19.563,11.758 C19.563,12.555 18.912,13.202 18.109,13.202 Z" class="cls-1"/>
					</svg>
				</a>
				<button type="button" onclick="add_activity('add_activity_job', '<?php echo $job_id; ?>')" class="submit-leg submit-event" data-role="none">Submit Event</button>
			</div>
		</div>
	</div>
