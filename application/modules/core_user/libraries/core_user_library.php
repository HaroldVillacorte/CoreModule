<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_user_library
{

    /**
     * The CI super object reference.
     *
     * @var object
     */
    private static $CI;

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * User profile uri.
     *
     * @var string
     */
    public $user_index_uri;

    /**
     * User login uri.
     *
     * @var string
     */
    public $user_login_uri;

    /**
     * User logout uri.
     *
     * @var string
     */
    public $user_logout_uri;

    /**
     * User create account uri.
     *
     * @var string
     */
    public $user_add_uri;

    /**
     * User activate account uri.
     *
     * @var string
     */
    public $user_activation_uri;

    /**
     * User edit account uri.
     *
     * @var string
     */
    public $user_edit_uri;

    /**
     * User delete own account uri.
     *
     * @var string
     */
    public $user_delete_uri;

    /**
     * Admin index page uri.  Currently not in use.
     *
     * @var string
     */
    public $user_admin_index_uri;

    /**
     * Admin user roles uri.
     *
     * @var string
     */
    public $user_admin_roles_uri;

    /**
     * Admin add user role uri.
     *
     * @var string
     */
    public $user_admin_add_role_uri;

    /**
     * Admin edit user roles uri.
     *
     * @var string
     */
    public $user_admin_edit_role_uri;

    /**
     * Admin delete user roles uri.
     *
     * @var string
     */
    public $user_delete_role_uri;

    /**
     * Admin paginted users page uri.
     *
     * @var string
     */
    public $user_admin_users_uri;

    /**
     * Admin edit user page uri.
     *
     * @var string
     */
    public $user_admin_edit_user_uri;

    /**
     * Admin add user page uri.
     *
     * @var string
     */
    public $user_admin_add_user_uri;

    /**
     * Admin delete user uri.
     *
     * @var string
     */
    public $user_admin_delete_user_uri;

    function __construct()
    {
        self::$CI = & get_instance();

        // Load the config and language files.
        self::$CI->config->load('core_user/core_user_config');
        self::$CI->lang->load('core_user/core_user', self::$CI->config->item('core_user_language'));

        // Load the libraries.
        self::$CI->load->library('session');
        self::$CI->load->library('form_validation');
        self::$CI->load->library('table');
        self::$CI->load->library('pagination');

        // Load the models.
        self::$CI->load->model('core_user/core_user_model');

        // Load the Database class.
        self::$CI->load->database();

        // Intialize the data array.
        self::$data = self::$CI->core_module_model->site_info();

        // Set the user uri's.
        $this->user_index_uri      = self::$CI->config->item('user_index_uri');
        $this->user_login_uri      = self::$CI->config->item('user_login_uri');
        $this->user_logout_uri     = self::$CI->config->item('user_logout_uri');
        $this->user_add_uri        = self::$CI->config->item('user_add_uri');
        $this->user_activation_uri = self::$CI->config->item('user_activation_uri');
        $this->user_edit_uri       = self::$CI->config->item('user_edit_uri');
        $this->user_delete_uri     = self::$CI->config->item('user_delete_uri');

        // Set the admin uri's.
        $this->user_admin_index_uri         = self::$CI->config->item('user_admin_index_uri');
        $this->user_admin_roles_uri         = self::$CI->config->item('user_admin_roles_uri');
        $this->user_admin_add_role_uri      = self::$CI->config->item('user_admin_add_role_uri');
        $this->user_admin_edit_role_uri     = self::$CI->config->item('user_admin_edit_role_uri');
        $this->user_delete_role_uri         = self::$CI->config->item('user_delete_role_uri');
        $this->user_admin_users_uri         = self::$CI->config->item('user_admin_users_uri');
        $this->user_admin_edit_user_uri     = self::$CI->config->item('user_admin_edit_user_uri');
        $this->user_admin_add_user_uri      = self::$CI->config->item('user_admin_add_user_uri');
        $this->user_admin_delete_user_uri   = self::$CI->config->item('user_admin_delete_user_uri');
    }

    /**
     * Options: user_login, user_insert, user_update, admin_role_isert,
     * admin_role_update, admin_user_insert, admin_user_update.
     *
     * @param string $rules
     */
    public function set_validation_rules($rules = '')
    {
        $user_login = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|max_length[12]|sha1',
            ),
        );

        $user_insert = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim|is_unique[users.username]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|matches[passconf]|sha1',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'required|trim',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|valid_email|is_unique[users.email]'
            ),
        );

        $user_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer',
            ),
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|valid_base64|trim|max_length[12]|matches[passconf]|sha1',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'trim',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|valid_email'
            ),
        );

        $admin_role_insert = array(
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required|is_unique[roles.role]',
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required',
            ),
        );

        $admin_role_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer',
            ),
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required',
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required',
            ),
        );

        $admin_user_insert = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|is_unique[users.username]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|matches[passconf]|sha1',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]'
            ),
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required',
            ),
        );

        // Set the validation rules for updating a user.
        $admin_user_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer',
            ),
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required',
            ),
        );

        $rule_set = array();

        switch ($rules)
        {
            case 'user_login':
                $rule_set = $user_login;
                break;
            case 'user_insert':
                $rule_set = $user_insert;
                break;
            case 'user_update':
                $rule_set = $user_update;
                break;
            case 'admin_role_insert':
                $rule_set = $admin_role_insert;
                break;
            case 'admin_role_update':
                $rule_set = $admin_role_update;
                break;
            case 'admin_user_insert':
                $rule_set = $admin_user_insert;
                break;
            case 'admin_user_update':
                $rule_set = $admin_user_update;
                break;
        }

        self::$CI->form_validation->set_rules($rule_set);

        // Set the valid_base64 error since it is currently missing from the CI core
        // language files.
        $valid_base64_error = self::$CI->lang->line('validation_valid_base64');;
        self::$CI->form_validation->set_message('valid_base64', $valid_base64_error);
    }

    /**
     * Finds a user by primary key $id.
     *
     * @param integer $id
     * @return mixed
     */
    public function user_find($id = NULL)
    {
        $user = self::$CI->core_user_model->user_find($id);
        return ($user) ? $user->row() : FALSE;
    }

    /**
     * User adds own account.
     *
     * @param array $post
     */
    public function user_add($post = array())
    {
        $result_id = self::$CI->core_user_model->user_add($post);

        switch ($result_id)
        {
            case TRUE:
                $this->user_send_welcome_email($result_id);
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_account_failed'));
                redirect(current_url());
                break;
        }
    }

    public function user_send_welcome_email($result_id = NULL)
    {
        // Load the email module.
        self::$CI->load->library('core_email/core_email_library');

        // Find new user.
        $user = $this->user_find($result_id);

        // Message.
        self::$data['username']            = $user->username;
        self::$data['email']               = $user->email;
        self::$data['activation_url_text'] = base_url() . $this->user_activation_uri . $user->activation_code;
        self::$data['activation_url_html'] = anchor($this->user_activation_uri . $user->activation_code, 'Activate');
        self::$data['login_url_text']      = base_url() . $this->user_login_uri;
        self::$data['login_url_html']      = anchor($this->user_login_uri, 'Login');
        $message     = self::$CI->load->view('core_user/email_templates/welcome', self::$data, TRUE);
        $message_alt = self::$CI->load->view('core_user/email_templates/welcome_alt', self::$data, TRUE);

        // Set the message array.
        $email = array(
            'to'          => $user->email,
            'to_name'     => $user->username,
            'subject'     => 'Welcome to ' . self::$data['site_name'],
            'message'     => $message,
            'message_alt' => $message_alt,
        );

        // Send the email.
        $result = self::$CI->core_email_library->system_email_send($email);

        // Log email error.
        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_user_account_created'));
            redirect(base_url() . $this->user_login_uri);

        }
        else
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_account_failed'));
            self::$CI->core_user_model->admin_user_delete($user->id);
            redirect(current_url());
        }
    }

    /**
     * User activation.
     *
     * @param string $activation_code
     */
    public function user_activate($activation_code = NULL)
    {
        $check_code = self::$CI->core_module_library->validate_sha1($activation_code);

        // Validate the activation code.
        if (!$check_code)
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_account_activation_invalid'));
            redirect(base_url() . $this->user_login_uri);
        }

        // Submit to the database.
        $result = self::$CI->core_user_model->user_activate($activation_code);
        switch ($result)
        {
            case 'activated':
                self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_user_account_activation'));
                redirect(base_url() . $this->user_login_uri);
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_account_activation'));
                redirect(base_url() . $this->user_login_uri);
                break;
            case 'not_found':
                self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_account_activation_not_found'));
                redirect(base_url() . $this->user_login_uri);
                break;
            case 'expired':
                self::$CI->session->set_flashdata('message_notice', self::$CI->lang->line('notice_user_account_activation_expired'));
                redirect(base_url() . $this->user_login_uri);
                break;
        }
    }

    /**
     * User edits own account.
     *
     * @param object $user
     * @param array $post
     */
    public function user_edit($user = NULL, $post = array())
    {
        // Check first if account is protected.
        $this->user_check_protected($user, 'edit');

        $result = self::$CI->core_user_model->user_edit($post);

        switch ($result)
        {
            case TRUE:
                self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_user_account_edited'));
                redirect(base_url() . $this->user_index_uri);

                // Reset the user session data ater update.
                $updated_user = $this->user_find((int) $post['id']);
                $this->user_set_session_data($updated_user);

                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_account_edit_failed'));
                redirect(current_url());
                break;
        }
    }

    /**
     * User deletes own account.
     *
     * @param object $user
     */
    public function user_delete($user = NULL)
    {
        // Delete the user.
        $result = self::$CI->core_user_model->user_delete($user->id);

        switch ($result)
        {
            case TRUE:
                redirect(base_url() . $this->user_logout_uri);
                break;
            case FALSE:
                self::$CI->session
                    ->set_flashdata('message_error', self::$CI->lang->line('error_user_account_delete_failed'));
                redirect(base_url() . $this->user_edit_uri);
                break;
        }
    }

    /**
     * User logs in.
     *
     * @param string $username
     * @param string $password
     * @param string $set_persistent_login
     */
    public function user_login($username = NULL, $password = NULL, $set_persistent_login = NULL)
    {
        $user = self::$CI->core_user_model->user_login($username, $password);
        if ($user)
        {
            // Login success.
            log_message('error', $username . ' logged in.');
            self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_user_login') . $username . '.');
            $this->user_set_session_data($user);

            // Set the login cookie if checked.
            if ($set_persistent_login)
            {
                $remember_code       = $this->user_set_persistent_login();
                $store_remember_code = self::$CI->core_user_model->user_remember_code_store($remember_code, $user->id);

                if (!$store_remember_code)
                {
                    self::$CI->session->set_flashdata('message_notice', self::$CI->lang->line('notice_user_persistent_fail'));
                    $this->user_unset_persistent_login();
                }
            }
            redirect(base_url() . $this->user_index_uri);
        }

        // Code to run if username and password combination is not found in the
        // database.
        else
        {
            // Unsuccessful login.
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_login_failed'));
            redirect(current_url());
        }
    }

    /**
     * Set the uri to redirect user after logging out.
     *
     * @param string $redirect_uri
     * @return boolean
     */
    public function user_logout()
    {
        $username = self::$CI->session->userdata('username');
        $id       = self::$CI->session->userdata('user_id');
        log_message('error', $username . ' logged out.');
        $result   = self::$CI->core_user_model->user_remember_code_delete($id);

        if (!$result)
        {
            log_message('error', $username . self::$CI->lang->line('error_user_delete_remember_failed'));
        }

        $userarray = array('user_id', 'username', 'password', 'email', 'role',);
        self::$CI->session->unset_userdata($userarray);
        self::$CI->session->sess_destroy();
        self::$CI->session->sess_create();
        self::$CI->session->set_flashdata('message_notice', self::$CI->lang->line('notice_user_logout'));

        // Unset the logged in cookie if set.
        $this->user_unset_persistent_login();

        redirect(base_url() . $this->user_login_uri);
        return TRUE;
    }

    /**
     * User object is instatiated first then pass to this function.
     *
     * @param object $user
     */
    public function user_set_session_data($user = NULL)
    {
        $userarray = array(
            'user_id'  => $user->id,
            'username' => $user->username,
            'email'    => $user->email,
            'role'     => $user->role,
        );

        // Set userdata session information.
        self::$CI->session->set_userdata($userarray);
    }

    /**
     * Logged in cookie is set and the remember code is returned for storing in the
     * database.
     *
     * @return string
     */
    public function user_set_persistent_login()
    {
        self::$CI->load->helper('string');
        self::$CI->load->library('encrypt');

        $cookie_name           = self::$CI->config->item('user_persistent_cookie_name');
        $remember_code         = random_string('alnum', 32);
        $remember_code_encoded = self::$CI->encrypt->encode($remember_code);
        $cookie_expire = self::$CI->config->item('user_persistent_cookie_expire');

        $data = array(
            'name'   => $cookie_name,
            'value'  => $remember_code_encoded,
            'expire' => $cookie_expire,
        );

        self::$CI->input->set_cookie($data);

        return $remember_code;
    }

    /**
     * Delete the logged in cookie.
     */
    public function user_unset_persistent_login()
    {

        $cookie_name = self::$CI->config->item('user_persistent_cookie_name');

        self::$CI->load->helper('cookie');
        delete_cookie($cookie_name);
    }

    /**
     * Check if a user has an unexpired logged in cookie.
     */
    public function user_check_logged_in()
    {
        self::$CI->load->helper('string');
        self::$CI->load->library('encrypt');

        $cookie_name = self::$CI->config->item('user_persistent_cookie_name');

        // If user is already logged in skip the checking.
        if (!self::$CI->session->userdata('user_id'))
        {
            // It is neccessary to do a query here because I cannot load the model while
            // the model loads the library.
            if (self::$CI->input->cookie($cookie_name))
            {
                $remember_code_encoded = self::$CI->db->escape_str(self::$CI->input->cookie($cookie_name));
                $remember_code         = self::$CI->encrypt->decode($remember_code_encoded);
                $ip_address            = self::$CI->db->escape_str(self::$CI->session->userdata('ip_address'));
                $user_agent            = self::$CI->db->escape_str(self::$CI->session->userdata('user_agent'));

                $result = self::$CI->db
                    ->select('users.id, users.protected, username, email, created, role')
                    ->join('join_users_roles', 'join_users_roles.user_id = users.id')
                    ->join('roles', 'roles.id = join_users_roles.role_id')
                    ->get_where('users', array(
                        'users.remember_code' => $remember_code,
                        'ip_address'          => $ip_address,
                        'user_agent'          => $user_agent,
                        ));

                $user = $result->row();

                // If logged in cookie is validated set the userdata.
                if ($user)
                {
                    $this->user_set_session_data($user);
                }
            }
        }
    }

    /**
     * The permissions method.
     *
     * @param array $role.
     */
    public function user_permission($role = array())
    {
        // Sets the $user_role variable.
        if (self::$CI->session->userdata('role'))
        {
            $user_role = self::$CI->session->userdata('role');
        }
        else
        {
            $user_role = '';
        }

        // If the $role set by the method that calls this method does not match the
        // $user_role variable user will be redirected.
        if (!in_array($user_role, $role))
        {
            // If the user is logged send user to the profile page.
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_permission'));
            redirect(base_url() . $this->user_login_uri);
        }
    }

    /**
     * Method accepts the user object.  Action is either 'edit' or 'delete'.
     *
     * @param object $user
     * @param string $action
     */
    public function user_check_protected($user = NULL, $action = NULL)
    {
        switch ($action)
        {
            case 'edit':
                // Super user can delete own account if it is not User 1.
                $condition = ($user->protected && self::$CI->session->userdata('role') != 'super_user');
                break;
            case 'delete':
                // User 1 cannot be deleted through this application.
                $condition = ($user->protected && self::$CI->session->userdata('role') != 'super_user') || $user->id == 1;
                break;
        }

        if ($condition)
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_user_protected'));
            redirect(base_url() . $this->user_index_uri);
        }
    }

    /**
     * Generates the table for the paginated roles page.
     *
     * @return mixed
     */
    public function admin_role_table()
    {
        // Get roles.
        $output = self::$CI->core_user_model->admin_role_get_all()->result_array();

        // Table headings
        $add_link = base_url() . $this->user_admin_add_role_uri;

        $heading = array(
            'ID', 'Role', 'Description', 'Protected',
            '<a href="' . $add_link . '" class="right">Add role +</a>',
        );

        self::$CI->table->set_heading($heading);

        // Table template
        $template = array(
            'table_open'  => '<table width="100%">',
            'table_close' => '</table>',
        );

        self::$CI->table->set_template($template);

        foreach ($output as $key => $value)
        {   // add edit link.
            $output[$key]['edit'] =
                '<a href="' . base_url() . $this->user_delete_role_uri
                . $output[$key]['id']
                . '" class="label alert round right" style="margin-left:10px;"'
                . 'onClick="return confirm(' . self::$CI->lang->line('confirm_admin_role_delete') . ')">Del</a>'
                . '<a href="' . base_url()
                . $this->user_admin_edit_role_uri . $output[$key]['id']
                . '" class="label secondary round right">Edit</a>';

            if ($output[$key]['protected'])
            {
                $output[$key]['protected'] = 'Yes';
            }
            else
            {
                $output[$key]['protected'] = 'No';
            }
        }

        $role_table = self::$CI->table->generate($output);
        return $role_table;
    }

    public function admin_role_get($id = NULL)
    {
        $role = self::$CI->core_user_model->admin_role_get($id);
        return ($role) ? $role->row() : FALSE ;
    }

    public function admin_role_get_all()
    {
        $result = self::$CI->core_user_model->admin_role_get_all();
        return ($result) ? $result->result() : FALSE;
    }

    /**
     * Admin adds role.
     *
     * @param array $post
     */
    public function admin_role_add($post = array())
    {
        $result = self::$CI->core_user_model->admin_role_save($post);

        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_admin_add_role'));
            redirect(base_url() . $this->user_admin_edit_role_uri . $result);
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_add_role'));
            redirect(current_url());
        }
    }

    /**
     * Admin edits role.
     *
     * @param array $post
     */
    public function admin_role_edit($post = array())
    {
        $result = self::$CI->core_user_model->admin_role_save($post);

        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_admin_edit_role'));
            redirect(base_url() . $this->user_admin_edit_role_uri . $post['id']);
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_edit_role'));
            redirect(base_url() . $this->user_admin_edit_role_uri . $post['id']);
        }
    }

    /**
     * Admin deletes a role.
     *
     * @param integer $id
     */
    public function admin_role_delete($id = NULL)
    {
        $result = self::$CI->core_user_model->admin_role_delete($id);

        switch ($result)
        {
            case TRUE:
                self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_admin_delete_role'));
                redirect(base_url() . $this->user_admin_roles_uri);
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_delete_role'));
                redirect(base_url() . $this->user_admin_roles_uri);
                break;
        }
    }

    /**
     * Admin edits a user.
     *
     * @param array $post
     */
    public function admin_user_add($post = array())
    {
        $result = self::$CI->core_user_model->admin_user_save($post);

                switch ($result)
                {
                    case TRUE:
                        self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_admin_add_user'));
                        redirect(base_url() . $this->user_admin_users_uri);
                        break;
                    case FALSE:
                        self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_add_user'));
                        redirect(base_url() . $this->user_admin_users_uri);
                        break;
                }
    }

    /**
     * Admin edits a user.
     *
     * @param array $post
     */
    public function admin_user_edit($post = array())
    {
        $result = self::$CI->core_user_model->admin_user_save($post);

        switch ($result)
        {
            case TRUE:
                self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_admin_edit_user'));
                redirect(base_url() . $this->user_admin_edit_user_uri . $post['id']);
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_edit_user'));
                redirect(base_url() . $this->user_admin_edit_user_uri . $post['id']);
                break;
        }
    }

    /**
     * Admindeletes a user.
     *
     * @param integer $id
     * @param string $user_page
     */
    public function admin_user_delete($id = NULL, $user_page = 1)
    {
        $result = self::$CI->core_user_model->admin_user_delete($id);

        switch ($result)
        {
            case $result == TRUE:
                self::$CI->session->set_flashdata('message_success', self::$CI->lang->line('success_admin_delete_user'));
                redirect(base_url() . $this->user_admin_users_uri);
                break;
            case $result == FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_delete_user'));
                redirect(base_url() . $this->user_admin_users_uri . $user_page);
                break;
        }
    }

    /**
     * Admin gets all users.
     *
     * @return integer
     */
    public function admin_get_user_count()
    {
        $result = self::$CI->core_user_model->admin_user_get_all();

        if ($result)
        {
            return count($result);
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_get_user_count'));
            redirect(base_url . $this->user_admin_index_uri);
        }
    }

    /**
     * Paginated users.
     *
     * @param integer $limit
     * @param integer $offset
     * @return mixed
     */
    public function admin_user_limit_offset_get($limit, $offset)
    {
        $result = self::$CI->core_user_model->admin_user_limit_offset_get($limit, $offset);
        return ($result) ? $result : FALSE;
    }

    /**
     * Generates pagination links for user admin page.
     *
     * @param integer $count
     * @param integer $per_page
     * @return string
     */
    public function admin_user_page_pagination_setup($count, $per_page)
    {
        $pagination_config = array();

        // Pagination setup
        $pagination_config['base_url']   = base_url() . $this->user_admin_users_uri;
        $pagination_config['total_rows'] = $count;
        $pagination_config['per_page']   = $per_page;

        // Style pagination Foundation 3
        // Full open
        $pagination_config['full_tag_open'] = '<ul class="pagination">';

        // Digits
        $pagination_config['num_tag_open']  = '<li>';
        $pagination_config['num_tag_close'] = '</li>';

        // Current
        $pagination_config['cur_tag_open']  = '<li class="current"><a href="#">';
        $pagination_config['cur_tag_close'] = '</a></li>';

        // Previous link
        $pagination_config['prev_tag_open']  = '<li class="arrow">';
        $pagination_config['prev_tag_close'] = '</li>';

        // Next link
        $pagination_config['next_tag_open']  = '<li class="arrow">';
        $pagination_config['nect_tag_close'] = '<li>';

        // First link
        $pagination_config['first_tag_open']  = '<li>';
        $pagination_config['first_tag_close'] = '</li>';

        // Last link
        $pagination_config['last_tag_open']  = '<li>';
        $pagination_config['last_tag_close'] = '</li>';

        // Full close
        $pagination_config['full_tag_close'] = '</ul>';

        self::$CI->pagination->initialize($pagination_config);
        $links = self::$CI->pagination->create_links();

        return $links;
    }

    /**
     * Generates the table for the paginated user admin page.
     *
     * @param array $output
     * @return array
     */
    public function admin_user_page_table_setup($output = NULL)
    {
        self::$CI->load->helper('date');

        // Table headings
        $add_link = base_url() . $this->user_admin_add_user_uri;
        $heading  = array(
            'ID', 'Username', 'Email', 'Role', 'Member since', 'Protected',
            '<a href="' . $add_link . '" class="right">Add user +</a>',
        );

        self::$CI->table->set_heading($heading);

        // Table template
        $template = array(
            'table_open'  => '<table width="100%">',
            'table_close' => '</table>',
        );

        self::$CI->table->set_template($template);

        foreach ($output as $key => $value)
        {   // add edit link.
            unset($output[$key]['password']);

            $output[$key]['edit'] =
                '<a href="' . base_url() . $this->user_admin_delete_user_uri
                . $output[$key]['id']
                . '" class="label alert round right" style="margin-left:10px;"'
                . 'onClick="return confirm(' . self::$CI->lang->line('confirm_admin_user_delete') . ')">Del</a>'
                . '<a href="' . base_url()
                . $this->user_admin_edit_user_uri . $output[$key]['id']
                . '" class="label secondary round right">Edit</a>';

            $output[$key]['created'] = unix_to_human($output[$key]['created']);

            if ($output[$key]['protected'])
            {
                $output[$key]['protected'] = 'Yes';
            }
            else
            {
                $output[$key]['protected'] = 'No';
            }
        }

        // Table render
        $table = self::$CI->table->generate($output);
        return $table;
    }

    /**
     * Method accepts the user object.
     *
     * @param object $user
     */
    public function admin_check_user_protected($user = NULL)
    {
        // Superuser can delete any account except User 1.
        if (($user->protected && self::$CI->session->userdata('role') != 'super_user') || $user->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_user_protected'));
            redirect(base_url() . $this->user_admin_users_uri);
        }
    }

    /**
     * Method accepts the role object.
     *
     * @param object $role
     */
    public function admin_check_role_protected($role = NULL)
    {
        // Superuser can delete any role except Role 1.
        if (($role->protected && self::$CI->session->userdata('role') != 'super_user') || $role->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', self::$CI->lang->line('error_admin_role_protected'));
            redirect(base_url() . $this->user_admin_roles_uri);
        }
    }

}
/* End of file core_user_library.php */
