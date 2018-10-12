<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: user
 * Date: 9/5/2016
 * Time: 10:58 AM
 * @property Epg_model $epg
 * @property Epg_repeat_time_model $epg_repeat_time
 * @property Iptv_program_model $Iptv_program
 * @property Png_compressor $png_compressor
 */
class Epg extends BaseController
{
    const FIXED = 'FIXED';
    const RECURRING = 'RECURRING';
    protected $user_session;
    protected $user_type;
    protected $user_id;
    protected $parent_id;
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


        /*if($this->user_type == self::LCO_LOWER){
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
        }*/

        if(in_array($this->user_type,array('subscriber'))){
            redirect('/');
        }

    }

    public function index()
    {
        $this->theme->set_title('EPG')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/epg/add.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('epg/index',$data,true);
    }

    public function edit($id)
    {

        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'manage-epg', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
                redirect('mange-epg');
            }
        }

        $epg = $this->epg->find_by_id($id);
        if(!$epg->has_attributes()){
            $this->session->set_flashdata('warning_messages',"Sorry! EPG not exist");
            redirect('mange-epg');
        }

        $this->theme->set_title('EPG')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/epg/edit.js');

        $data['id'] = $id;
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('epg/edit',$data,true);
    }

    public function view($id)
    {
        /*if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'manage-epg', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
                redirect('mange-epg');
            }
        }*/

        $epg = $this->epg->find_by_id($id);
        if(!$epg->has_attributes()){
            $this->session->set_flashdata('warning_messages',"Sorry! EPG not exist");
            redirect('mange-epg');
        }

        $this->theme->set_title('EPG')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/epg/edit.js');


        $data['id'] = $id;
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('epg/view',$data,true);
    }

    public function ajax_get_epg($id){
        if($this->input->is_ajax_request()){
            $epg = $this->epg->find_by_id($id);

            $epg = $epg->get_attributes();

            $timezone = $this->session->get_userdata()['timezone'];

            $timezone = str_split($timezone);
            $sign = (!empty($timezone)) ? $timezone[0] : null;
            $timeZoneHour = (isset($timezone[1]) && isset($timezone[2]))? $timezone[1].$timezone[2] : 0;
            $timeZoneMinute = (isset($timezone[4]) && isset($timezone[5]))? $timezone[4].$timezone[5] : 0;

            $epg['repeats'] = $this->epg_repeat_time->get_by_epg($epg['id']);

//            if($epg['epg_type'] == self::FIXED){
//
//                $startTimesTamp = strtotime($epg['show_date'].' '.$epg['start_time']);
//                $endTimesTamp = strtotime($epg['show_date'].' '.$epg['end_time']);
//
//                $startTimesTamp = ($sign=="+")? $startTimesTamp+($timeZoneHour*60*60) : $startTimesTamp-($timeZoneHour*60*60);
//                $startTimesTamp = ($sign=="+")? $startTimesTamp+($timeZoneMinute*60)  : $startTimesTamp-($timeZoneMinute*60);
//
//                $endTimesTamp = ($sign=="+")? $endTimesTamp+($timeZoneHour*60*60) : $endTimesTamp-($timeZoneHour*60*60);
//                $endTimesTamp = ($sign=="+")? $endTimesTamp+($timeZoneMinute*60)  : $endTimesTamp-($timeZoneMinute*60);
//
//                $epg['start_time'] = date("g:i a",$startTimesTamp);
//                $epg['end_time']   = date("g:i a",$endTimesTamp);
//
//                if(!empty($epg['repeats'])){
//
//                    foreach($epg['repeats'] as $i=> $repeat){
//                       
//                        $repeat_date = (!empty($repeat->repeat_date))? $repeat->repeat_date : date('Y-m-d');
//                        $repeatSTimesTamp = strtotime($repeat_date.' '.$repeat->repeat_start_time);
//                        $repeatETimesTamp = strtotime($repeat_date.' '.$repeat->repeat_end_time);
//
//                        $repeatSTimesTamp = ($sign=="+")? $repeatSTimesTamp+($timeZoneHour*60*60) : $repeatSTimesTamp-($timeZoneHour*60*60);
//                        $repeatSTimesTamp = ($sign=="+")? $repeatSTimesTamp+($timeZoneMinute*60)  : $repeatSTimesTamp-($timeZoneMinute*60);
//                        
//                        $repeatETimesTamp = ($sign=="+")? $repeatETimesTamp+($timeZoneHour*60*60) : $repeatETimesTamp-($timeZoneHour*60*60);
//                        $repeatETimesTamp = ($sign=="+")? $repeatETimesTamp+($timeZoneMinute*60)  : $repeatETimesTamp-($timeZoneMinute*60);
//
//                        $epg['repeats'][$i]->repeat_date = date("Y-m-d",$repeatSTimesTamp);
//                        $epg['repeats'][$i]->repeat_start_time = date("g:i a",$repeatSTimesTamp);
//                        $epg['repeats'][$i]->repeat_end_time = date("g:i a",$repeatETimesTamp);
//
//                    }
//                }
//
//
//            }else if($epg['epg_type'] == self::RECURRING){
//
//
//                $startTime = explode(" ",$epg['start_time']);
//                $endTime = explode(" ",$epg['end_time']);
//
//                $startTime = explode(":",$startTime[0]);
//                $startTime = mktime($startTime[0],$startTime[1]);
//                $startTime = ($sign=="+")? $startTime+($timeZoneHour*60*60) : $startTime-($timeZoneHour*60*60);
//                $startTime = ($sign=="+")? $startTime+($timeZoneMinute*60) : $startTime-($timeZoneMinute*60);
//
//
//                $endTime = explode(":",$endTime[0]);
//                $endTime = mktime($endTime[0],$endTime[1]);
//                $endTime = ($sign=="+")? $endTime+($timeZoneHour*60*60) : $endTime-($timeZoneHour*60*60);
//                $endTime = ($sign=="+")? $endTime+($timeZoneMinute*60) : $endTime-($timeZoneMinute*60);
//
//
//                $epg['start_time'] = date("g:i a",$startTime);
//                $epg['end_time']   = date("g:i a",$endTime);
//
//                if(!empty($epg['repeats'])){
//
//                    foreach($epg['repeats'] as $i=> $repeat){
//                        $repeatSTimesTamp = strtotime(date("Y-m-d").' '.$repeat->repeat_start_time);
//                        $repeatETimesTamp = strtotime(date("Y-m-d").' '.$repeat->repeat_end_time);
//
//                        $repeatSTimesTamp = ($sign=="+")? $repeatSTimesTamp+($timeZoneHour*60*60) : $repeatSTimesTamp-($timeZoneHour*60*60);
//                        $repeatSTimesTamp = ($sign=="+")? $repeatSTimesTamp+($timeZoneMinute*60)  : $repeatSTimesTamp-($timeZoneMinute*60);
//                        
//                        $repeatETimesTamp = ($sign=="+")? $repeatETimesTamp+($timeZoneHour*60*60) : $repeatETimesTamp-($timeZoneHour*60*60);
//                        $repeatETimesTamp = ($sign=="+")? $repeatETimesTamp+($timeZoneMinute*60)  : $repeatETimesTamp-($timeZoneMinute*60);
//
//                        $epg['repeats'][$i]->repeat_start_time = date("g:i a",$repeatSTimesTamp);
//                        $epg['repeats'][$i]->repeat_end_time = date("g:i a",$repeatETimesTamp);
//
//                    }
//                }
//            }
            $epg['program_name'] = (!empty($epg['program_name']))? base64_decode($epg['program_name']) : null;
            $epg['program_description'] = (!empty($epg['program_description']))? base64_decode($epg['program_description']): null;
           // print_r($epg['program_description']);die();
            echo json_encode(array('status'=>200,'epg'=>$epg));
        }else{
            redirect('/');
        }
    }
    
    


    public function ajax_get_permissions()
    {
        if($this->role_type == self::ADMIN){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $role = $this->user_session->role_id;
            $permissions = $this->menus->has_permission($role,1,'manage-epg',$this->user_type);
        }

        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function ajax_get_channels()
    {
        if($this->input->is_ajax_request()){
            $channels = $this->Iptv_program->get_all_program_name("type NOT IN('VOD','CATCHUP')");
            echo json_encode(array('status'=>200,'channels'=>$channels));
        }else{
            redirect('/');
        }
    }

    public function save_epg()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'manage-epg', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }
        
        $program_id = $this->input->post('program_id');
        $program_name = $this->input->post('program_name');
        $duration = $this->input->post('duration');
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        $repeats = $this->input->post('repeats');
        $epg_type = $this->input->post('epg_type');
        
//        print_r($end_time);die();

        $show_date = $weekDays = '';

        $program_description = $_POST['program_description'];
        $program_description = strip_tags($program_description,"<p> <span> <strong> <em>");
        $program_description = (!empty($program_description))? base64_encode($program_description):null;

        $exist = $this->epg->has_epg_by_program($program_id,$program_name);
        if(!empty($exist)){
            echo json_encode(array('status'=>400,'warning_messages'=>'Program ['.$program_name.'] already create with this channel. try something different'));
            exit;
        }



        if($epg_type == self::FIXED){
            $show_date = $this->input->post('show_date');

//            $startTimesTamp = strtotime($show_date.' '.$start_time);
//            $endTimesTamp = strtotime($show_date.' '.$end_time);
//            date_default_timezone_set('UTC');

            $saveEpgData = array(
                'program_id' => $program_id,
                'program_name' => base64_encode($program_name),
                'duration' => $duration,
                'show_date' => $show_date, //date("Y-m-d",$startTimesTamp),
                'start_time' => $start_time, //date("g:i a",$startTimesTamp),
                'end_time' => $end_time,
                'program_description'=> $program_description,
                'epg_type' => $epg_type
            );

        }else{
            $weekDays = $this->input->post('weekDays');
//            $startTimesTamp = strtotime(date("Y-m-d")." ".$start_time);
//            $endTimesTamp   = strtotime(date("Y-m-d")." ".$end_time);
//            date_default_timezone_set('UTC');

            $saveEpgData = array(
                'program_id' => $program_id,
                'program_name' => base64_encode($program_name),
                'duration' => $duration,
                'start_time' => $start_time, //date("g:i a",$startTimesTamp),
                'end_time'   => $end_time, //date("g:i a",$endTimesTamp),
                'program_description'=> $program_description,
                'week_days' => (!empty($weekDays))? implode(",",$weekDays) : '',
                'epg_type' => $epg_type
            );
        }

        $this->load->model('Default_image_size','default_image_size');
        $imageSize = $this->default_image_size->getEpgImageSizes();

        if(!empty($_POST['program_logo'])){

            $type = explode(";",$_POST['program_logo']);
            if(!preg_match('/png/',$type[0])){
                echo json_encode(array('status'=>400,'warning_messages'=>'Progarm Logo file format must be (*.png)'));
                exit;
            }

            $to_path = 'public/uploads/logo';

            $main_photo_path = $to_path.'/main_epg_logo.'.$type[1];

            if(@file_exists($to_path)){
                @mkdir($to_path,0777);
            }else{
                @mkdir($to_path,0777,true);
            }

            try {

                $w = $imageSize['advance']['EPG_LOGO']['width'];
                $h = $imageSize['advance']['EPG_LOGO']['height'];

                $to_path = $to_path.'/'.$w.'x'.$h;
                if(@file_exists($to_path)){
                    @mkdir($to_path,0777);
                }else{
                    @mkdir($to_path,0777,true);
                }

                $temp_path = $to_path.'/main_epg_logo_'.$w.'x'.$h.'.'.$type[1];
                $final_path = $to_path.'/main_epg_logo_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                file_put_contents($main_photo_path, base64_decode(trim(str_replace("base64,", "", $type[1]))));
                $compressResponse = $this->png_compressor->compress_png($main_photo_path,$temp_path,$final_path,$w,$h);

                $output = implode(',',$compressResponse['output']);
                if(preg_match('/error/',strtolower($output))){
                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                    exit;
                }

            }catch(Exception $ex){

                echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                exit;
            }

            $saveEpgData['program_logo'] = $final_path;
        }

        if(!empty($_POST['program_poster'])){


            $type = explode(";",$_POST['program_poster']);
            if(!preg_match('/png/',$type[0])){
                echo json_encode(array('status'=>400,'warning_messages'=>'Program Poster file format must be (*.png)'));
                exit;
            }

            $to_path = 'public/uploads/logo';
            $main_photo_path = $to_path.'/main_epg_poster.'.$type[1];

            if(@file_exists($to_path)){
                @mkdir($to_path,0777);
            }else{
                @mkdir($to_path,0777,true);
            }

            try {
                $w = $imageSize['advance']['EPG_POSTER']['width'];
                $h = $imageSize['advance']['EPG_POSTER']['height'];

                $to_path = $to_path.'/'.$w.'x'.$h;
                if(@file_exists($to_path)){
                    @mkdir($to_path,0777);
                }else{
                    @mkdir($to_path,0777,true);
                }

                $temp_path = $to_path.'/main_epg_poster_'.$w.'x'.$h.'.'.$type[1];
                $final_path = $to_path.'/main_epg_poster_'.str_replace(array(" ","0."),"",microtime()).'.'.$type[1];

                file_put_contents($main_photo_path, base64_decode(trim(str_replace("base64,", "", $type[1]))));
                $compressResponse = $this->png_compressor->compress_png($main_photo_path,$temp_path,$final_path,$w,$h);

                $output = implode(',',$compressResponse['output']);
                if(preg_match('/error/',strtolower($output))){
                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                    exit;
                }

            }catch(Exception $ex){
                echo json_encode(array('status'=>200,'warning_messages'=>$ex->getMessage()));
                exit;
            }

            $saveEpgData['program_poster'] = $final_path;
        }

        $epg_id = $this->epg->save($saveEpgData);

        if($epg_id){
            if(!empty($repeats)){
                foreach($repeats as $i=> $rd){
                    if($epg_type == self::FIXED){
                        if((!empty($rd['repeat_date'])) && (!empty($rd['repeat_start_time'])) && (!empty($rd['repeat_end_time']))){
//                            $repeat_date_stimestamp = strtotime($rd['repeat_date'].' '.$rd['repeat_start_time']);
//                            $repeat_date_etimestamp = strtotime($rd['repeat_date'].' '.$rd['repeat_end_time']);
//                            date_default_timezone_set('UTC');
                            $this->epg_repeat_time->save(array(
                                'epg_id'=>$epg_id,
                                'repeat_date'=>$rd['repeat_date'],
                                'repeat_start_time'=> $rd['repeat_start_time'],
                                'repeat_end_time'  => $rd['repeat_end_time']
                            ));
                        }
                    }else{
                        if((!empty($rd['repeat_start_time'])) && (!empty($rd['repeat_end_time']))){
//                            $repeat_stimestamp = strtotime(date('Y-m-d '.$rd['repeat_start_time']));
//                            $repeat_etimestamp = strtotime(date('Y-m-d '.$rd['repeat_end_time']));
//                            date_default_timezone_set('UTC');
                            $this->epg_repeat_time->save(array(
                                'epg_id'=>$epg_id,
                                'week_days' => implode(",",$weekDays),
                                'repeat_start_time' => $rd['repeat_start_time'],
                                'repeat_end_time'   => $rd['repeat_end_time']
                            ));
                        }
                    }

                }
            }
        }
        $this->set_notification('New EPG Created','New EPG created for program ['.$program_name.']');
        echo json_encode(array('status'=>200,'success_messages'=>'New EPG created successfully'));
    }


    public function update_epg()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'manage-epg', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                exit;
            }
        }

        $id  = $this->input->post('id');
        $epg = $this->epg->find_by_id($id);
        if(!$epg->has_attributes()){
            echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! EPG not found"));
            exit;
        }

        $saveEpgData = $epg->get_attributes();

        $program_id = $this->input->post('program_id');
        $program_name = $this->input->post('program_name');
        $duration = $this->input->post('duration');

        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        $program_description = $_POST['program_description'];
        $show_date = $weekDays = '';
        $repeats = $this->input->post('repeats');
        $epg_type = $this->input->post('epg_type');


        if($saveEpgData['program_name'] != $program_name) {
            $exist = $this->epg->has_epg_by_program($program_id, $program_name);
            if (!empty($exist)) {
                echo json_encode(array('status' => 400, 'warning_messages' => 'Program [' . $program_name . '] already create with this channel. try something different'));
                exit;
            }
        }

        $saveEpgData['program_id'] = $program_id;
        $saveEpgData['program_name'] = base64_encode($program_name);
        $saveEpgData['duration'] = $duration;
        $saveEpgData['start_time'] = $start_time;
        $saveEpgData['end_time']   = $end_time;
        $saveEpgData['epg_type'] = $epg_type;
        if($epg_type == self::FIXED){
            $show_date = $this->input->post('show_date');
//            $startTimesTamp = strtotime($show_date.' '.$start_time);
//            $endTimesTamp   = strtotime($show_date.' '.$end_time);
//            date_default_timezone_set('UTC');
//
            $saveEpgData['show_date'] = $show_date;
//            $saveEpgData['start_time'] = date("g:i a",$startTimesTamp);
//            $saveEpgData['end_time'] = date("g:i a",$endTimesTamp);
//
        }else if($epg_type == self::RECURRING){
            $weekDays = $this->input->post('weekDays');
//            $startTimesTamp = strtotime(date("Y-m-d")." ".$start_time);
//            $endTimesTamp   = strtotime(date("Y-m-d")." ".$end_time);
//            date_default_timezone_set('UTC');
            $saveEpgData['week_days'] = (!empty($weekDays))? implode(',',$weekDays) : '';
//            $saveEpgData['start_time'] = date("g:i a",$startTimesTamp);
//            $saveEpgData['end_time'] = date("g:i a",$endTimesTamp);
        }


        $program_description = strip_tags($program_description,"<p> <span> <strong> <em>");
        $saveEpgData['program_description'] = (!empty($program_description))? base64_encode($program_description):null;

        $this->load->model('Default_image_size','default_image_size');
        $imageSize = $this->default_image_size->getEpgImageSizes();

        if(!empty($_POST['program_logo']) && preg_match('/base64/',$_POST['program_logo'])){

            $oldLogo = $saveEpgData['program_logo'];

            $type = explode(";",$_POST['program_logo']);
            if(!preg_match('/png/',$type[0])){
                echo json_encode(array('status'=>400,'warning_messages'=>'Progarm Logo file format must be (*.png)'));
                exit;
            }

            $to_path = 'public/uploads/logo';

            $main_photo_path = $to_path.'/main_epg_logo.'.$type[1];

            if(@file_exists($to_path)){
                @mkdir($to_path,0777);
            }else{
                @mkdir($to_path,0777,true);
            }

            try {
                $w = $imageSize['advance']['EPG_LOGO']['width'];
                $h = $imageSize['advance']['EPG_LOGO']['height'];

                $to_path = $to_path.'/'.$w.'x'.$h;
                if(@file_exists($to_path)){
                    @mkdir($to_path,0777);
                }else{
                    @mkdir($to_path,0777,true);
                }

                $temp_path = $to_path.'/main_epg_logo_'.$w.'x'.$h.'.'.$type[1];
                $final_path = $to_path.'/main_epg_logo_'.str_replace(array(" ","0."),"",microtime()).'.'.$type;

                file_put_contents($main_photo_path, base64_decode(trim(str_replace("base64,", "", $type[1]))));
                $compressResponse = $this->png_compressor->compress_png($main_photo_path,$temp_path,$final_path,$w,$h);

                $output = implode(',',$compressResponse['output']);
                if(preg_match('/error/',strtolower($output))){
                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                    exit;
                }
            }catch(Exception $ex){
                echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                exit;
            }

            $saveEpgData['program_logo'] = $final_path;
            if(file_exists($oldLogo)){
                @unlink($oldLogo);
            }
        }

        if(!empty($_POST['program_poster']) && preg_match('/base64/',$_POST['program_poster'])){

            $oldPoster = $saveEpgData['program_poster'];

            $type = explode(";",$_POST['program_poster']);
            if(!preg_match('/png/',$type[0])){
                echo json_encode(array('status'=>400,'warning_messages'=>'Program Poster file format must be (*.png)'));
                exit;
            }

            $to_path = 'public/uploads/logo';
            $main_photo_path = $to_path.'/main_epg_poster.'.$type[1];

            if(@file_exists($to_path)){
                @mkdir($to_path,0777);
            }else{
                @mkdir($to_path,0777,true);
            }

            try {

                $w = $imageSize['advance']['EPG_POSTER']['width'];
                $h = $imageSize['advance']['EPG_POSTER']['height'];

                $to_path = $to_path.'/'.$w.'x'.$h;
                if(@file_exists($to_path)){
                    @mkdir($to_path,0777);
                }else{
                    @mkdir($to_path,0777,true);
                }

                $temp_path = $to_path.'/main_epg_poster_'.$w.'x'.$h.'.'.$type[1];
                $final_path = $to_path.'/main_epg_poster_'.str_replace(array(" ","0."),"",microtime()).'.'.$type[1];

                file_put_contents($main_photo_path, base64_decode(trim(str_replace("base64,", "", $type[1]))));
                $compressResponse = $this->png_compressor->compress_png($main_photo_path,$temp_path,$final_path,$w,$h);

                $output = implode(',',$compressResponse['output']);
                if(preg_match('/error/',strtolower($output))){
                    echo json_encode(array('status'=>400,'warning_messages'=>$output));
                    exit;
                }
            }catch(Exception $ex){
                echo json_encode(array('status'=>400,'warning_messages'=>$ex->getMessage()));
                exit;
            }

            $saveEpgData['program_poster'] = $final_path;

            if(file_exists($oldPoster)){
                @unlink($oldPoster);
            }
        }

        $this->epg->save($saveEpgData,$saveEpgData['id']);

        if(!empty($repeats)){
            
            $this->epg_repeat_time->remove_by_epg($saveEpgData['id']);
            foreach($repeats as $i=> $rd){
                
                /*if((!empty($rd['repeat_date'])) && (!empty($rd['repeat_time']))){
                    $this->epg_repeat_time->save(array(
                        'epg_id'=>$saveEpgData['id'],
                        'repeat_date'=>$rd['repeat_date'],
                        'repeat_time'=> $rd['repeat_time']
                    ));
                }*/

                if($epg_type == self::FIXED){
                    if((!empty($rd['repeat_date'])) && (!empty($rd['repeat_start_time'])) && (!empty($rd['repeat_end_time']))){
//                        $repeat_date_timestamp = strtotime($rd['repeat_date'].' '.$rd['repeat_starttime']);
//                        date_default_timezone_set('UTC');
                        
                        $this->epg_repeat_time->save(array(
                            'epg_id'=>$saveEpgData['id'],
                            'repeat_date'=>$rd['repeat_date'],
                            'repeat_start_time'=> $rd['repeat_start_time'],
                            'repeat_end_time' => $rd['repeat_end_time']
                        ));
                    }
                }else{
                    if((!empty($rd['repeat_start_time'])) && (!empty($rd['repeat_end_time']))){
//                        $repeat_timestamp = strtotime(date('Y-m-d '.$rd['repeat_time']));
//                        date_default_timezone_set('UTC');
                        
                        $this->epg_repeat_time->save(array(
                            'epg_id'=>$saveEpgData['id'],
                            'week_days' => implode(",",$weekDays),
                            'repeat_start_time'=> $rd['repeat_start_time'],
                            'repeat_end_time' => $rd['repeat_end_time']
                        ));
                    }
                }

            }
        }else{
            $this->epg_repeat_time->remove_by_epg($saveEpgData['id']);
        }

        $this->set_notification('New EPG Updated','EPG information updated successfully');
        echo json_encode(array('status'=>200,'success_messages'=>'EPG information updated successfully'));
    }

    public function ajax_get_epgs()
    {
        if($this->input->is_ajax_request()) {
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $filter = $this->input->get('filter');
            $sort = $this->input->get('sort');

            $channel_id = $this->input->get('channel_id');
            $show_date  = $this->input->get('show_date');
            $start_time = $this->input->get('start_time');
            $end_time   = $this->input->get('end_time');

            
            $timezone = $this->session->get_userdata()['timezone'];

            $timezone = str_split($timezone);
            $sign = $timezone[0];
            $timeZoneHour = $timezone[1].$timezone[2];
            $timeZoneMinute = $timezone[4].$timezone[5];

            
            
            if( (!empty($show_date)|| $show_date == 'undefined') ||
                (!empty($start_time) || $start_time == 'undefined') ||
                (!empty($end_time) || $end_time == 'undefined') ||
                (!empty($channel_id) || $channel_id == 'undefined')){

                $filter = array('channel_id'=>$channel_id,'show_date'=>$show_date,'start_time'=>$start_time,'end_time'=>$end_time);
                $epgs = $this->epg->get_searched_epgs($filter,$take,$skip,$sort);
                $total = $this->epg->count_searched_epgs($filter);
            }else{
                $epgs = $this->epg->get_all_epgs($take,$skip,$filter,$sort);
                $total = $this->epg->count_all_epgs($filter);
            }

            if(!empty($epgs)){
                foreach($epgs as $i=> $epg){
                    $epgs[$i]->program_name = base64_decode($epg->program_name);
//                    if($epg->epg_type == self::FIXED) {
//                        $startTimesTamp = strtotime($epg->show_date . ' ' . $epg->start_time);
//                        $endTimesTamp = strtotime($epg->show_date . ' ' . $epg->end_time);
//
//                        $startTimesTamp = ($sign=="+")? $startTimesTamp+($timeZoneHour*60*60) : $startTimesTamp-($timeZoneHour*60*60);
//                        $startTimesTamp = ($sign=="+")? $startTimesTamp+($timeZoneMinute*60)  : $startTimesTamp-($timeZoneMinute*60);
//
//                        $endTimesTamp = ($sign=="+")? $endTimesTamp+($timeZoneHour*60*60) : $endTimesTamp-($timeZoneHour*60*60);
//                        $endTimesTamp = ($sign=="+")? $endTimesTamp+($timeZoneMinute*60)  : $endTimesTamp-($timeZoneMinute*60);
//
//                        $epg->show_date = date("Y-m-d", $startTimesTamp);
//                        $epg->start_time = date("g:i a", $startTimesTamp);
//                        $epg->end_time = date("g:i a", $endTimesTamp);
//
//                    }else if($epg->epg_type == self::RECURRING){
//
//                        $startTime = explode(" ",$epg->start_time);
//                        $endTime = explode(" ",$epg->end_time);
//
//                        $startTime = explode(":",$startTime[0]);
//                        $startTime = mktime($startTime[0],$startTime[1]);
//
//                        $startTime = ($sign=="+")? $startTime+($timeZoneHour*60*60) : $startTime-($timeZoneHour*60*60);
//                        $startTime = ($sign=="+")? $startTime+($timeZoneMinute*60)  : $startTime-($timeZoneMinute*60);
//
//                        $endTime = explode(":",$endTime[0]);
//                        $endTime = mktime($endTime[0],$endTime[1]);
//                        $endTime = ($sign=="+")? $endTime+($timeZoneHour*60*60) : $endTime-($timeZoneHour*60*60);
//                        $endTime = ($sign=="+")? $endTime+($timeZoneMinute*60)  : $endTime-($timeZoneMinute*60);
//
//
//                        $epg->start_time = date("g:i a", $startTime);
//                        $epg->end_time = date("g:i a", $endTime);
//                    }
                }
            }


            echo json_encode(array('status'=>200,'epgs'=>$epgs,'total'=>$total));
        }else{
            redirect('/');
        }
    }

    public function delete($id)
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_delete_permission($this->role_id, 1, 'manage-epg', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have delete permission"));
                exit;
            }
        }

        $epg = $this->epg->find_by_id($id);
        if($epg->has_attributes()){
            $this->epg_repeat_time->remove_by_epg($id);
            $this->epg->remove_by_id($id);
        }
        $this->set_notification('EPG ['.$epg->get_attribute('program_name').'] Deleted','EPG ['.$epg->get_attribute('program_name').'] Deleted by '.$this->user_session->username);
        echo json_encode(array('status'=>200,'success_messsages'=>'EPG ['.$epg->get_attribute('program_name').'] Deleted'));

    }
    
    public function sync_epg()
    {
        $this->theme->set_title('Sync Epg')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/iptv/program/sync_epg_custom.js');

        $data['user_info'] = $this->user_session;
        $epgProviders   = $this->epg_provider->get_all();
        
        $data['providers'] = $epgProviders;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        
        $this->theme->set_view('iptv/program/sync_epg_custom',$data,true);
    }
    
    public function ajax_get_mappings()
    {
        $provider_id = $this->input->post('provider');
        if(empty($provider_id)){
            echo json_encode(['status'=>400,'message'=>'No Item found']);
            exit;
        }
        $mappings = $this->epg->get_epg_mapping_by_provider($provider_id);
        echo json_encode(['status'=>200,'mappings'=>$mappings]);
    }
    
    private function process($url,$data)
    {
        
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HTTPGET,false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;

    }
    
    public function download_epg()
    {
        echo 'hi';
        die();
        $id    = $this->input->post('channel_id');
        $date  = $this->input->post('date');
        $tagId = $this->input->post('tag_id');
        
        $epgProviderMapping = $this->epg->get_epg_provider_mapping($id);
        $epgProvider        = $this->epg->get_epg_provider($epgProviderMapping->provider_id);
        
        $epg_api_url = 'm/v2/api/getProgsByChannel';
        $program_api_url = 'm/v2/api/getProg';
        $domain = $epgProvider->web_url;
        
        $epg_url = $domain.$epg_api_url;
        $data = array('date'=>$date,'channel_id'=>$epgProviderMapping->provider_channel_id);

        $epgJson = $this->process($epg_url, $data);
        if(!empty($epgJson)){
            
            $tvProgram = json_decode($epgJson);
            
            if(!empty($tvProgram)){
                
                foreach($tvProgram->tbl_programme as $i=>$program){
                    
                    $program_url = $domain.$program_api_url;
                    $programJson = $this->process($program_url, array('prog_id'=>$program->prog_id));
                    
                    if(!empty($programJson)){

                        $programObj = (array)json_decode($programJson);
                        
                        $programObj = array_shift($programObj);
                        $programObj = array_shift($programObj);

                        $tvProgram->tbl_programme[$i]->end_time = $programObj->end_time;
                        $tvProgram->tbl_programme[$i]->file_name = $domain.'ignt/uploads/medium/medium_'.$programObj->file_name;
                        
                        if(!empty($programObj->artist_id) &&
                            (!preg_match('/(N\/A|n\/a|na|NA)/',$programObj->artist_id)) &&
                            !empty($programObj->director_id) && 
                            (!preg_match('/(N\/A|n\/a|na|NA)/',$programObj->director_id))){

                            $actor = (isset($_GET['ln']) && $_GET['ln'] == 'en')? "\r\nActors: " : "\r\nঅভিনয়ে: ";
                            $director = (isset($_GET['ln']) && $_GET['ln'] == 'en')? "\r\nDirector: " : "\r\nপরিচালনায়: ";

                            $tvProgram->tbl_programme[$i]->description = $programObj->description.$actor.$programObj->artist_id.$director.$programObj->director_id;
                        }else{
                            $tvProgram->tbl_programme[$i]->description = $programObj->description;
                        }

                        unset($tvProgram->tbl_programme[$i]->prog_type_id);
                        unset($tvProgram->tbl_programme[$i]->gallery_id);
                        unset($tvProgram->tbl_programme[$i]->tag);

                    }
                }


            }
            
            foreach($tvProgram->tbl_programme as $tvp){
                
                $start_date = new DateTime($date.' '.$tvp->start_time);
                $since_start = $start_date->diff(new DateTime($date.' '.$tvp->end_time));
                $hour   = ($since_start->h < 10)? '0'.$since_start->h: $since_start->h;
                $minute = ($since_start->i < 10)? '0'.$since_start->i: $since_start->i;
                $second = ($since_start->s < 10)? '0'.$since_start->s: $since_start->s;
                
                $saveData = array(
                    'program_id' => $id,
                    'program_name' => base64_encode($tvp->prog_title),
                    'program_description' => base64_encode($tvp->description),
                    'original_image' => $tvp->file_name,
                    'show_date' => $date,
                    'epg_type'  => 'FIXED',
                    'duration'  => $hour.':'.$minute.':'.$second,
                    'tag_id' => (!empty($tagId)) ? $tagId : 'AUTO',
                    'start_time' => $tvp->start_time,
                    'end_time'   => $tvp->end_time,
                    'created_by' => ($this->role_type == self::ADMIN)? $this->user_id : $this->parent_id
                );
                
                $this->epg->save($saveData);
            }
            echo json_encode(array('status'=>200));
            die();
        }
        echo json_encode(array('status'=>400));
        die();
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