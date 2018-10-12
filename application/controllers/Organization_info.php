<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Organization_info
 * @property Organization_model $organization
 * @property Png_compressor $png_compressor
 */
class Organization_info extends BaseController 
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

	public function __construct()
	{
		parent::__construct();
		$this->theme->set_theme('katniss');
		$this->theme->set_layout('main');
		$this->load->library('png_compressor');

		$this->user_type = strtolower($this->user_session->user_type);
		$this->user_id = $this->user_session->id;
		$this->created_by = $this->user_session->created_by;

		$role = $this->user->get_user_role($this->user_id);
		$role_name = (!empty($role))?  strtolower($role->role_name) : '';
		$role_type = (!empty($role))?  strtolower($role->role_type) : '';
		$this->role_name = $role_name;
		$this->role_type = $role_type;
		$this->role_id = $this->user_session->role_id;

		if(in_array($this->user_type,array('subscriber'))){
			redirect('/');
		}

	}

	/**
	* Package Landing page or index page
	*/
	public function index()
	{
		$this->load->model('Settings_model','settings');
		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
		->add_script('cbpFWTabs.js');
		$role_id = $this->user_session->role_id;
		if($this->role_type == "admin"){
			$data['permissions'] = (object)array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
		}else{
			$data['permissions'] = $this->menus->has_permission($role_id,1,'organization',$this->user_type);
		}

		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$parent_id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
		$this->db->from('organization_info');
		$this->db->where('parent_id',$parent_id);
		$q = $this->db->get();

		$data['organization'] = $q->row();
		$data['settings'] = $this->settings->find_api_settings(1);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('organization/organization_info',$data,true);
	}
	
	public function save_organaization()
	{
		if($this->role_type == "staff") {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'organization', $this->user_type);
			if (!$permission) {
				$this->session->set_flashdata('warning_messages', "Sorry! You don't have create permission");
				redirect('organization');
				exit;
			}
		}

		$tmp_uploaded_file = $_FILES['userfile']['tmp_name'];
		$tmp_uploaded_file_icon = $_FILES['iconfile']['tmp_name'];
		$this->form_validation->set_rules('organization_name','organization Name','required|trim|is_unique[organization_info.organization_name]');
		$this->form_validation->set_rules('organization_phone','Organization Phone','required');
		$this->form_validation->set_rules('organization_email','Organization Email','required');
		// $this->form_validation->set_rules('copyright_year','Organization Year','required');
		// $this->form_validation->set_rules('gift_amount','Gift Amount','required');
		// $this->form_validation->set_rules('administrator1','System Administrator 1','required');
		// $this->form_validation->set_rules('phone1','Administrator Phone 1','required');
		// $this->form_validation->set_rules('administrator1','System Administrator 2','required');
		// $this->form_validation->set_rules('phone2','Administrator Phone 2','required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('warning_messages',validation_errors());
			redirect('organization');
		}else{
			$file_path = null;
			$icon_file_path = null;
			if(!empty($_FILES['userfile']['name'])){


				$filesize= (1*(1024*1024));
				
				if($tmp_uploaded_file > $filesize && $tmp_uploaded_file_icon > $filesize){
					$this->session->set_flashdata('warning_messages','Sorry File size shuld be within 2 mb');
			 			redirect('organization');
			 			exit;
				}
				if(!preg_match('/(jpg|jpeg|png|gif)/',$_FILES['userfile']['type'])){
							
					$this->session->set_flashdata('warning_messages','Sorry File Type shuld be one of (png) This Format');
					redirect('organization');
					exit;
				}
				if(!preg_match('/(jpg|jpeg|png|gif)/',$_FILES['iconfile']['type'])){
							
					$this->session->set_flashdata('warning_messages','Sorry File Type shuld be one of (png) This Format');
					redirect('organization');
					exit;
				}
				$logo_type = (substr($_FILES['userfile']['type'],(strrpos($_FILES['userfile']['type'],'/')+1),strlen($_FILES['userfile']['type'])));
				$file_path = ORG_PATH.'logo.'.$logo_type;
				if($tmp_uploaded_file!=null){
					move_uploaded_file($tmp_uploaded_file,$file_path);

				}

				$icon_type = (substr($_FILES['iconfile']['type'],(strrpos($_FILES['iconfile']['type'],'/')+1),strlen($_FILES['iconfile']['type'])));
				$icon_file_path = ORG_PATH.'favicon.'.$icon_type;
				if($tmp_uploaded_file_icon != null){
					move_uploaded_file($tmp_uploaded_file_icon,$icon_file_path);
				}

			}

			$data = array(
				'organization_name' => $this->input->post('organization_name'),
				'organization_phone' => $this->input->post('organization_phone'),
				'organization_email' => $this->input->post('organization_email'),
				'operator_id' => $this->input->post('operator_id'),
				'copyright_year' => $this->input->post('copyright_year'),
				'gift_amount' => $this->input->post('gift_amount'),
				'administrator1' => $this->input->post('administrator1'),
				'phone1' => $this->input->post('phone1'),
				'is_show' => $this->input->post('is_show'),
				'administrator2' => $this->input->post('administrator2'),
				'phone2' => $this->input->post('phone2'),
				'logo' => $file_path,
				'icon'=> $icon_file_path,
                                'about_us' => $this->input->post('about_us')
				);
			$this->organization->save($data);
			$this->session->set_flashdata('success_messages', 'Organization Information Create Success ');				
			redirect('organization');
		}		
	}

	public function update_organaization($id)
	{
		$this->load->model('Settings_model','settings');
		if($this->role_type != self::ADMIN){
			$permission = $this->menus->has_edit_permission($this->role_id,1,'organization',$this->user_type);
			if(!$permission){
				$this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
				redirect('organization');
				exit;
			}
		}

				
		$tmp_uploaded_file = $_FILES['userfile']['tmp_name'];
		$tmp_uploaded_file_icon = $_FILES['iconfile']['tmp_name'];

		$this->form_validation->set_rules('organization_name','Organization Name','required');
		$this->form_validation->set_rules('organization_phone','Organization Phone','required');
		$this->form_validation->set_rules('organization_email','Organization Email','required');
		//$this->form_validation->set_rules('operator_id','Organization ID','required');
		// $this->form_validation->set_rules('copyright_year','Copyright Year','required');
		// $this->form_validation->set_rules('gift_amount','Gift Amount','required');
		// $this->form_validation->set_rules('administrator1','System Administrator 1','required');
		// $this->form_validation->set_rules('phone1','Administrator Phone 1','required');
		// $this->form_validation->set_rules('administrator1','System Administrator 2','required');
		// $this->form_validation->set_rules('phone2','Administrator Phone 2','required');
		if($this->form_validation->run()==FALSE){
			
			$this->session->set_flashdata('warning_messages',validation_errors());
			redirect('organization');
			
		}else{			
			$data = array(
					'organization_name' => $this->input->post('organization_name'),
					'organization_phone' => $this->input->post('organization_phone'),
					'organization_email' => $this->input->post('organization_email'),
					'operator_id' => $this->input->post('operator_id'),
					'copyright_year' => $this->input->post('copyright_year'),
					'gift_amount' => $this->input->post('gift_amount'),
					'administrator1' => $this->input->post('administrator1'),
					'phone1' => $this->input->post('phone1'),
					'is_show' => $this->input->post('is_show'),
					'administrator2' => $this->input->post('administrator2'),
					'phone2' => $this->input->post('phone2'),
                                        'about_us' => $this->input->post('about_us')
					);
			if(!empty($tmp_uploaded_file_icon) ){
				$filesize= (1024*1024);
				if($tmp_uploaded_file_icon > $filesize ){
					$this->session->set_flashdata('warning_messages','Sorry File size shuld be within 2 mb');
					redirect('organization');
					exit;
				}
				if(!preg_match('/(jpg|jpeg|png|gif)/', $_FILES['iconfile']['type'])){
						
					$this->session->set_flashdata('warning_messages','Sorry File Type shuld be one of (png) This Format');
					redirect('organization');
					exit;
				}
				$icon_type = (substr($_FILES['iconfile']['type'],(strrpos($_FILES['iconfile']['type'],'/')+1),strlen($_FILES['iconfile']['type'])));
				$icon_file_path = ORG_PATH.'favicon.'.$icon_type;
				move_uploaded_file($tmp_uploaded_file_icon,$icon_file_path);

				$data['icon'] = $icon_file_path;
			}
			if(!empty($tmp_uploaded_file)){
				//if(!empty($tmp_uploaded_file)){

					$filesize= (1*(1024*1024));

					if($tmp_uploaded_file > $filesize){

						$this->session->set_flashdata('warning_messages','Sorry File size shuld be within 2 mb');
			 			redirect('organization');
			 			exit;
					}
					if(!preg_match('/(jpg|jpeg|png|gif)/',$_FILES['userfile']['type'])){
						
						$this->session->set_flashdata('warning_messages','Sorry File Type shuld be one of (png) This Format');
						redirect('organization');
						exit;
					}
				//}
				$logo_type = (substr($_FILES['userfile']['type'],(strrpos($_FILES['userfile']['type'],'/')+1),strlen($_FILES['userfile']['type'])));
				$file_path = ORG_PATH.'logo.'.$logo_type;
				move_uploaded_file($tmp_uploaded_file,$file_path);
				$data['logo'] = $file_path;
				
			}
			$this->organization->save($data,$id);

			$this->settings->setTblInstance('api_settings');

			$this->settings->save(array(
				'reg_email' => $this->input->post('organization_reg_email'),
				'reg_email_password' => $this->input->post('organization_reg_email_pass'),
				'is_email_send' => ($this->input->post('is_email_send') == "on")? 1 : 0,
				'is_sms_send' => ($this->input->post('is_sms_send') == "on")? 1 : 0,
				'confirm_code_template' => $this->input->post('confirm_code_template'),
				'email_from_template' => $this->input->post('email_from_template')
			),1);

			$this->notification->save_notification(null,"Organization Info Updated","Organization Information has been Changed",$this->user_session->id);
			$this->session->set_flashdata('success_messages', 'Organization Information Updated');
       		redirect('organization');
		}

	}

	public function default_logo($id)
	{
		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
				->add_script('cbpFWTabs.js');
		$role_id = $this->user_session->role_id;
		if($this->role_type == "admin"){
			$data['permissions'] = (object)array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
		}else{
			$data['permissions'] = $this->menus->has_permission($role_id,1,'organization',$this->user_type);
		}

		$this->load->model('Default_image_size','default_image_size');
        $imageSize = $this->default_image_size->getContentImageSizes();

		$data['id'] = $id;
		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$parent_id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
		$this->db->from('organization_info');
		$this->db->where('parent_id',$parent_id);
		$q = $this->db->get();
		$data['organization'] = $q->row();
		$data['theme'] = $this->theme->get_image_path();
        $data['imageSize'] = $imageSize;
		$this->theme->set_view('organization/default_logo',$data,true);

	}

	public function default_hls($id)
	{
		$this->theme->set_title('Dashboard - Application')->add_style('component.css')
				->add_script('cbpFWTabs.js');
		$role_id = $this->user_session->role_id;
		if($this->role_type == "admin"){
			$data['permissions'] = (object)array('create_permission'=>1,'edit_permission'=>1,'view_permission'=>1,'delete_permission'=>1);
		}else{
			$data['permissions'] = $this->menus->has_permission($role_id,1,'organization',$this->user_type);
		}
		$data['id'] = $id;
		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$parent_id = ($this->role_type == self::ADMIN)? $this->user_id : $this->user_session->parent_id;
		$this->db->from('organization_info');
		$this->db->where('parent_id',$parent_id);
		$q = $this->db->get();
		$data['organization'] = $q->row();
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('organization/default_hls',$data,true);

	}

	public function upload_logo()
	{
		if($this->role_type != self::ADMIN){
			$permission = $this->menus->has_edit_permission($this->role_id,1,'organization',$this->user_type);
			if(!$permission){
				$this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
				redirect('organization');
				exit;
			}
		}

		$mbSize = 1*(1024*1024);
		$id = $this->input->post('id');
		$organization = $this->organization->find_by_id($id);
		if(!$organization->has_attributes()){
			$this->session->set_flashdata('warning_messages','Sorry! no organization info found');
			redirect('dashboard');
		}

        $this->load->model('Default_image_size','default_image_size');
        $imageSize = $this->default_image_size->getContentImageSizes();

		if(!empty($_FILES['web_logo']) && !empty($_FILES['web_logo']['tmp_name'])){

			$type = $_FILES['web_logo']['type'];
            $type = (substr($type,(strrpos($type,'/')+1),strlen($type)));
			$size = $_FILES['web_logo']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Web Logo file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['web_logo']['name'];
			$location = 'public/uploads/logo/';

			$main_default_web_logo = $location.$filename;
            $tmp_file_name = $_FILES['web_logo']['tmp_name'];

			if(!empty($size) && ($size>0 && $size<=$mbSize)){


				try {
                    if(move_uploaded_file($tmp_file_name,$main_default_web_logo)){

                        $w = $imageSize['advance']['WEB_LOGO']['width'];
                        $h = $imageSize['advance']['WEB_LOGO']['height'];

                        $to_path = $location.$w.'x'.$h;
                        if(@file_exists($location)){
                            @mkdir($to_path,0777);
                        }else{
                            @mkdir($to_path,0777,true);
                        }

                        $temp_path = $to_path.'/main-default-web-logo-'.$w.'x'.$h.'.'.$type;
                        $final_path = $to_path.'/default-web-logo-'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;


                        $compressResponse = $this->png_compressor->compress_png($main_default_web_logo,$temp_path,$final_path,$w,$h);
                        if(file_exists($organization->get_attribute('default_web_logo'))){
                            @unlink($organization->get_attribute('default_web_logo'));
                        }

                        $output = implode(',',$compressResponse['output']);
                        if(preg_match('/error/',strtolower($output))){
                            $this->session->set_flashdata('warning_messages',$output);
                            redirect('organization/default-logo/'.$id);
                        }

                        $this->organization->save(array(
                            'default_web_logo' => $final_path
                        ),$id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}


			}else{
				$this->session->set_flashdata('warning_messages','Web Logo file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		if(!empty($_FILES['stb_logo']) && !empty($_FILES['stb_logo']['tmp_name'])){
			$type = $_FILES['stb_logo']['type'];
            $type = (substr($type,(strrpos($type,'/')+1),strlen($type)));
			$size = $_FILES['stb_logo']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','STB Logo file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['stb_logo']['name'];
			$location = 'public/uploads/logo/';

			$main_default_stb_logo = $location.$filename;
            $tmp_file_name = $_FILES['stb_logo']['tmp_name'];

			if(!empty($size) && ($size>0 && $size<=$mbSize)){

				try {

                    $w = $imageSize['advance']['STB_LOGO']['width'];
                    $h = $imageSize['advance']['STB_LOGO']['height'];

                    if(move_uploaded_file($tmp_file_name,$main_default_stb_logo)){

                        $to_path = $location.$w.'x'.$h;
                        if(@file_exists($location)){
                            @mkdir($to_path,0777);
                        }else{
                            @mkdir($to_path,0777,true);
                        }

                        $temp_path = $to_path.'/main-default-stb-logo-'.$w.'x'.$h.'.'.$type;
                        $final_path = $to_path.'/default-stb-logo-'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;

                        $compressResponse = $this->png_compressor->compress_png($main_default_stb_logo,$temp_path,$final_path,$w,$h);
                        if(file_exists($organization->get_attribute('default_stb_logo'))){
                            @unlink($organization->get_attribute('default_stb_logo'));
                        }

                        $output = implode(',',$compressResponse['output']);
                        if(preg_match('/error/',strtolower($output))){
                            $this->session->set_flashdata('warning_messages',$output);
                            redirect('organization/default-logo/'.$id);
                        }

                        $this->organization->save(array(
                            'default_stb_logo' => $final_path
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}
			}else{
				$this->session->set_flashdata('warning_messages','STB Logo file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		if(!empty($_FILES['mobile_logo']) && !empty($_FILES['mobile_logo']['tmp_name'])){
			$type = $_FILES['mobile_logo']['type'];
            $type = (substr($type,(strrpos($type,'/')+1),strlen($type)));
			$size = $_FILES['mobile_logo']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Mobile Logo file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['mobile_logo']['name'];
			$location = 'public/uploads/logo/';
			/*if(file_exists($location.$organization->get_attribute('default_mobile_logo'))){
				@unlink($location.$organization->get_attribute('default_mobile_logo'));
			}*/
			$main_default_mobile_logo = $location.$filename;
            $tmp_file_name = $_FILES['mobile_logo']['tmp_name'];
			if(!empty($size) && ($size>0 && $size<=$mbSize)){

				try {

                    $w = $imageSize['advance']['MOBILE_LOGO']['width'];
                    $h = $imageSize['advance']['MOBILE_LOGO']['height'];

                    if(move_uploaded_file($tmp_file_name,$main_default_mobile_logo)){

                        $to_path = $location.$w.'x'.$h;
                        if(@file_exists($location)){
                            @mkdir($to_path,0777);
                        }else{
                            @mkdir($to_path,0777,true);
                        }

                        $temp_path = $to_path.'/main-default-mobile-logo-'.$w.'x'.$h.'.'.$type;
                        $final_path = $to_path.'/default-mobile-logo-'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;

                        $compressResponse = $this->png_compressor->compress_png($main_default_mobile_logo,$temp_path,$final_path,$w,$h);
                        if(file_exists($organization->get_attribute('default_mobile_logo'))){
                            @unlink($organization->get_attribute('default_mobile_logo'));
                        }

                        $output = implode(',',$compressResponse['output']);
                        if(preg_match('/error/',strtolower($output))){
                            $this->session->set_flashdata('warning_messages',$output);
                            redirect('organization/default-logo/'.$id);
                        }

                        $this->organization->save(array(
                            'default_mobile_logo' => $final_path
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}

			}else{
				$this->session->set_flashdata('warning_messages','Mobile Logo file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

        if(!empty($_FILES['web_poster']) && !empty($_FILES['web_poster']['tmp_name'])){
            $type = $_FILES['web_poster']['type'];
            $type = (substr($type,(strrpos($type,'/')+1),strlen($type)));
            $size = $_FILES['web_poster']['size'];
            if(!preg_match('/png/',$type)){
                $this->session->set_flashdata('warning_messages','Web Poster file type must be (*.png)');
                redirect('organization/default-logo/'.$id);
            }

            $filename = $_FILES['web_poster']['name'];
            $location = 'public/uploads/logo/';
            /*if(file_exists($location.$organization->get_attribute('default_poster_mobile'))){
                @unlink($location.$organization->get_attribute('default_poster_mobile'));
            }*/
            $main_default_web_poster = $location.$filename;
            $tmp_file_name = $_FILES['web_poster']['tmp_name'];

            if(!empty($size) && ($size>0 && $size<=$mbSize)){

                try {

                    $w = $imageSize['advance']['WEB_POSTER']['width'];
                    $h = $imageSize['advance']['WEB_POSTER']['height'];

                    if(move_uploaded_file($tmp_file_name,$main_default_web_poster)){
                        $to_path = $location.$w.'x'.$h;
                        if(@file_exists($location)){
                            @mkdir($to_path,0777);
                        }else{
                            @mkdir($to_path,0777,true);
                        }

                        $temp_path = $to_path.'/main-default-web-poster-'.$w.'x'.$h.'.'.$type;
                        $final_path = $to_path.'/default-web-poster-'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;

                        $compressResponse = $this->png_compressor->compress_png($main_default_web_poster,$temp_path,$final_path,$w,$h);
                        if(file_exists($organization->get_attribute('default_poster_web'))){
                            @unlink($organization->get_attribute('default_poster_web'));
                        }

                        $output = implode(',',$compressResponse['output']);
                        if(preg_match('/error/',strtolower($output))){
                            $this->session->set_flashdata('warning_messages',$output);
                            redirect('organization/default-logo/'.$id);
                        }

                        $this->organization->save(array(
                            'default_poster_web' => $final_path
                        ), $id);
                    }

                }catch(Exception $ex){
                    $this->session->set_flashdata('warning_messages',$ex->getMessage());
                    redirect('organization/default-logo/'.$id);
                }


            }else{
                $this->session->set_flashdata('warning_messages','Web Poster file size must be with 1MB');
                redirect('organization/default-logo/'.$id);
            }

        }

        if(!empty($_FILES['stb_poster']) && !empty($_FILES['stb_poster']['tmp_name'])){
            $type = $_FILES['stb_poster']['type'];
            $type = (substr($type,(strrpos($type,'/')+1),strlen($type)));
            $size = $_FILES['stb_poster']['size'];
            if(!preg_match('/png/',$type)){
                $this->session->set_flashdata('warning_messages','STB Poster file type must be (*.png)');
                redirect('organization/default-logo/'.$id);
            }

            $filename = $_FILES['stb_poster']['name'];
            $location = 'public/uploads/logo/';
            /*if(file_exists($location.$organization->get_attribute('default_poster_mobile'))){
                @unlink($location.$organization->get_attribute('default_poster_mobile'));
            }*/
            $main_default_stb_poster = $location.$filename;
            $tmp_file_name = $_FILES['stb_poster']['tmp_name'];

            if(!empty($size) && ($size>0 && $size<=$mbSize)){

                try {

                    $w = $imageSize['advance']['STB_POSTER']['width'];
                    $h = $imageSize['advance']['STB_POSTER']['height'];

                    if(move_uploaded_file($tmp_file_name,$main_default_stb_poster)){
                        $to_path = $location.$w.'x'.$h;
                        if(@file_exists($location)){
                            @mkdir($to_path,0777);
                        }else{
                            @mkdir($to_path,0777,true);
                        }

                        $temp_path = $to_path.'/main-default-stb-poster-'.$w.'x'.$h.'.'.$type;
                        $final_path = $to_path.'/default-stb-poster-'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;

                        $compressResponse = $this->png_compressor->compress_png($main_default_stb_poster,$temp_path,$final_path,$w,$h);
                        if(file_exists($organization->get_attribute('default_poster_stb'))){
                            @unlink($organization->get_attribute('default_poster_stb'));
                        }

                        $output = implode(',',$compressResponse['output']);
                        if(preg_match('/error/',strtolower($output))){
                            $this->session->set_flashdata('warning_messages',$output);
                            redirect('organization/default-logo/'.$id);
                        }

                        $this->organization->save(array(
                            'default_poster_stb' => $final_path
                        ), $id);
                    }

                }catch(Exception $ex){
                    $this->session->set_flashdata('warning_messages',$ex->getMessage());
                    redirect('organization/default-logo/'.$id);
                }


            }else{
                $this->session->set_flashdata('warning_messages','STB Poster file size must be with 1MB');
                redirect('organization/default-logo/'.$id);
            }

        }

		if(!empty($_FILES['mobile_poster']) && !empty($_FILES['mobile_poster']['tmp_name'])){
			$type = $_FILES['mobile_poster']['type'];
            $type = (substr($type,(strrpos($type,'/')+1),strlen($type)));
			$size = $_FILES['mobile_poster']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Mobile Poster file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['mobile_poster']['name'];
			$location = 'public/uploads/logo/';
			/*if(file_exists($location.$organization->get_attribute('default_poster_mobile'))){
				@unlink($location.$organization->get_attribute('default_poster_mobile'));
			}*/
			$main_default_mobile_poster = $location.$filename;
            $tmp_file_name = $_FILES['mobile_poster']['tmp_name'];

			if(!empty($size) && ($size>0 && $size<=$mbSize)){

				try {

                    $w = $imageSize['advance']['MOBILE_POSTER']['width'];
                    $h = $imageSize['advance']['MOBILE_POSTER']['height'];

                    if(move_uploaded_file($tmp_file_name,$main_default_mobile_poster)){
                        $to_path = $location.$w.'x'.$h;
                        if(@file_exists($location)){
                            @mkdir($to_path,0777);
                        }else{
                            @mkdir($to_path,0777,true);
                        }

                        $temp_path = $to_path.'/main-default-mobile-poster-'.$w.'x'.$h.'.'.$type;
                        $final_path = $to_path.'/default-mobile-poster-'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;

                        $compressResponse = $this->png_compressor->compress_png($main_default_mobile_poster,$temp_path,$final_path,$w,$h);
                        if(file_exists($organization->get_attribute('default_poster_mobile'))){
                            @unlink($organization->get_attribute('default_poster_mobile'));
                        }

                        $output = implode(',',$compressResponse['output']);
                        if(preg_match('/error/',strtolower($output))){
                            $this->session->set_flashdata('warning_messages',$output);
                            redirect('organization/default-logo/'.$id);
                        }

                        $this->organization->save(array(
                            'default_poster_mobile' => $final_path
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}


			}else{
				$this->session->set_flashdata('warning_messages','Mobile Poster file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		if(!empty($_FILES['watermark_logo']) && !empty($_FILES['watermark_logo']['tmp_name'])){

            $type = $_FILES['watermark_logo']['type'];
            $type = (substr($type,(strrpos($type,'/')+1),strlen($type)));
			$size = $_FILES['watermark_logo']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Watermark Logo file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['watermark_logo']['name'];
			$location = 'public/uploads/logo/';
			/*if(file_exists($location.$organization->get_attribute('default_watermark_logo'))){
				@unlink($location.$organization->get_attribute('default_watermark_logo'));
			}*/

			$main_default_watermark_logo = $location.$filename;
            $tmp_file_name = $_FILES['watermark_logo']['tmp_name'];

			if(!empty($size) && ($size>0 && $size<=$mbSize)){

				try {

                    $w = $imageSize['watermark']['width'];
                    $h = $imageSize['watermark']['height'];

                    if(move_uploaded_file($tmp_file_name,$main_default_watermark_logo)){

                        $to_path = $location.$w.'x'.$h;
                        if(@file_exists($location)){
                            @mkdir($to_path,0777);
                        }else{
                            @mkdir($to_path,0777,true);
                        }

                        $temp_path = $to_path.'/main-default-watermark-'.$w.'x'.$h.'.'.$type;
                        $final_path = $to_path.'/default-watermark-'.str_replace(array(" ","0."),"_",microtime()).'.'.$type;

                        $compressResponse = $this->png_compressor->compress_png($main_default_watermark_logo,$temp_path,$final_path,$w,$h);
                        if(file_exists($organization->get_attribute('default_watermark_logo'))){
                            @unlink($organization->get_attribute('default_watermark_logo'));
                        }

                        $output = implode(',',$compressResponse['output']);
                        if(preg_match('/error/',strtolower($output))){
                            $this->session->set_flashdata('warning_messages',$output);
                            redirect('organization/default-logo/'.$id);
                        }

                        $this->organization->save(array(
                            'default_watermark_logo' => $final_path
                        ), $id);

                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}

			}else{
				$this->session->set_flashdata('warning_messages','Watermark Logo file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		// PACKAGE
		if(!empty($_FILES['pkg_logo_stb']) && !empty($_FILES['pkg_logo_stb']['tmp_name'])){
			$type = $_FILES['pkg_logo_stb']['type'];
			$size = $_FILES['pkg_logo_stb']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Package STB Logo file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['pkg_logo_stb']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('default_pkg_logo_stb'))){
				@unlink($location.$organization->get_attribute('default_pkg_logo_stb'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
				//move_uploaded_file($_FILES['pkg_logo_stb']['tmp_name'],$location);
				try {
                    if(move_uploaded_file($_FILES['pkg_logo_stb']['tmp_name'],$location)){
                        $this->png_compressor->compress_png($location);
                        $this->organization->save(array(
                            'default_pkg_logo_stb' => $location
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}
			}else{
				$this->session->set_flashdata('warning_messages','Package STB Logo file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}
		if(!empty($_FILES['pkg_logo_mobile']) && !empty($_FILES['pkg_logo_mobile']['tmp_name'])){
			$type = $_FILES['pkg_logo_mobile']['type'];
			$size = $_FILES['pkg_logo_mobile']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Package Mobile Logo file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['pkg_logo_mobile']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('default_pkg_logo_mobile'))){
				@unlink($location.$organization->get_attribute('default_pkg_logo_mobile'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
				//move_uploaded_file($_FILES['pkg_logo_mobile']['tmp_name'],$location);
				try {

                    if(move_uploaded_file($_FILES['pkg_logo_mobile']['tmp_name'],$location)){
                        $this->png_compressor->compress_png($location);
                        $this->organization->save(array(
                            'default_pkg_logo_mobile' => $location
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}
			}else{
				$this->session->set_flashdata('warning_messages','Package Mobile Logo file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		if(!empty($_FILES['pkg_poster_stb']) && !empty($_FILES['pkg_poster_stb']['tmp_name'])){
			$type = $_FILES['pkg_poster_stb']['type'];
			$size = $_FILES['pkg_poster_stb']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Package STB Poster file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['pkg_poster_stb']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('default_pkg_poster_stb'))){
				@unlink($location.$organization->get_attribute('default_pkg_poster_stb'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
				//move_uploaded_file($_FILES['pkg_poster_stb']['tmp_name'],$location);
				try {

                    if(move_uploaded_file($_FILES['pkg_poster_stb']['tmp_name'],$location)){
                        $this->png_compressor->compress_png($location);
                        $this->organization->save(array(
                            'default_pkg_poster_stb' => $location
                        ), $id);
                    }
				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}

			}else{
				$this->session->set_flashdata('warning_messages','Package STB Poster file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}
		if(!empty($_FILES['pkg_poster_mobile']) && !empty($_FILES['pkg_poster_mobile']['tmp_name'])){
			$type = $_FILES['pkg_poster_mobile']['type'];
			$size = $_FILES['pkg_poster_mobile']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Package Mobile Poster file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['pkg_poster_mobile']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('default_pkg_poster_mobile'))){
				@unlink($location.$organization->get_attribute('default_pkg_poster_mobile'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
				//move_uploaded_file($_FILES['pkg_poster_mobile']['tmp_name'],$location);
				try {

                    if(move_uploaded_file($_FILES['pkg_poster_mobile']['tmp_name'],$location)){
                        $this->png_compressor->compress_png($location);
                        $this->organization->save(array(
                            'default_pkg_poster_mobile' => $location
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}

			}else{
				$this->session->set_flashdata('warning_messages','Package Mobile Poster file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}



		// EPG

		if(!empty($_FILES['epg_logo']) && !empty($_FILES['epg_logo']['tmp_name'])){
			$type = $_FILES['epg_logo']['type'];
			$size = $_FILES['epg_logo']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','EPG Logo file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['epg_logo']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('default_epg_logo'))){
				@unlink($location.$organization->get_attribute('default_epg_logo'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
				//move_uploaded_file($_FILES['epg_logo']['tmp_name'],$location);

				try {
					if(move_uploaded_file($_FILES['epg_logo']['tmp_name'], $location)){
                        $this->png_compressor->compress_png($location);
                        $this->organization->save(array(
                            'default_epg_logo' => $location
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}


			}else{
				$this->session->set_flashdata('warning_messages','EPG Logo file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		if(!empty($_FILES['epg_poster']) && !empty($_FILES['epg_poster']['tmp_name'])){
			$type = $_FILES['epg_poster']['type'];
			$size = $_FILES['epg_poster']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','EPG Poster file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = $_FILES['epg_poster']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('default_epg_poster'))){
				@unlink($location.$organization->get_attribute('default_epg_poster'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
				//move_uploaded_file($_FILES['epg_poster']['tmp_name'],$location);
				try {
                    if(move_uploaded_file($_FILES['epg_poster']['tmp_name'],$location)){
                        $this->png_compressor->compress_png($location);
                        $this->organization->save(array(
                            'default_epg_poster' => $location
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}

			}else{
				$this->session->set_flashdata('warning_messages','EPG Poster file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		// BKash Info Image

		if(!empty($_FILES['bkash_info']) && !empty($_FILES['bkash_info']['tmp_name'])){
			$type = $_FILES['bkash_info']['type'];
			$size = $_FILES['bkash_info']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','Bkash Info Image file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = 'bkash-info-'.time().'_'.$_FILES['bkash_info']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('bkash_info_img_url'))){
				@unlink($location.$organization->get_attribute('bkash_info_img_url'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
				//move_uploaded_file($_FILES['epg_poster']['tmp_name'],$location);
				try {

					if(move_uploaded_file($_FILES['bkash_info']['tmp_name'],$location)){
                        //$this->png_compressor->compress_png($location);
                        $this->organization->save(array(
                            'bkash_info_img_url' => $location
                        ), $id);
                    }

				}catch(Exception $ex){
					$this->session->set_flashdata('warning_messages',$ex->getMessage());
					redirect('organization/default-logo/'.$id);
				}

			}else{
				$this->session->set_flashdata('warning_messages','Bkash info image file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}
                
                // About us Info Image

		if(!empty($_FILES['about_us_info']) && !empty($_FILES['about_us_info']['tmp_name'])){
			$type = $_FILES['about_us_info']['type'];
			$size = $_FILES['about_us_info']['size'];
			if(!preg_match('/png/',$type)){
				$this->session->set_flashdata('warning_messages','About Us Info Image file type must be (*.png)');
				redirect('organization/default-logo/'.$id);
			}

			$filename = 'about-us-'.time().'_'.$_FILES['about_us_info']['name'];
			$location = 'public/uploads/logo/';
			if(file_exists($location.$organization->get_attribute('about_us'))){
				@unlink($location.$organization->get_attribute('about_us'));
			}
			$location = $location.$filename;
			if(!empty($size) && ($size>0 && $size<=$mbSize)){
                            //move_uploaded_file($_FILES['epg_poster']['tmp_name'],$location);
                            try {

                                    if(move_uploaded_file($_FILES['about_us_info']['tmp_name'],$location)){
                                        //$this->png_compressor->compress_png($location);
                                        $this->organization->save(array(
                                            'about_us' => $location
                                        ), $id);
                                    }

                            }catch(Exception $ex){
                                    $this->session->set_flashdata('warning_messages',$ex->getMessage());
                                    redirect('organization/default-logo/'.$id);
                            }

			}else{
				$this->session->set_flashdata('warning_messages','About us info image file size must be with 1MB');
				redirect('organization/default-logo/'.$id);
			}

		}

		$this->session->set_flashdata('success_messages','Default logo updated successfully');
		redirect('organization/default-logo/'.$id);


	}

	public function update_hls()
	{
		if($this->role_type != self::ADMIN){
			$permission = $this->menus->has_edit_permission($this->role_id,1,'organization',$this->user_type);
			if(!$permission){
				$this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
				redirect('organization');
				exit;
			}
		}

		$id = $this->input->post('id');
		$organization = $this->organization->find_by_id($id);
		if(!$organization->has_attributes()){
			$this->session->set_flashdata('warning_messages','Sorry! no organization info found');
			redirect('dashboard');
		}

		$default_hls_web    = $this->input->post('default_hls_web');
		$default_hls_stb    = $this->input->post('default_hls_stb');
		$default_hls_mobile = $this->input->post('default_hls_mobile');
		$default_expire_hls_mobile = $this->input->post('default_expire_hls_mobile');
		$default_expire_hls_web = $this->input->post('default_expire_hls_web');
		$default_expire_hls_stb = $this->input->post('default_expire_hls_stb');
		$default_unsubscribed_hls = $this->input->post('default_unsubscribed_hls');

		$organizationData = array(
			'default_hls_web' => $default_hls_web,
			'default_hls_stb' => $default_hls_stb,
			'default_hls_mobile' => $default_hls_mobile,
			'default_expire_hls_web' => $default_expire_hls_web,
			'default_expire_hls_mobile' => $default_expire_hls_mobile,
			'default_expire_hls_stb' => $default_expire_hls_stb,
			'default_unsubscribed_hls' => $default_unsubscribed_hls
		);

		$this->organization->save($organizationData,$id);
		$this->session->set_flashdata('success_messages','Default HLS links updated successfully');
		redirect('organization/default-hls/'.$id);
	}

	public function update_other_url()
	{
		// checking that , if user have any edit permission
		// then system allow to update information
		if($this->role_type != self::ADMIN){
			$permission = $this->menus->has_edit_permission($this->role_id,1,'organization',$this->user_type);
			if(!$permission){
				$this->session->set_flashdata('warning_messages',"Sorry! You don't have edit permission");
				redirect('organization');
				exit;
			}
		}

		// Get System info record to update changes
		$id = $this->input->post('id');
		$organization = $this->organization->find_by_id($id);
		if(!$organization->has_attributes()){
			$this->session->set_flashdata('warning_messages','Sorry! no organization info found');
			redirect('dashboard');
		}

		$organizationData = array(
			'default_channel_share_url' => $this->input->post('default_channel_share_url'),
			'default_vod_share_url'     => $this->input->post('default_vod_share_url'),
			'default_catchup_share_url' => $this->input->post('default_catchup_share_url')
		);

		$this->organization->save($organizationData,$id);
		$this->session->set_flashdata('success_messages','Default URL updated successfully');
		redirect('organization/default-hls/'.$id);


	}
}