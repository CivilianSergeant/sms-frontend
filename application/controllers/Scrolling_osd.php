<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scrolling_osd extends BaseController
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
        $this->theme->set_title('Conditional Scrolling OSD')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/tools-conditions/conditional_scrolling.js');

        $data['sign'] = $this->config->item('message_sign');
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('tools-conditions/scrolling-osd',$data,true);
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
        $settings[] = array('id'=>'0','name'=>'Default Settings');
        $settings[] = array('id'=>'-1','name'=>'New Settings');
        foreach($scrolling_settings as $ss){
            $settings[] = array(
                'id'=>$ss->id,
                'name' => $ss->name,
                'priority_id'   => $ss->priority,
                'position_id'   => $ss->position,
                'size_id'       => $ss->size,
                'type_id'       => $ss->type,
                'color_type_id' => $ss->color_type,
                'font_id'       => $ss->font,
                'back_color_id' => $ss->back_color
            );
        }
        //$scrolling_settings = (!empty($scrolling_settings))? $scrolling_settings : array(array('name'=>'Default Settings','value'=>0),array('name'=>'New Settings','value'=>-1));

        echo json_encode(array(
           'priorities' => $priorities,
           'positions'  => $positions,
           'sizes'      => $sizes,
           'types'      => $types,
           'color_types'=> $color_types,
           'fonts'      => $fonts,
           'back_colors'=> $back_colors,
           'settings'   => $settings
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
            $broadcast_type = $this->input->post('broadcast_type');
            $settings_id    = $this->input->post('settings_id');
            $settings_name  = $this->input->post('settings_name');
            $priority_id    = $this->input->post('priority_id');
            $position_id    = $this->input->post('position_id');
            $font_size_id   = $this->input->post('size_id');
            $font_type_id   = $this->input->post('type_id');
            $color_type_id  = $this->input->post('color_type_id');
            $font_id        = $this->input->post('font_id');
            $back_color_id  = $this->input->post('back_color_id');
            $display_times  = $this->input->post('display_times');
            $content        = $this->input->post('content');

            $content        = trim(preg_replace("/(\r\n|\r|\n)/"," ",$content));

            $sign    = $this->input->post('sign');
            $sign    = (empty($sign))? $this->config->item('message_sign') : $sign;
            $lco_id = $this->input->post('lco_id');
            $message = array();

            if($settings_id == -1){
                if(empty($settings_name)){
                    echo json_encode(array('status'=>400,'warning_messages'=>"Settings Name required"));
                    exit;
                }
                $this->settings->setTblInstance(Settings_model::SCROLLING_SETTINGS);
                $settings = $this->settings->find_settings_by_name($settings_name);

                if(!empty($settings)){
                    echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! Settings Name not available"));
                    exit;
                }

                $settings_data = array(
                    'name'       => $settings_name,
                    'priority'   => $priority_id,
                    'position'   => $position_id,
                    'size'       => $font_size_id,
                    'type'       => $font_type_id,
                    'color_type' => $color_type_id,
                    'font'       => $font_id,
                    'back_color' => $back_color_id
                );



                $this->settings->save($settings_data);

            }

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

                    $data['conditionLength'] = 11;
                    $data['contentLength']   = 272;
                    $data['condCounts']      = 1;
                    $data['content']         = $content;
                    $data['displayCounts']   = $display_times;
                    $data['priority']        = $priority_id;
                    $data['position']        = $position_id;
                    $data['font_size']       = $font_size_id;
                    $data['font_type']       = $font_type_id;
                    $data['font_color']      = $font_id;
                    $data['back_color']      = $back_color_id;

                    $api_scrolling_data = $this->services->get_lco_conditional_content($data,false,true);

                    $api_string = json_encode($api_scrolling_data);

                    $con_search_response = $this->services->conditional_osd($api_string);

                    if($con_search_response->status == 500 || $con_search_response->status == 400){
                        $administrator_info = $this->organization->get_administrators();
                        echo json_encode(array('status'=>400,'warning_messages'=>$con_search_response->message.' Please Contact with administrator. '.$administrator_info));
                        exit;
                    }

                    if($con_search_response->status != 200){
                        $code = $this->cas_sms_response_code->get_code_by_name($con_search_response->type);
                        echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                        exit;
                    }

                    if($con_search_response->status == 200){

                        if($lco_id>0){
                            $lco_name = $this->lco_profile->get_lco_name($lco_id);
                            $success_messages = 'Conditional Scrolling OSD successfully sent on LCO ['.$lco_name.']';
                        }else{
                            $api_scrolling_data['group_id'] = 0;
                            $success_messages = 'Conditional Scrolling OSD successfully sent on all LCO';
                        }



                        if(!empty($con_search_response->id)){
                            // storing response in conditional_search_log
                            $api_scrolling_data['lco_id'] = $lco_id;
                            $api_scrolling_data['settings_id'] = $settings_id;
                            $api_scrolling_data['display_times'] = $display_times;
                            $api_scrolling_data['content'] = $content;
                            $this->conditional_resource->save_conditional_scrolling_response($con_search_response,$api_scrolling_data);

                        }


                        $this->set_notification("Scrolling OSD",$success_messages);
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

                    if($subscriber_id == 0){

                        // for all subscriber
                        $data['type_operator'] = 115;
                        $data['group_id']      = 0;
                        $data['start_date_time'] = $this->input->post('startTime');
                        $data['end_date_time']   = $this->input->post('endTime');
                        $data['conditionLength'] = 11;
                        $data['contentLength']   = 272;
                        $data['condCounts']      = 1;
                        $data['content']         = $content;
                        $data['displayCounts']   = $display_times;
                        $data['priority']        = $priority_id;
                        $data['position']        = $position_id;
                        $data['font_size']       = $font_size_id;
                        $data['font_type']       = $font_type_id;
                        $data['font_color']      = $font_id;
                        $data['back_color']      = $back_color_id;


                        $api_scrolling_data = $this->services->get_lco_conditional_content($data,false,true);
                        $api_string = json_encode($api_scrolling_data);
                        $startDate = $api_scrolling_data['startTime'];
                        $endDate = $api_scrolling_data['endTime'];

                        $con_search_response = $this->services->conditional_osd($api_string);

                        if($con_search_response->status == 500 || $con_search_response->status == 400){
                            $administrator_info = $this->organization->get_administrators();
                            echo json_encode(array('status'=>400,'warning_messages'=>$con_search_response->message.' Please Contact with administrator. '.$administrator_info));
                            exit;
                        }

                        if($con_search_response->status != 200){
                            $code = $this->cas_sms_response_code->get_code_by_name($con_search_response->type);
                            echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                            exit;
                        }

                        if($con_search_response->status == 200){

                            if($subscriber_id>0){
                                $subscriber_name = $this->subscriber_profile->get_subscriber_name($subscriber_id);
                                $success_messages = 'Conditional Search successfully sent on Subscriber ['.$subscriber_name.']';
                            }else{
                                $success_messages = 'Conditional Search successfully sent on all Subscriber';
                            }


                            $api_scrolling_data['subscriber_id'] = $subscriber_id;
                            $api_scrolling_data['startTime'] = $startDate;
                            $api_scrolling_data['endTime'] = $endDate;
                            $api_scrolling_data['settings_id'] = $settings_id;
                            $api_scrolling_data['display_times'] = $display_times;
                            $api_scrolling_data['content'] = $content;

                            $this->conditional_resource->save_conditional_scrolling_response($con_search_response,$api_scrolling_data);

                            $this->set_notification("Scrolling OSD",$success_messages);
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
                            $api_scrolling_data['type_data'] = $type_data;
                            $api_scrolling_data['type_operator'] = 116;
                            $api_scrolling_data['cardNum']  = $cardNum;
                            $api_scrolling_data['start_date_time'] = $this->input->post('startTime');
                            $api_scrolling_data['end_date_time']   = $this->input->post('endTime');

                            $api_scrolling_data['conditionLength'] = 11;
                            $api_scrolling_data['contentLength']   = 272;
                            $api_scrolling_data['condCounts']      = 1;
                            $api_scrolling_data['content']         = $content;
                            $api_scrolling_data['displayCounts']   = $display_times;
                            $api_scrolling_data['priority']        = $priority_id;
                            $api_scrolling_data['position']        = $position_id;
                            $api_scrolling_data['font_size']       = $font_size_id;
                            $api_scrolling_data['font_type']       = $font_type_id;
                            $api_scrolling_data['font_color']      = $font_id;
                            $api_scrolling_data['back_color']      = $back_color_id;

                            $api_conditional_response = $this->services->get_subscriber_conditional_content($api_scrolling_data,false,true);

                            $api_string = json_encode($api_conditional_response);
                            $startDate = $api_conditional_response['startTime'];
                            $endDate = $api_conditional_response['endTime'];


                            $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                            $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];

                            $response = $this->services->conditional_osd($api_string);

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

                                $api_scrolling_data['subscriber_id'] = $subscriber_id;
                                $api_scrolling_data['startTime'] = $startDate;
                                $api_scrolling_data['endTime'] = $endDate;

                                if($address_by == "CARD"){
                                    $api_scrolling_data['smart_card_id'] = $api_scrolling_data['cardNum'];
                                }else{
                                    $api_scrolling_data['stb_id'] = $api_scrolling_data['cardNum'];
                                }


                                $api_scrolling_data['settings_id'] = $settings_id;
                                $api_scrolling_data['display_times'] = $display_times;
                                $api_scrolling_data['content'] = $content;

                                $this->conditional_resource->save_conditional_scrolling_response($response,$api_scrolling_data);

                                if($subscriber_id>0){
                                    $subscriber_name = $this->subscriber_profile->get_subscriber_name($subscriber_id);
                                    $success_messages = 'Conditional scrolling OSD successfully sent on Subscriber ['.$subscriber_name.']';
                                }else{
                                    $success_messages = 'Conditional scrolling OSD successfully sent on all Subscriber';
                                }

                                $this->set_notification("Scrolling OSD",$success_messages);
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

                        $data['conditionLength'] = 11;
                        $data['contentLength']   = 272;
                        $data['condCounts']      = 1;
                        $data['content']         = $content;
                        $data['displayCounts']   = $display_times;
                        $data['priority']        = $priority_id;
                        $data['position']        = $position_id;
                        $data['font_size']       = $font_size_id;
                        $data['font_type']       = $font_type_id;
                        $data['font_color']      = $font_id;
                        $data['back_color']      = $back_color_id;

                        $api_scrolling_data = $this->services->get_address_code_conditional_content($data,false,true);
                        $api_string = json_encode($api_scrolling_data);
                        $startDate = $api_scrolling_data['startTime'];
                        $endDate = $api_scrolling_data['endTime'];

                        $con_search_response = $this->services->conditional_osd($api_string);



                        if($con_search_response->status == 500 || $con_search_response->status == 400){
                            $administrator_info = $this->organization->get_administrators();
                            echo json_encode(array('status'=>400,'warning_messages'=>$con_search_response->message.' Please Contact with administrator. '.$administrator_info));
                            exit;
                        }

                        if($con_search_response->status != 200){
                            $code = $this->cas_sms_response_code->get_code_by_name($con_search_response->type);
                            echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                            exit;
                        }

                        if($con_search_response->status == 200){


                            $success_messages = 'Conditional Search successfully sent on address code ['.$business_region_id.']';



                            $api_scrolling_data['region_code'] = $business_region_id;
                            $api_scrolling_data['startTime'] = $startDate;
                            $api_scrolling_data['endTime'] = $endDate;
                            $api_scrolling_data['display_times'] = $display_times;
                            $api_scrolling_data['content'] = $content;
                            $api_scrolling_data['settings_id'] = $settings_id;

                            $this->conditional_resource->save_conditional_scrolling_response($con_search_response,$api_scrolling_data);

                            $this->set_notification("Scrolling OSD",$success_messages);
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