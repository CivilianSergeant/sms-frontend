<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/18/2016
 * Time: 3:11 PM
 */
class Conditional_resource
{
    protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->config->load('cas');
    }

    public function save_conditional_mail_response($search_response,$api_data)
    {
        $startDate = $api_data['startTime'];

        $endDate = $api_data['endTime'];
        if (is_array($startDate)) {
            $startDate = $startDate['year'] . '-' . $startDate['month'] . '-' . $startDate['day'] . ' ' . $startDate['hour'] . ':' . $startDate['minute'] . ':' . $startDate['second'];
        }

        if(is_array($endDate)) {
            $endDate = $endDate['year'] . '-' . $endDate['month'] . '-' . $endDate['day'] . ' ' . $endDate['hour'] . ':' . $endDate['minute'] . ':' . $endDate['second'];
        }
        $conditional_mail_data = array(

            'start_time'    => $startDate,
            'end_time'      => $endDate,
            'condition_return_code' => $search_response->id,
            //'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

        );


        if(isset($api_data['title'])){
            $conditional_mail_data['mail_title'] = $api_data['title'];
        }

        if(isset($api_data['content'])){
            $conditional_mail_data['mail_content'] = $api_data['content'];
        }

        if(isset($api_data['signStr'])){
            $conditional_mail_data['mail_sign'] = $api_data['signStr'];
        }

        if(isset($api_data['priority'])){
            $conditional_mail_data['mail_priority'] = $api_data['priority'];
        }

        if(isset($api_data['type'])){
            $conditional_mail_data['type'] = $api_data['type'];
        }

        if(isset($api_data['creator'])) {
            $conditional_mail_data['creator'] = $api_data['creator'];
        }

        if(isset($api_data['lco_id'])){
            $conditional_mail_data['lco_id'] = $api_data['lco_id'];
        }

        if(isset($api_data['subscriber_id'])){
            $conditional_mail_data['subscriber_id'] = $api_data['subscriber_id'];
        }

        if(!empty($api_data['stb_id'])){
            $conditional_mail_data['stb_id'] = $api_data['stb_id'];
        }

        if(!empty($api_data['smart_card_id'])){
            $conditional_mail_data['smart_card_id'] = $api_data['smart_card_id'];
        }

        if(isset($api_data['region_code'])){
            $conditional_mail_data['region_code'] = $api_data['region_code'];
        }

        if(isset($api_data['group_id'])){
            $conditional_mail_data['group_id'] = $api_data['group_id'];
        }

        $this->CI->conditional_mail->save($conditional_mail_data);
    }

    public function save_conditional_search_response($search_response,$api_data)
    {
        $startDate = $api_data['startTime'];

        $endDate = $api_data['endTime'];
        if (is_array($startDate)) {
            $startDate = $startDate['year'] . '-' . $startDate['month'] . '-' . $startDate['day'] . ' ' . $startDate['hour'] . ':' . $startDate['minute'] . ':' . $startDate['second'];
        }

        if(is_array($endDate)) {
            $endDate = $endDate['year'] . '-' . $endDate['month'] . '-' . $endDate['day'] . ' ' . $endDate['hour'] . ':' . $endDate['minute'] . ':' . $endDate['second'];
        }
        $conditional_mail_data = array(

            'start_time'    => $startDate,
            'end_time'      => $endDate,
            'condition_return_code' => $search_response->id,
            //'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

        );

        if(isset($api_data['lco_id'])){
            $conditional_mail_data['lco_id'] = $api_data['lco_id'];
        }

        if(isset($api_data['subscriber_id'])){
            $conditional_mail_data['subscriber_id'] = $api_data['subscriber_id'];
        }

        if(!empty($api_data['stb_id'])){
            $conditional_mail_data['stb_id'] = $api_data['stb_id'];
        }

        if(!empty($api_data['smart_card_id'])){
            $conditional_mail_data['smart_card_id'] = $api_data['smart_card_id'];
        }

        if(isset($api_data['region_code'])){
            $conditional_mail_data['region_code'] = $api_data['region_code'];
        }

        if(isset($api_data['group_id'])){
            $conditional_mail_data['group_id'] = $api_data['group_id'];
        }

        $this->CI->conditional_search->save($conditional_mail_data);
    }

    public function save_conditional_scrolling_response($scrolling_response,$api_data)
    {
        $startDate = $api_data['startTime'];

        $endDate = $api_data['endTime'];
        if (is_array($startDate)) {
            $startDate = $startDate['year'] . '-' . $startDate['month'] . '-' . $startDate['day'] . ' ' . $startDate['hour'] . ':' . $startDate['minute'] . ':' . $startDate['second'];
        }

        if(is_array($endDate)) {
            $endDate = $endDate['year'] . '-' . $endDate['month'] . '-' . $endDate['day'] . ' ' . $endDate['hour'] . ':' . $endDate['minute'] . ':' . $endDate['second'];
        }

        $conditional_response_data = array(

            'start_time'    => $startDate,
            'end_time'      => $endDate,
            'condition_return_code' => $scrolling_response->id,
            //'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

        );

        if(isset($api_data['display_times'])){
            $conditional_response_data['display_times'] = $api_data['display_times'];
        }

        if(isset($api_data['content'])){
            $conditional_response_data['content'] = $api_data['content'];
        }

        if(isset($api_data['settings_id'])){
            $conditional_response_data['settings_id'] = $api_data['settings_id'];
        }

        if(isset($api_data['lco_id'])){
            $conditional_response_data['lco_id'] = $api_data['lco_id'];
        }

        if(isset($api_data['subscriber_id'])){
            $conditional_response_data['subscriber_id'] = $api_data['subscriber_id'];
        }

        if(!empty($api_data['stb_id'])){
            $conditional_response_data['stb_id'] = $api_data['stb_id'];
        }

        if(!empty($api_data['smart_card_id'])){
            $conditional_response_data['smart_card_id'] = $api_data['smart_card_id'];
        }

        if(isset($api_data['region_code'])){
            $conditional_response_data['region_code'] = $api_data['region_code'];
        }

        if(isset($api_data['group_id'])){
            $conditional_response_data['group_id'] = $api_data['group_id'];
        }

        $this->CI->conditional_scrolling->save($conditional_response_data);
    }

    public function save_conditional_force_osd_response($response,$api_data)
    {
        $startDate = $api_data['startTime'];

        $endDate = $api_data['endTime'];
        if (is_array($startDate)) {
            $startDate = $startDate['year'] . '-' . $startDate['month'] . '-' . $startDate['day'] . ' ' . $startDate['hour'] . ':' . $startDate['minute'] . ':' . $startDate['second'];
        }

        if(is_array($endDate)) {
            $endDate = $endDate['year'] . '-' . $endDate['month'] . '-' . $endDate['day'] . ' ' . $endDate['hour'] . ':' . $endDate['minute'] . ':' . $endDate['second'];
        }

        $conditional_response_data = array(

            'start_time'    => $startDate,
            'end_time'      => $endDate,
            'condition_return_code' => $response->id,
            //'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

        );



        if(isset($api_data['content'])){
            $conditional_response_data['content'] = $api_data['content'];
        }

        if(isset($api_data['ratio'])){
            $conditional_response_data['ratio']  = $api_data['ratio'];
        }

        if(isset($api_data['showTime'])){
            $conditional_response_data['show_time']  = $api_data['showTime'];
        }

        if(isset($api_data['stopTime'])){
            $conditional_response_data['stop_time']  = $api_data['stopTime'];
        }

        if(isset($api_data['fontSize'])){
            $conditional_response_data['font_size']  = $api_data['fontSize'];
        }

        if(isset($api_data['fontType'])){
            $conditional_response_data['font_type']  = $api_data['fontType'];
        }

        if(isset($api_data['colorType'])){
            $conditional_response_data['color_type']  = $api_data['colorType'];
        }

        if(isset($api_data['fontColor'])){
            $conditional_response_data['font_color']  = $api_data['fontColor'];
        }

        if(isset($api_data['backgroundColor'])){
            $conditional_response_data['background_color']  = $api_data['backgroundColor'];
        }

        if(isset($api_data['clarity'])){
            $conditional_response_data['clarity'] = $api_data['clarity'];
        }

        if(isset($api_data['prgCount'])){
            $conditional_response_data['program_count'] = $api_data['prgCount'];
        }

        if(isset($api_data['programID'])){
            $conditional_response_data['program_id'] = json_encode($api_data['programID']);
        }

        if(isset($api_data['lco_id'])){
            $conditional_response_data['lco_id'] = $api_data['lco_id'];
        }

        if(isset($api_data['subscriber_id'])){
            $conditional_response_data['subscriber_id'] = $api_data['subscriber_id'];
        }

        if(!empty($api_data['stb_id'])){
            $conditional_response_data['stb_id'] = $api_data['stb_id'];
        }

        if(!empty($api_data['smart_card_id'])){
            $conditional_response_data['smart_card_id'] = $api_data['smart_card_id'];
        }

        if(isset($api_data['region_code'])){
            $conditional_response_data['region_code'] = $api_data['region_code'];
        }

        if(isset($api_data['group_id'])){
            $conditional_response_data['group_id'] = $api_data['group_id'];
        }


        $this->CI->conditional_force_osd->save($conditional_response_data);
    }

    public function save_conditional_limited_response($response,$api_data)
    {
        $startDate = $api_data['startTime'];

        $endDate = $api_data['endTime'];
        if (is_array($startDate)) {
            $startDate = $startDate['year'] . '-' . $startDate['month'] . '-' . $startDate['day'] . ' ' . $startDate['hour'] . ':' . $startDate['minute'] . ':' . $startDate['second'];
        }

        if(is_array($endDate)) {
            $endDate = $endDate['year'] . '-' . $endDate['month'] . '-' . $endDate['day'] . ' ' . $endDate['hour'] . ':' . $endDate['minute'] . ':' . $endDate['second'];
        }

        $conditional_response_data = array(

            'start_time'    => $startDate,
            'end_time'      => $endDate,
            'condition_return_code' => $response->id,
            //'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

        );





        if(isset($api_data['prodCounts'])){
            $conditional_response_data['product_count'] = $api_data['prodCounts'];
        }

        if(isset($api_data['productID'])){
            $conditional_response_data['product_id'] = json_encode($api_data['productID']);
        }

        if(isset($api_data['lco_id'])){
            $conditional_response_data['lco_id'] = $api_data['lco_id'];
        }

        if(isset($api_data['subscriber_id'])){
            $conditional_response_data['subscriber_id'] = $api_data['subscriber_id'];
        }

        if(!empty($api_data['stb_id'])){
            $conditional_response_data['stb_id'] = $api_data['stb_id'];
        }

        if(!empty($api_data['smart_card_id'])){
            $conditional_response_data['smart_card_id'] = $api_data['smart_card_id'];
        }

        if(isset($api_data['region_code'])){
            $conditional_response_data['region_code'] = $api_data['region_code'];
        }

        if(isset($api_data['group_id'])){
            $conditional_response_data['group_id'] = $api_data['group_id'];
        }

        $this->CI->conditional_limited->save($conditional_response_data);
    }

    public function save_conditional_ecm_response($response,$api_data)
    {
        $startDate = $api_data['startTime'];

        $endDate = $api_data['endTime'];
        if (is_array($startDate)) {
            $startDate = $startDate['year'] . '-' . $startDate['month'] . '-' . $startDate['day'] . ' ' . $startDate['hour'] . ':' . $startDate['minute'] . ':' . $startDate['second'];
        }

        if(is_array($endDate)) {
            $endDate = $endDate['year'] . '-' . $endDate['month'] . '-' . $endDate['day'] . ' ' . $endDate['hour'] . ':' . $endDate['minute'] . ':' . $endDate['second'];
        }

        $conditional_response_data = array(

            'start_time'    => $startDate,
            'end_time'      => $endDate,
            'condition_return_code' => $response->id,
            //'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

        );





        if(isset($api_data['progCounts'])){
            $conditional_response_data['program_count'] = $api_data['progCounts'];
        }

        if(isset($api_data['programID'])){
            $conditional_response_data['program_id'] = json_encode($api_data['programID']);
        }

        if(isset($api_data['lco_id'])){
            $conditional_response_data['lco_id'] = $api_data['lco_id'];
        }

        if(isset($api_data['subscriber_id'])){
            $conditional_response_data['subscriber_id'] = $api_data['subscriber_id'];
        }

        if(!empty($api_data['stb_id'])){
            $conditional_response_data['stb_id'] = $api_data['stb_id'];
        }

        if(!empty($api_data['smart_card_id'])){
            $conditional_response_data['smart_card_id'] = $api_data['smart_card_id'];
        }

        if(isset($api_data['region_code'])){
            $conditional_response_data['region_code'] = $api_data['region_code'];
        }

        if(isset($api_data['group_id'])){
            $conditional_response_data['group_id'] = $api_data['group_id'];
        }

        $this->CI->ecm_fingerprint->save($conditional_response_data);
    }

    public function save_conditional_emm_response($response,$api_data)
    {
        $startDate = $api_data['startTime'];

        $endDate = $api_data['endTime'];
        if (is_array($startDate)) {
            $startDate = $startDate['year'] . '-' . $startDate['month'] . '-' . $startDate['day'] . ' ' . $startDate['hour'] . ':' . $startDate['minute'] . ':' . $startDate['second'];
        }

        if(is_array($endDate)) {
            $endDate = $endDate['year'] . '-' . $endDate['month'] . '-' . $endDate['day'] . ' ' . $endDate['hour'] . ':' . $endDate['minute'] . ':' . $endDate['second'];
        }

        $conditional_response_data = array(

            'start_time'    => $startDate,
            'end_time'      => $endDate,
            'condition_return_code' => $response->id,
            //'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

        );




        if(isset($api_data['positionFlag'])){
            $conditional_response_data['position_flag'] = $api_data['positionFlag'];
        }

        if(isset($api_data['showTime'])){
            $conditional_response_data['show_time'] = $api_data['showTime'];
        }

        if(isset($api_data['stopTime'])){
            $conditional_response_data['stop_time'] = $api_data['stopTime'];
        }

        if(isset($api_data['fontSize'])){
            $conditional_response_data['font_size'] = $api_data['fontSize'];
        }

        if(isset($api_data['fontType'])){
            $conditional_response_data['font_type'] = $api_data['fontType'];
        }

        if(isset($api_data['colorType'])){
            $conditional_response_data['color_type'] = $api_data['colorType'];
        }

        if(isset($api_data['fontColor'])){
            $conditional_response_data['font_color'] = $api_data['fontColor'];
        }

        if(isset($api_data['backgroundColor'])){
            $conditional_response_data['back_color'] = $api_data['backgroundColor'];
        }

        if(isset($api_data['overtFlag'])){
            $conditional_response_data['overt_flag'] = $api_data['overtFlag'];
        }

        if(isset($api_data['showBKFlag'])){
            $conditional_response_data['show_bk_flag'] = $api_data['showBKFlag'];
        }

        if(isset($api_data['showSTBNumberFlag'])){
            $conditional_response_data['show_stb_number_flag'] = $api_data['showSTBNumberFlag'];
        }

        if(isset($api_data['xPosition'])){
            $conditional_response_data['x_pos'] = $api_data['xPosition'];
        }

        if(isset($api_data['yPosition'])){
            $conditional_response_data['y_pos'] = $api_data['yPosition'];
        }


        if(isset($api_data['lco_id'])){
            $conditional_response_data['lco_id'] = $api_data['lco_id'];
        }

        if(isset($api_data['subscriber_id'])){
            $conditional_response_data['subscriber_id'] = $api_data['subscriber_id'];
        }

        if(!empty($api_data['stb_id'])){
            $conditional_response_data['stb_id'] = $api_data['stb_id'];
        }

        if(!empty($api_data['smart_card_id'])){
            $conditional_response_data['smart_card_id'] = $api_data['smart_card_id'];
        }

        if(isset($api_data['region_code'])){
            $conditional_response_data['region_code'] = $api_data['region_code'];
        }

        if(isset($api_data['group_id'])){
            $conditional_response_data['group_id'] = $api_data['group_id'];
        }

        $this->CI->ecm_fingerprint->save($conditional_response_data);
    }

    public function save_pair_stb_ic($data)
    {
        $this->CI->pair_stb_ic_log->save($data);
    }

}