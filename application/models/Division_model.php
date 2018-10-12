<?php

class Division_model extends User_Model
{

	protected $table_name = "divisions";

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_divisions()
	{
		return $this->get_all();
	}

	/**
	* Get Districts by division $id
	* @return array of objects
	*/
	public function get_districts($id,$json=false)
	{
		$this->db->select('districts.id,district_name,district_code');
		$this->db->from('districts');
		$this->db->join($this->table_name,$this->table_name.'.id=districts.division_id','left');
		$this->db->where('division_id',$id);
		$result_set = $this->db->get();
		if($json)
			return json_encode($result_set->result());
		return $result_set->result();
	}

	/**
	 * Find Division By name
	 * @param $name
	 * @return mixed
	 */
	public function find_by_name($name)
	{
		$this->db->where('division_name',$name);
		$q=$this->db->get($this->table_name);
		//$q=$this->db->query("SELECT GetDivisionIdByName('{$name}') as id LIMIT 1");
		return $q->row();
	}

}