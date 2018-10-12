<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/5/2016
 * Time: 1:22 PM
 */
class Iptv_package_program_model extends MY_Model
{
    protected $table_name = 'iptv_package_programs';

    public function __construct()
    {
        parent::__construct();
    }

    public function delete_programs_by_package($id)
    {
        $this->db->where("package_id",$id);
        $this->db->delete($this->table_name);

    }

    public function get_programs_by_package($id)
    {
        $this->db->where("package_id",$id);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
}