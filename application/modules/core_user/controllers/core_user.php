<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Controller Module.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class Core_user extends MX_Controller
{

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * Store in the session to remember visited page.
     *
     * @var string
     */
    private static $user_page;

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

        // Load the config and language files.
        $this->config->load('core_user/core_user_config');
        $this->lang->load('core_user/core_user', $this->config->item('core_user_language'));

        // Load libraries.
        $this->load->library('core_user/core_user_library');

        // Load helpers.
        $this->load->helper('date');
        $this->load->helper('form');

        // Check if a user is logged in.
        $this->core_user_library->user_check_logged_in();

        // Initialize the data array.
        self::$data = $this->core_module_model->site_info();

        // Sets the module to be sent to the Template module.
        self::$data['module'] = 'user';

        // User admin remember paginated page.
        self::$user_page = NULL;
        if ($this->session->userdata('user_admin_page'))
        {
            self::$user_page = $this->session->userdata('user_admin_page');
        }
        self::$data['user_page'] = self::$user_page;
    }

    /**
     * The admin index page.
     */
    public function index()
    {
        $template = 'user_profile';

        // Checks if the user is logged in.  If not user is redirected to the
        // base_url().

        $id = $this->session->userdata('user_id');
        if (!$id)
        {
            redirect(base_url() . $this->core_user_library->user_login_uri);
        }
        $user = $this->core_user_library->user_find($id);
        self::$data['user'] = $user;

        // Render the page.
        echo $this->load->view($template, self::$data);
    }

    /**
     * Basic login method.
     */
    public function login()
    {
        $template = 'user_login';
        self::$data['user_add_url'] = base_url() . $this->core_user_library->user_add_uri;
        self::$data['user_user_forgotten_password_url'] = base_url() . $this->core_user_library->user_forgotten_password_uri;
        //self::$data['content'] = $this->load->view('user_login', self::$data, TRUE);

        // Code to run when the user hits the Login button.
        if ($this->input->post('submit'))
        {
            // Sets the CI validation rules.
            $this->core_user_library->set_validation_rules('user_login');
            // Code to run form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                $this->load->view($template, self::$data);
            }
            // Code to run when form validates.
            else
            {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $set_persistent_login = (bool) $this->input->post('set_persistent_login');
                $this->core_user_library->user_login($username, $password, $set_persistent_login);
            }
        }
        else
        {
            // Code to run when the user visits the page without hitting the Login
            // button.
            if ($this->session->userdata('user_id'))
            {
                keep_flashdata_messages();
                redirect(base_url() . $this->core_user_library->user_index_uri);
                exit();
            }

            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Basic logout method using Codeigniter.
     */
    public function logout()
    {
        $this->core_user_library->user_logout();
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
        echo $this->core_template_library->parse_view($this->template, self::$data);
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
                 echo $this->core_template_library->parse_view($this->template, self::$data);
            }
            else
            {
                $this->core_user_library->admin_role_add($this->input->post());
            }
        }
        else
        {
            // Render the page.
            echo $this->core_template_library->parse_view($this->template, self::$data);
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
                echo $this->core_template_library->parse_view($this->template, self::$data);
            }
            else
            {
                // Send to the database.
                $this->core_user_library->admin_role_edit($this->input->post());
            }
        }
        else
        {
            // Redirect if parameter not set.
            if (!$id)
            {
                redirect(base_url() . $this->core_user_library->user_admin_roles_uri);
            }

            // Render the page.
            echo $this->core_template_library->parse_view($this->template, self::$data);
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

            // Reset the template name.
            $this->template = 'admin_template/content/admin_users_ajax';

            // Render the page.
            echo $this->core_template_library->parse_view($this->template, self::$data);
        }
        else
        {
            // Set current page to session.
            $this->session->set_userdata(array('user_admin_page' => $page));

            // Render the page.
            echo $this->core_template_library->parse_view($this->template, self::$data);
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
                echo $this->core_template_library->parse_view($this->template, self::$data);
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
            echo $this->core_template_library->parse_view($this->template, self::$data);
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
                echo $this->core_template_library->parse_view($this->template, self::$data);
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
            echo $this->core_template_library->parse_view($this->template, self::$data);
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
