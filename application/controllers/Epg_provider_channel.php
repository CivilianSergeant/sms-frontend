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
class Epg_provider_channel extends BaseController
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
            ->add_script('controllers/epg_provider_channel/add.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['epg_providers'] = $this->epg_provider->get_all_epg_providers();
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('epg_provider_channel/index',$data,true);
    }
    
    public function save_epg_provider_channel()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'manage-provider-channel', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }
        
        $saveEpgProviderChannelData = array(
            'provider_channel_id' => $this->input->post('provider_channel_id'),
            'provider_id' => $this->input->post('provider_id'),
            'provider_channel_name' => $this->input->post('provider_channel_name'),
            'lang' => $this->input->post('lang'),
        );
        
        
        $epg_provider_channel_id = $this->epg_provider_channel->save($saveEpgProviderChannelData);
        if($epg_provider_channel_id){
            $this->set_notification('New EPG Provider Channel Created','New EPG Provider Channel created for program ['.$saveEpgProviderData['provider_name'].']');
            echo json_encode(array('status'=>200,'success_messages'=>'EPG Channel [' . $saveEpgProviderData['provider_name'] . '] Created.'));
        }

    }
    
    public function ajax_get_epg_provider_channels()
    {
        if($this->input->is_ajax_request()) {
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            
            $epg_provider_channels = $this->epg_provider_channel->get_all_epg_provider_channels($take,$skip);
            $total = $this->epg_provider_channel->count_all_epg_provider_channel();

            echo json_encode(array('status'=>200,'epg_provider_channels'=>$epg_provider_channels,'total'=>$total));
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