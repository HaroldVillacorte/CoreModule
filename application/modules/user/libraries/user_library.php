<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class User_library
{

    private static $CI;
    private static $data;

    function __construct()
    {
        self::$CI = & get_instance();
        self::$CI->load->library('session');
        self::$CI->load->library('form_validation');
        self::$CI->load->database();
        self::$CI->load->model('core_model/core_model');
        self::$data = self::$CI->core_model->site_info();
    }

    public function set_validation_rules($rules = '')
    {
        $login = array(
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

        $rule_set = '';

        switch ($rules)
        {
            case 'login':
                $rule_set = $login;
                break;
            case 'user_insert':
                $rule_set = $user_insert;
                break;
            case 'user_update':
                $rule_set = $user_update;
                break;
        }

        self::$CI->form_validation->set_rules($rule_set);
        $valid_base64_error = 'Password may only contain alpha-numeric characters, +\'s, and /\'s';
        self::$CI->form_validation->set_message('valid_base64', $valid_base64_error);
    }

    public function logout($redirect_uri = '')
    {
        $userarray = array('user_id', 'username', 'password', 'email', 'role',);
        self::$CI->session->unset_userdata($userarray);
        self::$CI->session->sess_destroy();
        self::$CI->session->sess_create();
        self::$CI->session->set_flashdata('message_notice', 'You are now logged out.');
        $this->unset_persistent_login();
        redirect(base_url() . $redirect_uri);
        return TRUE;
    }

    public function set_user_session_data($user = NULL)
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

    public function set_persistent_login()
    {
        self::$CI->load->helper('string');
        self::$CI->load->library('encrypt');
        self::$CI->load->helper('inflector');

        $name                  = underscore(self::$data['site_name']) . '_login';
        $remember_code         = random_string('alnum', 32);
        $remember_code_encoded = self::$CI->encrypt->encode($remember_code);

        $data = array(
            'name'   => $name,
            'value'  => $remember_code_encoded,
            'expire' => 1209600,
        );

        self::$CI->input->set_cookie($data);

        return $remember_code;
    }

    public function unset_persistent_login()
    {
        self::$CI->load->helper('inflector');

        $name = underscore(self::$data['site_name']) . '_login';

        self::$CI->load->helper('cookie');
        delete_cookie($name);
    }

    public function check_logged_in()
    {
        self::$CI->load->helper('string');
        self::$CI->load->library('encrypt');
        self::$CI->load->helper('inflector');

        $name = underscore(self::$data['site_name']) . '_login';

        if (!self::$CI->session->userdata('user_id'))
        {
            // It is neccessary to do a query here because I cannot load the model while
            // the model loads the library.
            if (self::$CI->input->cookie($name))
            {
                $remember_code_encoded = self::$CI->db->escape_str(self::$CI->input->cookie($name));
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

                if ($user)
                {
                    $this->set_user_session_data($user);
                }
            }
        }
    }

    /**
     * The permissions method.  If the role set in the session does not match the
     * role specified by the method that calls this method user will be
     * redirected.
     *
     * @param array $role Array of user roles.
     */
    public function permission($role = array())
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
            $message = 'You are not authorized to access that page.';
            self::$CI->session->set_flashdata('message_error', $message);
            redirect(base_url() . 'user/login/');
        }
    }

    public function check_user_protected($user = NULL, $action = NULL)
    {
        switch ($action)
        {
            case 'edit':
                $condition = ($user->protected && self::$CI->session->userdata('role') != 'super_user');
                break;
            case 'delete':
                $condition = ($user->protected && self::$CI->session->userdata('role') != 'super_user') || $user->id == 1;
                break;
        }

        if ($condition)
        {
            self::$CI->session->set_flashdata('message_error', 'Unable to process.  User account is protected.');
            redirect(base_url() . 'user/');
        }
    }

}
/* End of file user_library.php */
