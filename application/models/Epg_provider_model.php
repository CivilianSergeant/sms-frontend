<?php

class Epg_provider_model extends MY_Model
{
    protected $table_name="epg_providers";

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_all_epg_providers($limit=0,$offset=0)
    {
        $this->db->select('*');

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        $query = $this->db->get($this->table_name);

        return $query->result();
    }
    
    public function count_all_epg_providers()
    {
        return $this->db->count_all_results($this->table_name);
    }
    
    public function get_epg_provider_by_id($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);

        return $query->row();
    }
    
    public function remove_by_id($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }

    
}