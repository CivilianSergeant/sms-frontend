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
class Content_provider extends BaseController
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
        $this->theme->set_title('Content Provider')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/content_provider/add.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('content_provider/index',$data,true);
    }

    public function edit($id)
    {

        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'content-provider', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
                redirect('content-provider');
            }
        }

        $content_provider = $this->content_provider->find_by_id($id);
        if(!$content_provider->has_attributes()){
            $this->session->set_flashdata('warning_messages',"Sorry! Content Provider not exist");
            redirect('content-provider');
        }

        $this->theme->set_title('Content Provider')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/content_provider/edit.js');

        $data['id'] = $id;
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('content_provider/edit',$data,true);
    }

    public function view($id)
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'content-provider', $this->user_type);
            if (!$permission) {
                $this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
                redirect('content-provider');
            }
        }

        $content_provider = $this->content_provider->find_by_id($id);
        if(!$content_provider->has_attributes()){
            $this->session->set_flashdata('warning_messages',"Sorry! Content Provider not exist");
            redirect('content-provider');
        }

        $this->theme->set_title('content-provider')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/content_provider/edit.js');

        $data['id'] = $id;
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('content_provider/view',$data,true);
    }

    public function ajax_get_content_provider($id){
        if($this->input->is_ajax_request()){
            $content_provider = $this->content_provider->find_by_id($id);
            $content_provider = $content_provider->get_attributes();

            echo json_encode(array('status'=>200,'content_provider'=>$content_provider));
        }else{
            redirect('/');
        }
    }

// content_aggregator_type 
    public function  ajax_get_content_aggregator()
    {
        if($this->input->is_ajax_request()){
            $content_aggregator = $this->content_aggregator_type->get_all();
            echo json_encode(array('status'=>200,'content_aggregator'=>$content_aggregator));
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
            $permissions = $this->menus->has_permission($role,1,'content-provider',$this->user_type);
        }

        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }



    public function save_content_provider()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'content-provider', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }

        $this->form_validation->set_rules('company_name', 'Company Name', 'required|is_unique[content_providers.company_name]');

        if ($this->form_validation->run() == false) {
            

            echo json_encode(array('status'=>400,'warning_messages'=>'Company Name Must be Unique'));

        }else{

        
            $company_name = $this->input->post('company_name');
            // print_r($company_name);die();
            $address = $this->input->post('address');
            $phone = $this->input->post('phone');
            $mobile = $this->input->post('mobile');
            $lat = $this->input->post('lat');
            $lon = $this->input->post('lon');
            $contact_person_1 = $this->input->post('contact_person_1');
            $contact_person_1_phone = $this->input->post('contact_person_1_phone');
            $contact_person_1_email = $this->input->post('contact_person_1_email');
            $contact_person_1_designation = $this->input->post('contact_person_1_designation');
            $contact_person_2 = $this->input->post('contact_person_2');
            $contact_person_2_phone = $this->input->post('contact_person_2_phone');
            $contact_person_2_email = $this->input->post('contact_person_2_email');
            $contact_person_2_designation = $this->input->post('contact_person_2_designation');
            $content_aggregator_type = $this->input->post('content_aggregator_type');
            $remarks = $this->input->post('remarks');
            $isActive = $this->input->post('is_active');

            $saveContentProviderData = array(
                'company_name' => $company_name,
                'address' => $address,
                'phone' => $phone,
                'mobile' => $mobile,
                'lat' => $lat,
                'lon' => $lon,
                'contact_person_1'=> $contact_person_1,
                'contact_person_1_phone'=> $contact_person_1_phone,
                'contact_person_1_email'=> $contact_person_1_email,
                'contact_person_1_designation'=> $contact_person_1_designation,
                'contact_person_2'=> $contact_person_2,
                'contact_person_2_phone'=> $contact_person_2_phone,
                'contact_person_2_email'=> $contact_person_2_email,
                'contact_person_2_designation'=> $contact_person_2_designation,
                'content_aggregator_type_id'=> $content_aggregator_type,
                'remarks'=> $remarks,
                'is_active'=> $isActive

            );

            $this->content_provider->save($saveContentProviderData);
          
            $this->set_notification('New Content Provider Created','New Content Provider created for company ['.$company_name.']');
            echo json_encode(array('status'=>200,'success_messages'=>'New Content Provider Created created successfully'));
        }
    }


    public function update_content_provider()
    {
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'content-provider', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                exit;
            }
        }

        $id  = $this->input->post('id');
        $content_provider = $this->content_provider->find_by_id($id);
        if(!$content_provider->has_attributes()){
            echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! Content Provider not found"));
            exit;
        }

        $getContentProviderData = $content_provider->get_attributes();

        // print_r( $saveContentProviderData['id']);die();

        $company_name = $this->input->post('company_name');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $mobile = $this->input->post('mobile');
        $lat = $this->input->post('lat');
        $lon = $this->input->post('lon');
        $contact_person_1 = $this->input->post('contact_person_1');
        $contact_person_1_phone = $this->input->post('contact_person_1_phone');
        $contact_person_1_email = $this->input->post('contact_person_1_email');
        $contact_person_1_designation = $this->input->post('contact_person_1_designation');
        $contact_person_2 = $this->input->post('contact_person_2');
        $contact_person_2_phone = $this->input->post('contact_person_2_phone');
        $contact_person_2_email = $this->input->post('contact_person_2_email');
        $contact_person_2_designation = $this->input->post('contact_person_2_designation');
        $content_aggregator_type = $this->input->post('content_aggregator_type_id');
        $remarks = $this->input->post('remarks');
        $isActive = $this->input->post('is_active');

        if(empty($isActive)){
            $this->Iptv_program->update_is_removed($getContentProviderData['id']);
        }


        $saveContentProviderData = array(
            'company_name' => $company_name,
            'address' => $address,
            'phone' => $phone,
            'mobile' => $mobile,
            'lat' => $lat,
            'lon' => $lon,
            'contact_person_1'=> $contact_person_1,
            'contact_person_1_phone'=> $contact_person_1_phone,
            'contact_person_1_email'=> $contact_person_1_email,
            'contact_person_1_designation'=> $contact_person_1_designation,
            'contact_person_2'=> $contact_person_2,
            'contact_person_2_phone'=> $contact_person_2_phone,
            'contact_person_2_email'=> $contact_person_2_email,
            'contact_person_2_designation'=> $contact_person_2_designation,
            'content_aggregator_type_id'=> $content_aggregator_type,
            'remarks'=> $remarks,
            'is_active'=> $isActive

        );

        $this->set_notification('New Content Provider Updated','Content Provider information updated successfully');
        $this->content_provider->save($saveContentProviderData,$getContentProviderData['id']);


        echo json_encode(array('status'=>200,'success_messages'=>'Content Provider information updated successfully'));
    }

    public function ajax_get_content_providers()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
            $take = $this->input->post('take');
            $skip = $this->input->post('skip');
            $filter = $this->input->get('filter');
            $sort = $this->input->post('sort');

            $epgs = $this->content_provider->get_all_content_provider($id,$take,$skip,$filter,$sort);
            $total = $this->content_provider->count_all_content_provider($id,$filter);

            echo json_encode(array('status'=>200,'content_provider'=>$epgs,'total'=>$total));
        }else{
            redirect('/');
        }
    }

    public function delete($id)
    {
        $content_provider = $this->content_provider->find_by_id($id);
        if($content_provider->has_attributes()){
            // $this->epg_repeat_time->remove_by_epg($id);
            $this->content_provider->remove_by_id($id);
        }
    
        echo json_encode(array('status'=>200,'success_messsages'=>'Content Provider successfully Deleted'));

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