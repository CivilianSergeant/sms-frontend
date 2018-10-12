<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_captcha{

	protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->config->load('cas');
    }

    public function get_captcha_image(){

    	
    }

    public function get_captcha_code($strVal)
	{
	    for($i=0; $i<6; $i++){
	       $a[$i] = substr($strVal,$i,1);
	    }

	    $res = $a[0].$a[3].$a[1].$a[4].$a[2].$a[5];
	    
		return $res;
	}

	public function generate_random($length=6)
	{
		$_rand_src = array(
			array(48,57) //digits
			, array(97,122) //lowercase chars
			, array(65,90) //uppercase chars
		);
		
		srand ((double) microtime() * 1000000);
		$random_string = "";
		
		for($i=0;$i<$length;$i++){
			$i1=rand(0,sizeof($_rand_src)-1);
			$random_string .= chr(rand($_rand_src[$i1][0],$_rand_src[$i1][1]));
		}
		
		return $random_string;
	}
}