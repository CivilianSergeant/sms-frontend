<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/22/2016
 * Time: 12:22 PM
 */
class Png_compressor
{
    protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
    }

    /**
     * Optimizes PNG file with pngquant 1.8 or later (reduces file size of 24-bit/32-bit PNG images).
     *
     * You need to install pngquant 1.8 on the server (ancient version 1.0 won't work).
     * There's package for Debian/Ubuntu and RPM for other distributions on http://pngquant.org
     *
     * @param $path_to_png_file string - path to any PNG file, e.g. $_FILE['file']['tmp_name']
     * @param $max_quality int - conversion quality, useful values from 60 to 100 (smaller number = smaller file)
     * @return string - content of PNG file after conversion
     */
    public function compress_png($path_to_png_file, $temp_file_path,$final_path,$w,$h,$imageQuality="50-80")
    {
        if (!file_exists($path_to_png_file)) {
            throw new Exception("File does not exist: $path_to_png_file");
        }

        // guarantee that quality won't be worse than that.
        //$min_quality = $imageQuality;

        // '-' makes it use stdout, required to save to $compressed_png_content variable
        // '<' makes it read from the given file path
        // escapeshellarg() makes this safe to use with any path
        //$compressed_png_content = shell_exec("C:\\Users\\user\\Downloads\\PNGoo.0.1.1\\libs\\pngquant --quality=$min_quality-$max_quality - < ".escapeshellarg(    $path_to_png_file));
        //$compressed_png_content = shell_exec("pngquant --quality=$min_quality-$max_quality - < ".escapeshellarg(    $path_to_png_file));
        try {
            $command = "java -jar imageProcessor.jar '" . $path_to_png_file . "' '" . $temp_file_path . "' '" . $final_path. "' ".$w." ".$h." '".$imageQuality."'";
            exec($command,$output,$response);
            return array('command'=>$command,'output'=>$output,'response'=>$response);
        }catch(Exception $ex){
            throw new Exception($ex->getMessage());
        }

    }

}