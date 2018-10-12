<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/9/2016
 * Time: 1:49 PM
 */
class Bank_account extends BaseController
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



    }

    public function index()
    {
        $this->theme->set_title('Bank Accounts')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/bank-account/account.js');
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('bank-accounts/account',$data,true);
    }

    public function ajax_get_permissions()
    {
        if($this->role_type == "admin"){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $permissions = $this->menus->has_permission($this->role_id,1,'bank-accounts',$this->user_type);
        }
        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function ajax_get_lco(){
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $all_lco = $this->lco_profile->get_lco_users($id);
        echo json_encode(array('status'=>200,'lco_profiles'=>$all_lco));
    }

    public function ajax_get_accounts()
    {
        $take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

        $types = $this->payment_type->get_all();
        if($this->user_type != self::LCO_LOWER) {
            echo json_encode(array(
                'accounts' => $this->bank_account->get_all_accounts($id, $take, $skip, $filter, $sort),
                'payment_types' => $types,
                'total' => $this->bank_account->get_count_accounts($id, $filter),
                'status' => 200,

            ));
        }else{
            $accounts = $this->bank_account->get_all_lco_accounts($id,$take,$skip,$filter,$sort);

            echo json_encode(array(
                'accounts' => $accounts,
                'payment_types' => $types,
                'total'    => $this->bank_account->get_count_lco_accounts($id,$filter),
                'status' => 200,

            ));
        }

    }

    public function create(){

        if($this->role_type == "staff") {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'bank-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to Bank Account"));
                exit;
            }
        }

        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('account_no', 'Account Number', 'required|is_unique[bank_accounts.account_no]');
            $this->form_validation->set_rules('account_name', 'Account Name', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');

            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $save_account_data = array(
                'bank_name'    => $this->input->post('bank_name'),
                'account_name' => $this->input->post('account_name'),
                'account_no'   => $this->input->post('account_no'),
                'address'      => $this->input->post('address'),
                'token'        => md5($this->input->post('account_no'))
            );
            $this->bank_account->save($save_account_data);
            $this->set_notification("Bank Account Created",'Bank Account '.$save_account_data['bank_name'].' has been created');
            echo json_encode(array('status'=>200,'success_messages'=>'Bank Account '.$save_account_data['bank_name'].' has been created successfully'));

        }else{
            redirect('/');

        }
    }

    public function view($token){
        $bank_account = $this->bank_account->find_by_token($token);

        if(!$bank_account->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! No Bank account found or token is invalid');
            redirect('bank-accounts');
        }

        $this->theme->set_title('View Bank Account')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/bank-account/account.js');
        $data['user_info'] = $this->user_session;
        $data['token'] = $token;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['token'] = $bank_account->get_attribute('token');
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('bank-accounts/view-account',$data,true);
    }

    public function ajax_get_bank_account_details($token)
    {
        if($this->input->is_ajax_request()){

            $account = $this->bank_account->find_by_token($token);

            if(!$account->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No account found or invalid token'));
                exit;
            }
            $account_details = $this->bank_account->get_account_details($account);
            echo json_encode(array('status'=>200,'account'=>$account->get_attributes(),'account_details'=>$account_details));
        }
    }

    public function edit($token){

        if($this->role_type == "staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'bank-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to Bank Account"));
                exit;
            }
        }



        $bank_account = $this->bank_account->find_by_token($token);

        if($this->user_type == self::LCO_LOWER) {
            $shared = $this->bank_account_assign->is_shared($bank_account->get_attribute('id'));
            if ($shared) {
                $this->session->set_flashdata('warning_messages', 'Sorry! You cannot edit Shared Account');
                redirect('bank-accounts');
            }
        }

        if(!$bank_account->has_attributes()){
            $this->session->set_flashdata('warning_messages','Sorry! No Bank account found or token is invalid');
            redirect('bank-accounts');
        }

        $this->theme->set_title('Edit Bank Account')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/bank-account/account.js');
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['token'] = $bank_account->get_attribute('token');
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('bank-accounts/edit-account',$data,true);
    }

    public function ajax_get_account_info(){
        if($this->input->is_ajax_request()){
            $token = $this->input->post('token');
            $bank_account = $this->bank_account->find_by_token($token);
            echo json_encode(array('status'=>200,'bank_account'=>$bank_account->get_attributes()));
        }
    }

    public function update(){
        if($this->role_type == "staff") {
            $permission = $this->menus->has_create_permission($this->role_id, 1, 'bank-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to Bank Account"));
                exit;
            }
        }

        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('account_no', 'Account Number', 'required');
            $this->form_validation->set_rules('account_name', 'Account Name', 'required');
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required');

            $bank_account = $this->bank_account->find_by_token($this->input->post('token'));
            if(!$bank_account->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Update request cancelled, bank account not exist'));
                exit;
            }



            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $save_account_data = array(
                'bank_name'    => $this->input->post('bank_name'),
                'account_name' => $this->input->post('account_name'),
                'account_no'   => $this->input->post('account_no'),
                'address'      => $this->input->post('address'),
                'updated_at'   => date('Y-m-d H:i:s'),
                'updated_by'   => $this->user_id
            );
            $this->bank_account->save($save_account_data,$bank_account->get_attribute('id'));
            $this->set_notification("Bank Account Updated",'Bank Account '.$save_account_data['bank_name'].' has been updated');
            echo json_encode(array('status'=>200,'success_messages'=>'Bank Account '.$save_account_data['bank_name'].' has been updated successfully'));

        }else{
            redirect('/');
        }
    }


    public function share(){

        if(in_array($this->user_type,array('lco','subscriber'))){
            redirect('bank-accounts');
        }

        if($this->role_type == "staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'bank-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to Bank Account"));
                exit;
            }
        }

        $this->theme->set_title('Share Bank Account')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/bank-account/account.js');
        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('bank-accounts/share-account',$data,true);
    }

    public function share_account(){

        if($this->role_type == "staff") {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, 'bank-accounts', $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to Bank Account"));
                exit;
            }
        }

        if($this->input->is_ajax_request()){
            $account_no = $this->input->post('account_no');
            $lco_name   = $this->input->post('lco_name');
            $save_assign_data = array(

                'bank_account_id' => $this->input->post('bank_account_id'),
                'lco_id'          => $this->input->post('lco_user_id')
            );
            //test($save_assign_data);
            $id = $this->input->post('id');
            if($id != null){
                $this->bank_account_assign->save($save_assign_data,$id);
            }else{
                $id = $this->bank_account_assign->save($save_assign_data);
            }

            echo json_encode(array('status'=>200,'id'=>$id,'success_messages'=>'Account ['.$account_no.'] has been shared with LCO ['.$lco_name.']'));
        }
    }

    public function ajax_get_shared_accounts()
    {
        if($this->input->is_ajax_request()){
            $shared_accounts = $this->bank_account->get_shared_accounts();
            echo json_encode(array('status'=>200,'shared_accounts'=>$shared_accounts));
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