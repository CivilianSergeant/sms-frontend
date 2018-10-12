<?php

class Distributor_model extends MY_Model
{

	protected $table_name="distributors";

	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_all_distributor($created_by,$limit=0,$offset=0,$filter=null,$sort=null)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('parent_id', $created_by);
		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}
		if(!empty($sort)){
			foreach($sort as $s){
				$this->db->order_by($s['field'],$s['dir']);
			}
		}
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $result = $query->result();
	}	

	public function distributor_by_id($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$query = $this->db->get($this->table_name);
		return $result = $query->row();
	}

	public function get_all_distributor_by_lco($parent_id)
	{
		$this->db->select('*');
		//$this->db->where('lco_id', $lco_id);
		$this->db->where('parent_id', $parent_id);
		$query = $this->db->get($this->table_name);
		return $result = $query->result();
	}

	public function get_count_distributor($created_by,$filter)
	{
		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}
		$this->db->where('parent_id', $created_by);
		return $this->db->count_all_results($this->table_name);
	}

}