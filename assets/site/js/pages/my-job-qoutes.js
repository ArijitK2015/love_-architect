	function generate_raing_images(rating) {
		var res = (((typeof(rating) != "undefined")) && (rating != '')) ? rating.split(".") : [];
			
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
		
	$(document).ready(function(){
			
		var ajaxURL 	= main_base_url+'Jobs_controllers/job_details_quote_leg_ajax';
			
		$.ajax({
			url: 	ajaxURL,
			type: 	"POST",
			data:  	{
						job_id:	job_id,
						user_id:	user_id
					},
			//beforeSend: function(){
			//$('#loader-icon').show();
			//},
			complete: function(){
				$('#loading-filter-background').hide();
			},
			success: function(data)
			{
				var response 	= jQuery.parseJSON(data);
					
				if(typeof response =='object')
				{
					job_det 			= (typeof(response.job_details) 		!= "undefined") ? response.job_details 		: {};
					job_quote_leg_det 	= (typeof(response.job_quote_leg_det) 	!= "undefined") ? response.job_quote_leg_det : {};
						
					user_det 			= (typeof(response.user_details) 		!= "undefined") ? response.user_details 	: {};
					user_id 			= (typeof(user_det.id) 				!= "undefined") ? user_det.id 			: '';
					user_name 		= (typeof(user_det.name) 			!= "undefined") ? user_det.name 			: '';
					user_image 		= (typeof(user_det.user_image) 		!= "undefined") ? user_det.user_image 		: '';						
						
					job_quotes 		= job_quote_leg_det.job_quote;
					job_date_range 	= job_quote_leg_det.job_date_range;
						
					job_prices_extra 	= (typeof(job_quote_leg_det.job_prices_extra) != "undefined") ? job_quote_leg_det.job_prices_extra 	: '';
					job_total_prices 	= (typeof(job_quote_leg_det.job_total_prices) != "undefined") ? job_quote_leg_det.job_total_prices 	: '';
						
					all_job_quotes_html = all_job_legs_html = '';
					all_quote_ids 		= [];
						
					var delivary_type   	= job_det.deliver_method;
					var delivary_type_det 	= '';
						
					//console.log('arijit1: '+user_image);
						
					if (job_quotes.length > 0)
					{
						$("#total_qoutes").html('Quotes ('+job_quotes.length+')');
							
							
						for (var i = 0; i < job_quotes.length; i++)
						{
							var job_quote_det 			= job_quotes[i];
							var leg_total_price 		= job_total_prices[i];
							var total_price 			= leg_total_price;
							var leg_extra_price 		= job_prices_extra[i];
							var leg_date_range 			= job_date_range[i];
							var all_leg_ids 			= [];
							var job_quote_det_length 	= job_quote_det.length;
							var li_class = (i == 0) ? '' : 'hide';
								
							if (typeof(job_quote_det_length) == "undefined")
							{
								var quote_user_details 	= job_quote_det.user_details;
								var quote_user_image 	= (quote_user_details.profile_image!='') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+assets_url+'uploads/user_images/thumb/'+quote_user_details.profile_image : main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+assets_url+'site/images/user-image.png';
								var quote_user_rating 	= generate_raing_images(quote_user_details.user_rating);
								
								all_quote_ids.push(job_quote_det.id);
								
								all_job_quotes_html		= all_job_quotes_html +'<div class="quote-row">'
													+'<div class="quote-cell">'
														+'<div class="q-in-table" id="swipe_check'+i+'">'
															+'<div class="q-in-row">'
																+'<div class="q-in-cell quote-cell-big">'
																	+'<div class="quote-dp">'
																		+'<img src="'+quote_user_image+'" alt="user-img" />'
																	+'</div>'
																	+'<div class="quote-name">'
																		+'<h3><a href="javascript:void(0)">'+quote_user_details.first_name+' '+quote_user_details.last_name+'</a></h3>'
																		+'<div class="client-star">'
																			+quote_user_rating
																		+'</div>'
																		+'<span>QUOTE PRICE <big>$'+total_price+'</big></span>'
																	+'</div>'
																+'</div>'
																+'<div class="q-in-cell quote-cell-small">'
																	+'<a href="javascript:void(0)">'
																		+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.094" height="3.812" viewBox="0 0 19.094 3.812">'
																			+'<path d="M17.185,3.826 C16.130,3.826 15.276,2.970 15.276,1.915 C15.276,0.860 16.130,0.004 17.185,0.004 C18.239,0.004 19.093,0.860 19.093,1.915 C19.093,2.970 18.239,3.826 17.185,3.826 ZM9.550,3.826 C8.496,3.826 7.641,2.970 7.641,1.915 C7.641,0.860 8.496,0.004 9.550,0.004 C10.604,0.004 11.459,0.860 11.459,1.915 C11.459,2.970 10.604,3.826 9.550,3.826 ZM1.916,3.826 C0.862,3.826 0.007,2.970 0.007,1.915 C0.007,0.860 0.862,0.004 1.916,0.004 C2.970,0.004 3.824,0.860 3.824,1.915 C3.824,2.970 2.970,3.826 1.916,3.826 Z" class="cls-1"/>'
																		+'</svg>'
																	+'</a>'
																+'</div>'
																+'<div class="q-in-cell quote-cell-small">'
																	+'<a href="javascript:void(0)"  onclick="open_popup_customer(\''+job_det.id+'\', 2)">'
																		+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="27.25" height="29.812" viewBox="0 0 27.25 29.812">'
																			+'<path d="M26.967,1.680 L11.680,29.545 C11.477,29.749 11.195,29.817 10.918,29.779 C10.623,29.837 10.316,29.775 10.098,29.555 L0.278,19.670 C-0.082,19.307 -0.062,18.694 0.323,18.306 C0.709,17.919 1.319,17.898 1.680,18.261 L10.718,27.360 L25.575,0.280 C25.933,-0.080 26.540,-0.060 26.923,0.325 C27.305,0.710 27.326,1.320 26.967,1.680 Z" class="cls-1"/>'
																		+'</svg>'
																	+'</a>'
																+'</div>'
															+'</div>'
														+'</div>'
														+'<div class="swip-overlay"></div>'
														+'<div class="delete-contact">'
															+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="27.063" height="34.407" viewBox="0 0 27.063 34.407">'
																+'<path d="M27.039,4.310 C27.003,4.889 26.502,5.329 25.921,5.293 L0.988,3.751 C0.407,3.715 -0.035,3.217 0.001,2.639 L0.033,2.126 C0.069,1.548 0.569,1.108 1.151,1.144 L10.585,1.727 C10.599,1.495 10.600,1.330 10.630,0.842 C10.661,0.353 11.084,-0.018 11.575,0.012 L15.881,0.278 C16.372,0.308 16.745,0.729 16.715,1.218 C16.684,1.707 16.674,1.794 16.655,2.102 L26.084,2.685 C26.665,2.722 27.107,3.220 27.071,3.798 L27.039,4.310 ZM1.751,6.514 L13.487,6.514 L13.696,6.514 L25.431,6.514 C25.983,6.514 26.431,6.959 26.431,7.509 L22.420,33.402 C22.420,33.951 21.973,34.397 21.420,34.397 L13.696,34.397 L13.487,34.397 L5.762,34.397 C5.210,34.397 4.762,33.951 4.762,33.402 L0.751,7.509 C0.751,6.959 1.199,6.514 1.751,6.514 ZM18.360,30.691 L18.758,30.739 C19.307,30.806 19.805,30.418 19.872,29.872 L22.105,11.642 C22.171,11.096 21.781,10.601 21.233,10.534 L20.835,10.486 C20.287,10.419 19.788,10.808 19.722,11.353 L17.489,29.583 C17.422,30.128 17.812,30.624 18.360,30.691 ZM12.391,29.795 C12.391,30.344 12.838,30.790 13.391,30.790 L13.792,30.790 C14.344,30.790 14.792,30.344 14.792,29.795 L14.792,11.430 C14.792,10.881 14.344,10.435 13.792,10.435 L13.391,10.435 C12.838,10.435 12.391,10.881 12.391,11.430 L12.391,29.795 ZM7.356,29.872 C7.423,30.418 7.921,30.806 8.470,30.739 L8.868,30.691 C9.416,30.624 9.806,30.128 9.739,29.583 L7.506,11.353 C7.440,10.808 6.941,10.419 6.393,10.486 L5.995,10.534 C5.447,10.601 5.057,11.096 5.123,11.642 L7.356,29.872 Z" class="cls-1"/>'
															+'</svg>'
														+'</div>'
													+'</div>'
												+'</div>';
							}
							else
							{
								
								//all_job_quotes_html	= all_job_quotes_html + '<li id="quote_leg_id_'+i+'" class="job_li '+li_class+'">'
								//					+'<input type="hidden" name="leg_total_price_'+i+'" id="leg_total_price_'+i+'" 	value="'+leg_total_price+'" />'
								//					+'<input type="hidden" name="leg_extra_price_'+i+'" id="leg_extra_price_'+i+'" 	value="'+leg_extra_price+'" />'
								//					+'<input type="hidden" name="leg_date_pick_'+i+'"   id="leg_date_pick_'+i+'" 	value="'+leg_date_range.pick_up+'" />'
								//					+'<input type="hidden" name="leg_date_frop_'+i+'"   id="leg_date_frop_'+i+'" 	value="'+leg_date_range.drop+'" />'
								//					
								//					+'<ul>';
								
								all_job_quotes_html		= all_job_quotes_html +'<div class="quote-row">'
																				+'<div class="quote-cell">'
																					+'<div class="q-in-table" id="swipe_check'+i+'">'
																						+'<div class="q-in-row">'
																							+'<div class="q-in-cell quote-cell-big">';																	
							
																								if(job_quote_det.length == 1)
																								{
																										var job_leg_det 		= job_quote_det[0];
																										var leg_user_details 	= job_leg_det.user_details;
																										var leg_user_image 		= (leg_user_details.profile_image!='') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+assets_url+'uploads/user_images/thumb/'+leg_user_details.profile_image : main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+assets_url+'site/images/user-image.png';
																										var leg_user_rating 	= generate_raing_images(leg_user_details.user_rating);
																										
																										all_leg_ids.push(job_leg_det.id);
																										
																										all_job_quotes_html	= all_job_quotes_html +'<div class="quote-dp">'
																																						+'<img src="'+leg_user_image+'" alt="user-img" />'
																																					+'</div>'
																								}
																								else
																								{
																									all_job_quotes_html	= all_job_quotes_html+'<div class="quote-multi-dps">';
																									
																									for (var i1 = 0; i1 < job_quote_det.length; i1++)
																									{
																										var job_leg_det 		= job_quote_det[i1];
																										var leg_user_details 	= job_leg_det.user_details;
																										var leg_user_image 		= (leg_user_details.profile_image!='') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+assets_url+'uploads/user_images/thumb/'+leg_user_details.profile_image : main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+assets_url+'site/images/user-image.png';
																										var leg_user_rating 	= generate_raing_images(leg_user_details.user_rating);
																										
																										all_leg_ids.push(job_leg_det.id);
																										
																										all_job_quotes_html	= all_job_quotes_html +'<div class="quote-multi-dp">'
																																						+'<img src="'+leg_user_image+'" alt="user-img" />'
																																				  +'</div>'							
																									}
																									
																									all_job_quotes_html	= all_job_quotes_html+'</div>';
																								}
								
								
								all_job_quotes_html	= all_job_quotes_html+'<div class="quote-name">'
																				+'<h3><a href="javascript:void(0)">'+leg_user_details.first_name+' '+leg_user_details.last_name+'</a></h3>'
																				+'<div class="client-star">'
																					+leg_user_rating
																				+'</div>'
																				+'<span>QUOTE PRICE <big>$'+total_price+'</big></span>'
																			+'</div>'
																		+'</div>'
																		+'<div class="q-in-cell quote-cell-small">'
																			+'<a href="javascript:void(0)">'
																				+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.094" height="3.812" viewBox="0 0 19.094 3.812">'
																					+'<path d="M17.185,3.826 C16.130,3.826 15.276,2.970 15.276,1.915 C15.276,0.860 16.130,0.004 17.185,0.004 C18.239,0.004 19.093,0.860 19.093,1.915 C19.093,2.970 18.239,3.826 17.185,3.826 ZM9.550,3.826 C8.496,3.826 7.641,2.970 7.641,1.915 C7.641,0.860 8.496,0.004 9.550,0.004 C10.604,0.004 11.459,0.860 11.459,1.915 C11.459,2.970 10.604,3.826 9.550,3.826 ZM1.916,3.826 C0.862,3.826 0.007,2.970 0.007,1.915 C0.007,0.860 0.862,0.004 1.916,0.004 C2.970,0.004 3.824,0.860 3.824,1.915 C3.824,2.970 2.970,3.826 1.916,3.826 Z" class="cls-1"/>'
																				+'</svg>'
																			+'</a>'
																		+'</div>'
																		+'<div class="q-in-cell quote-cell-small">'
																			+'<a href="javascript:void(0)" onclick="open_popup_customer(\''+job_det.id+'\', 2)">'
																				+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="27.25" height="29.812" viewBox="0 0 27.25 29.812">'
																					+'<path d="M26.967,1.680 L11.680,29.545 C11.477,29.749 11.195,29.817 10.918,29.779 C10.623,29.837 10.316,29.775 10.098,29.555 L0.278,19.670 C-0.082,19.307 -0.062,18.694 0.323,18.306 C0.709,17.919 1.319,17.898 1.680,18.261 L10.718,27.360 L25.575,0.280 C25.933,-0.080 26.540,-0.060 26.923,0.325 C27.305,0.710 27.326,1.320 26.967,1.680 Z" class="cls-1"/>'
																				+'</svg>'
																			+'</a>'
																		+'</div>'
																	+'</div>'
																+'</div>'
																+'<div class="swip-overlay"></div>'
																+'<div class="delete-contact">'
																	+'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="27.063" height="34.407" viewBox="0 0 27.063 34.407">'
																		+'<path d="M27.039,4.310 C27.003,4.889 26.502,5.329 25.921,5.293 L0.988,3.751 C0.407,3.715 -0.035,3.217 0.001,2.639 L0.033,2.126 C0.069,1.548 0.569,1.108 1.151,1.144 L10.585,1.727 C10.599,1.495 10.600,1.330 10.630,0.842 C10.661,0.353 11.084,-0.018 11.575,0.012 L15.881,0.278 C16.372,0.308 16.745,0.729 16.715,1.218 C16.684,1.707 16.674,1.794 16.655,2.102 L26.084,2.685 C26.665,2.722 27.107,3.220 27.071,3.798 L27.039,4.310 ZM1.751,6.514 L13.487,6.514 L13.696,6.514 L25.431,6.514 C25.983,6.514 26.431,6.959 26.431,7.509 L22.420,33.402 C22.420,33.951 21.973,34.397 21.420,34.397 L13.696,34.397 L13.487,34.397 L5.762,34.397 C5.210,34.397 4.762,33.951 4.762,33.402 L0.751,7.509 C0.751,6.959 1.199,6.514 1.751,6.514 ZM18.360,30.691 L18.758,30.739 C19.307,30.806 19.805,30.418 19.872,29.872 L22.105,11.642 C22.171,11.096 21.781,10.601 21.233,10.534 L20.835,10.486 C20.287,10.419 19.788,10.808 19.722,11.353 L17.489,29.583 C17.422,30.128 17.812,30.624 18.360,30.691 ZM12.391,29.795 C12.391,30.344 12.838,30.790 13.391,30.790 L13.792,30.790 C14.344,30.790 14.792,30.344 14.792,29.795 L14.792,11.430 C14.792,10.881 14.344,10.435 13.792,10.435 L13.391,10.435 C12.838,10.435 12.391,10.881 12.391,11.430 L12.391,29.795 ZM7.356,29.872 C7.423,30.418 7.921,30.806 8.470,30.739 L8.868,30.691 C9.416,30.624 9.806,30.128 9.739,29.583 L7.506,11.353 C7.440,10.808 6.941,10.419 6.393,10.486 L5.995,10.534 C5.447,10.601 5.057,11.096 5.123,11.642 L7.356,29.872 Z" class="cls-1"/>'
																	+'</svg>'
																+'</div>'
															+'</div>'
														+'</div>';
												
							
							}
						}
						
						$("#qoutes_result").html(all_job_quotes_html);
					}
					else
					{
						$("#qoutes_result").html('No quotes found');
					}
					
						generate_delete_slide();
					
				}
				else if (parseInt(data) == 0) {
					
					$("#total_qoutes").html('Quotes (0)');
					$("#qoutes_result").html('No quotes found');
				}
				else
				{
					$("#total_qoutes").html('Quotes (0)');
					$("#qoutes_result").html('No quotes found');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$("#total_qoutes").html('Quotes (0)');
				$("#qoutes_result").html('No quotes found');
			} 	        
	   });
				
	});
	
	
	function generate_delete_slide() {
		$('.swip-overlay').on('click', function(){
				$('.q-in-table').animate({'left':'0'}, 100);
				$(this).parent().find('.delete-contact').animate({'left':'-70px'}, 100);
				$(this).parent().find('.swip-overlay').css({'visibility':'hidden','opacity':'0'});
			});
		
		$(".q-in-table").each(function(){
				var id = $(this).attr('id');
				
				//console.log('set id: '+id);
				$("#"+id).swipe( {
					swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
						console.log("You swiped " + direction );
						
						if (direction == 'right') {
							$('.q-in-table').not($(this)).animate({'left':'0'}, 100);
							$('.q-in-table').not($(this)).parent().find('.delete-contact').animate({'left':'-70px'}, 100);
							$('.q-in-table').not($(this)).parent().find('.swip-overlay').css({'visibility':'hidden','opacity':'0'});
						 
							$(this).animate({'left':'70px'}, 100);
							$(this).parent().find('.delete-contact').animate({'left':'-10px'}, 100);
							$(this).parent().find('.swip-overlay').css({'visibility':'visible','opacity':'0.8'});
						}
						else{
							$('.q-in-table').animate({'left':'0'}, 100);
							$(this).parent().find('.delete-contact').animate({'left':'-70px'}, 100);
							$(this).parent().find('.swip-overlay').css({'visibility':'hidden','opacity':'0'});
						}
					}
				});
		});
	}
	