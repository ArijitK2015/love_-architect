	function CustomMarker(latlng, map, args) {
		
		this.latlng = latlng;	
		this.args = args;	
		this.setMap(map);
	}
	
	CustomMarker.prototype = new google.maps.OverlayView();

	CustomMarker.prototype.draw = function() {
		
		var self = this;
		var marker_cont_html = '';
		var div = this.div;
		
		if (!div) {
		
			div = this.div = document.createElement('div');
				
			div.className 			= 'marker';
			div.id 				= self.args.id;
			div.style.position 		= 'absolute';
			div.style.cursor 		= 'pointer';
			div.style.width 		= '20px';
			div.style.height 		= '20px';
			//div.style.background 	= 'blue';
			div.innerHTML 			= self.args.inner_cont;
				
			var marker_type = self.args.marker_type;
				
			all_markers_div.push(div);
				
				
			if (typeof(self.args.marker_id) !== 'undefined') {
				//div.dataset.marker_id = self.args.marker_id;
			}
			
			google.maps.event.addDomListener(div, "click", function(event) {
				if (marker_type == 1) {
					//console.log(self.args.job_details);
					//job_det = jQuery.parseJSON(self.args.job_details);
					job_det 			= job_det_actual = self.args.job_details;
					var job_user_id 	= job_det_actual.user_id;
					
					//console.log(job_det);
					//marker_cont_img  = 	'<div class="user-img"><img src="'+base_url+'assets/site/images/user-img.jpg" alt="user-img" /></div>'
					//				+'<h3>'+job_det.user_name+'</h3>'
					//
					//marker_cont_html = 
					//				'<div class="terms" id="middle_content">'
					//					+'<div class="terms-scroll custom-scrollbar">'
					//						+'<div class="terms-content">'
					//							+'<p class="big-text">'
					//							+job_det.distance+' '+job_det.distance_type+' - '+job_det.drop_address.address+' <br/>'
					//							+'Deliver By: '+job_det.delivery_date+' <br/>'
					//							+job_det.weight+' Tonnes / '+job_det.size_type+' <br/>'
					//							+job_det.containt_type+' <br/>'
					//							+job_det.special+' <br/>'
					//							+'</p>'
					//						+'</div>'
					//					+'</div>'
					//				+'</div>'
					//				+'<div class="popup-btns">'
					//					+'<input type="button" onclick="send_pop_msg()" value="SEND MESSAGE" class="submit-leg" data-role="none" />'
					//					+'<input type="button" onclick="submit_job_leg()" value="SUBMIT LEG" class="submit-leg" data-role="none" />'
					//					+'<input type="button" onclick="submit_job_quote()" value="SUBMIT QUOTE" class="submit-leg" data-role="none" />'
					//				+'</div>'
					//				
					//
					//$("#main_cont").html(marker_cont_html);
					//$("#user_img").html(marker_cont_img);
					//$("#now_show").val('main_cont');
					
					//console.log('arijit: '+job_user_id+' '+session_user_id);
					
					if (job_user_id == session_user_id)
						open_popup_customer(this);
					else
						open_popup(this);
					
					//$(".custom-scrollbar").mCustomScrollbar({
					//	scrollButtons:{
					//	    enable:true
					//	},
					//	alwaysShowScrollbar:2
					//});
				}
				else
					google.maps.event.trigger(self, "click");
			});
			
			var panes = this.getPanes();
			panes.overlayImage.appendChild(div);
		}
		
		var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
		
		if (point) {
			div.style.left = (point.x - 10) + 'px';
			div.style.top = (point.y - 20) + 'px';
		}
	};

	//CustomMarker.prototype.onRemove = function() {
	//	if (this.div) {
	//		this.div.parentNode.removeChild(this.div);
	//		this.div = null;
	//	}	
	//};
		
	CustomMarker.prototype.hide = function() {
		if (this.div) {
			this.div.style.visibility = "hidden";
		}
	}
		
	CustomMarker.prototype.remove = function() {
		if (this.div) {
			$('#'+this.div.id).remove();
			//this.div.style.visibility = "hidden";
		}
	}
		
	CustomMarker.prototype.getPosition = function() {
		return this.latlng;	
	};
	
	CustomMarker.prototype.show = function() {
		if (this.div) {
			this.div.style.visibility = "visible";
		}
	}
	
	CustomMarker.prototype.toggle = function() {
		
		console.log(this.div);
		
		if (this.div) {
			if (this.div.style.visibility == 'hidden') {
				this.show();
			} else {
				this.hide();
			}
		}
	}