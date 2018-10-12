<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Lco
 * @property Lco_profile_model $lco_profile
 * @property Group_profile_model $group_profile
 * @property Subscriber_profile_model $subscriber_profile
 * @property Stb_Provider_model $stb_provider
 * @property Device_model $device
 */
class Lco extends BaseController
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
    const GROUP_LOWER ='group';
    const ADMIN = 'admin';
    const STAFF = 'staff';

    public function __construct()
    {
        /*parent::__construct();
        $this->theme->set_theme('katniss');
        $this->theme->set_layout('main');
        $this->user_type = strtolower($this->user_session->user_type);
        $this->user_id = $this->user_session->id;
        $this->parent_id = $this->user_session->parent_id;*/

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


        $this->theme->set_title('LSP User Creation - Application')
                ->add_style('component.css')
                ->add_style('custom.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_script('controllers/lco/lco.js');

        $data['countries'] = $this->country->get_all();
        $data['all_division'] = $this->division->get_divisions();
        $data['all_district'] = $this->district->get_districts();
        $data['all_area'] = $this->area->get_areas();
        $data['user_info'] = $this->user_session;

        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('lco_profile/lco_profile', $data, true);
    }

    public function ajax_get_permissions()
    {
        if($this->role_type == "admin"){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $permissions = $this->menus->has_permission($this->role_id,1,'lco',$this->user_type);
        }
        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function ajax_get_assign_stb_permission(){
        if($this->role_type == 'admin'){
            $permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
        }else{
            $role = $this->user_session->role_id;
            $permissions = $this->menus->has_permission($role,1,'lco-assign-stb',$this->user_type);
        }
        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function ajax_get_assign_card_permission(){
        if($this->role_type == 'admin'){
            $permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
        }else{
            $role = $this->user_session->role_id;
            $permissions = $this->menus->has_permission($role,1,'lco-assign-card',$this->user_type);
        }
        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function create_profile()
    {
        if($this->role_type=="staff"){
            $permission = $this->menus->has_create_permission($this->role_id,1,'lco',$this->user_type);
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
                redirect('lco');
            }
        } else {

            /* User Profile Information */
            $message_sign = $this->input->post('message_sign');
            $save_profile_data = array(
                'lco_name'        => $this->input->post('full_name'),
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

            $profile_insert_id = $this->lco_profile->save($save_profile_data);

            if ($profile_insert_id) {


                $save_login_data = $array = array(
                    'profile_id' => $profile_insert_id,
                    'role_id'    => ($this->user_session->user_type == self::MSO_UPPER)? 3 : 4,
                    'username'   => $this->input->post('email'),
                    'email'      => $this->input->post('email'),
                    'password'   => md5($this->input->post('email')),
                    'is_remote_access_enabled' => $this->input->post('is_remote_access_enabled'),
                    'lsp_type_id' => $this->input->post('lsp_type_id'),
                    'user_type'  => 'LCO',
                    'user_status'=> 1,
                    'token'      => $save_profile_data['token']
                    );

                $user_id = $this->user->save($save_login_data);

                if(!empty($user_id)){
                    $this->load->model('Settings_model','settings');
                    $this->settings->setTblInstance('api_settings');
                    $this->settings->save(array(
                        'parent_id' => $user_id,
                        'geo_ip_authorization' => 0,
                        'geo_territory_authorization' => 0,
                        'content_authorization' =>0,
                        'default_image_path' => base_url('/'),
                        'default_share_url'  => base_url('/'),
                        'website' => '',
                        'is_email_send' => 1,
                        'is_sms_send' => 0,
                        'reg_email' => '',
                        'reg_email_password' => '',
                        'confirm_code_template' => '',
                        'email_from_template' => ''
                    ));

                    $this->organization->save(array(
                        'organization_name' => $save_profile_data['lco_name'],
                        'parent_id' => $user_id
                    ));
                }

                if(@file_exists(LCO_PATH)){
                    @mkdir(LCO_PATH.$user_id,0777);
                }else{
                    @mkdir(LCO_PATH.$user_id,0777,true);
                }

                
            }
            $this->set_notification("New LCO Profile Created","New LCO Profile [{$save_profile_data['lco_name']}] has been created");
            echo json_encode(array('status'=>200,'token'=>$save_profile_data['token'],'success_messages'=>'Lco user '.$save_profile_data['lco_name'].' profile created successfully'));
        }
    }

    public function update_profile()
    {
        if($this->role_type=="staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                exit;
            }
        }

        $this->form_validation->set_rules('lco_name', 'Full Name', 'required');
        $this->form_validation->set_rules('email', 'E-mail','required');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            } else {
                $this->session->set_flashdata('warning_messages',validation_errors());
                redirect('lco');
            }
        } else {


            /* User Profile Information */
            $message_sign = $this->input->post('message_sign');
            $save_profile_data = array(
                'lco_name'        => $this->input->post('lco_name'),
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
            $this->lco_profile->save($save_profile_data,$profile_id);
            $this->set_notification("LCO Profile Updated","LCO Profile [{$save_profile_data['lco_name']}] has been updated");
            echo json_encode(array('status'=>200,'token'=>$this->input->post('token'),'success_messages'=>'Lco user '.$save_profile_data['lco_name'].' profile created successfully'));
        }
    }


    public function create_login_info()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff"){
                $permission = $this->menus->has_create_permission($this->role_id,1,'lco',$this->user_type);
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
                $profile = $this->lco_profile->find_by_token($token);
                $user    = $this->user->find_by_token($token);
                $role_id = $this->input->post('role_id');
                if ($profile->get_attribute('id')) {
                    $save_login_data = $array = array(
                        'profile_id' => $profile->get_attribute('id'),
                        'role_id'    => (!empty($role_id)) ? $role_id : (($this->user_session->user_type == self::MSO_UPPER)? 3 : 4),
                        'username'   => $this->input->post('username'),
                        'is_remote_access_enabled' => $this->input->post('is_remote_access_enabled'),
                        'lsp_type_id' => $this->input->post('lsp_type_id'),
                        'user_type'  => 'LCO',
                        'user_status'=> 1,
                        'token'      => $profile->get_attribute('token')
                        );

                    if ($user->has_attributes()) {
                        $password = $this->input->post('password');
                        if(!empty($password))
                           $save_login_data['password']   = md5($password);
                        $this->user->save($save_login_data,$user->get_attribute('id'));
                        $this->set_notification("LCO Login Info Updated","Login Information of LCO Profile [{$profile->get_attribute('lco_name')}] has been updated");
                   } else {

                    $this->user->save($save_login_data);
                    $this->set_notification("LCO Login Info Created","Login Information of LCO Profile [{$profile->get_attribute('lco_name')}] has been created");
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
            }

                echo json_encode(array('status'=>200,'success_messages'=>'Login Info of User '.$profile->get_attribute('lco_name').' created successfully'));    
            }

        } else {

            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('lco');
        }
    }

    public function update_login_info()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                // Authorization Checking
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco', $this->user_type);
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
            $profile = $this->lco_profile->find_by_token($token);
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
                        'lsp_type_id' => $this->input->post('lsp_type_id'),
                        'user_type'  => self::LCO_UPPER,
                        'user_status'=> 1,
                        'token'      => $profile->get_attribute('token')
                        );

                    if ($user->has_attributes()) {

                        if(!empty($password))
                           $save_login_data['password']   = md5($password);
                        
                        $this->user->save($save_login_data,$user->get_attribute('id'));
                        $this->set_notification("LCO Login Info Updated","Login Information of LCO Profile [{$profile->get_attribute('lco_name')}] has been updated");
                        echo json_encode(array('status'=>200,'success_messages'=>'Login username '.$username.' updated successfully'));    
                    } else {

                    $this->user->save($save_login_data);
                    $this->set_notification("LCO Login Info Created","Login Information of LCO Profile [{$profile->get_attribute('lco_name')}] has been created");
                    
                    }


            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
            }

        } else {

            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('lco');
        }
    }


    public function edit($token){
        if($this->user_type == self::GROUP_LOWER){
            redirect('lco');
        }
        if($this->role_type == "staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
                redirect('lco');
            }
        }

        $profile = $this->lco_profile->find_by_token($token);

        if ($profile->has_attributes()) {
            $this->theme->set_title('LSP Edit - Application')
            ->add_style('kendo/css/kendo.common-material.min.css')
            ->add_style('kendo/css/kendo.material.min.css')
            ->add_style('component.css')
            ->add_script('controllers/lco/lco-edit.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token; 
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('lco_profile/edit_lco_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('lco');
        }
    }

    public function view($token){

        $profile = $this->lco_profile->find_by_token($token);

        if ($profile->has_attributes()) {

            $this->theme->set_title('LSP View - Application')
            ->add_style('kendo/css/kendo.common-material.min.css')
            ->add_style('kendo/css/kendo.material.min.css')
            ->add_style('component.css')
            ->add_script('controllers/lco/lco-edit.js');

            $user = $this->user->find_by_token($token);
            $lcoRole = $this->role->find_by_id($user->get_attribute('role_id'));
            $data['user_info'] = $this->user_session;
            $data['lco_role_type'] =$lcoRole->get_attribute('role_type');
            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token; 
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('lco_profile/view_lco_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('lco');
        }
    }



    public function ajax_load_profiles()
    {
        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

        if($this->user_type == self::GROUP_LOWER){
            $all_lco = $this->group_profile->get_lco_list($id,$take,$skip,$filter,$sort);
            $total   = $this->group_profile->get_count_lco($id,$filter);
        }else{

            $all_lco = $this->lco_profile->get_all_lco_users($id,$take,$skip,$filter,$sort);
            $total   = $this->lco_profile->get_count_lco_user($id,$filter);
        }
        $roles = $this->role->get_role_for_permission($this->role_type,$this->user_type);
        $this->load->model('Lsp_type_model','lsp_type');
        echo json_encode(array(
            'status'=>200,
            'profiles'=>$all_lco,
            'roles' => $roles,
            'countries' => $this->country->get_all(),
            'lsp_types' => $this->lsp_type->get_all(),
            'total' => $total,
            'role_type' => $this->role_type,
            'user_type' => $this->user_type,
            ));
    }

    public function ajax_load_staff_profiles()
    {
        $lco_id = $this->input->get('lco_id');
        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $all_lco_staff = $this->lco_profile->get_all_lco_users($lco_id,$take,$skip,$filter,$sort);
        $total = $this->lco_profile->get_count_lco_user($lco_id);
        echo json_encode(array(
            'status'=>200,
            'profiles'=> (!empty($lco_id))? $all_lco_staff : array(),
            'total' => (!empty($lco_id))? $total : 0
        ));
    }


    public function ajax_load_subscriber_profiles()
    {
        $take   = $this->input->get('take');
        $skip   = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');

        $search = $this->input->get('search');
        $id = $this->input->get('lco_id');


        $all_subscribers = [];
        $total =0;

        $id = ($id=='null')? null : $id;
        $filter['search'] = ($search == 'undefined')? array() : $search;


        $all_subscribers = $this->subscriber_profile->get_all_subscribers($id,$take,$skip,$filter,$sort);

        $total = $this->subscriber_profile->get_count_subscribers($id,$filter);

        echo json_encode(array(
            'status'=>200,
            'profiles'=>$all_subscribers,
            'total'     =>$total
        ));
    }

    public function ajax_get_profile($token)
    {

        $profile = $this->lco_profile->get_profile_by_token($token);

        /*$profile->photo               = base64_encode($profile->photo);
        $profile->identity_attachment = base64_encode($profile->identity_attachment);*/
        $billing_address = $this->billing_address->find_by_token($token);
        $profile->hex_code = region_code_generator($profile->region_l1_code,$profile->region_l2_code,$profile->region_l3_code,$profile->region_l4_code);
        if($profile->region_l1_code != null || $profile->region_l2_code != null ||
        $profile->region_l3_code != null || $profile->region_l4_code != null){
            $profile->business_region_assigned = 1;
        }
        $roles = $this->role->get_role_for_permission($this->role_type,$this->user_type);
        $this->load->model('Lsp_type_model','lsp_type');
        echo json_encode(array(
            'status'=>200,
            'profile'=>$profile,
            'roles'  => $roles,
            'billing_address' => $billing_address->get_attributes(),
            'countries' => $this->country->get_all(),
            'lsp_types' => $this->lsp_type->get_all(),
            'role_type' => $this->role_type,
            'user_type' => $this->user_type
            ));
    }

    public function ajax_load_groups()
    {
        if($this->input->is_ajax_request()){
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $all_group = $this->group_profile->get_all_group_users($id);
            // test($this->db->last_query());
            if(!empty($all_group)){

                foreach($all_group as $i=> $g){
                    $all_group[$i]->group_name = '[GROUP] '.$g->group_name;
                }

                $all_group = array_key_sort($all_group,'group_name');
            }

            array_unshift($all_group,array('user_id'=>1,'group_name'=>'MSO [ LCO ]'));
            echo json_encode(array('status'=>200,'group_profiles'=>$all_group));

        }else{
            redirect('/');
        }
    }


    public function save_billing_address()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $this->form_validation->set_rules('full_name','Name','required');
            $this->form_validation->set_rules('email','Email','required');
            $this->form_validation->set_rules('contact','Contact','required');

            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;

            } else {

                $token = $this->input->post('token');
                $user  = $this->user->find_by_token($token);
                $billing_address = $this->billing_address->find_by_token($token);

                $save_billing_address['name']            = $this->input->post('full_name');
                $save_billing_address['email']           = $this->input->post('email');
                $save_billing_address['address1']        = $this->input->post('address1');
                $save_billing_address['address2']        = $this->input->post('address2');
                $save_billing_address['country_id']      = $this->input->post('country_id');
                $save_billing_address['division_id']     = $this->input->post('division_id');
                $save_billing_address['district_id']     = $this->input->post('district_id');
                $save_billing_address['area_id']         = $this->input->post('area_id');
                $save_billing_address['sub_area_id']     = $this->input->post('sub_area_id');
                $save_billing_address['road_id']         = $this->input->post('road_id');
                $save_billing_address['contact']         = $this->input->post('contact');
                $save_billing_address['billing_contact'] = $this->input->post('billing_contact');
                $save_billing_address['token']           = $token;
                $save_billing_address['is_same_as_profile'] = $this->input->post('is_same_as_profile');
                $save_billing_address['is_same_as_contact'] = $this->input->post('is_same_as_contact');

                if (!$user->has_attributes()) {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Before add business address please make sure that is user login info exist'));
                    exit;
                }

                $save_billing_address['user_id'] = $user->get_attribute('id');
                $billing_address_id = null;

                if (!empty($billing_address->has_attributes())) {
                    $save_billing_address['updated_by'] = $this->user_id;
                    $save_billing_address['updated_at'] = date('Y-m-d H:i:s');
                    $this->billing_address->save($save_billing_address,$billing_address->get_attribute('id'));
                    $this->set_notification("LCO Billing Info Updated","Billing Information of LCO Profile [{$billing_address->get_attribute('lco_name')}] has been updated");
                } else {
                    
                    $billing_address_id = $this->billing_address->save($save_billing_address);
                    $this->set_notification("LCO Billing Info Created","Billing Information of LCO Profile [{$billing_address->get_attribute('lco_name')}] has been created");
                }

                echo json_encode(array('status'=>200,'billing_address_id'=>$billing_address_id,'success_messages'=>'Billing address saved successfully'));
                exit;
            }

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('subscriber');
        }
    }

    public function save_business_region()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

           $token = $this->input->post('token'); 
           $region_l1_code = $this->input->post('region_l1_code');
           $region_l2_code = $this->input->post('region_l2_code');
           $region_l3_code = $this->input->post('region_l3_code');
           $region_l4_code = $this->input->post('region_l4_code');
           
           $region_l1_code = ($region_l1_code>0)? $region_l1_code : 0;
           $region_l2_code = ($region_l2_code>0)? $region_l2_code : 0;
           $region_l3_code = ($region_l3_code>0)? $region_l3_code : 0;
           $region_l4_code = ($region_l4_code>0)? $region_l4_code : 0;

           $profile = $this->lco_profile->find_by_token($token);
           if ($profile->has_attributes()) {

                $business_region['region_l1_code'] = $region_l1_code;
                $business_region['region_l2_code'] = $region_l2_code;
                $business_region['region_l3_code'] = $region_l3_code;
                $business_region['region_l4_code'] = $region_l4_code;
                $business_region['updated_at'] = date('Y-m-d H:i:s');
                $business_region['updated_by'] = $this->user_id;
                $this->lco_profile->save($business_region,$profile->get_attribute('id'));
                $this->set_notification("LCO Business Region Updated","Business Region of LCO [{$profile->get_attribute('lco_name')}] has been updated");
                echo json_encode(array('status'=>200,'success_messages'=>'Business Region saved successfully'));
                exit;
                
           } else {

                echo json_encode(array('status'=>400, 'warning_messages'=>'Profile not exist to add Business region'));
                exit;
           }

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('subscriber');
        }

    }

    public function upload_photo(){

        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token');
            $profile = $this->lco_profile->find_by_token($token);
            $user    = $this->user->find_by_token($token);
            $filesize = (2*(1024*1024));

            if ($profile->has_attributes()) {
                if(preg_match('/(jpg|jpeg|png|gif)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 2MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];
                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $LCO_PATH = LCO_PATH.$user->get_attribute('id');
                    $to_path = $LCO_PATH.'/photo';
                    $old_photo = $profile->get_attribute('photo');
                    $photo_path = $to_path.'/photo_'.date('ymdHis').'.'.$type;

                    if(@file_exists($LCO_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    if(move_uploaded_file($tmp_uploaded_file,$photo_path)){
                        $photo_data['photo'] = $photo_path;
                        $photo_data['updated_at'] = date('Y-m-d H:i:s');
                        $photo_data['updated_by'] = $this->user_id;
                        $this->lco_profile->save($photo_data,$profile->get_attribute('id'));
                        @unlink($old_photo);
                    }

                    //$photo_data['updated_at'] = date('Y-m-d H:i:s');
                    //$photo_data['updated_by'] = $this->user_id;
                    //$this->lco_profile->save($photo_data,$profile->get_attribute('id'));
                    $this->set_notification("LCO Photo Uploaded","Photo of LCO [{$profile->get_attribute('lco_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$photo_data['photo'],'success_messages'=>'Successfully photo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }

        } else {
         $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
         redirect('lco');
     }


    }

    public function upload_identity()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token'); 
            $profile = $this->lco_profile->find_by_token($token);
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
                    $LCO_PATH = LCO_PATH.$user->get_attribute('id');
                    $to_path = $LCO_PATH.'/identity';
                    $old_file = $profile->get_attribute('identity_attachment');
                    $identity_path = $to_path.'/identity'.date('ymdHis').'.'.$type;

                    if(@file_exists($LCO_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    if(move_uploaded_file($tmp_uploaded_file,$identity_path)){
                        $identity_data['identity_type'] = $this->input->post('type');
                        $identity_data['identity_number'] = $this->input->post('id');
                        $identity_data['identity_attachment'] = $identity_path;
                        $identity_data['updated_at'] = date('Y-m-d H:i:s');
                        $identity_data['updated_by'] = $this->user_id;
                        $this->lco_profile->save($identity_data,$profile->get_attribute('id'));
                        @unlink($old_file);
                    }

                    //$identity_data['identity_type'] = $this->input->post('type');
                    //$identity_data['identity_number'] = $this->input->post('id');
                    //$identity_data['updated_at'] = date('Y-m-d H:i:s');
                    //$identity_data['updated_by'] = $this->user_id;
                    $this->lco_profile->save($identity_data,$profile->get_attribute('id'));
                    $this->set_notification("LCO Identity Uploaded","Identity document of LCO [{$profile->get_attribute('lco_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$identity_data['identity_attachment'],'success_messages'=>'Successfully identity document attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('lco');
        }
    }

    public function update_modality()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=="staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token = $this->input->post('token');
            $profile = $this->lco_profile->find_by_token($token);
            if ($profile->has_attributes()) {

                $modality_data = array(               
                    'token'  => $this->input->post('token'),
                    'business_modality'  => $this->input->post('business_modality'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->user_id
                    );

                $update = $this->lco_profile->save($modality_data,$profile->get_attribute('id'));
                if ($update) {
                    echo json_encode(array('status'=>200,'success_messages'=>'Business Modality Successfully Saved'));
                }
                $this->set_notification("LCO Business Modality Updated","Business modality of LCO [{$profile->get_attribute('lco_name')}] has been updated");
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'Profile not exist to update business modality'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('lco');
        }

    }

    //assigning stb to lco
    public function assign_stb()
    {
        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }

        $this->theme->set_title('Assign STB')
        ->add_style('component.css')
        ->add_style('kendo/css/kendo.common-bootstrap.min.css')
        ->add_style('kendo/css/kendo.bootstrap.min.css')
        ->add_script('controllers/lco/lco-assign-stb.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('lco_profile/assign_stb', $data, true);
    }


    public function ajax_load_assign_stb_data()
    {
        if ($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            if($this->user_type == self::GROUP_LOWER){
                $all_lco = $this->group_profile->get_lco_list($id);
            }else{
                $all_lco = $this->lco_profile->get_lco_users($id);
            }
            $stb_types   = $this->stb_provider->get_all_distinct_provider_type($id);
            echo json_encode(array(
                'status' => 200,
                'lco_profile' =>$all_lco,
                'stb_type' => $stb_types
                ));
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('lco/lco-assign-stb');
        }
    }

    public function search_stb()
    {
            // if ($this->input->is_ajax_request()) {

        $take = $this->input->get('take');
        $skip = $this->input->get('skip');

        $stb_type_id = $this->input->get('stb_type_id');
        $stb_number  = $this->input->get('stb_number');
        $data = $this->stb_provider->get_cards($stb_type_id,$stb_number, array($_GET['pageSize'], $_GET['skip']));

        echo json_encode(array(
            'status'=>200,
            'cards'=> $data
            ));

            // } else {
            //     $this->session->set_flashdata('warning_messages','Direct access not allowed');
            //     redirect('lco/lco-assign-stb');
            // }

    }

    public function search_device()
    {
        // if ($this->input->is_ajax_request()) {

        $take = $this->input->get('take');
        $skip = $this->input->get('skip');

        $stb_type_id = $this->input->get('stb_type_id');
        $stb_number  = $this->input->get('stb_number');
        $data = $this->stb_provider->get_devices($stb_number, array($_GET['pageSize'], $_GET['skip']));

        echo json_encode(array(
            'status'=>200,
            'cards'=> $data
        ));

        // } else {
        //     $this->session->set_flashdata('warning_messages','Direct access not allowed');
        //     redirect('lco/lco-assign-stb');
        // }

    }

    public function assign_stb_to_lco()
    {
        if ($this->input->is_ajax_request()) {

            if($this->role_type=="staff") {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco-assign-stb', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                    exit;
                }
            }

            $lco_id = $this->input->post('lco_user_id');
            $boxes  = $this->input->post('cards');
            $lco_profile_name = $this->lco_profile->get_lco_name($lco_id);

            if (empty($boxes)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Select Set-Top boxes'));
                exit;
            }

            if (empty($lco_id)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Select LCO'));
                exit;
            }
            
            $this->set_top_box->assign_to_lco($lco_id,$boxes,$this->user_id);
            $this->set_notification("STB assigned to LCO","STB Card has been assigned to LCO [{$lco_profile_name}]");
            echo json_encode(array('status'=>200,'success_messages'=>count($boxes). ' STB assigned to this LCO'));
            exit;

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('lco/lco-assign-stb');
        }
    }

    public function assign_device_to_lco()
    {
        if ($this->input->is_ajax_request()) {

            if($this->role_type==self::STAFF) {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'assign-device', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                    exit;
                }
            }

            $lco_id = $this->input->post('lco_user_id');
            $boxes  = $this->input->post('cards');

            $lco_profile_name = $this->lco_profile->get_lco_name($lco_id);

            if (empty($boxes)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Select Devices'));
                exit;
            }

            if (empty($lco_id)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Select LCO'));
                exit;
            }

            $this->device->assign_to_lco($lco_id,$boxes,$this->user_id);
            $this->set_notification("Device assigned to LCO","Device has been assigned to LCO [{$lco_profile_name}]");
            echo json_encode(array('status'=>200,'success_messages'=>count($boxes). ' Device assigned to this LCO'));
            exit;

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('assign-device');
        }
    }

    // assigning smart card to lco
    public function assign_card()
    {
        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }

        $this->theme->set_title('Assign Smart Card')
        ->add_style('component.css')
        ->add_style('kendo/css/kendo.common-bootstrap.min.css')
        ->add_style('kendo/css/kendo.bootstrap.min.css')
        ->add_script('controllers/lco/lco-assign-card.js');

        $data['user_info'] = $this->user_session;

        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('lco_profile/assign_card', $data, true);
    }

    public function ajax_load_assign_smartcard_data()
    {
        if ($this->input->is_ajax_request()) {
            $id =($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $all_lco = $this->lco_profile->get_all_lco_users($id);
            $stb_types   = $this->ic_smart_provider->get_all_distinct_provider_type($id);
            echo json_encode(array(
                'status' => 200,
                'lco_profile' =>$all_lco,
                'stb_type'        => $stb_types
                ));
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('lco/lco-assign-stb');
        }
    }

    public function search_smartcard()
    {
        $stb_type_id = $this->input->get('stb_type_id');
        $smartcard_number  = $this->input->get('smartcard_number');
        $data = $this->ic_smart_provider->get_cards($stb_type_id, $smartcard_number, array($_GET['pageSize'], $_GET['skip']));

        echo json_encode(array(
            'status'=>200,
            'cards'=>$data,
            ));

    }

    public function assign_smartcard_to_lco()
    {
        if ($this->input->is_ajax_request()) {

            if($this->role_type=="staff") {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'lco-assign-card', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                    exit;
                }
            }

            $lco_id = $this->input->post('lco_user_id');
            $cards  = $this->input->post('cards');
            $lco_profile_name = $this->lco_profile->get_lco_name($lco_id);
            
            if (empty($cards)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Select Smart cards'));
                exit;
            }

            if (empty($lco_id)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Select LCO'));
                exit;
            }
            
            $this->ic_smartcard->assign_to_lco($lco_id,$cards,$this->user_id);
            $this->set_notification("SmartCard assigned to LCO","SmartCard has been assigned to LCO [{$lco_profile_name}]");
            echo json_encode(array('status'=>200,'success_messages'=>count($cards). ' Smart Card assigned to this LCO'));
            exit;

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('lco/lco-assign-stb');
        }
    }

    public function ajax_load_region()
    {
        if ($this->input->is_ajax_request()) {
            $regions = $this->region_level_one->get_regions();
            echo json_encode($regions);
        } else {
            redirect('lco');
        }
    }

    public function image_download($imagedata)
    {
        if($_GET['id']=='sub_copy'){
            //echo $_GET['id'];
            $data = $this->subscriber_profile->find_by_token($imagedata);
        //print_r($data->get_attribute('subscription_copy'));exit;
            $file = $data->get_attribute('subscription_copy');
        }elseif($_GET['id']=='identity'){

            $data = $this->subscriber_profile->find_by_token($imagedata);
            $file = $data->get_attribute('identity_attachment');
        }elseif($_GET['id']=='lco_profile'){

            $data = $this->lco_profile->find_by_token($imagedata);
            $file = $data->get_attribute('photo');

        }elseif($_GET['id']=='lco_identity'){

            $data = $this->lco_profile->find_by_token($imagedata);
            $file = $data->get_attribute('identity_attachment');

        }else{
            
            redirect('lco');
        }
        
        // $file = base64_decode($data->get_attribute('photo'));data:image/png;base64,
        
        $fileLocation = "download.png";

        file_put_contents($fileLocation, $file);

        header('Content-Description: File Transfer');
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="'.basename($fileLocation).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileLocation));
        readfile($fileLocation);
        unlink($fileLocation);
        exit;
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

    public function ajax_load_lco_profiles()
    {
        $id =($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $all_lco = $this->lco_profile->get_lco_users($id);
        echo json_encode(array('status'=>200,'lco_profile'=>$all_lco));
    }

    public function ajax_load_lco($Id,$user_type)
    {
        if ($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

            if($user_type == 'MSO'){
                $all_lco = $this->lco_profile->get_mso_lco_users($id);
            }else if($user_type == 'Group'){
                $all_lco = $this->group_profile->get_lco_list($Id);
            }

            echo json_encode(array(
                'status' => 200,
                'lco_profile' =>$all_lco,

            ));
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('/');
        }
    }

    public function lco_users()
    {
        $this->theme->set_title('LCO Users')
                ->add_style('component.css')
                ->add_style('custom.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_script('controllers/lco/lco_users.js');

        $data['user_info'] = $this->user_session;

        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('lco_profile/lco_users', $data, true);
    }

    public function lco_subscribers()
    {
        $this->theme->set_title('LCO Subscribers')
                ->add_style('component.css')
                ->add_style('custom.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_script('controllers/lco/lco_subscribers.js');

        $data['user_info'] = $this->user_session;

        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('lco_profile/lco_subscribers',$data,true);
    }

    public function ajax_get_request($type)
    {

        if ($this->input->is_ajax_request()) {

            switch ($type) {
                case 'divisions':
                    $country_id = $this->input->post('country_id');
                    echo json_encode($this->country->get_divisions($country_id));
                    break;
                case 'districts':
                    $division_id = $this->input->post('division_id');
                    echo json_encode($this->division->get_districts($division_id));
                    break;
                case 'areas':
                    $district_id = $this->input->post('district_id');
                    echo json_encode($this->district->get_areas($district_id));
                    break;
                case 'sub_areas':
                    $area_id = $this->input->post('area_id');
                    echo json_encode($this->area->get_sub_areas($area_id));
                    break;
                case 'sub_sub_areas':
                    $sub_area_id = $this->input->post('sub_area_id');
                    echo json_encode($this->sub_area->get_sub_sub_areas($sub_area_id));
                    break;
                case 'roads':
                    $sub_area_id = $this->input->post('sub_area_id');
                    echo json_encode($this->sub_area->get_roads($sub_area_id));
                    break;

                default:
                    redirect('location');
                    break;
            }
        } else {

            rediect('location');
        }
    }

    public function import()
    {
        $this->theme->set_title('LCO Import')
            ->add_style('component.css')
            ->add_style('custom.css')
            ->add_script('controllers/lco/lco-import.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('lco_profile/import', $data, true);
    }

    public function import_lco()
    {
        if($this->input->is_ajax_request()){
            if(!empty($_FILES)) {
                $tempPath = $_FILES['file']['tmp_name'];
                $uploadPath = 'public/uploads/templats' . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
                $types = array(
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                );
                if (!in_array($_FILES['file']['type'], $types)) {
                    echo json_encode(array("status"=>400,'warning_messages'=>'Sorry! File type must be in (.xlsx) format'));
                    exit;
                }
                $uploaded = move_uploaded_file($tempPath, $uploadPath);
                if (!$uploaded) {
                    echo json_encode(array("status"=>400,"error_messages"=>"Sorry! file didn't uploaded"));
                    exit;
                }

                require('public/extra-classes/php-excel-reader/excel_reader2.php');
                require('public/extra-classes/SpreadsheetReader.php');

                try {

                    $Spreadsheet = new SpreadsheetReader($uploadPath);
                    $Sheets = $Spreadsheet->Sheets();
                    $response = array();
                    $lco_profiles = array();
                    foreach ($Sheets as $Index => $Name) {
                        $Spreadsheet->ChangeSheet($Index);

                        foreach ($Spreadsheet as $key => $value) {

                            if ($key >= 2) {
                                //
                                /*if(empty($value[2])){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$key.'Email field blank found in Excel File'));

                                    exit;
                                }*/

                                if(empty($value[2]) && empty($value[3]) && empty($value[7]) && empty($value[16])&& empty($value[17]) && empty($value[18]) && empty($value[19])){
                                    break;
                                }

                                $email = str_replace(array("*","if Yes"),"",trim($value[2]));
                                $username = strtolower(str_replace(" ","_",str_replace(array("#","*","/"),"",trim($value[3]))));

                                if(empty($email) && empty($username)){
                                    continue;
                                }

                                $emailObj = $this->lco_profile->get_lco_by_email($email);
                                $usernameObj = $this->lco_profile->get_lco_by_username($username);
                                $response['email_duplicate'][] = $email;
                                $response['username_duplicate'][] = $username;
                                if(!empty($emailObj)){
                                    $response['email'][]  = $emailObj->email;
                                }
                                if(!empty($usernameObj)){
                                    $response['username'][] = $usernameObj->username;
                                }
                                $mobileNo = (!empty($value[7]))? trim($value[7]) : '';

                                if(preg_match('/^1/',$mobileNo)){
                                    $mobileNo  = '0'.$mobileNo;
                                }

                                $lco_profiles[$key]['nick']       = (!empty($value[0]))? trim($value[0]) : '';
                                $lco_profiles[$key]['full_name']  = (!empty($value[1]))? trim($value[1]) : '';
                                $lco_profiles[$key]['email']      = $email;
                                $lco_profiles[$key]['username']   = $username;
                                $lco_profiles[$key]['password']   = $username;
                                $lco_profiles[$key]['address1']   = (!empty($value[5]))? trim($value[5]) : '';
                                $lco_profiles[$key]['address2']   = (!empty($value[6]))? trim($value[6]) : '';
                                $lco_profiles[$key]['mobile_no']  = $mobileNo;
                                $lco_profiles[$key]['message_sign'] = (!empty($value[1]))? trim($value[8]) : '';

                                /*$hexCode = region_code_decode($value[7]);
                                $code = str_split($hexCode);*/

                                //$lco_profiles[$key]['business_region_code'] = $this->input->post();
                                $lco_profiles[$key]['nid'] = trim($value[9]);

                                $lco_profiles[$key]['country'] = trim($value[10]);
                                $lco_profiles[$key]['division'] = trim($value[11]);
                                $lco_profiles[$key]['district'] = trim($value[12]);
                                $lco_profiles[$key]['area'] = trim($value[13]);
                                $lco_profiles[$key]['sub_area'] = trim($value[14]);
                                $lco_profiles[$key]['road'] = trim($value[15]);
                                $lco_profiles[$key]['level1'] = trim($value[16]);
                                $lco_profiles[$key]['level2'] = trim($value[17]);
                                $lco_profiles[$key]['level3'] = trim($value[18]);
                                $lco_profiles[$key]['level4'] = trim($value[19]);
                                $lco_profiles[$key]['mso']  = 1;

                            }
                        }
                    }


                    //test($lco_profiles);
                    if(count($lco_profiles)>1500){
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! You cannot import more than 1500 records at a time'));
                        exit;
                    }

                    if(count($response['email_duplicate']) != count(array_unique($response['email_duplicate']))){
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! You cannot import, some email duplicate in Excel File.'));
                        exit;
                    }

                    if(isset($response['email']) && count($response['email'])){
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! You cannot import, some emails already exist. ['.implode(',',$response['email']).']'));
                        exit;
                    }

                    if(count($response['username_duplicate']) != count(array_unique($response['username_duplicate']))){
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! You cannot import, some username duplicate in Excel File.'));
                        exit;
                    }

                    if(isset($response['username']) && count($response['username'])){
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! You cannot import, some username already exist. ['.implode(',',$response['username']).']'));
                        exit;
                    }


                    //test($lco_profiles);
                    foreach($lco_profiles as $key=> $profile){

                        $country  = $this->country->find_by_name($profile['country']);
                        $division = $this->division->find_by_name($profile['division']);
                        $district = $this->district->find_by_name($profile['district']);
                        $area     = $this->area->find_by_name($profile['area']);
                        $sub_area = $this->sub_area->find_by_name($profile['sub_area']);
                        $road     = $this->road->find_by_name($profile['road']);
                        //test($road);
                        //$regions  = $profile['business_region_code'];
                        $save_profile_data = array(
                            'lco_name'        => $profile['full_name'].' ( '.$profile['nick'].' )',
                            'address1'        => $profile['address1'],
                            'address2'        => $profile['address2'],
                            'country_id'      => (!empty($country))? $country->id :null,
                            'division_id'     => (!empty($division))? $division->id :null,
                            'district_id'     => (!empty($district))? $district->id :null,
                            'area_id'         => (!empty($area))? $area->id :null,
                            'sub_area_id'     => (!empty($sub_area))? $sub_area->id :null,
                            'road_id'         => (!empty($road))? $road->id :null,
                            'identity_type'   => (!empty($profile['nid']))? 'NID' : null,
                            'identity_number' => (!empty($profile['nid']))? $profile['nid'] : null,
                            'contact'         => $profile['mobile_no'],
                            'is_same_as_contact' => 1,
                            'billing_contact' => $profile['mobile_no'],
                            'region_l1_code'  => (!empty($profile['level1']) && $profile['level1'] != 'undefined')? $profile['level1'] : null,
                            'region_l2_code'  => (!empty($profile['level2']) && $profile['level2'] != 'undefined')? $profile['level2'] : null,
                            'region_l3_code'  => (!empty($profile['level3']) && $profile['level3'] != 'undefined')? $profile['level3'] : null,
                            'region_l4_code'  => (!empty($profile['level4']) && $profile['level4'] != 'undefined')? $profile['level4'] : null,
                            'message_sign'    => strtoupper($profile['message_sign']),
                            'token'           => md5(time().$profile['email']),
                        );

                        //test($save_profile_data);

                        $profile_insert_id = $this->lco_profile->save($save_profile_data);

                        if ($profile_insert_id) {


                            $save_login_data = $array = array(
                                'profile_id' => $profile_insert_id,
                                'role_id'    => ($this->user_session->user_type == self::MSO_UPPER)? 3 : 4,
                                'username'   => (!empty($profile['username']))? $profile['username'] : $profile['email'],
                                'email'      => $profile['email'],
                                'password'   => md5($profile['password']),
                                'user_type'  => 'LCO',
                                'user_status'=> 1,
                                'token'      => $save_profile_data['token']
                            );

                            $user_id = $this->user->save($save_login_data);

                            $token = $save_profile_data['token'];
                            if($user_id){

                                $save_billing_address['user_id']            = $user_id;
                                $save_billing_address['name']               = $save_profile_data['lco_name'];
                                $save_billing_address['email']              = $profile['email'];
                                $save_billing_address['address1']           = $profile['address1'];
                                $save_billing_address['address2']           = $profile['address2'];
                                $save_billing_address['country_id']         = (!empty($country))? $country->id :null;
                                $save_billing_address['division_id']        = (!empty($division))? $division->id :null;
                                $save_billing_address['district_id']        = (!empty($district))? $district->id :null;
                                $save_billing_address['area_id']            = (!empty($area))? $area->id :null;
                                $save_billing_address['sub_area_id']        = (!empty($sub_area))? $sub_area->id :null;
                                $save_billing_address['road_id']            = (!empty($road))? $road->id :null;
                                $save_billing_address['contact']            = $profile['mobile_no'];
                                $save_billing_address['billing_contact']    = $profile['mobile_no'];
                                $save_billing_address['is_same_as_profile'] = 1;
                                $save_billing_address['is_same_as_contact'] = 1;
                                $save_billing_address['token']              = $token;
                                $this->billing_address->save($save_billing_address);
                            }


                            if(@file_exists(LCO_PATH)){
                                @mkdir(LCO_PATH.$user_id,0777);
                            }else{
                                @mkdir(LCO_PATH.$user_id,0777,true);
                            }


                        }
                    }

                    echo json_encode(array('status'=>200,'success_messages'=>'LCO successfully imported'));
                    exit;

                } catch (Exception $ex) {

                }

            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! file is not uploaded'));
                exit;
            }

        }else{
            redirect('/');
        }
    }

    public function assign_device()
    {
        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }

        $this->theme->set_title('Assign Device')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/devices/assign-device.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('devices/assign_device', $data, true);
    }

    public function download_subscriber_list($type,$id=null)
    {


        $type = ($type == 'undefined')? 'none' : $type;
        $id = ($id == 'null') ? null : $id;
        if(!in_array($type,array('none','zero-balance','active','expired','no-package'))){
            $this->session->set_flashdata('warning_messages','Sorry! Search Type is not valid');
            redirect('lco-subscribers');
        }
        $filter['search'] = $type;
        $data = $this->subscriber_profile->get_all_subscribers($id,null,null,$filter);



        require APPPATH.'libraries/fpdf/LcoSubscriberList.php';
        $pdf = new LcoSubscriberList('L','mm','A4');
        $pdf->setData(array(
            'filter' => $type,
            'user_type' => $this->user_type,
            'id' => $id,
            'parentName' => (!empty($id))? $this->lco_profile->get_lco_name($id) : ''

        ));
        $pdf->AddPage();

        $w=38;
        if($this->user_type != 'lco' && empty($id)){
            $w=33;
            $w = $w-1;
        }

        $h=7;

        $pdf->SetFont('Arial','',12);
        foreach($data as $d){
            $pdf->Cell($w+10,$h,$d->subscriber_name,1,0,'C');
            if($this->user_type != 'lco' && empty($id)){

                $pdf->SetFont('Arial','',10);
                $pdf->Cell($w+10,$h,(($d->parentName)? $d->parentName:''),1,0,'C');
            }


            $pdf->SetFont('Arial','',12);
            $pdf->Cell($w,$h,(($d->total_stb)? $d->total_stb:'0'),1,0,'C');
            $pdf->Cell($w,$h,(($d->total_packages)? $d->total_packages: '0'),1,0,'C');
            $pdf->Cell($w,$h,(($d->total_payable)?$d->total_payable : '0') ,1,0,'C');
            $pdf->Cell($w,$h,(($d->balance)? $d->balance : '0'),1,0,'C');
            $pdf->Cell($w,$h,(($d->subscription>0)? 'Expired' : 'Not Expired'),1,0,'C');
            $pdf->Cell($w,$h,(($d->user_status)? 'Active':'In-active'),1,1,'C');

        }

        $dir = 'public/downloads/pdf/';
        $filename = 'lco_subscriber_list_'.time().'.pdf';
        $pdf->Output('D',$filename);

        $file_name = $dir.$filename;
        if (file_exists($file_name)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
            header('Expires: 0');
            header("Cache-Control: no-cache, must-revalidate");
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
            readfile($file_name);
            if(file_exists($file_name)){
                unlink($file_name);
            }
            exit;
        }
        exit;

    }
                                                                                                                                                                                                                                                                                                 
}