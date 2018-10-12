<?php
class Authenticate_model extends User_model
{
	// Expire in seconds, value is equivalent to 30 minutes
	const EXPIRE = "1800";

	public function is_loggedin()
	{
		$loggedin = $this->session->get_userdata('login');
		if(!empty($loggedin['login']))
			return $loggedin['login'];
		else
			return false;
	}

	/**
	* Login user by username and password
	* @param $username
	* @param $password
	*/
	public function login_by_username($username,$password,$remember=null)
	{
		$this->db->where(array('username'=>$username,'password'=>$password));
		$this->db->where('is_remote_access_enabled',0);
		$this->db->limit(1);

		$result = $this->db->get($this->get_parent_table());
		$user = $result->row();
		$organization = $this->organization->get_row();
		if ($user) 
		{
			$login_data = (object)array(
			 'id' => $user->id,
			 'token' => $user->token,
			 'username' => $user->username,
			 'email' => $user->email,
			 'user_status' => $user->user_status,
			 'user_type' => $user->user_type,
			 'role_id' => $user->role_id,
                         'lsp_type_id' => $user->lsp_type_id,
			 'created_by' => $user->created_by,
			 'parent_id'=>$user->parent_id,
			 'is_first_loggedin' => $user->is_first_loggedin,
			 'is_remote_access_enabled' => $user->is_remote_access_enabled,
			 'gift_amount' => (!empty($organization))? $organization->gift_amount:null,
			 'organization_name' => (!empty($organization))? $organization->organization_name:null,
			 'organization_email' => (!empty($organization))? $organization->organization_email:null,
			 'operator_id' => (!empty($organization))? $organization->operator_id:null,
			 'administrator_1' => (!empty($organization))? $organization->administrator1 : null,
			 'phone_1' => (!empty($organization))? $organization->phone1 : null,
			 'administrator_2' => (!empty($organization))? $organization->administrator2 : null,
			 'phone_2' => (!empty($organization))? $organization->phone2 : null    			 
			 );
			$this->session->set_userdata('login',$login_data);
			//$this->session->mark_as_temp('login', self::EXPIRE);
		}

		/*if ($remember !== false) {
			setcookie('username',$username,time()+(60*60*24),'/');
			setcookie('password',$password,time()+(60*60*24),'/');
		}*/
		return (!empty($user))? true : false;
	}

	/**
	* Login user by email and password
	* @param $email
	* @param $password
	*/
	public function login_by_email($email,$password)
	{
		$this->db->where(array('email'=>$email,'password'=>$password));
		$this->db->where('is_remote_access_enabled',0);
		$this->db->limit(1);
		$result = $this->db->get($this->get_parent_table());
		$user = $result->row();
		$this->set_attributes($user);

		$this->session->set_userdata('login',$this->get_attributes());
		//$this->session->mark_as_temp('login', self::EXPIRE);
		return (!empty($user))? true : false;
	}

	public function has_route_permission($role_id,$module_id,$route)
	{

		$query = $this->db->query('SELECT HasPermissionToRoute('.$role_id.','.$module_id.',"'.$route.'") as permission');
		$result = $query->row();
		return (!empty($result))? $result->permission : 0;
	}

	/**
	* Login user by id
	* @param $id
	*/
	public function login_by_id($id)
	{
		$user = $this->find_by_id($id);
		$this->session->set_userdata('login',$user->get_attributes());
		return (!empty($user))? true : false;
	}

	/**
	* Logout user
	*/
	public function logout()
	{
		$this->session->set_userdata('login',null);
		/*setcookie('username','',0,'/');
		setcookie('password','',0,'/');*/
		return true;
	}

	public function is_username_password_matched($username,$password)
	{
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$this->db->limit(1);
		$q = $this->db->get('users');
		return $q->row();
	}

	
}