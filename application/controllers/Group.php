<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/25/2016
 * Time: 4:59 PM
 * @property Group_profile_model $group_profile
 */
class Group extends BaseController
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
    const GROUP_LOWER = 'group';
    const GROUP_UPPER = 'GROUP';
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
        $method = $this->router->fetch_method();

        if(in_array($method,array('ajax_get_lco','assign_lco_to_group')) && $this->user_type == self::GROUP_LOWER){
            redirect('/');
        }

        if($this->user_type == self::LCO_LOWER){
            redirect('/');
        }
    }

    public function index()
    {
        $this->theme->set_title('Group Accounts')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/group/group-account.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('group/groupprofile',$data,true);
    }

    public function assign_lco()
    {
        $this->theme->set_title('Assign LCO')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/group/group-account-assign.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('group/assign-lco',$data,true);
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $lco = $this->lco_profile->get_all_lco_users($this->parent_id);
            echo json_encode(array(
                'status' => 200,
                'lco' => $lco
            ));
        } else {
            redirect('/');
        }

    }

    public function ajax_get_group_lco($group_id)
    {
        if($this->input->is_ajax_request()){
            $results = $this->group_profile->get_lco_list($group_id);
            $group_lco = array();
            if (!empty($results)) {
                foreach ($results as $i=>$r) {
                    $group_lco[$r->id] = $r;
                }
            }


            $all_lco = $this->lco_profile->get_all_lco_users($this->parent_id);
            $lcos = array();
            $assigned_lcos = array();
            foreach ($all_lco as $i=>$al) {
                if (in_array($al->id,array_keys($group_lco))) {
                    $assigned_lcos[] = $al;
                    unset($all_lco[$i]);
                }else{
                    $lcos[] = $al;
                }
            }


            echo json_encode(array('status'=>200,'lcos'=>$lcos,'assigned_lcos'=>$assigned_lcos));
            exit;

        }else{
            redirect('/');
        }
    }

    public function ajax_get_lco_by_group($token=null)
    {
        if($this->input->is_ajax_request())
        {
            $group = $this->user->find_by_token($token);
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $filter = $this->input->get('filter');
            $sort = $this->input->get('sort');

            if($group->has_attributes()){
                $id = $group->get_attribute('id');
                echo json_encode(array(
                    'status'=>200,
                    'profiles' => $this->group_profile->get_lco_list($id,$take,$skip,$filter,$sort),
                    'total'    => $this->group_profile->get_count_lco($id,$filter)
                ));
            }
        }else{
            redirect('/');
        }
    }

    public function assign_lco_to_group()
    {
        if($this->input->is_ajax_request()){
            $lco_list = $this->input->post('included_list');
            $group_id = $this->input->post('group_id');


            if(!empty($lco_list)){

                $save_datas = array();
                foreach($lco_list as $i=>$lco){

                    // check is lco exist
                    $user = $this->user->find_by_id($lco['user_id']);

                    if($user->has_attributes()){

                        $save_datas[] = array(
                            'lco_id' => $lco['user_id'],
                            'group_id' => $group_id,
                            'created_by' => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        );
                    }
                }
            }else{
                echo json_encode(array(
                    'status'=>400,
                    'warning_messages'=> "Please include lco before  assign to group"
                ));
                exit;
            }

            $this->group_profile->remove_lco($group_id);
            //test($save_datas);
            if(!empty($save_datas)){


                foreach($save_datas as $s_data){
                    $this->lco_group->save($s_data);
                }
            }


            echo json_encode(array(
                'status' => 200,
                'success_messages' => "Lco successfully assigned to group"
            ));
            exit;
        }else{
            redirect('/');
        }
    }

    public function ajax_get_permissions()
    {
        if(!$this->input->is_ajax_request()){
            redirect('/');
        }

        if($this->role_type == "admin"){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $permissions = $this->menus->has_permission($this->role_id,1,'groups',$this->user_type);
        }

        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function ajax_load_profiles()
    {
        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $all_lco = $this->group_profile->get_all_group_users($id,$take,$skip,$filter,$sort);
        $roles = $this->role->get_role_for_permission($this->role_type,$this->user_type);
        echo json_encode(array(
            'status'=>200,
            'profiles'=>$all_lco,
            'roles' => $roles,
            'countries' => $this->country->get_all(),
            'total' => $this->group_profile->get_count_group($id),
            'role_type' => $this->role_type,
            'user_type' => $this->user_type,
        ));
    }

    public function create_profile()
    {
        if($this->role_type=="staff"){
            $permission = $this->menus->has_create_permission($this->role_id,1,'groups',$this->user_type);
            if(!$permission){
                echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! You don't have create permission"));
                exit;
            }
        }

        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $this->form_validation->set_rules('email', 'E-mail','required|is_unique[users.email]');

        if ($this->form_validation->run() == FALSE) {

            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
            } else {
                $this->session->set_flashdata('warning_messages',validation_errors());
                redirect('groups');
            }

        } else {

            /* User Profile Information */
            $message_sign = $this->input->post('message_sign');
            $save_profile_data = array(
                'group_name'        => $this->input->post('full_name'),
                'address1'        => $this->input->post('address1'),
                'address2'        => $this->input->post('address2'),
                'country_id'      => $this->input->post('country_id'),
                'division_id'     => $this->input->post('division_id'),
                'district_id'     => $this->input->post('district_id'),
                'area_id'         => $this->input->post('area_id'),
                'sub_area_id'     => $this->input->post('sub_area_id'),
                'road_id'         => $this->input->post('road_id'),
                'contact'         => $this->input->post('contact'),
                'billing_contact' => $this->input->post('billing_contact'),
                'token'           => md5(time().$this->input->post('email')),
                'message_sign'    => strtoupper($message_sign)
            );

            $profile_insert_id = $this->group_profile->save($save_profile_data);

            if ($profile_insert_id) {

                $save_login_data = $array = array(
                    'profile_id' => $profile_insert_id,
                    'role_id'    => ($this->user_session->user_type == self::MSO_UPPER)? 3 : 4,
                    'username'   => $this->input->post('email'),
                    'email'      => $this->input->post('email'),
                    'password'   => md5($this->input->post('email')),
                    'user_type'  => 'Group',
                    'user_status'=> 1,
                    'token'      => $save_profile_data['token']
                );

                $user_id = $this->user->save($save_login_data);

                if(@file_exists(GROUP_PATH)){
                    @mkdir(GROUP_PATH.$user_id,0777);
                }else{
                    @mkdir(GROUP_PATH.$user_id,0777,true);
                }
            }

            $this->set_notification("New Group Profile Created","New Group Profile [{$save_profile_data['group_name']}] has been created");
            echo json_encode(array('status'=>200,'token'=>$save_profile_data['token'],'success_messages'=>'Group user '.$save_profile_data['group_name'].' profile created successfully'));
        }
    }

    public function update_profile()
    {
        if($this->role_type=="staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'groups', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                exit;
            }
        }

        $this->form_validation->set_rules('group_name', 'Full Name', 'required');
        $this->form_validation->set_rules('email', 'E-mail','required');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            } else {
                $this->session->set_flashdata('warning_messages',validation_errors());
                redirect('groups');
            }
        } else {


            /* User Profile Information */
            $message_sign = $this->input->post('message_sign');
            $save_profile_data = array(
                'group_name'      => $this->input->post('group_name'),
                'address1'        => $this->input->post('address1'),
                'address2'        => $this->input->post('address2'),
                'country_id'      => $this->input->post('country_id'),
                'division_id'     => $this->input->post('division_id'),
                'district_id'     => $this->input->post('district_id'),
                'area_id'         => $this->input->post('area_id'),
                'sub_area_id'     => $this->input->post('sub_area_id'),
                'road_id'         => $this->input->post('road_id'),
                'contact'         => $this->input->post('contact'),
                'is_same_as_contact' => $this->input->post('is_same_as_contact'),
                'billing_contact' => $this->input->post('billing_contact'),
                'message_sign'    => strtoupper($message_sign),
                'updated_at'      => date('Y-m-d H:i:s'),
                'updated_by'      => $this->user_id
            );

            $profile_id = $this->input->post('profile_id');
            $this->group_profile->save($save_profile_data,$profile_id);
            $this->set_notification("Group Profile Updated","Group Profile [{$save_profile_data['group_name']}] has been updated");
            echo json_encode(array('status'=>200,'token'=>$this->input->post('token'),'success_messages'=>'Group user '.$save_profile_data['group_name'].' profile created successfully'));
        }
    }

    public function create_login_info()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff"){
                $permission = $this->menus->has_create_permission($this->role_id,1,'groups',$this->user_type);
                if(!$permission){
                    echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! You don't have create permission"));
                    exit;
                }
            }

            $this->form_validation->set_rules('username', 'Username','required|is_unique[users.username]');
            $this->form_validation->set_rules('password','Password','required|matches[re_password]');
            $this->form_validation->set_rules('re_password','Retype Password','required');

            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;

            } else {

                $token = $this->input->post('token');
                $profile = $this->group_profile->find_by_token($token);
                $user    = $this->user->find_by_token($token);
                $role_id = $this->input->post('role_id');

                if ($profile->get_attribute('id')) {
                    $save_login_data = $array = array(
                        'profile_id' => $profile->get_attribute('id'),
                        'role_id'    => (!empty($role_id)) ? $role_id : (($this->user_session->user_type == self::MSO_UPPER)? 3 : 4),
                        'username'   => $this->input->post('username'),
                        'is_remote_access_enabled' => $this->input->post('is_remote_access_enabled'),
                        'user_type'  => 'Group',
                        'user_status'=> 1,
                        'token'      => $profile->get_attribute('token')
                    );

                    if ($user->has_attributes()) {
                        $password = $this->input->post('password');
                        if(!empty($password))
                            $save_login_data['password']   = md5($password);
                        $this->user->save($save_login_data,$user->get_attribute('id'));
                        $this->set_notification("Group Login Info Updated","Login Information of Group Profile [{$profile->get_attribute('group_name')}] has been updated");
                    } else {

                        $this->user->save($save_login_data);
                        $this->set_notification("Group Login Info Created","Login Information of Group Profile [{$profile->get_attribute('group_name')}] has been created");
                    }

                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
                }

                echo json_encode(array('status'=>200,'success_messages'=>'Login Info of User '.$profile->get_attribute('group_name').' created successfully'));
            }

        } else {

            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('groups');
        }
    }

    public function update_login_info()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                // Authorization Checking
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            // update login info

            $password    = $this->input->post('password');
            $re_password = $this->input->post('re_password');
            if($password != null) {
                if($password != $re_password){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Password not matched with Retype Password'));
                    exit;
                }
            }


            $token = $this->input->post('token');
            $profile = $this->group_profile->find_by_token($token);
            $user    = $this->user->find_by_token($token);

            $username = $this->input->post('username');
            $unique = $user->is_unique($username);

            if(!empty($unique)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Username not available'));
                return;
            }
            $role_id = $this->input->post('role_id');
            if ($profile->get_attribute('id')) {
                $save_login_data = $array = array(
                    'profile_id' => $profile->get_attribute('id'),
                    'role_id'    => (!empty($role_id))? $role_id : (($this->user_session->user_type == self::MSO_UPPER)? 3 : 4),
                    'username'   => $username,
                    'is_remote_access_enabled' => $this->input->post('is_remote_access_enabled'),
                    'user_type'  => 'Group',
                    'user_status'=> 1,
                    'token'      => $profile->get_attribute('token')
                );

                if ($user->has_attributes()) {

                    if(!empty($password))
                        $save_login_data['password']   = md5($password);

                    $this->user->save($save_login_data,$user->get_attribute('id'));
                    $this->set_notification("Group Login Info Updated","Login Information of Group Profile [{$profile->get_attribute('group_name')}] has been updated");
                    echo json_encode(array('status'=>200,'success_messages'=>'Login username '.$username.' updated successfully'));
                } else {

                    $this->user->save($save_login_data);
                    $this->set_notification("Group Login Info Created","Login Information of Group Profile [{$profile->get_attribute('group_name')}] has been created");

                }


            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('groups');
        }
    }

    public function upload_photo(){

        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token');
            $profile = $this->group_profile->find_by_token($token);
            $user    = $this->user->find_by_token($token);
            $filesize = (2*(1024*1024));

            if ($profile->has_attributes()) {
                if(preg_match('/(jpg|jpeg|png|gif)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 2MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $GROUP_PATH = GROUP_PATH.$user->get_attribute('id');
                    $to_path = $GROUP_PATH.'/photo';
                    $old_photo = $profile->get_attribute('photo');
                    $photo_path = $to_path.'/photo_'.date('ymdHis').'.'.$type;

                    if(@file_exists($GROUP_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    if(move_uploaded_file($tmp_uploaded_file,$photo_path)){
                        $photo_data['photo'] = $photo_path;
                        $photo_data['updated_at'] = date('Y-m-d H:i:s');
                        $photo_data['updated_by'] = $this->user_id;
                        $this->group_profile->save($photo_data,$profile->get_attribute('id'));
                        @unlink($old_photo);
                    }


                    $this->set_notification("Group Photo Uploaded","Photo of Group [{$profile->get_attribute('group_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$photo_data['photo'],'success_messages'=>'Successfully photo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('groups');
        }

    }


    public function upload_identity()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token');
            $profile = $this->group_profile->find_by_token($token);
            $user    = $this->user->find_by_token($token);
            $filesize = (2*(1024*1024));

            if ($profile->has_attributes()) {
                if(preg_match('/(jpg|jpeg|png|gif)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 2MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];
                    //$identity_data['identity_attachment'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $GROUP_PATH = GROUP_PATH.$user->get_attribute('id');
                    $to_path = $GROUP_PATH.'/identity';
                    $old_file = $profile->get_attribute('identity_attachment');
                    $identity_path = $to_path.'/identity'.date('ymdHis').'.'.$type;

                    if(@file_exists($GROUP_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    $identity_data['identity_type'] = $this->input->post('type');
                    $identity_data['identity_number'] = $this->input->post('id');
                    $identity_data['updated_at'] = date('Y-m-d H:i:s');
                    $identity_data['updated_by'] = $this->user_id;

                    if(move_uploaded_file($tmp_uploaded_file,$identity_path)){
                        $identity_data['identity_attachment'] = $identity_path;
                        @unlink($old_file);
                    }



                    $this->group_profile->save($identity_data,$profile->get_attribute('id'));
                    $this->set_notification("Group Identity Uploaded","Identity document of Group [{$profile->get_attribute('group_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$identity_data['identity_attachment'],'success_messages'=>'Successfully identity document attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('groups');
        }
    }


    public function upload_modality()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'groups', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token = $this->input->post('token');
            $profile = $this->group_profile->find_by_token($token);
            if ($profile->has_attributes()) {

                $modality_data = array(
                    'token'  => $this->input->post('token'),
                    'business_modality'  => $this->input->post('business_modality'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->user_id
                );

                $update = $this->group_profile->save($modality_data,$profile->get_attribute('id'));
                if ($update) {
                    echo json_encode(array('status'=>200,'success_messages'=>'Business Modality Successfully Saved'));
                }
                $this->set_notification("Group Business Modality Updated","Business modality of Group [{$profile->get_attribute('group_name')}] has been updated");
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'Profile not exist to update business modality'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('groups');
        }
    }

    public function view($token){

        $profile = $this->group_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('Group View - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/group/group-view.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('group/view_group_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('groups');
        }
    }


    public function edit($token){

        if($this->role_type == "staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'groups', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
                redirect('groups');
            }
        }

        $profile = $this->group_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('Group Edit - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/group/group-edit.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token;
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('group/edit_group_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('groups');
        }
    }

    public function ajax_get_profile($token)
    {

        $profile = $this->group_profile->get_profile_by_token($token);
        //$billing_address = $this->billing_address->find_by_token($token);
        if($profile->region_l1_code != null || $profile->region_l2_code != null ||
            $profile->region_l3_code != null || $profile->region_l4_code != null){
            $profile->business_region_assigned = 1;
        }
        $roles = $this->role->get_role_for_permission($this->role_type,$this->user_type);
        echo json_encode(array(
            'status'=>200,
            'profile'=>$profile,
            'roles'  => $roles,
            //'billing_address' => $billing_address->get_attributes(),
            'countries' => $this->country->get_all(),
            'role_type' => $this->role_type,
            'user_type' => $this->user_type
        ));
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