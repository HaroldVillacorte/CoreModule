<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
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
                ->select('users.id, users.protected, username, email, created, role_id, role')
                ->join('join_users_roles', 'join_users_roles.user_id = users.id')
                ->join('roles', 'roles.id = join_users_roles.role_id')
                ->get_where('users', array('users.id' => (int) $id));
        return ($user) ? $user : FALSE;
    }

    public function save_user($post) {

        $post = $this->prep_post($post);

        if (isset($post['protected_value'])) {
            $post['protected'] = (int) $post['protected_value'];
            unset($post['protected_value']);
        }

        $role = (int) $post['role'];
        unset($post['role']);

        unset($post['passconf']);
        unset($post['save']);

        switch ($post) {
            case !isset($post['id']):
                $post['created'] = time();
                $result = $this->db->insert('users', $post);
                $id = $this->db->insert_id();
                if ($id) {
                $result2 = $this->db->insert(
                        'join_users_roles',
                        array(
                            'user_id' => $id,
                            'role_id' => $role,
                        ));
                }
                break;
            case isset($post['id']):
                $result = $this->db
                        ->where('id', $post['id'])
                        ->limit(1)
                        ->update('users', $post);
                if ($result) {
                $result2 = $this->db
                        ->where('user_id', $post['id'])
                        ->limit(1)
                        ->update('join_users_roles', array('role_id' => $role));
                }
                break;
        }

        return ($result2) ? TRUE : FALSE;
    }

    public function delete_user($id = NULL) {
        $result = $this->db->delete('users', array('id' => (int) $id), 1);
        if ($result) {
            $result2 = $this->db
                    ->delete('join_users_roles', array('user_id' => (int) $id), 1);
            return ($this->db->affected_rows() > 1) ? 'deleted' : FALSE;
        }
    }

    public function get_role($id = NULL) {
        $query = $this->db->where('id', (int) $id)->get('roles');
        $role = $query->row();
        return ($query->num_rows() > 0) ? $role : FALSE;
    }

    public function get_all_roles($data_type = 'array') {

        $query = $this->db->get('roles');
        $return_type = NULL;
        switch ($data_type) {
            case 'array':
                $return_type = $query->result_array();
                break;
            case 'object':
                $return_type = $query->result();
        }
        return ($query->num_rows() > 0) ? $return_type : FALSE;
    }

    public function save_role($post) {

        $post = $this->prep_post($post);

        if (isset($post['protected_value'])) {
            $post['protected'] = (int) $post['protected_value'];
            unset($post['protected_value']);
        }

        unset($post['save']);
        switch ($post) {
            case !isset($post['id']):
                $query = $this->db->insert('roles', $post);
                $id = $this->db->insert_id();
                return ($id) ? $id : FALSE;
                break;
            case isset($post['id']):
                $query = $this->db
                        ->where('id', (int) $post['id'])
                        ->update('roles', $post);
                return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
                break;
        }
    }

    public function delete_role($id = NULL) {
        $query = $this->db->delete('roles', array('id' => (int) $id), 1);
        $result = $this->db->affected_rows();
        return ($result) ? TRUE : FALSE;
    }

    public function get_all_users() {
        $query = $this->db->get('users');
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    public function get_limit_offset_users($per_page, $start) {
        $query = $this->db
                ->select('users.id, username, email, role, created, users.protected')
                ->join('join_users_roles', 'join_users_roles.user_id = users.id')
                ->join('roles', 'roles.id = join_users_roles.role_id')
                ->get('users', (int) $per_page, (int) $start);
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

}

/* End of file user_admin_model.php */
