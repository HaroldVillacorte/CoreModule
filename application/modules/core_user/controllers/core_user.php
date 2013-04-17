<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Controller Module.
 *
 * @package CoreModule
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
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
     * The Core user constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Initialize the data array.
        self::$data = initialize_module('core_user');

        // Load the config and language files.
        $this->config->load('core_user/core_user_config');
        $this->lang->load('core_user/core_user', $this->config->item('core_user_language'));

        // Load helpers.
        $this->load->helper('date');
        $this->load->helper('form');
    }

    /**
     * The core user settings page.
     */
    public function core_user_settings()
    {
        $template = 'core_user/settings';

        if ($this->input->post('submit'))
        {
            // Set the validation rules.
            $this->core_user_library->set_validation_rules('settings');

            // Run the validation.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_template_library->parse_view($template, self::$data);
            }
            else
            {
                // Set the settings.
                $post = $this->input->post();
                unset($post['submit']);

                // Send to the database.
                process_variables($post);
            }
        }
        // First visit.
        else
        {
            // Render the page.
            echo $this->core_template_library->parse_view($template, self::$data);
        }

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
            redirect(base_url($this->core_user_library->user_login_uri));
        }
        $user = $this->core_user_library->user_find($id);
        self::$data['user'] = $user;

        // Render the page.
        $this->load->view($template, self::$data);
    }

    /**
     * Basic login method.
     */
    public function login()
    {
        $template = 'user_login';

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
                redirect(base_url($this->core_user_library->user_index_uri));
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
     * User recovers password with email address.
     */
    public function forgotten_password()
    {
        $template = 'user_forgotten_password';

        if ($this->input->post('submit'))
        {
            $this->core_user_library->set_validation_rules('user_forgotten_password');

            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                $this->load->view($template, self::$data);
            }
            else
            {
                $this->core_user_library-> user_forgotten_password($this->input->post('email'));
            }
        }
        else
        {
            // Render the page.
            $this->load->view($template, self::$data);
        }


    }

    /**
     * User comes to this link from an email with the lost password code.
     *
     * @param string $code
     */
    public function forgotten_password_login($code = NULL)
    {
        // Send to the model.
        $this->core_user_library->user_forgotten_password_login($code);
    }

    /**
     * New user create account.
     */
    public function user_add()
    {
        $template = 'user_add';

        if ($this->input->post('add'))
        {
            $this->core_user_library->set_validation_rules('user_insert');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                $this->load->view($template, self::$data);
            }
            else
            {
                // Form validation passed.
                $this->core_user_library->user_add($this->input->post());
            }
        }
        // Code to run when user first visits.
        else
        {
            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * User submits activation code from email link.
     *
     * @param string $activation_code
     */
    public function user_activate($activation_code = NULL)
    {
        if ($activation_code == NULL)
        {
            redirect(base_url($this->core_user_library->user_login_uri));
        }
        else
        {
            $this->core_user_library->user_activate($activation_code);
        }
    }

    /**
     * User edits own account.
     */
    public function user_edit()
    {
        $template = 'user_edit';

        // Check if a user is logged in then set the user id from the session.
        if (!$this->session->userdata('user_id'))
        {
            redirect(base_url($this->core_user_library->user_index_uri));
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
                redirect(base_url($this->core_user_library->user_delete_uri));
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
                // Render the page.
                $this->load->view($template, self::$data);
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
            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * User deletes own account.
     */
    public function user_delete()
    {
        $template = 'user_delete';

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

            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Admin view all roles.
     */
    public function admin_user_roles($start = NULL)
    {
        $template = 'core_user/admin_roles';

        $per_page = variable_get('core_module_pagination_per_page');
        $start = ($start) ? $start : 0;
        $count = count($this->core_user_library->admin_role_get_all('array'));
        $base_url = base_url($this->core_user_library->user_admin_roles_uri);

        // Pagination setup.
        self::$data['pagination'] = pagination_setup($base_url, $count, $per_page, 2);

        // Get the roles.
        self::$data['roles'] = $this->core_user_library->admin_role_get_limit_offset($per_page, $start, 'object');

        // Parse values if neccessary.
        foreach (self::$data['roles'] as $value)
        {
            // Convert the protected boolean field to string.
            $value->protected = ($value->protected) ? 'Yes' : 'No';
        }

        // Render the page.
        echo $this->core_template_library->parse_view($template, self::$data);
    }

    /**
     * Admin add a role.
     */
    public function admin_user_role_add()
    {
        $template = 'admin_role_add';

        if ($this->input->post('save'))
        {
            $this->core_user_library->set_validation_rules('admin_role_insert');

            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                $this->load->view($template, self::$data);
            }
            else
            {
                $this->core_user_library->admin_role_add($this->input->post());
            }
        }
        else
        {
            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Admin edits a role.
     *
     * @param integer $id
     */
    public function admin_user_role_edit($id = NULL)
    {
        $template = 'admin_role_edit';

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
                $this->load->view($template, self::$data);
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
                redirect(base_url($this->core_user_library->user_admin_roles_uri));
            }

            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Admin deletes a role.
     *
     * @param integer $id
     */
    public function admin_user_role_delete($id = NULL)
    {
        // Redirect if role id is not set.
        if (!$id) redirect(base_url($this->core_user_library->user_admin_roles_uri));

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
        $template = 'core_user/admin_users';

        // Per_page for pagination and model query.
        $per_page = variable_get('core_module_pagination_per_page');

        // Set start record for query.
        $start = 0;

        if ($page)
        {
            $start = $page;
        }

        // Database queries.
        $count = $this->core_user_library->admin_user_get_count();
        self::$data['users'] = $this->core_user_library->admin_user_limit_offset_get($per_page, $start);

        // Parse user data that needs parsing.
        foreach (self::$data['users'] as $key => $value)
        {
            // Convert the protected boolean field to string.
            self::$data['users'][$key]['protected'] = (self::$data['users'][$key]['protected']) ? 'Yes' : 'No';
            // Convert created unix time stamp to time.
            self::$data['users'][$key]['created'] = standard_date(variable_get('core_module_time_format'), self::$data['users'][$key]['created']);
        }

        // Get first and last id's.
        self::$data['first'] = $page + 1;
        self::$data['last'] = $page + count(self::$data['users']);

        // Pagination setup
        $base_url = base_url() . 'admin_users/';
        $pagination_links = pagination_setup($base_url, $count, $per_page);

        // Page setup
        self::$data['pagination_links'] = $pagination_links;
        self::$data['count'] = $count;

        // Render the page.
        echo $this->core_template_library->parse_view($template, self::$data);
    }

    /**
     * Admin edits a user.
     *
     * @param integer $id
     */
    public function admin_user_edit($id = NULL)
    {
        $template = 'admin_user_edit';
        self::$data['all_roles'] = $this->core_user_library->admin_role_get_all('array');
        $user = $this->core_user_library->user_find((int) $id);

        // Redirect admin user if id is not set.
        if ($id == NULL && !$this->input->post('save'))
        {
            redirect(base_url($this->core_user_library->user_admin_users_uri));
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
                $this->load->view($template, self::$data);
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
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Admin adds a user.
     */
    public function admin_user_add()
    {
        $template = 'admin_user_add';
        self::$data['all_roles'] = $this->core_user_library->admin_role_get_all('array');

        if ($this->input->post('save'))
        {
            $this->core_user_library->set_validation_rules('admin_user_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                $this->load->view($template, self::$data);
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
            $this->load->view($template, self::$data);
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

/* End of file admin.php */
