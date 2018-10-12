<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/2/2016
 * Time: 2:50 PM
 */
class Iptv_program_model extends MY_Model
{
    protected $table_name = 'iptv_programs';

    public function get_free_programs()
    {
        $this->db->where('type', 'FREE');
        return $this->db->get($this->table_name)->result();
    }

    public function get_live_programs()
    {
        $this->db->where('type','LIVE');
        return $this->db->get($this->table_name)->result();
    }

    public function get_delay_programs()
    {
        $this->db->where('type','DELAY');
        return $this->db->get($this->table_name)->result();
    }

    public function get_live_delay_programs($id=null,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select('*, IsProgramEpgMapped(id) as mapped');
        if(!empty($id)){
            if($id == 1){
                $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
            }else{
                $this->db->where('parent_id = '.$id);
            }

        }
        $this->db->where("type != 'CATCHUP' AND type != 'VOD'");
        $this->db->where('is_remove',0);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        return $this->db->get($this->table_name)->result();
    }

    public function count_live_delay_programs($id=null,$filter=null)
    {
        if(!empty($id)){
            if($id == 1){
                $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
            }else{
                $this->db->where('parent_id = '.$id);
            }

        }

        $this->db->where("type != 'CATCHUP' AND type != 'VOD'");
        $this->db->where('is_remove',0);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        return $this->db->count_all_results($this->table_name);


    }

    public function get_catchup_programs($id=null,$limit=0,$offset=0,$filter=null,$sort=null)
    {

        if(!empty($id)){
            if($id == 1){
                $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
            }else{
                $this->db->where('parent_id = '.$id);
            }

        }
        $this->db->where('serial_content',0);
        $this->db->where('type', 'CATCHUP');
        $this->db->where('is_remove',0);
        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        return $this->db->get($this->table_name)->result();
    }

    public function count_catchup_programs($id=null,$filter=null)
    {
        if(!empty($id)){
            if($id == 1){
                $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
            }else{
                $this->db->where('parent_id = '.$id);
            }

        }

        $this->db->where('type', 'CATCHUP');
        $this->db->where('is_remove',0);
        $this->db->where('serial_content',0);
        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        return $this->db->count_all_results($this->table_name);
    }

    public function get_vod_programs($id=null,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        if(!empty($id)){
            if($id == 1){
                $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
            }else{
                $this->db->where('parent_id = '.$id);
            }

        }

        $this->db->where('type', 'VOD');
        $this->db->where('is_remove',0);
        $this->db->where('serial_content',0);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        return $this->db->get($this->table_name)->result();
    }

    public function count_vod_programs($id=null,$filter=null)
    {
        if(!empty($id)){
            if($id == 1){
                $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
            }else{
                $this->db->where('parent_id = '.$id);
            }

        }

        $this->db->where('type', 'VOD');
        $this->db->where('is_remove',0);
        $this->db->where('serial_content',0);
        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        return $this->db->count_all_results($this->table_name);
    }


    public function get_serial_programs($id=null,$limit=0,$offset=0,$filter=null,$sort=null)
    {

        if(!empty($id)){
            $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
        }

        $this->db->where('is_remove',0);
        $this->db->where('serial_content', 1);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        return $this->db->get($this->table_name)->result();
    }

    public function count_serial_programs($id=null,$filter=null)
    {
        if(!empty($id)){
            $this->db->where('(parent_id = '.$id.' OR lsp_type_id = 2)');
        }

        $this->db->where('is_remove',0);
        $this->db->where('serial_content', 1);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        return $this->db->count_all_results($this->table_name);
    }


    public function get_featured_programs($id=null,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        if(!empty($id)){
            $this->db->where('parent_id',$id);
        }

        //$this->db->where('type', 'VOD');
        $this->db->where('is_remove',0);
        $this->db->where('featured',1);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }

        return $this->db->get($this->table_name)->result();
    }

    public function count_featured_programs($id=null,$filter=null)
    {
        if(!empty($id)){
            $this->db->where('parent_id',$id);
        }

        //$this->db->where('type', 'VOD');
        $this->db->where('is_remove',0);
        $this->db->where('featured',1);

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        return $this->db->count_all_results($this->table_name);
    }



    public function get_all_program_name($filter=null){
        $this->db->select('id,program_name');
        if(!empty($filter)){
            $this->db->where($filter);
        }
        $q = $this->db->get($this->table_name);
        return $q->result();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function checkassign_program($id)
    {
        $this->db->select('program_id,package_id');
        $this->db->from('iptv_package_programs');
        $this->db->where('program_id',$id);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * @param $id
     */
    public function program_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }

    public function is_lcn_unique($lcn)
    {
        $this->db->where('lcn',$lcn);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    public function update_is_removed($contentProviderId)
    {
        $this->db->where('content_provider_id',$contentProviderId);
        $this->db->update($this->table_name,array('is_remove'=>1));
        return $this->db->affected_rows();
    }

    public function set_as_featured($contentId)
    {
        $this->db->where('id',$contentId);
        $this->db->update($this->table_name,array('featured'=>1));
        return $this->db->affected_rows();
    }

    public function set_as_normal($contentId)
    {
        $this->db->where('id',$contentId);
        $this->db->update($this->table_name,array('featured'=>0));
        return $this->db->affected_rows();
    }

    public function get_video_tags($parent_id=null)
    {
        $this->db->select('video_tags');
        $this->db->where('serial_content',1);
        if(!empty($parent_id)){
            $this->db->where('parent_id',$parent_id);
        }
        $this->db->group_by('video_tags');
        $result = $this->db->get($this->table_name);
        return $result->result();
    }

    public function is_name_available($program_name,$type)
    {
        $this->db->where('program_name',$program_name);
        $this->db->where('type',$type);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }

    public function is_content_dir_available($content_dir)
    {
        $this->db->where('content_dir',$content_dir);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }

    public function has_hls($id)
    {
        $this->db->where('program_id',$id);
        $q = $this->db->get('map_streamer_instances');
        return $q->row();
    }
    
    public function update_program_order_action($id, $lcn)
    {
        $this->db->where('id',$id);
        $this->db->update($this->table_name,array('lcn'=>$lcn));
    }
    
    public function update_program_status_action($id, $status)
    {
        $this->db->where('id',$id);
        $this->db->update($this->table_name,array('is_sort_locked'=>$status));
    }


}