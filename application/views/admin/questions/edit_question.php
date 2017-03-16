<style>
div#option_value {
    padding: 26px;
    border: 1px solid #d6cbcb;
    margin: 40px;
}
.each-val-sec.col-sm-9{
	padding-bottom: 10px;
}
.required {
	color: #FF0000;
	font-weight: bold;
}
.inp-form{
		width: 65%;
		height: 34px;
		font-size: 14px;
		color: #555;
		background-color: #fff;
		border: 1px solid #ccc;
		border-radius: 4px;
		margin:5px;
		padding: 0 10px 0 10px;
	}
	.border {
		border-top: 1px solid #d6cbcb;
		padding-top: 10px;
}
.error{
	color: #b94a48;
	font-weight: normal;
}
</style>

<?php
$category_id="";
	if($this->uri->segment(4)!="")
	{
		 $category_id=$this->uri->segment(4);
	}
?>

	<section id="main-content">
		<section class="wrapper">
			<!-- page start-->
			<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<header class="panel-heading">
							Edit Question
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " name="addoption_from" enctype="multipart/form-data"  id="addoption_from" method="post"  action="<?php echo site_url("control").'/manage-questions/edit/'.$this->uri->segment(4) ; ?>">
								
								<div class="form-group ">
									   <label for="cat_select" class="control-label col-lg-3"> Category:</label>
									   <div class="col-lg-6">
										   <select class="form-control"  name="category_id"  id="category_id"  required >
											<option value="">Select Category</option>
											<?php
										    if(isset($category_det) && count($category_det)>0)
										    {
												foreach($category_det as $k=>$v)
										    {
										    ?>
										     <option value="<?php echo strval($v['_id']); ?>"   <?php if(isset($question_det[0]['category_id']) && $question_det[0]['category_id'] == $v['_id']) echo 'selected';  ?>>
														<?php echo $v['title']; ?>
														</option>
										    <?php
											}
											}
										    ?>
										   </select>
									   </div>		
								</div>			
								<div class="form-group ">
										<label for="firstname" class="control-label col-lg-3">Question:</label>
										<div class="col-lg-6">
											<input class="form-control" name="ques_title" id="ques_title" value="<?php echo   (isset($question_det[0]['title'])) ? htmlentities($question_det[0]['title']) : '';      ?>" type="text" required />
										</div>
										
									</div>
								

								    <div class="form-group ">
									   <label for="cat_select" class="control-label col-lg-3">Answer Type:</label>
									   <div class="col-lg-6">
										   <select disabled class="form-control"  name="type_id"  id="type_id"   >
												<?php  $ans_type= isset($ans_type_id) ? $ans_type_id : '';  ?>
												<option value='1'<?php  if($ans_type=='1') echo 'selected';  ?>>Textbox</option>
												<option value='2'<?php  if($ans_type=='2') echo 'selected';  ?>>Radio Button</option>
												<option value='3'<?php  if($ans_type=='3') echo 'selected';  ?>>Checkbox</option>
												
											</select>
                                          
									   </div>
								    </div>
									
									<div class="form-group ">
										<label for="firstname" class="control-label col-lg-3">Is Required:</label>
										<div class="col-lg-1">
											
										    <input type="checkbox" class="form-control" name="is_required" id="is_required" value="1"<?php if(isset($question_det[0]['is_required']) && $question_det[0]['is_required']=='1') echo 'checked';  ?>  >
										 
										</div>
										
									</div>
									
									
						<?php
                        $allFields=$question_ans_det;
                        
						$count_fields	=  (isset($allFields) && count($allFields)>0) ? count($allFields) : 0;
						$style			= ($count_fields>0 && $ans_type!='1' ) ? '' : 'style="display: none;"';
                       
                        
						?>
						<input type="hidden" name="hid_count" id="hid_count" value="<?php echo $count_fields;?>">
						  <div class="row" id="option_value" <?php echo $style; ?> >
							<div class="col-sm-12" id="more_opt_sec">
								<?php
                                if($ans_type !='1')
                                {
								if(( isset($allFields) ) && ( count($allFields) > 0 ))
								{
									foreach($allFields as $key=>$allField)
									{ ?>
										<div class="row <?php echo ($key!=0) ? 'border' : ''; ?>" id="more_opt<?php echo $key+1; ?>" >
											<div class="col-sm-12">
										       <label for="option_title" class="control-label col-lg-2">Title:</label>
												<input type="text" placeholder="Title" class="inp-form" name="option_title[<?php echo $key+1;?>]" id="option_title<?php echo $key+1; ?>" value="<?php echo htmlentities($allField['title']); ?>" required >
												<br>
												
												
											</div>
											<div class="col-sm-12">
												<label for="option_val" class="control-label col-lg-2">Score:</label>
												<input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Score" class="inp-form" style="width: 20%;" name="option_val[<?php echo $key+1;?>]" id="option_val<?php echo $key+1; ?>" value="<?php echo $allField['score']; ?>" required >
											   
											    <?php
												if($key!=0)
												{ ?>
												<a onclick="removeOptionValue(<?php echo $key+1; ?>);" class="btn btn-danger">Remove</a>
												<?php	
												}else{ ?>
													<a onclick="addOptionValue();" class="btn btn-warning">Add Option Value</a>
													<?php
												}
												?>
											
											</div>
											
										</div>
									<?php	
									}
								}else{
								?>
									<div class="row" id="more_opt1" >
										<div class="col-sm-12">
									        <label for="option_title" class="control-label col-lg-2">Title:</label>
											<input type="text" placeholder="Title" class="inp-form" name="option_title[1]" id="option_title1" value="" required >
											<br>
											
											
										</div>
										<div class="col-sm-12">
											<label for="option_val" class="control-label col-lg-2">Score:</label>
											<input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Score" style="width: 20%;" class="inp-form" name="option_val[1]" id="option_val1" value="" required >
										    <a onclick="addOptionValue();" class="btn btn-warning">Add Option Value</a>
										</div>
										
									</div>
								<?php
								}
                                
                                }
                                
								?>
							</div>
								
						  </div>
                        
                           <?php
                            if($ans_type =='1')
                            {
                                
                               $txtarea_style= ($count_fields>0 && $ans_type =='1') ?  'style="padding: 26px;border: 1px solid #d6cbcb;margin: 40px;"' : 'style="display: none;"'  ;  
                           ?>
                           <div class="row"  id="txtbox_div" <?php echo $txtarea_style;  ?> >
						         <div class="col-sm-12" id="more_opt_sec">
								<div class="row" id="more_opt1" >
									<div class="col-sm-12">
										<label for="option_title_txt" class="control-label col-lg-2">Label:</label>
										<input type="text" placeholder="Label" class="inp-form" name="option_title_txt" id="option_title_txt" value="<?php echo isset($allFields[0]['title']) ? htmlentities($allFields[0]['title']) :''; ?>" required >
										<br>
										
										
									</div>
									<div class="col-sm-12">
										<label for="option_val_txt" class="control-label col-lg-2">Score:</label>
										<input type="text" style="width: 20%;" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Score" class="inp-form" name="option_val_txt" id="option_val_txt" value="<?php echo isset($allFields[0]['score']) ? $allFields[0]['score'] : ''; ?>"  >
									</div>
									
								</div>
							</div>
										
						</div>
                         <?php
                        
                            }
                        ?>
                        
                        
                        
                        
						  	<div class="form-group ">
										<label for="email" class="control-label col-lg-3">Status</label>
										<div class="col-lg-6">
											<select required name="status" id="status" style="width:330px"  class=" form-control">
												<option value="1"<?php if(isset($question_det[0]['status']) && $question_det[0]['status']==1 ) echo 'selected';   ?>>Active</option>
												<option value="0"<?php if(isset($question_det[0]['status']) && $question_det[0]['status']==0 ) echo 'selected';   ?>>Inactive</option>
											</select>
										</div>
									</div>
								    <div class="form-group">
									   <div class="col-lg-offset-3 col-lg-6">
										  <button class="btn btn-primary" type="submit" id="check_now">Save</button>
										  <button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>control/manage-questions';">Cancel</button>
									   </div>
								    </div>
								</form>
	  
					  
							</div>
						</div>
					</section>
				</div>
			</div>
		    <!-- page end-->
		</section>
    </section>

<script src="<?php echo base_url();?>assets/admin/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/jquery.validate.min.js"></script>
<script>
	$(document).ready(function(){
		$('#check_now').click(function(){
			$("#addoption_from").validate();
			if($("#addoption_from").valid() == true)
			{
				 $("#type_id").removeAttr("disabled");
			}
		});
	});    
	//$('#type_id').bind('change', function() {
	//		var fld_type	= $('#type_id').val();
	//		if(fld_type=='3' || fld_type=='4' || fld_type=='5')
	//		{
	//			$('#option_value').show();
	//		}else{
	//			$('#option_value').hide();
	//		}
	//	});
				
		
		function addOptionValue() {
			var count = (parseInt($('#hid_count').val())+parseInt(1));
			var html='';
			//alert(count);
			html='<div class="row border" id="more_opt'+count+'" >'+
									'<div class="col-sm-12">'+
									   '<label for="option_title" class="control-label col-lg-2">Title:</label>'+
										'<input type="text" placeholder="Title" class="inp-form" name="option_title['+count+']" id="option_title'+count+'" value="" required >&nbsp;'+
										'<br>'+
										
									'</div>'+
									'<div class="col-sm-12">'+
									'<label for="option_val" class="control-label col-lg-2">Score:</label>'+
										'<input type="text" oninput="num_only(this.id)" placeholder="Score" style="width: 20%;" class="inp-form" name="option_val['+count+']" id="option_val'+count+'" value="" required >'+
									'<a onclick="removeOptionValue('+count+');" class="btn btn-danger">Remove</a>'+
									'</div>'+
									
								'</div>';
				$('#more_opt_sec').append(html);
				$('#hid_count').val(count);
				count++;
		}
		function removeOptionValue(c) {
			$('#more_opt'+c).remove();
			var hid_count=$('#hid_count').val()-1;
			$('#hid_count').val(hid_count);
		}
		function num_only(v)
		{
			//return v.replace(/[^0-9]/g, '');
			var new_val;
		   new_val = document.getElementById(''+v).value.replace(/[^0-9]/g, '');
			 document.getElementById(''+v).value=new_val;
		}
		
    </script>