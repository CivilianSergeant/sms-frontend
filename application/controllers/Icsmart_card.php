<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Icsmart_card extends BaseController 
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
		$this->theme->set_title('Dashboard - Application')
				->add_style('component.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
				->add_script('controllers/ic.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['card_providers'] = $this->ic_smart_provider->get_all_ic_providers();
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('icsmart_card/icsmart_card',$data,true);
	}

	public function ajax_get_permissions()
	{
		if($this->role_type == 'admin'){
			$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else {
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role, 1, 'icsmart-card', $this->user_type);
		}
		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}


	protected function checkCardNumber($inputID){

		$returnArray = array();
		if(strlen($inputID) == 16){
			$tempArray = str_split($inputID);

			foreach ($tempArray as $key => $value) {
				if(is_int((int)$value)){
					switch ($key) {
						case '0':
						$returnArray['T_R_INDUSTRY'] = (int)$tempArray[0];
						break;
						case '1':
						$returnArray['OPERATOR_ID'] = $tempArray[1];
						break;
						case '2':
						$returnArray['OPERATOR_ID'] .= $tempArray[2];
						break;
						case '3':
						$returnArray['OPERATOR_ID'] .= $tempArray[3];
						break;
						case '4':
						$returnArray['OPERATOR_ID'] .= $tempArray[4];
						break;
						case '5':
						$returnArray['VENDER_ID'] = $tempArray[5];
						break;
						case '6':
						$returnArray['VENDER_ID'] .= $tempArray[6];
						break;
						case '7':
						$returnArray['INTERNAL_ID'] = $tempArray[7];
						break;
						case '8':
						$returnArray['INTERNAL_ID'] .= $tempArray[8];
						break;
						case '9':
						$returnArray['INTERNAL_ID'] .= $tempArray[9];
						break;
						case '10':
						$returnArray['INTERNAL_ID'] .= $tempArray[10];
						break;
						case '11':
						$returnArray['INTERNAL_ID'] .= $tempArray[11];
						break;
						case '12':
						$returnArray['INTERNAL_ID'] .= $tempArray[12];
						break;
						case '13':
						$returnArray['INTERNAL_ID'] .= $tempArray[13];
						break;
						case '14':
						$returnArray['INTERNAL_ID'] .= $tempArray[14];
						break;
						case '15':
						$returnArray['SECURITY_ID'] = $tempArray[15];
						break;
						default:
						return "No";
						break;
					}
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
		foreach ($returnArray as $key => $value) {
			$returnArray[$key] = (int)$value;
		}
		return $returnArray;
	}

	public function testExNum()
	{
		$data['organization']=$this->organization->get_row();
		$inputID = $this->input->post('external_card_number');
		$chkExNum = $this->checkCardNumber($inputID);
		$opetator_id = $data['organization']->operator_id;
		if ($chkExNum['OPERATOR_ID'] != $opetator_id) {
			echo json_encode(array('status'=>400,'warning_messages'=> 'Invalid Card Number'));
		} else {				
			echo json_encode(array('inter_card_num'=> $chkExNum['INTERNAL_ID']));
		}
		
	}

	public function create_ic_smartcard()
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'icsmart-card', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
				exit;
			}
		}

		$this->form_validation->set_rules('internal_card_number', 'Internal Card Number', 'required|is_unique[smart_cards.internal_card_number]');
		$this->form_validation->set_rules('external_card_number', 'External Number','required|max_length[16]|min_length[16]');
		$this->form_validation->set_rules('smart_card_provider', 'Provider','required');
		$this->form_validation->set_rules('price','price','required');

		if ($this->form_validation->run() == FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('icsmart_card');
			}
		} else {
			$chkExNum = $this->checkCardNumber($this->input->post('external_card_number'));

			$opetator_id = $this->config->item('operator_id');
			if ($chkExNum['OPERATOR_ID'] != $opetator_id) {
				echo json_encode(array('status'=>400,'warning_messages'=> 'Invalid Card Number'));
			} else {
				$save_ic_data = $array = array(
					'internal_card_number'   => $this->input->post('internal_card_number'),
					'external_card_number'  => $this->input->post('external_card_number'),
					'smart_card_provider'  => $this->input->post('smart_card_provider'),
					'price'  => $this->input->post('price')
					);

				$this->ic_smartcard->creat_ic_smart_card($save_ic_data);
				$this->set_notification("New Smartcard Added","SmartCard {$save_ic_data['external_card_number']} has been added");
				echo json_encode(array('status'=>200, 'success_messages'=>'IC or Smartcard ' . $save_ic_data['internal_card_number'] . ' created successfully'));
			}
		}
	}

	public function edit_ic_smartcard($id)
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'icsmart-card', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('icsmart-card');
			}
		}

		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
		->add_script('controllers/ic.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['card_providers'] = $this->ic_smart_provider->get_all_ic_providers();
		$data['ic_smartcards'] = $this->ic_smartcard->ic_smartcards_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('icsmart_card/edit_icsmart_card',$data,true);
	}

	public function update_action()
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'icsmart-card', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('icsmart-card');
			}
		}

		$this->form_validation->set_rules('internal_card_number', 'Internal Card Number', 'required');
		$this->form_validation->set_rules('external_card_number', 'External Number','required');
		$this->form_validation->set_rules('smart_card_provider', 'Provider','required');
		$this->form_validation->set_rules('price','price','required');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('warning_messages',validation_errors());
			redirect('update-ic-smartcard/' . $this->input->post('id'));
		} else {
			$ic_data = $array = array(
				'id'   => $this->input->post('id'),
				'internal_card_number'   => $this->input->post('internal_card_number'),
				'external_card_number'  => $this->input->post('external_card_number'),
				'smart_card_provider'  => $this->input->post('smart_card_provider'),
				'price'  => $this->input->post('price'),
				'updated_by'  => $this->user_id,
				'updated_at'  => date('Y-m-d H:i:s')
				);

			$update = $this->ic_smartcard->save($ic_data, $ic_data['id']);
			if ($update) {
				$this->set_notification("Smartcard Info updated","SmartCard {$ic_data['external_card_number']} information has been changed");
				$this->session->set_flashdata('success', 'IC or Smartcard ' . $ic_data['external_card_number'] . ' Updated Successfully');
				redirect('icsmart-card');
			}
		}
	}


	public function view_ic_smartcard($id)
	{
		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
		->add_script('controllers/ic.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['card_providers'] = $this->ic_smart_provider->get_all_ic_providers();
		$data['ic_smartcards'] = $this->ic_smartcard->ic_smartcards_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('icsmart_card/view_icsmart_card',$data,true);
	}

	public function ajax_load_ic_smartcards()
	{
		$take = $this->input->get('take');
        $skip = $this->input->get('skip');
		$id = ($this->role_type)? $this->parent_id : $this->user_id;
		$all_ic_smartcard = $this->ic_smartcard->get_all_ic_smart_card($id,$take,$skip);
		echo json_encode(array(
			'status'=>200,
			'ic_smartcard'=>$all_ic_smartcard,
			'total' => $this->ic_smartcard->get_count_card($id)
			));
	}

	public function export_template()
	{
		if($this->input->post()){
			$card_provider = $this->input->post('smart_card_provider');
			$price = $this->input->post('price');
			require('public/extra-classes/xlsxwriter.class.php');
			$data = array(
			    array('External Card Number','Smart-Card Provider','Price'),
			    array('Use Text Format','Keep it Same','Keep it Same'),
			    array('',$card_provider,$price)
			);
			$file_name = 'public/downloads/exports/smartcard-template.xlsx';
			$writer = new XLSXWriter();
			
			$writer->writeSheet($data);
			$writer->writeToFile($file_name);

			if (file_exists($file_name)) {
			    header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate');
			    header('Pragma: public');
			    header('Content-Length: ' . filesize($file_name));
			    readfile($file_name);
			    
			    if(file_exists($file_name)){
			    	unlink($file_name);
			    }

			    exit;


			}
		} else {
			$this->session->set_flashdata('Warning','Direct access not allowed');
			redirect('/');
		}
	}


	public function import_cards()
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'icsmart-card', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
				exit;
			}
		}

		if(!empty($_FILES)){
			$organization=$this->organization->get_row();
			$opetator_id = $organization->operator_id;
		
			$tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
		    $uploadPath = 'public/uploads/templats' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
		    $types = array(
		    	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		    );

		    if(!in_array($_FILES['file']['type'],$types))
		    {
		    	$this->session->set_flashdata('warning_messages','Sorry! File type must be in (.xlsx) format');
		    	exit;
		    }

		    move_uploaded_file($tempPath, $uploadPath);
		    //require('public/extra-classes/php-excel-reader/excel_reader2.php');
			//require('public/extra-classes/SpreadsheetReader.php');
			require('public/extra-classes/XLSXReader.php');
			try{
				$save_card_data = array();

				if(file_exists($uploadPath)){
					
					$xlsx = new XLSXReader($uploadPath);
					$sheets = $xlsx->getSheetNames();
					foreach($sheets as $i=> $sheet){
						$data = $xlsx->getSheetData($sheet);
						foreach($data as $i => $value){
							if($i>=2){

								$card_number = trim($value[0]);
								$chkExNum = $this->checkCardNumber($card_number);
								$card_provider = trim($value[1]);
								$price = trim($value[2]);

								if((strlen($card_number)!=16))
								{
									echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Card number must be 16 digit long at line no '.($i+1)));
									exit;
								}
								
								$exist = $this->ic_smartcard->get_card_by_ext_card_number($card_number);
								
								if(!empty($exist)>0){
									echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Card number '.$card_number.' already exist'));
									exit;
								}
								
								if ($chkExNum['OPERATOR_ID'] != $opetator_id) {
									echo json_encode(array('status'=>400,'warning_messages'=> 'Invalid Card Number'));
									exit;
								}

								$save_card_data[] = array(
									'internal_card_number' =>$chkExNum['INTERNAL_ID'],
									'external_card_number' => $card_number,
									'smart_card_provider'    => $card_provider,
									'price' => $price,
									'created_at' => date('Y-m-d H:i:s'),
									'subscriber_id' => null,
									'lco_id' => null,
									'is_used' => 0,
									'used_date' => null,
									'lco_assigned_date' => null
								);
								

							}
						}
					}
				}
				
				if(!empty($save_card_data)){
					foreach($save_card_data as $data){
						$this->ic_smartcard->save($data);
					}
					$this->set_notification("IC Card Imported","IC Card numbers Successfully imported");
					echo json_encode(array('status'=>200,'success_messages'=>'STB Card numbers Successfully imported'));
					exit;
				}
				
			}catch(Exception $ex){

			}

		} else {
			$this->session->set_flashdata('Warning','Direct access not allowed');
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