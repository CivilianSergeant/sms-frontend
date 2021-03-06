<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Iptv_program_model $Iptv_program
 * @property Streamer_instance_model $streamer_instance
 * @property User_model $user
 * @property Content_provider_model $content_provider
 * @property Png_compressor $png_compressor
 */
class Serial_contents extends BaseController
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
        $this->parent_id = $this->user_session->parent_id;
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
        $this->load->model('Default_image_size','default_image_size');
        $this->load->model('Settings_model','settings');
        $this->theme->set_title('Serial Contents')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/serial-contents/add.js');

        $data['user_info'] = $this->user_session;

        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['languages'] = $this->language->get_all();

        $data['content_aggregator_types'] = $this->content_aggregator_type->get_all();
        $id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
        $data['content_providers'] = $this->content_provider->get_all_content_provider($id);
        
        $data['service_operators'] = $this->service_operator->get_all();
        $data['video_tags'] = $this->Iptv_program->get_video_tags($id);
        $data['content_provider_categories'] = $this->content_provider_category->get_all();
        $data['image_sizes'] = $this->default_image_size->getContentImageSizes();
        $data['settings']  = $this->settings->find_api_settings();
        $data['image_qualities'] = $this->settings->get_image_qualities();
        if($this->user_session->lsp_type_id == 0){
            $data['lsps'] = $this->lco_profile->get_all_lco_users(1); 
        }
        $this->theme->set_view('serial-contents/program',$data,true);
    }
    
    public function ajax_get_permissions()
    {
        if($this->input->is_ajax_request()){
            if($this->role_type == self::ADMIN){
                $permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
            }else{
                $role = $this->user_session->role_id;
                $permissions = $this->menus->has_permission($role,1,'serial-contents',$this->user_type);
            }
            $types = $this->Iptv_program_type->get_content_types();
            echo json_encode(array(
                'status'=>200,
                'permissions'=>$permissions,
                'types'=>$types
            ));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_serial_programs()
    {
        if($this->input->is_ajax_request()){
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $filter = $this->input->get('filter');
            $sort = $this->input->get('sort');
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $programs = $this->Iptv_program->get_serial_programs($id,$take,$skip,$filter,$sort);

            $total = $this->Iptv_program->count_serial_programs($id,$filter);
            echo json_encode(array('status'=>200,
                'programs'=>$programs,
                'total'=>$total,
            ));

        }else{
            redirect('/');
        }
    }

    public function ajax_get_program($id){

        if($this->input->is_ajax_request()) {
            $this->load->model('Settings_model','settings');
            $iptvProgram = $this->Iptv_program->find_by_id($id);

            $iptvProgram = $iptvProgram->get_attributes();
            $iptvProgram['service_operator_id'] = explode(",", $iptvProgram['service_operator_id']);
            $iptvProgram['description'] = (!empty($iptvProgram['description'])) ? base64_decode($iptvProgram['description']) : null;

            $categoryProgram = $this->Iptv_category_program->find_by_program($id);

            $iptvProgram['category_id'] = (!empty($categoryProgram)) ? $categoryProgram->category_id : null;
            $iptvProgram['sub_category_id'] = (!empty($categoryProgram)) ? $categoryProgram->sub_category_id : null;

            $service_operators = $this->service_operator->get_all();
            $types = $this->Iptv_program_type->get_all();
            $apiSettings = $this->settings->find_api_settings();
            $types = $this->Iptv_program_type->get_content_types();
            echo json_encode(array('status' => 200, 'service_operators' => $service_operators, 'settings'=>$apiSettings,
                'types'=>$types, 'program' => $iptvProgram, 'types' => $types));
            exit;
        }else{
            redirect('/');
        }
    }
    
    public function ajax_get_categories($type)
    {
        $id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
        if($this->input->is_ajax_request()){
            if($type == "CATCHUP"){
                $categories = $this->Iptv_category->get_catchup_categories($id);
            }else{
                $categories = $this->Iptv_category->get_vod_categories($id);
            }
            echo json_encode(array('status'=>200,'categories'=>$categories));
        }else{
            redirect('/');
        }
    }

    public function ajaxGetSubCategories($id){
        if($this->input->is_ajax_request()) {
            $subCategories = $this->Iptv_sub_category->find_by_category_id($id);
            echo json_encode(array('status' => 200, 'sub_categories' => $subCategories));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_lco()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $lco = $this->lco_profile->get_all_lco_users($id);
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

    public function ajax_get_streamer_instance_by_lco($lco_id)
    {
        if($this->input->is_ajax_request()){
            $lco = $this->user->find_by_id($lco_id);
            if($lco->has_attributes()){
                $instances = $this->streamer_instance->find_by_lco($lco->get_attribute('id'));
                echo json_encode(array('status'=>200,'instances'=>$instances));
                exit;
            }
            echo json_encode(array('status'=>400,'instances'=>array()));
        }else{
            redirect('/');
        }
    }

    public function save_mapping()
    {
        if($this->input->is_ajax_request()){

            if($this->role_type == self::STAFF){
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                    exit;
                }
            }

            $programId = $this->input->post('programId');
            $operator_id = $this->input->post('operator_id');
            $instance_id  = $this->input->post('instance_id');
            $hls = $this->input->post('hls');

            $program = $this->Iptv_program->find_by_id($programId);
            if(!$program->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Content not found to map HLS link'));
                exit;
            }

            if(empty($operator_id)){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Select Operator/LCO to map HLS link'));
                exit;
            }

            if(empty($instance_id)){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Select Instance to map HLS link'));
                exit;
            }

            $instance = $this->streamer_instance->find_by_id($instance_id);
            if(!$instance->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Streamer Instance not found'));
                exit;
            }

            if(empty($hls)){
               echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Please add HLS link'));
               exit;
            }

            $counter = 0;

            $this->map_streamer_instance->delete_all_by_instance_program($instance_id,$programId);

            foreach($hls as $h){
                if(!empty($h['hls_url_mobile']) && !empty($h['hls_url_stb'])){

                    /*$exist = $this->map_streamer_instance->hasMapping($instance_id,$programId,$h['hls_url_mobile'],$h['hls_url_stb'],$h['hls_url_web']);
                    if(!empty($exist)){

                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! This combination of mapping already exist.'));
                        exit;
                    }*/

                    $saveData = array(
                        'program_id' => $programId,
                        'streamer_instance_id' => $instance_id,
                        'hls_url_mobile' => $h['hls_url_mobile'],
                        'hls_url_stb' => $h['hls_url_stb'],
                        'hls_url_web' => $h['hls_url_web']
                    );
                    $this->map_streamer_instance->save($saveData);
                    $counter++;
                }

            }

            $title = 'Content and Instance successfully mapped';
            $msg = 'Congratulations! Total ['.$counter.'] HLS link mapped with Catchup Content ['.$program->get_attribute('program_name').'], instance ['.$instance->get_attribute('instance_name').']';

            $this->set_notification($title,$msg);
            echo json_encode(array('status'=>200,'success_messages'=>$msg));

        }else{
            redirect('/');
        }
    }

    public function remove_mapping()
    {
        if($this->input->is_ajax_request()){

            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_delete_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have delete permission"));
                    exit;
                }
            }

            $id = $this->input->post('id');
            $password = md5($this->input->post('password'));
            $user  = $this->user->find_by_id($this->user_id);
            if(!empty($user) && ($user->get_attribute('password') == $password)) {
                $this->map_streamer_instance->remove_by_id($id);
                echo json_encode(array('status' => 200, 'success_messages' => 'HLS Link Successfully removed'));
            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Password did not matched. Delete operation failed'));
            }
        }else{
            redirect('/');
        }
    }


    public function upload_image(){

        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');
            $web_logo  = (int)$this->input->post('web_logo');
            $stb_logo  = (int)$this->input->post('stb_logo');
            $mobile_logo   = (int)$this->input->post('mobile_logo');
            $mobile_poster = (int)$this->input->post('mobile_poster');
            $web_poster    = (int)$this->input->post('web_poster');
            $stb_poster    = (int)$this->input->post('stb_poster');
            $web_player_poster    = (int)$this->input->post('web_player_poster');
            $mobile_player_poster = (int)$this->input->post('mobile_player_poster');
            $image_quality = $this->input->post('image_quality');

            $this->load->model('Default_image_size','default_image_size');
            $imageSize = $this->default_image_size->getContentImageSizes();

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $program = $this->Iptv_program->find_by_id($id);

            $filesize = (2*(1024*1024));

            if ($program->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');

                    $to_path = $PROGRAM_PATH.'/logo';
                    $main_photo_path = $to_path.'/main.'.$type;

                    if(@file_exists($PROGRAM_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    $warningMsg = '';

                    try {

                        $photo_data = array();

                        if(move_uploaded_file($tmp_uploaded_file,$main_photo_path)){

                            
                            // Upload web logo
                            if($web_logo){

                                $w = $imageSize['advance']['WEB_LOGO']['width'];
                                $h = $imageSize['advance']['WEB_LOGO']['height'];

                                $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
                                if(@file_exists($PROGRAM_PATH)){
                                    @mkdir($to_path,0777);
                                }else{
                                    @mkdir($to_path,0777,true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/web_logo_'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }


                                $old_photo  = $program->get_attribute('logo_web_url');
                                @unlink($old_photo);
                                $photo_data['logo_web_url'] = $final_path;

                            }

                            // Upload stb logo
                            if($stb_logo){

                                $w = $imageSize['advance']['STB_LOGO']['width'];
                                $h = $imageSize['advance']['STB_LOGO']['height'];

                                $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
                                if(@file_exists($PROGRAM_PATH)){
                                    @mkdir($to_path,0777);
                                }else{
                                    @mkdir($to_path,0777,true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/stb_logo_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }

                                $old_photo  = $program->get_attribute('logo_stb_url');
                                @unlink($old_photo);
                                $photo_data['logo_stb_url'] = $final_path;
                            }

                            // Upload mobile logo
                            if($mobile_logo){
                                $w = $imageSize['advance']['MOBILE_LOGO']['width'];
                                $h = $imageSize['advance']['MOBILE_LOGO']['height'];

                                $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
                                if(@file_exists($PROGRAM_PATH)){
                                    @mkdir($to_path,0777);
                                }else{
                                    @mkdir($to_path,0777,true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/mobile_logo_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }

                                $old_photo  = $program->get_attribute('logo_mobile_url');
                                @unlink($old_photo);
                                $photo_data['logo_mobile_url'] = $final_path;

                            }

                            // Upload mobile poster
                            if($mobile_poster){
                                $w = $imageSize['advance']['MOBILE_POSTER']['width'];
                                $h = $imageSize['advance']['MOBILE_POSTER']['height'];

                                $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
                                if(@file_exists($PROGRAM_PATH)){
                                    @mkdir($to_path,0777);
                                }else{
                                    @mkdir($to_path,0777,true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/mobile_poster_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }

                                $old_photo  = $program->get_attribute('poster_url_mobile');
                                @unlink($old_photo);
                                $photo_data['poster_url_mobile'] = $final_path;

                            }

                            // Upload web poster
                            if($web_poster){
                                $w = $imageSize['advance']['WEB_POSTER']['width'];
                                $h = $imageSize['advance']['WEB_POSTER']['height'];

                                $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
                                if(@file_exists($PROGRAM_PATH)){
                                    @mkdir($to_path,0777);
                                }else{
                                    @mkdir($to_path,0777,true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/web_poster_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }

                                $old_photo  = $program->get_attribute('poster_url_web');
                                @unlink($old_photo);
                                $photo_data['poster_url_web'] = $final_path;

                            }

                            // Upload stb poster
                            if($stb_poster){
                                $w = $imageSize['advance']['STB_POSTER']['width'];
                                $h = $imageSize['advance']['STB_POSTER']['height'];

                                $to_path = $PROGRAM_PATH . '/logo/' . $w . 'x' . $h;
                                if (@file_exists($PROGRAM_PATH)) {
                                    @mkdir($to_path, 0777);
                                } else {
                                    @mkdir($to_path, 0777, true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/stb_poster_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }

                                $old_photo = $program->get_attribute('poster_url_stb');
                                @unlink($old_photo);
                                $photo_data['poster_url_stb'] = $final_path;

                            }
                            
                            // Upload web player poster
                            if($web_player_poster){
                                $w = $imageSize['advance']['WEB_PLAYER_POSTER']['width'];
                                $h = $imageSize['advance']['WEB_PLAYER_POSTER']['height'];

                                $to_path = $PROGRAM_PATH . '/logo/' . $w . 'x' . $h;
                                if (@file_exists($PROGRAM_PATH)) {
                                    @mkdir($to_path, 0777);
                                } else {
                                    @mkdir($to_path, 0777, true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/web_player_poster_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }

                                $old_photo = $program->get_attribute('player_poster_web');
                                @unlink($old_photo);
                                $photo_data['player_poster_web'] = $final_path;

                            }
                            
                            // Upload mobile player poster
                            if($mobile_player_poster){
                                $w = $imageSize['advance']['MOBILE_PLAYER_POSTER']['width'];
                                $h = $imageSize['advance']['MOBILE_PLAYER_POSTER']['height'];

                                $to_path = $PROGRAM_PATH . '/logo/' . $w . 'x' . $h;
                                if (@file_exists($PROGRAM_PATH)) {
                                    @mkdir($to_path, 0777);
                                } else {
                                    @mkdir($to_path, 0777, true);
                                }

                                $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.'.$type;
                                $final_path = $to_path.'/mobile_player_poster_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                                $compressResponse = $this->png_compressor
                                    ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

                                $output = implode(',',$compressResponse['output']);
                                if(preg_match('/error/',strtolower($output))){
                                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                    exit;
                                }

                                $old_photo = $program->get_attribute('player_poster_mobile');
                                @unlink($old_photo);
                                $photo_data['player_poster_mobile'] = $final_path;

                            }

                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_program->save($photo_data, $program->get_attribute('id'));
                        }
                        

                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }


                    if ($form_type) {
                        $this->set_notification("VoD Content logo Uploaded", "Logo of Content [{$program->get_attribute('program_name')}] has been uploaded");
                    }
                    if($warningMsg != null){
                        echo json_encode(array('status'=>400,'image'=>$photo_data,'warning_messages'=>$warningMsg));
                    }else {
                        echo json_encode(array('status'=>200,'image'=>$photo_data,'success_messages'=>'Successfully logo attached'));
                    }
                } else {
                    echo json_encode(array('status'=>400,'redirect'=>site_url('serial-contents/edit/'.$id),'warning_messages'=>'Logo File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No Content found to attach logo'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('serial-contents');
        }


    }

    public function upload_logo(){

        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $program = $this->Iptv_program->find_by_id($id);

            $filesize = (1*(1024*1024));

            if ($program->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
                    $to_path = $PROGRAM_PATH.'/logo';
                    $old_photo  = $program->get_attribute('logo_url');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PROGRAM_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);

                        if (file_put_contents($photo_path,$compressed_png_content)) {
                            $photo_data['logo_url'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_program->save($photo_data, $program->get_attribute('id'));
                            @unlink($old_photo);
                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Content logo Uploaded", "Logo of Content [{$program->get_attribute('program_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['logo_url'],'success_messages'=>'Successfully logo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Logo File Type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No Content found to attach logo'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('serial-contents');
        }
    }


    public function upload_logo_mobile(){

        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $program = $this->Iptv_program->find_by_id($id);

            $filesize = (1*(1024*1024));

            if ($program->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
                    $to_path = $PROGRAM_PATH.'/logo';
                    $old_photo  = $program->get_attribute('logo_mobile_url');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PROGRAM_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try{
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);

                        if(file_put_contents($photo_path,$compressed_png_content)){
                            $photo_data['logo_mobile_url'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_program->save($photo_data,$program->get_attribute('id'));
                            @unlink($old_photo);

                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Content Logo(mobile) Uploaded", "Mobile Logo of Content [{$program->get_attribute('program_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['logo_mobile_url'],'success_messages'=>'Successfully logo attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Logo for mobile file type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No Content found to attach logo'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('serial-contents');
        }


    }

    public function upload_poster_mobile(){

        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $program = $this->Iptv_program->find_by_id($id);

            $filesize = (1*(1024*1024));

            if ($program->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];

                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
                    $to_path = $PROGRAM_PATH.'/poster-mobile';
                    $old_photo  = $program->get_attribute('poster_url_mobile');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PROGRAM_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);
                        if (file_put_contents($photo_path,$compressed_png_content)) {
                            $photo_data['poster_url_mobile'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_program->save($photo_data, $program->get_attribute('id'));
                            @unlink($old_photo);

                        }
                    }catch (Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Content Poster Uploaded", "Poster of Content [{$program->get_attribute('program_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['poster_url_mobile'],'success_messages'=>'Successfully poster attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Poster for mobile, file type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No Content found to attach poster'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('serial-contents');
        }


    }

    public function upload_poster_stb(){

        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $program = $this->Iptv_program->find_by_id($id);

            $filesize = (1*(1024*1024));
            //test(get_file_info($_FILES['file']['tmp_name']));
            if ($program->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];
                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
                    $to_path = $PROGRAM_PATH.'/poster-stb';
                    $old_photo  = $program->get_attribute('poster_url_stb');
                    $photo_path = $to_path.'/'.date('ymdHis').'.'.$type;

                    if(@file_exists($PROGRAM_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $compressed_png_content = $this->png_compressor->compress_png($tmp_uploaded_file);

                        if (file_put_contents($photo_path,$compressed_png_content)) {
                            $photo_data['poster_url_stb'] = $photo_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_program->save($photo_data, $program->get_attribute('id'));
                            @unlink($old_photo);

                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Content poster Uploaded", "Poster of Content [{$program->get_attribute('program_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['poster_url_stb'],'success_messages'=>'Successfully poster attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Poster for stb, file type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No Content found to attach poster'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('serial-contents');
        }


    }


    public function upload_water_mark(){

        if ($this->input->is_ajax_request()) {

            $form_type = $this->input->post('form_type');

            if($this->role_type == "staff") {
                if ($form_type) {
                    $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                        exit;
                    }
                } else {
                    $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                    if (!$permission) {
                        echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                        exit;
                    }
                }
            }

            $id   = $this->input->post('id');
            $this->load->model('Default_image_size','default_image_size');
            $imageSize = $this->default_image_size->getChannelImageSizes();
            $program = $this->Iptv_program->find_by_id($id);

            $filesize = (1*(1024*1024));
            //test(get_file_info($_FILES['file']['tmp_name']));
            if ($program->has_attributes()) {
                if(preg_match('/(png)/',$_FILES['file']['type'])){

                    if ($_FILES['file']['size'] > $filesize) {
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! File size should be within 1MB'));
                        exit;
                    }

                    $tmp_uploaded_file = $_FILES['file']['tmp_name'];
                    //$photo_data['photo'] = file_get_contents($tmp_uploaded_file);
                    $type = (substr($_FILES['file']['type'],(strrpos($_FILES['file']['type'],'/')+1),strlen($_FILES['file']['type'])));
                    $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
                    $to_path = $PROGRAM_PATH.'/logo';
                    $old_photo  = $program->get_attribute('water_mark_url');
                    $main_photo_path = $to_path.'/main_water_mark.'.$type;

                    if(@file_exists($PROGRAM_PATH)){
                        @mkdir($to_path,0777);
                    }else{
                        @mkdir($to_path,0777,true);
                    }

                    try {
                        $w = $imageSize['watermark']['width'];
                        $h = $imageSize['watermark']['height'];

                        if (move_uploaded_file($tmp_uploaded_file,$main_photo_path)) {

                            $to_path = $PROGRAM_PATH . '/logo/' . $w . 'x' . $h;
                            if (@file_exists($PROGRAM_PATH)) {
                                @mkdir($to_path, 0777);
                            } else {
                                @mkdir($to_path, 0777, true);
                            }

                            $temp_path = $PROGRAM_PATH.'/logo/watermark_'.$w.'x'.$h.'.'.$type;
                            $final_path = $to_path . '/watermark_' . str_replace(array(" ","0."),"",microtime()) . '.' . $type;

                            $compressResponse = $this->png_compressor
                                ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h);

                            $output = implode(',',$compressResponse['output']);
                            if(preg_match('/error/',strtolower($output))){
                                echo json_encode(array('status'=>400,'warning_messages'=>$output));
                                exit;
                            }

                            $photo_data['water_mark_url'] = $final_path;
                            $photo_data['updated_at'] = date('Y-m-d H:i:s');
                            $photo_data['updated_by'] = $this->user_id;
                            $this->Iptv_program->save($photo_data, $program->get_attribute('id'));
                            @unlink($old_photo);

                        }
                    }catch(Exception $ex){
                        echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                        exit;
                    }

                    //$this->mso_profile->save($photo_data,$profile->get_attribute('id'));
                    if ($form_type) {
                        $this->set_notification("Content poster Uploaded", "Poster of Content [{$program->get_attribute('program_name')}] has been uploaded");
                    }
                    echo json_encode(array('status'=>200,'image'=>$photo_data['water_mark_url'],'success_messages'=>'Successfully poster attached'));
                } else {
                    echo json_encode(array('status'=>400,'warning_messages'=>'Water mark file type is not valid'));
                }

            } else {
                echo json_encode(array('status'=>400,'warning_messages'=>'No Content found to attach poster'));
            }

        } else {
            $this->session->set_flashdata('warning_messages','Sorry! Direct access not allowed');
            redirect('serial-contents');
        }
    }

    public function save_program()
    {
        if($this->input->is_ajax_request()){

            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_create_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! You don't have create permission"));
                    exit;
                }
            }

            $this->form_validation->set_rules('program_name','Content Name','required|max_length[500]|trim');

            if($this->form_validation->run() == FALSE){

                echo json_encode(array('status'=>400,'warning_messages'=>validation_errors()));
                exit;

            }else{

                $programName = $this->Iptv_program->is_name_available($this->input->post('program_name'),$this->input->post('type'));
                if(!empty($programName)){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Content name not available'));
                    exit;
                }

                $contentDir = $this->Iptv_program->is_content_dir_available($this->input->post('content_dir'));
                if(!empty($contentDir)){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Content directory is not available'));
                    exit;
                }

                $lcnNumber = 0; //$this->input->post('lcn');

                if($lcnNumber<0){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! LCN could not be negative value'));
                    exit;
                }

                if($lcnNumber>0){
                    $isUnique = $this->Iptv_program->is_lcn_unique($lcnNumber);
                    if(!empty($isUnique)){
                        echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! LCN number [ '.$lcnNumber.' ] is not available'));
                        exit;
                    }
                }

                $description = strip_tags($_POST['description'],"<p> <span> <strong> <em>");
                
                $lsp = $this->lco_profile->get_lco_by_username($this->input->post('parent_id'));
                $lsp_id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
                
                $saveData = array(
                    'lcn'              => $lcnNumber,
                    'program_name'     => $this->input->post('program_name'),
                    'description'      => (!empty($description))? base64_encode($description):null,
                    'type'             => $this->input->post('type'),
                    'is_active'        => $this->input->post('is_active'),
                    'is_available'        => $this->input->post('is_available'),
                    'age_restriction'  => $this->input->post('age_restriction'),
                    'video_trailer_url'=> $this->input->post('video_trailer_url'),
                    'video_share_url'  => $this->input->post('video_share_url'),
                    'keywords'         => $this->input->post('keywords'),
                    'duration'         => $this->input->post('duration'),
                    'video_tags'       => $this->input->post('video_tags'),
                    'video_language'   => $this->input->post('video_language'),
                    'recording_date'   => $this->input->post('recording_date'),
                    'individual_price' => $this->input->post('individual_price'),
                    'content_provider_id' => $this->input->post('content_provider_id'),
                    'content_aggregator_type_id' => $this->input->post('content_aggregator_type_id'),
                    'content_provider_category_id' => $this->input->post('content_provider_category_id'),
                    'service_operator_id' => (!empty($this->input->post('service_operator_id')))? implode(",",$this->input->post('service_operator_id')) : '',
                    'serial_content'   => 1,
                    'content_dir' => $this->input->post('content_dir'),
                    'image_quality' => $this->input->post('image_quality'),
                    'lsp_type_id' => (!empty($lsp))? $lsp->lsp_type_id : 0,
                    'parent_id' => (!empty($lsp))? $lsp->id : $lsp_id
                );

                $saved = $this->Iptv_program->save($saveData);

                if(!empty($this->input->post('category_id')) && !empty($this->input->post('sub_category_id'))){
                    $this->Iptv_category_program->remove($this->input->post('category_id'),$this->input->post('sub_category_id'));
                    $this->Iptv_category_program->save(array(
                        'category_id'     => $this->input->post('category_id'),
                        'sub_category_id' => $this->input->post('sub_category_id'),
                        'program_id'      => $saved
                    ));
                }

                // set notification
                $title = " Content created.";
                $msg   = "A New Content {$saveData['program_name']} has been created";
                $this->set_notification($title,$msg);

                // return response
                echo json_encode(array(
                    'status'=>200,
                    'id'=>$saved,
                    'success_messages'=>'Content '. $saveData['program_name'].' successfully saved')
                );
            }
        }else{
            redirect('/');
        }
    }

    public function edit($id)
    {

        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
                redirect('serial-contents');
            }
        }

        $iptvProgram = $this->Iptv_program->find_by_id($id);
        if(!$iptvProgram->has_attributes()){
            $this->session->set_flashdata('warning_messages','Content not found');
            redirect('serial-contents');
        }

        $this->theme->set_title('Edit Serial Content')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/serial-contents/edit.js');
        
        $this->load->model('Default_image_size','default_image_size');
        $this->load->model('Settings_model','settings');

        $data['user_info'] = $this->user_session;
        //$data['unassigned_programs'] = $this->program->count_unassigned_program();
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['program'] = $iptvProgram->get_attributes();
        $data['programId'] = $iptvProgram->get_attribute('id');
        $data['languages'] = $this->language->get_all();
        $id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
        $data['categories'] = $this->Iptv_category->get_catchup_categories($id);
        $data['program'] = $iptvProgram->get_attributes();
        $data['content_aggregator_types'] = $this->content_aggregator_type->get_all();
        
        $data['content_providers'] = $this->content_provider->get_all_content_provider($id);
        $data['content_provider_categories'] = $this->content_provider_category->get_all();
        $data['video_tags'] = $this->Iptv_program->get_video_tags($id);
        $data['image_sizes'] = $this->default_image_size->getContentImageSizes();
        $data['image_qualities'] = $this->settings->get_image_qualities();
        if($this->user_session->lsp_type_id == 0){
            $data['lsps'] = $this->lco_profile->get_all_lco_users(1); 
        }
        $this->theme->set_view('serial-contents/edit-program',$data,true);
    }

    public function update_program()
    {
        if($this->input->is_ajax_request()){
            // here will be update code

            if($this->role_type == self::STAFF) {
                $permission = $this->menus->has_edit_permission($this->role_id, 1, 'serial-contents', $this->user_type);
                if (!$permission) {
                    json_encode(array('status'=>400,'warning_messages'=>"Sorry! You don't have edit permission"));
                    exit;
                }
            }

            $updateData = $this->input->post();

            $serviceOperators = array();
            if(!empty($updateData['service_operator_id'])){
                foreach($updateData['service_operator_id'] as $soi){
                    if(!empty($soi)){
                        $serviceOperators[] = $soi;
                    }
                }
            }

            $programName = $updateData['program_name'];
            unset($updateData['program_name']);
            $updateData['service_operator_id'] = implode(",",$serviceOperators);
            
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            $updateData['updated_by'] = (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id);
            $description = strip_tags($_POST['description'],"<p> <span> <strong> <em>");
            $updateData['description'] = (!empty($description))? base64_encode($description):null;
            $lsp = $this->lco_profile->get_lco_by_username($this->input->post('parent_id'));
            $lsp_id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
            $updateData['lsp_type_id'] = (!empty($lsp))? $lsp->lsp_type_id : 0;
            $updateData['parent_id']  = (!empty($lsp))? $lsp->id : $lsp_id;
            unset($updateData['web_logo']);
            unset($updateData['stb_logo']);
            unset($updateData['mobile_logo']);
            unset($updateData['mobile_poster']);
            unset($updateData['web_poster']);
            unset($updateData['stb_poster']);
            unset($updateData['web_player_poster']);
            unset($updateData['mobile_player_poster']);
            unset($updateData['category_id']);
            unset($updateData['sub_category_id']);
            unset($updateData['lsp']);
            if(strlen($updateData['video_share_url']) < 32){
                $updateData['video_share_url'] = md5($updateData['video_share_url']);
            }
            $this->Iptv_program->save($updateData,$updateData['id']);

            if(!empty($this->input->post('category_id')) && !empty($this->input->post('sub_category_id'))){
                $this->Iptv_category_program->remove($this->input->post('category_id'),$this->input->post('sub_category_id'));
                $this->Iptv_category_program->save(array(
                    'category_id'     => $this->input->post('category_id'),
                    'sub_category_id' => $this->input->post('sub_category_id'),
                    'program_id'      => $updateData['id']
                ));
            }

            // set notification
            $title = "Content Updated.";
            $msg   = 'Content '.$programName.' has been successfully updated';
            $this->set_notification($title,$msg);

            echo json_encode(array("status"=>200,'success_messages'=>$msg));
        }else{
            redirect('serial-contents');
        }
    }

    public function view($id)
    {
        $iptvProgram = $this->Iptv_program->find_by_id($id);
        if(!$iptvProgram->has_attributes()){
            $this->session->set_flashdata('warning_messages','Content not found');
            redirect('serial-contents');
        }

        $this->theme->set_title('View Serial Content')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/serial-contents/view.js');

        $types = $this->Iptv_program_type->get_all();

        $data['user_info'] = $this->user_session;
        //$data['unassigned_programs'] = $this->program->count_unassigned_program();
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['types']   = $types;
        $program = $iptvProgram->get_attributes();
        //$program['description'] = (!empty($program['description']))? base64_decode($program['description']):null;
        $data['program'] = $program;
        $data['programId'] = $iptvProgram->get_attribute('id');
        $data['streamer_instances'] = $this->map_streamer_instance->get_all_by_program($iptvProgram->get_attribute('id'));
        $data['languages'] = $this->language->get_all();
        $data['content_aggregator_types'] = $this->content_aggregator_type->get_all();
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $data['categories'] = $this->Iptv_category->get_program_categories($data['programId']);
        $data['content_providers'] = $this->content_provider->get_all_content_provider($id);
        $data['content_provider_categories'] = $this->content_provider_category->get_all();
        if($this->user_session->lsp_type_id == 0){
            $data['lsps'] = $this->lco_profile->get_all_lco_users(1); 
        }
        $this->theme->set_view('serial-contents/view-program',$data,true);
    }


    public function delete()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_delete_permission($this->role_id, 1, 'serial-contents', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages', "Sorry! You don't have delete permission");
                redirect('serial-contents');
            }
        }

        // checking is password matched for delete operation
        $password = $this->input->post('password');
        $userExist = $this->auth->is_username_password_matched($this->user_session->username,md5($password));
        if(empty($userExist)){
            echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Password not matched'));
            exit;
        }

        $id = $this->input->post('delete_item');

        $program = $this->Iptv_program->find_by_id($id);

        if(!$program->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! Delete Operation Failed. Content '.$program->get_attribute('program_name').' not found');
            redirect('serial-contents');
        }

        $program_check=$this->Iptv_program->checkassign_program($id);

        $hasHls = $this->Iptv_program->has_hls($id);

        if(!empty($hasHls)){
            $this->session->set_flashdata('warning_messages','Sorry! Delete Operation Failed. Content '.$program->get_attribute('program_name').' has HLS link assigned');
            redirect('serial-contents');
        }


        if($program_check != Null){
            $this->session->set_flashdata('warning_messages','Content already is used');
            redirect('serial-contents');
        }else{
            delete_files(PROGRAM_PATH.$id,true);
            @rmdir(PROGRAM_PATH.$id);
            $program = $this->Iptv_program->find_by_id($id);
            $program_name = $program->get_attribute('program_name');
            $this->Iptv_program->program_delete($id);
            //$this->notification->save_notification(null,"Program Deleted","Program Information {$program_name} has been deleted.",$this->user_session->id);

            // set notification
            $title = "Content Deleted.";
            $msg   = 'Information '.$program_name.' has been deleted';
            $this->set_notification($title,$msg);

            redirect('serial-contents');
        }
    }

    public function mapping($id)
    {
        $iptvProgram = $this->Iptv_program->find_by_id($id);
        if(!$iptvProgram->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! Content not found, Please check is Content exist');
            redirect('serial-contents');
        }

        $this->theme->set_title('Mapping Serial Content')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/serial-contents/mapping.js');



        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['program'] = $iptvProgram->get_attributes();

        if($this->user_session->lsp_type=0){
            $data['parentId'] = 1;
        }else{
            $parentId = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
            $data['parentId'] = $parentId;
        }

        $this->theme->set_view('serial-contents/mapping-program',$data,true);
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