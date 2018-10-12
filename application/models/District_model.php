<?php

class District_model extends User_Model
{

	protected $table_name = "districts";

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_districts()
	{
		return $this->get_all();
	}

	/**
	* Get Areas by district $id
	* @return array of objects
	*/
	public function get_areas($id,$json=false)
	{
		$this->db->select('areas.id,area_name,area_code');
		$this->db->from('areas');
		$this->db->join($this->table_name,$this->table_name.'.id=areas.district_id','left');
		$this->db->where('district_id',$id);
		$this->db->order_by('area_name','asc');
		$result_set = $this->db->get();
		if($json)
			return json_encode($result_set->result());
		return $result_set->result();
	}

	/**
	 * Find District By name
	 * @param $name
	 * @return mixed
	 */
	public function find_by_name($name)
	{
		$this->db->where('district_name',$name);
		$q=$this->db->get($this->table_name);
		//$q=$this->db->query("SELECT GetDistrictIdByName('{$name}') as id LIMIT 1");
		return $q->row();
	}
}