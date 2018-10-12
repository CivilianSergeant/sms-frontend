<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Money_receipt extends BaseController
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

        if($this->user_type == self::LCO_LOWER){
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
        }
		

	}

	public function index()
	{
		$this->theme->set_title('Money Receipt')
                ->add_style('component.css')
                ->add_style('custom.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_script('controllers/money_receipt/money_receipt.js');

		$data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('money_receipt/index', $data, true);
	}

	public function ajax_get_permissions()
	{
		if($this->role_type=="admin"){
			$permissions = array('create_permission'=>1,'view_permission'=>1,'edit_permission'=>1,'delete_permission'=>1);
		}else{
			$permissions = $this->menus->has_permission($this->role_id,1,'assign-money-receipt',$this->user_type);
		}
		echo json_encode(array('status'=>200,'permissions'=>$permissions));
	}

	public function ajax_get_collectors()
	{
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		if($this->input->is_ajax_request()){
			echo json_encode(array(
				'status'=>200,
				'collectors'=>$this->collector->get_all_collectors_by_lco($id)
			));
		} else {
			$this->session->set_flashdata('warning_messages','Direct access not allowed');
			redirect('/');
		}
	}

	public function assign_bulk_receipt()
	{


		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		if($this->input->is_ajax_request()){

			if($this->role_type == "staff") {
				$permission = $this->menus->has_create_permission($this->role_id, 1, 'assign-money-receipt', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to Assign Money Receipt"));
					exit;
				}
			}

			$token        = $this->input->post('token');
			$pages        = $this->input->post('pages');
			$collector_id = $this->input->post('collector_id');
			$book_number  = $this->input->post('book_number');
			$from         = $this->input->post('from');
			$to           = $this->input->post('to');
			$pages        = $this->input->post('pages');
			$collector    = $this->collector->find_by_id($collector_id);
			$save_money_receipt_book = array(
				'lco_id' => $id,
				'collector_id' => $collector_id,
				'money_receipt_book_number' => $book_number,
				'from' => $from,
				'to'   => $to,
				'number_of_page' => $pages,
				'created_by' => $id
			);



			$this->money_receipt_book->save($save_money_receipt_book);

			$assigned = $this->money_receipt->is_assigned_within_range($book_number,$from,$to);
			
			if($assigned){
				echo json_encode(array('status'=>400,'warning_messages'=>'A Receipt Number('.$assigned->money_receipt_number.') already assigned to '.$assigned->name.', So you cannot specify that range'));
				exit;
			}

			for($i=$from;$i<=$to;$i++)
			{
				$save_money_receipt = array(
					'lco_id' => $id,
					'collector_id' => $collector_id,
					'subscriber_id'=> null,
					'is_used'      => 0,
					'money_receipt_number' => $i,
					'money_receipt_book_number' => $book_number,
					'created_by' => $id
				);
				$this->money_receipt->save($save_money_receipt);
			}
			$this->set_notification("Bulk money receipt assinged","Bulk money receipt from {$from} to {$to}, total pages {$pages} has been assigned to collector {$collector->get_attribute('name')}");
			echo json_encode(array('status'=>200,'success_messages'=>'Money Receipt Book '. $book_number .' successfully assigned'));

		} else {
			$this->session->set_flashdata('warning_messages','Direct access not allowed');
			redirect('/');
		}
	}

	public function assign_single_receipt()
	{
		$id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
		if($this->input->is_ajax_request()){

			if($this->role_type == "staff") {
				$permission = $this->menus->has_create_permission($this->role_id, 1, 'assign-money-receipt', $this->user_type);
				if (!$permission) {
					echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission to Assign Money Receipt"));
					exit;
				}
			}

			$token        = $this->input->post('token');
			$pages        = $this->input->post('pages');
			$collector_id = $this->input->post('collector_id');
			$book_number  = $this->input->post('book_number');
			$receipt_number = $this->input->post('receipt_number');
			$collector  = $this->collector->find_by_id($collector_id);
			/*$from         = $this->input->post('from');
			$to           = $this->input->post('to');*/
			/*$pages        = $this->input->post('pages');*/

			/*$save_money_receipt_book = array(
				'lco_id' => $this->user_session->id,
				'collecotr_id' => $collector_id,
				'money_receipt_book_number' => $book_number,
				'from' => $from,
				'to'   => $to,
				'number_of_pages' => $pages,
				'created_by' => $this->user_session->id
			);

			$this->money_receipt_book->save($save_money_receipt_book);*/

			
			$save_money_receipt = array(
				'lco_id' => $id,
				'collector_id' => $collector_id,
				'subscriber_id'=> null,
				'is_used'      => 0,
				'money_receipt_number' => $receipt_number,
				'money_receipt_book_number' => $book_number,
				'created_by' => $id
			);



			$assigned = $this->money_receipt->is_assigned($book_number,$receipt_number);
			if($assigned){
				echo json_encode(array('status'=>400,'warning_messages'=>'Receipt Number already assigned to '.$assigned->name));
				exit;
			}

			$this->money_receipt->save($save_money_receipt);
			$this->set_notification("Single money receipt assinged","Single money receipt {$receipt_number} assigned to collector {$collector->get_attribute('name')}");

			echo json_encode(array('status'=>200,'success_messages'=>'Money Receipt Book '. $book_number .' successfully assigned'));

		} else {
			$this->session->set_flashdata('warning_messages','Direct access not allowed');
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

            /*$role_name = $this->user->get_user_role($this->user_id);
            $role_name = (!empty($role_name))?  strtolower($role_name) : '';  */

            if($this->role_type==self::ADMIN)
            {                                    
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);    
                          
            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->parent_id,$title,$msg,$this->user_session->id);
            }
        }
    }
}