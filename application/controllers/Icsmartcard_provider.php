<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Icsmartcard_provider extends BaseController 
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

		/*if($this->user_type == self::LCO_LOWER){
			$this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
		}*/

		if(in_array($this->user_type,array('lco','subscriber'))){
			redirect('/');
		}
	}

	/**
	* STB Provider Landing page or index page
	*/
	public function index()
	{

		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_script('controllers/ic_provider.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['countries'] = $this->country->get_all();
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('icsmartcard_provider/icsmartcard_provider',$data,true);
	}

	public function ajax_get_permissions()
	{
		if($this->role_type == 'admin'){
			$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role,1,'icsmartcard-provider',$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}

	public function create()
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'icsmartcard-provider', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
				exit;
			}
		}

		$this->form_validation->set_rules('ic_type', 'Full STB Type', 'required');
		$this->form_validation->set_rules('ic_supplier', 'STB Suplier','required');
		$this->form_validation->set_rules('address1', 'Address','required');
		$this->form_validation->set_rules('phone','Phone','required|max_length[15]|min_length[10]');

		if ($this->form_validation->run() == FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('icsmartcard_provider');
			}
		} else {
			$save_ic_provider_data = $array = array(
				'stb_type'   => $this->input->post('ic_type'),
				'stb_provider'  => $this->input->post('ic_supplier'),
				'description'  => $this->input->post('description'),
				'address1'  => $this->input->post('address1'),
				'address2'  => $this->input->post('address2'),
				'country'  => $this->input->post('country'),
				'state'  => $this->input->post('state'),
				'city'  => $this->input->post('city'),
				'zip'  => $this->input->post('zip'),
				'email'  => $this->input->post('email'),
				'phone'  => $this->input->post('phone')
				);

			$users_id = $this->ic_smart_provider->creat_ic_smartcard_provider($save_ic_provider_data);
			$this->notification->save_notification(null,"IC/SmartCard Provider Created","IC/SmartCard Provider {$save_ic_provider_data['stb_provider']} has been created",$this->user_session->id);
			echo json_encode(array('status'=>200, 'success_messages'=>'IC or Smartcard Provider ' . $save_ic_provider_data['stb_provider'] . ' created successfully'));
		}
		
	}

	public function edit_icsmartcard_provider($id)
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'icsmartcard-provider', $this->user_type);
			if (!$permission) {
				//test($permission);
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('icsmartcard-provider');
			}
		}

		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
		->add_script('controllers/ic_provider.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['countries'] = $this->country->get_all();
		$data['provider'] = $this->ic_smart_provider->provider_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('icsmartcard_provider/edit_icsmartcard_provider',$data,true);
	}

	public function update_action()
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'icsmartcard-provider', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('icsmartcard-provider');
			}
		}


		$this->form_validation->set_rules('stb_type', 'STB Type', 'required');
		$this->form_validation->set_rules('stb_provider', 'STB Provider','required');
		$this->form_validation->set_rules('address1', 'Address','required');
		$this->form_validation->set_rules('phone','Phone','required|max_length[15]|min_length[10]');

		if ($this->form_validation->run() == FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('icsmartcard-provider/edit/' . $this->input->post('id'));
			}
		} else {
			$ic_provider_data = $array = array(
				'id'   => $this->input->post('id'),
				'stb_type'   => $this->input->post('stb_type'),
				'stb_provider'  => $this->input->post('stb_provider'),
				'description'  => $this->input->post('description'),
				'address1'  => $this->input->post('address1'),
				'address2'  => $this->input->post('address2'),
				'country'  => $this->input->post('country'),
				'state'  => $this->input->post('state'),
				'city'  => $this->input->post('city'),
				'zip'  => $this->input->post('zip'),
				'email'  => $this->input->post('email'),
				'phone'  => $this->input->post('phone'),
				'updated_by'  => $this->user_id,
				'updated_at'  => date('Y-m-d H:i:s')
				);
			$update = $this->ic_smart_provider->update_provider_by_id($ic_provider_data, $ic_provider_data['id']);
			if ($update) {
				$this->notification->save_notification(null,"IC/SmartCard Provider Updated","IC/SmartCard Provider [{$ic_provider_data['stb_provider']}] has been updated",$this->user_session->id);
				$this->session->set_flashdata('success_messages', 'Provider ' . $ic_provider_data['stb_provider'] . ' Updated Successfully');
				redirect('icsmartcard-provider');
			}
		}
	}


	public function view_icsmartcard_proprovider($id)
	{
		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
		->add_script('controllers/ic_provider.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['countries'] = $this->country->get_all();
		$data['provider'] = $this->ic_smart_provider->provider_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('icsmartcard_provider/view_icsmartcard_provider',$data,true);
	}

	public function delete_provider($id)
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_delete_permission($this->role_id, 1, 'icsmartcard-provider', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have delete permission");
				redirect('icsmartcard-provider');
			}
		}
		$delete = $this->ic_smart_provider->delete_provider_by_id($id);
		if ($delete) {
			$this->session->set_flashdata('success', 'Deleted Successfully');
			redirect('icsmartcard-provider');
		}
	}

	public function ajax_load_providers()
	{
		$take = $this->input->get('take');
        $skip = $this->input->get('skip');
        $id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
		$all_provider = $this->ic_smart_provider->get_all_provider($id,$take,$skip);
		echo json_encode(array(
			'status'=>200,
			'profiles'=>$all_provider,
			'total' => $this->ic_smart_provider->get_count_provider($id)
			));
	}


}