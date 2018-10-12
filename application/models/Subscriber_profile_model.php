<?php

class Subscriber_profile_model extends MY_Model
{
	protected $table_name='subscriber_profiles';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_profile_by_token($token)
	{
		$select = 'subscriber_profiles.id, subscriber_profiles.subscriber_name,subscriber_profiles.foc_control_room,subscriber_profiles.foc_others,
		subscriber_profiles.reference_type,subscriber_profiles.reference_id,subscriber_profiles.remarks,
		subscriber_profiles.address1,subscriber_profiles.address2,subscriber_profiles.country_id,
		subscriber_profiles.division_id,subscriber_profiles.district_id,subscriber_profiles.area_id,
		subscriber_profiles.sub_area_id,subscriber_profiles.road_id,subscriber_profiles.contact,
		subscriber_profiles.is_same_as_contact,subscriber_profiles.billing_contact,
		subscriber_profiles.photo,subscriber_profiles.identity_type,subscriber_profiles.identity_number,
		subscriber_profiles.identity_attachment,subscriber_profiles.subscription_copy,
		subscriber_profiles.is_hide,subscriber_profiles.created_by,subscriber_profiles.token,
		users.username,users.profile_id,users.user_type,users.user_status,users.email,users.is_remote_access_enabled,
		countries.country_name,divisions.division_name,districts.district_name,areas.area_name,sub_areas.sub_area_name,roads.road_name';
		
		$this->db->select($select);
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		$this->db->join('countries','countries.id = subscriber_profiles.country_id','left');
		$this->db->join('divisions','divisions.id = subscriber_profiles.division_id','left');
		$this->db->join('districts','districts.id = subscriber_profiles.district_id','left');
		$this->db->join('areas','areas.id = subscriber_profiles.area_id','left');
		$this->db->join('sub_areas','sub_areas.id = subscriber_profiles.sub_area_id','left');
		$this->db->join('roads','roads.id = subscriber_profiles.road_id','left');
		$this->db->where('users.user_type','Subscriber');
		$this->db->where('subscriber_profiles.token',$token);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_all_subscribers($created_by=null,$limit=0,$offset=0,$filter=null,$sort=null)
	{
		/* subscriber_profiles.address1,
		subscriber_profiles.address2,
		subscriber_profiles.country_id,
		subscriber_profiles.division_id,
		subscriber_profiles.district_id,
		subscriber_profiles.area_id,
		subscriber_profiles.sub_area_id,
		subscriber_profiles.road_id,
		subscriber_profiles.contact,
		subscriber_profiles.billing_contact,
		subscriber_profiles.identity_type,
		subscriber_profiles.identity_number,
		subscriber_profiles.is_hide,
		subscriber_profiles.created_by,
		subscriber_profiles.created_at,
			subscriber_profiles.updated_at, */
		$this->db->select('users.id as user_id,users.username,users.email,users.user_type,users.user_status,users.role_id,
			subscriber_profiles.id, 
			subscriber_profiles.subscriber_name,
			subscriber_profiles.contact,
			subscriber_profiles.token,			
			GetTotalSTB(users.id) as total_stb,
			GetSubscriberPayable(users.id) as total_payable,
			GetSubscriberBal(users.id) as balance,
			GetSubscriberPackagesCount(users.id) as total_packages,
			GetExpiredSubscriptionCount(users.id) as subscription,
			if(is_foc,CONCAT(GetMSOName(users.parent_id)," [MSO]"),CONCAT(GetLCOName(users.parent_id)," [LCO]")) as parentName'

		);
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		//$this->db->join('subscriber_stb_smartcards','users.id = subscriber_stb_smartcards.subscriber_id','left');

		if(!empty($filter) && !empty($filter['filters'])){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		if(!empty($filter) && !empty($filter['search'])){
			if($filter['search'] == 'active'){
				$this->db->where('GetExpiredSubscriptionCount(users.id) IS NULL');
			}
			if($filter['search'] == 'expired'){
				$this->db->where('GetExpiredSubscriptionCount(users.id) IS NOT NULL');
			}
			if($filter['search'] == 'zero-balance'){
				$this->db->where('GetSubscriberBal(users.id) = 0 OR GetSubscriberBal(users.id) IS NULL');
			}
			if($filter['search'] == 'no-package'){
				$this->db->where('GetSubscriberPackagesCount(users.id) = 0 OR GetSubscriberPackagesCount(users.id) IS NULL');
			}

		}

		if(!empty($created_by)){
			$this->db->where('users.parent_id', $created_by);
		}

		$this->db->where('users.user_type','Subscriber');
		$this->db->group_by('users.id');

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


	public function get_all_members($created_by,$limit=0,$offset=0,$filter=null,$sort=null)
	{
		$this->db->select('users.id as user_id,users.username,users.email,users.user_type,users.user_status,users.role_id,
			subscriber_profiles.id,
			subscriber_profiles.subscriber_name,
			subscriber_profiles.address1,
			subscriber_profiles.address2,
			subscriber_profiles.country_id,
			subscriber_profiles.division_id,
			subscriber_profiles.district_id,
			subscriber_profiles.area_id,
			subscriber_profiles.sub_area_id,
			subscriber_profiles.road_id,
			subscriber_profiles.contact,
			subscriber_profiles.billing_contact,
			subscriber_profiles.identity_type,
			subscriber_profiles.identity_number,
			subscriber_profiles.is_hide,
			subscriber_profiles.created_by,
			subscriber_profiles.token,
			subscriber_profiles.created_at,
			subscriber_profiles.updated_at,
			GetTotalSTB(users.id) as total_stb,
			GetSubscriberPayable(users.id) as total_payable,
			GetSubscriberBal(users.id) as balance,
			GetSubscriberPackagesCount(users.id) as total_packages,
			GetExpiredSubscriptionCount(users.id) as subscription'

		);
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		$this->db->join('subscriber_stb_smartcards','users.id = subscriber_stb_smartcards.subscriber_id','left');

		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		$this->db->where('subscriber_profiles.parent_id', $created_by);
		$this->db->where('users.user_type','Subscriber');
		$this->db->where('users.is_iptv',1);
		$this->db->group_by('users.id');

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

	public function get_all_addon_subscribers($created_by,$limit=0,$offset=0,$filter=null,$sort=null)
	{
		$this->db->select('users.id as user_id,users.username,users.email,users.user_type,users.user_status,users.role_id,
			subscriber_profiles.id,
			subscriber_profiles.subscriber_name,
			subscriber_profiles.address1,
			subscriber_profiles.address2,
			subscriber_profiles.country_id,
			subscriber_profiles.division_id,
			subscriber_profiles.district_id,
			subscriber_profiles.area_id,
			subscriber_profiles.sub_area_id,
			subscriber_profiles.road_id,
			subscriber_profiles.contact,
			subscriber_profiles.billing_contact,
			subscriber_profiles.identity_type,
			subscriber_profiles.identity_number,
			subscriber_profiles.region_l1_code,
			subscriber_profiles.region_l2_code,
			subscriber_profiles.region_l3_code,
			subscriber_profiles.region_l4_code,
			subscriber_profiles.is_hide,
			subscriber_profiles.created_by,
			subscriber_profiles.token,
			subscriber_profiles.created_at,
			subscriber_profiles.updated_at,
			GetTotalSTB(users.id) as total_stb,
			user_addon_packages.package_start_date,
			user_addon_packages.package_expire_date,
			GetSubscriberAddonPackageCount(users.id) as total_packages,
			GetExpiredAddOnSubscriptionCount(users.id) as subscription'

		);
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		$this->db->join('subscriber_stb_smartcards','users.id = subscriber_stb_smartcards.subscriber_id','left');
		$this->db->join('user_addon_packages','user_addon_packages.user_id=users.id','left');
		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		$this->db->where('subscriber_profiles.parent_id', $created_by);
		$this->db->where('users.user_type','Subscriber');
		$this->db->group_by('users.id');

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

	public function get_count_subscribers($created_by=null,$filter=null)
	{
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		//$this->db->join('subscriber_stb_smartcards','users.id = subscriber_stb_smartcards.subscriber_id','left');

		if(!empty($filter) && !empty($filter['filters'])){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		if(!empty($filter) && !empty($filter['search'])){
			if($filter['search'] == 'active'){
				$this->db->where('GetExpiredSubscriptionCount(users.id) IS NULL');
			}
			if($filter['search'] == 'expired'){
				$this->db->where('GetExpiredSubscriptionCount(users.id) IS NOT NULL');
			}
			if($filter['search'] == 'zero-balance'){
				$this->db->where('GetSubscriberBal(users.id) = 0 OR GetSubscriberBal(users.id) IS NULL');
			}
			if($filter['search'] == 'no-package'){
				$this->db->where('GetSubscriberPackagesCount(users.id) = 0 OR GetSubscriberPackagesCount(users.id) IS NULL');
			}
                        

		}

		if(!empty($created_by)){
			$this->db->where('subscriber_profiles.parent_id', $created_by);
		}

		$this->db->where('users.user_type','Subscriber');
		return $this->db->count_all_results();
	}

	public function get_count_iptv_subscribers($created_by)
	{
		$this->db->from('subscriber_profiles');
		$this->db->join('users', 'subscriber_profiles.id = users.profile_id', 'left');
		$this->db->where('subscriber_profiles.parent_id', $created_by);
		$this->db->where('users.is_iptv',1);
		return $this->db->count_all_results();
	}

	public function get_subscriber_name($user_id)
	{
		$query = $this->db->query("SELECT GetSubscriberName({$user_id}) as subscriber_name");
		$result = $query->row();
		return (!empty($result))? $result->subscriber_name : '';
	}

	public function get_subscriber_package_count($user_id){
		$query = $this->db->query("SELECT GetSubscriberPackagesCount({$user_id}) as total_package_subscribed");
		$result = $query->row();
		return (!empty($result))? ((!empty($result->total_package_subscribed))? $result->total_package_subscribed : 0) : 0;
	}

	public function get_subscriber_addon_package_count($user_id){
		$query = $this->db->query("SELECT GetSubscriberAddonPackageCount({$user_id}) as total_package_subscribed");
		$result = $query->row();
		return (!empty($result))? ((!empty($result->total_package_subscribed))? $result->total_package_subscribed : 0) : 0;
	}

	/**
	 * Get Subscriber by email
	 * @param $email
	 * @return mixed
	 */
	public function get_subscriber_by_email($email)
	{
		$this->db->select('users.id,users.profile_id,username,email,subscriber_name');
		$this->db->from('users');
		$this->db->join($this->table_name,$this->table_name.'.id = users.profile_id');
		$this->db->where('email',$email);
		//$this->db->where('user_type','Subscriber');
		$q=$this->db->get();
		return $q->row();
	}

	/**
	 * Get Subscriber by user name
	 * @param $username
	 * @return mixed
	 */
	public function get_subscriber_by_username($username)
	{
		$this->db->select('users.id,users.profile_id,username,email,subscriber_name');
		$this->db->from('users');
		$this->db->join($this->table_name,$this->table_name.'.id = users.profile_id');
		$this->db->where('username',$username);
		//$this->db->where('user_type','Subscriber');
		$q = $this->db->get();
		return $q->row();
	}


	/**
	 * Get Subscriber by email
	 * @param $email
	 * @return mixed
	 */
	public function getSubscriberByEmail($email)
	{
		$this->db->select('users.id,users.profile_id,username,email');
		$this->db->from('users');
		//$this->db->join($this->table_name,$this->table_name.'.id = users.profile_id');
		$this->db->where('email',$email);
		//$this->db->where('user_type','Subscriber');
		$q=$this->db->get();
		return $q->row();
	}

	/**
	 * Get Subscriber by user name
	 * @param $username
	 * @return mixed
	 */
	public function getSubscriberByUsername($username)
	{
		$this->db->select('users.id,users.profile_id,username,email');
		$this->db->from('users');
		//$this->db->join($this->table_name,$this->table_name.'.id = users.profile_id');
		$this->db->where('username',$username);
		//$this->db->where('user_type','Subscriber');
		$q = $this->db->get();
		return $q->row();
	}

	
}