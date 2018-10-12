<?php

class Money_receipt_model extends MY_Model
{

	protected $table_name="billing_money_receipts";

	public function __construct()
	{
		parent::__construct();
	}

	public function is_assigned($book_number,$receipt_number)
	{
		$this->db->select('money_receipt_number,name');
		$this->db->from($this->table_name);
		$this->db->join('billing_lco_collector','billing_lco_collector.id = billing_money_receipts.collector_id');
		$this->db->where('money_receipt_book_number',$book_number);
		$this->db->where('money_receipt_number',$receipt_number);
		$query = $this->db->get();
		return $query->row();
	}

	public function is_assigned_within_range($book_number,$from,$to)
	{
		$this->db->select('money_receipt_number,name');
		$this->db->from($this->table_name);
		$this->db->join('billing_lco_collector','billing_lco_collector.id = billing_money_receipts.collector_id');
		$this->db->where('money_receipt_book_number',$book_number);
		$this->db->where('money_receipt_number BETWEEN '.$from. ' AND '.$to);
		$query = $this->db->get();
		return $query->row();
	}

	public function is_assign_to_collector($money_receipt_number, $collector_id)
	{
		$this->db->where('collector_id',$collector_id);
		$this->db->where('money_receipt_number',$money_receipt_number);
		$result = $this->db->get($this->table_name);
		$row = $result->row();
		return $row;
	}
	
}