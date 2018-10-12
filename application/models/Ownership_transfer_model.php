<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/13/2016
 * Time: 3:09 PM
 */
class Ownership_transfer_model extends MY_Model
{
    protected $table_name = 'ownership_transfers';

    //Get Parking Reassign Report
    public function get_transfer_report($parent,$subscriber_id=null,$stb_id=null,$card_id=null,$from_date=null,$to_date=null,$limit=0,$offset=0){
        $tableName = $this->table_name;
        $this->db->select($tableName.'.id,set_top_boxes.external_card_number as stb_id, smart_cards.external_card_number as card_id, GetSubscriberName('.$tableName.'.subscriber_id) as old_subscriber_name,GetSubscriberName(subscriber_stb_smartcards.subscriber_id) as new_subscriber_name,'.$tableName.'.created_at');
        $this->db->from($tableName);
        $this->db->join("set_top_boxes","set_top_boxes.id = ".$tableName.'.stb_id',"left");
        $this->db->join("smart_cards","smart_cards.id = ".$tableName.'.card_id',"left");
        $this->db->join("subscriber_stb_smartcards",$tableName.".old_id = subscriber_stb_smartcards.id","left");

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
            $this->db->where($tableName.".created_at BETWEEN '{$from_date}%' AND '{$to_date}%'");
        }

        if(!empty($limit) && !empty($offset)){
            $this->db->limit($limit,$offset);
        }

        $q = $this->db->get();
        return $q->result();

    }

    // Get Parking Reassign Report Count
    public function get_transfer_report_count($parent,$subscriber_id,$stb_id=null,$card_id=null,$from_date=null,$to_date=null)
    {
        $tableName = $this->table_name;

        $this->db->from($tableName);
        $this->db->join("set_top_boxes","set_top_boxes.id = ".$tableName.'.stb_id',"left");
        $this->db->join("smart_cards","smart_cards.id = ".$tableName.'.card_id',"left");
        $this->db->join("subscriber_stb_smartcards",$tableName.".old_id = subscriber_stb_smartcards.id","left");

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