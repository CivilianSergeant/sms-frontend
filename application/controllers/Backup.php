<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/31/2016
 * Time: 11:34 AM
 */
class Backup extends BaseController
{
    protected $user_session;
    protected $user_type;
    protected $user_id;
    protected $parent_id;
    protected $message_sign;
    protected $role_name;
    protected $role_type;
    protected $role_id;

    const LCO_UPPER='LCO';
    const LCO_LOWER='lco';
    const MSO_UPPER='MSO';
    const MSO_LOWER='mso';
    const ADMIN = 'admin';
    const STAFF = 'staff';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('services');
        $this->theme->set_theme('katniss');
        $this->theme->set_layout('main');

        $this->user_type = strtolower($this->user_session->user_type);
        $this->user_id = $this->user_session->id;
        $this->parent_id = $this->user_session->parent_id;

        $role = $this->user->get_user_role($this->user_id);
        $role_name = (!empty($role))?  strtolower($role->role_name) : '';
        $role_type = (!empty($role))?  strtolower($role->role_type) : '';
        $this->role_name = $role_name;
        $this->role_type = $role_type;
        $this->role_id = $this->user_session->role_id;

        if($this->user_type != self::MSO_LOWER){
            redirect('/');
        }
    }

    public function index()
    {
        $this->theme->set_title('System Backup')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/backup/backup.js');


        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $this->theme->set_view('backup/backup',$data,true);
    }

    public function transfer_file($file_name=null)
    {
        if($file_name == null){
            redirect('/');
        }

        $location = 'downloads/backup/';
        $file_name = $location.$file_name;
        if(!file_exists($file_name)){
            $this->set_flashdata('warning_messages','No file found for transfer');
            redirect('/');
        }

        $this->theme->set_title('Transfer Backup')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/backup/backup.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();
        $data['file_name'] = $file_name;
        $fileInfo = get_file_info($file_name);
        $data['datetime'] = date('Y-m-d H:i:s',$fileInfo['date']);
        $data['size']     = number_format((($fileInfo['size']/1024)/1024),2).' M';
        $this->theme->set_view('backup/transfer',$data,true);
    }

    public function transfer()
    {
        if($this->input->is_ajax_request()){

            $ftp_account_id = $this->input->post('ftp_account');
            $upload_file_name = $this->input->post('file_name');

            if(!file_exists($upload_file_name)){
                echo json_encode(array('status'=>400, 'warning_messages'=>'Sorry! '.$upload_file_name.' file not found'));
                exit;
            }

            $ftp_account = $this->ftp_account->find_by_id($ftp_account_id);

            if($ftp_account->has_attributes()){

                $run_as_background = $this->input->post('run_as_background');

                $ftp_server_ip = $ftp_account->get_attribute('server_ip');
                $ftp_server_port = $ftp_account->get_attribute('server_port');
                $ftp_user_id = $ftp_account->get_attribute('user_id');
                $ftp_password = $ftp_account->get_attribute('password');
                $ftp_dir_location = $ftp_account->get_attribute('dir_location');

                if(!empty($run_as_background)){
                    $this->db->insert('ftp_transfer_logs',array(
                       'ftp_account_id' => $ftp_account->get_attribute('id'),
                       'file_name'      => $upload_file_name,
                       'type'           => 'BACKGROUND'
                    ));

                    echo json_encode(array('status'=>200,'success_messages'=>'FTP transfer request to '.$ftp_server_ip.':'.$ftp_server_port.' successfully received'));
                    exit;
                }else {


                    set_time_limit(0);
                    $ftp_connection = ftp_connect($ftp_server_ip, $ftp_server_port);

                    if ($ftp_connection == false) {
                        echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Unable to establish connection to ' . $ftp_server_ip . ':' . $ftp_server_port));
                        exit;
                    }

                    $loginSuccess = ftp_login($ftp_connection, $ftp_user_id, $ftp_password);
                    if (!$loginSuccess) {
                        echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Unable to logged-in, User ID or Password not valid'));
                        exit;
                    }

                    $r = explode('/', $upload_file_name);
                    $file_name = $r[count($r) - 1];

                    if (!empty($ftp_dir_location)) {
                        $ftp_dir_location = str_replace('\\', '/', $ftp_dir_location);

                        $lastChar = substr($ftp_dir_location, strlen($ftp_dir_location) - 1, strlen($ftp_dir_location));

                        if ($lastChar != '/') {
                            $ftp_dir_location .= '/';
                        }
                    } else {
                        $ftp_dir_location = '/';
                    }


                    $file = $file_name;
                    $ftp_remote_file = $ftp_dir_location . $file;

                    ftp_pasv($ftp_connection, true);


                    if (ftp_put($ftp_connection, $ftp_remote_file, $upload_file_name, FTP_BINARY)) {

                        $this->db->insert('ftp_transfer_logs',array(
                            'ftp_account_id' => $ftp_account->get_attribute('id'),
                            'file_name'      => $upload_file_name,
                            'status'         => 1,
                            'done_time'      => date('Y-m-d H:i:s'),
                            'type'           => 'INSTANT'
                        ));

                        echo json_encode(array('status' => 200, 'success_messages' => $file . ' has been transfered successfully'));
                        exit;
                    } else {

                        $this->db->insert('ftp_transfer_logs',array(
                            'ftp_account_id' => $ftp_account->get_attribute('id'),
                            'file_name'      => $upload_file_name,
                            'status'         => 0,
                            'done_time'      => null,
                            'type'           => 'INSTANT'
                        ));

                        echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! ' . $file . ' was unable to transfer'));
                        exit;
                    }


                    ftp_close($ftp_connection);
                }


            }else{
                echo json_encode(array('400','warning_messages'=>'Sorry! FTP account not found'));
                exit;
            }


        }else{
            redirect('/');
        }
    }

    public function backup_logs()
    {
        $this->theme->set_title('DB Backup Logs')
            ->add_style('component.css')
            ->add_style('kendo/css/kendo.common-bootstrap.min.css')
            ->add_style('kendo/css/kendo.bootstrap.min.css')
            ->add_style('component.css')
            ->add_script('controllers/backup/backup-logs.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left',$data);
        $data['theme'] = $this->theme->get_image_path();

        $this->theme->set_view('reports/backup-logs',$data,true);
    }

    public function ajax_get_db_backup_logs()
    {
        if($this->input->is_ajax_request()){
            $take = $this->input->post('take');
            $skip = $this->input->post('skip');
            $filter = $this->input->post('filter');
            $sort = $this->input->post('sort');

            $logs = $this->ftp_account->get_ftp_transfer_logs($take,$skip,$filter,$sort);
            $total= $this->ftp_account->count_ftp_transfer_logs($filter);
            echo json_encode(array('status'=>200,'logs'=>$logs,'total'=>$total));

        }else{
            redirect('/');
        }
    }

    public function ajax_get_ftp_accounts()
    {
        if($this->input->is_ajax_request()){
            $id = ($this->role_type == self::STAFF)? $this->parent_id : $this->user_id;
            $accounts = $this->ftp_account->get_all_accounts($id);
            echo json_encode(array('status'=>200,'accounts'=>$accounts));
        }else{
            redirect('/');
        }
    }

    public function ajax_check_password()
    {
        if($this->input->is_ajax_request()){
            $password = $this->input->post('password');
            $user = $this->auth->is_username_password_matched($this->user_session->username,md5($password));
            if(!empty($user)){
                echo json_encode(array('status'=>200,'user'=>$user));
                exit;
            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Password not matched'));
                exit;
            }

        }else{
            redirect('/');
        }
    }

    public function ajax_dump_file_list()
    {
        if($this->input->is_ajax_request()){

            $files = get_filenames('downloads/backup');
            $list = array();
            foreach($files as $i=>$file){
                $list[$i]['filename'] = $file;
                $fileInfo = get_file_info('downloads/backup/'.$file);
                $list[$i]['datetime']=date('Y-m-d H:i:s',$fileInfo['date']);
                $list[$i]['size']= number_format((($fileInfo['size']/1024)/1024),2).' M';
            }
            $list = array_key_sort($list,'datetime',SORT_DESC);
            echo json_encode(array('files'=>$list));
        }else{
            redirect('/');
        }
    }

    public function download($file_name)
    {
        $location = 'downloads/backup/';
        $file_name = $location.$file_name;
        if (file_exists($file_name)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
            readfile($file_name);

            /*if(file_exists($file_name)){
                unlink($file_name);
            }*/

            exit;


        }
    }

    public function dump()
    {

        $hostname = $this->db->hostname;
        $username = $this->db->username;
        $password = $this->db->password;
        $database = $this->db->database;

        $sourceDir = realpath('downloads/backup').'/';
        $sourceDir = str_replace('\\','/',$sourceDir);

        $dumpfile =  $database.'_'.date('Ymdhis').'.zip';

        $rp = realpath('mysqldump.jar');

        $command = 'java -jar '.$rp.' -h'.$hostname.':3306 -u'.$username.' -p'.$password.' -D'.$database.' -d'.$sourceDir.' -f'.$dumpfile;

        exec($command,$arr,$status);


        if(!$status){
            $this->set_notification('DB backup '.$dumpfile, 'Database backup successfully done');
            echo json_encode(array('status'=>200,'success_messages'=>'Database backup successfully done'));
            exit;
        }else{

            echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! Unable to backup database'));
            exit;
        }

        /*exec($command,$arr,$v);
        echo '<pre>';
        print_r($arr);*/

        /*$db_name = 'Tables_in_'.$this->db->database;

        $sqlText = "SET GLOBAL max_allowed_packet=".((100*1024)*1024).";\r\n\r\nDROP DATABASE IF EXISTS `{$this->db->database}`; CREATE DATABASE `{$this->db->database}`; \r\n\r\nUSE `{$this->db->database}`;";

        $location = 'downloads/backup';
        if(!file_exists($location)){
            mkdir($location,0777,true);
        }

        $dumpLocation = $location.'/dump_'.date('Ymdhis').'.sql';
        //file_put_contents($dumpLocation, '--'.date('Y-m-d H:i:s') . "\r\n\r\n");
        file_put_contents($dumpLocation, $sqlText . "\r\n\r\n");

        file_put_contents($dumpLocation, '--' . "\r\n", FILE_APPEND);
        file_put_contents($dumpLocation, '-- Functions' . "\r\n", FILE_APPEND);
        file_put_contents($dumpLocation, '--' . "\r\n\r\n", FILE_APPEND);


        // Dump functions
        $functions = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_SCHEMA = '{$this->db->database}'")->result();
        if(!empty($functions)){
            file_put_contents($dumpLocation, 'DELIMITER $$' . "\r\n", FILE_APPEND);
            foreach($functions as $func){
                if($func->ROUTINE_TYPE != 'PROCEDURE'){

                    $functionName = $func->ROUTINE_NAME;
                    $ddl = $this->db->query("show create function `{$functionName}`")->result();
                    $ddl = array_shift($ddl);
                    if(property_exists($ddl,'Create Function')){
                        file_put_contents($dumpLocation, $ddl->{'Create Function'}.'$$' . "\r\n\r\n", FILE_APPEND);
                    }
                }

            }
            file_put_contents($dumpLocation, 'DELIMITER ;' . "\r\n\r\n", FILE_APPEND);
        }

        // Dump tables
        $q = $this->db->query('show tables');
        $tables = $q->result();
        if(!empty($tables)) {

            file_put_contents($dumpLocation, '--' . "\r\n", FILE_APPEND);
            file_put_contents($dumpLocation, '-- Tables' . "\r\n", FILE_APPEND);
            file_put_contents($dumpLocation, '--' . "\r\n\r\n", FILE_APPEND);

            foreach ($tables as $i => $table) {
                $tableName = $table->$db_name;
                $ddl = $this->db->query("SHOW CREATE TABLE `".$tableName."`");
                $ddl = $ddl->result();
                $ddl = array_shift($ddl);
                if(property_exists($ddl,'Create Table')){

                    file_put_contents($dumpLocation,"-- Table structure for table `{$tableName}` "."\r\n\r\n",FILE_APPEND);
                    $dropTable = "DROP TABLE IF EXISTS `{$tableName}`;";
                    file_put_contents($dumpLocation,$dropTable."\r\n\r\n",FILE_APPEND);

                    $createStatement = $ddl->{'Create Table'};
                    file_put_contents($dumpLocation, $createStatement . ";\r\n\r\n", FILE_APPEND);

                    $descriptions = $this->db->query('DESCRIBE `'.$tableName.'`')->result();
                    $qvalues = $this->db->query("SELECT * FROM  `$tableName`")->result_array();

                    $keys = array();
                    $types = array();
                    if(!empty($qvalues)){
                        if(!empty($descriptions)){
                            foreach($descriptions as $d){
                                $types[] = $d->Type;
                                $keys[] = $d->Field;
                            }

                            file_put_contents($dumpLocation,"INSERT INTO `{$tableName}` (`".implode("`,`",$keys)."`) VALUES "."\r\n",FILE_APPEND);
                        }


                        $count = count($qvalues);
                        foreach($qvalues as $c=>$value){

                            $data = array_values($value);

                            $values = array();
                            foreach($data as $i=>$d){

                                $dataType = $types[$i];

                                if(preg_match('/tinyint/',strtolower($dataType))){
                                    if(empty(trim($d)))
                                        $d = '0';
                                    $values[] = $d;
                                }else if(preg_match('/int/',strtolower($dataType))){
                                    if(empty(trim($d)))
                                        $d = 'NULL';
                                    $values[] = $d;
                                }else{
                                    if(empty(trim($d)))
                                        $d = 'NULL';
                                    else
                                        $d =addslashes($d);
                                    $values[] = "'{$d}'";
                                }

                            }

                            if(($count-1) == $c)
                            {
                                file_put_contents($dumpLocation,"(".implode(',',$values).")",FILE_APPEND);
                            }else{
                                file_put_contents($dumpLocation,"(".implode(',',$values)."),\r\n",FILE_APPEND);
                            }

                        }

                        file_put_contents($dumpLocation,";\r\n\r\n",FILE_APPEND);
                    }


                }

            } // end tables loop
        }

        // Dump PROCEDURES
        file_put_contents($dumpLocation, '--' . "\r\n", FILE_APPEND);
        file_put_contents($dumpLocation, '-- PROCEDURES' . "\r\n", FILE_APPEND);
        file_put_contents($dumpLocation, '--' . "\r\n\r\n", FILE_APPEND);
        $functions = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_SCHEMA = '{$this->db->database}'")->result();
        if(!empty($functions)){
            file_put_contents($dumpLocation, 'DELIMITER $$' . "\r\n", FILE_APPEND);
            foreach($functions as $func){
                if($func->ROUTINE_TYPE == 'PROCEDURE'){

                    $functionName = $func->ROUTINE_NAME;
                    $ddl = $this->db->query("show create procedure `{$functionName}`")->result();
                    $ddl = array_shift($ddl);
                    if(property_exists($ddl,'Create Procedure')){
                        file_put_contents($dumpLocation, $ddl->{'Create Procedure'}.'$$' . "\r\n\r\n", FILE_APPEND);
                    }
                }

            }
            file_put_contents($dumpLocation, 'DELIMITER ;' . "\r\n\r\n", FILE_APPEND);
        }

        // Dump Views
        $q = $this->db->query('show tables');
        $tables = $q->result();
        $tables = array_reverse($tables);
        if(!empty($tables)) {

            file_put_contents($dumpLocation, '--' . "\r\n", FILE_APPEND);
            file_put_contents($dumpLocation, '-- Views' . "\r\n", FILE_APPEND);
            file_put_contents($dumpLocation, '--' . "\r\n\r\n", FILE_APPEND);

            foreach ($tables as $i => $table) {
                $tableName = $table->$db_name;

                $ddl = $this->db->query("SHOW CREATE TABLE `".$tableName."`")->result();
                $ddl = array_shift($ddl);
                if(property_exists($ddl,'Create View')){
                    file_put_contents($dumpLocation,"-- Structure for view `{$table->$db_name}` "."\r\n\r\n",FILE_APPEND);
                    $dropTable = "DROP TABLE IF EXISTS `{$tableName}`";
                    file_put_contents($dumpLocation,$dropTable.";\r\n\r\n",FILE_APPEND);
                    $createStatement = $ddl->{'Create View'};
                    file_put_contents($dumpLocation, $createStatement . ";\r\n\r\n", FILE_APPEND);
                    $descriptions = $this->db->query('DESCRIBE `'.$tableName.'`')->result();
                    $qvalues = $this->db->query("SELECT * FROM  `$tableName`")->result_array();

                    //test($qvalues);
                    $keys = array();
                    $types = array();
                    if(!empty($qvalues)){
                        if(!empty($descriptions)){
                            foreach($descriptions as $d){
                                $types[] = $d->Type;
                                $keys[] = $d->Field;
                            }

                            file_put_contents($dumpLocation,"INSERT INTO `{$tableName}` (`".implode("`,`",$keys)."`) VALUES "."\r\n",FILE_APPEND);
                        }


                        $count = count($qvalues);
                        foreach($qvalues as $c=>$value){

                            $data = array_values($value);

                            $values = array();
                            foreach($data as $i=>$d){

                                $dataType = $types[$i];

                                if(preg_match('/tinyint/',strtolower($dataType))){
                                    if(empty(trim($d)))
                                        $d = '0';
                                    $values[] = $d;
                                }else if(preg_match('/int/',strtolower($dataType))){
                                    if(empty(trim($d)))
                                        $d = 'NULL';
                                    $values[] = $d;
                                }else{
                                    if(empty(trim($d)))
                                        $d = 'NULL';
                                    else
                                        $d =addslashes($d);
                                    $values[] = "'{$d}'";
                                }

                            }

                            if(($count-1) == $c)
                            {
                                file_put_contents($dumpLocation,"(".implode(',',$values).")",FILE_APPEND);
                            }else{
                                file_put_contents($dumpLocation,"(".implode(',',$values)."),\r\n",FILE_APPEND);
                            }

                        }

                        file_put_contents($dumpLocation,";\r\n\r\n",FILE_APPEND);
                    }
                }
            } // end view loop
        }

        // Dump TRIGGERS
        $triggers = $this->db->query('show triggers')->result();
        file_put_contents($dumpLocation, '--' . "\r\n", FILE_APPEND);
        file_put_contents($dumpLocation, '-- Triggers' . "\r\n", FILE_APPEND);
        file_put_contents($dumpLocation, '--' . "\r\n\r\n", FILE_APPEND);
        if(!empty($triggers)){
            file_put_contents($dumpLocation, 'DELIMITER $$' . "\r\n\r\n", FILE_APPEND);
            foreach($triggers as $trigger){
                $triggerName = $trigger->Trigger;
                $ddl = $this->db->query("show create trigger `{$triggerName}`")->result();
                $ddl = array_shift($ddl);

                if(property_exists($ddl,'SQL Original Statement')){
                    $createStatement = $ddl->{'SQL Original Statement'};
                    file_put_contents($dumpLocation, $createStatement . "$$\r\n\r\n", FILE_APPEND);
                }

            }
            file_put_contents($dumpLocation, 'DELIMITER ;' . "\r\n\r\n", FILE_APPEND);
        }*/

    }

    public function delete()
    {
        if($this->input->is_ajax_request()){
            $filename = $this->input->post('file');
            $location = 'downloads/backup/';
            if(!empty($filename) && file_exists($location.$filename)){
                unlink($location.$filename);
                $message = $filename.' has been successfully deleted';
                $this->set_notification($location.$filename.'Db backup deleted',$message);
                echo json_encode(array('status'=>200,'success_messages'=>$message));
                exit;
            }else{
                echo json_encode(array('status'=>400,'warning_messages'=>'Sorry! No Such file found to delete'));
                exit;
            }
        }else{
            redirect('/');
        }
    }

    /**
     * Set Notification With determine Who is the use
     * LCO Admin, MSO Admin or LCO Staff
     * @param string $title Title of Notification
     * @param string $msg Message of Notification
     */
    private function set_notification($title,$msg)
    {
        if($this->user_type == self::MSO_LOWER){
            if($this->role_type==self::ADMIN)
            {
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);

            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->created_by,$title,$msg,$this->user_session->id);
            }
        }elseif($this->user_type==self::LCO_LOWER){

            if($this->role_type==self::ADMIN)
            {
                $this->notification->save_notification($this->user_id,$title,$msg,$this->user_session->id);

            }elseif($this->role_type==self::STAFF){
                $this->notification->save_notification($this->created_by,$title,$msg,$this->user_session->id);
            }
        }
    }


}