<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for Listing of all categories.
|-->
	<section id="main-content">
		<section class="wrapper">
  
		<?php
			//flash messages
			$flash_message=$this->session->flashdata('flash_message');
			if(isset($flash_message)){
		
			    if($flash_message == 'success_option')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> County has been successfully deleted.';
				echo '</div>';
			    }
			    if($flash_message == 'error_optionvalue'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in Option Value. Please try again.';        
				echo'</div>';
			    }
			    
			    if($flash_message == 'error_option'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to delete county. Please try again.';        
				echo'</div>';
		    
			    }
			    
			    if($flash_message == 'option_deleted')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Option has been successfully deleted.';
				echo '</div>';
			    }
			    if($flash_message == 'option_not_deleted'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in deletion. Please try again.';        
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
						All forms
					</header>
					<div class="panel-body">
						<div class="clearfix">
									   <div class="btn-group">
								<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/data-forms/add';">
											   <a href="javascript:void(0);">Add Form<i class="fa fa-plus"></i></a>
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
								<th>Form name</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
					<tbody>
					<?php
						if(count($all_forms)>0)
						{
							
							foreach($all_forms as $forms)
							{
						?>
								<tr>
									<td><?php echo (isset($forms)) ? ucfirst($forms) : 'N/A';?></td>
									<td><a href="<?php echo base_url().'control/data-forms/edit/'.$forms ?>"><i class="fa fa-pencil"></i></a></td>
									<td><a href="javascript:void(0)" onclick="remove_main('<?php echo base_url().'Data_form_controller/remove_all_cat_field/'.addslashes($forms) ?>')"><i class="fa fa-trash-o"></i></a></td>
								</tr>
						<?php
							}
						}
						else
						{
						?>
							<tr>
								<td class="center"></td>
								<td class="center"><?php echo 'No result found...'; ?></td>
								<td class="center"></td>
							</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>  