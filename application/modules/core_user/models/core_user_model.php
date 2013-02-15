<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_user_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

        // Load the config file.
        $this->config->load('core_user/core_user_config');

        // Load the database class.
        $this->load->database();
    }

    /**
     * Sanitize the input post array and unset null values.
     *
     * @param array $post_array
     * @return array
     */
    public function prep_post($post_array = array())
    {
        foreach ($post_array as $key => $value)
        {
            if ((!isset($post_array[$key]) || empty($value) || $value == '' || $value == NULL) && $value !== 0)
            {
                unset($post_array[$key]);
            }

            if (is_string($value))
            {
                $this->db->escape_str($value);
            }
        }

        return $post_array;
    }

    /**
     * Find user and join roles.
     *
     * @param integer $id
     * @return object
     */
    public function user_find($id = NULL)
    {
        $user = $this->db
            ->select('core_users.id, core_users.protected, username, email,activation_code,
                      created, role_id, role, locked_out_time')
            ->join('core_join_users_roles', 'core_join_users_roles.user_id = core_users.id')
            ->join('core_roles', 'core_roles.id = core_join_users_roles.role_id')
            ->get_where('core_users', array('core_users.id' => (int) $id));

        return ($user) ? $user->row() : FALSE;
    }

    /**
     * Find user by identity and join roles.
     *
     * @param string $column
     * @param string $identity
     * @return object
     */
    public function user_find_by_identity($column = NULL, $identity = NULL)
    {
        $column   = $this->db->escape_str($column);
        $identity = $this->db->escape_str($identity);

        $user = $this->db
            ->select('core_users.id, core_users.protected, username, email, activation_code,
                      created, role_id, role, locked_out_time')
            ->join('core_join_users_roles', 'core_join_users_roles.user_id = core_users.id')
            ->join('core_roles', 'core_roles.id = core_join_users_roles.role_id')
            ->get_where('core_users', array('core_users.' . $column => $identity));

        return ($user) ? $user->row() : FALSE;
    }

    /**
     * Currently not in use.  Will get all roles of user.
     *
     * @return array $roles
     */
    public function user_get_roles()
    {
        if ($this->session->userdata('user_id'))
        {
            $id       = $this->session->userdata('user_id');
            $role_ids = $this->db
                ->get_where('core_join_users_roles', array('user_id' => (int) $id))
                ->result_array();
            $roles = array();

            foreach ($role_ids as $role_id)
            {
                $found_role = $this->db
                    ->select('role')
                    ->get_where('core_roles', array('id' => (int) $role_id));
                $roles[] = $found_role;
            }

            return $roles;
        }
    }

    /**
     * Matches username and password in the database and returns the user object.
     *
     * @param string $username
     * @param string $password
     * @return object $user
     */
    public function user_login($username, $password)
    {
        // Sanitize username.
        $sanitized_username = $this->db->escape_str($username);
        // Sanitize and encrypt password.
        $sanitized_password = $this->db->escape_str($password);
        // Run the query.
        $result = $this->db
            ->select('core_users.id, username, email, created, role, active')
            ->join('core_join_users_roles', 'core_join_users_roles.user_id = core_users.id')
            ->join('core_roles', 'core_roles.id = core_join_users_roles.role_id')
            ->get_where('core_users', array(
            'username' => $sanitized_username,
            'password' => $sanitized_password,), 1);

        return ($result->num_rows() == 1) ? $result->row() : FALSE;
    }

    /**
     * Store failed login attempts and check number of attempts.
     *
     * @param array $post
     * @return string
     */
    public function user_login_log_failed_attempt($post = array())
    {
        $post = $this->prep_post($post);
        $expire_time = time() - $this->config->item('user_login_attempts_time');

        // Delete expired attempts.
        $this->db->where('time <', $expire_time)->delete('core_user_login_attempts');

        // Get all unexpired attempts.
        $all_login_attempts = $this->db
                                   ->select('id')
                                   ->where(array(
                                        'login' => $post['login'],
                                        'ip_address' => $post['ip_address'],
                                   ))
                                   ->get('core_user_login_attempts');

        // Count number of attempts.
        if ($all_login_attempts->num_rows() < $this->config->item('user_login_attempts_max'))
        {
            // Insert if less than the max.
            $this->db->set($post)->insert('core_user_login_attempts');

            return FALSE;
        }
        else
        {
            // Delete all atempts.
            $this->db
                ->where(array(
                    'login' => $post['login'],
                    'ip_address' => $post['ip_address'],
                ))
                ->delete('core_user_login_attempts');

            // Insert locked out time.
            $time = time();
            $this->db->where('username', $post['login'])
                ->update('core_users', array('locked_out_time' => $time));

            return $post['login'];
        }
    }

    /**
     * Delete locked out time from user.
     *
     * @param object $user
     */
    public function user_login_unlock($user)
    {
        $this->db
            ->where('id', $user->id)
            ->update('core_users', array('locked_out_time' => NULL));
    }

    /**
     * Check if a user has an unexpired logged in cookie.
     *
     * @param string $remember_code
     * @param string $ip_address
     * @param string $user_agent
     * @return object
     */
    public function user_check_logged_in($remember_code = NULL, $ip_address = NULL, $user_agent = NULL)
    {
        // Sanitize.
        $remember_code_clean = $this->db->escape_str($remember_code);
        $ip_address_clean    = $this->db->escape_str($ip_address);
        $user_agent_clean    = $this->db->escape_str($user_agent);

        // Run the query.
        $result = $this->db
                    ->select('core_users.id, core_users.protected, username, email, created, role')
                    ->join('core_join_users_roles', 'core_join_users_roles.user_id = core_users.id')
                    ->join('core_roles', 'core_roles.id = core_join_users_roles.role_id')
                    ->get_where('core_users', array(
                        'core_users.remember_code' => $remember_code_clean,
                        'ip_address'          => $ip_address_clean,
                        'user_agent'          => $user_agent_clean,
                        ));

        return ($result->num_rows() > 0) ? $result->row() : FALSE;
    }

    /**
     * Stores the persistent login cookie code, the users ip, and the user's user
     * agent in the database.
     *
     * @param string $remember_code
     * @param integer $id
     * @return boolean
     */
    public function user_remember_code_store($remember_code = NULL, $id = NULL)
    {
        $ip_address = $this->session->userdata('ip_address');
        $user_agent = $this->session->userdata('user_agent');

        $this->db
            ->set('remember_code', $remember_code)
            ->set('ip_address', $ip_address)
            ->set('user_agent', $user_agent)
            ->where('id', (int) $id)
            ->update('core_users');
        $num_rows = $this->db->affected_rows();

        return ($num_rows > 0) ? TRUE : FALSE;
    }

    /**
     * Deletes the persistent login cookie code, the core_users ip, and the user's user
     * agent from the database.
     *
     * @param integer $id
     * @return boolean
     */
    public function user_remember_code_delete($id = NULL)
    {
        $this->db
            ->set('remember_code', '')
            ->set('ip_address', '')
            ->set('user_agent', '')
            ->where('id', (int) $id)
            ->update('core_users');
        $num_rows = $this->db->affected_rows();

        return ($num_rows > 0) ? TRUE : FALSE;
    }

    /**
     * Returns the id of the added user or false.
     *
     * @param array $post
     * @return mixed
     */
    public function user_add($post = NULL)
    {
        $post = $this->prep_post($post);

        // Generate unique activation code from email.
        $post['activation_code'] = sha1($post['email']);

        $post['created'] = time();
        unset($post['passconf']);
        unset($post['add']);

        $result  = $this->db->insert('core_users', $post);
        $id      = $this->db->insert_id();

        if ($result)
        {
            $result2 = $this->db
            ->insert('core_join_users_roles', array(
                'user_id' => (int) $id,
                'role_id' => 3,
            ));
        }

        return ($result2) ? $id : FALSE;
    }

    /**
     * User activates own account through email link.
     *
     * @param string $activation_code
     * @return string
     */
    public function user_activate($activation_code = NULL)
    {
        $code = $this->db->escape_str($activation_code);

        // Find inactive user with activaton code.
        $result = $this->db
            ->get_where('core_users', array(
                'activation_code' => $code,
                'active'          => 0,
                ), 1);
        if ($result->num_rows() > 0)
        {
            $user = $result->row();
            $time = time();
            // Activate user if time now is less than 24 hours.
            if (($time - $user->created) <= $this->config->item('user_activation_expire_limit'))
            {
                $data = array(
                    'activation_code' => NULL,
                    'active'          => 1,
                );
                $this->db->where('id', $user->id)->update('core_users', $data);
                // Return 'updated if query success.'
                return ($this->db->affected_rows() > 0) ? 'activated' : FALSE;
            }
            else
            {
                // Or delete expired account and return 'expired'.
                $this->admin_user_delete($user->id);
                return 'expired';
            }
        }
        else
        {
            // If activation code/inactive not found in the database.
            return 'not_found';
        }
    }

    /**
     * User edits own account and the session userdata is reset with the new values.
     *
     * @param array $post
     * @return boolean
     */
    public function user_edit($post)
    {
        $post = $this->prep_post($post);

        // Make sure this is the logged in user.
        $user_id = $this->session->userdata('user_id');

        if ($post['id'] !== $user_id)
        {
            $this->session->set_flashdata('message_error', $this->lang->line('error_user_account_edit_unauthorized'));
            redirect(base_url() . 'user/');
        }

        unset($post['passconf']);
        unset($post['save']);

        $result = $this->db
            ->where('id', (int) $user_id)
            ->limit(1)
            ->update('core_users', $post);

        return ($result) ? TRUE : FALSE;
    }

    /**
     * User deletes own account.
     *
     * @return boolean
     */
    public function user_delete()
    {
        // Get the user id from the session.
        $id = $this->session->userdata('user_id');

        // Delete the user.
        $result = $this->db->delete('core_users', array('id' => (int) $id), 1);

        if ($result)
        {
            // Delete the user role from the join table.
            $result2 = $this->db
                ->delete('core_join_users_roles', array('user_id' => (int) $id), 1);
        }

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Admin adds or edits a user.
     *
     * @param array $post
     * @return boolean
     */
    public function admin_user_save($post)
    {
        $post = $this->prep_post($post);

        // There is a name conflict in the core input class that prohibits using
        // 'protected' as a field name.  So the name is changed here.
        if (isset($post['protected_value']))
        {
            $post['protected'] = (int) $post['protected_value'];
            unset($post['protected_value']);
        }

        $role = (int) $post['role'];
        unset($post['role']);

        unset($post['passconf']);
        unset($post['save']);

        switch ($post)
        {
            // Add a user.
            case !isset($post['id']):
                $post['created'] = time();
                $result          = $this->db->insert('core_users', $post);
                $id              = $this->db->insert_id();

                if ($id)
                {
                    // Set the users role.
                    $result2 = $this->db->insert(
                        'core_join_users_roles', array(
                        'user_id' => $id,
                        'role_id' => $role,
                        ));
                }
                break;

            // Edit a user.
            case isset($post['id']):
                $result = $this->db
                    ->where('id', $post['id'])
                    ->limit(1)
                    ->update('core_users', $post);

                if ($result)
                {
                    // Edit the user's role.
                    $result2 = $this->db
                        ->where('user_id', $post['id'])
                        ->limit(1)
                        ->update('core_join_users_roles', array('role_id' => $role));
                }
                break;
        }

        return ($result2) ? TRUE : FALSE;
    }

    /**
     * Admin deletes a user.
     *
     * @param integer $id
     * @return mixed
     */
    public function admin_user_delete($id = NULL)
    {
        // Delte the user.
        $result = $this->db->delete('core_users', array('id' => (int) $id), 1);

        if ($result)
        {
            // Delete the user role.
            $this->db->delete('core_join_users_roles', array('user_id' => (int) $id), 1);

            return ($this->db->affected_rows() > 1) ? TRUE : FALSE;
        }
    }

    /**
     * Get the role of a user.
     *
     * @param integer $id
     * @return mixed
     */
    public function admin_role_get($id = NULL)
    {
        $query = $this->db->where('id', (int) $id)->get('core_roles');
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Admin gets all roles.  Choose whether to return an 'array' or 'object'.
     *
     * @param string $data_type
     * @return mixed
     */
    public function admin_role_get_all($data_type = 'object')
    {
        $result = $this->db->get('core_roles');

        switch ($data_type)
        {
            case 'array':
                $result_type = $result->result_array();
                break;
            case 'object':
                $result_type = $result->result();
                break;
        }

        return ($result->num_rows() > 0) ? $result_type : FALSE;
    }

    /**
     * Admin adds or edits a role.
     *
     * @param array $post
     * @return mixed
     */
    public function admin_role_save($post)
    {
        $post = $this->prep_post($post);

        // There is a name conflict in the core input class that prohibits using
        // 'protected' as a field name.  So the name is changed here.
        if (isset($post['protected_value']))
        {
            $post['protected'] = (int) $post['protected_value'];
            unset($post['protected_value']);
        }

        unset($post['save']);

        switch ($post)
        {
            // Add a role.
            case !isset($post['id']):
                $query = $this->db->insert('core_roles', $post);
                $id    = $this->db->insert_id();
                return ($id) ? $id : FALSE;
                break;

            // Edit a role.
            case isset($post['id']):
                $query = $this->db
                    ->where('id', (int) $post['id'])
                    ->update('core_roles', $post);
                return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
                break;
        }
    }

    /**
     * Admin deltes a role.
     *
     * @param integer $id
     * @return boolean
     */
    public function admin_role_delete($id = NULL)
    {
        $query = $this->db->delete('core_roles', array('id' => (int) $id), 1);
        $result = $this->db->affected_rows();

        return ($result > 0) ? TRUE : FALSE;
    }

    /**
     * Admin gets all users.
     *
     * @return array
     */
    public function admin_user_get_all()
    {
        $query = $this->db->get('core_users');

        if ($query->num_rows() > 0)
        {
            $result_array =  $query->result_array();
            foreach ($result_array as $result)
            {
                unset($result['password']);
            }
        }

        return ($query->num_rows() > 0) ? $result_array : FALSE;
    }

    /**
     * Paginated users.
     *
     * @param integer $per_page
     * @param integer $start
     * @return mixed
     */
    public function admin_user_limit_offset_get($per_page, $start)
    {
        $query = $this->db
            ->select('core_users.id, username, email, role, created, core_users.protected')
            ->join('core_join_users_roles', 'core_join_users_roles.user_id = core_users.id')
            ->join('core_roles', 'core_roles.id = core_join_users_roles.role_id')
            ->get('core_users', (int) $per_page, (int) $start);

        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

}
/* End of file core_user_model.php */
