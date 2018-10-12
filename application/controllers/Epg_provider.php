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
class Epg_provider extends BaseController
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
        $this->theme->set_title('EPG Provider')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/epg_provider/add.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('epg_provider/index',$data,true);
    }
    
    public function save_epg_provider()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'manage-epg-provider', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }
        
        $saveEpgProviderData = array(
            'provider_name' => $this->input->post('provider_name'),
            'address' => $this->input->post('address'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'contact_person_name' => $this->input->post('contact_person_name'),
            'contact_person_phone' => $this->input->post('contact_person_phone'),
            'web_url' => $this->input->post('web_url')
        );
        
        
        $epg_provider_id = $this->epg_provider->save($saveEpgProviderData);
        if($epg_provider_id){
            $this->set_notification('New EPG Provider Created','New EPG Provider created ['.$saveEpgProviderData['provider_name'].']');
            echo json_encode(array('status'=>200,'success_messages'=>'EPG [' . $saveEpgProviderData['provider_name'] . '] Provider Created.'));
        }

    }
    
    public function ajax_get_epg_providers()
    {
        if($this->input->is_ajax_request()) {
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            
            $epg_providers = $this->epg_provider->get_all_epg_providers($take,$skip);
            $total = $this->epg_provider->count_all_epg_providers();

            echo json_encode(array('status'=>200,'epg_providers'=>$epg_providers,'total'=>$total));
        }else{
            redirect('/');
        }
    }
    
    public function get_provider_by_id($id)
    {
        $provider = $this->epg_provider->get_epg_provider_by_id($id);
        echo json_encode(array('status'=>200, 'provider'=>$provider));
    }

    public function edit($id)
    {
        $this->theme->set_title('EPG Provider')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/epg_provider/edit.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['id'] = $id;
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('epg_provider/edit',$data,true);
    }
    
    public function update_epg_provider()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'manage-epg-provider', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }
        
        $saveEpgProviderData = array(
            'id' => $this->input->post('id'),
            'provider_name' => $this->input->post('provider_name'),
            'address' => $this->input->post('address'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'contact_person_name' => $this->input->post('contact_person_name'),
            'contact_person_phone' => $this->input->post('contact_person_phone'),
            'web_url' => $this->input->post('web_url')
        );
        
        $epg_provider_id = $this->epg_provider->save($saveEpgProviderData, $saveEpgProviderData['id']);
        if($epg_provider_id){
            echo json_encode(array('status'=>200,'success_messages'=>'EPG [' . $saveEpgProviderData['provider_name'] . '] Provider Updated.'));
        }

    }
    
    public function view($id)
    {
        $this->theme->set_title('EPG Provider')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/epg_provider/add.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['provider'] = $this->epg_provider->get_epg_provider_by_id($id);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('epg_provider/view',$data,true);
    }
    
    public function delete($id)
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_delete_permission($this->role_id, 1, 'manage-epg-provider', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have delete permission"));
                exit;
            }
        }

        $epg_provider = $this->epg_provider->find_by_id($id);
        if($epg_provider->has_attributes()){
            $this->epg_provider->remove_by_id($id);
        }
        $this->set_notification('EPG Provider ['.$epg_provider->get_attribute('provider_name').'] Deleted','EPG ['.$epg_provider->get_attribute('provider_name').'] Deleted by '.$this->user_session->username);
        echo json_encode(array('status'=>200,'success_messsages'=>'EPG ['.$epg_provider->get_attribute('provider_name').'] Deleted'));

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