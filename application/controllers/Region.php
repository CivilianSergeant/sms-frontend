<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Region extends BaseController
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
		$this->theme->set_theme('katniss');
		$this->theme->set_layout('main');

		$this->user_type = strtolower($this->user_session->user_type);
		$this->user_id = $this->user_session->id;
		$this->created_by = $this->user_session->created_by;

		$role = $this->user->get_user_role($this->user_id);
		$role_name = (!empty($role))?  strtolower($role->role_name) : '';
		$role_type = (!empty($role))?  strtolower($role->role_type) : '';
		$this->role_name = $role_name;
		$this->role_type = $role_type;
		$this->role_id = $this->user_session->role_id;

		if(in_array($this->user_type,array('lco','subscriber'))){
			redirect('/');
		}

	}

	public function index()
	{
		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
					->add_script('controllers/region.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('region/index',$data,true);

	}

	public function create()
	{
		if ($this->input->is_ajax_request()) {
			$name = $this->input->post('name');
			
			$region_id = $this->input->post('region_id');
			$type = substr_count($region_id, '-0');

			if($this->role_type == self::STAFF) {
				$permission = $this->menus->has_create_permission($this->role_id, 1, 'region', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
					exit;
				}
			}

			switch ($type) {
				
				case 1:
					$result = $this->region_level_four->get_auto_id();
					$autoid = $result->autoid;
					$regions = explode("-",$region_id);
					$hex = region_code_generator($regions[0],$regions[1],$regions[2],$autoid);
					$save_data['id'] = $autoid;
					$save_data['region_l4_name'] = $name;
					$save_data['region_l1_code'] = $regions[0];
					$save_data['region_l2_code'] = $regions[1];
					$save_data['region_l3_code'] = $regions[2];
					$save_data['region_l4_code'] = $autoid;
					$save_data['hex_code'] = $hex;
					//$save_data['created_by'] = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
					$this->region_level_four->save($save_data);
					break;

				case 2:

					$result = $this->region_level_three->get_auto_id();
					$autoid = $result->autoid;
					$regions = explode("-",$region_id);
					$hex = region_code_generator($regions[0],$regions[1],$autoid,0);
					$save_data['id'] = $autoid;
					$save_data['region_l3_name'] = $name;
					$save_data['region_l1_code'] = $regions[0];
					$save_data['region_l2_code'] = $regions[1];
					$save_data['region_l3_code'] = $autoid;
					$save_data['hex_code'] = $hex;
					//$save_data['created_by'] = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
					$this->region_level_three->save($save_data);
					break;

				case 3:
					$result = $this->region_level_two->get_auto_id();
					$autoid = $result->autoid;
					$regions = explode("-",$region_id);
					$hex = region_code_generator($regions[0],$autoid,0,0);
					$save_data['id'] = $autoid;
					$save_data['region_l2_name'] = $name;
					$save_data['region_l1_code'] = $regions[0];
					$save_data['region_l2_code'] = $autoid;
					$save_data['hex_code'] = $hex;
					//$save_data['created_by'] = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
					$this->region_level_two->save($save_data);
					break;

				default:
					$result = $this->region_level_one->get_auto_id();
					$autoid = $result->autoid;
					$hex = region_code_generator($autoid,0,0,0);
					$save_data['id'] = $autoid;
					$save_data['region_l1_name'] = $name;
					$save_data['region_l1_code'] = $autoid;
					$save_data['hex_code'] = $hex;
					//$save_data['created_by'] = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
					$this->region_level_one->save($save_data);
					break;
			}
		}
		$this->notification->save_notification(null,"New Business Region Created","New business region {$name} created by MSO",$this->user_session->id);
		echo json_encode(array('status'=>200,'regions'=>$this->region_level_one->get_regions(),'success_messages'=>'New region created successfully'));

	}

	public function update()
	{
		if ($this->input->is_ajax_request()) {

			$name = $this->input->post('name');
			$id   = $this->input->post('id');
			$region_id = $this->input->post('region_id');
			$type = substr_count($region_id, '0');

			if($this->role_type == self::STAFF) {
				$permission = $this->menus->has_edit_permission($this->role_id, 1, 'region', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
					exit;
				}
			}

			switch ($type) {
				case 1:
					$save_data['region_l3_name'] = $name;
					$this->region_level_three->save($save_data,$id);
					break;
				case 2:
					$save_data['region_l2_name'] = $name;
					$this->region_level_two->save($save_data,$id);
					break;
				case 3:
					$save_data['region_l1_name'] = $name;
					$this->region_level_one->save($save_data,$id);
					break;
				default:
					$save_data['region_l4_name'] = $name;
					$this->region_level_four->save($save_data,$id);
					break;
			}
		}
		$this->notification->save_notification(null,"Business Region Updated","Business region renamed to {$name} by MSO",$this->user_session->id);
		echo json_encode(array('status'=>200,'regions'=>$this->region_level_one->get_regions()));
	}


	public function load_tree()
	{
		if ($this->input->is_ajax_request()) {
			$role_id = $this->user_session->role_id;
			if($this->role_type == self::STAFF) {
				$permissions = $this->menus->has_permission($role_id, 1, 'region', $this->user_type);
			}else{
				$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
			}
			//test($permissions);
			$regions = $this->region_level_one->get_regions();
			echo json_encode(array(
				'status'=>200,
				'regions'=>$regions,
				'permissions' => $permissions
			));
		} else {
			redirect('region');
		}
	}

	
}