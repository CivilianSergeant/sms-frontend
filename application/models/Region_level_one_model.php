<?php

class Region_level_one_model extends MY_Model
{
	protected $table_name = 'region_level_1';

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

	public function get_regions()
	{
		$query = $this->db->query("CALL GetRegions()");
		$regions = $query->result();

		$tree = array();
		foreach($regions as $i=> $region)
		{

			if ($region->region_l1_code != 0 && $region->region_l2_code == 0
				&& $region->region_l3_code == 0 && $region->region_l4_code == 0) {

				$tree[$region->region_l1_code]['create_form'] = 0;
				$tree[$region->region_l1_code]['childItemName'] = '';
				$tree[$region->region_l1_code]['id'] = $region->id;
				$tree[$region->region_l1_code]['name'] = $region->name;
				$tree[$region->region_l1_code]['region_id'] = $region->region_l1_code.'-'.$region->region_l2_code.
				'-'.$region->region_l3_code.'-'.$region->region_l4_code;
				//$hex = region_code_generator($region->region_l1_code,$region->region_l2_code,$region->region_l3_code,$region->region_l4_code);
				$tree[$region->region_l1_code]['hex'] = $region->hex_code;


			} else if ($region->region_l1_code != 0 && $region->region_l2_code != 0
				&& $region->region_l3_code == 0 && $region->region_l4_code == 0) {

				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['create_form'] = 0;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childItemName'] = '';
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['id'] = $region->id;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['name'] = $region->name;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['region_id'] = $region->region_l1_code.
							'-'.$region->region_l2_code.'-'.$region->region_l3_code.'-'.$region->region_l4_code;
				//$hex = region_code_generator($region->region_l1_code,$region->region_l2_code,$region->region_l3_code,$region->region_l4_code);
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['hex'] = $region->hex_code;

			} else if ($region->region_l1_code != 0 && $region->region_l2_code != 0
				&& $region->region_l3_code != 0 && $region->region_l4_code == 0) {

				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['create_form']=0;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childItemName']='';
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['id']=$region->id;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['name']=$region->name;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['region_id'] = $region->region_l1_code.
							'-'.$region->region_l2_code.'-'.$region->region_l3_code.'-'.$region->region_l4_code;

				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childs'] = array();
				
				//$hex = region_code_generator($region->region_l1_code,$region->region_l2_code,$region->region_l3_code,$region->region_l4_code);
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['hex'] = $region->hex_code;

			} else if ($region->region_l1_code != 0 && $region->region_l2_code != 0
				&& $region->region_l3_code != 0 && $region->region_l4_code != 0) {

				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childs'][$region->region_l4_code]['create_form']=0;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childs'][$region->region_l4_code]['childItemName']='';
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childs'][$region->region_l4_code]['id']=$region->id;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childs'][$region->region_l4_code]['name']=$region->name;
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childs'][$region->region_l4_code]['region_id'] = $region->region_l1_code.
							'-'.$region->region_l2_code.'-'.$region->region_l3_code.'-'.$region->region_l4_code;

				//$hex = region_code_generator($region->region_l1_code,$region->region_l2_code,$region->region_l3_code,$region->region_l4_code);
				$tree[$region->region_l1_code]['childs'][$region->region_l2_code]['childs'][$region->region_l3_code]['childs'][$region->region_l4_code]['hex'] = $region->hex_code;
			}

		}

		return $tree;
	}

	public function get_regions_with_name()
	{
		$sql = "select id,name,GetRegionL1Name(region_l1_code) as region_l1_name ,GetRegionL2Name(region_l2_code) as region_l2_name,GetRegionL3Name(region_l3_code)as region_l3_name,GetRegionL4Name(region_l4_code) as region_l4_name, 
				region_l1_code, region_l2_code,region_l3_code,region_l4_code,hex_code 
				from
				(
					select a.region_l1_name name,a.id,a.region_l1_code,a.region_l2_code,a.region_l3_code,a.region_l4_code,a.hex_code 
					from region_level_1 a
					union
					select b.region_l2_name name,b.id,b.region_l1_code,b.region_l2_code,b.region_l3_code,b.region_l4_code,b.hex_code  
					from region_level_2 b
					union
					select d.region_l3_name name,d.id,d.region_l1_code,d.region_l2_code,d.region_l3_code,d.region_l4_code,d.hex_code 
					from region_level_3 d
					union
					select e.region_l4_name name,e.id,e.region_l1_code,e.region_l2_code,e.region_l3_code,e.region_l4_code,e.hex_code 
					from region_level_4 e
				) c
				order by region_l1_code,region_l2_code,region_l3_code,region_l4_code;";

		$query   = $this->db->query($sql);
		$results = $query->result();
		$regions = array();

		foreach($results as $result)
		{
			$name = ($result->region_l1_name)? $result->region_l1_name: '';
			$name .= ($result->region_l2_name)? '-'.$result->region_l2_name: '';
			$name .= ($result->region_l3_name)? '-'.$result->region_l3_name: '';
			$name .= ($result->region_l4_name)? '-'.$result->region_l4_name: '';
			$regions[] = (object)array(
				'id'   => $result->id,
				'name' => $name,
				'hex'  => $result->hex_code
			);
		}

		return $regions;
	}
}