<?php

/**
 * @property Billing_subscriber_transaction_model $subscriber_transcation
 * @property Bank_account_model $bank_account
 */
class Bank_statement extends BaseController
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
    const ROUTE = 'pos-settings';

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
        $this->theme->set_title('Bank Statement')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/reports/bank-statement.js');
        $subscriber_id = base64_decode(urldecode($this->uri->segment(2)));
        $data['subscriber_id'] = $subscriber_id;
        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('reports/bank-statement', $data, true);
    }

    public function ajax_get_bank_accounts()
    {
        if($this->input->is_ajax_request()){
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            if($this->user_type != self::LCO_LOWER) {
                $bank_accounts = $this->bank_account->get_all_accounts($id);
            }else{
                $bank_accounts = $this->bank_account->get_all_lco_accounts($id);
            }
            array_unshift($bank_accounts,array('id'=>0,'account_name'=>'','account_no'=>'All'));
            echo json_encode(array('status'=>200,'bank_accounts'=>$bank_accounts));
        }else{
            redirect('/');
        }
    }

    public function ajax_get_statements()
    {
        if($this->input->is_ajax_request()){
            $bank_account_id = $this->input->post('bank_account_id');
            $from_date     = $this->input->post('from_date');
            $to_date	   = $this->input->post('to_date');
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            if($bank_account_id == 0){
                $transactions = $this->subscriber_transcation->get_bank_statements($id,null,$from_date,$to_date);
            }else{

                $bank_account = $this->bank_account->get_account_by_creator($id,$bank_account_id);

                $transactions =	$this->subscriber_transcation->get_bank_statements($id,$bank_account->id,$from_date,$to_date);

            }

            echo json_encode(array('status'=>200,'transactions'=>$transactions));
        }else{
            redirect('/');
        }
    }
}