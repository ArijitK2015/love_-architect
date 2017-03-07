$(document).ready(function() {

	//$("#dateRange").ionRangeSlider({
	//	min: 0,
	//	max: 30,
	//	from:20,
	//	postfix: " days"
	//});
	//   
	//$("#priceRange").ionRangeSlider({
	//	min: 200,
	//	max: 12564,
	//	from:800,
	//	prefix: "$"
	//});

	$('.bottom-collapse-btn').click(function(){
		$('.map-filter').toggleClass('map-filter-active');
	});
 
	$('.menu-strap, .menu-overlay').click(function(){
		$('body').toggleClass('menu-active');
	});

});