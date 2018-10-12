<?php 
class User_package_assign_type_model extends User_Model
{

	protected $table_name = "user_package_assign_types";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_type_by_name($name)
	{
		$this->db->where('type',$name);
		$query = $this->db->get($this->table_name);
		return $query->row();
	}

}