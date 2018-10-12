<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{

	protected $table_name;
	protected $id;
	protected $attributes = array();
	protected $filters = array();
	protected $child  = '';

	public function __construct()
	{
		parent::__construct();

		$this->user_session = null;
	}
	
	
	
	/**
	* @param $data
	* @return integer
	*/
	protected function create($data)
	{
		date_default_timezone_set('Asia/Dhaka');

		$this->user_session = $this->auth->is_loggedin();
		if(!empty($this->user_session)){
			$role = $this->user->get_user_role($this->user_session->id);
			$role_type = (!empty($role))?  strtolower($role->role_type) : '';
			if($role_type == "staff"){
				if(!isset($data['parent_id']))
                                    $data['parent_id'] = $this->user_session->created_by;
			}else{
                                if(!isset($data['parent_id']))
                                    $data['parent_id'] = $this->user_session->id;
			}
			$data['created_by'] = $this->user_session->id;
		}

		$data['created_at'] = date('Y-m-d H:i:s',time());
		$data['updated_at'] = (!empty($data['updated_at']))? $data['updated_at'] : null;
		$this->db->insert($this->table_name,$data);
		$insert_id = $this->db->insert_id();
		

		if ($insert_id) {

			return $insert_id;

		}

		return -1;

	}

	protected function get_parent_table()
	{
		return $this->table_name;
	}

	protected function update($data,$id)
	{
		date_default_timezone_set('Asia/Dhaka');
		$data['updated_at'] = date('Y-m-d H:i:s',time());
		$this->db->where('id',$id);
		$this->db->update($this->table_name,$data);
		return $this->db->affected_rows();
	}


	public function get_all()
	{
		$resultSet = $this->db->get($this->table_name);
		return $resultSet->result();
	}

	public function get_row()
	{
		$resultSet = $this->db->get($this->table_name);
		return $resultSet->row();
	}


	public function save($data,$id=null)
	{

		if ($id==null) {

			$insert_id = $this->create($data);
			return $insert_id;

		} else {
			return $this->update($data,$id);

		}
		
	}

	public function soft_delete($id)
	{
		$this->db->where($id);
		$this->db->update($this->table_name,array('deleted_at'=>date('Y-m-d H:i:s',time())));
	}

	public function permanent_delete($id=false)
	{
		$this->db->where($id);
		$this->db->delete($this->table_name);
	}

	public function find_by_id($id)
	{
		$this->db->where('id',$id);
		$result = $this->db->get($this->table_name);
		$row = $result->row();
		$result->free_result();
		if(!empty($row))
			$this->set_attributes($row);
		
		return $this;
	}

	public function find_by_token($token)
	{

		$this->db->where('token',$token);
		$result = $this->db->get($this->table_name);
		$row = $result->row();
		$result->free_result();
		if(!empty($row))
			$this->set_attributes($row);
		return $this;
	}

	protected function set_attributes($data)
	{
		$result_set = get_object_vars($data);
		$this->attributes = $result_set;
		//array_filter($result_set,array($this,"filter"),ARRAY_FILTER_USE_BOTH);
		

	}

	private function filter($v,$k)
	{
		if(in_array($k,$this->filters))
		{
			unset($this->attributes[$k]);
			
		}	
	}

	public function has_attributes()
	{
		return ($this->attributes) ? true : false;
	}

	public function get_attributes()
	{
		return $this->attributes;
		
	}

	public function get_attribute($name)
	{
		if(array_key_exists($name, $this->attributes))
			return $this->attributes[$name];
		return null;
	}

	public function count_all()
	{
		return $this->db->count_all($this->table_name);
	}


	public function get_id()
	{
		return $this->id;
	}

	public function to_json()
	{
		return json_encode($this->get_attributes());
	}



	


}