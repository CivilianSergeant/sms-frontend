<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Scratch_card
 * @property Scratch_Card_model $scratch_card
 * @property Scratch_Card_Detail_model $scratch_card_detail
 */
class Scratch_card extends BaseController 
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
	const GROUP_LOWER='group';

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
		$this->theme->set_title('Scratch Card - Generate Card')
		->add_style('component.css')
		->add_style('custom.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_script('controllers/generate_card.js');

		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/index', $data, true);
	}

	public function save_cards()
	{
		if($this->role_type == "staff"){
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'scratch-card-generate', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
				exit;
			}
		}

		$this->form_validation->set_rules('prefix', 'Prefix Name', 'required|is_unique[scratch_card_info.prefix]|max_length[2]|min_length[2]|numeric');
		$this->form_validation->set_rules('value', 'Value','required|numeric');
		$this->form_validation->set_rules('number_of_cards', 'Number Of Cards','required|numeric');
		$this->form_validation->set_rules('active_from','Active From Date','required');

		if ($this->form_validation->run() == FALSE) {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('scratch-card-generate');
			}

		} else {
				$card_info_row_id = $this->scratch_card->get_auto_id();
				$id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
				$card_data = array(
						'id' => $card_info_row_id->autoid,
						'date' => date('m-d-y H:i:s'),
						'prefix' => $this->input->post('prefix'),
						'value' => $this->input->post('value'),
						'number_of_cards' => $this->input->post('number_of_cards'),
						'active_from_date' => $this->input->post('active_from'),
						'length' => 16,
						'is_active' => 1,
						'is_suspended' => 0,
				);

				// add card numbers to array
				$cards = array();
				for($i=1;$i<=$this->input->post('number_of_cards'); $i++){
					$cards[$i] = Scratch_Card_model::completed_number($card_data['prefix'],16);
				}

				// check if array contain any duplicate card number
				if(Scratch_Card_model::array_has_dupes($cards)){
					echo json_encode(array('status'=>400, 'warning_messages'=>'Duplicate number exist. Please Try again'));
					exit;
				}


				// save card info
				$card_info_id = $this->scratch_card->save($card_data);


				// save card detail info
				for($i=1; $i<=$this->input->post('number_of_cards'); $i++){

					$last_cadr_last_number = $this->scratch_card->last_card_number();
					$card_detail_row_id = $this->scratch_card_detail->get_auto_id();

					if($card_info_id){

						$card_detail = array(
							'id' => $card_detail_row_id->autoid,
							'card_info_id' => $card_info_id,
							'card_no' => $cards[$i],//number_format(($card_data['prefix'] . '00000000000000' + $last_cadr_last_number->card_no + $i),0,'',''),
							'serial_no' => ($card_detail_row_id->autoid + $i),
							'parent_id' => $id
							);

						$this->scratch_card_detail->save($card_detail);

						/*if($i == $card_data['number_of_cards']){

							$last_card = array(
									'card_no' => $i + $last_cadr_last_number->card_no,
							);
							$this->scratch_card->save_last_card($last_card);
						}*/

					}
				}
			
				$this->set_notification("New Scratch Cards Created","New Scratch Cards has been created");
				echo json_encode(array('status'=>200, 'success_messages'=>'New Cards created successfully'));
		}
	}

	public function scratch_card_batch_info($id)
	{

		$this->theme->set_title('Scratch Card - Scratch Card Batch Info')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/scratch_card_batch_info.js');
		$data['user_info']    = $this->user_session;
		$data['card_info_id'] = $id;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['card_info'] = $this->scratch_card->card_by_id($id);
		if(empty($data['card_info']))
		{
			$this->session->set_flashdata('warning_messages','Sorry! No card info found');
			redirect('scratch-card-generate');
		}
		$data['status']       = array('Inactive','Active');
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/scratch_card_batch_info', $data, true);
	}


	public function ajax_load_all_cards()
	{

		if (!$this->input->is_ajax_request()) {
			redirect('scratch-card-generate');
			exit();
		}
		$card_info_id = $this->input->get('card_info_id');
		$card_no      = $this->input->get('card_no');
		$serial_no    = $this->input->get('serial_no');
		$type         = $this->input->get('search_type');

		$take = $this->input->get('take');
		$skip = $this->input->get('skip');

		$id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
		$all_cards = $this->scratch_card_detail->get_cards_by_batch($card_info_id,$card_no,$serial_no,$type,$take,$skip);
		//echo $this->db->last_query();
		echo json_encode(array(
				'status'=>200,
				'all_cards'=>$all_cards,
				'total' => $this->scratch_card_detail->get_count_cards($card_info_id,$card_no,$serial_no,$type),
		));
	}

	public function card_view($id)
	{
		$this->theme->set_title('Scratch Card - Card Detail')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/generate_card.js');

		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['card_detail'] = $this->scratch_card_detail->get_card_by_id($id);
		if(empty($data['card_detail']))
			redirect('scratch-card-generate');
		//test($data['card_detail']);
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/scratch_card_detail_view', $data, true);
	}

	public function available_card_view($id)
	{
		$this->theme->set_title('Scratch Card - Card Detail')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/generate_card.js');

		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$card_detail = $this->scratch_card_detail->get_card_by_id($id);

		if(empty($card_detail))
			redirect('scratch-card-generate');

		$user = $this->user->find_by_id($card_detail->updated_by);
		$recharge_by = '';
		if($card_detail->is_used){

			if($user->get_attribute('user_type')==self::MSO_UPPER){
				$recharge_by = $this->mso_profile->get_mso_name($user->get_attribute('id'));
			}else if($user->get_attribute('user_type') == self::LCO_UPPER){

				$recharge_by = $this->lco_profile->get_lco_name($user->get_attribute('id'));

			}else if($user->get_attribute('user_type') == 'Group'){

				$recharge_by = $this->group_profile->get_group_name($user->get_attribute('id'));
			}else if($user->get_attribute('user_type') == 'Subscriber'){
				$recharge_by = $this->subscriber_profile->get_subscriber_name($user->get_attribute('id'));
			}
		}




		$group = $this->lco_group->is_already_exist($card_detail->lco_id);
		$group_name = '';
		if(!empty($group)){
			$group_name = $this->group_profile->get_group_name($group->group_id);
		}else{
			$group_name = $this->group_profile->get_group_name($card_detail->group_id);
		}

		$data['group_name']  = $group_name;
		$data['card_detail'] = $card_detail;
		$data['recharge_by'] = (!empty($recharge_by))? $recharge_by : '';
		//test($data['card_detail']);
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/available_card_view', $data, true);
	}

	public function card_edit($id)
	{
		if($this->role_type == "staff"){
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'scratch-card-generate', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
				redirect('scratch-card-generate');
			}
		}

		$this->theme->set_title('Scratch Card - Card Detail')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/generate_card.js');

		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['card_detail'] = $this->scratch_card_detail->get_card_by_id($id);
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/scratch_edit', $data, true);
	}

	public function card_update()
	{
		if($this->role_type == "staff"){
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'scratch-card-generate', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
				redirect('scratch-card-generate');
			}
		}

		$card_data = array(
				'id' => $this->input->post('card_id'),
				'is_active' => $this->input->post('is_active'),
				'is_suspended' => $this->input->post('is_suspended'),
		);
		$this->scratch_card_detail->save($card_data, $card_data['id']);
		$this->set_notification("Scratch Card Update","Scratch Card has been updated");
		$this->session->set_flashdata('success_messages', 'Card updated successfully');
		redirect('scratch-card-generate/card-edit/' . $card_data['id']);
	}

	public function change_status()
	{
		if($this->role_type == "staff"){
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'scratch-card-generate', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
				redirect('scratch-card-generate');
			}
		}

		$card_id = $this->input->post('id');
		$card_status = $this->input->post('status');
		$scratch_card = $this->scratch_card->find_by_id($card_id);
		if($scratch_card->has_attributes()){
			$this->scratch_card->save(array('is_active'=>$card_status),$scratch_card->get_attribute('id'));
			$this->session->set_flashdata("success_messages","Scratch Card Batch Status Updated");
			$this->set_notification("Scratch Card Batch Status Updated","Scratch Card Batch Status Updated");

		}else{
			$this->session->set_flashdata("warning_messages","Scratch Card Batch not found, Scratch Card Batch Status not updated");
		}

		redirect('scratch-card-generate');
	}

	public function ajax_load_cards()
	{
		if (!$this->input->is_ajax_request()) {
			redirect('scratch-card-generate');
			exit();
		}

		$take = $this->input->get('take');
		$skip = $this->input->get('skip');
		$id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;

		switch($this->user_type){
			case self::MSO_LOWER:
				$all_cards = $this->scratch_card->get_all_batch($id,$take,$skip);
				$total = $this->scratch_card->get_count_cards($id);
				break;
			case self::LCO_LOWER:
				$all_cards = $this->scratch_card->get_all_lco_available_batch($id,$take,$skip);
				$total = $this->scratch_card->get_count_lco_available_cards($id);
				break;
			case self::GROUP_LOWER:
				$all_cards = $this->scratch_card->get_all_group_available_batch($id,$take,$skip);
				$total = $this->scratch_card->get_count_group_available_cards($id);
				break;
		}



		echo json_encode(array(
				'status'=>200,
				'cards'=>$all_cards,
				'total' => $total,
		));
	}

	public function ajax_get_cards($from,$to)
	{
		if($this->input->is_ajax_request()){

			$id   = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
			$take = $this->input->get('take');
			$skip = $this->input->get('skip');
			if($this->user_type == self::GROUP_LOWER){
				$cards = $this->scratch_card->has_group_available_card($id,$from,$to,$take,$skip);
				$total = $this->scratch_card->count_group_available_card($id,$from,$to);
			}else if($this->user_type == self::LCO_LOWER){
				$cards = $this->scratch_card->has_lco_available_card($id,$from,$to,$take,$skip);
				$total = $this->scratch_card->count_lco_available_card($id,$from,$to);
			}else if($this->user_type==self::MSO_LOWER){
				$cards = $this->scratch_card->has_available_card($from,$to,$take,$skip);
				$total = $this->scratch_card->count_available_card($from,$to);
			}

			echo json_encode(array('status'=>200,'cards'=>$cards,'total'=>$total));
		}else{
			redirect('/');
		}
	}

	public function available_card_detail($from,$to)
	{

		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		if($this->user_type != self::MSO_LOWER) {
			if ($this->user_type == self::GROUP_LOWER) {
				$cards = $this->scratch_card->has_group_available_card($id, $from, $to);
			} else if ($this->user_type == self::LCO_LOWER) {
				$cards = $this->scratch_card->has_lco_available_card($id, $from, $to);
			}
			if(empty($cards)){
				redirect('scratch-card-available');
			}
		}




		$this->theme->set_title('Scratch Card - Scratch Card Batch Info')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/scratch_card_batch_info.js');
		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['from'] = $from;
		$data['to']   = $to;

		$data['status']       = array('Inactive','Active');
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/available_card_details', $data, true);
	}

	public function ajax_load_cards_by_cardno_serialno()
	{
		$search_key = $this->input->get('search_key');
		$all_cards = $this->scratch_card_detail->get_card_by_cardno_serial($search_key);

		echo json_encode(array(
				'all_cards'=>$all_cards,
				'total' => $this->scratch_card_detail->get_count_card_by_cardno_serial($search_key),
		));
	}

	public function ajax_download_request()
	{
		if($this->input->is_ajax_request()){
			$batch_id = $this->input->post('batch_id');
			$password = $this->input->post('password');
			$userObj  = $this->user->find_by_id($this->user_id);

			if(!$userObj->has_attributes()){
				echo json_encode(array('status'=>400,'warning_messages'=>'Session has been expired'));
				exit;
			}

			$user_pass =trim($userObj->get_attribute('password'));
			$password  = trim(md5($password));

			if($user_pass != $password){
				echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Password was wrong'));
				exit;
			}
			$time = time()+(5*60);
			$token = str_replace("==",'',(base64_encode($userObj->get_attribute('password').'####'.$batch_id.'####'.$time)));
			echo json_encode(array('status'=>200,'download_url'=>site_url('scratch-card-generate/download/'.$token)));
			exit;


		}else{
			redirect('/');
		}
	}

	public function download_pdf()
	{
		require APPPATH.'libraries/fpdf/FPDF.php';
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		$all_cards = $this->scratch_card->get_all_batch($id);
		$pdf = new FPDF('P','mm','A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(176,10,'Scratch Card Batch Information',0,1,'C');
		$pdf->SetY(30);
		$pdf->SetFont('Arial','B',9);
		$w=24.45;
		$pdf->Cell($w,10,'Batch Number','T,R,L',0,'C');
		$pdf->Cell($w,10,'Value','T,R,L',0,'C');
		$pdf->Cell($w,10,'Total Cards','T,R,L',0,'C');
		$pdf->Cell($w,10,'Prefix','T,R,L',0,'C');
		$pdf->Cell($w,10,'Distributed','T,R,L',0,'C');
		$pdf->Cell($w,10,'Used','T,R,L',0,'C');
		$pdf->Cell($w,10,'Un-Used','T,R,L',0,'C');
		$pdf->Cell($w,10,'Created Date','T,R,L',1,'C');
		$pdf->SetFont('Arial','',9);
		foreach($all_cards as $cards){
			$pdf->Cell($w,10,$cards->batch_no,1,0,'C');
			$pdf->Cell($w,10,$cards->value,1,0,'C');
			$pdf->Cell($w,10,$cards->number_of_cards,1,0,'C');
			$pdf->Cell($w,10,$cards->prefix,1,0,'C');
			$pdf->Cell($w,10,$cards->distributed,1,0,'C');
			$pdf->Cell($w,10,$cards->used,1,0,'C');
			$pdf->Cell($w,10,$cards->unused,1,0,'C');
			$pdf->Cell($w,10,substr($cards->created_at,0,10),1,1,'C');
		}
		$dir = 'public/downloads/pdf/';
		$filename = 'scratch_card_batch_info_'.time().'.pdf';
		$pdf->Output('D',$filename);
		$file_name = $dir.$filename;
		if (file_exists($file_name)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
			header('Expires: 0');
			header("Cache-Control: no-cache, must-revalidate");
			header('Pragma: public');
			header('Content-Length: ' . filesize($file_name));
			readfile($file_name);

			if(file_exists($file_name)){
				unlink($file_name);
			}

			exit;


		}
		exit;
	}

	public function download($token)
	{
		$token = $token.'==';
		$token = base64_decode($token);
		$token = explode("####",$token);
		$userObj  = $this->user->find_by_id($this->user_id);

		if(!$userObj->has_attributes()){
			$this->session->set_flashdata('warning_messages','Session has been expired');
			redirect('scratch-card-generate');
		}

		if(!empty($token) && count($token)==3){
			$currentTime = time();
			$expireTime = $token[2];

			if($expireTime<$currentTime){
				$this->session->set_flashdata('warning_messages','Download token expired');
				redirect('scratch-card-generate');
			}

			$batch_id = $token[1];
			$data = $this->scratch_card->get_download_data($batch_id);


			require('public/extra-classes/xlsxwriter.class.php');
			$keys = array_values($data);

			$data = array_map(function($item){
						return array($item->serial_no,$item->card_no);
					}, $keys);

			array_unshift($data, array('Serial No','Card No'));

			$file_name = 'public/downloads/exports/export-scrach-cards.xlsx';

			$writer = new XLSXWriter();
			$writer->writeSheet($data);
			$writer->writeToFile($file_name);

			if (file_exists($file_name)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
				header('Expires: 0');
				header("Cache-Control: no-cache, must-revalidate");
				header('Pragma: public');
				header('Content-Length: ' . filesize($file_name));
				readfile($file_name);

				if(file_exists($file_name)){
					unlink($file_name);
				}

				exit;


			}
		}else{
			$this->session->set_flashdata('warning_messages','Download token not valid');
			redirect('scratch-card-generate');
		}
	}





	public function ajax_get_permissions()
	{
		if($this->role_type == "admin"){
			$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{
			$url_segment = $this->uri->segment(1);
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role,1,$url_segment,$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}

	public function available_list()
	{
		$this->theme->set_title('Scratch Card - Available Card List')
				->add_style('component.css')
				->add_style('custom.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/generate_card.js');

		$data['user_info']    = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
		$data['theme']        = $this->theme->get_image_path();
		$this->theme->set_view('scratch_card/available_list', $data, true);
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