<?php

/**
 * @property Role_model $role
 * @property Role_menu_privilege_model $role_menu_privilege
 */
class Role extends BaseController
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
	const GROUP_LOWER='group';
	const GROUP = 'Group';
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
		$this->theme->set_title('User Roles')
			->add_style('custom.css')
			->add_style('kendo/css/kendo.common-bootstrap.min.css')
			->add_style('kendo/css/kendo.bootstrap.min.css')
			->add_script('controllers/role/user_role.js');

		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('role/create',$data,true);
	}

	public function ajax_get_permissions()
	{
		if($this->role_type=="admin"){
			$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role,1,'user-role',$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}

	public function ajax_get_roles()
	{
		$take   = $this->input->get('take');
		$skip   = $this->input->get('skip');
		$filter = $this->input->get('filter');
		$sort   = $this->input->get('sort');
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		$roles  = $this->role->get_roles($id,$take,$skip,$filter,$sort);
		$total  = $this->role->get_count_roles($id,$filter);
		echo json_encode(array('status'=>200,'roles'=>$roles,'total'=>$total));
	}

	public function create()
	{
		if($this->input->is_ajax_request())
		{
			$roleName = $this->input->post('role_name');
			$exist = $this->role->find_role_by_name($roleName);
			if(!empty($exist)){
				echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! role name is not available, try another one'));
				exit;
			}

			if($this->role_type == "staff") {
				$permission = $this->menus->has_create_permission($this->role_id, 1, 'user-role', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
					exit;
				}
			}
			switch($this->user_type){
				case self::MSO_LOWER:
					$user_type = self::MSO_UPPER;
					break;
				case self::LCO_LOWER:
					$user_type = self::LCO_UPPER;
					break;
				case self::GROUP_LOWER:
					$user_type = self::GROUP;
					break;
			}

			$save_data = array(
				'role_name' => $this->input->post('role_name'),
				'user_type' => $user_type,
				'role_type' => $this->input->post('role_type'),
				'status' => 1
			);

			$role_id = $this->role->save($save_data);
			$this->set_notification("New User Role Created","New User Role [".$save_data['role_name']."] has been created");
			echo json_encode(array('status'=>200,'role_id'=>$role_id,'success_messages'=>"Role has been created successfully"));

		}else{
			redirect('/');
		}
	}

	public function edit($id)
	{
		if($id == 1){
			$this->session->set_flashdata('warning_messages', "Sorry! You cannot change built-in features");
			redirect('user-role');
		}
		if($this->role_type == "staff") {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'user-role', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('user-role');
			}
		}

		$this->theme->set_title('User Roles')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/role/user_role.js');

		$role = $this->role->find_by_id($id);

		if($role->has_attributes()){
			$data['role'] = $role->get_attributes();
			$data['user_info'] = $this->user_session;
			$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
			$data['theme'] = $this->theme->get_image_path();
			$this->theme->set_view('role/edit',$data,true);
		}else{
			$this->session->set_flashdata("warning_messages","Sorry! No Role Found with");
			redirect('user-role');
		}
	}

	public function ajax_get_role($id)
	{

		if($this->input->is_ajax_request()){


			$role = $this->role->find_by_id($id);
			$role = $role->get_attributes();
			echo json_encode(array('status'=>200,'role'=>$role));
		}
	}

	public function update()
	{
		if($this->input->post())
		{
			if($this->role_type == "staff") {
				$permission = $this->menus->has_edit_permission($this->role_id, 1, 'user-role', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
					exit;
				}
			}

			$role_id = $this->input->post('id');
			$role = $this->role->find_by_id($role_id);
			if($role->has_attributes()){
				$save_data = array(
					'role_name' => $this->input->post('role_name'),
					/*'user_type' => $this->input->post('user_type'),*/
					//'role_type' => $this->input->post('role_type'),
					'updated_at'=> date('Y-m-d H:i:s'),
					'updated_by'=> $this->user_id
				);
				$this->role->save($save_data,$role->get_attribute('id'));
				$this->set_notification("User Role Updated","User Role [".$save_data['role_name']."] has been modified");
				$this->session->set_flashdata('success_messages',"Role updated successfully");
				echo json_encode(array('status'=>200));
				exit;
			}else{
				echo json_encode(array('status'=>400,'warning_messages'=>"Sorry! No Role Found with"));
				exit;
			}
		}else{
			redirect('/');
		}
	}

	public function delete($id)
	{
		$role = $this->role->find_by_id($id);
		$role_id = $role->get_attribute('id');
		if($role_id<=5){
			$this->session->set_flashdata('warning_messages',"Sorry! Built-in role cannot be deleted");
		}

		if(!$role->has_attributes()){
			$this->session->set_flashdata('warning_messages',"Sorry! role not found");
		}

		$isAssigned = $this->role->is_assigned($role->get_attribute('id'));

		if(!empty($isAssigned) && $isAssigned->assigned == 0){
			$permission_deleted =$this->role_menu_privilege->delete_by_role($role);
			$this->role->delete($role);

			$this->session->set_flashdata('success_messages','Role '.$role->get_attribute('role_name'). ' is deleted');
			redirect('user-role');
		}
		redirect('user-role');

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