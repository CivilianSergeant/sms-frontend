<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/19/2016
 * Time: 3:19 PM
 */
class Add_on_package extends BaseController
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
        $this->role_id = $this->user_session->role_id;

        if($this->user_type == self::LCO_LOWER){
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
        }

    }

    public function index()
    {
        $this->theme->set_title('Dashboard - Application')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/add-on/package.js');



        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('add-on-package/package',$data,true);
    }

    public function ajax_get_permissions()
    {
        if($this->role_type == 'admin'){
            if($this->user_type == self::LCO_LOWER){
                $permissions = array('create_permission'=>0,'edit_permission'=>0,'view_permission'=>1,'delete_permission'=>0);
            }elseif($this->user_type == self::MSO_LOWER){
                $permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
            }

        }else{
            $role = $this->user_session->role_id;
            $permissions = $this->menus->has_permission($role,1,'add-on-package',$this->user_type);
        }

        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function ajax_load_programs()
    {
        echo json_encode(array('status'=>200,'programs'=>$this->program->get_all()));
    }

    /**
     * View Specific Package details by token
     * @param $token
     * @return View
     */
    public function view($token)
    {
        $this->theme->set_title('Package - View')->add_style('component.css')
            ->add_script('cbpFWTabs.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);

        $package = $this->package->find_by_token($token);
        if (empty($package)) {
            $this->session->set_flashdata('warning_messages','Sorry! No package found');
            redirect('add-on-package');
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
        $this->theme->set_view('add-on-package/view_package',$data,true);

    }

    /**
     * Edit Package by token
     * @param $token
     * @return View
     */
    public function edit($token)
    {
        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }

        if($this->role_type == 'staff') {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'add-on-package', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
                redirect('add-on-package');
            }
        }

        $this->theme->set_title('Package - Edit')->add_style('component.css')
            ->add_script('controllers/add-on/package.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['token'] = $token;

        $package = $this->package->find_by_token($token);
        if (empty($package)) {
            $this->session->set_flashdata('warning_messages','Sorry! No Package Found');
            redirect('add-on-package');
        }

        $data['package']  = $package;
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('add-on-package/edit_package',$data,true);

    }

    public function ajax_load_package_programs($token)
    {


        $package = $this->package->find_by_token($token);
        if (empty($package)) {
            echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No Package Found'));
            exit;
        }

        $package_programs = $package->get_programs();
        $programs = $this->program->get_all();

        foreach ($programs as $i=>$program) {
            if (in_array($program->id,array_keys($package_programs))) {
                unset($programs[$i]);
            }
        }

        echo json_encode(array(
            'status' => 200,
            'pkg' => $package->get_attributes(),
            'programs' => array_values($programs),
            'assigned_programs' => array_values($package_programs)
        ));
    }

    /**
     * Accept form data and save package
     */
    public function save_package()
    {
        if($this->role_type == 'staff') {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'add-on-package', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }

        if ($this->input->post() != null) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $save_data['id']               = $this->package->get_last_next_id();
            $save_data['package_name']     = $this->input->post('package_name');
            $save_data['duration']         = $this->input->post('package_duration');
            $save_data['price']            = $this->input->post('package_price');
            $save_data['token']            = md5($this->input->post('package_name'));
            $save_data['is_active']        = $this->input->post('is_active');
            $save_data['is_add_on']        = 1;
            $save_data['created_by']       = $id;
            $selected_programs             = $this->input->post('programs');


            $this->form_validation->set_rules('package_name','Package Name','required|max_length[20]|is_unique[packages.package_name]');
            $this->form_validation->set_rules('package_duration','Package Duration','required');
            $this->form_validation->set_rules('package_price','Package Price','required');
            /*$this->form_validation->set_rules('programs','Programs','required');*/


            if($this->form_validation->run() == False) {

                if ($this->input->is_ajax_request()) {

                    echo json_encode(array('status'=>400,'warning_messages'=>validation_errors()));

                } else {
                    $this->session->set_flashdata('warning_messages',validation_errors());
                    redirect('add-on-package');
                }
            }

            if(count($selected_programs)<=0){
                if ($this->input->is_ajax_request()) {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Please Assign Programs'));
                    exit;
                } else {
                    $this->session->set_flashdata('warning_messages','Please Assign Programs');
                    redirect('add-on-package');
                }
            }

            $api_programs = array();

            foreach($selected_programs as $prog)
            {
                $api_programs[] = (int)$prog; //(int)$prog['id'];
            }

            $api_data = array(
                'packageId'   => (int)$save_data['id'],
                'packageName' => $save_data['package_name'],
                'limitFlag'   => 0,
                'matchFlag'   => 0,
                'operatorName'=> 'administrator',
                'counts' => count($api_programs),
                'programs' => $api_programs
            );

            $api_string = json_encode($api_data);

            $response = $this->services->update_package($api_string);

            if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();

                echo json_encode(array('status'=>400,'warning_messages'=>$response->message.'. Please Contact with administrator. '.$administrator_info));
                exit;
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                exit;
            }

            $package_id = $this->package->save($save_data);
            $this->notification->save_notification(null,"New Add-on Package Created","New Package [{$api_data['packageName']}] has been created",$this->user_session->id);
            $success_message = 'Package successfully created';

            if ($package_id !== false) {

                $this->package->assign_program($selected_programs,$package_id,$save_data['created_by']);

            }

            if ($this->input->is_ajax_request()) {

                echo json_encode(array('status'=>200,'success_messages'=>$success_message));
                exit;
            } else {

                $this->session->set_flashdata('success_messages',$success_message);
                redirect('add-on-package');
            }



        } else {

            $this->session->set_flashdata('warning_messages','Access Denied');
            redirect('add-on-package');

        }
    }

    /**
     * Update Package by token
     * @param $token
     */
    public function update_package($token)
    {
        if($this->role_type == 'staff') {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'add-on-package', $this->user_type);

            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                exit;
            }
        }

        date_default_timezone_set('Asia/Dhaka');
        $save_data['package_name']     = $this->input->post('package_name');
        $save_data['duration']         = $this->input->post('package_duration');
        $save_data['price']            = $this->input->post('package_price');
        $save_data['last_editor']      = $this->user_session->username;
        $save_data['last_edit_time']   = date('Y-m-d H:i:s',time());
        $save_data['is_active']        = $this->input->post('is_active');
        $selected_programs             = $this->input->post('programs');



        $package = $this->package->find_by_token($token);
        $package_exist = $this->package->find_by_name($save_data['package_name']);
        if(!empty($package_exist)){
            if($package->get_attribute('id') != $package_exist->id){

                if($this->input->is_ajax_request()){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Package name is not unique'));
                    exit;
                }else{
                    $this->session->set_flashdata('warning_messages','Add-on Package name is not unique');
                    redirect('add-on-package');
                }
            }
        }


        if(!$save_data['is_active'])
        {
            $result = $this->user_addon_package->assigned_package($package->get_attribute('id'));
            if(!empty($result)){
                if($this->input->is_ajax_request()){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Add-on Package Already Assigned you cannot change status. <a style="color:red;" href="'.site_url('package/assign-details/'.$package->get_attribute('token')).'">Show Details</a>'));
                    exit;
                }else{

                    $this->session->set_flashdata('warning_messages','Add-on Package Already Assigned you cannot change status. <a style="color:red;" href="'.site_url('package/assign-details/'.$package->get_attribute('token')).'">Show Details</a>');
                    redirect('add-on-package');
                }
            }
        }

        if(count($selected_programs)<=0){
            if ($this->input->is_ajax_request()) {

                echo json_encode(array('status'=>400,'warning_messages'=>'Please Assign Programs'));
                exit;
            } else {
                $this->session->set_flashdata('warning_messages','Please Assign Programs');
                redirect('add-on-package');
            }
        }


        $created_by  = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

        if (!empty($package)) {

            $this->form_validation->set_rules('package_name','Package Name','required|max_length[20]');
            $this->form_validation->set_rules('package_duration','Package Duration','required');
            $this->form_validation->set_rules('package_price','Package Price','required');

            if($this->form_validation->run() == False) {

                if ($this->input->is_ajax_request()) {

                    echo json_encode(array('status'=>500,'message'=>validation_errors()));
                    exit;
                } else {
                    $this->session->set_flashdata('error_messages',validation_errors());
                    redirect('add-on-package/view/'.$token);
                }
            }

            $api_programs = array();
            foreach($selected_programs as $prog)
            {
                $api_programs[] = (int)$prog; //(int)$prog['id'];
            }

            $api_data = array(
                'packageId'   => (int)$package->get_attribute('id'),
                'packageName' => $save_data['package_name'],
                'limitFlag'   => 0,
                'matchFlag'   => 0,
                'operatorName'=> 'administrator',
                'counts' => count($api_programs),
                'programs' => $api_programs
            );

            $api_string = json_encode($api_data);

            $response = $this->services->update_package($api_string);

            if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                echo json_encode(array('status'=>400,'warning_messages'=>$response->message.'. Please Contact with administrator. '.$administrator_info));
                exit;
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                exit;
            }

            $this->package->save($save_data,$package->get_attribute('id'));
            $this->package->remove_programs();
            $this->package->assign_program($selected_programs,$package->get_attribute('id'),$created_by);
            $success_message = 'Package successfully updated';
            $this->notification->save_notification(null,"Package Updated","Add-on Package information of [{$api_data['packageName']}] has been changed",$this->user_session->id);
            if ($this->input->is_ajax_request()) {

                echo json_encode(array('status'=>200,'success_messages'=>$success_message));
                exit;
            } else {

                $this->session->set_flashdata('success_messages',$success_message);
                redirect('add-on-package');
            }

        } else {
            if($this->input->is_ajax_request())
            {
                echo json_encode(array('status'=>400,'warning_messages'=>'No Package Found'));
                exit;
            }
            $this->session->set_flashdata('warning_messages','No Package Found');
            redirect('add-on-package');
        }

    }

    public function delete($id)
    {
        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }

        if($this->role_type == 'staff') {
            $permission = $this->menus->has_delete_permission($this->role_id, 1, 'add-on-package', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have delete permission");
                redirect('add-on-package');
            }
        }

        $package = $this->package->find_by_id($id);
        $assigned_package = $this->user_addon_package->assigned_package($package->get_attribute('id'));

        if ($assigned_package != null)
        {

            $this->session->set_flashdata('warning_messages','This Package Already Assigned. <a style="color:red;" href="'.site_url('package/assign-details/'.$package->get_attribute('token')).'">Show Details</a>');
            redirect('add-on-package');
        }
        else
        {


            /*$api_data = array(
                'packageId' => (int)$package->get_attribute('id'),
                'operatorName' => 'administrator'
            );

            $api_string = json_encode($api_data);

            $response = $this->services->delete_package($api_string);

            if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                $this->session->set_flashdata('warning_messages',$response->message.'. Please Contact with administrator. '.$administrator_info);
                redirect('package');
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                $this->session->set_flashdata('warning_messages',$code->details);
                redirect('package');
            }

            if($response->status == 200){
                if(!empty($response->type)){
                    $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                    $this->session->set_flashdata('warning_messages',$code->details);
                }else{
                    $this->session->set_flashdata('success_messages', 'Package has been deleted');
                }
            }*/

            $this->package_program->delete_programs_by_package($package->get_attribute('id'));

            $this->package->package_delete($package->get_attribute('id'));
            $this->notification->save_notification(null,"Add-on Package Deleted","Package [{$package->get_attribute('package_name')}] has been deleted");
            $this->session->set_flashdata('success_messages', 'Add-on Package has been deleted');
            redirect('add-on-package');
        }
    }


    public function ajax_load_package()
    {
        $take = $this->input->post('take');
        $skip = $this->input->post('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $all_package = $this->package->get_all_add_on_packages($take,$skip,$filter,$sort);
        echo json_encode(array('status'=>200,
            'packages'=>$all_package,
            'total'=>count($all_package)
        ));
    }

    public function assign_details($token)
    {
        $this->theme->set_title('Dashboard - Application')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/add-on/package.js');

        $package = $this->package->find_by_token($token);
        if($package->has_attributes()){


            $data['user_info'] = $this->user_session;
            $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
            $data['token'] = $token;

            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('add-on-package/assign_details',$data,true);

        }else{
            $this->session->set_flashdata('warning_messages','Sorry package not found');
            redirect('add-on-package');
        }

    }

    public function ajax_get_assigned_package_list($token)
    {
        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort = $this->input->get('sort');
        $package = $this->package->find_by_token($token);
        $list = $this->user_addon_package->get_assign_packages_with_details($package->get_attribute('id'),$take,$skip,$filter,$sort);
        $total = $this->user_addon_package->get_assign_packages_with_details($package->get_attribute('id'),0,0,null,null,true);
        echo json_encode(array(
            'status' => 200,
            'assigned_package_list'=> $list,
            'total'=>$total
        ));
    }

    public function assign()
    {
        $this->theme->set_title('Add-on Assign Package')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/add-on/assign.js');
        if($this->role_type == "admin"){
            $data['permissions'] = (object)array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
        }else{
            $data['permissions'] = $this->menus->has_permission($this->role_id,1,'add-on-package-assign',$this->user_type);
        }

        $data['user_info']  = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('add-on-package/package_assign',$data,true);

    }

    public function ajax_load_subscribers()
    {

        if($this->input->is_ajax_request()){
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            echo json_encode(array(
                'status'=>200,
                'subscribers'=>$this->subscriber_profile->get_all_subscribers($id)
            ));
        }else{
            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('billing/cash');
        }
    }

    public function ajax_pairing_id()
    {
        $subscriber_id = $this->input->post('subscriber_id');
        $pairs = $this->payment_method->get_subscriber_pairing_id($subscriber_id);

        $newPairs = array();

        foreach($pairs as $val){
            $newPairs[] = $val;
        }
        echo json_encode(array('status'=>200,'stb_card_pairs'=>$pairs));
    }

    public function save_assign_packages()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type == "staff"){
                $permission = $this->menus->has_create_permission($this->role_id,1,'add-on-package-assign',$this->user_type);
                if(!$permission){
                    echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! You don't have create permission to Add-on assign package"));
                    exit;
                }
            }

            $id = $this->input->post('subscriber');
            $user  = $this->user->find_by_id($id);
            $subscriber  = $this->subscriber_profile->find_by_token($user->get_attribute('token'));

            $package_name = $this->input->post('package_name');
            $package_id = $this->input->post('id');
            $pairing_id = $this->input->post('pairing_id');
            $stb_card_id = $this->input->post('stb_card_id');
            $no_of_days  = $this->input->post('duration');


            $amount_charge = $this->input->post('price');
            //test($this->input->post());die();

            $payment_method = $this->payment_method->get_payment_method_by_name('Cash');

            if (empty($package_name)) {
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Add Package'));
                exit;
            }

            if ($user->has_attributes()) {

                $last_balance = $this->subscriber_transcation->get_subscriber_balance($user->get_attribute('id'));
                $balance = (!empty($last_balance->balance))? $last_balance->balance : 0;

                if($balance < $amount_charge){
                    echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! Subscriber don't have sufficient balance to purchase add-on package"));
                    exit;
                }

                $pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
                $cardNum = $pairing->internal_card_number;
                $cardExtNum = $pairing->external_card_number;
                $start_datetimes = $end_datetimes = $flags = array();

                $api_data = array(
                    'cardNum' => $cardNum,
                    'operatorName' => $this->user_session->username,
                    'authCounts' => 1,
                );

                $package_ids = $package_names = array();
                $user_package_assign_type = $this->user_package_assign_type->get_type_by_name('Package Assign');
                $package_names[] = $package_name;
                $package_ids[] = $package_id;
                /*foreach($packages as $package)
                {*/


                    $start_datetimes[] = datetime_to_array($this->input->post('start_date'));
                    $end_datetimes[] = datetime_to_array($this->input->post('expire_date'));
                    $flags[] = 1;
                /*}*/

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

               /* foreach($packages as $package){*/

                    $save_package_assign_data = array();
                    $save_package_assign_data['user_id'] = $user->get_attribute('id');
                    $save_package_assign_data['package_id'] = $package_id;
                    $save_package_assign_data['status'] = 1;
                    $save_package_assign_data['user_stb_smart_id'] = $stb_card_id;

                    $save_package_assign_data['package_start_date'] = $this->input->post('start_date');
                    $save_package_assign_data['package_expire_date'] = $this->input->post('expire_date');
                    //$save_package_assign_data['created_by'] = ($this->role_name == self::STAFF)? $this->parent_id : $this->user_id;
                    $save_package_assign_data['no_of_days'] = $no_of_days;
                    $save_package_assign_data['user_package_type_id'] = $user_package_assign_type->id;
                    // test($save_package_assign_data);die();
                    $user_package_id = $this->user_addon_package->save($save_package_assign_data);

               /* }*/

                // save subscriber transaction during package assign
                $save_debit_data['pairing_id'] = $pairing_id;
                $save_debit_data['subscriber_id'] = $user->get_attribute('id');
                $save_debit_data['lco_id'] =  (($this->role_name == self::STAFF)? $this->parent_id : $this->user_id);
                $save_debit_data['package_id'] = $package_id;


                $remaining_balance = ($balance-$amount_charge);
                $save_debit_data['debit']  = ($amount_charge);
                $save_debit_data['balance'] = $remaining_balance;


                $save_debit_data['transaction_types'] = 'D';
                $save_debit_data['payment_type'] = 'MRC';
                $save_debit_data['payment_method_id'] = (!empty($payment_method))? $payment_method->id : null;
                $save_debit_data['user_package_assign_type_id'] = $user_package_assign_type->id;



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
                //$save_debit_data['created_by'] = ($this->role_name == self::STAFF)? $this->parent_id : $this->user_id;


                // Send Conditional Mail using cas api

                $api_mail_data['title']  = 'Charge';
                $api_mail_data['package_name'] = $package_name;
                $api_mail_data['amount'] = $save_debit_data['balance'];
                $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
                $api_mail_data['expire_date']  = $this->input->post('expire_date');
                $api_mail_data['cardNum'] = $cardNum;
                $api_mail_data['template'] = 'msg_template/package_assign';

                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                $api_string = json_encode($api_conditional_mail);
                $startDate = $api_conditional_mail['startTime'];
                $endDate = $api_conditional_mail['endTime'];
                $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];
                $response = $this->services->conditional_mail($api_string);
                if(!empty($response->id)){
                    $conditional_mail_data = array(
                        'lco_id' => (($this->role_name == self::STAFF)? $this->parent_id : $this->user_id),
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
                        'creator'       => ($this->role_name == self::STAFF)? $this->parent_id : $this->user_id,

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }

                $this->subscriber_transcation->save($save_debit_data);
                //$package_name = implode(",",$package_names);
                $this->set_notification("Add-on Package Assigned to Subscriber","Packages [{$package_name}] assigned to Subscriber [{$subscriber->get_attribute('subscriber_name')}]");
                echo json_encode(array('status'=>200,'user_package_id'=>$user_package_id,'success_messages'=>'Packages assigned successfully to user ' . $subscriber->get_attribute('subscriber_name')));
                exit;

            } else {

                echo json_encode(array('status'=>400,'warning_messages'=>'User account not exist. Please Create User Login information'));
                exit;
            }

        } else {

            $this->session->set_flashdata('warning_messages','Direct access not allowed');
            redirect('subscriber');
        }
    }

    public function subscriber(){
        $this->theme->set_title('Addon Subscribers')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/add-on/assign.js');

        $data['user_info']    = $this->user_session;

        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('add-on-package/subscriber_profile', $data, true);
    }

    public function ajax_load_profiles()
    {
        $take   = $this->input->get('take');
        $skip   = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');

        $id = 0;
        if($this->role_type==self::ADMIN)
        {
            $id=$this->user_id;

        }elseif($this->role_type==self::STAFF){
            $id=$this->parent_id;
        }

        $all_subscribers = $this->subscriber_profile->get_all_addon_subscribers($id,$take,$skip,$filter,$sort);
        $id = ($this->role_name == self::STAFF)? $this->parent_id : $this->user_id;
        echo json_encode(array('status'=>200,
            'profiles'=>$all_subscribers,
            'total'     => $this->subscriber_profile->get_count_subscribers($id)
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