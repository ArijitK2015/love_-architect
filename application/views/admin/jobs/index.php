<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for Listing of all categories.
|-->

<script>
	function set_page_id(job_id) {
			
					job_id=job_id.trim();
					
					document.frm_opt.job_id.value = job_id;
					document.frm_opt.submit();
	}
	
</script>

<form name="frm_opt" action="<?php echo base_url().'control/manage-jobs/edit'; ?>" method="POST">
	<input type="hidden" name="job_id" id="job_id" value="">
</form>
	<section id="main-content">
		<section class="wrapper">
  
		<?php
			//flash messages
			$flash_message=$this->session->flashdata('flash_message');
			if(isset($flash_message)){
		
			    if($flash_message == 'reg_success')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Job has been successfully added.';
				echo '</div>';
			    }
			    if($flash_message == 'reg_error'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to add Job';        
				echo'</div>';
			    }
			    
			    if($flash_message == 'error_option'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to delete Job. Please try again.';        
				echo'</div>';
		    
			    }
			    
			    if($flash_message == 'option_updated')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Job has been successfully updated.';
				echo '</div>';
			    }
			    if($flash_message == 'email_error'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong>  Email id already exist. Please try with different one.';        
				echo'</div>';
			    }
			    
			    if($flash_message == 'success_option_update')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Job has been successfully updated.';
				echo '</div>';
			    }
			    if($flash_message == 'error_option_update'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
				echo'</div>';
			    }
			}
		?>
		<!-- page start-->
		<script>
			function remove_main(url)
			{
				var r = confirm('Confrim to remove this type form.')
				if(r === true)
				{
					window.location = url;
				}
			}
		</script>
		<div class="row">
			<div class="col-sm-12">
				<section class="panel">
					<header class="panel-heading">
						All Jobs
					</header>
					<div class="panel-body">
						<div class="clearfix">
									   <!--<div class="btn-group">
								<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/pages-help-contents/add';">
											   <a href="javascript:void(0);">Add page help content<i class="fa fa-plus"></i></a>
										  </button>
								
									   </div>-->
						</div>
			    <div class="space15"></div>
			    <br>
			    
				<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
					<!-- <table class="table table-bordered table-striped table-condensed">-->
					<table  class="table table-bordered table-striped table-condensed" id="dynamic-table">
						<thead>
							<tr>
								<th>User</th>
								<th>Description</th>
								<th>Image</th>
								<th>Pickup address</th>
								<th>Drop address</th>
								<th>Added on</th>
								<th>Edit</th>
								<!--<th>Delete</th>-->
							</tr>
						</thead>
					<tbody>
					<?php
						if(count($all_contents)>0)
						{
							
							foreach($all_contents as $jobs)
							{
								
								$job_id    	= strval($jobs['_id']);
								$client_f_name = (isset($jobs['user_details']['first_name'])) ?$jobs['user_details']['first_name'] : '';
								$client_l_name = (isset($jobs['user_details']['last_name']) && $jobs['user_details']['last_name']!='') ?$jobs['user_details']['last_name'] : '';
								$client_name   = (strlen($client_f_name." ".$client_l_name)>50) ? mb_substr($client_f_name." ".$client_l_name,0,50,'UTF-8')."..." : $client_f_name." ".$client_l_name;
								
								$job_description_db = (isset($jobs['description'])) ?$jobs['description'] : '';
								$job_description   = (strlen($job_description_db)>100) ? mb_substr($job_description_db,0,100,'UTF-8')."..." : $job_description_db;
								
								
								$pick_up_address_db = (isset($jobs['pickup_address']['address'])) ?$jobs['pickup_address']['address'] : '';
								$pick_up_address   = (strlen($pick_up_address_db)>100) ? mb_substr($pick_up_address_db,0,100,'UTF-8')."..." : $pick_up_address_db;
								$drop_address_db = (isset($jobs['drop_address']['address'])) ? $jobs['drop_address']['address'] : '';
								$drop_address   = (strlen($drop_address_db)>100) ? mb_substr($drop_address_db,0,100,'UTF-8')."..." : $drop_address_db;
								
								$added_on		= (isset($jobs['added_on'])) ? date('Y-m-d',strtotime($jobs['added_on'])) : '';
								
						?>
								<tr>
									<td><?php echo ucfirst($client_name);?></td>
									<td><?php echo $job_description;?></td>
									<?php if(isset($jobs['image']) && ($jobs['image']!='')){?>
									<td><img  width="100px" src="<?php echo assets_url().'uploads/job_images/thumb/'. $jobs['image']; ?>"></td>
									<?php }else{?> <td><img  width="100px" src="<?php echo assets_url().'admin/images/no_images.png'; ?>"></td>
									<?php }?>
									<td><?php echo $pick_up_address;?></td>
									<td><?php echo $drop_address;?></td>
									<td><?php echo $added_on;?></td>
									<td><a href="javascript:void(0)" onclick="set_page_id('<?php echo $job_id; ?>');"><i class="fa fa-pencil"></i></a></td>
									<!--<td><a href="javascript:void(0)" onclick="remove_main('<?php echo base_url().'Data_form_controller/remove_all_cat_field/'.addslashes($driver['id']) ?>')"><i class="fa fa-trash-o"></i></a></td>-->
								</tr>
						<?php
							}
						}
						else
						{
						?>
							<tr>
								<td class="center"></td>
								<td class="center"></td>
								<td class="center"></td>
								<td class="center"><?php echo 'No result found...'; ?></td>
								<td class="center"></td>
								<td class="center"></td>
								<td class="center"></td>
							</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>  