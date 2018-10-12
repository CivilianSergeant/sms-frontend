<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends BaseController {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */  
	protected $user_session;
	protected $user_type;
	protected $user_id;
	protected $parent_id;
	protected $role_name;
	protected $role_type;
	protected $role_id;

	const ADMIN = 'admin';
	const STAFF = 'staff';

	public function __construct(){

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
	} 

	public function index()
	{
		

		$id= $this->user_session->id;
		$type= $this->user_session->user_type;
		$role = $this->user_session->role_id;

		if($this->user->is_user_loggedin_firstime($id))
		{
			$this->user->save(array('is_first_loggedin'=>1),$id);
			$this->notification->set_welcome_messasge($id,$type);
		}

		/*if(($type == "MSO") && ($role==1)){
			$this->notification->setUnAssignedProgramNotification($id);
		}*/

		$this->theme->set_title('Dashboard - Application')
				->add_script('controllers/dashboard.js');


		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['user_info'] = $this->user_session;

		// To Insert Operator Id If Operator System Info Table Is Empty

		if ($data['user_info']->user_type == 'MSO') {
			if ($data['user_info']->operator_id == null) {
				$operator_id = $this->config->item('operator_id');
				$org_data = array('operator_id'=>$operator_id);
				$this->db->insert('organization_info', $org_data);
			}
		}

		// End

		


		$data['total_program']=$this->dashboard->total_program();
		$data['program_active']=$this->dashboard->active_program();
		$data['program_deactive']=$this->dashboard->deactive_program();
		$data['total_package']=$this->dashboard->total_package();
		$data['package_active']=$this->dashboard->active_package();
		$data['package_deactive']=$this->dashboard->deactive_package();
		$data['count_lco']=$this->dashboard->total_lco();
		$data['mso_staff']=$this->dashboard->msostaff();
		$data['lco_staff']=$this->dashboard->lcostaff($this->user_session->id);
		$data['staff_active']=$this->dashboard->lcostaffactive($this->user_session->id);
		$data['staff_deactive']=$this->dashboard->lcostaffdeactive($this->user_session->id);
		$data['theme'] = $this->theme->get_image_path();

		if($this->user_session->user_type == 'MSO'){
			$this->theme->set_view('welcome_message',$data,true);
		}else if($this->user_session->user_type == 'LCO'){
			$this->theme->set_view('welcome_message',$data,true);
		}else if($this->user_session->user_type == 'Subscriber'){
			$data['package_count'] = $this->subscriber_profile->get_subscriber_package_count($this->user_id);
			$data['add_on_package_count'] = $this->subscriber_profile->get_subscriber_addon_package_count($this->user_id);
			$this->theme->set_view('subscriber-portal/subscriber-dashboard',$data,true);
		}else if($this->user_session->user_type == 'Group'){
			$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
			$data['total_lco'] = $this->group_profile->get_group_lco_count($id);

			$this->theme->set_view('group-portal/group-dashboard',$data,true);
		}else{
			redirect('/');
		}

	}

	public function app_setting()
	{
		$this->theme->set_title('Dashboard - Application')->add_style('index.css')
		->add_script('custom_js/dashboard.js');


		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['user_info'] = $this->user_session;
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('app_setting',$data,true);
	}

	public function ajax_get_available_packages()
	{
		if($this->input->is_ajax_request()){
			$packages = $this->package->get_packages();
			$add_ons  = $this->package->get_all_add_on_packages();
			echo json_encode(array('status'=>200,'packages'=>$packages,'add_ons'=>$add_ons));
		}else{
			redirect('/');
		}
	}

	public function error()
	{
		$this->theme->set_title('Dashboard - Application')->add_style('index.css')
		->add_script('custom_js/dashboard.js');

		
		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['user_info'] = $this->user_session;
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('404error',$data,true);
	}

	public function demo($id=null)
	{
		$this->load->model('user');
		$user = $this->user->find_by_id(4);
		echo $user->get_attribute('email');
	}

	

	
}
