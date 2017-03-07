<script src="http://ec2-52-39-45-240.us-west-2.compute.amazonaws.com/assets/site/js/jquery-1.11.3.min.js"></script>
<!--//"images_sbs_last_item_fill_color": 	"99,93,93",
//"images_sbs_last_item_border_color": 	"145,133,133",-->
<script>
	var ValueToPass 	= '{"bins": [{"w":"10","h":"10","d":"10","max_wg":"0","id":"Bin1"}],"items": [{"w":"5","h":"3","d":"2","q":"2","vr":"1","wg":"0","id":"Item1"},{"w":"3","h":"3","d":"3","q":"3","vr":"1","wg":"0","id":"Item2"}],"username":"abhishekesolz","api_key":"36e57e6c56530f3f7717004d435f4a0d","params": {"images_background_color":"5,14,45","images_bin_border_color":"186,186,186","images_bin_fill_color":"255,255,255","images_item_border_color":"59,59,59","images_item_fill_color":"255,255,255","images_item_back_border_color":"0,0,0","images_width":"250","images_height":"250","images_source":"file","images_sbs":"1","stats":"1","item_coordinates":"1","images_complete":"1","images_separated":"1"}}';
	
	var urlpass 		= 'http://global-api.3dbinpacking.com/packer/pack?query='+ValueToPass.toString();
	
	$.ajax({ 
		type: "GET",
		url: decodeURIComponent((urlpass+'').replace(/\+/g, '%20')),
		cache: false,
		success: function(data){
			$("#result").html(data);
		}
	});
</script>
<div id="result"></div>