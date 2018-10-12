<?php

class Sub_sub_area_model extends MY_Model
{
	protected $table_name = 'sub_sub_areas';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_roads($id)
	{
		$this->db->select('roads.id,road_name,road_code');
		$this->db->from('roads');
		$this->db->join($this->table_name,$this->table_name.'.id=roads.sub_sub_area_id','left');
		$this->db->where('sub_sub_area_id',$id);
		$result_set = $this->db->get();
		return $result_set->result();
	}
	
}