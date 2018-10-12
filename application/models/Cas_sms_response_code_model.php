<?php 
class Cas_sms_response_code_model extends MY_Model
{
	
	protected $table_name = "cas_sms_response_codes";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_code_by_name($code)
	{

		$this->db->where('decimal_error_code',$code);
		$this->db->limit(1);
		$query = $this->db->get($this->table_name);
		$result =  $query->row();
		$query->free_result();
		return $result;
	}
	
}