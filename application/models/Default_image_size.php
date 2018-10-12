<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/26/2016
 * Time: 3:09 PM
 */
class Default_image_size extends MY_Model
{
    protected $table_name = "default_image_sizes";

    public function getChannelImageSizes()
    {

        $this->db->where('type','CHANNEL');
        $this->db->or_where('type','BOTH');
        $cursor = $this->db->get($this->table_name);
        $results = $cursor->result();
        $channelLogoSize = null;
        $watermark = null;
        $minimum = null;
        if(!empty($results)){
            foreach($results as $i=> $result){
//                if($result->name == 'MINIMUM_SIZE'){
//                    $minimum = $results[$i];
//                    unset($results[$i]);
//                }else if($result->name == 'CHANNEL_LOGO'){
//                    $channelLogoSize = $results[$i];
//                    unset($results[$i]);
//                }else if($result->name == 'WATERMARK'){
//                    $watermark = $results[$i];
//                    unset($results[$i]);
//                }else{
//                    $adv[$result->name]['width'] = $result->width;
//                    $adv[$result->name]['height'] = $result->height;
//                }
                
                if($result->name == 'MINIMUM_SIZE'){
                    $minimum = $results[$i];
                    unset($results[$i]);
                }else if($result->name == 'WATERMARK'){
                    $watermark = $results[$i];
                    unset($results[$i]);
                }else{
                    $adv[$result->name]['width'] = $result->width;
                    $adv[$result->name]['height'] = $result->height;
                }
            }
        }
        return array('minimum'=>array('width'=>$minimum->width,'height'=>$minimum->height),
            'advance'=>$adv,
//            'channel_logo'=>array('width'=>$channelLogoSize->width,'height'=>$channelLogoSize->height),
                'watermark'=>array('width'=>$watermark->width,'height'=>$watermark->height)
            );

    }

    public function getContentImageSizes()
    {
        $this->db->where('type','CONTENT');
        $this->db->or_where('type','BOTH');
        $cursor = $this->db->get($this->table_name);
        $results = $cursor->result();
        $watermark = null;
        $minimum = null;
        if(!empty($results)){
            foreach($results as $i=> $result){
                if($result->name == 'MINIMUM_SIZE'){
                    $minimum = $results[$i];
                    unset($results[$i]);
                }else if($result->name == 'WATERMARK'){
                    $watermark = $results[$i];
                    unset($results[$i]);
                }else{
                    $adv[$result->name]['width'] = $result->width;
                    $adv[$result->name]['height'] = $result->height;
                }
            }
        }
        return array('minimum'=>array('width'=>$minimum->width,'height'=>$minimum->height),
            'advance'=>$adv,'watermark'=>array('width'=>$watermark->width,'height'=>$watermark->height)
        );
    }

    public function getEpgImageSizes()
    {
        $this->db->where('type','EPG');

        $cursor = $this->db->get($this->table_name);
        $results = $cursor->result();

        if(!empty($results)){
            foreach($results as $i=> $result){

                    $adv[$result->name]['width'] = $result->width;
                    $adv[$result->name]['height'] = $result->height;

            }
        }
        return array('advance'=>$adv);

    }
    
    
}