<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Authenticate
 * @property Authenticate_model $auth
 */
class Authenticate extends BaseController
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('services');
		$this->theme->set_theme('katniss');
		$this->theme->set_layout('main');
		
		$this->load->helper('string');
	}
	
	public function login()
	{
		/*$response = $this->services->login_api();
			
		if($response->status != 200){
			redirect('access-denied');
			
		}*/
		$licenseCheck = $this->config->item('license_check');
		if($licenseCheck){
			if(file_exists('license.nexdecad')){
				$licence = file_get_contents('license.nexdecad');
				$machineData = getLicence();
				if($licence !== $machineData){
					redirect('license-missing');
				}
				$data = array();
				$this->load->view('theme/katniss/login', $data);
			}else{
				redirect('license-missing');
			}
		}else{
			$data = array();
			$this->load->view('theme/katniss/login', $data);
		}


	}

	public function check_status()
	{
		if (!empty($this->user_session)) {

            $timezone = $this->input->post('timezone');
            $timezone = substr($timezone,3,3).":".substr($timezone,6);
            $this->session->set_userdata('timezone',$timezone);

			echo 1;
		}else{
			echo 0;
		}
	}

	public function refresh_captcha()
	{
		$cap = $this->custom_captcha->generate_random();
		
		$this->session->set_userdata('captcha', $cap);

	}

	public function get_captcha_image(){
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-type: image/jpeg');

		$rand = $this->custom_captcha->generate_random();
		$strCaptcha = $this->session->set_userdata('captcha',$rand);
		$strCaptcha = $rand;
		$img_id = rand(1,8);
		$im = @imagecreatefromjpeg("public/theme/katniss/img/captcha_".$img_id.".jpg");

		$fonts = array('ERASLGHT.TTF','ERASMD.TTF','ALGER.TTF');
		$font = 'public/font/'.$fonts[rand(0,(count($fonts)-1))];
		imagettftext($im,12,0,8,20,ImageColorAllocate ($im, 0, 0, 0),$font,$rand);

		/*$rand = substr($strCaptcha, 3, 3);
		$str = $rand[0]." ".$rand[1]." ".$rand[2];
		imagettftext($im,12,0,20,20,ImageColorAllocate ($im, 0, 0, 0),$font,$str);*/

		/*$rand = substr($strCaptcha, 0, 3);
		imagestring($im, 9, 14, 5, $rand[0]." ".$rand[1]." ".$rand[2]." ", ImageColorAllocate ($im, 0, 0, 0));

		$rand = substr($strCaptcha, 3, 3);
		imagestring($im, 9, 14, 5, " ".$rand[0]." ".$rand[1]." ".$rand[2], ImageColorAllocate ($im, 255, 0, 0));*/



		imagejpeg($im,NULL,100);
		ImageDestroy($im);
	}

	public function post_login()
	{
		$stored_captcha = $this->session->userdata('captcha');
		//$stored_captcha = $this->custom_captcha->get_captcha_code($stored_captcha);
		$response = $this->services->login_api();
			
		if($response->status != 200){
			echo 400;
			exit;
		}

		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$captcha = $this->input->post('captcha');
		$remember = $this->input->post('remember');

		if(!empty($captcha)){
			
			if(strtolower($captcha) == strtolower($stored_captcha)){

				$loggedin = $this->auth->login_by_username($username,$password,$remember);
				if($loggedin)
				{
					$this->notification->save_notification(null,"Login Success","Welcome to dashboard, {$username} successfully loggedin",1);
					
				}
				if (!$this->input->is_ajax_request()) {
					if($loggedin)
					{
						
						redirect('dashboard');
					}		
					else
						redirect('login');
				} else {
					if($loggedin)
					{	echo 1;
						exit;
					}else{
						echo 0;
						exit;
					}
				}
			}else{
				echo 3;
				exit;
			}

	

		}
	}
	
	public function user_registration()
	{
		$this->theme->set_title('Dashboard - Application')->add_style('index.css')
		->add_script('custom_js/dashboard.js');


		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('user_registration',$data,true);
	}

	public function user_permission()
	{
		$this->theme->set_title('Dashboard - Application')->add_style('index.css')
		->add_script('custom_js/dashboard.js');


		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('user_permission',$data,true);
	}

	public function user_role()
	{
		$this->theme->set_title('Dashboard - Application')->add_style('index.css')
		->add_script('custom_js/dashboard.js');


		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('user_role_creation',$data,true);
	}

	public function logout()
	{
		$this->auth->logout();
		redirect('login');
	}
}
