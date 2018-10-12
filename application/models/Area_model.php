<?php

class Area_model extends User_Model
{

	protected $table_name = "areas";

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_areas()
	{
		return $this->get_all();
	}

	/**
	* @return array of objects
	*/
	public function get_sub_areas($id,$json=false)
	{
		$this->db->select('sub_areas.id,sub_area_name,sub_area_code');
		$this->db->from('sub_areas');
		$this->db->join($this->table_name,$this->table_name.'.id=sub_areas.area_id','left');
		$this->db->where('area_id',$id);
		$this->db->order_by('sub_areas.sub_area_name','asc');
		$result_set = $this->db->get();
		if($json)
			return json_encode($result_set->result());
		return $result_set->result();
	}

	/**
	 * Find Area By name
	 * @param $name
	 * @return mixed
	 */
	public function find_by_name($name)
	{
		$this->db->where('area_name',$name);
		$q=$this->db->get($this->table_name);
		//$q=$this->db->query("SELECT GetAreaIdByName('{$name}') as id LIMIT 1");
		return $q->row();
	}
	

}