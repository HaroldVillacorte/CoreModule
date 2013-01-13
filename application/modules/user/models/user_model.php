<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {

  public function save_user($post) {
    if ($post['id'] != NULL || $post['id'] != '') {
      $result = $this->get_user_by_id ($post['id']);
      $result_row = $result->row ();
      if ($result_row->protected == 0) {
        unset ($post['submit']);
        unset ($post['passconf']);
        if ($this->db->update ('user', $post, array('id' => $post['id']), 1)) {
          $id = $post['id'];
          return 'updated';
        }
        else {
          return FALSE;
        }
        ;
      }
      elseif ($result_row->protected == 1) {
        return 'protected';
      }
    }
    else {
      unset ($post['submit']);
      unset ($post['passconf']);
      if ($this->db->insert ('user', $post)) {
        $result_set = $this->db->get_where ('user', array('username' => $post['username']), 1);
        $result = $result_set->row ();
        $id = $result->id;
        return 'inserted';
      }
      else {
        return FALSE;
      }
    }
  }

  public function delete_user($id) {
    $result = $this->get_user_by_id ($id);
    $result_row = $result->row ();
    if ($result_row->protected == 0) {
      if ($this->db->delete ('user', array('id' => $id), 1)) {
        return 'success';
      }
      else {
        return FALSE;
      }
    }
    elseif ($result_row->protected == 1) {
      return 'protected';
    }
    else {
      return FALSE;
    }
  }

  public function get_user_by_id($id) {
    if (isset ($id['id'])) {
      if ($result = $this->db->get_where ('user', array('id' => $id))) {
        return $result;
      }
      else {
        return FALSE;
      }
    }
    else {
      return FALSE;
    }
  }

  public function login($post) {
    if (isset ($post['username']) && isset ($post['password'])) {
      $where = array(
        'username' => $post['username'], 'password' => $post['password'],
      );
      $result = $this->db->get_where ('user', $where, 1);
      if ($result->num_rows == 1) {
        $result_set = $result->row_array ();
        $userarray = array(
          'user_id' => $result_set['id'],
          'username' => $result_set['username'],
          'email' => $result_set['email'],
          'first_name' => $result_set['first_name'],
          'last_name' => $result_set['last_name'],
          'role' => $result_set['role'],
        );
        $this->session->set_userdata ($userarray);
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
  }
}
/* End of file user.php */
