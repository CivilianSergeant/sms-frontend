<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Billing
 * @property Billing_payment_method_model $payment_method
 */
class Billing extends BaseController 
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

		$this->theme->set_title('MSO User Creation - Application')
		->add_style('component.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_style('component.css')
		->add_script('controllers/payments.js');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('payments/payments',$data,true);
	}

	public function refund()
	{
		$this->theme->set_title('MSO User Creation - Application')
		->add_style('component.css');


		$data['user_info'] = $this->user_session;	
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('payments/refunds',$data,true);

	}

	public function cash()
	{
		$this->theme->set_title('Subscriber Cash Receive - Application')
		->add_style('component.css')
		->add_style('custom.css')
		->add_style('kendo/css/kendo.common-bootstrap.min.css')
		->add_style('kendo/css/kendo.bootstrap.min.css')
		->add_script('controllers/billing.js');
		$subscriber_id = base64_decode(urldecode($this->uri->segment(2)));
		$data['subscriber_id'] = $subscriber_id;

		$data['user_info'] = $this->user_session;
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		$data['collectors'] = $this->payment_method->get_all_collector($id);
		if($this->role_type == "admin"){
			$data['permissions'] = (object) array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else {
			$data['permissions'] = $this->menus->has_permission($this->role_id, 1, 'cash', $this->user_type);
		}
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('payments/cash',$data,true);

	}

	public function pairing_id()
	{
		$subscriber_id = $this->input->post('subscriber_id');
		$pairs = $this->payment_method->get_subscriber_pairing_id($subscriber_id);
		$newPairs = array(
			(object)array(
				'id' => 'all',
				'pairing_id' => 'Select All'
				)
			);
		
		foreach($pairs as $val){
			$newPairs[] = $val;
		}
		echo json_encode(array('status'=>200,'stb_card_pairs'=>$newPairs));
	}

	public function save_cash_receive()
	{
		if($this->role_type == self::STAFF) {
			$permission = $this->menus->has_create_permission($this->role_id, 1, 'cash', $this->user_type);

			if (!$permission) {
				echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have to create permission to LCO Payments Cash"));
				exit();
			}
		}

		$check_money_receipt = $this->money_receipt->is_assign_to_collector($this->input->post('money_receipt'), $this->input->post('collector_id'));
		
		if(empty($check_money_receipt))
		{
			echo json_encode(array('status' => 400, 'warning_messages' => 'Money Receipt Is Not Assigned'));
			exit();
		}

		if($check_money_receipt->is_used){
			echo json_encode(array('status' => 400, 'warning_messages' => 'Money Receipt Already Used'));
			exit();
		}
		else{
			$this->form_validation->set_rules('money_receipt','Money Receipt','required');
			$this->form_validation->set_rules('subscriber_id','Subscriber','required');
			$this->form_validation->set_rules('stb_card_id','Pairing ID','required');
			$this->form_validation->set_rules('receive_date','Receive Date','required');
			$this->form_validation->set_rules('amount','Amount','required');
			// $this->form_validation->set_rules('discount','Discount','required');
			$this->form_validation->set_rules('vat_amount','Vat','required');
			$this->form_validation->set_rules('total_amount','Total Amount','required');
			$this->form_validation->set_rules('collector_id','Collector Name','required');

			if(!$this->form_validation->run()) {
				echo json_encode(array('status' => 400, 'warning_messages' => strip_tags(validation_errors())));
				exit();
			} else {

				$balance = $this->subscriber_transcation->get_subscriber_balance($this->input->post('subscriber_id'));
				$prev_balance = (!empty($balance))? $balance->balance:0;
				$token = $this->payment_method->get_subscriber_token($this->input->post('subscriber_id'));

				$trn_data = array(
					'pairing_id' => $this->input->post('pairing_id'), 
					'subscriber_id' => $this->input->post('subscriber_id'), 
					'discount' => $this->input->post('discount'),
					'vat_amount' => $this->input->post('vat_amount'),
					'credit' => $this->input->post('total_amount'),
					'balance' => $prev_balance + $this->input->post('total_amount'),
					'user_package_assign_type_id' => 4,
					'collection_date' => $this->input->post('receive_date'),
					'transaction_types' => 'c',
					'demo' => 0,
					'payment_method_id' => 1,
					'transaction_date' => date('Y/m/d'),
					'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
					//'created_by' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id)
					);
				$stb_card_id  = $this->input->post('stb_card_id');
				$subscriber_name = $this->subscriber_profile->get_subscriber_name($this->input->post('subscriber_id'));

				if(!empty($stb_card_id) && $stb_card_id != "all"){

					/*$pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
					$cardNum = $pairing->internal_card_number;
					$cardExtNum = $pairing->external_card_number;

					$api_mail_data = array();
					$api_mail_data['title']  = 'Recharge';
					$api_mail_data['payment_method'] = 'Cash';
	                $api_mail_data['amount'] = $trn_data['balance'];
	                $api_mail_data['recharge_amount'] = $this->input->post('total_amount');
	                $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
	                $api_mail_data['template'] = 'msg_template/single_recharge';
	                $api_mail_data['cardNum'] = $cardNum;

	                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
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

	                if(!empty($response->id)){
	                    $conditional_mail_data = array(
	                        'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
	                        'subscriber_id' => $this->input->post('subscriber_id'),
	                        'smart_card_ext_id' => $cardExtNum,
	                        'smart_card_id' => $cardNum,
	                        'start_time'    => $startDate,
	                        'end_time'      => $endDate,
	                        'mail_title'    => $api_mail_data['title'],
	                        'mail_content'  => $api_conditional_mail['content'],
	                        'mail_sign'     => $api_conditional_mail['signStr'],
	                        'mail_priority' => $api_conditional_mail['priority'],
	                        'condition_return_code' => $response->id,
	                        'creator'       => $this->user_session->id,
							'type'			=> 'SYSTEM'

	                    );
	                    $this->conditional_mail->save($conditional_mail_data);
	                }*/
				} else {
					
					/*$pairing_cards = $this->subscriber_stb_smartcard->get_pairing_by_subscriber_id($this->input->post('subscriber_id'));
					$pairings = array();
					if(!empty($pairing_cards)){

						foreach($pairing_cards as $p){
							$pairings[] = $p->pairing_id;
						}

						foreach($pairing_cards as $p){
							$api_mail_data = array();
							$api_mail_data['title']  = 'Recharge';
							$api_mail_data['payment_method'] = 'Cash';
							$api_mail_data['pairings'] = implode(",",$pairings);
			                $api_mail_data['amount'] = $trn_data['balance'];
			                $api_mail_data['recharge_amount'] = $this->input->post('total_amount');
			                $api_mail_data['message_sign'] = ($this->message_sign != null)? $this->message_sign : $this->config->item('message_sign');
			                $api_mail_data['template'] = 'msg_template/all_recharge';
			                $api_mail_data['cardNum'] = $p->internal_card_number;

			                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
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

			                if(!empty($response->id)){
			                    $conditional_mail_data = array(
			                        'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
			                        'subscriber_id' => $this->input->post('subscriber_id'),
			                        'smart_card_ext_id' => $p->external_card_number,
			                        'smart_card_id' => $p->internal_card_number,
			                        'start_time'    => $startDate,
			                        'end_time'      => $endDate,
			                        'mail_title'    => $api_mail_data['title'],
			                        'mail_content'  => $api_conditional_mail['content'],
			                        'mail_sign'     => $api_conditional_mail['signStr'],
			                        'mail_priority' => $api_conditional_mail['priority'],
			                        'condition_return_code' => $response->id,
			                        'creator'       => $this->user_session->id,
									'type'			=> 'SYSTEM'

			                    );
			                    $this->conditional_mail->save($conditional_mail_data);
			                }
						}
					}*/
				}

				//test($trn_data);

				$trn_id = $this->subscriber_transcation->save($trn_data);
				$this->set_notification("Cash Received","Cash Received from Subscriber [{$subscriber_name}]");
				if($trn_id){
					$cash_receive = array(
						'subscriber_transection_id' => $trn_id,
						'receipt_number' => $this->input->post('money_receipt'),  
						'collector_id' => $this->input->post('collector_id'),
						'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id)
						);
					$save_cash = $this->billing_transaction->save($cash_receive);
					if($save_cash){
						$update_receipt = array(
							'subscriber_id' => $this->input->post('subscriber_id'), 
							'is_used' => 1
							);
						$update_receipt_status = $this->money_receipt->save($update_receipt, $check_money_receipt->id);
						if($update_receipt_status){
							// here speifiy url
							$subscriber_url = ($this->user_type==self::MSO_LOWER)? 'foc-subscriber' : 'subscriber';
							$user_packages = $this->user_package->get_assigned_packages_by_id($this->input->post('subscriber_id'));
							if (!$user_packages['current_package']) {
								$redirect_url = site_url($subscriber_url.'/edit/' . $token->token . '#package_assign');
							}
							else{
								$id = (!empty($stb_card_id)) ? $stb_card_id : 'all';
								$redirect_url =  site_url($subscriber_url.'/charge/' . $token->token . '/' .$id );
							}			
							echo json_encode(array('status'=>200, 'reditect_to' => $redirect_url, 'success_messages' => 'Transaction Successfull'));
						}					
					}									
				}
			}
		}
	}

	public function subscriber_packages($token, $stb_card_pair_id)
	{

		$this->theme->set_title('Dashboard - Application')
		->add_style('component.css')
		->add_script('controllers/billing.js');

		$data['token'] = $token;
		$data['pair_id'] = $stb_card_pair_id;
		$subscriber = $this->subscriber_profile->find_by_token($token);
		$data['subscriber'] = $subscriber;
		$url = $this->uri->segment(1);
		$data['back_url']   = ($url=='foc-subscriber')? site_url('foc-subscriber') : site_url('subscriber');
		$data['user_info']  = $this->user_session;	
		$data['segment']    = $url;
		$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
		$data['theme'] = $this->theme->get_image_path();
		$this->theme->set_view('payments/subscriber_recharge',$data,true);
	}

	public function ajax_load_subscribers()
	{
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		if($this->input->is_ajax_request()){
			echo json_encode(array(
				'status'=>200,
				'all_subscribers'=>$this->subscriber_profile->get_all_subscribers($id)
				));
		}else{
			$this->session->set_flashdata('warning_messages','Direct access not allowed');
			redirect('billing/cash');
		}
	}

	public function charge()
	{
		if($this->input->is_ajax_request())
		{
			if($this->role_type == self::STAFF) {
				$permission = $this->menus->has_edit_permission($this->role_id, 1, 'subscriber', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have edit permission to subscriber"));
					exit;
				}
			}
			$token = $this->input->post('token');

            $user  = $this->user->find_by_token($token);
            
            $packages = $this->input->post('packages');
            $pairing_id = $this->input->post('pairing_id');
            $stb_card_id = $this->input->post('stb_card_id');
            $charge_type = $this->input->post('charge_type');
            $duration  = $this->input->post('duration');

            $amount_charge = $this->input->post('total_price');
            $payment_method = $this->payment_method->get_payment_method_by_name('Cash');
            
            date_default_timezone_set('Asia/Dhaka');

            $today = date('Y-m-d H:i:s');
            $today_object     = new DateTime($today);
            
            $expire_date      = $this->input->post('expire_date');            
            $expire_object    = new DateTime(substr($expire_date,0,10));

            $expire_diff      = date_diff($today_object,$expire_object);

            $no_of_days = 0;


			if ($user->has_attributes()) {
				//$pairing = $this->subscriber_stb_smartcard->get_pairing_by_id($stb_card_id);
				//$cardNum = $pairing->internal_card_number;
				//$cardExtNum = $pairing->external_card_number;
				/*$api_data = array(
	            	'cardNum' => $cardNum,
	            	'operatorName' => $this->user_session->username,
				    'authCounts' => count($packages),
	        	);*/

				$last_balance = $this->subscriber_transcation->get_subscriber_balance($user->get_attribute('id'));
				$available_balance = (!empty($last_balance))? $last_balance->balance:0;
				if($available_balance == 0){
					echo json_encode(array('status'=>400,'warning_messages'=>'Subscriber don\'t have enough money'));
                	exit;
				}

				if($charge_type == 1){
					// calculate new duration if charge by amount
					$unit_price = round($amount_charge/$duration);
					$duration   = round($available_balance/$unit_price);
				}

				// if charge within validity period
	            if($expire_diff->days > 0 && $expire_diff->invert == 0){
	            	$remaining_days = $expire_diff->days;
	            	$no_of_days = ($duration + $remaining_days);
	            	$expire_object->add(new DateInterval('P'.$duration.'D'));
	            	/*test($remaining_days);
	            	test($expire_diff);*/
	            }
	 
	            // if charge on the day it will expired
	            if($expire_diff->days == 0 && $expire_diff->invert == 0){
	            	$remaining_days = 0;
	            	$no_of_days = ($duration + $remaining_days);
	            	$expire_object = $today_object;
	            	$expire_object->add(new DateInterval('P'.$duration.'D'));
	            	
	            }

	            // if charge on after expired
	            if($expire_diff->days > 0 && $expire_diff->invert >= 1){
	            	$remaining_days = 0;
	            	$no_of_days = ($duration + $remaining_days);
	            	$expire_object = $today_object;
	            	$expire_object->add(new DateInterval('P'.$duration.'D'));
	            	
	            }

				$package_ids = $package_names = array();
            	$user_package_assign_type = 3; // Charge Fee;
            	$start_datetimes = $end_datetimes = $flags = array();

            	foreach($packages as $package){
            		$package_names[] = $package['package_name'];
            		$package_ids[] = (int)$package['id'];
                    $start_datetimes[] = datetime_to_array($today);
                    $end_datetimes[]   = datetime_to_array($expire_object->format('Y-m-d 23:59:59'));
            		$flags[] = 1; 
            	}

            	/*$api_data['productId'] = $package_ids;
            	$api_data['startTime'] = $start_datetimes;
            	$api_data['endTime']   = $end_datetimes;
            	$api_data['flag']      = $flags;
            	$api_string = json_encode($api_data);
            	
            	// call api here
				$response = $this->services->package_update($api_string);
					
				if($response->status == 500 || $response->status == 400){
                    $administrator_info = $this->organization->get_administrators();
                    echo json_encode(array('status'=>400,'warning_messages'=>$response->message.' Please Contact with administrator. '.$administrator_info));
                    exit;
                }

                if($response->status != 200){
                    $code = $this->cas_sms_response_code->get_code_by_name($response->type);
                    echo json_encode(array('status'=>400,'warning_messages'=>$code->details));
                    exit;
                }*/

                /*if($response->status == 200){
                	if(isset($response->type)){
                		$code = $this->cas_sms_response_code->get_code_by_name($response->type);
	                	echo json_encode(array('status'=>200,'success_messages'=>$code->details));
                	}else{
                		echo json_encode(array('status'=>200,'success_messages'=>'Subscriber Charged Successfully'));
                	}
	                

	            }*/
                /*if($response->status != 200){
                    echo json_encode(array('status'=>400,'warning_messages'=>'Server out of sync please refresh your browser'));
                    exit;
                }*/
                
            	foreach($packages as $package){
                    $save_package_assign_data = array();
                    $save_package_assign_data['charge_type'] = $charge_type;
                    $save_package_assign_data['package_start_date'] =  $today;
                    $save_package_assign_data['package_expire_date'] = $expire_object->format('Y-m-d 23:59:59');
                    $save_package_assign_data['no_of_days'] = $no_of_days;
                    $save_package_assign_data['user_package_type_id'] = $user_package_assign_type;
     
                    $this->user_package->save($save_package_assign_data,$package['user_package_id']);
                }

                

                
                
                $save_debit_data['pairing_id'] = $pairing_id;
                $save_debit_data['subscriber_id'] = $user->get_attribute('id');
                $save_debit_data['lco_id'] =  (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id);
                $save_debit_data['package_id'] = implode(",",$package_ids);
                
                if($charge_type == 1){
                	// if charge by amount
                	$balance = (($available_balance)-($available_balance));
                	$save_debit_data['debit']  = ($available_balance);
                }else {
                	$balance = (($available_balance)-($amount_charge));
                	$save_debit_data['debit']  = ($amount_charge);
                }
                
                
                //$save_debit_data['debit']  = ($amount_charge);
                $save_debit_data['balance'] = $balance;
                $save_debit_data['transaction_types'] = 'D';
                $save_debit_data['payment_type'] = 'MRC';
                $save_debit_data['payment_method_id'] = (!empty($payment_method))? $payment_method->id : null;
                $save_debit_data['user_package_assign_type_id'] = $user_package_assign_type;
                
                if(!empty($last_balance)){
                    if($last_balance->demo == 1){
                        $save_debit_data['demo'] = 1;
                    }else{
                        $save_debit_data['demo'] = 0;
                    }
                } else {
                    $save_debit_data['demo'] = 1;
                }

                $save_debit_data['transaction_date'] = date('Y-m-d H:i:s',time());
                //$save_debit_data['created_by'] = $this->user_session->id;
               
                /*$api_mail_data['title']  = 'Charge';
                $api_mail_data['package_name'] = implode(",",$package_names);
                $api_mail_data['amount'] = $save_debit_data['balance'];
                $api_mail_data['message_sign'] = ($this->message_sign != null) ? $this->message_sign : $this->config->item('message_sign');
                $api_mail_data['expire_date']  = $this->input->post('expire_date');
                $api_mail_data['cardNum'] = $cardNum;
                $api_mail_data['template'] = 'msg_template/charge';
                
                $api_conditional_mail = $this->services->get_conditional_mail_content($api_mail_data);
                $api_string = json_encode($api_conditional_mail);
                $startDate = $api_conditional_mail['startTime'];
                $endDate = $api_conditional_mail['endTime'];
                $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'].' '.$startDate['hour'].':'.$startDate['minute'].':'.$startDate['second'];
                $endDate   = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['minute'].':'.$endDate['second'];
                $response = $this->services->conditional_mail($api_string);
                if(!empty($response->id)){
                    $conditional_mail_data = array(
                        'lco_id' => (($this->role_type == self::STAFF)? $this->parent_id : $this->user_id),
                        'subscriber_id' => $user->get_attribute('id'),
                        'smart_card_ext_id' => $cardExtNum,
                        'smart_card_id' => $cardNum,
                        'start_time'    => $startDate,
                        'end_time'      => $endDate,
                        'mail_title'    => $api_mail_data['title'],
                        'mail_content'  => $api_conditional_mail['content'],
                        'mail_sign'     => $api_conditional_mail['signStr'],
                        'mail_priority' => $api_conditional_mail['priority'],
                        'condition_return_code' => $response->id,
                        'creator'       => $this->user_session->id,
						'type'			=> 'SYSTEM'

                    );
                    $this->conditional_mail->save($conditional_mail_data);
                }*/
                $packageNames = implode(",",$package_names);
                $this->subscriber_transcation->save($save_debit_data);
                $this->set_notification("Subscriber account charged", "Subscriber account has been charged {$save_debit_data['debit']} for packages [{$packageNames}]");
                $redirect_url = $this->input->post('redirect_to');

                echo json_encode(array('status'=>200,'redirect_to'=>$redirect_url,'success_messages'=>'Subscriber Successfully Charged for DEVICE ID '.$pairing_id));
                exit;

            } else {

                echo json_encode(array('status'=>400,'warning_messages'=>'User account not exist. Please Create User Login information'));
                exit;

            }



        } else {
        	$this->session->set_flashdata('warning_messages','Direct access not allowed');
        	redirect('/');
        }
    }


    public function ajax_get_assigned_packages()
    {
    	if($this->input->is_ajax_request()){
    		$token = $this->input->post('token');
    		$pair_id = $this->input->post('pair_id');
    		$user = $this->user->find_by_token($token);
    		$pairing_id = (($pair_id == 'all')? null : $pair_id);
    		$selected_pacakges = $this->user_package->get_assigned_packages($user->get_attribute('id'), $pairing_id);

    		echo json_encode(array('status'=>200,'assigned_packages'=>$selected_pacakges));
    		exit;
    	} else {
    		$this->session->set_flashdata('warning_messages','Direct access not allowed');
    		redirect('/');
    	}
    }

    public function bank()
    {
    	$this->theme->set_title('MSO User Creation - Application')
    	->add_style('component.css');


    	$data['user_info'] = $this->user_session;	
    	$data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
    	$data['theme'] = $this->theme->get_image_path();
    	$this->theme->set_view('payments/bank-account',$data,true);

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
			/*$role = $this->user->get_user_role($this->user_id);
			$role_type = (!empty($role))?  strtolower($role->role_type) : '';*/

			if($this->role_type==self::ADMIN)
			{
				$this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);

			}elseif($this->role_type==self::STAFF){
				$this->notification->save_notification($this->parent_id,$title,$msg,$this->user_session->id);
			}
        }elseif($this->user_type==self::LCO_LOWER){

            $role = $this->user->get_user_role($this->user_id);
            $role_type = (!empty($role))?  strtolower($role->role_type) : '';

            if($this->role_type==self::ADMIN)
            {                                    
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);    
                          
            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->parent_id,$title,$msg,$this->user_session->id);
            }
        }
    }

}