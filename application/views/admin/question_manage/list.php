<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for Listing of all categories.
|-->
<script>
		function set_page_id(page_id) {
			page_id						= page_id.trim();
			document.frm_opt.page_id.value 	= page_id;
			document.frm_opt.submit();
		}
</script>
<form name="frm_opt" action="<?php echo base_url().'control/email-template/edit'; ?>" method="POST">
		<input type="hidden" name="page_id" id="page_id" value="">
	</form>
<section id="main-content">
        <section class="wrapper">

	<?php
	        //echo "<pre>";print_r($langs);exit;
	
		//flash messages
		$flash_message=$this->session->flashdata('flash_message');
		if(isset($flash_message)){
	
		    if($flash_message == 'pages_updated')
		    {
			echo '<div class="alert alert-success">';
			echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Changes have been successfully updated.';
			echo '</div>';
		    }
		    if($flash_message == 'pages_not_updated'){
			echo'<div class="alert alert-error">';
			echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
			echo'</div>';
		    }
		    
		     if($flash_message == 'pages_inserted')
		    {
			echo '<div class="alert alert-success">';
			echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Data has been successfully inserted.';
			echo '</div>';
		    }
		    if($flash_message == 'pages_not_inserted'){
			echo'<div class="alert alert-error">';
			echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in insertion. Please try again.';        
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
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Questions Management
                    </header>
                    <div class="panel-body">
		
		    <!--<div class="clearfix">
                                <div class="btn-group">
				    <button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/email_template/add';">
                                          <a href="javascript:void(0);">Add Category<i class="fa fa-plus"></i></a>
                                    </button>
				    
                                </div>
		    </div>-->
		    <div class="space15"></div>
		    <br>
		    
			<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
                           <!-- <table class="table table-bordered table-striped table-condensed">-->
				<table class="table table-bordered table-striped table-condensed" id="dynamic-table">
                                <thead>
                                <tr>
					<th>Title</th>
					<th>Status</th>
					<th>Options</th>    
				    
                                </tr>
                                </thead>
                                <tbody>
				<?php
                                 
					$this->load->helper('text');
					if( isset($info) && count($info)>0)
					{
						foreach($info as $row)
						{
								$prntcat='No Parent';
												
										  
					?>
							<tr>
								<td><?php echo isset($row['title']) ? $row['title'] :''; ?></td>
								<td><?php echo (isset($row['status']) && $row['status']=='1') ? 'Active' :'Inactive';
								
								
								?></td>
								<td><a href="javascript:void(0)" onclick="set_page_id('<?php echo strval($row['_id']); ?>');"><i class="fa fa-pencil"></i></a></td>
				
											
										   
							</tr>
				<?php
				                }
					}
					else
					{
				?>
								    <tr>
										<td class="center "></td>
										<td class="center ">No result found...</td>
										<td class="center "></td>
									</tr>
				<?php
					}
				?>
                                </tbody>
				
                            </table>
			  </div>  
                        
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>