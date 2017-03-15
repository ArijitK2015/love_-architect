<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] 								= 'welcome';
//$route['404_override'] 									= '';
//$route['translate_uri_dashes'] 								= FALSE;


//$route['default_controller'] 								= 'Home_controllers/index';
//$route['default_controller']								= 'Customer_signup_controllers/index';
//$route['default_controller']									= 'User/index';
$route['control/login'] 										= 'User/index';
$route['(:any)/control/login'] 								= 'User/index';
$route['login'] 									         	= 'Home_controllers/index';
$route['(:any)/login'] 									     = 'Home_controllers/index';

$route['control'] 											= 'User/index';
$route['(:any)/control'] 									= 'User/index';


$route['control/logout'] 									= 'User/logout';
$route['(:any)/control/logout'] 								= 'User/logout';
$route['control/login/validate_credentials'] 					= 'User/validate_credentials';
$route['(:any)/control/login/validate_credentials'] 				= 'User/validate_credentials';

$route['control/admin-forgot-password']							= 'User/forgot_password';
$route['(:any)/control/admin-forgot-password']					= 'User/forgot_password';

$route['control/admin-validate-password/(:any)']					= 'User/validate_password';
$route['(:any)/control/admin-validate-password/(:any)']			= 'User/validate_password';

//Chnage password
$route['control/admin-update-password']							= 'User/change_pass_verify';
$route['(:any)/control/admin-update-password']					= 'User/change_pass_verify';

//Login section
$route['login-validate'] 									= 'Home_controllers/login_validate';
$route['(:any)/login-validate'] 								= 'Home_controllers/login_validate';

$route['linkdin-auth']										= 'Home_controllers/linkdin_validate';
$route['(:any)/linkdin-auth']									= 'Home_controllers/linkdin_validate';

$route['zappier-job-json']									= 'Home_controllers/zappier_job_json';
$route['zappier-job-events-json']								= 'Home_controllers/zappier_job_events_json';

$route['job_details_quote_leg_ajax']							= 'Jobs_controllers/job_details_quote_leg_ajax';
$route['(:any)/job_details_quote_leg_ajax']						= 'Jobs_controllers/job_details_quote_leg_ajax';


$route['linkdin-auth/(:any)']									= 'Home_controllers/linkdin_validate';
$route['(:any)/linkdin-auth/(:any)']							= 'Home_controllers/linkdin_validate';

//After login dashboard section
$route['dashboard']											= 'Dashboard_controllers/index';
$route['(:any)/dashboard']									= 'Dashboard_controllers/index';

$route['dashboard/(:any)']									= 'Dashboard_controllers/index';
$route['(:any)/dashboard/(:any)']								= 'Dashboard_controllers/index';

//Payment section links
$route['pre-make-payment']									= 'Dashboard_controllers/pre_make_payment';
$route['(:any)/pre-make-payment']								= 'Dashboard_controllers/pre_make_payment';

$route['pre-make-payment/(:any)']								= 'Dashboard_controllers/pre_make_payment';
$route['(:any)/pre-make-payment/(:any)']						= 'Dashboard_controllers/pre_make_payment';

$route['make-payment']										= 'Dashboard_controllers/make_payment';
$route['(:any)/make-payment']									= 'Dashboard_controllers/make_payment';

$route['make-payment/(:any)']									= 'Dashboard_controllers/make_payment';
$route['(:any)/make-payment/(:any)']							= 'Dashboard_controllers/make_payment';

$route['map-search-result']                                 		= 'Dashboard_controllers/map_search_result';
$route['(:any)/map-search-result']                          		= 'Dashboard_controllers/map_search_result';

$route['map-search-result/(:any)']                          		= 'Dashboard_controllers/map_search_result';
$route['(:any)/map-search-result/(:any)']                   		= 'Dashboard_controllers/map_search_result';

//Register section
$route['sign-up/(:any)'] 									= 'Home_controllers/register';
$route['(:any)/sign-up/(:any)'] 								= 'Home_controllers/register';

$route['signup-validate'] 									= 'Home_controllers/signup_validate';
$route['(:any)/signup-validate'] 								= 'Home_controllers/signup_validate';

$route['forgot-password']									= 'Home_controllers/forgot_password';
$route['(:any)/forgot-password']								= 'Home_controllers/forgot_password';

$route['validate-password/(:any)']								= 'Home_controllers/validate_password';
$route['(:any)/validate-password/(:any)']						= 'Home_controllers/validate_password';

//Logout user
$route['logout']											= 'Dashboard_controllers/logout';
$route['(:any)/logout']										= 'Dashboard_controllers/logout';

//Chnage password
$route['update-password']									= 'Home_controllers/change_pass_verify';
$route['(:any)/update-password']								= 'Home_controllers/change_pass_verify';


$route['control/admin-dashboard'] 								= 'Admin_dashboard/index';
$route['(:any)/control/admin-dashboard'] 						= 'Admin_dashboard/index';
$route['control/admin-Dashboard'] 								= 'Admin_dashboard/index';
$route['(:any)/control/admin-Dashboard'] 						= 'Admin_dashboard/index';
$route['control/change-password'] 								= 'Admin_chngpassword/index';
$route['control/change-password/updt'] 							= 'Admin_chngpassword/updt';

$route['control/sitesetting'] 								= 'Admin_sitesetting/index';
$route['control/sitesetting/updt'] 							= 'Admin_sitesetting/updt';

$route['control/contactsetting'] 								= 'Admin_contactsetting/index';
$route['control/contactsetting/updt'] 							= 'Admin_contactsetting/updt';

$route['control/myaccount'] 									= 'Admin_myaccount/index';
$route['(:any)/control/myaccount'] 							= 'Admin_myaccount/index';

$route['control/myaccount/updt'] 								= 'Admin_myaccount/updt';
$route['(:any)/control/myaccount/updt'] 						= 'Admin_myaccount/update_merchant';

$route['control/data-forms']									= 'Data_form_controller/index';
$route['control/data-forms/add']								= 'Data_form_controller/add';
$route['control/data-forms/edit/(:any)']						= 'Data_form_controller/updt';
$route['control/data-forms/delete/(:any)']						= 'Data_form_controller/delete';

//Static pages
$route['customer-support']									= 'Article_controller/index/customer-support';
$route['(:any)/customer-support']								= 'Article_controller/index/customer-support';

$route['help']												= 'Article_controller/index/help';
$route['(:any)/help']										= 'Article_controller/index/help';

$route['about']											= 'Article_controller/index/about';
$route['(:any)/about']										= 'Article_controller/index/about';

//My account section	
$route['my-account']										= 'Dashboard_controllers/my_account';
$route['notifications']										= 'Dashboard_controllers/notifications';
$route['(:any)/notifications']								= 'Dashboard_controllers/notifications';

$route['update-notifications']								= 'Dashboard_controllers/notifications';
$route['(:any)/update-notifications']					    		= 'Dashboard_controllers/notifications';

$route['notifications-countries']								= 'Dashboard_controllers/notifications_countries';
$route['(:any)/notifications-countries']						= 'Dashboard_controllers/notifications_countries';

//For Customer management
$route['control/manage-customers']								= 'Manage_customers_controller/index';
$route['(:any)/control/manage-customers']						= 'Manage_customers_controller/index';
$route['control/manage-customers/add']							= 'Manage_customers_controller/add';
$route['(:any)/control/manage-customers/add']					= 'Manage_customers_controller/add';
$route['control/manage-customers/add_cust']						= 'Manage_customers_controller/add_cust';
$route['(:any)/control/manage-customers/add_cust']				= 'Manage_customers_controller/add_cust';
$route['control/manage-customers/edit']						    	= 'Manage_customers_controller/updt';
$route['(:any)/control/manage-customers/edit']					= 'Manage_customers_controller/updt';
$route['control/manage-customers/delete/(:any)']					= 'Manage_customers_controller/delete';
$route['(:any)/control/manage-customers/delete/(:any)']			= 'Manage_customers_controller/delete';

//For broker management
$route['control/manage-brokers']								= 'Manage_brokers_controller/index';
$route['(:any)/control/manage-brokers']							= 'Manage_brokers_controller/index';
$route['control/manage-brokers/add']							= 'Manage_brokers_controller/add';
$route['(:any)/control/manage-brokers/add']						= 'Manage_brokers_controller/add';
$route['control/manage-brokers/add_broker']						= 'Manage_brokers_controller/add_broker';
$route['(:any)/control/manage-brokers/add_broker']				= 'Manage_brokers_controller/add_broker';
$route['control/manage-brokers/edit']						    	= 'Manage_brokers_controller/updt';
$route['(:any)/control/manage-brokers/edit']				    		= 'Manage_brokers_controller/updt';
$route['control/manage-brokers/delete/(:any)']				    	= 'Manage_brokers_controller/delete';
$route['(:any)/control/manage-brokers/delete/(:any)']				= 'Manage_brokers_controller/delete';


//For fleet management
$route['control/manage-fleets']								= 'Manage_fleet_controller/index';
$route['(:any)/control/manage-fleets']							= 'Manage_fleet_controller/index';
$route['control/manage-fleets/add']							= 'Manage_fleet_controller/add';
$route['(:any)/control/manage-fleets/add']						= 'Manage_fleet_controller/add';
$route['control/manage-fleets/add_fleet']						= 'Manage_fleet_controller/add_fleet';
$route['(:any)/control/manage-fleets/add_fleet']					= 'Manage_fleet_controller/add_fleet';
$route['control/manage-fleets/edit']						    	= 'Manage_fleet_controller/updt';
$route['(:any)/control/manage-fleets/edit']						= 'Manage_fleet_controller/updt';
$route['control/manage-fleets/delete/(:any)']				    	= 'Manage_fleet_controller/delete';
$route['(:any)/control/manage-fleets/delete/(:any)']		    		= 'Manage_fleet_controller/delete';

//For driver management
$route['control/manage-drivers']								= 'Manage_driver_controller/index';
$route['(:any)/control/manage-drivers']							= 'Manage_driver_controller/index';
$route['control/manage-drivers/add']							= 'Manage_driver_controller/add';
$route['(:any)/control/manage-drivers/add']						= 'Manage_driver_controller/add';
$route['control/manage-drivers/add_driver']						= 'Manage_driver_controller/add_driver';
$route['(:any)/control/manage-drivers/add_driver']				= 'Manage_driver_controller/add_driver';
$route['control/manage-drivers/edit']						    	= 'Manage_driver_controller/updt';
$route['(:any)/control/manage-drivers/edit']						= 'Manage_driver_controller/updt';
$route['control/manage-drivers/delete/(:any)']				    	= 'Manage_driver_controller/delete';
$route['(:any)/control/manage-drivers/delete/(:any)']				= 'Manage_driver_controller/delete';

//For depot management
$route['control/manage-depots']								= 'Manage_depot_controller/index';
$route['(:any)/control/manage-depots']							= 'Manage_depot_controller/index';
$route['control/manage-depots/add']							= 'Manage_depot_controller/add';
$route['(:any)/control/manage-depots/add']						= 'Manage_depot_controller/add';
$route['control/manage-depots/add_depot']						= 'Manage_depot_controller/add_depot';
$route['(:any)/control/manage-depots/add_depot']					= 'Manage_depot_controller/add_depot';
$route['control/manage-depots/edit']						    	= 'Manage_depot_controller/updt';
$route['(:any)/control/manage-depots/edit']						= 'Manage_depot_controller/updt';
$route['control/manage-depots/delete/(:any)']				    	= 'Manage_depot_controller/delete';
$route['(:any)/control/manage-depots/delete/(:any)']				= 'Manage_depot_controller/delete';

//Manage sizes
$route['control/manage-sizes']								= 'Manage_sizes_controller/index';
$route['control/manage-sizes/add']								= 'Manage_sizes_controller/add';
$route['control/manage-sizes/edit']						     = 'Manage_sizes_controller/updt';
$route['control/manage-sizes/delete/(:any)']				        	= 'Manage_sizes_controller/delete';

//Manage sizes
$route['control/manage-types']								= 'Manage_types_controller/index';
$route['control/manage-types/add']								= 'Manage_types_controller/add';
$route['control/manage-types/edit']						     = 'Manage_types_controller/updt';
$route['control/manage-types/delete/(:any)']				        	= 'Manage_types_controller/delete';

//Manage types
$route['control/manage-special']								= 'Manage_special_controller/index';
$route['control/manage-special/add']							= 'Manage_special_controller/add';
$route['control/manage-special/edit']						     = 'Manage_special_controller/updt';
$route['control/manage-special/delete/(:any)']				     = 'Manage_special_controller/delete';

//Manage menus
$route['control/manage-menus']								= 'Menu_controller/index';
$route['control/manage-menus/(:any)']							= 'Menu_controller/show_menus';
$route['control/manage-menus/add/(:any)']						= 'Menu_controller/add';
$route['control/manage-menus/update/(:any)']						= 'Menu_controller/updt';
$route['control/manage-menus/delete/(:any)']				     	= 'Menu_controller/delete';

//Jobs section

$route['submit-job']										= 'Jobs_controllers/submit_job';
$route['(:any)/submit-job']									= 'Jobs_controllers/submit_job';

$route['add-job']				     						= 'Jobs_controllers/add_job';
$route['(:any)/add-job']				     					= 'Jobs_controllers/add_job';

$route['all-jobs']				     						= 'Jobs_controllers/all_jobs';
$route['(:any)/all-jobs']				     				= 'Jobs_controllers/all_jobs';

$route['my-jobs']				     						= 'Jobs_controllers/all_jobs/my_job';
$route['(:any)/my-jobs']				     					= 'Jobs_controllers/all_jobs/my_job';

$route['all-quotes']				     					= 'Jobs_controllers/my_job_quote_list';
$route['(:any)/all-quotes']				     				= 'Jobs_controllers/my_job_quote_list';

$route['job-activities/(:any)']					     		= 'Jobs_controllers/job_activities';
$route['(:any)/job-activities/(:any)']					     	= 'Jobs_controllers/job_activities';

$route['job-activities/(:any)/(:any)']					     	= 'Jobs_controllers/job_activities';
$route['(:any)/job-activities/(:any)/(:any)']					= 'Jobs_controllers/job_activities';

$route['add-activity/(:any)']									= 'Jobs_controllers/add_activity';
$route['(:any)/add-activity/(:any)']							= 'Jobs_controllers/add_activity';

$route['add-activity/(:any)/(:any)']							= 'Jobs_controllers/add_activity';
$route['(:any)/add-activity/(:any)/(:any)']						= 'Jobs_controllers/add_activity';

$route['submit-activity']								     = 'Jobs_controllers/add_activity_submit';
$route['(:any)/submit-activity']							    	= 'Jobs_controllers/add_activity_submit';

//for customer 3 step sign up
//$route['customer-signup']								        = 'Customer_signup_controllers/index';
//$route['(:any)/customer-signup']						        	= 'Customer_signup_controllers/index';

//sign up from on demand
$route['ondemand']								        		= 'Customer_signup_controllers/on_demand_signup';
$route['(:any)/ondemand']						        		= 'Customer_signup_controllers/on_demand_signup';

$route['activity-details/(:any)']								= 'Jobs_controllers/activity_details';
$route['(:any)/activity-details/(:any)']						= 'Jobs_controllers/activity_details';

//Admin static pages
$route['control/static-contents']				     			= 'Static_pages_controller/index';
$route['(:any)/control/static-contents']				     	= 'Static_pages_controller/index';
$route['control/static-contents/add']							= 'Static_pages_controller/add';
$route['(:any)/control/static-contents/add']					= 'Static_pages_controller/add';
$route['control/static-contents/edit']						    = 'Static_pages_controller/updt';
$route['(:any)/control/static-contents/edit']				    = 'Static_pages_controller/updt';
$route['control/static-contents/delete/(:any)']				    = 'Static_pages_controller/delete';
$route['(:any)/control/static-contents/delete/(:any)']			= 'Static_pages_controller/delete';

//Admin pages help contents
$route['control/pages-help-contents']				     		= 'Pages_help_content_controller/index';
$route['control/pages-help-contents/add']						= 'Pages_help_content_controller/add';
$route['control/pages-help-contents/edit']						= 'Pages_help_content_controller/updt';
$route['control/pages-help-contents/delete/(:any)']				= 'Pages_help_content_controller/delete';

//Admin jobs contents
$route['control/manage-jobs']				     		        	= 'Manage_jobs_controller/index';
$route['(:any)/control/manage-jobs']				     		= 'Manage_jobs_controller/index';


//$route['control/manage-sizes/add']						     = 'Manage_jobs_controller/add';
$route['control/manage-jobs/edit']						        	= 'Manage_jobs_controller/updt';
$route['(:any)/control/manage-jobs/edit']						= 'Manage_jobs_controller/updt';
$route['control/manage-jobs/delete/(:any)']				       	= 'Manage_jobs_controller/delete';



//Admin SMS template contents
$route['control/sms-template']				     			= 'Admin_sms_template/index';
$route['control/sms-template/add']								= 'Admin_sms_template/add';
$route['control/sms-template/edit']							= 'Admin_sms_template/updt';
$route['control/sms-template/delete/(:any)']						= 'Admin_sms_template/delete';

//Manage payment types
$route['control/manage-payment-types']							= 'Manage_payment_type_controller/index';
$route['control/manage-payment-types/add']						= 'Manage_payment_type_controller/add';
$route['control/manage-payment-types/edit']						= 'Manage_payment_type_controller/updt';
$route['control/manage-payment-types/delete/(:any)']				= 'Manage_payment_type_controller/delete';

//For merchant management
$route['control/manage-merchants']								= 'Manage_merchant_controller/index';
$route['control/manage-merchants/add']							= 'Manage_merchant_controller/add';
$route['control/manage-merchants/edit']						   	= 'Manage_merchant_controller/updt';
$route['control/manage-merchants/delete/(:any)']					= 'Manage_merchant_controller/delete';

//For questions management
$route['control/manage-questions']								= 'Questions_controller/index';
$route['control/manage-questions/add']							= 'Questions_controller/add';
$route['control/manage-questions/edit/(:any)']				   	= 'Questions_controller/updt';
$route['control/manage-questions/delete/(:any)']					= 'Questions_controller/delete';


//for manage sub-admin
$route['control/manage-subadmin'] = 'Subadmin/index';
$route['control/manage-subadmin/(:num)'] = 'Subadmin/index';
$route['control/user_name_chk'] = 'Subadmin/user_name_chk';
$route['control/manage-subadmin/add'] = 'Subadmin/add';
$route['control/manage-subadmin/edit/(:any)'] = 'Subadmin/update/$1';
$route['control/manage-subadmin/delete/(:any)'] = 'Subadmin/delete/$1';
$route['control/manage-subadmin/search/(:any)/:num'] = 'Subadmin/search';

$route['control/manage-subadmin/change_status'] = 'Subadmin/change_status';
//End

//Admin email template contents
$route['control/email-template']				     			= 'Admin_email_template/index';
$route['control/email-template/add']							= 'Admin_email_template/add';
$route['control/email-template/edit']							= 'Admin_email_template/updt';
$route['control/email-template/delete/(:any)']					= 'Admin_email_template/delete';

//Admin Category Managements 
$route['control/category-manage']				     			= 'Manage_category/index';
$route['control/category-manage/add']							= 'Manage_category/add';
$route['control/category-manage/edit']							= 'Manage_category/updt';
$route['control/category-manage/delete/(:any)']					= 'Manage_category/delete';



$route['uber_rush_api_check']									= 'Customer_signup_controllers/uber_rush_api_check';
$route['(:any)/uber_rush_api_check']							= 'Customer_signup_controllers/uber_rush_api_check';

$route['customer-signup-submit']								= 'Customer_signup_controllers/customer_signup_submit';
$route['(:any)/customer-signup-submit']							= 'Customer_signup_controllers/customer_signup_submit';

$route['customer-ondemand-submit']								= 'Customer_signup_controllers/customer_ondemand_submit';
$route['(:any)/customer-ondemand-submit']						= 'Customer_signup_controllers/customer_ondemand_submit';

//$route['(:any)'] 											= 'Home_controllers/index';
$route['(:any)'] 											= 'Customer_signup_controllers/index';