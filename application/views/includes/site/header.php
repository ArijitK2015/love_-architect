<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			$site_name 		= (isset($settings[0]['site_name'])) 			? ucfirst($settings[0]['site_name']) 			: 'New site';
			$meta_desc 		= (isset($settings[0]['meta_description'])) 		? ucfirst($settings[0]['meta_description']) 		: '';
			$meta_key 		= (isset($settings[0]['meta_keywords'])) 		? ucfirst($settings[0]['meta_keywords']) 		: '';
				
			$system_timezone	= (isset($settings[0]['system_timezone'])) 		? $settings[0]['system_timezone'] 				: 'America/Los_Angeles';
				
			if(isset($settings[0]['site_fabicon']))
				$favicon_img	= $settings[0]['site_fabicon'];
			elseif(isset($settings['site_fabicon']))
				$favicon_img	= $settings['site_fabicon'];
			else
				$favicon_img	= 'favicon.ico';
				
			if(isset($site_fav_icon) && !empty($site_fav_icon))
				$favicon_img 	= (isset($site_fav_icon) && !empty($site_fav_icon))? $site_fav_icon	: $favicon_img;
		?>
			
		<meta charset="utf-8">
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
			
		<title> <?php echo (isset($ptitle) && ($ptitle != '')) ? ucfirst($ptitle) : ucfirst($site_name); ?> </title>
			
		<meta name="description" content="<?php echo (isset($pdesc) && ($pdesc != '')) ? ucfirst($pdesc) : ucfirst($meta_desc); ?>">
		<meta name="keywords" 	content="<?php echo (isset($pkeys) && ($pkeys != '')) ? ucfirst($pkeys) : ucfirst($meta_key); ?>">
		<meta name="author" content="Esolz technologies">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
			
		<!-- favicon -->
		<link rel="icon" href="<?php echo assets_url('site/images/'.$favicon_img); ?>" type="image/x-icon">
		<!--<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/site/images/favicon.png" />-->
			
		<!-- css links -->
		<link rel="stylesheet" href="<?php echo assets_url('site/css/jquery-ui.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets_url('site/css/jquery.mobile-1.4.5.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets_url('site/css/font-awesome.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets_url('site/css/fonts.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets_url('site/css/ion.rangeSlider.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets_url('site/css/ion.rangeSlider.skinHTML5.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets_url('site/css/custom.css'); ?>" />
		<link rel="stylesheet" href="<?php echo assets_url('site/css/developer.css'); ?>" />
			
		<!-- js links -->
		<script src="<?php echo assets_url('site/js/jquery-1.11.3.min.js'); ?>"></script>
		<script src="<?php echo assets_url('site/js/jquery-ui.js'); ?>"></script>
		<script src="<?php echo assets_url('site/js/jquery.mobile-1.4.5.min.js'); ?>"></script>
		<script src="<?php echo assets_url('site/js/ion.rangeSlider.min.js'); ?>"></script>
		<script src="<?php echo assets_url('site/js/custom.js'); ?>"></script>
		<script src="<?php echo assets_url('site/js/jstz.min.js'); ?>" type="text/javascript"></script>	
			
		<script>
			var base_url 		= '<?php echo base_url() ?>'; 	//global veriable to store site url
			var main_base_url 	= '<?php echo main_base_url() ?>'; //global veriable to store site url
			var assets_url 	= '<?php echo assets_url() ?>'; 	//global veriable to store site url
			var job_det_actual 	= '';						//global veriable to store job details
			var session_user_id = '<?php echo ($this->session->userdata('site_user_objId_hotcargo')) ? $this->session->userdata('site_user_objId_hotcargo') : 0 ?>';
														//global veriable to store session user id
													
			var timezone = '';
			$(document).ready(function(){
				var localtime 			= new Date().getTimezoneOffset();;
				var current_timezone 	= '<?php echo ($this->session->userdata('user_timezone')) ? $this->session->userdata('user_timezone') : $system_timezone; ?>';
				//console.log('server timezone: '+current_timezone);
				var tz = jstz.determine();
				// Determines the time zone of the browser client
				timezone = tz.name(); //'Asia/Kolkata' for Indian Time.
				timezone = (timezone != '') ? timezone : '<?php echo $system_timezone; ?>';
				//console.log('server timezone: '+timezone);
				document.cookie="user_timezone="+timezone;
			});
				
			//google Analytics
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
				
			ga('create', 'UA-88355516-1', 'auto');
			ga('send', 'pageview');
		</script>
	</head>
		
	<body>