<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_user_model extends CI_Model
{

    private static $PasswordHash;

    function __construct()
    {
        parent::__construct();

        // Load the config file.
        $this->config->load('_core_user/core_user_config');

        // Load the libraries.
        $this->load->library('encrypt');

        // Load the helpers.
        $this->load->helper('string');

        // Load the PHPass class and instantiate.
        require_once 'PasswordHash.php';
        self::$PasswordHash = new PasswordHash(8, FALSE);

        // Load the database class.
        $this->load->database();
    }

    /**
     * Generate salt and hash password.
     *
     * @param array $post
     * @return array
     */
    public function user_password_salt($post = array())
    {
        // Generate salt.
        $salt = random_string('alnum', 16);
        $encrypted_salt = $this->encrypt->encode($salt);

        // Store salt encrypted in the database.
        $post['salt'] = $encrypted_salt;

        // Salt the password.
        $salted_password = $post['password'] . $salt;

        // Hash the salted password.
        $post['password'] = self::$PasswordHash->HashPassword($salted_password);

        return $post;
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
            ->select('core_users.id, core_users.protected, username, email,
                      created, active, role_id, role, locked_out_time')
            ->join('core_user_join_users_roles', 'core_user_join_users_roles.user_id = core_users.id')
            ->join('core_roles', 'core_roles.id = core_user_join_users_roles.role_id')
            ->get_where('core_users', array('core_users.id' => (int) $id));

        return ($user->num_rows() > 0) ? $user->row() : FALSE;
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
            ->select('core_users.id, core_users.protected, username, email,
                      created, active, role_id, role, locked_out_time')
            ->join('core_user_join_users_roles', 'core_user_join_users_roles.user_id = core_users.id')
            ->join('core_roles', 'core_roles.id = core_user_join_users_roles.role_id')
            ->get_where('core_users', array('core_users.' . $column => $identity));

        return ($user->num_rows() > 0) ? $user->row() : FALSE;
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
                ->get_where('core_user_join_users_roles', array('user_id' => (int) $id))
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

        // Run the query.
        $query = $this->db
            ->select('id, password, salt')
            ->where('username', $sanitized_username)
            ->get('core_users', 1);

        // Generate result.
        $result = $query->row();

        // Construct the password.
        $salt = $this->encrypt->decode($result->salt);
        $salted_password = $password . $salt;

        // Authenticate password.
        $authenticated = self::$PasswordHash->CheckPassword($salted_password, $result->password);

        if (!$authenticated)
        {
            return FALSE;
        }
        else
        {
            // Find user and return user object.
            $user = $this->user_find($result->id);
            return ($user) ? $user : FALSE;
        }
    }

    /**
     * Store failed login attempts and check number of attempts.
     *
     * @param array $post
     * @return string
     */
    public function user_login_log_failed_attempt($post = array())
    {
        $post = $this->core_module_library->prep_post($post);
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
     * @param array $remember_code_array
     * @return object
     */
    public function user_check_logged_in($remember_code_array = array())
    {
        // Sanitize.
        $post = $this->core_module_library->prep_post($remember_code_array);

        // Run the query.
        $query = $this->db
                    ->select('remember_code')
                    ->get_where('core_user_remember_codes', array(
                        'user_id'       => (int) $post['user_id'],
                        'ip_address'    => $post['ip_address'],
                        'user_agent'    => $post['user_agent'],
                        ));
        $row = $query->row();

        // Validate the remember code.
        $validated_code = self::$PasswordHash
            ->CheckPassword($post['remember_code'], $row->remember_code);

        // Return user object if code validates.
        if (!$validated_code)
        {
            return FALSE;
        }
        else
        {
            // Find user and return user object.
            $user = $this->user_find($post['user_id']);
            return ($user) ? $user : FALSE;
        }


    }

    /**
     * Stores the persistent login cookie code, the users ip, and the user's user
     * agent in the database.
     *
     * @param string $remember_code
     * @param integer $id
     * @return boolean
     */
    public function user_remember_code_store($remember_code_array = array())
    {
        // Clean up expired stuff.
        $this->admin_user_tables_cleanup();

        // Sanitize array.
        $post = $this->core_module_library->prep_post($remember_code_array);

        // Assign user session data.
        $post['ip_address']    = $this->session->userdata('ip_address');
        $post['user_agent']    = $this->session->userdata('user_agent');
        $post['remember_code'] = self::$PasswordHash->HashPassword($post['remember_code']);

        // Runf the query.
        $this->db->insert('core_user_remember_codes', $post);
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
        $this->db->where('user_id', (int) $id)->delete('core_user_remember_codes');
        $num_rows = $this->db->affected_rows();

        return ($num_rows > 0) ? TRUE : FALSE;
    }

    /**
     * Insert forgotten password code and time.
     *
     * @param array $forgotten_password_data
     * @return boolean
     */
    public function user_forgotten_password_code_add($forgotten_password_data = array())
    {
        // Clean up expired stuff.
        $this->admin_user_tables_cleanup();

        // Prep post.
        $post = $this->core_module_library->prep_post($forgotten_password_data);

        // Delete any previous records for this user.
        $this->user_forgotten_password_code_delete($post['user_id']);

        // Hash code.
        $post['forgotten_password_code'] = self::$PasswordHash
            ->HashPassword($post['forgotten_password_code']);

        // Insert new record.
        $this->db->insert('core_user_forgotten_passwords', $post);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Delete forgotten password code and time.
     *
     * @param integer $id
     */
    public function user_forgotten_password_code_delete($id = NULL)
    {
        $this->db->where('user_id', (int) $id)
            ->delete('core_user_forgotten_passwords');
    }

    /**
     * User logs in once.
     *
     * @param array $array
     * @return string
     */
    public function user_forgotten_password_login($array = array())
    {
        // Prep the post array.
        $post = $this->core_module_library->prep_post($array);

        // Run the query.
        $query = $this->db
            ->select('forgotten_password_code, forgotten_password_expire_time')
            ->where('user_id', (int) $post['id'])
            ->get('core_user_forgotten_passwords', 1);

        if ($query->num_rows() != 1)
        {
            // User id not found.
            return 'not_found';
            exit();
        }
        else
        {
            // User id found.
            $record = $query->row();

            // Authenticate code.
            $result = self::$PasswordHash->CheckPassword($post['code'], $record->forgotten_password_code);

            if (!$result)
            {
                // Code was not valid
                return FALSE;
            }
            else
            {
                // Code validated check expired.
                switch ($record)
                {
                    // Forgotten password time is not expired.
                    case $record->forgotten_password_expire_time >= time():
                        $this->user_forgotten_password_code_delete($record->user_id);
                        return 'authenticated';
                        break;
                    // Forgotten password time is expired.
                    case $record->forgotten_password_expire_time < time():
                        $this->user_forgotten_password_code_delete($record->user_id);
                        return 'expired';
                        break;
                }
            }
        }
    }

    /**
     * Returns the id of the added user or false.
     *
     * @param array $post
     * @return mixed
     */
    public function user_add($post = NULL)
    {
        $sanitized_post = $this->core_module_library->prep_post($post);

        // Generate salt and hash password.
        $post = $this->user_password_salt($sanitized_post);

        // Set created time.
        $post['created'] = time();

        unset($post['passconf']);
        unset($post['add']);

        // Add the user.
        $this->db->insert('core_users', $post);
        $id = $this->db->insert_id();

        if ($id)
        {
            // Add the user role
            $this->db
            ->insert('core_user_join_users_roles', array(
                'user_id' => (int) $id,
                'role_id' => 3,
            ));

            // Generate and add the activation code.
            if ($this->db->affected_rows() > 0)
            {
                // Generate unique activation code from email.
                $activation_code = random_string('alnum', 64);
                $hashed_code = self::$PasswordHash->HashPassword($activation_code);
                $expire_time = time() + $this->config->item('user_activation_expire_limit');
                // Generate the activation code insert array.
                $activation_code_array = array(
                    'user_id' => (int) $id,
                    'activation_code' => $this->db->escape_str($hashed_code),
                    'expire_time' => (int) $expire_time,
                );
                // Run the query
                $this->db->insert('core_user_activation_codes', $activation_code_array);

                if ($this->db->affected_rows() > 0)
                {
                    // Reassign the unhased activation code to array and return.
                    $activation_code_array['activation_code'] = $activation_code;
                    return $activation_code_array;
                }
                else
                {
                    // Delete user and role record if activation code insert fail.
                    $this->user_delete($id);
                    return FALSE;
                }
            }
            else
            {
                // Delete user and role record if role insert fail.
                $this->user_delete($id);
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * User activates own account through email link.
     *
     * @param array $code_array
     * @return string
     */
    public function user_activate($code_array = array())
    {
        // Sanitize code.
        $code = $this->db->escape_str($code_array['code']);

        // Clean up expired stuff.
        $this->admin_user_tables_cleanup();

        // Find activaton code.
        $result = $this->db
            ->select('user_id, activation_code, expire_time')
            ->where('user_id', (int) $code_array['id'])
            ->get('core_user_activation_codes', 1);

        if ($result->num_rows() != 1)
        {
            // If activation code id not found in the database.
            return 'not_found';
        }
        else
        {
            $code_row = $result->row();

            // Check if code is valid.
            $validated_code = self::$PasswordHash->CheckPassword($code, $code_row->activation_code);
            if (!$validated_code)
            {
                return 'invalid';
            }
            // Check if code expired.
            elseif ($code_row->expire_time < time())
            {
                // Or delete expired account and return 'expired'.
                $this->admin_user_delete($code_row->user_id);
                return 'expired';
            }
            else
            {
                $this->db->where('id', $code_row->user_id)->update('core_users', array('active' => 1));

                if ($this->db->affected_rows() > 0)
                {
                    // Delete activation code and return TRUE.
                    $this->db->delete('core_user_activation_codes', array('user_id' => $code_row->user_id));
                    return 'activated';
                }
                else
                {
                    return FALSE;
                }
            }
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
        $post = $this->core_module_library->prep_post($post);

        // Make sure this is the logged in user.
        $user_id = $this->session->userdata('user_id');

        // If logged in user and post id do not match.
        if ($post['id'] !== $user_id)
        {
            // Redirect user.
            $this->session->set_flashdata('message_error', $this->lang->line('error_user_account_edit_unauthorized'));
            redirect(base_url());
        }

        unset($post['passconf']);
        unset($post['save']);

        if (isset($post['password']))
        {
            // Generate salt and hash password.
            $post = $this->user_password_salt($post);
        }

        // Run the query.
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

        // Delete the roles records.
        $this->db->delete('core_user_join_users_roles', array('user_id' => (int) $id), 1);
        // Delete remember codes.
        $this->db->delete('core_user_remember_codes', array('user_id' => (int) $id), 1);
        // Delete forgotten password codes.
        $this->db->delete('core_user_forgotten_passwords', array('user_id' => (int) $id), 1);
        // Delete activation codes.
        $this->db->delete('core_user_activation_codes', array('user_id' => (int) $id), 1);
        // Delete the user.
        $this->db->delete('core_users', array('id' => (int) $id), 1);

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
        $post = $this->core_module_library->prep_post($post);

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

                // Generate salt and hash password.
                $post = $this->user_password_salt($post);

                $post['created'] = time();
                $result          = $this->db->insert('core_users', $post);
                $id              = $this->db->insert_id();

                if ($id)
                {
                    // Set the users role.
                    $result2 = $this->db->insert(
                        'core_user_join_users_roles', array(
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
                        ->update('core_user_join_users_roles', array('role_id' => $role));
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
        // Delete the roles records.
        $this->db->delete('core_user_join_users_roles', array('user_id' => (int) $id), 1);
        // Delete remember codes.
        $this->db->delete('core_user_remember_codes', array('user_id' => (int) $id), 1);
        // Delete forgotten password codes.
        $this->db->delete('core_user_forgotten_passwords', array('user_id' => (int) $id), 1);
        // Delete activation codes.
        $this->db->delete('core_user_activation_codes', array('user_id' => (int) $id), 1);
        // Delete the user.
        $this->db->delete('core_users', array('id' => (int) $id), 1);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get the role of a user.
     *
     * @param integer $id
     * @return mixed
     */
    public function admin_role_get($id = NULL)
    {
        $query = $this->db->select('id, role, description, protected')
            ->where('id', (int) $id)->get('core_roles');
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
        // Exclude super_user role if not super user.
        if ($this->session->userdata('role') != 'super_user')
        {
            $result = $this->db->select('id, role, description, protected')->where('id !=', 1)->get('core_roles');
        }
        else
        {
            $result = $this->db->select('id, role, description, protected')->get('core_roles');
        }

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
        $post = $this->core_module_library->prep_post($post);

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
     * Admin deletes a role.
     *
     * @param integer $id
     * @return boolean
     */
    public function admin_role_delete($id = NULL)
    {
        $this->db->delete('core_roles', array('id' => (int) $id), 1);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
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
            ->join('core_user_join_users_roles', 'core_user_join_users_roles.user_id = core_users.id')
            ->join('core_roles', 'core_roles.id = core_user_join_users_roles.role_id')
            ->get('core_users', (int) $per_page, (int) $start);

        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    /**
     * Delete expired acivation codes and users, forgotten password codes, and
     * remember codes.
     */
    public function admin_user_tables_cleanup()
    {
        $time = time();

        // Find and delete expired inactive users.
        $query = $this->db
            ->select('user_id')
            ->where('expire_time <', $time)
            ->get('core_user_activation_codes');
        if ($query->num_rows() > 0)
        {
            $expired_array = $query->result_array();
            foreach ($expired_array as $expired_user)
            {
                $this->db->delete('core_users', array('id' => (int) $expired_user['user_id']));
            }
        }

        // Delete expired codes.
        $this->db->delete('core_user_activation_codes', array('expire_time <' => $time));
        $this->db->delete('core_user_forgotten_passwords', array('forgotten_password_expire_time <' => $time));
        $this->db->delete('core_user_remember_codes', array('expire_time <' => $time));
    }

}

/* End of file core_user_model.php */
