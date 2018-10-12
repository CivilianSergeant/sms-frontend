<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/5/2016
 * Time: 1:22 PM
 */
class Iptv_package_model extends MY_Model
{
    protected $table_name = 'iptv_packages';

    public function get_all_packages($id,$limit=0,$offset=0,$filter=null,$sort=null)
    {	$sql = 'iptv_packages.id,iptv_packages.package_name,iptv_packages.duration,
		iptv_packages.price,iptv_packages.is_commercial,iptv_packages.package_name,iptv_packages.not_deleteable,
		if(iptv_packages.package_type="LIVE",GetIptvPackageProgramsCount(iptv_packages.id),
		GetContentPackageProgramCount(iptv_packages.package_type,iptv_packages.parent_id)) as programs,
		count(iptv_package_subscription.package_id) as assigned';
        $this->db->select($sql);
        $this->db->from($this->table_name);
        //$this->db->join('package_programs','packages.id = package_programs.package_id','left');
        $this->db->join('iptv_package_subscription','iptv_packages.id = iptv_package_subscription.package_id','left');
        //$this->db->where('packages.is_deleted',0);
        $this->db->where('iptv_packages.parent_id = '.$id);
        
        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{
                    if(is_array($column['value']))
                        $this->db->where_in($column['field'],$column['value']);
                    else
                        $this->db->where($column['field'],$column['value']);
                }
            }
        }

        $this->db->group_by($this->table_name.'.id');

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }else{
            $this->db->order_by('id','desc');
        }

        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }



        $query = $this->db->get();

        return $query->result();
    }

    public function count_all_packages($id,$filter=null)
    {
        $this->db->from($this->table_name);

        $this->db->join('iptv_package_subscription','iptv_packages.id = iptv_package_subscription.package_id','left');
        $this->db->where('iptv_packages.parent_id',$id);
        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{
                    if(is_array($column['value']))
                        $this->db->where_in($column['field'],$column['value']);
                    else
                        $this->db->where($column['field'],$column['value']);
                }
            }
        }

        $this->db->group_by($this->table_name.'.id');

        $count = $this->db->count_all_results();

        return $count;
    }


    /**
     * Get programs of a package
     * @return array
     */
    public function get_programs($id=null,$count=false,$sort_order=null)
    {
        $this->db->from('iptv_package_programs');
        $this->db->join('iptv_programs','iptv_package_programs.program_id=iptv_programs.id','left');

        if ($id==null) {
            $this->db->where('package_id',$this->get_attribute('id'));
        } else {
            $this->db->where('package_id',$id);
        }

        if ($sort_order) {
            $this->db->order_by($sort_order);
        }
        $result_set = $this->db->get();
        $result = $result_set->result();


        $programs = array();
        if (!empty($result)) {
            foreach ($result as $i=>$r) {
                $programs[$r->id] = $r;
            }
        }

        if ($count) {
            return count($programs);
        }

        return $programs;
    }

    public function find_by_name($package_name,$parent_id=null)
    {
        if(!empty($parent_id)){
            $this->db->where('parent_id',$parent_id);
        }

        $this->db->where('package_name',$package_name);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    public function find_by_name_not_in($package_name,$id,$parent_id=null)
    {
        if(!empty($parent_id)){
            $this->db->where('parent_id',$parent_id);
        }
        $this->db->where("package_name = '".$package_name."'");
        $this->db->where_not_in("id",$id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * Remove programs by Package
     * @return boolean
     */
    public function remove_programs()
    {
        if ($this->attributes['id']) {
            $this->db->where('package_id',$this->attributes['id']);
            $this->db->delete('iptv_package_programs');
            return true;
        }

        return false;

    }

    public function assign_programs($programs,$package_id=null)
    {

        if (!empty($programs)) {
            foreach ($programs as $p) {
                $savePackageProgram = array(
                    'package_id' => (!empty($package_id))? $package_id :$this->attributes['id'],
                    'program_id' => $p
                );
                $this->Iptv_package_program->save($savePackageProgram);
            }
            return true;
        }

        return false;

    }

    public function get_packages($parentId)
    {
        $this->db->select('iptv_packages.id,iptv_packages.package_name,iptv_packages.duration,iptv_packages.price,iptv_packages.package_type,
        if(iptv_packages.package_type="LIVE",GetIptvPackageProgramsCount(iptv_packages.id),GetContentPackageProgramCount(iptv_packages.package_type,'.$parentId.')) as no_of_program');
        $this->db->from($this->table_name);
        $this->db->join('iptv_package_programs','iptv_packages.id = iptv_package_programs.package_id','left');
        $this->db->where('iptv_packages.is_active',1);
        $this->db->where('iptv_packages.parent_id',$parentId);
        $this->db->group_by('iptv_packages.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function package_delete($id)
    {
        $this->db->where('id',$id);
        return $this->db->delete($this->table_name);
    }
}