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
    private static $template = 'core_template/default_template';

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
        $this->config->load('user_config');
        $this->lang->load('user', $this->config->item('user_language'));

        // Load libraries.
        $this->load->library('user_library');
        $this->load->library('core_library/core_library');

        // Load helpers.
        $this->load->helper('date');
        $this->load->helper('form');

        // Load models.
        $this->load->model('user_model');

        // Check if a user is logged in.
        $this->user_library->check_logged_in();

        // Initialize the data array.
        self::$data = $this->core_model->site_info();

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
            redirect(base_url() . $this->user_library->user_login_uri);
        }
        $user = $this->user_model->find_user($id);
        self::$data['user'] = $user;

        echo Modules::run(self::$template, self::$data);
    }

    /**
     * Basic login method.
     */
    public function login()
    {
        self::$data['view_file'] = 'user_login';

        // Code to run when the user hits the Login button.
        if ($this->input->post('submit'))
        {
            // Sets the CI validation rules.
            $this->user_library->set_validation_rules('user_login');
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
                $user     = $this->user_model->login($username, $password);
                if ($user)
                {
                    // Login success.
                    log_message('error', $username . ' logged in.');
                    $this->session->set_flashdata('message_success', $this->lang->line('success_user_login') . $username . '.');
                    $this->user_library->set_user_session_data($user);

                    // Set the login cookie if checked.
                    $set_persistent_login = (bool) $this->input->post('set_persistent_login');
                    if ($set_persistent_login)
                    {
                        $remember_code       = $this->user_library->set_persistent_login();
                        $store_remember_code = $this->user_model->store_remember_code($remember_code, $user->id);

                        if (!$store_remember_code)
                        {
                            $this->session->set_flashdata('message_notice', $this->lang->line('notice_user_persistent_fail'));
                            $this->user_library->unset_persistent_login();
                        }
                    }
                    redirect(base_url() . $this->user_library->user_index_uri);
                }

                // Code to run if username and password combination is not found in the
                // database.
                else
                {
                    // Unsuccessful login.
                    $this->session->set_flashdata('message_error', $this->lang->line('error_user_login_failed'));
                    redirect(current_url());
                }
            }
        }

        // Code to run when the user visits the page without hitting the Login
        // button.
        if ($this->session->userdata('user_id'))
        {
            $this->core_library->keep_flashdata_messages();
            redirect(base_url() . $this->user_library->user_index_uri);
        }

        echo Modules::run(self::$template, self::$data);
    }

    /**
     * Basic logout method using Codeigniter.
     */
    public function logout()
    {
        $username = $this->session->userdata('username');
        $id       = $this->session->userdata('user_id');
        log_message('error', $username . ' logged out.');
        $result   = $this->user_model->delete_remember_code($id);

        if (!$result)
        {
            log_message('error', $username . $this->lang->line('error_user_delete_remember_failed'));
        }

        $this->user_library->logout('user/login/');
    }

    /**
     * New user create account.
     */
    public function add()
    {
        self::$data['view_file'] = 'user_add';

        if ($this->input->post('add'))
        {
            $this->user_library->set_validation_rules('user_insert');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Form does not validate.
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                // Form validation passed.
                $result_id = $this->user_model->add_user($this->input->post());

                switch ($result_id)
                {
                    case TRUE:
                        $this->session->set_flashdata('message_success', $this->lang->line('success_user_account_created'));
                        redirect(base_url() . $this->user_library->user_login_uri);
                        break;
                    case FALSE:
                        $this->session->set_flashdata('message_error', $this->lang->line('error_user_account_failed'));
                        redirect(current_url());
                        break;
                }
            }
        }
        else
        {
            // Code to run when user first visits.
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * User edits own account.
     */
    public function edit()
    {
        self::$data['view_file'] = 'user_edit';

        // Check if a user is logged in then set the user id from the session.
        if (!$this->session->userdata('user_id'))
        {
            redirect(base_url() . $this->user_library->user_index_uri);
        }
        else
        {
            $id   = $this->session->userdata('user_id');
            $user = $this->user_model->find_user($id);
            self::$data['user'] = $user;
        }

        // When the user hits the delete button redirect them to the delete method.
        if ($this->input->post('delete'))
        {
            if ($this->session->userdata('user_id'))
            {
                redirect(base_url() . $this->user_library->user_delete_uri);
            }
        }

        // Code to run when the user hits the save button.
        if ($this->input->post('save'))
        {
            // Set the validation rules for updating a user.
            $this->user_library->set_validation_rules('user_update');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Form does not validate.
                echo Modules::run(self::$template, self::$data);
            }

            // Code to run when the form passes validation.
            else
            {
                // Check first if account is protected.
                $this->user_library->check_user_protected($user, 'edit');

                $result = $this->user_model->edit_user($this->input->post());

                switch ($result)
                {
                    case TRUE:
                        $this->session->set_flashdata('message_success', $this->lang->line('success_user_account_edited'));
                        redirect(base_url() . $this->user_library->user_index_uri);
                        break;
                    case FALSE:
                        $this->session->set_flashdata('message_error', $this->lang->line('error_user_account_edit_failed'));
                        redirect(current_url());
                        break;
                }
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
            $user = $this->user_model->find_user($id);
        }
        else
        {
            redirect(base_url());
        }

        // Code to run when user hits the final delete button.
        if ($this->input->post('delete'))
        {
            // Check first if account is protected.
            $this->user_library->check_user_protected($user, 'delete');

            // Delete the user.
            $result = $this->user_model->delete_user($id);

            switch ($result)
            {
                case TRUE:
                    redirect(base_url() . $this->user_library->user_logout_uri);
                    break;
                case FALSE:
                    $this->session
                        ->set_flashdata('message_error', $this->lang->line('error_user_account_delete_failed'));
                    redirect(base_url() . $this->user_library->user_edit_uri);
                    break;
            }
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
        $roles      = $this->user_model->admin_get_all_roles('array');
        $role_table = $this->user_library->admin_role_table($roles);

        // Render the page.
        self::$data['output'] = $role_table;
        echo Modules::run(self::$template, self::$data);
    }

    /**
     * Admin add a role.
     */
    public function admin_add_role()
    {
        self::$data['view_file'] = 'admin_add_role';

        if ($this->input->post('save'))
        {
            $this->user_library->set_validation_rules('admin_role_insert');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $result = $this->user_model->admin_save_role($this->input->post());

                if ($result)
                {
                    $this->session->set_flashdata('message_success', $this->lang->line('success_admin_add__role'));
                    redirect(current_url());
                }
                else
                {
                    $this->session->set_flashdata('message_error', $this->lang->line('error_admin_add_role'));
                    redirect(current_url());
                }
            }
        }
        else
        {
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin edites a role.
     *
     * @param integer $id
     */
    public function admin_edit_role($id = NULL)
    {
        self::$data['view_file'] = 'admin_edit_role';

        // Instantiate the role to populate the form.
        $role = $this->user_model->admin_get_role($id);
        self::$data['role'] = ($role) ? $role : NULL;

        if ($this->input->post('save'))
        {
            $role = $this->user_model->admin_get_role($this->input->post('id'));

            // Check first if role is protected.
            $this->user_library->admin_check_role_protected($role);

            $this->user_library->set_validation_rules('admin_role_update');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $result = $this->user_model->admin_save_role($this->input->post());

                if ($result)
                {
                    $this->session->set_flashdata('message_success', $this->lang->line('success_admin_edit_role'));
                    redirect(base_url() . $this->user_library->user_admin_roles_uri);
                }
                else
                {
                    $this->session->set_flashdata('message_error', $this->lang->line('error_admin_edit_role'));
                    redirect(current_url());
                }
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
    public function admin_delete_role($id = NULL)
    {
        // Redirect if role id is not set.
        if (!$id) redirect(base_url() . $this->user_library->user_admin_roles_uri);

        $role = $this->user_model->admin_get_role($id);

        // Check if role is protected.
        $this->user_library->admin_check_role_protected($role);

        // Delete the role.
        $result = $this->user_model->admin_delete_role($id);

        switch ($result)
        {
            case TRUE:
                $this->session->set_flashdata('message_success', $this->lang->line('success_delete_role'));
                redirect(base_url() . $this->user_library->user_admin_roles_uri);
                break;
            case FALSE:
                $this->session->set_flashdata('message_error', $this->lang->line('error_admin_delete_role'));
                redirect(current_url());
                break;
        }
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
        $query1 = $this->user_model->admin_get_limit_offset_users($per_page, $start);
        $query2 = $this->user_model->admin_get_all_users();
        $count  = count($query2);
        $output = $query1;

        // Get first and last id's.
        self::$data['first'] = $page + 1;
        self::$data['last'] = $page + count($output);

        // Pagination setup
        $pagination_config = $this->user_library
            ->admin_user_page_pagination_setup($count, $per_page);

        // Table render
        $table_output = $this->user_library->admin_user_page_table_setup($output);

        // Page setup
        self::$data['pagination_links'] = $pagination_config;
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
    public function admin_edit_user($id = NULL)
    {
        self::$data['view_file'] = 'admin_edit_user';
        self::$data['all_roles'] = $this->user_model->admin_get_all_roles('object');

        // Redirect admin user if id is not set.
        if ($id == NULL && !$this->input->post('save'))
        {
            redirect(base_url() . $this->user_library->user_admin_users_uri);
        }
        elseif ($this->input->post('save'))
        {
            $this->user_library->set_validation_rules('admin_user_update');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $id   = $this->input->post('id');
                $user = $this->user_model->find_user($id)->row();

                // Check first if user account is protected.
                $this->user_library->admin_check_user_protected($user);

                // Delete the user account.
                $result = $this->user_model->admin_save_user($this->input->post());

                switch ($result)
                {
                    case TRUE:
                        $this->session->set_flashdata('message_success', $this->lang->line('success_admin_edit_user'));
                        redirect(base_url() . $this->user_library->user_admin_add_user_uri . $id);
                        break;
                    case FALSE:
                        $this->session->set_flashdata('message_error', $this->lang->line('error_admin_edit_user'));
                        redirect(base_url() . $this->user_library->user_admin_add_user_uri . $id);
                        break;
                }
            }
        }
        else
        {
            // Add the js file.
            array_unshift(self::$data['scripts'], 'user_admin_ajax.js');

            $user = $this->user_model->find_user((int) $id);
            self::$data['user'] = $user;
            echo Modules::run(self::$template, self::$data);
        }
    }

    /**
     * Admin adds a user.
     */
    public function admin_add_user()
    {
        self::$data['view_file'] = 'admin_add_user';
        self::$data['all_roles'] = $this->user_model->admin_get_all_roles('object');

        if ($this->input->post('save'))
        {
            $this->user_library->set_validation_rules('admin_user_insert');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                // Add the user.
                $result = $this->user_model->admin_save_user($this->input->post(), NULL);

                switch ($result)
                {
                    case TRUE:
                        $this->session->set_flashdata('message_success', $this->lang->line('success_admin_add_user'));
                        redirect(base_url() . $this->user_library->user_admin_users_uri);
                        break;
                    case FALSE:
                        $this->session->set_flashdata('message_error', $this->lang->line('error_admin_add_user'));
                        redirect(base_url() . $this->user_library->user_admin_users_uri);
                        break;
                }
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
    public function admin_delete_user($id = NULL)
    {
        if ($id == NULL)
        {
            redirect(base_url());
        }
        else
        {
            $user   = $this->user_model->find_user($id);

            // Check first if user account is protected.
            $this->user_library->admin_check_user_protected($user);

            // Delete the user.
            $result = $this->user_model->admin_delete_user($id);

            switch ($result)
            {
                case $result == 'deleted':
                    $this->session->set_flashdata('message_success', $this->lang->line('success_admin_delete_user'));
                    redirect(base_url() . $this->user_library->user_admin_users_uri);
                    break;
                case $result == FALSE:
                    $this->session->set_flashdata('message_error', $this->lang->line('error_admin_delete_user'));
                    redirect(base_url() . $this->user_library->user_admin_users_uri . self::$user_page);
                    break;
            }
        }
    }

}
/* End of file user.php */
