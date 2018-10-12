<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Process_image
 *
 * @author Himel
 */
class Process_image extends CI_Controller{
    const WEB_POSTER = 1;
    const CHANNEL_LOGO = 2;
    const OTHER = 3;
    const LIVE = 'LIVE';
    const IMG_QUALITY = '0-80';
    const USER='encoder';
    const PASS='75970a8f53d83bcc36c6ffcc99417ff2'; //nexPhotoUploadService@Process_image
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Tmp_image_upload','tmp_image_upload');
        $this->load->model('Default_image_size','default_image_size');
        $this->load->model('Iptv_program_model','Iptv_program');
        $this->load->library('png_compressor');
        
    }
    
    public function index()
    {
        $all = $this->input->post();
        
        if(empty($all)){
            echo 'Sorry! Post request invalid';
            exit;
        }
        
        if(empty($all['user_name'])){
            echo 'Sorry! user name need to access this service';
            exit;
        }
        
        if(empty($all['user_pass'])){
            echo 'Sorry! user pass need to access this service';
            exit;
        }
        
        if($all['user_name'] != self::USER || $all['user_pass'] != self::PASS){
            echo 'Sorry! User or Pass not matched';
            exit;
        }
        
        $imageObj = $this->tmp_image_upload->getImage($all['content_id'],$all['image_type']);
        if(empty($imageObj)){
            echo 'Sorry! No Record found with Content ID: '.$all['content_id']. ' and Image Type:'.$all['image_type'];
            exit;
        }
        
        
          
        // for 16:9
        if($all['image_type'] == self::OTHER){
            
            $program = $this->Iptv_program->find_by_id($all['content_id']);
            $image_quality = (!empty($all['image_quality']))? $all['image_quality'] : (!empty($program->get_attribute('image_quality'))? $program->get_attribute('image_quality') : self::IMG_QUALITY);
            if(!$program->has_attributes()){
                echo 'Sorry! No Program found with Content ID:'.$all['content_id'];
                exit;
            }
            
            if($program->get_attribute('type') == self::LIVE){
                $imageSize = $this->default_image_size->getChannelImageSizes();
            }else{
                $imageSize = $this->default_image_size->getContentImageSizes();
            }
            $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
            $to_path = $PROGRAM_PATH.'/logo';
            $photo_data = array();
            $main_photo_path = $this->_writeMainImage($imageObj, $to_path, $PROGRAM_PATH);
            
            $response = $this->_writeWebLogo($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $image_quality, $photo_data);
            if(is_string($response)){
                echo $response; exit;
            }
            
            $response = $this->_writeSTBLogo($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $image_quality, $photo_data);
            if(is_string($response)){
                echo $response; exit;
            }
            
            $response = $this->_writeMobileLogo($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $image_quality, $photo_data);
            if(is_string($response)){
                echo $response; exit;
            }
            
            $response = $this->_writeMobilePoster($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $image_quality, $photo_data);
            if(is_string($response)){
                echo $response; exit;
            }
            
            $response = $this->_writeStbPoster($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $image_quality, $photo_data);
            if(is_string($response)){
                echo $response; exit;
            }
            
            $response = $this->_writeWebPlayerPoster($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $image_quality, $photo_data);
            if(is_string($response)){
                echo $response; exit;
            }
            
            $response = $this->_writeMobilePlayerPoster($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $image_quality, $photo_data);
            if(is_string($response)){
                echo $response; exit;
            }
            
            
            $this->Iptv_program->save($photo_data,$program->get_attribute('id'));
            $this->tmp_image_upload->delete($imageObj->id);
            echo 'Successfully All image saved';
            exit;
        }
        
        // for web poster
        if($all['image_type'] == self::WEB_POSTER){
            
            $program = $this->Iptv_program->find_by_id($all['content_id']);
            $image_quality = (!empty($all['image_quality']))? $all['image_quality'] : (!empty($program->get_attribute('image_quality'))? $program->get_attribute('image_quality') : self::IMG_QUALITY);
            if(!$program->has_attributes()){
                echo 'Sorry! No Program found with Content ID:'.$all['content_id'];
                exit;
            }
           
            if($program->get_attribute('type') == self::LIVE){
                $imageSize = $this->default_image_size->getChannelImageSizes();
            }else{
                $imageSize = $this->default_image_size->getContentImageSizes();
            }
            
            $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
            $to_path = $PROGRAM_PATH.'/logo';
            $photo_data = array();
            $main_photo_path = $this->_writeMainImage($imageObj, $to_path, $PROGRAM_PATH);
            $response = $this->_writeWebPoster($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $program->get_attribute('image_quality'), $photo_data);
            $this->Iptv_program->save($photo_data,$program->get_attribute('id'));
            $this->tmp_image_upload->delete($imageObj->id);
            echo 'Successfully Web Poster Saved';
            exit;
        }
        
        // for channel logo
        if($all['image_type'] == self::CHANNEL_LOGO){
            $program = $this->Iptv_program->find_by_id($all['content_id']);
            $image_quality = (!empty($all['image_quality']))? $all['image_quality'] : (!empty($program->get_attribute('image_quality'))? $program->get_attribute('image_quality') : self::IMG_QUALITY);
            if(!$program->has_attributes()){
                echo 'Sorry! No Program found with Content ID:'.$all['content_id'];
                exit;
            }
            
            if($program->get_attribute('type') == self::LIVE){
                $message = 'Successfully Channel Logo Saved';
                $imageSize = $this->default_image_size->getChannelImageSizes();
            }else{
                $message = 'Successfully Mobile Logo Saved';
                $imageSize = $this->default_image_size->getContentImageSizes();
            }
            
            $PROGRAM_PATH = PROGRAM_PATH.$program->get_attribute('id');
            $to_path = $PROGRAM_PATH.'/logo';
            $photo_data = array();
            $main_photo_path = $this->_writeMainImage($imageObj, $to_path, $PROGRAM_PATH);
            $response = $this->_writeMobileLogo($program, $PROGRAM_PATH, $main_photo_path, $imageSize, $program->get_attribute('image_quality'), $photo_data);
            $this->Iptv_program->save($photo_data,$program->get_attribute('id'));
            $this->tmp_image_upload->delete($imageObj->id);
            echo $message;
            exit;
        }
        
        
        
    }
    
    private function _writeMainImage($imageObj,$to_path,$PROGRAM_PATH)
    {
        $main_photo_path = $to_path.'/main.png';

        if(file_exists($PROGRAM_PATH)){
            @mkdir($to_path,0777);
        }else{
            @mkdir($to_path,0777,true);
        }
        if(file_put_contents($main_photo_path,$imageObj->image)){
            return $main_photo_path;
        }else{
            return null;
        }
    }
    
    private function _writeWebLogo($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['WEB_LOGO']['width'];
        $h = $imageSize['advance']['WEB_LOGO']['height'];

        $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
        if(file_exists($PROGRAM_PATH)){
            @mkdir($to_path,0777);
        }else{
            @mkdir($to_path,0777,true);
        }

        $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        $final_path = $to_path.'/web_logo_'.str_replace(array(" ","0."),"",microtime()).'.png';

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            return 'Sorry! '.$output;
        }
        $old_photo  = $program->get_attribute('logo_web_url');
        @unlink($old_photo);
        $photo_data['logo_web_url'] = $final_path;
        return;
    }
    
    public function _writeSTBLogo($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['STB_LOGO']['width'];
        $h = $imageSize['advance']['STB_LOGO']['height'];

        $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
        if(file_exists($PROGRAM_PATH)){
            @mkdir($to_path,0777);
        }else{
            @mkdir($to_path,0777,true);
        }

        $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        $final_path = $to_path.'/stb_logo_'.str_replace(array(" ","0."),"",microtime()).'.png';

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo $output;
        }

        $old_photo  = $program->get_attribute('logo_stb_url');
        @unlink($old_photo);
        $photo_data['logo_stb_url'] = $final_path;
        return;
    }
    
    public function _writeMobileLogo($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['MOBILE_LOGO']['width'];
        $h = $imageSize['advance']['MOBILE_LOGO']['height'];

        $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
        if(file_exists($PROGRAM_PATH)){
            @mkdir($to_path,0777);
        }else{
            @mkdir($to_path,0777,true);
        }

        $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        if($program->get_attribute('type') == self::LIVE){
            $final_path = $to_path.'/channel_logo_'.str_replace(array(" ","0."),"",microtime()).'.png';
        }else{
            $final_path = $to_path.'/mobile_logo_'.str_replace(array(" ","0."),"",microtime()).'.png';
        }

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo $output;
        }
        
        if($program->get_attribute('type') == self::LIVE){
            $old_photo  = $program->get_attribute('channel_logo');
            @unlink($old_photo);
            $photo_data['channel_logo'] = $final_path;
        }else{
            $old_photo  = $program->get_attribute('logo_mobile_url');
            @unlink($old_photo);
            $photo_data['logo_mobile_url'] = $final_path;
        }
        
        return;

        
    }
    
    public function _writeMobilePoster($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['MOBILE_POSTER']['width'];
        $h = $imageSize['advance']['MOBILE_POSTER']['height'];

        $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
        if(file_exists($PROGRAM_PATH)){
            @mkdir($to_path,0777);
        }else{
            @mkdir($to_path,0777,true);
        }

        $temp_path  = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        $final_path = $to_path.'/mobile_poster_'.str_replace(array(" ","0."),"",microtime()).'.png';

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo $output;
        }

        $old_photo  = $program->get_attribute('poster_url_mobile');
        @unlink($old_photo);
        $photo_data['poster_url_mobile'] = $final_path;
        
        return;
    }
    
    public function _writeWebPoster($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['WEB_POSTER']['width'];
        $h = $imageSize['advance']['WEB_POSTER']['height'];

        $to_path = $PROGRAM_PATH.'/logo/'.$w.'x'.$h;
        if(file_exists($PROGRAM_PATH)){
            @mkdir($to_path,0777);
        }else{
            @mkdir($to_path,0777,true);
        }

        $temp_path  = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        $final_path = $to_path.'/web_poster_'.str_replace(array(" ","0."),"",microtime()).'.png';

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo $output;
        }

        $old_photo  = $program->get_attribute('poster_url_web');
        @unlink($old_photo);
        $photo_data['poster_url_web'] = $final_path;
        
        return;
    }
    
    public function _writeStbPoster($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['STB_POSTER']['width'];
        $h = $imageSize['advance']['STB_POSTER']['height'];

        $to_path = $PROGRAM_PATH . '/logo/' . $w . 'x' . $h;
        if (file_exists($PROGRAM_PATH)) {
            @mkdir($to_path, 0777);
        } else {
            @mkdir($to_path, 0777, true);
        }

        $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        $final_path = $to_path . '/stb_poster_' . str_replace(array(" ","0."),"",microtime()) . '.png';

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo json_encode(array('status'=>400,'warning_messages'=>$output));
            exit;
        }

        $old_photo = $program->get_attribute('poster_url_stb');
        //file_put_contents('image_delete_log.txt',$old_photo."\n",FILE_APPEND);
        @unlink($old_photo);
        $photo_data['poster_url_stb'] = $final_path;
        return;
    }
    
    private function _writeWebPlayerPoster($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['WEB_PLAYER_POSTER']['width'];
        $h = $imageSize['advance']['WEB_PLAYER_POSTER']['height'];

        $to_path = $PROGRAM_PATH . '/logo/' . $w . 'x' . $h;
        if (@file_exists($PROGRAM_PATH)) {
            @mkdir($to_path, 0777);
        } else {
            @mkdir($to_path, 0777, true);
        }

        $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        $final_path = $to_path . '/web_pposter_' . str_replace(array(" ","0."),"",microtime()) . '.png';

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo json_encode(array('status'=>400,'warning_messages'=>$output));
            exit;
        }

        $old_photo = $program->get_attribute('player_poster_web');
        //file_put_contents('image_delete_log.txt',$old_photo."\n",FILE_APPEND);
        @unlink($old_photo);
        $photo_data['player_poster_web'] = $final_path;
        return;
    }
    
    private function _writeMobilePlayerPoster($program,$PROGRAM_PATH,$main_photo_path,$imageSize,$image_quality,&$photo_data)
    {
        $w = $imageSize['advance']['MOBILE_PLAYER_POSTER']['width'];
        $h = $imageSize['advance']['MOBILE_PLAYER_POSTER']['height'];

        $to_path = $PROGRAM_PATH . '/logo/' . $w . 'x' . $h;
        if (@file_exists($PROGRAM_PATH)) {
            @mkdir($to_path, 0777);
        } else {
            @mkdir($to_path, 0777, true);
        }

        $temp_path = $PROGRAM_PATH.'/logo/main-'.$w.'x'.$h.'.png';
        $final_path = $to_path . '/mobile_player_poster_' . str_replace(array(" ","0."),"",microtime()) . '.png';

        $compressResponse = $this->png_compressor
            ->compress_png($main_photo_path,$temp_path,$final_path,$w,$h,$image_quality);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo json_encode(array('status'=>400,'warning_messages'=>$output));
            exit;
        }

        $old_photo = $program->get_attribute('player_poster_mobile');
        //file_put_contents('image_delete_log.txt',$old_photo."\n",FILE_APPEND);
        @unlink($old_photo);
        $photo_data['player_poster_mobile'] = $final_path;
        return;

    }
}


