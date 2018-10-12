<?php

class Subscriber_stb_smartcard_model extends MY_Model
{
	protected $table_name = "subscriber_stb_smartcards";

	public function __construct()
	{
		parent::__construct();
	}

	public function has_stb_card_pair($subscriber,$lco,$stb,$card)
	{
		$this->db->where('subscriber_id',$subscriber->get_attribute('id'));
		$this->db->where('lco_id',$lco);
		$this->db->where('stb_id',$stb->get_attribute('id'));
		$this->db->where('card_id',$card->get_attribute('id'));
		$result = $this->db->get($this->table_name);
		return $result->row();
	}

	public function get_paring_cards($subscriber_id)
	{
		$select = 'subscriber_stb_smartcards.id,pairing_id, stb_providers.stb_provider,free_stb,free_card,free_subscription_fee,
					stb_providers.stb_type,set_top_boxes.external_card_number as stb_number, 
					smart_card_providers.stb_provider as smart_card_provider, 
					smart_card_providers.stb_type as smart_card_type, smart_cards.external_card_number as smart_card_number';
		$this->db->select($select);
		$this->db->from('subscriber_stb_smartcards');
		$this->db->join('set_top_boxes','subscriber_stb_smartcards.stb_id = set_top_boxes.id');
		$this->db->join('smart_cards','subscriber_stb_smartcards.card_id = smart_cards.id');
		$this->db->join('stb_providers','set_top_boxes.stb_card_provider = stb_providers.id');
		$this->db->join('smart_card_providers','smart_cards.smart_card_provider = smart_card_providers.id');
		$this->db->where('subscriber_stb_smartcards.subscriber_id',$subscriber_id);

		/*$this->db->where('set_top_boxes.subscriber_id',$subscriber_id);
		$this->db->where('smart_cards.subscriber_id',$subscriber_id);*/

		$result = $this->db->get();
		//echo $this->db->last_query();

		return $result->result();

	}

	public function get_devices($subscriber_id)
	{
		$select = 'id,device_number,lco_id,is_assigned,is_used,assigned_date,free_subscription_fee';
		$this->db->select($select);
		$this->db->from('devices');
		//$this->db->join('set_top_boxes','subscriber_stb_smartcards.stb_id = set_top_boxes.id');
		//$this->db->join('smart_cards','subscriber_stb_smartcards.card_id = smart_cards.id');
		//$this->db->join('stb_providers','set_top_boxes.stb_card_provider = stb_providers.id');
		//$this->db->join('smart_card_providers','smart_cards.smart_card_provider = smart_card_providers.id');
		$this->db->where('devices.subscriber_id',$subscriber_id);

		/*$this->db->where('set_top_boxes.subscriber_id',$subscriber_id);
		$this->db->where('smart_cards.subscriber_id',$subscriber_id);*/

		$result = $this->db->get();
		//echo $this->db->last_query();

		return $result->result();

	}

	public function get_unassigned_pairing_cards($subscriber_id)
	{
		$select = "subscriber_stb_smartcards.id, free_stb,free_card,free_subscription_fee,pairing_id, set_top_boxes.external_card_number as stb_number, smart_cards.external_card_number as smart_card_number";
		$this->db->select($select);
		$this->db->from('subscriber_stb_smartcards');
		$this->db->join('user_packages','user_packages.user_stb_smart_id = subscriber_stb_smartcards.id','left'); 
		$this->db->join('set_top_boxes','set_top_boxes.id = subscriber_stb_smartcards.stb_id','left');
		$this->db->join('smart_cards','smart_cards.id = subscriber_stb_smartcards.card_id','left');
		$this->db->where('subscriber_stb_smartcards.subscriber_id',$subscriber_id);
		$this->db->where('user_packages.user_stb_smart_id IS NULL');
		
		$result = $this->db->get();
		
		return $result->result();
	}

	public function get_unassigned_devices($subscriber_id)
	{
		$select = 'id,device_number,lco_id,is_assigned,is_used,assigned_date,free_subscription_fee';
		$this->db->select($select);
		$this->db->from('devices');
		//$this->db->join('user_packages','user_packages.user_stb_smart_id = subscriber_stb_smartcards.id','left');
		//$this->db->join('set_top_boxes','set_top_boxes.id = subscriber_stb_smartcards.stb_id','left');
		//$this->db->join('smart_cards','smart_cards.id = subscriber_stb_smartcards.card_id','left');
		//$this->db->where('subscriber_stb_smartcards.subscriber_id',$subscriber_id);
		//$this->db->where('user_packages.user_stb_smart_id IS NULL');
		$this->db->where('devices.subscriber_id',$subscriber_id);
		$this->db->where('is_assigned',0);
		$result = $this->db->get();

		return $result->result();
	}

	public function get_pairing_by_id($id)
	{
		$this->db->select($this->table_name.'.id,free_stb,free_card,free_subscription_fee,pairing_id,smart_cards.external_card_number,smart_cards.internal_card_number,set_top_boxes.external_card_number as stb_id,set_top_boxes.id as stb_pid,smart_cards.id as card_pid');
		$this->db->from($this->table_name);
		$this->db->join('smart_cards','smart_cards.id = '.$this->table_name.'.card_id');
		$this->db->join('set_top_boxes','set_top_boxes.id = '.$this->table_name.'.stb_id');
		$this->db->where($this->table_name.'.id',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_pairing_by_subscriber_id($subscriber_id)
	{
		$this->db->select($this->table_name.'.id,pairing_id,smart_cards.external_card_number,smart_cards.internal_card_number,set_top_boxes.external_card_number as stb_external_card_number');
		$this->db->from($this->table_name);
		$this->db->join('smart_cards','smart_cards.id = '.$this->table_name.'.card_id');
		$this->db->join('set_top_boxes','set_top_boxes.id = '.$this->table_name.'.stb_id');
		$this->db->where($this->table_name.'.subscriber_id',$subscriber_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_device_by_subscriber_id($subscriber_id)
	{
		$this->db->select('devices.id,devices.device_number as pairing_id');
		$this->db->from('devices');
		//$this->db->join('smart_cards','smart_cards.id = '.$this->table_name.'.card_id');
		//$this->db->join('set_top_boxes','set_top_boxes.id = '.$this->table_name.'.stb_id');
		$this->db->where('devices.subscriber_id',$subscriber_id);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_auto_id()
	{
		$sql_str = "SELECT GetAutoID('{$this->table_name}') as autoid";
		$query = $this->db->query($sql_str);
		return $query->row();
	}

	public function remove_by_subscriber($subscriber_id,$stb_card_id)
	{
		$this->db->where('id',$stb_card_id);
		$this->db->where('subscriber_id',$subscriber_id);
		$this->db->delete($this->table_name);
		return $this->db->affected_rows();
	}

}