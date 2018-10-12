<?php


class Settings_model extends MY_Model
{
    protected $table_name='';

    const PRIORITIES_TBL     = 'priorities';
    const POSITIONS_TBL      = 'display_positions';
    const SIZES_TBL          = 'sizes';
    const TYPES_TBL          = 'types';
    const COLOR_TYPES_TBL    = 'color_types';
    const FONTS_TBL          = 'fonts';
    const BACK_COLORS_TBL    = 'back_colors';
    const API_SETTINGS       = 'api_settings';
    const TIMEZONE           = 'timezones';
    const IMAGE_QUALITIES_TBL  = 'image_qualities';

    const SCROLLING_SETTINGS ='scrolling_osd_settings';


    public function __construct()
    {

    }

    public function setTblInstance($tbl_name)
    {
        $this->table_name = $tbl_name;
    }

    public function find_settings_by_name($name)
    {
        $this->db->where('name',$name);
        $this->db->limit(1);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    public function find_api_settings($parent_id = null)
    {
        if(!empty($parent_id)){
            $this->db->where('parent_id',$parent_id);
        }
        $this->db->limit(1);
        $q = $this->db->get(self::API_SETTINGS);
        return $q->row();
    }

    public function get_timezone_by_offset($offset)
    {
        $this->db->where('offset',$offset);
        $q = $this->db->get(self::TIMEZONE);
        return $q->row();
    }
    
    public function get_image_qualities()
    {
        $q = $this->db->get(self::IMAGE_QUALITIES_TBL);
        return $q->result();
    }

}