<?php

class App_category_model extends MY_Model
{
	protected $table_name="app_home_page_categories";

	public function __construct()
	{
		parent::__construct();

	}

	public function get_all_app_categories($limit=0,$offset=0,$filter=null,$sort=null,$count=false)
	{
		$this->db->select('*');
		$this->db->from($this->table_name);

		if(!empty($filter)){
			foreach($filter['filters'] as $column){
				if($column['operator']=="startswith"){

					$this->db->like($column['field'],$column['value'],'after');
				}else{

					$this->db->where($column['field'],$column['value']);
				}
			}
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
		if($count){
			return $this->db->count_all_results();
		}
                $this->db->order_by('order_index', 'asc');
		$query = $this->db->get();
		return $query->result();
	}
        
        public function get_count_all_categories(){
            $this->db->from($this->table_name);
            $query = $this->db->get();
            return $rowcount = $query->num_rows();
        }
        
        public function get_category_data_by_id($id){
            $this->db->select('*');
            $this->db->from($this->table_name);
            $this->db->where('id', $id);
            $query = $this->db->get();
            
            return $query->row();
        }
        
        
    public function get_iptv_programs($type)
    {
        $this->db->select('*');
        $this->db->where('is_remove',0);
        $this->db->where('type', $type);
        $this->db->order_by('program_name', 'asc');
        return $this->db->get('iptv_programs')->result();
    }
    
    public function search_iptv_programs($search_key, $type)
    {
        $this->db->select('*');
        $this->db->where('is_remove',0);
        $this->db->where('type', $type);
        $this->db->like('program_name', $search_key);
        $this->db->order_by('program_name', 'asc');
        return $this->db->get('iptv_programs')->result();
    }
    
    public function cat_duplicate_check($cat_name)
    {
        $this->db->select('id');
        $this->db->where('category_name', $cat_name);
        return $this->db->get($this->table_name)->row();
    }
        

}