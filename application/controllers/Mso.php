<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mso extends BaseController
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
        $this->theme->set_title('MSO User Creation - Application')
                ->add_style('component.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/mso.js');

        $data['countries'] = $this->country->get_all();
        $data['all_division'] = $this->division->get_divisions();
        $data['all_district'] = $this->district->get_districts();
        $data['all_area'] = $this->area->get_areas();
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('mso_profile/mso_profile', $data, true);
    }

    public function ajax_get_permissions()
    {
        if($this->role_type=="admin"){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $role = $this->user_session->role_id;
            $permissions = $this->menus->has_permission($role,1,'mso',$this->user_type);
        }

        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function create_profile()
    {
        if($this->role_type == "staff") {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'mso', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
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
        		redirect('mso');
        	}
        } else {

            /* User Profile Information */
            $save_profile_data = array(
                'mso_name'        => $this->input->post('full_name'),
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
                'token'           => md5(time().$this->input->post('email'))
            );

            $profile_insert_id = $this->mso_profile->save($save_profile_data);

            if ($profile_insert_id) {

              
                $save_login_data = $array = array(
                    'profile_id' => $profile_insert_id,
                    'role_id'    => 2,
                    'username'   => $this->input->post('email'),
                    'email'      => $this->input->post('email'),
                    'password'   => md5($this->input->post('email')),
                    'user_type'  => 'MSO',
                    'user_status'=> 1,
                    'token'      => $save_profile_data['token']
                );

                $user_id = $this->user->save($save_login_data);

                if(@file_exists(MSO_PATH)){
                    @mkdir(MSO_PATH.$user_id,0777);
                }else{
                    @mkdir(MSO_PATH.$user_id,0777,true);
                }

                $this->set_notification("New Mso Profile Created","New Mso Profile [{$save_profile_data['mso_name']}] has been created");
            }
            echo json_encode(array('status'=>200,'token'=>$save_profile_data['token'],'success_messages'=>'Mso user '.$save_profile_data['mso_name'].' profile created successfully'));
        }
    }

    public function update_profile()
    {
        if($this->role_type == "staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'mso', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                exit;
            }
        }

        $this->form_validation->set_rules('mso_name', 'Full Name', 'required');
        $this->form_validation->set_rules('email', 'E-mail','required');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
            } else {
                $this->session->set_flashdata('warning_messages',validation_errors());
                redirect('mso');
            }
        } else {

            
            /* User Profile Information */
            $save_profile_data = array(
                'mso_name'        => $this->input->post('mso_name'),
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
            );

            $profile_id = $this->input->post('profile_id');
            $this->mso_profile->save($save_profile_data,$profile_id);
            $this->set_notification("Mso Profile Updated","Mso Profile [{$save_profile_data['mso_name']}] has been updated");
            echo json_encode(array('status'=>200,'token'=>$this->input->post('token'),'success_messages'=>'Mso user '.$save_profile_data['mso_name'].' profile created successfully'));
        }
    }


    public function create_login_info()
    {
        if ($this->input->is_ajax_request()) {

            if($this->role_type == "staff") {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'mso', $this->user_type);
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
                
            } else {

                $token = $this->input->post('token');
                $profile = $this->mso_profile->find_by_token($token);
                $user    = $this->user->find_by_token($token);
                $role_id = $this->input->post('role_id');
                if ($profile->get_attribute('id')) {
                    $save_login_data = $array = array(
                        'profile_id' => $profile->get_attribute('id'),
                        'role_id'    => (!empty($role_id))? $role_id : 2,
                        'username'   => $this->input->post('username'),
                        'user_type'  => 'MSO',
                        'user_status'=> 1,
                        'token'      => $profile->get_attribute('token')
                    );

                    if ($user->has_attributes()) {
                        $password = $this->input->post('password');
                        if(!empty($password))
                             $save_login_data['password']   = md5($password);
                        $this->user->save($save_login_data,$user->get_attribute('id'));
                        $this->set_notification("Mso Login Info Updated","Login Info of Mso [{$profile->get_attribute('mso_name')}] has been updated");
                    } else {

                        $this->user->save($save_login_data);
                        $this->set_notification("Mso Login Info Created","Login Info of Mso [{$profile->get_attribute('mso_name')}] has been created");
                    }
                   
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
                }

                echo json_encode(array('status'=>200,'success_messages'=>'Login Info of User '.$profile->get_attribute('mso_name').' created successfully'));    
            }

        } else {
            
            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('mso');
        }
    }

    public function update_login_info()
    {
        if ($this->input->is_ajax_request()) {

            if($this->role_type == "staff") {
                // Authorization Checking
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'mso', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'mso', $this->user_type);
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
                    }
                }


                $token = $this->input->post('token');
                $profile = $this->mso_profile->find_by_token($token);
                $user    = $this->user->find_by_token($token);

                $username = $this->input->post('username');
                $unique = $user->is_unique($username);
                if(!empty($unique)) {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Username not available'));
                }
                $role_id = $this->input->post('role_id',true);
                if ($profile->get_attribute('id')) {
                    $save_login_data = $array = array(
                        'profile_id' => $profile->get_attribute('id'),
                        'role_id'    => (!empty($role_id))? $role_id : 2,
                        'username'   => $username,
                        'user_type'  => 'MSO',
                        'user_status'=> 1,
                        'token'      => $profile->get_attribute('token')
                    );

                    if ($user->has_attributes()) {
                        
                        if(!empty($password))
                             $save_login_data['password']   = md5($password);
                        $this->user->save($save_login_data,$user->get_attribute('id'));
                        $this->set_notification("Mso Login Info Updated","Login Info of Mso [{$profile->get_attribute('mso_name')}] has been updated");
                        echo json_encode(array('status'=>200,'success_messages'=>'Login username '.$username.' updated successfully'));    
                    } else {

                        $this->user->save($save_login_data);
                        $this->set_notification("Mso Login Info Created","Login Info of Mso [{$profile->get_attribute('mso_name')}] has been created");
                    }
                    
            
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Profile not found to add user info'));
                }

               
            

        } else {
            
            $this->session->set_flashdata('warning_messages','Direct access not allowed to this url');
            redirect('mso');
        }
    }


    public function edit($token){
        if($this->role_type == "staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'mso', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
                redirect('mso');
            }
        }

        $profile = $this->mso_profile->find_by_token($token);
        
        if ($profile->has_attributes()) {
            $this->theme->set_title('MSO User Creation - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/mso.js');
            $data['user_info'] = $this->user_session;
            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token; 
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('mso_profile/edit_mso_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('mso');
        }
    }

    public function view($token){

        $profile = $this->mso_profile->find_by_token($token);
        
        if ($profile->has_attributes()) {
            $this->theme->set_title('MSO User Creation - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/mso.js');
            $data['user_info'] = $this->user_session;
            $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
            $data['token'] = $token; 
            $data['theme'] = $this->theme->get_image_path();
            $this->theme->set_view('mso_profile/view_mso_profile', $data, true);
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! No data found');
            redirect('mso');
        }
    }



    public function ajax_load_profiles()
    {
        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
    	$all_mso = $this->mso_profile->get_all_mso_users($id,$take,$skip,$filter,$sort);
        $roles = $this->role->get_role_for_permission($this->role_type,$this->user_type);
    	echo json_encode(array(
            'status'=>200,
            'profiles'=>$all_mso,
            'roles' => $roles,
            'total' => $this->mso_profile->get_count_mso($id)
            ));
    }

    public function ajax_get_profile($token)
    {
       
        $profile = $this->mso_profile->get_profile_by_token($token);
        /*$profile->photo               = base64_encode($profile->photo);
        $profile->identity_attachment = base64_encode($profile->identity_attachment);*/
        $roles = $this->role->get_role_for_permission($this->role_type,$this->user_type);
        echo json_encode(array(
            'status'=>200,
            'profile'=>$profile,
            'roles'  => $roles,
            'countries' => $this->country->get_all(),
        ));
    }

    public function upload_photo(){

        if ($this->input->is_ajax_request()) {

            if($this->role_type == "staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'mso', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'mso', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token');
            $profile = $this->mso_profile->find_by_token($token);
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
                    $MSO_PATH = MSO_PATH.$user->get_attribute('id');
                    $to_path = $MSO_PATH.'/photo';
                    $old_photo  = $profile->get_attribute('photo');
                    $photo_path = $to_path.'/photo_'.date('ymdHis').'.'.$type;

                    if(@file_exists($MSO_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    if(move_uploaded_file($tmp_uploaded_file,$photo_path)){
                        $photo_data['photo'] = $photo_path;
                        $photo_data['updated_at'] = date('Y-m-d H:i:s');
                        $photo_data['updated_by'] = $this->user_id;
                        $this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                        @unlink($old_photo);

                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    $this->set_notification("Mso Photo Uploaded","Photo of Mso [{$profile->get_attribute('mso_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$photo_data['photo'],'success_messages'=>'Successfully photo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }
                
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }

        } else {
           $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('mso');
        }
        
        
    }

    public function upload_identity()
    {
        if ($this->input->is_ajax_request()) {
            if($this->role_type == "staff") {
                $form_type = $this->input->post('form_type');
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'mso', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'mso', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $token   = $this->input->post('token'); 
            $profile = $this->mso_profile->find_by_token($token);
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
                    $MSO_PATH = MSO_PATH.$user->get_attribute('id');
                    $to_path = $MSO_PATH.'/identity';
                    $old_file = $profile->get_attribute('identity_attachment');
                    $identity_path = $to_path.'/identity'.date('ymdHis').'.'.$type;

                    if(@file_exists($MSO_PATH)){
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
                        $this->mso_profile->save($identity_data,$profile->get_attribute('id'));
                        @unlink($old_file);
                    }

                    //$identity_data['identity_type'] = $this->input->post('type');
                    //$identity_data['identity_number'] = $this->input->post('id');
                    //$this->mso_profile->save($identity_data,$profile->get_attribute('id'));
                    $this->set_notification("Mso Identity Uploaded","Identity document of Mso [{$profile->get_attribute('mso_name')}] has been uploaded");
                    echo json_encode(array('status'=>200,'image'=>$identity_data['identity_attachment'],'success_messages'=>'Successfully identity document attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }
            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No profile found to attach photo'));
            }
        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('mso');
        }
    }

     public function image_download($imagedata)
    {
        if($_GET['id']=='mso_profile'){
            //echo $_GET['id'];
            $data = $this->mso_profile->find_by_token($imagedata);
            //print_r($data->get_attribute('photo'));exit;
            $file = $data->get_attribute('photo');
        }elseif($_GET['id']=='mso_identity'){

            $data = $this->mso_profile->find_by_token($imagedata);
            $file = $data->get_attribute('identity_attachment');
        }else{

            redirect('mso');
        }
      
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
}