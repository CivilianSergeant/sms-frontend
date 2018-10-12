<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forget_password extends CI_Controller
{
	protected $user_session;

    public function __construct()
    {
        parent::__construct();
        $this->theme->set_theme('katniss');
        $this->theme->set_layout('main');
    }

    public function index() 
    {
        $this->theme->set_title('LCO User Creation - Application')
                ->add_style('kendo/css/kendo.common-material.min.css')
                ->add_style('kendo/css/kendo.material.min.css')
                ->add_style('component.css')
                ->add_script('controllers/lco/lco.js');
       
       /* $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();*/
        $this->theme->set_view('forget/forget_password', true);
    }

    

}