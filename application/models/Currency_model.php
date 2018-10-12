<?php 

class Currency_model extends MY_Model
{
	protected $table_name="currencies";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_active_curreny()
	{
		$this->db->where('is_active',1);
		$this->db->limit(1);
		$result = $this->db->get($this->table_name);
		return $result->row();
	}
}