<?php

class Ic_Smart_Provider_model extends MY_Model
{

	protected $table_name="smart_card_providers";
	

	public function __construct()
	{
		parent::__construct();
	}

	public function creat_ic_smartcard_provider($data)
	{
		return $this->create($data);
	}

	public function get_all_provider($created_by,$limit=0,$offset=0)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('parent_id', $created_by);
		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
		return $result = $query->result();
	}

	/**
	* @author Himel
	* Get all stb provider type distinctly
	*/
	public function get_all_distinct_provider_type($created_by)
	{
		$this->db->distinct();
		$this->db->select('stb_type,id');
		$this->db->from($this->table_name);
		$this->db->where('parent_id',$created_by);
		$this->db->group_by('stb_type','ASC');
		$query = $this->db->get();
		return $result = $query->result();
	}

	public function get_all_ic_providers()
	{
		return $this->get_all();
	}

	public function provider_by_id($id)
	{
		return $this->find_by_id($id);
	}

	public function update_provider_by_id($data,$id)
	{
		return $this->update($data,$id);
	}

	public function delete_provider_by_id($id)
	{
		return $this->permanent_delete($id);
	}

	/**
	* @author Himel
	* Get all set-top-boxes that is unassigned to lco 
	* @param $stb_type_id
	* @param $stb_number
	*/
	public function get_cards($stb_type_id,$smartcard_number,$limit)
	{

		$this->db->select(
				'smart_card_providers.id as provider_id, 
				smart_cards.id as smart_card_id,
				smart_card_providers.stb_provider as supplier, 
				description, 
				external_card_number,
				DATE_FORMAT(smart_cards.created_at,"%b %d %Y") as created_date'
		);

		$this->db->from($this->table_name);
		$this->db->join('smart_cards','smart_cards.smart_card_provider='.$this->table_name.'.id');
		
		$this->db->where('smart_cards.lco_id IS NULL');
		if(!empty($stb_type_id) && $stb_type_id != 'null')
			$this->db->where('smart_card_providers.id',$stb_type_id);

		if(!empty($smartcard_number))
			$this->db->like('external_card_number',$smartcard_number,'after');

		$this->db->limit($limit[0], $limit[1]);

		$query = $this->db->get();
		$result['data'] = $query->result_array();


		//Query For Count Rows


		$this->db->select('count(smart_cards.id) AS TOTAL');
		$this->db->from($this->table_name);
		$this->db->join('smart_cards','smart_cards.smart_card_provider='.$this->table_name.'.id');
		$this->db->where('smart_cards.lco_id IS NULL');
		if(!empty($stb_type_id))
			$this->db->where('smart_card_providers.id',$stb_type_id);

		if(!empty($smartcard_number))
			$this->db->like('smart_cards.external_card_number',$smartcard_number,'after');

		$query = $this->db->get();
		$temp = $query->result_array();
		$result['total'] = $temp['0']['TOTAL'];

		return $result;
	}

	public function get_count_provider($created_by)
	{
		$this->db->where('parent_id', $created_by);
		return $this->db->count_all_results($this->table_name);
	}
}