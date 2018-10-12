<?php

class User_addon_package_model extends MY_Model
{
	protected $table_name="user_addon_packages";

	public function __construct()
	{
		parent::__construct();

	}

	/**
	* @author Himel
	* Get Assigned Packages By User Id of Subscriber
	* @param $id
	* @return array
	*/
	public function get_assigned_packages_by_id($id)
	{
		$select = 'packages.id,user_packages.user_id,user_packages.status,
				packages.package_name,packages.duration,packages.price,
				packages.package_name,count(package_programs.program_id) as no_of_program,
				user_addon_packages.created_at as purchase_date, user_addon_packages.package_start_date as start_date,
				user_addon_packages.package_expire_date as expire_date,subscriber_stb_smartcards.pairing_id,subscriber_stb_smartcards.id as stb_card_id';
		
		$this->db->select($select);
		$this->db->from($this->table_name);
		$this->db->join('packages','packages.id = user_addon_packages.package_id','left');
		$this->db->join('subscriber_stb_smartcards','subscriber_stb_smartcards.id = '.$this->table_name.'.user_stb_smart_id','left');
		$this->db->join('package_programs','package_programs.package_id = user_addon_packages.package_id','left');
		$this->db->where('user_addon_packages.user_id',$id);
		$this->db->where('user_addon_packages.status',1);
		$this->db->group_by('packages.id');
		$query = $this->db->get();
		$result = $query->result();
		
		$packages = array();
		$current_package = '';
		
		if (!empty($result)) {
			foreach ($result as $i=>$r) {
				$packages[$r->id] = $r;
				if($r->status==1){
					$current_package = $r->id;
				}
			}
		}
		return array('current_package'=>$current_package, 'packages'=>$packages);
	}


	public function _get_assigned_packages($id,$stb_card_id=null)
	{
		$select = "subscriber_stb_smartcards.id as stb_card_id, subscriber_stb_smartcards.pairing_id,
				packages.id,packages.token as package_token,packages.package_name,packages.price,packages.duration,user_addon_packages.id,user_addon_packages.user_id, user_addon_packages.status,
				user_addon_packages.package_start_date as start_date,user_addon_packages.package_expire_date as expire_date,
				count(package_programs.program_id) as no_of_program,user_addon_packages.no_of_days";
		
		$this->db->select($select);
		$this->db->from('subscriber_stb_smartcards');
		$this->db->join('user_addon_packages', 'user_addon_packages.user_stb_smart_id = subscriber_stb_smartcards.id');
		$this->db->join('packages','packages.id = user_addon_packages.package_id');
		$this->db->join('package_programs','package_programs.package_id = user_addon_packages.package_id');
		$this->db->where('subscriber_id',$id);
		$this->db->where('user_addon_packages.user_stb_smart_id',$stb_card_id);
		$this->db->group_by('subscriber_stb_smartcards.id');
		$query = $this->db->get();
		$results = $query->result();

		return array(
			 'packages'=>$results
		);

	}

	public function get_assigned_packages($subscriber_id,$stb_card_id=null)
	{
		$select = "subscriber_stb_smartcards.id as stb_card_id, subscriber_stb_smartcards.pairing_id,
			packages.id,packages.package_name,packages.duration,packages.price,packages.duration,user_addon_packages.id as user_package_id,user_addon_packages.package_start_date,user_addon_packages.package_expire_date,
			count(package_programs.program_id) as no_of_program,`user_addon_packages`.`no_of_days`";
		
		$this->db->select($select);
		$this->db->from('subscriber_stb_smartcards');  
		$this->db->join('user_addon_packages','user_addon_packages.user_stb_smart_id = subscriber_stb_smartcards.id','left');
		$this->db->join('packages','packages.id = user_addon_packages.package_id','left');
		$this->db->join('package_programs','package_programs.package_id = user_addon_packages.package_id','left');
		$this->db->where('user_addon_packages.user_id',$subscriber_id);

	
		if($stb_card_id != null){

			$this->db->where('subscriber_stb_smartcards.id',$stb_card_id);
		}

		$this->db->group_by('user_addon_packages.id');
		$query = $this->db->get();
		$results = $query->result();

		$pairing_packages = array();
		date_default_timezone_set('Asia/Dhaka');
		foreach($results as $result){
			
			$pairing_packages[$result->stb_card_id]['stb_card_id'] = $result->stb_card_id;
			$pairing_packages[$result->stb_card_id]['pairing_id']  = $result->pairing_id;
			$pairing_packages[$result->stb_card_id]['start_date']  = $result->package_start_date;
			$pairing_packages[$result->stb_card_id]['expire_date'] = $result->package_expire_date;
			//$pairing_packages[$result->stb_card_id]['charge_type'] = $result->charge_type;

			$today_date_object   = new DateTime(); 
			$expire_date_object  = new DateTime($result->package_expire_date);
			$date_diff = date_diff($today_date_object,$expire_date_object);
			$no_of_days = 0;
			if($date_diff->days > 0 && $date_diff->invert == 0){
				$no_of_days = $date_diff->days;
			}else{
				$no_of_days = '-'.$date_diff->days;
			}
			$pairing_packages[$result->stb_card_id]['no_of_days'] = $no_of_days;
			$pairing_packages[$result->stb_card_id]['duration'] = $result->duration;
			/*if(empty($pairing_packages[$result->stb_card_id]['no_of_days'])){
				
			} else {
				$pairing_packages[$result->stb_card_id]['no_of_days'] += $result->no_of_days;
			}*/

			if(empty($pairing_packages[$result->stb_card_id]['total_price'])){
				$pairing_packages[$result->stb_card_id]['total_price'] = $result->price;
			} else {
				$pairing_packages[$result->stb_card_id]['total_price'] += $result->price;
			}

			$pairing_packages[$result->stb_card_id]['packages'][] = array(
				'user_package_id' => $result->user_package_id,
				'id'           => $result->id,
				'package_name' => $result->package_name,
				'duration'     => $result->duration,
				'start_date'   => $result->package_start_date,
				'expire_date'  => $result->package_expire_date,
				'price'        => $result->price,
				'no_of_program'=> $result->no_of_program,
				'no_of_days'   => $result->no_of_days
			);
		}

		return $pairing_packages;

	}

	public function has_package_assigned($subscriber_id,$stb_card_id=null,$package_id=null)
	{
		
		$this->db->where('user_id',$subscriber_id);
		if($stb_card_id != null){
			$this->db->where('user_stb_smart_id',$stb_card_id);
		}

		if($package_id != null){
			$this->db->where('package_id',$package_id);
		}
		$this->db->where('status',1);
		$query = $this->db->get($this->table_name);
		//echo $this->db->last_query();
		$result = $query->result();
		return $result;
	}

	public function remove_packages($user_id,$package_id,$stb_card_id)
	{
		if ($user_id) {
			$this->db->where('user_id',$user_id);
			$this->db->where('package_id',$package_id);
			$this->db->where('user_stb_smart_id',$stb_card_id);
			$this->db->delete($this->table_name);
			return true;
		}

		return false;

	}

	public function update_package_status($id)
	{
		if ($id) {
			$this->db->where('user_id',$id);
			$this->db->update($this->table_name,array('status'=>0));
			return true;
		}
		return false;
	}

	public function assigned_package($id)
	{
		$this->db->where('package_id',$id);
		$query = $this->db->get($this->table_name);
		$this->db->group_by('package_id');
		$results = $query->result();
		$query->free_result();
		return $results;
	}

	/**
	* @author Himel
	* @param $package_id
	*/
	public function get_assign_packages_with_details($package_id,$limit=0,$offset=0,$filter=null,$sort=null,$count=false)
	{
		$this->db->select('pairing_id, subscriber_name, user_addon_packages.package_start_date,user_addon_packages.package_expire_date');
		$this->db->from('user_addon_packages');
		$this->db->join('packages','packages.id = user_addon_packages.package_id');
		$this->db->join('subscriber_stb_smartcards','subscriber_stb_smartcards.id = user_addon_packages.user_stb_smart_id');
		$this->db->join('users','users.id = user_addon_packages.user_id');
		$this->db->join('subscriber_profiles','subscriber_profiles.id = users.profile_id');
		$this->db->where('user_addon_packages.package_id',$package_id);

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
		if($count){
			return $this->db->count_all_results();
		}
		$query = $this->db->get();
		return $query->result();
	}



	public function get_expired_packages($user_id)
	{
		$select = 'packages.package_name,packages.price,packages.duration,user_addon_packages.id,
				   user_addon_packages.user_id,user_addon_packages.package_start_date,
				   user_addon_packages.package_expire_date';
		$this->db->select($select);
		$this->db->from($this->table_name);
		$this->db->join('packages','packages.id = user_addon_packages.package_id');
		$this->db->where('user_addon_packages.user_id',$user_id);
		$this->db->where('user_addon_packages.package_expire_date < NOW()');
		$query = $this->db->get();
		return $query->result();
	}

	public function remove_packages_by_stb_card_pairing($stb_card_id)
	{
		$this->db->where('user_stb_smart_id',$stb_card_id);
		$this->db->delete($this->table_name);
		return $this->db->affected_rows();
	}
	
}