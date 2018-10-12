<?php
/**
 * Created by PhpStorm.
 * User: Office
 * Date: 11/2/2015
 * Time: 1:20 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if( !function_exists('datetime_to_array') ) {

	/**
	* Get Date to Array
	* @param $str_datetime should be Y-m-d H:i:s format
	*/
	function datetime_to_array($str_datetime)
	{
		$datetime = explode(" ",$str_datetime);
		$date     = explode("-",$datetime[0]);
		$time     = explode(":",$datetime[1]);
		$result = array(
			'year'  => $date[0],
			'month' => $date[1],
			'day'   => $date[2],
			'hour'  => $time[0],
			'minute'=> $time[1],
			'second'=> $time[2]
		);
		return $result;
	}

}