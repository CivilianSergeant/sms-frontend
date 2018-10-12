<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/6/2016
 * Time: 4:23 PM
 */
class Iptv_category_program_model extends MY_Model
{
    protected $table_name = "iptv_category_programs";


    public function find_all_programs($category_id, $sub_category_id=null)
    {
        $this->db->where('category_id',$category_id);

        if(!empty($sub_category_id)){
            $this->db->where('sub_category_id',$sub_category_id);
        }

        $q =$this->db->get($this->table_name);

        return $q->result();
    }

    public function find_by_program($program_id)
    {
        $this->db->where('program_id',$program_id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }

    public function remove($category_id,$sub_category_id=null)
    {
        $this->db->where('category_id',$category_id);
        if(!empty($sub_category_id))
            $this->db->where('sub_category_id',$sub_category_id);
        return $this->db->delete($this->table_name);
    }

    public function remove_program($category_id,$sub_category_id,$program_id)
    {
        $this->db->where('category_id',$category_id);
        $this->db->where('sub_category_id',$sub_category_id);
        $this->db->where('program_id',$program_id);
        return $this->db->delete($this->table_name);
    }
    
    public function get_catchup_programs($id=null,$limit=0,$offset=0,$filter=null,$sort=null)
    {

        if(!empty($id)){
            $this->db->where('parent_id',$id);
        }
        //$this->db->where('serial_content',0);
        $this->db->where('type', 'CATCHUP');
        $this->db->where('is_remove',0);
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

        return $this->db->get('iptv_programs')->result();
    }
    
    public function get_vod_programs($id=null,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        if(!empty($id)){
            $this->db->where('parent_id',$id);
        }

        $this->db->where('type', 'VOD');
        $this->db->where('is_remove',0);
        //$this->db->where('serial_content',0);

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

        return $this->db->get('iptv_programs')->result();
    }

}