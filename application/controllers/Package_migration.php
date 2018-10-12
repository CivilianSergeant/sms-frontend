<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Billing_subscriber_transaction_model $subscriber_transcation
 * @property Subscriber_stb_smartcard_model $subscriber_stb_smartcard
 * @property User_package_model $user_package
 */
class Package_migration extends BaseController 
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

        if($this->user_type == self::LCO_LOWER){
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
        }

	}

	public function index()
	{

	}

	public function subscriber($token)
	{
		$this->theme->set_title('Dashboard - Application')
					->add_style('component.css')
					->add_script('controllers/package_migrate/package_migrate.js');
					
		$data['token'] = $token;
		$subscriber = $this->subscriber_profile->find_by_token($token);
		$data['subscriber'] = $subscriber; 

		$data['user_info']  = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('package_migration/subscriber',$data,true);
	}

    public function foc_subscriber($token)
    {
        $this->theme->set_title('Dashboard - Application')
                    ->add_style('component.css')
                    ->add_script('controllers/package_migrate/focsubscriber_package_migrate.js');
                    
        $data['token'] = $token;
        $subscriber = $this->subscriber_profile->find_by_token($token);
        $data['subscriber'] = $subscriber; 

        $data['user_info']  = $this->user_session;  
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('package_migration/foc_subscriber',$data,true);
    }

	public function unsubscribe_package()
    {
        if($this->role_type=="staff") {
            $segment = $this->uri->segment(1);
            if ($segment == "subscriber") {
                $permission = $this->menus->has_edit_permission($this->role_id, 1, 'subscriber', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to subscriber"));
                    exit;
                }
            } elseif ($segment == "foc-subscriber") {
                $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to foc subscriber"));
                    exit;
                }
            } else {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! Invalid Request"));
                exit;
            }
        }

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

            
            $transaction = $this->subscriber_transcation->get_subscribe_charge_transactions($pairing_id,$subscriber_id,$start_date);

            $amountDebit = 0;
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
            $this->migrate_transaction->migrateTransactions($transaction);
            $debit_amount = (!empty($transaction))? $amountDebit : 0;
            $unit_price = ($debit_amount == 0)? 0 : ($debit_amount/(int)$package_duration);

            $startDateObj   = new DateTime(substr($transaction_start_date,0,10));

            $dateDiff       = date_diff($startDateObj,$todayDateObj);
            $days_passed    = 0;

            if($dateDiff->days > 0 && $dateDiff->invert == 0){
                $days_passed = $dateDiff->days;
            }
            
            $remainingDays  = ($package_duration - $days_passed);

            $refund         = (float)($remainingDays * $unit_price);

            $transaction_balance = $subscriber_balance->balance;
            $refund_amount = round($refund); //array_sum($refund);
            $total_refund = round($transaction_balance + $refund_amount);

            /*$pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
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
                

            }*/

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
                
                $user_package_assign_type = $this->user_package_assign_type->get_type_by_name('Package Migrate');

                
               
                $save_credit_data['pairing_id'] = $transaction_pairing_id;
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
            

            echo json_encode(array('status'=>200,'stb_card_id'=> $stb_card_id, 'success_messages'=>'Successfully Unsubscribed'));
            exit;
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('/');
        }
    }


    public function foc_unsubscribe_package()
    {
        if($this->role_type == self::STAFF) {
            $segment = $this->uri->segment(1);
            if ($segment == "subscriber") {
                $permission = $this->menus->has_edit_permission($this->role_id, 1, 'subscriber', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to subscriber"));
                    exit;
                }
            } elseif ($segment == "foc-subscriber") {
                $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to foc subscriber"));
                    exit;
                }
            } else {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! Invalid Request"));
                exit;
            }
        }

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

                    $save_credit_data['pairing_id'] = $transaction_pairing_id;
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
        $this->theme->set_title('Dashboard - Application')
                    ->add_style('component.css')
                    ->add_script('controllers/package_migrate/package_migrate.js');
                    
        $data['token'] = $token;
        $subscriber = $this->subscriber_profile->find_by_token($token);
        $data['subscriber'] = $subscriber; 
        $data['stb_card_id'] = $this->uri->segment(4);
        $data['user_info']  = $this->user_session;  
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('package_migration/package_reassign',$data,true);

    }

    public function foc_package_reassign($token)
    {
        $this->theme->set_title('Dashboard - Application')
                    ->add_style('component.css')
                    ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                    ->add_style('kendo/css/kendo.bootstrap.min.css')
                    ->add_script('controllers/package_migrate/focsubscriber_package_migrate.js');
                    
        $data['token'] = $token;
        $subscriber = $this->subscriber_profile->find_by_token($token);
        $data['subscriber'] = $subscriber;
        $data['stb_card_id'] = $this->uri->segment(4);
        $data['user_info']  = $this->user_session;  
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('package_migration/foc_package_reassign',$data,true);

    }

    public function save_reassign_packages()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type == "staff") {
                $segment = $this->uri->segment(1);
                if ($segment == "subscriber") {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to subscriber"));
                        exit;
                    }
                } elseif ($segment == "foc-subscriber") {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to foc subscriber"));
                        exit;
                    }
                } else {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! Invalid Request"));
                    exit;
                }
            }

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

                //$pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
                //$cardNum = $pairing->internal_card_number;
                //$cardExtNum = $pairing->external_card_number;
                $start_datetimes = $end_datetimes = $flags = array();

                /*$api_data = array(
                    'cardNum' => $cardNum,
                    'operatorName' => $this->user_session->username,
                    'authCounts' => count($packages),
                );*/
                
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

                /*$api_data['productId'] = $package_ids;
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
                }*/

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
                $save_debit_data['lco_id'] =  (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id);
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
                $save_debit_data['created_by'] = (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id);
               
                // Send Conditional Mail using cas api
                /*$api_mail_data['title']  = 'Pkg Re-Assign';
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
                        'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
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
                        'creator'       => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }*/
                
                $this->subscriber_transcation->save($save_debit_data);
                $packageNames = implode(",",$package_names);
                $this->set_notification("Package Re-assigned to Subscriber","Packages [{$packageNames}] re-assigned to Subscriber [{$subscriber->get_attribute('subscriber_name')}]");
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


    public function save_foc_reassign_packages()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type == self::STAFF) {
                $segment = $this->uri->segment(1);
                if ($segment == "subscriber") {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to subscriber"));
                        exit;
                    }
                } elseif ($segment == "foc-subscriber") {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to foc subscriber"));
                        exit;
                    }
                } else {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! Invalid Request"));
                    exit;
                }
            }

            $token = $this->input->post('token');
            $user  = $this->user->find_by_token($token);
            $profile = $this->subscriber_profile->find_by_token($token);
            //$package_id = $this->input->post('package_id');
            $subscription_fee = $this->input->post('free_subscription_fee');
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
                    
                    //$package_ids[] = $package['id'];

                   
                    $save_package_assign_data = array();
                    $save_package_assign_data['user_id'] = $user->get_attribute('id');
                    $save_package_assign_data['package_id'] = $package['id'];
                    $save_package_assign_data['status'] = 1;
                    $save_package_assign_data['user_stb_smart_id'] = $stb_card_id;
                    $save_package_assign_data['charge_type'] = null;
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
                $save_debit_data['lco_id'] =  $this->user_session->id;
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
                        $save_debit_data['demo'] = 0;
                    }
                } else {
                    $save_debit_data['demo'] = 1;
                }

                $save_debit_data['transaction_date'] = date('Y-m-d H:i:s',time());

                // Send Conditional Mail using cas api
                $api_mail_data['title']  = 'Pkg Re-Assign';
                $api_mail_data['package_name'] = implode(",",$package_names);
                
                $api_mail_data['message_sign'] = $this->config->item('message_sign');
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
                        'lco_id' => $this->user_session->id,
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
                        'creator'       => $this->user_session->id,

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }

                if(!$subscription_fee)
                {
                    $this->subscriber_transcation->save($save_debit_data);
                }

                $this->set_notification("Packages Re-assigned to FOC Subscriber","Packages ".implode(',',$package_names)." re-assigned to Foc Subscriber [{$profile->get_attribute('subscriber_name')}]");

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

    /**
     * Set Notification With determine Who is the use 
     * LCO Admin, MSO Admin or LCO Staff
     * @param string $title Title of Notification
     * @param string $msg Message of Notification
     */
    private function set_notification($title,$msg)
    {
        if($this->user_type == self::MSO_LOWER){
            if($this->role_type==self::ADMIN)
            {
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);

            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->parent_id,$title,$msg,$this->user_session->id);
            }
        }elseif($this->user_type==self::LCO_LOWER){



            if($this->role_type==self::ADMIN)
            {                                    
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);    
                          
            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->parent_id,$title,$msg,$this->user_session->id);
            }
        }
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
            //test($transaction);
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
            $total_refund = round($transaction_balance + $refund_amount);
            echo json_encode(array('status'=>200,'message'=>'Amount '. $refund_amount.' will be refund to subscriber account'));
            exit;
        }else{
            redirect('/');
        }

    }

    public function ajax_get_foc_migration_amount()
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

            $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);

            $transaction = $this->subscriber_transcation->get_subscribe_charge_transactions($pairing_id, $subscriber_id, $start_date);

            $amountDebit = 0;
            $refund = 0;
            $total_refund = 0;
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
            if (!$pairing->free_subscription_fee) {
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
                $total_refund = round($transaction_balance + $refund_amount);
            }
            echo json_encode(array('status'=>200,'message'=>'Amount '. $refund_amount.' will be refund to subscriber account'));
            exit;
        }else{
            redirect('/');
        }
    }


}