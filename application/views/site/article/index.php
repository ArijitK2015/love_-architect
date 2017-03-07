<!--Left menu section-->
<?php
		$flash_message 	= $this->session->flashdata('flash_message');
		$flash_message_cont = $this->session->flashdata('flash_message_cont');
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
		$flash_message 	= $this->session->flashdata('flash_message');
		$default_site_logo 	= (isset($settings[0]['site_logo'])) ? $settings[0]['site_logo'] : 'logo.png';
	?>
	<div class="sidebar-menu">
		<?php
			if(!empty($site_logo))
				echo '<a data-ajax="false" href="'.base_url().'" class="logo"><img src="'.assets_url('uploads/merchant_images/thumb/'.$site_logo).'" alt="logo" /></a>';
			else
				echo '<a data-ajax="false" href="'.base_url().'" class="logo"><img src="'.assets_url('site/images/'.$default_site_logo).'" alt="logo" /></a>';
		?>
		
		<ul>
			<?php
				if($user_id != '')
				{
					if(!empty($users_all_menus))
					{
						foreach($users_all_menus as $menu)
							echo '<li><a href="'.base_url().$menu['url'].'" data-ajax="false">'.$menu['title'].'</a></li>';
					}
				}
				else
				{
			?>
						<li><a data-ajax="false" href="<?php echo base_url().'login' ?>">Login</a></li>
						<li><a data-ajax="false" href="<?php echo base_url().'sign-up/customer' ?>">Register</a></li>
						<li><a data-ajax="false" href="<?php echo base_url().'help' ?>">Help</a></li>
						<li><a data-ajax="false" href="<?php echo base_url().'about' ?>">About</a></li>
			<?php
				}
				
			?>
		</ul>
	</div>
	<div class="menu-overlay"></div>
	<!--Left menu section-->
	
	<!-- dashboard screen -->
	<div data-role="page" id="signupPage" class="main-page">
		<div data-role="main" class="ui-content login-content">
			<a data-ajax="false" href="<?php echo base_url() ?>" class="cancel-signup ui-link">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="18.531" height="18.469" viewBox="0 0 18.531 18.469">
					<path d="M10.950,9.191 L18.215,16.449 C18.653,16.887 18.628,17.627 18.160,18.095 C17.692,18.563 16.951,18.588 16.513,18.150 L9.248,10.892 L1.996,18.137 C1.560,18.572 0.824,18.547 0.359,18.082 C-0.106,17.617 -0.131,16.882 0.304,16.447 L7.556,9.202 L0.370,2.023 C-0.068,1.586 -0.043,0.845 0.425,0.377 C0.893,-0.091 1.635,-0.115 2.073,0.322 L9.258,7.501 L16.344,0.422 C16.779,-0.012 17.516,0.012 17.981,0.477 C18.446,0.942 18.471,1.678 18.036,2.112 L10.950,9.191 Z" class="cls-1"></path>
				</svg>
			</a>
			
			<a data-ajax="false" href="javascript:void(0)" class="menu-strap">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="20.281" height="14.813" viewBox="0 0 20.281 14.813">
					<path d="M19.413,14.821 L0.862,14.821 C0.384,14.821 -0.007,14.402 -0.007,13.891 C-0.007,13.380 0.384,12.962 0.862,12.962 L19.413,12.962 C19.891,12.962 20.283,13.380 20.283,13.891 C20.283,14.402 19.891,14.821 19.413,14.821 ZM0.862,6.485 L15.724,6.485 C16.202,6.485 16.593,6.903 16.593,7.414 C16.593,7.926 16.202,8.344 15.724,8.344 L0.862,8.344 C0.384,8.344 -0.007,7.926 -0.007,7.414 C-0.007,6.903 0.384,6.485 0.862,6.485 ZM18.491,1.867 L0.862,1.867 C0.384,1.867 -0.007,1.449 -0.007,0.938 C-0.007,0.426 0.384,0.008 0.862,0.008 L18.491,0.008 C18.969,0.008 19.360,0.426 19.360,0.938 C19.360,1.449 18.969,1.867 18.491,1.867 Z" class="cls-1"/>
				</svg>
			</a>
			
			<?php
				if(!empty($site_logo))
					echo '<a data-ajax="false" href="'.base_url().'" class="logo map-logo"><img src="'.assets_url('uploads/merchant_images/thumb/'.$site_logo).'" alt="logo" /></a>';
				else
					echo '<a data-ajax="false" href="'.base_url().'" class="logo map-logo"><img src="'.assets_url('site/images/'.$default_site_logo).'" alt="logo" /></a>';
			?>
				
			<!-- Main map search form -->
			<div class="map-wrapper">
				<div class="serach-map">
					<div class="signup-top article_cont">
						<span><?php echo (isset($all_details[0]['page_title'])) ? ucfirst($all_details[0]['page_title']) : '' ?></span>
					</div>
					
					<div class="register-form article_cont" id="customer_fields">
						<p><?php echo (isset($all_details[0]['page_content'])) ? ucfirst($all_details[0]['page_content']) : '' ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
