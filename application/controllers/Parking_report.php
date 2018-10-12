<?php

/**
* @property Subscriber_parking_model $subscriber_parking
*/
class Parking_report extends BaseController
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
        $this->theme->set_title('Parking Report')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/parkings/parking-report.js');

        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('reports/parking-report', $data, true);
    }

    public function ajax_get_data()
    {
        if($this->input->is_ajax_request()) {
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

            if($this->user_type == self::MSO_LOWER){
                $stb_cards   = $this->set_top_box->get_all_stb($id);
                $ic_cards    = $this->ic_smartcard->get_all_ic_smart_card($id);
                $subscribers = $this->subscriber_profile->get_all_subscribers($id);
            }else{
                $stb_cards = $this->set_top_box->get_stb_assigned_to_lco($id);
                $ic_cards  = $this->ic_smartcard->get_smartcard_assigned_to_lco($id);
                $subscribers = $this->subscriber_profile->get_all_subscribers($id);
            }

            echo json_encode(
                array(
                    'status'=>200,
                    'stb_cards'=>$stb_cards,
                    'ic_cards'=>$ic_cards,
                    'subscribers' => $subscribers,
                )
            );

        }else{
            redirect('/');
        }
    }

    public function ajax_get_parking_report()
    {
        if($this->input->is_ajax_request()){
            $subscriber_id = $this->input->get('subscriber_id');
            $stb_id = $this->input->get('stb');
            $card_id = $this->input->get('smart_card');
            $from_date = $this->input->get('from_date');
            $to_date = $this->input->get('to_date');
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $parent = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;

            $reports = $this->subscriber_parking->get_parking_report($parent,$subscriber_id,$stb_id,$card_id,$from_date,$to_date,$take,$skip);
            $total = $this->subscriber_parking->get_parking_report_count($parent,$subscriber_id,$stb_id,$card_id,$from_date,$to_date);

            echo json_encode(array('status'=>200,'reports'=>$reports,'total'=>$total));
        }else{
            redirect('/');
        }
    }


    public function reassign_pairing_report()
    {
        $this->theme->set_title('Reassign Parked Item Report')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/parkings/parking-report.js');

        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('reports/reassign-from-parking', $data, true);
    }

    public function ajax_get_reassign_report()
    {
        if($this->input->is_ajax_request()){
            $subscriber_id = $this->input->get('subscriber_id');
            $stb_id = $this->input->get('stb');
            $card_id = $this->input->get('smart_card');
            $from_date = $this->input->get('from_date');
            $to_date = $this->input->get('to_date');
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $parent = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $reports = $this->subscriber_parking->get_reassign_report($parent,$subscriber_id,$stb_id,$card_id,$from_date,$to_date,$take,$skip);

            $total = $this->subscriber_parking->get_reassign_report_count($parent,$subscriber_id,$stb_id,$card_id,$from_date,$to_date);

            echo json_encode(array('status'=>200,'reports'=>$reports,'total'=>$total));
        }else{
            redirect('/');
        }
    }

    public function ownership_transfer()
    {
        $this->theme->set_title('Ownership Transfer Report')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/parkings/parking-report.js');

        $data['user_info']    = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme']        = $this->theme->get_image_path();
        $this->theme->set_view('reports/ownership-transfer', $data, true);
    }


    public function ajax_get_transfer_report()
    {
        if($this->input->is_ajax_request()){
            $subscriber_id = $this->input->get('subscriber_id');
            $stb_id = $this->input->get('stb');
            $card_id = $this->input->get('smart_card');
            $from_date = $this->input->get('from_date');
            $to_date = $this->input->get('to_date');
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $parent = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $reports = $this->ownership_transfer->get_transfer_report($parent,$subscriber_id,$stb_id,$card_id,$from_date,$to_date,$take,$skip);

            $total   = $this->ownership_transfer->get_transfer_report_count($parent,$subscriber_id,$stb_id,$card_id,$from_date,$to_date);

            echo json_encode(array('status'=>200,'reports'=>$reports,'total'=>$total));
        }else{
            redirect('/');
        }
    }

}