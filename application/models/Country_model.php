<?php

class Country_model extends User_Model
{

	protected $table_name = "countries";

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_counties()
	{
		return $this->get_all();
	}

	/**
	* Get Divisons by country $id
	* @return array of objects
	*/
	public function get_divisions($id,$json=false)
	{
		$this->db->select('divisions.id,division_name,division_code');
		$this->db->from('divisions');
		$this->db->join($this->table_name,$this->table_name.'.id=divisions.country_id','left');
		$this->db->where('country_id',$id);
		$result_set = $this->db->get();
		if($json)
			return json_encode($result_set->result());
		return $result_set->result();
	}

	/**
	 * Find Country By name
	 * @param $name
	 * @return mixed
	 */
	public function find_by_name($name)
	{
		$this->db->where('country_name',$name);
		$q=$this->db->get($this->table_name);
		//$q=$this->db->query("SELECT GetCountryIdByName('{$name}') as id LIMIT 1");
		return $q->row();
	}


}