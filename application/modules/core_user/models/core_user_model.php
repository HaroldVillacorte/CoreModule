<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_user_model extends CI_Model
{

    private static $PasswordHash;

    function __construct()
    {
        parent::__construct();

        // Load the config file.
        $this->config->load('core_user/core_user_config');

        // Load the libraries.
        $this->load->library('encrypt');

        // Load the helpers.
        $this->load->helper('string');

        // Load the PHPass class and instantiate.
        require_once 'PasswordHash.php';
        self::$PasswordHash = new PasswordHash(8, FALSE);
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
     * Find user and join permissions.
     *
     * @param integer $id
     * @return object
     */
    public function user_find($id = NULL)
    {
        $query = $this->db
            ->select('id, protected, username, email, created, active, locked_out_time')
            ->get_where('core_users', array('id' => (int) $id));

        $user = $query->row();

        $user->permissions = $this->admin_user_permissions_get((int) $id);

        return ($query->num_rows() > 0) ? $user : FALSE;
    }

    /**
     * Find user by identity and join permissions.
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
                      created, active, permission_id, permission, locked_out_time')
            ->join('core_user_join_users_permissions', 'core_user_join_users_permissions.user_id = core_users.id')
            ->join('core_permissions', 'core_permissions.id = core_user_join_users_permissions.permission_id')
            ->get_where('core_users', array('core_users.' . $column => $identity));

        return ($user->num_rows() > 0) ? $user->row() : FALSE;
    }

    /**
     * Currently not in use.  Will get all permissions of user.
     *
     * @return array $permissions
     */
    public function user_get_permissions()
    {
        if ($this->session->userdata('user_id'))
        {
            $id       = $this->session->userdata('user_id');
            $permission_ids = $this->db
                ->get_where('core_user_join_users_permissions', array('user_id' => (int) $id))
                ->result_array();
            $permissions = array();

            foreach ($permission_ids as $permission_id)
            {
                $found_permission = $this->db
                    ->select('permission')
                    ->get_where('core_permissions', array('id' => (int) $permission_id));
                $permissions[] = $found_permission;
            }

            return $permissions;
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
        $post = prep_post($post);
        $expire_time = time() - variable_get('user_login_attempts_time');

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

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Count number of attempts.
        if ($all_login_attempts->num_rows() < variable_get('user_login_attempts_max'))
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

        // Clear the database cache.
        $this->db->cache_delete_all();
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
        $post = prep_post($remember_code_array);

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
        $post = prep_post($remember_code_array);

        // Assign user session data.
        $post['ip_address']    = $this->session->userdata('ip_address');
        $post['user_agent']    = $this->session->userdata('user_agent');
        $post['remember_code'] = self::$PasswordHash->HashPassword($post['remember_code']);

        // Runf the query.
        $this->db->insert('core_user_remember_codes', $post);
        $num_rows = $this->db->affected_rows();

        // Clear the database cache.
        $this->db->cache_delete_all();

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

        // Clear the database cache.
        $this->db->cache_delete_all();

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
        $post = prep_post($forgotten_password_data);

        // Delete any previous records for this user.
        $this->user_forgotten_password_code_delete($post['user_id']);

        // Hash code.
        $post['forgotten_password_code'] = self::$PasswordHash
            ->HashPassword($post['forgotten_password_code']);

        // Clear the database cache.
        $this->db->cache_delete_all();

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

        // Clear the database cache.
        $this->db->cache_delete_all();
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
        $post = prep_post($array);

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
        $sanitized_post = prep_post($post);

        // Generate salt and hash password.
        $post = $this->user_password_salt($sanitized_post);

        // Set created time.
        $post['created'] = time();

        unset($post['passconf']);
        unset($post['add']);

        // Add the user.
        $this->db->insert('core_users', $post);
        $id = $this->db->insert_id();

        // Clear the database cache.
        $this->db->cache_delete_all();

        if ($id)
        {
            // Add the user permission
            $this->db
            ->insert('core_user_join_users_permissions', array(
                'user_id' => (int) $id,
                'permission_id' => 3,
            ));

            // Generate and add the activation code.
            if ($this->db->affected_rows() > 0)
            {
                // Generate unique activation code from email.
                $activation_code = random_string('alnum', 64);
                $hashed_code = self::$PasswordHash->HashPassword($activation_code);
                $expire_time = time() + variable_get('user_activation_expire_limit');
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
                    // Delete user and permission record if activation code insert fail.
                    $this->user_delete($id);
                    return FALSE;
                }
            }
            else
            {
                // Delete user and permission record if permission insert fail.
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

        // Clear the database cache.
        $this->db->cache_delete_all();

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
        $post = prep_post($post);

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

        // Clear the database cache.
        $this->db->cache_delete_all();

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

        // Delete the permissions records.
        $this->db->delete('core_user_join_users_permissions', array('user_id' => (int) $id), 1);
        // Delete remember codes.
        $this->db->delete('core_user_remember_codes', array('user_id' => (int) $id), 1);
        // Delete forgotten password codes.
        $this->db->delete('core_user_forgotten_passwords', array('user_id' => (int) $id), 1);
        // Delete activation codes.
        $this->db->delete('core_user_activation_codes', array('user_id' => (int) $id), 1);
        // Delete the user.
        $this->db->delete('core_users', array('id' => (int) $id), 1);

        // Clear the database cache.
        $this->db->cache_delete_all();

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
        //Set and unset.
        $permissions = $post['permissions'];
        unset($post['permissions']);
        unset($post['passconf']);
        unset($post['save']);

        $post = prep_post($post);

        // There is a name conflict in the core input class that prohibits using
        // 'protected' as a field name.  So the name is changed here.
        if (isset($post['protected_value']))
        {
            $post['protected'] = (int) $post['protected_value'];
            unset($post['protected_value']);
        }
        else
        {
            $post['protected'] = 0;
        }



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
                    foreach ($permissions as $permission)
                    {
                        // Set the users permission.
                        $result2 = $this->db->insert(
                            'core_user_join_users_permissions', array(
                            'user_id' => $id,
                            'permission_id' => (int) $permission,
                        ));
                    }
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
                    // First delete all users permission.
                    $this->db->delete('core_user_join_users_permissions', array('user_id' => $post['id']));

                    // Now loop through and add permissions.
                    foreach ($permissions as $permission)
                    {
                        // Set the users permission.
                        $result2 = $this->db->insert(
                            'core_user_join_users_permissions', array(
                            'user_id' => (int) $post['id'],
                            'permission_id' => (int) $permission,
                        ));
                    }
                }
                break;
        }

        // Clear the database cache.
        $this->db->cache_delete_all();

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
        // Delete the permissions records.
        $this->db->delete('core_user_join_users_permissions', array('user_id' => (int) $id));
        // Delete remember codes.
        $this->db->delete('core_user_remember_codes', array('user_id' => (int) $id), 1);
        // Delete forgotten password codes.
        $this->db->delete('core_user_forgotten_passwords', array('user_id' => (int) $id), 1);
        // Delete activation codes.
        $this->db->delete('core_user_activation_codes', array('user_id' => (int) $id), 1);
        // Delete the user.
        $this->db->delete('core_users', array('id' => (int) $id), 1);

        // Clear the database cache.
        $this->db->cache_delete_all();

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Get a permission by id.
     *
     * @param integer $id
     * @return object
     */
    public function admin_permission_get($id = NULL)
    {
        $query = $this->db
            ->select('id, permission, description, protected')
            ->get_where('core_permissions', array('id' => (int) $id), 1);
        return ($query->num_rows() > 0) ? $query->row() : FALSE;
    }

    /**
     * Get the permissions of a user.
     *
     * @param integer $id
     * @return mixed
     */
    public function admin_user_permissions_get($id = NULL)
    {
        $query = $this->db->select('GROUP_CONCAT(permission) as permissions')
            ->where('core_user_join_users_permissions.user_id', (int) $id)
            ->join('core_permissions', 'core_permissions.id = core_user_join_users_permissions.permission_id')
            ->get('core_user_join_users_permissions');

        $row = $query->row();

        return ($query->num_rows() > 0) ? $row->permissions : '';
    }

    /**
     * Admin gets all permissions.  Choose whether to return an 'array' or 'object'.
     *
     * @param string $data_type
     * @return mixed
     */
    public function admin_permissions_get_all($data_type = 'object')
    {
        // Exclude super_user permission if not super user.
        if (!strstr($this->session->userdata('permissions'), 'super_user'))
        {
            $result = $this->db->select('id, permission, description, protected')->where('id !=', 1)->get('core_permissions');
        }
        else
        {
            $result = $this->db->select('id, permission, description, protected')->get('core_permissions');
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
     * Get paginated results for permission table.
     *
     * @param integer $per_page
     * @param integer $start
     * @param string $data_type
     * @return mixed
     */
    public function admin_permissions_get_limit_offset($per_page = NULL, $start = NULL, $data_type = 'object')
    {
        // Run the query.
        $result = $this->db
            ->select('id, permission, description, protected')
            ->get('core_permissions', (int) $per_page, (int) $start);

        // Return results.
        switch ($data_type)
        {
            case 'object':
                return ($result->num_rows() > 0) ? $result->result() : FALSE;
                break;
            case 'array':
                return ($result->num_rows() > 0) ? $result->result_array() : FALSE;
                break;
            case 'row':
                return ($result->num_rows() > 0) ? $result->row() : FALSE;
                break;
        }
    }

    /**
     * Admin adds or edits a permission.
     *
     * @param array $post
     * @return mixed
     */
    public function admin_permission_save($post)
    {
        $post = prep_post($post);

        // There is a name conflict in the core input class that prohibits using
        // 'protected' as a field name.  So the name is changed here.
        if (isset($post['protected_value']))
        {
            $post['protected'] = (int) $post['protected_value'];
            unset($post['protected_value']);
        }

        unset($post['save']);

        // Clear the database cache.
        $this->db->cache_delete_all();

        switch ($post)
        {
            // Add a permission.
            case !isset($post['id']):
                $query = $this->db->insert('core_permissions', $post);
                $id    = $this->db->insert_id();
                return ($id) ? $id : FALSE;
                break;

            // Edit a permission.
            case isset($post['id']):
                $query = $this->db
                    ->where('id', (int) $post['id'])
                    ->update('core_permissions', $post);
                return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
                break;
        }
    }

    /**
     * Admin deletes a permission.
     *
     * @param integer $id
     * @return boolean
     */
    public function admin_permission_delete($id = NULL)
    {
        $this->db->delete('core_permissions', array('id' => (int) $id), 1);

        // Clear the database cache.
        $this->db->cache_delete_all();

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
    public function admin_user_limit_offset_get($per_page = NULL, $start = NULL)
    {
        $query = $this->db
            ->select('id, username, email, created, protected')
            ->get('core_users', (int) $per_page, (int) $start);

        if ($query->num_rows() > 0)
        {
            $result_array = $query->result_array();

            foreach ($result_array as $key => $value)
            {
                $result_array[$key]['permissions'] = $this->admin_user_permissions_get($result_array[$key]['id']);
            }
        }

        return ($query->num_rows() > 0) ? $result_array : FALSE;
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

        // Clear the database cache.
        $this->db->cache_delete_all();
    }

}

/* End of file core_user_model.php */
