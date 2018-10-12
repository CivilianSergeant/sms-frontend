<?php
/**
 * Description of Fcm_token
 *
 * @author Himel
 */
class Fcm_token_model extends MY_Model
{
    
    protected $table_name="fcm_tokens";
    
    
    public function get_devices_by_group($groupId)
    {
        $this->db->select('fcm_tokens.id,fcm_tokens.fcm_token,fcm_tokens.device_group_id,subscriber_profiles.subscriber_name');
        $this->db->from($this->table_name);
        $this->db->join('users','users.id = '.$this->table_name.'.user_id');
        $this->db->join('subscriber_profiles','subscriber_profiles.id = users.profile_id');
        $this->db->where('device_group_id',$groupId);
        $this->db->where('logout_flag',0);
        $q = $this->db->get();
        return $q->result();
    }
    
    public function find_by_fcm_token($groupId,$token)
    {
        
        $this->db->where('fcm_token',$token);
        $this->db->where('device_group_id',$groupId);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }
    
}
