<?php

class Scratch_Card_Detail_model extends MY_Model
{

	protected $table_name="scratch_card_detail";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_auto_id()
	{
		$sql_str = "SELECT GetAutoID('{$this->table_name}') as autoid";
		$query = $this->db->query($sql_str);
		return $query->row();
	}


	public function get_cards_by_batch($card_info_id,$card_no=null,$serial_no=null,$type=null,$limit=0,$offset=0)
	{
		/*$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('card_info_id', $card_info_id);

		if(!empty($card_no)){
			$this->db->like('card_no',$card_no,'before');
		}

		if(!empty($serial_no)){
			$this->db->like('serial_no',$serial_no,'before');
		}


		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $result = $query->result();*/

		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('card_info_id', $card_info_id);

		if(!empty($card_no)){
			$this->db->like('card_no',$card_no,'before');
		}

		if(!empty($serial_no)){
			$this->db->like('serial_no',$serial_no,'before');
		}

		if(!empty($type)){
			if($type == 'used'){
				$this->db->where('is_used',1);
			}else if($type == 'unused'){
				$this->db->where('is_used',0);
			}else if($type == 'distributed'){
				$this->db->where('group_id > 0');
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

	public function get_count_cards($card_info_id,$card_no=null,$serial_no=null,$type=null)
	{
		/*$this->db->where('card_info_id', $card_info_id);

		if(!empty($card_no)){
			$this->db->like('card_no',$card_no,'before');
		}

		if(!empty($serial_no)){
			$this->db->like('serial_no',$serial_no,'before');
		}
		return $this->db->count_all_results($this->table_name);*/

		$this->db->where('card_info_id', $card_info_id);

		if(!empty($card_no)){
			$this->db->like('card_no',$card_no,'before');
		}

		if(!empty($serial_no)){
			$this->db->like('serial_no',$serial_no,'before');
		}

		if(!empty($type)){
			if($type == 'used'){
				$this->db->where('is_used',1);
			}else if($type == 'unused'){
				$this->db->where('is_used',0);
			}else if($type == 'distributed'){
				$this->db->where('group_id > 0');

			}
		}

		return $this->db->count_all_results($this->table_name);
	}

	public function get_count_card_by_cardno_serial($search_key)
	{
		$this->db->where('card_no', $search_key);
		$this->db->or_where('serial_no' , $search_key);
		return $this->db->count_all_results($this->table_name);
	}

	public function get_card_by_id($id)
	{
		$this->db->select('scratch_card_info.id as card_id,
		 				 scratch_card_info.batch_no,
		 				 scratch_card_info.active_from_date,
		 				 scratch_card_info.is_active,
		 				 scratch_card_info.is_suspended,
		 				 scratch_card_info.value,
		 				 scratch_card_info.number_of_cards,
						 scratch_card_detail.id,
						 scratch_card_detail.is_suspended,
						 scratch_card_detail.card_no,
						 scratch_card_detail.used_date,
						 scratch_card_detail.is_used,
						 scratch_card_detail.is_active,
						 scratch_card_detail.serial_no,
						 scratch_card_detail.created_at,
						 scratch_card_detail.parent_id,
						 scratch_card_detail.lco_id,
						 scratch_card_detail.group_id,
						 scratch_card_detail.updated_by,
						 GetLCOName(scratch_card_detail.lco_id) as lco_name,
						 GetOperatorName(scratch_card_detail.parent_id) as operator_name,
						 GetDistributorName(scratch_card_detail.distributor_id) as distributor_name,
						 GetSubscriberName(scratch_card_detail.subscriber_id) as subscriber_name
						');
		$this->db->from($this->table_name);
		$this->db->join('scratch_card_info','scratch_card_info.id = scratch_card_detail.card_info_id');
		$this->db->where('scratch_card_detail.id', $id);
		$query = $this->db->get();
		return $result = $query->row();
	}

	public function get_card_by_cardno_serial($search_key)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('card_no', $search_key);
		$this->db->or_where('serial_no', $search_key);
		$query = $this->db->get();
		return $result = $query->row();
	}

	public function validate_scratch_cards($serial_no)
	{
		$this->db->select('scratch_card_info.id as batch_id, scratch_card_info.batch_no, scratch_card_detail.is_used,scratch_card_detail.user_type,scratch_card_detail.group_id,scratch_card_detail.lco_id,scratch_card_detail.distributor_id');
		$this->db->from($this->table_name);
		$this->db->join('scratch_card_info','scratch_card_info.id = scratch_card_detail.card_info_id');
		$this->db->where('scratch_card_detail.serial_no', $serial_no);
		$query = $this->db->get();
		return $result = $query->row();
	}

	public function scratch_card_distribution($data)
	{
		$this->db->where('serial_no >=', $data['serial_from']);
		$this->db->where('serial_no <=',  $data['serial_to']);
		/*$this->db->where('lco_id', null);
		$this->db->or_where('distributor_id',null);
		$this->db->or_where('distributor_id',0);*/
		$this->db->set('group_id',$data['group_id']);
		$this->db->set('lco_id', $data['lco_user_id']);
		$this->db->set('distributor_id', $data['distributor_id']);
		$this->db->set('user_type',$data['user_type']);
		$this->db->set('updated_at', date('Y-m-d H:i:s'));
		$this->db->set('updated_by', $data['updated_by']);
		$this->db->update($this->table_name);
		return $this->db->affected_rows();
	}



}