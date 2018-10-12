<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/1/2016
 * Time: 4:11 PM
 * @property Subscriber_stb_smartcard_model $subscriber_stb_smartcard
 */
class Scratch_payment extends BaseController
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
        $this->theme->set_title('Scratch Card Payment')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/payment/scratch.js');

        $subscriber_id = base64_decode(urldecode($this->uri->segment(2)));
        $data['subscriber_id'] = $subscriber_id;
        $data['user_id'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('payments/scratch', $data, true);
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            if($id > 1){
                $lco = $this->group_profile->get_lco_list($id);
            }else{
                $lco = $this->lco_profile->get_all_lco_users($id);
            }

            //array_unshift($lco, array('user_id'=>0,'lco_name'=>'All'));
            if($this->user_type == 'mso')
                array_unshift($lco, array('user_id'=>1,'lco_name'=>'All FOC'));
            //array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All MSO'));
            echo json_encode(array(
                'status' => 200,
                'lco' => $lco
            ));
        } else {
            redirect('/');
        }

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

    public function ajax_get_pairing_id($subscriber_id)
    {
        if($this->input->is_ajax_request()) {
            $pairs = $this->subscriber_stb_smartcard->get_device_by_subscriber_id($subscriber_id);
            array_unshift($pairs, array('id'=>0,'pairing_id'=>'All'));
            echo json_encode(array('status'=>200,'pairings'=>$pairs));
        } else {
            redirect('/');
        }
    }

    public function ajax_get_serial_cards()
    {
        if($this->input->is_ajax_request()){
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $serials = $this->scratch_card->get_serial_no($id,$this->user_session->user_type);
            $cards   = $this->scratch_card->get_card_no($id,$this->user_session->user_type);
            echo json_encode(array('status'=>200,'serials'=>$serials,'cards'=>$cards));
        }else{
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
            $parent_id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

            $subscriber_user = $this->user->find_by_id($subscriber_id);
            if(!$subscriber_user->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! subscriber not found'));
                exit;
            }
            $subscriber_profile = $this->subscriber_profile->find_by_id($subscriber_user->get_attribute('profile_id'));




            $card_info = $this->scratch_card->get_scratch_card_by_serial_card_no($serial_no,$card_no,$parent_id,$this->user_session->user_type);

            // if not assigned
            if(empty($card_info->group_id) && empty($card_info->lco_id) && empty($card_info->distributor_id)){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Card ['.$card_no.'] is not assigned'));
                exit;
            }

            // if vendor is not valid
            /*if($subscriber_profile->get_attribute('is_foc')){
                if($subscriber_user->get_attribute('parent_id') != $card_info->group_id){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Scratch card vendor is not valid','parent_id'=>$subscriber_user->get_attribute('parent_id'),'group_id'=>$card_info->group_id));
                    exit;
                }
            }else{
                if($subscriber_user->get_attribute('parent_id') != $card_info->lco_id){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Scratch card vendor is not valid'));
                    exit;
                }
            }*/


            // if card not found
            if(empty($card_info)){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Card ['.$card_no.'] not found'));
                exit;
            }

            // if card batch not active
            if(!$card_info->batch_active){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] batch is not active'));
                exit;
            }

            // if card batch suspended
            if($card_info->batch_suspended){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] batch is suspended'));
                exit;
            }

            $today_timestamp = time();
            $card_active_timestamp = strtotime($card_info->active_from_date);

            // if card is not activated
            if($today_timestamp < $card_active_timestamp){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] will be active after '.$card_info->active_from_date ));
                exit;
            }

            // if card is not active
            if(!$card_info->card_active){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] is not active'));
                exit;
            }

            // if card suspended
            if($card_info->card_suspended){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! card no ['.$card_info->card_no.'] is suspended'));
                exit;
            }

            // if card used
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
                'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
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

                /*$pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
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
                }*/
            } else {

                /*$pairing_cards = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($this->input->post('subscriber_id'));
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
                }*/
            }

            // test($save_bank_transaction);

            $trn_id = $this->subscriber_transcation->save($trn_data);
            $this->set_notification("Scratch Card Cash Received","Scratch-Card Cash Received from Subscriber [{$subscriber_name}]");

            if($trn_id){
                $save_scratch_transaction['subscriber_transaction_id'] = $trn_id;

                $scratch_trans = $this->scratch_transaction->save($save_scratch_transaction);

                if($scratch_trans){
                    $update_card_detail['subscriber_id'] = $this->input->post('subscriber_id');
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
                        $redirect_url =  site_url($subscriber_url.'/charge/' . $token->token . '/' . $id);
                    }

                    if($this->user_type == 'group'){
                        $redirect_url = site_url('payments-scratch-card');
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