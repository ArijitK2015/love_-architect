	<!--|
	| Copyright © 2016 by Esolz Technologies
	| Author :  debojit.talukdar@esolzmail.com
	|
	|	http://www.esolz.net/
	|
	| All rights reserved. This page is used for showing dashboard.
	|-->
	<link rel="stylesheet" href="<?php echo assets_url();?>admin/js/morris-chart/morris.css">
	
	<script>
		function car_losting_search() {
			return false;
		}
	</script>
	
	<section id="main-content">
		<section class="wrapper">
		
			<div class="row">
				<div class="col-md-8">
				
				</div>
				
				<div class="col-md-4">
					<div class="profile-nav alt">
						<section class="panel">
							<div class="user-heading alt clock-row terques-bg">
								<h1><?php echo date("F d"); ?></h1>
								<p class="text-left"><?php echo date("Y, l"); ?></p>
							</div>
							<ul id="clock">
								<li id="sec"></li>
								<li id="hour"></li>
								<li id="min"></li>
							</ul>
						</section>
					</div>
				</div>
			</div>
		</section>
	</section>
<!--main content end-->



<!--right sidebar start-->




<!--right sidebar end-->
</section>
	<!--clock init-->
	<script src="<?php echo assets_url();?>admin/js/morris-chart/morris.js"></script>
	<script src="<?php echo assets_url();?>admin/js/morris-chart/raphael-min.js"></script>
	<script src="<?php echo assets_url();?>admin/js/css3clock/js/css3clock.js"></script>
	<script src="<?php echo assets_url();?>admin/js/dashboard.js"></script>
	<script src="<?php echo assets_url();?>admin/js/jquery.customSelect.min.js" ></script>
	