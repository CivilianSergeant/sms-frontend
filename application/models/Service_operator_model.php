<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/8/2016
 * Time: 4:31 PM
 */
class Service_operator_model extends MY_Model
{
    protected $table_name = "service_operators";

    public function get_all()
    {
        $this->db->group_by('telco_id');
        $q = $this->db->get($this->table_name);
        return $q->result();
    }
}