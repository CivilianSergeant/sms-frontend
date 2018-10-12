<?php

class Road_model extends MY_Model
{
	protected $table_name='roads';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Find Road By name
	 * @param $name
	 * @return mixed
	 */
	public function find_by_name($name)
	{
		$this->db->where('road_name',$name);
		$q=$this->db->get($this->table_name);
		//$q=$this->db->query("SELECT GetRoadIdByName('{$name}') as id LIMIT 1");
		return $q->row();
	}
}