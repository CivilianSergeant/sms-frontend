<?php

/**
 * Description of Map_provider_channel_model
 *
 * @author Himel
 */
class Map_provider_channel_model extends MY_Model
{
    protected $table_name="epg_channel_mapping";

    public function __construct()
    {
        parent::__construct();
    }
    
    public function is_exist($provider_id,$provider_channel_id,$streaming_channel_id)
    {
        $this->db->where('provider_id',$provider_id);
        $this->db->where('provider_channel_id',$provider_channel_id);
        $this->db->where('streaming_channel_id',$streaming_channel_id);
        return $this->db->count_all_results($this->table_name);
    }
}
