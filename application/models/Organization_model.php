<?php

class Organization_model extends MY_Model
{

	protected $table_name="organization_info";

	public function __construct()
	{
		parent::__construct();
	}


	public function get_administrators()
	{
		$query= $this->db->get($this->table_name);
		$result = $query->row();
		if(!empty($result))
		{
			$str = '';

			$admin_1 = $result->administrator1;
			$admin_2 = $result->administrator2;
			$phone1  = $result->phone1;
			$phone2  = $result->phone2;
			$is_show = $result->is_show;

			if(!empty($admin_1)){
				$str .= $admin_1;
				if($is_show)
				{
					$str .= ' ['.$phone1.']';
				}
			}

			if(!empty($admin_2)){
				$str .= ',  '.$admin_2;
				if($is_show)
				{
					$str .= ' ['.$phone2.']';
				}
			}

		}

		return $str;
	}
	

	
}