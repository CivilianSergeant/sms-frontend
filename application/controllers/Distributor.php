<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distributor extends BaseController
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

		if($this->user_type == self::LCO_LOWER){
			$this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
		}

	}

	public function index()
	{
		$this->theme->set_title('Scratch Card Distributor')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/distributor.js');

		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('distributor/index', $data, true);
	}

	public function distributor_view($id)
	{

		$this->theme->set_title('Dashboard - View Distributor');

		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['distributor'] = $this->distributor->distributor_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('distributor/view_distributor',$data,true);
	}

	public function save_distributor()
	{
		if($this->role_type == self::STAFF) {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'scratch-card-distributor', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to distributor"));
				exit;
			}
		}

		$this->form_validation->set_rules('distributor_name', 'Distributor Name', 'required');
		$this->form_validation->set_rules('present_address', 'Present Address','required');
		$this->form_validation->set_rules('parmanent_address', 'Permanent Address','required');
		$this->form_validation->set_rules('phone1','Phone 1','required|max_length[11]|min_length[11]');
		$this->form_validation->set_rules('nid_number','National ID','required');

		if ($this->form_validation->run() == FALSE) {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('scratch-card-distributor');
			}

		} else {

			$id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
			$distributor_data = $array = array(
				'distributor_name' => $this->input->post('distributor_name'),
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

			$this->distributor->save($distributor_data);
			$this->set_notification("New Distributor Created","New Distributor [{$distributor_data['distributor_name']}] has been created");
			echo json_encode(array('status'=>200, 'success_messages'=>'Distributor ' . $distributor_data['distributor_name'] . ' created successfully'));
		}	
	}

	public function distributor_edit($id)
	{
		if($this->role_type == self::STAFF) {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'scratch-card-distributor', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission to distributor");
				redirect('scratch-card-distributor');
			}
		}

		$this->theme->set_title('Dashboard - Update Collector');
		
		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['distributor'] = $this->distributor->distributor_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('distributor/distributor_update',$data,true);
	}

	public function distributor_update()
	{
		if($this->role_type == self::STAFF) {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'scratch-card-distributor', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission to distributor");
				redirect('scratch-card-distributor');
			}
		}

		$this->form_validation->set_rules('distributor_name', 'Distributor Name', 'required');
		$this->form_validation->set_rules('present_address', 'Present Address','required');
		$this->form_validation->set_rules('permanent_address', 'Permanent Address','required');
		$this->form_validation->set_rules('phone1','Phone 1','required|max_length[11]|min_length[10]');
		$this->form_validation->set_rules('nid_number','National ID','required');

		if ($this->form_validation->run() == FALSE) {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));

			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('scratch-card-distributor');
			}

		} else {



			$id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
			$distributor_data = $array = array(
				'id' => $this->input->post('distributor_id'),
				'distributor_name' => $this->input->post('distributor_name'),
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

			$this->distributor->save($distributor_data, $distributor_data['id']);
			$this->set_notification("Distributor Update","Distributor [{$distributor_data['distributor_name']}] has been updated");
			$this->session->set_flashdata('success_messages', 'Distributor ' . $distributor_data['distributor_name'] . ' updated successfully');
			redirect('scratch-card-distributor');
		}	
	}

	public function ajax_load_distributor()
	{
		if (!$this->input->is_ajax_request()) {
			redirect('scratch-card-distributor');
			exit();
		}

		$filter = $this->input->get('filter');
		$sort = $this->input->get('sort');
		$take = $this->input->get('take');
		$skip = $this->input->get('skip');
		$id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
		$all_distributor = $this->distributor->get_all_distributor($id,$take,$skip,$filter,$sort);

		echo json_encode(array(
			'status'=>200,
			'distributor'=>$all_distributor,
			'total' => $this->distributor->get_count_distributor($id,$filter),
			));
	}

	public function ajax_get_permissions()
	{
		if($this->role_type == 'admin'){
			$permissions = array(
				'create_permission' => 1,
				'view_permission' => 1,
				'delete_permission' => 1,
				'edit_permission' => 1
			);
		}else{
			$url_segment = $this->uri->segment(1);
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role,1,$url_segment,$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
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
					$this->notification->save_notification($this->created_by,$title,$msg,$this->user_session->id);
				}
			}elseif($this->user_type==self::LCO_LOWER){

				if($this->role_type==self::ADMIN)
				{                                    
					$this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);    

				}elseif($this->role_type==self::STAFF){
					$this->notification->save_notification($this->created_by,$title,$msg,$this->user_session->id);    
				}
			}
		}

	}