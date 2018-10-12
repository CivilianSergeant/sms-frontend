<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Collector extends BaseController 
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

	/**
	* Collector Landing page or index page
	*/
	public function index()
	{

		$this->theme->set_title('Dashboard - Application')
		->add_style('component.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_script('controllers/collector.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('collector/collector',$data,true);
	}

	public function ajax_get_permissions()
	{
		if($this->role_type == "admin"){
			$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{
			$role_id = $this->user_session->role_id;
			$role = $this->role->find_by_id($role_id);
			$permissions = $this->menus->has_permission($this->role_id,1,'collector',$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}


	public function save_collector()
	{
		if($this->role_type==self::STAFF) {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'collector', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to Billing collector"));
				exit;
			}
		}

		$this->form_validation->set_rules('collector_name', 'Collector Name', 'required');
		$this->form_validation->set_rules('present_address', 'Present Address','required');
		$this->form_validation->set_rules('permanent_address', 'Permanent Address','required');
		$this->form_validation->set_rules('phone1','Phone 1','required|max_length[11]|min_length[11]');
		$this->form_validation->set_rules('nid_number','National ID','required');

		if ($this->form_validation->run() == FALSE) {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('collector');
			}

		} else {



			$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
			$collector_data = $array = array(
				'name' => $this->input->post('collector_name'),
				'phone1'  => $this->input->post('phone1'),
				'phone2'  => $this->input->post('phone2'),
				'parmanent_address'  => $this->input->post('permanent_address'),
				'present_address'  => $this->input->post('present_address'),
				'reference_phone'  => $this->input->post('ref_phone'),
				'reference_name'  => $this->input->post('reference_name'),
				'reference_phone' => $this->input->post('reference_phone'),
				'nid_number'  => $this->input->post('nid_number'),
				'lco_id'  => $id
				);

			$this->collector->save($collector_data);
			$this->set_notification("New Collector Created","New Collector [{$collector_data['name']}] has been created");
			echo json_encode(array('status'=>200, 'success_messages'=>'Collector ' . $collector_data['name'] . ' created successfully'));
		}	
	}


	public function edit($id)
	{
		if($this->role_type == "staff") {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'collector', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission to Billing Collector");
				redirect('collector');
			}
		}

		$this->theme->set_title('Dashboard - Update Collector')
					->add_script('controllers/collector.js');

		$data['collector_id'] = $id;
		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		//$data['collector'] = $this->collector->collector_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('collector/collector_update',$data,true);
	}

	public function ajax_get_collector($id)
	{
		if($this->input->is_ajax_request()){
			$collector = $this->collector->collector_by_id($id);

			echo json_encode(array('status'=>200,'collector'=>$collector));
		}else{
			redirect('/');
		}

	}


	public function update()
	{
		if($this->role_type == "staff") {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'collector', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission to Billing Collector");
				redirect('collector');
			}
		}

		$this->form_validation->set_rules('name', 'Collector Name', 'required');
		$this->form_validation->set_rules('present_address', 'Present Address','required');
		$this->form_validation->set_rules('parmanent_address', 'Permanent Address','required');
		$this->form_validation->set_rules('phone1','Phone 1','required|max_length[11]|min_length[11]');
		$this->form_validation->set_rules('nid_number','National ID','required');

		if ($this->form_validation->run() == FALSE) {
			//$this->session->set_flashdata('warning_messages',validation_errors());
			/*redirect('collector/edit/' . $this->input->post('collector_id'));*/
			echo json_encode(array('status'=>400,'warning_messages'=>validation_errors()));
			exit;
		} else {



			$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
			$collector_data = $array = array(
				'name' => $this->input->post('name'),
				'phone1'  => $this->input->post('phone1'),
				'phone2'  => $this->input->post('phone2'),
				'parmanent_address'  => $this->input->post('parmanent_address'),
				'present_address'  => $this->input->post('present_address'),
				'reference_phone'  => $this->input->post('ref_phone'),
				'reference_name'  => $this->input->post('reference_name'),
				'reference_phone' => $this->input->post('reference_phone'),
				'nid_number'  => $this->input->post('nid_number'),
				'lco_id'  => $id
				);

			$update = $this->collector->save($collector_data, $this->input->post('id'));
			
			if ($update) {
				$this->set_notification("Collector Info Updated","Collector Information [{$collector_data['name']}] has been updated");
				//$this->session->set_flashdata('success_messages', 'Collector ' . $collector_data['name'] . ' Updated Successfully');
				//redirect('collector');
				echo json_encode(array('status'=>200,'success_messages'=>'Collector ' . $collector_data['name'] . ' Updated Successfully'));
				exit;
			}
		}
	}


	public function view($id)
	{
		if($this->role_type == "admin"){
			$permissions = (object)array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{

			$permissions = $this->menus->has_permission($this->role_id,1,'collector',$this->user_type);
		}
		$this->theme->set_title('Dashboard - View Collector');

		$data['collector_id'] = $id;
		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['collector'] = $this->collector->collector_by_id($id);

		$data['permissions'] = $permissions;
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('collector/view_collector',$data,true);
	}

	public function ajax_load_collector()
	{
		$take = $this->input->get('take');
		$skip = $this->input->get('skip');
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		$all_provider = $this->collector->get_all_collector($id,$take,$skip);

		echo json_encode(array(
			'status'=>200,
			'collectors'=>$all_provider,
			'total' => $this->collector->get_count_collector($id)
		));
	}

	public function ajax_get_collectors_by_lco($lco_id)
	{
		$collectors = $this->collector->get_all_collectors_by_lco($lco_id);
		echo json_encode(array(
			'status'=>200,
			'collectors'=>$collectors
		));
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
