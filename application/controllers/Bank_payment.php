<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/29/2016
 * Time: 4:19 PM
 */
class Bank_payment extends BaseController
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
    const ROUTE = 'pos-settings';

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
        $this->theme->set_title('Bank Payment')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/bank-account/payment.js');
        $subscriber_id = base64_decode(urldecode($this->uri->segment(2)));
        $data['subscriber_id'] = $subscriber_id;
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('bank-accounts/payment', $data, true);
    }

    public function ajax_get_accounts()
    {

        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

        $types = $this->payment_type->get_all();
        if($this->user_type != self::LCO_LOWER) {
            echo json_encode(array(
                'accounts' => $this->bank_account->get_all_accounts($id, $take, $skip, $filter, $sort),
                'payment_types' => $types,
                'total' => $this->bank_account->get_count_accounts($id, $filter),
                'status' => 200,

            ));
        }else{
            $accounts = $this->bank_account->get_all_lco_accounts($id,$take,$skip,$filter,$sort);

            echo json_encode(array(
                'accounts' => $accounts,
                'payment_types' => $types,
                'total'    => $this->bank_account->get_count_lco_accounts($id,$filter),
                'status' => 200,

            ));
        }

    }

    public function ajax_get_subscribers($id)
    {
        if($this->input->is_ajax_request()) {
            $subscribers = $this->subscriber_profile->get_all_subscribers($id);

            //array_unshift($subscribers, array('user_id'=>0,'subscriber_name'=>'All'));
            echo json_encode(array(
                'status' => 200,
                'subscribers'   => $subscribers,

            ));
        } else {
            redirect('/');
        }
    }

    public function ajax_get_pairing_id($id)
    {
        if($this->input->is_ajax_request()) {
            $pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($id);
            array_unshift($pairs, array('id'=>0,'pairing_id'=>'All'));
            echo json_encode(array('status'=>200,'pairings'=>$pairs));
        } else {
            redirect('/');
        }
    }

    public function payment()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'bank-payment', $this->user_type);

            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have to create permission to Bank Payment"));
                exit();
            }
        }

        if($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('bank_account_id','Bank Account','required');
            $this->form_validation->set_rules('subscriber_id','Subscriber','required');
            $this->form_validation->set_rules('pairing_id','Pairing ID','required');
            $this->form_validation->set_rules('type_id','Payment Type','required');
            $this->form_validation->set_rules('amount','Amount','required');
            $this->form_validation->set_rules('check_no','Check No','required');
            $this->form_validation->set_rules('transaction_id','Transaction ID','required|is_unique[billing_bank_transactions.transaction_id]');
            $this->form_validation->set_rules('depositor_name','Depositor Name','required');
            $this->form_validation->set_rules('depositor_phone','Depositor Phone','required');
            $this->form_validation->set_rules('deposit_date','Deposit Date','required');

            if($this->form_validation->run() == FALSE){
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $stb_card_id = $this->input->post('stb_card_id');
            $subscriber_name = $this->subscriber_profile->get_subscriber_name($this->input->post('subscriber_id'));
            $token = $this->payment_method->get_subscriber_token($this->input->post('subscriber_id'));
            $balance = $this->subscriber_transcation->get_subscriber_balance($this->input->post('subscriber_id'));
            $prev_balance = (!empty($balance))? $balance->balance:0;



            $trn_data = array(
                'pairing_id' => $this->input->post('pairing_id'),
                'subscriber_id' => $this->input->post('subscriber_id'),
                'credit' => $this->input->post('amount'),
                'balance' => $prev_balance + $this->input->post('amount'),
                'user_package_assign_type_id' => 6,
                'collection_date' => $this->input->post('deposit_date'),
                'transaction_types' => 'c',
                'demo' => 0,
                'payment_method_id' => 5,
                'transaction_date' => date('Y-m-d'),
                'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
            );



            $save_bank_transaction = array(
                'bank_account_id' => $this->input->post('bank_account_id'),
                'subscriber_id'   => $this->input->post('subscriber_id'),
                'pairing_id'      => $this->input->post('pairing_id'),
                'payment_type_id' => $this->input->post('type_id'),
                'amount'          => $this->input->post('amount'),
                'check_no'        => $this->input->post('check_no'),
                'transaction_id'  => $this->input->post('transaction_id'),
                'depositor_name'  => $this->input->post('depositor_name'),
                'depositor_phone' => $this->input->post('depositor_phone'),
                'deposit_date'  => $this->input->post('deposit_date'),
            );

            if(!empty($stb_card_id) && $stb_card_id != "all"){

                $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
                $cardNum = $pairing->internal_card_number;
                $cardExtNum = $pairing->external_card_number;

                $api_mail_data = array();
                $api_mail_data['title']  = 'Recharge';
                $api_mail_data['payment_method'] = 'Bank';
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
                        'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
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
                        $api_mail_data['payment_method'] = 'Bank';
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
                                'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
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
            $this->set_notification("Cash Received","Cash Received from Subscriber [{$subscriber_name}]");

            if($trn_id){
                $save_bank_transaction['subscriber_transaction_id'] = $trn_id;

                $bank_trans = $this->bank_transaction->save($save_bank_transaction);

                if($bank_trans){

                    $subscriber_url = ($this->user_type==self::MSO_LOWER)? 'foc-subscriber' : 'subscriber';
                    $user_packages = $this->user_package->get_assigned_packages_by_id($this->input->post('subscriber_id'));
                    if (!$user_packages['current_package']) {
                        $redirect_url = site_url($subscriber_url.'/edit/' . $token->token . '#package_assign');
                    }
                    else{
                        $id = ($this->input->post('stb_card_id'))? $this->input->post('stb_card_id') : 'all';
                        $redirect_url =  site_url($subscriber_url.'/charge/' . $token->token . '/' . $id);
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
     */
    private function set_notification($title,$msg)
    {
        if($this->user_type == self::MSO_LOWER){
            /*$role = $this->user->get_user_role($this->user_id);
            $role_type = (!empty($role))?  strtolower($role->role_type) : '';*/

            if($this->role_type==self::ADMIN)
            {
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);

            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->parent_id,$title,$msg,$this->user_session->id);
            }
        }elseif($this->user_type==self::LCO_LOWER){

            $role = $this->user->get_user_role($this->user_id);
            $role_type = (!empty($role))?  strtolower($role->role_type) : '';

            if($this->role_type==self::ADMIN)
            {
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);

            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->parent_id,$title,$msg,$this->user_session->id);
            }
        }
    }
}