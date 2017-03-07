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
					
					page_id=page_id.trim();
					
					document.frm_opt.type_id.value = page_id;
					document.frm_opt.submit();
	}
	
</script>

<form name="frm_opt" action="<?php echo base_url().'control/pages-help-contents/edit'; ?>" method="POST">
	<input type="hidden" name="type_id" id="type_id" value="">
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
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Page help content has been successfully added.';
				echo '</div>';
			    }
			    if($flash_message == 'reg_error'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to add Page help content';        
				echo'</div>';
			    }
			    
			    if($flash_message == 'error_option'){
				echo'<div class="alert alert-error">';
				echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to delete Page help content. Please try again.';        
				echo'</div>';
		    
			    }
			    
			    if($flash_message == 'option_updated')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Page help content has been successfully updated.';
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
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Page help content has been successfully updated.';
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
						All page help content
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
								<th>Page Title</th>
								<th>Edit</th>
								<!--<th>Delete</th>-->
							</tr>
						</thead>
					<tbody>
					<?php
						if(count($all_contents)>0)
						{
							
							foreach($all_contents as $pages)
							{
						?>
								<tr>
									<td><?php echo (isset($pages['page_title'])) ? ucfirst($pages['page_title']) : 'N/A';?></td>
									<td><a href="javascript:void(0)" onclick="set_page_id('<?php echo $pages['_id']; ?>');"><i class="fa fa-pencil"></i></a></td>
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
								<td class="center"><?php echo 'No result found...'; ?></td>
							</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>  