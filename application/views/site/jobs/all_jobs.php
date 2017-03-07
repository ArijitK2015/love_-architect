
<?php
		
		$flash_message 	= $this->session->flashdata('flash_message');
		$flash_message_cont = $this->session->flashdata('flash_message_cont');
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
	?>
	
<link href="<?php echo assets_url('site/css/jquery.mCustomScrollbar.min.css') ?>" rel="stylesheet">
	
<script> var flash_msg = '<?php echo $flash_message ?>', error_msg = '', flash_message_cont = '<?php echo $flash_message_cont ?>'; </script>


<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.mCustomScrollbar.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.validate.min.js') ?>"></script>

<form data-ajax="false" name="frm_opt" action="<?php echo base_url().'all-quotes'; ?>" method="POST">
	<input type="hidden" name="job_id" id="job_id" value="">
</form>

<script>
		
		var job_det_actual  = '',
			all_job_json	= '<?php echo $all_job_json; ?>',
			all_job_arr     = $.parseJSON(all_job_json);
		
		console.log(all_job_arr);
		//to generate user rating image only for this page
		function generate_all_jobs_images(rating) {
		
		var res = rating.split(".");
				
		var  full_star 	= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="21.219" height="19.438" viewBox="0 0 21.219 19.438">'
					+'<path d="M16.503,19.369 C16.448,19.406 16.385,19.424 16.322,19.424 C16.216,19.424 16.113,19.373 16.050,19.279 C15.950,19.129 15.990,18.926 16.140,18.827 C18.919,16.977 20.577,13.889 20.577,10.565 C20.577,5.091 16.109,0.638 10.617,0.638 C5.125,0.638 0.656,5.091 0.656,10.565 C0.656,13.889 2.315,16.978 5.094,18.827 C5.244,18.926 5.285,19.129 5.184,19.279 C5.084,19.428 4.880,19.469 4.731,19.369 C1.770,17.399 0.002,14.107 0.002,10.565 C0.002,4.732 4.764,-0.014 10.617,-0.014 C16.470,-0.014 21.232,4.732 21.232,10.565 C21.232,14.107 19.464,17.399 16.503,19.369 ZM3.364,8.384 L8.863,8.326 L10.617,3.132 L12.371,8.326 L17.869,8.384 L13.455,11.651 L15.099,16.881 L10.617,13.706 L6.134,16.881 L7.779,11.651 L3.364,8.384 Z" class="cls-1 star-svg"/>'
				+'</svg>',
			half_star = '<img src="'+assets_url+'site/images/half-star.png" alt="" />';
		
		var rating_first 	= (typeof(res[0]) != "undefined") ? parseInt(res[0]) 	: 0;
		var rating_second 	= (typeof(res[1]) != "undefined") ? parseInt(res[1]) 	: 0;
		
		var rating_html = '';
		
		for(i = 0; i < rating_first; i++)
			rating_html = rating_html + full_star;
			
		if (rating_second >= 5)
			rating_html = rating_html + half_star;
		
		return rating_html;
	}
	//END	
		
		var  job_det 			= {},
			job_quote_leg_det 	= {},
			user_det 			= {},
			user_id 			= '',
			user_name 		= '',
			user_image 		= '',
			job_quotes 		= '',
			job_prices 		= '',
			job_prices_arr		= '',
			job_quote_dates 	= '',
			quote_user_det 	= '',
			quote_user_rating 	= '',
			job_prices_extra 	= '',
			job_prices_extra_arr= '',
			job_total_prices 	= '',
			job_total_prices_arr= '',
			all_job_quotes_html = '',
			all_job_legs_html 	= '',
			all_quote_ids 		= [],
			only_legs 		= 0;
			
		
		function close_overlay(args) {
			$(".overlay-popup").hide();
			$('body').removeClass('info-popup-active');
		}

	//To open the quote and leg pop up
	function submit_qoute_leg(job_id) {
		
		job_det_actual = (typeof(all_job_arr[job_id]) != "undefined") ? all_job_arr[job_id] : [];
		console.log(job_det_actual);
		open_popup();	
	}
	
	//To open the message and leg pop up
	function open_message_popup(job_id) {
		//console.log(job_id);
		//console.log(all_job_arr);
		job_det_actual = (typeof(all_job_arr[job_id]) != "undefined") ? all_job_arr[job_id] : [];
		//console.log(job_det_actual);
		send_pop_msg(1);	
	}
	
	//To open the view bids details
	function open_view_bid_popup(job_id) {
		//console.log(job_id);
		//console.log(all_job_arr);
		job_det_actual = (typeof(all_job_arr[job_id]) != "undefined") ? all_job_arr[job_id] : [];
		//console.log(job_det_actual);
		view_bid_pop();	
	}
	
	//Function to open the job options popup
	function view_bid_pop(args){
		$("#user_img").show();	
		$(".popup-form").hide();
		$('#loading_content').show();
		$(".select-credit").show();
		$(".terms-anc").show();
		$(".agree-terms").show();
		$(".popup-accpt-bottom-btns").show();
		
		$("#current_job_id").val('');
		
		//console.log(job_det_actual);
		
		var postData 	= 'job_id='+job_det_actual.id+'&user_id='+session_user_id;
		var formURL 	= main_base_url+'Jobs_controllers/my_accepted_quote_det';
		var marker_cont_html = user_cont_html = '';
		
		$.ajax(
		{
			url : 	formURL,
			type: 	"POST",
			data : 	postData,
			success:function(data, textStatus, jqXHR) 
			{
				var response 	= jQuery.parseJSON(data);
				
				if(typeof response =='object')
				{
					var is_job_owner 		= 0;
					
					var leg_det 			= (typeof(response.leg_details) != "undefined") 		? response.leg_details[0] 				: {};
					
					var user_image 		= (typeof(leg_det.user_details.profile_image) != "undefined") 	? leg_det.user_details.profile_image 		: '';
					var leg_user_image 		= (user_image != '') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+assets_url+'uploads/user_images/thumb/'+user_image : assets_url+'site/images/user-image.png';
					//console.log('arijit: '+leg_det.pickup_date);
					
					//if (user_name != '') { $("#job-user-name").html(user_name); }
					if (user_image != '') { $("#job-user-image").attr('src', leg_user_image); }
					//			  
					//var img_sec_link 		= (job_det.image != '') ? '<a href="javascript:void(0)" onclick="open_popup_image(\'myPopup\', \''+base_url+'assets/uploads/job_images/'+job_det.image+'\')"><img class="popphoto" style="width: 100px" src="'+base_url+'assets/uploads/job_images/thumb/'+job_det.image+'" alt=""></a>' : '';
					//var size_details 	= size_details_width = size_details_height = size_details_length = '';
					//if (typeof(job_det.size.width) != "undefined") size_details_width 	= job_det.size.width;
					//if (typeof(job_det.size.width) != "undefined") size_details_height 	= job_det.size.height;
					//if (typeof(job_det.size.width) != "undefined") size_details_length 	= job_det.size.depth;
					//
					//if ( job_det.size_details == 'Enter Dimensions') size_details = '('+size_details_width+' x '+size_details_height+' x '+size_details_length+' mt)';
					
					
					marker_cont_html = 
							'<div class="terms" id="middle_content">'
								+'<div class="terms-scroll custom-scrollbar">'
									+'<div class="terms-content">'
										+'<p class="big-text">'
											+'<span>Start Location: </span>'+leg_det.start_location.address+' <br/>'
											+'<span>Pickup Date: </span>'+leg_det.pick_up_formated_date+' <br/>'
											+'<span>End Location: </span>'+leg_det.end_location.address+' <br/>'
											+'<span>Drop Date: </span>'+leg_det.drop_formated_date+' <br/>'
											+'<span>Price: </span>$'+leg_det.job_price+' <br/>'
										+'</p>'
										+'<div id="myPopup"></div>'
									+'</div>'
								+'</div>'
							+'</div>'
					$("#main_cont").html(marker_cont_html);
					$("#now_show").val('main_cont');
					$(".popup-form").hide();
					$("#main_cont").show();
					
					$('#myPopup').trigger('create');
					$(".custom-scrollbar").mCustomScrollbar({
						scrollButtons:{ enable:true }
					});
				}
				else if (parseInt(data) == 0) {
					$(".popup-form").hide();
					$("#error_cont").show();
					$("#error_cont").html('<label class="job-error">We are unable to get the job details. Please try again.</label>');
				}
				else
				{
					$(".popup-form").hide();
					$("#error_cont").show();
					$("#error_cont").html('<label class="job-error">Error occured. Please try again.</label>');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$(".popup-form").hide();
				$("#error_cont").show();
				$("#error_cont").html('<label class="job-error">Error occured. Please try again.</label>');
			}
		});
		
		$('#popup_cont').toggleClass('info-popup-active');
	}
	
	var is_my_job = parseInt('<?php echo $is_my_job; ?>');
	function set_job_id(job_id) {
					
		job_id=job_id.trim();
		
		document.frm_opt.job_id.value = job_id;
		document.frm_opt.submit();
	}
	
	$(document).ready(function(){
		$(window).scroll(function(){
			if ($(window).scrollTop() == $(document).height() - $(window).height()){
				
				var is_next_value = $('#is_next_value').val();
				if (parseInt(is_next_value)>0)
				{
					if(parseInt($(".pagenum").val()) <= parseInt($(".total-page").val()))
					{
						var pagenum = parseInt($(".pagenum").val()) + 1;
						getresult(pagenum, 1);
					}
				}
				
			}
		}); 
	});
	
	function getresult(pagenum, t) {
		var rating_val 		= $('#sort_by_rating').val();
		var distance_val    = $('#sort_by_distance').val();
		var is_ajax_load    = parseInt($('#is_ajax_load').val());
		
		if (is_ajax_load == 0) {
				
			$('#is_ajax_load').val(1);
			
			$.ajax({
					url: "<?php echo base_url() ?><?php echo $is_my_job;?>",
					type: "POST",
					data:  {
							rowcount:		'',
							current_page  	: pagenum,
							distance_val  	: distance_val,
							rating_val	:	rating_val,	
						},
					beforeSend: function(){
						$('#loader-icon').show();
					},
					complete: function(){
						$('#loader-icon').hide();
					},
					success: function(data){
						//alert(data);
						//return false;
						if (data!='') {
							
							$(".pagenum").val(pagenum);
							
							var json = $.parseJSON(data);
							var has_next_list =	json.has_next_list;				 
							var html_data = qoute_html = open_msg_pop_html = '';
							 $.each( json.results, function( key, val ) {
									var user_count_quote = user_rating =user_submit_quote_leg= qoute_html ='';
									
									var user_rating_value = generate_all_jobs_images(val.user_rating);
									
									//if(val.user_quote_count > 0)
										//user_count_quote = 'disabled';
									//user_count_quote = "onlcick=submit_qoute_leg('"+val.job_id+"')";
									var msg_pop_html_active_stat	= job_activity_pop_stat = cross_redirect_url_html = '';
										open_msg_pop_html	= '<div class="journey-btn">'
																	+'<a data-ajax="false" href="javascript:void(0);" onclick="open_message_popup(\''+val.job_id+'\')">'
																			+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.25" height="16.125" viewBox="0 0 19.25 16.125">'
																					+'<path d="M18.327,11.980 L15.213,11.980 L15.213,16.126 L11.061,11.980 L8.044,11.980 C7.673,11.980 7.352,11.764 7.202,11.451 L8.913,9.743 L11.958,9.743 C12.962,9.743 13.780,8.926 13.780,7.923 L13.780,3.470 L18.327,3.470 C18.842,3.470 19.260,3.887 19.260,4.401 L19.260,11.049 C19.260,11.563 18.842,11.980 18.327,11.980 ZM11.610,8.507 L8.197,8.507 L4.045,12.653 L4.045,8.507 L0.930,8.507 C0.416,8.507 -0.002,8.090 -0.002,7.576 L-0.002,0.928 C-0.002,0.414 0.416,-0.003 0.930,-0.003 L11.610,-0.003 C12.125,-0.003 12.543,0.414 12.543,0.928 L12.543,7.576 C12.543,8.090 12.125,8.507 11.610,8.507 Z" class="cls-1"/>'
																			+'</svg>'
																	+'</a>'
															+'</div>';
									//for appearing message pop
									if (is_my_job != 1) {
										
										if (parseInt(val.curr_job_status) == 2)
										{
											if (parseInt(val.is_part_of_job)>0) {
												
												msg_pop_html_active_stat 	= 	open_msg_pop_html;
											}
										}				
										else
										{
											msg_pop_html_active_stat 	= 	open_msg_pop_html;
										}
										
									}
									else
									{
										msg_pop_html_active_stat 	= 	open_msg_pop_html;
									}
									//END
									
									//for cross icon redirection
									if (is_my_job != 1) {
										cross_redirect_url_html 	= 	'<a data-ajax="false" href="'+base_url+'job-activities/'+val.job_id+'/all-jobs" class="eye-anc">'
									}
									else
									{
										cross_redirect_url_html 	= 	'<a data-ajax="false" href="'+base_url+'job-activities/'+val.job_id+'/my-jobs" class="eye-anc">'
									//END
									}
									//for appearing job activity pop
									if (parseInt(val.curr_job_status) == 2 && parseInt(val.is_part_of_job)>0)
									{
										
										job_activity_pop_stat = '<div class="journey-btn">'
																		+cross_redirect_url_html
																				+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" viewBox="0 0 24.25 13.625" class="eye-off">'
																						+'<path d="M23.584,2.436 L1.753,13.360 C1.578,13.447 1.392,13.489 1.209,13.489 C0.762,13.489 0.333,13.243 0.119,12.818 C-0.182,12.217 0.061,11.487 0.662,11.186 L22.494,0.262 C23.095,-0.039 23.826,0.204 24.127,0.805 C24.429,1.405 24.185,2.135 23.584,2.436 ZM12.009,2.180 C9.499,2.180 7.460,4.173 7.379,6.660 L2.952,8.875 C2.089,7.981 1.369,7.467 1.370,6.811 C1.369,6.092 2.232,5.545 3.202,4.481 C4.973,2.539 7.885,0.087 12.123,-0.006 C14.263,0.041 16.063,0.690 17.551,1.570 L14.661,3.015 C13.909,2.490 12.996,2.180 12.009,2.180 ZM12.009,11.443 C14.479,11.443 16.492,9.512 16.633,7.080 L21.295,4.747 C22.158,5.641 22.877,6.155 22.877,6.811 C22.877,7.530 22.015,8.077 21.044,9.141 C19.273,11.083 16.362,13.535 12.123,13.628 C9.984,13.581 8.183,12.933 6.696,12.052 L9.452,10.673 C10.185,11.158 11.063,11.443 12.009,11.443 Z" class="cls-1"/>'
																				+'</svg>'
																				+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 487.55 487.55" xml:space="preserve" class="eye-on">'
																						+'<path id="XMLID_50_" d="M481.325,227.515c-224.8-258.6-428-53.9-476.4,2.8c-6.5,7.6-6.6,18.8-0.1,26.4    c221.9,259,423.4,64.6,476.5,3.7C489.625,251.015,489.625,237.015,481.325,227.515z M329.825,250.715c-3.3,41.4-36.8,75-78.3,78.3    c-52.7,4.2-96.3-39.5-92.1-92.1c3.3-41.4,36.8-75,78.3-78.3C290.325,154.415,333.925,198.115,329.825,250.715z" fill="#ff0000"/>'
																						+'<path id="XMLID_34_" d="M244.625,188.615c-30.4,0-55.2,24.8-55.2,55.2c0,7.3,5.9,13.2,13.2,13.2s13.2-5.9,13.2-13.2    c0-15.9,12.9-28.8,28.8-28.8c7.3,0,13.2-5.9,13.2-13.2S251.825,188.615,244.625,188.615z" fill="#ff0000"/>'
																				+'</svg>'
																		+'</a>'
																+'</div>';
									}
									//END
									if (is_my_job == 1) {
										
										if (parseInt(val.total_bids)>0) {
											qoute_html = '<div class="journey-btn">'
															+'<a data-ajax="false" href="javascript:void(0);" onclick="set_job_id('+val.job_id+');" class="submit-leg quote-leg">VIEW</a>'
														+'</div>';
										}				
										else
										{
											qoute_html = '<div class="journey-btn">'
															+'<a data-ajax="false" href="javascript:void(0)" class="submit-leg quote-leg">NO BIDS</a>'
														+'</div>';
										}
										
									}
									else
									{
										if (parseInt(val.curr_job_status) == 2 && parseInt(val.is_part_of_job)>0)
										{
												qoute_html = '<div class="journey-btn">'
														+'<a data-ajax="false" href="javascript:void(0)" class="submit-leg quote-leg" onclick="open_view_bid_popup(\''+val.job_id+'\');">VIEW BID</a>'
													+'</div>';
										}
										else if(parseInt(val.curr_job_status) != 2)
										{
												qoute_html = '<div class="journey-btn">'
														+'<a data-ajax="false" href="javascript:void(0)" class="submit-leg quote-leg" onclick="submit_qoute_leg(\''+val.job_id+'\');">QUOTE</a>'
													+'</div>';
										}
										
										user_rating = '<div class="client-star">'
															+user_rating_value
														+'</div>';		
									}
									
									html_data=html_data+'<div class="client-each">'
																+'<div class="client-top-table">'
																		+'<div class="client-top-row">'
																			+'<div class="client-top-cell">'
																	+'<div class="client-pic">'
																	   +'<img src="'+val.profile_image+'" />'
																		+'</div>'
																		 +'<div class="client-name">'
																			 +'<h5>'+val.client_name+'</h5>'
																			 +user_rating
																		 +'</div>'
																	 +'</div>'
																	 +'<div class="client-top-cell">'
																		+'<p>'+val.job_description+'</p>'
																	 +'</div>'
																+'</div>'
															+'</div>'
																	+'<div class="client-det-table">'
																		+'<div class="client-det-row">'
																			+'<div class="client-det-cell client-det-cell-dis">'
																				+'<div class="pic-des pickup">'
																					+'<span>PICKUP</span>'
																					+'<span>'+val.pick_up_address+'</span>'
																				+'</div>'
																				+'<div class="pic-arrow">'
																					+'<img src="'+assets_url+'site/images/picup-arrow.png" alt="picup-arrow" />'
																				+'</div>'
																				+'<div class="pic-des destination">'
																					+'<span>DESTINATION</span>'
																					+'<span>'+val.drop_address+'</span>'
																				+'</div>'
																			+'</div>'
																			+'<div class="client-det-cell client-det-cell-det">'
																				+'<div class="journey-row">'
																					+'<span>JOB ID</span>'
																					+'<span class="sp-cont">'+val.job_id+'</span>'
																				+'</div>'
																				+'<div class="journey-row">'
																					+'<span>DISTANCE</span>'
																					+'<span class="sp-cont">'+val.job_distance+' '+val.job_distance_unit+'</span>'
																				+'</div>'
																				+'<div class="journey-row">'
																					+'<span>Delivery Date</span>'
																					+'<span class="sp-cont">'+val.delivery_date+'</span>'
																				+'</div>'
																				+'<div class="journey-row">'
																					+'<span>HEIGHT  x  WIDTH  x  DEPTH</span>'
																					+'<span class="sp-cont">'+val.height+'  x  '+val.width+'  x  '+val.depth+' mt</span>'
																				+'</div>'
																				+'<div class="journey-row">'
																					+'<span>Weight</span>'
																					+'<span class="sp-cont">'+val.weight+' Tonnes</span>'
																				+'</div>'
																				+'<div class="journey-row">'
																					+'<span>NUMBER OF BIDSS</span>'
																					+'<span class="sp-cont">'+val.total_bids+'</span>'
																				+'</div>'
																				+'<div class="journey-row text-right journey-footer">'
																					+msg_pop_html_active_stat
																					+job_activity_pop_stat
																					+qoute_html
																				+'</div>'
																			+'</div>'
																		+'</div>'
																	+'</div>'
																+'</div>';
															});
						}
						
						if (t == '1') {
							$("#jobs_results").append(html_data);
							
						}
						else
						{
							$("#jobs_results").html(html_data);
						}
						
						$('#is_ajax_load').val(0);
						//alert(data);
						
						if(parseInt(has_next_list)>0)
							$('#is_next_value').val(1);
						else
							$('#is_next_value').val(0);
						
					},
					error: function(){
						$('#is_ajax_load').val(0);
					} 	        
			   });
		}
		else
		{
			$('#is_ajax_load').val(0);
		}
	}
</script>	
<script type="text/javascript" src="<?php echo assets_url('site/js/pages/dashboard-index.js'); ?>"></script>	
	
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

			
			
			
			
			<a data-ajax="false" href="<?php echo base_url().'dashboard' ?>" class="cancel-signup">
				<img src="<?php echo assets_url(); ?>site/images/cross.png" alt="cross" />
			</a>
			<div class="signup-top">
				<span>Jobs</span>
			</div>
			
			<form name="" id="" action="" method="post" data-ajax="false">
				<input type="hidden" class="pagenum" value="1" />
				<input type="hidden" class="total-page" value="<?php echo $total_jobs_count; ?>" />
				<input type="hidden" name="is_next_value" id="is_next_value" value="<?php echo (isset($has_next_list) && $has_next_list>0) ? 1 : 0; ?>" />
				<input type="hidden" name="is_ajax_load" id="is_ajax_load" value="0" />
				
				<div class="filter-table">
				    <div class="filter-row">
					   <div class="filter-cell filter-text-cell">
						  <h5>FILTER</h5>
						  <span>SHOWING <?php echo (isset($total_jobs_count) && $total_jobs_count>0) ? $total_jobs_count : 0; ?> JOBS</span>
					   </div>
					   <div class="filter-cell filter-content-cell">
						  <span class="sort-text">SORT BY</span>
						  <?php if($is_my_job!='my-jobs')
							{
							?>
						  <div class="selectForm">
							 <select name="sort_by_rating" id="sort_by_rating" onchange="return getresult(1, 0)">
								<option value="">Rating</option>
								<option value="high">High to low</option>
								<option value="low">Low to high</option>
							 </select>
						  </div>
						  <?php
							}
							?>
						  <div class="selectForm selectForm-back">
							 <select name="sort_by_distance" id="sort_by_distance" onchange="return getresult(1, 0)">
								<option value="">DISTANCE</option>
								<option value="long">Longest</option>
								<option value="short">Shortest</option>
							 </select>
						  </div>
					   </div>
				    </div>
				</div>
			</form>
			
			<div id="jobs_results" class="client-wrap">
				<?php
					if(isset($all_job_details) && count($all_job_details)>0)
					{
						foreach($all_job_details as $key => $result)
						{
							$job_id    	= strval($result['_id']);
							$client_f_name = (isset($result['user_details']['first_name'])) ?$result['user_details']['first_name'] : '';
							$client_l_name = (isset($result['user_details']['last_name']) && $result['user_details']['last_name']!='') ?$result['user_details']['last_name'] : '';
							$client_name   = (strlen($client_f_name." ".$client_l_name)>50) ? mb_substr($client_f_name." ".$client_l_name,0,50,'UTF-8')."..." : $client_f_name." ".$client_l_name;
							
							$profile_image = (isset($result['user_details']['profile_image'])) ?$result['user_details']['profile_image'] : '';
							$job_description_db = (isset($result['description'])) ?$result['description'] : '';
							$job_description   = (strlen($job_description_db)>500) ? mb_substr($job_description_db,0,500,'UTF-8')."..." : $job_description_db;
							
							$pick_up_address_db = (isset($result['pickup_address']['address'])) ?$result['pickup_address']['address'] : '';
							$pick_up_address   = (strlen($pick_up_address_db)>100) ? mb_substr($pick_up_address_db,0,100,'UTF-8')."..." : $pick_up_address_db;
							$drop_address_db = (isset($result['drop_address']['address'])) ? $result['drop_address']['address'] : '';
							$drop_address   = (strlen($drop_address_db)>100) ? mb_substr($drop_address_db,0,100,'UTF-8')."..." : $drop_address_db;
							$job_distance = (isset($result['distance'])) ? number_format($result['distance'],2,'.','') : '0';
							$job_distance_unit = (isset($result['distance_type'])) ? $result['distance_type'] : '';
							$delivery_date = (isset($result['delivery_date']) && $result['delivery_date']!='') ? date("j F Y",strtotime($result['delivery_date'])) : '';
							$height 		= (isset($result['size']['height'])) ?$result['size']['height'] : '0';
							$width 			= (isset($result['size']['width']))  ?$result['size']['width'] : '0';
							$depth			= (isset($result['size']['depth']))  ?$result['size']['depth'] : '0';
							$weight			= (isset($result['weight']))  		 ?$result['weight'] : '0';
							$total_bids 	= (isset($result['quote_count'])) 	 ?$result['quote_count'] : '0';
				
				?>
							<div class="client-each">
								<div class="client-top-table">
									<div class="client-top-row">
										<div class="client-top-cell">
											<div class="client-pic">
												<?php
													if($profile_image!= '')
													{
													?>
														<img src="<?php echo main_base_url().'thumb.php?height=70&width=70&type=aspectratio&img='.assets_url().'uploads/user_images/thumb/'.$profile_image;?>" alt="<?php echo $profile_image; ?>" />
												   <?php
													}
													else
													{
													?>
														<img src="<?php echo main_base_url().'thumb.php?height=70&width=70&type=aspectratio&img='.assets_url().'site/images/user-image.png';?>" alt="user-image.png" />
													<?php	
													}
													?>
											</div>
											<div class="client-name">
												<h5><?php echo ucfirst(trim($client_f_name." ".$client_l_name)); ?></h5>
												<?php if($is_my_job!='my-jobs')
												{
												?>
												<div id="start_rating_<?php echo $key; ?>" class="client-star">
													<!--<img src="<?php echo base_url(); ?>assets/site/images/client-star.png" alt="client-star" />-->
													<?php echo "<script> 
																$('#start_rating_".$key."').html(generate_all_jobs_images('".$result['user_details']['user_rating']."'));
																</script>" ?>
												</div>
												<?php
												}
												?>
											</div>
										</div>
										<div class="client-top-cell">
										    <p><?php echo ucfirst(trim($job_description)); ?></p>
										</div>
									</div>
								</div>
								<div class="client-det-table">
									<div class="client-det-row">
										<div class="client-det-cell client-det-cell-dis">
											<div class="pic-des pickup">
												<span>PICKUP</span>
												<span><?php echo ucfirst(trim($pick_up_address)); ?></span>
											</div>
											<div class="pic-arrow">
												<img src="<?php echo assets_url(); ?>site/images/picup-arrow.png" alt="picup-arrow" />
											</div>
											<div class="pic-des destination">
												<span>DESTINATION</span>
												<span><?php echo ucfirst(trim($drop_address)); ?></span>
											</div>
										</div>
										<div class="client-det-cell client-det-cell-det">
											<div class="journey-row">
												<span>JOB ID</span>
												<span class="sp-cont"><?php echo $job_id; ?></span>
											</div>
											<div class="journey-row">
												<span>DISTANCE</span>
												<span class="sp-cont"><?php echo $job_distance; ?> <?php echo ucfirst($job_distance_unit); ?></span>
											</div>
											<div class="journey-row">
												<span>Delivery Date</span>
												<span class="sp-cont"><?php echo $delivery_date; ?></span>
											</div>
											<div class="journey-row">
												<span>HEIGHT  x  WIDTH  x  DEPTH</span>
												<span class="sp-cont"><?php echo $height; ?>  x  <?php echo $width; ?>  x  <?php echo $depth; ?> mt</span>
											</div>
											<div class="journey-row">
												<span>Weight</span>
												<span class="sp-cont"><?php echo $weight ?> Tonnes</span>
											</div>
											<div class="journey-row">
												<span>NUMBER OF BIDS</span>
												<span class="sp-cont"><?php echo $total_bids; ?></span>
											</div>
											<div class="journey-row text-right journey-footer">
				<!--		check for all jobs or my jobs for appearing the chat icon						-->
												<?php if($is_my_job!='my-jobs')
												{
														if($result['curr_job_status'] == '2')
														{
																if($result['is_part_of_job']>0)
																{
														?>
																<div class="journey-btn">
																		<a href="javascript:void(0);" data-ajax="false" onclick="open_message_popup('<?php echo $job_id; ?>')">
																		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.25" height="16.125" viewBox="0 0 19.25 16.125">
																			<path d="M18.327,11.980 L15.213,11.980 L15.213,16.126 L11.061,11.980 L8.044,11.980 C7.673,11.980 7.352,11.764 7.202,11.451 L8.913,9.743 L11.958,9.743 C12.962,9.743 13.780,8.926 13.780,7.923 L13.780,3.470 L18.327,3.470 C18.842,3.470 19.260,3.887 19.260,4.401 L19.260,11.049 C19.260,11.563 18.842,11.980 18.327,11.980 ZM11.610,8.507 L8.197,8.507 L4.045,12.653 L4.045,8.507 L0.930,8.507 C0.416,8.507 -0.002,8.090 -0.002,7.576 L-0.002,0.928 C-0.002,0.414 0.416,-0.003 0.930,-0.003 L11.610,-0.003 C12.125,-0.003 12.543,0.414 12.543,0.928 L12.543,7.576 C12.543,8.090 12.125,8.507 11.610,8.507 Z" class="cls-1"/>
																		</svg>
																	</a>
																</div>
														<?php
																}
														}
														else
														{
														?>
																<div class="journey-btn">
																		<a href="javascript:void(0);" data-ajax="false" onclick="open_message_popup('<?php echo $job_id; ?>')">
																		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.25" height="16.125" viewBox="0 0 19.25 16.125">
																			<path d="M18.327,11.980 L15.213,11.980 L15.213,16.126 L11.061,11.980 L8.044,11.980 C7.673,11.980 7.352,11.764 7.202,11.451 L8.913,9.743 L11.958,9.743 C12.962,9.743 13.780,8.926 13.780,7.923 L13.780,3.470 L18.327,3.470 C18.842,3.470 19.260,3.887 19.260,4.401 L19.260,11.049 C19.260,11.563 18.842,11.980 18.327,11.980 ZM11.610,8.507 L8.197,8.507 L4.045,12.653 L4.045,8.507 L0.930,8.507 C0.416,8.507 -0.002,8.090 -0.002,7.576 L-0.002,0.928 C-0.002,0.414 0.416,-0.003 0.930,-0.003 L11.610,-0.003 C12.125,-0.003 12.543,0.414 12.543,0.928 L12.543,7.576 C12.543,8.090 12.125,8.507 11.610,8.507 Z" class="cls-1"/>
																		</svg>
																	</a>
																</div>
														<?php		
														}
														
												?>
												<?php
												}
												else
												{
												?>
														<div class="journey-btn">
																		<a href="javascript:void(0);" data-ajax="false" onclick="open_message_popup('<?php echo $job_id; ?>')">
																		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.25" height="16.125" viewBox="0 0 19.25 16.125">
																			<path d="M18.327,11.980 L15.213,11.980 L15.213,16.126 L11.061,11.980 L8.044,11.980 C7.673,11.980 7.352,11.764 7.202,11.451 L8.913,9.743 L11.958,9.743 C12.962,9.743 13.780,8.926 13.780,7.923 L13.780,3.470 L18.327,3.470 C18.842,3.470 19.260,3.887 19.260,4.401 L19.260,11.049 C19.260,11.563 18.842,11.980 18.327,11.980 ZM11.610,8.507 L8.197,8.507 L4.045,12.653 L4.045,8.507 L0.930,8.507 C0.416,8.507 -0.002,8.090 -0.002,7.576 L-0.002,0.928 C-0.002,0.414 0.416,-0.003 0.930,-0.003 L11.610,-0.003 C12.125,-0.003 12.543,0.414 12.543,0.928 L12.543,7.576 C12.543,8.090 12.125,8.507 11.610,8.507 Z" class="cls-1"/>
																		</svg>
																	</a>
														</div>
												<?php
												}
												?>
										<!--					END							-->
										
				<!--		check for all jobs or my jobs for appearing the job activity icon						-->
										<?php
												
										if($result['curr_job_status'] == '2' && $result['is_part_of_job']>0)
										{
										?>
												<div class="journey-btn">
												<?php if($is_my_job!='my-jobs')
												{
												?>
												<a href="<?php echo base_url().'job-activities/'.$job_id.'/all-jobs' ?>" data-ajax="false" class="eye-anc">
												<?php
												}
												else
												{
												?>
												<a href="<?php echo base_url().'job-activities/'.$job_id.'/my-jobs' ?>" data-ajax="false" class="eye-anc">
												<?php
												}
												?>
													
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" viewBox="0 0 24.25 13.625" class="eye-off">
															<path d="M23.584,2.436 L1.753,13.360 C1.578,13.447 1.392,13.489 1.209,13.489 C0.762,13.489 0.333,13.243 0.119,12.818 C-0.182,12.217 0.061,11.487 0.662,11.186 L22.494,0.262 C23.095,-0.039 23.826,0.204 24.127,0.805 C24.429,1.405 24.185,2.135 23.584,2.436 ZM12.009,2.180 C9.499,2.180 7.460,4.173 7.379,6.660 L2.952,8.875 C2.089,7.981 1.369,7.467 1.370,6.811 C1.369,6.092 2.232,5.545 3.202,4.481 C4.973,2.539 7.885,0.087 12.123,-0.006 C14.263,0.041 16.063,0.690 17.551,1.570 L14.661,3.015 C13.909,2.490 12.996,2.180 12.009,2.180 ZM12.009,11.443 C14.479,11.443 16.492,9.512 16.633,7.080 L21.295,4.747 C22.158,5.641 22.877,6.155 22.877,6.811 C22.877,7.530 22.015,8.077 21.044,9.141 C19.273,11.083 16.362,13.535 12.123,13.628 C9.984,13.581 8.183,12.933 6.696,12.052 L9.452,10.673 C10.185,11.158 11.063,11.443 12.009,11.443 Z" class="cls-1"/>
														</svg>
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 487.55 487.55" xml:space="preserve" class="eye-on">
															<path id="XMLID_50_" d="M481.325,227.515c-224.8-258.6-428-53.9-476.4,2.8c-6.5,7.6-6.6,18.8-0.1,26.4    c221.9,259,423.4,64.6,476.5,3.7C489.625,251.015,489.625,237.015,481.325,227.515z M329.825,250.715c-3.3,41.4-36.8,75-78.3,78.3    c-52.7,4.2-96.3-39.5-92.1-92.1c3.3-41.4,36.8-75,78.3-78.3C290.325,154.415,333.925,198.115,329.825,250.715z" fill="#ff0000"/>
															<path id="XMLID_34_" d="M244.625,188.615c-30.4,0-55.2,24.8-55.2,55.2c0,7.3,5.9,13.2,13.2,13.2s13.2-5.9,13.2-13.2    c0-15.9,12.9-28.8,28.8-28.8c7.3,0,13.2-5.9,13.2-13.2S251.825,188.615,244.625,188.615z" fill="#ff0000"/>
														</svg>
													</a>
												</div>
										<?php
										}
										?>
										<!--			END						-->
												<?php if($is_my_job!='my-jobs')
												{
													if($result['curr_job_status'] == '2' && $result['is_part_of_job']>0)
													{
												?>
														<div class="journey-btn">
															<a data-ajax="false" href="<?php echo base_url().'all-quotes?job_id='.$job_id ?>" class="submit-leg quote-leg" onclick="open_view_bid_popup('<?php echo $job_id; ?>')">VIEW BID</a>
														</div>
												<?php		
													}
													elseif($result['curr_job_status'] != '2')
													{
												?>
														<div class="journey-btn">
															<a data-ajax="false" href="<?php echo base_url().'all-quotes?job_id='.$job_id ?>" class="submit-leg quote-leg">QUOTE</a>
														</div>
												<?php
													}
												?>
												<?php
												}else
												{
													if($result['quote_count']>0)
													{
												?>
												<div class="journey-btn">
													<a data-ajax="false" href="<?php echo base_url().'all-quotes?job_id='.$job_id ?>" class="submit-leg quote-leg">VIEW</a>
												</div>
												<?php
													}
													else
													{
												?>
												<div class="journey-btn">
													<a data-ajax="false" href="javascript:void(0)" class="submit-leg quote-leg" disabled>NO BIDS</a>
												</div>
												<?php
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
				<?php
						}
					}
					else
					{
						echo '<div class="client-each">
								No jobs found
						     </div>';
					}
				?>
			</div>
			
			<div id="loader-icon" class="client-load text-center hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
		</div>
	</div>
	
	<?php
		//Google api Key is important and we are using the key stored in database
		if(isset($settings[0]['google_map_api_key']) && !empty($settings[0]['google_map_api_key']))
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key='.$settings[0]['google_map_api_key'].'&libraries=geometry,places"></script>';
		else
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry,places"></script>';
	?>
	<script>
		$( ".inp-address" ).each(function() {
			var id = $(this).attr('id');
			var k  = new google.maps.places.SearchBox(this);
			
			google.maps.event.addListener(k, "places_changed", function() {
				var e = place = k.getPlaces();
				var srch_lat  = srch_lon = '';
				if (e.length > 0) {
					   
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lat = place[0].geometry.location.lat();
					
					if(typeof(place[0].geometry.location) != "undefined")
						srch_lon = place[0].geometry.location.lng();
					
					$('#'+id+'_lat').val(srch_lat);
					$('#'+id+'_long').val(srch_lon);
				}
			})
		});
	</script>