<?php

class Lco_group_model extends MY_Model
{
    protected $table_name = "lco_groups";

    public function is_already_exist($lco_id,$group_id=null)
    {
        $this->db->where('lco_id',$lco_id);
        if(!empty($group_id)){
            $this->db->where('group_id',$group_id);
        }
        $this->db->limit(1);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
}