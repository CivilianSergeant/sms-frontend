<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PortalController extends CI_Controller
{
    protected $user_session;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('authenticate_model','auth');
        //$this->load->model('system_sections_model','menu');
        $this->load->model('Mso_profile_model', 'mso_profile');
        $this->load->model('Lco_profile_model', 'lco_profile');
        $this->load->model('Subscriber_profile_model', 'subscriber_profile');
        $this->load->model('billing_address_model', 'billing_address');
        $this->load->model('business_region_model','business_region');
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
        $this->load->model('Device_model','device');

        $this->config->load('cas');


        $this->user_session = $this->auth->is_loggedin();

        if (!in_array($this->uri->segment(1),array('login','authenticate'))) {

            if (empty($this->user_session)) {

                return redirect('login');

            }
        }else{

            if (!empty($this->user_session)) {
                redirect('dashboard');
            }
        }
    }

}