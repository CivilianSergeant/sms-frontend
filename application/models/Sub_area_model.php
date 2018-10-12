<?php

class Sub_area_model extends MY_Model
{
	protected $table_name='sub_areas';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_sub_sub_areas($id)
	{
		$this->db->select('sub_sub_areas.id,sub_sub_area_name,sub_sub_area_code');
		$this->db->from('sub_sub_areas');
		$this->db->join($this->table_name,$this->table_name.'.id=sub_sub_areas.sub_area_id','left');
		$this->db->where('sub_area_id',$id);
		$result_set = $this->db->get();
		return $result_set->result();
	}

	public function get_roads($id,$json=false)
	{
		$this->db->select('roads.id,road_name,road_code');
		$this->db->from('roads');
		$this->db->join($this->table_name,$this->table_name.'.id=roads.sub_area_id','left');
		$this->db->where('sub_area_id',$id);
		$result_set = $this->db->get();
		if($json)
			return json_encode($result_set->result());
		return $result_set->result();
	}

	/**
	 * Find Sub Area By name
	 * @param $name
	 * @return mixed
	 */
	public function find_by_name($name)
	{
		$this->db->where('sub_area_name',$name);
		$q=$this->db->get($this->table_name);
		//$q=$this->db->query("SELECT GetSubAreaIdByName('{$name}') as id LIMIT 1");
		return $q->row();
	}
	
}