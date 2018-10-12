<?php

class Billing_payment_method_model extends MY_Model
{
	
	protected $table_name = "billing_payment_methods";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_payment_method_by_name($name)
	{
		$this->db->where('method',$name);
		$query = $this->db->get($this->table_name);
		return $query->row();
	}

	public function get_payment_method()
	{
		$query = $this->db->get($this->table_name);
		return $query->result();
	}

	public function get_all_collector($id=null)
	{
		$this->db->select('*');
		if(!empty($id)){
			$this->db->where('parent_id',$id);
		}
		$query = $this->db->get('billing_lco_collector');
		return $query->result();
	}

	public function get_subscriber_pairing_id($subscriber_id)
	{
		$this->db->select('id, device_number as pairing_id');
		$this->db->where('subscriber_id',$subscriber_id);
		$query = $this->db->get('devices');
		return $query->result();
	}

	/*public function get_all_subscribers($created_by)
	{
		$this->db->select('*');
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		$this->db->where('users.created_by', $created_by);
		$query = $this->db->get();
		return $result = $query->result();
	}*/

	public function get_subscriber_token($user_id)
	{
		$this->db->select('token');
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$query = $this->db->get();
		return $result = $query->row();
	}

}