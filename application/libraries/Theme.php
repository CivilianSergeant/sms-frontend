<?php
/**
 * Created by PhpStorm.
 * User: Office
 * Date: 11/2/2015
 * Time: 1:28 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Theme{
    protected $CI;
    protected $theme;
    protected $layout;
    protected $styles;
    protected $scripts;
    protected $title='Application Title';

    public function __construct(){
        $this->CI =& get_instance();
    }

    /**
     * Set theme that will be used in a controller or method
     * @param $theme
     * @return $this
     */
    public function set_theme($theme)
    {
        $this->theme = 'theme/'.$theme;
        return $this;
    }

    /**
    * Set Title of application
    * @param $title
    * @return $this
    */
    public function set_title($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title of application
     * @return mixed
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Get current theme
     * @return mixed
     */
    public function get_theme()
    {
        return $this->theme;
    }

    /**
     * Return object itself
     * @return $this
     */
    public function get_object()
    {
        return $this;
    }

    /**
     * Add stylesheet to the queue to render in layout
     * @param $style
     * @return $this
     */
    public function add_style($style)
    {
        $this->styles[] = base_url('public/'.$this->theme.'/css/').'/'.$style;
        return $this;
    }

    /**
     * Get stylesheets
     * @return mixed
     */
    public function get_styles()
    {
        return $this->styles;
    }

    /**
     * Add script to the queue to render in layout
     * @param $script
     * @return $this
     */
    public function add_script($script)
    {
        $this->scripts[] = $this->theme.'/js/'.$script;
        return $this;
    }

    /**
     * Get scripts
     * @return mixed
     */
    public function get_scripts()
    {
        return $this->scripts;
    }

    /**
     * Set layout that will be used in a controller or method
     * @param $layout
     * @return $this
     */
    public function set_layout($layout)
    {
        $this->layout = 'layout/'.$layout;
        return $this;
    }

    /**
     * Set sidebar to render in layout
     * @param $sidebar
     * @param $data
     * @return mixed
     */
    public function set_sidebar($sidebar,$data)
    {
        return $this->CI->load->view($this->theme.'/layout/sidebars/'.$sidebar,$data,true);
    }

    public function get_image_path()
    {
        
            return PUBLIC_PATH.$this->theme.'/img/';
        
    }

    /**
     * Set View to render view partially or with layout
     * @param $view
     * @param array $data
     * @param bool $flag
     * @return mixed
     */
    public function set_view($view,$data=array(),$flag=false)
    {

        if($flag)
        {
            $data['content'] = $this->CI->load->view($this->theme.'/'.$view,$data,$flag);


            if(!empty($this->layout))
            {
                $data['scripts'] = $this->get_scripts();
                $data['title']  = $this->get_title();
                $data['styles'] = $this->get_styles();
                $data['script_layout'] = $this->CI->load->view($this->theme.'/layout/resources/scripts',$data,$flag);
                $data['style_layout'] = $this->CI->load->view($this->theme.'/layout/resources/styles',$data,$flag);
                $data['custom_script_layout'] = $this->CI->load->view($this->theme.'/layout/resources/footer_scripts',$data,$flag);

                $this->CI->load->view($this->theme.'/'.$this->layout,$data);

            }
            return $data['content'];
        }else
            $this->CI->load->view($this->theme.'/'.$view,$data);
    }

}