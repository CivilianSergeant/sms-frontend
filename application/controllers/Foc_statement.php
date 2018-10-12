<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/14/2016
 * Time: 3:03 PM
 */
class Foc_statement extends BaseController
{
    protected $user_session;
    protected $user_type;
    protected $user_id;
    protected $parent_id;
    protected $message_sign;
    protected $role_name;
    protected $role_type;
    protected $role_id;

    const LCO_UPPER = 'LCO';
    const LCO_LOWER = 'lco';
    const MSO_UPPER = 'MSO';
    const MSO_LOWER = 'mso';
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
        $role_name = (!empty($role)) ? strtolower($role->role_name) : '';
        $role_type = (!empty($role)) ? strtolower($role->role_type) : '';
        $this->role_name = $role_name;
        $this->role_type = $role_type;
        $this->role_id = $this->user_session->role_id;

        if ($this->user_type == self::LCO_LOWER) {
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
        }

    }

    public function index()
    {

        $this->theme->set_title('Report: Foc Client Statement')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/reports/foc-client-statement.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('reports/foc-client-statement', $data, true);
    }

    public function ajax_get_subscriber_by_lco($lco_id)
    {
        if($this->input->is_ajax_request()) {
            if($lco_id == -1){
                $lco_id = 1;
            }
            $subscribers = $this->subscriber_profile->get_all_subscribers($lco_id);
            //array_unshift($subscribers, array('user_id'=>0,'subscriber_name'=>'All'));
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
            $lco_id = $this->user_id;
            $subscriber_id = $this->input->post('subscriber_id');
            $pairing_id    = $this->input->post('pairing_id');
            $from_date     = $this->input->post('from_date');
            $to_date       = $this->input->post('to_date');
            $transactions =	$this->subscriber_transcation->get_statements($lco_id,$subscriber_id,$pairing_id,$from_date,$to_date);
            echo json_encode(array('status'=>200,'transactions'=>$transactions));
        }else{
            redirect('/');
        }
    }

    public function collection_statements()
    {

        $this->theme->set_title('Report: Foc Collection Statement')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_script('controllers/reports/foc-collection-statement.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('reports/foc-collection-statement',$data,true);
    }

    public function ajax_get_collection_statements()
    {

        if($this->input->is_ajax_request()){
            $lco_id        = $this->user_id;
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