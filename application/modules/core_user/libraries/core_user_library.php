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

    function __construct()
    {
        self::$CI = & get_instance();

        // Load the config and language files.
        self::$CI->config->load('core_user/core_user_config');
        self::$CI->lang->load('core_user/core_user');

        // Load the libraries.
        self::$CI->load->library('form_validation');
        self::$CI->load->library('table');
        self::$CI->load->library('pagination');

        // Load the helpers.
        self::$CI->load->helper('language');

        // Load the models.
        self::$CI->load->model('core_user/core_user_model');
        self::$CI->load->model('core_module/core_module_model');

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
        $this->user_admin_permissions_uri         = self::$CI->config->item('user_admin_permissions_uri');
        $this->user_admin_permission_add_uri      = self::$CI->config->item('user_admin_permission_add_uri');
        $this->user_admin_permission_edit_uri     = self::$CI->config->item('user_admin_permission_edit_uri');
        $this->user_admin_permission_delete_uri   = self::$CI->config->item('user_admin_permission_delete_uri');
        $this->user_admin_users_uri         = self::$CI->config->item('user_admin_users_uri');
        $this->user_admin_user_edit_uri     = self::$CI->config->item('user_admin_user_edit_uri');
        $this->user_admin_user_add_uri      = self::$CI->config->item('user_admin_user_add_uri');
        $this->user_admin_user_delete_uri   = self::$CI->config->item('user_admin_user_delete_uri');
    }

    /**
     * Options: user_login, user_insert, user_update, admin_permission_isert,
     * admin_permission_update, admin_user_insert, admin_user_update.
     *
     * @param string $rules
     */
    public function set_validation_rules($rules = '')
    {
        $settings = array(
            array(
                'field' => 'user_activation_expire_limit',
                'label' => 'User activation email expire limit',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'user_forgotten_password_code_expire_limit',
                'label' => 'Forgotten password code expire limit',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'user_persistent_cookie_name',
                'label' => 'Logged-in cookie name',
                'rules' => 'required|trim|alpha_dash|xss_clean',
            ),
            array(
                'field' => 'user_persistent_cookie_expire',
                'label' => 'Logged-in cookie expire time',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'user_login_attempts_max',
                'label' => 'Max login attempts',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'user_login_attempts_time',
                'label' => 'Max login attempts time span',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'user_login_attempts_lockout_time',
                'label' => 'User locked out time',
                'rules' => 'required|trim|integer|xss_clean',
            ),
        );
        $user_login = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim|alpha_dash|max_length[20]|xss_clean',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|valid_base64|max_length[20]|xss_clean',
            ),
        );

        $user_forgotten_password = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|valid_email|xss_clean'
            ),
        );

        $user_insert = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim|alpha_dash|max_length[20]|is_unique[core_users.username]|xss_clean',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|matches[passconf]|xss_clean',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|trim|valid_email|is_unique[core_users.email]|xss_clean'
            ),
        );

        $user_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer|xss_clean',
            ),
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|alpha_dash|max_length[20]|xss_clean',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|valid_base64|trim|max_length[12]|matches[passconf]|xss_clean',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|valid_email|xss_clean'
            ),
        );

        $admin_permission_insert = array(
            array(
                'field' => 'permission',
                'label' => 'permission',
                'rules' => 'required|is_unique[core_permissions.permission]|xss_clean',
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required|xss_clean',
            ),
            array(
                'field' => 'protected_value',
                'label' => 'Protected value',
                'rules' => 'trim|integer|max_length[1]|xss_clean',
            ),
        );

        $admin_permission_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer|xss_clean',
            ),
            array(
                'field' => 'permission',
                'label' => 'permission',
                'rules' => 'required|xss_clean',
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required|xss_clean',
            ),
            array(
                'field' => 'protected_value',
                'label' => 'Protected value',
                'rules' => 'trim|integer|max_length[1]|xss_clean',
            ),
        );

        $admin_user_insert = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim|alpha_dash|max_length[20]|is_unique[core_users.username]|xss_clean',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|matches[passconf]|xss_clean',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|xss_clean',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[core_users.email]|xss_clean'
            ),
            array(
                'field' => 'permissions',
                'label' => 'Permissions',
                'rules' => 'required|xss_clean',
            ),
            array(
                'field' => 'protected_value',
                'label' => 'Protected value',
                'rules' => 'trim|xss_clean',
            ),
        );

        // Set the validation rules for updating a user.
        $admin_user_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer|xss_clean',
            ),
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|alpha_dash|max_length[20]|xss_clean',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|xss_clean'
            ),
            array(
                'field' => 'permissions',
                'label' => 'Permissions',
                'rules' => 'required|xss_clean',
            ),
            array(
                'field' => 'protected_value',
                'label' => 'Protected value',
                'rules' => 'trim|xss_clean',
            ),
        );

        $rule_set = array();

        switch ($rules)
        {
            case 'settings':
                $rule_set = $settings;
                break;
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
            case 'admin_permission_insert':
                $rule_set = $admin_permission_insert;
                break;
            case 'admin_permission_update':
                $rule_set = $admin_permission_update;
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
        set_valid_base_64_error('Password');
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
        $validate_code = validate_alnum_64($forgotten_password_code);
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
        self::$CI->load->library('core_email/core_email_library');

        // Load the date helper and set the expire time for email.
        self::$CI->load->helper('date');
        $expire_time = variable_get('user_activation_expire_limit');

        // Find new user.
        $user = $this->user_find($activation_code_array['user_id']);

        // Message.
        self::$data['username']            = $user->username;
        self::$data['email']               = $user->email;
        self::$data['expire_time']         = timespan(0, $expire_time);
        self::$data['activation_url_text'] = base_url($this->user_activation_uri)
                                             . $user->id . '_' . $activation_code_array['activation_code'];
        self::$data['activation_url_html'] = anchor($this->user_activation_uri
                                             . $user->id . '_' . $activation_code_array['activation_code'], 'Activate');
        self::$data['login_url_text']      = base_url($this->user_login_uri);
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

        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', lang('success_user_account_created'));
            redirect(base_url($this->user_login_uri));
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
                exit();
                break;
            case 'activated':
                self::$CI->session->set_flashdata('message_success', lang('success_user_account_activation'));
                exit();
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_user_account_activation'));
                exit();
                break;
            case 'not_found':
                self::$CI->session->set_flashdata('message_error', lang('error_user_account_activation_not_found'));
                exit();
                break;
            case 'expired':
                self::$CI->session->set_flashdata('message_notice', lang('notice_user_account_activation_expired'));
                exit();
                break;
        }

        redirect(base_url($this->user_login_uri));
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
                redirect(base_url($this->user_index_uri));
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
                redirect(base_url($this->user_logout_uri));
                exit();
                break;
            case FALSE:
                self::$CI->session
                    ->set_flashdata('message_error', lang('error_user_account_delete_failed'));
                redirect(base_url($this->user_edit_uri));
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
            redirect(base_url($this->user_index_uri));
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
            $forgotten_password_expire_time = time() + variable_get('user_forgotten_password_code_expire_limit');
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
                $expire_time = variable_get('user_forgotten_password_code_expire_limit');

                // Message.
                self::$data['username']          = $user->username;
                self::$data['email']             = $user->email;
                self::$data['expire_time']       = timespan(0, $expire_time);
                self::$data['recovery_url_text'] = base_url($this->user_forgotten_password_login_uri)
                                                   . $user->id . '_' . $forgotten_password_code;
                self::$data['recovery_url_html'] = anchor($this->user_forgotten_password_login_uri
                                                   . $user->id . '_' . $forgotten_password_code, 'One time login');
                $message     = self::$CI->load->view('core_user/email_templates/forgotten_password', self::$data, TRUE);
                $message_alt = self::$CI->load->view('core_user/email_templates/forgotten_password_alt', self::$data, TRUE);

                // Set the message array.
                $email = array(
                    'to'          => $user->email,
                    'to_name'     => $user->username,
                    'subject'     => 'Lost password information from ' . self::$data['site_name'],
                    'message'     => $message,
                    'message_alt' => $message_alt,
                );

                // Send the email.
                self::$CI->load->library('core_email/core_email_library');
                $result = self::$CI->core_email_library->system_email_send($email);

                if ($result)
                {
                    self::$CI->session->set_flashdata('message_success', lang('success_user_forgotten_password_sent'));
                    redirect(base_url($this->user_login_uri));
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
                redirect(base_url($this->user_login_uri));
                exit();
                break;
            case 'expired':
                self::$CI->session->set_flashdata('message_error', self::$CI->lang
                ->line('error_user_forgotten_password_code_expired'));
                redirect(base_url($this->user_login_uri));
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
                    redirect(base_url($this->user_login_uri));
                    exit();
                    break;
                }
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', self::$CI->lang
                ->line('error_user_forgotten_password_email_not_valid'));
                redirect(base_url($this->user_login_uri));
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
        $time_to_unlock = $user->locked_out_time + variable_get('user_login_attempts_lockout_time');

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

        $userarray = array('user_id', 'username', 'password', 'email', 'permission',);
        self::$CI->session->unset_userdata($userarray);
        self::$CI->session->sess_destroy();
        self::$CI->session->sess_create();
        self::$CI->session->set_flashdata('message_notice', lang('notice_user_logout'));

        // Unset the logged in cookie if set.
        $this->user_unset_persistent_login();

        redirect(base_url($this->user_login_uri));
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
            'permissions'     => $this->admin_user_permissions_get($user->id),
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

        $cookie_name   = variable_get('user_persistent_cookie_name');
        $random_string = random_string('alnum', 64);
        $remember_code = self::$CI->encrypt->encode($random_string);
        $cookie_expire = variable_get('user_persistent_cookie_expire');
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

        $cookie_name = variable_get('user_persistent_cookie_name');

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

        $cookie_name = variable_get('user_persistent_cookie_name');
        $cookie = self::$CI->input->cookie($cookie_name);
        // Parse the cookie content.
        $cookie_array = explode('_', $cookie);

        // If user is already logged in skip the checking.
        if (!self::$CI->session->userdata('user_id'))
        {
            // Check the cookie against the database.
            if (count($cookie_array) == 2)
            {
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
     * @param array $permissions.
     */
    public function check_permissions($permissions = '')
    {
        // Check logged in.
        $this->user_check_logged_in();

        // Convert permissions string to array.
        $permissions = explode(',', $permissions);

        // Sets the $user_permissions variable.
        if (self::$CI->session->userdata('permissions'))
        {
            $user_permissions = explode(',', self::$CI->session->userdata('permissions'));
        }
        else
        {
            $user_permissions = array();
        }
        
        // Compare the permissions to the user perssion and return bool.
        return (count(array_intersect($user_permissions, $permissions)) > 0 ||
            in_array('super_user', $user_permissions) ||
            (count($permissions) == 1 && $permissions[0] == ''));

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
                $condition = ($user->protected && self::$CI->session->userdata('permission') != 'super_user');
                break;
            case 'delete':
                // User 1 cannot be deleted through this application.
                $condition = ($user->protected && self::$CI->session->userdata('permission') != 'super_user') || $user->id == 1;
                break;
        }

        if ($condition)
        {
            self::$CI->session->set_flashdata('message_error', lang('error_user_protected'));
            redirect(base_url($this->user_index_uri));
            exit();
        }
    }

    /**
     * Get a permission.
     *
     * @param integer $id
     * @return object
     */
    public function admin_permission_get($id = NULL)
    {
        $permission = self::$CI->core_user_model->admin_permission_get($id);
        return ($permission) ? $permission : FALSE ;
    }

    /**
     * Get a users permissions.
     *
     * @param integer $id
     * @return string
     */
    public function admin_user_permissions_get($id = NULL)
    {
        $permissions = self::$CI->core_user_model->admin_user_permissions_get($id);
        return ($permissions) ? $permissions : FALSE ;
    }

    /**
     * Get all the permissions.
     *
     * @param string $data_type
     * @return mixed
     */
    public function admin_permissions_get_all($data_type = 'object')
    {
        $result = self::$CI->core_user_model->admin_permissions_get_all($data_type);
        return ($result) ? $result : FALSE;
    }

    /**
     * Get paginated results for permission table.
     *
     * @param integer $per_page
     * @param integer $start
     * @param string $data_type
     * @return mixed
     */
    public function admin_permissions_get_limit_offset($per_page = NULL, $start = NULL, $data_type = 'object')
    {
        $permissions = self::$CI->core_user_model->admin_permissions_get_limit_offset($per_page, $start , $data_type );
        return ($permissions) ? $permissions : FALSE;
    }

    /**
     * Admin adds permission.
     *
     * @param array $post
     */
    public function admin_permission_add($post = array())
    {
        $result = self::$CI->core_user_model->admin_permission_save($post);

        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', lang('success_admin_add_permission'));
            redirect(base_url($this->user_admin_permission_edit_uri . $result));
            exit();
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_add_permission'));
            redirect(current_url());
            exit();
        }
    }

    /**
     * Admin edits permission.
     *
     * @param array $post
     */
    public function admin_permission_edit($post = array())
    {
        $result = self::$CI->core_user_model->admin_permission_save($post);

        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', lang('success_admin_edit_permission'));
            redirect(base_url($this->user_admin_permission_edit_uri . $post['id']));
            exit();
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_edit_permission'));
            redirect(base_url($this->user_admin_permission_edit_uri . $post['id']));
            exit();
        }
    }

    /**
     * Admin deletes a permission.
     *
     * @param integer $id
     */
    public function admin_permission_delete($id = NULL)
    {
        $result = self::$CI->core_user_model->admin_permission_delete($id);

        switch ($result)
        {
            case TRUE:
                self::$CI->session->set_flashdata('message_success', lang('success_admin_delete_permission'));
                redirect(base_url($this->user_admin_permissions_uri));
                exit();
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_admin_delete_permission'));
                redirect(base_url($this->user_admin_permissions_uri));
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
                redirect(base_url($this->user_admin_users_uri));
                exit();
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_admin_add_user'));
                redirect(base_url($this->user_admin_users_uri));
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
                redirect(base_url($this->user_admin_user_edit_uri . $post['id']));
                exit();
                break;
            case FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_admin_edit_user'));
                redirect(base_url($this->user_admin_user_edit_uri . $post['id']));
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
                redirect(base_url($this->user_admin_users_uri));
                exit();
                break;
            case $result == FALSE:
                self::$CI->session->set_flashdata('message_error', lang('error_admin_delete_user'));
                redirect(base_url($this->user_admin_users_uri . $user_page));
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
            redirect(base_url($this->user_admin_index_uri));
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
     * Method accepts the user object.
     *
     * @param object $user
     */
    public function admin_user_check_protected($user = NULL)
    {
        // Superuser can delete any account except User 1.
        if (($user->protected && self::$CI->session->userdata('permission') != 'super_user') || $user->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_user_protected'));
            redirect(base_url($this->user_admin_users_uri));
            exit();
        }
    }

    /**
     * Method accepts the permission object.
     *
     * @param object $permission
     */
    public function admin_permission_check_protected($permission = NULL)
    {
        // Superuser can delete any permission except permission 1.
        if (($permission->protected && self::$CI->session->userdata('permission') != 'super_user') || $permission->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', lang('error_admin_permission_protected'));
            redirect(base_url($this->user_admin_permissions_uri));
            exit();
        }
    }

}
/* End of file core_user_library.php */
