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
							 Add Question
						</header>
						<div class="panel-body">
							<div class="form">
								<form class="cmxform form-horizontal " name="addoption_from" enctype="multipart/form-data"  id="addoption_from" method="post" onsubmit="javascript:return chkFrm();"; action="<?php echo site_url("control").'/manage-questions/add' ; ?>">
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
										     <option value="<?php echo strval($v['_id']); ?>">
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
											<input class="form-control" name="ques_title" id="ques_title" value="" type="text" required />
										</div>
										
									</div>
								

								    <div class="form-group ">
									   <label for="cat_select" class="control-label col-lg-3">Answer Type:</label>
									   <div class="col-lg-6">
										   <select class="form-control"  name="type_id"  id="type_id"  required >
												
												<option value='1'>Textbox</option>
												<option value='2'>Radio Button</option>
												<option value='3'>Checkbox</option>
												
											</select>
									   </div>
								    </div>
									

							<input type="hidden" name="hid_count" id="hid_count" value="1">
						  <div class="row" id="option_value"  style="display:none;">
							<div class="col-sm-12" id="more_opt_sec">
								<div class="row" id="more_opt1" >
									<div class="col-sm-12">
										<label for="option_title" class="control-label col-lg-2">Title:</label>
										<input type="text" placeholder="Title" class="inp-form" name="option_title[1]" id="option_title1" value="" required >
										<br>
										
										
									</div>
									<div class="col-sm-12">
										<label for="option_val" class="control-label col-lg-2">Score:</label>
										<input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" style="width: 20%;" placeholder="Score" class="inp-form" name="option_val[1]" id="option_val1" value="" required >
									   
									     <a onclick="addOptionValue();" class="btn btn-warning">Add Option Value</a>
									</div>
									
								</div>
							</div>
								
						  </div>
						  
						  <div class="form-group ">
										<label for="firstname" class="control-label col-lg-3">Is Required:</label>
										<div class="col-lg-1">
											
										    <input type="checkbox" class="form-control" name="is_required" id="is_required" value="1"  >
										 
										</div>
										
									</div>
						  
						  
						  <div class="row" id="txtbox_div" style="padding: 26px;border: 1px solid #d6cbcb;margin: 40px;" >
						         <div class="col-sm-12" id="more_opt_sec">
								<div class="row" id="more_opt1" >
									<div class="col-sm-12">
										<label for="option_title_txt" class="control-label col-lg-2">Label:</label>
										<input type="text" placeholder="Label" class="inp-form" name="option_title_txt" id="option_title_txt" value="" required >
										<br>
										
										
									</div>
									<div class="col-sm-12">
										<label for="option_val_txt" class="control-label col-lg-2">Score:</label>
										<input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" style="width: 20%;" placeholder="Score" class="inp-form" name="option_val_txt" id="option_val_txt" value=""  >
									</div>
									
								</div>
							</div>
										
						</div>
						  
						  
						  
						  
						  	<div class="form-group ">
										<label for="email" class="control-label col-lg-3">Status</label>
										<div class="col-lg-6">
											<select required name="status" id="status" style="width:330px"  class=" form-control">
												<option value="1">Active</option>
												<option value="0">Inctive</option>
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
		});
	});    
	$('#type_id').bind('change', function() {
			var fld_type	= $('#type_id').val();
			if(fld_type=='2' || fld_type=='3' )
			{
				$('#option_value').show();
				$('#txtbox_div').hide();
				
			}else{
				$('#option_value').hide();
				$('#txtbox_div').show();
			}
		});
			
		
		
		function addOptionValue() {
			var count = (parseInt($('#hid_count').val())+parseInt(1));
			var html='';
			
			var type_id_val = $('#type_id').val();
			
			var type_id_placeholder = 'Title';
			
			var option_placeholder = 'Score';
			
			//alert(count);
			html='<div class="row border" id="more_opt'+count+'" >'+
									'<div class="col-sm-12">'+
									'<label for="option_title" class="control-label col-lg-2">Title:</label>'+
									'<input placeholder="'+type_id_placeholder+'"  type="text" class="inp-form" name="option_title['+count+']" id="option_title'+count+'" value="" required >&nbsp;'+
										'<br>'+
										
									'</div>'+
									'<div class="col-sm-12">'+
									'<label for="Score" class="control-label col-lg-2">Score:</label>'+
										'<input placeholder="'+option_placeholder+'" oninput="num_only(this.id)"  style="width: 20%;" type="text" class="inp-form" name="option_val['+count+']" id="option_val'+count+'" value="" required >'+
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