	<?php
		if(!empty($country_lists)){
			foreach($country_lists as $country){ $auto_search_data_arr[] =  htmlentities(ucfirst(strtolower(str_replace("'", "", $country['name'])))); }
			$auto_search_data 	= json_encode($auto_search_data_arr);
		}
	?>
	
	<!--Includng all necessary css and js for map-->
	<link href="<?php echo assets_url('site/css/jquery.mCustomScrollbar.min.css') ?>" rel="stylesheet">
	<script type="text/javascript" src="<?php echo assets_url('site/js/jquery.mCustomScrollbar.min.js') ?>"></script>
	
	<script>
		$(document).ready(function () {
			$(".country-list").mCustomScrollbar();
			
			$(".country-nav a").click(function(event){
			    var actPos = $(this).attr('href');
			    $(".country-list").mCustomScrollbar('scrollTo', $(actPos).position().top-1);
			    $(".country-nav a").not($(this)).removeClass('active');
			    $(this).addClass('active');
			});
			
			var auto_search_data_org = '<?php echo $auto_search_data; ?>';
			var auto_search_data  	= $.parseJSON(auto_search_data_org);
			var auto_search_data1 	= [];
			
			if(typeof(auto_search_data) != "undefined")
			{
				$(".tags").autocomplete({
					source: function(req, responseFn) {
						var re = $.ui.autocomplete.escapeRegex(req.term);
						var matcher = new RegExp( "^" + re, "i" );
						var a = $.grep( auto_search_data, function(item,index){
							return matcher.test(item);
						});
						
						responseFn( a );
					},
					select: function( event, ui ) {
						var sec_country = ui.item.label;
						console.log(sec_country);
						
						sec_country 	 = sec_country.replace('"', '_');
						sec_country 	 = sec_country.replace("'", '_');
						sec_country 	 = sec_country.replace(' ', '_');
						sec_country	 = sec_country.toLowerCase();
						
						//console.log(sec_country);
						
						var target 	= $('#countries_list'); 
						if(target.length) target.mCustomScrollbar("scrollTo", '#'+sec_country);
					}
				});
			}
		});
		
		function select_all(args) {
			$("#countries_list :input").each(function() {
				var id 	= $(this).attr('id');
				
				if (args == 1) $('#'+id).attr('checked', 'checked');
				else 		$('#'+id).removeAttr('checked');
				
				$('#'+id).trigger("changed");
			});
		}
	</script>
	
	<div data-role="page" id="signupPage">
		<div data-role="main" class="ui-content notif-content">
			<div class="close-container">
				<div class="popup-wrap">
					<div class="user-top">
						<div class="user-img"><img src="<?php echo assets_url('site/images/user-img.jpg') ?>" alt="user-img" /></div>
						<h3>David James</h3>
					</div>
					<div class="popup-form">
						<div class="terms">
							<div class="terms-scroll custom-scrollbar">
								<div class="terms-content">
									<p class="big-text">408 Miles - Fort Lauderdale <br />
									Deliver By: 1st June 2016 <br />
									12 Tonnes / 3 Pallets <br />
									ForkLift Required</p>
								</div>
							</div>
						</div>
						<div class="popup-btns">
							<input type="button" value="SEND MESSAGE" class="submit-leg" data-role="none" />
							<input type="button" value="SUBMIT LEG" class="submit-leg" data-role="none" />
							<input type="button" value="SUBMIT QUOTE" class="submit-leg" data-role="none" />
						</div>
					</div>
				</div>
			</div>
			<a href="<?php echo base_url(); ?>" class="cancel-signup" data-ajax="false" >
				<img src="<?php echo assets_url('site/images/cross.png') ?>" alt="cross" />
			</a>
			<div class="signup-top country-top"> <span>Choose Countries</span> </div>
			
			<div class="choose-country-block">
				<form name="country_settings" id="country_settings" action="" data-ajax="false" method="post">
					<div class="search-country">
						<input type="text" data-role="none" class="search-country-input tags" placeholder="Search country" />
						<button data-role="none" class="search-country-button" type="submit"><img src="<?php echo assets_url('site/images/search-country-ico.png') ?>" alt="search-country-ico" /></button>
					</div>
					<div class="select-deselect">
						<a href="javascript:void(0)" onclick="select_all(1)" data-role="none">SELECT ALL</a>
						<span class="divider"></span>
						<a href="javascript:void(0)" onclick="select_all(0)" data-role="none">DESELECT ALL</a>
					</div>
					<div class="country-list-wrap">
						<div class="country-nav">
							<a href="#a" id="dt-a" data-target="a" data-role="none" class="active">A</a>
							<a href="#b" id="dt-b" data-target="b" data-role="none">B</a>
							<a href="#c" id="dt-c" data-target="c" data-role="none">C</a>
							<a href="#d" id="dt-d" data-target="d" data-role="none">D</a>
							<a href="#e" id="dt-e" data-target="e" data-role="none">E</a>
							<a href="#f" id="dt-f" data-target="f" data-role="none">F</a>
							<a href="#g" id="dt-g" data-target="g" data-role="none">G</a>
							<a href="#h" id="dt-h" data-target="h" data-role="none">H</a>
							<a href="#i" id="dt-i" data-target="i" data-role="none">I</a>
							<a href="#j" id="dt-j" data-target="j" data-role="none">J</a>
							<a href="#k" id="dt-k" data-target="k" data-role="none">K</a>
							<a href="#l" id="dt-l" data-target="l" data-role="none">L</a>
							<a href="#m" id="dt-m" data-target="m" data-role="none">M</a>
							<a href="#n" id="dt-n" data-target="n" data-role="none">N</a>
							<a href="#o" id="dt-o" data-target="o" data-role="none">O</a>
							<a href="#p" id="dt-p" data-target="p" data-role="none">P</a>
							<a href="#q" id="dt-q" data-target="q" data-role="none">Q</a>
							<a href="#r" id="dt-r" data-target="r" data-role="none">R</a>
							<a href="#s" id="dt-s" data-target="s" data-role="none">S</a>
							<a href="#t" id="dt-t" data-target="t" data-role="none">T</a>
							<a href="#u" id="dt-u" data-target="u" data-role="none">U</a>
							<a href="#v" id="dt-v" data-target="v" data-role="none">V</a>
							<a href="#w" id="dt-w" data-target="w" data-role="none">W</a>
							<a href="#x" id="dt-x" data-target="x" data-role="none">X</a>
							<a href="#y" id="dt-y" data-target="y" data-role="none">Y</a>
							<a href="#z" id="dt-z" data-target="z" data-role="none">Z</a>
						</div>
						<div class="country-list" id="countries_list">
							<ul>
								<?php
									if(!empty($country_lists))
									{
										$all_user_countries		= isset($user_job_countries['countries']) ? $user_job_countries['countries'] : array();
										$search 				= array('"', "'", ' '); $replace = array('', '', '_');
										
										foreach($country_lists as $k => $country)
										{
											$current_coun_word1 = $current_coun_word2 = '';
											
											$current_coun_word1 = substr($country['name'], 0, 1);
											$current_coun_word1 = strtolower($current_coun_word1);
											
											$current_coun_word2 = (isset($country_lists[$k+1]['name'])) ? substr($country_lists[$k+1]['name'], 0, 1) : '';
											$current_coun_word2 = strtolower($current_coun_word2);
											
											if($k > 0)
											{
												$country_id 	= strval($country['_id']);
												if(in_array($country_id, $all_user_countries))
													$selected = 'checked';
												else
													$selected = '';
												
												if($current_coun_word1 != $current_coun_word2)
													echo '</ul></li> <li id="'.$current_coun_word2.'"><ul>';
												else
													echo '<li class="guranteed-row" id="'.str_replace($search, $replace, strtolower($country['name'])).'">
															<input type="checkbox" '.$selected.' name="user_countries[]" id="country_'.$k.'" value="'.strval($country['_id']).'" data-role="none" />
															<label for="country_'.$k.'">'.ucfirst($country['name']).'</label>
														</li>';
											}
											else{
												if($k != (count($country_lists) - 1))
													echo '<li id="'.$current_coun_word1.'"><ul>';
											}
										}
									}
								?>
							</ul>
						</div>
					</div>
					<div class="country-submit">
						<input type="submit" class="submit-leg" data-role="none" value="DONE" />
					</div>
				</form>
			</div>
		</div>
	</div>
