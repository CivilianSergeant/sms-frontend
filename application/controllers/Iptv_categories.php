<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Iptv_category_model $Iptv_category
 * @property Iptv_sub_category_model $Iptv_sub_category
 * @property Iptv_category_program_model $Iptv_category_program
 * @property Iptv_program_model $Iptv_program
 */
class Iptv_categories extends BaseController {

    protected $user_session;
    protected $user_type;
    protected $user_id;
    protected $parent_id;
    protected $created_by;
    protected $message_sign;
    protected $role_name;
    protected $role_type;
    protected $role_id;

    const LCO_UPPER = 'LCO';
    const LCO_LOWER = 'lco';
    const MSO_UPPER = 'MSO';
    const MSO_LOWER = 'mso';
    const ADMIN = 'admin';
    const STAFF = 'staff';

    public function __construct() {
        parent::__construct();

        $this->load->library('services');
        $this->theme->set_theme('katniss');
        $this->theme->set_layout('main');

        $this->user_type = strtolower($this->user_session->user_type);
        $this->user_id = $this->user_session->id;
        $this->created_by = $this->user_session->created_by;
        $this->parent_id = $this->user_session->parent_id;
        $role = $this->user->get_user_role($this->user_id);
        $role_name = (!empty($role)) ? strtolower($role->role_name) : '';
        $role_type = (!empty($role)) ? strtolower($role->role_type) : '';
        $this->role_name = $role_name;
        $this->role_type = $role_type;
        $this->role_id = $this->user_session->role_id;

        if ($this->user_type == self::LCO_LOWER) {
            $this->message_sign = $this->lco_profile->get_message_sign($this->user_id);
        }
    }

    public function index() {
        $this->theme->set_title('Categories - Application')
                ->add_style('component.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/iptv/categories.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $uri = $this->uri->segment(1);
        $data['uri'] = $uri;

        $this->theme->set_view('iptv/categories', $data, true);
    }

    public function ajax_check_password() {
        if ($this->input->is_ajax_request()) {
            $password = $this->input->post('password');
            $user = $this->auth->is_username_password_matched($this->user_session->username, md5($password));
            if (!empty($user)) {
                echo json_encode(array('status' => 200, 'user' => $user));
                exit;
            } else {
                echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Password not matched'));
                exit;
            }
        } else {
            redirect('/');
        }
    }

    public function save_category() {
        if ($this->input->is_ajax_request()) {

            if ($this->role_type == "staff") {
                $uri = $this->uri->segment(1);
                $permission = $this->menus->has_create_permission($this->role_id, 1, $uri, $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have create permission"));
                    exit;
                }
            }

            $categoryName = $this->input->post('category_name');
            $id = $this->input->post('subCategoryId');
            $categoryId = $this->input->post('categoryId');
            $subCategoryName = $this->input->post('sub_category_name');
            $parentId = ($this->role_type == self::ADMIN) ? $this->user_id : $this->user_session->parent_id;

            $uri = $this->uri->segment(1);
            if (preg_match('/catchup/', $uri)) {
                $type = 'CATCHUP';
            } else if (preg_match('/vod/', $uri)) {
                $type = 'VOD';
            } else {
                $type = 'LIVE';
            }

            if (!empty($categoryId)) {

                // save sub category
                if (empty($subCategoryName)) {
                    echo json_encode(array('status' => 400, 'warning_messages' => 'Sub Category Cannot be empty'));
                    exit;
                }


                $saveSubCategoryData = array(
                    'sub_category_name' => $subCategoryName,
                    'category_id' => $categoryId,
                    'type' => $type,
                    'parent_id' => $parentId
                );


                if (!empty($id)) {

                    $subCategory = $this->Iptv_sub_category->find_by_id($id);
                    if (!$subCategory->has_attributes()) {
                        echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Sub Category not found'));
                        exit;
                    }

                    if (strtolower($subCategory->get_attribute('sub_category_name')) != strtolower($subCategoryName)) {
                        $hasCategory = $this->Iptv_sub_category->find_by_categoryId_with_subcategory($categoryId, $subCategoryName, $type, $parentId);
                        if (!empty($hasCategory)) {
                            echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Sub Category already exist'));
                            exit;
                        }
                    }


                    $this->Iptv_sub_category->save($saveSubCategoryData, $id);

                    // set notification
                    $title = "Sub category Updated.";
                    $msg = 'Sub category ' . $subCategoryName . ' has been updated successfully';
                    $this->set_notification($title, $msg);

                    echo json_encode(array('status' => 200, 'success_messages' => $msg));
                } else {

                    $hasCategory = $this->Iptv_sub_category->find_by_categoryId_with_subcategory($categoryId, $subCategoryName, $type);
                    if (!empty($hasCategory)) {
                        echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Sub Category already exist'));
                        exit;
                    }
                    $this->Iptv_sub_category->save($saveSubCategoryData);

                    // set notification
                    $title = "Sub category Created.";
                    $msg = 'Sub category ' . $subCategoryName . ' has been created successfully';
                    $this->set_notification($title, $msg);

                    echo json_encode(array('status' => 200, 'success_messages' => $msg));
                }


                exit;
            } else {
                // save category
                if (empty($categoryName)) {
                    echo json_encode(array('status' => 400, 'warning_messages' => 'Category Cannot be empty'));
                    exit;
                }


                $hasCategory = $this->Iptv_category->find_by_name($categoryName, $type, $parentId);
                if (!empty($hasCategory)) {
                    echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Category already exist'));
                    exit;
                }

                $saveData['category_name'] = $categoryName;
                $saveData['type'] = $type;
                $saveData['lsp_type_id'] = $this->user_session->lsp_type_id;
                $saveData['parent_id'] = $parentId;
                $id = $this->input->post('id');
                if (!empty($id)) {
                    $this->Iptv_category->save($saveData, $id);

                    // set notification
                    $title = "Category Updated.";
                    $msg = 'category ' . $categoryName . ' has been updated successfully';
                    $this->set_notification($title, $msg);

                    echo json_encode(array('status' => 200, 'success_messages' => $msg));
                } else {
                    $this->Iptv_category->save($saveData);

                    // set notification
                    $title = "Category Created.";
                    $msg = 'category ' . $categoryName . ' has been created successfully';
                    $this->set_notification($title, $msg);

                    echo json_encode(array('status' => 200, 'success_messages' => $msg));
                }

                exit;
            }
        } else {
            redirect('/');
        }
    }

    public function ajax_get_categories() {
        if ($this->input->is_ajax_request()) {
            $uri = $this->uri->segment(1);
            /* if(preg_match('/free/',$uri)){
              $categories = $this->Iptv_category->get_free_categories();
              }else if(preg_match('/live/',$uri)){
              $categories = $this->Iptv_category->get_live_delay_categories();
              }else if(preg_match('/delay/',$uri)){
              $categories = $this->Iptv_category->get_delay_categories();
              }else */
            $parent_id = ($this->role_type == self::ADMIN) ? $this->user_id : $this->parent_id;
            if (preg_match('/catchup/', $uri)) {
                $categories = $this->Iptv_category->get_catchup_categories($parent_id);
            } else if (preg_match('/vod/', $uri)) {
                $categories = $this->Iptv_category->get_vod_categories($parent_id);
            } else {
                $categories = $this->Iptv_category->get_live_delay_categories($parent_id);
            }

            echo json_encode(array('status' => 200, 'categories' => $categories));
        } else {
            redirect('/');
        }
    }

    public function ajax_get_sub_categories($category_id) {
        if ($this->input->is_ajax_request()) {
            $sub_categories = $this->Iptv_sub_category->find_by_category_id($category_id);
            echo json_encode(array('status' => 200, 'sub_categories' => $sub_categories));
        } else {
            redirect('/');
        }
    }

    public function ajax_get_programs() {
        if ($this->input->is_ajax_request()) {
            $uri = $this->uri->segment(1);
            /* if(preg_match('/free/',$uri)){
              $programs = $this->Iptv_program->get_free_programs();
              }else if(preg_match('/live/',$uri)){
              $programs = $this->Iptv_program->get_live_delay_programs();
              }else if(preg_match('/delay/',$uri)){
              $programs = $this->Iptv_program->get_delay_programs();
              }else */

            if (preg_match('/catchup/', $uri)) {
                $programs = $this->Iptv_program->get_catchup_programs();
            } else if (preg_match('/vod/', $uri)) {
                $programs = $this->Iptv_program->get_vod_programs();
            } else {
                $programs = $this->Iptv_program->get_live_delay_programs();
            }

            echo json_encode(array('status' => 200, 'programs' => $programs));
            exit;
        } else {
            redirect('/');
        }
    }

    public function ajax_get_selected_programs($id) {
        if ($this->input->is_ajax_request()) {
            $subCategory = $this->Iptv_sub_category->find_by_id($id);
            if ($subCategory->has_attributes()) {

                $category_id = $subCategory->get_attribute('category_id');
                $sub_category_id = $subCategory->get_attribute('id');
                $results = $this->Iptv_category_program->find_all_programs($category_id, $sub_category_id);
                $uri = $this->uri->segment(1);

                /* if(preg_match('/free/',$uri)){
                  $programs = $this->Iptv_program->get_free_programs();
                  }else if(preg_match('/live/',$uri)){

                  }else if(preg_match('/delay/',$uri)){
                  $programs = $this->Iptv_program->get_delay_programs();
                  }else */

                if (preg_match('/catchup/', $uri)) {
                    $programs = $this->Iptv_category_program->get_catchup_programs();
                } else if (preg_match('/vod/', $uri)) {
                    $programs = $this->Iptv_category_program->get_vod_programs();
                } else {
                    $programs = $this->Iptv_program->get_live_delay_programs();
                }

                $assigned_programs = array();

                if (!empty($results)) {
                    foreach ($results as $r) {

                        foreach ($programs as $i => $p) {
                            if ($r->program_id == $p->id) {
                                $assigned_programs[] = $p;
                                unset($programs[$i]);
                            }
                        }
                    }
                }

                echo json_encode(array('status' => 200, 'programs' => $programs, 'assigned_programs' => $assigned_programs));
                exit;
            }
        } else {
            redirect('/');
        }
    }

    public function assign_program_category() {
        if ($this->input->is_ajax_request()) {

            $category_id = $this->input->post('category_id');
            $sub_category_id = $this->input->post('sub_category_id');
            $programs = $this->input->post('programs');
            //$this->Iptv_category_program->remove($category_id,$sub_category_id);

            if (empty($category_id)) {
                echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Please select category'));
                exit;
            }

            if (empty($sub_category_id)) {
                echo json_encode(array('status' => 400, 'warning_messages' => 'Sorry! Please select sub category'));
                exit;
            }



            foreach ($programs as $p) {
                $saveData = array(
                    'category_id' => $category_id,
                    'sub_category_id' => $sub_category_id,
                    'program_id' => $p,
                    'updated_at' => date("Y-m-d H:i:s")
                );

                $this->Iptv_category_program->save($saveData);
            }

            // set notification
            $title = "Content Assigned.";
            $msg = 'Content' . ((count($programs) > 1) ? "'s" : '') . ' Successfully assigned';
            $this->set_notification($title, $msg);

            echo json_encode(array('status' => 200, 'success_messages' => $msg));
            exit;
        } else {
            redirect('/');
        }
    }

    public function delete_category_program() {
        if ($this->input->is_ajax_request()) {
            if ($this->role_type == "staff") {
                $uri = $this->uri->segment(1);
                $permission = $this->menus->has_delete_permission($this->role_id, 1, $uri, $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have delete permission"));
                    exit;
                }
            }

            $program_id = $this->input->post('id');
            $category_id = $this->input->post('category_id');
            $sub_category_id = $this->input->post('sub_category_id');

            $this->Iptv_category_program->remove_program($category_id, $sub_category_id, $program_id);

            $program = $this->Iptv_program->find_by_id($program_id);

            // set notification
            $title = "Content Unassigned.";
            $msg = 'Content [' . $program->get_attribute('program_name') . '] successfully unassigned';
            $this->set_notification($title, $msg);

            echo json_encode(array('status' => 200, 'success_messages' => $msg));
        } else {
            redirect('/');
        }
    }

    public function delete_category($id) {
        if ($this->input->is_ajax_request()) {
            $uri = $this->uri->segment(1);

            if ($this->role_type == self::STAFF) {
                $permission = $this->menus->has_delete_permission($this->role_id, 1, $uri, $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have delete permission"));
                    exit;
                }
            }

            $category = $this->Iptv_category->find_by_id($id);
            if (!$category->has_attributes()) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! there is no category to delete, please refresh page"));
                exit;
            }

            $category_name = $category->get_attribute('category_name');

            $sub_category = $this->Iptv_sub_category->find_by_category_id($category->get_attribute('id'));
            if (!empty($sub_category)) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! Unable to delete category {$category_name}, Sub-category exist associate with it"));
                exit;
            }

            $programs = $this->Iptv_category_program->find_all_programs($category->get_attribute('id'));
            if (!empty($programs)) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! Category cannot be deleted. Program assigned to Category $category_name"));
                exit;
            }



            $this->Iptv_category->remove($id);

            // set notification
            $title = "Content Category Deleted.";
            $msg = $category_name . ' was successfully deleted';
            $this->set_notification($title, $msg);

            echo json_encode(array('status' => 200, 'success_messages' => $msg));
            exit;
        } else {
            redirect('/');
        }
    }

    public function delete_sub_category($id) {
        if ($this->input->is_ajax_request()) {
            //test($id);
            $uri = $this->uri->segment(1);
            if ($this->role_type == self::STAFF) {
                $permission = $this->menus->has_delete_permission($this->role_id, 1, $uri, $this->user_type);
                if (!$permission) {
                    echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! You don't have deleted permission"));
                    exit;
                }
            }

            $subCategory = $this->Iptv_sub_category->find_by_id($id);
            if (!$subCategory->has_attributes()) {
                echo json_encode(array('status' => 400, 'warning_messagse' => "Sorry! there is no sub category to delete, please refresh page"));
                exit;
            }
            $sub_category_name = $subCategory->get_attribute('sub_category_name');
            $category_id = $subCategory->get_attribute('category_id');
            $sub_category_id = $subCategory->get_attribute('id');
            $programs = $this->Iptv_category_program->find_all_programs($category_id, $sub_category_id);
            if (!empty($programs)) {
                echo json_encode(array('status' => 400, 'warning_messages' => "Sorry! Item cannot be deleted. Program assigned to Sub Category $sub_category_name"));
                exit;
            }

            $this->Iptv_sub_category->remove($id);

            // set notification
            $title = "Content Sub-Category Deleted.";
            $msg = $sub_category_name . ' was successfully deleted';
            $this->set_notification($title, $msg);

            echo json_encode(array('status' => 200, 'success_messages' => $msg));
            exit;
        } else {
            redirect('/');
        }
    }

    public function app_categories() {
        $this->theme->set_title('App Categories - Application')
                ->add_style('component.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/iptv/app_categories.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();

        $this->theme->set_view('iptv/app-category/app_categories', $data, true);
    }

    public function ajax_get_app_categories() {
        if ($this->input->is_ajax_request()) {
            $take = $this->input->get('take');
            $skip = $this->input->get('skip');
            $filter = $this->input->get('filter');
            $sort = $this->input->get('sort');
            $categories = $this->app_categories->get_all_app_categories($take, $skip, $filter, $sort);
            $total = $this->app_categories->get_count_all_categories();
            echo json_encode(array('status' => 200, 'total' => $total, 'categories' => $categories));
            exit;
        } else {
            redirect('/');
        }
    }

    public function save_app_category() {
        $res = $this->app_categories->cat_duplicate_check($this->input->post('category_name'));
        if(!empty($res)){
            echo json_encode(array('status' => 400, 'warning_messages' => "Category already exist"));
            exit;
        }
        $categoryData = array(
            'category_name' => $this->input->post('category_name'),
            'order_index' => $this->input->post('order_index'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->created_by,
            'parent_id' => $this->parent_id
        );
        $catId = $this->app_categories->save($categoryData);
        $programs = $this->input->post('programs');
        if (isset($catId)) {
            foreach ($programs as $program) {
                $programData = array(
                    'category_id' => $catId,
                    'content_id' => $program['id'],
                    'order_index' => $program['index'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->created_by,
                    'parent_id' => $this->parent_id
                );
//                $res = $this->categories_programs->category_programs_duplicate_check($catId, $programData['content_id']);
//                if(empty($res)){
                    $this->categories_programs->save($programData);
                //}
            }
        }
        echo json_encode(array('status' => 200, 'success_messages' => 'Category Created Successfully'));
    }

    public function edit_app_category($id) {
        $this->theme->set_title('App Categories - Application')
                ->add_style('component.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/iptv/edit_app_categories.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $data['cat_id'] = $id;

        $this->theme->set_view('iptv/app-category/edit_app_categories', $data, true);
    }

    public function get_appcat_data() {
        $id = $this->input->get('id');

        $category_data = $this->app_categories->get_category_data_by_id($id);
        $category_program_data = $this->categories_programs->get_category_programs_cat_id($id);

        echo json_encode(array('status' => 200, 'cat_data' => $category_data, 'program_data' => $category_program_data));
    }

    public function view_app_category($id) {
        $this->theme->set_title('App Categories - Application')
                ->add_style('component.css')
                ->add_style('kendo/css/kendo.common-bootstrap.min.css')
                ->add_style('kendo/css/kendo.bootstrap.min.css')
                ->add_style('component.css')
                ->add_script('controllers/iptv/edit_app_categories.js');

        $data['user_info'] = $this->user_session;
        $data['left_sidebar'] = $this->theme->set_sidebar('left', $data);
        $data['theme'] = $this->theme->get_image_path();
        $data['cat_id'] = $id;

        $this->theme->set_view('iptv/app-category/view_app_categories', $data, true);
    }

    public function update_app_category() {
        $id = $this->input->post('id');
        $categoryData = array(
            'category_name' => $this->input->post('category_name'),
            'order_index' => $this->input->post('order_index'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->created_by,
        );
        $catId = $this->app_categories->save($categoryData, $id);
        $programs = $this->input->post('programs');
        $this->categories_programs->delete_category_programs($id);

        foreach ($programs as $program) {

            $programData = array(
                'category_id' => $id,
                'content_id' => $program['content_id'],
                'order_index' => $program['index'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->created_by,
                'parent_id' => $this->parent_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->created_by,
            );
            $this->categories_programs->save($programData);
        }
        echo json_encode(array('status' => 200, 'success_messages' => 'Category Updated Successfully'));
    }

    public function ajax_get_all_programs() {
        $type = $this->input->get('type');
        $programs = $this->app_categories->get_iptv_programs($type);
        echo json_encode(array('status' => 200, 'programs' => $programs));
    }
    
    public function search_programs() {
        $search_key = $this->input->get('search_key');
        $type = $this->input->get('type');
        $programs = $this->app_categories->search_iptv_programs($search_key, $type);
        echo json_encode(array('status' => 200, 'programs' => $programs));
    }

    /**
     * Set Notification With determine Who is the use
     * LCO Admin, MSO Admin or LCO Staff
     * @param string $title Title of Notification
     * @param string $msg Message of Notification
     */
    private function set_notification($title, $msg) {
        if ($this->user_type == self::MSO_LOWER) {

            if ($this->role_type == self::ADMIN) {
                $this->notification->save_notification($this->user_id, $title, $msg, $this->user_session->id);
            } elseif ($this->role_type == self::STAFF) {
                $this->notification->save_notification($this->parent_id, $title, $msg, $this->user_session->id);
            }
        } elseif ($this->user_type == self::LCO_LOWER) {


            if ($this->role_type == self::ADMIN) {
                $this->notification->save_notification($this->user_id, $title, $msg, $this->user_session->id);
            } elseif ($this->role_type == self::STAFF) {
                $this->notification->save_notification($this->parent_id, $title, $msg, $this->user_session->id);
            }
        }
    }

}
