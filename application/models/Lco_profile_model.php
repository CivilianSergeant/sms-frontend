<?php
class Lco_profile_model extends MY_Model
{

	protected $table_name='lco_profiles';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_profile_by_token($token)
	{
		$this->db->select('lco_profiles.*,HasLCOBusinessRegion(lco_profiles.region_l1_code,lco_profiles.region_l2_code,lco_profiles.region_l3_code,lco_profiles.region_l4_code) as business_region_assigned,users.username,
		users.profile_id,users.role_id,users.lsp_type_id,users.user_type,users.user_status,users.is_remote_access_enabled,users.email,countries.country_name,divisions.division_name,districts.district_name,areas.area_name,sub_areas.sub_area_name,roads.road_name');
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');
		$this->db->join('countries','countries.id = lco_profiles.country_id','left');
		$this->db->join('divisions','divisions.id = lco_profiles.division_id','left');
		$this->db->join('districts','districts.id = lco_profiles.district_id','left');
		$this->db->join('areas','areas.id = lco_profiles.area_id','left');
		$this->db->join('sub_areas','sub_areas.id = lco_profiles.sub_area_id','left');
		$this->db->join('roads','roads.id = lco_profiles.road_id','left');
                
		$this->db->where('users.user_type','LCO');
		$this->db->where('lco_profiles.token',$token);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_region_code_by_token($token)
	{
		$this->db->select('lco_profiles.lco_name,lco_profiles.country_id,lco_profiles.division_id,lco_profiles.district_id,lco_profiles.area_id,lco_profiles.sub_area_id,lco_profiles.road_id,
			lco_profiles.region_l1_code,lco_profiles.region_l2_code,lco_profiles.region_l3_code,lco_profiles.region_l4_code,lco_profiles.message_sign,users.username,users.profile_id,users.user_type,users.user_status,users.email,countries.country_name,
			divisions.division_name,districts.district_name,areas.area_name,sub_areas.sub_area_name,roads.road_name,HasLCOBusinessRegion(lco_profiles.region_l1_code,lco_profiles.region_l2_code,lco_profiles.region_l3_code,lco_profiles.region_l4_code) as business_region_assigned');
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');
		$this->db->join('countries','countries.id = lco_profiles.country_id','left');
		$this->db->join('divisions','divisions.id = lco_profiles.division_id','left');
		$this->db->join('districts','districts.id = lco_profiles.district_id','left');
		$this->db->join('areas','areas.id = lco_profiles.area_id','left');
		$this->db->join('sub_areas','sub_areas.id = lco_profiles.sub_area_id','left');
		$this->db->join('roads','roads.id = lco_profiles.road_id','left');
		$this->db->where('users.user_type','LCO');
		$this->db->where('lco_profiles.token',$token);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_region_code_by_id($id)
	{
		$this->db->select('lco_profiles.lco_name,lco_profiles.country_id,lco_profiles.division_id,lco_profiles.district_id,lco_profiles.area_id,lco_profiles.sub_area_id,lco_profiles.road_id,
			lco_profiles.region_l1_code,lco_profiles.region_l2_code,lco_profiles.region_l3_code,lco_profiles.region_l4_code,lco_profiles.message_sign,users.username,users.profile_id,users.user_type,users.user_status,users.email,countries.country_name,
			divisions.division_name,districts.district_name,areas.area_name,sub_areas.sub_area_name,roads.road_name,HasLCOBusinessRegion(lco_profiles.region_l1_code,lco_profiles.region_l2_code,lco_profiles.region_l3_code,lco_profiles.region_l4_code) as business_region_assigned');
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');
		$this->db->join('countries','countries.id = lco_profiles.country_id','left');
		$this->db->join('divisions','divisions.id = lco_profiles.division_id','left');
		$this->db->join('districts','districts.id = lco_profiles.district_id','left');
		$this->db->join('areas','areas.id = lco_profiles.area_id','left');
		$this->db->join('sub_areas','sub_areas.id = lco_profiles.sub_area_id','left');
		$this->db->join('roads','roads.id = lco_profiles.road_id','left');
		$this->db->where('users.user_type','LCO');
		$this->db->where('users.id',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_all_lco_users($created_by,$limit=0,$offset=0,$filter=null,$sort=null)
	{
		$this->db->select('users.id as user_id,users.username,users.email,users.user_type,users.user_status,users.role_id,users.lsp_type_id as lsp,
			lco_profiles.id, 
			lco_profiles.lco_name, 
			lco_profiles.address1, 
			lco_profiles.address2, 
			lco_profiles.country_id, 
			lco_profiles.division_id, 
			lco_profiles.district_id, 
			lco_profiles.area_id, 
			lco_profiles.sub_area_id, 
			lco_profiles.road_id, 
			lco_profiles.identity_type, 
			lco_profiles.identity_number, 
			lco_profiles.contact, 
			lco_profiles.billing_contact, 
			lco_profiles.business_modality, 
			lco_profiles.token, 
			lco_profiles.region_l1_code,
			lco_profiles.region_l2_code,
			lco_profiles.region_l3_code,
			lco_profiles.region_l4_code,
			HasLCOBusinessRegion(lco_profiles.region_l1_code,lco_profiles.region_l2_code,lco_profiles.region_l3_code,lco_profiles.region_l4_code) as business_region_assigned,
			lco_profiles.message_sign,
			lco_profiles.created_by,
			lco_profiles.parent_id,
			lco_profiles.created_at, 
			lco_profiles.updated_at
		');
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');

		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		$this->db->where('users.user_type','LCO');
		$this->db->where('lco_profiles.parent_id', $created_by);

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

	public function get_lco_users($created_by,$limit=0,$offset=0)
	{
		$this->db->select('users.id as user_id,
			lco_profiles.id, 
			lco_profiles.lco_name, 
			lco_profiles.token, 
			lco_profiles.created_by,
			lco_profiles.parent_id,
			lco_profiles.created_at, 
			lco_profiles.updated_at
		');
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');
		$this->db->where('users.user_type','LCO');
		$this->db->where('lco_profiles.parent_id', $created_by);
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $result = $query->result();
	}

	public function get_count_lco_user($created_by,$filter=null)
	{
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');

		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		$this->db->where('users.user_type','LCO');
		$this->db->where('lco_profiles.parent_id', $created_by);
		return $this->db->count_all_results();
	}



	public function update_modality($data, $token)
	{
		$this->db->where('token',$token);
		$this->db->update($this->table_name,$data);
		return $this->db->affected_rows();
	}

	public function get_lco_name($user_id)
	{
		$query = $this->db->query("SELECT GetLCOName({$user_id}) as lco_name");
		$result = $query->row();
		return (!empty($result))? $result->lco_name : '';
	}

	public function get_message_sign($user_id){
		$query = $this->db->query("SELECT GetMessageSign({$user_id}) as message_sign");
		$result = $query->row();
		return (!empty($result))? $result->message_sign : '';
	}

	/**
	 * Get LCO by email
	 * @param $email
	 * @return mixed
	 */
	public function get_lco_by_email($email)
	{
		$this->db->select('users.id,users.profile_id,username,email,lco_name');
		$this->db->from('users');
		//$this->db->join($this->table_name,$this->table_name.'.id = users.profile_id');
		$this->db->where('email',$email);
		//$this->db->where('user_type','LCO');
		$q=$this->db->get();
		return $q->row();
	}

	/**
	 * Get LCO by user name
	 * @param $username
	 * @return mixed
	 */
	public function get_lco_by_username($username)
	{
		$this->db->select('users.id,users.profile_id,username,email,lsp_type_id');
		$this->db->from('users');
		//$this->db->join($this->table_name,$this->table_name.'.id = users.profile_id');
		$this->db->where('username',$username);
		//$this->db->where('user_type','LCO');
		$q = $this->db->get();
		return $q->row();
	}

	public function get_mso_lco_users($created_by,$limit=0,$offset=0)
	{
		$this->db->select('users.id as user_id,
			lco_profiles.id,
			lco_profiles.lco_name,
			lco_profiles.token,
			lco_profiles.created_by,
			lco_profiles.parent_id,
			lco_profiles.created_at,
			lco_profiles.updated_at
		');
		$this->db->from('lco_profiles');
		$this->db->join('users', 'lco_profiles.id = users.profile_id', 'left');
		$this->db->where('users.user_type','LCO');

		$list = $this->getAssignedLCOID();
		if($list != null){
			$this->db->where('users.id NOT IN ('.trim($list).')');
		}

		$this->db->where('lco_profiles.parent_id', $created_by);
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $result = $query->result();
	}

	private function getAssignedLCOID()
	{
		$q = $this->db->query("select GROUP_CONCAT(lco_id)  as p from lco_groups");
		$result = $q->row();
		return (!empty($result))? $result->p : null;
	}

	
}