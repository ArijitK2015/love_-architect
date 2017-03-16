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
	
                    if($flash_message == 'user_added')
                    {
                    echo '<div class="alert alert-success">';
                    echo '<i class="icon-ok-sign"></i><strong>Success!</strong>  New user is successfully added.';
                    echo '</div>';
                    }
						  if($flash_message == 'email_exist')
                    {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="icon-ok-sign"></i><strong>Error!</strong>  Email exists.New user could not be added.';
                    echo '</div>';
                    }
                    if($flash_message == 'user_not_added')
                    {
                    echo '<div class="alert alert-danger">';
                    echo '<i class="icon-ok-sign"></i><strong>Error!</strong>  New user could not be added.';
                    echo '</div>';
                    }
                    if($flash_message == 'user_updated')
                    {
                    echo '<div class="alert alert-success">';
                    echo '<i class="icon-ok-sign"></i><strong>Success!</strong>  user is successfully updated.';
                    echo '</div>';
                    
                    }
                    if($flash_message == 'user_not_updated'){
                    echo'<div class="alert alert-danger">';
                    echo'<i class="icon-remove-sign"></i><strong>Error!</strong> in updation. Please try again.';        
                    echo'</div>';
                    
                    }
					
					 if($flash_message == 'user_deleted')
					 {
					 echo '<div class="alert alert-success">';
					 echo '<i class="icon-ok-sign"></i><strong>Success!</strong>  user  has been successfully deleted.';
					 echo '</div>';
				   
					 }
					 if($flash_message == 'user_not_deleted')
					 {
					 echo '<div class="alert alert-success">';
					 echo '<i class="icon-ok-sign"></i><strong>Error!</strong> . Please try again.';
					 echo '</div>';
					 }
                    
                     if($flash_message == 'error'){
                     echo'<div class="alert alert-danger">';
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
				  window.location.href = '<?php echo site_url('control'); ?>/manage-users/delete/'+user_id;
               }
			}
			
		 </script>
		 
		 <!-- settings changer -->
	<div class="row">
	    <div class="col-sm-12">	
            <section class="panel">
                        <header class="panel-heading">
                            User Management
                        </header>
       <div id="d2">
		 <div class="panel-body">  
               <div class="clearfix">
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="location.href='<?php echo site_url('control'); ?>/manage-users/add';">
                        <a href="javascript:void(0);">Add User<i class="fa fa-plus"></i></a>
                        </button>  
                    </div>
                </div>
               
               <div class="panel-body">
                    <section id="unseen">
                        <div class="adv-table">
                            <table  class="display table table-bordered table-striped" id="dynamic-table">
                              <thead>
                               <tr>
                                  <th>Name</th>
                                  
								           <th>Email Address</th>
                                  
                                  <th style="width:25px";>Profile Image</th>
                                  <th>Status</th>
                                  <td>Options</td>
                               </tr>
                              </thead>
                              <tbody>
			       <?php
									
				if((!empty($user_det)) &&(count($user_det)>0))
                {
                    foreach($user_det as $k=>$row)
					{
					?>
                    <tr>
                            <td><?php $first_name= isset($row['first_name']) ? $row['first_name'] :'';
									$last_name=  isset($row['last_name']) ? $row['last_name'] :'';
									 echo ucfirst($first_name).' '.ucfirst($last_name);
									 ?></td>
							
                            <td><?php echo isset($row['email']) ? $row['email'] : '' ;?></td>
                           
							<td><center>
							<?php
							$profile_image = (isset($row['profile_image']) && $row['profile_image']!='') ? $row['profile_image'] : '';
							if($profile_image !='')
							{
							?>
							<img src="<?php echo base_url();?>assets/uploads/user_images/thumb/<?php echo $profile_image; ?>" style="height:50px; width:50px">
							<?php } ?>
							</center>
							</td>
                            <td><?php //if($row['status']=='1'){ echo "ACTIVE";} else {echo "INACTIVE";}
									 if($row['status']=='1')
									 {
									 ?>
									  <button type="button"  id="btn_stat_change<?php echo (string)$row['_id']; ?>" onclick="change_stat('<?php echo $row['_id'] ?>');" class="btn btn-round btn-success">Active</button>
									 <?php
									 }
									 else
									 {
									 ?>
									   <button type="button"  id="btn_stat_change<?php echo (string)$row['_id']; ?>" onclick="change_stat('<?php echo $row['_id'] ?>');" class="btn btn-round btn-danger">Inactive</button>
									 <?php
									 }
									 ?>
									 
									 
									 <i id="refresh_<?php echo (string)$row['_id']; ?>" style="display:none;margin-left: 5px;"   class="fa fa-spin fa-refresh" aria-hidden="true"></i>
									 
									 </td>
                            <td>
                                <a class="fa fa-edit" href="<?php echo site_url("control").'/manage-users/edit/'.$row['_id']; ?>" onclick="b()" > </a>
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
<script>
function change_stat(Id) {
	  // var ValueToPass 	= "?id="+Id ;
	  $('#btn_stat_change'+Id).attr('disabled','disabled');    
	  $('#refresh_'+Id).show();
	
	//  alert(Id);alert(stat);
	   var urlpass		= '<?php echo base_url(); ?>control/manage-users/change_status';
	//alert(urlpass);
	  $.ajax({ 
		   type: "POST",
		   url: urlpass,
			data: {id:Id},
		   cache: false,
		   success: function(data){
			  // alert(data);
			  
			   if(data){
					
					
				  if(data == '1') {
					$('#btn_stat_change'+Id).removeAttr('disabled');
					$('#refresh_'+Id).hide();
					$('#btn_stat_change'+Id).removeClass('btn-danger');
					$('#btn_stat_change'+Id).addClass('btn-success');
					
					$('#btn_stat_change'+Id).html('Active');
					//$('#btn_stat_change'+Id').attr('onclick')='"change_battleground_stat('+Id+',0)"';
					
				  }else{
					$('#btn_stat_change'+Id).removeAttr('disabled');
					$('#refresh_'+Id).hide();
				    $('#btn_stat_change'+Id).removeClass('btn-success');
					  $('#btn_stat_change'+Id).addClass('btn-danger');
				    $('#btn_stat_change'+Id).html('Inactive');
					//$('#btn_stat_change'+Id').attr('onclick')='"change_battleground_stat('+Id+',1)"';
				  }
			   }
		   }
	   });
	   
  }


</script>