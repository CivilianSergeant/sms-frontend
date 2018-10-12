<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge extends BaseController
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
    	
    	$this->theme->set_title('Recharge Subscriber')
    		 ->add_style('component.css')
             ->add_script('controllers/recharge/recharge.js');

		$data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('recharge/index', $data, true);

    }

    public function subscriber($token)
    {

    	$this->theme->set_title('Recharge Subscriber')
    		 ->add_style('component.css')
             ->add_script('controllers/recharge/recharge.js');

        $subscriber      = $this->subscriber_profile->find_by_token($token);
        $subscriber_user = $this->user->find_by_token($token);
        $data['segment'] = $this->uri->segment(1);
        $data['subscriber']   = $subscriber->get_attributes();
        $data['subscriber_user'] =  $subscriber_user->get_attributes();    
		$data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('recharge/subscriber', $data, true);
    }


    public function ajax_load_payment_methods()
    {
        if ($this->input->is_ajax_request()) {
            $payment_methods = $this->payment_method->get_all();
            echo json_encode(array('status'=>200,'payment_methods'=>$payment_methods));
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('/');
        }
        
    }

    public function ajax_load_subscribers()
    {
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        if($this->input->is_ajax_request()){
            $subscribers = $this->subscriber_profile->get_all_subscribers($id);
            echo json_encode(array('status'=>200,'subscribers'=>$subscribers));
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('/');
        }
    }



    public function ajax_get_payment_url()
    {

        if($this->input->is_ajax_request()){

            $subscriber_id     = base64_encode($this->input->post('subscriber_user_id'));
            $payment_method_id = $this->input->post('payment_method_id');

            $redirect_url = '';
            switch($payment_method_id){
                case 1:
                    $redirect_url = site_url('cash/'.urlencode($subscriber_id));
                    break;
                case 2:
                    $redirect_url = site_url('payments-online/'.urlencode($subscriber_id));
                    break;
                case 3:
                    $redirect_url = site_url('payments-scratch-card/'.urlencode($subscriber_id));
                    break;
                case 4:
                    $redirect_url = site_url('payments-bkash/'.urlencode($subscriber_id));
                    break;
                case 5:
                    $redirect_url = site_url('bank-payment/'.urlencode($subscriber_id));
                    break;
                case 6:
                    $redirect_url = site_url('bank-payment/'.urlencode($subscriber_id));
                    break;
                case 7:
                    $redirect_url = site_url('pos-payment/'.urlencode($subscriber_id));
                    break;
            }
            /*$recharge_amount   = $this->input->post('amount');
            $subscriber_user = $this->user->find_by_id($subscriber_id);

            if(!$subscriber_user->has_attributes())
            {
                echo json_encode(array('status'=>400,'warning_messages'=>'Subscriber not exist'));
                exit;
            }

            $save_credit_data['subscriber_id'] = $subscriber_id;
            $save_credit_data['lco_id'] = $this->user_session->id;
            $balance = $this->subscriber_transcation->get_subscriber_balance($save_credit_data['subscriber_id']);

            $save_credit_data['credit'] = $recharge_amount;

            if(!empty($balance)){
                $save_credit_data['balance'] = ($balance->balance+$recharge_amount);
            } else {
                $save_credit_data['balance'] = $recharge_amount;
            }
            $save_credit_data['payment_method_id'] = $payment_method_id;
            $save_credit_data['transaction_types'] = 'C';
            $save_credit_data['payment_type'] = 'MRC';
            $save_credit_data['transaction_date'] = date('Y-m-d H:i:s');
            $save_credit_data['created_by'] = $this->user_session->id;
            $save_credit_data['demo'] = 0;

            $this->subscriber_transcation->save($save_credit_data);*/
            //'success_messages'=>'Subscriber account recharged by amount '.$recharge_amount.' successfully'
            echo json_encode(array('status'=>200,'redirect_to'=>$redirect_url));
            exit;

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('recharge');
        }
    }
}