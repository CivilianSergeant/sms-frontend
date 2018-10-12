<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Iptv_program_model $Iptv_program
 * @property Iptv_category_program_model $Iptv_category_program
 * @property Streamer_instance_model $streamer_instance
 * @property User_model $user
 * @property Content_provider_model $content_provider
 * @property Language_model $language
 * @property Png_compressor $png_compressor
 */
class Channel_sort extends BaseController
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
        $this->load->model('Default_image_size','default_image_size');
        $this->load->model('Settings_model','settings');
        $this->theme->set_title('All Channels')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/iptv/program/channel_sort.js');

        $data['user_info'] = $this->user_session;
        //$data['unassigned_programs'] = $this->program->count_unassigned_program();
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['languages'] = $this->language->get_all();
        $data['content_aggregator_types'] = $this->content_aggregator_type->get_all();
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $data['content_providers'] = $this->content_provider->get_all_content_provider($id);
        $data['service_operators'] = $this->service_operator->get_all();
        $parent_id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
        $data['categories'] = $this->Iptv_category->get_live_delay_categories($parent_id);
        $data['content_provider_categories'] = $this->content_provider_category->get_all();
        $data['image_sizes'] = $this->default_image_size->getChannelImageSizes();
        $data['settings']  = $this->settings->find_api_settings();
        $data['image_qualities'] = $this->settings->get_image_qualities();
        //$data['permissions'] = $permissions;
        //test($data['image_sizes']);
        if($this->user_session->lsp_type_id == 0){
            $data['lsps'] = $this->lco_profile->get_all_lco_users(1); 
        }
        $this->theme->set_view('iptv/program/channel_sort',$data,true);
    }

    public function update_program_order()
    {
         if($this->input->is_ajax_request()){
             $programData = $this->input->post('dataArr');
             foreach ($programData as $val) {
                $this->Iptv_program->update_program_order_action($val['id'], $val['index']);
             }
             
         }else{
             redirect('channels');
        }
        
    }
    
    public function update_program_status()
    {
        if($this->input->is_ajax_request()){
             $id = $this->input->post('id');
             $status = $this->input->post('status');
                $this->Iptv_program->update_program_status_action($id, $status);           
         }else{
             redirect('channels');
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
    
    public function sync_epg($id)
    {
        $iptvProgram = $this->Iptv_program->find_by_id($id);
        if(!$iptvProgram->has_attributes()){
            $this->session->set_flashdata('warning_messages','Channel not found');
            redirect('channels');
        }
        
        
        $this->theme->set_title('Sync Epgs')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/iptv/program/sync_epg.js');

        $data['user_info'] = $this->user_session;
        $epgProviderInfo   = $this->epg->get_epg_provider_mapping($iptvProgram->get_attribute('id'));
        if(empty($epgProviderInfo)){
            $this->session->set_flashdata('warning_messages','System channel is not mapped with provider channel');
            redirect('channels');
        }
        $data['epg_provider_info'] = $epgProviderInfo;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['program'] = $iptvProgram->get_attributes();
        $this->theme->set_view('iptv/program/sync_epg',$data,true);
    }
    
    public function download_epg()
    {
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
            $this->set_notification("EPG Syncronized", "EPG Successfully Syncronized");
            echo json_encode(array('status'=>200));
            die();
        }
        echo json_encode(array('status'=>400));
        die();
    }
    
    private function replace_unicode_escape_sequence($match) {
        return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
    }

    private function unicode_decode($str) {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
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


}