<?php

class Notification_model extends MY_Model
{
	protected $table_name="notifications";

	public function __construct()
	{
		parent::__construct();
	}
	public function set_welcome_messasge($id,$type)
	{
		//$this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean|is_unique[notifications.title]');

		$mso_id=null;
		$lco_id=null;
		if($id){
			if ($type == 'MSO') {
				$mso_id=$id;
			}
			if ($type =='LCO') {
				$lco_id=$id;
			}
			$data = array(
				'title'=>'Welcome to Plaas SMS',
				'description'=>'Welcome to Plaas Subscriber Management System.',
				'mso_id'=>$mso_id,
				'lco_id'=>$lco_id
				);
			$this->save($data);
		}		
	}

	public function set_unassigned_program_notification($id)
	{	$count = $this->program->count_unassigned_program();
		if($count){
			$data = array(
				'mso_id' => $id,
				'title'  => 'System has un-assigned programs',
				'description' => 'Your System has '.$count.' un-assigned programs'
			);
			$this->notification->save($data);
		}
		
	}

	public function get_popup_notifications($user_type=null,$id=null,$limit=5)
	{
		if(!empty($user_type) && !empty($id)){
			$this->db->where(strtolower($user_type).'_id',$id);
			$this->db->where('is_read_'.strtolower($user_type),'0');
		}

		$this->db->order_by('id','desc');
		$this->db->limit($limit);
		$query= $this->db->get($this->table_name);
		
		return $query->result();
	}

	/*public function count_msg()
	{
		$this->db->select('count(title) as counttitle')->from($this->table_name);
		$query=$this->db->get();
		return $query->row();
	}*/

	public function get_all_notifications($user_type,$id,$limit=0,$offset=0)
	{
		$this->db->where(strtolower($user_type).'_id',$id);
		$this->db->where('is_read_'.strtolower($user_type),'0');
		$this->db->order_By('id','desc');
		
		if(!empty($limit)){
			$this->db->limit($limit,$offset);	
		}
		
		$query=$this->db->get($this->table_name);
		
		return $query->result();
	}

	/**
	 * @param int $lco_id lco admin who will receieve message
	 * @param string $title title of notification
	 * @param string $desc description of notification
	 * @param int $created_by creator user_id
	 * @return int inserted id of notification
	 */
	public function save_notification($lco_id,$title,$desc,$created_by)
	{
		$data = array(
			'title'=>$title,
			'description'=>$desc,
			'mso_id'=>1,
			'lco_id'=>$lco_id,
			'is_read_mso'=>0,
			'is_read_lco'=>0,
			'created_by' =>$created_by
		);
		
		return $this->save($data);
	}

	/**
	 * @param int $lco_id lco admin who will receieve message
	 * @param string $title title of notification
	 * @param string $desc description of notification
	 * @param int $created_by creator user_id
	 * @return int inserted id of notification
	 */
	public function save_subscriber_notification($parent_id,$self_id,$title,$desc,$created_by)
	{
		$data = array(
				'title'=>$title,
				'description'=>$desc,
				'mso_id'=>1,
				'lco_id'=>$parent_id,
				'subscriber_id' => $self_id,
				'is_read_mso'=>0,
				'is_read_lco'=>0,
				'is_read_subscriber'=>0,
				'created_by' =>$created_by
		);

		return $this->save($data);
	}

	public function get_report($from_date,$to_date,$limit=0,$offset=0,$filter=null,$sort=null)
	{
		$this->db->select('title,created_at, GetOperatorName(parent_id) as action_performed_by');
		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}

		if(!empty($from_date) && !empty($to_date)){

			$this->db->where("created_at BETWEEN '".$from_date."%' AND '".$to_date."%'");
		}

		if(!empty($sort)){
			foreach($sort as $s){
				$this->db->order_by($s['field'],$s['dir']);
			}
		}

		if(!empty($limit))
		{
			$this->db->limit($limit,$offset);
		}

		$q = $this->db->get($this->table_name);

		return $q->result();
	}

	public function get_count_report($from_date,$to_date,$filter)
	{
		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
		}
		if(!empty($from_date) && !empty($to_date))
		{

			$this->db->where("created_at BETWEEN '".$from_date."%' AND '".$to_date."%'");
		}

		return $this->db->count_all_results($this->table_name);

	}


	public function get_count_notifications($user_type,$id){
		$this->db->where(strtolower($user_type).'_id',$id);
		$this->db->where('is_read_'.strtolower($user_type),'0');
		return $this->db->count_all_results($this->table_name);
	}

	

}