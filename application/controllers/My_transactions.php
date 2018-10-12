<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/6/2016
 * Time: 3:45 PM
 */
class My_transactions extends PortalController
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
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/portal/transactions.js');
            $data['user_info'] = $this->user_session;
            $data['subscriber_name'] = $this->subscriber_profile->get_subscriber_name($this->user_id);
            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('subscriber-portal/transaction-history', $data, true);
        } else {

            redirect('/');
        }
    }

    public function ajax_get_statements()
    {

        if($this->input->is_ajax_request()){
            $subscriber_id = $this->user_id;
            $lco_id = $this->parent_id;
            $from_date = $this->input->post('from_date');
            $to_date   = $this->input->post('to_date');
            $pairing_id = $this->input->post('pairing_id');
            $transactions =	$this->subscriber_transcation->get_statements($lco_id,$subscriber_id,$pairing_id,$from_date,$to_date);
            echo json_encode(array('status'=>200,'transactions'=>$transactions));

        }else{
            redirect('/');
        }
    }
}