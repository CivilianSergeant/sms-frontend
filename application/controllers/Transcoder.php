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
 */
class Transcoder extends BaseController
{
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
        $this->theme->set_title('Transcoder')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/transcoder/add.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('transcoder/index',$data,true);
    }

    public function edit($id)
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'transcoder', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
                redirect('transcoder');
            }
        }

        $transcoder = $this->transcoder->find_by_id($id);
        if(!$transcoder->has_attributes()){
            $this->session->set_flashdata('warning_messages',"Sorry! Transcoder not exist");
            redirect('transcoder');
        }

        $this->theme->set_title('Transcoder')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/transcoder/edit.js');

        $data['id'] = $id;
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('transcoder/edit',$data,true);
    }

    public function view($id)
    {
        $transcoder = $this->transcoder->find_by_id($id);
        if(!$transcoder->has_attributes()){
            $this->session->set_flashdata('warning_messages',"Sorry! Transcoder not exist");
            redirect('transcoder');
        }

        $this->theme->set_title('Transcoder')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/transcoder/edit.js');

        $data['id'] = $id;
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('transcoder/view',$data,true);
    }
    
    public function ajax_get_transcoder_by_id($id){
        if($this->input->is_ajax_request()){
            $transcoder = $this->transcoder->find_by_id($id);
            $transcoder = $transcoder->get_attributes();
            echo json_encode(array('status'=>200, 'transcoder'=>$transcoder));
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



    public function save_transcoder()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'transcoder', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }

        $transcoder_name = $this->input->post('transcoder_name');
        $number = $this->input->post('number');
        $vendor_id = $this->input->post('vendor_id');
        $data_ip = $this->input->post('data_ip');
        $out_ip = $this->input->post('out_ip');
        $impl_ip = $this->input->post('impl_ip');
        $transcoder_user_name = $this->input->post('transcoder_user_name');
        $transcoder_password = $this->input->post('transcoder_password');

        $transcoderData = array(
            'transcoder_name' => $transcoder_name,
            'number' => $number,
            'vendor_id' => $vendor_id,
            'data_ip' => $data_ip,
            'out_ip' => $out_ip,
            'impl_ip' => $impl_ip,
            'transcoder_user_name' => $transcoder_user_name,
            'transcoder_password'=> $transcoder_password,
        );

        $this->transcoder->save($transcoderData);
        echo json_encode(array('status'=>200,'success_messages'=>'New Transcoder Created successfully'));
    }
    
     public function update_transcoder()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'transcoder', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
                exit;
            }
        }

        $id  = $this->input->post('id');
        $transcoder = $this->transcoder->find_by_id($id);
        if(!$transcoder->has_attributes()){
            echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! transcoder not found"));
            exit;
        }
        
        $transcoder_name = $this->input->post('transcoder_name');
        $number = $this->input->post('number');
        $vendor_id = $this->input->post('vendor_id');
        $data_ip = $this->input->post('data_ip');
        $out_ip = $this->input->post('out_ip');
        $impl_ip = $this->input->post('impl_ip');
        $transcoder_user_name = $this->input->post('transcoder_user_name');
        $transcoder_password = $this->input->post('transcoder_password');

        $transcoderData = array(
            'transcoder_name' => $transcoder_name,
            'number' => $number,
            'vendor_id' => $vendor_id,
            'data_ip' => $data_ip,
            'out_ip' => $out_ip,
            'impl_ip' => $impl_ip,
            'transcoder_user_name' => $transcoder_user_name,
            'transcoder_password'=> $transcoder_password,
        );

        $this->transcoder->save($transcoderData, $id);

        $this->set_notification('New transcoder Updated','Transcoder information updated successfully');
        echo json_encode(array('status'=>200,'success_messages'=>'Transcoder information updated successfully'));
    }

    public function ajax_get_transcoders()
    {
        if($this->input->is_ajax_request()) {
            $take = $this->input->post('take');
            $skip = $this->input->post('skip');
            $filter = $this->input->get('filter');
            $sort = $this->input->post('sort');

            $transcoders = $this->transcoder->get_all_transcoders($take,$skip,$filter,$sort);
            $total = $this->transcoder->count_all_transcoders($filter);

            echo json_encode(array('status'=>200,'transcoders'=>$transcoders,'total'=>$total));
        }else{
            redirect('/');
        }
    }

    public function delete($id)
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_delete_permission($this->role_id, 1, 'transcoder', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have delete permission"));
                exit;
            }
        }
        
        $transcoder = $this->transcoder->find_by_id($id);
        if($transcoder->has_attributes()){
            $this->transcoder->remove_by_id($id);
        }
        $this->set_notification('Transcoder ['.$transcoder->get_attribute('transcoder_name').'] Deleted','Transcoder ['.$transcoder->get_attribute('transcoder_name').'] Deleted by '.$this->user_session->username);
        echo json_encode(array('status'=>200,'success_messsages'=>'Transcoder ['.$transcoder->get_attribute('transcoder_name').'] Deleted'));

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