<!--|
| Copyright © 2016 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  site settings.
|--> 
<section id="main-content">
        <section class="wrapper">

		<?php
			//flash messages
			$flash_message=$this->session->flashdata('flash_message');
			if(isset($flash_message)){
				
				if($flash_message == 'site_updated')
				{
					echo '<div class="alert alert-success">';
					echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Site Settings has been successfully updated.';
					echo '</div>';
					
				}
				if($flash_message == 'site_not_updated'){
					echo'<div class="alert alert-error">';
					echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
					echo'</div>';
					
				}
					
				if($flash_message == 'error'){
					echo'<div class="alert alert-error">';
					echo'<i class="icon-remove-sign"></i><strong>Error!</strong> . Please try again.';        
					echo'</div>';
					
				}
				
			}
		?>
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
					<header class="panel-heading">
						Site Settings
					</header>
					<div class="panel-body">
						<div class="form">
							<form class="cmxform form-horizontal " id="siteSettings" method="post" action="<?php echo base_url(); ?>control/sitesetting/updt" enctype="multipart/form-data">
								<input type="hidden" name="site_id" id="site_id" value="<?php echo strval($settings[0]['_id']); ?>" />
									
								<header class="panel-heading" style="margin-bottom: 20px;"> General Settings </header>
								<div class="form-group ">
									<label for="firstname" class="control-label col-lg-3">Site Name:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="site_name" value="<?php echo $settings[0]['site_name']; ?>" type="text" />
									</div>
								</div>
									
								<div class="form-group ">
									<label for="firstname" class="control-label col-lg-3">Site Logo:</label>
									<div class="col-lg-4">
										<p><img style="width: 250px;" src="<?php echo base_url().'assets/site/images/'.$settings[0]['site_logo'] ?>" alt="" /></p>
										<input class=" form-control" name="site_logo" id="site_logo" value="" type="file" />
										<span>(Please upload a logo with the following dimensions : 170 x 50)</span>
									</div>
								</div>
									
								<div class="form-group ">
									<label for="firstname" class="control-label col-lg-3">Site Favicon:</label>
									<div class="col-lg-4">
										<p><img src="<?php echo base_url().'assets/site/images/'.$settings[0]['site_fabicon'] ?>" alt="" /></p>
										<input class=" form-control" name="site_fabicon" id="site_fabicon" value="" type="file" />
									</div>
								</div>
									
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">System Email:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="system_email" value="<?php echo $settings[0]['system_email']; ?>" type="text" />
									</div>
								</div>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Records per page of Admin:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="admin_pagination" value="<?php echo $settings[0]['admin_pagination']; ?>" type="text" />
									</div>
								</div>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Records per page of Site:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="site_pagination" value="<?php echo $settings[0]['site_pagination']; ?>" type="text" />
									</div>
								</div>
								<div class="form-group" id="percent_div">
										<label for="Title" class="control-label col-lg-3">Platform fee :</label>
										<div class="col-lg-4">
											<div class="input-group">
												<input class="form-control filterme" id="platform_fee" name="platform_fee" autocomplete="off" value="<?php echo (isset($settings[0]['platform_fee'])) ? $settings[0]['platform_fee'] : ''; ?>" type="text" placeholder="" aria-describedby="basic-addon2">
												<span class="input-group-addon" id="basic-addon2">%</span>
											</div>
										</div>
								</div>
								<div class="form-group ">
									<label for="ccomment" class="control-label col-lg-3">Meta Keywords:</label>
									<div class="col-lg-4">
										<textarea class="form-control " id="meta_keywords" name="meta_keywords"><?php echo $settings[0]['meta_keywords']; ?></textarea>
									</div>
								</div>
								<div class="form-group ">
									<label for="ccomment" class="control-label col-lg-3">Meta Description:</label>
									<div class="col-lg-4">
										<textarea class="form-control " id="meta_description" name="meta_description"><?php echo $settings[0]['meta_description']; ?></textarea>
									</div>
								</div>
								
								<div class="form-group ">
										<label for="admin_status" class="control-label col-lg-3">Server timezone </label>
											<div class="col-lg-4">
												<select class="form-control" name="server_timezone" id="server_timezone" required>
													<option value="">Select</option>
													<?php
														$tzlists = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
														if(!empty($tzlists))
														{
															$server_timezone	= (isset($settings[0]['system_timezone'])) ? $settings[0]['system_timezone'] : $system_timezone;
															foreach($tzlists as $tzk=>$timezones)
															{
													?>
																<option value="<?php echo $timezones;?>" <?php echo ($server_timezone == $timezones) ? 'selected' :'';?>><?php echo $timezones;?></option>
													<?php
															}
														}
													?>
												</select>
											</div>
								</div>

								
								<header class="panel-heading" style="margin-bottom: 20px;"> Social Network Settings </header>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Facebook Link:</label>
									<div class="col-lg-4">
										<input class="form-control" name="facebook" value="<?php echo $settings[0]['facebook']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Twitter Link:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="twitter" value="<?php echo $settings[0]['twitter']; ?>" type="text" />
									</div>
								</div>
								
								<header class="panel-heading" style="margin-bottom: 20px;"> Email Server Settings </header>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">SMTP server:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="smtp_server" value="<?php echo $settings[0]['smtp_server']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">SMTP Port:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="smtp_port" value="<?php echo $settings[0]['smtp_port']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">SMTP Username:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="smtp_username" value="<?php echo $settings[0]['smtp_username']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">SMTP Password:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="smtp_password" value="<?php echo $settings[0]['smtp_password']; ?>" type="password" />
									</div>
								</div>
								
								<header class="panel-heading" style="margin-bottom: 20px;"> Linkedin App Settings </header>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Linkedin App Client ID:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="linkedinApiKey" value="<?php echo $settings[0]['linkedinApiKey']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Linkedin App Secret:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="linkedinApiSecret" value="<?php echo $settings[0]['linkedinApiSecret']; ?>" type="text" />
									</div>
								</div>
								
								<header class="panel-heading" style="margin-bottom: 20px;"> Google Map Settings </header>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Google Map API Key:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="google_map_api_key" value="<?php echo $settings[0]['google_map_api_key']; ?>" type="text" />
									</div>
								</div>
								
								<header class="panel-heading" style="margin-bottom: 20px;"> Stripe Settings </header>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Stripe Payment type:</label>
									<div class="col-lg-4">
										<select name="stripe_pay_type" id="stripe_pay_type" class="form-control">
											<option value="1" <?php echo ($settings[0]['stripe_pay_type'] == 1) ? 'selected' : ''; ?>>Live</option>
											<option value="2" <?php echo ($settings[0]['stripe_pay_type'] == 2) ? 'selected' : ''; ?>>Sandbox</option>
										</select>
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Stripe Live Secret Key:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="stripe_live_secret_key" value="<?php echo $settings[0]['stripe_live_secret_key']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Stripe Live Public Key:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="stripe_live_public_key" value="<?php echo $settings[0]['stripe_live_public_key']; ?>" type="text" />
									</div>
								</div>
								
								<div style="margin-bottom: 20px;"> &nbsp; </div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Stripe Sandbox Secret Key:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="stripe_sandbox_secret_key" value="<?php echo $settings[0]['stripe_sandbox_secret_key']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Stripe Sandbox Public Key:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="stripe_sandbox_public_key" value="<?php echo $settings[0]['stripe_sandbox_public_key']; ?>" type="text" />
									</div>
								</div>
								
								<header class="panel-heading" style="margin-bottom: 20px;"> Twilio Account </header>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Twilio Account ssid:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="twilio_AccountSid" value="<?php echo $settings[0]['twilio_AccountSid']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Twilio Auth Token:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="twilio_AuthToken" value="<?php echo $settings[0]['twilio_AuthToken']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Twilio Auth Mobile No:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="twilio_mobile_no" value="<?php echo $settings[0]['twilio_mobile_no']; ?>" type="text" />
									</div>
								</div>
								
								
								<header class="panel-heading" style="margin-bottom: 20px;"> Uber Rush Account Details </header>
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Client Id:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="client_id" value="<?php echo $settings[0]['client_id']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group ">
									<label for="lastname" class="control-label col-lg-3">Client Secret:</label>
									<div class="col-lg-4">
										<input class=" form-control" name="client_secret" value="<?php echo $settings[0]['client_secret']; ?>" type="text" />
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-lg-offset-3 col-lg-6">
										<button class="btn btn-primary" type="submit">Save</button>
										<button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/dashboard';">Cancel</button>
									</div>
								</div>
								
							</form>
						</div>
					</div>
                    </section>
               </div>
          </div>
            <!-- page end-->
        </section>
    </section>
<script>
		
		$('.filterme').keypress(function(eve) {
					if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0) ) {
					eve.preventDefault();
			}
			   
				// this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
				 $('.filterme').keyup(function(eve) {
				  if($(this).val().indexOf('.') == 0) {    $(this).val($(this).val().substring(1));
				  }
				 });
		});
		
</script>