<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for Listing of all categories.
|-->

	<script>
		function set_type_id(fleet_id) {
			fleet_id						= fleet_id.trim();
			document.frm_opt.type_id.value 	= fleet_id;
			document.frm_opt.submit();
		}
		function remove_main(url)
		{
			var r = confirm('Confrim to remove this type.')
			if(r === true)
				window.location = '<?php echo base_url() ?>control/manage-payment-types/delete/'+url;
		}
	</script>

	<form name="frm_opt" action="<?php echo base_url().'control/manage-payment-types/edit'; ?>" method="POST">
		<input type="hidden" name="type_id" id="type_id" value="">
	</form>
	<section id="main-content">
		<section class="wrapper">
			<?php
				//flash messages
				$flash_message=$this->session->flashdata('flash_message');
				if(isset($flash_message)){
				
					if($flash_message == 'insert_success')
					{
						echo '<div class="alert alert-success">';
						echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Payment Type details successfully added.';
						echo '</div>';
					}
					if($flash_message == 'insert_failed'){
						echo'<div class="alert alert-error">';
						echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to add type details. Please try again.';        
						echo'</div>';
					}
					
					if($flash_message == 'error_option'){
						echo'<div class="alert alert-error">';
						echo'<i class="icon-remove-sign"></i><strong>Error!</strong> Failed to delete county. Please try again.';        
						echo'</div>';
					}
					    
					if($flash_message == 'type_updated')
					{
						echo '<div class="alert alert-success">';
						echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Payment Type has been successfully updated.';
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
		
			<div class="row">
				<div class="col-sm-12">
					<section class="panel">
						<header class="panel-heading">
							All Payment Types
						</header>
						<div class="panel-body">
							<div class="clearfix">
								<div class="btn-group">
									<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/manage-payment-types/add';">
										<a href="javascript:void(0);">Add Payment Type<i class="fa fa-plus"></i></a>
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
											<th>Title</th>
											<th>Payment Type</th>
											<th>Edit</th>
											
										</tr>
									</thead>
									<tbody>
										<?php
											if(count($all_types)>0)
											{
												foreach($all_types as $type)
												{
													$pay_type = '';
													if(isset($type['pay_type']) && $type['pay_type']=='1'){ $pay_type = 'Credit card'; }
													if(isset($type['pay_type']) && $type['pay_type']=='2'){ $pay_type = 'Wire'; }
													if(isset($type['pay_type']) && $type['pay_type']=='3'){ $pay_type = 'Invoice'; } 
											?>
												<tr>
													<td><?php echo (isset($type['title'])) 	? ucfirst($type['title']) : 'N/A';?></td>
													<td><?php echo (isset($pay_type)) 	? ucfirst($pay_type) : 'N/A';?></td>
													<td><a href="javascript:void(0)" onclick="set_type_id('<?php echo strval($type['_id']); ?>');"><i class="fa fa-pencil"></i></a></td>
													<!--<td><a href="javascript:void(0)" onclick="remove_main('<?php echo strval($type['_id']); ?>')"><i class="fa fa-trash-o"></i></a></td>-->
												</tr>
										<?php
												}
											}
											else
											{
										?>
												<tr>
													<td class="center "></td>
													<td class="center"><?php echo 'No result found...'; ?></td>
													<!--<td class="center "></td>-->
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
		</section>
	</section>