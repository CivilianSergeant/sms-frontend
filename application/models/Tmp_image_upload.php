<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tmp_image_upload
 *
 * @author Himel
 */
class Tmp_image_upload extends MY_Model{
    protected $table_name = "tmp_image_upload";
    
    public function getImage($content_id, $image_type_id)
    {
        $this->db->where('content_id',$content_id);
        $this->db->where('image_type_id',$image_type_id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }
}
