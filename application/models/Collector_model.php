<?php

class Collector_model extends MY_Model
{

	protected $table_name="billing_lco_collector";

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_all_collector($created_by,$limit=0,$offset=0)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('lco_id', $created_by);
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $result = $query->result();
	}	


	public function get_all_collectors_by_lco($lco_id=null)
	{
		$this->db->where('lco_id',$lco_id);
		$query = $this->db->get($this->table_name);
		return $query->result();
	}
	

	public function collector_by_id($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$query = $this->db->get($this->table_name);
		return $result = $query->row();
	}

	public function get_count_collector($created_by)
	{
		$this->db->where('lco_id', $created_by);
		return $this->db->count_all_results($this->table_name);
	}

}