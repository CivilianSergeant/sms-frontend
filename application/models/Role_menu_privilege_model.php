<?php

class Role_menu_privilege_model extends MY_Model
{

	protected $table_name = "plaas_role_menu_privileges";

	public function __construct()
	{
		parent::__construct();
	}

	public function is_permission_exist($role_id,$menu_id)
	{
		$this->db->where('role_id',$role_id);
		$this->db->where('menu_id',$menu_id);
		$result = $this->db->get($this->table_name);
		return $result->row();
	}

	public function has_permission($role_id)
	{
		$this->db->where('role_id',$role_id);
		return $this->db->count_all_results($this->table_name);
	}

	public function delete_by_role($role)
	{
		$this->db->where('role_id',$role->get_attribute('id'));
		$this->db->delete($this->table_name);
		return $this->db->affected_rows();
	}

}