<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Package extends BaseController 
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

		$this->user_type = strtolower($this->user_session->user_type);
		$this->user_id = $this->user_session->id;
		$this->created_by = $this->user_session->created_by;

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

	/**
	* Package Landing page or index page
	*/
	public function index()
	{

		$this->theme->set_title('Dashboard - Application')
		->add_style('component.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_style('component.css')
		->add_script('controllers/package.js');
					//->add_script('cbpFWTabs.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		
		//$data['packages'] = $this->package->get_all();
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('package/package',$data,true);
	}

	public function ajax_get_permissions()
	{
		if($this->role_type == "admin"){
			if($this->user_type == self::LCO_LOWER){
				$permissions = array('create_permission'=>0,'edit_permission'=>0,'view_permission'=>1,'delete_permission'=>0);
			}elseif($this->user_type == self::MSO_LOWER){
				$permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
			}
		}else{
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role,1,'package',$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}

	public function ajax_load_programs()
	{
		echo json_encode(array('status'=>200,'programs'=>$this->program->get_all()));
	}

	/**
	* View Specific Package details by token
	* @param $token
	* @return View 
	*/
	public function view($token)
	{
		$this->theme->set_title('Package - View')->add_style('component.css')
		->add_script('cbpFWTabs.js');

		$data['user_info'] = $this->user_session;		
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);

		$package = $this->package->find_by_token($token);
		if (empty($package)) {
			$this->session->set_flashdata('warning_messages','Sorry! No package found');
			redirect('package');
		}
		$package_programs = $package->get_programs(null,false,'programs.id asc');
		
		$data['currency'] = $this->currency->get_active_curreny();
		$data['package']  = $package;
		
		$tempProgramList = array();
		$i = 0;
		foreach ($package_programs as $value) {
			$tempProgramList[$i]['program_id'] = $value->program_id;
			$tempProgramList[$i]['lcn'] = $value->lcn;
			$tempProgramList[$i]['program_name'] = $value->program_name;
			$tempProgramList[$i]['network_id'] = $value->network_id;
			$tempProgramList[$i]['program_type'] = $value->program_type;
			$i++;
		}

		$data['package_programs'] = json_encode($tempProgramList);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('package/view_package',$data,true);

	}

	/**
	* Edit Package by token
	* @param $token
	* @return View
	*/
	public function edit($token)
	{
		if(in_array($this->user_type,array('lco','subscriber'))){
			redirect('/');
		}

		if($this->role_type == "staff") {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'package', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('package');
			}
		}

		$this->theme->set_title('Package - Edit')->add_style('component.css')
		->add_script('controllers/package.js');


		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['token'] = $token;
		
		$package = $this->package->find_by_token($token);
		if (empty($package)) {
			$this->session->set_flashdata('warning_messages','Sorry! No Package Found');
			redirect('package');
		}

		$data['package']  = $package;
		/*$data['programs'] = $programs;
		$data['package_programs'] = $package_programs;*/
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('package/edit_package',$data,true);

	}

	public function ajax_load_package_programs($token)
	{

		
		$package = $this->package->find_by_token($token);
		if (empty($package)) {
			echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No Package Found'));
			exit;
		}

		$package_programs = $package->get_programs();
		$programs = $this->program->get_all();

		foreach ($programs as $i=>$program) {
			if (in_array($program->id,array_keys($package_programs))) {
				unset($programs[$i]);
			}
		}

		echo json_encode(array(
			'status' => 200,
			'pkg' => $package->get_attributes(),
			'programs' => array_values($programs),
			'assigned_programs' => array_values($package_programs)
		));
	}

	/**
	* Accept form data and save package 
	*/
	public function save_package()
	{
		if($this->role_type == "staff") {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'package', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
				exit;
			}
		}

		if ($this->input->post() != null) {
			$save_data['id']               = $this->package->get_last_next_id();
			$save_data['package_name']     = $this->input->post('package_name');
			$save_data['duration']         = $this->input->post('package_duration');
			$save_data['price']            = $this->input->post('package_price');
			$save_data['token']            = md5($this->input->post('package_name'));
			$save_data['is_active']        = $this->input->post('is_active');
			$selected_programs             = $this->input->post('programs');

			//$save_data['created_by']       = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
			

			
			$this->form_validation->set_rules('package_name','Package Name','required|max_length[20]|is_unique[packages.package_name]');
			$this->form_validation->set_rules('package_duration','Package Duration','required');
			$this->form_validation->set_rules('package_price','Package Price','required');
			/*$this->form_validation->set_rules('programs','Programs','required');*/
			

			if($this->form_validation->run() == False) {

				if ($this->input->is_ajax_request()) {

					echo json_encode(array('status'=>400,'warning_messages'=>validation_errors()));
					exit;
				} else {
					$this->session->set_flashdata('warning_messages',validation_errors());
					redirect('package');
				}
			}

			if(count($selected_programs)<=0){
				if ($this->input->is_ajax_request()) {

					echo json_encode(array('status'=>400,'warning_messages'=>'Please Assign Programs'));
					exit;
				} else {
					$this->session->set_flashdata('warning_messages','Please Assign Programs');
					redirect('package');
				}
			}

			$api_programs = array();

			foreach($selected_programs as $prog)
			{
				$api_programs[] = (int)$prog; //(int)$prog['id'];
			}

			$api_data = array(
				'packageId'   => (int)$save_data['id'],
				'packageName' => $save_data['package_name'],
				'limitFlag'   => 0,
				'matchFlag'   => 0,
				'operatorName'=> 'administrator',
				'counts' => count($api_programs),
				'programs' => $api_programs
			);

			$api_string = json_encode($api_data);

			$response = $this->services->update_package($api_string);

			if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                
                echo json_encode(array('status'=>400,'warning_messages'=>$response->message.'. Please Contact with administrator. '.$administrator_info));
                exit;
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                exit;
            }

			$package_id = $this->package->save($save_data);
			$this->notification->save_notification(null,"New Package Created","New Package [{$api_data['packageName']}] has been created",$this->user_session->id);
			$success_message = 'Package successfully created';
			
			if ($package_id !== false) {

				$this->package->assign_program($selected_programs,$package_id);

			}

			if ($this->input->is_ajax_request()) {

				echo json_encode(array('status'=>200,'success_messages'=>$success_message));
				exit;
			} else {

				$this->session->set_flashdata('success_messages',$success_message);
				redirect('package');
			}
			
			

		} else {

			$this->session->set_flashdata('warning_messages','Access Denied');
			redirect('package');

		}
	}

	/**
	* Update Package by token
	* @param $token
	*/
	public function update_package($token)
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'package', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission"));
				exit;
			}
		}

		date_default_timezone_set('Asia/Dhaka');
		$save_data['package_name']     = $this->input->post('package_name');
		$save_data['duration']         = $this->input->post('package_duration');
		$save_data['price']            = $this->input->post('package_price');
		$save_data['last_editor']      = $this->user_session->username;
		$save_data['last_edit_time']   = date('Y-m-d H:i:s',time());
		$save_data['is_active']        = $this->input->post('is_active');
		$selected_programs             = $this->input->post('programs');
		
		//test($this->input->post());

		$package = $this->package->find_by_token($token);
		$package_exist = $this->package->find_by_name($save_data['package_name']);
		if(!empty($package_exist)){
			if($package->get_attribute('id') != $package_exist->id){
				
				if($this->input->is_ajax_request()){
					echo json_encode(array('status'=>400,'warning_messages'=>'Package name is not unique'));
					exit;
				}else{
					$this->session->set_flashdata('warning_messages','Package name is not unique');
					redirect('package');
				}
			}
		}
		

		if(!$save_data['is_active'])
		{
			$result = $this->user_package->assigned_package($package->get_attribute('id'));
			if(!empty($result)){
				if($this->input->is_ajax_request()){
					echo json_encode(array('status'=>400,'warning_messages'=>'Package Already Assigned you cannot change status. <a style="color:red;" href="'.site_url('package/assign-details/'.$package->get_attribute('token')).'">Show Details</a>'));
					exit;
				}else{

					$this->session->set_flashdata('warning_messages','Package Already Assigned you cannot change status. <a style="color:red;" href="'.site_url('package/assign-details/'.$package->get_attribute('token')).'">Show Details</a>');
					redirect('package');
				}
			}
		}

		if(count($selected_programs)<=0){
			if ($this->input->is_ajax_request()) {

				echo json_encode(array('status'=>400,'warning_messages'=>'Please Assign Programs'));
				exit;
			} else {
				$this->session->set_flashdata('warning_messages','Please Assign Programs');
				redirect('package');
			}
		}


		$created_by  = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;

		if (!empty($package)) {

			$this->form_validation->set_rules('package_name','Package Name','required|max_length[20]');
			$this->form_validation->set_rules('package_duration','Package Duration','required');
			$this->form_validation->set_rules('package_price','Package Price','required');

			if($this->form_validation->run() == False) {
				
				if ($this->input->is_ajax_request()) {

					echo json_encode(array('status'=>500,'message'=>validation_errors()));
					exit;
				} else {
					$this->session->set_flashdata('error_messages',validation_errors());
					redirect('package/view/'.$token);
				}
			}

			$api_programs = array();
			foreach($selected_programs as $prog)
			{
				$api_programs[] = (int)$prog; //(int)$prog['id'];
			}

			$api_data = array(
				'packageId'   => (int)$package->get_attribute('id'),
				'packageName' => $save_data['package_name'],
				'limitFlag'   => 0,
				'matchFlag'   => 0,
				'operatorName'=> 'administrator',
				'counts' => count($api_programs),
				'programs' => $api_programs
			);

			$api_string = json_encode($api_data);

			$response = $this->services->update_package($api_string);

			if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                echo json_encode(array('status'=>400,'warning_messages'=>$response->message.'. Please Contact with administrator. '.$administrator_info));
                exit;
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                exit;
            }

			$this->package->save($save_data,$package->get_attribute('id'));
			$this->package->remove_programs();
			$this->package->assign_program($selected_programs,$package->get_attribute('id'),$created_by);
			$success_message = 'Package successfully updated';
			$this->notification->save_notification(null,"Package Updated","Package information of [{$api_data['packageName']}] has been changed",$this->user_session->id);
			if ($this->input->is_ajax_request()) {

				echo json_encode(array('status'=>200,'success_messages'=>$success_message));
				exit;
			} else {
				
				$this->session->set_flashdata('success_messages',$success_message);
				redirect('package');
			}

		} else {
			if($this->input->is_ajax_request())
			{
				echo json_encode(array('status'=>400,'warning_messages'=>'No Package Found'));
				exit;
			}
			$this->session->set_flashdata('warning_messages','No Package Found');
			redirect('package');
		}
		
	}

	public function delete($id)
	{
		if(in_array($this->user_type,array('lco','subscriber'))){
			redirect('/');
		}

		if($this->role_type == 'staff') {
			$permission = $this->menus->has_delete_permission($this->role_id, 1, 'package', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have delete permission");
				redirect('package');
			}
		}

		$package = $this->package->find_by_id($id);
		$assigned_package = $this->user_package->assigned_package($package->get_attribute('id'));

		if ($assigned_package != null)
		{

			$this->session->set_flashdata('warning_messages','This Package Already Assigned. <a style="color:red;" href="'.site_url('package/assign-details/'.$package->get_attribute('token')).'">Show Details</a>');
			redirect('package');
		}
		else
		{
			

			/*$api_data = array(
				'packageId' => (int)$package->get_attribute('id'),
				'operatorName' => 'administrator'
			);

			$api_string = json_encode($api_data);

			$response = $this->services->delete_package($api_string);

			if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                $this->session->set_flashdata('warning_messages',$response->message.'. Please Contact with administrator. '.$administrator_info);
                redirect('package');
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                $this->session->set_flashdata('warning_messages',$code->details);
                redirect('package');
            }

            if($response->status == 200){
            	if(!empty($response->type)){
	                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
	                $this->session->set_flashdata('warning_messages',$code->details);
                }else{
            		$this->session->set_flashdata('success_messages', 'Package has been deleted');
            	}
            }*/
            
            $this->package_program->delete_programs_by_package($package->get_attribute('id'));
            
			$this->package->package_delete($package->get_attribute('id'));
			$this->notification->save_notification(null,"Package Deleted","Package [{$package->get_attribute('package_name')}] has been deleted");
			$this->session->set_flashdata('success_messages', 'Package has been deleted');
			redirect('package');
		}
	}

	public function ajax_load_package()
	{
		$take = $this->input->post('take');
		$skip = $this->input->post('skip');
		$filter = $this->input->get('filter');
        $sort   = $this->input->get('sort');
		$all_package = $this->package->get_all_packages($take,$skip,$filter,$sort);
		echo json_encode(array('status'=>200,
			'packages'=>$all_package,
			'total'=>count($all_package)
			));
	}


	public function assign_details($token)
	{
		$this->theme->set_title('Dashboard - Application')
		->add_style('component.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_script('controllers/package.js');

		$package = $this->package->find_by_token($token);
		if($package->has_attributes()){


			$data['user_info'] = $this->user_session;	
			$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
			$data['token'] = $token;
			
			$data['theme'] = $this->theme->get_image_path();
			$this->theme->set_view('package/assign_details',$data,true);

		}else{
			$this->session->set_flashdata('warning_messages','Sorry package not found');
			redirect('package');
		}

	}

	public function ajax_get_assigned_package_list($token)
	{
		$take = $this->input->get('take');
		$skip = $this->input->get('skip');
		$filter = $this->input->get('filter');
		$sort = $this->input->get('sort');
		$package = $this->package->find_by_token($token);
		$list = $this->user_package->get_assign_packages_with_details($package->get_attribute('id'),$take,$skip,$filter,$sort);
		$total = $this->user_package->get_assign_packages_with_details($package->get_attribute('id'),0,0,null,null,true);
		echo json_encode(array(
			'status' => 200,
			'assigned_package_list'=> $list,
			'total'=>$total
		));
	}

}