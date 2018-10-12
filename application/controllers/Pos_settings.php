<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/15/2016
 * Time: 4:36 PM
 */
class Pos_settings extends BaseController
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
    const ROUTE = 'pos-settings';

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

        if($this->user_type == self::LCO_LOWER){
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
        }

    }

    public function ajax_get_permissions()
    {
        if($this->role_type == self::ADMIN){
            $permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
        }else{
            $role = $this->user_session->role_id;
            $permissions = $this->menus->has_permission($role,1,self::ROUTE,$this->user_type);

        }
        echo json_encode(array('status'=>200,'permissions'=>$permissions));
    }

    public function index()
    {
        $this->theme->set_title('Pos Settings')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/pos/pos.js');
        $data['user_id']      = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('pos/index', $data, true);
    }

    public function create()
    {
        if($this->role_type==self::STAFF) {
            $permission = $this->menus->has_create_permission($this->role_id, 1, self::ROUTE, $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to POS"));
                exit;
            }
        }

        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('bank_account_id','Bank Account','required');
            $this->form_validation->set_rules('collector_id','Collector','required');
            $this->form_validation->set_rules('pos_machine_id','Pos Machine no','required|is_unique[pos_machines.pos_machine_id]');

            if($this->form_validation->run() == FALSE){
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $save_data = array(
                'bank_account_id' => $this->input->post('bank_account_id'),
                'collector_id'    => $this->input->post('collector_id'),
                'pos_machine_id'  => $this->input->post('pos_machine_id'),
                'charge_interest' => $this->input->post('charge_interest'),
                'token'           => md5($this->input->post('pos_machine_id'))
            );

            $this->pos->save($save_data);

            echo json_encode(array('status'=>200,'success_messages'=>'Pos ['.$save_data['pos_machine_id'].'] created successfully'));

        }else{
            redirect('/');
        }
    }

    public function view($token)
    {
        $this->theme->set_title('View Pos Settings')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/pos/pos.js');
        $data['token']        = $token;
        $data['user_id']      = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('pos/view', $data, true);
    }

    public function update()
    {
        if($this->role_type==self::STAFF) {
            $permission = $this->menus->has_edit_permission($this->role_id, 1, self::ROUTE, $this->user_type);
            if (!$permission) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to POS"));
                exit;
            }
        }

        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('bank_account_id','Bank Account','required');
            $this->form_validation->set_rules('collector_id','Collector','required');
            $this->form_validation->set_rules('pos_machine_id','Pos Machine no','required');

            if($this->form_validation->run() == FALSE){
                echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
                exit;
            }

            $token = $this->input->post('token');

            $pos = $this->pos->find_by_token($token);
            if(!$pos->has_attributes()){
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Pos Machine not found'));
                exit;
            }

            $save_data = array(
                'bank_account_id' => $this->input->post('bank_account_id'),
                'collector_id'    => $this->input->post('collector_id'),
                'pos_machine_id'  => $this->input->post('pos_machine_id'),
                'charge_interest' => $this->input->post('charge_interest'),

            );

            $this->pos->save($save_data,$pos->get_attribute('id'));

            echo json_encode(array('status'=>200,'success_messages'=>'Pos ['.$save_data['pos_machine_id'].'] updated successfully'));

        }else{
            redirect('/');
        }
    }

    public function ajax_get_pos_machines()
    {
        $take   = $this->input->get('take');
        $skip   = $this->input->get('skip');
        $filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');

        $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $pos_machines = $this->pos->get_pos_machines($id, $take,$skip,$filter,$sort);
        echo json_encode(array(
            'status'=>200,
            'pos_machines' => $pos_machines,
            'total'   => $this->pos->get_count_pos_machines($id,$filter)
        ));
    }

    public function edit($token)
    {
        $this->theme->set_title('Pos Settings')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/pos/pos.js');
        $data['token']       = $token;
        $data['user_id']      = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('pos/edit', $data, true);
    }

    public function ajax_get_pos_machine($token)
    {
        $pos = $this->pos->find_by_token($token);
        if(!$pos->has_attributes()){
            echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Pos machine not found or invalid request'));
            exit;
        }

        echo json_encode(array('status'=>200,'pos'=>$pos->get_attributes()));
    }
}