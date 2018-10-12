<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/29/2016
 * Time: 1:56 PM
 */
class Ftp_account_model extends MY_Model
{
    protected $table_name = 'ftp_accounts';

    /**
     * @param $id
     * @param int $limit
     * @param int $offset
     * @param null $filter
     * @param null $sort
     * @return mixed
     */
    public function get_all_accounts($id,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select('id, name, CONCAT(server_ip,":",server_port) as server_ip,dir_location ,user_id,password,parent_id,created_at');
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

        $q = $this->db->get($this->table_name);
        return $q->result();
    }

    /**
     * @param $id
     * @param null $filter
     * @return mixed
     */
    public function count_all_accounts($id,$filter=null)
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

    public function get_ftp_transfer_logs($limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select('name,server_ip,server_port,file_name,status,done_time,type');
        $this->db->from('ftp_transfer_logs');
        $this->db->join($this->table_name,$this->table_name.'.id = ftp_transfer_logs.ftp_account_id','left');


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

    public function count_ftp_transfer_logs($filter=null)
    {
        $this->db->from('ftp_transfer_logs');
        $this->db->join($this->table_name,$this->table_name.'.id = ftp_transfer_logs.ftp_account_id','left');


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

    public function remove_by_id($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }
}