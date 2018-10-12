<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 9/21/2016
 * Time: 5:37 PM
 * @property Iptv_program_model $Iptv_program
 */
class Feature_content extends BaseController
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

    public function ajax_get_permissions()
    {
        if($this->input->is_ajax_request()){
            if($this->role_type == self::ADMIN){
                $permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
            }else{
                $role = $this->user_session->role_id;
                $uri = $this->uri->segment(1);

                $permissions = $this->menus->has_permission($role,1,$uri,$this->user_type);
            }
            echo json_encode(array('status'=>200,'permissions'=>$permissions));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_channel_programs()
    {
        if($this->input->is_ajax_request()) {

            $id = ($this->role_type == self::STAFF) ? $this->parent_id : $this->user_id;
            $filter = array(
                'filters' => array(array(
                    'operator' => null,
                    'field' => 'featured',
                    'value' => 0
                ))
            );
            $programs = $this->Iptv_program->get_live_delay_programs($id, 0, 0, $filter);

            $filter = array(
                'filters' => array(
                    array(
                        'operator' => null,
                        'field' => 'featured',
                        'value' => 1
                    )
                )
            );

            $featured_programs = $this->Iptv_program->get_live_delay_programs($id, 0, 0, $filter);
            echo json_encode(array('status' => 200, 'programs' => $programs,'featured_programs'=>$featured_programs));


        }else{
            redirect('/');
        }
    }

    public function ajax_get_catchup_contents()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF) ? $this->parent_id : $this->user_id;
            $filter = array(
                'filters' => array(array(
                    'operator' => null,
                    'field' => 'featured',
                    'value' => 0
                ))
            );
            $programs = $this->Iptv_program->get_catchup_programs($id, 0, 0, $filter);

            $filter = array(
                'filters' => array(
                    array(
                        'operator' => null,
                        'field' => 'featured',
                        'value' => 1
                    )
                )
            );

            $featured_programs = $this->Iptv_program->get_catchup_programs($id, 0, 0, $filter);

            echo json_encode(array('status' => 200, 'programs' => $programs,'featured_programs'=>$featured_programs));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_vod_contents()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF) ? $this->parent_id : $this->user_id;
            $filter = array(
                'filters' => array(array(
                    'operator' => null,
                    'field' => 'featured',
                    'value' => 0
                ))
            );
            $programs = $this->Iptv_program->get_vod_programs($id, 0, 0, $filter);

            $filter = array(
                'filters' => array(
                    array(
                        'operator' => null,
                        'field' => 'featured',
                        'value' => 1
                    )
                )
            );
            $featured_programs = $this->Iptv_program->get_vod_programs($id, 0, 0, $filter);
            echo json_encode(array('status' => 200, 'programs' => $programs,'featured_programs'=>$featured_programs));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_featured_channel_programs()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF) ? $this->parent_id : $this->user_id;
            $filter = array(
                'filters' => array(
                    array(
                        'operator' => null,
                        'field' => 'featured',
                        'value' => 1
                    )
                )
            );

            $programs = $this->Iptv_program->get_live_delay_programs($id, 0, 0, $filter);
            echo json_encode(array('status' => 200, 'programs' => $programs));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_featured_catchup_contents()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF) ? $this->parent_id : $this->user_id;
            $filter = array(
                'filters' => array(
                    array(
                        'operator' => null,
                        'field' => 'featured',
                        'value' => 1
                    )
                )
            );

            $programs = $this->Iptv_program->get_catchup_programs($id, 0, 0, $filter);
            echo json_encode(array('status' => 200, 'programs' => $programs));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_featured_vod_contents()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF) ? $this->parent_id : $this->user_id;
            $filter = array(
                'filters' => array(
                    array(
                        'operator' => null,
                        'field' => 'featured',
                        'value' => 1
                    )
                )
            );
            $programs = $this->Iptv_program->get_vod_programs($id, 0, 0, $filter);
            echo json_encode(array('status' => 200, 'programs' => $programs));
        }else{
            redirect('/');
        }
    }

    public function index()
    {
        $this->theme->set_title('Feature Content')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/feature/content/add.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('feature/content/index',$data,true);
    }

    public function ajax_get_feature_contents()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF) ? $this->parent_id : $this->user_id;
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $filter = $this->input->get('filter');
            $sort = $this->input->get('sort');
            $featured_programs = $this->Iptv_program->get_featured_programs($id, $take, $skip, $filter, $sort);
            $total = $this->Iptv_program->count_featured_programs($id, $filter);
            echo json_encode(array('status' => 200, 'featured_programs' => $featured_programs, 'total' => $total));
        }else{
            redirect('/');
        }
    }

    public function save_feature_content()
    {
        if($this->input->is_ajax_request()){
            $contents = $this->input->post('contents');
            if(!empty($contents)){
                foreach($contents as $content){
                    $this->Iptv_program->set_as_featured($content);
                }

                echo json_encode(array('status'=>200,'success_messages'=>'Feature content successfully saved'));
            }
        }else{
            redirect('/');
        }

    }

    public function set_as_normal_content()
    {
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            $updated = $this->Iptv_program->set_as_normal($id);
            if($updated){
                echo json_encode(array('status'=>200,'success_messages'=>'Content information changed'));
            }else{
                echo json_encode(array('status'=>400,'warning_messages'=> 'Sorry! Unable to change content information'));
            }
        }else{
            redirect('/');
        }
    }

}