
<!-- css links -->
<link rel="stylesheet" href="<?php echo assets_url(); ?>site/css/slick.css" />

<!-- js links -->
<?php
		//Google api Key is important and we are using the key stored in database
		if(isset($settings[0]['google_map_api_key']) && !empty($settings[0]['google_map_api_key']))
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key='.$settings[0]['google_map_api_key'].'&libraries=geometry,places"></script>';
		else
			echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry,places"></script>';
			
			$event_lat	= (isset($job_activities_details[0]['event_address']['lat_str'])) ? $job_activities_details[0]['event_address']['lat_str'] : '';
			$event_long	= (isset($job_activities_details[0]['event_address']['long_str'])) ? $job_activities_details[0]['event_address']['long_str'] : '';
?>
<script src="<?php echo assets_url(); ?>site/js/slick.min.js"></script>

<script>
	var myCenter=new google.maps.LatLng(<?php echo $event_lat; ?>,<?php echo $event_long; ?>);
    function initialize(){
        var mapProp = {
            center:myCenter,
            zoom:10,
            draggable: false,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        var map=new google.maps.Map(document.getElementById("damageGoogleMap"),mapProp);
        var marker=new google.maps.Marker({
            position:myCenter,
            icon:assets_url+'site/images/map-overlay-arrow.svg'
        });
        marker.setMap(map);
    }
	
	$(document).ready(function(){
		
			$('.damage-slider').slick({
			arrows: false,
			dots:false,
			centerMode: true,
			centerPadding: '20px',
			slidesToShow: 1,
			focusOnSelect: true
		});
		google.maps.event.addDomListener(window, 'load', initialize);	
	});
	
	
	
</script>


<div data-role="page" id="signupPage">
    <div data-role="main" class="ui-content notif-map-content">
        <div class="close-container">
            <div class="popup-wrap">
                <div class="user-top">
                    <div class="user-img"><img src="<?php echo assets_url(); ?>site/images/user-img.jpg" alt="user-img" /></div>
                    <h3>David James</h3>
                </div>
                <div class="popup-form">
                    <div class="terms">
                        <div class="terms-scroll custom-scrollbar">
                            <div class="terms-content">
                                <p class="big-text">408 Miles - Fort Lauderdale <br />
                                Deliver By: 1st June 2016 <br />
                                12 Tonnes / 3 Pallets <br />
                                ForkLift Required</p>
                            </div>
                        </div>
                    </div>
                    <div class="popup-btns">
                        <input type="button" value="SEND MESSAGE" class="submit-leg" data-role="none" />
                        <input type="button" value="SUBMIT LEG" class="submit-leg" data-role="none" />
                        <input type="button" value="SUBMIT QUOTE" class="submit-leg" data-role="none" />
                    </div>
                </div>
            </div>
        </div>
		<a data-ajax="false" href="<?php echo base_url().'job-activities/'.$job_id ?>" class="cancel-signup">
				<img src="<?php echo assets_url() ?>site/images/cross.png" alt="cross" />
		</a>
		
		<?php
			$event_status		=  '';
			if(isset($job_activities_details[0]['event_type']) && $job_activities_details[0]['event_type']=='pickup') { $event_status = "Pickup"; }
			if(isset($job_activities_details[0]['event_type']) && $job_activities_details[0]['event_type']=='damage') { $event_status = "Damage"; }
			if(isset($job_activities_details[0]['event_type']) && $job_activities_details[0]['event_type']=='delay') { $event_status = "Delay"; }
			if(isset($job_activities_details[0]['event_type']) && $job_activities_details[0]['event_type']=='delivery_progress') { $event_status = "Delivery In Progress"; }
			if(isset($job_activities_details[0]['event_type']) && $job_activities_details[0]['event_type']=='quality_inspec') { $event_status = "Quality Inspection"; }
			if(isset($job_activities_details[0]['event_type']) && $job_activities_details[0]['event_type']=='delivered') { $event_status = "Delivered"; }
			if(isset($job_activities_details[0]['event_type']) && $job_activities_details[0]['event_type']=='update_location') { $event_status = "Current Location"; }
		?>
        <div class="signup-top">
			
		    <span><?php echo ucfirst($event_status); ?></span>
		</div>
		<?php
			if(isset($job_activities_details[0]['event_image']) && count($job_activities_details[0]['event_image'])>0)
			{
		?>
				<div class="damage-slider-wrap">
					<div class="damage-slider">
					<?php
						foreach($job_activities_details[0]['event_image'] as $key =>$images)
						{
					?>	
						<div class="damage-slider-box">
							<img src="<?php echo assets_url().'uploads/event_images/thumb/'.$images;?>" alt="damage-img" />
						</div>
					<?php
						}
					?>	
					</div>
				</div>
		<?php
			}
		?>
        <div class="damage-content">
			<?php
					$event_time_date 	= (isset($job_activities_details[0]['added_on'])) ? date('H:i a', strtotime($job_activities_details[0]['added_on'])).' '.date('m/d/Y', strtotime($job_activities_details[0]['added_on'])) : '';
					$event_amount		= (isset($job_activities_details[0]['event_cost']) && $job_activities_details[0]['event_cost'] > 0) ? number_format($job_activities_details[0]['event_cost'], 2) : '0';
					$event_description		= (isset($job_activities_details[0]['activity_details'])) ? $job_activities_details[0]['activity_details'] : '';
					$event_address		= (isset($job_activities_details[0]['event_address']['address'])) ? $job_activities_details[0]['event_address']['address'] : '';
					
			?>
            <span><?php echo $event_time_date; ?></span>
            <h3><?php echo ($event_amount > 0) ? 'Cost $'.$event_amount : ''; ?></h3>
            <p><?php echo ucfirst($event_description); ?></p>
        </div>

        <div class="damage-map">
            <div id="damageGoogleMap"></div>
            <h3><?php echo $event_address; ?></h3>
        </div>

	</div>
</div>