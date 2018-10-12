<?php

class Epg_provider_channel_model extends MY_Model
{
    protected $table_name="epg_provider_channels";

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_all_epg_provider_channels($limit=0,$offset=0)
    {
        $this->db->select('*');

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        $query = $this->db->get($this->table_name);

        return $query->result();
    }
    
    public function count_all_epg_provider_channel()
    {
        return $this->db->count_all_results($this->table_name);
    }
    
    public function get_channels_by_provider($provider_id)
    {
        $this->db->where('provider_id',$provider_id);
        $q = $this->db->get($this->table_name);
        return $q->result();
    }
    
    public function get_channel_by_id($provider_channel_id)
    {
        $this->db->where('provider_channel_id',$provider_channel_id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }

    
}