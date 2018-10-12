<?php

class Plaas_module_model extends User_Model
{

	protected $table_name = "plaas_modules";

	public function __construct()
	{
		parent::__construct();
	}


	public function get_all_modules($limit=0,$offset=0)
	{
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$query = $this->db->get($this->table_name);
		return $query->result();
	}
	

	public function get_count_modules()
	{
		return $this->db->count_all($this->table_name);
	}

}