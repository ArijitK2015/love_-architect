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
	    if($flash_message == 'added')
	    {
		echo '<div class="alert alert-success">';
		echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Details has been successfully added.';
		echo '</div>';
	    }
	    if($flash_message == 'not_added'){
		echo'<div class="alert alert-danger">';
		echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in Details Value. Please try again.';        
		echo'</div>';
	    }
	    
	    if($flash_message == 'error_option'){
		echo'<div class="alert alert-error">';
		echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in Details. Please try again.';        
		echo'</div>';
	    }
	    
	    if($flash_message == 'deleted')
	    {
		echo '<div class="alert alert-success">';
		echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Details has been successfully deleted.';
		echo '</div>';
	    }
	    if($flash_message == 'not_deleted'){
		echo'<div class="alert alert-error">';
		echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in deletion. Please try again.';        
		echo'</div>';
	    }
	    
	    if($flash_message == 'updated')
	    {
		echo '<div class="alert alert-success">';
		echo '<i class="icon-ok-sign"></i><strong>Success!</strong> Details has been successfully updated.';
		echo '</div>';
	    }
	    if($flash_message == 'not_updated'){
		echo'<div class="alert alert-error">';
		echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
		echo'</div>';
	    }
	}
	$search_var		= '';
	if(isset($_REQUEST['search_by']))
	{ 
		 $search_var	= htmlentities($_REQUEST['search_by']);
	}
	?>
        <!-- page start-->
	<script type="text/javascript">
	function check_confirm(id)
	{
		var del = window.confirm('Are you sure want to delete?');
		if (del === true)
		{
			window.location.href = '<?php echo site_url('control'); ?>/profileform/delete/'+id;
		}
	}
	</script>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Profile Form Management
                    </header>
                    <div class="panel-body">
		
		    <div class="clearfix">
                                <div class="btn-group">
				    <!--<button id="editable-sample_new" class="btn btn-primary" onclick="location.href='<?php //echo site_url('control'); ?>/profileform/add';">
                                          <a href="javascript:void(0);">Add New Profile Form Field<i class="fa fa-plus"></i></a>
                                    </button>-->
                                </div>
				<form method="Post" action="<?php echo site_url("control").'/profileform';?>" class="pull-right admin">
						<!-- for search field-->
                                    <div class="btn-group">
                                        <input type="text" value="<?php echo($this->session->userdata('search_key')!='') ? $this->session->userdata('search_key'): $search_var; ?>" name="search_by" id="search_by" class="form-control">
                                    </div>
							 	<!-- for search button-->
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                    </div>
                                </form>

		    </div>
		    <div class="space15"></div>
		    <br>
		    
			<div id="editable-sample_wrapper" class="dataTables_wrapper form-inline" role="grid">		
                    <!-- <table class="table table-bordered table-striped table-condensed">-->
			<table class="table table-bordered table-striped table-condensed" >
				<thead>
                                <tr>
					<th>Form lebel</th>
					<th>Type</th>
					<th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
				<?php
				if($profile_forms)
				{
					foreach($profile_forms AS $option)
					{ ?>
						<tr>
							<td><?php echo ucfirst(htmlentities(stripslashes($option['type_lebel_eng'])));?></td>
							<td><?php echo ucfirst($option['type_name']);?></td>
							<td>
								<a href="<?php echo site_url("control").'/profileform/update/'.$option['id']; ?>"><i class="fa fa-edit"></i></a>
								<a class="fa fa-trash-o" onclick="javascript:check_confirm('<?php echo $option['id']?>')"></a>
							</td>
						<!-- Delete Modal -->
						</tr>
				<?php
					}
				}else{ ?>
					<tr>
						<td class="center" colspan="4"><?php echo 'No result found...'; ?></td>
					</tr>
				<?php
				}
				?>
                                </tbody>
                            </table>
			  </div>  
			</div>
		<div id="paginate_div" class="text-right">
		<?php echo $this->pagination->create_links(); ?>
		</div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>