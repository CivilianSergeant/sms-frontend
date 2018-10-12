<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Change_password extends BaseController 
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
		

	}

	/**
	* Package Landing page or index page
	*/
	public function index()
	{

		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
					->add_script('cbpFWTabs.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('change_password/change_pass',$data,true);
	}
	/*public function check_pass($id)
	{
		$pass_check=$this->change_password->check_pass($id);
		
	}*/
	public function change_pass()
	{
		$this->form_validation->set_rules('old_password','Old Password','required');
		$this->form_validation->set_rules('new_password','New password','trim|required|min_length[8]|max_length[32]');
		$this->form_validation->set_rules('re_password','Retype password','trim|required|matches[new_password]|min_length[8]|max_length[32]');
		if($this->form_validation->run()== FALSE){
			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'error_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('error_messages',validation_errors());
				redirect('change-password');
			}
			
		}else{
			if(!empty($this->user_session)){$id=$this->user_session->id;}
			$data=$this->change_password->check_pass($id);
			$check= $data->password;
			if($check== md5($this->input->post('old_password'))){
				$data= array(
					'password'=>md5($this->input->post('re_password'))
				);
				$this->change_password->save($data,$id);
				$this->session->set_flashdata('success_messages','Change Password Successfully');
				$this->set_notification("Password Changed","{$this->user_session->username} account's password has been changed");
				redirect('change-password');
			}
			$warning_messages='Old Password Dont Matches';
			$this->session->set_flashdata('warning_messages',$warning_messages);
					redirect('change-password');
			
		}
		
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