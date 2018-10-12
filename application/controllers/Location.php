<?php
class Location extends BaseController
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
		$this->theme->set_title('Geo Location - Application')
		->add_style('component.css')
		->add_script('controllers/org.js');

		$role_id = $this->user_session->role_id;
		if($this->role_type == "admin") {
			$data['permissions'] = (object)array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{
			$data['permissions'] = $this->menus->has_permission($role_id, 1, 'location', $this->user_type);
		}
		$data['user_info']     = $this->user_session;	
		$data['left_sidebar']  = $this->theme->set_sidebar('left',$data);
		$data['countries']     = $this->country->get_all();
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('location/location',$data,true);
	}

	public function get_tree_view()
	{
		$data['countries']     = $this->country->get_all();
		$this->theme->set_view('location/location-item-block',$data,false);
	}

	public function save_location()
	{

		if ($this->input->post()) {
			
			$country_name      = $this->input->post('country_name');
			$division_name     = $this->input->post('division_name');
			$district_name     = $this->input->post('district_name');
			$area_name         = $this->input->post('area_name');
			$sub_area_name     = $this->input->post('sub_area_name');
			$sub_sub_area_name = $this->input->post('sub_sub_area_name');
			$road_name         = $this->input->post('road_name');

			$country_id        = $this->input->post('country_id');
			$division_id       = $this->input->post('division_id');
			$district_id       = $this->input->post('district_id');
			$area_id           = $this->input->post('area_id');
			$sub_area_id       = $this->input->post('sub_area_id');
			$sub_sub_area_id   = $this->input->post('sub_sub_area_id');
			$road_id           = $this->input->post('road_id');
			if($this->role_type == "staff") {
				$permission = $this->menus->has_create_permission($this->role_id, 1, 'location', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
					exit();
				}
			}
			// country
			if (!empty($country_name)) {
				$this->form_validation->set_rules('country_name','Country Name','required|is_unique[countries.country_name]');
				if ($this->form_validation->run() == False) {
					echo json_encode(array('status'=>400,
							'warning_messages'=>'Sorry ! Country Name '.$country_name.' already exist'));
					exit;
				}

				$country_insert_id = $this->country->save(array(
					'country_name'=>$country_name,
					'country_code'=>strtoupper(substr($country_name,0,3)),
					//'created_by'  => ($this->role_type == self::STAFF)? $this->created_by : $this->user_id
				));

				$this->notification->save_notification(null,"New Location Created","New Location Created [Country:{$country_name}]",$this->user_session->id);

				echo json_encode(array('status'=>200,'type'=>'country','id'=>$country_insert_id,'success_messages'=>'Country '.$country_name. ' Created Successfully'));
				exit();

			}

			// division
			if (!empty($division_name)) {
				$this->form_validation->set_rules('division_name','Division Name','required|is_unique[divisions.division_name]');
				if ($this->form_validation->run() == False) {
					echo json_encode(array('status'=>400,'warning_messages'=>'Sorry ! Division Name '.$country_name.' already exist'));
					exit;
				}

				$division_insert_id = $this->division->save(array(
					'division_name'=>$division_name,
					'division_code'=>strtoupper(substr($division_name,0,3)),
					'country_id'   => $country_id,
					//'created_by'   => ($this->role_type == self::STAFF)? $this->created_by : $this->user_id
				));
				$this->notification->save_notification(null,"New Location Created","New Location Created [Division:{$division_name}]",$this->user_session->id);

				echo json_encode(array('status'=>200,'type'=>'division','id'=>$division_insert_id,'success_messages'=>'Division '.$division_name. ' Created Successfully'));
				exit();

			}

			// district
			if (!empty($district_name)) {
				$this->form_validation->set_rules('district_name','District Name','required|is_unique[districts.district_name]');
				if ($this->form_validation->run() == False) {
					echo json_ecode(array('status'=>400,'warning_messages'=>'Sorry ! District Name '.$district_name.' already exist'));
					exit;
				}
				$district_insert_id = $this->district->save(array(
					'district_name' => $district_name,
					'district_code' => strtoupper(substr($district_name,0,3)),
					'country_id'    => $country_id,
					'division_id'   => $division_id,
					//'created_by'    => ($this->role_type == self::STAFF)? $this->created_by : $this->user_id
				));
				$this->notification->save_notification(null,"New Location Created","New Location Created [District:{$district_name}]",$this->user_session->id);

				echo json_encode(array('status'=>200,'type'=>'district','id'=>$district_insert_id,'success_messages'=>'District '.$district_name.' Created Successfully'));
				exit();
			}

			// area
			if (!empty($area_name)) {
				$this->form_validation->set_rules('area_name','Area Name','required|is_unique[areas.area_name]');
				if ($this->form_validation->run() == False) {
					echo json_encode(array('status'=>400,'warning_messages'=>'Sorry ! Area Name '.$area_name.' already exist'));
					exit;
				}

				$area_insert_id = $this->area->save(array(
						'area_name'  => $area_name,
						'area_code'  => strtoupper(substr($area_name,0,3)),
						'country_id' => $country_id,
						'division_id'=> $division_id,
						'district_id'=> $district_id,
						//'created_by' => ($this->role_type == self::STAFF)? $this->created_by : $this->user_id
				));

				$this->notification->save_notification(null,"New Location Created","New Location Created [Area:{$area_name}]",$this->user_session->id);

				echo json_encode(array('status'=>200,'type'=>'area','id'=>$area_insert_id,'success_messages'=>'Area '.$area_name.' Created Successfully'));
				exit();

			}

			// sub area
			if (!empty($sub_area_name)) {
				$this->form_validation->set_rules('sub_area_name','Sub Area Name','required|is_unique[sub_areas.sub_area_name]');
				if ($this->form_validation->run() == False) {
					echo json_encode(array('status'=>400,'warning_messages'=>'Sorry ! Sub Area Name '.$sub_area_name.' already exist'));
					exit;
				}

				$sub_area_insert_id = $this->sub_area->save(array(
					'sub_area_name' => $sub_area_name,
					'sub_area_code' => strtoupper(substr($sub_area_name,0,3)),
					'country_id'    => $country_id,
					'division_id'   => $division_id,
					'district_id'   => $district_id,
					'area_id'       => $area_id,
					//'created_by'    => ($this->role_type == self::STAFF)? $this->created_by : $this->user_id
				));

				$this->notification->save_notification(null,"New Location Created","New Location Created [Sub Area:{$sub_area_name}]",$this->user_session->id);

				echo json_encode(array('status'=>200,'type'=>'sub_area','id'=>$sub_area_insert_id,'success_messages'=>'Sub Area '.$sub_area_name.' Created Successfully'));
				exit();


			}
			
			// sub sub area
			/*if (!empty($sub_sub_area_name)) {
				$this->form_validation->set_rules('sub_sub_area_name','Sub Area Name','required|is_unique[sub_sub_areas.sub_sub_area_name]');
				if ($this->form_validation->run() == False) {
					$this->session->set_flashdata('warning_messages','Sorry ! '.$sub_sub_area_name.' already exist');
					redirect('location');
				}
				$this->sub_sub_area->save(array(
						'sub_sub_area_name' => $sub_sub_area_name,
						'sub_sub_area_code' => strtoupper(substr($sub_sub_area_name,0,3)),
						'country_id'        => $country_id,
						'division_id'       => $division_id,
						'district_id'       => $district_id,
						'area_id'           => $area_id,
						'sub_area_id'       => $sub_area_id,
						'created_by'        => ($this->role_type == self::STAFF)? $this->created_by : $this->user_id
					));

				$this->session->set_flashdata('success_messages','Sub Area of Sub Area '.$sub_area_name.' Created Successfully');
				redirect('location');
			}*/

			// road
			if (!empty($road_name)) {
				$this->form_validation->set_rules('road_name','Road Name','required|is_unique[roads.road_name]');
				if ($this->form_validation->run() == False) {
					echo json_encode(array('status'=>400,'warning_messages'=>'Sorry ! '.$road_name.' already exist'));
					exit;
				}

				$road_id = $this->road->save(array(
					'road_name'       => $road_name,
					'road_code'       => strtoupper(substr($road_name,0,3)),
					'country_id'      => $country_id,
					'division_id'     => $division_id,
					'district_id'     => $district_id,
					'area_id'         => $area_id,
					'sub_area_id'     => $sub_area_id,

					//'created_by'      => ($this->role_type == self::STAFF)? $this->created_by : $this->user_id
				));
				$this->notification->save_notification(null,"New Location Created","New Location Created [Road:{$road_name}]",$this->user_session->id);

				echo json_encode(array('status'=>200,'type'=>'road','id'=>$road_id,'success_messages'=>'Road '.$road_name.' Created Successfully'));
				exit();

			}


			if (empty($country_name) && empty($division_name) && empty($district_name) &&
				empty($area_name) && empty($sub_area_name) && empty($sub_sub_area_name) && empty($road_name) )
			{
				echo json_encode(array('status'=>400,'warning_messages'=>'No location name found for saveing data'));
				exit;
			}

			

			$this->session->set_flashdata('success_messages','Region Successfully Added.');
			redirect('location');

		} else {
			redirect('location');
		}
	}

	public function ajax_get_request($type)
	{

		if ($this->input->is_ajax_request()) {

			switch ($type) {
				case 'divisions':
					$country_id = $this->input->post('country_id');
					echo json_encode($this->country->get_divisions($country_id));
					break;
				case 'districts':
					$division_id = $this->input->post('division_id');
					echo json_encode($this->division->get_districts($division_id));
					break;
				case 'areas':
					$district_id = $this->input->post('district_id');
					echo json_encode($this->district->get_areas($district_id));
					break;
				case 'sub_areas':
					$area_id = $this->input->post('area_id');
					echo json_encode($this->area->get_sub_areas($area_id));
					break;
				case 'sub_sub_areas':
					$sub_area_id = $this->input->post('sub_area_id');
					echo json_encode($this->sub_area->get_sub_sub_areas($sub_area_id));
					break;
				case 'roads':
					$sub_area_id = $this->input->post('sub_area_id');
					echo json_encode($this->sub_area->get_roads($sub_area_id));
					break;

					default:
						redirect('location');
						break;
			}
		} else {

			rediect('location');
		}
	}

	
}