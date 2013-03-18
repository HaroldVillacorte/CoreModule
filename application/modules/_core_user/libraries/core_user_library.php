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
     * User password recovery uri.
     *
     * @var string
     */
    public $user_forgotten_password_uri;

    /**
     * User password recovery login uri.
     *
     * @var string
     */
    public $user_forgotten_password_login_uri;

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
    public $user_admin_role_add_uri;

    /**
     * Admin edit user roles uri.
     *
     * @var string
     */
    public $user_admin_role_edit_uri;

    /**
     * Admin delete user roles uri.
     *
     * @var string
     */
    public $user_admin_role_delete_uri;

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
    public $user_admin_user_edit_uri;

    /**
     * Admin add user page uri.
     *
     * @var string
     */
    public $user_admin_user_add_uri;

    /**
     * Admin delete user uri.
     *
     * @var string
     */
    public $user_admin_user_delete_uri;

    function __construct()
    {
        self::$CI = & get_instance();

        // Load the config and language files.
        self::$CI->config->load('_core_user/core_user_config');
        self::$CI->lang->load('_core_user/core_user');

        // Load the libraries.
        self::$CI->load->library('form_validation');
        self::$CI->load->library('table');
        self::$CI->load->library('pagination');

        // Load the helpers.
        self::$CI->load->helper('language');

        // Load the models.
        self::$CI->load->model('_core_user/core_user_model');
        self::$CI->load->model('_core_module/core_module_model');

        // Intialize the data array.
        self::$data = self::$CI->core_module_model->site_info();

        // Set the user uri's.
        $this->user_index_uri                    = self::$CI->config->item('user_index_uri');
        $this->user_login_uri                    = self::$CI->config->item('user_login_uri');
        $this->user_forgotten_password_uri       = self::$CI->config->item('user_forgotten_password_uri');
        $this->user_forgotten_password_login_uri = self::$CI->config->item('user_forgotten_password_login_uri');
        $this->user_logout_uri                   = self::$CI->config->item('user_logout_uri');
        $this->user_add_uri                      = self::$CI->config->item('user_add_uri');
        $this->user_activation_uri               = self::$CI->config->item('user_activation_uri');
        $this->user_edit_uri                     = self::$CI->config->item('user_edit_uri');
        $this->user_delete_uri                   = self::$CI->config->item('user_delete_uri');

        // Set the admin uri's.
        $this->user_admin_roles_uri         = self::$CI->config->item('user_admin_roles_uri');
        $this->user_admin_role_add_uri      = self::$CI->config->item('user_admin_role_add_uri');
        $this->user_admin_role_edit_uri     = self::$CI->config->item('user_admin_role_edit_uri');
        $this->user_admin_role_delete_uri   = self::$CI->config->item('user_admin_role_delete_uri');
        $this->user_admin_users_uri         = self::$CI->config->item('user_admin_users_uri');
        $this->user_admin_user_edit_uri     = self::$CI->config->item('user_admin_user_edit_uri');
        $this->user_admin_user_add_uri      = self::$CI->config->item('user_admin_user_add_uri');
        $this->user_admin_user_delete_uri   = self::$CI->config->item('user_admin_user_delete_uri');
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
                'rules' => 'required|trim|alpha_dash|max_length[20]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|valid_base64|max_length[20]',
            ),
        );

        $user_forgotten_password = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|valid_email'
            ),
        );

        $user_insert = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim|alpha_dash|max_length[20]|is_unique[core_users.username]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|matches[passconf]',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'required|trim',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|valid_email|is_unique[core_users.email]'
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
                'rules' => 'trim|alpha_dash|max_length[20]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|valid_base64|trim|max_length[12]|matches[passconf]',
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
                'rules' => 'required|is_unique[core_roles.role]',
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
                'rules' => 'required|trim|alpha_dash|max_length[20]|is_unique[core_users.username]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|matches[passconf]',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[core_users.email]'
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
                'rules' => 'trim|alpha_dash|max_length[20]',
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
            case 'user_forgotten_password':
                $rule_set = $user_forgotten_password;
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
        self::$CI->core_module_library->set_valid_base_64_error('Password');
    }

    /**
     * Extract Id and code from meial codes.
     *
     * @param string $code
     * @return array
     */
    public function user_email_code_parse($code = NULL)
    {
        // Seperate the user id and the code.
        $array = explode('_', $code);

        // Validate the url parameter.
        $forgotten_password_code = $array[1];
        $validate_code = self::$CI->core_module_library->validate_alum_64($forgotten_password_code);
        if (!$validate_code)
        {
            // Redirect if false.
            self::$CI->session->set_flashdata('message_error', lang('error_user_email_code_invalid'));
            redirect(base_url());
            exit();
        }
        else
        {
            // If the input validates set the array key.
            $code_array = array();
            $code_array['id'] = (int) $array[0];
            $code_array['code'] = $forgotten_password_code;
        }

        return $code_array;
    }

    /**
     * Finds a user by primary key $id.
     *
     * @param integer $id
     * @return object
     */
    public function user_find($id = NULL)
    {
        $user = self::$CI->core_user_model->user_find($id);
        return ($user) ? $user : FALSE;
    }

    /**
     * Finds a user by identity.
     *
     * @param string $id
     * @return object
     */
    public function user_find_by_identity($column = NULL, $identity = NULL)
    {
        $user = self::$CI->core_user_model->user_find_by_identity($column, $identity);
        return ($user) ? $user : FALSE;
    }

    /**
     * User adds own account.
     *
     * @param array $post
     */
    public function user_add($post = array())
    {
        $activation_code_array = self::$CI->core_user_model->user_add($post);

        switch ($activation_code_array)
        {
            case TRUE:
                $this->user_send_welcome_email($activation_code_array);
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_user_account_failed'));
                redirect(current_url());
                exit();
                break;
        }
    }

    /**
     * Send activation email after user generates new account.
     *
     * @param integer $result_id
     */
    public function user_send_welcome_email($activation_code_array = array())
    {
        // Load the email module.
        self::$CI->load->library('_core_email/core_email_library');

        // Load the date helper and set the expire time for email.
        self::$CI->load->helper('date');
        $expire_time = self::$CI->config->item('user_activation_expire_limit');

        // Find new user.
        $user = $this->user_find($activation_code_array['user_id']);

        // Message.
        self::$data['username']            = $user->username;
        self::$data['email']               = $user->email;
        self::$data['expire_time']         = timespan(0, $expire_time);
        self::$data['activation_url_text'] = base_url() . $this->user_activation_uri
                                             . $user->id . '_' . $activation_code_array['activation_code'];
        self::$data['activation_url_html'] = anchor($this->user_activation_uri
                                             . $user->id . '_' . $activation_code_array['activation_code'], 'Activate');
        self::$data['login_url_text']      = base_url() . $this->user_login_uri;
        self::$data['login_url_html']      = anchor($this->user_login_uri, 'Login');
        $message     = self::$CI->load->view('_core_user/email_templates/welcome', self::$data, TRUE);
        $message_alt = self::$CI->load->view('_core_user/email_templates/welcome_alt', self::$data, TRUE);

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

        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', lang('success_user_account_created'));
            redirect(base_url() . $this->user_login_uri);
            exit();
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', lang('error_user_account_failed'));
            self::$CI->core_user_model->admin_user_delete($user->id);
            redirect(current_url());
            exit();
        }
    }

    /**
     * User activation.
     *
     * @param string $activation_code
     */
    public function user_activate($code = NULL)
    {
        // // Extract array from code.
        $code_array = $this->user_email_code_parse($code);

        // Submit to the database.
        $result = self::$CI->core_user_model->user_activate($code_array);
        switch ($result)
        {
            case 'invalid':
                self::$CI->session->set_flashdata('message_error', lang('error_user_account_activation_invalid'));
                redirect(base_url() . $this->user_login_uri);
                exit();
                break;
            case 'activated':
                self::$CI->session->set_flashdata('message_success', lang('success_user_account_activation'));
                redirect(base_url() . $this->user_login_uri);
                exit();
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_user_account_activation'));
                redirect(base_url() . $this->user_login_uri);
                exit();
                break;
            case 'not_found':
                self::$CI->session->set_flashdata('message_error', lang('error_user_account_activation_not_found'));
                redirect(base_url() . $this->user_login_uri);
                exit();
                break;
            case 'expired':
                self::$CI->session->set_flashdata('message_notice', lang('notice_user_account_activation_expired'));
                redirect(base_url() . $this->user_login_uri);
                exit();
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
                self::$CI->session->set_flashdata('message_success', lang('success_user_account_edited'));
                redirect(base_url() . $this->user_index_uri);
                exit();

                // Reset the user session data ater update.
                $updated_user = $this->user_find((int) $post['id']);
                $this->user_set_session_data($updated_user);

                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_user_account_edit_failed'));
                redirect(current_url());
                exit();
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
                exit();
                break;
            case FALSE:
                self::$CI->session
                    ->set_flashdata('message_error', lang('error_user_account_delete_failed'));
                redirect(base_url() . $this->user_edit_uri);
                exit();
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
        // Check if user is locked out.
        $this->user_locked_out_check($username);

        $user = self::$CI->core_user_model->user_login($username, $password);
        if ($user)
        {
            // Check active.
            if (!$user->active)
            {
                self::$CI->session->set_flashdata('message_error', lang('error_user_login_inactive'));
                redirect(current_url());
                exit();
            }
            else
            {
                // Login success.
                self::$CI->session->set_flashdata('message_success', lang('success_user_login') . $username . '.');
                $this->user_set_session_data($user);
            }

            // Set the login cookie if checked.
            if ($set_persistent_login)
            {
                $remember_code_array = $this->user_set_persistent_login($user->id);
                $store_remember_code = self::$CI->core_user_model->user_remember_code_store($remember_code_array);

                if (!$store_remember_code)
                {
                    self::$CI->session->set_flashdata('message_notice', lang('notice_user_persistent_fail'));
                    $this->user_unset_persistent_login();
                }
            }
            redirect(base_url() . $this->user_index_uri);
            exit();
        }

        // Code to run if username and password combination is not found in the
        // database.
        else
        {
            // Unsuccessful login.
            self::$CI->session->set_flashdata('message_error', lang('error_user_login_failed'));
            $this->user_login_log_failed_attempt($username);
            redirect(current_url());
            exit();
        }
    }

    /**
     * User submits email address to reset own password.
     *
     * @param string $email
     */
    public function user_forgotten_password($email = NULL)
    {
        // Find the user with email.
        $user = self::$CI->core_user_model->user_find_by_identity('email', $email);
        if (!$user)
        {
            // User email not found.
            self::$CI->session->set_flashdata('message_error', lang('error_user_forgotten_password_email_not_found'));
            redirect(current_url());
            exit();
        }
        // User found generate code and time send recovery email.
        else
        {
            // Set the recovery code and time.
            self::$CI->load->helper('string');
            $forgotten_password_code        = random_string('alnum', 64);
            $forgotten_password_expire_time = time() + self::$CI->config->item('user_forgotten_password_code_expire_limit');
            $forgotten_password_data        = array(
                                                'user_id'                        => $user->id,
                                                'forgotten_password_code'        => $forgotten_password_code,
                                                'forgotten_password_expire_time' => $forgotten_password_expire_time,
                                              );

            // Send the forgotten password data to the database.
            $result = self::$CI->core_user_model
                ->user_forgotten_password_code_add($forgotten_password_data);

            if (!$result)
            {
                // Failed to insert password recovery data into the database.
                self::$CI->session->set_flashdata('message_error', lang('error_user_forgotten_password_failed'));
                redirect(current_url());
                exit();
            }
            else
            {
                // Set the expire time for email.
                self::$CI->load->helper('date');
                $expire_time = self::$CI->config->item('user_forgotten_password_code_expire_limit');

                // Message.
                self::$data['username']          = $user->username;
                self::$data['email']             = $user->email;
                self::$data['expire_time']       = timespan(0, $expire_time);
                self::$data['recovery_url_text'] = base_url() . $this->user_forgotten_password_login_uri
                                                   . $user->id . '_' . $forgotten_password_code;
                self::$data['recovery_url_html'] = anchor($this->user_forgotten_password_login_uri
                                                   . $user->id . '_' . $forgotten_password_code, 'One time login');
                $message     = self::$CI->load->view('_core_user/email_templates/forgotten_password', self::$data, TRUE);
                $message_alt = self::$CI->load->view('_core_user/email_templates/forgotten_password_alt', self::$data, TRUE);

                // Set the message array.
                $email = array(
                    'to'          => $user->email,
                    'to_name'     => $user->username,
                    'subject'     => 'Lost password information from ' . self::$data['site_name'],
                    'message'     => $message,
                    'message_alt' => $message_alt,
                );

                // Send the email.
                self::$CI->load->library('_core_email/core_email_library');
                $result = self::$CI->core_email_library->system_email_send($email);

                if ($result)
                {
                    self::$CI->session->set_flashdata('message_success', lang('success_user_forgotten_password_sent'));
                    redirect(base_url() . $this->user_login_uri);
                    exit();
                }
                else
                {
                    self::$CI->session->set_flashdata('message_error', lang('error_user_forgotten_password_failed'));
                    self::$CI->core_user_model->user_forgotten_password_code_delete($user->id);
                    redirect(current_url());
                    exit();
                }
            }
        }
    }

    /**
     * User visits page from email link.
     *
     * @param array $code
     */
    public function user_forgotten_password_login($code = NULL)
    {
        // Extract array from code.
        $code_array = $this->user_email_code_parse($code);

        // Send the array to the database.
        $user = self::$CI->core_user_model->user_forgotten_password_login($code_array);
        switch ($user)
        {
            case 'not_found':
                self::$CI->session->set_flashdata('message_error', self::$CI->lang
                ->line('error_user_forgotten_password_code_not_found'));
                redirect(base_url() . $this->user_login_uri);
                exit();
                break;
            case 'expired':
                self::$CI->session->set_flashdata('message_error', self::$CI->lang
                ->line('error_user_forgotten_password_code_expired'));
                redirect(base_url() . $this->user_login_uri);
                exit();
                break;
            case 'authenticated':
                $user = $this->user_find($code_array['id']);
                if ($user)
                {
                    $this->user_set_session_data($user);
                    self::$CI->session->set_flashdata('message_success', self::$CI->lang
                    ->line('success_user_forgotten_password_login'));
                    // Delete the code.
                    self::$CI->core_user_model->user_forgotten_password_code_delete($user->id);
                    redirect(base_url() . $this->user_login_uri);
                    exit();
                    break;
                }
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang
                ->line('error_user_forgotten_password_email_not_valid'));
                redirect(base_url() . $this->user_login_uri);
                exit();
                break;
        }
    }

    /**
     * Store failed login attempts and check number of attempts.
     *
     * @param string $login
     */
    public function user_login_log_failed_attempt($login = '')
    {
        $ip_address = self::$CI->session->userdata('ip_address');
        $time = time();

        $data = array(
            'ip_address' => $ip_address,
            'login'      => $login,
            'time'       => $time,
        );

        // Store the failed attempt and get total attempts information.
        $locked_out_username = self::$CI->core_user_model->user_login_log_failed_attempt($data);

        // If user has exceeded max login attempts.
        if ($locked_out_username)
        {
            // This function checks lockout and locks them out.
            $this->user_locked_out_check($locked_out_username);
        }
    }

    /**
     * Check if user is locked out.
     *
     * @param string $locked_out_username
     */
    public function user_locked_out_check($locked_out_username = NULL)
    {
        self::$CI->load->helper('date');

        // Instantiate the user.
        $user = $this->user_find_by_identity('username', $locked_out_username);

        $time = time();

        // The time when a user can try to login again.
        $time_to_unlock = $user->locked_out_time + self::$CI->config->item('user_login_attempts_lockout_time');

        // If user has a lockout time.
        if ($user->locked_out_time)
        {
            // User is still locked out.
            if ($time < $time_to_unlock)
            {
                // Set the flashdata message.
                self::$CI->session->set_flashdata('message_error', lang('error_user_login_locked_out_1')
                . ucfirst($user->username) . lang('error_user_login_locked_out_2') . timespan($time, $time_to_unlock));
                // Redirect the user.
                redirect(current_url());
                exit();
            }
            // Lockout time is expired
            else
            {
                // Delete the lockout time.
                self::$CI->core_user_model->user_login_unlock($user);
            }
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

        //  Delete persistent login cookie.
        $result   = self::$CI->core_user_model->user_remember_code_delete($id);

        if (!$result)
        {
            log_message('error', $username . lang('error_user_delete_remember_failed'));
        }

        $userarray = array('user_id', 'username', 'password', 'email', 'role',);
        self::$CI->session->unset_userdata($userarray);
        self::$CI->session->sess_destroy();
        self::$CI->session->sess_create();
        self::$CI->session->set_flashdata('message_notice', lang('notice_user_logout'));

        // Unset the logged in cookie if set.
        $this->user_unset_persistent_login();

        redirect(base_url() . $this->user_login_uri);
        exit();
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
     * @param integer $user_id
     * @return array
     */
    public function user_set_persistent_login($user_id = NULL)
    {
        self::$CI->load->helper('string');
        self::$CI->load->library('encrypt');

        $cookie_name   = self::$CI->config->item('user_persistent_cookie_name');
        $random_string = random_string('alnum', 64);
        $remember_code = self::$CI->encrypt->encode($random_string);
        $cookie_expire = self::$CI->config->item('user_persistent_cookie_expire');
        $user_id_encoded = self::$CI->encrypt->encode($user_id);

        $cookie_data = array(
            'name'   => $cookie_name,
            'value'  => $user_id_encoded . '_' . $remember_code,
            'expire' => $cookie_expire,
        );

        self::$CI->input->set_cookie($cookie_data);

        return array(
            'user_id'       => (int) $user_id,
            'remember_code' => $random_string,
            'expire_time'   => $cookie_expire + time(),
        );
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
        $cookie = self::$CI->input->cookie($cookie_name);

        // If user is already logged in skip the checking.
        if (!self::$CI->session->userdata('user_id'))
        {
            // Check the cookie against the database.
            if ($cookie)
            {
                // Parse the cookie content.
                $cookie_array                         = explode('_', $cookie);
                // Set the array to submit to model.
                $remember_code_array                  = array();
                $remember_code_array['user_id']       = self::$CI->encrypt->decode($cookie_array[0]);
                $remember_code_array['remember_code'] = self::$CI->encrypt->decode($cookie_array[1]);
                $remember_code_array['ip_address']    = self::$CI->session->userdata('ip_address');
                $remember_code_array['user_agent']    = self::$CI->session->userdata('user_agent');

                $user = self::$CI->core_user_model->user_check_logged_in($remember_code_array);

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
        // Check logged in.
        $this->user_check_logged_in();

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
            self::$CI->session->set_flashdata('message_error', lang('error_user_permission'));
            redirect(base_url() . $this->user_login_uri);
            exit();
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
            self::$CI->session->set_flashdata('message_error', lang('error_user_protected'));
            redirect(base_url() . $this->user_index_uri);
            exit();
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
        $output = $this->admin_role_get_all('array');

        // Table headings
        $add_link = base_url() . $this->user_admin_role_add_uri;

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
                '<a href="' . base_url() . $this->user_admin_role_delete_uri
                . $output[$key]['id']
                . '" class="label alert round right" style="margin-left:10px;"'
                . 'onClick="return confirm(' . lang('confirm_admin_role_delete') . ')">Del</a>'
                . '<a href="' . base_url()
                . $this->user_admin_role_edit_uri . $output[$key]['id']
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
        return ($role) ? $role : FALSE ;
    }

    public function admin_role_get_all($data_type = 'object')
    {
        $result = self::$CI->core_user_model->admin_role_get_all($data_type);
        return ($result) ? $result : FALSE;
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
            self::$CI->session->set_flashdata('message_success', lang('success_admin_add_role'));
            redirect(base_url() . $this->user_admin_role_edit_uri . $result);
            exit();
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_add_role'));
            redirect(current_url());
            exit();
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
            self::$CI->session->set_flashdata('message_success', lang('success_admin_edit_role'));
            redirect(base_url() . $this->user_admin_role_edit_uri . $post['id']);
            exit();
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_edit_role'));
            redirect(base_url() . $this->user_admin_role_edit_uri . $post['id']);
            exit();
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
                self::$CI->session->set_flashdata('message_success', lang('success_admin_delete_role'));
                redirect(base_url() . $this->user_admin_roles_uri);
                exit();
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_admin_delete_role'));
                redirect(base_url() . $this->user_admin_roles_uri);
                exit();
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
                        self::$CI->session->set_flashdata('message_success', lang('success_admin_add_user'));
                        redirect(base_url() . $this->user_admin_users_uri);
                        exit();
                        break;
                    case FALSE:
                        self::$CI->session->set_flashdata('message_error', lang('error_admin_add_user'));
                        redirect(base_url() . $this->user_admin_users_uri);
                        exit();
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
                self::$CI->session->set_flashdata('message_success', lang('success_admin_edit_user'));
                redirect(base_url() . $this->user_admin_user_edit_uri . $post['id']);
                exit();
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_admin_edit_user'));
                redirect(base_url() . $this->user_admin_user_edit_uri . $post['id']);
                exit();
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
                self::$CI->session->set_flashdata('message_success', lang('success_admin_delete_user'));
                redirect(base_url() . $this->user_admin_users_uri);
                exit();
                break;
            case $result == FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_admin_delete_user'));
                redirect(base_url() . $this->user_admin_users_uri . $user_page);
                exit();
                break;
        }
    }

    /**
     * Admin gets all users.
     *
     * @return integer
     */
    public function admin_user_get_count()
    {
        $result = self::$CI->core_user_model->admin_user_get_all();

        if ($result)
        {
            return count($result);
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_get_user_count'));
            redirect(base_url() . $this->user_admin_index_uri);
            exit();
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

        // Set time date format.
        $date_format = self::$CI->config->item('core_user_date_format');

        // Table headings
        $add_link = base_url() . $this->user_admin_user_add_uri;
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
                '<a href="' . base_url() . $this->user_admin_user_delete_uri
                . $output[$key]['id']
                . '" class="label alert round right" style="margin-left:10px;"'
                . 'onClick="return confirm(' . lang('confirm_admin_user_delete') . ')">Del</a>'
                . '<a href="' . base_url()
                . $this->user_admin_user_edit_uri . $output[$key]['id']
                . '" class="label secondary round right">Edit</a>';

            $output[$key]['created'] = standard_date($date_format, $output[$key]['created']);

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
    public function admin_user_check_protected($user = NULL)
    {
        // Superuser can delete any account except User 1.
        if (($user->protected && self::$CI->session->userdata('role') != 'super_user') || $user->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_user_protected'));
            redirect(base_url() . $this->user_admin_users_uri);
            exit();
        }
    }

    /**
     * Method accepts the role object.
     *
     * @param object $role
     */
    public function admin_role_check_protected($role = NULL)
    {
        // Superuser can delete any role except Role 1.
        if (($role->protected && self::$CI->session->userdata('role') != 'super_user') || $role->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_role_protected'));
            redirect(base_url() . $this->user_admin_roles_uri);
            exit();
        }
    }

}
/* End of file core_user_library.php */
