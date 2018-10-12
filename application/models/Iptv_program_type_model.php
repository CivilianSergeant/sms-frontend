<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/7/2016
 * Time: 9:58 AM
 */
class Iptv_program_type_model extends MY_Model
{
    protected $table_name = "iptv_program_types";

    public function get_content_types()
    {
        $this->db->where('type','CATCHUP');
        $this->db->or_where('type','VOD');
        $q= $this->db->get($this->table_name);
        return $q->result();
    }
}