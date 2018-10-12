<?php


class Group_profile_model extends MY_Model
{
    protected $table_name="group_profiles";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_profile_by_token($token)
    {
        $this->db->select('group_profiles.*,HasLCOBusinessRegion(group_profiles.region_l1_code,group_profiles.region_l2_code,group_profiles.region_l3_code,group_profiles.region_l4_code) as business_region_assigned,users.username,users.profile_id,users.role_id,users.is_remote_access_enabled,users.user_type,users.user_status,users.email,countries.country_name,divisions.division_name,districts.district_name,areas.area_name,sub_areas.sub_area_name,roads.road_name');
        $this->db->from('group_profiles');
        $this->db->join('users', 'group_profiles.id = users.profile_id', 'left');
        $this->db->join('countries','countries.id = group_profiles.country_id','left');
        $this->db->join('divisions','divisions.id = group_profiles.division_id','left');
        $this->db->join('districts','districts.id = group_profiles.district_id','left');
        $this->db->join('areas','areas.id = group_profiles.area_id','left');
        $this->db->join('sub_areas','sub_areas.id = group_profiles.sub_area_id','left');
        $this->db->join('roads','roads.id = group_profiles.road_id','left');
        $this->db->where('users.user_type','Group');
        $this->db->where('group_profiles.token',$token);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all_group_users($created_by,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select('users.id as user_id,users.username,users.email,users.user_type,users.user_status,users.role_id,
			group_profiles.id,
			group_profiles.group_name,
			group_profiles.address1,
			group_profiles.address2,
			group_profiles.country_id,
			group_profiles.division_id,
			group_profiles.district_id,
			group_profiles.area_id,
			group_profiles.sub_area_id,
			group_profiles.road_id,
			group_profiles.identity_type,
			group_profiles.identity_number,
			group_profiles.contact,
			group_profiles.billing_contact,
			group_profiles.business_modality,
			group_profiles.token,
			group_profiles.region_l1_code,
			group_profiles.region_l2_code,
			group_profiles.region_l3_code,
			group_profiles.region_l4_code,
			HasLCOBusinessRegion(group_profiles.region_l1_code,group_profiles.region_l2_code,group_profiles.region_l3_code,group_profiles.region_l4_code) as business_region_assigned,
			group_profiles.message_sign,
			group_profiles.created_by,
			group_profiles.parent_id,
			group_profiles.created_at,
			group_profiles.updated_at
		');

        $this->db->from('group_profiles');
        $this->db->join('users', 'group_profiles.id = users.profile_id', 'left');

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        $this->db->where('users.user_type','Group');
        $this->db->where('group_profiles.parent_id', $created_by);

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit))
        {
            $this->db->limit($limit,$offset);
        }
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        return $result = $query->result();
    }

    public function get_count_group($created_by,$filter=null)
    {
        $this->db->from('group_profiles');
        $this->db->join('users', 'group_profiles.id = users.profile_id', 'left');

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        $this->db->where('users.user_type','Group');
        $this->db->where('group_profiles.parent_id', $created_by);
        return $this->db->count_all_results();
    }

    public function get_group_name($user_id)
    {

        $query = $this->db->query("SELECT GetGroupName({$user_id}) as group_name");
        $result = $query->row();
        return (!empty($result))? $result->group_name : '';
    }

    public function get_lco_list($group_id,$limit=0,$offset=0,$filter=null,$sort=null)
    {
        $this->db->select('users.id as user_id,users.username,users.email,users.user_type,users.user_status,users.role_id,
			lco_profiles.id,
			lco_profiles.lco_name,
			lco_profiles.address1,
			lco_profiles.address2,
			lco_profiles.country_id,
			lco_profiles.division_id,
			lco_profiles.district_id,
			lco_profiles.area_id,
			lco_profiles.sub_area_id,
			lco_profiles.road_id,
			lco_profiles.identity_type,
			lco_profiles.identity_number,
			lco_profiles.contact,
			lco_profiles.billing_contact,
			lco_profiles.business_modality,
			lco_profiles.token,
			lco_profiles.region_l1_code,
			lco_profiles.region_l2_code,
			lco_profiles.region_l3_code,
			lco_profiles.region_l4_code,
			HasLCOBusinessRegion(lco_profiles.region_l1_code,lco_profiles.region_l2_code,lco_profiles.region_l3_code,lco_profiles.region_l4_code) as business_region_assigned,
			lco_profiles.message_sign,
			lco_profiles.created_by,
			lco_profiles.parent_id,
			lco_profiles.created_at,
			lco_profiles.updated_at
		');
        $this->db->from('lco_groups');
        $this->db->join('users', 'lco_groups.lco_id = users.id', 'left');
        $this->db->join('lco_profiles','lco_profiles.id = users.profile_id','left');

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        $this->db->where('users.user_type','LCO');
        $this->db->where('lco_groups.group_id', $group_id);

        if(!empty($sort)){
            foreach($sort as $s){
                $this->db->order_by($s['field'],$s['dir']);
            }
        }

        if(!empty($limit))
        {
            $this->db->limit($limit,$offset);
        }
        $this->db->order_by('lco_profiles.lco_name','ASC');
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        return $result = $query->result();
    }

    public function get_count_lco($group_id,$filter=null)
    {
        $this->db->from('lco_groups');
        $this->db->join('users', 'lco_groups.lco_id = users.id', 'left');
        $this->db->join('lco_profiles','lco_profiles.id = users.profile_id','left');

        if(!empty($filter)){
            foreach($filter['filters'] as $column){
                if($column['operator']=="startswith"){

                    $this->db->like($column['field'],$column['value'],'after');
                }else{

                    $this->db->where($column['field'],$column['value']);
                }
            }
        }

        $this->db->where('users.user_type','LCO');
        $this->db->where('lco_groups.group_id', $group_id);
        return $this->db->count_all_results();
    }

    public function get_group_lco_count($group_id)
    {
        $q = $this->db->query("SELECT GetGroupLCOCount($group_id) as total");
        $r = $q->row();
        return (!empty($r)? $r->total : '');
    }

    public function remove_lco($group_id)
    {
        $this->db->where('group_id',$group_id);
        $this->db->delete('lco_groups');
        return true;
    }
}