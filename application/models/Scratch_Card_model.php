<?php

class Scratch_Card_model extends MY_Model
{

	protected $table_name="scratch_card_info";

	public function __construct()
	{
		parent::__construct();
	}

	public function find_batch_by_number($number){
		$this->db->where('batch_no',$number);
		$q = $this->db->get($this->table_name);
		return $q->row();
	}

	public function get_all_batch($created_by,$limit=0,$offset=0)
	{
		$this->db->select('*,GetBatchDistributedCount(scratch_card_detail.card_info_id) as distributed,sum(scratch_card_detail.is_used) as used, (count(*)-sum(scratch_card_detail.is_used)) as unused');
		$this->db->from($this->table_name);
		$this->db->join('scratch_card_detail',$this->table_name.'.id = scratch_card_detail.card_info_id');
		$this->db->where($this->table_name.'.parent_id', $created_by);
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->group_by('scratch_card_info.id');
		$this->db->order_by('scratch_card_info.id','DESC');
		$query = $this->db->get();
		return $result = $query->result();
	}

	public function get_count_group_available_cards($id)
	{
		$sql = "SELECT count(*) as total
 				FROM card_distribution_lists cdl
 				JOIN scratch_card_info sci ON sci.id = cdl.card_info_id";

		if(!empty($id)){
			$sql .= " WHERE cdl.group_id = ".$id." AND (cdl.lco_id IS NULL || cdl.lco_id = 0)";
		}

		$result = $this->db->query($sql);
		$total = $result->row();
		return (!empty($total))? $total->total :0 ;
	}

	public function get_all_group_available_batch($id){
		$sql = "SELECT sci.value,cdl.created_at,cdl.id,cdl.from as from_serial_no,
				cdl.to as to_serial_no,GetGroupName(cdl.group_id) as group_name,
				GetLCOName(cdl.lco_id) as lco_name,cdl.total,
				GetUsedScratchCard(cdl.from,cdl.to) as used,
				(cdl.total - GetUsedScratchCard(cdl.from,cdl.to)) as unused
 				FROM card_distribution_lists cdl
 				JOIN scratch_card_info sci ON sci.id = cdl.card_info_id";

		if(!empty($id)){
			$sql .= " WHERE cdl.group_id = ".$id." AND (cdl.lco_id IS NULL || cdl.lco_id = 0)";
		}

		$result = $this->db->query($sql);
		return $result->result();


	}

	public function get_count_lco_available_cards($id)
	{
		$sql = "SELECT count(*) as total
 				FROM card_distribution_lists cdl
 				JOIN scratch_card_info sci ON sci.id = cdl.card_info_id";

		if(!empty($id)){
			$sql .= " WHERE cdl.lco_id = ".$id;
		}

		$result = $this->db->query($sql);
		$total = $result->row();
		return (!empty($total))? $total->total :0 ;
	}

	public function get_all_lco_available_batch($id,$limit=0,$offset=0){
		$sql = "SELECT sci.value,cdl.created_at,cdl.id,cdl.from as from_serial_no,
				cdl.to as to_serial_no,GetGroupName(cdl.group_id) as group_name,
				GetLCOName(cdl.lco_id) as lco_name,cdl.total,
				GetUsedScratchCard(cdl.from,cdl.to) as used,
				(cdl.total - GetUsedScratchCard(cdl.from,cdl.to)) as unused
 				FROM card_distribution_lists cdl
 				JOIN scratch_card_info sci ON sci.id = cdl.card_info_id";

		if(!empty($id)){
			$sql .= " WHERE cdl.lco_id = ".$id;
		}

		$result = $this->db->query($sql);
		return $result->result();
	}

	public function get_auto_id()
	{
		$sql_str = "SELECT GetAutoID('{$this->table_name}') as autoid";
		$query = $this->db->query($sql_str);
		return $query->row();
	}

	public function get_count_cards($created_by)
	{
		$this->db->from($this->table_name);
		//$this->db->join('scratch_card_detail',$this->table_name.'.id = scratch_card_detail.card_info_id');
		$this->db->where($this->table_name.'.parent_id', $created_by);
		//$this->db->group_by('scratch_card_detail.card_info_id');
		//$this->db->order_by('scratch_card_info.id','DESC');
		return $this->db->count_all_results();
	}

	public function card_by_id($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$query = $this->db->get($this->table_name);
		return $result = $query->row();
	}

	public function last_card_number()
	{
		$this->db->select('*');
		$this->db->where('id', 1);
		$query = $this->db->get('last_scratch_card_info');
		return $result = $query->row();
	}

	public function save_last_card($data)
	{
		$this->db->where('id', 1);
		$this->db->update('last_scratch_card_info', $data);
	}

	public function all_batch_numbers($number)
	{
		$this->db->select('id, batch_no');
		$this->db->where('is_active', 1);
		$this->db->where('is_suspended', 0);
		$this->db->where('parent_id',$number);
		$query = $this->db->get($this->table_name);
		return $result = $query->result();
	}

	public function get_batch_numbers_by_lco($id,$user_type)
	{
		$this->db->distinct();
		$this->db->select('batch_no');
		$this->db->from($this->table_name);
		$this->db->join('scratch_card_detail','scratch_card_detail.card_info_id = scratch_card_info.id');

		if($user_type == "LCO"){
			$this->db->where('scratch_card_detail.lco_id',$id);
		}elseif($user_type == "Group"){
			$this->db->where('scratch_card_detail.group_id',$id);
		}

		//$this->db->where('scratch_card_detail.user_type',$user_type);
		$q = $this->db->get();
		return $q->result();
	}


	public function get_scratch_card_by_serial_card_no($serial_no,$card_no,$parent_id=null,$user_type=null)
	{
		$select = 'scratch_card_info.id,scratch_card_info.is_active as batch_active,scratch_card_info.is_suspended as batch_suspended,
		scratch_card_info.active_from_date,scratch_card_detail.serial_no,
		scratch_card_info.value,scratch_card_detail.card_no,scratch_card_detail.id as card_detail_id,
		scratch_card_detail.parent_id,scratch_card_detail.group_id,scratch_card_detail.lco_id,scratch_card_detail.distributor_id,
		scratch_card_detail.is_active as card_active, scratch_card_detail.is_suspended as card_suspended,
		scratch_card_detail.is_used as card_used';
		$this->db->select($select);
		$this->db->from($this->table_name);
		$this->db->join('scratch_card_detail',$this->table_name.'.id=scratch_card_detail.card_info_id');
		$this->db->where('scratch_card_detail.serial_no',$serial_no);
		$this->db->where('scratch_card_detail.card_no',$card_no);

		if($user_type == "LCO"){
			$this->db->where('scratch_card_detail.lco_id',$parent_id);
		}else if($user_type == "Group"){
			$this->db->where('scratch_card_detail.group_id',$parent_id);
		}else if($user_type == "MSO"){
			$this->db->where('scratch_card_detail.parent_id',$parent_id);
		}
		$query = $this->db->get();
		return $query->row();
	}

	public function get_serial_no($id,$user_type)
	{
		$this->db->select('id,card_info_id,serial_no');
		$this->db->where('is_used',0);
		if($user_type == 'LCO'){
			$this->db->where('lco_id',$id);
		}else if($user_type == 'Group'){
			$this->db->where('group_id',$id);
		}
		$q = $this->db->get('scratch_card_detail');
		return $q->result();
	}

	public function get_card_no($id,$user_type)
	{
		$this->db->select('id,card_info_id,card_no');
		$this->db->where('is_used',0);
		if($user_type == 'LCO'){
			$this->db->where('lco_id',$id);
		}else if($user_type == 'Group'){
			$this->db->where('group_id',$id);
		}
		$q = $this->db->get('scratch_card_detail');
		return $q->result();
	}

	public function get_serial_no_by_batch($batch_id,$id = null,$user_type = null)
	{
		$this->db->select('serial_no');
		$this->db->from($this->table_name);
		$this->db->join('scratch_card_detail','scratch_card_detail.card_info_id = scratch_card_info.id');
		$this->db->where('scratch_card_info.batch_no',$batch_id);
		$this->db->where('scratch_card_detail.is_used',0);
		if(!empty($user_type) && $user_type == "LCO"){
			// for lco
			$this->db->where('scratch_card_detail.distributor_id',0);
			$this->db->where('scratch_card_detail.lco_id',$id);
		}
		if(!empty($user_type) && $user_type == "Group"){
			// for group
			$this->db->where('scratch_card_detail.group_id',$id);
			$this->db->where('scratch_card_detail.lco_id',0);
			$this->db->or_where('scratch_card_detail.lco_id IS NULL');
			$this->db->where('scratch_card_detail.distributor_id',0);
			$this->db->or_where('scratch_card_detail.distributor_id IS NULL');
		}

		if(!empty($user_type) && $user_type == "MSO"){
			// for mso
			$this->db->where('scratch_card_detail.group_id',0);
			$this->db->where('scratch_card_detail.lco_id',0);
			$this->db->where('scratch_card_detail.distributor_id',0);
		}


		$q = $this->db->get();
		return $q->result();
	}

	public function get_distributed_list($id=null,$limit=null,$offset=null)
	{
		$sql = "SELECT sci.value,cdl.created_at,cdl.id,cdl.from as from_serial_no,
				cdl.to as to_serial_no,GetGroupName(cdl.group_id) as group_name,
				GetLCOName(cdl.lco_id) as lco_name,cdl.total,
				GetUsedScratchCard(cdl.from,cdl.to) as used,
				(cdl.total - GetUsedScratchCard(cdl.from,cdl.to)) as unused
 				FROM card_distribution_lists cdl
 				JOIN scratch_card_info sci ON sci.id = cdl.card_info_id";

		if(!empty($id)){
			$sql .= " WHERE cdl.group_id = ".$id ." AND lco_id > 0";
		}


		if(!empty($limit)){
			$sql .= " LIMIT {$offset}, {$limit}";
		}


		$result = $this->db->query($sql);
		//test($this->db->last_query());
		return $result->result();
	}

	public function count_distribution_list($id)
	{
		$sql = "SELECT count(*) as total
 				FROM card_distribution_lists cdl
 				JOIN scratch_card_info sci ON sci.id = cdl.card_info_id";

		if(!empty($id)){
			$sql .= " WHERE cdl.group_id = ".$id ." AND lco_id > 0";
		}

		$result = $this->db->query($sql);
		$row = $result->row();
		return (!empty($row))? $row->total : 0;
	}

	public function has_group_available_card($id,$from,$to,$take=null,$skip=null)
	{
		$sql = "select * from scratch_card_detail where group_id = $id
				AND  (serial_no between $from AND $to)";
		if(!empty($take)){
			$sql .= " LIMIT $skip, $take";
		}
		$q = $this->db->query($sql);
		return $q->result();
	}

	public function has_lco_available_card($id,$from,$to,$take=null,$skip=null)
	{
		$sql = "select * from scratch_card_detail where lco_id = $id
				AND  (serial_no between $from AND $to)";
		if(!empty($take)){
			$sql .= " LIMIT $skip, $take";
		}
		$q = $this->db->query($sql);
		return $q->result();
	}

	public function has_available_card($from,$to,$take=null,$skip=null)
	{
		$sql = "select * from scratch_card_detail where serial_no between $from AND $to";
		if(!empty($take)){
			$sql .= " LIMIT $skip, $take";
		}
		$q = $this->db->query($sql);
		return $q->result();
	}

	public function count_group_available_card($id,$from,$to)
	{
		$sql = "select count(*) as total from scratch_card_detail where group_id = $id
				AND  (serial_no between $from AND $to)";
		$q = $this->db->query($sql);
		$total = $q->row();
		return (!empty($total))? $total->total : 0;
	}

	public function count_lco_available_card($id,$from,$to)
	{
		$sql = "select count(*) as total from scratch_card_detail where lco_id = $id
				AND  (serial_no between $from AND $to)";
		$q = $this->db->query($sql);
		$total = $q->row();
		return (!empty($total))? $total->total : 0;
	}

	public function count_available_card($from,$to)
	{
		$sql = "select count(*) as total from scratch_card_detail where serial_no between $from AND $to";
		$q = $this->db->query($sql);
		$total = $q->row();
		return (!empty($total))? $total->total : 0;
	}

	public function get_download_data($id,$serial_no=null,$search_type=null)
	{
		$sql = "select serial_no,card_no from scratch_card_info
				join scratch_card_detail on
					scratch_card_info.id = scratch_card_detail.card_info_id
				where scratch_card_info.id = {$id}";

		if(!empty($serial_no) && $serial_no != 'n/a'){
			$sql .= " AND serial_no like '{$serial_no}%'";
		}

		if(!empty($search_type) && $search_type != 'n/a'){

			if($search_type == 'used'){
				$sql .= ' AND scratch_card_detail.is_used = 1';
			}else if($search_type == 'unused'){
				$sql .= ' AND scratch_card_detail.is_used = 0';
			}else if($search_type == 'distributed'){
				$sql .= ' AND scratch_card_detail.group_id > 0';
			}

		}
		$q = $this->db->query($sql);
		return $q->result();
	}


	public static function completed_number($prefix, $length) {

		$ccnumber = $prefix;

		# generate digits

		while ( strlen($ccnumber) < ($length - 1) ) {
			$ccnumber .= rand(0,9);
		}

		# Calculate sum

		$sum = 0;
		$pos = 0;

		$reversedCCnumber = strrev( $ccnumber );

		while ( $pos < $length - 1 ) {

			$odd = $reversedCCnumber[ $pos ] * 2;
			if ( $odd > 9 ) {
				$odd -= 9;
			}

			$sum += $odd;

			if ( $pos != ($length - 2) ) {

				$sum += $reversedCCnumber[ $pos +1 ];
			}
			$pos += 2;
		}

		# Calculate check digit

		$checkdigit = (( floor($sum/10) + 1) * 10 - $sum) % 10;
		$ccnumber .= $checkdigit;

		return $ccnumber;
	}

	public static function array_has_dupes($array) {
		// streamline per @Felix
		$countArray = count($array);
		$uniqueCountArray = count(array_unique($array));
		if($countArray != $uniqueCountArray){
			return true;
		}else{
			return false;
		}
	}

	public function is_valid_from_range($from,$batch_id)
	{
		$this->db->from('scratch_card_detail');
		$this->db->where("serial_no" ,$from);
		$this->db->where('card_info_id',$batch_id);
		$query = $this->db->get();
		return $query->row();
	}

	public function is_valid_to_range($to,$batch_id)
	{
		$this->db->from('scratch_card_detail');
		$this->db->where("serial_no" ,$to);
		$this->db->where('card_info_id',$batch_id);
		$query = $this->db->get();
		return $query->row();
	}

}