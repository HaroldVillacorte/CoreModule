<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Core_menu_library
{
    /**
     * The CI super object.
     *
     * @var object
     */
    private static $CI;

    function __construct()
    {
        self::$CI =& get_instance();

        // Load the config and language.
        self::$CI->load->config('core_menu/core_menu_config');
        self::$CI->lang->load('core_menu/core_menu');

        // Load the libraries.
        self::$CI->load->library('form_validation');

        // Load the helpers.
        self::$CI->load->helper('language');
        self::$CI->load->helper('date');

        // Load the models.
        self::$CI->load->model('core_menu/core_menu_model');

        // Set the uri's.
        $this->menus_uri                 = self::$CI->config->item('menus_uri');
        $this->menu_add_uri              = self::$CI->config->item('menu_add_uri');
        $this->menu_edit_uri             = self::$CI->config->item('menu_edit_uri');
        $this->menu_delete_uri           = self::$CI->config->item('menu_delete_uri');
        $this->menu_link_add_uri         = self::$CI->config->item('menu_link_add_uri');
        $this->menu_link_delete_uri      = self::$CI->config->item('menu_link_delete_uri');
        $this->menu_link_edit_weight_uri = self::$CI->config->item('menu_link_edit_weight_uri');
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
                'rules' => 'required|trim|integer|xss_clean'
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
                'field' => 'weight',
                'label' => 'Weight',
                'rules' => 'required|trim|integer|max_length[11]|xss_clean',
            ),
            array(
                'field' => 'parent_menu_id',
                'label' => 'Parent menu',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'child_menu_id',
                'label' => 'Child menu',
                'rules' => 'trim|integer|xss_clean',
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
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'weight',
                'label' => 'Weight',
                'rules' => 'trim|integer|max_length[11]|xss_clean',
            ),
            array(
                'field' => 'parent_menu_id',
                'label' => 'Parent menu',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'child_menu_id',
                'label' => 'Child menu',
                'rules' => 'trim|integer|xss_clean',
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
        $menu_link_update_weight = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'weight',
                'label' => 'Weight',
                'rules' => 'required|trim|integer|max_length[11]|xss_clean',
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
            case 'menu_link_update_weight':
                $rule_set = $menu_link_update_weight;
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
    public function menu_link_find($by = 'id', $identifier = NULL, $data_type = 'row')
    {
        // Get the menu.
        $links = self::$CI->core_menu_model->menu_link_find($by, $identifier, $data_type);

        // Check permissions.
        if ($links)
        {
            // If result() is returned.
            if (is_array($links))
            {
                foreach ($links as $key => $value)
                {
                    if (!is_array($links[$key]))
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
                    else
                    {
                        // Set array from string of roles.
                        $roles = explode(',', $links[$key]['permissions']);

                        // Check the permissions.
                        if (!$this->menu_link_check_permissions($roles) && $links[$key]['permissions'] != NULL)
                        {
                            // Unset link if permission is false.
                            unset($links[$key]);
                        }
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
            redirect(base_url() . $this->menus_uri . $post['parent_menu_id']);
            exit();
        }
    }

    /**
     * Edit a menu link.
     *
     * @param array $post
     */
    public function menu_link_edit($post = array(), $is_ajax = FALSE)
    {
        // Send post to the model.
        $result = self::$CI->core_menu_model->menu_link_edit($post, $is_ajax);

        // Insert failed.
        if (!$result)
        {
            // Check for ajax.
            if ($is_ajax)
            {
                // Return false for ajax.
                return FALSE;
            }
            else
            {
                // Or redirect and give failure message.
                self::$CI->session->set_flashdata('message_error', lang('menu_link_edit_failed'));
                redirect(base_url() . $this->menus_uri . $post['parent_menu_id']);
                exit();
            }
        }
        // Insert success.
        else
        {
            // Check for ajax.
            if ($is_ajax)
            {
                // Return true for ajax.
                return TRUE;
            }
            else
            {
                // Or redirect and give success message.
                self::$CI->session->set_flashdata('message_success', lang('menu_link_edit_success'));
                redirect(base_url() . $this->menus_uri . $post['parent_menu_id']);
                exit();
            }
        }
    }

    /**
     * Delete a menu lik.
     *
     * @param integer $id
     * @param integer $parent_menu_id
     */
    public function menu_link_delete($id = NULL)
    {
        // Find the menu links first and get the parent menu id to redirect to.
        $link = self::$CI->core_menu_library->menu_link_find('id', $id, 'row');
        $parent_menu_id = $link->parent_menu_id;

        // Run the query.
        $result = self::$CI->core_menu_model->menu_link_delete($id);

        switch ($result)
        {
            // Delete success.
            case TRUE:
                self::$CI->session->set_flashdata('message_success', lang('menu_link_delete_success'));
                redirect(base_url() . $this->menus_uri . $parent_menu_id);
                break;

            // Delete failure.
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('menu_link_delete_failed'));
                redirect(base_url() . $this->menus_uri . $parent_menu_id);
                break;
        }
    }

    /**
     * Get the final data to generate menu.
     *
     * @param integer $parent_menu_id
     * @param string $template
     * @return array
     */
    public function generate_data($parent_menu_id = NULL)
    {
        // Initialize the data array.
        $data = array();

        // Get the menu.
        $data['menu'] = $this->menu_find('id', $parent_menu_id);

        // Get the menu links.
        $data['links'] = $this->menu_link_find('parent_menu_id', $parent_menu_id, 'result');

        if ($data['links'])
        {
            // Array of the link tags.
            $link_tags = array('#', '{front}','{label}', '{login}');

            // Parse the links.
            foreach ($data['links'] as $link)
            {
                // Internal links.
                if (!$link->external && !in_array($link->link, $link_tags))
                {
                    $link->link = 'href="' .  base_url() . $link->link . '"';
                }

                // External links.
                elseif ($link->external && !in_array($link->link, $link_tags))
                {
                    $link->link = 'href="http://' . $link->link . '"';
                }

                // Front page links.
                elseif ($link->link == '{front}')
                {
                    $link->link = 'href="' .  base_url() . '"';
                }

                // Void links.
                elseif ($link->link == '#')
                {
                    $link->link = 'href="#"';
                }

                // Parse labels.
                elseif ($link->link == '{label}')
                {
                    $link->link = '';
                }

                // Parse login links.
                elseif ($link->link == '{login}')
                {
                    if (self::$CI->session->userdata('user_id'))
                    {
                        $link->link  = 'href="' . base_url() . self::$CI->core_user_library->user_logout_uri . '"';
                        $link->text = 'Logout';
                    }
                    else
                    {
                        $link->link  = 'href="' . base_url() . self::$CI->core_user_library->user_login_uri . '"';
                        $link->text = 'Login';
                    }
                }

                // Add the active class to current url.
                $link->class = ('href="'.current_url().'"' == $link->link) ? 'class="active"' : NULL;
            }
        }

        return $data;
    }

    /**
     * Generate plain unordered list menu.
     *
     * @param array $data
     * @return string
     */
    public function generate_menu($data = NULL)
    {
        // Get the menu ul classes.
        $menu_classes = (isset($data['menu_data']['menu']->menu_classes))
            ? $data['menu_data']['menu']->menu_classes : '';

        // Start the string.  Only if site name is in the data array so child menus
        // will not run this part.
        $output = (isset($data['site_name'])) ? '<ul id="menu-' . $data['menu_data']['menu']->id
            . '" class="' . $menu_classes . '">' : '';


        // Loop through the links.
        if ($data['menu_data']['links'])
        {
            foreach ($data['menu_data']['links'] as $link)
            {
                // If link has no child menu.
                if (!$link->child_menu_id)
                {
                    $output .= '<li ' . $link->class . '><a ' . $link->link . ' title="' . $link->title . '">' . $link->text . '</a></li>';
                }
                // If link has child menu.
                else
                {
                    // Generate link data for the child menu id.
                    $new_link_data['menu_data'] = $this->generate_data($link->child_menu_id);

                    // Generate the child menu.
                    $child_links = $this->generate_menu($new_link_data);

                    // Insert child menu into the new link.
                    $output .= '<li>'
                            // The actual link will be inactive.
                            .  '<a href="#">' . $link->text . '</a>'
                            .       '<ul>'
                            .           $child_links
                            .       '</ul>'
                            .  '</li>';
                }
            }
        }

        // End the string.  Only if site name is in the data array so child menus
        // will not run this part.
        $output .= (isset($data['site_name'])) ? '</ul>' : '';

        return $output;
    }

    /**
     * Generate a Foundation 3 nav-bar from menu data.
     *
     * @param array $data
     * @return string
     */
    public function generate_navbar($data = NULL)
    {
        // Get the menu ul classes.
        $menu_classes = (isset($data['menu_data']['menu']->menu_classes))
            ? $data['menu_data']['menu']->menu_classes : '';

        // Start the string.  Only if site name is in the data array so child menus
        // will not run this part.
        $output = (isset($data['site_name'])) ? '<ul id="menu-' . $data['menu_data']['menu']->id
            . '" class="' . $menu_classes . '">' : '';

        // Loop through the links.
        if ($data['menu_data']['links'])
        {
            foreach ($data['menu_data']['links'] as $link)
            {
                // If link has no child menu.
                if (!$link->child_menu_id)
                {
                    $output .= '<li ' . $link->class . '><a ' . $link->link . ' title="' . $link->title . '">' . $link->text . '</a></li>';
                }
                // If link has child menu.
                else
                {
                    // Generate link data for the child menu id.
                    $new_link_data['menu_data'] = $this->generate_data($link->child_menu_id);

                    // Generate the child menu.
                    $child_links = $this->generate_navbar($new_link_data);

                    // Insert child menu into the new link.
                    $output .= '<li class="has-flyout">'
                            .       '<a href="#">' . $link->text . '</a>'
                            .       '<a href="#" class="flyout-toggle"><span> </span></a>'
                            .       '<ul class="flyout">'
                            .           $child_links
                            .       '</ul>'
                            .  '</li>';
                }
            }
        }

        // End the string.  Only if site name is in the data array so child menus
        // will not run this part.
        $output .= (isset($data['site_name'])) ? '</ul>' : '';

        return $output;
    }

    /**
     * Generate a Foundation 3 top-bar from menu data.
     *
     * @param array $data
     * @return string
     */
    public function generate_topbar($data = NULL)
    {
        // Get the menu ul classes.
        $menu_classes = (isset($data['menu_data']['menu']->menu_classes))
            ? $data['menu_data']['menu']->menu_classes : '';

        // Start the string.  Only if site name is in the data array so child menus
        // will not run this part.
        if (isset($data['site_name']))
        {
            $output = '<nav id="nav-' . $data['menu_data']['menu']->id . '" class="' . $menu_classes . '">'
                . '<ul id="menu-' . $data['menu_data']['menu']->id . '">'
                . '<li class="name"><h1><a href="' . base_url() . 'admin/">' . $data['site_name'] . 'Admin</a></h1></li>'
                .      '<li class="divider hide-for-small"></li>'
                .      '<li class="toggle-topbar"><a href="#"></a></li>'
                . '</ul>'
                . '<section>'
                . '<ul class="left">';
        }
        else
        {
            $output = '';
        }

        // Loop through the links.
        foreach ($data['menu_data']['links'] as $link)
        {
            // Define the divider.
            $divider = (isset($data['site_name'])) ? '<li class="divider"></li>' : NULL;

            // You can put label tags per Foundation 3 top-bar.
            if (strstr($link->text, '<label>'))
            {
                $output .= '<li>' . $link->text . '</li>'
                        .  $divider;
            }
            // If link has no child menu.
            elseif (!$link->child_menu_id)
            {
                $output .= '<li ' . $link->class . '><a ' . $link->link . ' title="' . $link->title . '">' . $link->text . '</a></li>'
                        .  $divider;
            }
            // If link has child menu.
            else
            {
                // Generate link data for the child menu id.
                $link_data['menu_data'] = $this->generate_data($link->child_menu_id);

                // Generate the child menu.
                $child_links = $this->generate_topbar($link_data);

                // Insert child menu into the new link.
                $output .= '<li class="has-dropdown">'
                        .       '<a href="#">' . $link->text . '</a>'
                        .       '<ul class="dropdown">'
                        .           $child_links
                        .       '</ul>'
                        .  '</li>'
                        .  $divider;
            }
        }

        // End the string.  Only if site name is in the data array so child menus
        // will not run this part.
        $output .= (isset($data['site_name'])) ? '</ul></section>' : '';

        return $output;
    }

}

/* End of file core_menu_library.php */
