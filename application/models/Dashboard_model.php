<?php

class Dashboard_model extends MY_Model
{
	protected $table_name = "iptv_packages";
	protected $child      = "iptv_programs";
	protected $child_lco  = "lco_profiles";
	protected $child_user = "users";

	public function __construct()
	{
		parent::__construct();

		$this->load->model('package_program_model','package_program');
	}


	

	/**
	* Assign Program to Package
	* @param $program
	* @param $package_id
	* @param created_by
	* @return boolean 
	*/
	public function total_program()
	{
		$this->db->select('count(id) as totalprogram')->from($this->child);
		$query=$this->db->get();
		return $query->row();
	}
	public function active_program()
	{
		$this->db->select('count(id) as activeprogram')->from($this->child)->where('is_active',1);
		$query=$this->db->get();
		return $query->row();
	}
	public function deactive_program()
	{
		$this->db->select('count(id) as deactiveprogram')->from($this->child)->where('is_active',0);
		$query= $this->db->get();
		return $query->row();
	}
	public function total_package()
	{
		$this->db->select('count(id) as totalpackage')->from($this->table_name);
		$query= $this->db->get();
		return $query->row();
	}
	public function active_package()
	{
		$this->db->select('count(id) as activepackage')->from($this->table_name)->where('is_active',1);
		$query= $this->db->get();
		return $query->row();
	}
	public function deactive_package()
	{
		$this->db->select('count(id) as deactivepackage')->from($this->table_name)->where('is_active',0);
		$query= $this->db->get();
		return $query->row();
	}
	public function total_lco()
	{
		$this->db->select('count(id) as totalcount')->from($this->child_lco);
		$query= $this->db->get();
		return $query->row();
	}
	public function msostaff()
	{
		$this->db->select('count(id) as totalstaff')->from($this->child_user)->where('user_type','MSO');
		$query= $this->db->get();
		return $query->row();
	}
	public function lcostaff($id)
	{
		$this->db->select('count(id) as totalstaff')->from($this->child_user)->where(array('user_type'=>'Subscriber','created_by'=>$id));
		$query= $this->db->get();
		return $query->row();
	}
	public function lcostaffactive($id)
	{
		$this->db->select('count(id) as staffactive')->from($this->child_user)->where(array('user_type'=>'Subscriber','user_status'=>'1','created_by'=>$id));
		$query= $this->db->get();
		return $query->row();
	}
		public function lcostaffdeactive($id)
	{
		$this->db->select('count(id) as staffdeactive')->from($this->child_user)->where(array('user_type'=>'Subscriber','user_status'=>'0','created_by'=>$id));
		$query= $this->db->get();
		return $query->row();
	}
	
}