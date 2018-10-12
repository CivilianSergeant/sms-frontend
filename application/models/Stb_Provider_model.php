<?php

class Stb_Provider_model extends MY_Model
{

	protected $table_name="stb_providers";
	

	public function __construct()
	{
		parent::__construct();
	}

	public function creat_stb_provider($data)
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

	public function get_all_stb_providers()
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

	/**
	* @author Himel
	* Get all set-top-boxes that is unassigned to lco 
	* @param $stb_type_id
	* @param $stb_number
	*/
	public function get_cards($stb_type_id,$stb_number,$limit)
	{

		// getting data
		$this->db->select('stb_providers.id as provider_id, set_top_boxes.id as stb_box_id,stb_provider as supplier, description, external_card_number,DATE_FORMAT(set_top_boxes.created_at,"%b %d %Y") as created_date');
		$this->db->from($this->table_name);
		$this->db->join('set_top_boxes','set_top_boxes.stb_card_provider='.$this->table_name.'.id');
		$this->db->where('set_top_boxes.lco_id IS NULL');
		if(!empty($stb_type_id) && $stb_type_id != 'null')
			$this->db->where('stb_providers.id',$stb_type_id);

		if(!empty($stb_number))
			$this->db->like('external_card_number',$stb_number,'after');

		$this->db->limit($limit[0], $limit[1]);
		
		$query = $this->db->get();
		$result['data'] = $query->result_array();


		// getting total count. before end of development it will be replace with more flexible code.

		$this->db->select('count(set_top_boxes.id) AS TOTAL');
		$this->db->from($this->table_name);
		$this->db->join('set_top_boxes','set_top_boxes.stb_card_provider='.$this->table_name.'.id');
		$this->db->where('set_top_boxes.lco_id IS NULL');
		if(!empty($stb_type_id))
			$this->db->where('stb_providers.id',$stb_type_id);

		if(!empty($stb_number))
			$this->db->like('set_top_boxes.external_card_number',$stb_number,'after');

		$query = $this->db->get();
		$temp = $query->result_array();
		$result['total'] = $temp['0']['TOTAL'];

		// print_r($result);exit;
		return $result;
	}

	/**
	 * @author Himel
	 * Get all set-top-boxes that is unassigned to lco
	 * @param $stb_type_id
	 * @param $stb_number
	 */
	public function get_devices($stb_number,$limit)
	{

		// getting data
		$this->db->select('devices.id as stb_box_id,price,device_number,DATE_FORMAT(devices.created_at,"%b %d %Y") as created_date');
		$this->db->from('devices');
		//$this->db->join('set_top_boxes','set_top_boxes.stb_card_provider='.$this->table_name.'.id');
		$this->db->where('devices.lco_id IS NULL');


		if(!empty($stb_number))
			$this->db->like('device_number',$stb_number,'after');

		$this->db->limit($limit[0], $limit[1]);

		$query = $this->db->get();
		$result['data'] = $query->result_array();


		// getting total count. before end of development it will be replace with more flexible code.

		$this->db->select('count(devices.id) AS TOTAL');
		$this->db->from('devices');
		//$this->db->join('devices','devices.stb_card_provider='.$this->table_name.'.id');
		$this->db->where('devices.lco_id IS NULL');
		/*if(!empty($stb_type_id))
			$this->db->where('stb_providers.id',$stb_type_id);*/

		if(!empty($stb_number))
			$this->db->like('devices.device_number',$stb_number,'after');

		$query = $this->db->get();
		$temp = $query->result_array();
		$result['total'] = $temp['0']['TOTAL'];

		// print_r($result);exit;
		return $result;
	}

	public function get_count_provider($created_by)
	{
		$this->db->where('parent_id', $created_by);
		return $this->db->count_all_results($this->table_name);
	}

}