<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for Listing of all categories.
|-->

<script>
	function set_depot_id(depot_id) {
					
					depot_id=depot_id.trim();
					
					document.frm_opt.depots_id.value = depot_id;
					document.frm_opt.submit();
	}
	
</script>

<form name="frm_opt" action="<?php echo base_url().'control/manage-depots/edit'; ?>" method="POST">
	<input type="hidden" name="depots_id" id="depots_id" value="">
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
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Depot has been successfully added.';
				echo '</div>';
			    }
			    if($flash_message == 'reg_error'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to add user';        
				echo'</div>';
			    }
			    
			    if($flash_message == 'error_option'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to delete county. Please try again.';        
				echo'</div>';
		    
			    }
			    
			    if($flash_message == 'option_updated')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Depot has been successfully updated.';
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
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Option has been successfully updated.';
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
						All Depots
					</header>
					<div class="panel-body">
						<div class="clearfix">
									   <div class="btn-group">
								<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/manage-depots/add';">
											   <a href="javascript:void(0);">Add Depots<i class="fa fa-plus"></i></a>
										  </button>
								
									   </div>
						</div>
			    <div class="space15"></div>
			    <br>
			    
				<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
					<!-- <table class="table table-bordered table-striped table-condensed">-->
					<table  class="table table-bordered table-striped table-condensed" id="dynamic-table">
						<thead>
							<tr>
								<th>First name</th>
								<th>Last name</th>
								<th>Email</th>
								<th>Company address</th>
								<th>Edit</th>
								<!--<th>Delete</th>-->
							</tr>
						</thead>
					<tbody>
					<?php
						if(count($all_depot)>0)
						{
							
							foreach($all_depot as $depot)
							{
						?>
								<tr>
									<td><?php echo (isset($depot['first_name'])) ? ucfirst($depot['first_name']) : 'N/A';?></td>
									<td><?php echo (isset($depot['last_name'])) ? ucfirst($depot['last_name']) : 'N/A';?></td>
									<td><?php echo (isset($depot['email'])) ? $depot['email'] : 'N/A';?></td>
									<td><?php echo (isset($depot['company_address']['address'])) ? $depot['company_address']['address'] : 'N/A';?></td>
									<td><a href="javascript:void(0)" onclick="set_depot_id('<?php echo $depot['_id']; ?>');"><i class="fa fa-pencil"></i></a></td>
									<!--<td><a href="javascript:void(0)" onclick="remove_main('<?php echo base_url().'Data_form_controller/remove_all_cat_field/'.addslashes($depot['id']) ?>')"><i class="fa fa-trash-o"></i></a></td>-->
								</tr>
						<?php
							}
						}
						else
						{
						?>
									<tr>
										<td class="center "></td>
										<td class="center "></td>
										<td class="center ">No result found...</td>
										<td class="center "></td>
										<td class="center "></td>
									</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>  