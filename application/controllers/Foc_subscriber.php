<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foc_subscriber extends BaseController
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
        $this->role_id   = $this->user_session->role_id;

        /*if($this->user_type == self::LCO_LOWER){
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);

        }*/

        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }
    }

    public function index() 
    {
        $this->theme->set_title('Subscriber Creation - Application')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/foc_subscriber/subscriber.js');

        $data['countries'] = $this->country->get_all();
        $data['all_division'] = $this->division->get_divisions();
        $data['all_district'] = $this->district->get_districts();
        $data['all_area']     = $this->area->get_areas();
        $data['user_info']    = $this->user_session;

        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('foc_subscriber_profile/subscriber_profile', $data, true);
    }

    public function ajax_get_permissions()
    {
        if($this->role_type == "admin") {
            $permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
        }else{
            $role = $this->user_session->role_id;
            $permissions = $this->menus->has_permission($role,1,'foc-subscriber',$this->user_type);
        }

        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function create_profile()
    {

        //$form_type = $this->input->post('form_type');
        //echo $this->db->last_query();
        //test($permission);
        if($this->role_type == "staff") {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
            if (!$permission) {
                // if(!$form_type){
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
                // }
            }
        }


        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $this->form_validation->set_rules('email', 'E-mail','required|is_unique[users.email]');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
            } else {
                $this->session->set_flashdata('warning_messages',validation_errors());
                redirect('subscriber');
            }
        } else {
            $foc_control_room = $this->input->post('foc_control_room');
            $foc_others = $this->input->post('foc_others');
            /* User Profile Information */
            $save_profile_data = array(
                'subscriber_name' => $this->input->post('full_name'),
                'address1'        => $this->input->post('address1'),
                'address2'        => $this->input->post('address2'),
                'country_id'      => $this->input->post('country_id'),
                'division_id'     => $this->input->post('division_id'),
                'district_id'     => $this->input->post('district_id'),
                'area_id'         => $this->input->post('area_id'),
                'sub_area_id'     => $this->input->post('sub_area_id'),
                'road_id'         => $this->input->post('road_id'),
                'contact'         => $this->input->post('contact'),
                'is_foc'          => 1,
                'foc_control_room'=> (isset($foc_control_room))? $foc_control_room : 0,
                'foc_others'      => (isset($foc_others))? $foc_others:0,
                'reference_type'   => $this->input->post('reference_type'),
                'reference_id'     => $this->input->post('reference_id'),
                'remarks'          => $this->input->post('remarks'),
                'region_l1_code'  => ($this->input->post('region_l1_code'))? $this->input->post('region_l1_code') : 0,
                'region_l2_code'  => ($this->input->post('region_l2_code'))? $this->input->post('region_l2_code') : 0,
                'region_l3_code'  => ($this->input->post('region_l3_code'))? $this->input->post('region_l3_code') : 0,
                'region_l4_code'  => ($this->input->post('region_l4_code'))? $this->input->post('region_l4_code') : 0,
                'is_same_as_contact' => $this->input->post('is_same_as_contact'),
                'billing_contact' => $this->input->post('billing_contact'),
                'identity_type'   => $this->input->post('type'),
                'identity_number' => $this->input->post('identity_number'),
                'token'           => md5(time().$this->input->post('email'))
            );
            //test($save_profile_data);

            $profile_insert_id = $this->subscriber_profile->save($save_profile_data);

            if ($profile_insert_id) {


                $save_login_data = $array = array(
                    'profile_id' => $profile_insert_id,
                    'role_id'    => 5,
                    'username'   => $this->input->post('email'),
                    'email'      => $this->input->post('email'),
                    'password'   => md5($this->input->post('email')),
                    'user_type'  => 'Subscriber',
                    'user_status'=> 1,
                    'token'      => $save_profile_data['token']

                );

                $user_id = $this->user->save($save_login_data);

                if(@file_exists(SUBSCRIBER_PATH)){
                    @mkdir(SUBSCRIBER_PATH.$user_id,0777);
                }else{
                    @mkdir(SUBSCRIBER_PATH.$user_id,0777,true);
                }

                
            }
            $this->set_notification("New Foc Subscriber Profile Created","New Foc Subscriber Profile [{$save_profile_data['subscriber_name']}] has been created");
            echo json_encode(array('status'=>200,'token'=>$save_profile_data['token'],'success_messages'=>'Subscriber '.$save_profile_data['subscriber_name'].' profile created successfully'));
            exit;
        }
    }

    public function update_profile()
    {
        if($this->role_type == 'staff') {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                exit;
            }
        }

        $this->form_validation->set_rules('subscriber_name', 'Full Name', 'required');
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
            $save_profile_data = array(
                'subscriber_name' => $this->input->post('subscriber_name'),
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
                'billing_contact'  => $this->input->post('billing_contact'),
                'identity_type'    => $this->input->post('identity_type'),
                'identity_number'  => $this->input->post('identity_number'),
                'foc_control_room' => $this->input->post('foc_control_room'),
                'foc_others'       => $this->input->post('foc_others'),
                'reference_type'   => $this->input->post('reference_type'),
                'reference_id'     => $this->input->post('reference_id'),
                'remarks'          => $this->input->post('remarks'),
                'updated_by'       => $this->user_id,
                'updated_at'       => date('Y-m-d H:i:s')
            );
            //test($save_profile_data);
            $profile_id = $this->input->post('profile_id');
            $profile_insert_id = $this->subscriber_profile->save($save_profile_data,$profile_id);
            $this->set_notification("Foc Subscriber Profile Updated","Foc Subscriber Profile [{$save_profile_data['subscriber_name']}] has been updated");
            echo json_encode(array('status'=>200,'token'=>$this->input->post('token'),'success_messages'=>'Subscriber user '.$save_profile_data['subscriber_name'].' profile updated successfully'));
        }
    }


    public function create_login_info()
    {

        if ($this->input->is_ajax_request()) {
            if($this->role_type == 'staff') {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
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
                $profile = $this->subscriber_profile->find_by_token($token);
                $user    = $this->user->find_by_token($token);
               
                if ($profile->get_attribute('id')) {
                    $save_login_data = $array = array(
                        'profile_id' => $profile->get_attribute('id'),
                        'role_id'    => 5,
                        'username'   => $this->input->post('username'),
                        'is_remote_access_enabled' => $this->input->post('is_remote_access_enabled'),
                        'user_type'  => 'Subscriber',
                        'user_status'=> 1,
                        'token'      => $profile->get_attribute('token')
                    );

                    if ($user->has_attributes()) {
                        $password = $this->input->post('password');
                        if(!empty($password))
                             $save_login_data['password']   = md5($password);
                        $this->user->save($save_login_data,$user->get_attribute('id'));
                        $this->set_notification("Foc Subscriber Login Info Updated","Login Information of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been updated");
                    } else {

                        $this->user->save($save_login_data);
                        $this->set_notification("Foc Subscriber Login Info Created","Login Information of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been created");
                    }

                    echo json_encode(array('status'=>200,'success_messages'=>'Login Info of User '.$profile->get_attribute('subscriber_name').' created successfully'));    
                    exit;

                } else {

                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
                    exit;
                }
                
            }

        } else {
            
            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('subscriber');
        }
    }

    public function update_login_info()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type == 'staff') {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

                $password    = $this->input->post('password');
                $re_password = $this->input->post('re_password');
                if($password != null) {
                    if($password != $re_password){
                        echo json_encode(array('status'=>400,'warning_messages'=>'Password not matched with Retype Password'));
                        exit;
                    }
                }


                $token = $this->input->post('token');
                $profile = $this->subscriber_profile->find_by_token($token);
                $user    = $this->user->find_by_token($token);

                $username = $this->input->post('username');
                $unique = $user->is_unique($username);

                if(!empty($unique)) {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Username not available'));
                    return;
                }

                if ($profile->get_attribute('id')) {
                    $save_login_data = $array = array(
                        'profile_id' => $profile->get_attribute('id'),
                        'role_id'    => 5,
                        'username'   => $username,
                        'is_remote_access_enabled' => $this->input->post('is_remote_access_enabled'),
                        'user_type'  => 'Subscriber',
                        'user_status'=> 1,
                        'token'      => $profile->get_attribute('token'),

                    );

                    if ($user->has_attributes()) {
                        
                        if(!empty($password))
                             $save_login_data['password']   = md5($password);
                        $save_login_data['updated_at'] = date('Y-m-d H:i:s');
                        $save_login_data['updated_by'] = $this->user_id;

                        $this->user->save($save_login_data,$user->get_attribute('id'));
                        $this->set_notification("Foc Subscriber Login Info Updated","Login Information of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been updated");
                        echo json_encode(array('status'=>200,'success_messages'=>'Login username '.$username.' updated successfully'));    
                    } else {

                        $this->user->save($save_login_data);
                        $this->set_notification("Foc Subscriber Login Info Created","Login Information of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been created");
                    }
                    
            
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
                }

               
            

        } else {
            
            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('subscriber');
        }
    }


    public function edit($token)
    {
        if($this->role_type == 'staff') {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
                redirect('foc-subscriber');
            }
        }

        $profile = $this->subscriber_profile->find_by_token($token);
        
        if ($profile->has_attributes()) {
            $this->theme->set_title('Subscriber Edit - Application')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/foc_subscriber/subscriber-edit.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token; 
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('foc_subscriber_profile/edit_subscriber_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('foc-subscriber');
        }
    }

    public function view($token)
    {

        $profile = $this->subscriber_profile->find_by_token($token);
        
        if ($profile->has_attributes()) {
            $this->theme->set_title('Subscriber View - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/foc_subscriber/subscriber-view.js');
            $data['user_info'] = $this->user_session;

            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token; 
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('foc_subscriber_profile/view_subscriber_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('subscriber');
        }
    }



    public function ajax_load_profiles()
    {
        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $all_subscribers = $this->subscriber_profile->get_all_subscribers($id,$take,$skip,$filter,$sort);

        echo json_encode(array('status'=>200,
            'profiles'=>$all_subscribers,
            //'lco_profile' => $this->lco_profile->get_region_code_by_id($id),
            'countries' => $this->country->get_all(),
            'packages'  => $this->package->get_packages(),
            'total'     => $this->subscriber_profile->get_count_subscribers($id)
        ));
    }



    public function ajax_get_profile($token)
    {
       
        $profile = $this->subscriber_profile->get_profile_by_token($token);
        /*$profile->subscription_copy   = base64_encode($profile->subscription_copy);
        $profile->photo               = base64_encode($profile->photo);
        $profile->identity_attachment = base64_encode($profile->identity_attachment);*/
        $profile->hex_code = null;//region_code_generator($profile->region_l1_code,$profile->region_l2_code,$profile->region_l3_code,$profile->region_l4_code);
        $user = $this->user->find_by_token($token);

        $billing_address = $this->billing_address->get_billing_address($token);
        echo json_encode(array(
            'status'=> 200,
            'profile'=> $profile,
            'billing_address' => $billing_address,
            'countries' => $this->country->get_all()
        ));
       
    }

    public function ajax_get_packages($token,$stb_card_id=null)
    {
        $user = $this->user->find_by_token($token);
        $packages = $this->package->get_packages();
        $selected_pacakges = $this->user_package->get_assigned_packages($user->get_attribute('id'),$stb_card_id);
        //echo $this->db->last_query();
        echo json_encode(array(
            'status' => 200,
            'packages'  => array_values($packages),
            'assigned_package_list' => $selected_pacakges //['packages']
        ));
    }

    public function ajax_get_unused_cards()
    {
        if ($this->input->is_ajax_request()) {
            $token = $this->input->post('token');
            $susbscriber = $this->user->find_by_token($token);
            $stbs  = $this->set_top_box->get_unsused_stbs();
            $cards = $this->ic_smartcard->get_unused_smartcards();
            $stb_cards = $this->subscriber_stb_smartcard->get_paring_cards($susbscriber->get_attribute('id'));
            $unassigned_stb_cards = $this->subscriber_stb_smartcard->get_unassigned_pairing_cards($susbscriber->get_attribute('id'));
            echo json_encode(array('status'=>200,
                'stbs' => $stbs,
                'cards' => $cards,
                'stb_cards' => $stb_cards,
                'unassigned_stb_cards' => $unassigned_stb_cards
                ));
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('subscriber');
        }
    }

    public function ajax_get_balance()
    {
        if ($this->input->is_ajax_request()) {
            $token = $this->input->post('token');
            $subscriber = $this->user->find_by_token($token);
            $result = $this->subscriber_transcation->get_subscriber_balance($subscriber->get_attribute('id'));
            if(!empty($result)){
                $balance = $result->balance;
            } else {
                $balance = $this->user_session->gift_amount;
            }
            echo json_encode(array('status'=>200,'balance'=>$balance));
        } else {

            $this->session->set_flashdata('warning_messages','Direct access not allowed');
        }
    }

    public function ajax_get_assigned_packages()
    {
        if($this->input->is_ajax_request()){
            $token = $this->input->post('token');
            $user = $this->user->find_by_token($token);
            $selected_pacakges = $this->user_package->get_assigned_packages($user->get_attribute('id'));
            
            echo json_encode(array('status'=>200,'assigned_packages'=>$selected_pacakges));
            exit;
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('/');
        }
    }

    public function has_package_assigned()
    {
        if($this->input->is_ajax_request()){
            $token = $this->input->post('token');
            $subscriber = $this->user->find_by_token($token);
            $stb_card_id = $this->input->post('stb_card_id');
            $package_id = $this->input->post('package_id');
            $package = $this->user_package->has_package_assigned($subscriber->get_attribute('id'),$stb_card_id,$package_id);
            echo json_encode(array('status'=>200,'package'=>$package));
        } else {

        }
    }

    public function save_billing_address()
    {
        if ($this->input->is_ajax_request()) {

            $this->form_validation->set_rules('subscriber_name','Name','required');
            $this->form_validation->set_rules('email','Email','required');
            $this->form_validation->set_rules('contact','Contact','required');

            if ($this->form_validation->run() == FALSE) {
                
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;

            } else {

                $token = $this->input->post('token');
                $user  = $this->user->find_by_token($token);
                $profile = $this->subscriber_profile->find_by_token($token);
                $billing_address = $this->billing_address->find_by_token($token);
                $save_billing_address['is_same_as_profile'] = $this->input->post('is_same_as_profile');
                $save_billing_address['name']            = $this->input->post('subscriber_name');
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
                $save_billing_address['is_same_as_contact'] = $this->input->post('is_same_as_contact');
                $save_billing_address['billing_contact'] = $this->input->post('billing_contact');
                $save_billing_address['token']           = $token;
                $save_billing_address['created_by']      = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

               
                
                if (!$user->has_attributes()) {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Before add business address please make sure that is user login info exist'));
                    exit;
                }

                $save_billing_address['user_id'] = $user->get_attribute('id');
                $billing_address_id = null;

                if ($billing_address->has_attributes()) {
                    $billing_address_id = $billing_address->get_attribute('id');
                    $this->billing_address->save($save_billing_address,$billing_address->get_attribute('id'));
                    $this->set_notification("Subscriber Billing Info Updated","Billing Information of Subscriber Profile [{$profile->get_attribute('subscriber_name')}] has been updated");
                } else {

                    $billing_address_id = $this->billing_address->save($save_billing_address);

                    $this->set_notification("Subscriber Billing Info Created","Billing Information of Subscriber Profile [{$profile->get_attribute('subscriber_name')}] has been created");
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
            if($this->role_type == 'staff') {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
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

           $profile = $this->subscriber_profile->find_by_token($token);
           if ($profile->has_attributes()) {
                $business_region['region_l1_code'] = $region_l1_code;
                $business_region['region_l2_code'] = $region_l2_code;
                $business_region['region_l3_code'] = $region_l3_code;
                $business_region['region_l4_code'] = $region_l4_code;
                $this->subscriber_profile->save($business_region,$profile->get_attribute('id'));
                $this->set_notification("Foc Subscriber Business Region Updated","Business Region of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been updated");
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

    public function save_assign_packages()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type == 'staff') {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token = $this->input->post('token');
            $user  = $this->user->find_by_token($token);
            $profile = $this->subscriber_profile->find_by_token($token);
            //$package_id = $this->input->post('package_id');
            $subscription_fee = $this->input->post('free_subscription_fee');
            $packages = $this->input->post('packages');
            $pairing_id = $this->input->post('pairing_id');
            $stb_card_id = $this->input->post('stb_card_id');
            $charge_type = $this->input->post('charge_type');
            $no_of_days  = $this->input->post('no_of_days');
            
            $balance = $this->input->post('balance');
            $amount_charge = $this->input->post('amount_charge');

            $payment_method = $this->payment_method->get_payment_method_by_name('Cash');
            
            if (empty($packages)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Include Package'));
                exit;
            }

            if ($user->has_attributes()) {

                $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
                $cardNum = $pairing->internal_card_number;
                $cardExtNum = $pairing->external_card_number;
                $start_datetimes = $end_datetimes = $flags = array();

                $api_data = array(
                    'cardNum' => $cardNum,
                    'operatorName' => $this->user_session->username,
                    'authCounts' => count($packages),
                );
                
                $package_ids = $package_names = array();
                $user_package_assign_type = $this->user_package_assign_type->get_type_by_name('Package Assign');
                
                foreach($packages as $package)
                {
                    $package_names[] = $package['package_name'];
                    $package_ids[] = $package['id'];
                    $start_datetimes[] = datetime_to_array($this->input->post('start_date'));
                    $end_datetimes[] = datetime_to_array($this->input->post('expire_date'));
                    $flags[] = 1; 
                }

                $api_data['productId'] = $package_ids;
                $api_data['startTime'] = $start_datetimes;
                $api_data['endTime']   = $end_datetimes;
                $api_data['flag']      = $flags;
                $api_string = json_encode($api_data);

                // call api here
                $response = $this->services->package_update($api_string);

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

                /*if($response->status != 200){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Server out of sync please refresh your browser'));
                    exit;
                }*/

                foreach($packages as $package){

                    $save_package_assign_data = array();
                    $save_package_assign_data['user_id'] = $user->get_attribute('id');
                    $save_package_assign_data['package_id'] = $package['id'];
                    $save_package_assign_data['status'] = 1;
                    $save_package_assign_data['user_stb_smart_id'] = $stb_card_id;
                    $save_package_assign_data['charge_type'] = $charge_type;
                    $save_package_assign_data['package_start_date'] = $this->input->post('start_date');
                    $save_package_assign_data['package_expire_date'] = $this->input->post('expire_date');

                    $save_package_assign_data['no_of_days'] = $no_of_days;
                    $save_package_assign_data['user_package_type_id'] = $user_package_assign_type->id;

                    $this->user_package->save($save_package_assign_data);

                }

                // save subscriber transaction during package assign
                $save_debit_data['pairing_id'] = $pairing_id;
                $save_debit_data['subscriber_id'] = $user->get_attribute('id');
                $save_debit_data['lco_id'] =  $this->user_session->id;
                $save_debit_data['package_id'] = implode(",",$package_ids);

                if($charge_type == 1){
                    $save_debit_data['debit']  = $balance;
                    $save_debit_data['balance'] = ($balance-$amount_charge);
                } else {
                    $balance = ($balance-$amount_charge);
                    $save_debit_data['debit']  = ($amount_charge);
                    $save_debit_data['balance'] = $balance;
                }

                $save_debit_data['transaction_types'] = 'D';
                $save_debit_data['payment_type'] = 'MRC';
                $save_debit_data['payment_method_id'] = (!empty($payment_method))? $payment_method->id : null;
                $save_debit_data['user_package_assign_type_id'] = $user_package_assign_type->id;
                
                $last_balance = $this->subscriber_transcation->get_subscriber_balance($user->get_attribute('id'));
                
                if(!empty($last_balance)){
                    if($last_balance->demo == 1){
                        $save_debit_data['demo'] = 1;
                    }else{
                        $save_debit_data['demo'] = 0;
                    }
                } else {
                    $save_debit_data['demo'] = 1;
                }

                $save_debit_data['transaction_date'] = date('Y-m-d H:i:s',time());



                // Send Conditional Mail using cas api
               
                $api_mail_data['title']  = 'Charge';
                $api_mail_data['package_name'] = implode(",",$package_names);
                $api_mail_data['amount'] = $save_debit_data['balance'];
                $api_mail_data['message_sign'] = $this->config->item('message_sign');
                $api_mail_data['expire_date']  = $this->input->post('expire_date');
                $api_mail_data['cardNum'] = $cardNum;
                $api_mail_data['template'] = 'msg_template/foc/package_assign';
                
                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                $api_string = json_encode($api_conditional_mail);
                $startDate = $api_conditional_mail['startTime'];
                $endDate = $api_conditional_mail['endTime'];
                $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];
                $response = $this->services->conditional_mail($api_string);
                if(!empty($response->id)){
                    $conditional_mail_data = array(
                        'lco_id' => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,
                        'subscriber_id' => $user->get_attribute('id'),
                        'smart_card_ext_id' => $cardExtNum,
                        'smart_card_id' => $cardNum,
                        'start_time'    => $startDate,
                        'end_time'      => $endDate,
                        'mail_title'    => $api_mail_data['title'],
                        'mail_content'  => $api_conditional_mail['content'],
                        'mail_sign'     => $api_conditional_mail['signStr'],
                        'mail_priority' => $api_conditional_mail['priority'],
                        'condition_return_code' => $response->id,
                        'type'          => 'SYSTEM',
                        'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }

                if(!$subscription_fee)
                {
                    $this->subscriber_transcation->save($save_debit_data);
                }

                $this->set_notification("Packages Assigned to FOC Subscriber","Packages ".implode(',',$package_names)." assigned to Foc Subscriber [{$profile->get_attribute('subscriber_name')}]");

                echo json_encode(array('status'=>200,'success_messages'=>'Packages assigned successfully to user ' . $user->get_attribute('username')));
                exit;

            } else {

                echo json_encode(array('status'=>400,'warning_messages'=>'User account not exist. Please Create User Login information'));
                exit;
            }

        } else {

            $this->session->set_flashdata();
            redirect('subscriber');
        }
    }


    /*public function unsubscribe_package()
    {
        if ($this->input->is_ajax_request()) {
            $token = $this->input->post('token');
            $subscriber = $this->user->find_by_token($token);
            $subscriber_id = $subscriber->get_attribute('id');
            $package_id = $this->input->post('id');
            $stb_card_id = $this->input->post('stb_card_id');
            $pairing_id  = $this->input->post('pairing_id');
            $start_date = substr($this->input->post('start_date'),0,10);
            $package = $this->user_package->has_package_assigned($subscriber_id,$stb_card_id,$package_id);
           
            if(empty($package)){
                echo json_encode(array('status'=>400,'warning_messages'=>'You don\'t have any package assigned to unsubscribe or You already unsubscribed'));
                exit;
            }
            
            $transaction = $this->subscriber_transcation->get_subscribe_charge_transaction($pairing_id,$subscriber_id,$package_id,$start_date);
            if(empty($transaction)){

                // empty means there is no claimable amount in transaction if unsubscribe
                // where demo is 1

                $this->user_package->save(array('status'=>0),$package->id);

            } else {

                // here will be functionality to give money back if any possibilites
                $package = $this->package->find_by_id($package->id);
                $debit_amount = $transaction->debit;
                $package_duration = $package->get_attribute('duration');
                $unit_price = round($debit_amount/$package_duration);
                $start_date = substr($transaction->transaction_date,0,10);
                $today      = date('Y-m-d');
                $startDateObj   = new DateTime($start_date);
                $todayDateObj   = new DateTime($today);
                $dateDiff       = date_diff($start_date,$todayDateObj);
                $remainingDays  = ($package_duration - ($dateDiff->days));
                $refund         = $remainingDays * $unit_price;

                $save_credit_data['pairing_id'] = $transaction->pairing_id;
                $save_credit_data['subscriber_id'] = $transaction->subscriber_id;
                $save_credit_data['lco_id']        = $transaction->lco_id;
                $save_credit_data['credit']        = $refund;
                $save_credit_data['balance']       = round($transaction->balance + $refund); 
                $save_credit_data['payment_menthod_id'] = $transaction->payment_menthod_id;
                $save_credit_data['transaction_date'] = date('Y-m-d');
                $save_credit_data['created_by'] = $this->user_session->id;

                $this->subscriber_transcation->save($save_credit_data);
            }
            
            echo json_encode(array('status'=>200,'success_messages'=>'Successfully Unsubscribed'));
            exit;
        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('/');
        }
    }*/

    public function upload_photo()
    {

        if ($this->input->is_ajax_request()) {
            if($this->role_type == 'staff') {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token    = $this->input->post('token');
            $profile  = $this->subscriber_profile->find_by_token($token);
            $user     = $this->user->find_by_token($token);
            $filesize = (2*(1024*1024));
            if ($profile->has_attributes()) {
                if(preg_match('/(jpg|jpeg|png|gif)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 2MB'));
                        exit;
                    }

                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];
                    $subscriber_path = SUBSCRIBER_PATH.$user->get_attribute('id');
                    $to_path = $subscriber_path.'/photo';
                    $old_photo = $profile->get_attribute('photo');
                    $photo_path = $to_path.'/photo_'.date('ymdHis').'.'.$type;

                    if(@file_exists($subscriber_path)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    if(move_uploaded_file($tmp_uploaded_file,$photo_path)){
                        $photo_data['photo'] = $photo_path;
                        $photo_data['updated_at'] = date('Y-m-d H:i:s');
                        $this->subscriber_profile->save($photo_data,$profile->get_attribute('id'));
                        @unlink($old_photo);
                    }


                    $this->set_notification("Foc Subscriber Photo Uploaded","Photo of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$photo_data['photo'],'success_messages'=>'Successfully photo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }
                
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }

        } else {
           $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('subscriber');
        }
        
        
    }


    public function upload_identity()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type=='staff') {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token'); 
            $profile = $this->subscriber_profile->find_by_token($token);
            $user    = $this->user->find_by_token($token);
            $filesize    = (2*(1024*1024));

            if ($profile->has_attributes()) {
                if(preg_match('/(jpg|jpeg|png|gif)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 2MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];
                    //$identity_data['identity_attachment'] = file_get_contents($tmp_uploaded_file);

                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $subscriber_path = SUBSCRIBER_PATH.$user->get_attribute('id');
                    $to_path = $subscriber_path.'/identity';
                    $old_identity = $profile->get_attribute('identity_attachment');
                    $identity_path = $to_path.'/identity'.date('ymdHis').'.'.$type;

                    if(@file_exists($subscriber_path)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    if(move_uploaded_file($tmp_uploaded_file,$identity_path)){
                        $identity_data['identity_attachment'] = $identity_path;
                        $identity_data['updated_at'] = date('Y-m-d H:i:s');
                        $this->subscriber_profile->save($identity_data,$profile->get_attribute('id'));
                        @unlink($old_identity);
                    }

                    //$this->subscriber_profile->save($identity_data,$profile->get_attribute('id'));
                    $this->set_notification("Foc Subscriber Identity Uploaded","Identity document of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$identity_data['identity_attachment'],'success_messages'=>'Successfully identity document attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('subscriber');
        }
    }

    public function upload_subscription_copy(){

        if ($this->input->is_ajax_request()) {
            if($this->role_type == 'staff') {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token');
            $profile = $this->subscriber_profile->find_by_token($token);
            $user    = $this->user->find_by_token($token);
            $filesize    = (2*(1024*1024));
            if ($profile->has_attributes()) {
                if(preg_match('/(jpg|jpeg|png|gif)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 2MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];
                    //$image_data['subscription_copy'] = file_get_contents($tmp_uploaded_file);

                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $subscriber_path = SUBSCRIBER_PATH.$user->get_attribute('id');
                    $to_path = $subscriber_path.'/subscription-copy';
                    $old_subscription = $profile->get_attribute('subscription_copy');
                    $subscription_path = $to_path.'/subscription-copy'.date('ymdHis').'.'.$type;

                    if(@file_exists($subscriber_path)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    if(move_uploaded_file($tmp_uploaded_file,$subscription_path)){
                        $image_data['subscription_copy'] = $subscription_path;
                        $image_data['updated_at']   = date('Y-m-d H:i:s');
                        $this->subscriber_profile->save($image_data,$profile->get_attribute('id'));
                        @unlink($old_subscription);
                    }

                    //$this->subscriber_profile->save($image_data,$profile->get_attribute('id'));
                    $this->set_notification("Foc Subscriber Subscription Copy Uploaded","Subscription Copy of Foc Subscriber [{$profile->get_attribute('subscriber_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$image_data['subscription_copy'],'success_messages'=>'Successfully subscription copy attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }
                
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }

        } else {
           $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('subscriber');
        }  
    }


    public function ajax_load_region()
    {
        if ($this->input->is_ajax_request()) {
            $regions = $this->region_level_one->get_regions();
            echo json_encode($regions);
        } else {
            redirect('region');
        }
    }

    

    public function assign_stb_smartcard()
    {
        if ($this->input->is_ajax_request()) {

            if($this->role_type == 'staff') {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'foc-subscriber', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $stb_box_id    = $this->input->post('stb_box_id');
            $smart_card_id = $this->input->post('smart_card_id');
            $stb_number    = $this->input->post('stb_number');
            $smart_card_number = $this->input->post('smart_card_number');
            $token         = $this->input->post('token');
            $smart_card_provider_id  = $this->input->post('smart_card_provider_id');
            $set_tob_box_provider_id = $this->input->post('stb_provider_id'); 

            $lco           = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $subscriber    = $this->user->find_by_token($token);
            $subscriber_profile = $this->subscriber_profile->find_by_id($subscriber->get_attribute('profile_id'));
            $subscriber_name = $subscriber_profile->get_attribute('subscriber_name');
            $stb           = $this->set_top_box->find_by_id($stb_box_id);
            $smart_card    = $this->ic_smartcard->find_by_id($smart_card_id);

            $cardNum = $smart_card->get_attribute('internal_card_number');
            $cardExtNum = $smart_card->get_attribute('external_card_number');
            $stbExtNum  = $stb->get_attribute('external_card_number');

            if (!$subscriber->has_attributes()) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Subscriber not found'));
                exit;
            }

            if (!$stb->has_attributes()) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Stb number '.$stb_number.' either used or not exist'));
                exit;
            }

            if(!$smart_card->has_attributes()) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Smart Card with number '.$smart_card_number.' not found'));
                exit;
            }


            
            $hasAssigned = $this->subscriber_stb_smartcard->has_stb_card_pair($subscriber,$lco,$stb,$smart_card);
            if (empty($hasAssigned)) {

                date_default_timezone_set('Asia/Dhaka');
                
                $save_data['subscriber_id'] = $subscriber->get_attribute('id');
                $save_data['lco_id']        = $lco;
                $save_data['stb_id']        = $stb->get_attribute('id');
                $save_data['card_id']       = $smart_card->get_attribute('id');
                $save_data['free_stb']      = $this->input->post('free_stb');
                $save_data['free_card']     = $this->input->post('free_card');
                $save_data['free_subscription_fee'] = $this->input->post('free_subscription_fee');
                $save_data['remarks']  = $this->input->post('remarks');

                $l1 = $subscriber_profile->get_attribute('region_l1_code');
                $l2 = $subscriber_profile->get_attribute('region_l2_code');
                $l3 = $subscriber_profile->get_attribute('region_l3_code');
                $l4 = $subscriber_profile->get_attribute('region_l4_code');

                $business_region = region_code_generator($l1,$l2,$l3,$l4);
                $addressContent = $subscriber_profile->get_attribute('address1'). ' '. $subscriber_profile->get_attribute('address2');
                $autoIdObjcect = $this->subscriber_stb_smartcard->get_auto_id();

                $api_data = array(
                    'customerId' => $autoIdObjcect->autoid,
                    'customerName' => $subscriber_name,
                    'mobilePhone' => $subscriber_profile->get_attribute('contact'),
                    'addressCode' =>$business_region,
                    'addressContent' =>$addressContent,
                    'mailAddress' =>$subscriber->get_attribute('email'),
                    'operatorName' =>'administrator'
                );

                $api_string = json_encode($api_data);

                $response    = null;
                $m_response  = null;
                $uc_response = null;
                $success_messages = null;

                // call api CustomerInformation
                $response = $this->services->update_customer($api_string);


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

                if($response->status == 200) {

                    if($response->type != null){
                        $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                        $success_messages[] = ($code)? $code->details : '';
                    }else{
                        $success_messages[] = 'STB and Smartcard Sucessfully paired and asisgned to subscriber ' . $subscriber->get_attribute('username');
                    }

                    $uc_api_data = array(
                        'cardnumber'=>$smart_card->get_attribute('internal_card_number'),
                        'groupId'=> ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,
                        'motherCardNum'=>0,
                        'cardStatus'=> 1,
                        'mainCardFlag'=>1,
                        'matchFlag'=>1,
                        'priority'=>0,
                        'lcoId'=> 1,
                        'stbNo'=> $stb->get_attribute('external_card_number'),
                        'customerId'=>$autoIdObjcect->autoid,
                        'operatorName'=>'administrator',
                        'addressRegion'=>$business_region
                    );

                    $uc_api_string = json_encode($uc_api_data);
                    $uc_response = $this->services->update_card_info($uc_api_string);

                    // call api Modify


                    if($uc_response->status == 500 || $uc_response->status == 400){
                        $administrator_info = $this->organization->get_administrators();
                        echo json_encode(array('status'=>400,'warning_messages'=>$uc_response->message.' Please Contact with administrator. '.$administrator_info));

                        exit;
                    }

                    if($uc_response->status != 200){
                        $code = $this->cas_sms_response_code->get_code_by_name($uc_response->type);
                        echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                        exit;
                    }

                    if($uc_response->status == 200) {

                        $m_api_data = array(
                            'cardnumber'=>$smart_card->get_attribute('internal_card_number'),
                            'groupId'=> ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,
                            'motherCardNum'=>0,
                            'cardStatus'=>1,
                            'mainCardFlag'=>1,
                            'addressRegion'=>$business_region,
                            'matchFlag'=>1,
                            'priority'=>1,
                            'lcoId'=>1,
                            'operatorName'=>'administrator'
                        );

                        $m_api_string = json_encode($m_api_data);
                        $m_response = $this->services->modify_card_info($m_api_string);

                        if($m_response->status == 500 || $m_response->status == 400){
                            $administrator_info = $this->organization->get_administrators();
                            echo json_encode(array('status'=>400,'warning_messages'=>$m_response->message.' Please Contact with administrator. '.$administrator_info));

                            exit;
                        }

                        if($m_response->status != 200){
                            $code = $this->cas_sms_response_code->get_code_by_name($m_response->type);
                            echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                            exit;
                        }

                        if($m_response->status == 200){

                            // Send Conditional Mail using cas api

                            $api_mail_data['title']  = 'Welcome';


                            $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
                            $api_mail_data['cable_tv_network'] = ($this->user_session->organization_name)? $this->user_session->organization_name:'';
                            $api_mail_data['cardNum'] = $cardNum;
                            $api_mail_data['template'] = 'msg_template/welcome';

                            $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                            $api_string = json_encode($api_conditional_mail);

                            $startDate = $api_conditional_mail['startTime'];
                            $endDate = $api_conditional_mail['endTime'];

                            $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                            $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];

                            // cas repair stb ic
                            $api_repair_data=array(
                                "cardNumber" => $cardNum,
                                "match" => 1,
                                "stbNo" => $stbExtNum,
                                "operatorName" => "administrator"
                            );
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

                            // cas repair stb ic

                            $conditional_mail_response = $this->services->conditional_mail($api_string);

                            if(!empty($conditional_mail_response->id)){
                                $conditional_mail_data = array(
                                    'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
                                    'subscriber_id' => $subscriber->get_attribute('id'),
                                    'smart_card_ext_id' => $cardExtNum,
                                    'smart_card_id' => $cardNum,
                                    'start_time'    => $startDate,
                                    'end_time'      => $endDate,
                                    'mail_title'    => $api_mail_data['title'],
                                    'mail_content'  => $api_conditional_mail['content'],
                                    'mail_sign'     => $api_conditional_mail['signStr'],
                                    'mail_priority' => $api_conditional_mail['priority'],
                                    'condition_return_code' => $conditional_mail_response->id,
                                    'type'          => 'SYSTEM',
                                    'creator'       => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

                                );
                                $this->conditional_mail->save($conditional_mail_data);
                            }

                        }

                    }


                }

                $saved = $this->subscriber_stb_smartcard->save($save_data);
                if ($saved) {
                    $update_stb = array();
                    $update_stb['subscriber_id'] = $subscriber->get_attribute('id');
                    $update_stb['is_used']       = 1;
                    $update_stb['used_date']     = date('Y-m-d H:i:s');
                    $this->set_top_box->save($update_stb,$stb->get_attribute('id'));

                    $updated_smartcard = array();
                    $updated_smartcard['subscriber_id'] = $subscriber->get_attribute('id');
                    $updated_smartcard['is_used']       = 1;
                    $updated_smartcard['used_date']     = date('Y-m-d H:i:s');
                    
                    $this->ic_smartcard->save($updated_smartcard,$smart_card->get_attribute('id'));
                    $this->set_notification("STB-SmartCard Paired to FOC Subscriber","STB-SmartCard Paired to Foc Subscriber [{$subscriber_name}]");

                    echo json_encode(array('status'=>200,
                        'success_messages'=>'STB and Smartcard Sucessfully paired and asisgned to subscriber '.$subscriber_profile->get_attribute('subscriber_name'),
                        'stbs' => $this->set_top_box->get_unsused_stbs(),
                        'cards'=> $this->ic_smartcard->get_unused_smartcards(),
                        'stb_cards' => $this->subscriber_stb_smartcard->get_paring_cards($subscriber->get_attribute('id')),
                        'unassigned_stb_cards'=> $this->subscriber_stb_smartcard->get_unassigned_pairing_cards($subscriber->get_attribute('id'))
                    ));

                    exit;

                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Stb and smart card number unable to paired'));
                    exit;
                }
            } else {
                $user = $this->user->find_by_id($hasAssigned->subscriber_id);
                $subscriber_profile = $this->subscriber_profile->find_by_id($user->profile_id);

                echo json_encode(array('status'=>400,'warning_messages'=>'Stb ('.$stb_number.') - Card ('.$smart_card_number.') already assigned to '.$subscriber_profile->get_attribute('subscriber_name')));
                exit;
            }

        } else {
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('subscriber');
        }
    }


    /**
    * View Specific Package details by token
    * @param $token
    * @return View 
    */
    public function package_details($token)
    {
        $this->theme->set_title('Package - View')->add_style('component.css')
                    ->add_script('cbpFWTabs.js');

        $data['user_info'] = $this->user_session;       
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);

        $package = $this->package->find_by_token($token);
        if (empty($package)) {
            $this->session->set_flashdata('warning_messages','Sorry! No package found');
            redirect('package-management');
        }
        $package_programs = $package->get_programs(null,false,'programs.id asc');
        
        $data['currency'] = $this->currency->get_active_curreny();
        $data['package']  = $package;
        
        $tempProgramList = array();
        $i = 0;
        foreach ($package_programs as $value) {
            $tempProgramList[$i]['program_id'] = $value->program_id;
            $tempProgramList[$i]['lcn'] = $value->lcn;
            $tempProgramList[$i]['program_name'] = $value->program_name;
            $tempProgramList[$i]['network_id'] = $value->network_id;
            $tempProgramList[$i]['program_type'] = $value->program_type;
            $i++;
        }

        $data['package_programs'] = json_encode($tempProgramList);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('package/view_package',$data,true);

    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $lco = $this->lco_profile->get_all_lco_users($this->user_session->id);

            echo json_encode(array(
                'status' => 200,
                'lco' => $lco
            ));
        } else {
            redirect('/');
        }

    }

    public function ajax_get_references()
    {
        if($this->input->is_ajax_request()){
            $this->load->model('reference_model','reference');
            $references = $this->reference->get_all();
            echo json_encode(array(
                'status'     => 200,
                'references' => $references
            ));
        } else {
            redirect('/');
        }
    }

    public function send_authorization()
    {
        if($this->input->is_ajax_request()){
            $token = $this->input->post('token');
            $pairing_id = $this->input->post('pairing_id');
            $user = $this->user->find_by_token($token);
            $subscriber_profile = $this->subscriber_profile->find_by_id($user->get_attribute('profile_id'));
            $subscriber_name = $subscriber_profile->get_attribute('subscriber_name');

            $l1 = $subscriber_profile->get_attribute('region_l1_code');
            $l2 = $subscriber_profile->get_attribute('region_l2_code');
            $l3 = $subscriber_profile->get_attribute('region_l3_code');
            $l4 = $subscriber_profile->get_attribute('region_l4_code');

            $business_region = region_code_generator($l1,$l2,$l3,$l4);
            $addressContent = $subscriber_profile->get_attribute('address1'). ' '. $subscriber_profile->get_attribute('address2');

            $subscriber_stb_card = $this->subscriber_stb_smartcard->get_pairing_by_id($pairing_id);

            $api_data = array(
                'customerId' => $subscriber_stb_card->id,
                'customerName' => $subscriber_name,
                'mobilePhone' => $subscriber_profile->get_attribute('contact'),
                'addressCode' => $business_region,
                'addressContent' => $addressContent,
                'mailAddress' => $user->get_attribute('email'),
                'operatorName' => 'administrator'
            );

            $api_string = json_encode($api_data);

            $response    = null;
            $uc_response = null;
            $success_messages = null;

            // call api CustomerInformation
            $response = $this->services->update_customer($api_string);


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

            if($response->status == 200) {

                $uc_api_data = array(
                    'cardnumber'=>$subscriber_stb_card->internal_card_number,
                    'groupId'=> ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,
                    'motherCardNum'=>0,
                    'cardStatus'=> 1,
                    'mainCardFlag'=>1,
                    'matchFlag'=>1,
                    'priority'=>0,
                    'lcoId'=> 1,
                    'stbNo'=> $subscriber_stb_card->stb_id,
                    'customerId'=>$subscriber_stb_card->id,
                    'operatorName'=>'administrator',
                    'addressRegion'=>$business_region
                );

                $uc_api_string = json_encode($uc_api_data);
                $uc_response = $this->services->update_card_info($uc_api_string);

                // call api update


                if($uc_response->status == 500 || $uc_response->status == 400){
                    $administrator_info = $this->organization->get_administrators();
                    echo json_encode(array('status'=>400,'warning_messages'=>$uc_response->message.' Please Contact with administrator. '.$administrator_info));

                    exit;
                }

                if($uc_response->status != 200){
                    $code = $this->cas_sms_response_code->get_code_by_name($uc_response->type);
                    echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                    exit;
                }

                echo json_encode(array('status'=>200,'success_messages'=>'Subscriber Successfully Authorized'));
                exit;

            }
        }else{
            redirect('/');
        }
    }

    public function send_pair()
    {
        if($this->input->is_ajax_request()){
            // cas repair stb ic
            $token = $this->input->post('token');
            $pairing_id = $this->input->post('pairing_id');
            $user  = $this->user->find_by_token($token);

            if(!$user->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No user found'));
                exit;
            }

            $subscriber_stb_card = $this->subscriber_stb_smartcard->get_pairing_by_id($pairing_id);
            $cardNum = $subscriber_stb_card->internal_card_number;
            $stbExtNum = $subscriber_stb_card->stb_id;
            $api_repair_data=array(
                "cardNumber" => $cardNum,
                "match" => 1,
                "stbNo" => $stbExtNum,
                "operatorName" => "administrator"
            );
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

            echo json_encode(array('status'=>200,'success_messages'=>'Subscriber paired successfully'));
        }else{
            redirect('/');
        }
    }

    public function send_unpair()
    {
        if($this->input->is_ajax_request()){
            // cas repair stb ic
            $token = $this->input->post('token');
            $pairing_id = $this->input->post('pairing_id');
            $user  = $this->user->find_by_token($token);

            if(!$user->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No user found'));
                exit;
            }

            $subscriber_stb_card = $this->subscriber_stb_smartcard->get_pairing_by_id($pairing_id);
            $cardNum = $subscriber_stb_card->internal_card_number;
            $stbExtNum = $subscriber_stb_card->stb_id;
            $api_repair_data=array(
                "cardNumber" => $cardNum,
                "match" => 0,
                "stbNo" => $stbExtNum,
                "operatorName" => "administrator"
            );
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

            echo json_encode(array('status'=>200,'success_messages'=>'Subscriber un-paired successfully'));
        }else{
            redirect('/');
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