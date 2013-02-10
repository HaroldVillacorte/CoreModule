<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class User_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

        // Load libraries.
        $this->load->library('user_library');

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
    public function find_user($id = NULL)
    {
        $user = $this->db
            ->select('users.id, users.protected, username, email, created, role_id, role')
            ->join('join_users_roles', 'join_users_roles.user_id = users.id')
            ->join('roles', 'roles.id = join_users_roles.role_id')
            ->get_where('users', array('users.id' => (int) $id));

        return ($user) ? $user->row() : FALSE;
    }

    /**
     * Currently not in use.  Will get all roles of user.
     *
     * @return array $roles
     */
    public function get_user_roles()
    {
        if ($this->session->userdata('user_id'))
        {
            $id       = $this->session->userdata('user_id');
            $role_ids = $this->db
                ->get_where('join_users_roles', array('user_id' => (int) $id))
                ->result_array();
            $roles    = array();

            foreach ($role_ids as $role_id)
            {
                $found_role = $this->db
                    ->select('role')
                    ->get_where('roles', array('id' => (int) $role_id));
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
    public function login($username, $password)
    {
        // Sanitize username.
        $sanitized_username = $this->db->escape_str($username);
        // Sanitize and encrypt password.
        $sanitized_password = $this->db->escape_str($password);
        // Run the query.
        $result = $this->db
            ->select('users.id, username, email, created, role')
            ->join('join_users_roles', 'join_users_roles.user_id = users.id')
            ->join('roles', 'roles.id = join_users_roles.role_id')
            ->get_where('users', array(
            'username' => $sanitized_username,
            'password' => $sanitized_password,), 1);

        return ($result->num_rows() == 1) ? $result->row() : FALSE;
    }

    /**
     * Stores the persistent login cookie code, the users ip, and the user's user
     * agent in the database.
     *
     * @param string $remember_code
     * @param integer $id
     * @return boolean
     */
    public function store_remember_code($remember_code = NULL, $id = NULL)
    {
        $ip_address = $this->session->userdata('ip_address');
        $user_agent = $this->session->userdata('user_agent');

        $this->db
            ->set('remember_code', $remember_code)
            ->set('ip_address', $ip_address)
            ->set('user_agent', $user_agent)
            ->where('id', (int) $id)
            ->update('users');
        $num_rows = $this->db->affected_rows();

        return ($num_rows > 0) ? TRUE : FALSE;
    }

    /**
     * Deletes the persistent login cookie code, the users ip, and the user's user
     * agent from the database.
     *
     * @param integer $id
     * @return boolean
     */
    public function delete_remember_code($id = NULL)
    {
        $this->db
            ->set('remember_code', '')
            ->set('ip_address', '')
            ->set('user_agent', '')
            ->where('id', (int) $id)
            ->update('users');
        $num_rows = $this->db->affected_rows();

        return ($num_rows > 0) ? TRUE : FALSE;
    }

    /**
     * Returns the id of the added user or false.
     *
     * @param array $post
     * @return mixed
     */
    public function add_user($post = NULL)
    {
        $post = $this->prep_post($post);

        $post['created'] = time();
        unset($post['passconf']);
        unset($post['add']);

        $result  = $this->db->insert('users', $post);
        $id      = $this->db->insert_id();
        $result2 = $this->db
            ->insert('join_users_roles', array(
            'user_id' => (int) $id,
            'role_id' => 3,
            ));

        return ($result2) ? $id : FALSE;
    }

    /**
     * User edits own account and the session userdata is reset with the new values.
     *
     * @param array $post
     * @return boolean
     */
    public function edit_user($post)
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
            ->where('id', (int) $post['id'])
            ->limit(1)
            ->update('users', $post);

        // Reset the user session data ater update.
        $updated_user = $this->find_user((int) $post['id']);
        $this->user_library->set_user_session_data($updated_user);

        return ($result) ? TRUE : FALSE;
    }

    /**
     * User deletes own account.
     *
     * @return boolean
     */
    public function delete_user()
    {
        // Get the user id from the session.
        $id = $this->session->userdata('user_id');

        // Delete the user.
        $result = $this->db->delete('users', array('id' => (int) $id), 1);

        if ($result)
        {
            // Delete the user role from the join table.
            $result2 = $this->db
                ->delete('join_users_roles', array('user_id' => (int) $id), 1);
        }

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * Admin adds or edits a user.
     *
     * @param array $post
     * @return boolean
     */
    public function admin_save_user($post)
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
                $result          = $this->db->insert('users', $post);
                $id              = $this->db->insert_id();

                if ($id)
                {
                    // Set the users role.
                    $result2 = $this->db->insert(
                        'join_users_roles', array(
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
                    ->update('users', $post);

                if ($result)
                {
                    // Edit the user's role.
                    $result2 = $this->db
                        ->where('user_id', $post['id'])
                        ->limit(1)
                        ->update('join_users_roles', array('role_id' => $role));
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
    public function admin_delete_user($id = NULL)
    {
        // Delte the user.
        $result = $this->db->delete('users', array('id' => (int) $id), 1);

        if ($result)
        {
            // Delete the role.
            $result2 = $this->db
                ->delete('join_users_roles', array('user_id' => (int) $id), 1);

            // For some reason I chose to return a string instead of TRUE.
            return ($this->db->affected_rows() > 1) ? 'deleted' : FALSE;
        }
    }

    /**
     * Get the role of a user.
     *
     * @param integer $id
     * @return mixed
     */
    public function admin_get_role($id = NULL)
    {
        $query = $this->db->where('id', (int) $id)->get('roles');
        $role  = $query->row();
        return ($query->num_rows() > 0) ? $role : FALSE;
    }

    /**
     * Admin gets all roles.  Choose whether to return an 'array' or 'object'.
     *
     * @param string $data_type
     * @return mixed
     */
    public function admin_get_all_roles($data_type = 'array')
    {
        $query       = $this->db->get('roles');
        $return_type = NULL;

        switch ($data_type)
        {
            case 'array':
                $return_type = $query->result_array();
                break;
            case 'object':
                $return_type = $query->result();
        }

        return ($query->num_rows() > 0) ? $return_type : FALSE;
    }

    /**
     * Admin adds or edits a role.
     *
     * @param array $post
     * @return mixed
     */
    public function admin_save_role($post)
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
                $query = $this->db->insert('roles', $post);
                $id    = $this->db->insert_id();
                return ($id) ? $id : FALSE;
                break;

            // Edit a role.
            case isset($post['id']):
                $query = $this->db
                    ->where('id', (int) $post['id'])
                    ->update('roles', $post);
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
    public function admin_delete_role($id = NULL)
    {
        $query = $this->db->delete('roles', array('id' => (int) $id), 1);
        $result = $this->db->affected_rows();

        return ($result) ? TRUE : FALSE;
    }

    /**
     * Admin gets all users.  Make sure to unset passwords.
     *
     * @return mixed
     */
    public function admin_get_all_users()
    {
        $query = $this->db->get('users');

        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    /**
     * Paginated users.
     * 
     * @param integer $per_page
     * @param integer $start
     * @return mixed
     */
    public function admin_get_limit_offset_users($per_page, $start)
    {
        $query = $this->db
            ->select('users.id, username, email, role, created, users.protected')
            ->join('join_users_roles', 'join_users_roles.user_id = users.id')
            ->join('roles', 'roles.id = join_users_roles.role_id')
            ->get('users', (int) $per_page, (int) $start);

        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

}
/* End of file user_model.php */
