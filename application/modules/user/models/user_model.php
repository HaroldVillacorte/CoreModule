<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed.');

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('user_library');
        $this->load->database();
    }

    public function prep_post($post_array = array()) {
        foreach ($post_array as $key => $value) {
            if ((!isset($post_array[$key]) || empty($value) || $value == ''|| $value == NULL) && $value !== 0) {
                unset($post_array[$key]);
            }
            if (is_string($value)) {
                $this->db->escape_str($value);
            }
        }
        return $post_array;
    }

    public function find_user($id = NULL) {
        $user = $this->db
                ->select('users.id, users.protected, username, email, created, role')
                ->join('join_users_roles', 'join_users_roles.user_id = users.id')
                ->join('roles', 'roles.id = join_users_roles.role_id')
                ->get_where('users', array('users.id' => (int) $id));
        return ($user) ? $user->row() : FALSE;
    }

    public function get_user_roles() {
        if ($this->session->userdata('user_id')) {
            $id = $this->session->userdata('user_id');
            $role_ids = $this->db
                    ->get_where('join_users_roles', array('user_id' => (int) $id))
                    ->result_array();
            $roles = array();
            foreach ($role_ids as $role_id) {
                $found_role = $this->db
                        ->select('role')
                        ->get_where('roles', array('id' => (int) $role_id));
                $roles[] = $found_role;
            }
            return $roles;
        }
    }

    public function login($username, $password) {
        // Sanitize username.
        $sanitized_username = $this->db->escape_str($username);
        // Sanitize and encrypt password.
        $encrypted_password = $this->db->escape_str($password);
        // Run the query.
        $result = $this->db
                ->select('users.id, username, email, created, role')
                ->join('join_users_roles', 'join_users_roles.user_id = users.id')
                ->join('roles', 'roles.id = join_users_roles.role_id')
                ->get_where('users', array(
                            'username' => $sanitized_username,
                            'password' => $encrypted_password,), 1);
        $user = $result->row();
        return ($result->num_rows() == 1) ? $user : FALSE;
    }

    public function store_remember_code($remember_code = NULL, $id = NULL) {
        $this->db->set('remember_code', $remember_code)
                ->where('id', (int) $id)
                ->update('users');
        $num_rows = $this->db->affected_rows();
        return ($num_rows > 0) ? TRUE : FALSE ;
    }

    public function delete_remember_code($id = NULL) {
        $this->db->set('remember_code', '')
                ->where('id', (int) $id)
                ->update('users');
        $num_rows = $this->db->affected_rows();
        return ($num_rows > 0) ? TRUE : FALSE ;
    }

    public function add_user($post = NULL) {
        $this->load->helper('date');

        $post = $this->prep_post($post);

        $post['created'] = now();
        unset($post['passconf']);
        unset($post['add']);
        $result = $this->db->insert('users', $post);
        $id = $this->db->insert_id();
        $result2 = $this->db
                ->insert('join_users_roles', array(
                                                'user_id' => (int) $id,
                                                'role_id' => 3,
                                             ));
        return ($result2) ? $id : FALSE;
    }

    public function edit_user($post) {

        $post = $this->prep_post($post);

        // Make sure this is the logged in user.
        $user_id = $this->session->userdata('user_id');
        if ($post['id'] !== $user_id) {
            $this->session->set_flashdata('message_error', 'You are not authorized to edit this user.');
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

        return ($result) ? TRUE : FALSE ;
    }

    public function delete_user($id = NULL) {
        $this->db->delete('users', array('id' => (int) $id), 1);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

}

/* End of file user_model.php */