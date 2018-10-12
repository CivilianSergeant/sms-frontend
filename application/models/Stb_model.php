<?php

class Stb_model extends MY_Model
{

	protected $table_name="set_top_boxes";
	protected $id = 3;

	public function __construct()
	{
		parent::__construct();
	}

	public function creat_stb($data)
	{
		return $this->create($data);
	}

	public function get_all_stb($created_by, $args=array())
	{

		$this->db->select('set_top_boxes.id, set_top_boxes.internal_card_number, set_top_boxes.external_card_number, set_top_boxes.stb_card_provider, set_top_boxes.stb_card_provider, stb_providers.stb_provider, set_top_boxes.price, set_top_boxes.is_used');
		$this->db->from($this->table_name);
		$this->db->join('stb_providers','stb_providers.id = set_top_boxes.stb_card_provider');
		$this->db->where('set_top_boxes.parent_id', $created_by);

		if(!empty($args[2])){
			foreach($args[2]['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		if(!empty($args[3])){
			foreach($args[3] as $s){
				$this->db->order_by($s['field'],$s['dir']);
			}
		}else{
			$this->db->order_by("set_top_boxes.id", "DESC");
		}


		if(!empty($args[0])){

			$this->db->limit($args[0], $args[1]);
		}

		$query = $this->db->get();
		$result['data'] = $query->result_array();

		$this->db->select('count(set_top_boxes.id) AS TOTAL');
		$this->db->from($this->table_name);
		$this->db->join('stb_providers','stb_providers.id = set_top_boxes.stb_card_provider');
		$this->db->where('set_top_boxes.parent_id', $created_by);
		$query = $this->db->get();
		$temp = $query->result_array();
		$result['total'] = $temp['0']['TOTAL'];
		return $result;
	}

	public function stb_by_id($id)
	{
		$this->db->select('*,set_top_boxes.id as stb_id');
		$this->db->from($this->table_name);
		$this->db->join('stb_providers','stb_providers.id = set_top_boxes.stb_card_provider');
		
		$this->db->join('users as u_subs','u_subs.id = set_top_boxes.subscriber_id', 'left');
		$this->db->join('subscriber_profiles','subscriber_profiles.id = u_subs.profile_id', 'left');

		$this->db->join('users as u_lco','u_lco.id = set_top_boxes.lco_id', 'left');
		$this->db->join('lco_profiles','lco_profiles.id = u_lco.profile_id', 'left');

		$this->db->where('set_top_boxes.id', $id);
		$query = $this->db->get();
		
		return $result = $query->row();
	}

	public function update_by_id($data,$id)
	{
		return $this->update($data,$id);
	}

	/**
	* @author Himel
	* Assign Set-top boxes to lco
	* @param $lco_id
	* @param $boxes
	*/
	public function assign_to_lco($lco_id,$boxes,$id=null)
	{
		date_default_timezone_set('Asia/Dhaka');
		if(is_array($boxes)){
			$this->db->where('id in('.implode(',',$boxes).')');
			$data = array('lco_id'=>$lco_id,'lco_assigned_date'=>date('Y-m-d H:i:s',time()));
			$data['updated_at'] = date('Y-m-d H:i:s');
			if($id != null){
				$data['updated_by'] = $id;
			}
			$this->db->update($this->table_name,$data);
			return $this->db->affected_rows();
		} else {
			return false;
		}
		
	}

	/**
	* @author Himel
	* Get all stb assigned to lco
	*/
	public function get_stb_assigned_to_lco($id)
	{
		$this->db->select('set_top_boxes.id as stb_box_id, stb_providers.id as stb_provider_id, subscriber_id,lco_id, set_top_boxes.external_card_number as stb_number, stb_providers.stb_provider, stb_providers.stb_type');
		$this->db->from('set_top_boxes');
		$this->db->join('stb_providers','set_top_boxes.stb_card_provider = stb_providers.id');
		$this->db->where('set_top_boxes.lco_id',$id);
		$this->db->where('set_top_boxes.subscriber_id IS NULL');
		$query = $this->db->get();

		return $query->result();
	}

	/**
	* @author Himel
	* Get all unused stb to MSO
	*/
	public function get_unsused_stbs()
	{
		$this->db->select('set_top_boxes.id as stb_box_id, stb_providers.id as stb_provider_id, subscriber_id,lco_id, set_top_boxes.external_card_number as stb_number, stb_providers.stb_provider, stb_providers.stb_type');
		$this->db->from('set_top_boxes');
		$this->db->join('stb_providers','set_top_boxes.stb_card_provider = stb_providers.id');
		$this->db->where('set_top_boxes.lco_id IS NULL');
		$this->db->where('set_top_boxes.subscriber_id IS NULL');
		$query = $this->db->get();

		return $query->result();
	}


	public function get_count_stb()
	{
		$this->db->where('is_used', 0);
		return $this->db->count_all_results($this->table_name);
	}

	public function get_stb_by_ext_card_number($number){
		$this->db->where('external_card_number',$number);
		$query = $this->db->get($this->table_name);
		return $query->result();
	}
}