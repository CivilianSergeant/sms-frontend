<?php

/**
 * @property Streamer_instance_model $streamer_instance
 * @property Menu_model $menus
 * @property Subscriber_profile_model $subscriber_profile
 * @property User_model $user
 */
class Streamer_instance extends BaseController
{
    const EXPIRE_IN_SEC = 3600;
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
        $this->theme->set_title('Streamer Instance')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/streamer-instance/add.js');
       
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('streamer_instance/index',$data,true);
    }

    public function view($id)
    {
        $instance = $this->streamer_instance->find_by_id($id);
        if(!$instance->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! Streamer instance not found');
            redirect('streamer-instance');
        }
        $this->theme->set_title('Streamer Instance')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/streamer-instance/add.js');

        $data['user_info'] = $this->user_session;
        $data['instanceId'] = $id;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('streamer_instance/view',$data,true);
    }

    public function edit($id)
    {
        $instance = $this->streamer_instance->find_by_id($id);
        if(!$instance->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! Streamer instance not found');
            redirect('streamer-instance');
        }
        $this->theme->set_title('Streamer Instance')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/streamer-instance/add.js');

        $data['user_info'] = $this->user_session;
        $data['instanceId'] = $id;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('streamer_instance/edit',$data,true);
    }

    public function ajax_get_permissions()
    {
        if($this->role_type == self::ADMIN){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $permissions = $this->menus->has_permission($this->role_id,1,'streamer-instance',$this->user_type);
        }

        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function save()
    {

        if($this->input->is_ajax_request()){

            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'streamer-instance', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to Streamer Instance"));
                    exit;
                }
            }

            $this->form_validation->set_rules('instance_local_ip','Instance Local Ip','required|is_unique[streamer_instances.instance_local_ip]');
            if($this->form_validation->run() == False) {
                echo json_encode(array('status'=>400,'warning_messages'=>validation_errors()));
                exit;
            }

            $this->form_validation->set_rules('instance_global_ip','Instance Global Ip','required|is_unique[streamer_instances.instance_global_ip]');
            if($this->form_validation->run() == False) {
                echo json_encode(array('status'=>400,'warning_messages'=>validation_errors()));
                exit;
            }
            if($this->user_session->lsp_type_id > 0){
                $operator_id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
            }else{
                $operator_id = $this->input->post('operator_id');
            }
            
            $aliasDomain = $this->input->post('alias_domain_url');
            $aliasDomain = (!empty(trim($aliasDomain)))? trim($aliasDomain) : '';
            if(empty($aliasDomain)){
                echo json_encode(array('status'=>400,'Sorry! alias / domain must have value to create streamer instance'));
                exit;
            }

            $saveData = array(
                'instance_name'        => $this->input->post('instance_name'),
                'instance_local_ip'    => $this->input->post('instance_local_ip'),
                'instance_global_ip'   => $this->input->post('instance_global_ip'),
                'alias_domain_url'     => $aliasDomain,
                'instance_index'       => $this->input->post('instance_index'),
                'instance_capacity'    => $this->input->post('instance_capacity'),
                'instance_description' => $this->input->post('instance_description'),
                'operator_id'          => $operator_id,
                'type'                 => (!empty($operator_id>1))? 'LCO':'MSO',
                'is_active'            => 1
            );

            $id = $this->streamer_instance->save($saveData);
            if($id){
                echo json_encode(array('status'=>200,'success_messages'=>'Streamer instance '.$saveData['instance_name'].' successfully created'));
            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Something wrong please try again later'));
            }
            exit;


        }else{
            redirect('/');
        }
    }

    public function update()
    {

        if($this->input->is_ajax_request()){

            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_edit_permission($this->role_id, 1, 'streamer-instance', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have update permission to Streamer Instance"));
                    exit;
                }
            }

            $streamerInstance = $this->streamer_instance->find_by_id($this->input->post('id'));
            if(!empty($streamerInstance)){
                if($streamerInstance->get_attribute('instance_local_ip') != $this->input->post('instance_local_ip')){
                    $available = $this->streamer_instance->find_by_local_ip($this->input->post('instance_local_ip'));
                    if(!empty($available)){
                        echo json_encode(array('status'=>400,'warning_messages'=> "Sorry! Your specified Local Ip is already used"));
                        exit;
                    }
                }
            }

            if(!empty($streamerInstance)){
                if($streamerInstance->get_attribute('instance_global_ip') != $this->input->post('instance_global_ip')){
                    $available = $this->streamer_instance->find_by_global_ip($this->input->post('instance_global_ip'));
                    if(!empty($available)){
                        echo json_encode(array('status'=>400,'warning_messages'=> "Sorry! Your specified Global Ip is already used"));
                        exit;
                    }
                }
            }

            $operator_id = $this->input->post('operator_id');
            $aliasDomain = $this->input->post('alias_domain_url');
            $aliasDomain = (!empty(trim($aliasDomain)))? trim($aliasDomain) : '';

            if(empty($aliasDomain)){
                echo json_encode(array('status'=>400,'Sorry! alias / domain must have value to update streamer instance'));
                exit;
            }

            $oldAliasDomainUrl = $streamerInstance->get_attribute('alias_domain_url');

            $saveData = array(
                'instance_name'        => $this->input->post('instance_name'),
                'instance_local_ip'    => $this->input->post('instance_local_ip'),
                'instance_global_ip'   => $this->input->post('instance_global_ip'),
                'alias_domain_url'     => $aliasDomain,
                'instance_index'       => $this->input->post('instance_index'),
                'instance_capacity'    => $this->input->post('instance_capacity'),
                'instance_description' => $this->input->post('instance_description'),
                'operator_id'   => $operator_id,
                'type'          => (!empty($operator_id)>1)? 'LCO':'MSO',
                'is_active'     => 1
            );




            $this->streamer_instance->save($saveData,$this->input->post('id'));
            if($this->db->affected_rows()){

                $this->db->query("Update map_streamer_instances SET hls_url_stb = Replace(hls_url_stb,'{$oldAliasDomainUrl}','{$aliasDomain}'),
                hls_url_mobile = Replace(hls_url_mobile,'{$oldAliasDomainUrl}','{$aliasDomain}'),
                hls_url_web = Replace(hls_url_web,'{$oldAliasDomainUrl}','{$aliasDomain}')
                where streamer_instance_id=".$streamerInstance->get_attribute('id'));

                echo json_encode(array('status'=>200,'success_messages'=>'Streamer instance '.$saveData['instance_name'].' successfully updated'));
            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Something wrong please try again later'));
            }
            exit;


        }else{
            redirect('/');
        }
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $lco = $this->lco_profile->get_all_lco_users($id);
            if(!empty($lco))
                $lco = array_key_sort($lco,'lco_name',SORT_ASC);
            array_unshift($lco, array('user_id'=>1,'lco_name'=>'MSO'));
            echo json_encode(array(
                'status' => 200,
                'lco' => $lco
            ));
        } else {
            redirect('/');
        }

    }

    public function ajax_get_instances()
    {
        if($this->input->is_ajax_request()){
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $take = $this->input->get('post');
            $skip = $this->input->get('post');
            $filter = $this->input->get('post');
            $sort = $this->input->get('post');
            $instances = $this->streamer_instance->get_all_instances($id,$take,$skip,$filter,$sort);
            $total     = $this->streamer_instance->count_all_instances($id,$filter);
            echo json_encode(array(
                'status' => 200,
                'instances' => $instances,
                'total'     => $total
            ));

        }else{
            redirect('/');
        }
    }

    public function ajax_get_monitor_instances()
    {
        if($this->input->is_ajax_request()){
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

            $instances = $this->streamer_instance->get_all_instances($id);
            //array_unshift($instances,array('id'=>-1,'instance_name'=>'All'));
            $total     = $this->streamer_instance->count_all_instances($id);
            echo json_encode(array(
                'status' => 200,
                'instances' => $instances,
                'total'     => $total
            ));

        }else{
            redirect('/');
        }
    }

    public function ajax_get_instance_data()
    {
        if($this->input->is_ajax_request()){

            $id = $this->input->post('instance_id');
            $remoteAddrs = array();
            $PORT = 11211;
            if($id != "All"){
                $instance = $this->streamer_instance->find_by_id($id);

                if(!$instance->has_attributes()){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Instance not found'));
                    exit;
                }

                if(empty($instance->get_attribute('instance_local_ip'))){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Instance Local IP not found'));
                    exit;
                }
                $remoteAddrs[] = array($instance->get_attribute('instance_local_ip'),$PORT); //'127.0.0.1'

            }else{
                $userId = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
                $instances = $this->streamer_instance->get_all_instances($userId);
                foreach($instances as $instance){
                    $remoteAddrs[] = array($instance->instance_local_ip,$PORT); //'127.0.0.1'
                }

            }


            $result = array();
            try{

                foreach($remoteAddrs as $i=> $addr){
                    $memcached = new Memcached;
                    $memcached->addServer($addr[0],$addr[1]);
                    $memcachedResult = $memcached->get('user_info');
                    if(!empty($memcachedResult)){
                        foreach($memcachedResult as $j=> $mr){
                            $subscriberName = $this->subscriber_profile->get_subscriber_name($mr['customerId']);
                            $user           = $this->user->find_by_id($mr['customerId']);

                            $mr['customerName'] = $subscriberName;
                            $mr['profileToken'] = $user->get_attribute('token');

                            $mr['startTime']    = (!empty($mr['startTime']))? $mr['startTime'] : '---';
                            $mr['channelName']  = (!empty($mr['channelName']))? $mr['channelName'] : '---';
                            $mr['bitRate']      = (!empty($mr['bitRate']))? $mr['bitRate'] : '---';
                            $mr['ip']           = $addr[0];//($id == "All")? $instances[$i]->instance_local_ip : $instance->instance_local_ip; //

                            $result[] = $mr;
                        }
                    }

                    $memcached->resetServerList();
                }

                if(is_bool($result)){
                    $result = array();
                }

            }catch(Exception $ex){
                echo json_decode(array('status'=>400,'Sorry ! Please try again later'));
                exit;
            }

            echo json_encode(array('status' => 200,
                    'instance_data'=>$result));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_instance($id)
    {
        if($this->input->is_ajax_request()){
            $instance = $this->streamer_instance->find_by_id($id);
            $hls = $this->map_streamer_instance->get_all_by_instance($id);
            echo json_encode(array(
                'status'=>200,
                'instance'=>$instance->get_attributes(),
                'hls'     => $hls
            ));

        }else{
            redirect('/');
        }
    }

    public function delete()
    {
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $instance = $this->streamer_instance->find_by_id($id);
            if($instance->has_attributes()){
                $this->streamer_instance->remove($instance->get_attribute('id'));
                echo json_encode(array('status'=>200,'success_messages'=>'Streamer Instance '.$instance->get_attribute('instance_name').' has been deleted successfully'));
            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No item found'));
            }
            exit;
        }else{
            redirect('/');
        }
    }

    public function sync(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $remoteAddrs = array();
            $PORT = 11211;
            $instance = $this->streamer_instance->find_by_id($id);


            if($instance->has_attributes()){
                $pushData = array();
                $users = $this->user->get_active_subscribers();
                foreach($users as $i=> $user){
                    //$subscriberName = $this->subscriber_profile->get_subscriber_name($user->id);
                    $pushData[$i] = array(
                        'streamerId' => $instance->get_attribute('id'),
                        'customerId' => $user->id,
                        'loginStartTime' => date('Y-m-d H:i:s'),
                        'userIp'        => $_SERVER['SERVER_ADDR'],
                        'customerToken' => $user->iptv_token,
                        'watchTime' => null,
                        'channelName' => null,
                        'bitRate' => '',
                        'duration' => 0
                    );

                }

                $remoteAddrs[] = array($instance->get_attribute('instance_local_ip'),$PORT);
                try {
                    foreach ($remoteAddrs as $i => $addr) {
                        $memcached = new Memcached;
                        $memcached->addServer($addr[0], $addr[1]);
                        $memcachedData = $memcached->get('user_info');
                        if(empty($memcachedData)){
                            if(!$memcached->set('user_info', $pushData, self::EXPIRE_IN_SEC)){
                                echo json_encode(array('status'=>400,'warning_messages'=>'Unable to push data to memcached server :'.implode(':',$addr)));
                            }
                        }
                    }
                }catch(Exception $ex){
                    echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));

                }
            }else{
                echo json_encode(array('status'=>400,'warning_messaegs'=>'Sorry! No Streamer Instance found with id: '.$id));
            }
            exit;
        }else{
            redirect('/');
        }
    }

    public function monitor()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_view_permission($this->role_id, 1, 'monitor-instance', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have view permission to Monitor Instance"));
                exit;
            }
        }

        $this->theme->set_title('Monitor Instance')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/streamer-instance/monitor.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('streamer_instance/monitor',$data,true);


    }








}