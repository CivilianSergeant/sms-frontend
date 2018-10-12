<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/3/2016
 * Time: 4:45 PM
 */
class Profile extends PortalController
{
    protected $user_session;

    protected $user_type;
    protected $user_id;
    protected $parent_id;
    protected $message_sign;
    protected $role_name;
    protected $role_type;
    protected $role_id;

    const LCO_UPPER='LCO';
    const LCO_LOWER='lco';
    const MSO_UPPER='MSO';
    const MSO_LOWER='mso';
    const SUBSCRIBER="subscriber";
    const ADMIN = 'admin';
    const STAFF = 'staff';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services');
        $this->theme->set_theme('katniss');
        $this->theme->set_layout('main');

        $this->user_type = strtolower($this->user_session->user_type);
        $this->user_id = $this->user_session->id;
        $this->parent_id = $this->user_session->parent_id;

        $role = $this->user->get_user_role($this->user_id);
        $role_name = (!empty($role))?  strtolower($role->role_name) : '';
        $role_type = (!empty($role))?  strtolower($role->role_type) : '';
        $this->role_name = $role_name;
        $this->role_type = $role_type;
        $this->role_id = $this->user_session->role_id;
        $this->message_sign = $this->lco_profile->get_message_sign($this->parent_id);

        if(strtolower($this->user_type) != self::SUBSCRIBER){
            redirect('/');
        }
    }

    public function index($token)
    {

        $profile = $this->subscriber_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('Subscriber View - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/portal/subscriber-view.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('subscriber-portal/profile', $data, true);
        } else {

            redirect('/');
        }
    }
    public function user_info($token)
    {

        $profile = $this->subscriber_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('Subscriber View - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/portal/subscriber-view.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('subscriber-portal/user-info', $data, true);
        } else {

            redirect('/');
        }
    }

    public function billing_info($token)
    {
        $profile = $this->subscriber_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('Subscriber View - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/portal/subscriber-view.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('subscriber-portal/billing-info', $data, true);
        } else {

            redirect('/');
        }
    }

    public function user_documents($token)
    {
        $profile = $this->subscriber_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('Subscriber View - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/portal/subscriber-view.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('subscriber-portal/documents', $data, true);
        } else {

            redirect('/');
        }

    }

    public function subscription_info($token)
    {
        $profile = $this->subscriber_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('Subscriber View - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/portal/subscriber-view.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('subscriber-portal/subscriber-info', $data, true);
        } else {

            redirect('/');
        }
    }

    public function recharge_account($token)
    {

        $this->theme->set_title('Scratch Card - Generate Card')
            ->add_style('component.css')
            ->add_style('custom.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/portal/scratch-payment.js');
        $data['user_info']    = $this->user_session;
        $data['user_id'] =  $this->user_id;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('subscriber-portal/recharge-account', $data, true);

    }

    public function online_recharge()
    {
        $this->theme->set_title('Online Payment')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/portal/online-payment.js');

        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('subscriber-portal/online-payment', $data, true);
    }

    public function packages($token)
    {
        $this->theme->set_title('Packages')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/portal/packages.js');


        $data['subscriber_name'] = $this->subscriber_profile->get_subscriber_name($this->user_id);
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('subscriber-portal/packages', $data, true);
    }

    public function add_on_packages()
    {
        $this->theme->set_title('Dashboard - Application')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/portal/add_on_packages.js');

        $data['subscriber_name'] = $this->subscriber_profile->get_subscriber_name($this->user_id);
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('subscriber-portal/add_on_packages', $data, true);
    }

    /*public function add_on_packages()
    {
        $this->theme->set_title('Add-on Packages')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/portal/add_on_packages.js');
        $data['subscriber_name'] = $this->subscriber_profile->get_subscriber_name($this->user_id);
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('subscriber-portal/add_on_packages', $data, true);
    }*/

    public function ajax_get_profile($token)
    {

        $profile = $this->subscriber_profile->get_profile_by_token($token);
        //test($profile);
        $billing_address = $this->billing_address->get_billing_address($token);
        //$profile->hex_code = region_code_generator($profile->region_l1_code,$profile->region_l2_code,$profile->region_l3_code,$profile->region_l4_code);
        $user = $this->user->find_by_token($token);
        $id = $this->parent_id;
        $lco_profile = $this->lco_profile->get_region_code_by_id($id);
        echo json_encode(array(
            'status'=> 200,
            'lco_profile' => $lco_profile,
            'profile'=> $profile,
            'billing_address' => $billing_address,
            'countries' => $this->country->get_all()
        ));

    }

    public function ajax_load_region()
    {

        if ($this->input->is_ajax_request()) {
            $regions = $this->region_level_one->get_regions();
            echo json_encode($regions);
        } else {
            redirect('/');
        }
    }

    public function ajax_get_location($type)
    {
        if ($this->input->is_ajax_request()) {

            switch ($type) {
                case 'divisions':
                    $country_id = $this->input->post('country_id');
                    echo json_encode($this->country->get_divisions($country_id));
                    break;
                case 'districts':
                    $division_id = $this->input->post('division_id');
                    echo json_encode($this->division->get_districts($division_id));
                    break;
                case 'areas':
                    $district_id = $this->input->post('district_id');
                    echo json_encode($this->district->get_areas($district_id));
                    break;
                case 'sub_areas':
                    $area_id = $this->input->post('area_id');
                    echo json_encode($this->area->get_sub_areas($area_id));
                    break;
                case 'sub_sub_areas':
                    $sub_area_id = $this->input->post('sub_area_id');
                    echo json_encode($this->sub_area->get_sub_sub_areas($sub_area_id));
                    break;
                case 'roads':
                    $sub_area_id = $this->input->post('sub_area_id');
                    echo json_encode($this->sub_area->get_roads($sub_area_id));
                    break;

                default:
                    redirect('location');
                    break;
            }
        } else {

            rediect('location');
        }
    }

    public function ajax_get_unused_cards()
    {
        if ($this->input->is_ajax_request()) {
            $token = $this->input->post('token');
            $susbscriber = $this->user->find_by_token($token);
            $id = ($this->role_type == self::STAFF)? $this->parent_id :$this->user_id;
            $devices = $this->device->get_stb_assigned_to_lco($id);
            $cards = $this->ic_smartcard->get_smartcard_assigned_to_lco($id);
            $stb_cards = $this->subscriber_stb_smartcard->get_devices($susbscriber->get_attribute('id'));
            $unassigned_stb_cards = $this->subscriber_stb_smartcard->get_unassigned_devices($susbscriber->get_attribute('id'));
            echo json_encode(array('status'=>200,
                'stbs' => $devices,
                'cards' => $cards,
                'stb_cards' => $stb_cards,
                'unassigned_stb_cards' => $unassigned_stb_cards
            ));
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('subscriber');
        }
    }

    public function ajax_get_balance()
    {
        if ($this->input->is_ajax_request()) {
            $token = $this->input->post('token');
            $subscriber = $this->user->find_by_token($token);
            $result = $this->subscriber_transcation->get_subscriber_balance($subscriber->get_attribute('id'));
            if(!empty($result)){
                $balance = $result->balance;
            } else {
                $balance = $this->user_session->gift_amount;
            }
            echo json_encode(array('status'=>200,'balance'=>$balance));
        } else {

            $this->session->set_flashdata('warning_messages','Direct access not allowed');
        }
    }

    public function ajax_get_packages($token,$stb_card_id=null)
    {
        $user = $this->user->find_by_token($token);
        $packages = $this->package->get_packages();
        $selected_pacakges = $this->user_package->get_assigned_packages($user->get_attribute('id'),$stb_card_id);
        //echo $this->db->last_query();
        echo json_encode(array(
            'status' => 200,
            'packages'  => array_values($packages),
            'assigned_package_list' => $selected_pacakges //['packages']
        ));
    }

    public function ajx_get_addon_packages($token)
    {
        $all_package = $this->package->get_all_add_on_packages();
        echo json_encode(array(
            'status' => 200,
            'packages' => $all_package
        ));

    }

    public function ajax_get_subscriber_migration_amount()
    {
        if ($this->input->is_ajax_request()) {
            $token = $this->input->post('token');
            $subscriber = $this->user->find_by_token($token);
            $subscriber_profile = $this->subscriber_profile->find_by_token($token);
            $subscriber_id = $subscriber->get_attribute('id');

            $stb_card_id = $this->input->post('stb_card_id');
            $pairing_id = $this->input->post('pairing_id');
            $start_date = substr($this->input->post('start_date'), 0, 10);
            $packages = $this->user_package->has_package_assigned($subscriber_id, $stb_card_id);
            $subscriber_balance = $this->subscriber_transcation->get_subscriber_balance($subscriber_id);

            if (empty($packages)) {
                echo json_encode(array('status' => 400, 'warning_messages' => 'You don\'t have any package assigned to unsubscribe or You already unsubscribed'));
                exit;
            }
            $package_ids = array();

            $no_of_days = 0;
            foreach ($packages as $p) {

                $package_ids[] = $p->package_id;
                //$package = $this->package->find_by_id($p->package_id);
                //$package_duration = $package->get_attribute('duration');

                $pkg_start_date = new DateTime(substr($p->package_start_date, 0, 10));
                $pkg_expire_date = new DateTime(substr($p->package_expire_date, 0, 10));
                $pkg_time_diff = date_diff($pkg_start_date, $pkg_expire_date);

                $package_duration = (string)($pkg_time_diff->days);
                $no_of_days = $p->no_of_days;


            }

            $package_id = implode(",", $package_ids);


            $transaction = $this->subscriber_transcation->get_subscribe_charge_transactions($pairing_id, $subscriber_id, $start_date);

            $amountDebit = 0;
            $today = date('Y-m-d H:i:s');
            $todayDateObj = new DateTime($today);
            $transaction_start_date = '';
            $transaction_payment_method_id = '';
            $transaction_subscriber_id = '';
            $transaction_pairing_id = '';
            $transaction_lco_id = '';
            $transaction_demo = '';
            $transaction_package_id = '';

            if (!empty($transaction)) {
                foreach ($transaction as $trans) {
                    $transaction_payment_method = $trans->payment_method_id;
                    $transaction_subscriber_id = $trans->subscriber_id;
                    $transaction_pairing_id = $trans->pairing_id;
                    $transaction_lco_id = $trans->lco_id;
                    $transaction_package_id = $trans->package_id;
                    $transaction_demo = $trans->demo;

                    // calculation of charge fee amount and date
                    if ($trans->user_package_assign_type_id == 3) {
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj = new DateTime(substr($transaction_date, 0, 10));
                        $dateDiff = date_diff($transactionDtObj, $todayDateObj);

                        if ($dateDiff->days < $no_of_days) {
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }


                    // calculation of package assign amount and date
                    if ($trans->user_package_assign_type_id == 1) {
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj = new DateTime(substr($transaction_date, 0, 10));
                        $dateDiff = date_diff($transactionDtObj, $todayDateObj);

                        if ($dateDiff->days < $no_of_days) {
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }

                    // calculation of migration amount and date
                    if ($trans->user_package_assign_type_id == 2) {
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj = new DateTime(substr($transaction_date, 0, 10));
                        $dateDiff = date_diff($transactionDtObj, $todayDateObj);
                        if ($dateDiff->days < $no_of_days) {
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }

                    // calculation of package reassign amount and date
                    if ($trans->user_package_assign_type_id == 5) {
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj = new DateTime(substr($transaction_date, 0, 10));
                        $dateDiff = date_diff($transactionDtObj, $todayDateObj);

                        if ($dateDiff->days < $no_of_days) {
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }


                }
            }
            //test($amountDebit);
            //$this->migrate_transaction->migrateTransactions($transaction);
            $debit_amount = (!empty($transaction)) ? $amountDebit : 0;
            $unit_price = ($debit_amount == 0) ? 0 : ($debit_amount / (int)$package_duration);

            $startDateObj = new DateTime(substr($transaction_start_date, 0, 10));

            $dateDiff = date_diff($startDateObj, $todayDateObj);
            $days_passed = 0;

            if ($dateDiff->days > 0 && $dateDiff->invert == 0) {
                $days_passed = $dateDiff->days;
            }

            $remainingDays = ($package_duration - $days_passed);

            $refund = (float)($remainingDays * $unit_price);

            $transaction_balance = $subscriber_balance->balance;
            $refund_amount = round($refund); //array_sum($refund);
            $total_refund  = round($transaction_balance + $refund_amount);
            echo json_encode(array('status'=>200,'message'=>'Amount '. $refund_amount.' will be refund to subscriber account'));
            exit;

        }else{
            redirect('/');
        }

    }

    public function unsubscribe_package()
    {

        date_default_timezone_set('Asia/Dhaka');
        if ($this->input->is_ajax_request()) {
            $token = $this->input->post('token');
            $subscriber = $this->user->find_by_token($token);
            $subscriber_profile = $this->subscriber_profile->find_by_token($token);
            $subscriber_id = $subscriber->get_attribute('id');

            $stb_card_id = $this->input->post('stb_card_id');
            $pairing_id  = $this->input->post('pairing_id');
            $start_date = substr($this->input->post('start_date'),0,10);
            $packages = $this->user_package->has_package_assigned($subscriber_id,$stb_card_id);
            $subscriber_balance = $this->subscriber_transcation->get_subscriber_balance($subscriber_id);

            if(empty($packages)){
                echo json_encode(array('status'=>400,'warning_messages'=>'You don\'t have any package assigned to unsubscribe or You already unsubscribed'));
                exit;
            }
            $package_ids = array();

            $no_of_days = 0;
            foreach($packages as $p){

                $package_ids[] = $p->package_id;
                //$package = $this->package->find_by_id($p->package_id);
                //$package_duration = $package->get_attribute('duration');

                $pkg_start_date = new DateTime(substr($p->package_start_date,0,10));
                $pkg_expire_date = new DateTime(substr($p->package_expire_date,0,10));
                $pkg_time_diff   = date_diff($pkg_start_date,$pkg_expire_date);

                $package_duration = (string)($pkg_time_diff->days);
                $no_of_days = $p->no_of_days;
            }

            $package_id = implode(",",$package_ids);

            $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);

            $transaction = $this->subscriber_transcation->get_subscribe_charge_transactions($pairing_id,$subscriber_id,$start_date);

            $amountDebit = 0;
            $refund = 0;
            $total_refund = 0;
            $today      = date('Y-m-d H:i:s');
            $todayDateObj   = new DateTime($today);
            $transaction_start_date = '';
            $transaction_payment_method_id = '';
            $transaction_subscriber_id = '';
            $transaction_pairing_id = '';
            $transaction_lco_id = '';
            $transaction_demo = '';
            $transaction_package_id = '';

            if(!empty($transaction)){
                foreach($transaction as $trans){
                    $transaction_payment_method = $trans->payment_method_id;
                    $transaction_subscriber_id  = $trans->subscriber_id;
                    $transaction_pairing_id     = $trans->pairing_id;
                    $transaction_lco_id         = $trans->lco_id;
                    $transaction_package_id     = $trans->package_id;
                    $transaction_demo           = $trans->demo;

                    // calculation of charge fee amount and date
                    if($trans->user_package_assign_type_id == 3){
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj   = new DateTime(substr($transaction_date,0,10));
                        $dateDiff       = date_diff($transactionDtObj,$todayDateObj);

                        if($dateDiff->days < $no_of_days){
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }


                    // calculation of package assign amount and date
                    if($trans->user_package_assign_type_id == 1){
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj   = new DateTime(substr($transaction_date,0,10));
                        $dateDiff       = date_diff($transactionDtObj,$todayDateObj);

                        if($dateDiff->days < $no_of_days){
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }

                    // calculation of migration amount and date
                    if($trans->user_package_assign_type_id == 2){
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj   = new DateTime(substr($transaction_date,0,10));
                        $dateDiff       = date_diff($transactionDtObj,$todayDateObj);
                        if($dateDiff->days < $no_of_days){
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }

                    // calculation of package reassign amount and date
                    if($trans->user_package_assign_type_id == 5){
                        $transaction_date = $trans->transaction_date;
                        $transactionDtObj   = new DateTime(substr($transaction_date,0,10));
                        $dateDiff       = date_diff($transactionDtObj,$todayDateObj);

                        if($dateDiff->days < $no_of_days){
                            $amountDebit += $trans->debit;
                            $transaction_start_date = $transaction_date;
                        }
                    }


                }
            }

            //test($amountDebit);
            if(!$pairing->free_subscription_fee) {
                $this->migrate_transaction->migrateTransactions($transaction);
                $debit_amount = (!empty($transaction)) ? $amountDebit : 0;
                $unit_price = ($debit_amount == 0) ? 0 : ($debit_amount / (int)$package_duration);

                $startDateObj = new DateTime(substr($transaction_start_date, 0, 10));

                $dateDiff = date_diff($startDateObj, $todayDateObj);
                $days_passed = 0;

                if ($dateDiff->days > 0 && $dateDiff->invert == 0) {
                    $days_passed = $dateDiff->days;
                }

                $remainingDays = ($package_duration - $days_passed);

                $refund = (float)($remainingDays * $unit_price);

                $transaction_balance = $subscriber_balance->balance;
                $refund_amount = round($refund); //array_sum($refund);
                $total_refund = round($transaction_balance + $refund_amount);
            }


            $cardNum = $pairing->internal_card_number;
            $cardExtNum = $pairing->external_card_number;

            $api_data = array(
                'cardNum' => $cardNum,
                'operatorName' => $this->user_session->username,
                'authCounts' => 0,
                'productId' => array(0),
                'startTime' => array(datetime_to_array(date('Y-m-d H:i:s'))),
                'endTime'   => array(datetime_to_array(date('Y-m-d H:i:s'))),
                'flag'      => array(0)
            );

            $api_string = json_encode($api_data);

            // call api here
            $response = $this->services->package_update($api_string);

            if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                echo json_encode(array('status'=>400,'warning_messages'=>$response->message.'. Please Contact with administrator. '.$administrator_info));
                exit;
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                $message = '';
                if($response->type == '3073'){
                    $message = 'Migration successfully done';
                }
                echo json_encode(array('status'=>400,'warning_messages'=>$message));
                exit;
            }

            if($response->status == 200){

                $api_mail_data['title']  = 'Migration';
                $api_mail_data['amount'] = $refund;
                if($subscriber_profile->get_attribute('is_foc')){
                    $api_mail_data['message_sign'] = $this->config->item('message_sign');
                }else{
                    $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
                }

                $api_mail_data['cardNum'] = $cardNum;

                if($subscriber_profile->get_attribute('is_foc')){
                    $api_mail_data['template'] = 'msg_template/foc/migration';
                }else{
                    $api_mail_data['template'] = 'msg_template/migration';
                }

                $api_mail_data['current_balance'] = $total_refund;
                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                $api_string = json_encode($api_conditional_mail);
                $startDate = $api_conditional_mail['startTime'];
                $endDate = $api_conditional_mail['endTime'];
                $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];
                //test($api_string);die();
                $response = $this->services->conditional_mail($api_string);
                if(!empty($response->id)){
                    $conditional_mail_data = array(
                        'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
                        'subscriber_id' => $subscriber_id,
                        'smart_card_ext_id' => $cardExtNum,
                        'smart_card_id' => $cardNum,
                        'start_time'    => $startDate,
                        'end_time'      => $endDate,
                        'mail_title'    => $api_mail_data['title'],
                        'mail_content'  => $api_conditional_mail['content'],
                        'mail_sign'     => $api_conditional_mail['signStr'],
                        'mail_priority' => $api_conditional_mail['priority'],
                        'condition_return_code' => $response->id,
                        'creator'       => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }

                $this->set_notification("Migration","Migration has been done for subscriber {$subscriber_profile->get_attribute('subscriber_name')}");

                if(isset($response->type)){
                    $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                    $message = '';
                    if($response->type == '3073'){
                        $message = 'Migration successfully done';
                    }
                    $this->session->set_flashdata('success_messages',$message);
                }else{
                    $code = $this->cas_sms_response_code->get_code_by_name(514);
                    $this->session->set_flashdata('success_messages',$code->details);
                }


            }

            /*if($response->status != 200){

                echo json_encode(array('status'=>400,'warning_messages'=>'Server out of sync please refresh your browser'));
                exit;
            }*/

            if(empty($transaction)){

                // empty means there is no claimable amount in transaction if unsubscribe
                foreach($packages as $package)
                {
                    $this->user_package->remove_packages($subscriber->get_attribute('id'),$package->package_id,$stb_card_id);
                }



            } else {

                // here will be functionality to give money back if any possibilites


                foreach($packages as $p){

                    $this->user_package->remove_packages($subscriber->get_attribute('id'),$p->package_id,$stb_card_id);
                }


                if(!$pairing->free_subscription_fee){
                    $user_package_assign_type = $this->user_package_assign_type->get_type_by_name('Package Migrate');

                    $save_credit_data['pairing_id']    = $transaction_pairing_id;
                    $save_credit_data['subscriber_id'] = $transaction_subscriber_id;
                    $save_credit_data['lco_id']        = $transaction_lco_id;
                    $save_credit_data['package_id']    = $transaction_package_id;
                    $save_credit_data['credit']        = $refund_amount;
                    $save_credit_data['balance']       =  $total_refund;
                    $save_credit_data['payment_method_id'] = $transaction_payment_method_id;
                    $save_credit_data['transaction_date'] = date('Y-m-d');
                    $save_credit_data['transaction_types'] = 'C';
                    $save_credit_data['payment_type'] = 'MRC';
                    $save_credit_data['user_package_assign_type_id'] = $user_package_assign_type->id;
                    $save_credit_data['created_by'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
                    $save_credit_data['demo'] = $transaction_demo;

                    $this->subscriber_transcation->save($save_credit_data);
                }
            }


            echo json_encode(array('status'=>200,'stb_card_id'=>$stb_card_id,'success_messages'=>'Successfully Unsubscribed'));
            exit;

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('/');
        }
    }

    public function package_reassign($token)
    {
        $subscriber = $this->subscriber_profile->find_by_token($token);
        if($subscriber->has_attributes()){

        $this->theme->set_title('Package Re-assign')
            ->add_style('component.css')
            ->add_script('controllers/portal/packages.js');


        $data['token'] = $token;
        $subscriber = $this->subscriber_profile->find_by_token($token);
        $data['subscriber'] = $subscriber;
        $data['stb_card_id'] = $this->uri->segment(4);
        $data['user_info']  = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        if($subscriber->get_attribute('is_foc')){
            $this->theme->set_view('subscriber-portal/foc_package_reassign',$data,true);
        }else{
            $this->theme->set_view('subscriber-portal/package_reassign',$data,true);
        }

        }else{
            redirect('/');
        }
    }

    public function save_reassign_packages()
    {
        if ($this->input->is_ajax_request()) {


            $token = $this->input->post('token');
            $user  = $this->user->find_by_token($token);
            $subscriber  = $this->subscriber_profile->find_by_token($token);
            $packages = $this->input->post('packages');
            $pairing_id = $this->input->post('pairing_id');
            $stb_card_id = $this->input->post('stb_card_id');
            $charge_type = $this->input->post('charge_type');
            $no_of_days  = $this->input->post('no_of_days');

            $balance = $this->input->post('balance');
            $amount_charge = $this->input->post('amount_charge');

            $payment_method = $this->payment_method->get_payment_method_by_name('Cash');

            if (empty($packages)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Include Package'));
                exit;
            }

            if ($user->has_attributes()) {

                $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
                $cardNum = $pairing->internal_card_number;
                $cardExtNum = $pairing->external_card_number;
                $start_datetimes = $end_datetimes = $flags = array();

                $api_data = array(
                    'cardNum' => $cardNum,
                    'operatorName' => $this->user_session->username,
                    'authCounts' => count($packages),
                );

                $package_ids = $package_names = array();
                $user_package_assign_type = $this->user_package_assign_type->get_type_by_name('Package Reassign');

                foreach($packages as $package)
                {
                    $package_names[] = $package['package_name'];
                    $package_ids[] = $package['id'];
                    $start_datetimes[] = datetime_to_array($this->input->post('start_date'));
                    $end_datetimes[] = datetime_to_array($this->input->post('expire_date'));
                    $flags[] = 1;
                }

                $api_data['productId'] = $package_ids;
                $api_data['startTime'] = $start_datetimes;
                $api_data['endTime']   = $end_datetimes;
                $api_data['flag']      = $flags;
                $api_string = json_encode($api_data);

                // call api here
                $response = $this->services->package_update($api_string);
                if($response->status == 500 || $response->status == 400){
                    $administrator_info = $this->organization->get_administrators();
                    echo json_encode(array('status'=>400,'warning_messages'=>$response->message.'. Please Contact with administrator. '.$administrator_info));
                    exit;
                }

                if($response->status != 200){
                    $type = (!empty($response->type))? $response->type : 514;
                    $code = $this->cas_sms_response_code->get_code_by_name($type);
                    echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                    exit;
                }

                foreach($packages as $package){

                    $save_package_assign_data = array();
                    $save_package_assign_data['user_id'] = $user->get_attribute('id');
                    $save_package_assign_data['package_id'] = $package['id'];
                    $save_package_assign_data['status'] = 1;
                    $save_package_assign_data['user_stb_smart_id'] = $stb_card_id;
                    $save_package_assign_data['charge_type'] = $charge_type;
                    $save_package_assign_data['package_start_date'] = $this->input->post('start_date');
                    $save_package_assign_data['package_expire_date'] = $this->input->post('expire_date');
                    $save_package_assign_data['created_by'] = $this->user_session->id;
                    $save_package_assign_data['no_of_days'] = $no_of_days;
                    $save_package_assign_data['user_package_type_id'] = $user_package_assign_type->id;

                    $this->user_package->save($save_package_assign_data);

                }

                // save subscriber transaction during package assign
                $save_debit_data['pairing_id'] = $pairing_id;
                $save_debit_data['subscriber_id'] = $user->get_attribute('id');
                $save_debit_data['lco_id'] =  $this->parent_id;
                $save_debit_data['package_id'] = implode(",",$package_ids);

                if($charge_type == 1){
                    $save_debit_data['debit']  = $balance;
                    $save_debit_data['balance'] = ($balance-$amount_charge);
                } else {
                    $balance = ($balance-$amount_charge);
                    $save_debit_data['debit']  = ($amount_charge);
                    $save_debit_data['balance'] = $balance;
                }

                $save_debit_data['transaction_types'] = 'D';
                $save_debit_data['payment_type'] = 'MRC';
                $save_debit_data['payment_method_id'] = (!empty($payment_method))? $payment_method->id : null;
                $save_debit_data['user_package_assign_type_id'] = $user_package_assign_type->id;
                $last_balance = $this->subscriber_transcation->get_subscriber_balance($user->get_attribute('id'));

                if(!empty($last_balance)){
                    if($last_balance->demo == 1){

                        $save_debit_data['demo'] = 1;
                    }else{
                        /*$user_package_assign_type = $this->user_package_assign_type->get_by_name('Package Assign');
                        $save_debit_data['user_package_assign_type_id'] = $user_package_assign_type->id;*/
                        $save_debit_data['demo'] = 0;
                    }
                } else {
                    /*$user_package_assign_type = $this->user_package_assign_type->get_by_name('Charge Free');
                    $save_debit_data['user_package_assign_type_id'] = $user_package_assign_type->id;*/
                    $save_debit_data['demo'] = 1;
                }

                $save_debit_data['transaction_date'] = date('Y-m-d H:i:s',time());
                $save_debit_data['created_by'] =  $this->user_id;

                // Send Conditional Mail using cas api
                $api_mail_data['title']  = 'Pkg Re-Assign';
                $api_mail_data['package_name'] = implode(",",$package_names);
                $api_mail_data['amount'] = $save_debit_data['balance'];
                $api_mail_data['message_sign'] = ($this->message_sign !=null)? $this->message_sign : $this->config->item('message_sign');
                $api_mail_data['expire_date']  = $this->input->post('expire_date');
                $api_mail_data['cardNum'] = $cardNum;
                $api_mail_data['template'] = 'msg_template/package_assign';

                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                $api_string = json_encode($api_conditional_mail);
                $startDate = $api_conditional_mail['startTime'];
                $endDate = $api_conditional_mail['endTime'];
                $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];

                //test($api_string);die();
                $response = $this->services->conditional_mail($api_string);
                if(!empty($response->id)){
                    $conditional_mail_data = array(
                        'lco_id' => $this->parent_id ,
                        'subscriber_id' => $user->get_attribute('id'),
                        'smart_card_ext_id'=>$cardExtNum,
                        'smart_card_id' => $cardNum,
                        'start_time'    => $startDate,
                        'end_time'      => $endDate,
                        'mail_title'    => $api_mail_data['title'],
                        'mail_content'  => $api_conditional_mail['content'],
                        'mail_sign'     => $api_conditional_mail['signStr'],
                        'mail_priority' => $api_conditional_mail['priority'],
                        'condition_return_code' => $response->id,
                        'creator'       =>  $this->user_id,

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }

                $this->subscriber_transcation->save($save_debit_data);
                $package_name = implode(",",$package_names);
                $this->set_notification("Package Re-assigned to Subscriber","Packages [{$package_name}] re-assigned to Subscriber [{$subscriber->get_attribute('subscriber_name')}]");
                echo json_encode(array('status'=>200,'success_messages'=>'Packages assigned successfully to user ' . $user->get_attribute('username')));
                exit;

            } else {

                echo json_encode(array('status'=>400,'warning_messages'=>'User account not exist. Please Create User Login information'));
                exit;
            }

        } else {

            $this->session->set_flashdata();
            redirect('subscriber');
        }
    }


    public function save_add_on_package()
    {
        if ($this->input->is_ajax_request()) {


            $id = $this->input->post('subscriber');
            $user  = $this->user->find_by_id($id);
            $subscriber  = $this->subscriber_profile->find_by_token($user->get_attribute('token'));

            $package_name = $this->input->post('package_name');
            $package_id = $this->input->post('id');
            $pairing_id = $this->input->post('pairing_id');
            $stb_card_id = $this->input->post('stb_card_id');
            $no_of_days  = $this->input->post('duration');


            $amount_charge = $this->input->post('price');
            //test($this->input->post());die();

            $payment_method = $this->payment_method->get_payment_method_by_name('Cash');

            if (empty($package_name)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Add Package'));
                exit;
            }

            if ($user->has_attributes()) {

                $last_balance = $this->subscriber_transcation->get_subscriber_balance($user->get_attribute('id'));
                $balance = (!empty($last_balance->balance))? $last_balance->balance : 0;

                if($balance < $amount_charge){
                    echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! Subscriber don't have sufficient balance to purchase add-on package"));
                    exit;
                }

                $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);

                $cardNum = $pairing->internal_card_number;
                $cardExtNum = $pairing->external_card_number;
                $start_datetimes = $end_datetimes = $flags = array();

                $is_assigned =  $this->user_addon_package->has_package_assigned($user->get_attribute('id'),$stb_card_id,$package_id);

                if(!empty($is_assigned)){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Package Already Assigned'));
                    exit;
                }

                $api_data = array(
                    'cardNum' => $cardNum,
                    'operatorName' => $this->user_session->username,
                    'authCounts' => 1,
                );

                $package_ids = $package_names = array();
                $user_package_assign_type = $this->user_package_assign_type->get_type_by_name('Package Assign');
                $package_names[] = $package_name;
                $package_ids[] = $package_id;
                /*foreach($packages as $package)
                {*/


                $start_datetimes[] = datetime_to_array($this->input->post('start_date'));
                $end_datetimes[] = datetime_to_array($this->input->post('expire_date'));
                $flags[] = 1;
                /*}*/

                $api_data['productId'] = $package_ids;
                $api_data['startTime'] = $start_datetimes;
                $api_data['endTime']   = $end_datetimes;
                $api_data['flag']      = $flags;
                $api_string = json_encode($api_data);

                // call api here
                $response = $this->services->package_update($api_string);

                if($response->status == 500 || $response->status == 400){
                    $administrator_info = $this->organization->get_administrators();
                    echo json_encode(array('status'=>400,'warning_messages'=>$response->message.' Please Contact with administrator. '.$administrator_info));
                    exit;
                }

                if($response->status != 200){
                    $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                    echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                    exit;
                }

                /*if($response->status != 200){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Server out of sync please refresh your browser'));
                    exit;
                }*/

                /* foreach($packages as $package){*/

                $save_package_assign_data = array();
                $save_package_assign_data['user_id'] = $user->get_attribute('id');
                $save_package_assign_data['package_id'] = $package_id;
                $save_package_assign_data['status'] = 1;
                $save_package_assign_data['user_stb_smart_id'] = $stb_card_id;

                $save_package_assign_data['package_start_date'] = $this->input->post('start_date');
                $save_package_assign_data['package_expire_date'] = $this->input->post('expire_date');
                //$save_package_assign_data['created_by'] = ($this->role_name == self::STAFF)? $this->parent_id : $this->user_id;
                $save_package_assign_data['no_of_days'] = $no_of_days;
                $save_package_assign_data['user_package_type_id'] = $user_package_assign_type->id;
                // test($save_package_assign_data);die();
                $user_package_id = $this->user_addon_package->save($save_package_assign_data);

                /* }*/

                // save subscriber transaction during package assign
                $save_debit_data['pairing_id'] = $pairing_id;
                $save_debit_data['subscriber_id'] = $user->get_attribute('id');
                $save_debit_data['lco_id'] =  $this->parent_id;
                $save_debit_data['package_id'] = $package_id;


                $remaining_balance = ($balance-$amount_charge);
                $save_debit_data['debit']  = ($amount_charge);
                $save_debit_data['balance'] = $remaining_balance;


                $save_debit_data['transaction_types'] = 'D';
                $save_debit_data['payment_type'] = 'MRC';
                $save_debit_data['payment_method_id'] = (!empty($payment_method))? $payment_method->id : null;
                $save_debit_data['user_package_assign_type_id'] = $user_package_assign_type->id;



                if(!empty($last_balance)){
                    if($last_balance->demo == 1){
                        $save_debit_data['demo'] = 1;
                    }else{
                        $save_debit_data['demo'] = 0;
                    }
                } else {
                    $save_debit_data['demo'] = 1;
                }

                $save_debit_data['transaction_date'] = date('Y-m-d H:i:s',time());
                //$save_debit_data['created_by'] = ($this->role_name == self::STAFF)? $this->parent_id : $this->user_id;


                // Send Conditional Mail using cas api

                $api_mail_data['title']  = 'Charge';
                $api_mail_data['package_name'] = $package_name;
                $api_mail_data['amount'] = $save_debit_data['balance'];
                $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
                $api_mail_data['expire_date']  = $this->input->post('expire_date');
                $api_mail_data['cardNum'] = $cardNum;
                $api_mail_data['template'] = 'msg_template/package_assign';

                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                $api_string = json_encode($api_conditional_mail);
                $startDate = $api_conditional_mail['startTime'];
                $endDate = $api_conditional_mail['endTime'];
                $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];
                $response = $this->services->conditional_mail($api_string);
                if(!empty($response->id)){
                    $conditional_mail_data = array(
                        'lco_id' =>  $this->parent_id ,
                        'subscriber_id' => $user->get_attribute('id'),
                        'smart_card_ext_id' => $cardExtNum,
                        'smart_card_id' => $cardNum,
                        'start_time'    => $startDate,
                        'end_time'      => $endDate,
                        'mail_title'    => $api_mail_data['title'],
                        'mail_content'  => $api_conditional_mail['content'],
                        'mail_sign'     => $api_conditional_mail['signStr'],
                        'mail_priority' => $api_conditional_mail['priority'],
                        'condition_return_code' => $response->id,
                        'creator'       =>  $this->user_id,

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }

                $this->subscriber_transcation->save($save_debit_data);
                //$package_name = implode(",",$package_names);
                $this->set_notification("Add-on Package Assigned to Subscriber","Packages [{$package_name}] assigned to Subscriber [{$subscriber->get_attribute('subscriber_name')}]");
                echo json_encode(array('status'=>200,'user_package_id'=>$user_package_id,'success_messages'=>'Packages assigned successfully to user ' . $subscriber->get_attribute('subscriber_name')));
                exit;

            } else {

                echo json_encode(array('status'=>400,'warning_messages'=>'User account not exist. Please Create User Login information'));
                exit;
            }

        } else {


            redirect('/');
        }
    }

    public function ajax_get_addon_packages($token){
        if($this->input->is_ajax_request()){
            $user = $this->user->find_by_token($token);
            $assigned_packages = $this->user_addon_package->get_assigned_packages($user->get_attribute('id'));
            echo json_encode(array('status'=>200,'assigned_packages'=>$assigned_packages));
        }else{
            redirect('/');
        }
    }



    public function ajax_get_pairing_id($subscriber_id)
    {
        if($this->input->is_ajax_request()) {
            $pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($subscriber_id);
            array_unshift($pairs, array('id'=>0,'pairing_id'=>'All'));
            echo json_encode(array('status'=>200,'pairings'=>$pairs));
        } else {
            redirect('/');
        }
    }

    public function payment()
    {


        if($this->input->is_ajax_request()) {


            $this->form_validation->set_rules('subscriber_id','Subscriber','required');
            $this->form_validation->set_rules('pairing_id','Pairing ID','required');
            $this->form_validation->set_rules('serial_no','Serial No','required');
            $this->form_validation->set_rules('card_no','Card No','required');

            if($this->form_validation->run() == FALSE){
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $subscriber_id = $this->input->post('subscriber_id');
            $serial_no = $this->input->post('serial_no');
            $card_no   = $this->input->post('card_no');
            $parent_id = $this->parent_id;

            $subscriber_user = $this->user->find_by_id($subscriber_id);

            if(!$subscriber_user->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! subscriber not found'));
                exit;
            }

            $subscriber_profile = $this->subscriber_profile->find_by_id($subscriber_user->get_attribute('profile_id'));


            $card_info = $this->scratch_card->get_scratch_card_by_serial_card_no($serial_no,$card_no,$parent_id);


            if(empty($card_info)){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Card ['.$card_no.'] not found'));
                exit;
            }

            // if vendor is not valid
            if($subscriber_profile->get_attribute('is_foc')){
                if($subscriber_user->get_attribute('parent_id') != $card_info->group_id){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Scratch card vendor is not valid'));
                    exit;
                }
            }else{
                if($subscriber_user->get_attribute('parent_id') != $card_info->lco_id){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Scratch card vendor is not valid'));
                    exit;
                }
            }


            if(!$card_info->batch_active){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] batch is not active'));
                exit;
            }

            if($card_info->batch_suspended){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] batch is suspended'));
                exit;
            }

            $today_timestamp = time();
            $card_active_timestamp = strtotime($card_info->active_from_date);

            if($today_timestamp < $card_active_timestamp){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] will be active after '.$card_info->active_from_date ));
                exit;
            }

            if(!$card_info->card_active){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] is not active'));
                exit;
            }

            if($card_info->card_suspended){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] is suspended'));
                exit;
            }

            if($card_info->card_used){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] is already used'));
                exit;
            }

            $amount = $card_info->value;
            $stb_card_id = $this->input->post('pairing_id');
            $subscriber_name = $this->subscriber_profile->get_subscriber_name($this->input->post('subscriber_id'));
            $token = $this->payment_method->get_subscriber_token($this->input->post('subscriber_id'));
            $balance = $this->subscriber_transcation->get_subscriber_balance($this->input->post('subscriber_id'));
            $prev_balance = (!empty($balance))? $balance->balance:0;



            $trn_data = array(
                'pairing_id' => $this->input->post('stb_card_id'),
                'subscriber_id' => $this->input->post('subscriber_id'),
                'credit' => $amount,
                'balance' => $prev_balance + $amount,
                'user_package_assign_type_id' => 8,
                'collection_date' => date('Y-m-d H:i:s'),
                'transaction_types' => 'c',
                'demo' => 0,
                'payment_method_id' => 3,
                'transaction_date' => date('Y-m-d'),
                'lco_id' => $this->parent_id,
            );



            $save_scratch_transaction = array(
                'lco_id' => $this->input->post('lco_id'),
                'subscriber_id'   => $this->input->post('subscriber_id'),
                'pairing_id'      => $this->input->post('pairing_id'),
                'serial_no'       => $this->input->post('serial_no'),
                'card_no'         => $this->input->post('card_no'),
                'amount'          => $amount,


            );

            if(!empty($stb_card_id) && $stb_card_id != "all"){

                $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
                $cardNum = $pairing->internal_card_number;
                $cardExtNum = $pairing->external_card_number;

                $api_mail_data = array();
                $api_mail_data['title']  = 'Recharge';
                $api_mail_data['payment_method'] = 'Scratch';
                $api_mail_data['amount'] = $trn_data['balance'];
                $api_mail_data['recharge_amount'] = $this->input->post('amount');
                $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
                $api_mail_data['template'] = 'msg_template/single_recharge';
                $api_mail_data['cardNum'] = $cardNum;

                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                $api_string = json_encode($api_conditional_mail);
                $startDate = $api_conditional_mail['startTime'];
                $endDate = $api_conditional_mail['endTime'];
                $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];
                $response = $this->services->conditional_mail($api_string);

                if($response->status == 500 || $response->status == 400){
                    $administrator_info = $this->organization->get_administrators();
                    echo json_encode(array('status'=>400,'warning_messages'=>$response->message.' Please Contact with administrator. '.$administrator_info));
                    exit;
                }

                if($response->status != 200){
                    $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                    echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                    exit;
                }

                if(!empty($response->id)){
                    $conditional_mail_data = array(
                        'lco_id' => $this->parent_id,
                        'subscriber_id' => $this->input->post('subscriber_id'),
                        'smart_card_ext_id' => $cardExtNum,
                        'smart_card_id' => $cardNum,
                        'start_time'    => $startDate,
                        'end_time'      => $endDate,
                        'mail_title'    => $api_mail_data['title'],
                        'mail_content'  => $api_conditional_mail['content'],
                        'mail_sign'     => $api_conditional_mail['signStr'],
                        'mail_priority' => $api_conditional_mail['priority'],
                        'condition_return_code' => $response->id,
                        'type'			=> 'SYSTEM'

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }
            } else {
                $pairing_cards = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($this->input->post('subscriber_id'));
                $pairings = array();

                if(!empty($pairing_cards)){

                    foreach($pairing_cards as $p){
                        $pairings[] = $p->pairing_id;
                    }

                    foreach($pairing_cards as $p){
                        $api_mail_data = array();
                        $api_mail_data['title']  = 'Recharge';
                        $api_mail_data['payment_method'] = 'Scratch';
                        $api_mail_data['pairings'] = implode(",",$pairings);
                        $api_mail_data['amount'] = $trn_data['balance'];
                        $api_mail_data['recharge_amount'] = $this->input->post('amount');
                        $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
                        $api_mail_data['template'] = 'msg_template/all_recharge';
                        $api_mail_data['cardNum'] = $p->internal_card_number;

                        $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                        $api_string = json_encode($api_conditional_mail);
                        $startDate = $api_conditional_mail['startTime'];
                        $endDate = $api_conditional_mail['endTime'];
                        $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                        $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];

                        $response = $this->services->conditional_mail($api_string);

                        if($response->status == 500 || $response->status == 400){
                            $administrator_info = $this->organization->get_administrators();
                            echo json_encode(array('status'=>400,'warning_messages'=>$response->message.' Please Contact with administrator. '.$administrator_info));
                            exit;
                        }

                        if($response->status != 200){
                            $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                            echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                            exit;
                        }

                        if(!empty($response->id)){
                            $conditional_mail_data = array(
                                'lco_id' =>  $this->parent_id,
                                'subscriber_id' => $this->input->post('subscriber_id'),
                                'smart_card_ext_id' => $p->external_card_number,
                                'smart_card_id' => $p->internal_card_number,
                                'start_time'    => $startDate,
                                'end_time'      => $endDate,
                                'mail_title'    => $api_mail_data['title'],
                                'mail_content'  => $api_conditional_mail['content'],
                                'mail_sign'     => $api_conditional_mail['signStr'],
                                'mail_priority' => $api_conditional_mail['priority'],
                                'condition_return_code' => $response->id,
                                'type'			=> 'SYSTEM'

                            );
                            $this->conditional_mail->save($conditional_mail_data);
                        }
                    }
                }
            }

            // test($save_bank_transaction);

            $trn_id = $this->subscriber_transcation->save($trn_data);
            //$this->set_notification("Scratch Card Recharge","Amount of {$trn_data['credit']} Cash Received successfully by Scratch-Card recharge from Subscriber [{$subscriber_name}]",self::MSO_LOWER);
            $this->set_notification("Scratch Card Recharge","Amount of {$trn_data['credit']} Cash Received successfully by Scratch-Card recharge from Subscriber [{$subscriber_name}]");
            //$this->set_notification("Scratch Card Recharge","Amount of {$trn_data['credit']} Cash Received successfully by Scratch-Card recharge");

            if($trn_id){
                $save_scratch_transaction['subscriber_transaction_id'] = $trn_id;

                $scratch_trans = $this->scratch_transaction->save($save_scratch_transaction);

                if($scratch_trans){
                    $update_card_detail['is_used'] = 1;
                    $update_card_detail['updated_at'] = date('Y-m-d H:i:s');
                    $update_card_detail['updated_by'] = $this->user_id;

                    $this->scratch_card_detail->save($update_card_detail,$card_info->card_detail_id);

                    $subscriber_url = ($this->user_type==self::MSO_LOWER)? 'foc-subscriber' : 'subscriber';
                    $user_packages = $this->user_package->get_assigned_packages_by_id($this->input->post('subscriber_id'));

                    if (!$user_packages['current_package']) {
                        $redirect_url = site_url($subscriber_url.'/edit/' . $token->token . '#package_assign');
                    }
                    else{
                        $id = (!empty($stb_card_id))? $stb_card_id : 'all';
                        $redirect_url =  site_url('subscription-info/'.$token->token);
                    }

                    echo json_encode(array('status'=>200, 'redirect_to' => $redirect_url, 'success_messages' => 'Transaction Successfull'));
                    exit;
                }
            }

            echo json_encode(array('status'=>200,'success_messages'=>'Transaction successfully saved'));

        }else{
            redirect('/');
        }
    }



    /**
     * Set Notification With determine Who is the use
     * LCO Admin, MSO Admin or LCO Staff
     * @param string $title Title of Notification
     * @param string $msg Message of Notification
     * @param string $user_type receiver user type
     */
    private function set_notification($title,$msg)
    {
        $this->notification->save_subscriber_notification($this->parent_id,$this->user_id,$title,$msg,$this->user_id);

    }
}