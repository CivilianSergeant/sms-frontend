<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/28/2016
 * Time: 5:26 PM
 */
class Pos_payment extends BaseController
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
        $this->theme->set_title('Pos Payment')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/pos/pos.js');
        $subscriber_id = base64_decode(urldecode($this->uri->segment(2)));
        $data['subscriber_id'] = $subscriber_id;

        $data['user_id'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('pos/payment', $data, true);
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $lco = $this->lco_profile->get_all_lco_users($id);
            //array_unshift($lco, array('user_id'=>0,'lco_name'=>'All'));
            array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All FOC'));
            //array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All MSO'));
            echo json_encode(array(
                'status' => 200,
                'lco' => $lco,
                'payment_types' => $this->payment_type->get_all()
            ));

        } else {
            redirect('/');
        }

    }

    public function ajax_get_pos($user_id)
    {
        if($this->input->is_ajax_request()){
            $id = ($user_id>1)? $user_id : 1;
            $pos = $this->pos->get_pos_machines($id);
            //array_unshift($pos,array('id'=>0,'pos_machine_id'=>'All'));
            echo json_encode(array('status'=>200,'pos'=>$pos));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_collectors_by_lco($lco_id)
    {
        if($lco_id <=0){
            $lco_id = 1;
        }
        $collectors = $this->collector->get_all_collectors_by_lco($lco_id);
        echo json_encode(array(
            'status'=>200,
            'collectors'=>$collectors
        ));
    }

    public function ajax_get_subscribers($id)
    {
        if($this->input->is_ajax_request()) {
            if($id == -1){
                $id = 1;
            }
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

            $this->form_validation->set_rules('lco_id','Group','required');
            $this->form_validation->set_rules('collector_id','Collector','required');
            $this->form_validation->set_rules('subscriber_id','Subscriber','required');
            $this->form_validation->set_rules('pairing_id','Pairing ID','required');
            $this->form_validation->set_rules('pos_machine_id','Pos Machine ID','required');
            $this->form_validation->set_rules('date','Date','required');
            $this->form_validation->set_rules('time','Time','required');
            $this->form_validation->set_rules('mid','MID','required');
            $this->form_validation->set_rules('tid','TID','required|is_unique[billing_pos_transactions.tid]');
            $this->form_validation->set_rules('invoice_no','Invoice No','required');
            $this->form_validation->set_rules('batch_no','Batch No','required');
            $this->form_validation->set_rules('last_four','Last Four','required');
            $this->form_validation->set_rules('card_type','Card Type','required');
            $this->form_validation->set_rules('approval_code','Approval Code','required');
            $this->form_validation->set_rules('rpn','RPN','required');
            $this->form_validation->set_rules('amount','Amount','required');

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
                'user_package_assign_type_id' => 7,
                'collection_date' => $this->input->post('date'),
                'transaction_types' => 'c',
                'demo' => 0,
                'payment_method_id' => 7,
                'transaction_date' => date('Y-m-d'),
                'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
            );

            $pos_machine_id = $this->input->post('pos_machine_id');
            $collector_id = $this->input->post('collector_id');
            $pos = $this->pos->get_pos_by_machine_id($pos_machine_id,$collector_id);
            if(empty($pos)){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Collector assigned for this pos ['.$pos_machine_id.'] is not found'));
                exit;
            }

            $save_pos_transaction = array(
                'lco_id' => $this->input->post('lco_id'),
                'subscriber_id'   => $this->input->post('subscriber_id'),
                'collector_id'    => $this->input->post('collector_id'),
                'pairing_id'      => $this->input->post('pairing_id'),
                'pos_id'          => $pos->id,
                'date'            => $this->input->post('date'),
                'time'            => $this->input->post('time'),
                'tid'             => $this->input->post('tid'),
                'mid'             => $this->input->post('mid'),
                'invoice_no'      => $this->input->post('invoice_no'),
                'batch_no'        => $this->input->post('batch_no'),
                'last_four'       => $this->input->post('last_four'),
                'payment_type_id' => $this->input->post('card_type'),
                'amount'          => $this->input->post('amount'),
                'approval_code'   => $this->input->post('approval_code'),
                'rpn'             => $this->input->post('rpn'),
                'amount'          => $this->input->post('amount')

            );

            if(!empty($stb_card_id) && $stb_card_id != "all"){

                $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
                $cardNum = $pairing->internal_card_number;
                $cardExtNum = $pairing->external_card_number;

                $api_mail_data = array();
                $api_mail_data['title']  = 'Recharge';
                $api_mail_data['payment_method'] = 'POS';
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
                        $api_mail_data['payment_method'] = 'POS';
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
                $save_pos_transaction['subscriber_transaction_id'] = $trn_id;

                $bank_trans = $this->pos_transaction->save($save_pos_transaction);

                if($bank_trans){

                    $subscriber_url = ($this->user_type==self::MSO_LOWER)? 'foc-subscriber' : 'subscriber';
                    $user_packages = $this->user_package->get_assigned_packages_by_id($this->input->post('subscriber_id'));
                    if (!$user_packages['current_package']) {
                        $redirect_url = site_url($subscriber_url.'/edit/' . $token->token . '#package_assign');
                    }
                    else{
                        $id = (!empty($stb_card_id))? $stb_card_id : 'all';
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