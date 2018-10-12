<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Services $services
 * @property Conditional_resource $conditional_resource
 */
class Emm_fingerprint extends BaseController
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
        $this->load->model('settings_model','settings');
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
        $this->theme->set_title('EMM Finger Print')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/tools-conditions/emm_fingerprint.js');

        $data['sign'] = $this->config->item('message_sign');
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('tools-conditions/emm-fingerprint',$data,true);
    }

    public function ajax_get_settings_data()
    {
        $this->settings->setTblInstance(Settings_model::PRIORITIES_TBL);
        $priorities  = $this->settings->get_all();

        $this->settings->setTblInstance(Settings_model::POSITIONS_TBL);
        $positions   = $this->settings->get_all();

        $this->settings->setTblInstance(Settings_model::SIZES_TBL);
        $sizes       = $this->settings->get_all();

        $this->settings->setTblInstance(Settings_model::TYPES_TBL);
        $types       = $this->settings->get_all();

        $this->settings->setTblInstance(Settings_model::COLOR_TYPES_TBL);
        $color_types = $this->settings->get_all();

        $this->settings->setTblInstance(Settings_model::FONTS_TBL);
        $fonts       = $this->settings->get_all();

        $this->settings->setTblInstance(Settings_model::BACK_COLORS_TBL);
        $back_colors = $this->settings->get_all();

        $this->settings->setTblInstance(Settings_model::SCROLLING_SETTINGS);
        $scrolling_settings = $this->settings->get_all();
        $scrolling_settings = (!empty($scrolling_settings))? $scrolling_settings : array(array('name'=>'Default Settings','value'=>0));

        echo json_encode(array(
            'priorities' => $priorities,
            'positions'  => $positions,
            'sizes'      => $sizes,
            'types'      => $types,
            'color_types'=> $color_types,
            'fonts'      => $fonts,
            'back_colors'=> $back_colors,
            'settings'   => $scrolling_settings
        ));
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $lco = $this->lco_profile->get_all_lco_users($this->user_session->id);
            array_unshift($lco, array('user_id'=>0,'lco_name'=>'All'));
            array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All FOC'));
            //array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All MSO'));
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
            if($lco_id == -1){
                $lco_id = 1;
            }
            $subscribers = $this->subscriber_profile->get_all_subscribers($lco_id);
            array_unshift($subscribers, array('user_id'=>0,'subscriber_name'=>'All'));
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

    public function process()
    {
        if($this->input->post())
        {
            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'tools-emm-fingerprint', $this->user_type);

                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have to create permission to EMM Fingerprint"));
                    exit();
                }
            }

            $broadcast_type = $this->input->post('broadcast_type');
            $positionFlag   = $this->input->post('position_id');
            $xPosition      = $this->input->post('position_x');
            $yPosition      = $this->input->post('position_y');
            $showTime       = $this->input->post('show_duration');
            $stopTime       = $this->input->post('stop_duration');
            $overtFlag      = $this->input->post('fingerprint_overt');
            $showBKFlag     = $this->input->post('display_background');
            $showSTBNumberFlag  = $this->input->post('show_card_stb');
            $fontSize  = $this->input->post('font_size_id');
            $fontType  = $this->input->post('font_type_id');
            $colorType = $this->input->post('color_type_id');
            $fontColor = $this->input->post('font_id');
            $backgroundColor = $this->input->post('back_color_id');
            //$sign    = $this->input->post('sign');
            //$sign    = (empty($sign))? $this->config->item('message_sign') : $sign;
            $lco_id = $this->input->post('lco_id');
            $message = array();

            switch($broadcast_type){
                case 'LCO':

                    if($lco_id=="")
                    {
                        // start time will never old time ex
                        // end time wil never less than start time
                        $message['status'] = 400;
                        $message['warning_messages'] = 'Please Select LCO';
                        echo json_encode($message);
                        exit;

                    }

                    $api_string = null;
                    $data = array(
                        'start_date_time' => $this->input->post('startTime'),
                        'end_date_time'   => $this->input->post('endTime'),
                        'type_data'       => 50
                    );

                    if($lco_id == 0){
                        // for all lco_id/group
                        $data['type_operator'] = 115;
                        $data['group_id']    = 0;
                    }else{
                        // for specific lco_id/group
                        $data['type_operator'] = 116;
                        $data['group_id']    = $lco_id;
                    }

                    $data['positionFlag'] = $positionFlag;
                    $data['showTime']     = $showTime;
                    $data['stopTime']     = $stopTime;
                    $data['fontSize']     = $fontSize;
                    $data['fontType']     = $fontType;
                    $data['colorType']    = $colorType;
                    $data['fontColor']    = $fontColor;
                    $data['backgroundColor']   = $backgroundColor;
                    $data['overtFlag']         = $overtFlag;
                    $data['showBKFlag']        = $showBKFlag;
                    $data['showSTBNumberFlag'] = $showSTBNumberFlag;
                    $data['xPosition'] = $xPosition;
                    $data['yPosition'] = $yPosition;

                    $api_emm_data = $this->services->get_lco_conditional_content($data,false,false,false,false,false,true);
                    $api_string = json_encode($api_emm_data);

                    $con_emm_response = $this->services->emm_fingerprint($api_string);

                    if($con_emm_response->status == 500 || $con_emm_response->status == 400){
                        $administrator_info = $this->organization->get_administrators();
                        echo json_encode(array('status'=>400,'warning_messages'=>$con_emm_response->message.' Please Contact with administrator. '.$administrator_info));
                        exit;
                    }

                    if($con_emm_response->status != 200){
                        $code = $this->cas_sms_response_code->get_code_by_name($con_emm_response->type);
                        echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                        exit;
                    }

                    if($con_emm_response->status == 200){

                        if($lco_id>0){
                            $lco_name = $this->lco_profile->get_lco_name($lco_id);
                            $success_messages = 'Emm Fingerprint successfully sent on LCO ['.$lco_name.']';
                        }else{
                            $api_emm_data['group_id'] = 0;
                            $success_messages = 'Emm Fingerprint successfully sent on all LCO';
                        }


                        if(!empty($con_emm_response->id)){
                            // storing response in conditional_search_log
                            $api_emm_data['lco_id'] = $lco_id;
                            $this->conditional_resource->save_conditional_emm_response($con_emm_response,$api_emm_data);

                        }


                        $this->set_notification('Emm Fingerprint',$success_messages);
                        echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
                        exit;
                    }
                    break;

                case 'SUBSCRIBER':
                    $lco_id = $this->input->post('lco_id');
                    $subscriber_id = $this->input->post('subscriber_id');
                    $pairing_id    = $this->input->post('pairing_id');
                    $address_by    = $this->input->post('address_by');


                    if($subscriber_id=="")
                    {

                        $message['status'] = 400;
                        $message['warning_messages'] = 'Please select all or specific subscriber from subscriber drop-down menu';
                        echo json_encode($message);
                        exit;
                    }


                    $api_string = null;
                    $data = array(
                        'start_date_time' => $this->input->post('startTime'),
                        'end_date_time'   => $this->input->post('endTime'),
                        'type_data'       => 50
                    );

                    $data['positionFlag'] = $positionFlag;
                    $data['showTime']     = $showTime;
                    $data['stopTime']     = $stopTime;
                    $data['fontSize']     = $fontSize;
                    $data['fontType']     = $fontType;
                    $data['colorType']    = $colorType;
                    $data['fontColor']    = $fontColor;
                    $data['backgroundColor']   = $backgroundColor;
                    $data['overtFlag']         = $overtFlag;
                    $data['showBKFlag']        = $showBKFlag;
                    $data['showSTBNumberFlag'] = $showSTBNumberFlag;
                    $data['xPosition'] = $xPosition;
                    $data['yPosition'] = $yPosition;

                    if($subscriber_id == 0){

                        // for all subscriber
                        $data['type_operator'] = 115;
                        $data['group_id']      = 0;


                        $api_emm_data = $this->services->get_lco_conditional_content($data,false,false,false,false,false,true);
                        $api_string = json_encode($api_emm_data);
                        $startDate = $api_emm_data['startTime'];
                        $endDate = $api_emm_data['endTime'];

                        $con_emm_response = $this->services->emm_fingerprint($api_string);

                        if($con_emm_response->status == 500 || $con_emm_response->status == 400){
                            $administrator_info = $this->organization->get_administrators();
                            echo json_encode(array('status'=>400,'warning_messages'=>$con_emm_response->message.' Please Contact with administrator. '.$administrator_info));
                            exit;
                        }

                        if($con_emm_response->status != 200){
                            $code = $this->cas_sms_response_code->get_code_by_name($con_emm_response->type);
                            echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                            exit;
                        }

                        if($con_emm_response->status == 200){

                            if($subscriber_id>0){
                                $subscriber_name = $this->subscriber_profile->get_subscriber_name($subscriber_id);
                                $success_messages = 'Emm Fingerprint successfully sent on Subscriber ['.$subscriber_name.']';
                            }else{
                                $success_messages = 'Emm Fingerprint successfully sent on all Subscriber';
                            }


                            $api_emm_data['subscriber_id'] = $subscriber_id;
                            $api_emm_data['startTime'] = $startDate;
                            $api_emm_data['endTime'] = $endDate;

                            $this->conditional_resource->save_conditional_emm_response($con_emm_response,$api_emm_data);

                            $this->set_notification('Emm Fingerprint',$success_messages);
                            echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
                            exit;
                        }

                    } else {

                        if($lco_id=="")
                        {

                            $message['status'] = 400;
                            $message['warning_messages'] = 'Please select LCO before to select subscriber';
                            echo json_encode($message);
                            exit;
                        }

                        if($pairing_id == ''){
                            $message['status'] = 400;
                            $message['warning_messages'] = 'Please select Pairing ID before start search';
                            echo json_encode($message);
                            exit;
                        }

                        if($pairing_id != "")
                        {
                            $pairing =$this->subscriber_stb_smartcard->get_pairing_by_id($pairing_id);

                            if($address_by == "CARD"){
                                $cardNum = $pairing->internal_card_number;
                                $type_data = 48;
                            }else{
                                $cardNum = $pairing->stb_id;
                                $type_data = 52;
                            }
                            $data['type_data'] = $type_data;
                            $data['type_operator'] = 116;
                            $data['cardNum']  = $cardNum;


                            $api_emm_mail = $this->services->get_subscriber_conditional_content($data,false,false,false,false,false,true);

                            $api_string = json_encode($api_emm_mail);
                            $startDate = $api_emm_mail['startTime'];
                            $endDate = $api_emm_mail['endTime'];


                            $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                            $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];

                            $response = $this->services->emm_fingerprint($api_string);

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

                            if((!empty($response->id)) && (!empty($response->status)) && ($response->status == 200)){

                                $api_emm_data['subscriber_id'] = $subscriber_id;
                                $api_emm_data['startTime'] = $startDate;
                                $api_emm_data['endTime'] = $endDate;

                                if($address_by == "CARD"){
                                    $api_emm_data['smart_card_id'] =$data['cardNum'];
                                }else{
                                    $api_emm_data['stb_id'] = $data['cardNum'];
                                }

                                $this->conditional_resource->save_conditional_emm_response($response,$api_emm_data);

                                if($subscriber_id>0){
                                    $subscriber_name = $this->subscriber_profile->get_subscriber_name($subscriber_id);
                                    $success_messages = 'Emm Fingerprint successfully sent on Subscriber ['.$subscriber_name.']';
                                }else{
                                    $success_messages = 'Emm Fingerprint successfully sent on all Subscriber';
                                }

                                $this->set_notification('Emm Fingerprint',$success_messages);
                                echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
                                exit;
                            }

                        }

                    }

                    break;

                case 'BUSINESS_REGION':

                    $business_region_id = $this->input->post('business_region_id');

                    if($business_region_id == ''){
                        $message['status'] = 400;
                        $message['warning_messages'] = 'Please Select Business Region before to start search';
                        echo json_encode($message);
                        exit;

                    }

                    if($business_region_id != ''){
                        $data = array(
                            'start_date_time' => $this->input->post('startTime'),
                            'end_date_time'   => $this->input->post('endTime'),
                            'type_data'       => 49,
                            'type_operator'   => 116,
                            'addr'             => $business_region_id
                        );

                        $data['positionFlag'] = $positionFlag;
                        $data['showTime']     = $showTime;
                        $data['stopTime']     = $stopTime;
                        $data['fontSize']     = $fontSize;
                        $data['fontType']     = $fontType;
                        $data['colorType']    = $colorType;
                        $data['fontColor']    = $fontColor;
                        $data['backgroundColor']   = $backgroundColor;
                        $data['overtFlag']         = $overtFlag;
                        $data['showBKFlag']        = $showBKFlag;
                        $data['showSTBNumberFlag'] = $showSTBNumberFlag;
                        $data['xPosition'] = $xPosition;
                        $data['yPosition'] = $yPosition;

                        $api_emm_data = $this->services->get_address_code_conditional_content($data,false,false,false,false,false,true);

                        $api_string = json_encode($api_emm_data);
                        $startDate = $api_emm_data['startTime'];
                        $endDate = $api_emm_data['endTime'];

                        $con_emm_response = $this->services->emm_fingerprint($api_string);



                        if($con_emm_response->status == 500 || $con_emm_response->status == 400){
                            $administrator_info = $this->organization->get_administrators();
                            echo json_encode(array('status'=>400,'warning_messages'=>$con_emm_response->message.' Please Contact with administrator. '.$administrator_info));
                            exit;
                        }

                        if($con_emm_response->status != 200){
                            $code = $this->cas_sms_response_code->get_code_by_name($con_emm_response->type);
                            echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                            exit;
                        }

                        if($con_emm_response->status == 200){

                            $success_messages = 'Emm Fingerprint successfully sent on address code ['.$business_region_id.']';
                            $api_emm_data['region_code'] = $business_region_id;
                            $api_emm_data['startTime'] = $startDate;
                            $api_emm_data['endTime'] = $endDate;
                            $this->conditional_resource->save_conditional_emm_response($con_emm_response,$api_emm_data);

                            $this->set_notification('Emm Fingerprint',$success_messages);
                            echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
                            exit;
                        }

                    }

                    break;
                default:
                    break;
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