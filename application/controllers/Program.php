<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Program extends BaseController 
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


		private $programCount;
		public function __construct()
		{
			parent::__construct();
			$this->load->library('services');
			$this->theme->set_theme('katniss');
			$this->theme->set_layout('main')
							->add_style('kendo/css/kendo.common-bootstrap.min.css')
             				->add_style('kendo/css/kendo.bootstrap.min.css');
			$this->programCount = 190;

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
		
		public function index()
		{
			$id= $this->user_session->id;
			$type= $this->user_session->user_type;
			$role = $this->user_session->role_id;

			if(($type == "MSO") && ($role==1)){
				$this->notification->set_unassigned_program_notification($id);
			}

			$this->theme->set_title('Dashboard - Application')->add_style('component.css')
			->add_script('cbpFWTabs.js');

			// $data['program']=$this->program->get_all();// Display data to Database


			$data['user_info'] = $this->user_session;
			$data['unassigned_programs'] = $this->program->count_unassigned_program();
			$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
			$data['theme'] = $this->theme->get_image_path();
			$this->theme->set_view('program/program',$data,true);
		}

		public function ajax_get_permissions()
		{
			if($this->role_type == "admin"){
				$permissions = array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
			}else{
				$role = $this->user_session->role_id;
				$permissions = $this->menus->has_permission($role,1,'program',$this->user_type);

			}
			echo json_encode(array('status'=>200,'permissions'=>$permissions));
		}


		public function programView(){
			header('Content-Type: application/json');
			$take = $this->input->get('take');
			$skip = $this->input->get('skip');
			$filter = $this->input->get('filter');
			$sort   = $this->input->get('sort');
			echo $this->program->showprogram("JSON", array($take, $skip,$filter,$sort));// Display data to Database
		}
		

		private function _checkProgramName($name, $update=false){
			$dataSet = $this->program->findByColName(array('program_name' => $name));
			if (count($dataSet) > 0){
				if($update){return $dataSet["0"]['lcn'];}else{return 0;}
			}
			return 1;
		}


		private function _checkLcn($lcn, $update=false){
			$dataSet = $this->program->findByColName(array('lcn' => $lcn));
			if($update){$countRow = 1;}else{$countRow = 0;}
			if (count($dataSet) > $countRow){
				return $dataSet["0"]['program_name'];
			}
			return 1;
		}

		public function save_program()
		{
			$this->form_validation->set_rules('program_name','Program Name','required|max_length[20]|trim|is_unique[programs.program_name]');

			if($this->form_validation->run() == FALSE){
				
				$this->session->set_flashdata("error_messages", "This Program Name Exists");
				redirect('program');

			}else{
				if($this->role_type == "staff") {
					$permission = $this->menus->has_create_permission($this->role_id, 1, 'program', $this->user_type);
					if (!$permission) {
						$this->session->set_flashdata('warning_messages', "Sorry! You don't have create permission");
						redirect('program');
					}
				}

				if($this->input->post('LCN') != 0){
					$programName = $this->_checkLcn($this->input->post('LCN'));
				}else{
					$programName = 1;
				}
				if($programName == 1){
					$service_id = $this->input->post('program_service_id');
					$data = array(
						'id' => $this->program->get_last_next_id(),//$this->input->post('id'),
						'program_name' => $this->input->post('program_name'),
						'LCN' => $this->input->post('LCN'),
						'program_service_id' => (!empty($service_id))? $service_id : 0,
						'program_type' => $this->input->post('program_type'),
						'visible_level' => $this->input->post('teleview_level'),
						// teleview_level
						'status' => $this->input->post('program_status'),
						'network_id' => $this->input->post('network_id'),
						'transport_stream_id' => $this->input->post('transport_stream_id'),
						'service_id' => $this->input->post('service_id'),
						'display_position' => $this->input->post('display_position'),
						'position_x' => $this->input->post('position_x'),
						'position_y' => $this->input->post('position_y'),
						'font_type' => $this->input->post('font_type'),
						'font_size' => $this->input->post('font_size'),
						'font_color' => $this->input->post('font_color'),
						'background_color' => $this->input->post('background_color'),
						'show_time' => $this->input->post('show_time'),
						'stop_time' => $this->input->post('stop_time'),
						'over_flag' => $this->input->post('over_flag'),
						'show_background_flag' => $this->input->post('show_background_flag'),
						'show_stb_number_flag' => $this->input->post('show_stb_number_flag'),
						//'created_by' => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id,

					);

					// api data 
					$api_data = array(
						'programId'         => (int)$data['id'],
						'programName'       => $data['program_name'],
						'ippvFlag'          => 0,
						'visibleLevel'      => (int)$data['visible_level'],
						'fingerPrintFlag'   => 0,
						'displayPosition'   => $data['display_position'],
						'fontSize'          => (int)$data['font_size'],
						'fontType'          => (int)$data['font_type'],
						'colorType'         => 3,
						'fontColor'         => (int)$data['font_color'],
						'backgroundColor'   => (int)$data['background_color'],
						'networkId'         => (int)$data['network_id'],
						'transportStreamId' => (int)$data['transport_stream_id'],
						'serviceId'         => (int)$data['service_id'],
						'xPosition'         => (int)$data['position_x'],
						'yPosition'         => (int)$data['position_y'],
						'showTime'          => (int)$data['show_time'],
						'stopTime'          => (int)$data['stop_time'],
						'overtFlag'         => (int)$data['over_flag'],
						'showBKFlag'        => (int)$data['show_background_flag'],
						'showSTBNumberFlag' => (int)$data['show_stb_number_flag'],
						'operatorName'      => 'administrator'
					);

					$api_string = json_encode($api_data);

					$response = $this->services->update_program($api_string);

					if($response->status == 500 || $response->status == 400){
		                $administrator_info = $this->organization->get_administrators();
		                $this->session->set_flashdata('warning_messages',$response->message.'. Please Contact with administrator. '.$administrator_info);
		                redirect('program');
		            }

		            if($response->status != 200){
		                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
		                $this->session->set_flashdata('warning_messages',$code->details);
		                redirect('program');
		            }

					if($this->program->save($data)){
						$this->notification->save_notification(null,"New Program Created","A New Program [{$api_data['program_name']}]has been created.",$this->user_session->id);					
						$this->session->set_flashdata("success_messages", "Program Created Successfully!");
						redirect('program');
					}

				}else{
					$this->session->set_flashdata("error_messages", "This LCN all ready assign for $programName");
					redirect('program');
				}
				
			}
		}

			//Edit view program

		public function edit_view($id)
		{
			if($this->role_type == "staff") {
				$permission = $this->menus->has_edit_permission($this->role_id, 1, 'program', $this->user_type);

				if (!$permission) {
					$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
					redirect('program');
				}
			}

			$data['program']=$this->program->find_by_id($id);

			$data['user_info'] = $this->user_session;

			$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
			$data['theme'] = $this->theme->get_image_path();
			$this->theme->set_view('program/editprogram',$data,true);
		}


			//Edit view program

		public function export_ac_table()
		{
			$dataSet = $this->program->export_ac_table();
			

			
			$file = "public/downloads/exports/AcTable.txt";
			/*Open the file*/
			$fp = fopen($file,"wb+");
			/*fixed tag value for AC Table 36bytes */
			$tag="0229ADD6-266C-4D4B-A1EC-8FCC586CE2BE";
			fwrite($fp,$tag);
			/*Write 1 Byte */
			$tmp = chr("0x00");
			fwrite($fp,$tmp);


			//execute the SQL query and return records
			//$query = "SELECT * FROM programs ORDER BY id ASC";
			//if($result = $mysqli->query($query)){
			if(!empty($dataSet)){
			    
			    
			    $totalChannel=count($dataSet);

			    /*Total Number of Channel*/
			    $tmp = pack("L", $totalChannel);
			    fwrite($fp,$tmp);
			    
			    //$index =0;

			    //$result = $mysqli->query($query)
			    //while ($row = $result->fetch_assoc()) {
		    	for($index=0; $index<count($dataSet); $index++){
			        //echo "ID:".$row['programID']." Name:".$row['ProgramName']."</br>" ;
			        
			        /*Specific Loop Index start Lenth 4 Byte*/
			        $tmp = pack("L", strlen($index));
			        fwrite($fp,$tmp);
			        
			       
			        for ($i = 0; $i < strlen($index); $i++) {
			           $xx =  substr("$index", $i, 1);
			            /*Specific index in ansi format*/
			            $tmp = chr($this->convertToAnsi($xx));
			            fwrite($fp,$tmp);
			            
			        }

			        
			        /*Calculate byte legth 4 Byte*/
			        $x =  strlen($dataSet[$index]['program_name']);
			        $x= $x+2 +4+2;
			        $tmp = pack("L", $x);
			        fwrite($fp,$tmp);
			        
			        /*Write Channel Name*/
			        $tmp = $dataSet[$index]['program_name'];
			        fwrite($fp,$tmp);
			        
			        /*Write Fixed Value*/
			        $tmp = chr("0x0D");
			        fwrite($fp,$tmp);
			        $tmp = chr("0x0A");
			        fwrite($fp,$tmp);
			        
			        /*Write the channel ID*/
			        $tmp = sprintf("%04X", $dataSet[$index]['id']);
			        fwrite($fp,$tmp);
			        
			        /*Write Fixed Value*/
			        $tmp = chr("0x0D");
			        fwrite($fp,$tmp);
			        $tmp = chr("0x0A");
			        fwrite($fp,$tmp);
			        
			        /*Increment Index*/
			        //$index++;
			    }

			    //$result->free();
			}


			/*Close the file*/
			fclose($fp);



			if (file_exists($file)) {
			    header('Content-Description: File Transfer');
			    header('Content-Type: application/x-download');
			    header('Content-Disposition: attachment; filename="'.basename($file).'"');
			    header('Content-Transfer-Encoding: binary');
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate');
			    header('Pragma: public');
			    header('Content-Length: ' . filesize($file));
			    readfile($file);
			    
			    if(file_exists($file)){
			    	unlink($file);
			    }

			    exit;


			}

		}

		private function convertToAnsi($indexs){
		    $desc = iconv("ISO-8859-1", "WINDOWS-1252", ord($indexs));
		 return $desc;
		}

		public function upload_file(){
			if($this->role_type == 'staff') {
				$permission = $this->menus->has_create_permission($this->role_id, 1, 'program', $this->user_type);

				if (!$permission) {
					$this->session->set_flashdata('warning_messages', "Sorry! You don't have create permission");
					redirect('program');
				}
			}

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
			    $uploaded = move_uploaded_file( $tempPath, $uploadPath );
				if(!$uploaded){
					$this->session->set_flashdata("error_messages", "Sorry! file didn't uploaded");
					redirect('/');
				}
				require('public/extra-classes/php-excel-reader/excel_reader2.php');
				require('public/extra-classes/SpreadsheetReader.php');
				try{
					$Spreadsheet = new SpreadsheetReader($uploadPath);
					$Sheets = $Spreadsheet->Sheets();
					$response = array();
					$response["success"] = array();
					$response["error"]["program"] = array();
					$response["error"]["lcn"] = array();
					// echo "<pre>";
					// print_r($Sheets);
					$data = array();
					$tempName = $tempLcn = $tempServiceId = array();
					$id = $this->program->get_last_next_id();
					foreach ($Sheets as $Index => $Name){
						$Spreadsheet->ChangeSheet($Index);

						foreach ($Spreadsheet as $key => $value) {
							if($key >= 2){
								if($this->_checkProgramName($value["0"])){
									// print_r($value);
									if($value["1"] != 0){
										$programName = $this->_checkLcn($value["1"]);
									}else{
										$programName = 1;
									}
									
									if($programName == 1){

										if(strlen($value["0"])>20){
											$this->session->set_flashdata("error_messages", "Sorry! Program Name should not more than 20 characters at line number [$key]");
											exit;
										}
										
										// Checking Duplicate Entry of name wihtin File
										if(!in_array($value["0"],$tempName,true)){
											$tempName[] = trim($value["0"]);
										}else{
											$this->session->set_flashdata("error_messages", "Sorry! Program Name (".$value["0"]. ") is duplicated.");
											exit;
										}

										if($value["1"] != 0){
											// Checking Duplicate Entry of LCN wihtin File
											if(!in_array($value["1"],$tempLcn)){
												$tempLcn[] = $value["1"];
											}else{
												$this->session->set_flashdata("error_messages", "Sorry! Duplicate number found (".$value["1"]. ") in LCN column.");
												exit;
											}
										}
										
										if($value["2"] != 0){
											// Checking Duplicate Entry of Service ID wihtin File
											if(!in_array($value["2"],$tempServiceId)){
												$tempServiceId[] = $value["2"];
											}else{
												$this->session->set_flashdata("error_messages", "Sorry! Duplicate number found (".$value["2"]. ") in Service ID column.");
												exit;
											}
										}


										$data[] = array(
											'id'  => $id++,
											'program_name' => trim($value["0"]),
											'LCN' => $value["1"],
											'program_service_id' => $value["2"],
											'program_type' => 2,
											'visible_level' => 1,
											'status' => 1,
											'network_id' => 0,
											'transport_stream_id' => 0,
											'service_id' => 0,
											'display_position' => 4,
											'position_x' => 0,
											'position_y' => 0,
											'font_type' => 0,
											'font_size' => 8,
											'font_color' => -2139062017,
											'background_color' => -1,
											'show_time' => 0,
											'stop_time' => 0,
											'over_flag' => 1,
											'show_background_flag' => 1,
											'show_stb_number_flag' => 1,
											//'created_by' => ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id
										);
										
									}else{
										$response["error"]["lcn"][] = $value["1"] .' lcn allready add for '.$programName;
									}
								}else{
									$response["error"]["program"][] = $value["0"];
								}
							}
						}
					}

					// echo count($response["success"]); exit;
					/*if(count($response["success"]) !== 0){
						$this->session->set_flashdata("success_messages", "Created Successfully for Program ". implode(", ", $response["success"]));
					}*/

					if(count($data)>$this->programCount){
						$this->session->set_flashdata("warning_messages", "Maximum 50 program support using bulk import");
						exit;
					}

					if(count($response["error"]["lcn"]) !== 0 || count($response["error"]["program"]) != 0){
						$errorMessage = "";
					}

					if(count($response["error"]["program"]) !== 0){
						$program_name_list = implode(',', $response['error']['program']);
						$errorMessage .= "These following Programs already added [" .$program_name_list."] in your system.";
					}

					if(count($response["error"]["lcn"]) != 0){
						if(!empty($errorMessage)){$errorMessage .= "<br>";}
						$errorMessage .= "Program add Faild For <br>". implode("<br>", $response["error"]["lcn"]);
					}

					if(!empty($errorMessage)){
						$this->session->set_flashdata("error_messages", $errorMessage);
						exit;
					}

					$login_with_state_data = array(
						'lcoId'=>1,
						'operatorId'=>$this->config->item('operator_id'),
						'operatorName' => 'administrator'
					);

					$login_api_string = json_encode($login_with_state_data);

					// Login to cas
					$login_response = $this->services->login_logout_with_state($login_api_string);


	                if($login_response->status == 500 || $login_response->status == 400){
	                    $administrator_info = $this->organization->get_administrators();
	                    $this->session->set_flashdata('warning_messages',$login_response->message.' Please Contact with administrator. '.$administrator_info);
	                    exit;
	                }

	                if($login_response->status != 200){
	                    $code = $this->cas_sms_response_code->get_code_by_name($login_response->type);
	                    $this->session->set_flashdata('warning_messages',$code->details);
	                	exit;
	                }

					if(!empty($data)){
						$update_response = null;
						foreach($data as $item){

							$api_data = array(
								'programId'         => (int)$item['id'],
								'programName'       => $item['program_name'],
								'ippvFlag'          => 0,
								'visibleLevel'      => (int)$item['visible_level'],
								'fingerPrintFlag'   => 0,
								'displayPosition'   => $item['display_position'],
								'fontSize'          => (int)$item['font_size'],
								'fontType'          => (int)$item['font_type'],
								'colorType'         => 3,
								'fontColor'         => (int)$item['font_color'],
								'backgroundColor'   => (int)$item['background_color'],
								'networkId'         => (int)$item['network_id'],
								'transportStreamId' => (int)$item['transport_stream_id'],
								'serviceId'         => (int)$item['service_id'],
								'xPosition'         => (int)$item['position_x'],
								'yPosition'         => (int)$item['position_y'],
								'showTime'          => (int)$item['show_time'],
								'stopTime'          => (int)$item['stop_time'],
								'overtFlag'         => (int)$item['over_flag'],
								'showBKFlag'        => (int)$item['show_background_flag'],
								'showSTBNumberFlag' => (int)$item['show_stb_number_flag'],
								'operatorName'      => 'administrator',
								'batchCommand'      => true
							);

							// here will be program entry cas api call
							$api_string = json_encode($api_data);
							$update_response = $this->services->update_program($api_string);

							if($update_response->status == 500 || $update_response->status == 400){
				                $administrator_info = $this->organization->get_administrators();
				                $this->session->set_flashdata('warning_messages',$update_response->message.'. Please Contact with administrator. '.$administrator_info);
				                exit;
				            }

				            if($update_response->status != 200){
				                $code = $this->cas_sms_response_code->get_code_by_name($update_response->type);
				                $this->session->set_flashdata('warning_messages',$code->details);
				                exit;
				            }

							if($this->program->save($item)){	
								$response["success"][] = $item["program_name"];
							}
						}

						
					}

					$logout_with_state_data = array(
						'lcoId' => 1,
						'operatorId' => $this->config->item('operator_id'),
						'operatorName' => 'administrator'
					);

					$logout_api_string = json_encode($logout_with_state_data);
					$logout_response = $this->services->login_logout_with_state($logout_api_string,false);
					

	                if($logout_response->status == 500 || $logout_response->status == 400){
	                    $administrator_info = $this->organization->get_administrators();
	                    $this->session->set_flashdata('warning_messages',$logout_response->message.' Please Contact with administrator. '.$administrator_info);
	                    exit;
	                }

	                if($logout_response->status != 200){
	                    $code = $this->cas_sms_response_code->get_code_by_name($logout_response->type);
	                    $this->session->set_flashdata('warning_messages',$code->details);
	                	exit;
	                }

	                // success message
					if(!empty($update_response) && $update_response->status == 200){
						if(!empty($update_response->type)){
			                $code = $this->cas_sms_response_code->get_code_by_name($update_response->type);
			                $this->session->set_flashdata('success_messages',$code->details);
			                exit;
						}else{
							$this->session->set_flashdata('success_messages',"Program list succesfuly imported.");
			                exit;
						}
		            }

					if(count($response["success"]) !== 0){
						$this->session->set_flashdata("success_messages", "Program list succesfuly imported.");
						exit;
					}

				}catch (Exception $E){

					// echo $E -> getMessage();
				}
			}else{
				$response["error"]["upload"] = "400";
			}

			$json = json_encode( $response );
			header('Content-Type: application/json');
		    echo $json;
		}

			//update program

		public function updateprogram()
		{

			// $lcnCheckWithName = $this->_checkLcn($this->input->post('LCN'));
			// $programNameWithLcn = $this->_checkProgramName($this->input->post('program_name'), true);

			if($this->role_type == 'staff') {
				$permission = $this->menus->has_edit_permission($this->role_id, 1, 'program', $this->user_type);

				if (!$permission) {
					$this->session->set_flashdata('warning_messages', "Sorry! You don't have edit permission");
					redirect('program');
				}
			}

			$data = array(
				'id' => $this->input->post('id'),
				'program_name' => $this->input->post('program_name'),
				'lcn' => $this->input->post('LCN'),
				'program_service_id' => $this->input->post('program_service_id'),
				'Fingerprint' => $this->input->post('Fingerprint'),
				'display_position' => $this->input->post('display_position'),
				'font_type' => $this->input->post('font_type'),
				'font_color' => $this->input->post('font_color'),
				'font_size' => $this->input->post('font_size'),
				'background_color' => $this->input->post('background_color'),
				'visible_level' => $this->input->post('visible_level'),
				'program_type' => $this->input->post('program_type'),
				'visible_level' => $this->input->post('teleview_level'),
				// teleview_level
				'status' => $this->input->post('program_status'),
				'network_id' => $this->input->post('network_id'),
				'transport_stream_id' => $this->input->post('transport_stream_id'),
				'service_id' => $this->input->post('service_id'),
				'display_position' => $this->input->post('display_position'),
				'position_x' => $this->input->post('position_x'),
				'position_y' => $this->input->post('position_y'),
				'font_type' => $this->input->post('font_type'),
				'font_size' => $this->input->post('font_size'),
				'font_color' => $this->input->post('font_color'),
				'background_color' => $this->input->post('background_color'),
				'show_time' => $this->input->post('show_time'),
				'stop_time' => $this->input->post('stop_time'),
				'over_flag' => $this->input->post('over_flag'),
				'show_background_flag' => $this->input->post('show_background_flag'),
				'show_stb_number_flag' => $this->input->post('show_stb_number_flag'),
				
				// 'color_type' => $this->input->post('color_type'),
				// 'Fingerprint' => $this->input->post('Fingerprint'),
			);

			// api data 
			$api_data = array(
				'programId'         => (int)$data['id'],
				'programName'       => $data['program_name'],
				'ippvFlag'          => 0,
				'visibleLevel'      => (int)$data['visible_level'],
				'fingerPrintFlag'   => 0,
				'displayPosition'   => $data['display_position'],
				'fontSize'          => (int)$data['font_size'],
				'fontType'          => (int)$data['font_type'],
				'colorType'         => 3,
				'fontColor'         => (int)$data['font_color'],
				'backgroundColor'   => (int)$data['background_color'],
				'networkId'         => (int)$data['network_id'],
				'transportStreamId' => (int)$data['transport_stream_id'],
				'serviceId'         => (int)$data['service_id'],
				'xPosition'         => (int)$data['position_x'],
				'yPosition'         => (int)$data['position_y'],
				'showTime'          => (int)$data['show_time'],
				'stopTime'          => (int)$data['stop_time'],
				'overtFlag'         => (int)$data['over_flag'],
				'showBKFlag'        => (int)$data['show_background_flag'],
				'showSTBNumberFlag' => (int)$data['show_stb_number_flag'],
				'operatorName'      => 'administrator'
			);

			$newProgramName = $this->input->post('program_name');
			$api_string = json_encode($api_data);

			if(strlen($newProgramName)>20)
			{
				$this->session->set_flashdata('warning_messages','Sorry! Program Name should not more than 20 characters');
                redirect('program');
			}

			$response = $this->services->update_program($api_string);

			if($response->status == 500 || $response->status == 400){
                $administrator_info = $this->organization->get_administrators();
                $this->session->set_flashdata('warning_messages',$response->message.'. Please Contact with administrator. '.$administrator_info);
                redirect('program/edit_view/'.$data['id']);
            }

            if($response->status != 200){
                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                $this->session->set_flashdata('warning_messages',$code->details);
                redirect('program/edit_view/'.$data['id']);
            }

			$oldDataSet =$this->program->findByColName(array('id' => $this->input->post('id')));
			$oldLcn = $oldDataSet[0]['lcn']; 
			$oldProgramName = $oldDataSet[0]['program_name']; 
			

			$newLcn = $this->input->post('LCN');
			


			if($oldLcn == $newLcn && $oldProgramName == $newProgramName){
				if($this->program->save($data, $data['id'])){
					$this->notification->save_notification(null,"Program Updated","Program Information {$newProgramName} has been Changed.",$this->user_session->id);						
					$this->session->set_flashdata("success_messages", "Program Updated Successfully!");
					redirect('program');
				}
			}else{
				if($oldLcn == $newLcn || $oldProgramName !== $newProgramName){
					$checkProgramName = $this->program->findByColName(array('program_name' => $newProgramName));
					if(count($checkProgramName) < 1){
						// echo "Program Name Change";
						if($this->program->save($data, $data['id'])){	
							$this->notification->save_notification(null,"Program Updated","Program Information {$newProgramName} has been Changed.",$this->user_session->id);						
							$this->session->set_flashdata("success_messages", "Program Updated Successfully!");
							redirect('program');
						}
					}else{
						$this->session->set_flashdata("error_messages", "This Program Name Exists");
						redirect('program/edit_view/'.$data['id']);
						// echo "Program Name Exists";
						// print_r($checkProgramName);
					}
				}

				if($oldProgramName === $newProgramName  &&  $oldLcn != $newLcn){
					if($newLcn == 0){
						if($this->program->save($data, $data['id'])){	
							$this->notification->save_notification(null,"Program Updated","Program Information {$newProgramName} has been Changed.",$this->user_session->id);						
							$this->session->set_flashdata("success_messages", "Program Updated Successfully!");
							redirect('program');
						}
					}else{
						$checkLcn = $this->program->findByColName(array('lcn' => $newLcn));
						if(count($checkLcn) < 1){
							// echo "Lcn Change";
							if($this->program->save($data, $data['id'])){
								$this->notification->save_notification(null,"Program Updated","Program Information {$newProgramName} has been Changed.",$this->user_session->id);							
								$this->session->set_flashdata("success_messages", "Program Updated Successfully!");
								redirect('program');
							}
						}else{
							$this->session->set_flashdata("error_messages", "This LCN all ready assign for ". $checkLcn[0]['program_name']);
							redirect('program/edit_view/'.$data['id']);
							// echo "Lcn Exists";
							// print_r($checkLcn);
							
						}
					}
				}
			}
	
		}
		public function view($id)
		{
			$this->theme->set_title('Dashboard - Application')->add_style('component.css')
			->add_script('cbpFWTabs.js');

			$data['user_info'] = $this->user_session;
			$data['program_view']= $this->program->find_by_id($id);
			$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
			$data['theme'] = $this->theme->get_image_path();
			$this->theme->set_view('program/view_program',$data,true);
		}
		
		public function program_delete($id)
		{
			if($this->role_type == 'staff') {
				$permission = $this->menus->has_delete_permission($this->role_id, 1, 'program', $this->user_type);
				if (!$permission) {
					$this->session->set_flashdata('warning_messages', "Sorry! You don't have delete permission");
					redirect('program');
				}
			}

			$program_check=$this->program->checkassign_program($id);
			$program=$program_check;
			//print_r($program);exit();
			if($program != Null){
				$this->session->set_flashdata('warning_messages','Program already is used');
				redirect('program');
			}else{
				
				$api_data = array(
					'programId' => (int)$id,
					'operatorName' => 'administrator'
				);

				$api_string = json_encode($api_data);
				$response = $this->services->delete_program($api_string);

				if($response->status == 500 || $response->status == 400){
	                $administrator_info = $this->organization->get_administrators();
	                $this->session->set_flashdata('warning_messages',$response->message.'. Please Contact with administrator. '.$administrator_info);
	                redirect('program');
	            }

	            if($response->status != 200){
	                $code = $this->cas_sms_response_code->get_code_by_name($response->type);
	                $this->session->set_flashdata('warning_messages',$code->details);
	                redirect('program');
	            }

	            if($response->status = 200){
	            	if(!empty($response->type)){
		            	$code = $this->cas_sms_response_code->get_code_by_name($response->type);
		                $this->session->set_flashdata('success_messages',$code->details);
	            	}else{
	            		$this->session->set_flashdata('success_messages', 'Program has been deleted');
	            	}
	            }

	            $program = $this->program->find_by_id($id);
	            $program_name = $program->get_attribute('program_name');
				$this->program->program_delete($id);
				$this->notification->save_notification(null,"Program Deleted","Program Information {$program_name} has been deleted.",$this->user_session->id);
				//$this->session->set_flashdata('success_messages', 'Program Data Deleted');
				redirect('program');
			}
		}

		public function export_programs()
		{
			$this->theme->set_title('Export Programs')->add_style('component.css')
			->add_script('cbpFWTabs.js');

			// $data['program']=$this->program->get_all();// Display data to Database
			//$data['user_menu']=$this->menu->get_menu_by_role();
			$fields = array(
				'program_name'=>'Program Name',
				'lcn'=>'LCN',
				'program_service_id'=>'Service ID',
				'program_type'=>'Program Type',
				'visible_level'=>'Teleview Level',
				'status' => 'Program Status',
				'network_id' => 'Network ID',
				'transport_stream_id'=>'Transport Stream ID',
				'service_id' => 'Emergency Brodcast ServiceID',
				'display_position' => 'Fingerprint Display Position',
				'position_x'=>'Position X',
				'position_y'=>'Position Y',
				'font_type'=>'Font Type',
				'font_size'=>'Font Size',
				'font_color'=>'Font Color',
				'background_color'=>'Font Background Color',
				'show_time'=>'Show Duration(sec)',
				'stop_time'=>'Stop Duration(sec)',
				'over_flag'=>'Fingerprint Over',
				'show_background_flag'=>'Show Background Color',
				'show_stb_number_flag'=>'Show STB or IC Number'

			);
			$data['fields'] = $fields;
			$data['user_info'] = $this->user_session;
			$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
			$data['theme'] = $this->theme->get_image_path();
			$this->theme->set_view('program/export_program',$data,true);
		}

		public function dump_to_xlsx()
		{
			if($this->input->post()){
				$fields = $this->input->post('field');

				if(empty($fields)){
					$this->session->set_flashdata('warning_messages','Please select fields to export.');
					redirect('export-programs');
				}
				$id = ($this->role_type == self::STAFF)? $this->parent_id: $this->user_id;
				$programs = $this->program->dump_to_xlsx($id,$fields);
				if(empty($programs)){
					$this->session->set_flashdata('warning_messages','Sorry! No program found to export');
					redirect('program');
				}

				require('public/extra-classes/xlsxwriter.class.php');	
				$keys = array_values($fields);
				
				$data = array(
				    array_map(function($item){
				    	return strtoupper(str_replace("_"," ",$item));
				    }, $keys),
				    array_map(function($item){
				    	$item="";
				    	return $item;
				    }, $keys),
				   
				);
				
				foreach(array_values($programs) as $val){
					array_push($data, array_values((array)$val));
				}
				
				$file_name = 'public/downloads/exports/export-programs.xlsx';

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
				$this->session->set_flashdata('warning_messages','Direct access not allowed');
				redirect('/');
			}
		}

	}