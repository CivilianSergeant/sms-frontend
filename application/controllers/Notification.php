<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Notification extends BaseController 
{
	protected $user_session;


	public function __construct()
	{
		parent::__construct();
		/*$this->load->library('services');*/
		$this->theme->set_theme('katniss');
		$this->theme->set_layout('main');


	}

	public function index()
	{
		$this->theme->set_title('Dashboard - Application')
		->add_style('component.css')
		->add_script('controllers/notification.js');
		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$data['token'] = $this->user_session->token;
		$this->theme->set_view('notification/all',$data,true);
	}

	public function ajax_load_notifications()
	{
		$user_type = $this->user_session->user_type;
		$id = $this->user_session->id;
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$all_notification=$this->notification->get_all_notifications($user_type,$id,$limit,$offset);
		echo json_encode(array('status'=>200,'notification'=>$all_notification));
		
	}

	public function delete()
	{
		date_default_timezone_set('Asia/Dhaka');
		$update_data = array();
		if($this->user_session->user_type == "MSO"){
			$update_data['updated_at'] = date('Y-m-d H:i:s');
			$update_data['is_read_mso'] = 1;
		}else{
			$update_data['lco_updated_at'] = date('Y-m-d H:i:s');
			$update_data['is_read_lco'] = 1;
		}
		if(!empty($update_data)){
			$this->db->where('id',$this->input->post('id'));
			$this->db->update('notifications',$update_data);
		}
		
		echo json_encode(array('status' => 200,'success_messages' =>'Notification Delete Successfully'));
	}

	public function delete_all()
	{
		if($this->input->is_ajax_request()){
			$token = $this->input->post('token');
			$user =  $this->user->find_by_token($token);
			if($user->has_attributes()){
				$update_data = array();
				if($this->user_session->user_type == "MSO"){
					$update_data['updated_at'] = date('Y-m-d H:i:s');
					$update_data['is_read_mso'] = 1;
					if(!empty($update_data)){
						$this->db->where('mso_id',$user->get_attribute('id'));
						$this->db->update('notifications',$update_data);
					}
				}else{
					$update_data['lco_updated_at'] = date('Y-m-d H:i:s');
					$update_data['is_read_lco'] = 1;
					if(!empty($update_data)){
						$this->db->where('lco_id',$user->get_attribute('id'));
						$this->db->update('notifications',$update_data);
					}
				}
				echo json_encode(array('status'=>200,'success_messages'=>'All notification deleted'));
			}
		}else{
			$this->session->set_flashdata('warning_messages','Direct access not allowed');
			redirect('/');

		}

	}

	public function ajax_load_popup_notifications()
	{
		$count = 0;
		$messages = array();

		if($this->user_session->user_type != 'Subscriber'){
			$count = $this->notification->get_count_notifications($this->user_session->user_type,$this->user_session->id);
			$messages = $this->notification->get_popup_notifications($this->user_session->user_type,$this->user_session->id);
		}

		echo json_encode(array(
				'status'   => 200,
				'count'    => $count,
				'messages' => $messages
			));

	}

	public function report()
	{
		$this->theme->set_title('Dashboard - Application')
				->add_style('component.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/notification.js');
		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$data['token'] = $this->user_session->token;
		$this->theme->set_view('reports/notification-statement',$data,true);
	}

	public function ajax_get_report()
	{
		if($this->input->is_ajax_request()) {
			$from_date = $this->input->get('from_date');
			$to_date = $this->input->get('to_date');
			$take = $this->input->get('take');
			$skip = $this->input->get('skip');
			$filter = $this->input->get('filter');
			$sort = $this->input->get('sort');
			$reports = $this->notification->get_report($from_date,$to_date,$take,$skip,$filter,$sort);
			$total = $this->notification->get_count_report($from_date,$to_date,$filter);
			//echo $this->db->last_query();
			echo json_encode(array('status'=>200,'reports'=>$reports,'total'=>$total));
		}else{
			redirect('/');
		}
	}
}