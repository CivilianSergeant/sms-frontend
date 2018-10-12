<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/12/2016
 * Time: 2:26 PM
 */
class Subscriber_parking_reassign_log_model extends MY_Model
{
    protected $table_name = "subscriber_parking_reassign_logs";

    public function get_parks($id){
        $this->db->select($this->table_name.'.id, stb_card_pairing_id,parking_date,'.$this->table_name.'.lco_id,is_cas_pairing,free_stb,free_card,free_subscription_fee,pairing_id,stb_id,card_id,set_top_boxes.external_card_number,smart_cards.internal_card_number');
        $this->db->from($this->table_name);
        $this->db->join('set_top_boxes','set_top_boxes.id = '.$this->table_name.'.stb_id');
        $this->db->join('smart_cards','smart_cards.id = '.$this->table_name.'.card_id');
        $this->db->where($this->table_name.'.parent_id',$id);
        $q = $this->db->get();
        return $q->result();
    }
}