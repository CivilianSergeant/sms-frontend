<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Module extends BaseController 
{
	protected $user_session;
	protected $user_type;
    protected $user_id;
    protected $created_by;

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

	}

	public function index()
	{
		$this->theme->set_title('')
		->add_style('component.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_script('controllers/module/module.js');

		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('module/index',$data,true);
	}

	public function create()
	{
		if($this->input->is_ajax_request()){
			$this->form_validation->set_rules('module_name', 'Module Name', 'required');
        	$this->form_validation->set_rules('route', 'module_route','required');
        	if ($this->form_validation->run() == FALSE) {

        		echo json_encode(array('status'=>200,'warning_messages'=>strip_tags(validation_errors())));

        	}else{

        		$save_data = array(
					'module_name'        => $this->input->post('module_name'),
					'route'              => $this->input->post('route'),
					'module_description' => $this->input->post('module_description')
				);
				$this->module->save($save_data);
				json_encode(array('status'=>200,'success_messages'=>''));

        	}
			
		}else{
			redirect('/');
		}
	}

	public function ajax_load_modules()
	{
		$take = $this->input->get('take');
		$skip = $this->input->get('skip');
		$modules = $this->module->get_all_modules($take,$skip);
		$total = $this->module->get_count_modules();
		echo json_encode(array('status'=>200,'modules'=>$modules,'total'=>$total));
	}

	public function edit()
	{

	}

	public function update()
	{

	}

}