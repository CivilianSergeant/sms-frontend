<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Devices
 * @property Device_model $device
 */
class Devices extends BaseController
{
	protected $user_session;
	protected $user_type;
	protected $user_id;
	protected $parent_id;
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
		$this->parent_id = $this->user_session->parent_id;
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

		if(in_array($this->user_type,array('subscriber'))){
			redirect('/');
		}

	}

	/**
	* STB Provider Landing page or index page
	*/
	public function index()
	{

		$this->theme->set_title('Devices')
				->add_style('component.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
		->add_script('controllers/devices/device.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['stb_providers'] = $this->stb_provider->get_all_stb_providers();
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('devices/index',$data,true);
	}

	public function ajax_get_permissions()
	{   if($this->role_type == self::ADMIN){
			$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{
			$role = $this->user_session->role_id;
			$permissions = $this->menus->has_permission($role,1,'set-top-box',$this->user_type);
		}

		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}

	public function create_stb()
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'devices', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
				exit;
			}
		}

		// $this->form_validation->set_rules('internal_card_number', 'Internal Card Number', 'required|is_unique[set_top_boxes.internal_card_number]');
		$this->form_validation->set_rules('external_card_number', 'External Number','required|max_length[16]|min_length[16]|is_unique[devices.device_number]');
		//$this->form_validation->set_rules('stb_card_provider', 'Provider','required');
		$this->form_validation->set_rules('price','Price','required');

		if ($this->form_validation->run() == FALSE) {
			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status'=>400,'warning_messages'=>strip_tags(validation_errors())));
			} else {
				$this->session->set_flashdata('warning_messages',validation_errors());
				redirect('devices');
			}
		} else {

				$save_stb_data = array(
					// 'internal_card_number'   => $this->input->post('internal_card_number'),
					'device_number'  => $this->input->post('external_card_number'),
					//'stb_card_provider'  => $this->input->post('stb_card_provider'),
					'price'  => $this->input->post('price')
					);

				$id = ($this->role_type == self::ADMIN)? $this->user_id : $this->parent_id;
			    if($this->user_type == self::LCO_LOWER){
					$save_stb_data['lco_id'] = $id;
					$save_stb_data['lco_assigned_date'] = date('Y-m-d H:i:s');
				}
				$this->device->creat_stb($save_stb_data);
				$this->set_notification("New Device Added","Device {$save_stb_data['device_number']} has been added successfully");
				echo json_encode(array('status'=>200, 'success_messages'=>'Device ' . $save_stb_data['device_number'] . ' created successfully'));
		}
	}

	public function edit_set_top_box($id)
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'devices', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('devices');
			}
		}

		$this->theme->set_title('Edit Device')->add_style('component.css')
		->add_script('controllers/devices/device.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		//$data['stb_providers'] = $this->stb_provider->get_all_stb_providers();
		$data['stbs'] = $this->device->stb_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('devices/edit_device',$data,true);
	}

	/**
	 * @author Name
	 */
	public function update_action()
	{
		if($this->role_type == 'staff') {
			$permission = $this->menus->has_edit_permission($this->role_id, 1, 'devices', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
				redirect('devices');
			}
		}

		//$this->form_validation->set_rules('internal_card_number', 'Internal Card Number', 'required');
		$this->form_validation->set_rules('external_card_number', 'External Number','required');
		$this->form_validation->set_rules('stb_card_provider', 'Provider','required');
		$this->form_validation->set_rules('price','Price','required');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('warning_messages',validation_errors());
			redirect('devices/edit/' . $this->input->post('id'));
		} else {
			$stb_data = $array = array(
				'id'   => $this->input->post('id'),
				//'internal_card_number'   => $this->input->post('internal_card_number'),
				'device_number'  => $this->input->post('external_card_number'),

				'price'  => $this->input->post('price'),
				'updated_by'  => $this->user_id,
				'updated_at'  => date('Y-m-d H:i:s')
				);

			//test($stb_data);
			$update = $this->device->save($stb_data, $stb_data['id']);
			if ($update) {
				$this->set_notification("Device Info updated","Device {$stb_data['device_number']} information has been changed");
				$this->session->set_flashdata('success', 'Device ' . $stb_data['device_number'] . ' Updated Successfully');
				redirect('devices');
			}
		}
	}


	public function view_stb($id)
	{

		$this->theme->set_title('Device Detail')->add_style('component.css')
		->add_script('controllers/devices/device.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		//$data['stb_providers'] = $this->stb_provider->get_all_stb_providers();
		$data['stbs'] = $this->device->stb_by_id($id);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('devices/view_device',$data,true);
	}

	public function ajax_load_stb()
	{
		$id = ($this->role_type == self::STAFF)? $this->created_by : $this->user_id;
		$take = $this->input->get('take');
		$skip = $this->input->get('skip');
		$filter = $this->input->get('filter');
		$sort = $this->input->get('sort');
		$user_type=$this->user_type;
		$all_stb = $this->device->get_all_stb($id, array($take, $skip,$filter,$sort),$user_type);
		echo json_encode(array('status'=>200,'stb'=>$all_stb));
	}

	public function export_stb()
	{

		if($this->input->post()){
			$card_provider = $this->input->post('stb_card_provider');
			$price = $this->input->post('price');
			require('public/extra-classes/xlsxwriter.class.php');
			$data = array(
			    array('Dedvice Number','Price'),
			    array('Use Text Format','Keep it Same'),
			    array('',$price)
			);
			$file_name = 'public/downloads/exports/device-template.xlsx';
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

	public function import_stb()
	{
		if($this->role_type == self::STAFF) {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'devices', $this->user_type);
			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
				exit;
			}
		}

		$id = ($this->role_type == self::ADMIN)? $this->user_id : $this->parent_id;

		if(!empty($_FILES)){

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
								$stb_card_provider = trim($value[1]);
								$price = trim($value[2]);
								
								if((strlen($card_number)!=16))
								{
									echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Card number must be 16 digit long at line no '.($i+1)));
									exit;
								}
								
								$exist = $this->set_top_box->get_stb_by_ext_card_number($card_number);
								if(count($exist)>0){
									echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Device number '.$card_number.' already exist'));
									exit;
								}

								$save_card_data[] = array(

									'device_number' => $card_number,
									'price' => $price,
									'created_at' => date('Y-m-d H:i:s'),
									'subscriber_id' => null,
									'lco_id' => ($this->user_type == self::LCO_LOWER)? $id : null,
									'is_used' => 0,
									'used_date' => null,
									'lco_assigned_date' => ($this->user_type == self::LCO_LOWER)? date('Y-m-d H:i:s') : null
								);
								

							}
						}
					}
				}

				if(!empty($save_card_data)){
					foreach($save_card_data as $data){
						$this->device->save($data);
					}
					$this->set_notification("Device Imported","Device numbers Successfully imported");
					echo json_encode(array('status'=>200,'success_messages'=>'Device numbers Successfully imported'));
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