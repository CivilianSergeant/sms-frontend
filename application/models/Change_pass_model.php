<?php

class Change_pass_model extends MY_Model
{

	protected $table_name="users";

	public function __construct()
	{
		parent::__construct();
	}
	public function check_pass($id)
	{
		$this->db->select('password','id');
		$this->db->where('password');
		$this->db->or_where('id',$id);
		$this->db->from($this->table_name);
		$query= $this->db->get();
		return $query->row();
	}

	
}