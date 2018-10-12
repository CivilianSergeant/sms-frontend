<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends CI_Controller
{
	protected $user_session;

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('authenticate_model','auth');
		//$this->load->model('system_sections_model','menu');
		$this->load->model('Mso_profile_model', 'mso_profile');
		$this->load->model('Group_profile_model', 'group_profile');
		$this->load->model('Lco_profile_model', 'lco_profile');
		$this->load->model('Lco_group_model','lco_group');
		$this->load->model('Subscriber_profile_model', 'subscriber_profile');
		$this->load->model('billing_address_model', 'billing_address');
		$this->load->model('business_region_model','business_region');
		$this->load->model('Bulk_rechage_logs_model','bulk_rechage_logs');
		//$this->load->model('contact_model', 'contact');
		$this->load->model('user_model', 'user');
		$this->load->model('country_model','country');
		$this->load->model('division_model','division');
		$this->load->model('district_model','district');
		$this->load->model('Area_model','area');
		$this->load->model('Sub_area_model','sub_area');
		$this->load->model('Sub_sub_area_model','sub_sub_area');
		$this->load->model('Road_model','road');
		$this->load->model('package_model','package');
		$this->load->model('Program_model','program');
		$this->load->model('Role_model','role');
		$this->load->model('currency_model','currency');
		$this->load->model('Organization_model','organization');
		$this->load->model('Region_level_one_model','region_level_one');
		$this->load->model('Region_level_two_model','region_level_two');
		$this->load->model('Region_level_three_model','region_level_three');
		$this->load->model('Region_level_four_model','region_level_four');
		$this->load->model('Ic_Smart_Provider_model','ic_smart_provider');
		$this->load->model('Stb_Provider_model','stb_provider');
		$this->load->model('Ic_Smartcard_model','ic_smartcard');
		$this->load->model('Stb_model','set_top_box');
		$this->load->model('Device_model','device');
		$this->load->model('Change_pass_model','change_password');

		$this->load->model('Dashboard_model','dashboard');

		$this->load->model('User_package_model','user_package');
		$this->load->model('User_addon_package_model','user_addon_package');
		$this->load->model('Subscriber_stb_smartcard_model','subscriber_stb_smartcard');
		$this->load->model('Billing_subscriber_transaction_model','subscriber_transcation');
		$this->load->model('Billing_migrate_transaction_model','migrate_transaction');
		$this->load->model('Billing_payment_method_model','payment_method');
		$this->load->model('Billing_bank_transaction_model','bank_transaction');
		$this->load->model('Billing_pos_transaction_model','pos_transaction');
		$this->load->model('Billing_bkash_transaction_model','bkash_transaction');
		$this->load->model('Billing_cash_transaction','billing_transaction');
		$this->load->model('Billing_scratch_transaction_model','scratch_transaction');
		$this->load->model('Collector_model','collector');
		$this->load->model('User_package_assign_type_model','user_package_assign_type');
		
		
		$this->load->model('Money_receipt_book_model','money_receipt_book');
		$this->load->model('Money_receipt_model','money_receipt');
		$this->load->model('Cas_sms_response_code_model','cas_sms_response_code');
		$this->load->model('Conditional_mail_model','conditional_mail');
		$this->load->model('Conditional_search_model','conditional_search');
		$this->load->model('Conditional_scrolling_model','conditional_scrolling');
		$this->load->model('Conditional_force_osd_model','conditional_force_osd');
		$this->load->model('Conditional_limited_model','conditional_limited');
		$this->load->model('Ecm_fingerprint_model','ecm_fingerprint');
		$this->load->model('Emm_fingerprint_model','emm_fingerprint');

		$this->load->model('Notification_model','notification');
		$this->load->model("Menu_model","menus");
		$this->load->model("Role_menu_privilege_model","role_menu_privilege");

		$this->load->model("Distributor_model","distributor");
		$this->load->model("Scratch_Card_model","scratch_card");
		$this->load->model("Scratch_Card_Detail_model","scratch_card_detail");

		$this->load->model('Bank_account_model','bank_account');
		$this->load->model('Bank_account_assign_model','bank_account_assign');

		$this->load->model('Pos_machine_model','pos');
		$this->load->model('Payment_type_model','payment_type');
		$this->load->model('Subscriber_parking_model','subscriber_parking');
		$this->load->model('Subscriber_parking_reassign_log_model','subscriber_parking_reassign_log');
		$this->load->model('Ownership_transfer_model','ownership_transfer');
		$this->load->model('Card_distribution_list_model','card_distribution_list');
		$this->load->model('Iptv_program_model','Iptv_program');
		$this->load->model('Iptv_program_type_model','Iptv_program_type');
		$this->load->model('Iptv_package_model','Iptv_package');
		$this->load->model('Iptv_package_program_model','Iptv_package_program');
		$this->load->model('Iptv_category_model','Iptv_category');
		$this->load->model('Iptv_sub_category_model','Iptv_sub_category');
		$this->load->model('Iptv_category_program_model','Iptv_category_program');
		$this->load->model('Iptv_package_subscription_model','Iptv_package_subscription');
		$this->load->model('Ftp_account_model','ftp_account');
		$this->load->model('Streamer_instance_model','streamer_instance');
		$this->load->model('Map_streamer_instance_model','map_streamer_instance');
		$this->load->model('Epg_model','epg');
		$this->load->model('Epg_repeat_time_model','epg_repeat_time');
                $this->load->model('Epg_provider_model','epg_provider');
                $this->load->model('Epg_provider_channel_model','epg_provider_channel');
                $this->load->model('Map_provider_channel_model','map_provider_channel');
		$this->load->model('Language_model','language');
		$this->load->model('Content_provider_model','content_provider');
		$this->load->model('Vendor_model','vendor');
		$this->load->model('Transcoder_model','transcoder');
		$this->load->model('Device_type_model','device_type');
		$this->load->model('Content_aggregator_type_model','content_aggregator_type');
                $this->load->model('App_category_model','app_categories');
                $this->load->model('Category_programs_model','categories_programs');

		$this->load->model('Service_operator_model','service_operator');
		$this->load->model('Content_provider_category_model','content_provider_category');
                
		$this->config->load('cas');


		$this->user_session = $this->auth->is_loggedin();
		

		if (!in_array($this->uri->segment(1),array('login','authenticate'))) {
			
			if (empty($this->user_session)) {
				/*if(!empty($_COOKIE)) {
					if (!empty($_COOKIE['username']) && !empty($_COOKIE['password'])) {
						$this->auth->login_by_username($_COOKIE['username'],$_COOKIE['password'],1);
					}
				}*/
				return redirect('login');
			
			}

			// Check Authorization
			$request_uri = $_SERVER['REQUEST_URI'];
			//test($request_uri);
			$routes = $request_uri; //substr($request_uri,(strpos($request_uri,'.php/')+5),strlen($request_uri));

			$routes = explode('/',$routes);
			$class_name = strtolower($this->router->fetch_class());

			//print_r($routes);die();

			if(!in_array($class_name,array('change_password','notification','dashboard','authenticate'))){

				// if menu is not visible
				$routeObj = $this->menus->find_menu_by_route($routes[2],$this->user_session->user_type);

				if(!empty($routeObj)){
					if($routeObj->visible == 0){
						$this->session->set_flashdata('warning_messages',"Sorry! Page you trying to visit is not exist.");
						redirect('/');
					}
				}else{
					$this->session->set_flashdata('warning_messages',"Sorry! Page you trying to visit is not exist.");
					redirect('/');
				}

				// check if there is permission for user
				$role = $this->role->find_by_id($this->user_session->role_id);

				if($role->get_attribute('role_type')!="admin" && $role->get_attribute('role_type') != "subscriber"){
					$permission = $this->auth->has_route_permission($this->user_session->role_id,1,$routes[2]);

					if(!$permission){

						$this->session->set_flashdata('warning_messages',"Sorry! you don't have access permission.");
						redirect('/');
						exit;
					}
				}
			}

		}else{

			if (!empty($this->user_session)) {

				redirect('dashboard');
			}
		}
		
			

	}
}