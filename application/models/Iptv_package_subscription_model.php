<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/7/2016
 * Time: 12:25 PM
 */
class Iptv_package_subscription_model extends MY_Model
{
    protected $table_name = "iptv_package_subscription";

    public function __construct()
    {
        parent::__construct();
    }

    public function assigned_package($id)
    {
        $this->db->where('package_id',$id);
        $query = $this->db->get($this->table_name);
        $this->db->group_by('package_id');
        $results = $query->result();
        $query->free_result();
        return $results;
    }

    public function get_assigned_packages($subscriber_id,$stb_card_id=null)
    {
        $select = "subscriber_stb_smartcards.id as stb_card_id, subscriber_stb_smartcards.pairing_id,free_subscription_fee,
			iptv_packages.id,iptv_packages.package_name,iptv_packages.duration,iptv_packages.price,iptv_package_subscription.id as user_package_id,iptv_package_subscription.package_start_date,iptv_package_subscription.package_expire_date,
			count(iptv_package_programs.program_id) as no_of_program,`iptv_package_subscription`.`no_of_days`,`iptv_package_subscription`.`charge_type`";

        $this->db->select($select);
        $this->db->from('subscriber_stb_smartcards');
        $this->db->join('iptv_package_subscription','iptv_package_subscription.stb_smart_id = subscriber_stb_smartcards.id','left');
        $this->db->join('iptv_packages','iptv_packages.id = iptv_package_subscription.package_id','left');
        $this->db->join('iptv_package_programs','iptv_package_programs.package_id = iptv_package_subscription.package_id','left');
        $this->db->where('iptv_package_subscription.user_id',$subscriber_id);


        if($stb_card_id != null){

            $this->db->where('subscriber_stb_smartcards.id',$stb_card_id);
        }

        $this->db->group_by('iptv_package_subscription.id');
        $query = $this->db->get();
        $results = $query->result();
        $pairing_packages = array();
        date_default_timezone_set('Asia/Dhaka');
        foreach($results as $result){

            $pairing_packages[$result->stb_card_id]['stb_card_id'] = $result->stb_card_id;
            $pairing_packages[$result->stb_card_id]['pairing_id']  = $result->pairing_id;
            $pairing_packages[$result->stb_card_id]['start_date']  = $result->package_start_date;
            $pairing_packages[$result->stb_card_id]['expire_date'] = $result->package_expire_date;
            $pairing_packages[$result->stb_card_id]['charge_type'] = $result->charge_type;
            $pairing_packages[$result->stb_card_id]['free_subscription_fee'] = $result->free_subscription_fee;

            $today_date_object   = new DateTime();
            $expire_date_object  = new DateTime($result->package_expire_date);
            $date_diff = date_diff($today_date_object,$expire_date_object);
            $no_of_days = 0;
            if($date_diff->days > 0 && $date_diff->invert == 0){
                $no_of_days = $date_diff->days;
            }else{
                $no_of_days = '-'.$date_diff->days;
            }
            $pairing_packages[$result->stb_card_id]['no_of_days'] = $no_of_days;
            $pairing_packages[$result->stb_card_id]['duration'] = $result->duration;


            if(empty($pairing_packages[$result->stb_card_id]['total_price'])){
                $pairing_packages[$result->stb_card_id]['total_price'] = $result->price;
            } else {
                $pairing_packages[$result->stb_card_id]['total_price'] += $result->price;
            }

            $pairing_packages[$result->stb_card_id]['packages'][] = array(
                'user_package_id' => $result->user_package_id,
                'id'           => $result->id,
                'package_name' => $result->package_name,
                'duration'     => $result->duration,
                'start_date'   => $result->package_start_date,
                'expire_date'  => $result->package_expire_date,
                'price'        => $result->price,
                'no_of_program'=> $result->no_of_program,
                'no_of_days'   => $result->no_of_days
            );
        }

        return $pairing_packages;

    }
}