<?php

class Ic_Smartcard_model extends MY_Model
{

	protected $table_name="smart_cards";
	protected $id = 3;

	public function __construct()
	{
		parent::__construct();
	}

	public function creat_ic_smart_card($data)
	{
		return $this->create($data);
	}

	public function get_all_ic_smart_card($created_by,$limit=0,$offset=0)
	{
		$this->db->select('smart_cards.id, smart_cards.internal_card_number, smart_cards.external_card_number, smart_cards.smart_card_provider, smart_card_providers.stb_provider, smart_cards.price, smart_cards.is_used');
		$this->db->from($this->table_name);
		$this->db->join('smart_card_providers','smart_card_providers.id = smart_cards.smart_card_provider');
		$this->db->where('smart_cards.parent_id', $created_by);
		$this->db->order_by('smart_cards.id', 'DESC');
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $result = $query->result();
	}

	public function ic_smartcards_by_id($id)
	{
		$this->db->select('*,smart_cards.id as smart_card_id');
		$this->db->from($this->table_name);
		$this->db->join('smart_card_providers','smart_card_providers.id = smart_cards.smart_card_provider');

		$this->db->join('users as u_subs','u_subs.id = smart_cards.subscriber_id', 'left');
		$this->db->join('subscriber_profiles','subscriber_profiles.id = u_subs.profile_id', 'left');

		$this->db->join('users as u_lco','u_lco.id = smart_cards.lco_id', 'left');
		$this->db->join('lco_profiles','lco_profiles.id = u_lco.profile_id', 'left');
		
		$this->db->where('smart_cards.id', $id);
		$query = $this->db->get();
		return $result = $query->row();
	}

	public function update_by_id($data,$id)
	{
		return $this->update($data,$id);
	}

	/**
	* @author Himel
	* Assign Smart cards to lco
	* @param $lco_id
	* @param $cards
	*/
	public function assign_to_lco($lco_id,$cards,$id=null)
	{
		date_default_timezone_set('Asia/Dhaka');
		if(is_array($cards)){
			
			$this->db->where('id in('.implode(',',$cards).')');
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
	public function get_smartcard_assigned_to_lco($id)
	{
		$this->db->select('smart_cards.id as smart_card_id, smart_card_providers.id as smart_card_provider_id, subscriber_id,lco_id, smart_cards.external_card_number as smart_card_number, smart_card_providers.stb_provider as smart_card_provider, smart_card_providers.stb_type as smart_card_type');
		$this->db->from('smart_cards');
		$this->db->join('smart_card_providers','smart_cards.smart_card_provider = smart_card_providers.id');
		$this->db->where('smart_cards.lco_id',$id);
		$this->db->where('smart_cards.subscriber_id IS NULL');
		$query = $this->db->get();
		return $query->result();
	}

	/**
	* @author Himel
	* Get all Unused cards mso
	*/
	public function get_unused_smartcards()
	{
		$this->db->select('smart_cards.id as smart_card_id, smart_card_providers.id as smart_card_provider_id, subscriber_id,lco_id, smart_cards.external_card_number as smart_card_number, smart_card_providers.stb_provider as smart_card_provider, smart_card_providers.stb_type as smart_card_type');
		$this->db->from('smart_cards');
		$this->db->join('smart_card_providers','smart_cards.smart_card_provider = smart_card_providers.id');
		$this->db->where('smart_cards.lco_id IS NULL');
		$this->db->where('smart_cards.subscriber_id IS NULL');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_count_card($created_by)
	{
/*		$this->db->where('is_used', 0);*/
		$this->db->where('parent_id', $created_by);
		return $this->db->count_all_results($this->table_name);
	}

	public function get_card_by_ext_card_number($number){
		$this->db->where('external_card_number',$number);
		$query = $this->db->get($this->table_name);
		return $query->result();
	}


}