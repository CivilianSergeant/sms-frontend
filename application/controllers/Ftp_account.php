<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/29/2016
 * Time: 12:53 PM
 */
class Ftp_account extends BaseController
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

        if($this->user_type != self::MSO_LOWER){
            redirect('/');
        }
    }

    public function index()
    {
        $this->theme->set_title('FTP Account')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/ftp-accounts/ftp_account.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('ftp-accounts/index',$data,true);
    }

    public function ajax_get_permissions()
    {
        if($this->role_type == self::ADMIN){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $permissions = $this->menus->has_permission($this->role_id,1,'ftp-accounts',$this->user_type);
        }
        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function ajax_get_accounts()
    {
        if($this->input->is_ajax_request()){
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $filter = $this->input->get('filter');
            $sort   = $this->input->get('sort');
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;


            $accounts = $this->ftp_account->get_all_accounts($id,$take,$skip,$filter,$sort);
            $total    = $this->ftp_account->count_all_accounts($id,$filter);

            echo json_encode(array('status'=>200,'accounts'=>$accounts,'total'=>$total));
            exit;
        }else{
            redirect('/');
        }
    }


    public function create(){

        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'ftp-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to FTP Accounts"));
                exit;
            }
        }

        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('name','FTP Name','required');
            $this->form_validation->set_rules('server_ip', 'Server IP', 'required');
            $this->form_validation->set_rules('server_port', 'Server Port', 'required');
            $this->form_validation->set_rules('user_id', 'User ID', 'required');
            $this->form_validation->set_rules('password','Password','required');

            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $save_account_data = array(
                'name'         => $this->input->post('name'),
                'server_ip'    => $this->input->post('server_ip'),
                'server_port'  => $this->input->post('server_port'),
                'user_id'      => $this->input->post('user_id'),
                'password'     => $this->input->post('password'),
                'dir_location' => $this->input->post('dir_location')
            );

            $this->ftp_account->save($save_account_data);
            $this->set_notification("FTP Account Created",'FTP Account '.$save_account_data['server_ip'].':'.$save_account_data['server_port'].' has been created');
            echo json_encode(array('status'=>200,'success_messages'=>'FTP Account '.$save_account_data['server_ip'].':'.$save_account_data['server_port'].' has been created successfully'));

        }else{
            redirect('/');

        }
    }

    public function view($id){
        $ftp_account = $this->ftp_account->find_by_id($id);

        if(!$ftp_account->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! No FTP account found or id is invalid');
            redirect('ftp-accounts');
        }

        $this->theme->set_title('View FTP Account')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/ftp-accounts/ftp_account.js');

        $data['user_info'] = $this->user_session;
        $data['id'] = $id;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['id'] = $ftp_account->get_attribute('id');
        $data['theme'] = $this->theme->get_image_path();

        $this->theme->set_view('ftp-accounts/view-account',$data,true);
    }

    public function edit($id){

        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'ftp-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to FTP Account"));
                exit;
            }
        }


        $ftp_account = $this->ftp_account->find_by_id($id);

        if(!$ftp_account->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! No FTP account found or id is invalid');
            redirect('ftp-accounts');
        }

        $this->theme->set_title('Edit Bank Account')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/ftp-accounts/ftp_account.js');
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['id'] = $ftp_account->get_attribute('id');
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('ftp-accounts/edit-account',$data,true);
    }

    public function ajax_get_account_info(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $ftp_account = $this->ftp_account->find_by_id($id);
            echo json_encode(array('status'=>200,'ftp_account'=>$ftp_account->get_attributes()));
        }
    }

    public function update(){
        if($this->role_type == self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'ftp-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to FTP Account"));
                exit;
            }
        }

        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('name','FTP Name','required');
            $this->form_validation->set_rules('server_ip', 'Server IP', 'required');
            $this->form_validation->set_rules('server_port', 'Server Port', 'required');
            $this->form_validation->set_rules('user_id', 'User ID', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            $ftp_account = $this->ftp_account->find_by_id($this->input->post('id'));
            if(!$ftp_account->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Update request cancelled, ftp account not exist'));
                exit;
            }



            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $save_account_data = array(
                'name'         => $this->input->post('name'),
                'server_ip'    => $this->input->post('server_ip'),
                'server_port'  => $this->input->post('server_port'),
                'user_id'      => $this->input->post('user_id'),
                'password'     => $this->input->post('password'),
                'dir_location' => $this->input->post('dir_location'),
                'updated_at'   => date('Y-m-d H:i:s'),
                'updated_by'   => $this->user_id
            );
            $this->ftp_account->save($save_account_data,$ftp_account->get_attribute('id'));
            $this->set_notification("Bank Account Updated",'Bank Account '.$save_account_data['server_ip'].' has been updated successfully');
            echo json_encode(array('status'=>200,'success_messages'=>'Bank Account '.$save_account_data['server_ip'].' has been updated successfully'));

        }else{
            redirect('/');
        }
    }

    public function delete()
    {
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $ftp_account = $this->ftp_account->find_by_id($id);
            if($ftp_account->has_attributes()){
                $server_ip = $ftp_account->get_attribute('server_ip').':'.$ftp_account->get_attribute('server_port');

                $deleted = $this->ftp_account->remove_by_id($id);
                if($deleted){
                    echo json_encode(array('status'=>200,'success_messages'=>'FTP account '.$server_ip.' has been successfully deleted' ));
                    exit;
                }

                echo json_encode(array('status'=>400,'success_messages'=>'Sorry! FTP account has not been deleted, try again later' ));
                exit;

            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'FTP account not found to delete'));
                exit;
            }


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