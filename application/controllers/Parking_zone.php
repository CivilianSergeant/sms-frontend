<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Parking_zone extends BaseController 
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

	public function index()
	{
		$this->theme->set_title('Park Subscriber')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_style('component.css')
				->add_script('controllers/parkings/park-subscriber.js');
		$data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('parking_zone/index',$data,true);
	}

	public function ajax_get_subscribers()
	{

		if($this->input->is_ajax_request()) {
			$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

			$subscribers = $this->subscriber_profile->get_all_subscribers($id);
			//array_unshift($subscribers, array('user_id'=>0,'subscriber_name'=>'All'));
			echo json_encode(array(
					'status' => 200,
					'subscribers'   => $subscribers,

			));
		} else {
			redirect('/');
		}
	}

	public function ajax_get_pairing_id($subscriber_id)
	{
		if($this->input->is_ajax_request()) {
			$pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($subscriber_id);
			//array_unshift($pairs, array('id'=>0,'pairing_id'=>'All'));
			echo json_encode(array('status'=>200,'pairings'=>$pairs));
		} else {
			redirect('/');
		}
	}

	public function assign_from_parking()
	{
		$this->theme->set_title('Park Subscriber')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_style('component.css')
				->add_script('controllers/parkings/park-subscriber.js');
		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('parking_zone/assign_from_parking',$data,true);
	}

	public function ajax_get_parks()
	{
		if($this->input->is_ajax_request()){
			$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
			$parks = $this->subscriber_parking->get_parks($id);
			echo json_encode(array('status'=>200,'parks'=>$parks));
		}else{
			redirect('/');
		}
	}

	public function ownership_transfer()
	{
		$this->theme->set_title('Ownership Transfer')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_style('component.css')
				->add_script('controllers/parkings/park-subscriber.js');
		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('parking_zone/ownership-transfer',$data,true);
	}

	public function park()
	{
		if($this->input->is_ajax_request()){
			$subscriber_id = $this->input->post('subscriber_id');
			$pairing_id    = $this->input->post('pairing_id');
			$stb_card_id   = $this->input->post('stb_card_id');
			$parking_date  = $this->input->post('parking_date');
			$pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);

			$stb_card_pair = $this->subscriber_stb_smartcard->find_by_id($stb_card_id);
			if(!$stb_card_pair->has_attributes()){
				echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Pairing ID not found'));
				exit;
			}
			$subscriber_user = $this->user->find_by_id($subscriber_id);

			$card_id = $pairing->card_pid;
			$stb_id = $pairing->stb_pid;
			$pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($subscriber_id);

			$save_parking_data = array(
				'pairing_id'    => $pairing_id,
				'subscriber_id' => $subscriber_id,
				'lco_id'		=> $subscriber_user->get_attribute('parent_id'),
				'stb_id'	    => $stb_id,
				'card_id'		=> $card_id,
				'stb_card_pairing_id' => $stb_card_id,
				'parking_date'	=> $parking_date,
				'is_cas_pairing'=> $stb_card_pair->get_attribute('is_cas_pairing'),
				'free_stb' => $stb_card_pair->get_attribute('free_stb'),
				'free_card'=> $stb_card_pair->get_attribute('free_card'),
				'free_subscription_fee'=>$stb_card_pair->get_attribute('free_subscription_fee'),
				'created_by'	=> $this->user_id
			);

			$id = $this->subscriber_parking->save($save_parking_data);
			if($id){
				//delete normal packages
				$this->user_package->remove_packages_by_stb_card_pairing($stb_card_id);

				//delete add-on packages
				$this->user_addon_package->remove_packages_by_stb_card_pairing($stb_card_id);

				// remove pairing id
				$this->subscriber_stb_smartcard->remove_by_subscriber($subscriber_id,$stb_card_id);
			}

			echo json_encode(array('status'=>200,'id'=>$id,'pairings'=>$pairs,'success_messages'=>'Pairing ID [ '.$pairing_id.' ] Parked successfully'));
		}else{
			redirect('/');
		}
	}

	public function reassign()
	{
		if($this->input->is_ajax_request()){
			$id            = $this->input->post('id');
			$pairing_id    = $this->input->post('pairing_id');
			$subscriber_id = $this->input->post('subscriber_id');
			$lco_id  = $this->input->post('lco_id');
			$stb_id  = $this->input->post('stb_id');
			$card_id = $this->input->post('card_id');

			$is_cas_pairing = $this->input->post('is_cas_pairing');
			$free_stb  = $this->input->post('free_stb');
			$free_card = $this->input->post('free_card');
			$free_subscription_fee = $this->input->post('free_subscription_fee');
			$by = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
			$save_stb_pair_data = array(
				'pairing_id'   			=> $pairing_id,
				'subscriber_id' 		=> $subscriber_id,
				'lco_id'				=> $lco_id,
				'stb_id'				=> $stb_id,
				'card_id'				=> $card_id,
				'is_cas_pairing'		=> $is_cas_pairing,
				'free_stb'				=> $free_stb,
				'free_card'				=> $free_card,
				'free_subscription_fee' => $free_subscription_fee,
				'created_by'			=> $this->user_id
			);

			$stb_card_pair_id = $this->subscriber_stb_smartcard->save($save_stb_pair_data);

			if($stb_card_pair_id){

				$save_log_data = array(
					'pairing_id'    => $pairing_id,
					'subscriber_id' => $subscriber_id,
					'lco_id'		=> $lco_id,
					'stb_id'	    => $stb_id,
					'card_id'		=> $card_id,
					'stb_card_pairing_id' => $stb_card_pair_id,
					'parking_date'	=> date('Y-m-d H:i:s'),
					'is_cas_pairing'=> $is_cas_pairing,
					'free_stb' => $free_stb,
					'free_card'=> $free_card,
					'free_subscription_fee'=>$free_subscription_fee,
					'created_by'	=> $this->user_id
				);

				$log_id = $this->subscriber_parking_reassign_log->save($save_log_data);
				if($log_id){

					$this->subscriber_parking->update_park_status($id,$by);
				}
			}

			$parks = $this->subscriber_parking->get_parks($by);
			echo json_encode(array('status'=>200,'log_id'=>$log_id,'parks'=>$parks,'success_messages'=>'Parked Pairing ID [ '.$pairing_id.' ] successfully reassigned'));

		}else{
			redirect('/');
		}
	}

	public function transfer()
	{
		if($this->input->is_ajax_request()){
			$old_subscriber_id = $this->input->post('old_subscriber_id');
			$new_subscriber_id = $this->input->post('new_subscriber_id');
			$pairings = $this->input->post('pairings');

			$old_subscriber_name = $this->subscriber_profile->get_subscriber_name($old_subscriber_id);
			$new_subscriber_name = $this->subscriber_profile->get_subscriber_name($new_subscriber_id);
			$pairing_ids = array();
			foreach($pairings as $p){
				$stb_card_pair = $this->subscriber_stb_smartcard->find_by_id($p);
				if($stb_card_pair->has_attributes()){
					$pairing_ids[] = $stb_card_pair->get_attribute('pairing_id');
					// add log for ownership transfer
					$save_stb_card_pair = $stb_card_pair->get_attributes();
					$save_stb_card_pair['old_id'] = $save_stb_card_pair['id'];
					$save_stb_card_pair['id'] = '';
					$this->ownership_transfer->save($save_stb_card_pair);

					//delete normal packages
					$this->user_package->remove_packages_by_stb_card_pairing($stb_card_pair->get_attribute('id'));

					//delete add-on packages
					$this->user_addon_package->remove_packages_by_stb_card_pairing($stb_card_pair->get_attribute('id'));

					// update pairing id with new subscriber_id
					$this->subscriber_stb_smartcard->save(
							array('subscriber_id'=>$new_subscriber_id),
							$stb_card_pair->get_attribute('id')
							);

				}
			}

			echo json_encode(array('status'=>200,'success_messages'=>'Stb Card Pairing ['.implode(',',$pairing_ids).'] successfully transfer from subscriber [ '.$old_subscriber_name. ' ] to [ '.$new_subscriber_name.' ]'));
		}else{
			redirect('/');
		}
	}

}