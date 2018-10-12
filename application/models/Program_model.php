<?php

class Program_model extends MY_Model
{

	protected $table_name="programs";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_next_id()
	{
		$query = $this->db->query("SELECT GetAutoID('".$this->table_name."') as next_id");
		$result = $query->row();
		return (!empty($result))? $result->next_id : 1; 
	}

	public function get_last_next_id()
	{
		$this->db->select('id');
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$result_set = $this->db->get();
		$result = $result_set->row();
		if (!empty($result)) {
			return ($result->id + 1);
		} else {
			return 1;
		}
		
	}
	
	public function insertprogram($data)
	{
		$this->save($data);

	}

	public function findByColName($colname){
		$query = $this->db->get_where('programs', $colname);
		return $query->result_array();
	}

	public function showprogram($type = "OBJECT",$args)
	{
		$this->db->select('programs.id,programs.program_name,programs.lcn,programs.program_service_id,count(package_programs.id) as pp_id');
		$this->db->from('programs');
		$this->db->join('package_programs','programs.id = package_programs.program_id','left');

		if(!empty($args[2])){
			foreach($args[2]['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}



		$this->db->group_by("id");
		if(!empty($args[3])){
			foreach($args[3] as $s){
				$this->db->order_by($s['field'],$s['dir']);
			}
		}else{
			$this->db->order_by("id", "desc");
		}

		$this->db->limit($args[0], $args[1]);
		$query= $this->db->get();
		//echo $this->db->last_query();
		switch ($type) {
			case 'ARRAY':
				return $query->result_array();
				break;
			case 'JSON':
				$tempArray = array();
				$tempArray['data'] = $query->result_array();
				$tempArray['total'] = $this->db->count_all('programs');
				return json_encode($tempArray);
				break;
			default:
				return $query->result();
				break;
		}
	}

	public function export_ac_table(){

		$this->db->select('id,program_name')->from('programs');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function count_unassigned_program()
	{
		$query = $this->db->query("SELECT GetTotalUnAssignedPrograms() as total");
		$count = $query->row();
		return (!empty($count))? $count->total : 0;
	}

	public function updateprogram($data,$id)
	{
		$this->update($data,$id);
	}

	public function checkassign_program($id)
	{
	  $this->db->select('program_id,package_id');
	  $this->db->from('package_programs');
	  $this->db->where('program_id',$id);
	  $query = $this->db->get();
	  return $query->result();
	}

	public function program_delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table_name); 
	}

	public function dump_to_xlsx($created_by,$select)
	{
		$this->db->select($select);
		$this->db->where('parent_id',$created_by);
		$query=$this->db->get($this->table_name);
		return $query->result();
	}
}