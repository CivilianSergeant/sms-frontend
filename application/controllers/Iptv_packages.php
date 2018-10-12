<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Iptv_package_model $Iptv_package
 * @property Iptv_program_model $Iptv_program
 * @property Png_compressor $png_compressor
 */
class Iptv_packages extends BaseController
{
    protected $user_session;
    protected $user_type;
    protected $user_id;
    protected $created_by;
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
        $this->load->library('png_compressor');

        $this->user_type = strtolower($this->user_session->user_type);
        $this->user_id = $this->user_session->id;
        $this->created_by = $this->user_session->created_by;

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
        $this->theme->set_title('Packages - Application')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/iptv/package/add.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['uri'] = $this->uri->segment(1);

        $this->theme->set_view('iptv/package/package',$data,true);
    }

    public function ajax_get_permissions()
    {
        if($this->input->is_ajax_request()){
            if($this->role_type == self::ADMIN){
                $permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
            }else{
                $role = $this->user_session->role_id;
                $uri = $this->uri->segment(1);

                $permissions = $this->menus->has_permission($role,1,$uri,$this->user_type);
            }
            echo json_encode(array('status'=>200,'permissions'=>$permissions));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_packages()
    {
        if($this->input->is_ajax_request()){
            $take   = $this->input->post('take');
            $skip   = $this->input->post('skip');
            $filter = $this->input->get('filter');
            $sort   = $this->input->post('sort');
            $uri = $this->uri->segment(1);

            if(preg_match('/catchup/',$uri)){
                $value = 'CATCHUP';
            }else if(preg_match('/vod/',$uri)){
                $value = 'VOD';
            }else{
                $value = array('FREE','LIVE','DELAY');
            }
            if(!empty($filter)){
                $filter['filters'][] = array(array(
                        'operator' => 'eq',
                        'field'    => 'package_type',
                        'value'    => $value
                    ));
            }else{
                $filter = array(
                    'filters'  => array(array(
                        'operator' => 'eq',
                        'field'    => 'package_type',
                        'value'    => $value
                    ))
                );
            }
            $id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
            $packages = $this->Iptv_package->get_all_packages($id,$take,$skip,$filter,$sort);
            $total = $this->Iptv_package->count_all_packages($id,$filter);
            echo json_encode(array('status'=>200,'packages'=>$packages,'total'=>$total));

        }else{
            redirect('/');
        }
    }

    public function ajax_get_categories()
    {
        if($this->input->is_ajax_request()){
            $categories = $this->Iptv_category->get_all();
            echo json_encode(array('status'=>200,'categories'=>$categories));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_sub_categories($category_id)
    {
        if($this->input->is_ajax_request()){
            $sub_categories = $this->Iptv_sub_category->find_by_category_id($category_id);
            echo json_encode(array('status'=>200,'sub_categories'=>$sub_categories));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_programs()
    {
        $id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
        if($this->input->is_ajax_request()){
            $uri = $this->uri->segment(1);
            /*if(preg_match('/(free)/',$uri)){
                $programs = $this->Iptv_program->get_free_programs();
            }else if(preg_match('/live/',$uri)){
                $programs = $this->Iptv_program->get_live_delay_programs();
            }else if(preg_match('/delay/',$uri)){
                $programs = $this->Iptv_program->get_delay_programs();
            }else */
            if(preg_match('/catchup/',$uri)){
                $programs = $this->Iptv_program->get_catchup_programs($id);
            }else if(preg_match('/vod/',$uri)){
                $programs = $this->Iptv_program->get_vod_programs($id);
            }else{
                $programs = $this->Iptv_program->get_live_delay_programs($id);
            }
            echo json_encode(array('status'=>200,'programs'=>$programs));
        }else{
            redirect('/');
        }
    }

    public function save_package()
    {
        if($this->input->is_ajax_request()){
            $uri = $this->uri->segment(1);

            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_create_permission($this->role_id, 1, $uri, $this->user_type);
                if (!$permission) {
                    $this->session->set_flashdata('warning_messages',"Sorry! You don't have create permission");
                    redirect($uri);
                }
            }

            $programs = $this->input->post('programs');
            
            if(empty($programs) && !preg_match('/(catchup|vod)/',$uri)){
                // warning message
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Include Program'));
                exit;
            }

            $parentId = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
            $packageExist = $this->Iptv_package->find_by_name($this->input->post('package_name'),$parentId);
            if(!empty($packageExist)){
                echo json_encode(array('status'=>400,'warning_messages'=>"Package name already exist, try something different"));
                exit;
            }

            $uri = $this->uri->segment(1);
            /*if(preg_match('/free/',$uri)){
                $package_type = 'FREE';
            }else if(preg_match('/live/',$uri)) {
                $package_type = 'LIVE';
            }else if(preg_match('/delay/',$uri)){
                $package_type = 'DELAY';
            }else*/
            if(preg_match('/catchup/',$uri)){
                $package_type = 'CATCHUP';
            }else if(preg_match('/vod/',$uri)){
                $package_type = 'VOD';
            }else{
                $package_type = 'LIVE';
            }

            $savePackageData = array(
                'package_name'          => $this->input->post('package_name'),
                'price'                 => $this->input->post('package_price'),
                'duration'              => $this->input->post('package_duration'),
                'package_type'          => $package_type,
                'is_commercial'         => $this->input->post('is_commercial'),
                'is_active'             => $this->input->post('is_active'),
                'not_deleteable'        => $this->input->post('not_deleteable'),
                'package_mobile_logo'   => $this->input->post('package_logo_mobile'),
                'package_stb_logo'      => $this->input->post('package_logo_stb'),
                'package_poster_mobile' => $this->input->post('package_poster_mobile'),
                'package_poster_stb'    => $this->input->post('package_poster_stb')
            );

            $packageId = $this->Iptv_package->save($savePackageData);

            if($packageId && !preg_match('/(catchup|vod)/',$uri)){
                 $this->Iptv_package->assign_programs($programs,$packageId);
            }

            // set notification
            $title = "{$package_type} Package Created.";
            $msg   = "{$package_type} Package [ {$savePackageData['package_name']} ]  has been successfully created";
            $this->set_notification($title,$msg);

            echo json_encode(array('status'=>200,'id'=>$packageId,'success_messages'=>$msg));

        }else{
            redirect('/');
        }
    }

    public function update_package()
    {
        $uri = $this->uri->segment(1);
        if($this->input->is_ajax_request()){

            if($this->role_type == self::STAFF) {



                $permission = $this->menus->has_edit_permission($this->role_id, 1, $uri, $this->user_type);
                if (!$permission) {
                    json_encode(array('status'=>400,'warning_messages'=>"Sorry! You don't have edit permission"));
                    exit;
                }
            }

            $id= $this->input->post('id');
            $package=$this->Iptv_package->find_by_id($id);


            if(!$package->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Something wrong please make sure package exist'));
                exit;
            }

            $programs = $this->input->post('programs');
            if(empty($programs) && !preg_match('/(catchup|vod)/',$uri)){
                echo json_encode(array('status'=>400,'warning_messages'=>'Please Include Program'));
                exit;
            }

            $updatePackageData = array(

                'package_name'          => $this->input->post('package_name'),
                'price'                 => $this->input->post('price'),
                'duration'              => $this->input->post('duration'),
                'package_type'          => $this->input->post('package_type'),
                'is_commercial'         => $this->input->post('is_commercial'),
                'is_active'             => $this->input->post('is_active'),
                'package_mobile_logo'   => $this->input->post('package_mobile_logo'),
                'package_stb_logo'      => $this->input->post('package_stb_logo'),
                'package_poster_mobile' => $this->input->post('package_poster_mobile'),
                'package_poster_stb'    => $this->input->post('package_poster_stb'),
                'updated_at'            => date('Y-m-d H:i:s'),
                'updated_by'            => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id
            );
            $parentId = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
            $packageObj = $this->Iptv_package->find_by_name_not_in($updatePackageData['package_name'],$id,$parentId);

            if(!empty($packageObj)){
                echo json_encode(array('status'=>400,'warning_message'=>'Name is not available'));
                exit;
            }

            $this->Iptv_package->save($updatePackageData,$id);


            if(!empty($package) && !preg_match('/(catchup|vod)/',$uri)) {
                // remove old programs
                $package->remove_programs();
                // assign programs
                $package->assign_programs($programs);
            }


            // set notification
            $title = "Package Updated";
            $msg   = 'Package '.$package->get_attribute('package_name').' updated successfully';
            $this->set_notification($title,$msg);

            echo json_encode(array('status'=>200,'success_messages'=>$msg));
            exit;
        }else{
            redirect('/');
        }
    }

    public function view($id)
    {
        $this->theme->set_title('View Packages - Application')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css');

        $package = $this->Iptv_package->find_by_id($id);
        $package_programs = $package->get_programs(null,false,'iptv_programs.id asc');

        $tempProgramList = array();
        $i = 0;
        foreach ($package_programs as $value) {
            $tempProgramList[$i]['program_id'] = $value->program_id;
            $tempProgramList[$i]['lcn'] = $value->lcn;
            $tempProgramList[$i]['program_name'] = $value->program_name;

            $i++;
        }

        $data['package_programs'] = json_encode($tempProgramList);

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['package']  = $package;
        $data['uri'] = $this->uri->segment(1);
        $this->theme->set_view('iptv/package/view-package',$data,true);
    }

    public function edit($id)
    {
        if($this->role_type == "staff") {
            $uri = $this->uri->segment(1);

            $permission = $this->menus->has_edit_permission($this->role_id, 1, $uri, $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
                redirect($uri);
            }
        }

        $package = $this->Iptv_package->find_by_id($id);

        $this->theme->set_title('Edit Packages - Application')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/iptv/package/edit.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['package'] = $package;
        $data['uri'] = $this->uri->segment(1);
        $this->theme->set_view('iptv/package/edit-package',$data,true);
    }

    public function delete($id)
    {
        $uri = $this->uri->segment(1);

        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('/');
        }
        if($this->role_type == 'staff') {

            $permission = $this->menus->has_delete_permission($this->role_id, 1, $uri, $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have delete permission");
                redirect($uri);
            }
        }

        $package = $this->Iptv_package->find_by_id($id);
        $assigned_package = $this->Iptv_package_subscription->assigned_package($package->get_attribute('id'));

        if ($assigned_package != null)
        {
            $this->session->set_flashdata('warning_messages','This Package Already Assigned.');
            redirect($uri);
        }
        else
        {

            $this->Iptv_package_program->delete_programs_by_package($package->get_attribute('id'));

            $this->Iptv_package->package_delete($package->get_attribute('id'));
            //$this->notification->save_notification(null,"Package Deleted","Package [{$package->get_attribute('package_name')}] has been deleted");
            // set notification
            $title = "Package Deleted";
            $msg   = "Package [{$package->get_attribute('package_name')}] has been deleted";
            $this->set_notification($title,$msg);

            $this->session->set_flashdata('success_messages', 'Package has been deleted');
            redirect($uri);
        }
    }

    public function ajax_get_package_programs($id)
    {


        $package = $this->Iptv_package->find_by_id($id);
        if (empty($package)) {
            echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No Package Found'));
            exit;
        }

        $package_programs = $package->get_programs();

        $uri = $this->uri->segment(1);
        /*if(preg_match('/(free)/',$uri)) {
            $programs = $this->Iptv_program->get_free_programs();
        }else if(preg_match('/(live)/',$uri)) {
            $programs = $this->Iptv_program->get_live_delay_programs();
        }else if(preg_match('/(delay)/',$uri)){
            $programs = $this->Iptv_program->get_delay_programs();
        }else */
        if(preg_match('/catchup/',$uri)){
            $programs = $this->Iptv_program->get_catchup_programs();
        }else if(preg_match('/vod/',$uri)){
            $programs = $this->Iptv_program->get_vod_programs();
        }else{
            $programs = $this->Iptv_program->get_live_delay_programs();
        }


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

    public function upload_logo_stb(){
        $uri = $this->uri->segment(1);
        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == self::STAFF) {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $package = $this->Iptv_package->find_by_id($id);

            $filesize = (1*(1024*1024));

            if ($package->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PACKAGE_PATH = PACKAGE_PATH.$package->get_attribute('id');
                    $to_path = $PACKAGE_PATH.'/logo-stb';
                    $old_photo  = $package->get_attribute('package_stb_logo');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PACKAGE_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);
                        if (file_put_contents($photo_path,$compressed_png_content)) {
                            $photo_data['package_stb_logo'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_package->save($photo_data, $package->get_attribute('id'));
                            if (file_exists($old_photo)) {
                                @unlink($old_photo);
                            }

                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Package STB logo Uploaded", "STB Logo of Package [{$package->get_attribute('package_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['package_stb_logo'],'success_messages'=>'Successfully STB logo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No package found to attach logo'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect($uri);
        }


    }

    public function upload_logo_mobile(){
        $uri = $this->uri->segment(1);
        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == self::STAFF) {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $package = $this->Iptv_package->find_by_id($id);

            $filesize = (1*(1024*1024));

            if ($package->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PACKAGE_PATH = PACKAGE_PATH.$package->get_attribute('id');
                    $to_path = $PACKAGE_PATH.'/logo-mobile';
                    $old_photo  = $package->get_attribute('package_mobile_logo');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PACKAGE_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);

                        if (file_put_contents($photo_path,$compressed_png_content)) {
                            $photo_data['package_mobile_logo'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_package->save($photo_data, $package->get_attribute('id'));
                            if (file_exists($old_photo)) {
                                @unlink($old_photo);
                            }

                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Package Mobile logo Uploaded", "Mobile Logo of Package [{$package->get_attribute('package_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['package_mobile_logo'],'success_messages'=>'Successfully Mobile logo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No package found to attach logo'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect($uri);
        }
    }

    public function upload_poster_mobile(){

        $uri = $this->uri->segment(1);
        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $package = $this->Iptv_package->find_by_id($id);

            $filesize = (1*(1024*1024));

            if ($package->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PACKAGE_PATH = PACKAGE_PATH.$package->get_attribute('id');
                    $to_path = $PACKAGE_PATH.'/poster-mobile';
                    $old_photo  = $package->get_attribute('package_poster_mobile');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PACKAGE_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);
                        if (file_put_contents($photo_path,$compressed_png_content)) {
                            $photo_data['package_poster_mobile'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_package->save($photo_data, $package->get_attribute('id'));
                            if (file_exists($old_photo)) {
                                @unlink($old_photo);
                            }
                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Package Mobile Poster Uploaded", "Mobile Poster of Package [{$package->get_attribute('program_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['package_poster_mobile'],'success_messages'=>'Successfully poster attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No package found to attach poster'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect($uri);
        }


    }

    public function upload_poster_stb(){

        $uri = $this->uri->segment(1);
        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, $uri, $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $package = $this->Iptv_package->find_by_id($id);

            $filesize = (1*(1024*1024));

            if ($package->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PACKAGE_PATH = PACKAGE_PATH.$package->get_attribute('id');
                    $to_path = $PACKAGE_PATH.'/poster-stb';
                    $old_photo  = $package->get_attribute('package_poster_stb');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PACKAGE_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);
                        if (file_put_contents($photo_path, $compressed_png_content)) {
                            $photo_data['package_poster_stb'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_package->save($photo_data, $package->get_attribute('id'));
                            if (file_exists($old_photo)) {
                                @unlink($old_photo);
                            }
                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Program STB Poster Uploaded", "STB Poster of Package [{$package->get_attribute('program_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['package_poster_stb'],'success_messages'=>'Successfully poster attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No package found to attach poster'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect($uri);
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