<?php

class Epg_repeat_time_model extends MY_Model
{
    protected $table_name="epg_repeat_times";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_epg($epg_id)
    {
        $this->db->where('epg_id',$epg_id);
        $q = $this->db->get($this->table_name);
        return $q->result();
    }

    public function remove_by_epg($epg_id)
    {
        $this->db->where('epg_id',$epg_id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }
}