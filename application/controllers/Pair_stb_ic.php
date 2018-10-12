<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Conditional_resource $conditional_resource
 * @property Services $services
 */
class Pair_stb_ic extends BaseController
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
        $this->load->library('conditional_resource');
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

        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }

    }

    public function index()
    {
        $this->theme->set_title('Pair STB IC')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/tools-conditions/pair-stb-ic.js');

        $data['sign'] = $this->config->item('message_sign');
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('tools-conditions/pair-stb-ic',$data,true);
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $lco = $this->lco_profile->get_all_lco_users($this->user_session->id);
            array_unshift($lco, array('user_id'=>0,'lco_name'=>'All Foc'));
            echo json_encode(array(
                'status' => 200,
                'lco' => $lco
            ));
        } else {
            redirect('/');
        }

    }

    public function ajax_get_subscriber_by_lco($lco_id)
    {
        if($this->input->is_ajax_request()) {
            if($lco_id==0){
                $lco_id = 1;
            }
            $subscribers = $this->subscriber_profile->get_all_subscribers($lco_id);
            //array_unshift($subscribers, array('user_id'=>0,'subscriber_name'=>'All'));
            echo json_encode(array(
                'status' => 200,
                'subscribers' => $subscribers
            ));
        } else {
            redirect('/');
        }

    }

    public function ajax_get_pairing($subscriber_id)
    {
        if($this->input->is_ajax_request()) {
            $pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($subscriber_id);
            //array_unshift($pairs, array('id' => 'all','pairing_id' => 'All'));
            echo json_encode(array('status'=>200,'pairings'=>$pairs));
        } else {
            redirect('/');
        }
    }

    public function ajax_get_regions()
    {
        if($this->input->is_ajax_request()) {
            $regions = $this->region_level_one->get_regions_with_name();
            echo json_encode(array('status'=>200,'regions'=>$regions));
        } else {
            redirect('/');
        }
    }

    public function process_pairing()
    {
        if($this->input->post())
        {
            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'tools-pair-stb-ic', $this->user_type);

                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have to create permission to Pair STB IC"));
                    exit();
                }
            }

            $condition = $this->input->post('condition');
            $subscriber_id = $this->input->post('subscriber_id');
            $pairing_id    = $this->input->post('pairing_id');
            $lco_id = $this->input->post('lco_id');
            $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($pairing_id);
            $cardNum = $pairing->internal_card_number;
            $stbExtNum = $pairing->stb_id;

            switch($condition){
                case 0:
                    $api_repair_data=array(
                        "cardNumber" => $cardNum,
                        "match" => 0,
                        "stbNo" => $stbExtNum,
                        "operatorName" => "administrator"
                    );
                    $success_messages = 'IC ['.$cardNum.'] - STB ['.$stbExtNum.'] successfully un-paired';
                    break;

                case 1:
                    $api_repair_data=array(
                        "cardNumber" => $cardNum,
                        "match" => 1,
                        "stbNo" => $stbExtNum,
                        "operatorName" => "administrator"
                    );
                    $success_messages = 'IC ['.$cardNum.'] - STB ['.$stbExtNum.'] successfully paired';
                    break;
                default:
                    break;
            }


            $api_repair_string = json_encode($api_repair_data);
            $repair_response = $this->services->repair_cancel_stb_ic($api_repair_string);

            if($repair_response->status == 500 || $repair_response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                echo json_encode(array('status'=>400,'warning_messages'=>$repair_response->message.' Please Contact with administrator. '.$administrator_info));
                exit;
            }

            if($repair_response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($repair_response->type);
                echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                exit;
            }

            if($repair_response->status == 200){

                $pair_stb_ic_logs = array(
                    'lco_id'        => $lco_id,
                    'subscriber_id' => $subscriber_id,
                    'pairing_id'    => $pairing_id,
                    'condition'     => $condition,
                    'type'          => 'USER'
                );

                $this->conditional_resource->save_pair_stb_ic($pair_stb_ic_logs);
                echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
                exit;
            }


        } else{
            redirect('tools-conditional-search');
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
}