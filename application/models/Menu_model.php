<?php
class Menu_model extends MY_Model
{

	protected $table_name = "plaas_menus";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_menu_routes($role_id)
	{

		if(empty($role_id)){
			return array();
		}

		$this->db->where("role_id = {$role_id} or role_id is null");
		$query = $this->db->get($this->table_name);
		$result = $query->result();
		array_map(function($item){
			$item->submenus = json_decode($item->submenus);
			return $item;
		},$result);
		return $result;
	}

	public function get_menu_routes_un_assigned($role_id,$user_type)
	{
		$role = $this->role->find_by_id($role_id);

		$q = $this->db->query("SELECT *,HasViewPermission(role_id,module_id,menu_id) as view_permission,HasCreatePermission(role_id,module_id,menu_id) as create_permission,
 		HasEditPermission(role_id,module_id,menu_id) as edit_permission, HasDeletePermission(role_id,module_id,menu_id) as delete_permission FROM getmenus
 		LEFT JOIN plaas_role_menu_privileges
 			ON plaas_role_menu_privileges.menu_id = getmenus.sub_menu_id
		WHERE main_menu_type = '{$user_type}'
		GROUP BY main_menu,sub_menu
 		ORDER BY display_order, m2_display ASC");

		$results = $q->result();
		$menus = array();

		foreach($results as $result){
			if($role->get_attribute('role_type')=="staff" && $user_type=="LCO"){
				if(in_array($result->route,array('lco','permissions'))){
					continue;
				}
			}
			$menus[$result->main_menu_id]['main_menu_id'] = $result->main_menu_id;
			$menus[$result->main_menu_id]['main_menu'] = $result->main_menu;
			$menus[$result->main_menu_id]['main_menu_type'] = $result->main_menu_type;
			$menus[$result->main_menu_id]['role_id'] = $result->role_id;
			$menus[$result->main_menu_id]['main_menu_route'] = $result->main_menu_route;
			$menus[$result->main_menu_id]['permission'] = 0;
			$menus[$result->main_menu_id]['create_permission'] = 0;
			$menus[$result->main_menu_id]['edit_permission'] = 0;
			$menus[$result->main_menu_id]['delete_permission'] = 0;
			$menus[$result->main_menu_id]['routes'][] = $result->route;
			if($result->sub_menu_id != null){
				$menus[$result->main_menu_id]['submenus'][] = array(
						'display_order'=>$result->m2_display,
						'id'=>$result->sub_menu_id,
						'name'=>$result->sub_menu,
						'route'=>$result->route,
						'parent'=>$result->parent,
						'permission' => 0,
						'create_permission' => 0,
						'edit_permission' => 0,
						'delete_permission' => 0
				);
			}else{
				$menus[$result->main_menu_id]['submenus'] = null;
			}

		}
		return $menus;
	}

	public function get_menus($role_id, $user_type){

		if($user_type == 'Subscriber'){
			$query = "SELECT * FROM getmenus

						WHERE
						main_menu_type = 'Subscriber'
						ORDER BY display_order, m2_display ASC";
		}else{
			$query = "SELECT *,HasViewPermission(role_id,module_id,getmenus.main_menu_id) as permission,
				HasCreatePermission(role_id,module_id,getmenus.main_menu_id) as create_permission ,
				HasEditPermission(role_id,module_id,getmenus.main_menu_id) as edit_permission ,
				HasDeletePermission(role_id,module_id,getmenus.main_menu_id) as delete_permission ,

				HasViewPermission(role_id,module_id,getmenus.sub_menu_id) as sub_permission,
				HasCreatePermission(role_id,module_id,getmenus.sub_menu_id) as sub_create_permission,
				HasEditPermission(role_id,module_id,getmenus.sub_menu_id) as sub_edit_permission,
			    HasDeletePermission(role_id,module_id,getmenus.sub_menu_id) as sub_delete_permission
				FROM getmenus
 		LEFT JOIN plaas_role_menu_privileges
 			ON plaas_role_menu_privileges.menu_id = getmenus.sub_menu_id
		WHERE role_id = {$role_id} AND main_menu_type = '{$user_type}'
 		ORDER BY display_order, m2_display ASC";
		}

		$q = $this->db->query($query);
		$results = $q->result();

		$menus = array();


		$unassign_menus = $this->get_menu_routes_un_assigned($role_id,$user_type);
		foreach($unassign_menus as $u=>$um){
			foreach($results as $result){
				if($um['main_menu_id'] == $result->main_menu_id){
					$unassign_menus[$u]['main_menu_route'] = $result->main_menu_route;
					$unassign_menus[$u]['permission'] =  ($user_type == 'Subscriber')? 1 : (isset($result->permission)? $result->permission : '');
					$unassign_menus[$u]['create_permission'] = ($user_type == 'Subscriber')? 1 : (isset($result->create_permission)? $result->create_permission : '');
					$unassign_menus[$u]['edit_permission'] = ($user_type == 'Subscriber')? 1 : (isset($result->edit_permission)? $result->edit_permission : '');
					$unassign_menus[$u]['delete_permission'] = ($user_type == 'Subscriber')? 1 : (isset($result->delete_permission)? $result->delete_permission : '');

					if(!empty($um['submenus'])){
						foreach($um['submenus'] as $s=>$subm){
							if($subm['id'] == $result->sub_menu_id){
								$unassign_menus[$u]['routes'][$s] = $result->route;
								$unassign_menus[$u]['submenus'][$s]['permission'] = ($user_type == 'Subscriber')? 1 : (isset($result->sub_permission)? $result->sub_permission : '');
								$unassign_menus[$u]['submenus'][$s]['create_permission'] = ($user_type == 'Subscriber')? 1 : (isset($result->sub_create_permission)? $result->sub_permission : '');
								$unassign_menus[$u]['submenus'][$s]['edit_permission'] = ($user_type == 'Subscriber')? 1 : (isset($result->sub_edit_permission)? $result->sub_edit_permission : '');
								$unassign_menus[$u]['submenus'][$s]['delete_permission'] = ($user_type == 'Subscriber')? 1 : (isset($result->sub_delete_permission)? $result->sub_delete_permission : '');
							}

						}
					}else{
						$unassign_menus[$u]['submenus'] = array();
					}

				}

			}
		}

		//test($unassign_menus);
		return $unassign_menus;

	}

	public function find_menu_by_route($route,$user_type)
	{
		$this->db->where('route',$route);
		$this->db->where('menu_type',$user_type);
		$q = $this->db->get($this->table_name);
		return $q->row();
	}

	public function has_permission($role_id,$module_id,$route,$user_type)
	{
		$menu = $this->find_menu_by_route($route,$user_type);
		$menu_id = $menu->id;

		$sql = "SELECT HasViewPermission({$role_id},{$module_id},{$menu_id}) as view_permission,
		HasCreatePermission({$role_id}, {$module_id}, {$menu_id}) as create_permission,
		HasEditPermission({$role_id}, {$module_id}, {$menu_id}) as edit_permission,
		HasDeletePermission({$role_id}, {$module_id}, {$menu_id}) as delete_permission";
		$q = $this->db->query($sql);
		return $q->row();

	}

	public function has_view_permission($role_id,$module_id,$route,$user_type)
	{
		$menu = $this->find_menu_by_route($route,$user_type);
		$menu_id = $menu->id;
		$q = $this->db->query("SELECT HasViewPermission({$role_id},{$module_id},{$menu_id}) as view_permission");
		$result = $q->row();
		return (!empty($result))? $result->view_permission : 0;
	}

	public function has_create_permission($role_id,$module_id,$route,$user_type)
	{
		$menu = $this->find_menu_by_route($route,$user_type);
		$menu_id = $menu->id;
		$q = $this->db->query("SELECT HasCreatePermission({$role_id},{$module_id},{$menu_id}) as create_permission");
		$result = $q->row();
		return (!empty($result))? $result->create_permission : 0;
	}

	public function has_edit_permission($role_id,$module_id,$route,$user_type)
	{
		$menu = $this->find_menu_by_route($route,$user_type);
		$menu_id = $menu->id;
		$q = $this->db->query("SELECT HasEditPermission({$role_id},{$module_id},{$menu_id}) as edit_permission");
		$result = $q->row();
		return (!empty($result))? $result->edit_permission : 0;
	}

	public function has_delete_permission($role_id,$module_id,$route,$user_type)
	{
		$menu = $this->find_menu_by_route($route,$user_type);
		$menu_id = $menu->id;
		$q = $this->db->query("SELECT HasDeletePermission({$role_id},{$module_id},{$menu_id}) as delete_permission");
		$result = $q->row();
		return (!empty($result))? $result->delete_permission : 0;
	}


}