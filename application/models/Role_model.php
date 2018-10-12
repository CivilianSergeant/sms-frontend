<?php

class Role_model extends MY_Model
{

	protected $table_name = "roles";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_user_type($user_type)
	{
		$this->db->distinct();
		$this->db->select('user_type');
		$this->db->from('roles');
		if($user_type == 'MSO')
		{
			$this->db->where('role_name', 'Staff');
			$this->db->or_where('user_type', 'LCO');			
		}
		if($user_type == 'LCO')
		{
			$this->db->where('role_name !=', 'Admin');
			$this->db->where('user_type !=', 'MSO');			
		}
		$query = $this->db->get();		
		$result = $query->result();
		
		return $result;	
	}

	public function get_count_roles($id,$filter=null,$type = null)
	{
		if(!empty($type)){
			$this->db->where('role_type',$type);
		}

		$this->db->where('parent_id',$id);

		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		return $this->db->count_all_results($this->table_name);

	}

	public function get_role_for_permission($role_type,$user_type)
	{


		if($role_type == "admin" && strtoupper($user_type) == "MSO"){
			$this->db->where('user_type','MSO');
			$this->db->where('role_type','staff');
			$query = $this->db->get($this->table_name);

		}else if($role_type == "staff" && strtoupper($user_type) == "MSO"){
			$this->db->where("user_type","MSO");
			$this->db->where("role_type","staff");
			//$this->db->or_where('user_type','Subscriber');
			$query = $this->db->get($this->table_name);

		}else if($role_type == "admin" && strtoupper($user_type) == "LCO"){

			$this->db->where('user_type','LCO');
			$this->db->where('role_type','staff');
			//$this->db->or_where('user_type','Subscriber');
			$query = $this->db->get($this->table_name);

		}else if($role_type == "staff" && strtoupper($user_type) == "LCO"){
			$this->db->where('user_type','LCO');
			$this->db->where('role_type','staff');
			//$this->db->or_where('user_type','Subscriber');
			$query = $this->db->get($this->table_name);
		}else if($role_type == "admin" && strtoupper($user_type) == "GROUP"){
			$this->db->where('user_type','Group');
			$this->db->where('role_type','staff');
			$query = $this->db->get($this->table_name);
		}else if($role_type == "staff" && strtoupper($user_type) == "GROUP"){
			$this->db->where('user_type','Group');
			$this->db->where('role_type','staff');
			$query = $this->db->get($this->table_name);
		}

		return $query->result();
	}
	
	public function get_roles($id,$limit=0,$offset=0,$filter=null,$sort=null,$type=null)
	{
		$this->db->select("id,user_type,role_type,role_name,parent_id,created_by,updated_by,status,created_at,updated_at,IsRoleAssigned(id) as is_assigned");

		if(!empty($type)){
			$this->db->where('role_type',$type);
		}

		$this->db->where('parent_id',$id);

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


		$query = $this->db->get($this->table_name);
		return $query->result();


		/*$this->db->select('*');
		$this->db->from('roles');
		if($user_type == 'MSO')
		{
			$query = $this->db->query("Call Get_User_MSO_Access_Role()");
			$result = $query->result();
			$query->free_result();
			//test($result);die();
			return $result;
		}
		if($user_type == 'LCO')
		{
			$query = $this->db->query("Call Get_User_LCO_Access_Role()");
			$result = $query->result();
			$query->free_result();
			//test($result);die();
			return $result;
		}*/
		
	}

	public function find_role_by_name($name)
	{
		$this->db->where('role_name',$name);
		$this->db->limit(1);
		$q = $this->db->get($this->table_name);
		return $q->row();
	}

	public function is_assigned($id)
	{
		$select = "Select IsRoleAssigned({$id}) as assigned";
		$q = $this->db->query($select);
		return $q->row();
	}

	public function delete($role)
	{
		$this->db->where('id',$role->get_attribute('id'));
		$this->db->delete($this->table_name);
		return true;
	}

}