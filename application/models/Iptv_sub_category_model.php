<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/4/2016
 * Time: 3:47 PM
 */
class Iptv_sub_category_model extends MY_Model
{
    protected $table_name = "iptv_sub_categories";

    public function find_by_name($name,$type=null)
    {
        $this->db->where('sub_category_name',$name);
        if(!empty($type)){
            $this->db->where('type',$type);
        }
        return $this->db->get($this->table_name)->row();
    }

    public function find_by_category_id($category_id)
    {
        $this->db->where('category_id',$category_id);
        return $this->db->get($this->table_name)->result();
    }

    public function find_by_categoryId_with_subcategory($id,$name,$type=null,$parentId=null)
    {
        $this->db->where('sub_category_name',$name);
        $this->db->where('category_id',$id);
        if(!empty($type)){
            $this->db->where('type',$type);
        }
        if(!empty($parentId)){
            $this->db->where('parentId',$parentId);
        }
        return $this->db->get($this->table_name)->row();
    }

    public function remove($id)
    {
        $this->db->where('id',$id);
        return $this->db->delete($this->table_name);
    }
}