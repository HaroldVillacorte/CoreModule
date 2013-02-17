<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Module
 *
 * The User module provides full user CRUD, authentication, and a very simple yet
 * highly effective permissions system.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class User extends MX_Controller
{

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * The template module to run.
     *
     * @var string
     */
    private static $template = '_core_template/default_template';

    /**
     * Store in the session to remember visited page.
     *
     * @var string
     */
    private static $user_page;

    public function __construct()
    {
        parent::__construct();

        // Load the config and language files.
        $this->config->load('_core_user/core_user_config');
        $this->lang->load('_core_user/core_user', $this->config->item('core_user_language'));

        // Load libraries.
        $this->load->library('_core_user/core_user_library');
        $this->load->library('_core_module/core_module_library');

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
     * The user profile page.
     */
    public function index()
    {
        self::$data['view_file'] = 'user_profile';

        // Checks if the user is logged in.  If not user is redirected to the
        // base_url().

        $id = $this->session->userdata('user_id');
        if (!$id)
        {
            redirect(base_url() . $this->core_user_library->user_login_uri);
        }
        $user = $this->core_user_library->user_find($id);
        self::$data['user'] = $user;

        echo Modules::run(self::$template, self::$data);
    }

    /**
     * Basic login method.
     */
    public function login()
    {
        self::$data['view_file'] = 'user_login';
        self::$data['user_add_url']                     = base_url()
            . $this->core_user_library->user_add_uri;
        self::$data['user_user_forgotten_password_url'] = base_url()
            . $this->core_user_library->user_forgotten_password_uri;

        // Code to run when the user hits the Login button.
        if ($this->input->post('submit'))
        {
            // Sets the CI validation rules.
            $this->core_user_library->set_validation_rules('user_login');
            // Code to run form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
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

        // Code to run when the user visits the page without hitting the Login
        // button.
        if ($this->session->userdata('user_id'))
        {
            $this->core_module_library->keep_flashdata_messages();
            redirect(base_url() . $this->core_user_library->user_index_uri);
        }

        echo Modules::run(self::$template, self::$data);
    }

    /**
     * User recovers password with email address.
     */
    public function forgotten_password()
    {
        self::$data['view_file'] = 'user_forgotten_password';

        if ($this->input->post('submit'))
        {
            $this->core_user_library->set_validation_rules('user_forgotten_password');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $this->core_user_library-> user_forgotten_password($this->input->post('email'));
            }
        }

        echo Modules::run(self::$template, self::$data);
    }

    public function forgotten_password_login($code = NULL)
    {
        $this->core_user_library->user_forgotten_password_login($code);
    }

    /**
     * Basic logout method using Codeigniter.
     */
    public function logout()
    {
        $this->core_user_library->user_logout();
    }

    /**
     * New user create account.
     */
    public function add()
    {
        self::$data['view_file'] = 'user_add';

        if ($this->input->post('add'))
        {
            $this->core_user_library->set_validation_rules('user_insert');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Form does not validate.
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                // Form validation passed.
                $this->core_user_library->user_add($this->input->post());
            }
        }
        else
        {
            // Code to run when user first visits.
            echo Modules::run(self::$template, self::$data);
        }
    }

    public function activate($activation_code = NULL)
    {
        if ($activation_code == NULL)
        {
            redirect(base_url() . $this->core_user_library->user_login_uri);
        }
        else
        {
            $this->core_user_library->user_activate($activation_code);
        }
    }

    /**
     * User edits own account.
     */
    public function edit()
    {
        self::$data['view_file'] = 'user_edit';
        if ($this->session->userdata('user_id'))

        // Check if a user is logged in then set the user id from the session.
        if (!$this->session->userdata('user_id'))
        {
            redirect(base_url() . $this->core_user_library->user_index_uri);
        }
        else
        {
            $id   = $this->session->userdata('user_id');
            $user = $this->core_user_library->user_find($id);
            self::$data['user'] = $user;
        }

        // When the user hits the delete button redirect them to the delete method.
        if ($this->input->post('delete'))
        {
            if ($this->session->userdata('user_id'))
            {
                redirect(base_url() . $this->core_user_library->user_delete_uri);
            }
        }

        // Code to run when the user hits the save button.
        if ($this->input->post('save'))
        {
            // Set the validation rules for updating a user.
            $this->core_user_library->set_validation_rules('user_update');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Form does not validate.
                echo Modules::run(self::$template, self::$data);
            }
            // Code to run when the form passes validation.
            else
            {
                $this->core_user_library->user_edit($user, $this->input->post());
            }
        }

        // Code to run when user first visits the page without hitting submit.
        else
        {
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * User deletes own account.
     */
    public function delete()
    {
        self::$data['view_file'] = 'user_delete';

        // Instanitate User based on session userdata('user_id').
        if ($id = $this->session->userdata('user_id'))
        {
            $user = $this->core_user_library->user_find($id);
        }
        else
        {
            redirect(base_url());
        }

        // Code to run when user hits the final delete button.
        if ($this->input->post('delete'))
        {
            // Check first if account is protected.
            $this->core_user_library->user_check_protected($user, 'delete');

            // Delete.
            $this->core_user_library->user_delete($user);
        }

        // Redirect user if they come to this page without being logged in.
        elseif ($id == NULL)
        {
            redirect(base_url());
        }

        // Code to run when logged in user first visits the page.
        else
        {
            self::$data['user'] = $user;
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin view all roles.
     */
    public function admin_roles()
    {
        self::$data['view_file'] = 'admin_roles';

        // Generate table
        $role_table = $this->core_user_library->admin_role_table();

        // Render the page.
        self::$data['output'] = $role_table;
        echo Modules::run(self::$template, self::$data);
    }

    /**
     * Admin add a role.
     */
    public function admin_role_add()
    {
        self::$data['view_file'] = 'admin_role_add';

        if ($this->input->post('save'))
        {
            $this->core_user_library->set_validation_rules('admin_role_insert');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $this->core_user_library->admin_role_add($this->input->post());
            }
        }
        else
        {
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin edits a role.
     *
     * @param integer $id
     */
    public function admin_role_edit($id = NULL)
    {
        self::$data['view_file'] = 'admin_role_edit';

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
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $this->core_user_library->admin_role_edit($this->input->post());
            }
        }
        else
        {
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin deletes a role.
     *
     * @param integer $id
     */
    public function admin_role_delete($id = NULL)
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
    public function admin_users($page = NULL)
    {
        self::$data['view_file'] = 'admin_users';

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

        // Check for ajax request then pick view_file.
        if ($this->input->is_ajax_request())
        {
            // Set current page to session.
            $this->session->set_userdata(array('user_admin_page' => $page));
            $this->load->view('admin_users_ajax', self::$data);
        }
        else
        {
            // Set current page to session.
            $this->session->set_userdata(array('user_admin_page' => $page));
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin edits a user.
     *
     * @param integer $id
     */
    public function admin_user_edit($id = NULL)
    {
        self::$data['view_file'] = 'admin_user_edit';
        self::$data['all_roles'] = $this->core_user_library->admin_role_get_all();
        $user = $this->core_user_library->user_find((int) $id);

        // Redirect admin user if id is not set.
        if ($id == NULL && !$this->input->post('save'))
        {
            redirect(base_url() . $this->core_user_library->user_admin_users_uri);
        }
        elseif ($this->input->post('save'))
        {
            $this->core_user_library->set_validation_rules('admin_user_update');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
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
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin adds a user.
     */
    public function admin_user_add()
    {
        self::$data['view_file'] = 'admin_user_add';
        self::$data['all_roles'] = $this->core_user_library->admin_role_get_all();

        if ($this->input->post('save'))
        {
            $this->core_user_library->set_validation_rules('admin_user_insert');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
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

            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin deletes a user.
     *
     * @param integer $id
     */
    public function admin_user_delete($id = NULL)
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
/* End of file core_user.php */
