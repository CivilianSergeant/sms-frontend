<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Services{

    protected $CI;

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->config->load('cas');
    }

    public function login_api()
    {
    	
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ServerStatus";
		$curl_post_data='{
			"input1": "connectionCheck",
			"input2": 0
		}'; 

		if($api_call_status){

    		try{

				if($this->CI->config->item('debug'))
				{

					$this->write_log('request',$curl_post_data);
				}

				$api_response = $this->get_response($url,$curl_post_data);
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->message = '';
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			return $response;
		}
    }

    public function login_logout_with_state($json_data,$login=true)
    {
    	
		$api_call_status = $this->CI->config->item('is_api_active');
		
		if($login)
    		$url = "Login";
    	else 
    		$url = "Logout";

    	if($api_call_status){
    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }

    public function repair_cancel_stb_ic($json_data)
    {
    	$api_call_status = $this->CI->config->item('is_api_active');
		$url = "RepairOrCancelSTBnICPair";
    	
    	if($api_call_status){

    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }

    public function conditional_mail($json_data)
    {
    	$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ConditionalMail";
    	
    	if($api_call_status){

    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }

	/**
	 * Same API for all conditional delete
	 * @param $json_data
	 * @return stdClass
	 */
	public function stop_conditional_mail($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "DeleteConditionalSearch";

		if($api_call_status){

			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				//$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
	}

    public function get_conditional_mail_content($mail_data,$cardType="ic")
    {
    	date_default_timezone_set('Asia/Dhaka');
        $start_date = new DateTime(date('Y-m-d H:i:s'));
    	$api_conditional_mail = array(
            'operatorName' => 'administrator',
            'startTime' => datetime_to_array($start_date->format('Y-m-d H:i:s')),
        );
        $end_date   = $start_date->add(new DateInterval('P30D'));
        $api_conditional_mail['endTime'] = datetime_to_array($end_date->format('Y-m-d H:i:s'));
        $api_conditional_mail['conditionLength'] = 11;
        $api_conditional_mail['contentLength']   = 1089;
        $api_conditional_mail['condCounts'] =1;

        $conditionList = array(
        		'typeData'=> ($cardType=="ic")? 48 : 52,
        		'typeOperator'=>116
    		);

        if($cardType == "ic")
        	$conditionList['cardNumber'] = $mail_data['cardNum'];
        else
        	$conditionList['stbId']      = $mail_data['cardNum'];

        $api_conditional_mail['conditionList'] = array($conditionList);
        $api_conditional_mail['reserved'] = 4294967295;
        $api_conditional_mail['title']   = $mail_data['title'];
        $api_conditional_mail['content'] = $this->CI->load->view($mail_data['template'],$mail_data,true);
        $api_conditional_mail['signStr'] = $mail_data['message_sign'];
        $api_conditional_mail['priority'] = 1;
        return $api_conditional_mail;
    }


	/**
	 * Update Package Api will call during Add/Update Package
	 * @param $json_data , json string send as post data
	 */
	public function update_customer($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "CustomerInformation";
		if($api_call_status){
			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
	}

	/**
	 * Update Package Api will call during Add/Update Package
	 * @param $json_data , json string send as post data
	 */
	public function modify_card_info($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ModifyCardInformation";
		if($api_call_status){
			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
	}

	/**
	 * Update Package Api will call during Add/Update Package
	 * @param $json_data , json string send as post data
	 */
	public function update_card_info($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "UpdateCardInformation";
		if($api_call_status){
			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
	}

    /**
    * Delete Package Api will call during Delete Package
    * @param $json_data , json string send as post data
    */
    public function delete_package($json_data)
    {
    	$api_call_status = $this->CI->config->item('is_api_active');
    	$url = "DeletePackage";
    	if($api_call_status){
    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }

    /**
    * Delete Program Api will call during Delete Program
    * @param $json_data , json string send as post data
    */
    public function delete_program($json_data)
    {
    	$api_call_status = $this->CI->config->item('is_api_active');
    	$url = "DeleteProgram";
    	if($api_call_status){
    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }

    /**
    * Update Package Api will call during Add/Update Package
    * @param $json_data , json string send as post data
    */
    public function update_package($json_data)
    {
    	$api_call_status = $this->CI->config->item('is_api_active');
    	$url = "UpdatePackage";
    	if($api_call_status){
    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }

    /**
    * Update Program Api will call during Add/Update Program
    * @param $json_data , json string send as post data
    */
    public function update_program($json_data)
    {
    	$api_call_status = $this->CI->config->item('is_api_active');
    	$url = "UpdateProgram";
    	if($api_call_status){
    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }


    /**
    * Package Update Api will call during Charge Fee
    * @param $json_data , json string send as post data
    */
    public function package_update($json_data)
    {
    	$api_call_status = $this->CI->config->item('is_api_active');
    	$url = "AuthorizeSingleCard";
    	if($api_call_status){
    		try{
				
				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				return $response;
			}

    	} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			return $response;
		}
    }



    private function get_status_code($api_response)
    {
    	if($api_response->status == 0){

			return 200;

		} else if ($api_response->status == 1) {

			return 400;

		} else {

			return 401;
		}
    }

    private function write_log($type,$data)
    {
    	$r = file_put_contents($type.'.txt', "\r\n".'DATE:'.date('Y-m-d H:i:s').', '.strtoupper($type).': '.$data,FILE_APPEND);
    	
    }


    private function get_response($url,$curl_post_data){

    	$ip = $this->CI->config->item('ip');
		$port =  $this->CI->config->item('port');
		$url = "http://".$ip.":".$port."/sms/api/".$url; 

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$curl_response = curl_exec($curl);
		
		curl_close($curl);
  
        if(!$curl_response)
        {
        	throw new Exception("Gateway is not activated");
        } else {
        	$object = json_decode($curl_response);

        	return $object;
        }
		
	}

	/**
	 * @param $mail_data
	 * @param bool|false $mail_flag Contains Data
	 * @param bool|false $scrolling if true then function will return Scrolling related data
	 * @param bool|false $force_osd if true then function will return Force osd related data
	 * @param bool|false $limited   if true then function will return Limited related data
	 * @param bool|false $ecm		if true then function will return ECM related data
	 * @param bool|false $emm		if true then function will return EMM related data
	 * @return array
	 */
	public function get_lco_conditional_content($mail_data,$mail_flag=false,$scrolling=false,$force_osd=false,$limited=false,$ecm=false,$emm=false)
	{
		date_default_timezone_set('Asia/Dhaka');
		$start_date = new DateTime($mail_data['start_date_time']);
		$api_conditional_data = array(
				'operatorName' => 'administrator',
				'startTime' => datetime_to_array($start_date->format('Y-m-d H:i:s')),
		);
		$end_date = new DateTime($mail_data['end_date_time']);
		$api_conditional_data['endTime'] = datetime_to_array($end_date->format('Y-m-d H:i:s'));

		$api_conditional_data['conditionLength'] = $mail_data['conditionLength'];
		$api_conditional_data['contentLength'] = $mail_data['contentLength']; //($mail_flag)? 1089 : 12;
		$api_conditional_data['condCounts'] = $mail_data['condCounts'];

		$conditionList = array(
				'typeData' => $mail_data['type_data'],
				'typeOperator' => $mail_data['type_operator'],
				'groupId' => $mail_data['group_id']
		);


		$api_conditional_data['conditionList'] = array($conditionList);
		$api_conditional_data['reserved'] = 4294967295;

		if ($mail_flag) {
			$api_conditional_data['title'] = $mail_data['title'];
			$api_conditional_data['content'] = $this->CI->load->view($mail_data['template'], $mail_data, true);
			$api_conditional_data['signStr'] = $mail_data['message_sign'];
			$api_conditional_data['priority'] = 1;
		}

		if($scrolling) {
			$api_conditional_data['content']         = $mail_data['content'];
			$api_conditional_data['displayCounts']   = $mail_data['displayCounts'];
			$api_conditional_data['priority']        = $mail_data['priority'];
			$api_conditional_data['position']        = $mail_data['position'];
			$api_conditional_data['fontSize']        = $mail_data['font_size'];
			$api_conditional_data['fontType']        = $mail_data['font_type'];
			$api_conditional_data['fontColor']       = $mail_data['font_color'];
			$api_conditional_data['backgroundColor'] = $mail_data['back_color'];
		}

		if($force_osd){
			$api_conditional_data['content']         = $mail_data['content'];
			$api_conditional_data['ratio']			 = $mail_data['ratio'];
			$api_conditional_data['showTime']		 = $mail_data['showTime'];
			$api_conditional_data['stopTime']		 = $mail_data['stopTime'];
			$api_conditional_data['fontSize']		 = $mail_data['fontSize'];
			$api_conditional_data['fontType']		 = $mail_data['fontType'];
			$api_conditional_data['colorType']	     = $mail_data['colorType'];
			$api_conditional_data['fontColor']		 = $mail_data['fontColor'];
			$api_conditional_data['backgroundColor'] = $mail_data['backgroundColor'];
			$api_conditional_data['clarity']         = $mail_data['clarity'];
			$api_conditional_data['prgCount']		 = $mail_data['prgCount'];
			$api_conditional_data['programID']		 = $mail_data['programID'];
		}

		if($limited){
			$api_conditional_data['prodCounts'] = $mail_data['prodCounts'];
			$api_conditional_data['productID']  = $mail_data['productID'];
		}

		if($ecm){
			$api_conditional_data['progCounts']		 = $mail_data['progCounts'];
			$api_conditional_data['programID']		 = $mail_data['programID'];
		}

		if($emm){
			$api_conditional_data['positionFlag']      = $mail_data['positionFlag'];
			$api_conditional_data['showTime']		   = $mail_data['showTime'];
			$api_conditional_data['stopTime']		   = $mail_data['stopTime'];
			$api_conditional_data['fontSize']		   = $mail_data['fontSize'];
			$api_conditional_data['fontType']		   = $mail_data['fontType'];
			$api_conditional_data['colorType']		   = $mail_data['colorType'];
			$api_conditional_data['fontColor']         = $mail_data['fontColor'];
			$api_conditional_data['backgroundColor']   = $mail_data['backgroundColor'];
			$api_conditional_data['overtFlag']         = $mail_data['overtFlag'];
			$api_conditional_data['showBKFlag']		   = $mail_data['showBKFlag'];
			$api_conditional_data['showSTBNumberFlag'] = $mail_data['showSTBNumberFlag'];
			$api_conditional_data['xPosition']		   = $mail_data['xPosition'];
			$api_conditional_data['yPosition']		   = $mail_data['yPosition'];
		}

		return $api_conditional_data;
	}

	/**
	 * @param $mail_data
	 * @param bool|false $mail_flag Contains Data
	 * @param bool|false $scrolling if true then function will return Scrolling related data
	 * @param bool|false $force_osd if true then function will return Force osd related data
	 * @param bool|false $limited   if true then function will return Limited related data
	 * @param bool|false $ecm		if true then function will return ECM related data
	 * @param bool|false $emm		if true then function will return EMM related data
	 * @return array
	 */
	public function get_subscriber_conditional_content($mail_data,$mail_flag=false,$scrolling=false,$force_osd=false,$limited=false,$ecm=false,$emm=false)
	{
		date_default_timezone_set('Asia/Dhaka');
		$start_date = new DateTime($mail_data['start_date_time']);
		$api_conditional_data = array(
				'operatorName' => 'administrator',
				'startTime' => datetime_to_array($start_date->format('Y-m-d H:i:s')),
		);
		$end_date = new DateTime($mail_data['end_date_time']);

		$api_conditional_data['endTime'] = datetime_to_array($end_date->format('Y-m-d H:i:s'));
		$api_conditional_data['conditionLength'] = $mail_data['conditionLength']; //11;
		$api_conditional_data['contentLength']   = $mail_data['contentLength']; //($mail_flag)? 1089 : 12;
		$api_conditional_data['condCounts']      = $mail_data['condCounts']; //1;

		$conditionList = array(
				'typeData' => $mail_data['type_data'],
				'typeOperator' => $mail_data['type_operator'],
		);

		if($mail_data['type_data'] == 48){

			$conditionList['cardNumber'] = $mail_data['cardNum'];

		}elseif($mail_data['type_data'] == 52){

			$conditionList['stbId'] = $mail_data['cardNum'];
		}


		$api_conditional_data['conditionList'] = array($conditionList);
		$api_conditional_data['reserved'] = 4294967295;

		if ($mail_flag) {
			$api_conditional_data['title'] = $mail_data['title'];
			$api_conditional_data['content'] = $this->CI->load->view($mail_data['template'], $mail_data, true);
			$api_conditional_data['signStr'] = $mail_data['message_sign'];
			$api_conditional_data['priority'] = 1;
		}

		if($scrolling) {
			$api_conditional_data['content']         = $mail_data['content'];
			$api_conditional_data['displayCounts']   = $mail_data['displayCounts'];
			$api_conditional_data['priority']        = $mail_data['priority'];
			$api_conditional_data['position']        = $mail_data['position'];
			$api_conditional_data['fontSize']        = $mail_data['font_size'];
			$api_conditional_data['fontType']        = $mail_data['font_type'];
			$api_conditional_data['fontColor']       = $mail_data['font_color'];
			$api_conditional_data['backgroundColor'] = $mail_data['back_color'];
		}

		if($force_osd){
			$api_conditional_data['content']         = $mail_data['content'];
			$api_conditional_data['ratio']			 = $mail_data['ratio'];
			$api_conditional_data['showTime']		 = $mail_data['showTime'];
			$api_conditional_data['stopTime']		 = $mail_data['stopTime'];
			$api_conditional_data['fontSize']		 = $mail_data['fontSize'];
			$api_conditional_data['fontType']		 = $mail_data['fontType'];
			$api_conditional_data['colorType']	     = $mail_data['colorType'];
			$api_conditional_data['fontColor']		 = $mail_data['fontColor'];
			$api_conditional_data['backgroundColor'] = $mail_data['backgroundColor'];
			$api_conditional_data['clarity']         = $mail_data['clarity'];
			$api_conditional_data['prgCount']		 = $mail_data['prgCount'];
			$api_conditional_data['programID']		 = $mail_data['programID'];
		}

		if($limited){
			$api_conditional_data['prodCounts'] = $mail_data['prodCounts'];
			$api_conditional_data['productID']  = $mail_data['productID'];
		}

		if($ecm){
			$api_conditional_data['progCounts']		 = $mail_data['progCounts'];
			$api_conditional_data['programID']		 = $mail_data['programID'];
		}

		if($emm){
			$api_conditional_data['positionFlag']      = $mail_data['positionFlag'];
			$api_conditional_data['showTime']		   = $mail_data['showTime'];
			$api_conditional_data['stopTime']		   = $mail_data['stopTime'];
			$api_conditional_data['fontSize']		   = $mail_data['fontSize'];
			$api_conditional_data['fontType']		   = $mail_data['fontType'];
			$api_conditional_data['colorType']		   = $mail_data['colorType'];
			$api_conditional_data['fontColor']         = $mail_data['fontColor'];
			$api_conditional_data['backgroundColor']   = $mail_data['backgroundColor'];
			$api_conditional_data['overtFlag']         = $mail_data['overtFlag'];
			$api_conditional_data['showBKFlag']		   = $mail_data['showBKFlag'];
			$api_conditional_data['showSTBNumberFlag'] = $mail_data['showSTBNumberFlag'];
			$api_conditional_data['xPosition']		   = $mail_data['xPosition'];
			$api_conditional_data['yPosition']		   = $mail_data['yPosition'];
		}

		return $api_conditional_data;
	}

	/**
	 * @param $mail_data
	 * @param bool|false $mail_flag Contains Data
	 * @param bool|false $scrolling if true then function will return Scrolling related data
	 * @param bool|false $force_osd if true then function will return Force osd related data
	 * @param bool|false $limited   if true then function will return Limited related data
	 * @param bool|false $ecm		if true then function will return ECM related data
	 * @param bool|false $emm		if true then function will return EMM related data
	 * @return array
	 */
	public function get_address_code_conditional_content($mail_data,$mail_flag=false,$scrolling=false,$force_osd=false,$limited=false,$ecm=false,$emm=false)
	{
		date_default_timezone_set('Asia/Dhaka');
		$start_date = new DateTime($mail_data['start_date_time']);
		$api_conditional_data = array(
				'operatorName' => 'administrator',
				'startTime' => datetime_to_array($start_date->format('Y-m-d H:i:s')),
		);
		$end_date = new DateTime($mail_data['end_date_time']);
		$api_conditional_data['endTime'] = datetime_to_array($end_date->format('Y-m-d H:i:s'));
		$api_conditional_data['conditionLength'] = $mail_data['conditionLength']; //11;
		$api_conditional_data['contentLength']   = $mail_data['contentLength']; //($mail_flag)? 1089 : 12;
		$api_conditional_data['condCounts']      = $mail_data['condCounts']; //1;

		$conditionList = array(
				'typeData' => $mail_data['type_data'],
				'typeOperator' => $mail_data['type_operator'],
				'addr'         => $mail_data['addr']

		);

		$api_conditional_data['conditionList'] = array($conditionList);
		$api_conditional_data['reserved'] = 4294967295;

		if ($mail_flag) {
			$api_conditional_data['title'] = $mail_data['title'];
			$api_conditional_data['content'] = $this->CI->load->view($mail_data['template'], $mail_data, true);
			$api_conditional_data['signStr'] = $mail_data['message_sign'];
			$api_conditional_data['priority'] = 1;
		}

		if($scrolling) {
			$api_conditional_data['content']         = $mail_data['content'];
			$api_conditional_data['displayCounts']   = $mail_data['displayCounts'];
			$api_conditional_data['priority']        = $mail_data['priority'];
			$api_conditional_data['position']        = $mail_data['position'];
			$api_conditional_data['fontSize']        = $mail_data['font_size'];
			$api_conditional_data['fontType']        = $mail_data['font_type'];
			$api_conditional_data['fontColor']       = $mail_data['font_color'];
			$api_conditional_data['backgroundColor'] = $mail_data['back_color'];
		}

		if($force_osd){
			$api_conditional_data['content']         = $mail_data['content'];
			$api_conditional_data['ratio']			 = $mail_data['ratio'];
			$api_conditional_data['showTime']		 = $mail_data['showTime'];
			$api_conditional_data['stopTime']		 = $mail_data['stopTime'];
			$api_conditional_data['fontSize']		 = $mail_data['fontSize'];
			$api_conditional_data['fontType']		 = $mail_data['fontType'];
			$api_conditional_data['colorType']	     = $mail_data['colorType'];
			$api_conditional_data['fontColor']		 = $mail_data['fontColor'];
			$api_conditional_data['backgroundColor'] = $mail_data['backgroundColor'];
			$api_conditional_data['clarity']         = $mail_data['clarity'];
			$api_conditional_data['prgCount']		 = $mail_data['prgCount'];
			$api_conditional_data['programID']		 = $mail_data['programID'];
		}

		if($limited){
			$api_conditional_data['prodCounts'] = $mail_data['prodCounts'];
			$api_conditional_data['productID']  = $mail_data['productID'];
		}

		if($ecm){
			$api_conditional_data['progCounts']		 = $mail_data['progCounts'];
			$api_conditional_data['programID']		 = $mail_data['programID'];
		}

		if($emm){
			$api_conditional_data['positionFlag']      = $mail_data['positionFlag'];
			$api_conditional_data['showTime']		   = $mail_data['showTime'];
			$api_conditional_data['stopTime']		   = $mail_data['stopTime'];
			$api_conditional_data['fontSize']		   = $mail_data['fontSize'];
			$api_conditional_data['fontType']		   = $mail_data['fontType'];
			$api_conditional_data['colorType']		   = $mail_data['colorType'];
			$api_conditional_data['fontColor']         = $mail_data['fontColor'];
			$api_conditional_data['backgroundColor']   = $mail_data['backgroundColor'];
			$api_conditional_data['overtFlag']         = $mail_data['overtFlag'];
			$api_conditional_data['showBKFlag']		   = $mail_data['showBKFlag'];
			$api_conditional_data['showSTBNumberFlag'] = $mail_data['showSTBNumberFlag'];
			$api_conditional_data['xPosition']		   = $mail_data['xPosition'];
			$api_conditional_data['yPosition']		   = $mail_data['yPosition'];
		}

		return $api_conditional_data;
	}

	public function conditional_search($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ConditionalProgramSearch";

		if($api_call_status){

			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				$response->id = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			$response->id = null;
			return $response;
		}
	}

	public function conditional_osd($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ConditionalOsd";

		if($api_call_status){

			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				$response->id = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			$response->id = null;
			return $response;
		}
	}

	public function conditional_force_osd($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ConditionalForceOSD";

		if($api_call_status){

			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				$response->id = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			$response->id = null;
			return $response;
		}
	}

	public function conditional_limited($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ConditionalLimitedBroadcast";

		if($api_call_status){

			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				$response->id = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			$response->id = null;
			return $response;
		}
	}

	public function ecm_fingerprint($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ConditionalECMFingerprint";

		if($api_call_status){

			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				$response->id = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			$response->id = null;
			return $response;
		}
	}

	public function emm_fingerprint($json_data)
	{
		$api_call_status = $this->CI->config->item('is_api_active');
		$url = "ConditionalEMMFingerprint";

		if($api_call_status){

			try{

				if($this->CI->config->item('debug'))
				{
					$this->write_log('request',$json_data);
				}

				$api_response = $this->get_response($url,$json_data);

				if($this->CI->config->item('debug'))
				{
					$this->write_log('response',print_r($api_response,true));
				}

				$response = new stdClass;
				$response->status = $this->get_status_code($api_response);
				$response->type   = $api_response->type;
				$response->message = $api_response->statusMsg;
				$response->id      = $api_response->id;
				return $response;

			}catch(Exception $ex){
				$response = new stdClass;
				$response->status = 500;
				$response->message = $ex->getMessage();
				$response->type   = null;
				$response->id = null;
				return $response;
			}

		} else {

			$response = new stdClass;
			$response->status = 200;
			$response->message = 'OK';
			$response->type   = null;
			$response->id = null;
			return $response;
		}
	}

}