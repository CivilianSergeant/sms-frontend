<?php


class Billing_address_model extends MY_Model
{
	
	protected $table_name = "billing_addresses";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_billing_address($token)
	{
		$select = 'billing_addresses.id as billing_address_id,billing_addresses.user_id,billing_addresses.is_same_as_profile,
		billing_addresses.name,billing_addresses.email,billing_addresses.address1,
		billing_addresses.address2,billing_addresses.country_id,billing_addresses.division_id,
		billing_addresses.district_id,billing_addresses.area_id,billing_addresses.sub_area_id,
		billing_addresses.road_id,billing_addresses.contact,billing_addresses.is_same_as_contact,
		billing_addresses.billing_contact,countries.country_name,divisions.division_name,districts.district_name,
		areas.area_name,sub_areas.sub_area_name,roads.road_name';
		$this->db->select($select);
		$this->db->from($this->table_name);
		$this->db->join('countries','countries.id = billing_addresses.country_id','left');
		$this->db->join('divisions','divisions.id = billing_addresses.division_id','left');
		$this->db->join('districts','districts.id = billing_addresses.district_id','left');
		$this->db->join('areas','areas.id = billing_addresses.area_id','left');
		$this->db->join('sub_areas','sub_areas.id = billing_addresses.sub_area_id','left');
		$this->db->join('roads','roads.id = billing_addresses.road_id','left');
		$this->db->where('billing_addresses.token',$token);
		$query = $this->db->get();
		return $query->row();
	}

	

	
}