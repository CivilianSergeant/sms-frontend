<?php
/**
 * Created by PhpStorm.
 * User: Office
 * Date: 11/2/2015
 * Time: 1:20 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');


function test($data,$flag=1,$msg='',$die=1){
    echo '<br/>'.$msg;
    echo '<pre>';
    if($flag){
        print_r($data);
    }else{
        var_dump($data);
    }
    if($die)
        die();
}

if(!function_exists('array_key_sort')){

    function array_key_sort($assoc_array_data,$key_name,$order=null)
    {
        if(empty($assoc_array_data)){
            return $assoc_array_data;
        }
        $sort = array();
        foreach($assoc_array_data as $data){
            foreach($data as $key=>$value){
                if(!isset($sort[$key])){
                    $sort[$key] = array();
                }
                $sort[$key][] = $value;
            }
        }


        if(!empty($order)){
            array_multisort($sort[$key_name],$order,$assoc_array_data);
        }else{
            array_multisort($sort[$key_name],SORT_ASC,$assoc_array_data);
        }

        return $assoc_array_data;
    }
}

if(!function_exists('card_mask')){
    function card_mask()
    {
        $mark_str = '';
        for($i=0; $i<16;$i++){
            $mark_str .= 'X';
        }
        return $mark_str;
    }
}

if( !function_exists('getInetAddress') ) {
    function getInetAddress()
    {
        ob_start();
        system('ifconfig | grep "inet"');
        $ipAddress = ob_get_contents();
        ob_clean();
        if(empty($ipAddress)){
            return null;
        }
        $ipAddress = explode("Bcast:",$ipAddress);
        $ipAddress = substr($ipAddress[0],(strpos($ipAddress[0],":")+1));
        return $ipAddress;
    }

}

if(!function_exists('getHardwareAddress')){
    function getHardwareAddress(){
        ob_start();
        system('ifconfig | grep "HWaddr "');
        $macAddress = ob_get_contents();
        ob_clean();
        if(empty($macAddress)){
            return null;
        }
        $macAddress = str_replace(":","-",substr($macAddress,strrpos(trim($macAddress)," ")));
        return $macAddress;
    }
}

if(!function_exists('getLicence')){
    function getLicence(){
        $ipAddress = getInetAddress();
        $hwAddress = getHardwareAddress();
        $licence = '';
        for($i=0; $i<5; $i++){
            $licence .= base64_encode($ipAddress.$hwAddress);
        }
        return $licence;
    }
}




if( !function_exists('error_get_last') ) {
    set_error_handler(
        create_function(
            '$errno,$errstr,$errfile,$errline,$errcontext',
            '
                global $__error_get_last_retval__;
                $__error_get_last_retval__ = array(
                    \'type\'        => $errno,
                    \'message\'        => $errstr,
                    \'file\'        => $errfile,
                    \'line\'        => $errline
                );
                return false;
            '
        )
    );

    function error_get_last() {
        global $__error_get_last_retval__;
        if( !isset($__error_get_last_retval__) ) {
            return null;
        }
        return $__error_get_last_retval__;
    }
}

if( !function_exists('resize_image')){
    function resize_image($file, $w, $h, $ratio=true) {
        list($width, $height) = getimagesize($file);
        $dh = ($w/16*9);

        if($ratio && floor($dh) != $h){
            return -1;
        }

        if($width < $w){
            return -2;
        }
        if($height < $h){
            return -3;
        }
        /*$r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }*/
        $src = imagecreatefrompng($file);
        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        return $dst;
    }
}
