<?php

class Category_programs_model extends MY_Model {

    protected $table_name = "app_home_page_category_programs";

    public function __construct() {
        parent::__construct();
    }

    public function get_category_programs_cat_id($cat_id) {
        $this->db->select('app_home_page_category_programs.id, app_home_page_category_programs.order_index, iptv_programs.program_name, iptv_programs.id as content_id');
        $this->db->from($this->table_name);
        $this->db->join('iptv_programs', 'app_home_page_category_programs.content_id = iptv_programs.id', 'left');
        $this->db->where('app_home_page_category_programs.category_id', $cat_id);
        $this->db->order_by('app_home_page_category_programs.order_index', 'asc');
        $query = $this->db->get();

        return $query->result();
    }

    public function update_program($data, $id) {
        $this->db->where('id', $id);
        $res = $this->db->update($this->table_name, $data);
    }

    public function delete_category_programs($cat_id) {
        $this->db->where('category_id', $cat_id);
        $this->db->delete($this->table_name);
    }

    public function category_programs_duplicate_check($catId, $content_id) {

        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('category_id', $catId);
        $this->db->where('content_id', $content_id);
        $query = $this->db->get();

        return $query->row();
    }

}
