<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.touchSwipe.min.js'); ?>"></script>

<script>
	var job_id 	= '<?php echo $job_id; ?>';
	var user_id = '<?php echo $user_id; ?>';

</script>

<script type="text/javascript" src="<?php echo assets_url('site/js/pages/my-job-qoutes.js'); ?>"></script>

<!--Default page loader section-->
	<div id="loading-filter-background" style="display: block;">
		<div id="loading-filter-image">
			<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
		</div>
		<div class="loading-text">Loading</div>
	</div>


<div data-role="page" id="signupPage">
    <div data-role="main" class="ui-content notif-map-content">
        <div class="close-container">
            <div class="popup-wrap">
                <div class="user-top">
                    <div class="user-img"><img src="<?php echo assets_url('site/images/user-img.jpg'); ?>" alt="user-img" /></div>
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
		<a data-ajax="false" href="<?php echo base_url().'my-jobs' ?>" class="cancel-signup">
				<img src="<?php echo assets_url(); ?>site/images/cross.png" alt="cross" />
		</a>
			<div class="signup-top">
				<span id="total_qoutes">Quotes (0)</span>
			</div>
			
				
		<div id="qoutes_result" class="quote-table">
			
		</div>
</div>