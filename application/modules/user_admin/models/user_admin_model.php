<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_Admin_Model extends CI_Model {

  function __construct() {
    parent::__construct();
    $this->load->library('doctrine');
    $this->load->database();
  }

  public function find_user($id = NULL) {
    $user = $this->db->get_where('users', array('id' => $id));
    return ($user) ? $user : FALSE;
  }

  public function save_user($post) {

    $post['protected'] = $post['protected_value'];
    $post['password'] = sha1($post['password']);
    unset($post['protected_value']);
    unset($post['passconf']);
    unset($post['save']);

    switch ($post) {
      case !isset($post['id']):
        $post['created'] = time();
        $result = $this->db->insert('users', $post);
        break;
      case isset($post['id']):
        $result = $this->db
                ->where('id', $post['id'])
                ->limit(1)
                ->update('users', $post);
        break;
    }
    return ($result) ? TRUE : FALSE;
  }

  public function delete_user($id = NULL) {
    $user = $this->find_user($id);
    if ($user->potected == 'protected') {
      return 'protected';
    }
    else {
      $this->db->delete('users', array('id' => $id), 1);
      return ($this->db->affected_rows() > 0) ? 'deleted' : FALSE ;
    }
  }

  public function save_role_set_validation_rules($rules) {
    // Set the validation rules for inserting a new user.
    $rules_insert = array(
        array(
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'required|is_unique[roles.role]',
        ),
    );
    $rules_update = array(
        array(
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'required',
        ),
    );
    $rule_set = NULL;
    switch ($rules) {
      case 'insert':
        $rule_set = $rules_insert;
        break;
      case 'update':
        $rule_set = $rules_update;
        break;
    }
    return $rule_set;
  }

  public function add_edit_user_set_validation_rules($rules) {
    // Set the validation rules for inserting a new user.
    $rules_insert = array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|is_unique[users.username]',
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|valid_base64|trim|max_length[12]|matches[passconf]',
        ),
        array(
            'field' => 'passconf',
            'label' => 'Password confirmation',
            'rules' => 'required',
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|is_unique[users.email]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'First name',
            'rules' => 'required',
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last name',
            'rules' => 'required',
        ),
        array(
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'required',
        ),
        array(
            'field' => 'protected_value',
            'label' => 'Protected',
            'rules' => 'required',
        ),
    );
    // Set the validation rules for updating a user.
    $rules_update = array(
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
            'field' => 'first_name',
            'label' => 'First name',
            'rules' => 'required',
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last name',
            'rules' => 'required',
        ),
        array(
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'required',
        ),
        array(
            'field' => 'protected_value',
            'label' => 'Protected',
            'rules' => 'required',
        ),
    );
    $rule_set = NULL;
    switch ($rules) {
      case 'insert':
        $rule_set = $rules_insert;
        break;
      case 'update':
        $rule_set = $rules_update;
        break;
    }
    return $rule_set;
  }

  public function get_role($id = NULL) {
    $query = $this->db->where('id', $id)->get('roles');
    $role = $query->row();
    return ($query->num_rows() > 0) ? $role : FALSE;
  }

  public function get_all_roles() {
    $query = $this->db->get('roles');
    return ($query->num_rows() > 0) ? $query->result() : FALSE;
  }

  public function save_role($post) {
    unset($post['save']);
    switch ($post) {
      case!isset($post['id']):
        $query = $this->db->insert('roles', $post);
        $id = $this->db->insert_id();
        return ($id) ? $id : FALSE;
        break;
      case isset($post['id']):
        $query = $this->db
                ->where('id', $post['id'])
                ->update('roles', $post);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
        break;
    }
  }

  public function delete_role($id = NULL) {
    $query = $this->db->delete('roles', array('id' => $id), 1);
    $result = $this->db->affected_rows();
    return ($result) ? TRUE : FALSE;
  }

  public function get_all_users() {
    $query = $this->db->get('users');
    return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
  }

  public function get_limit_offset($per_page, $start) {
    $query = $this->db->get('users', $per_page, $start);
    return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
  }

  public function user_page_pagination_setup($count, $per_page) {

    $pagination_config = array();

    // Pagination setup
    $pagination_config['base_url'] = base_url() . 'user_admin/users/';
    $pagination_config['total_rows'] = $count;
    $pagination_config['per_page'] = $per_page;
    // Style pagination Foundation 3
    // Full open
    $pagination_config['full_tag_open'] = '<ul class="pagination">';
    // Digits
    $pagination_config['num_tag_open'] = '<li>';
    $pagination_config['num_tag_close'] = '</li>';
    // Current
    $pagination_config['cur_tag_open'] = '<li class="current"><a href="#">';
    $pagination_config['cur_tag_close'] = '</a></li>';
    // Previous link
    $pagination_config['prev_tag_open'] = '<li class="arrow">';
    $pagination_config['prev_tag_close'] = '</li>';
    // Next link
    $pagination_config['next_tag_open'] = '<li class="arrow">';
    $pagination_config['nect_tag_close'] = '<li>';
    // First link
    $pagination_config['first_tag_open'] = '<li>';
    $pagination_config['first_tag_close'] = '</li>';
    // Last link
    $pagination_config['last_tag_open'] = '<li>';
    $pagination_config['last_tag_close'] = '</li>';
    // Full close
    $pagination_config['full_tag_close'] = '</ul>';

    return $pagination_config;
  }

  public function user_page_table_setup($output) {
    $this->load->helper('date');
    $result = $output;

    foreach ($result as $key => $value) { // add edit link.
      unset($result[$key]['password']);
      $result[$key]['edit'] =
              '<a href="' . base_url() . 'user_admin/delete/'
              . $result[$key]['id']
              . '" class="label alert round right" style="margin-left:10px;"'
              . 'onClick="return confirm(\'Are you sure?\')">Del</a>'
              . '<a href="' . base_url()
              . 'user_admin/edit_user/' . $result[$key]['id']
              . '" class="label secondary round right">Edit</a>';
      $result[$key]['created'] = unix_to_human($result[$key]['created']);
      if ($result[$key]['protected']) {
        $result[$key]['protected'] = 'Yes';
      } else {
        $result[$key]['protected'] = 'No';
      }
    }
    return $result;
  }

}

/* End of file user_admin_model.php */
