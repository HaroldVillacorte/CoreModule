<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Core_menu extends MX_Controller
{
    /**
     * The data array.
     *
     * @var array
     */
    private static $data = array();

    /**
     * The Core menu constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Set the data array.
        self::$data = initialize_module('core_menu');

        // Load the libraries.
        $this->load->library('core_menu_library');

        // Get the link count.
        $max_link_count = $this->config->item('menu_link_maximum_weight');

        // Set the count array.
        self::$data['max_link_count'] = array();

        // Iterate count and add to count array.
        for ($i = 1; $i <= $max_link_count; $i++)
        {
            self::$data['max_link_count'][] = $i;
        }
    }

    /**
     * Load the menu.
     *
     * @param string $by
     * @param string $menu_identifier
     */
    public function index($parent_menu_id = NULL)
    {
        // Generate the menu data.
        self::$data['menu_data'] = $this->core_menu_library->generate_data($parent_menu_id);

        // Get the menu classes.
        $menu_classes = explode(' ', self::$data['menu_data']['menu']->menu_classes);

        // Serve the type of menu requested.
        switch ($menu_classes)
        {
            // If 'nav-bar' is found in the class string.
            case in_array('nav-bar', $menu_classes):
                echo $this->core_menu_library->generate_navbar(self::$data);
                break;

            // If 'top-bar' is found in the class string.
            case in_array('top-bar', $menu_classes):
                echo $this->core_menu_library->generate_topbar(self::$data);
                break;

            // Generate plain unorderd list menu.
            default:
                echo $this->core_menu_library->generate_menu(self::$data);
                break;
        }
    }

    /**
     * The menus admin page.
     *
     * @param integer $menu
     */
    public function menus($menu = NULL)
    {
        // Set the default menu.
        $menu = ($menu) ? $menu : 1;

        // Set the tempalte file.
        $template = 'menus';

        // Get the menus.
        self::$data['menus'] = $this->core_menu_library->menu_find_all('object');

        // Get the menu.
        self::$data['menu'] = $this->core_menu_library->menu_find('id', $menu);

        // Get the menu links.
        self::$data['links'] = $this->core_menu_library->menu_link_find('parent_menu_id', $menu, 'array');

        // Set the menu link edit weight url.  Will be used by ajax.
        self::$data['menu_link_edit_weight_url'] = base_url() . $this->core_menu_library->menu_link_edit_weight_uri;

        // Set the csrf test name.  For some reason it is not getting output.
        self::$data['csrf_test_name'] = $this->input->cookie('csrf_cookie_name');

        // Edit a menu link.
        if ($this->input->post('submit'))
        {
            // Set the validation rules.
            $this->core_menu_library->set_validation_rules('menu_link_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Check for set_value() and set the values.
                foreach (self::$data['links'] as $key => $value)
                {
                    if (set_value('id') && set_value('id') == self::$data['links'][$key]['id'])
                    {
                        foreach (self::$data['links'][$key] as $k => $v)
                        {
                            self::$data['links'][$key][$k] = set_value($k) ;
                        }
                    }
                }

                // Render the page.
                $this->load->view($template, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_menu_library->menu_link_edit($this->input->post());
            }
        }

        // Non post visit.
        else
        {
            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Add a menu.
     */
    public function menu_add()
    {
        // Set the permission.
        $this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        $template = 'menu_add';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_menu_library->set_validation_rules('menu_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the menu.
                $this->load->view($template, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_menu_library->menu_add($this->input->post());
            }
        }
        // First menu visit.
        else
        {
            // Render the menu.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Edit a menu.
     */
    public function menu_edit($id = NULL)
    {
        // Set the permission.
        $this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        $template = 'menu_edit';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_menu_library->set_validation_rules('menu_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the menu.
                $this->load->view($template, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_menu_library->menu_edit($this->input->post());
            }
        }
        // First menu visit.
        else
        {
            // Render the menu.
            self::$data['menu'] = $this->core_menu_library->menu_find('id', $id);
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Delete a menu and all of it's links.
     *
     * @param integer $id
     */
    public function menu_delete($id = NULL)
    {
        if ($id == NULL)
        {
            redirect(base_url());
        }
        else
        {
            // Delete the menu.
            $this->core_menu_library->menu_delete($id);
        }
    }

    /**
     * Add a menu link.
     *
     * @param integer $menu_id
     */
    public function menu_link_add($menu_id = 1)
    {
        // Set the permission.
        $this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        $template = 'menu_link_add';

        // Get the menus.
        self::$data['menus'] = $this->core_menu_library->menu_find_all('object');

        // Set the menu parent menu id.
        self::$data['menu_id'] = $menu_id;

        // Set the next weight to preset form.
        self::$data['next_weight'] = count($this->core_menu_library
            ->menu_link_find('parent_menu_id', $menu_id, 'array')) + 1;

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_menu_library->set_validation_rules('menu_link_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the menu.
                $this->load->view($template, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_menu_library->menu_link_add($this->input->post());
            }
        }
        // First menu visit.
        else
        {
            // Render the menu.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Delete a menu link.
     *
     * @param integer $id
     */
    public function menu_link_delete($id = NULL)
    {
        if ($id == NULL)
        {
            redirect(base_url());
            exit();
        }
        else
        {
            // Delete the menu.
            $this->core_menu_library->menu_link_delete($id);
        }
    }

    /**
     * Edit a menu link weight.
     */
    public function menu_link_edit_weight()
    {
        // This method should be available only to ajax post requests.
        if (!$this->input->is_ajax_request() || !$this->input->post())
        {
            redirect(base_url());
            exit();
        }

        if ($this->input->post('submit'))
        {
            // Run the validation.
            $this->core_menu_library->set_validation_rules('menu_link_update_weight');
            if ($this->form_validation->run() == FALSE)
            {
                // Read by the javascript.
                echo 'invalid';
            }
            else
            {
                // Send to the database.
                $result = $this->core_menu_library->menu_link_edit($this->input->post(), TRUE);

                // Read by the javascript.
                echo ($result) ? 'true' : 'false';
            }
        }
    }

}

/* End of file core_menu.php */
