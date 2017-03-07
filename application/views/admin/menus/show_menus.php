<!--|
| Copyright © 2014 by Esolz Technologies
| Author :  debojit.talukdar@esolzmail.com
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for Listing of all categories.
|-->
	<script>
		
		$(document).ready(function() {

			$('#dynamic-table-custom').dataTable( {
			    "aaSorting": [[ 4, "desc" ]]
			} );
		});
		
		function set_menu_id(menu_id) {
			menu_id=menu_id.trim();
			document.frm_opt.menus_id.value = menu_id;
			document.frm_opt.submit();
		}
		
	</script>
	
	
	<form name="frm_opt" action="<?php echo base_url().'control/manage-menus/edit'; ?>" method="POST">
		<input type="hidden" name="menu_id" id="menu_id" value="">
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
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Menu has been successfully added.';
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
				echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Menu has been successfully updated.';
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
						All Menus
					</header>
					<div class="panel-body">
						<div class="clearfix">
							<div class="btn-group">
								<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/manage-menus/add';">
									<a href="javascript:void(0);">Add Menu<i class="fa fa-plus"></i></a>
							    </button>
							</div>
						</div>
			    <div class="space15"></div>
			    <br>
			    
				<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">	
					<table  class="table table-bordered table-striped table-condensed" id="dynamic-table-custom">
						<thead>
							<tr>
								<th>Menu title</th>
								<th>Parent Menu</th>
								<th>Menu Location</th>
								<th>Menu Url</th>
								<th>Menu Icon</th>
								<th>Order</th>
								<th>Status</th>
								<th>Options</th>
							</tr>
						</thead>
					<tbody>
					<?php
						if(count($all_menus)>0)
						{
							foreach($all_menus as $m => $menu)
							{
								$menu_id 	= (isset($menu['_id'])) ? strval($menu['_id']) : $m;
								$location = $menu_url = $menu_icon = '';
								if($menu['menu_type'] == 0)
									$location = ($menu['menu_location'] == 0) ? 'Left section' : '';
								else $location = ($menu['menu_location'] == 1) ? 'Header menu' : 'Footer menu';
									
								if($menu['url_type'] == 0)
									$menu_url = ($menu['menu_type'] == 0) ? 'control/'.$menu['url'].'/'.$menu['parameters'] : $menu['url'];
								else $menu_url	= $menu['url'];
									
								if($menu['use_image'] == 0) $menu_icon = $menu['icon_class'];
								else $menu_icon = $menu['icon_image'];
						?>
								<tr>
									<td><?php echo (isset($menu['title'])) 		? ucfirst($menu['title']) : '';?></td>
									<td>&nbsp;</td>
									<td><?php echo (!empty($location)) ? $location : 'N/A'; ?></td>
									<td><?php echo (!empty($menu_url)) ? $menu_url : 'N/A'; ?></td>
									<td>
										<?php
											if($menu['use_image'] == 0 && (!empty($menu_icon))) echo '<i style="font-size: 22px;" class="'.$menu_icon.'"></i>';
											if($menu['use_image'] == 1 && (!empty($menu_icon))) echo '<img style="width: 30px" src="'.base_url().'assets/site/images/'.$menu_icon.'" alt="menu_image" />';
										?>
									</td>
									<td>
										<div class="spinit">
											<div class="input-group" style="width:150px;">
												<div class="spinner-buttons input-group-btn">
													<button type="button" onclick="change_order('order_val_<?php echo $menu_id ?>', 'order_show_<?php echo $menu_id ?>', 1)" class="btn spinner-up btn-primary"><i class="fa fa-angle-up"></i></button>
												</div>
												<input type="text" style="text-align: center" class="spinner-input form-control" name="order_val_<?php echo $menu_id ?>" id="order_show_<?php echo $menu_id ?>" readonly="readonly" value="<?php echo (isset($menu['order_no']) && (!empty($menu['order_no']))) ? $menu['order_no'] : $m+1; ?>" />
												<div class="spinner-buttons input-group-btn">
													<button type="button" onclick="change_order('order_val_<?php echo $menu_id ?>', 'order_show_<?php echo $menu_id ?>', 0)" class="btn spinner-down btn-warning"><i class="fa fa-angle-down"></i></button>
												</div>
											</div>
										</div>
									</td>
									<td><?php echo (isset($menu['status']) && $menu['status'] == '1') ? 'Active' : 'inactive'; ?></td>
									<td>
										<a href="javascript:void(0)" onclick="set_menu_id('<?php echo $menu['_id']; ?>');"><i class="fa fa-pencil"></i></a>
										<a href="javascript:void(0)" onclick="remove_main('<?php echo base_url().'control/manage-menus/delete/'.$menu['_id']; ?>');"><i class="fa fa-transh"></i></a>
									</td>
								</tr>
						<?php
							}
						} else { ?>
							<tr>
								<td class="center "></td>
								<td class="center "></td>
								<td class="center "></td>
								<td class="center ">No result found...</td>
								<td class="center "></td>
								<td class="center "></td>
								<td class="center "></td>
							</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		
		