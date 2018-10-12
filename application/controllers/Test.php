<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Test extends CI_Controller
{	
    
    
    public function __construct()
    {
            parent::__construct();

            $this->load->library('services');
            $this->theme->set_theme('katniss');
            $this->theme->set_layout('main');

    }

    public function index()
    {
            //test($this->session->get_userdata('timezone'));
            //$this->session->sess_destroy();
            /*$this->load->model('Menu_model','menus');
            test($this->menus->get_menus(3,'lco'));*/
        $img = PUBLIC_PATH.'theme/katniss/images/1.jpg';
        
echo <<<EOT
        <html>
            <head>
                <title>Transparent Background</title>
            </head>
            <body style="color:#fff;text-align:center;background: center url($img) no-repeat;opacity:0.5;">
                <h1 style="margin-top: 300px;">Hello world</h1>
                <p>is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,</p>
            </body>
        </html>
EOT;
    }
    
    public function upload_image()
    {
        $url = site_url('process-image');
echo <<<EOT
        <form action="{$url}" method="post">
            
            <p>
                <input type="text" placeholder="content id" name="content_id"/>
            </p>
            <p>
                <input type="text" placeholder="image type" name="image_type"/>
            </p>
        
            <p>
                <input type="text" placeholder="user name" name="user_name"/>
            </p>
            <p>
                <input type="text" placeholder="password" name="user_pass"/>
            </p>
        
            <p>
                <input name="submit" type="submit"/>
            </p>
        </form>
EOT;
    }
    
    


    /**
     * Important method
     */
    public function access_denied()
    {
		 $this->theme->set_title('Access Denied')
                ->add_style('component.css');
        $this->load->model('Organization_model','organization');
       	$data['organization']=$this->organization->get_row();
        $this->load->view('theme/katniss/access_denied/access_denied', $data);	
    }

    /**
     * Important method
     */
    public function license_missing()
    {
            $this->theme->set_title('License Missing')
                            ->add_style('component.css');
            $this->load->model('Organization_model','organization');
            $data['organization']=$this->organization->get_row();
            $this->load->view('theme/katniss/access_denied/license_missing', $data);
    }

    /**
     * Important method
     */
    public function no_permission()
    {
            $this->theme->set_title('NO Permission')
                            ->add_style('component.css');
            $this->load->model('Organization_model','organization');
            $data['organization']=$this->organization->get_row();
            $this->load->view('theme/katniss/access_denied/no_permission', $data);
    }

    /**
     * Important method
     */
    public function test_repair()
    {
            $curl_post_data=array(
                     "cardNumber" => "61",
                     "match" => 1,
                     "stbNo" => "8100588000000618",
                     "operatorName" => "administrator"
            );
            $api_string = json_encode($curl_post_data);
            $response = $this->services->repair_cancel_stb_ic($api_string);
            test($response);
    }



    public function import_csv()
    {
            echo '<pre>';
            $row = 0;
            if (($handle = fopen("d:\lco-template Final_csv.csv", "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            //print_r($data);

                            $save_data = array(
                                            'nickname' => $data[0],
                                            'level'    => $data[1],
                                            'name'     => $data[2],
                                            'email'    => $data[3],
                                            'login'    => $data[4],
                                            'pass'     => md5($data[4]),
                                            'address1' => $data[6],
                                            'address2' => $data[7],
                                            'mobile'   => $data[8],
                                            'message_sign' => $data[9],
                                            'nid'       => $data[10],
                                            'country'   => $data[11],
                                            'division'  => $data[12],
                                            'district'  => $data[13],
                                            'area'		=> $data[14],
                                            'sub_area'	=> $data[15]
                            );
                            //print_r($save_data);
                            //$this->db->insert($this->table_name,$data);
                            //$this->db->insert('lco_template',$save_data);
                    }
                    fclose($handle);
            }
    }

    /**
     * Important method for epg, never delete this method
     */
    public function download_epg_image($id,$w)
    {
       
        $this->load->model('Epg_model','epg');
        $epg = $this->epg->find_by_id($id);
        if(!$epg->has_attributes()){
            return false;
        }
        
        $imgData = file_get_contents($epg->get_attribute('original_image'));
        $location = 'public/uploads/program/'.$epg->get_attribute('program_id').'/logo/epg/';
        
        if(!file_exists($location)){
            mkdir($location, 0777, true);
        }
        
        $type = '.png';
        $filename = 'original_epg_logo-'.$id;
        $filelocation = $location.$filename.$type;
        file_put_contents($filelocation, $imgData);
        $this->load->library('png_compressor');

        $h = round(($w/16)*9);
        $temp_path = $location.$filename.'-'.$w.'x'.$h.$type;
        $final_path = $location.'epg-final-'.$id.'-'. str_replace(array(" ","0."),"",microtime()). $type;

        $compressResponse = $this->png_compressor
            ->compress_png($filelocation,$temp_path,$final_path,$w,$h);

        $output = implode(',',$compressResponse['output']);
        if(preg_match('/error/',strtolower($output))){
            echo json_encode(array('status'=>400,'warning_messages'=>$output));
            exit;
        }
        
        unlink($temp_path);
        
        $epg->save([
            'program_logo'     => $final_path,
            'program_poster'   => $filelocation,
            'is_img_processed' => 1
        ],$id);
        
        echo '200 | Image downloaded at '.$filelocation.' '.$final_path.', size '.$w.'x'.$h;
        exit;

    }

}

