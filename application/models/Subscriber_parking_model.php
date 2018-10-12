<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/12/2016
 * Time: 2:26 PM
 */
class Subscriber_parking_model extends MY_Model
{
    protected $table_name = "subscriber_parkings";

    public function get_parks($id){
        $this->db->select($this->table_name.'.id, stb_card_pairing_id,parking_date,'.$this->table_name.'.lco_id,is_cas_pairing,free_stb,free_card,free_subscription_fee,pairing_id,stb_id,card_id,set_top_boxes.external_card_number,smart_cards.internal_card_number');
        $this->db->from($this->table_name);
        $this->db->join('set_top_boxes','set_top_boxes.id = '.$this->table_name.'.stb_id');
        $this->db->join('smart_cards','smart_cards.id = '.$this->table_name.'.card_id');
        $this->db->where($this->table_name.'.parent_id',$id);
        $this->db->where('status',0);
        $q = $this->db->get();
        return $q->result();
    }

    public function update_park_status($id,$by)
    {
        $this->db->where('id',$id);
        $this->db->update($this->table_name,array(
            'status'=>1,
            'updated_at' => date('Y-m-d H:I:s'),
            'updated_by' => $by
        ));
        return $this->db->affected_rows();
    }

    //Get Parking Report
    public function get_parking_report($parent,$subscriber_id=null,$stb_id=null,$card_id=null,$from_date=null,$to_date=null,$limit=0,$offset=0){

        $this->db->select($this->table_name.'.id,set_top_boxes.external_card_number as stb_id, smart_cards.external_card_number as card_id, GetSubscriberName('.$this->table_name.'.subscriber_id) as subscriber_name, parking_date');
        $this->db->from($this->table_name);
        $this->db->join("set_top_boxes","set_top_boxes.id = ".$this->table_name.'.stb_id');
        $this->db->join("smart_cards","smart_cards.id = ".$this->table_name.'.card_id');

        $this->db->where($this->table_name.'.status',0);
        $this->db->where($this->table_name.'.parent_id',$parent);

        if(!empty($subscriber_id)) {
            $this->db->where($this->table_name . '.subscriber_id', $subscriber_id);
        }
        if(!empty($stb_id)){
            $this->db->where($this->table_name.'.stb_id',$stb_id);
        }

        if(!empty($card_id)){
            $this->db->where($this->table_name.'.card_id',$card_id);
        }

        if(!empty($from_date) && !empty($to_date)){
            $this->db->where($this->table_name.".parking_date BETWEEN '{$from_date}%' AND '{$to_date}%'");
        }

        if(!empty($limit) && !empty($offset)){
            $this->db->limit($limit,$offset);
        }

        $q = $this->db->get();
        return $q->result();

    }

    // Get Parking Report Count
    public function get_parking_report_count($parent,$subscriber_id,$stb_id=null,$card_id=null,$from_date=null,$to_date=null)
    {
        //$this->db->select($this->table_name.'.id,set_top_boxes.external_card_number as stb_id, smart_cards.external_card_number as card_id, GetSubscriberName('.$this->table_name.'.subscriber_id) as subscriber_name, parking_date');
        $this->db->from($this->table_name);
        $this->db->join("set_top_boxes","set_top_boxes.id = ".$this->table_name.'.stb_id');
        $this->db->join("smart_cards","smart_cards.id = ".$this->table_name.'.card_id');

        $this->db->where($this->table_name.'.status',0);
        $this->db->where($this->table_name.'.parent_id',$parent);

        if(!empty($subscriber_id)){
            $this->db->where($this->table_name.'.subscriber_id',$subscriber_id);
        }

        if(!empty($stb_id)){
            $this->db->where($this->table_name.'.stb_id',$stb_id);
        }

        if(!empty($card_id)){
            $this->db->where($this->table_name.'.card_id',$card_id);
        }

        if(!empty($from_date) && !empty($to_date)){
            $this->db->where($this->table_name.".parking_date BETWEEN '{$from_date}%' AND '{$to_date}%'");
        }

        if(!empty($limit) && !empty($offset)){
            $this->db->limit($limit,$offset);
        }

        return $this->db->count_all_results();

    }


    //Get Parking Reassign Report
    public function get_reassign_report($parent,$subscriber_id=null,$stb_id=null,$card_id=null,$from_date=null,$to_date=null,$limit=0,$offset=0){
        $tableName = $this->table_name; //"subscriber_parking_reassign_logs";
        $this->db->select($tableName.'.id,set_top_boxes.external_card_number as stb_id, smart_cards.external_card_number as card_id, GetSubscriberName('.$tableName.'.subscriber_id) as old_subscriber_name,GetSubscriberName(subscriber_stb_smartcards.subscriber_id) as new_subscriber_name, parking_date,'.$tableName.'.updated_at');
        $this->db->from($tableName);
        $this->db->join("set_top_boxes","set_top_boxes.id = ".$tableName.'.stb_id',"left");
        $this->db->join("smart_cards","smart_cards.id = ".$tableName.'.card_id',"left");
        $this->db->join("subscriber_stb_smartcards","set_top_boxes.id = subscriber_stb_smartcards.stb_id AND smart_cards.id = subscriber_stb_smartcards.card_id","left");

        $this->db->where($tableName.'.status',1);
        $this->db->where($tableName.'.parent_id',$parent);

        if(!empty($subscriber_id)) {
            $this->db->where($tableName . '.subscriber_id', $subscriber_id);
        }
        if(!empty($stb_id)){
            $this->db->where($tableName.'.stb_id',$stb_id);
        }

        if(!empty($card_id)){
            $this->db->where($tableName.'.card_id',$card_id);
        }

        if(!empty($from_date) && !empty($to_date)){
            $this->db->where($tableName.".updated_at BETWEEN '{$from_date}%' AND '{$to_date}%'");
        }

        if(!empty($limit) && !empty($offset)){
            $this->db->limit($limit,$offset);
        }

        $q = $this->db->get();
        return $q->result();

    }

    // Get Parking Reassign Report Count
    public function get_reassign_report_count($parent,$subscriber_id,$stb_id=null,$card_id=null,$from_date=null,$to_date=null)
    {
        $tableName = $this->table_name; //"subscriber_parking_reassign_logs";

        //$this->db->select($tableName.'.id,set_top_boxes.external_card_number as stb_id, smart_cards.external_card_number as card_id, GetSubscriberName('.$tableName.'.subscriber_id) as old_subscriber_name,GetSubscriberName(subscriber_stb_smartcards.subscriber_id) as new_subscriber_name, parking_date');
        $this->db->from($tableName);
        $this->db->join("set_top_boxes","set_top_boxes.id = ".$tableName.'.stb_id',"left");
        $this->db->join("smart_cards","smart_cards.id = ".$tableName.'.card_id',"left");
        $this->db->join("subscriber_stb_smartcards","set_top_boxes.id = subscriber_stb_smartcards.stb_id AND smart_cards.id = subscriber_stb_smartcards.card_id","left");

        $this->db->where($tableName.'.status',1);
        $this->db->where($tableName.'.parent_id',$parent);

        if(!empty($subscriber_id)){
            $this->db->where($tableName.'.subscriber_id',$subscriber_id);
        }

        if(!empty($stb_id)){
            $this->db->where($tableName.'.stb_id',$stb_id);
        }

        if(!empty($card_id)){
            $this->db->where($tableName.'.card_id',$card_id);
        }

        if(!empty($from_date) && !empty($to_date)){
            $this->db->where($tableName.".parking_date BETWEEN '{$from_date}%' AND '{$to_date}%'");
        }

        if(!empty($limit) && !empty($offset)){
            $this->db->limit($limit,$offset);
        }

        return $this->db->count_all_results();

    }






}