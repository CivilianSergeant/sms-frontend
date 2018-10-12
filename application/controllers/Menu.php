<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Menu extends BaseController 
{
	protected $user_session;
	protected $user_type;
    protected $user_id;
    protected $created_by;

    const LCO_UPPER='LCO';
    const LCO_LOWER='lco';
    const MSO_UPPER='MSO';
    const MSO_LOWER='mso';
    const ADMIN = 'admin';
    const STAFF = 'staff';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services');
		$this->theme->set_theme('katniss');
		$this->theme->set_layout('main');

		$this->user_type = strtolower($this->user_session->user_type);
        $this->user_id = $this->user_session->id;
        $this->created_by = $this->user_session->created_by;

	}

}