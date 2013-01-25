<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class User_Admin_Model extends CI_Model {

  function __construct() {
    parent::__construct();
    $this->load->library('doctrine');
  }

  public function find_user ($id = NULL) {
    $user = $this->doctrine->em->find('Entities\User', $id);
    return ($user) ? $user : FALSE ;
  }

  public function save_user($post, $user = NULL) {
    switch ($post) {
      case !isset($post['id']):
        $password = sha1($post['password']);
        $new_user = new Entities\User;
        $new_user->setUsername($post['username']);
        $new_user->setPassword($password);
        $new_user->setEmail($post['email']);
        $new_user->setFirstName($post['first_name']);
        $new_user->setLastName($post['last_name']);
        $new_user->setRole($post['role']);
        $new_user->setCreated(new DateTime);
        $new_user->setProtected($post['protected_value']);
        $this->doctrine->em->persist($new_user);
        break;
      case isset($post['id']):
        $user->setUsername($post['username']);
        $user->setEmail($post['email']);
        $user->setFirstname($post['first_name']);
        $user->setLastname($post['last_name']);
        $user->setRole($post['role']);
        $user->setProtected($post['protected_value']);
        break;
    }
    try {
      $this->doctrine->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  public function delete_user($id = NULL) {
    $user = $this->find_user($id);
    switch ($user) {
      case $user->getProtected() == TRUE:
        return 'protected';
        break;
      case $user->getProtected() == FALSE:
        try {
          $this->doctrine->em->remove($user);
          $this->doctrine->em->flush();
          return 'deleted';
        }
        catch (Exception $e) {
          return FALSE;
        }
        break;
    }
  }

  public function add_role_set_validation_rules ($rules) {
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

  public function add_edit_user_set_validation_rules ($rules) {
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

  public function get_all_roles () {
    $dql = 'SELECT u FROM Entities\Role u';
    $query1 = $this->doctrine->em->createQuery($dql);
    $roles = $query1->getResult();
    return ($roles) ? $roles : FALSE;
  }

  public function insert_role ($post) {
    $role = new Entities\Role;
    $role->setRole($post['role']);
    $this->doctrine->em->persist($role);
    try {
      $this->doctrine->em->flush();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  public function get_all_users () {
    $dql = 'SELECT u FROM Entities\User u';
    $query = $this->doctrine->em->createQuery($dql);
    return ($query) ?  $query : FALSE ;
  }

  public function get_limit_offset($per_page, $start) {
    $dql = 'SELECT u FROM Entities\User u';
    $query = $this->doctrine->em
            ->createQuery($dql)
            ->setMaxResults($per_page)
            ->setFirstResult($start);
    return ($query) ? $query  : FALSE ;
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
      $result[$key]['created'] = $result[$key]['created']->format('D M, d Y h:i:s a');
      if ($result[$key]['protected']) {
        $result[$key]['protected'] = 'Yes';
      }
      else {
        $result[$key]['protected'] = 'No';
      }
    }
    return $result;
  }

}

/* End of file user_admin_model.php */
