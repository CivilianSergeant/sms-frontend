<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/1/2016
 * Time: 4:11 PM
 */
class Bkash_payment extends BaseController
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
        $this->theme->set_title('Bkash Payment')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/payment/bkash.js');
        $subscriber_id = base64_decode(urldecode($this->uri->segment(2)));
        $data['subscriber_id'] = $subscriber_id;
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('payments/bkash', $data, true);
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $lco = $this->lco_profile->get_all_lco_users($this->user_session->id);
            //array_unshift($lco, array('user_id'=>0,'lco_name'=>'All'));
            array_unshift($lco, array('user_id'=>-1,'lco_name'=>'MSO'));
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
            $pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($subscriber_id);
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
            $this->form_validation->set_rules('subscriber_id','Subscriber','required');
            //$this->form_validation->set_rules('pairing_id','Pairing ID','required');
            $this->form_validation->set_rules('bkash_phone','Bkash Phone','required');
            $this->form_validation->set_rules('bkash_transaction_id','Bkash Transaction ID','required');
            $this->form_validation->set_rules('bkash_amount','Bkash Amount','required');

            if($this->form_validation->run() == FALSE){
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            //$stb_card_id = $this->input->post('stb_card_id');
            $subscriber_name = $this->subscriber_profile->get_subscriber_name($this->input->post('subscriber_id'));
            $token = $this->payment_method->get_subscriber_token($this->input->post('subscriber_id'));
            $balance = $this->subscriber_transcation->get_subscriber_balance($this->input->post('subscriber_id'));
            $prev_balance = (!empty($balance))? $balance->balance:0;



            $trn_data = array(
                'pairing_id' => null, //$this->input->post('pairing_id'),
                'subscriber_id' => $this->input->post('subscriber_id'),
                'credit' => $this->input->post('bkash_amount'),
                'balance' => $prev_balance + $this->input->post('bkash_amount'),
                'user_package_assign_type_id' => 9,
                'collection_date' => date('Y-m-d H:i:s'),
                'transaction_types' => 'c',
                'demo' => 0,
                'payment_method_id' => 4,
                'transaction_date' => date('Y-m-d'),
                'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
            );



            $save_bkash_transaction = array(
                'lco_id' => $this->input->post('lco_id'),
                'subscriber_id'   => $this->input->post('subscriber_id'),
                'pairing_id'      => null,//$this->input->post('pairing_id'),
                'amount'          => $this->input->post('bkash_amount'),
                'bkash_phone'     => $this->input->post('bkash_phone'),
                'bkash_transaction_id' => $this->input->post('bkash_transaction_id')
            );

            

            // test($save_bank_transaction);

            $trn_id = $this->subscriber_transcation->save($trn_data);
            $this->set_notification("Bkash Received","Bkash Received from Subscriber [{$subscriber_name}]");

            if($trn_id){
                $save_bkash_transaction['subscriber_transaction_id'] = $trn_id;

                $bank_trans = $this->bkash_transaction->save($save_bkash_transaction);

                if($bank_trans){

//                    $subscriber_url = ($this->user_type==self::MSO_LOWER)? 'foc-subscriber' : 'subscriber';
//                    $user_packages = $this->user_package->get_assigned_packages_by_id($this->input->post('subscriber_id'));
//                    if (!$user_packages['current_package']) {
//                        $redirect_url = site_url($subscriber_url.'/edit/' . $token->token . '#package_assign');
//                    }
//                    else{
//                        $id = (!empty($stb_card_id))? $stb_card_id : 'all';
//                        $redirect_url =  site_url($subscriber_url.'/charge/' . $token->token . '/' . $id);
//                    }
                    echo json_encode(array('status'=>200, 'redirect_to' => site_url('subscriber'), 'success_messages' => 'Transaction Successfull'));
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