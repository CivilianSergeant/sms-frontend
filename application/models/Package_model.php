<?php

class Package_model extends MY_Model
{
	protected $table_name = "packages";
	protected $child      = "programs";

	public function __construct()
	{
		parent::__construct();

		$this->load->model('package_program_model','package_program');
	}

	public function get_next_id()
	{
		$query = $this->db->query("SELECT GetAutoID('".$this->table_name."') as next_id");
		$result = $query->row();
		return (!empty($result))? $result->next_id : 1; 
	}


	public function get_packages($add_on=false)
	{
		$this->db->select('packages.id,packages.package_name,packages.duration,packages.price,packages.package_name,count(package_programs.program_id) as no_of_program ');
		$this->db->from($this->table_name);
		$this->db->join('package_programs','packages.id = package_programs.package_id','left');
		$this->db->where('packages.is_active',1);
		$this->db->where('packages.is_deleted',0);
		if($add_on){
			$this->db->where('is_add_on',1);
		}else{
			$this->db->where('is_add_on',0);
		}
		$this->db->group_by('packages.id');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_packages($limit=0,$offset=0,$filter=null,$sort=null)
	{	$sql = 'packages.id,packages.token,packages.package_name,packages.duration,
		packages.price,packages.is_active,packages.package_name,
		GetPackageProgramsCount(packages.id) as programs,count(user_packages.package_id) as assigned';
		$this->db->select($sql);
		$this->db->from($this->table_name);
		$this->db->join('package_programs','packages.id = package_programs.package_id','left');
		$this->db->join('user_packages','packages.id = user_packages.package_id','left');
		$this->db->where('packages.is_deleted',0);
		$this->db->where('packages.is_add_on',0);
		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		$this->db->group_by('packages.id');

		if(!empty($sort)){
			foreach($sort as $s){
				$this->db->order_by($s['field'],$s['dir']);
			}
		}

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}

		

		$query = $this->db->get();

		return $query->result();
	}


	public function get_all_add_on_packages($limit=0,$offset=0,$filter=null,$sort=null)
	{	$sql = 'packages.id,packages.token,packages.package_name,packages.duration,
		packages.price,packages.is_active,packages.package_name,
		count(package_programs.program_id) as programs,count(user_addon_packages.package_id) as assigned';
		$this->db->select($sql);
		$this->db->from($this->table_name);
		$this->db->join('package_programs','packages.id = package_programs.package_id','left');
		$this->db->join('user_addon_packages','packages.id = user_addon_packages.package_id','left');
		$this->db->where('packages.is_deleted',0);
		$this->db->where('packages.is_add_on',1);
		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		$this->db->group_by('packages.id');

		if(!empty($sort)){
			foreach($sort as $s){
				$this->db->order_by($s['field'],$s['dir']);
			}
		}

		if(!empty($limit)){
			$this->db->limit($limit,$offset);
		}



		$query = $this->db->get();

		return $query->result();
	}
	

	/**
	* Assign Program to Package
	* @param $program
	* @param $package_id
	* @param created_by
	* @return boolean 
	*/
	public function assign_program($programs,$package_id,$created_by=null)
	{

		if (!empty($programs)) {
			foreach ($programs as $program) {
				$save_data = array(
					'program_id' => $program,//$program['id'],
					'package_id' => $package_id,
					'is_active'  => 1
					);
				$this->package_program->save($save_data);
			}
			return true;
		} else {
			return false;
		}

	}

	/**
	* Remove programs by Package
	* @return boolean
	*/
	public function remove_programs()
	{
		if ($this->attributes['id']) {
			$this->db->where('package_id',$this->attributes['id']);
			$this->db->delete('package_programs');
			return true;
		}

		return false;

	}

	/**
	* Get programs of a package
	* @return array
	*/
	public function get_programs($id=null,$count=false,$sort_order=null)
	{
		$this->db->from('package_programs');
		$this->db->join('programs','package_programs.program_id=programs.id','left');
		
		if ($id==null) {
			$this->db->where('package_id',$this->get_attribute('id'));
		} else {
			$this->db->where('package_id',$id);
		}

		if ($sort_order) {
			$this->db->order_by($sort_order);
		}
		$result_set = $this->db->get();
		$result = $result_set->result();
		

		$programs = array();
		if (!empty($result)) {
			foreach ($result as $i=>$r) {
				$programs[$r->id] = $r;
			}
		}

		if ($count) {
			return count($programs);
		}

		return $programs;
	}

	public function get_last_next_id()
	{
		$this->db->select('id');
		$this->db->from($this->table_name);
		$this->db->where('id < 65534');
		$this->db->order_by('id','desc');
		$result_set = $this->db->get();
		$result = $result_set->row();
		if (!empty($result)) {
			return ($result->id + 1);
		} else {
			return 1;
		}
		
	}

	public function find_by_name($package_name)
	{
		$this->db->where('package_name',$package_name);
		$query = $this->db->get($this->table_name);
		return $query->row();
	}

	public function package_delete($id)
	{
		$this->db->where('id', $id);
		$this->db->update($this->table_name,array('is_deleted'=>1));
	}
	
}