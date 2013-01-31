<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class User_Model extends CI_Model {

  function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function find_user($id = NULL) {
    $user = $this->db->get_where('users', array('id' => $id));
    return ($user) ? $user->row() : FALSE;
  }

  public function login ($username, $password) {
    $result = $this->db->get_where('users', array(
        'username' => $username,
        'password' => $password), 1);
    $user = $result->row();
    return ($result->num_rows() == 1) ? $user : FALSE ;
  }

  public function add_user ($post = NULL) {
    $this->load->helper('date');
    $post['password'] = sha1($post['password']);
    $post['role'] = 'authenticated';
    $post['created'] = now();
    unset($post['passconf']);
    unset($post['add']);
    $result = $this->db->insert('users', $post);
    $id = $this->db->insert_id();
    return ($id) ? $id : FALSE ;
  }

  public function edit_user($post) {

    $user = $this->find_user($post['id']);
    $post['password'] = sha1($post['password']);
    unset($post['passconf']);
    unset($post['save']);

    if (!$user->protected) {
      $result = $this->db
                ->where('id', $user->id)
                ->limit(1)
                ->update('users', $post);
      $num_rows = $this->db->affected_rows();

      // Reset the user session data ater update.
      $updated_user = $this->find_user($user->id);
      $this->set_user_session_data($updated_user);

      return ($num_rows > 0) ? 'updated' : 'fail' ;
    }
    elseif ($user->protected == 1) {
      return 'protected';
    }
  }

  public function delete_user($id = NULL) {
    $user = $this->find_user($id);
    if ($user->protected) {
      return 'protected';
    }
    else {
      $this->db->delete('users', array('id' => $id), 1);
      return ($this->db->affected_rows() > 0) ? 'deleted' : FALSE ;
    }
  }

  public function set_user_session_data ($user = NULL) {
    $userarray = array(
        'user_id' => $user->id,
        'username' => $user->username,
        'email' => $user->email,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'role' => $user->role,
        );
    // Set userdata session information.
    $this->session->set_userdata ($userarray);
  }

}

/* End of file user_model.php */