<!--|
| Copyright © 2014 by Esolz Technologies
| Author : Arijit Modak
|
|	http://www.esolz.net/
|
| All rights reserved. This page is used for listing of all email templates.
|--> 
<section id="main-content">
   <section class="wrapper">
	<?php
  
		$flash_message=$this->session->flashdata('flash_message');
		if(isset($flash_message))
        {
	
                    if($flash_message == 'subadmin_added')
                    {
                    echo '<div class="alert alert-success">';
                    echo '<i class="icon-ok-sign"></i><strong>Success!</strong>  New Subadmin is successfully added.';
                    echo '</div>';
                    }
                    if($flash_message == 'subadmin_not_added')
                    {
                    echo '<div class="alert alert-error">';
                    echo '<i class="icon-ok-sign"></i><strong>Error!</strong>  New Subadmin could not be added.';
                    echo '</div>';
                    }
                    if($flash_message == 'subadmin_updated')
                    {
                    echo '<div class="alert alert-success">';
                    echo '<i class="icon-ok-sign"></i><strong>Success!</strong>  Subadmin is successfully updated.';
                    echo '</div>';
                    
                    }
                    if($flash_message == 'subadmin_not_updated'){
                    echo'<div class="alert alert-error">';
                    echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
                    echo'</div>';
                    
                    }
					
					 if($flash_message == 'subadmin_deleted')
					 {
					 echo '<div class="alert alert-success">';
					 echo '<i class="icon-ok-sign"></i><strong>Success!</strong>  Subadmin  has been successfully deleted.';
					 echo '</div>';
				   
					 }
					 if($flash_message == 'subadmin_not_deleted')
					 {
					 echo '<div class="alert alert-success">';
					 echo '<i class="icon-ok-sign"></i><strong>Error!</strong> . Please try again.';
					 echo '</div>';
					 }
                    
                     if($flash_message == 'error'){
                     echo'<div class="alert alert-error">';
                     echo'<i class="icon-remove-sign"></i><strong>Error!</strong> . Please try again.';        
                     echo'</div>';
                     }
                   
        }
	?>
		  <script>
			function check_confirm(user_id)
			{
			   var del = window.confirm('Are you sure want to delete?');
			   if (del == true)
			   {
				  window.location.href = '<?php echo site_url('control'); ?>/manage-subadmin/delete/'+user_id;
               }
			}
			
		 </script>
		 
		 <!-- settings changer -->
	<div class="row">
	    <div class="col-sm-12">	
            <section class="panel">
                        <header class="panel-heading">
                            Subadmin Management
                        </header>
       <div id="d2">
		 <div class="panel-body">  
               <div class="clearfix">
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/manage-subadmin/add';">
                        <a href="javascript:void(0);">Add Subadmin<i class="fa fa-plus"></i></a>
                        </button>  
                    </div>
                </div>
               
               <div class="panel-body">
                    <section id="unseen">
                        <div class="adv-table">
                            <table  class="display table table-bordered table-striped" id="dynamic-table">
                              <thead>
                               <tr>
                                  <th>First Name</th>
                                  <th>Last Name</th>
								  <th>Email Address</th>
                                  <th>User Name</th>
                                  <th style="width:25px";>Profile Image</th>
                                  <th>Status</th>
                                  <td>Options</td>
                               </tr>
                              </thead>
                              <tbody>
			       <?php
									
				if((!empty($subadmin)) &&(count($subadmin)>0))
                {
                    foreach($subadmin as $row)
					{
					?>
                    <tr>
                            <td><?php echo $row['first_name'] ;?></td>
							<td><?php echo $row['last_name'] ;?></td>
                            <td><?php echo $row['email_addres'] ;?></td>
                            <td><?php echo $row['user_name'] ;?></td>
							<td><center><img src="<?php echo base_url();?>assets/uploads/subadmin_image/<?php echo $row['profile_image']; ?>" style="height:50px; width:50px"></center></td>
                            <td><?php if($row['status']=='1'){ echo "ACTIVE";} else {echo "INACTIVE";}?></td>
                            <td>
                                <a class="fa fa-edit" href="<?php echo site_url("control").'/manage-subadmin/edit/'.$row['_id']; ?>" onclick="b()" > </a>
								<a class="fa fa-trash-o" href="javascript:check_confirm('<?php echo $row['_id']?>')"></a>
						   </td>
						</div>
                </div>
            </div>   
	    </div>                               
                  </tr>
                    <?php
                    }			      
                    ?>
                    <?php
                }
                else
                {
                   echo "No Result Found";
                }
                ?>
			      </tbody>
                        </table>
                       </div>
                    </section>
	       </div>
			<?php  // echo '<div class="pagination" style="float:right">'.$this->pagination->create_links().'</div>'; ?>
         </section>
	</div>
    <!-- end main container -->
  </div>
 </section>
</section>