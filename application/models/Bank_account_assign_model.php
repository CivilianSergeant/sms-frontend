<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/9/2016
 * Time: 12:39 PM
 */
class Bank_account_assign_model extends MY_Model
{
    protected $table_name = "bank_accounts_assign";

    public function __construct()
    {
        parent::__construct();
    }

    public function is_shared($account_id){
        $this->db->where('bank_account_id',$account_id);
        return $this->db->count_all_results($this->table_name);
    }
}