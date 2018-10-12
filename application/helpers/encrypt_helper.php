<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if( !function_exists('region_code_generator') ) {
	// generate hexadecimal number
	function region_code_generator($L0,$L1,$L2,$L3){
	    $format = '%02X%04X%04X%04X';
		return "0x".sprintf($format, $L0,$L1,$L2,$L3);

	}
}

if( !function_exists('region_code_decode') ){
	function region_code_decode($code){
		$hexCode = str_replace("0x","",$code);
		$hexCode = preg_replace("/((0)|(0{1,3}))/","",$hexCode);
		$len = strlen($hexCode);
		switch($len){
			case 1:
				return $hexCode.'000';
				break;
			case 2:
				return $hexCode.'00';
				break;
			case 3:
				return $hexCode.'0';
				break;
			default:
				return $hexCode;
				break;
		}
	}
}