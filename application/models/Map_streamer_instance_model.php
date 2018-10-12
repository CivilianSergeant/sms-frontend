<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/13/2016
 * Time: 5:53 PM
 */
class Map_streamer_instance_model extends MY_Model
{
    protected $table_name = "map_streamer_instances";

    public function hasMapping($instance_id,$program_id,$hls_url_mobile=null,$hls_url_stb=null)
    {
        $this->db->where('streamer_instance_id',$instance_id);
        $this->db->where('program_id',$program_id);
        $this->db->where('hls_url_mobile',$hls_url_mobile);
        $this->db->where('hls_url_stb',$hls_url_stb);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    public function get_all_by_program($p_id){
        $this->db->select('id,GetIptvProgramName(program_id) as program_name,hls_url_stb,hls_url_mobile,hls_url_web');
        $this->db->where('program_id',$p_id);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    public function get_all_by_instance($instance_id){
        $this->db->select('id,GetIptvProgramName(program_id) as program_name,hls_url_stb,hls_url_mobile,hls_url_web');
        $this->db->where('streamer_instance_id',$instance_id);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    public function delete_all_by_instance_program($instance_id,$program_id)
    {
        $this->db->where('streamer_instance_id',$instance_id);
        $this->db->where('program_id',$program_id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }

    public function remove_by_id($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }
}