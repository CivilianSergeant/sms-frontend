<?php
/**
 * Created by PhpStorm.
 * User: Office
 * Date: 11/3/2015
 * Time: 10:00 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('set_image'))
{
	function set_image($theme,$image,$flag=false)
	{
		if($flag)
			return $theme.$image;
		else
			echo $theme.$image;
	}
}