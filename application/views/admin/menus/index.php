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
			<div class="row">
				<div class="col-sm-12">
					<section class="panel">
						<header class="panel-heading">
							All menus
						</header>
						<div class="panel-body">
							<div class="clearfix">
								<div class="btn-group">
									<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/manage-menus/add';">
										<a href="javascript:void(0);">Add Menus<i class="fa fa-plus"></i></a>
									</button>
									
								</div>
							</div>
				    <div class="space15"></div>
				    <br>
				    
					<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
						<table  class="table table-bordered table-striped table-condensed" id="dynamic-table">
							<thead>
								<tr>
									<th>Menu Type</th>
									<th>Total Menus</th>
									<th>View</th>
								</tr>
							</thead>
						<tbody>
						<?php
							if(count($all_admin_menus) > 0 || count($all_site_menus) > 0)
							{
								if(!empty($all_admin_menus))
									echo '<tr>
											<td>Admin Menus </td>
											<td>'.count($all_admin_menus).' menu(s)</td>
											<td><a href="'.base_url().'control/manage-menus/admin" ><i class="fa fa-eye"></i></a></td>
										</tr>';
										
								if(!empty($all_site_menus))
									echo '<tr>
											<td>Menu Type</td>
											<td>'.count($all_site_menus).' menu(s)</td>
											<td><a href="'.base_url().'control/manage-menus/site" ><i class="fa fa-eye"></i></a></td>
										</tr>';
							} else {
							?>
								<tr>
									<td class="center "></td>
									<td class="center ">No menu added yet ...</td>
									<td class="center "></td>
								</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>  