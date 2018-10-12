<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Conditional_mail_model $conditional_mail
 * @property Conditional_search_model $conditional_search
 * @property Conditional_scrolling_model $conditional_scrolling
 * @property Conditional_force_osd_model $conditional_force_osd
 * @property Conditional_limited_model $conditional_limited
 * @property Ecm_fingerprint_model $ecm_fingerprint
 * @property Emm_fingerprint_model $emm_fingerprint
 * @property Billing_subscriber_transaction_model $subscriber_transcation
 */
class Reports extends BaseController
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
		
		$this->theme->set_title('Report: Mail-log')
			 ->add_style('component.css')
			 ->add_style('kendo/css/kendo.common-bootstrap.min.css')
			 ->add_style('kendo/css/kendo.bootstrap.min.css')
			 ->add_script('controllers/reports/mail-log.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('reports/mail_log',$data,true);
	}

	public function ajax_load_cards($subscriber_id)
	{
		if($this->input->is_ajax_request())
		{
			$cards = $this->conditional_mail->get_cards_by_id($subscriber_id);
			$stbcards = $this->conditional_mail->get_stbcards_by_id($subscriber_id);
			array_unshift($stbcards, array('external_card_number'=>'All'));
			array_unshift($cards, array('external_card_number'=>'All'));
			echo json_encode(array(
				'status'=>200,
				'stbcards'=>$stbcards,
				'cards'=>$cards));
		} 
	}

	public function ajax_load_mail_logs()
	{
		if($this->input->is_ajax_request())
		{
			$subscriber_id   = $this->input->get('subscriber_id');
			$stb   = $this->input->get('stb');
			$smart_card   = $this->input->get('smart_card');
			$type = $this->input->get('type');
			$status = $this->input->get('status');


			$take   = $this->input->get('take');
        	$skip   = $this->input->get('skip');
			$logs   = [];
			$total  = 0;
			switch($type){
				case 'Mail':
					$logs   = $this->conditional_mail->get_all_logs($take,$skip,false,$subscriber_id,$stb,$smart_card,$status);
					$total  = $this->conditional_mail->get_all_logs(0,0,true,$subscriber_id,$stb,$smart_card,$status);
					break;
				case 'Search':
					$logs   = $this->conditional_search->get_all_logs($take,$skip,false,$subscriber_id,$stb,$smart_card,$status);
					$total  = $this->conditional_search->get_all_logs(0,0,true,$subscriber_id,$stb,$smart_card,$status);
					break;

				case 'Scrolling':
					$logs   = $this->conditional_scrolling->get_all_logs($take,$skip,false,$subscriber_id,$stb,$smart_card,$status);
					$total  = $this->conditional_scrolling->get_all_logs(0,0,true,$subscriber_id,$stb,$smart_card,$status,$status);
					break;
				case 'Force':
					$logs   = $this->conditional_force_osd->get_all_logs($take,$skip,false,$subscriber_id,$stb,$smart_card,$status);
					$total  = $this->conditional_force_osd->get_all_logs(0,0,true,$subscriber_id,$stb,$smart_card,$status);
					break;
				case 'Limited':
					$logs   = $this->conditional_limited->get_all_logs($take,$skip,false,$subscriber_id,$stb,$smart_card,$status);
					$total  = $this->conditional_limited->get_all_logs(0,0,true,$subscriber_id,$stb,$smart_card,$status);
					break;
				case 'ECM':
					$logs   = $this->ecm_fingerprint->get_all_logs($take,$skip,false,$subscriber_id,$stb,$smart_card,$status);
					$total  = $this->ecm_fingerprint->get_all_logs(0,0,true,$subscriber_id,$stb,$smart_card,$status);
					break;
				case 'EMM':
					$logs   = $this->emm_fingerprint->get_all_logs($take,$skip,false,$subscriber_id,$stb,$smart_card,$status);
					$total  = $this->emm_fingerprint->get_all_logs(0,0,true,$subscriber_id,$stb,$smart_card,$status);
					break;
				default:

					break;
			}


			echo json_encode(array('logs'=>$logs,'total'=>$total));
		} else {
			redirect('/');
		}
	}

	public function client_statements()
	{
		$this->theme->set_title('Report: Client Statement')
				->add_style('component.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/reports/client-statement.js');


		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('reports/client-statement',$data,true);
	}

	public function ajax_get_lco()
	{
		if($this->input->is_ajax_request()) {
			$lco = $this->lco_profile->get_all_lco_users($this->user_session->id);
			//array_unshift($lco, array('user_id'=>0,'lco_name'=>'All'));
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

	public function ajax_get_pairings($subscriber_id)
	{
		if($this->input->is_ajax_request()) {
			$pairs = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($subscriber_id);
			array_unshift($pairs, array('id' => 'all','pairing_id' => 'All'));
			echo json_encode(array('status'=>200,'pairings'=>$pairs));
		} else {
			redirect('/');
		}
	}

	public function ajax_get_statements()
	{
		if($this->input->is_ajax_request()){
			$lco_id = $this->input->post('lco_id');
			$subscriber_id = $this->input->post('subscriber_id');
			$pairing_id    = $this->input->post('pairing_id');
			$from_date     = $this->input->post('from_date');
			$to_date	   = $this->input->post('to_date');

			$transactions =	$this->subscriber_transcation->get_statements($lco_id,$subscriber_id,$pairing_id,$from_date,$to_date);
			echo json_encode(array('status'=>200,'transactions'=>$transactions));
		}else{
			redirect('/');
		}
	}

	public function collection_statements()
	{

		$this->theme->set_title('Report: Collection Statement')
				->add_style('component.css')
				->add_style('kendo/css/kendo.common-bootstrap.min.css')
				->add_style('kendo/css/kendo.bootstrap.min.css')
				->add_script('controllers/reports/collection-statement.js');


		$data['user_info'] = $this->user_session;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('reports/collection-statement',$data,true);
	}

	public function ajax_get_collection_lco()
	{
		if($this->input->is_ajax_request()) {
			$lco = $this->lco_profile->get_all_lco_users($this->user_session->id);
			array_unshift($lco, array('user_id'=>0,'lco_name'=>'All'));
			//array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All FOC'));
			//array_unshift($lco, array('user_id'=>-1,'lco_name'=>'All MSO'));
			echo json_encode(array(
					'status' => 200,
					'lco' => $lco
			));
		} else {
			redirect('/');
		}

	}

	public function ajax_get_collection_statements()
	{
		//test('here');
		if($this->input->is_ajax_request()){
			$lco_id        = $this->input->post('lco_id');
			$from_date     = $this->input->post('from_date');
			$to_date       = $this->input->post('to_date');
			$query_status  = $this->input->post('query_status');
			if($lco_id == 'All'){
				$result = $this->user->get_lco_ids_by_mso($this->user_id);
				if(!empty($result)){
					$lco_id = $result->id;
				}

			}
			$transactions  =	$this->subscriber_transcation->get_collection_statements($lco_id,$from_date,$to_date);

			echo json_encode(array('status'=>200,'transactions'=>$transactions));
		}else{
			redirect('/');
		}
	}




}