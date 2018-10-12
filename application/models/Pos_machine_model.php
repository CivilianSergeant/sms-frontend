<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/17/2016
 * Time: 4:30 PM
 */
class Pos_machine_model extends MY_Model
{
    protected $table_name = "pos_machines";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_pos_machines($id, $limit=0,$offset=0,$filter=null,$sort=null)
    {
        $select = "pos_machines.token,concat(account_name,' [',account_no,']') as account_info,pos_machine_id,name,charge_interest,pos_machines.is_active";
        $this->db->select($select);
        $this->db->from($this->table_name);
        $this->db->join('bank_accounts',$this->table_name.'.bank_account_id=bank_accounts.id');
        $this->db->join('billing_lco_collector',$this->table_name.'.collector_id=billing_lco_collector.id');



        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        $this->db->where($this->table_name.'.parent_id',$id);

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

    public function get_count_pos_machines($id,$filter)
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
        $this->db->where($this->table_name.'.parent_id',$id);
        return $this->db->count_all_results($this->table_name);
    }

    public function get_pos_by_machine_id($machine_id,$collector_id = null)
    {
        $this->db->where('pos_machine_id',$machine_id);
        if(!empty($collector_id))
        {
            $this->db->where('collector_id',$collector_id);
        }
        $this->db->limit(1);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }
}