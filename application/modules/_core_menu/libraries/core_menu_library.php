<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Core_menu_library
{
    /**
     * The CI super object.
     *
     * @var object
     */
    private static $CI;

    /**
     * The menus uri.
     *
     * @var string
     */
    public $menus_uri;

    /**
     * The add menu uri.
     *
     * @var string
     */
    public $menu_add_uri;

    /**
     * The edit menu uri.
     *
     * @var string
     */
    public $menu_edit_uri;

    /**
     * The delete menu uri.
     *
     * @var string
     */
    public $menu_delete_uri;

    function __construct()
    {
        self::$CI =& get_instance();

        // Load the config and language.
        self::$CI->load->config('_core_menu/core_menu_config');
        self::$CI->lang->load('_core_menu/core_menu');

        // Load the libraries.
        self::$CI->load->library('form_validation');

        // Load the helpers.
        self::$CI->load->helper('language');
        self::$CI->load->helper('date');

        // Load the models.
        self::$CI->load->model('_core_menu/core_menu_model');

        // Set the uri's.
        $this->menus_uri          = self::$CI->config->item('menus_uri');
        $this->menu_add_uri       = self::$CI->config->item('menu_add_uri');
        $this->menu_edit_uri      = self::$CI->config->item('menu_edit_uri');
        $this->menu_delete_uri    = self::$CI->config->item('menu_delete_uri');
        $this->menu_link_add_uri  = self::$CI->config->item('menu_link_add_uri');
        $this->menu_link_edit_uri = self::$CI->config->item('menu_link_edit_uri');
    }

    /**
     * Set the form validation rules.
     *
     * @param string $rules
     */
    public function set_validation_rules($rules = NULL)
    {
        $menu_insert = array(
            array(
                'field' => 'menu_name',
                'label' => 'Menu name',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'parent_link_id',
                'label' => 'Parent link',
                'rules' => 'integer|xss_clean'
            ),
        );
        $menu_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'required|trim|integer|max_length[1]|xss_clean'
            ),
            array(
                'field' => 'menu_name',
                'label' => 'Menu name',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'parent_link_id',
                'label' => 'Parent link',
                'rules' => 'integer|xss_clean'
            ),
        );
        $menu_link_insert = array(
            array(
                'field' => 'parent_menu_id',
                'label' => 'Parent menu',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'external',
                'label' => 'External',
                'rules' => 'trim|integer|max_length[1]|xss_clean',
            ),
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'text',
                'label' => 'Text',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'link',
                'label' => 'Link',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'permissions',
                'label' => 'Permissons',
                'rules' => 'trim|xss_clean',
            ),
        );
        $menu_link_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'required|trim|integer|max_length[1]xss_clean',
            ),
            array(
                'field' => 'parent_menu_id',
                'label' => 'Parent menu',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'external',
                'label' => 'External',
                'rules' => 'trim|integer|max_length[1]|xss_clean',
            ),
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'text',
                'label' => 'Text',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'link',
                'label' => 'Link',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'permissions',
                'label' => 'Permissons',
                'rules' => 'trim|xss_clean',
            ),
        );

        $rule_set = '';

        switch ($rules)
        {
            case 'menu_insert':
                $rule_set = $menu_insert;
                break;
            case 'menu_update':
                $rule_set = $menu_update;
                break;
            case 'menu_link_insert':
                $rule_set = $menu_link_insert;
                break;
            case 'menu_link_update':
                $rule_set = $menu_link_update;
                break;
        }

        self::$CI->form_validation->set_rules($rule_set);
    }


    /**
     * Check if user has permission to view menu item.
     *
     * @param array $roles
     * @return boolean
     */
    public function menu_link_check_permissions($roles = array())
    {
        // Set the user role.
        if (self::$CI->session->userdata('role'))
        {
            // From session if logged in.
            $user_role = self::$CI->session->userdata('role');
        }
        else
        {
            // NULL if not logged in.
            $user_role = NULL;
        }

        // Return result.
        return (in_array($user_role, $roles)) ? TRUE : FALSE;
    }

    /**
     * Find a menu.
     *
     * @param string $by
     * @param mixed $identifier
     * @return object
     */
    public function menu_find($by = 'id', $identifier = NULL)
    {
        // Get the menu.
        $menu = self::$CI->core_menu_model->menu_find($by, $identifier);

        // Return result.
        return ($menu) ? $menu : FALSE;
    }

    /**
     * Find all menus.
     *
     * @return array
     */
    public function menu_find_all($data_type = 'object')
    {
        // Get the menu.
        $menus = self::$CI->core_menu_model->menu_find_all($data_type);

        // Return result.
        return ($menus) ? $menus : FALSE;
    }

    /**
     * Find all menus.
     *
     * @return array
     */
    public function menu_find_limit_offset($limit = 1, $offset = 0, $data_type = 'object')
    {
        // Get the menu.
        $menus = self::$CI->core_menu_model->menu_find_limit_offset($limit, $offset, $data_type);

        // Check permissions.
        if ($menus)
        {
            foreach ($menus as $key => $value)
            {
                // Check if there are links.
                if (!empty($menus[$key]->links))
                {
                    foreach ($menus[$key]->links as $link_key => $link)
                    {
                        // Set array from string of roles.
                        $roles = explode(',', $menus[$key]->links[$link_key]->permissions);

                        // Check the permissions.
                        if (!$this->menu_link_check_permissions($roles) && $menus[$key]->links[$link_key]->permissions != NULL)
                        {
                            // Unset link if permission is false.
                            unset($menus[$key]->links[$link_key]);
                        }
                    }
                }
            }
        }

        // Return result.
        return ($menus) ? $menus : FALSE;
    }

    /**
     * Add a menu.
     *
     * @param array $post
     */
    public function menu_add($post = array())
    {
        // Send post to the model.
        $menu_id = self::$CI->core_menu_model->menu_add($post);

        // Insert failed.
        if (!$menu_id)
        {
            self::$CI->session->set_flashdata('message_error', lang('menu_add_failed'));
            redirect(current_url());
            exit();
        }
        // Insert success.
        else
        {
            self::$CI->session->set_flashdata('message_success', lang('menu_add_success'));

            // Redirect to the edit menu.
            redirect(base_url() . $this->menus_uri);
            exit();
        }
    }

    /**
     * Edit a menu.
     *
     * @param array $post
     */
    public function menu_edit($post = array())
    {
        // Send post to the model.
        $result = self::$CI->core_menu_model->menu_edit($post);

        // Insert failed.
        if (!$result)
        {
            self::$CI->session->set_flashdata('message_error', lang('menu_edit_failed'));
            redirect(base_url() . $this->menu_edit_uri . $post['id']);
            exit();
        }
        // Insert success.
        else
        {
            self::$CI->session->set_flashdata('message_success', lang('menu_edit_success'));

            // Redirect to the edit menu.
            redirect(base_url() . $this->menu_edit_uri . $post['id']);
            exit();
        }
    }

    /**
     * Delete a menu.
     *
     * @param integer $id
     */
    public function menu_delete($id = NULL)
    {
        // Run the query.
        $result = self::$CI->core_menu_model->menu_delete($id);

        switch ($result)
        {
            // Delete success.
            case TRUE:
                self::$CI->session->set_flashdata('message_success', lang('menu_delete_success'));
                redirect(base_url() . $this->menus_uri);
                break;

            // Delete failure.
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('menu_delete_failed'));
                redirect(base_url() . $this->menus_uri);
                break;
        }

    }

    /**
     * Find a menu link.
     *
     * @param string $by
     * @param mixed $identifier
     * @return object
     */
    public function menu_link_find($by = 'id', $identifier = NULL, $get_row = FALSE)
    {
        // Get the menu.
        $links = self::$CI->core_menu_model->menu_link_find($by, $identifier, $get_row);

        // Check permissions.
        if ($links)
        {
            // If result() is returned.
            if (is_array($links))
            {
                foreach ($links as $key => $value)
                {
                    // Set array from string of roles.
                    $roles = explode(',', $links[$key]->permissions);

                    // Check the permissions.
                    if (!$this->menu_link_check_permissions($roles) && $links[$key]->permissions != NULL)
                    {
                        // Unset link if permission is false.
                        unset($links[$key]);
                    }
                }
            }
            // If row() is returned.
            else
            {
                // Set array from string of roles.
                $roles = explode(',', $links->permissions);

                // Check the permissions.
                if (!$this->menu_link_check_permissions($roles) && $links->permissions != NULL)
                {
                    // Only applicable if user visits edit page from typing url in
                    // the menu bar.
                    return FALSE;
                    exit();
                }
            }

        }

        // Return result.
        return ($links) ? $links : FALSE;
    }

    /**
     * Add a menu link.
     *
     * @param array $post
     */
    public function menu_link_add($post = array())
    {
        // Send post to the model.
        $menu_link_id = self::$CI->core_menu_model->menu_link_add($post);

        // Insert failed.
        if (!$menu_link_id)
        {
            self::$CI->session->set_flashdata('message_error', lang('menu_link_add_failed'));
            redirect(current_url());
            exit();
        }
        // Insert success.
        else
        {
            self::$CI->session->set_flashdata('message_success', lang('menu_link_add_success'));

            // Redirect to the edit menu.
            redirect(base_url() . $this->menu_link_edit_uri . $menu_link_id);
            exit();
        }
    }

    /**
     * Edit a menu link.
     *
     * @param array $post
     */
    public function menu_link_edit($post = array())
    {
        // Send post to the model.
        $result = self::$CI->core_menu_model->menu_link_edit($post);

        // Insert failed.
        if (!$result)
        {
            self::$CI->session->set_flashdata('message_error', lang('menu_link_edit_failed'));
            redirect(base_url() . $this->menu_edit_uri . $post['id']);
            exit();
        }
        // Insert success.
        else
        {
            self::$CI->session->set_flashdata('message_success', lang('menu_link_edit_success'));

            // Redirect to the edit menu.
            redirect(base_url() . $this->menu_link_edit_uri . $post['id']);
            exit();
        }
    }

}

/* End of file core_menu_library.php */
