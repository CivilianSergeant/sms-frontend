<?php
/**
 * Description of Fcm_device_group_model
 *
 * @author Himel
 */
class Fcm_device_group_model extends MY_Model
{
    
    protected $table_name = "fcm_device_groups";
    
    public function get_fcm_device_groups()
    {
        $q = $this->db->get($this->table_name);
        return $q->result();
    }
}
