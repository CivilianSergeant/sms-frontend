<?php
class Billing_migrate_transaction_model extends MY_Model
{
	
	protected $table_name = "billing_migrate_transactions";

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* @author Himel
	* Get Subscriber Latest Balance by following Parameter
	* @param $subscriber_id , represents Subscriber's Users table id
	*/
	public function get_subscriber_balance($subscriber_id)
	{
		$this->db->select('balance,demo');
		$this->db->where('subscriber_id',$subscriber_id);
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$query = $this->db->get($this->table_name);
		$result = $query->row();

		return (!empty($result))? $result: null;
	}

	/**
	* @author Himel
	* Get Subscriber charge transaction by following Parameters
	* @param $pairing_id
	* @param $subscriber_id , represents Subscriber's Users table id
	* @param $package_id
	* @param $date
	*/
	public function get_subscribe_charge_transaction($pairing_id,$subscriber_id,$package_id,$date)
	{
		$this->db->where('pairing_id',$pairing_id);
		$this->db->where("subscriber_id",$subscriber_id);
		$this->db->where('package_id',$package_id);
		$this->db->where('debit IS NOT NULL');
		$this->db->where('demo',0);
		$this->db->like('transaction_date',$date,'after');
		$this->db->order_by('id','desc');
		$query = $this->db->get($this->table_name);
		return $query->row();
	}

	public function get_subscribe_charge_transactions($pairing_id,$subscriber_id,$date)
	{
		$this->db->where('pairing_id',$pairing_id);
		$this->db->where("subscriber_id",$subscriber_id);
		//$this->db->where('package_id',$package_id);
		$this->db->where('debit IS NOT NULL');
		$this->db->where('credit = 0');
		$this->db->where('demo',0);
		$this->db->where('(user_package_assign_type_id = 1 OR user_package_assign_type_id = 2 OR user_package_assign_type_id = 3)');
		$this->db->like('transaction_date',$date,'after');
		$this->db->order_by('id','desc');
		$query = $this->db->get($this->table_name);
		return $query->result();
	}

	public function migrateTransactions($transactions)
	{
		$transaction_id = array();
		foreach($transactions as $transaction){
			$transaction_id[] = $transaction->id;
			$this->save((array)$transaction);

		}

		$this->db->where_in('billing_subscriber_transactions.id',$transaction_id);
		$this->db->delete('billing_subscriber_transactions');

	}



}