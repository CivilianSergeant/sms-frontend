<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/9/2016
 * Time: 10:53 AM
 */
class Bank_account_model extends MY_Model
{
    protected $table_name = "bank_accounts";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_accounts($id,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select('bank_accounts.id,bank_accounts_assign.id as shared_account_id,bank_name,account_no,account_name,bank_accounts.is_active,token');
        $this->db->from($this->table_name);
        $this->db->join('bank_accounts_assign','bank_accounts.id=bank_accounts_assign.bank_account_id','left');
        $this->db->where('bank_accounts.parent_id', $id);

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

    public function get_count_accounts($id,$filter=null)
    {
        $this->db->from($this->table_name);
        $this->db->join('bank_accounts_assign','bank_accounts.id=bank_accounts_assign.bank_account_id','left');
        $this->db->where('bank_accounts.parent_id',$id);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        return $this->db->count_all_results();
    }


    public function get_all_lco_accounts($id,$limit=0,$offset=0,$filter=null,$sort=null)
    {

        $this->db->select('bank_accounts.id,bank_accounts_assign.id as shared_account_id,bank_name,account_no,account_name,bank_accounts.is_active,token');
        $this->db->from($this->table_name);
        $this->db->join('bank_accounts_assign','bank_accounts.id=bank_accounts_assign.bank_account_id','left');
        $this->db->where('bank_accounts.parent_id', $id);
        $this->db->or_where('bank_accounts_assign.lco_id',$id);

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

    public function get_count_lco_accounts($id,$filter=null)
    {
        $this->db->from($this->table_name);
        $this->db->join('bank_accounts_assign','bank_accounts.id=bank_accounts_assign.bank_account_id','left');
        $this->db->where('bank_accounts.parent_id',$id);
        $this->db->or_where('bank_accounts_assign.lco_id',$id);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        return $this->db->count_all_results();
    }

    public function get_shared_accounts()
    {
        $this->db->select("bank_accounts_assign.id, bank_name,account_name,account_no,lco_id,bank_account_id,GetLCOName(lco_id) as lco_name");
        $this->db->from($this->table_name);
        $this->db->join("bank_accounts_assign","bank_accounts.id = bank_accounts_assign.bank_account_id");
        $query = $this->db->get();
        return $query->result();
    }



    public function get_account_details($account)
    {
        $this->db->select('bank_accounts.account_no,GetLCOName(lco_id) as lco_name,bank_accounts_assign.created_at');
        $this->db->from($this->table_name);
        $this->db->join('bank_accounts_assign','bank_accounts.id = bank_accounts_assign.bank_account_id');
        $this->db->where('bank_accounts_assign.bank_account_id',$account->get_attribute('id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function get_account_by_creator($id,$account_id)
    {
        $this->db->select("bank_accounts.id, bank_name,account_name,account_no,lco_id,bank_account_id,GetLCOName(lco_id) as lco_name");
        $this->db->from($this->table_name);
        $this->db->join("bank_accounts_assign","bank_accounts.id = bank_accounts_assign.bank_account_id","left");
       //$this->db->where('bank_accounts.parent_id',$id);
        $this->db->where("(`bank_accounts`.`parent_id` = '$id' OR bank_accounts_assign.lco_id = '$id')");
        $this->db->where('bank_accounts.id',$account_id);
        $query = $this->db->get();

        return $query->row();
    }
}