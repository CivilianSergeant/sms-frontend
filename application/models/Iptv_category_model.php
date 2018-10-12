<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/4/2016
 * Time: 3:47 PM
 */
class Iptv_category_model extends MY_Model
{
    protected $table_name = "iptv_categories";

    public function find_by_name($name,$type=null,$parentId=null){
        $this->db->where('category_name',$name);
        if(!empty($type)){
            $this->db->where('type',$type);
        }
        if(!empty($parentId)){
            $this->db->where('parent_id',$parentId);
        }
        return $this->db->get($this->table_name)->row();
    }

    public function get_free_categories()
    {
        $this->db->where('type','FREE');
        return $this->db->get($this->table_name)->result();
    }

    public function get_live_categories()
    {
        $this->db->where('type','LIVE');
        return $this->db->get($this->table_name)->result();
    }

    public function get_delay_categories()
    {
        $this->db->where('type','DELAY');
        return $this->db->get($this->table_name)->result();
    }

    public function get_live_delay_categories($parentId)
    {
        $this->db->where("type != 'VOD' && type != 'CATCHUP'");
        $this->db->where('parent_id',$parentId);
        return $this->db->get($this->table_name)->result();
    }

    public function get_catchup_categories($parentId)
    {
        $this->db->where('type','CATCHUP');
        $this->db->where('parent_id',$parentId);
        return $this->db->get($this->table_name)->result();
    }

    public function get_vod_categories($parentId)
    {
        $this->db->where('type','VOD');
        $this->db->where('parent_id',$parentId);
        return $this->db->get($this->table_name)->result();
    }
    
    public function get_program_categories($program_id)
    {
        $sql = "select category_name,GROUP_CONCAT(sub_category_name) as sub_categories from iptv_category_programs icp
JOIN iptv_categories ic ON ic.id = icp.category_id
JOIN iptv_sub_categories isc ON isc.id = icp.sub_category_id
WHERE icp.program_id = {$program_id} GROUP BY category_name";
        $q = $this->db->query($sql);
        return $q->result();
    }

    public function remove($id)
    {
        $this->db->where('id',$id);
        return $this->db->delete($this->table_name);
    }
}