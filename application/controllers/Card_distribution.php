<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Scratch_Card_model $scratch_card
 */
class Card_distribution extends BaseController
{
	protected $user_session;
	protected $user_type;
	protected $user_id;
	protected $parent_id;
	protected $message_sign;
	protected $role_name;
	protected $role_type;
	protected $role_id;

	const LCO_UPPER = 'LCO';
	const LCO_LOWER = 'lco';
	const MSO_UPPER = 'MSO';
	const MSO_LOWER = 'mso';
	const Group_LOWER = 'group';
	const Group     = 'Group';
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
		$role_name = (!empty($role)) ? strtolower($role->role_name) : '';
		$role_type = (!empty($role)) ? strtolower($role->role_type) : '';
		$this->role_name = $role_name;
		$this->role_type = $role_type;
		$this->role_id = $this->user_session->role_id;

		if ($this->user_type == self::LCO_LOWER) {
			$this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
		}

	}


	public function index()
	{
		$this->theme->set_title('Scratch Card - Card Distribution')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/distribution.js');

		$data['user_info'] = $this->user_session;
		$data['id'] = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/card-distribution', $data, true);
	}

	public function distribution_card()
	{
		if($this->user_type == self::LCO_LOWER){
			$lco_id = $this->user_id;
		}else{
			$lco_id = $this->input->post('lco_id');
		}

		if($this->user_type == self::Group_LOWER){
			$groupId = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		}else{
			$groupId = $this->input->post('group_id');
		}

		$user = $this->user->find_by_id($lco_id);

		/*if(empty($groupId)){
			echo json_encode(array('status'=>400,'warning_messages'=> 'Sorry User not found to assign cards'));
			exit;
		}*/

		$from = $this->input->post('serial_from');
		$to   = $this->input->post('serial_to');

		$total = ($to - $from + 1);
		if($total > 500){
			echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! You cannot distribute more than 500 Scratch-Card at a time'));
			exit;
		}

		$batch = $this->input->post('batch');
		$card = $this->scratch_card->find_batch_by_number($batch);
		if(empty($card)){
			echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No batch found with number ['.$batch.']'));
			exit;
		}

		$validFromRange = $this->scratch_card->is_valid_from_range($from,$card->id);
		$validToRange   = $this->scratch_card->is_valid_to_range($to,$card->id);

		if(empty($validFromRange) || empty($validToRange)){
			echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Distribution range as you specified from '.$from.'-'.$to.' is not valid'));
			exit;
		}

		$distribution_data = array(
				'updated_by' => $this->user_id,
				'group_id'   => ($groupId>1)? $groupId : 1,
				'lco_user_id' => $lco_id,
				'user_type'   => null,//(!$user->has_attributes()) ? (($groupId>1)? 'Group' : 'MSO') : $user->get_attribute('user_type'),
				'distributor_id' => $this->input->post('distributor_id'),
				'batch' => $this->input->post('batch'),
				'serial_from' => $this->input->post('serial_from'),
				'serial_to' => $this->input->post('serial_to')
		);




		$batch = $this->scratch_card->find_batch_by_number($distribution_data['batch']);
		if(empty($batch)){
			echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Batch not found'));
			exit;
		}

		$get_batch_no = $this->scratch_card_detail->validate_scratch_cards($distribution_data['serial_from']);

		if(empty($get_batch_no)){
			echo json_encode(array('status'=>400,'warning_messages'=>'Serial Number not found for Batch No ['.$batch->batch_no.']'));
			exit;
		}
		if ($distribution_data['batch'] != $get_batch_no->batch_no) {
			echo json_encode(array('status' => 400, 'warning_messages' => "Incorrect Batch Number!"));
			exit();
		}

		if(!empty($distribution_data['lco_user_id']) && !empty($distribution_data['distributor_id'])) {

			if ($get_batch_no->distributor_id != null && $get_batch_no->distributor_id > 0) {
				$distributor = $this->distributor->find_by_id($get_batch_no->distributor_id);
				echo json_encode(array('status' => 400, 'warning_messages' => 'Already Assigned to Distributor [ '. $distributor->get_attribute('distributor_name').' ]'));
				exit;
			}
		}

		if(!empty($distribution_data['lco_user_id']) && empty($distribution_data['distributor_id'])){
			if($get_batch_no->lco_id != null && $get_batch_no->lco_id > 0){
				$lco_name = $this->lco_profile->get_lco_name($get_batch_no->lco_id);
				echo json_encode(array('status'=>400,'warning_messages'=>'Already Assigned to LCO [ '.$lco_name.' ]'));
				exit;
			}
		}

		if(empty($distribution_data['lco_user_id']) && empty($distribution_data['distributor_id'])){
			if($get_batch_no->group_id != null && $get_batch_no->group_id > 0){
				if($get_batch_no->group_id > 1){
					$group_name = $this->group_profile->get_group_name($get_batch_no->group_id);
					echo json_encode(array('status'=>400,'warning_messages'=>'Already Assigned to Group [ '.$group_name.' ]'));
				}else{
					$group_name = $this->mso->get_mso_name($get_batch_no->group_id);
					echo json_encode(array('status'=>400,'warning_messages'=>'Already Assigned to MSO [ '.$group_name.' ]'));
				}

				exit;
			}
		}



		if($get_batch_no->is_used>0) {
			echo json_encode(array('status' => 400, 'warning_messages' => "Serial Number Combination Already Used!"));
			exit();
		}

		$distribution_data['batch'] = $get_batch_no->batch_id;
		$distribute = $this->scratch_card_detail->scratch_card_distribution($distribution_data);

		if($distribute >0){
			$distribution_list_data = array(
				'card_info_id' => $get_batch_no->batch_id,
				'group_id' => ($groupId>1)? $groupId : 1,
				'lco_id'   => $lco_id,
				'from'     => $this->input->post('serial_from'),
				'to'       => $this->input->post('serial_to'),
				'total'    => (($this->input->post('serial_to') - $this->input->post('serial_from'))+1)
			);
			$this->card_distribution_list->save($distribution_list_data);
			$this->set_notification("Scratch Card Distribution", $distribution_data['serial_to'] - $distribution_data['serial_from'] + 1 . " ({$distribution_data['serial_from']} to {$distribution_data['serial_to']}) Scratch Card Distributed");
			echo json_encode(array('status' => 200, 'success_messages' => $distribution_data['serial_to'] - $distribution_data['serial_from'] + 1 . " ({$distribution_data['serial_from']} to {$distribution_data['serial_to']})Scratch Card(s) Distributed"));
		}else{
			echo json_encode(array('status' => 400, 'warning_messages' => "Serial Number Combination Alreay Used!"));
			exit();
		}


	}

	public function ajax_get_groups()
	{
		if($this->input->is_ajax_request()){
			$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
			$all_group = $this->group_profile->get_all_group_users($id);

			foreach($all_group as $i=> $g){
				$all_group[$i]->group_name = '[GROUP] '.$g->group_name;
			}
			if(!empty($all_group))
				$all_group = array_key_sort($all_group,'group_name');
			array_unshift($all_group,array('user_id'=>1,'group_name'=>'MSO [ LCO ]'));
			echo json_encode(array('status'=>200,'group_profiles'=>$all_group));

		}else{
			redirect('/');
		}
	}

	public function ajax_get_lco($Id,$user_type)
	{
		if ($this->input->is_ajax_request()) {
			$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

			if($user_type == 'MSO'){
				$all_lco = $this->lco_profile->get_mso_lco_users($id);
			}else if($user_type == 'Group'){
				$all_lco = $this->group_profile->get_lco_list($Id);
			}



			echo json_encode(array(
					'status' => 200,
					'lco_profile' =>$all_lco,

			));
		} else {
			$this->session->set_flashdata('warning_messages','Direct access not allowed');
			redirect('/');
		}
	}


	public function ajax_load_distributor_by_lco($lco_id)
	{
		if (!$this->input->is_ajax_request()) {
			redirect('scratch-card-distributor');
			exit();
		}

		if($this->user_type == self::LCO_LOWER && $this->role_type == self::STAFF){
			$id = $this->parent_id;
		}elseif($this->user_type == self::LCO_LOWER && $this->role_type == self::ADMIN){
			$id = $this->user_id;
		}else{
			$id = ($lco_id>0)? $lco_id : 1;
		}
		$all_distributor = $this->distributor->get_all_distributor_by_lco($id);

		echo json_encode(array(
				'distributors'=> (!empty($all_distributor))? $all_distributor : array(),
				'status'=>200
		));
	}

	public function ajax_load_batch_numbers($number)
	{
		if (!$this->input->is_ajax_request()) {
			redirect('scratch-card-distributor');
			exit();
		}

		if($number <=0){
			$number = 1;
		}
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		if($this->user_type == self::LCO_LOWER) {

			$all_batch_numbers = $this->scratch_card->get_batch_numbers_by_lco($id,self::LCO_UPPER);

		}else if($this->user_type == self::Group_LOWER){

			$all_batch_numbers = $this->scratch_card->get_batch_numbers_by_lco($id,self::Group);
			//echo $this->db->last_query();
		}else {
			$all_batch_numbers = $this->scratch_card->all_batch_numbers($number);
		}

		echo json_encode(array(
				'all_batch_numbers'=>$all_batch_numbers,
				'status'=>200
		));
	}

	public function ajax_load_serial_no($batch_id,$id=null)
	{


		if($this->input->is_ajax_request()){
			$user = $this->user->find_by_id($id);
			if($user->has_attributes()){

				$serial_numbers = $this->scratch_card->get_serial_no_by_batch($batch_id,$id,$user->get_attribute('user_type'));
				echo json_encode(array('status'=>200,'serial_numbers'=>$serial_numbers));

			}
		}else{
			redirect('/');
		}
	}

	public function ajax_get_permissions()
	{
		if($this->role_type == 'admin'){
			$permissions = array(
					'view_permission' => 1,
					'create_permission' => 1,
					'edit_permission' => 1,
					'delete_permission' => 1
			);
		}else{
			$url_segment = $this->uri->segment(1);
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role,1,$url_segment,$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}

	public function ajax_get_distributed_list()
	{
		if($this->input->is_ajax_request()){

			$id = ($this->user_type == "group")? (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id) : '';
			$take = $this->input->get('take');
			$skip = $this->input->get('skip');
			$distributed_list = $this->scratch_card->get_distributed_list($id,$take,$skip);
			$total = $this->scratch_card->count_distribution_list($id);
			echo json_encode(array('status'=>200,'distributed_list'=>$distributed_list,'total'=>$total));
		}else{
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