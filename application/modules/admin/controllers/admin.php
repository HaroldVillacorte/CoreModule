<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin Controller Module
 *
 * Serves as a basic boilerplate for developing with CI Starter.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class Admin extends MX_Controller
{

    /** Sets the $data property.
     *
     * @var array
     */
    protected static $data;

    /**
     * The template array.
     *
     * @var array
     */
    private static $template_array;

    /**
     * The data property is set to the site_info() array which passes an array
     * containing the site wide information such as the site name and asset path
     * information.  self::$data['module'] is the name of this module which is
     * passed to the Template module.
     *
     * @see core_model.php
     */
    public function __construct()
    {
        parent::__construct();

        // Load the libraries.
        $this->load->library('_core_raintpl/core_raintpl_library');
        $this->load->library('_core_pages/core_pages_library');
        $this->load->library('_core_menu/core_menu_library');

        // Load the helpers.
        $this->load->helper('date');

        // Sets the the data array.
        self::$data = $this->core_module_model->site_info();

        // Initialize the Template array.
        self::$template_array = array(
            'template_name' => 'admin_template/',
            'template_file' => 'admin_template',
        );
    }

    /**
     * Javascript and css filenames are added to the script array from
     * core_model then the Asset Loader module will load these files.  Using
     * array_unshift adds the file to the begining of the array causing it to be
     * loaded first.  Alternatively you can use this method:
     * self::$data['scripts'][] = 'js_file_name.js'
     * to add the file to the end of the array causing it to be loaded last.
     *
     * @see core_model.php
     * @see asset_loader.php
     */
    public function index()
    {
        // Add filenames to the $scripts array.
        array_unshift(self::$data['scripts'], 'jquery.foundation.orbit.js');
        // Uncomment and edit to add a css file.
        /* array_unshift(self::$data['stylesheets'], 'CSS file GOES HERE'); */
        self::$data['content_file'] = 'grid';

        // render the page.
        echo $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

    /**
     * The administrative email settings page.
     */
    public function email_settings()
    {
        // Load th elibrary.
        $this->load->library('_core_email/core_email_library');

        self::$data['content_file'] = 'admin_email_settings';

        if ($this->input->post('submit'))
        {
            $this->core_email_library->system_settings_set_validation_rules();

            if ($this->form_validation->run() == FALSE)
            {
                // render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            else
            {
                $this->core_email_library->system_settings_set($this->input->post());
            }
        }
        elseif ($this->input->post('test'))
        {
            $this->core_email_library->system_email_test_send();
        }
        else
        {
            self::$data['settings'] = $this->core_email_library->system_settings_get(FALSE);
            // render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * The adminstrtive page of all pages.
     */
    public function pages($page = 0)
    {
        self::$data['content_file'] = 'admin_pages';

        // Load the libraries.
        $this->load->library('pagination');

        // Per page used for pagination and query.
        $per_page = 1;

        // Run the query.
        self::$data['pages'] = $this->core_pages_library->page_find_limit_offset($per_page, $page, 'object');

        // Get the count.
        $count = count($this->core_pages_library->page_find_all('array'));

        // Get the pagination links.
        $base_url = base_url() . $this->config->item('pages_uri');
        self::$data['pagination'] = $this->core_module_library->pagination_setup($base_url, $count, $per_page);

        // Render the page.
        echo $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

    /**
     * Add a page.
     */
    public function page_add()
    {
        // Set the permission.
        $this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        self::$data['content_file'] = 'admin_page_add';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_pages_library->set_validation_rules('page_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_pages_library->page_add($this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Edit a page.
     */
    public function page_edit($id = NULL)
    {
        // Set the permission.
        $this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        self::$data['content_file'] = 'admin_page_edit';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_pages_library->set_validation_rules('page_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_pages_library->page_edit($this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Find page to edit.
            self::$data['page'] = $this->core_pages_library->page_find('id', (int) $id);

            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Delete a page.
     *
     * @param integer $id
     */
    public function page_delete($id = NULL)
    {
        // Delete the page.
        $this->core_pages_library->page_delete($id);
    }

    /**
     * The adminstrtive page of all menus.
     */
    public function menus($page = 0)
    {
        self::$data['content_file'] = 'admin_menus';

        // Load the libraries.
        $this->load->library('pagination');

        // Per page used for pagination and query.
        $per_page = 1;

        // Run the query.
        self::$data['menus'] = $this->core_menu_library->menu_find_limit_offset($per_page, $page, 'object');

        // Get the count.
        $count = count($this->core_menu_library->menu_find_all('array'));

        // Get the pagination links.
        $base_url = base_url() . $this->config->item('menus_uri');
        self::$data['pagination'] = $this->core_module_library->pagination_setup($base_url, $count, $per_page);

        // Render the page.
        echo $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

    /**
     * Add a menu.
     */
    public function menu_add()
    {
        // Set the permission.
        $this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        self::$data['content_file'] = 'admin_menu_add';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_menu_library->set_validation_rules('menu_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the menu.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
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
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
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
        self::$data['content_file'] = 'admin_menu_edit';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_menu_library->set_validation_rules('menu_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the menu.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
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
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
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

        // Get the menus.
        self::$data['menus'] = $this->core_menu_library->menu_find_all('object');

        // Set the menu parent menu id.
        self::$data['menu_id'] = $menu_id;

        // Set the content template file.
        self::$data['content_file'] = 'admin_menu_link_add';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_menu_library->set_validation_rules('menu_link_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the menu.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
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
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Edit a menu link.
     */
    public function menu_link_edit($id = NULL)
    {
        // Set the permission.
        $this->core_user_library->user_permission(array('admin', 'super_user'));

        // Get the menus.
        self::$data['menus'] = $this->core_menu_library->menu_find_all('object');

        // Set the content template file.
        self::$data['content_file'] = 'admin_menu_link_edit';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_menu_library->set_validation_rules('menu_link_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the menu.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_menu_library->menu_link_edit($this->input->post());
            }
        }
        // First menu visit.
        else
        {
            // Render the menu.
            self::$data['menu_link'] = $this->core_menu_library->menu_link_find('id', $id, TRUE);
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Admin view all roles.
     */
    public function user_roles()
    {
        self::$data['content_file'] = 'admin_roles';

        // Generate table
        $role_table = $this->core_user_library->admin_role_table();

        self::$data['output'] = $role_table;

        // Render the page.
        echo $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

    /**
     * Admin add a role.
     */
    public function user_role_add()
    {
        self::$data['content_file'] = 'admin_role_add';

        if ($this->input->post('save'))
        {
            $this->core_user_library->set_validation_rules('admin_role_insert');

            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                 echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            else
            {
                $this->core_user_library->admin_role_add($this->input->post());
            }
        }
        else
        {
            // Render the page.
             echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Admin edits a role.
     *
     * @param integer $id
     */
    public function user_role_edit($id = NULL)
    {
        self::$data['content_file'] = 'admin_role_edit';

        // Instantiate the role to populate the form.
        $role = $this->core_user_library->admin_role_get($id);
        self::$data['role'] = ($role) ? $role : NULL;

        if ($this->input->post('save'))
        {
            $role = $this->core_user_library->admin_role_get($this->input->post('id'));

            // Check first if role is protected.
            $this->core_user_library->admin_role_check_protected($role);

            $this->core_user_library->set_validation_rules('admin_role_update');

            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            else
            {
                $this->core_user_library->admin_role_edit($this->input->post());
            }
        }
        else
        {
            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Admin deletes a role.
     *
     * @param integer $id
     */
    public function user_role_delete($id = NULL)
    {
        // Redirect if role id is not set.
        if (!$id) redirect(base_url() . $this->core_user_library->user_admin_roles_uri);

        $role = $this->core_user_library->admin_role_get($id);

        // Check if role is protected.
        $this->core_user_library->admin_role_check_protected($role);

        // Delete the role.
        $this->core_user_library->admin_role_delete($role->id);
    }

    /**
     * Paginated users page.
     *
     * @param integer $page
     */
    public function users($page = NULL)
    {
        self::$data['content_file'] = 'admin_users';

        // Per_page for pagination and model query.
        $per_page = 1;

        // Set start record for query.
        $start = 0;

        if ($page)
        {
            $start = $page;
        }

        // Database queries.
        $count = $this->core_user_library->admin_user_get_count();
        $output = $this->core_user_library->admin_user_limit_offset_get($per_page, $start);

        // Get first and last id's.
        self::$data['first'] = $page + 1;
        self::$data['last'] = $page + count($output);

        // Pagination setup
        $pagination_links = $this->core_user_library
            ->admin_user_page_pagination_setup($count, $per_page);

        // Table render
        $table_output = $this->core_user_library->admin_user_page_table_setup($output);

        // Page setup
        self::$data['pagination_links'] = $pagination_links;
        self::$data['output'] = $table_output;
        self::$data['count'] = $count;

        // Add the javascript.
        array_unshift(self::$data['scripts'], 'user_admin_ajax.js');

        // Check for ajax request then pick content_file.
        if ($this->input->is_ajax_request())
        {
            // Set current page to session.
            $this->session->set_userdata(array('user_admin_page' => $page));

            // Reset the template data array.
            self::$template_array = array(
                'template_name' => 'admin_template/content/',
                'template_file' => 'admin_users_ajax',
            );

            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
        else
        {
            // Set current page to session.
            $this->session->set_userdata(array('user_admin_page' => $page));

            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Admin edits a user.
     *
     * @param integer $id
     */
    public function user_edit($id = NULL)
    {
        self::$data['content_file'] = 'admin_user_edit';
        self::$data['all_roles'] = $this->core_user_library->admin_role_get_all('array');
        $user = $this->core_user_library->user_find((int) $id);

        // Redirect admin user if id is not set.
        if ($id == NULL && !$this->input->post('save'))
        {
            redirect(base_url() . $this->core_user_library->user_admin_users_uri);
        }
        // Post submit.
        elseif ($this->input->post('save'))
        {
            // Set validation rules.
            $this->core_user_library->set_validation_rules('admin_user_update');

            // Form does not validate
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            else
            {
                $id   = $this->input->post('id');
                $user = $this->core_user_library->user_find($id);

                // Check first if user account is protected.
                $this->core_user_library->admin_user_check_protected($user);

                // Edit the user account.
                $this->core_user_library->admin_user_edit($this->input->post());
            }
        }
        else
        {
            // Add the js file.
            array_unshift(self::$data['scripts'], 'user_admin_ajax.js');

            self::$data['user'] = $user;

            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Admin adds a user.
     */
    public function user_add()
    {
        self::$data['content_file'] = 'admin_user_add';
        self::$data['all_roles'] = $this->core_user_library->admin_role_get_all('array');

        if ($this->input->post('save'))
        {
            $this->core_user_library->set_validation_rules('admin_user_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            else
            {
                // Add the user.
                $this->core_user_library->admin_user_add($this->input->post());
            }
        }
        else
        {
            // Add the js file.
            array_unshift(self::$data['scripts'], 'user_admin_ajax.js');

            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * Admin deletes a user.
     *
     * @param integer $id
     */
    public function user_delete($id = NULL)
    {
        if ($id == NULL)
        {
            redirect(base_url());
        }
        else
        {
            $user = $this->core_user_library->user_find($id);

            // Check first if user account is protected.
            $this->core_user_library->admin_user_check_protected($user);

            // Delete the user.
            $this->core_user_library->admin_user_delete($user->id, self::$user_page);
        }
    }

}

/* End of file admin.php */
