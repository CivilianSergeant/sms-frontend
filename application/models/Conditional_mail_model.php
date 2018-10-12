<?php

class Conditional_mail_model extends MY_Model
{

	protected $table_name="condition_mail_log";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_logs($limit=0,$offset=0,$count=false,$subscriber_id,$stb,$smart_card,$status=null)
	{
		$select = 'id,GetLCOName(lco_id) as lco_name ,GetSubscriberName(subscriber_id) as subscriber_name,
		mail_title,condition_return_code,start_time,end_time,is_stoped,stb_id,smart_card_ext_id,(end_time < NOW()) as expired';
		$this->db->select($select);

		if(!empty($subscriber_id))
		$this->db->where('subscriber_id', $subscriber_id);

		if(!empty($stb))
		$this->db->where('stb_id', $stb);

		if(!empty($smart_card))
		$this->db->where('smart_card_ext_id', $smart_card);

		if(!empty($status)){
			switch($status){
				case 'expired':
					$this->db->where('end_time < NOW()');
					break;
				case 'not_expired':
					$this->db->where('end_time > NOW()');
					break;
				case 'active':
					$this->db->where('is_stoped IS NULL');
					break;
				case 'stopped':
					$this->db->where('is_stoped',1);
					break;
			}
		}

		$this->db->order_by('id','desc');

		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		if($count)
		{
			$query = $this->db->count_all_results($this->table_name);
			return $query;
		} 

		$query = $this->db->get($this->table_name);
		//test($this->db->last_query());
		return $query->result();	
	}

	public function get_stbcards_by_id($subscriber_id)
	{
		$this->db->select('external_card_number');
		$this->db->where('subscriber_id', $subscriber_id);
		$query = $this->db->get('set_top_boxes');
		return $query->result();
	}

	public function get_cards_by_id($subscriber_id)
	{
		$this->db->select('external_card_number');
		$this->db->where('subscriber_id', $subscriber_id);
		$query = $this->db->get('smart_cards');
		return $query->result();
	}

	public function stop_mail($id,$data)
	{
		$this->db->where('id',$id);
		$this->db->update($this->table_name,$data);
		return true;
	}

}