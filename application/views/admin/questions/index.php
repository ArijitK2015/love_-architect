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
		
			    if($flash_message == 'reg_success')
			    {
				echo '<div class="alert alert-success">';
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Merchant has been successfully added.';
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
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Merchant has been successfully updated.';
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
				var r = confirm('Confrim to delete this data.');
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
						All Questions
					</header>
					<div class="panel-body">
						<div class="clearfix">
							<div class="btn-group">
								<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/manage-questions/add';">
									<a href="javascript:void(0);">Add Question<i class="fa fa-plus"></i></a>
								</button>
							</div>
						</div>
						<div class="space15"></div>
					<br>
						
					<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">	
						<table  class="table table-bordered table-striped table-condensed" id="dynamic-table">
							<thead>
								<tr>
									<th>Question</th>
									
									<th>Category</th>
									<th>Answer Type</th>
									<th>Added On</th>
									<th>Status</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
						<tbody>
						<?php
							if(count($all_contents)>0)
							{
								foreach($all_contents as $content)
								{
							?>
									<tr>
										<td><?php echo (isset($content['title'])) 	? htmlentities(mb_substr($content['title'], 0, 50, 'UTF-8')) : 'N/A'; ?></td>
										<td><?php echo  (isset($content['category_title'])) ? htmlentities($content['category_title']) : '';   ?></td>
										<td>
										<?php
										$ans_type_id =  (isset($content['ans_type'])) ? $content['ans_type'] : '' ;
										$ans_type= '';
										
										if($ans_type_id =='1')
										{ $ans_type = 'Textbox'; }
										elseif($ans_type_id =='2')
										{ $ans_type = 'Radio Button'; }
										else
										{ $ans_type = 'Checkbox'; }
										
										echo  $ans_type;
										
										?>
										</td>
										<td><?php echo ( isset($content['added_on']) && $content['added_on'] !='') ?  date('M d, Y', strtotime($content['added_on'])) : '' ; ?></td>
										<td><?php echo ( isset($content['status']) && $content['status'] =='1') ? 'Active' : 'Inactive' ; ?></td>
										<td><a href="<?php echo base_url().'control/manage-questions/edit/'.strval($content['_id']) ?>"><i class="fa fa-pencil"></i></a></td>
										<td><a href="javascript:void(0)" onclick="remove_main('<?php echo base_url().'control/manage-questions/delete/'.strval($content['_id']) ?>')"><i class="fa fa-trash-o"></i></a></td>
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
									<td class="center "></td>
									<td class="center ">No result found...</td>
									<td class="center "></td>
									<td class="center "></td>
									<td class="center "></td>
								</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>  