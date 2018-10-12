<?php

class Region_level_three_model extends MY_Model
{
	protected $table_name = 'region_level_3';

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_auto_id()
	{
		$sql_str = "SELECT GetAutoID('{$this->table_name}') as autoid";
		$query = $this->db->query($sql_str);
		return $query->row();
	}
}