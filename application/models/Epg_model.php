<?php

class Epg_model extends MY_Model
{
    protected $table_name="epgs";

    public function __construct()
    {
        parent::__construct();
    }

    public function has_epg_by_program($program_id,$program_name)
    {
        $this->db->where('program_id',$program_id);
        $this->db->where('program_name',$program_name);
        $this->db->limit(1);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }

    public function get_searched_epgs($filter,$limit=0,$offset=0,$sort=null)
    {

        $select = "select * from epgs where program_id = ".$filter['channel_id'];
        if($filter['show_date'] != 'undefined'){
            $select .= " AND show_date = '".$filter['show_date']."'";
        }

        if($filter['start_time'] != 'undefined'){
            $select .= " AND start_time >= '".$filter['start_time']."'";
        }

        if($filter['end_time'] != 'undefined'){
            $select .= " AND end_time <= '".$filter['end_time']."'";
        }

        if(!empty($limit))
        $select .= " LIMIT {$offset}, {$limit}";

        $q = $this->db->query($select);
        return $q->result();
    }

    public function count_searched_epgs($filter){
        $select = "select count(*) as total from epgs where program_id = ".$filter['channel_id'];
        if($filter['show_date'] != 'undefined'){
            $select .= " AND show_date = '".$filter['show_date']."'";
        }

        if($filter['start_time'] != 'undefined'){
            $select .= " AND start_time >= '".$filter['start_time']."'";
        }

        if($filter['end_time'] != 'undefined'){
            $select .= " AND end_time <= '".$filter['end_time']."'";
        }

        $q = $this->db->query($select);
        $total = $q->row();
        return (!empty($total))? $total->total : 0;
    }

    public function get_all_epgs($limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select('id,GetIptvProgramName(program_id) as channel_name,program_name,show_date,UCASE(week_days) as week_days,start_time,end_time,epg_type');

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    public function count_all_epgs($filter=null)
    {
        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }
        return $this->db->count_all_results($this->table_name);
    }

    public function remove_by_id($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }
    
    public function get_epg_provider_mapping($id)
    {
        $sql = "select provider_id,provider_channel_id, GetEpgProviderName(provider_id) as provider, 
            GetEpgProviderChannel(provider_channel_id) as provider_channel from epg_channel_mapping
where streaming_channel_id = $id LIMIT 1";
        $q  = $this->db->query($sql);
        return $q->row();
    }
    
    public function get_epg_mapping_by_provider($id)
    {
        $sql = "SELECT epg_channel_mapping.id, epg_provider_channels.provider_channel_name,iptv_programs.program_name,epg_channel_mapping.provider_channel_id,streaming_channel_id FROM epg_channel_mapping
JOIN epg_provider_channels ON epg_provider_channels.provider_channel_id = epg_channel_mapping.provider_channel_id
JOIN iptv_programs ON iptv_programs.id = epg_channel_mapping.streaming_channel_id
WHERE epg_channel_mapping.provider_id = $id";
        $q = $this->db->query($sql);
        return $q->result();
    }
    
    public function get_epg_provider($id)
    {
        $sql = "SELECT * FROM epg_providers WHERE id = ".$id." LIMIT 1";
        $q = $this->db->query($sql);
        return $q->row();
    }
}