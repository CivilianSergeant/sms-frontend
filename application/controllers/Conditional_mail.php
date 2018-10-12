<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Services $services
 * @property Conditional_resource $conditional_resource
 */
class Conditional_mail extends BaseController 
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
		$this->load->library('services');
		$this->load->library('conditional_resource');
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

		if(in_array($this->user_type,array('lco','subscriber'))){
			redirect('/');
		}

	}

	public function index()
	{
		$this->theme->set_title('Conditional Mail')
			 ->add_style('component.css')
			 ->add_style('kendo/css/kendo.common-bootstrap.min.css')
			 ->add_style('kendo/css/kendo.bootstrap.min.css')
			 ->add_script('controllers/tools-conditions/conditional_mail.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['sign'] = $this->config->item('message_sign');
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('tools-conditions/index',$data,true);
	}

	public function process_mail()
	{
		if($this->input->post())
		{
			if($this->role_type == self::STAFF) {
				$permission = $this->menus->has_create_permission($this->role_id, 1, 'tools-conditional-mail', $this->user_type);

				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have to create permission to Conditional Mail"));
					exit();
				}
			}

			$broadcast_type = $this->input->post('broadcast_type');
			$title   = $this->input->post('title');
			$content = $this->input->post('content');
			$sign    = $this->input->post('sign');
			$sign    = (empty($sign))? $this->config->item('message_sign') : $sign;
			$lco_id = $this->input->post('lco_id');
			$message = array();

			switch($broadcast_type){
				case 'LCO':

					if($lco_id=="")
					{
						// start time will never old time ex
						// end time wil never less than start time
						$message['status'] = 400;
						$message['warning_messages'] = 'Please Select LCO';
						echo json_encode($message);
						exit;

					}

					$api_string = null;
					$data = array(
							'start_date_time' => $this->input->post('startTime'),
							'end_date_time'   => $this->input->post('endTime'),
							'type_data'       => 50
					);

					if($lco_id == 0){
						// for all lco_id/group
						$data['type_operator'] = 115;
						$data['group_id']    = 0;
					}else{
						// for specific lco_id/group
						$data['type_operator'] = 116;
						$data['group_id']    = $lco_id;
					}

					$data['title'] = $title;
					$data['message_content'] = $content;
					$data['template'] = 'msg_template/conditional_mail/lco_template';
					$data['message_sign'] = $sign;

					$data['conditionLength'] = 11;
					$data['contentLength']   = 1089;
					$data['condCounts']      = 1;

					$api_mail_data = $this->services->get_lco_conditional_content($data,true);
					$api_string = json_encode($api_mail_data);

					$con_search_response = $this->services->conditional_mail($api_string);

					if($con_search_response->status == 500 || $con_search_response->status == 400){
						$administrator_info = $this->organization->get_administrators();
						echo json_encode(array('status'=>400,'warning_messages'=>$con_search_response->message.' Please Contact with administrator. '.$administrator_info));
						exit;
					}

					if($con_search_response->status != 200){
						$code = $this->cas_sms_response_code->get_code_by_name($con_search_response->type);
						echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
						exit;
					}

					if($con_search_response->status == 200){

						if($lco_id>0){
							$lco_name = $this->lco_profile->get_lco_name($lco_id);
							$success_messages = 'Conditional Mail successfully sent on LCO ['.$lco_name.']';
						}else{
							$api_mail_data['group_id'] = 0;
							$success_messages = 'Conditional Mail successfully sent on all LCO';
						}


						if(!empty($con_search_response->id)){
							// storing response in conditional_search_log
							$api_mail_data['lco_id'] = $lco_id;
							$api_mail_data['type']	 = 'USER';
							$api_mail_data['creator'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
							$this->conditional_resource->save_conditional_mail_response($con_search_response,$api_mail_data);

						}

						$this->set_notification("Conditional mail",$success_messages);

						echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
						exit;
					}
					break;

				case 'SUBSCRIBER':
					$lco_id = $this->input->post('lco_id');
					$subscriber_id = $this->input->post('subscriber_id');
					$pairing_id    = $this->input->post('pairing_id');
					$address_by    = $this->input->post('address_by');


					if($subscriber_id=="")
					{

						$message['status'] = 400;
						$message['warning_messages'] = 'Please select all or specific subscriber from subscriber drop-down menu';
						echo json_encode($message);
						exit;
					}


					$api_string = null;
					$data = array(
							'start_date_time' => $this->input->post('startTime'),
							'end_date_time'   => $this->input->post('endTime'),
							'type_data'       => 50
					);

					if($subscriber_id == 0){

						// for all subscriber
						$data['type_operator'] = 115;
						$data['group_id']      = 0;
						$data['start_date_time'] = $this->input->post('startTime');
						$data['end_date_time']   = $this->input->post('endTime');

						$data['title'] = $title;
						$data['message_content'] = $content;
						$data['template'] = 'msg_template/conditional_mail/subscriber_template';
						$data['message_sign'] = $sign;
						$data['conditionLength'] = 11;
						$data['contentLength']  = 1089;
						$data['condCounts'] = 1;
						$api_search_data = $this->services->get_subscriber_conditional_content($data,true);
						$api_string = json_encode($api_search_data);
						$startDate = $api_search_data['startTime'];
						$endDate = $api_search_data['endTime'];

						$con_search_response = $this->services->conditional_mail($api_string);

						if($con_search_response->status == 500 || $con_search_response->status == 400){
							$administrator_info = $this->organization->get_administrators();
							echo json_encode(array('status'=>400,'warning_messages'=>$con_search_response->message.' Please Contact with administrator. '.$administrator_info));
							exit;
						}

						if($con_search_response->status != 200){
							$code = $this->cas_sms_response_code->get_code_by_name($con_search_response->type);
							echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
							exit;
						}

						if($con_search_response->status == 200){

							if($subscriber_id>0){
								$subscriber_name = $this->subscriber_profile->get_subscriber_name($subscriber_id);
								$success_messages = 'Conditional Mail successfully sent on Subscriber ['.$subscriber_name.']';
							}else{
								$success_messages = 'Conditional Mail successfully sent on all Subscriber';
							}


							$api_search_data['subscriber_id'] = $subscriber_id;
							$api_search_data['startTime'] = $startDate;
							$api_search_data['endTime'] = $endDate;
							$api_search_data['type']	= 'USER';
							$api_search_data['creator'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
							$this->conditional_resource->save_conditional_mail_response($con_search_response,$api_search_data);

							$this->set_notification("Conditional mail",$success_messages);
							echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
							exit;
						}

					} else {

						// for specific subscriber

						if($lco_id=="")
						{

							$message['status'] = 400;
							$message['warning_messages'] = 'Please select LCO before to select subscriber';
							echo json_encode($message);
							exit;
						}

						if($pairing_id == ''){
							$message['status'] = 400;
							$message['warning_messages'] = 'Please select Pairing ID before start search';
							echo json_encode($message);
							exit;
						}

						if($pairing_id != "")
						{
							$pairing =$this->subscriber_stb_smartcard->get_pairing_by_id($pairing_id);

							if($address_by == "CARD"){
								$cardNum = $pairing->internal_card_number;
								$type_data = 48;
							}else{
								$cardNum = $pairing->stb_id;
								$type_data = 52;
							}
							$api_mail_data['type_data'] = $type_data;
							$api_mail_data['type_operator'] = 116;
							$api_mail_data['cardNum']  = $cardNum;
							$api_mail_data['start_date_time'] = $this->input->post('startTime');
							$api_mail_data['end_date_time']   = $this->input->post('endTime');

							$api_mail_data['title'] = $title;
							$api_mail_data['message_content'] = $content;
							$api_mail_data['template'] = 'msg_template/conditional_mail/subscriber_template';
							$api_mail_data['message_sign'] = $sign;
							$api_mail_data['conditionLength'] = 11;
							$api_mail_data['contentLength']  = 1089;
							$api_mail_data['condCounts'] = 1;
							$api_conditional_mail = $this->services->get_subscriber_conditional_content($api_mail_data,true);

							$api_string = json_encode($api_conditional_mail);
							$startDate = $api_conditional_mail['startTime'];
							$endDate = $api_conditional_mail['endTime'];


							$startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
							$endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];

							$response = $this->services->conditional_mail($api_string);

							if($response->status == 500 || $response->status == 400){
								$administrator_info = $this->organization->get_administrators();
								echo json_encode(array('status'=>400,'warning_messages'=>$response->message.' Please Contact with administrator. '.$administrator_info));
								exit;
							}

							if($response->status != 200){
								$code = $this->cas_sms_response_code->get_code_by_name($response->type);
								echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
								exit;
							}

							if((!empty($response->id)) && (!empty($response->status)) && ($response->status == 200)){

								$api_mail_data['subscriber_id'] = $subscriber_id;
								$api_mail_data['startTime'] = $startDate;
								$api_mail_data['endTime'] = $endDate;

								if($address_by == "CARD"){
									$api_mail_data['smart_card_id'] =$api_mail_data['cardNum'];
								}else{
									$api_mail_data['stb_id'] = $api_mail_data['cardNum'];
								}
								$api_mail_data['type']	= 'USER';
								$api_mail_data['creator'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

								$this->conditional_resource->save_conditional_mail_response($response,$api_mail_data);

								if($subscriber_id>0){
									$subscriber_name = $this->subscriber_profile->get_subscriber_name($subscriber_id);
									$success_messages = 'Conditional Mail successfully sent on Subscriber ['.$subscriber_name.']';
								}else{
									$success_messages = 'Conditional Mail successfully sent on all Subscriber';
								}

								$this->set_notification("Conditional mail",$success_messages);
								echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
								exit;
							}

						}



					}



					break;

				case 'BUSINESS_REGION':

					$business_region_id = $this->input->post('business_region_id');

					if($business_region_id == ''){
						$message['status'] = 400;
						$message['warning_messages'] = 'Please Select Business Region before to start search';
						echo json_encode($message);
						exit;

					}

					if($business_region_id != ''){
						$data = array(
								'start_date_time' => $this->input->post('startTime'),
								'end_date_time'   => $this->input->post('endTime'),
								'type_data'       => 49,
								'type_operator'   => 116,
								'addr'             => $business_region_id
						);


						$data['title'] = $title;
						$data['message_content'] = $content;
						$data['template'] = 'msg_template/conditional_mail/business_address_template';
						$data['message_sign'] = $sign;

						$data['conditionLength'] = 11;
						$data['contentLength']  = 1089;
						$data['condCounts'] = 1;

						$api_search_data = $this->services->get_address_code_conditional_content($data,true);
						$api_string = json_encode($api_search_data);
						$startDate = $api_search_data['startTime'];
						$endDate = $api_search_data['endTime'];

						$con_search_response = $this->services->conditional_mail($api_string);



						if($con_search_response->status == 500 || $con_search_response->status == 400){
							$administrator_info = $this->organization->get_administrators();
							echo json_encode(array('status'=>400,'warning_messages'=>$con_search_response->message.' Please Contact with administrator. '.$administrator_info));
							exit;
						}

						if($con_search_response->status != 200){
							$code = $this->cas_sms_response_code->get_code_by_name($con_search_response->type);
							echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
							exit;
						}

						if($con_search_response->status == 200){

							$success_messages = 'Conditional Mail successfully sent on address code ['.$business_region_id.']';

							$api_search_data['region_code'] = $business_region_id;
							$api_search_data['startTime'] = $startDate;
							$api_search_data['endTime'] = $endDate;
							$api_search_data['type']	= 'USER';
							$api_search_data['creator'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
							$this->conditional_resource->save_conditional_mail_response($con_search_response,$api_search_data);

							$this->set_notification("Conditional mail",$success_messages);
							echo json_encode(array('status'=>200,'success_messages'=>$success_messages));
							exit;
						}

					}

					break;
				default:
					break;
			}

		} else{
			redirect('tools-conditional-mail');
		}
	}

	public function stop_mail()
	{
		date_default_timezone_set('Asia/Dhaka');

		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			$mail = $this->conditional_mail->find_by_id($id);
			if($mail->has_attributes()){

				// cas api call
				$api_data = array(
					'searchId'     => $mail->get_attribute('condition_return_code'), //'4611686018427388172'
					'operatorName' => 'administrator'
				);

				$json_data = json_encode($api_data);
				$response = $this->services->stop_conditional_mail($json_data);

				if($response->status == 500 || $response->status == 400){
					$administrator_info = $this->organization->get_administrators();
					echo json_encode(array('status'=>400,'warning_messages'=>$response->message.' Please Contact with administrator. '.$administrator_info));
					exit;
				}

				if($response->status != 200){
					$code = $this->cas_sms_response_code->get_code_by_name($response->type);
					echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
					exit;
				}

				if($response->status == 200){
					$code = $this->cas_sms_response_code->get_code_by_name($response->type);
					$success_messages = $code->details;
				}


				$this->conditional_mail->stop_mail($id,array(
					'is_stoped' => 1,
					'stopped_editor' => $this->user_session->id,
					'stopped_edit_time' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				));

				echo json_encode(array('status'=>200,'success_messages'=>$success_messages.'. Conditional Mail ['.$mail->get_attribute('condition_return_code').'] stopped successfully'));
			} else {
				echo json_encode(array('status'=>400,'success_messages'=>'Conditional Mail not found'));
			}
			exit;
		}else{
			redirect('/');
		}
	}

	public function ajax_get_lco()
	{
		if($this->input->is_ajax_request()) {
			$lco = $this->lco_profile->get_all_lco_users($this->user_session->id);
			array_unshift($lco, array('user_id'=>0,'lco_name'=>'All'));
			array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All FOC'));
			//array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All MSO'));
			echo json_encode(array(
				'status' => 200,
				'lco' => $lco
			));
		} else {
			redirect('/');
		}
		
	}

	public function ajax_get_subscriber_by_lco($lco_id)
	{
		if($this->input->is_ajax_request()) {
			if($lco_id == -1){
				$lco_id = 1;
			}
			$subscribers = $this->subscriber_profile->get_all_subscribers($lco_id);
			array_unshift($subscribers, array('user_id'=>0,'subscriber_name'=>'All'));
			echo json_encode(array(
				'status' => 200,
				'subscribers' => $subscribers
			));
		} else {
			redirect('/');
		}
		
	}

	public function ajax_get_pairing($subscriber_id)
	{
		if($this->input->is_ajax_request()) {
			$pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($subscriber_id);
			//array_unshift($pairs, array('id' => 'all','pairing_id' => 'All'));
			echo json_encode(array('status'=>200,'pairings'=>$pairs));
		} else {
			redirect('/');
		}
	}

	public function ajax_get_regions()
	{
		if($this->input->is_ajax_request()) {
			$regions = $this->region_level_one->get_regions_with_name();
			echo json_encode(array('status'=>200,'regions'=>$regions));
		} else {
			redirect('/');
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