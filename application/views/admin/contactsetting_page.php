<!--|
| Copyright © 2015 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for edit  contact settings.
|--> 
<section id="main-content">
        <section class="wrapper">

		<?php
			//flash messages
			$flash_message=$this->session->flashdata('flash_message');
			if(isset($flash_message)){
		
			if($flash_message == 'contact_updated')
			{
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Contact Settings has been successfully updated.';
				echo '</div>';
			
			}
			if($flash_message == 'contact_not_updated'){
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
                            Contact info
                        </header>
                        <div class="panel-body">
                            <div class="form">
                                <form class="cmxform form-horizontal " id="contactinfo" method="post" action="<?php echo base_url(); ?>control/contactsetting/updt">
                                    <div class="form-group ">
                                        <label for="firstname" class="control-label col-lg-3">Contact Email:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" name="contact_email" value="<?php echo $contact_settings[0]['contact_email']; ?>" type="text" />
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="lastname" class="control-label col-lg-3">Phone no:</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" name="ph_no" value="<?php echo $contact_settings[0]['ph_no']; ?>" type="text" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group ">
                                        <label for="email" class="control-label col-lg-3">Fax:</label>
                                        <div class="col-lg-6">
                                            <input class="form-control " name="fax" value="<?php echo $contact_settings[0]['fax']; ?>" type="text" />
                                        </div>
                                    </div>
                                    <!--<div class="form-group ">
                                        <label for="ccomment" class="control-label col-lg-3">Address:</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control " name="address"><?php //echo $contact_settings[0]['address']; ?></textarea>
                                        </div>
                                    </div>-->

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