<?php

class User_model extends MY_Model
{

	protected $table_name="users";
	protected $id = 3;
	
	protected $filters = array('password');


	public function __construct()
	{
		parent::__construct();
	}

	public function creat_subscriber_login($data)
	{
		return $this->create($data);
	}

	public function get_all_users($created_by)
	{
		$this->db->select('*');
		$this->db->from('mso_profiles');
		$this->db->join('users', 'mso_profiles.id = users.profile_id', 'left');
		$this->db->where('users.created_by', $created_by);
		$query = $this->db->get();
		return $result = $query->result();
	}

	/**
	* Get List of Mso users with profile
	* @author Himel
	* @return Collection of objects
	*/
	public function get_all_mso_users($created_by)
	{
		$this->db->select('*');
		$this->db->from('mso_profiles');
		$this->db->join('users', 'mso_profiles.id = users.profile_id', 'left');
		$this->db->where('users.created_by', $created_by);
		$query = $this->db->get();
		return $result = $query->result();
	}

	/**
	* Get List of Lco users with profile
	* @author Himel
	* @return Collection of objects
	*/
	public function get_all_lco_users($created_by)
	{
		$this->db->select('*');
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');
		$this->db->where('users.created_by', $created_by);
		$query = $this->db->get();
		return $result = $query->result();
	}

	/**
	* Get List of Subscriber users with profile
	* @author Himel
	* @return Collection of objects
	*/
	public function get_all_subscribers()
	{
		$this->db->select('*');
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		//$this->db->where('users.created_by', $created_by);
		$query = $this->db->get();
		return $result = $query->result();
	}

	public function get_login_info_by_token($token)
	{
		return $this->find_by_token($token);
	}

	public function is_unique($username)
	{
		$this->db->select('id,username');
		$this->db->where('id != '.$this->get_attribute('id'));
		$this->db->where('username',$username);
		$query = $this->db->get($this->table_name);
	 	return $query->row();
	}

	public function is_user_loggedin_firstime($id)
	{
		$this->db->where('id',$id);
		$this->db->where('is_first_loggedin',0);
		return $this->db->count_all_results($this->table_name);
	}

	public function get_user_role($user_id)
	{

		$query = $this->db->query("SELECT roles.id,users.user_type, lower(role_name) as role_name,lower(role_type) as role_type FROM roles
		JOIN users ON users.role_id = roles.id
		WHERE users.id = {$user_id}");
		$result = $query->row();

		return (!empty($result))? $result : null;
	}

	public function get_lco_ids_by_mso($mso_id)
	{
		$select = "SELECT GROUP_CONCAT(id) as id FROM users where parent_id = {$mso_id} and user_type='LCO' and role_id =3";
		$query = $this->db->query($select);
		return $query->row();
	}

	public function get_active_subscribers()
	{
		$this->db->where('user_type','Subscriber');
		$this->db->where('user_status',1);
		$this->db->where('is_remote_access_enabled',0);
		$this->db->where('password IS NOT NULL');
		$this->db->where('otp IS NULL');
		$this->db->order_by('id','asc');
		$q = $this->db->get($this->table_name);
		return $q->result();
	}


}