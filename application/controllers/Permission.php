<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property Role_model $role
 */
class Permission extends BaseController
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

        if($this->user_type == self::LCO_LOWER){
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);

        }

    }

    public function index($id=null)
    {
        $this->theme->set_title('Permissions')
            ->add_style('component.css')
            ->add_script('controllers/permission/permission.js');

        if($id != null){
            $role = $this->role->find_by_id($id);

            $data['user_role'] = $role->get_attribute('id');
        }else{
            $data['user_role'] = 0;
        }

        $data['user_info']  = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('permission/permission',$data,true);
    }

    public function ajax_get_roles()
    {

        $roles = $this->role->get_role_for_permission($this->role_type,$this->user_type);

        echo json_encode(array('status'=>200,'roles'=>$roles));
    }

    public function ajax_get_menu_routes()
    {
        $role_id = $this->input->post('role_id');
        $role = $this->role->find_by_id($role_id);
        $role = $role->get_attributes();
        $user_type = $role['user_type'];
        $role_type = $role['role_type'];
        $permission_exist = $this->role_menu_privilege->has_permission($role_id);

        $permission_editable = 0;
        if($this->role_type == $role['role_type'] && strtoupper($this->user_type) == $role['user_type']){
            $permission_editable = 1;
        }
        $parent_routes = [];

        //$parent = $this->user->find_by_id($this->parent_id);

        $parent_permission_exist = $this->role_menu_privilege->has_permission($this->role_id);
        if($parent_permission_exist){
            $parent_routes = $this->menus->get_menus($this->role_id,self::LCO_UPPER);
        }

        if($permission_exist){

            $menu_routes = $this->menus->get_menus($role_id,$user_type);
        }else{
            $menu_routes = $this->menus->get_menu_routes_un_assigned($role_id,$user_type);
        }
        echo json_encode(array('status'=>200,'parent_routes'=>$parent_routes,'role_type'=>$role_type,'user_type'=>$this->user_type,'menu_routes'=>$menu_routes,'role'=>$role,'permission_editable'=>$permission_editable));

    }

    public function toggle()
    {
        if($this->input->is_ajax_request()){

            $sub_menus    = $this->input->post('sub_menus');
            $role_id      = $this->input->post('role_id');
            $main_menu_id = $this->input->post('main_menu_id');
            $permission   = $this->input->post('permission');
            $create_permission   = $this->input->post('create_permission');
            $edit_permission     = $this->input->post('edit_permission');
            $delete_permission   = $this->input->post('delete_permission');

            if(!empty($sub_menus)){
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);

                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'view' => $permission,
                    'create'=>$create_permission,
                    'edit' => $edit_permission,
                    'delete'=>$delete_permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }

                // set permission to submenu
                foreach($sub_menus as $menu){
                    $exist = $this->role_menu_privilege->is_permission_exist($role_id,$menu['id']);
                    $save_permission_data = array(
                        'role_id' => $role_id,
                        'module_id' => 1,
                        'menu_id' => $menu['id'],
                        'view' => $menu['permission'],
                        'create'=>$menu['create_permission'],
                        'edit' => $menu['edit_permission'],
                        'delete'=>$menu['delete_permission']
                    );

                    if(!empty($exist)){
                        $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                        $save_permission_data['updated_by'] = $this->user_id;
                        $this->role_menu_privilege->save($save_permission_data,$exist->id);
                    }else{
                        $this->role_menu_privilege->save($save_permission_data);
                    }
                }
            }else{
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);
                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'view' => $permission,
                    'create'=>$create_permission,
                    'edit'=>$edit_permission,
                    'delete'=>$delete_permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }
            }
            echo json_encode(array('status'=>200,'success_messages'=>'Permission successfully changed'));
        }else{
            redirect('/');
        }
    }

    public function toggle_create()
    {
        if($this->input->is_ajax_request()){

            $sub_menus    = $this->input->post('sub_menus');
            $role_id      = $this->input->post('role_id');
            $main_menu_id = $this->input->post('main_menu_id');
            $permission   = $this->input->post('create_permission');

            if(!empty($sub_menus)){
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);

                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'create' => $permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }

                // set permission to submenu
                foreach($sub_menus as $menu){
                    $exist = $this->role_menu_privilege->is_permission_exist($role_id,$menu['id']);
                    $save_permission_data = array(
                        'role_id' => $role_id,
                        'module_id' => 1,
                        'menu_id' => $menu['id'],
                        'create' => $menu['create_permission']
                    );
                    if(!empty($exist)){
                        $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                        $save_permission_data['updated_by'] = $this->user_id;
                        $this->role_menu_privilege->save($save_permission_data,$exist->id);
                    }else{
                        $this->role_menu_privilege->save($save_permission_data);
                    }
                }
            }else{
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);
                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'create' => $permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }
            }
            echo json_encode(array('status'=>200,'success_messages'=>'Permission successfully changed'));
        }else{
            redirect('/');
        }
    }

    public function toggle_edit()
    {
        if($this->input->is_ajax_request()){

            $sub_menus    = $this->input->post('sub_menus');
            $role_id      = $this->input->post('role_id');
            $main_menu_id = $this->input->post('main_menu_id');
            $permission   = $this->input->post('edit_permission');

            if(!empty($sub_menus)){
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);

                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'edit' => $permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }

                // set permission to submenu
                foreach($sub_menus as $menu){
                    $exist = $this->role_menu_privilege->is_permission_exist($role_id,$menu['id']);
                    $save_permission_data = array(
                        'role_id' => $role_id,
                        'module_id' => 1,
                        'menu_id' => $menu['id'],
                        'edit' => $menu['edit_permission']
                    );
                    if(!empty($exist)){
                        $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                        $save_permission_data['updated_by'] = $this->user_id;
                        $this->role_menu_privilege->save($save_permission_data,$exist->id);
                    }else{
                        $this->role_menu_privilege->save($save_permission_data);
                    }
                }
            }else{
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);
                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'edit' => $permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }
            }
            echo json_encode(array('status'=>200,'success_messages'=>'Permission successfully changed'));
        }else{
            redirect('/');
        }
    }

    public function toggle_delete()
    {
        if($this->input->is_ajax_request()){

            $sub_menus    = $this->input->post('sub_menus');
            $role_id      = $this->input->post('role_id');
            $main_menu_id = $this->input->post('main_menu_id');
            $permission   = $this->input->post('delete_permission');

            if(!empty($sub_menus)){
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);

                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'delete' => $permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }

                // set permission to submenu
                foreach($sub_menus as $menu){
                    $exist = $this->role_menu_privilege->is_permission_exist($role_id,$menu['id']);
                    $save_permission_data = array(
                        'role_id' => $role_id,
                        'module_id' => 1,
                        'menu_id' => $menu['id'],
                        'delete' => $menu['delete_permission']
                    );
                    if(!empty($exist)){
                        $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                        $save_permission_data['updated_by'] = $this->user_id;
                        $this->role_menu_privilege->save($save_permission_data,$exist->id);
                    }else{
                        $this->role_menu_privilege->save($save_permission_data);
                    }
                }
            }else{
                $main_permission_exist=$this->role_menu_privilege->is_permission_exist($role_id,$main_menu_id);
                $save_permission_data = array(
                    'role_id' => $role_id,
                    'module_id' => 1,
                    'menu_id' => $main_menu_id,
                    'delete' => $permission
                );

                if(!empty($main_permission_exist)){
                    $save_permission_data['updated_at'] = date('Y-m-d H:i:s');
                    $save_permission_data['updated_by'] = $this->user_id;
                    $this->role_menu_privilege->save($save_permission_data,$main_permission_exist->id);
                }else{
                    $this->role_menu_privilege->save($save_permission_data);
                }
            }
            echo json_encode(array('status'=>200,'success_messages'=>'Permission successfully changed'));
        }else{
            redirect('/');
        }
    }
}