<?php

class Vendor_model extends MY_Model
{
    protected $table_name="vendors";

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_all_vendors($limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select();

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

    public function count_all_vendors($filter=null)
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

}

