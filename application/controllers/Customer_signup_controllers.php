<?php
class Customer_signup_controllers extends CI_Controller {
	
	var $password_salt 		= '12345678';
	var $system_timezone 	= 'UTC';
	var $geonames_username	= "arijit2016";
	var $linkedinApiKey		= '';
	var $linkedinApiSecret 	= '';
	
	var $get_company_det 	= '';
	var $get_company_arr 	= '';
	var $cmp_auth_name		= '';
	var $cmp_auth_id		= '';
	var $cmp_details 		= '';
	var $cmp_id			= '';
	var $settings			= '';
	var $site_title 		= '';
	var $pdesc			= '';
	var $pkeys			= '';
	var $site_logo			= '';
	var $site_favicon		= '';
	var $cmp_auth_link_id 	= '';
	var $cmp_auth_no		= '';
	var $data				= array();
	var $site_name			= '';
	var $client_secret		= '';
	var $client_id			= '';
		
		
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Home_model');
		$this->load->model('sitesetting_model');
		$this->load->model('Users_model');
		$this->load->model('User_email_model');
		$this->load->library('ImageThumb');
			
		$this->password_salt 	= $this->config->item('encryption_key');
		$this->password_salt 	= ($this->password_salt) ? $this->password_salt : '12345678';
			
		$settings_data 		= $this->sitesetting_model->get_settings();
		$this->system_timezone	= (isset($settings_data[0]['system_timezone'])) 	? $settings_data[0]['system_timezone'] 		: $this->system_timezone;
		$this->geonames_username = (isset($settings_data[0]['geonames_username'])) ? $settings_data[0]['geonames_username'] 	: $this->geonames_username;
			
		$this->linkedinApiKey	= (isset($settings_data[0]['linkedinApiKey'])) 	? $settings_data[0]['linkedinApiKey'] 		: '';
		$this->linkedinApiSecret = (isset($settings_data[0]['linkedinApiSecret'])) ? $settings_data[0]['linkedinApiSecret'] 	: '';
			
		$this->settings		= (isset($settings_data[0])) 		? $settings_data[0] : '';
		$this->get_company_det 	= $this->uri->segment('1');
		$this->get_company_arr	= explode('-', $this->get_company_det);
			
		$this->cmp_auth_name 	= isset($this->get_company_arr[0]) ? $this->get_company_arr[0] : '';
		$this->cmp_auth_id 		= isset($this->get_company_arr[1]) ? $this->get_company_arr[1] : '';
			
		//Get company details
		$this->mongo_db->where(array('cmp_auth_id' => $this->cmp_auth_id));
		$cmp_details_act 		= $this->mongo_db->get('site_users');
		$this->cmp_details		= (isset($cmp_details_act[0]) && !empty($cmp_details_act[0])) ? $cmp_details_act[0] : array();
			
		$this->client_secret	= (isset($settings_data[0]['client_secret'])) 	? $settings_data[0]['client_secret'] 	: '';
		$this->client_id		= (isset($settings_data[0]['client_id'])) 		? $settings_data[0]['client_id'] 		: '';
			
		$this->site_name		= (isset($settings_data[0]['site_name'])) 		? $settings_data[0]['site_name'] 		: '';
			
		if(!empty($this->cmp_details))
		{
			$this->cmp_id			= isset($this->cmp_details['_id']) 		? strval($this->cmp_details['_id']) 	: '';		
			$this->site_title 		= isset($this->cmp_details['site_title']) 	? $this->cmp_details['site_title'] 	: $this->settings['site_name'];
			$this->pdesc 			= isset($this->cmp_details['site_meta']) 	? $this->cmp_details['site_meta'] 		: $this->settings['meta_description'];
			$this->pkeys 			= isset($this->cmp_details['site_keyword']) 	? $this->cmp_details['site_keyword'] 	: $this->settings['meta_keywords'];
			$this->site_logo 		= isset($this->cmp_details['site_logo']) 	? $this->cmp_details['site_logo'] 		: '';
			$this->site_favicon		= isset($this->cmp_details['site_fabicon']) 	? $this->cmp_details['site_fabicon'] 	: '';
				
			if(!empty($this->cmp_auth_name) && !empty($this->cmp_auth_id))
				$this->cmp_auth_link_id	= $this->cmp_auth_name.'-'.$this->cmp_auth_id;
		}
		else
		{
			$this->cmp_auth_name = $this->cmp_auth_id =  $this->site_logo = '';
				
			$this->site_title 		= $this->settings['site_name'];
			$this->pdesc 			= $this->settings['meta_description'];
			$this->pkeys 			= $this->settings['meta_keywords'];
		}
			
		if(!empty($this->cmp_auth_link_id))
			$this->config->set_item('base_url', base_url().$this->cmp_auth_link_id) ;
			
		//if($this->session->userdata('site_is_logged_in'))
		//	redirect('dashboard');
			
		$settings					= $this->sitesetting_model->get_settings();
		$this->data['settings'] 		= $this->sitesetting_model->get_settings();
		$this->data['cmp_auth_link']	= isset($this->cmp_auth_link_id) 	? $this->cmp_auth_link_id : '';
		$this->data['cmp_auth_name']	= isset($this->cmp_auth_name) 	? $this->cmp_auth_name 	 : '';
		$this->data['cmp_auth_id']	= isset($this->cmp_auth_id) 		? $this->cmp_auth_id 	 : '';
		$this->data['cmp_details']	= isset($this->cmp_details[0]) 	? $this->cmp_details[0] 	 : array();
		$this->data['ptitle']		= $this->site_title;
		$this->data['pdesc']		= $this->pdesc;
		$this->data['pkeys']		= $this->pkeys;
		$this->data['site_logo']		= $this->site_logo;
		$this->data['site_fav_icon']	= $this->site_favicon;
	}
		
	public function index()
	{
		if($this->session->userdata('site_is_logged_in'))
			redirect('dashboard');
			
		$cmp_auth_no 						= isset($this->cmp_auth_id) 				? $this->cmp_auth_id : '';
		$cmp_auth_name 					= isset($this->cmp_details[0]['name'])  	? $this->cmp_details[0]['name'] 		: '';
			
		if($cmp_auth_no) 	$this->mongo_db->where(array('page_alias' => 'terms-conditions', 'merchant_id' => $cmp_auth_no));
		else 			$this->mongo_db->where(array('page_alias' => 'terms-conditions', 'merchant_id' => ''));
			
		$static_contents 					= $this->mongo_db->get('static_contents'); 
			
		if(!empty($static_contents)){	
			if(isset($static_contents[0]['page_content']) && ($static_contents[0]['page_content'] == ''))
			{
				$this->mongo_db->where(array('page_alias' => 'terms-conditions'));
				$static_contents 			= $this->mongo_db->get('static_contents'); 
			}
		}
		else{
			$this->mongo_db->where(array('page_alias' => 'terms-conditions'));
			$static_contents 				= $this->mongo_db->get('static_contents'); 
		}	
			
		$this->mongo_db->where(array('show_ond' => 1, 'status' => 1));
		$sizes_details 					= $this->mongo_db->get('sizes');	
		$this->data['sizes_details']			= $sizes_details;		
			
		$this->mongo_db->where(array('show_ond' => '1', 'status' => '1'));
		$this->mongo_db->order_by(array('ord_id' => 'asc'));
		$sizes_details 					= $this->mongo_db->get('sizes');	
		$this->data['sizes_details']			= $sizes_details;		
			
		$this->data['static_contents']		= (isset($static_contents[0])) 				? $static_contents[0] 				: array();
		$this->data['cmp_auth_no']			= $cmp_auth_no;
		$this->data['terms_condition']		= (isset($static_contents[0]['page_content'])) 	? $static_contents[0]['page_content'] 	: '';
			
		$this->mongo_db->where(array('page_title' => 'On-demand page'));
		$pages_help_contents				= $this->mongo_db->get('pages_help_contents');	
		$this->data['pages_help_contents']		= isset($pages_help_contents[0])	? $pages_help_contents[0] : array();			
			
		$data['data']						= $this->data;
		$data['view_link'] 					= 'site/customer_signup/index';
			
		$this->load->view('includes/template_site', $data);
	}
	
	public function uber_rush_api_check()
	{
		//if($this->session->userdata('site_is_logged_in'))
		//	redirect('dashboard');
			
		$pick_up_addr			= isset($_REQUEST['pick_up_addr']) 	? $_REQUEST['pick_up_addr'] 		: '';
		$drop_of_addr			= isset($_REQUEST['drop_of_addr']) 	? $_REQUEST['drop_of_addr'] 		: '';
			
		$pick_address_1		= isset($_REQUEST['pick_address_1']) 	? $_REQUEST['pick_address_1'] 	: '';
		$pick_city			= isset($_REQUEST['pick_city']) 		? $_REQUEST['pick_city'] 		: '';
		$pick_state			= isset($_REQUEST['pick_state']) 		? $_REQUEST['pick_state'] 		: '';
		$pick_code			= isset($_REQUEST['pick_code']) 		? $_REQUEST['pick_code'] 		: '';
		$pick_country			= isset($_REQUEST['pick_country']) 	? $_REQUEST['pick_country'] 		: '';
			
		$pickup_address_lat		= isset($_REQUEST['pick_lat']) 		? $_REQUEST['pick_lat'] 			: '';
		$pickup_address_lng		= isset($_REQUEST['pick_lng']) 		? $_REQUEST['pick_lng'] 			: '';
			
		$dropoff_address_1		= isset($_REQUEST['dropoff_address_1']) ? $_REQUEST['dropoff_address_1'] 	: '';
		$dropoff_city			= isset($_REQUEST['dropoff_city']) 	? $_REQUEST['dropoff_city'] 		: '';
		$dropoff_state			= isset($_REQUEST['dropoff_state']) 	? $_REQUEST['dropoff_state'] 		: '';
		$dropoff_code			= isset($_REQUEST['dropoff_code']) 	? $_REQUEST['dropoff_code'] 		: '';
		$dropoff_country		= isset($_REQUEST['dropoff_country']) 	? $_REQUEST['dropoff_country'] 	: '';
			
		$dropoff_address_lat	= isset($_REQUEST['dropoff_lat']) 		? $_REQUEST['dropoff_lat'] 		: '';
		$dropoff_address_lng	= isset($_REQUEST['dropoff_lng']) 		? $_REQUEST['dropoff_lng'] 		: '';
		
		$cmd	= 	'curl -F "client_secret='.$this->client_secret.'" \
				-F "client_id='.$this->client_id.'" \
				-F "grant_type=client_credentials" \
				-F "scope=delivery" \
				https://login.uber.com/oauth/v2/token';
			
		exec($cmd, $result);
			
		//echo '<pre>'; print_r($result);	
			
		if(isset($result[0]) && trim($result[0])!='' )
		{
			$json_decode	= json_decode($result[0]);
			$token		= $json_decode->access_token;
			
			$dropoff['pickup']['location']=  array(
										'address' 	=> $pick_address_1,
										'address_2' 	=> '',
										'city' 		=> $pick_city,
										'state' 		=> $pick_state,
										'postal_code' 	=> $pick_code,
										'country' 	=> $pick_country,
										'latitude' 	=> $pickup_address_lat,
										'longitude' 	=> $pickup_address_lng
									);
			    
			$dropoff['dropoff']['location']=  array(
										'address' 	=> $dropoff_address_1,
										'address_2' 	=> '',
										'city' 		=> $dropoff_city,
										'state' 		=> $dropoff_state,
										'postal_code' 	=> $dropoff_code,
										'country' 	=> $dropoff_country,
										'latitude' 	=> $dropoff_address_lat,
										'longitude' 	=> $dropoff_address_lng
									);
				
			//echo '<pre>'; print_r($dropoff);	
			
			$drop		= json_encode($dropoff);
			$ch 			= curl_init('https://api.uber.com/v1/deliveries/quote');
				
			curl_setopt_array($ch, array(
				CURLOPT_POST 			=> TRUE,
				CURLOPT_RETURNTRANSFER 	=> TRUE,
				CURLOPT_HTTPHEADER 		=> array(
				    'Authorization: Bearer '.$token,
				    'Content-Type: application/json'
				),
				CURLOPT_POSTFIELDS 		=> $drop
			));
				
			$response 				= curl_exec($ch);
			$json_array				= json_decode($response);
				
			if(isset($json_array->quotes) && !empty($json_array->quotes))
			{
				foreach($json_array->quotes as $j => $array)
				{
					$price = (isset($array->fee))	? $array->fee : 0;
					$json_array->quotes[$j]->price_formated		= number_format($price, 2);
				}
			}
			
			$messages					= array();
				
			echo json_encode($json_array);
		}
		else{
			$messages['message']		= '';
			$messages['error_code']		= '';
			
			echo json_encode($messages);
		}
	}
		
		
	function customer_signup_submit()
	{
		$user_timezone 				= (isset($_COOKIE['user_timezone']) && $_COOKIE['user_timezone']!='') ? $_COOKIE['user_timezone'] : $this->system_timezone;
			
		$opts = array('http' =>
			array(
			    'method'  => 'GET',
			    'timeout' => 120 
			)
		);
			
		$context  		= stream_context_create($opts);
		$data_to_store 	= $pickup_addr = $drop_addr = array();
			
		$email_id			= $this->input->post('email');
		$first_name		= $this->input->post('first_name');
		$last_name		= $this->input->post('last_name');
		$password			= $this->input->post('password');
			
		$user_phone		= $this->input->post('mobile_no');
		$country_code		= $this->input->post('country_code');
		$phone_code		= $this->input->post('phone_code');
			
		$merchant_id		= $this->input->post('cmp_auth_id');	
				
		//Encrype the password with blowfish password encryption
		$enc_password 		= crypt($password, $this->password_salt);
			
		$pickup_addr 		= $this->input->post('pickup_address');
		$pickup_addr_1 	= $this->input->post('pick_address_1');
		$pickup_city 		= $this->input->post('pick_city');
		$pickup_state 		= $this->input->post('pick_state');
		$pickup_code 		= $this->input->post('pick_code');
		$pickup_country 	= $this->input->post('pick_country');
		$pickup_addr_lat	= $this->input->post('pickup_address_lat');
		$pickup_addr_lng	= $this->input->post('pickup_address_lng');
			
		$pickup_addr_url 	= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$pickup_addr_lat.'&lng='.$pickup_addr_lng.'&username='.$this->geonames_username.'&type=JSON', false, $context));
		$pickup_addr_arr 	= json_decode($pickup_addr_url);
		$pickup_addr_name 	= isset($pickup_addr_arr->countryName) ? $pickup_addr_arr->countryName : '';
		$pickup_addr_code 	= isset($pickup_addr_arr->countryCode) ? $pickup_addr_arr->countryCode : '';
			
			
		$delivery_addr 	= $this->input->post('dropoff_address');
		$delivery_addr_1 	= $this->input->post('dropoff_address_1');
		$delivery_city 	= $this->input->post('dropoff_city');
		$delivery_state 	= $this->input->post('dropoff_state');
		$delivery_code 	= $this->input->post('dropoff_code');
		$delivery_country 	= $this->input->post('dropoff_country');
		$delivery_addr_lat	= $this->input->post('dropoff_address_lat');
		$delivery_addr_lng	= $this->input->post('dropoff_address_lng');
			
		$delivery_addr_url 	= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$delivery_addr_lat.'&lng='.$delivery_addr_lng.'&username='.$this->geonames_username.'&type=JSON', false, $context));
		$delivery_addr_arr 	= json_decode($delivery_addr_url);
		$delivery_addr_name = isset($delivery_addr_arr->countryName) ? $delivery_addr_arr->countryName : '';
		$delivery_addr_code = isset($delivery_addr_arr->countryCode) ? $delivery_addr_arr->countryCode : '';
			
			
		$data_to_store['user_type']		= 'customer';
		$data_to_store['email']			= $email_id;
		$data_to_store['password']		= $enc_password;
		$data_to_store['first_name']		= $first_name;
		$data_to_store['last_name']		= $last_name;
		$data_to_store['company_name']	= '';
			
		$data_to_store['company_address']	= array('address' => '', 'lat' => '',	'lat_str' => '', 'long' => '', 'long_str' => '', 'country' => '', 'country_code' => '');
			
		$data_to_store['delivery_address']	= array('address' => $delivery_addr, 	'lat' => (float)$delivery_addr_lat, 	'lat_str' => strval($delivery_addr_lat), 	'long' => (float)$delivery_addr_lng, 'long_str' => strval($delivery_addr_lng), 	'country' => $pickup_addr_name, 	'country_code' => $pickup_addr_code, 'city' => $pickup_city, 'state' => $pickup_state, 'code' => $pickup_code);
			
		$data_to_store['pickup_address']	= array('address' => $pickup_addr, 	'lat' => (float)$pickup_addr_lat, 		'lat_str' => strval($pickup_addr_lat), 	'long' => (float)$pickup_addr_lng, 		'long_str' => strval($pickup_addr_lng), 	'country' => $delivery_addr_name, 'country_code' => $delivery_addr_code, 'city' => $delivery_city, 'state' => $delivery_state, 'code' => $delivery_code);
			
		$data_to_store['user_timezone']	= $user_timezone;
		$data_to_store['system_timezone']	= $this->system_timezone;
		$data_to_store['password_salt'] 	= $this->password_salt;
			
		$data_to_store['user_phone']		= $user_phone;
		$data_to_store['country_code']	= $country_code;
		$data_to_store['phone_code'] 		= $phone_code;
			
		$data_to_store['forgot_pass']		= '0';
		$data_to_store['verify_pass_code']	= '';
		$data_to_store['added_on']		= strval(date('Y-m-d H:i:s'));
		$data_to_store['admin_status']	= strval(1);
		$data_to_store['status']			= strval(1);
		$data_to_store['merchant_id'] 	= ($merchant_id) ? $merchant_id : '';
		$do_insert 					= 1;		
			
		if($email_id)
		{
			if(!empty($merchant_id))
			{
				//check for similar emial
				$this->mongo_db->where(array('email' => $email_id,'merchant_id' => $merchant_id));
				$count 	= $this->mongo_db->count('site_users');
			}
			else
			{
				//check for similar emial
				$this->mongo_db->where(array('email' => $email_id));
				$count 	= $this->mongo_db->count('site_users');
			}
			
			if($count > 0)
			{
				$do_insert = 0;
				$this->session->set_flashdata('flash_message', 'email_exist');
			}
		}
		else{
			$this->session->set_flashdata('flash_message', 'email_blank');
			$do_insert 	= 0;
		}
			
		if($do_insert)
		{
			$insert 		= $this->mongo_db->insert('site_users', $data_to_store);
				
			if($insert)
			{
				//Get job start country id
				$this->mongo_db->where(array('name' => ucwords($pickup_addr_name)));
				$job_start_country_det 					= $this->mongo_db->get('countries');
					
				//Get job start country id
				$this->mongo_db->where(array('name' => ucwords($delivery_addr_name)));
				$job_end_country_det 					= $this->mongo_db->get('countries');
					
				$job_start_country_id					= (isset($job_start_country_det[0]['_id'])) 	? strval($job_start_country_det[0]['_id']) : '';
				$job_end_country_id						= (isset($job_end_country_det[0]['_id'])) 	? strval($job_end_country_det[0]['_id']) 	: '';
					
				//Updating this user's job countries data
				$data_to_store_country['countries']		= array($job_start_country_id, $job_end_country_id);
				$data_to_store_country['user_id']			= strval($insert);
				$data_to_store_country['is_all_countries']	= '0';
					
				$this->mongo_db->insert('user_job_countries', $data_to_store_country);
					
				//Automatically login to the site after user registration
				$this->mongo_db->where(array('email' => $email_id));
				$user_details 	= $this->mongo_db->get('site_users');
					
				$user_timezone = (isset($_COOKIE['user_timezone']) && $_COOKIE['user_timezone']!='') ? $_COOKIE['user_timezone'] : $this->system_timezone;
				if(is_array($user_details))
				{
					$data 	= array(
								'user_timezone'			=> $user_timezone,
								'site_user_id_hotcargo' 		=> isset($user_details[0]['_id']) 		? strval($user_details[0]['_id']) 	: '',
								'site_user_objId_hotcargo' 	=> isset($user_details[0]['_id']) 		? strval($user_details[0]['_id']) 	: '',
								'site_user_type_hotcargo' 	=> isset($user_details[0]['user_type'])	? $user_details[0]['user_type'] 	: '',
								'site_user_name_hotcargo' 	=> ucfirst($user_details[0]['first_name'].' '.$user_details[0]['last_name']),
								'site_is_logged_in' 		=> true
							);
					
					$this->session->set_userdata($data);
				}
				
				$uber_rush_quote_id					= $this->input->post('uber_rush_quote_id');
				$uber_rush_quote_price				= $this->input->post('uber_rush_quote_price');
				$api_type_cmp						= $this->input->post('api_type_cmp');
				
				//Add the job
				$job_weight						= $this->input->post('job_weight');
				$description						= ($this->input->post('description'))	? $this->input->post('description') : '';
				$choose_delivery					= $this->input->post('choose-delivery');
				$this->mongo_db->where(array('_id' => $choose_delivery));
				$delivery_det_arr 					= $this->mongo_db->get('sizes');
					
				$delivery_det_width					= isset($delivery_det_arr['width']) 	? $delivery_det_arr['width'] 	: '';
				$delivery_det_height				= isset($delivery_det_arr['height']) 	? $delivery_det_arr['height'] : '';
				$delivery_det_depth					= isset($delivery_det_arr['depth']) 	? $delivery_det_arr['depth']	: '';
				$delivery_det_weight				= isset($delivery_det_arr['weight']) 	? $delivery_det_arr['weight'] : '';
				$delivery_det_weight				= $this->input->post('weight');
				$is_fragile						= $this->input->post('is_fragile');
					
				$data_to_store_job['user_id']			= strval($insert);
				$data_to_store_job['title']			= '';
				$data_to_store_job['description']		= '';
				$data_to_store_job['image']			= '';
				$data_to_store_job['pickup_address']	= array('address' => $pickup_addr, 'address_1' => $pickup_addr_1, 'lat' => (float)$pickup_addr_lat, 'lat_str' => strval($pickup_addr_lat), 	'long' => (float)$pickup_addr_lng, 'long_str' => strval($pickup_addr_lng), 'country' => $pickup_addr_name, 'country_code' => $pickup_addr_code, 'city' => $pickup_city, 'state' => $pickup_state, 'code' => $pickup_code);
					
				$data_to_store_job['drop_address']		= array('address' => $delivery_addr, 'address_1' => $delivery_addr_1, 'lat' => (float)$delivery_addr_lat, 'lat_str' => strval($delivery_addr_lat), 	'long' => (float)$delivery_addr_lng, 'long_str' => strval($delivery_addr_lng), 	'country' => $delivery_addr_name, 'country_code' => $delivery_addr_code, 'city' => $delivery_city, 'state' => $delivery_state, 'code' => $delivery_code);
					
				$data_to_store_job['distance']		= (float)$this->input->post('distance_val');
				$data_to_store_job['distance_type']	= "miles";
				$data_to_store_job['deliver_method']	= '';
				$data_to_store_job['delivery_date']	= '';
					
				$data_to_store_job['cargo_value']		= (float)0;
				$data_to_store_job['size_type']		= $choose_delivery;
				$data_to_store_job['size']			= (object)array('width' => (float)$delivery_det_width, 'height' => (float)$delivery_det_height, 'depth' => (float)$delivery_det_depth);
				$data_to_store_job['bins']				= array();
				$data_to_store_job['items']				= array();
					
				$data_to_store_job['type']				= '';
				$data_to_store_job['special']				= '';
				$data_to_store_job['weight']				= $delivery_det_weight;
				$data_to_store_job['is_fragile']			= $is_fragile;
				$data_to_store_job['max_job_price']		= (float)0;
				$data_to_store_job['is_gurrented']			= 0;
				$data_to_store_job['is_insured']			= 0;
					
				//Add job info for on-demand section
				$data_to_store_job['is_ondemand']			= '1';
				$data_to_store_job['on_demand_user']		= $api_type_cmp;
				$data_to_store_job['on_demand_quote_id']	= $uber_rush_quote_id;
				$data_to_store_job['on_demand_quote_price']	= $uber_rush_quote_price;
				$data_to_store_job['do_pay_now']			= ($uber_rush_quote_id) ? '1' : '0';	
					
				$data_to_store_job['job_priority']			= '0';
				$data_to_store_job['other_details']		= (object)array();
				$data_to_store_job['job_status']			= '0';
				$data_to_store_job['job_taken_by']			= '0';
				$data_to_store_job['added_on']			= strval(date('Y-m-d H:i:s'));
				$data_to_store_job['system_timezone']		= $this->system_timezone;
				$data_to_store_job['status']				= '1';
					
					
				//for sending email to user for registration
				$this->mongo_db->where(array('_id' => $insert));
				$job_user_details 		= $this->mongo_db->get('site_users');
				
				$user_email_id 		= isset($job_user_details[0]['email']) ? $job_user_details[0]['email'] : '';
				$to_name 				= isset($job_user_details[0]['first_name']) ? ucwords($job_user_details[0]['first_name'].' '.$job_user_details[0]['last_name']) : '';
					
				//for checking the reply id is parent id or not
				$this->mongo_db->where(array('email_title' => 'reg_success'));
				$email_temp_arr 		= $this->mongo_db->get('email_templates');
				$email_temp			= isset($email_temp_arr[0]) ? $email_temp_arr[0] : '';
					
				//Check for email settings 
				if(!empty($email_temp))
				{
					$search 			= array('[SITE_LOGO]', '[NAME]', '[USER_TYPE]', '[SITE_NAME]');
					$replace 			= array(base_url().'assets/site/images/logo.png', $to_name, ucfirst($data_to_store['user_type']), $this->site_name);
						
					$email_temp_msg	= isset($email_temp['email_template']) 	? $email_temp['email_template'] : '';
					$email_temp_msg	= str_replace($search, $replace, $email_temp_msg);
						
					$email_temp_sub	= isset($email_temp['email_subject']) 	? $email_temp['email_subject'] : '';
						
					if($user_email_id) 	$this->User_email_model->send_email($user_email_id, $email_temp_sub, $email_temp_msg, '', '', '', $to_name);
				}
					
					
				$job_id 							= $this->mongo_db->insert('jobs', $data_to_store_job);
				$data_to_store_file 				= array();
					
				$this->mongo_db->where(array('name' => ucfirst($pickup_addr_name)));
				$pick_country_det 					= $this->mongo_db->get('countries');
				$pick_country_id					= (isset($pick_country_det[0]['_id']))	? strval($pick_country_det[0]['_id']) : '';
					
				$this->mongo_db->where(array('name' => ucfirst($delivery_addr_name)));
				$drop_country_det 					= $this->mongo_db->get('countries');
				$drop_country_id					= (isset($drop_country_det[0]['_id']))	? strval($drop_country_det[0]['_id']) : '';
					
				//get user prefered countries
				$this->mongo_db->where(array('user_id' => $user_id));
				$user_country_det 					= $this->mongo_db->get('user_job_countries');
				$user_countries_list				= (isset($user_country_det[0]['countries'])) 		? $user_country_det[0]['countries'] 		: array();
				$is_all_countries					= (isset($user_country_det[0]['is_all_countries'])) 	? $user_country_det[0]['is_all_countries'] 	: array();
					
				//echo 'pick id: '.$pick_country_id.' drop id: '.$drop_country_id.'<br>';
				//echo '<pre>'; print_r($user_countries_list); echo '</pre>';
					
				$dat_to_update_countries['countries']			= $user_countries_list;
					
				if(!in_array($pick_country_id, $user_countries_list))
					$dat_to_update_countries['countries'][]		= $pick_country_id;
				if(!in_array($drop_country_id, $user_countries_list))
					$dat_to_update_countries['countries'][]		= $drop_country_id;
					
				$dat_to_update_countries 					= array_filter( $dat_to_update_countries );
					
				if($job_id)
				{
					if($is_all_countries == 0)
					{
						//Update user $user_id
						$this->mongo_db->where(array('user_id' => $user_id));
						$this->mongo_db->set($dat_to_update_countries);
						$this->mongo_db->update('user_job_countries');
					}
						
					if(!empty($data_to_store_job['on_demand_quote_id']))
					{
						//Insert user job quote id
						$quote_cmp_name 				= $api_type_cmp 			? $api_type_cmp 			: '';
						$this->mongo_db->where(array('company_name' => ucfirst($quote_cmp_name)));
						$quote_cmp_details 				= $this->mongo_db->get('site_users');
							
						//store quote data in db
						$quote_det_store['job_id']		= strval($job_id);
						$quote_det_store['user_id']		= isset($quote_cmp_details[0]['_id']) 			? strval($quote_cmp_details[0]['_id']) 		 	: '';
						$quote_det_store['type']			= '1';
						$quote_det_store['pickup_date']	= date('Y-m-d');
						$quote_det_store['drop_date']		= date('Y-m-d');
						$quote_det_store['job_price']		= ($uber_rush_quote_price) 					? $uber_rush_quote_price						: '';
						$quote_det_store['start_location']	= $data_to_store_job['pickup_address'];
						$quote_det_store['end_location']	= $data_to_store_job['drop_address'];
						$quote_det_store['added_on']		= date('Y-m-d H:i:s');
						$quote_det_store['request_status']	= '1';
						$quote_det_store['status']		= '1';
						$quote_id 					= $this->mongo_db->insert('job_quotes_legs', $quote_det_store);	
					}
						
					$in_onsd			= (!empty($data_to_store_job['on_demand_quote_id'])) ? '1' : '0';
					$job_cordinates 	= array(array('lat' => $pickup_addr_lat, 'lng' => $pickup_addr_lng), array('lat' => $delivery_addr_lat, 'lng' => $delivery_addr_lng), 'ins_id' => strval($job_id), 'is_onsd' => $in_onsd);
						
					$this->session->set_flashdata('flash_message_cont', 		json_encode($job_cordinates));
					$this->session->set_flashdata('flash_message', 			'job_add_success');
						
					redirect('dashboard');
				}
				else	$this->session->set_flashdata('flash_message', 			'job_add_failed');
				redirect('customer-signup');
			}
			else{
				$this->session->set_flashdata('flash_message', 'user_job_error');
				redirect('customer-signup');
			}
		}
		else{
			//echo 'arijit'; die;
			$this->session->set_flashdata('flash_message', 'email_exist');
			redirect('customer-signup');
		}
	}
		
	public function on_demand_signup()
	{
		$cmp_auth_no 								= isset($this->cmp_auth_id) 				? $this->cmp_auth_id : '';
		$cmp_auth_name 							= isset($this->cmp_details[0]['name'])  	? $this->cmp_details[0]['name'] 		: '';
			
		if($cmp_auth_no) 	$this->mongo_db->where(array('page_alias' => 'terms-conditions', 'auth_id' => $cmp_auth_no));
		else 			$this->mongo_db->where(array('page_alias' => 'terms-conditions'));	
			
		$static_contents 							= $this->mongo_db->get('static_contents'); 
			
		if(!empty($static_contents)){	
			if(isset($static_contents[0]['page_content']) && ($static_contents[0]['page_content'] == ''))
			{
				$this->mongo_db->where(array('page_alias' => 'terms-conditions'));
				$static_contents 					= $this->mongo_db->get('static_contents'); 
			}
		}
		else{
			$this->mongo_db->where(array('page_alias' => 'terms-conditions'));
			$static_contents 						= $this->mongo_db->get('static_contents'); 
		}
			
		$this->data['terms_condition']		= (isset($static_contents[0]['page_content'])) 	? $static_contents[0]['page_content'] 	: '';
			
		$this->data['static_contents']		= (isset($static_contents[0])) 				? $static_contents[0] 				: array();
		$this->data['cmp_auth_no']			= $cmp_auth_no;	
		$user_id 							= $this->session->userdata('site_user_objId_hotcargo');
		$this->data['user_id']				= $user_id;
			
		$this->mongo_db->where(array('menu_type' => '1', 'status' => '1', 'menu_location' => '1'));
		$this->mongo_db->order_by(array('ord_id' => 'asc'));
		$users_all_menus 					= $this->mongo_db->get('menus');
		$this->data['users_all_menus']		= (isset($users_all_menus)) ? $users_all_menus : array();
		$this->data['cmp_auth_no']			= $cmp_auth_no;
			
		$this->mongo_db->where(array('show_ond' => '1', 'status' => '1'));
		$this->mongo_db->order_by(array('ord_id' => 'asc'));
		$sizes_details 					= $this->mongo_db->get('sizes');	
		$this->data['sizes_details']			= $sizes_details;	
			
		$this->mongo_db->where(array('page_title' => 'On-demand page'));
		$pages_help_contents				= $this->mongo_db->get('pages_help_contents');	
		$this->data['pages_help_contents']		= isset($pages_help_contents[0])	? $pages_help_contents[0] : array();		
			
		$data['data']						= $this->data;
		$data['view_link'] 					= 'site/customer_signup/ondemand_signup';
		$this->load->view('includes/template_site', $data);
	}
	
	
	public function customer_ondemand_submit()
	{
		$user_timezone 	= (isset($_COOKIE['user_timezone']) && $_COOKIE['user_timezone']!='') ? $_COOKIE['user_timezone'] : $this->system_timezone;
		$user_id 			= $this->session->userdata('site_user_objId_hotcargo');	
		$opts = array('http' =>
			array(
			    'method'  => 'GET',
			    'timeout' => 120 
			)
		);
			
		$context  						= stream_context_create($opts);
		$data_to_store 					= $pickup_addr = $drop_addr = array();
			
		$pickup_addr 						= $this->input->post('pickup_address');
		$pickup_addr_1 					= $this->input->post('pick_address_1');
		$pickup_city 						= $this->input->post('pick_city');
		$pickup_state 						= $this->input->post('pick_state');
		$pickup_code 						= $this->input->post('pick_code');
		$pickup_country 					= $this->input->post('pick_country');
		$pickup_addr_lat					= $this->input->post('pickup_address_lat');
		$pickup_addr_lng					= $this->input->post('pickup_address_lng');
			
		$pickup_addr_url 					= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$pickup_addr_lat.'&lng='.$pickup_addr_lng.'&username='.$this->geonames_username.'&type=JSON', false, $context));
		$pickup_addr_arr 					= json_decode($pickup_addr_url);
		$pickup_addr_name 					= isset($pickup_addr_arr->countryName) ? $pickup_addr_arr->countryName : '';
		$pickup_addr_code 					= isset($pickup_addr_arr->countryCode) ? $pickup_addr_arr->countryCode : '';
			
		$delivery_addr 					= $this->input->post('dropoff_address');
		$delivery_addr_1 					= $this->input->post('dropoff_address_1');
		$delivery_city 					= $this->input->post('dropoff_city');
		$delivery_state 					= $this->input->post('dropoff_state');
		$delivery_code 					= $this->input->post('dropoff_code');
		$delivery_country 					= $this->input->post('dropoff_country');
		$delivery_addr_lat					= $this->input->post('dropoff_address_lat');
		$delivery_addr_lng					= $this->input->post('dropoff_address_lng');
			
		$delivery_addr_url 					= (@file_get_contents('http://api.geonames.org/countryCode?lat='.$delivery_addr_lat.'&lng='.$delivery_addr_lng.'&username='.$this->geonames_username.'&type=JSON', false, $context));
		$delivery_addr_arr 					= json_decode($delivery_addr_url);
		$delivery_addr_name 				= isset($delivery_addr_arr->countryName) ? $delivery_addr_arr->countryName : '';
		$delivery_addr_code 				= isset($delivery_addr_arr->countryCode) ? $delivery_addr_arr->countryCode : '';
			
		$uber_rush_quote_id					= $this->input->post('uber_rush_quote_id');
		$uber_rush_quote_price				= $this->input->post('uber_rush_quote_price');
		$api_type_cmp						= $this->input->post('api_type_cmp');
			
		//Add the job
		$job_weight						= $this->input->post('job_weight');
		$description						= $this->input->post('description');
		$choose_delivery					= $this->input->post('choose-delivery');
			
		$this->mongo_db->where(array('_id' => $choose_delivery));
		$delivery_det_arr 					= $this->mongo_db->get('sizes');
			
		$delivery_det_width					= isset($delivery_det_arr[0]['width']) 	? $delivery_det_arr[0]['width'] 	: '';
		$delivery_det_height				= isset($delivery_det_arr[0]['height']) ? $delivery_det_arr[0]['height'] 	: '';
		$delivery_det_depth					= isset($delivery_det_arr[0]['depth']) 	? $delivery_det_arr[0]['depth']	: '';
		$delivery_det_weight				= $this->input->post('weight');
		$is_fragile						= $this->input->post('is_fragile');
			
			
		$data_to_store_job['user_id']			= $user_id;
		$data_to_store_job['title']			= '';
		$data_to_store_job['description']		= '';
		$data_to_store_job['image']			= '';
			
			
		$data_to_store_job['pickup_address']	= array('address' => $pickup_addr, 'address_1' => $pickup_addr_1, 'lat' => (float)$pickup_addr_lat, 'lat_str' => strval($pickup_addr_lat), 	'long' => (float)$pickup_addr_lng, 'long_str' => strval($pickup_addr_lng), 'country' => $pickup_addr_name, 'country_code' => $pickup_addr_code, 'city' => $pickup_city, 'state' => $pickup_state, 'code' => $pickup_code);
			
		$data_to_store_job['drop_address']		= array('address' => $delivery_addr, 'address_1' => $delivery_addr_1, 'lat' => (float)$delivery_addr_lat, 'lat_str' => strval($delivery_addr_lat), 	'long' => (float)$delivery_addr_lng, 'long_str' => strval($delivery_addr_lng), 	'country' => $delivery_addr_name, 'country_code' => $delivery_addr_code, 'city' => $delivery_city, 'state' => $delivery_state, 'code' => $delivery_code);
			
		$data_to_store_job['distance']		= (float)$this->input->post('distance_val');
		$data_to_store_job['distance_type']	= "miles";
		$data_to_store_job['deliver_method']	= 'Urgent';
		$data_to_store_job['delivery_date']	= date('Y-m-d H:i:s');
			
		$data_to_store_job['cargo_value']		= (float)0;
		$data_to_store_job['size_type']		= $choose_delivery;
		$data_to_store_job['size']			= (object)array('width' => (float)$delivery_det_width, 'height' => (float)$delivery_det_height, 'depth' => (float)$delivery_det_depth);
		$data_to_store_job['bins']			= array();
		$data_to_store_job['items']			= array();
			
		$data_to_store_job['type']			= '';
		$data_to_store_job['special']			= '';
		$data_to_store_job['weight']			= (float)$delivery_det_weight;
		$data_to_store_job['is_fragile']		= $is_fragile;
		$data_to_store_job['max_job_price']	= (float)0;
		$data_to_store_job['is_gurrented']		= 0;
		$data_to_store_job['is_insured']		= 0;
			
		//Add job info for on-demand section
		$data_to_store_job['is_ondemand']			= '1';
		$data_to_store_job['on_demand_user']		= $api_type_cmp;
		$data_to_store_job['on_demand_quote_id']	= $uber_rush_quote_id;
		$data_to_store_job['on_demand_quote_price']	= $uber_rush_quote_price;
		$data_to_store_job['do_pay_now']			= ($uber_rush_quote_id) ? '1' : '0';	
			
		$data_to_store_job['job_priority']		= '0';
		$data_to_store_job['other_details']	= (object)array();
		$data_to_store_job['job_status']		= '0';
		$data_to_store_job['job_taken_by']		= '0';
		$data_to_store_job['added_on']		= strval(date('Y-m-d H:i:s'));
		$data_to_store_job['system_timezone']	= $this->system_timezone;
		$data_to_store_job['status']			= '1';
					
		$insert 							= $this->mongo_db->insert('jobs', $data_to_store_job);
		$data_to_store_file 				= array();
			
		$this->mongo_db->where(array('name' => ucfirst($pickup_addr_name)));
		$pick_country_det 					= $this->mongo_db->get('countries');
		$pick_country_id					= (isset($pick_country_det[0]['_id']))	? strval($pick_country_det[0]['_id']) : '';
			
		$this->mongo_db->where(array('name' => ucfirst($delivery_addr_name)));
		$drop_country_det 					= $this->mongo_db->get('countries');
		$drop_country_id					= (isset($drop_country_det[0]['_id']))	? strval($drop_country_det[0]['_id']) : '';
		
		//get user prefered countries
		$this->mongo_db->where(array('user_id' => $user_id));
		$user_country_det 					= $this->mongo_db->get('user_job_countries');
		$user_countries_list				= (isset($user_country_det[0]['countries'])) 		? $user_country_det[0]['countries'] 		: array();
		$is_all_countries					= (isset($user_country_det[0]['is_all_countries'])) 	? $user_country_det[0]['is_all_countries'] 	: array();
		
		//echo 'pick id: '.$pick_country_id.' drop id: '.$drop_country_id.'<br>';
		//echo '<pre>'; print_r($user_countries_list); echo '</pre>';
			
		$dat_to_update_countries['countries']			= $user_countries_list;
			
		if(!in_array($pick_country_id, $user_countries_list))
			$dat_to_update_countries['countries'][]		= $pick_country_id;
		if(!in_array($drop_country_id, $user_countries_list))
			$dat_to_update_countries['countries'][]		= $drop_country_id;
			
		$dat_to_update_countries 					= array_filter( $dat_to_update_countries );
			
		if($insert)
		{
			if($is_all_countries == 0)
			{
				//Update user $user_id
				$this->mongo_db->where(array('user_id' => $user_id));
				$this->mongo_db->set($dat_to_update_countries);
				$this->mongo_db->update('user_job_countries');
			}
				
			if(!empty($data_to_store_job['on_demand_quote_id']))
			{
				//Insert user job quote id
				$quote_cmp_name 				= $api_type_cmp 			? $api_type_cmp 			: '';
				$this->mongo_db->where(array('company_name' => ucfirst($quote_cmp_name)));
				$quote_cmp_details 				= $this->mongo_db->get('site_users');
				
				//store quote data in db
				$quote_det_store['job_id']		= strval($insert);
				$quote_det_store['user_id']		= isset($quote_cmp_details[0]['_id']) 			? strval($quote_cmp_details[0]['_id']) 		 	: '';
				$quote_det_store['type']			= '1';
				$quote_det_store['pickup_date']	= date('Y-m-d');
				$quote_det_store['drop_date']		= date('Y-m-d');
				$quote_det_store['job_price']		= ($uber_rush_quote_price) 					? $uber_rush_quote_price						: '';
				$quote_det_store['start_location']	= $data_to_store_job['pickup_address'];
				$quote_det_store['end_location']	= $data_to_store_job['drop_address'];
				$quote_det_store['added_on']		= date('Y-m-d H:i:s');
				$quote_det_store['request_status']	= '1';
				$quote_det_store['status']		= '1';
					
				$quote_id 					= $this->mongo_db->insert('job_quotes_legs', $quote_det_store);	
			}
				
			$in_onsd			= (!empty($data_to_store_job['on_demand_quote_id'])) ? '1' : '0';
			$job_cordinates 	= array(array('lat' => $pickup_addr_lat, 'lng' => $pickup_addr_lng), array('lat' => $delivery_addr_lat, 'lng' => $delivery_addr_lng), 'ins_id' => strval($insert), 'is_onsd' => $in_onsd);
				
			$this->session->set_flashdata('flash_message_cont', 		json_encode($job_cordinates));
			$this->session->set_flashdata('flash_message', 			'job_add_success');
				
			redirect('dashboard');
		}
		else	$this->session->set_flashdata('flash_message', 			'job_add_failed');
	}
		
}
?>