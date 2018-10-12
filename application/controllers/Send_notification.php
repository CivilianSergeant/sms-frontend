<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Send_notification
 *
 * @author Himel
 */
class Send_notification extends BaseController{
        
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
        
        const API_KEY = 'AAAA0DnS4uY:APA91bHKg8YgqL0oybwlz-C1B5y3b2AkTOPI7sfJ6RC2iGwpJ79M9LcpHW1RgxXTsqgMeeTj2QQ_IWA8QUBlvU3Q3_7fxPUgFRTdrCLIesvicBtepe3O9arxjuo383ryaMWbubnh-AVtheTpvVu3K1nB810277UkjA';
        
        public function __construct()
	{
            parent::__construct();
            
            
            $this->theme->set_theme('katniss');
            $this->theme->set_layout('main');
            $this->load->model('Fcm_device_group_model','fcm_device_group');
            $this->load->model('Fcm_token_model','fcm_token');
            $this->load->model('Fcm_notification_log_model','fcm_notification_log');

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
            $this->theme->set_title('Conditional Mail')
                     ->add_style('component.css')
                     ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                     ->add_style('kendo/css/kendo.bootstrap.min.css')
                     ->add_script('controllers/send_notification/send_notification.js');


            $data['user_info'] = $this->user_session;	
            $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
            $data['sign'] = $this->config->item('message_sign');
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('send_notification/index',$data,true);
	}
        
        public function ajax_get_device_groups()
        {
            if($this->input->is_ajax_request()) {
                    $fcm_groups = $this->fcm_device_group->get_fcm_device_groups();
                    array_unshift($fcm_groups, array('user_id'=>0,'group_name'=>'All'));
                    
                    echo json_encode(array(
                            'status' => 200,
                            'fcm_groups' => $fcm_groups
                    ));
            } else {
                    redirect('/');
            }
        }
        
        public function ajax_get_devices($groupId)
        {
            if($this->input->is_ajax_request()) {
                $devices = $this->fcm_token->get_devices_by_group($groupId);
                array_unshift($devices, array('fcm_token'=>0,'subscriber_name'=>'All'));
                    
                echo json_encode(array(
                        'status' => 200,
                        'devices' => $devices
                ));
            }else{
                redirect('/');
            }
        }
        
        public function send_fcm_notification()
        {
            if($this->input->is_ajax_request()){
                $all = $this->input->post();
                $imgMeta = $all['imgType'];
                $imgMeta = explode("/",$imgMeta);
                $base64_string = null;
                if(!empty($all['img'])){
                    if(preg_match('/removed/',$all['img'])){
                        $base64_string = str_replace('[removed]','',$all['img']);
                    }else{
                        $imgMetaInfo = explode(",",$all['img']);
                        $base64_string = $imgMetaInfo[1];
                    }
                }
                $imgName = null;
                if(!empty($all['img'])){
                    $imgName = NOTIFICATION_PATH.'/'.time().'_test.'.$imgMeta[1];
                    $imgName = $this->_base64_to_jpeg($base64_string, $imgName);
                }
                
                $payload = array(
                        'image'              => ($all['type'] == 'LOGOUT')? '' : base_url($imgName),
                        'notificationHeader' => $all['title'],
                        'notificationText'   => $all['content'],
                        'resourceUrl'        => $all['resource_url'],
                        'notificationType'   => $all['type']
                 );
                
                if(!empty($all['device_group_id']) && !empty($all['device_id'])){
                    // for specific fcm token
                    $fcm_device = $this->fcm_token->find_by_fcm_token($all['device_group_id'],$all['device_id']);
                    $token = $fcm_device->fcm_token;
                    $response = $this->_send_notification($token, $payload);
                    $this->_save_notification_log($all, $imgName);
                    echo json_encode($response);
                    
                }else if(!empty($all['device_group_id']) && is_numeric($all['device_group_id']) && empty($all['device_id'])){
                    // for all fcm token of a specific group
                  
                    $fcm_device_group = $this->fcm_device_group->find_by_id($all['device_group_id']);
                    $token = $fcm_device_group->get_attribute('fcm_device_group');
                    $response = $this->_send_notification($token, $payload);
                    $this->_save_notification_log($all, $imgName);
                    echo json_encode($response);
                }else{
                    // for all device group
                    $fcm_device_groups = $this->fcm_device_group->get_fcm_device_groups();
                    
                    if(!empty($fcm_device_groups)){
                        foreach($fcm_device_groups as $fcm_device_group){
                            $token = $fcm_device_group->fcm_device_group;
                            $response = $this->_send_notification($token, $payload);
                            $this->_save_notification_log($all, $imgName);
                        } 
                        echo json_encode($response);
                    }
                    
                }
            }else{
                redirect('/');
            }
        }
        
        private function _save_notification_log($all,$imgName)
        {
            return $this->fcm_notification_log->save(array(
                'fcm_device_group_id' => $all['device_group_id'],
                'fcm_token_id'        => $all['device_id'],
                'type'                => $all['type'],
                'header'              => $all['title'],
                'text'                => $all['content'],
                'image_url'           => $imgName,
                'resource_url'        => $all['resource_url'],
                'created_by'          => $this->user_id,
                'parent_id'           => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id
            ));
        }
        
        private function _base64_to_jpeg($base64_string, $output_file) {
            
            file_put_contents($output_file, base64_decode($base64_string)); 
  
            return $output_file; 
        }
        
        private function _send_notification($token, $payload){
            $url = "https://fcm.googleapis.com/fcm/send";
            $fields = array(
                'to' => $token,  //"/topics/test",//$tokens,
                'data' => $payload
            );

            $headers = array(
                'Authorization:key='.self::API_KEY,
                'Content-Type:application/json'
            );

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
            $result = curl_exec($ch);
            if($result === FALSE){
                die('CURL FAILED '. curl_error($ch));
            }

            $info = curl_getinfo($ch);

            curl_close($ch);
            return array('result'=>$result,'status'=>$info['http_code']);
        }
}
