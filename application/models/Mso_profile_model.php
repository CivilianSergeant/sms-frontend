<?php

class Mso_profile_model extends MY_Model
{

	protected $table_name="mso_profiles";

	public function __construct()
	{
		parent::__construct();
	}
	

	public function get_profile_by_token($token)
	{
		$this->db->select('mso_profiles.*,users.role_id,users.profile_id,users.username,users.user_type,users.user_status,users.email,countries.country_name,divisions.division_name,districts.district_name,areas.area_name,sub_areas.sub_area_name,roads.road_name');
		$this->db->from('mso_profiles');
		$this->db->join('users', 'mso_profiles.id = users.profile_id', 'left');
		$this->db->join('countries','countries.id = mso_profiles.country_id','left');
		$this->db->join('divisions','divisions.id = mso_profiles.division_id','left');
		$this->db->join('districts','districts.id = mso_profiles.district_id','left');
		$this->db->join('areas','areas.id = mso_profiles.area_id','left');
		$this->db->join('sub_areas','sub_areas.id = mso_profiles.sub_area_id','left');
		$this->db->join('roads','roads.id = mso_profiles.road_id','left');
		$this->db->where('users.user_type','MSO');
		$this->db->where('mso_profiles.token',$token);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_all_mso_users($created_by,$limit=0,$offset=0,$filter=null,$sort=null)
	{
		$this->db->select('users.token,users.username,users.email,users.user_type,users.user_status,users.role_id,
			mso_profiles.id, 
			mso_profiles.mso_name
		');
		$this->db->from('mso_profiles');
		$this->db->join('users', 'mso_profiles.id = users.profile_id', 'left');

		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		$this->db->where('users.user_type','MSO');
		$this->db->where('mso_profiles.parent_id', $created_by);

		if(!empty($sort)){
			foreach($sort as $s){
				$this->db->order_by($s['field'],$s['dir']);
			}
		}
		
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		//$this->db->order_by('id','DESC');
		

		$query = $this->db->get();
		return $result = $query->result();
	}

	public function get_count_mso($created_by)
	{
		$this->db->from('mso_profiles');
		$this->db->join('users', 'mso_profiles.id = users.profile_id', 'left');
		$this->db->where('users.user_type','MSO');
		$this->db->where('mso_profiles.parent_id', $created_by);
		return $this->db->count_all_results();
	}
	
	public function delete_user_profile($id)
	{
		return $this->permanent_delete($id);
	}

	public function get_mso_name($user_id)
	{
		$query = $this->db->query("SELECT GetMSOName({$user_id}) as mso_name");
		$result = $query->row();
		return (!empty($result))? $result->mso_name : '';
	}
}