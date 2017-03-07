	//Initilize the necessary components on page load
	$(document).ready(function(){
		if (flash_msg != '') {
			if (flash_msg == 'payment_failed') 		error_msg = '<span class="error">Payment failed.'+flash_message_cont+' Please try again</span>';
			else if (flash_msg == 'payment_success') 	error_msg = '<span class="success">Payment success.</span>';
			else if (flash_msg == 'job_added') 		error_msg = '<span class="success">Job successfully posted.</span>';
			else if (flash_msg == 'error') 			error_msg = '<span class="error">Error occured. Please try again.</span>';
			else if (flash_msg == 'job_add_success') 	error_msg = '';
				
			if (error_msg != '') {
				$("#error_msg").html(error_msg);
				$("#error-section").show();
			}
				
			if (flash_msg == 'job_added') 			do_map_zoom_out = 1;
		}
			
		setTimeout(function(){
			$("#error-section").hide();
			$("#error_msg").html('');
		}, 5000);
			
		//add a custom validation rule 
		$.validator.addMethod("positivenumber", function (value, element, options){
			var bothEmpty = false;
			var data_value = parseFloat(value);
			if (data_value >= 0) bothEmpty = true;
			return bothEmpty;
		},"Please enter positive value.");
			
		//Initilize custom scroll bar
		$(".custom-scrollbar").mCustomScrollbar({
			scrollButtons:{
			    enable:true
			}
		});
			
		$('.infoI').on('click', function(){
			var info_content 	= $(this).attr('info-cont');
			if (info_content != '' && info_content != 'undefined')
				$("#info_content").html(info_content);
			
			$('body').toggleClass('info-popup-active');
			$(".overlay-popup").show();
		});
			
		//Intilize the popup open and close
		$('.close-btn').on('click', function(){
			$('#popup_cont').toggleClass('info-popup-active');
			
		});
	});
	
	//This function convert urlencoded strings to normal strings in jquery
	function string_escape(args) {
		try{
			fixedstring = decodeURIComponent(escape(args));
			fixedstring = decodeURIComponent((fixedstring+'').replace(/\+/g, '%20'));
		}
		catch(e){ fixedstring=args; }
		
		return fixedstring;
	}

	// Make the page full screen
	function launchFullscreen(element) {
		
		if(element.requestFullscreen) {
			element.requestFullscreen();
		} else if(element.mozRequestFullScreen) {
			element.mozRequestFullScreen();
		} else if(element.webkitRequestFullscreen) {
			element.webkitRequestFullscreen();
		} else if(element.msRequestFullscreen) {
			element.msRequestFullscreen();
		}	
		
		$("#goFS").html('<i class="fa fa-times-circle-o" aria-hidden="true"></i>');
		$("#goFS").attr('onclick', "exitFullscreen(document.documentElement);");
	}
	
	// Make the page to normal screen
	function exitFullscreen() {
		if(document.exitFullscreen) {
			document.exitFullscreen();
		} else if(document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if(document.webkitExitFullscreen) {
			document.webkitExitFullscreen();
		}
		
		$("#goFS").html('<i class="fa fa-arrows-alt" aria-hidden="true"></i>');
		$("#goFS").attr('onclick', "launchFullscreen(document.documentElement);");
	}
	
	//Function to get current latlng if location services enable
	function get_current_latlng(args) {
		show_addr_id = args;
		if (navigator.geolocation){
			navigator.geolocation.getCurrentPosition(showPosition);
		}
		else{
			var error_msg = "Geolocation is not supported by this browser.";
			if (error_msg != '') {
				$("#error_msg").html(error_msg);
				$("#error-section").show();
			}
		}
	}
	
	//Supporting fuction for get current latlng
	function showPosition(position){ 
		location.latitude	= position.coords.latitude;
		location.longitude	= position.coords.longitude;
		
		var geocoder 		= new google.maps.Geocoder();
		var latLng 		= new google.maps.LatLng(location.latitude, location.longitude);
	
		if (geocoder) {
			geocoder.geocode({ 'latLng': latLng}, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK)
				{
					$('#'+show_addr_id).val(results[0].formatted_address);
					if(typeof(results[0].geometry.location) != "undefined")
						srch_lat = results[0].geometry.location.lat();
				
					if(typeof(results[0].geometry.location) != "undefined")
						srch_lon = results[0].geometry.location.lng();
					
					$('#'+show_addr_id+'_lat').val(srch_lat);
					$('#'+show_addr_id+'_lng').val(srch_lon);
					
					if (show_addr_id == 'drop_address') {
						var pick_search_lat = $('#pickup_address_lat').val(), 	pick_search_lng = $('#pickup_address_lng').val();
						var drop_search_lat = srch_lat, 					drop_search_lng = srch_lon;
						
						p1 = new google.maps.LatLng(pick_search_lat, pick_search_lng), p2 = new google.maps.LatLng(drop_search_lat, drop_search_lng);
						
						calcDistance(p1, p2);
					}
				}
				else {
					var error_msg = "Geolocation is not supported by this browser.";
					if (error_msg != '') {
						$("#error_msg").html(error_msg);
						$("#error-section").show();
					}
				}
			}); //geocoder.geocode()
		}      
	} //showPosition
	
	function open_popup_image(args, img_link) {
		
		var html = '<div data-role="popup" id="imagePopup" data-overlay-theme="b" data-theme="b" data-corners="false">'
					+'<a href="#imagePopup" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a><img src="'+img_link+'" alt="">'
				   +'</div>';
		
		//$(html).appendTo("#"+args);
		$("#"+args).html(html);
		$("#"+args).trigger("create");
		
		$("#imagePopup").html(html);
		setTimeout(function(){ $("#imagePopup").popup({ positionTo: "origin" }).popup("open"); }, 150);
		
	}
	
	//Function to open the job options popup
	function open_popup(args){
			
		hide_terms('leg_trms_show');
			
		$("#user_img").show();	
		$(".popup-form").hide();
		$('#loading_content').show();
			
		$(".select-credit").show();
		$(".terms-anc").show();
		$(".agree-terms").show();
		$(".popup-accpt-bottom-btns").show();
			
		$("#current_job_id").val('');
			
		//console.log(job_det_actual);
			
		var postData 			= 'job_id='+job_det_actual.id+'&user_id='+session_user_id;
		var formURL 			= main_base_url+'Jobs_controllers/job_details_ajax';
		var marker_cont_html 	= user_cont_html = '';
		
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
					var leg_details 		= (typeof(response.job_quote_user_details) != "undefined") 	? response.job_quote_user_details 			: {};
					//console.log(leg_details);
						
					var job_det 			= (typeof(response.job_details) != "undefined") 		? response.job_details 				: {};
					var job_cur_status 		= (typeof(job_det.job_status) != "undefined") 		? parseInt(job_det.job_status) 		: 0;
					var user_det 			= (typeof(response.job_user_details) != "undefined") 	? response.job_user_details 			: {};
					var quote_leg_submited 	= (typeof(response.quote_leg_submited) != "undefined") ? parseInt(response.quote_leg_submited) : 0;
					var added_class 		= (quote_leg_submited == 1)	? 'style="display:none"' 			: '';
						
					//console.log();
					is_job_owner			= (job_det.user_id == session_user_id) ? 1 : 0;
						
					var added_class_deny 	= (quote_leg_submited == 1 && job_det.job_status != 2)	? '' : 'style="display:none"';
					//If this job is approved then no need to show the quote or leg button.
					//console.log('arijit: '+job_cur_status);
					if (job_cur_status == 2) added_class = 'style="display:none"';
						
					//console.log('ql: '+quote_leg_submited+' jo: '+is_job_owner);
					var msg_btn_style		= 'style="display:none"';
					if (job_cur_status == 2) { if (quote_leg_submited == 1 || is_job_owner == 1) msg_btn_style = ''; }
					else msg_btn_style = '';
						
					var activity_btn = '';
					if (job_cur_status == 2 && quote_leg_submited == 1) activity_btn = '<button type="button" id="accept-activity-btn" onclick="open_activity_sec(\'activity-button\', \'activity_section\')" class="submit-leg" data-role="none">View Activity</button>';
						
					var user_id 			= (typeof(user_det.id) != "undefined") 			? user_det.id 				: '';
					var user_name 			= (typeof(user_det.name) != "undefined") 		? user_det.name 			: '';
					var user_image 		= (typeof(user_det.user_image) != "undefined") 	? user_det.user_image 		: '';
					user_image 			= (user_image != '') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+user_image : main_base_url+'assets/site/images/user-image.png';
						
					//console.log('arijit: '+user_image);
						
					if (user_name != '') { $("#job-user-name").html(user_name); }
					if (user_image != '') { $("#job-user-image").attr('src', user_image); }
								  
					var img_sec_link 		= (job_det.image != '') ? '<a href="javascript:void(0)" onclick="open_popup_image(\'myPopup\', \''+main_base_url+'assets/uploads/job_images/'+job_det.image+'\')"><img class="popphoto" style="width: 100px" src="'+main_base_url+'assets/uploads/job_images/thumb/'+job_det.image+'" alt=""></a>' : '';
					var size_details 	= size_details_width = size_details_height = size_details_length = '';
					if (typeof(job_det.size.width) != "undefined") size_details_width 	= job_det.size.width;
					if (typeof(job_det.size.width) != "undefined") size_details_height 	= job_det.size.height;
					if (typeof(job_det.size.width) != "undefined") size_details_length 	= job_det.size.depth;
						
					if ( job_det.size_details == 'Enter Dimensions') size_details = '('+size_details_width+' x '+size_details_height+' x '+size_details_length+' mt)';
						
						
					marker_cont_html = 
							'<div class="terms" id="middle_content">'
								+'<div class="terms-scroll custom-scrollbar">'
									+'<div class="terms-content">'
										+'<p class="big-text">'
											+'<span class="bix-text">&nbsp;</span>'+img_sec_link+' <br/>'
											+'<span class="bix-text">Description: </span>'+job_det.description+' <br/>'
											+'<span>Pickup Address: </span>'+job_det.pick_up_addr+' <br/>'
											+'<span>Delivery Address: </span>'+job_det.drop_addr+' <br/>'
											+'<span>Distance: </span>'+job_det.distance+' miles<br/>'
											+'<span>Deliver By: </span>'+job_det.deliver_method+' ('+job_det.formated_date+') <br/>'
											+'<span>Cargo Value: </span>'+job_det.cargo_value+' <br/>'
											+'<span>Size: </span>'+job_det.size_details+' '+size_details+'<br/>'
											+'<span>Type: </span>'+job_det.type_details+' <br/>'
											+'<span>Special: </span>'+job_det.special_details+' <br/>'
											+'<span>Weight: </span>'+job_det.weight+' lbs<br/>'
											+'<span>Need Guaranteed: </span>'+job_det.is_gurrented+' <br/>'
											+'<span>Need Insurance: </span>'+job_det.is_insured+' <br/>'
											
										+'</p>'
										+'<div id="myPopup"></div>'
									+'</div>'
								+'</div>'
							+'</div>'
							+'<div class="popup-btns">'
								+'<input type="button" onclick="send_pop_msg()" '+msg_btn_style+' value="Send Message" class="submit-leg" data-role="none" />'
								+'<input type="button" onclick="submit_job_leg()" '+added_class+' value="Submit Leg" class="submit-leg" data-role="none" />'
								+'<input type="button" onclick="submit_job_quote()" '+added_class+' value="Submit Quote" class="submit-leg" data-role="none" />'
								
								+'<input type="button" onclick="discard_job_quote(\''+job_det.id+'\', \''+leg_details.id+'\', \''+session_user_id+'\')" '+added_class_deny+' value="Delete Quote" class="submit-leg" data-role="none" />'
								+activity_btn
							+'</div>'
						
					$("#main_cont").html(marker_cont_html);
					$("#now_show").val('main_cont');
					$(".popup-form").hide();
					$("#main_cont").show();
						
					$("#current_job_id").val(job_det.id);
						
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
	
	//Function to open the job options popup
	function close_det_popup(args) {
		$("#user_img").show();
		job_det_actual = '';
		$("#now_show").val('main_cont');
		$(".popup-form").hide();
		$("#main_cont").show();
		$('#popup_cont').toggleClass('info-popup-active');
		$("#current_job_id").val('');
		$(".select-credit").show();
		$(".terms-anc").show();
		$(".agree-terms").show();
		$(".popup-accpt-bottom-btns").show();
	}

	function submit_msg(args) {
		
		var job_id 	= $("#msg_job_id").val();
		var user_id 	= $("#msg_user_id").val();
		var message 	= $("#job_msg_txt").val();
		
		if (message.search(/\S/) != -1) {
			$("#job_msg_txt-error").hide();
			
			var postData 	= 'job_id='+job_id+'&user_id='+user_id+'&message='+message;
			var formURL 	= main_base_url+'Jobs_controllers/submit_job_mesage';
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
						var send_message 	= (typeof(response.message) != "undefined") 		? response.message 				: {};
						var msg_id 		= (typeof(response.insert_id) != "undefined") 	? response.insert_id 			: {};
						var msg_time 		= (typeof(response.post_time) != "undefined") 	? response.post_time 			: {};
						var user_image 	= (typeof(response.user_image) != "undefined") 	? response.user_image 			: '';
						
						var msg_usr_img  	= (user_image != '') 	? main_base_url+'thumb.php?height=45&width=45&type=aspectratio&img='+main_base_url+'assets/uploads/user_images/thumb/'+user_image	: main_base_url+'assets/site/images/user-image.png';
						msg_cont_class		= '';
						
						var msg_html = '<div class="chat-each-content '+msg_cont_class+'">'
										+'<figure>'
											+'<img src="'+msg_usr_img+'" alt="chat-img" />'
										+'</figure>'
										+'<p>'+string_escape(send_message)+'</p>'
										+'<span class="chat-time">'+string_escape(msg_time)+'</span>'
									+'</div>'
						
						$("#all_message_div").find('.mCSB_container').append(msg_html);
						$("#all_message_div").mCustomScrollbar("scrollTo","bottom");
						$("#job_msg_txt").val('');
					}
					else if (parseInt(data == 0)) {
						$('#job_msg_txt-error').html('Failed to send message. Please try again.');
						$("#job_msg_txt-error").show();
					}
					else if (parseInt(data == 1)) {
						$('#job_msg_txt-error').html('Error occured. Please try again.');
						$("#job_msg_txt-error").show();
					}
					else if (parseInt(data == 2)) {
						$('#job_msg_txt-error').html('Message can not be blank. Please try again.');
						$("#job_msg_txt-error").show();
					}
					else{
						$('#job_msg_txt-error').html('Error occured. Please try again.');
						$("#job_msg_txt-error").show();
					}
				}
			});
		}
		else{
			$('#job_msg_txt-error').html('Please enter message.');
			$("#job_msg_txt-error").show();
		}
	}
	
	//Function to open the job message options popup
	function send_pop_msg(args, is_ond) {
		console.log('arijit');
			
		$("#user_img").show();	
		$(".popup-form").hide();
		$('#loading_content').show();
			
		if (is_ond == 1)		var postData 	= 'job_id='+args+'&user_id='+session_user_id;
		else if (is_ond == 2)	var postData 	= 'job_id='+args+'&user_id='+session_user_id;
		else					var postData 	= 'job_id='+job_det_actual.id+'&user_id='+session_user_id;
			
		//var postData 		= 'job_id='+job_det_actual.id+'&user_id='+session_user_id;
		var formURL 			= main_base_url+'Jobs_controllers/job_messages_details_ajax';
		var marker_cont_html 	= user_cont_html = '';
			
		$.ajax({
				
			url : 	formURL,
			type: 	"POST",
			data : 	postData,
			success:function(data, textStatus, jqXHR) 
			{
				var response 	= jQuery.parseJSON(data);
					
				if(typeof response =='object')
				{
					var job_det 			= (typeof(response.job_details) != "undefined") 		? response.job_details 				: {};
					var job_id 	  		= (typeof(job_det._id) != "undefined") 				? job_det._id						: '';
					var job_cur_status 		= (typeof(job_det.job_status) != "undefined") 		? parseInt(job_det.job_status) 		: 0;
					var job_user_det 		= (typeof(job_det.user_details) != "undefined") 		? job_det.user_details 				: {};
					var job_messages 		= (typeof(response.job_messages) != "undefined") 		? response.job_messages 				: {};
					var msg_cont_class		= '';
						
					console.log('arijit: '+job_cur_status);
						
					var job_user_id 		= (typeof(job_user_det.id) != "undefined") 			? job_user_det.id 				: '';
					var job_user_name 		= (typeof(job_user_det.name) != "undefined") 		? job_user_det.name 			: '';
					var job_user_image 		= (typeof(job_user_det.user_image) != "undefined") 	? job_user_det.user_image 		: '';
					job_user_image 		= (job_user_image != '') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+job_user_image : main_base_url+'assets/site/images/user-image.png';
						
					//console.log('arijit: '+user_image);
						
					if (job_user_name != '')  { $("#job-user-name").html(job_user_name); }
					if (job_user_image != '') { $("#job-user-image").attr('src', job_user_image); }
						
					marker_cont_html = '<div class="chat-content custom-scrollbar" id="all_message_div">';
						
					if (job_messages.length > 0) {
						for(i=0; i<job_messages.length; i++)
						{
							var msg_cont 	= job_messages[i];
							msg_cont_class = ((i%2) != 0) ? 'chat-content-me' : '';
								
							var message_cont = (typeof(msg_cont.message.message) != "undefined") 	? msg_cont.message.message	: '';
							var msg_usr_img  = (typeof(msg_cont.user_details.profile_image) != "undefined") 	? main_base_url+'thumb.php?height=45&width=45&type=aspectratio&img='+main_base_url+'assets/uploads/user_images/thumb/'+msg_cont.user_details.profile_image	: main_base_url+'assets/site/images/user-image.png';
							var msg_time 	  = (typeof(msg_cont.message.formated_time) != "undefined") 	? msg_cont.message.formated_time	: '';
							
							marker_cont_html = marker_cont_html +'<div class="chat-each-content '+msg_cont_class+'">'
												+'<figure>'
													+'<img src="'+msg_usr_img+'" alt="chat-img" />'
												+'</figure>'
												+'<p>'+message_cont+'</p>'
												+'<span class="chat-time">'+msg_time+'</span>'
											+'</div>'
						}
					}
						
					marker_cont_html = marker_cont_html +'</div>'
								+'<div class="chat-input">'
									+'<form name="send_message" id="send_message" action="" data-ajax="false" method="post">'
										+'<input type="hidden" name="msg_job_id" id="msg_job_id" value="'+job_id+'" />'
										+'<input type="hidden" name="msg_user_id" id="msg_user_id" value="'+session_user_id+'" />'
										+'<textarea name="job_msg_txt" id="job_msg_txt" data-role="none" placeholder="Write a message" class="chat-textbox"></textarea>'
										+'<label id="job_msg_txt-error" class="error" for="job_msg_txt"></label>'
										+'<button type="button" onclick="submit_msg(\'send_message\')" data-role="none" class="chat-btn"><img src="'+main_base_url+'assets/site/images/chat-submit-arrow.png" alt="chat-submit-arrow" /></button>'
									+'</form>'
								+'</div>'
						
					$("#msg_cont").html(marker_cont_html);
					$("#now_show").val('msg_cont');
					$(".popup-form").hide();
					$("#msg_cont").show();
						
					$(".custom-scrollbar").mCustomScrollbar({
						scrollButtons:{ enable:true }
					}).mCustomScrollbar("scrollTo","bottom");
				}
				else if (parseInt(data) == 0) {
					$(".popup-form").hide();
					$("#error_cont").show();
					$("#error_cont").html('<label class="job-error">We are unable to get the all messages of this job. Please try again.</label>');
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
			
		if ($("#popup_cont").hasClass("info-popup-active") == false) 
			$('#popup_cont').toggleClass('info-popup-active');
			
	}
	//Function to open the job message options popup
	function submit_job_leg() {
		$("#user_img").show();
		$(".popup-form").hide();
		$("#leg_cont").show();
		
		$(".select-credit").show();
		$(".terms-anc").show();
		$(".agree-terms").show();
		$(".popup-accpt-bottom-btns").show();
		
		console.log(job_det_actual);
		
		var pickup_addr= job_det_actual.pickup_address.address;
		var drop_addr 	= job_det_actual.drop_address.address;
		
		var all_legs 	= (typeof(job_det_actual.legs_arr) != "undefined") ? job_det_actual.legs_arr : [];
		var destination_html = '<option>Select</option><option value=\'{"address": "'+job_det_actual.pickup_address.address+'", "lat": "'+job_det_actual.pickup_address.lat_str+'", "long" : "'+job_det_actual.pickup_address.long_str+'"}\'>'+pickup_addr+'</option>';
		
		var drop_html 		= '<option>Select</option>';
		
		if (all_legs.length > 0) {
			for (var al = 0; al < all_legs.length; al++){
				var  cur_det 	= all_legs[al];
				if (pickup_addr != cur_det.pick_up.address) {
					destination_html = destination_html + '<option value=\'{"address": "'+cur_det.pick_up.address+'", "lat": "'+cur_det.pick_up.lat_str+'", "long" : "'+cur_det.pick_up.long_str+'"}\'>'+cur_det.pick_up.address+'</option>';
					
					drop_html = drop_html + '<option value=\'{"address": "'+cur_det.pick_up.address+'", "lat": "'+cur_det.pick_up.lat_str+'", "long" : "'+cur_det.pick_up.long_str+'"}\'>'+cur_det.pick_up.address+'</option>';
				}
					
				if (pickup_addr != cur_det.drop_point.address) {
					
					destination_html = destination_html + '<option value=\'{"address": "'+cur_det.drop_point.address+'", "lat": "'+cur_det.drop_point.lat_str+'", "long" : "'+cur_det.drop_point.long_str+'"}\'>'+cur_det.drop_point.address+'</option>';
					
					drop_html = drop_html + '<option value=\'{"address": "'+cur_det.drop_point.address+'", "lat": "'+cur_det.drop_point.lat_str+'", "long" : "'+cur_det.drop_point.long_str+'"}\'>'+cur_det.drop_point.address+'</option>';
				}
			}
		}
		
		drop_html = drop_html + '<option value=\'{"address": "'+job_det_actual.drop_address.address+'", "lat": "'+job_det_actual.drop_address.lat_str+'", "long" : "'+job_det_actual.drop_address.long_str+'"}\'>'+drop_addr+'</option>';
		
		$('#pick_addr_id').html(destination_html);
		$('#drop_addr_id').html(drop_html);
		
		$("#pick_addr_id").selectmenu("refresh");
		$("#drop_addr_id").selectmenu("refresh");
		
		$(".date-picker").datepicker({minDate : 'today'});
		$(".dropdownA").datepicker({minDate : 'today'});
	}
	
	function submit_job_quote() {
		$("#user_img").show();
		$(".popup-form").hide();
		$("#quote_cont").show();
		
		$(".select-credit").show();
		$(".terms-anc").show();
		$(".agree-terms").show();
		$(".popup-accpt-bottom-btns").show();
	}
	
	function open_popup_customer(args, is_ond){
			
		$(".popup-form").hide();
		$('#loading_content').show();
		$(".popup-accpt-bottom-btns").show();
		$(".select-credit").show();
		$(".terms-anc").show();
		$(".agree-terms").show();
		$(".popup-accpt-bottom-btns").show();
		$("#donext_btn").show();
		$("#accept-button-btn").html('Accept');
		$("#accept-button-btn").removeAttr('disabled');
			
		$("#pickup_date").html('');
		$("#drop_date").html('');
		$("#job_quote_lists").html('');
		$("#total_job_price").html('$0.00');
		$("#to_be_pay").val('0.00');
		$("#extra_job_price").html('$0.00');
		$("#current_quote_id").val('');
		$("#current_job_id").val('');
			
		console.log('a1: '+args);
			
		if (is_ond == 1)		var postData 	= 'job_id='+args+'&user_id='+session_user_id;
		else if (is_ond == 2)	var postData 	= 'job_id='+args+'&user_id='+session_user_id;
		else					var postData 	= 'job_id='+job_det_actual.id+'&user_id='+session_user_id;
		
		var formURL 			= base_url+'job_details_quote_leg_ajax';
		var marker_cont_html 	= user_cont_html = '';
			
		console.log('url: '+formURL);	
			
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
					var  exist_card_valid	= (typeof(response.exist_card_valid) 	!= "undefined") ? response.exist_card_valid 		: '0',
						platform_fee		= (typeof(response.platform_fee) 		!= "undefined") ? response.platform_fee 		: '0',
						job_det 			= (typeof(response.job_details) 		!= "undefined") ? response.job_details 			: {},
						job_quote_leg_det 	= (typeof(response.job_quote_leg_det) 	!= "undefined") ? response.job_quote_leg_det 	: {},
						is_ondemand		= (typeof(job_det.is_ondemand) 		!= "undefined") ? parseInt(job_det.is_ondemand) 	: '0',
						user_det 			= (typeof(response.user_details) 		!= "undefined") ? response.user_details 		: {},
						user_id 			= (typeof(user_det.id) 				!= "undefined") ? user_det.id 				: '',
						user_name 		= (typeof(user_det.name) 			!= "undefined") ? user_det.name 				: '',
						user_image 		= (typeof(user_det.user_image) 		!= "undefined") ? user_det.user_image 			: '',
						job_quotes 		= job_quote_leg_det.job_quote,
						job_date_range 	= job_quote_leg_det.job_date_range,
						job_prices_extra 	= (typeof(job_quote_leg_det.job_prices_extra) != "undefined") ? job_quote_leg_det.job_prices_extra 	: '',
						job_total_prices 	= (typeof(job_quote_leg_det.job_total_prices) != "undefined") ? job_quote_leg_det.job_total_prices 	: '';
							
					var	all_job_quotes_html = '',
						all_job_legs_html 	= '',
						all_quote_ids 		= [];
						
					var delivary_type   	= job_det.deliver_method;
					var delivary_type_det 	= '';
						
					console.log('arijit1: '+job_quotes.length);
						
					if (job_quotes.length > 0)
					{
						$("#total_qoutes").html('Quotes ('+job_quotes.length+')');
						$("#total_quote_legs").val(job_quotes.length);
							
						for (var i = 0; i < job_quotes.length; i++)
						{
							var job_quote_det 			= job_quotes[i];
							var leg_total_price 		= job_total_prices[i];
							var leg_extra_price 		= job_prices_extra[i];
							var leg_date_range 			= job_date_range[i];
							var all_leg_ids 			= [];
							var job_quote_det_length 	= job_quote_det.length;
							var li_class 				= (i == 0) ? '' : 'hide';
								
							if (typeof(job_quote_det_length) 		== "undefined")
							{
								var quote_user_details 	= job_quote_det.user_details;
									
								var quote_user_image 	= (quote_user_details.profile_image != '') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+main_base_url+'assets/uploads/user_images/thumb/'+quote_user_details.profile_image :  main_base_url+'assets/site/images/user-image.png';
								console.log('arijit: '+quote_user_details.user_rating);
								var quote_user_rating 	= (typeof(quote_user_details.user_rating) != "undefined") ? generate_raing_images(quote_user_details.user_rating) : '';
									
								all_quote_ids.push(job_quote_det.id);
									
								all_job_quotes_html		= all_job_quotes_html + '<li id="quote_leg_id_'+i+'" class="job_li '+li_class+'">'
													
													+'<input type="hidden" name="leg_total_price_'+i+'" id="leg_total_price_'+i+'" 	value="'+leg_total_price+'" />'
													+'<input type="hidden" name="leg_extra_price_'+i+'" id="leg_extra_price_'+i+'" 	value="'+leg_extra_price+'" />'
													+'<input type="hidden" name="leg_date_pick_'+i+'"   id="leg_date_pick_'+i+'" 	value="'+leg_date_range.pick_up+'" />'
													+'<input type="hidden" name="leg_date_frop_'+i+'"   id="leg_date_frop_'+i+'" 	value="'+leg_date_range.drop+'" />'
													+'<div class="user-list-left">'
														+'<figure><img src="'+quote_user_image+'" alt="user-list-img" /></figure>'
														+'<h3><a href="javascript:void(0)">'+quote_user_details.first_name+' '+quote_user_details.last_name+'</a></h3>'
														+'<div class="user-rating">'
															+quote_user_rating
														+'</div>'
													+'</div>'
													+'<div class="user-list-right">$'+job_quote_det.job_price+'</div>'
												+'</li>';
							}
							else
							{
								all_job_quotes_html	= all_job_quotes_html + '<li id="quote_leg_id_'+i+'" class="job_li '+li_class+'">'
													+'<input type="hidden" name="leg_total_price_'+i+'" id="leg_total_price_'+i+'" 	value="'+leg_total_price+'" />'
													+'<input type="hidden" name="leg_extra_price_'+i+'" id="leg_extra_price_'+i+'" 	value="'+leg_extra_price+'" />'
													+'<input type="hidden" name="leg_date_pick_'+i+'"   id="leg_date_pick_'+i+'" 	value="'+leg_date_range.pick_up+'" />'
													+'<input type="hidden" name="leg_date_frop_'+i+'"   id="leg_date_frop_'+i+'" 	value="'+leg_date_range.drop+'" />'
													
													+'<ul>';
									
								for (var i1 = 0; i1 < job_quote_det.length; i1++)
								{
									var job_leg_det 		= job_quote_det[i1];
									var leg_user_details 	= job_leg_det.user_details;
										
									var leg_user_image 		= (leg_user_details.profile_image != '') ? main_base_url+'thumb.php?height=70&width=70&type=aspectratio&img='+main_base_url+'assets/uploads/user_images/thumb/'+leg_user_details.profile_image : main_base_url+'assets/site/images/user-image.png';
									console.log('arijit: '+leg_user_details.user_rating);
									var leg_user_rating 	= (typeof(leg_user_details.user_rating) != "undefined") ? generate_raing_images(leg_user_details.user_rating) : '';
										
									all_leg_ids.push(job_leg_det.id);
										
									all_job_quotes_html	= all_job_quotes_html +
														'<li>'
															+'<div class="user-list-left">'
																+'<figure><img src="'+leg_user_image+'" alt="user-list-img" /></figure>'
																+'<h3><a href="javascript:void(0)">'+leg_user_details.first_name+' '+leg_user_details.last_name+'</a></h3>'
																+'<div class="user-rating">'
																	+leg_user_rating
																+'</div>'
															+'</div>'
															+'<div class="user-list-right">$'+job_leg_det.job_price+'</div>'
														+'</li>';
								}
									
								all_job_quotes_html	= all_job_quotes_html + '</ul>';
								all_quote_ids.push(all_leg_ids.toString());
							}
								
							if (li_class == '')
							{
								var current_show_li 	= parseInt($("#current_show_li").val());
								var current_total_price 	= leg_total_price;
								var current_extra_price 	= leg_extra_price;
								var pick_date 			= (typeof(leg_date_range.pick_up)  != "undefined") ? leg_date_range.pick_up 	: '';
								var drop_date 			= (typeof(leg_date_range.drop)  	!= "undefined") ? leg_date_range.drop 		: '';
							}
						}
							
						$("#donext_btn").html('Next Quote (1/'+job_quotes.length+')');
							
						if (delivary_type == 'flexible') 		delivary_type_det 	= 'Flexible';
						else if (delivary_type == 'deliver_by') delivary_type_det 	= 'Deliver By';
						else if (delivary_type == 'send_by') 	delivary_type_det 	= 'Send By';
						else if (delivary_type == 'urgent') 	delivary_type_det 	= 'Urgent';
						else 							delivary_type_det 	= 'Flexible';
							
						if (is_ondemand == 1) 				delivary_type_det	= 'Immediate Pickup';
							
						var pick_date_new	= (typeof(job_det.formated_date)  != "undefined") ? job_det.formated_date 	: '';
						$("#pickup_date").html(delivary_type_det+': '+pick_date_new);
						$("#drop_date").hide();
							
						$("#job_quote_lists").html(all_job_quotes_html);
							
						$("#total_job_price").html('$'+current_total_price);
						$("#to_be_pay").val(current_total_price);
						$("#to_be_refund").val(current_extra_price);
							
						$("#extra_job_price").html('$'+current_extra_price);
						$("#current_quote_id").val(all_quote_ids[current_show_li]);
						$("#current_job_id").val(job_det.id);
							
						$("#user_img").hide();
						$(".popup-form").hide();
						$("#job_quote_list_cont").show();
						$("#now_show").val('job_quote_list_cont');
					}
					else
					{
						$("#error_cont").html('<div class="terms"><div class="terms-scroll"><div class="terms-content"><p style="color: #f00 !important; font-size: 15px !important">No quotes or legs has been submitted yet.</p></div></div></div>');
							
						$("#user_img").hide();
						$(".popup-form").hide();
						$("#error_cont").show();
						console.log('arijit');
					}
						
					//console.log('a: '+job_det.job_status);
					if (parseInt(job_det.job_status) == 2)
					{
						//console.log('arijit');
							
						$(".select-credit").hide();
						$(".terms-anc").hide();
						$(".agree-terms").hide();
						$(".popup-accpt-bottom-btns").hide();
							
						$("#accept-button-btn").hide();
						$("#accept-button-btn").html('Accepted');
						$("#accept-button-btn").attr('disabled', 'disabled');
							
						if (is_ond == 1) 		$("#send-sms-btn").attr('onclick', 'send_pop_msg(\''+args+'\', \'2\')');
						else if (is_ond == 2) 	$("#send-sms-btn").attr('onclick', 'send_pop_msg(\''+args+'\', \'2\')');
						else 				$("#send-sms-btn").attr('onclick', 'send_pop_msg(\''+args+'\', \'2\')');
							
						$("#send-sms-btn").show();	
							
						$("#accept-activity-btn").show();
					}
						
					console.log('arijit exist card id: '+exist_card_valid);	
						
						
						
					$("#refundable_dep").html('Platform Fee ('+platform_fee+'%)');	
					$("#user_has_acard").val(exist_card_valid);	
						
						
					if(parseInt(exist_card_valid) == 0)
					{
						//$("#card_not_avil_msg").html('Existing card not valid anymore. Please add a new one.');
						$("#card_not_avil_msg").html('');
							
						$("#change_pay_card_type").hide();	
							
						$("#cardholdername_ex").hide();
						$("#cardholdername").removeAttr('readonly');
						$("#cardholdername").show();
							
						$("#cardnumber_ex").hide();
						$("#cardnumber").removeAttr('readonly');
						$("#cardnumber").show();
							
						$("#show_exp_years").hide();	
						$("#new_exp_years").show();	
							
						$("#cvv_ex").hide();
						$("#cvv").removeAttr('readonly');
						$("#cvv").show();		
					}
					else	$("#card_not_avil_msg").html('');
						
					if (is_ondemand == 1)
					{
						$("#is_ondemand").val('1');
							
						if (job_quotes.length == 0)
						{
							$(".popup-form").hide();
							$("#error_cont").show();
							$("#now_show").val('error_cont');
						}
							
						$("#payment_type > option").each(function() {
							console.log(this.text + ' ' + this.value);
							if (this.value != 'credit_card')	$(this).attr('disabled', 'disabled');
						});
							
						$(".popup-accpt-bottom-btns").hide();
					}
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
					
					
				console.log('cu: '+card_user_name+' cl: '+card_last_digits+' cy: '+exp_year+' cm: '+exp_month+' cv: '+cvv_code);
					
				$("#cardholdername_ex").html(card_user_name);
				$("#cardnumber_ex").html(card_last_digits);
				$("#expirymonth_ex").html(exp_month);
				$("#expyear_ex").html(exp_year);
				$("#cvv_ex").html(cvv_code);
					
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
	
	function generate_raing_images(rating) {
		var res = rating.split(".");
		
		var  full_star 	= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="21.219" height="19.438" viewBox="0 0 21.219 19.438">'
					+'<path d="M16.503,19.369 C16.448,19.406 16.385,19.424 16.322,19.424 C16.216,19.424 16.113,19.373 16.050,19.279 C15.950,19.129 15.990,18.926 16.140,18.827 C18.919,16.977 20.577,13.889 20.577,10.565 C20.577,5.091 16.109,0.638 10.617,0.638 C5.125,0.638 0.656,5.091 0.656,10.565 C0.656,13.889 2.315,16.978 5.094,18.827 C5.244,18.926 5.285,19.129 5.184,19.279 C5.084,19.428 4.880,19.469 4.731,19.369 C1.770,17.399 0.002,14.107 0.002,10.565 C0.002,4.732 4.764,-0.014 10.617,-0.014 C16.470,-0.014 21.232,4.732 21.232,10.565 C21.232,14.107 19.464,17.399 16.503,19.369 ZM3.364,8.384 L8.863,8.326 L10.617,3.132 L12.371,8.326 L17.869,8.384 L13.455,11.651 L15.099,16.881 L10.617,13.706 L6.134,16.881 L7.779,11.651 L3.364,8.384 Z" class="cls-1"/>'
				+'</svg>',
			half_star = '<img src="'+main_base_url+'assets/site/images/half-star.png" alt="" />';
		
		var rating_first 	= (typeof(res[0]) != "undefined") ? parseInt(res[0]) 	: 0;
		var rating_second 	= (typeof(res[1]) != "undefined") ? parseInt(res[1]) 	: 0;
		
		var rating_html = '';
		
		for(i = 0; i < rating_first; i++)
			rating_html = rating_html + full_star;
			
		if (rating_second >= 5)
			rating_html = rating_html + half_star;
		
		return rating_html;
	}
	
	function show_next_leg(args)
	{
		var existing_leg_id = parseInt($("#current_show_li").val());
		var total_legs 	= parseInt($("#total_quote_legs").val());
		
		current_show_li 	= existing_leg_id + 1;
		
		if (total_legs == current_show_li) 	current_show_li = 0;
		
		$(".job_li").hide();
		$("#quote_leg_id_"+current_show_li).show();
		
		var current_total_price 	= $("#leg_total_price_"+current_show_li).val();
		var current_extra_price 	= $("#leg_extra_price_"+current_show_li).val();
		var pick_date 			= $("#leg_date_pick_"+current_show_li).val();
		var drop_date 			= $("#leg_date_frop_"+current_show_li).val();
		
		$("#donext_btn").html('Next Quote ('+(current_show_li+1)+'/'+job_quotes.length+')');
		
		//$("#pickup_date").html(pick_date);
		//$("#drop_date").html(drop_date);
		
		$("#total_job_price").html('$'+current_total_price);
		$("#to_be_pay").val(current_total_price);
		$("#to_be_refund").val(current_extra_price);
		$("#extra_job_price").html('$'+current_extra_price);
		
		$("#current_quote_id").val(all_quote_ids[current_show_li]);
		$("#current_show_li").val(current_show_li);
	}
	
	function open_activity_sec(args) {
		
		var job_id = $('#current_job_id').val();
		//$("#job_id").val(job_id);
		//$("#activity_job").submit();
		
		window.location = base_url+'job-activities/'+job_id;
	}
	
	$(document).ready(function(){
		//$('#submit-quote').on('click', function() {
			$("#quote-form-error").hide();
			$("#quote-form-error").html('');
			$("#quote_job_form").validate({
				rules: {
					job_price: 	{
								required: true,
								positivenumber: true
							},
					term_agree: 	{ required: true, }
				},
				messages: {
					job_price:    {
								required: 'Please enter quote price.',
								positivenumber: "Please enter positive value."
							},
					term_agree:   {
								required: 'Please agree to our terms and conditions.',
							}
				},
				submitHandler: function(form) {
					
					$('#submit-quote').attr('disabled', 'disabled');
					$("#quote_loading").show();
					
					//console.log('form submited');
					var 	user_id 		= session_user_id,
						job_id 		= job_det_actual.id,
						start_location	= {address : job_det_actual.pickup_address.address, lat : job_det_actual.pickup_address.lat, long : job_det_actual.pickup_address.long},
						end_location	= {address : job_det_actual.drop_address.address, lat : job_det_actual.drop_address.lat, long : job_det_actual.drop_address.long};
					
					$("#quote_user_id").val(user_id); 			$("#quote_job_id").val(job_id);
					
					$('#submit-quote').attr('disabled', 'disabled');
					var postData 	= $(form).serializeArray();;
					var formURL 	= main_base_url+'Jobs_controllers/submit_quote';
					
					$.ajax(
					{
						url : 	formURL,
						type: 	"POST",
						data : 	postData,
						success:function(data, textStatus, jqXHR) 
						{
							$('#submit-quote').removeAttr('disabled');
							
							if (parseInt(data) == 0){
								$('#quote-form-error').html('Failed to submit quote. Please try again.');
								$("#quote-form-error").show();
								
								$("#quote_loading").hide();
							}
							else if (parseInt(data) == 1)
							{
								close_det_popup();
								$("#search_filter").click();
							}
							else if (parseInt(data) == 2){
								$('#quote-form-error').html('You have already quoted or put leg for this job.');
								$("#quote-form-error").show();
								
								$('#submit-quote').removeAttr('disabled');
								$("#quote_loading").hide();
							}
							else if (parseInt(data) == 3){
								$('#quote-form-error').html('Failed to submit quote. Please try again.');
								$("#quote-form-error").show();
								
								$('#submit-quote').removeAttr('disabled');
								$("#quote_loading").hide();
							}
						},
						error: function(jqXHR, textStatus, errorThrown) 
						{
							$('#submit-quote').removeAttr('disabled');
							$('#quote-form-error').html('Failed to submit quote. Please try again.');
							$("#quote-form-error").show();
							
							$('#submit-quote').removeAttr('disabled');
							$("#quote_loading").hide();
						}
					});
				}
			});
				
			$("#leg_job_form").validate({
				rules: {
					leg_pickup_addr: { required: true },
					leg_start: 	{ required: true },
					leg_drop_addr: { required: true },
					leg_end: 		{ required: true },
					job_leg_price: {
								required: true,
								positivenumber: true
							},
					leg_term_agree:{ required: true }
				},
				messages: {
					leg_pickup_addr: { required: 'Please enter pickup address.' },
					leg_drop_addr: { required: 'Please enter drop address.' },
					job_leg_price:    {
								required: 'Please enter price.',
								positivenumber: "Please enter positive value."
							},
					leg_term_agree:   {
								required: 'Please agree to our terms and conditions.',
							}
				},
				submitHandler: function(form) {
						
					$('#submit-leg').attr('disabled', 'disabled');
					$("#leg_loading").show();
						
					//console.log('form submited');
					var 	user_id 		= session_user_id,
						job_id 		= job_det_actual.id,
						start_location	= {address : job_det_actual.pickup_address.address, lat : job_det_actual.pickup_address.lat, long : job_det_actual.pickup_address.long},
						end_location	= {address : job_det_actual.drop_address.address, lat : job_det_actual.drop_address.lat, long : job_det_actual.drop_address.long};
						
					$("#leg_user_id").val(user_id); 			$("#leg_job_id").val(job_id);
					//$("#start_location").val(start_location.toString()); 	$("#end_location").val(end_location.toString());
						
						
					var postData 	= $(form).serializeArray();;
					var formURL 	= main_base_url+'Jobs_controllers/submit_leg';
						
					$.ajax(
					{
						url : 	formURL,
						type: 	"POST",
						data : 	postData,
						success:function(data, textStatus, jqXHR) 
						{
							if (parseInt(data) == 0){
								$('#leg-form-error').html('Failed to submit leg. Please try again.');
								$("#leg-form-error").show();
								
								$('#submit-leg').removeAttr('disabled');
								$("#leg_loading").hide();
							}
							else if (parseInt(data) == 1)
							{
								close_det_popup();
								$("#search_filter").click();
							}
							else if (parseInt(data) == 2){
								$('#leg-form-error').html('You have already quote or put leg for this job.');
								$("#leg-form-error").show();
								
								$('#submit-leg').removeAttr('disabled');
								$("#leg_loading").hide();
							}
							else if (parseInt(data) == 3){
								$('#leg-form-error').html('Failed to submit leg. Please try again.');
								$("#leg-form-error").show();
								
								$('#submit-leg').removeAttr('disabled');
								$("#leg_loading").hide();
							}
						},
						error: function(jqXHR, textStatus, errorThrown) 
						{
							$('#submit-leg').removeAttr('disabled');
							$("#leg_loading").hide();
							
							$('#leg-form-error').html('Failed to submit leg. Please try again.');
							$("#leg-form-error").show();
						}
					});
						
					return false;
				}
			});
				
			$('#payment-form').validate({
				rules: {
					agree: 			{ required: true },
					cardholdername: 	{ required: true },
					cardnumber: 		{ required: true },
					expirymonth: 		{ required: true },
					expyear: 			{ required: true },
					cvv: 			{ required: true }
				},
				messages: {
					agree: 			{ required: 'Please accept terms and conditions.' },
					cardholdername: 	{ required: 'Please enter card holder\'s name.' },
					cardnumber: 		{ required: 'Please enter card number.' },
					expirymonth: 		{ required: 'Please enter expiry date.' },
					expyear: 			{ required: '' },
					cvv:   			{ required: 'Please enter expiry cvv.' }
				},
				submitHandler: function(form) {
						
					//console.log('arijit 1');
					var choosed_pay_option = $("#payment_type").val();
						
					if (choosed_pay_option == 'credit_card')
					{
						$("#payment-card-error").html('');
						$('#submit-pay').attr('disabled', 'disabled');
						$("#pay_loading").show();
							
						var 	card_type 		= '',
							card_valid 		= '',
							expirymonth_val	= parseInt($("#expirymonth").val(), 10),
							expyear_val		= parseInt($("#expyear").val(), 10);
								
						var user_has_acard 		= parseInt($("#user_has_acard").val());
						var current_stripe_id 	= $("#current_stripe_id").val();
						var current_card_id 	= $("#user_stripe_card_id").val();
							
						console.log('arijit: '+user_has_acard);
							
						if (user_has_acard == 1)
						{
							if (current_stripe_id != '' && current_card_id != '')
								form.submit();
							else{
								$("#user_has_acard").val('0');
									
								$("#cardholdername").val(''); $("#cardholdername").removeAttr('readonly');
								$("#cardnumber").val('');	$("#cardnumber").removeAttr('readonly');
								$("#expirymonth").val('');	$("#expirymonth").removeAttr('readonly'); 	$("#expirymonth").attr('style', '');
								$("#expyear").val('');		$("#expyear").removeAttr('readonly'); 		$("#expyear").attr('style', '');
								$("#cvv").val('');			$("#cvv").removeAttr('readonly');
							}
						}
						else{
							$('#cardnumber').validateCreditCard(function(result) {
									
								card_type 	= result.card_type == null ? '' : result.card_type.name;
								card_valid	= result.valid;
								card_valid 	= card_valid.toString();
									
								console.log(card_type+' '+card_valid);
									
								if (card_valid == 'true')
								{
									//console.log('tested');
										
									var year         	= expyear_val,
										currentMonth 	= new Date().getMonth() + 1,
										currentYear  	= new Date().getFullYear();
										
									if (expirymonth_val < 0 || expirymonth_val > 12) {
										$("#expirymonth-error").show(); $("#expirymonth-error").html('Invalid month');
									}
										
									if (year == '') {
										$("#expirymonth-error").show(); $("#expirymonth-error").html('Invalid year');
									}
										
									if ((year > currentYear) || ((year == currentYear) && (expirymonth_val >= currentMonth))) {
											
										console.log('validation successfull');
										var is_ondemand 		= parseInt($("#is_ondemand").val());
											
										console.log('arijit: ' + settings_stripe_public_key + ' sss: ' + stripe_public_key);	
											
										if(is_ondemand == 1)	
											Stripe.setPublishableKey(stripe_public_key);
										else
											Stripe.setPublishableKey(settings_stripe_public_key);
											
										console.log('arijit 2');
											
										Stripe.card.createToken({
											number: 			$('#cardnumber').val(),
											cvc: 			$('#cvv').val(),
											exp_month: 		$('#expirymonth').val(),
											exp_year: 		$('#expyear').val(),
											name: 			$('#cardholdernamel').val(),
										}, stripeResponseHandler);
											
										console.log('arijit 3');
											
									} else {
										//console.log(year+' '+currentYear+' '+expirymonth_val+' '+currentMonth);
										$("#expirymonth-error").html('Card has been expired.'); $("#expirymonth-error").show();
											
										//$("#payment-card-error").html('');
										$('#submit-pay').removeAttr("disabled");
										$("#pay_loading").hide();
									}
								}
								else{
									$("#cardnumber-error").show(); $("#cardnumber-error").html('Invalid card number');
									//$("#payment-card-error").html('');
									$("#payment-card-error").html('');
									$('#submit-pay').removeAttr('disabled');
									$("#pay_loading").hide();
								}
							});
						}
					}
					else{
							
						form.submit();
					}
					
					return false; // submit from callback
				}
			});
		//});
	});
		
	function hide_terms(args) {
			
		if ($('#leg_cont_show').is(":visible") == false) {
			if ($("#"+args).is(":visible") == true) { 
				$("#"+args).hide();
			}
				
			$('#leg_cont_show').show();
		}
	}
		
	function show_terms(args, args1, type) {
			
		if ($("#"+args1).is(":visible") == true) { 
			$("#"+args).show(); $("#"+args1).hide();
		}
		else {
		    $("#"+args).hide(); $("#"+args1).show();
		}
	}
		
	function choosed_pickdrop_det(args, div_id) {
		var addr 	= [];
		try{
			addr = JSON.parse(args);
			//console.log(addr);
			$("#"+div_id+'_div').hide();
			
			$("#"+div_id).val(addr.address);
			$("#"+div_id+'_lat').val(addr.lat);
			$("#"+div_id+'_long').val(addr.long);
			
		}catch(e){
		    //alert(e); //error in the above string(in this case,yes)!
		    $("#"+div_id+'_div').show();
		    $("#"+div_id).val('');
		}
			
		//var addr = jQuery.parseJSON(args);
	}
		
	function open_pay_sec(accept_id, paybtn_id) {
		//console.log('arijit 0');
		$("#cardholdername").val(''); 
		$("#cardnumber").val('');	
		$("#expirymonth").val('');	 	
		$("#expyear").val('');		 		
		$("#cvv").val('');	
			
		var choosed_pay_option = $("#payment_type").val();
			
		if (choosed_pay_option == 'credit_card') {
			$("#"+accept_id).hide();
			$("#"+paybtn_id).show();
		}
		else{
			//console.log('arijit 1');
			if(document.getElementById('agree').checked) {
				//console.log('arijit 2');
				$("#cardholdername").val('test'); 
				$("#cardnumber").val('test');	
				$("#expirymonth").val('1');	 	
				$("#expyear").val('1');		 		
				$("#cvv").val('1');	
					
				$("#agree-error").html('');
				$("#payment-form").submit();
			} else {
				//console.log('arijit 3');
				$("#agree-error").show();
				$("#agree-error").html('Please accept our terms and conditions.');
			}
		}
	}
		
	function show_hide_pay_sec(cur_val, args) {
			
		var extra_p = parseFloat($('#payment_type>option:selected').attr('extra_p'));
		var extra_t = $('#payment_type>option:selected').attr('extra_t');
		var extra_d = $('#payment_type>option:selected').attr('extra_d');
			
		var cur_amount 	= parseFloat($("#to_be_pay").val()); var new_amount = 0;
		if (extra_p > 0) {
			var extra_amount = parseFloat((cur_amount * extra_p) / 100);
				
			if (extra_t == '+') 	new_amount = (cur_amount + extra_amount);
			else if (extra_t == '-') new_amount = (cur_amount - extra_amount);
			else					new_amount = cur_amount;
		}
		else new_amount = cur_amount;
			
		new_amount = new_amount.toFixed(2)
			
		//console.log('arijit: '+extra_p+' '+extra_t+' '+new_amount.toFixed(2));
		$("#total_job_price").html('$'+new_amount);
		//$("#to_be_pay").val(new_amount);
			
		//Clear all data and reasign them
		$("#deduction_amount").val(''); 	$("#extra_amount").val(''); 	$("#extra_days").val('');
		$("#deduction_percent").val(''); 	$("#extra_percent").val('');
			
		if (extra_t == '+')
		{
			$("#extra_amount").val(extra_amount);
			$("#extra_percent").val(extra_p);
		}
			
		if (extra_t == '-')
		{
			$("#deduction_amount").val(extra_amount);
			$("#deduction_percent").val(extra_p);
		}
			
		$("#extra_days").val(extra_d);
			
		$("#"+args).hide();
		$("#accept-button").show();
		if (cur_val == 'payment_section'){ $("#"+args).show(); }
	}
		
	function discard_job_quote(job_id, job_quote_id, user_id) {
		var postData 	= 'job_quote_id='+job_id+'&job_quote_id='+job_quote_id+'&user_id='+user_id;
		var formURL 	= main_base_url+'Jobs_controllers/discard_job_quote';
		
		$.ajax(
		{
			url : 	formURL,
			type: 	"POST",
			data : 	postData,
			success:function(data, textStatus, jqXHR) 
			{
				close_det_popup();
				$("#search_filter").click();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$('#submit-leg').removeAttr('disabled');
				$("#leg_loading").hide();
				
				$('#leg-form-error').html('Failed to submit leg. Please try again.');
				$("#leg-form-error").show();
			}
		});
	}
	
	function decline_legs() {
		
		//var postData 	= $(form).serializeArray();;
		//var formURL 	= base_url+'Jobs_controllers/submit_leg';
		//
		//$.ajax(
		//{
		//	url : 	formURL,
		//	type: 	"POST",
		//	data : 	postData,
		//	success:function(data, textStatus, jqXHR) 
		//	{
		//		
		//	},
		//	error: function(jqXHR, textStatus, errorThrown) 
		//	{
		//		$('#submit-leg').removeAttr('disabled');
		//		$("#leg_loading").hide();
		//		
		//		$('#leg-form-error').html('Failed to submit leg. Please try again.');
		//		$("#leg-form-error").show();
		//	}
		//});
	}
		
	function change_pay_card_det(args) {
		if (args == 1) {
				
			$("#cardholdername_ex").show();			$("#cardnumber_ex").show(); 
			$("#cvv_ex").show();					$("#show_exp_years").show();
				
			$("#cardholdername").hide();				$("#cardnumber").hide();
			$("#new_exp_years").hide();				$("#cvv").hide();
				
			$("#cardholdername").attr('readonly', 'readonly');
			$("#cardnumber").attr('readonly', 'readonly');
			$("#cvv").attr('readonly', 'readonly');
				
			//$("#cardholdername").val(card_user_name); 	$("#cardholdername").attr('readonly', 'readonly');
			//$("#cardnumber").val(card_last_digits);	$("#cardnumber").attr('readonly', 'readonly');
			//$("#expirymonth").val(exp_month);		$("#expirymonth").attr('readonly', 'readonly');
			//$("#expyear").val(exp_year);			$("#expyear").attr('readonly', 'readonly');
			//$("#cvv").val(cvv_code);				$("#cvv").attr('readonly', 'readonly');
					
			//$("#expirymonth").selectmenu("refresh", true);
			//$("#expyear").selectmenu("refresh", true);
				
			$("#user_has_acard").val(1);
		}
		else{
				
			$("#cardholdername_ex").hide();			$("#cardnumber_ex").hide();
			$("#cvv_ex").hide();					$("#show_exp_years").hide();
				
			$("#cardholdername").show();				$("#cardnumber").show();
			$("#new_exp_years").show();				$("#cvv").show();
				
			$("#cardholdername").removeAttr('readonly');	
			$("#cardnumber").removeAttr('readonly');
			$("#cvv").removeAttr('readonly');
				
				
			//$("#cardholdername").removeAttr('readonly'); $("#cardholdername").val(''); 
			//$("#cardnumber").removeAttr('readonly'); 	$("#cardnumber").val('');
				
			//$("#expirymonth").removeAttr('readonly'); 	$("#expirymonth").removeAttr("style"); 
			//$("#expirymonth").selectmenu( "option", "defaults", true );		$("#expirymonth").selectmenu('refresh');	
				
			//$("#expyear").removeAttr('readonly');		$("#expyear").removeAttr('style');
			//$("#expyear").selectmenu( "option", "defaults", true ); 		$("#expyear").selectmenu("refresh"); 		
				
			//$("#cvv").removeAttr('readonly'); 			$("#cvv").val('');			
				
			$("#user_has_acard").val(2);
		}
	}
		