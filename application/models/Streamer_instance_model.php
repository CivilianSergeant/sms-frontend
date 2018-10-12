<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/13/2016
 * Time: 5:52 PM
 */
class Streamer_instance_model extends MY_Model
{
    protected $table_name='streamer_instances';

    public function get_all_instances($id,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        $select = "id,instance_name,instance_local_ip,instance_global_ip,instance_index,instance_description,is_active,instance_capacity, alias_domain_url,
        if(type='MSO',GetMSOName(operator_id),GetLCOName(operator_id)) as operator,operator_id,type, if(GetAssignedHLSCount(id)>0,GetAssignedHLSCount(id),0) as assigned_hls";

        $this->db->select($select);
        $this->db->from($this->table_name);
        $this->db->where('parent_id',$id);

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
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_instances($id,$filter=null)
    {
        $this->db->where('parent_id',$id);

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

    public function get_all_active_instances()
    {

    }

    public function find_by_lco($lco_id)
    {
        $this->db->where('operator_id',$lco_id);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    public function find_by_local_ip($ip)
    {
        $this->db->where('instance_local_ip',$ip);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    public function find_by_global_ip($ip)
    {
        $this->db->where('instance_global_ip',$ip);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    public function remove($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
        return true;
    }
}