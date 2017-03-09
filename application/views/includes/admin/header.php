<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php echo (isset($pdesc) && $pdesc!='') ? ucfirst($pdesc) : $settings[0]['meta_description']; ?>">
		<meta name="keywords" 	content="<?php echo (isset($pkeys) && $pkeys!='') ? ucfirst($pkeys) : $settings[0]['meta_keywords']; ?>">
		<meta name="author" content="Esolz technologies">
	
		<title> <?php echo (isset($ptitle) && ($ptitle != '')) ? ucfirst($ptitle) : ucfirst($settings[0]['site_name']).' - Admin'; ?> </title>
		<?php
				
			$favicon_img		= (isset($settings[0]['site_fabicon'])) 		? $settings[0]['site_fabicon'] 		: 'favicon.ico';
				
				
			if(isset($cmp_auth_id) && !empty($cmp_auth_id))
				$favicon_img 	= (isset($admin_details[0]['site_fabicon']))		? $admin_details[0]['site_fabicon']	: $favicon_img;
		?>
		<!--Site logo-->
		<link rel="icon" href="<?php echo assets_url();?>site/images/<?php echo $favicon_img; ?>" type="image/x-icon">
		 
		<!--Core CSS -->
		<link href="<?php echo assets_url();?>admin/bs3/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo assets_url();?>admin/js/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet">
		<link href="<?php echo assets_url();?>admin/css/bootstrap-reset.css" rel="stylesheet">
		<link href="<?php echo assets_url();?>admin/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>admin/js/bootstrap-fileupload/bootstrap-fileupload.css" />
		<!--clock css-->
		<link href="<?php echo assets_url();?>admin/js/css3clock/css/style.css" rel="stylesheet">
		
		<!--responsive table-->
		<link href="<?php echo assets_url();?>admin/css/table-responsive.css" rel="stylesheet" />
	 
		<!-- Custom styles for this template -->
		<link href="<?php echo assets_url();?>admin/css/style.css" rel="stylesheet">
		<link href="<?php echo assets_url();?>admin/css/style-responsive.css" rel="stylesheet"/>
	 
		<!--dynamic table-->
		<link href="<?php echo assets_url();?>admin/js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
		<link href="<?php echo assets_url();?>admin/js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo assets_url();?>admin/js/data-tables/DT_bootstrap.css" />
	
		<!-- Just for debugging purposes. Don't actually copy this line! -->
		<!--[if lt IE 9]>
		<script src="<?php echo assets_url();?>admin/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		
		<script src="<?php echo assets_url(); ?>admin/js/jquery.js"></script>
		<script src="<?php echo assets_url(); ?>admin/bs3/js/bootstrap.min.js"></script>
		<script src="<?php echo assets_url(); ?>admin/js/jquery-ui-1.9.2.custom.min.js"></script>
		<script src="<?php echo assets_url(); ?>admin/js/jquery-migrate-1.2.1.js"></script>
		
		<?php
			$pay_amount 		= (isset($setting_data[0]['single_car_amount'])) ? $setting_data[0]['single_car_amount'] : 5;
			$pay_amount 		= number_format($pay_amount, 2);
		?>
		
		<script>
			var base_url 			= '<?php echo base_url(); ?>';
			var assets_url 		= '<?php echo assets_url(); ?>';
			var main_base_url		= '<?php echo main_base_url(); ?>'
		</script>
		<?php
			$controller_name 		= $this->router->fetch_class();
			$function_name 		= $this->router->fetch_method();
		?>
		<!--	get all time zones	-->
		
	</head>
		
	<body>
		<?php $page = $this->uri->segment(2); ?>
		<section id="container">
			<!--header start-->
			<header class="header fixed-top clearfix">
				<!--logo start-->
				<div class="brand">
					<a href="<?php echo base_url(); ?>control/admin-dashboard" class="logo">
						<b><?php echo (isset($ptitle) && ($ptitle != '')) ? substr($ptitle, 0, 10) : substr($settings[0]['site_name'], 0, 10); ?></b>
					</a>
					<div class="sidebar-toggle-box">
						<div class="fa fa-bars"></div>
					</div>
				</div>
					
				<!--logo end-->
				<div class="top-nav clearfix">
					<!--search & user info start-->
					<ul class="nav pull-right top-menu">
						<!-- user login dropdown start-->
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle new_img" href="javascript:void(0)">
								<?php
									$permission_arr 	= $permissions = array();
									$user_id 			= ($this->session->userdata('user_id_lovearchitect')) ? $this->session->userdata('user_id_lovearchitect') : 0 ;
									
									$settings_det 	= $this->common_model->get('settings');
									$site_title = isset($settings_det[0]['site_name']) ? $settings_det[0]['site_name'] : '';
									if(!empty($cmp_auth_id))	{
										$member_details= $this->common_model->get('membership', array(), array('_id' => (string)$user_id));
										$admin_details = $member_details;
										if(isset($admin_details[0]) && !empty($admin_details[0]))
											$admin_details[0]['is_sub_admin']	= isset($member_details[0]['is_sub_admin']) ? $member_details[0]['is_sub_admin'] : 0;
									}
									else
										$admin_details = $this->common_model->get('membership', array(), array('_id' => (string)$user_id));
										
									$user_permissions 	= $this->common_model->get('user_permission', array('*'), array('user_id' => (string)$user_id));
										
									if(!empty($user_permissions))
										$permission_arr= (isset($user_permissions[0]['menu_ids'])) 	? $user_permissions[0]['menu_ids'] : array();
										
										
									if(isset($admin_details[0]['profile_image']) && ($admin_details[0]['profile_image']!=""))
									{
										if($admin_details[0]['is_sub_admin'] == 2)
											echo '<img alt="" src="'.main_base_url().'thumb.php?height=40&width=40&type=aspectratio&img='.assets_url().'uploads/dealer_image/thumb/'.$admin_details[0]['profile_image'].'" >';
										elseif($admin_details[0]['is_sub_admin'] == 1)
											//echo '<img alt="" src="'.main_base_url().'thumb.php?height=40&width=40&type=aspectratio&img='.assets_url().'uploads/merchant_images/thumb/'.$admin_details[0]['site_logo'].'" >';
											echo '<img alt="" src="'.main_base_url().'thumb.php?height=40&width=40&type=aspectratio&img='.assets_url().'uploads/subadmin_image/thumb/'.$admin_details[0]['profile_image'].'">';
										else
											echo '<img alt="" src="'.main_base_url().'thumb.php?height=40&width=40&type=aspectratio&img='.assets_url().'uploads/subadmin_image/thumb/'.$admin_details[0]['profile_image'].'">';
									}
									elseif(isset($admin_details[0]['site_logo']) && ($admin_details[0]['site_logo']!=""))
									{
										//if($admin_details[0]['is_sub_admin'] == 1)
										//	//echo '<img alt="" src="'.main_base_url().'thumb.php?height=40&width=40&type=aspectratio&img='.assets_url().'uploads/merchant_images/thumb/'.$admin_details[0]['site_logo'].'" >';
										//	echo '';
										//else
										//	echo '<img alt="" src="'.main_base_url().'thumb.php?height=40&width=40&type=aspectratio&img='.assets_url().'admin/images/avatar1_small.jpg">';
										echo '';
									}
									else
									{
										echo '<img alt="" src="'.main_base_url().'thumb.php?height=40&width=40&type=aspectratio&img='.assets_url().'admin/images/avatar1_small.jpg">';
									}
									
									$is_sub_admin_stat = isset($admin_details[0]['is_subadmin']) ? $admin_details[0]['is_subadmin'] : '0';
									//echo "hello".$is_sub_admin_stat;
									//if($admin_details[0]['is_sub_admin'] == 1)
									//	echo '<span class="username">'.substr($site_title, 0, 15).'</span>';
									//else
										echo '<span class="username">'.ucwords($admin_details[0]['first_name'].' '.$admin_details[0]['last_name']).'</span>';
								?>
								
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu extended logout">
								<li><a href="<?php echo base_url(); ?>control/myaccount"><i class=" fa fa-suitcase"></i>Profile</a></li>
								<?php if($admin_details[0]['is_sub_admin'] != 2){ ?>
								<li><a href="<?php echo base_url(); ?>control/change-password"><i class="fa fa-unlock-alt"></i>Change Password</a></li>
								<?php } ?>
								<?php if($admin_details[0]['is_sub_admin'] != 2 && $admin_details[0]['is_sub_admin'] != 1){ ?>
									<li><a href="<?php echo base_url(); ?>control/contactsetting"><i class="fa fa-cog"></i>Contact Settings</a></li>
								<?php } ?>
								<li><a href="<?php echo base_url(); ?>control/logout"><i class="fa fa-key"></i> Log Out</a></li>
							</ul>
						</li>
						<!-- user login dropdown end -->
					</ul>
				    <!--search & user info end-->
				</div>
			</header>
			<!--header end-->
			<!--sidebar start-->
		
			<aside>
				<div id="sidebar" class="nav-collapse">
					<!-- sidebar menu start-->
					<div class="leftside-navigation">
						<ul class="sidebar-menu" id="nav-accordion">
							<?php
							    if($is_sub_admin_stat=='0')
								    $all_menus = $this->common_model->get('menus', array(), array('parent_id' => '0', 'menu_type' => '0', 'status' => '1'), null, null, null, null, array('_id' => 'asc'));
								else
								   $all_menus = $this->common_model->get('menus', array(), array('parent_id' => '0',"is_subadmin" =>'1', 'menu_type' => '0', 'status' => '1'), null, null, null, null, array('_id' => 'asc'));
								
								foreach($all_menus as $menus)
								{
									$al_menu_id	= (isset($menus['_id']))	? strval($menus['_id']) : '';
									$all_sub_menus = $this->common_model->get('menus',  array(), array('parent_id' => $menus['id'], 'status' => '1'));
										
									$super 		= ($is_sub_admin_stat)	? 1 : 0;
										
									if(in_array($al_menu_id, $permission_arr) || $super == 0)
									{
								?>
										<li>
											<a <?php if($page == $menus['url']){ ?> class="active" <?php } ?> href="<?php echo (!empty($menus['url'])) ? base_url().'control/'.$menus['url'].'/'.$menus['parameters'] : 'javascript:void(0);'; ?>">
												<?php
													if(isset($menus['use_image']) && ($menus['use_image'] == 1))
														echo (!empty($menus['icon_image'])) ? '<img class="admin-left-menu-img" alt="" src="'.base_url().'site/images/'.$menus['icon_image'].'">' : '';
													else echo (!empty($menus['icon_class'])) ? '<i class="'.$menus['icon_class'].'"></i>' : '';
												?>
												
												<span><?php echo $menus['title'] ;?></span>
											</a>
											
											<?php if (!empty($all_sub_menus))
											{
												echo "<ul class='sub'>";
												foreach ($all_sub_menus as $sub_menu)
												{
													$sub_menu_id	= (isset($sub_menu['_id']))	? strval($sub_menu['_id']) : '';
														
													if(in_array($sub_menu_id, $permission_arr) || $super == 0 )
													{
											?>
														<li <?php if($page == $sub_menu['url']){ ?> class="active" <?php } ?>><a href="<?php echo (!empty($sub_menu['url'])) ? base_url().'control/'.$sub_menu['url'].'/'.$sub_menu['parameters'] : 'javascript:void(0);'; ?>"><?php echo $sub_menu['title'] ;?></a></li>
													
												<?php
													}
												}
												echo "</ul>";
											}
											?>
										</li>
							<?php
									}
								}
							?>
						</ul>
						<ul class="sidebar-menu" id="dealer_menu_id" style="padding-top: 0;">
							<?php
								if(!empty($all_existing_menus)){
									foreach($all_existing_menus as $menu){
										$menu_name = ($menu['search_year']) ? $menu['search_year'].' - '.$menu['search_param'] : ucfirst($menu['search_param']);
										$menu_name = (!empty($menu_name)) ? $menu_name : 'All cars';
										$menu_name = (strlen($menu_name) > 20) ? substr($menu_name, 0, 20) : $menu_name;
										
										echo '<li><a href="'.base_url().'control/search-list/'.$menu['id'].'">'.htmlentities($menu_name).'</a></li>';
									}
								}
							?>
						</ul>
					</div>
					<!-- sidebar menu end-->
				</div>
			</aside>
			<!--sidebar end-->
			<script>
			$(document).ready(function(){
			$(".alert-error").addClass("alert-danger");
			});
			</script>