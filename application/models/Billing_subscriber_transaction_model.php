<?php
class Billing_subscriber_transaction_model extends MY_Model
{
	
	protected $table_name = "billing_subscriber_transactions";

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
		$this->db->where('(user_package_assign_type_id = 1 OR user_package_assign_type_id = 2 OR user_package_assign_type_id = 3 OR user_package_assign_type_id = 5)');
		$this->db->like('transaction_date',$date,'after');
		$this->db->order_by('id','desc');
		$query = $this->db->get($this->table_name);
		return $query->result();
	}

	public function get_statements($lco_id,$subscriber_id,$pairing_id=null,$from_date=null,$to_date=null)
	{

		$select = "SELECT * FROM ((SELECT id,credit,debit,transaction_date,pairing_id,GetUserPackageAssignTypeName(user_package_assign_type_id) as description FROM billing_subscriber_transactions WHERE lco_id = '{$lco_id}' AND subscriber_id = '{$subscriber_id}' AND demo != '1'";
		if(!empty($pairing_id) && $pairing_id != 'All') {
			$select .= " AND (pairing_id = '{$pairing_id}' OR pairing_id = '0' )";
		}
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")UNION(SELECT id,credit,debit,transaction_date,pairing_id,GetUserPackageAssignTypeName(user_package_assign_type_id) as description  FROM billing_migrate_transactions WHERE lco_id = '{$lco_id}' AND subscriber_id = '{$subscriber_id}' AND demo != '1'";
		if(!empty($pairing_id) && $pairing_id != 'All') {
			$select .= " AND (pairing_id = '{$pairing_id}' OR pairing_id = '0' )";
		}
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")) as trns order by id";
		$q = $this->db->query($select);

		return $q->result();
	}

	public function get_bank_statements($id,$bank_account_id,$from_date=null,$to_date=null)
	{

		$select = "SELECT * FROM billing_bank_transactions
					JOIN ((SELECT id,credit,debit,lco_id,pairing_id,transaction_date,GetUserPackageAssignTypeName(user_package_assign_type_id) as description FROM billing_subscriber_transactions WHERE user_package_assign_type_id = '6' AND demo != '1'";
		/*if(!empty($pairing_id) && $pairing_id != 'All') {
			$select .= " AND (pairing_id = '{$pairing_id}' OR pairing_id = '0' )";
		}*/
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")UNION(SELECT id,credit,debit,lco_id,pairing_id,transaction_date,GetUserPackageAssignTypeName(user_package_assign_type_id) as description  FROM billing_migrate_transactions WHERE user_package_assign_type_id = '6' AND demo != '1'";
		/*if(!empty($pairing_id) && $pairing_id != 'All') {
			$select .= " AND (pairing_id = '{$pairing_id}' OR pairing_id = '0' )";
		}*/
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")) as trns
		ON trns.id = billing_bank_transactions.subscriber_transaction_id
		WHERE trns.lco_id = '$id'";
		if(!empty($bank_account_id)){
			$select .= " AND billing_bank_transactions.bank_account_id = '$bank_account_id'";
		}

		$select .= " order by trns.id";
		$q = $this->db->query($select);

		return $q->result();
	}

	public function get_pos_statements($lco_id,$pos_id=0,$from_date=null,$to_date=null)
	{

		$select = "SELECT * FROM billing_pos_transactions
					JOIN ((SELECT id,credit,debit,pairing_id,lco_id,transaction_date,GetUserPackageAssignTypeName(user_package_assign_type_id) as description FROM billing_subscriber_transactions WHERE user_package_assign_type_id = '7' AND demo != '1' ";
		/*if(!empty($pairing_id) && $pairing_id != 'All') {
			$select .= " AND (pairing_id = '{$pairing_id}' OR pairing_id = '0' )";
		}*/
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")UNION(SELECT id,credit,debit,pairing_id,lco_id,transaction_date,GetUserPackageAssignTypeName(user_package_assign_type_id) as description  FROM billing_migrate_transactions WHERE user_package_assign_type_id = '7' AND demo != '1'";
		/*if(!empty($pairing_id) && $pairing_id != 'All') {
			$select .= " AND (pairing_id = '{$pairing_id}' OR pairing_id = '0' )";
		}*/
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")) as trns
		ON trns.id = billing_pos_transactions.subscriber_transaction_id
		WHERE  trns.lco_id = $lco_id";
		if(!empty($pos_id)) {
			$select .= " AND billing_pos_transactions.pos_id = '$pos_id'";
		}
		$select .= " order by trns.id";
		$q = $this->db->query($select);


		return $q->result();
	}

	public function get_collector_statements($lco_id,$collector_id=0,$from_date=null,$to_date=null)
	{
		$select = "SELECT * FROM view_collector_statements WHERE  lco_id = ".$lco_id;

		if(!empty($collector_id)){
			$select .= " AND id = ".$collector_id;
		}
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND (transaction_date BETWEEN '{$from_date}' AND '{$to_date}')";
		}
		$select .= " order by id";
		$q = $this->db->query($select);
		return $q->result();
	}

	public function get_cash_statements ($lco_id,$from_date=null,$to_date=null)
	{
		$select = "SELECT * FROM ((SELECT id,credit,debit,pairing_id,transaction_date,GetUserPackageAssignTypeName(user_package_assign_type_id) as description FROM billing_subscriber_transactions WHERE user_package_assign_type_id = '4' AND demo != '1'";
		if(!empty($lco_id)) {
			$select .= " AND lco_id = '$lco_id'";
		}
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")UNION(SELECT id,credit,debit,pairing_id,transaction_date,GetUserPackageAssignTypeName(user_package_assign_type_id) as description  FROM billing_migrate_transactions WHERE user_package_assign_type_id = '4' AND demo != '1'";
		if(!empty($lco_id)) {
			$select .= " AND lco_id = '$lco_id'";
		}
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")) as trns order by id";
		$q = $this->db->query($select);

		return $q->result();
	}

	public function get_collection_statements($lco_id,$from_date,$to_date)
	{

		$select = "SELECT * FROM ((SELECT id,lco_id,credit,debit,transaction_date,pairing_id,GetUserPackageAssignTypeName(user_package_assign_type_id) as description FROM billing_subscriber_transactions WHERE  demo != '1'
		AND user_package_assign_type_id in (4,6,7,8) ";
		if(!empty($lco_id) && $lco_id != 'All') {
			$select .= " AND lco_id in ({$lco_id})";
		}
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}

		$select .= ")UNION(SELECT id,lco_id,credit,debit,transaction_date,pairing_id,GetUserPackageAssignTypeName(user_package_assign_type_id) as description  FROM billing_migrate_transactions WHERE demo != '1'
		AND user_package_assign_type_id in (4,6,7,8)";
		if(!empty($lco_id) && $lco_id != 'All') {
			$select .= " AND lco_id in ({$lco_id})";
		}
		if(!empty($from_date) && !empty($to_date)){
			$select .= " AND transaction_date BETWEEN '{$from_date}' AND '{$to_date}'";
		}
		$select .= ")) as trns order by id";
		$q = $this->db->query($select);

		return $q->result();
	}




}