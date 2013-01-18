<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class User extends MX_Controller {

  protected static $data;
  protected $template = 'template/default_template';

  public function __construct() {
    parent::__construct ();
    $this->load->library ('encrypt');
    $this->load->library ('form_validation');
    //$this->load->model ('user_model');
    self::$data = $this->default_model->site_info ();
    self::$data['module'] = 'user';
    $this->load->library('doctrine');
  }

  public function index() {
    redirect (base_url () . 'user/profile');
  }

  public function profile() {
    $id = $this->session->userdata('user_id');
    if (!$id) {
      redirect (base_url ());
    }
    $user = $this->doctrine->em->find('Entities\User', $id);
    self::$data['user'] = $user;
    self::$data['view_file'] = 'user_profile';
    echo Modules::run ($this->template, self::$data);
  }

  public function permission($role) {
    if ($this->session->userdata ('role')) {
      $user_role = $this->session->userdata ('role');
    }
    else {
      $user_role = '';
    }
    if ($role != $user_role) {
      if ($this->session->userdata ('user_id')) {
        $message = 'That page requires ' . $role . ' permission. Please login as admin.';
        $this->session->set_flashdata ('message_error', $message);
        redirect (base_url () . 'user/profile/' . $this->session->userdata ('user_id'));
      }
      else {
        $message = 'That page requires ' . $role . ' permission. Please login.';
        $this->session->set_flashdata ('message_error', $message);
        redirect (base_url () . 'user/login');
      }
    }
  }

  public function login() {
    if ($this->input->post('submit')) {
      $rules = array(
          array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required',
          ),
          array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required',
          ),
      );
      $this->form_validation->set_rules ($rules);
      if ($this->form_validation->run () == FALSE) {
        // Form does not validate.
        self::$data['view_file'] = 'user_login';
        echo Modules::run ('template/default_template', self::$data);
      }
      else {
        // Encrypt password and send to database.
        $password = sha1 ($this->input->post('password'));
        $username = $this->input->post('username');
        $user = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array(
            'username' => $username,
            'password' => $password,)
                );
        if ($user) {
          // Login success.
          $this->session->set_flashdata ('message_success', 'You are now logged in as ' . $username . '.');
          $userarray = array(
              'user_id' => $user->getId(),
              'username' => $user->getUsername(),
              'email' => $user->getEmail(),
              'first_name' => $user->getFirstName(),
              'last_name' => $user->getLastName(),
              'role' => $user->getRole(),
              );
          $this->session->set_userdata ($userarray);
          redirect (base_url () . 'user/profile/');
        }
        else {
          // Unseccessful login.
          $this->session->set_flashdata ('message_error', 'Username and password combination not found.');
          redirect (current_url ());
        }
      }
    }
    if ($this->session->userdata('user_id')) {
      $this->session->keep_flashdata('message_success');
      redirect(base_url() . 'user/profile/');
    }
    self::$data['view_file'] = 'user_login';
    echo Modules::run ('template/default_template', self::$data);
  }

  public function logout() {
    $userarray = array('user_id', 'username', 'password', 'email', 'first_name', 'last_name', 'role',);
    $this->session->unset_userdata ($userarray);
    $this->session->sess_destroy ();
    redirect (base_url ());
  }

  public function crud() {
    if ($id = $this->session->userdata ('user_id')) {
      $user = $this->doctrine->em->find('Entities\User', $id);
      self::$data['user'] = $user;
    }
    if ($this->input->post('delete')) {
      if ($this->input->post('id')) {
        redirect (base_url () . 'user/delete/');
      }
    }

    if ($this->input->post('save')) {
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
      );
      $rules_update = array(
          array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required',
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
      );
      if ($this->input->post('id')) {
        $this->form_validation->set_rules ($rules_update);
      }
      else {
        $this->form_validation->set_rules ($rules_insert);
      }
      $valid_base64_error = 'Password may only contain alpha-numeric characters, +\'s, and /\'s';
      $this->form_validation->set_message ('valid_base64', $valid_base64_error);
      if ($this->form_validation->run () == FALSE) {
        // Form does not validate.
        self::$data['view_file'] = 'user_crud';
        echo Modules::run ($this->template, self::$data);
      }
      elseif ($this->form_validation->run () != FALSE) {

        // Encrypt password and send to database.
        $password = sha1 ($this->input->post('password'));

        if (!$this->input->post('id')) {
          // Doctrine
          $user = new Entities\User;
          $user->setUsername($this->input->post('username'));
          $user->setPassword($password);
          $user->setEmail($this->input->post('email'));
          $user->setFirstName($this->input->post('first_name'));
          $user->setLastName($this->input->post('last_name'));
          $user->setRole('authenticated');
          $user->setCreated(new DateTime());
          $user->setProtected(FALSE);
          $this->doctrine->em->persist($user);
        }
        else {
          $found_user = $this->doctrine->em->find('Entities\User', $this->input->post('id'));
          if($found_user->getProtected() == TRUE) {
            $this->session->set_flashdata ('message_error', 'Unable to save.  Account is protected.');
            redirect (current_url ());
          }
          else {
            $found_user->setUsername($this->input->post('username'));
            $found_user->setPassword($password);
            $found_user->setEmail($this->input->post('email'));
            $found_user->setFirstName($this->input->post('first_name'));
            $found_user->setLastName($this->input->post('last_name'));
            $found_user->setRole('authenticated');
          }
        }
        try {
          $this->doctrine->em->flush();
          $this->session->set_flashdata ('message_success', $this->input->post('username') . ' saved succesfully.');
          redirect (base_url () . 'user/login/');
        }
        catch (Exception $e) {
          $this->session->set_flashdata ('message_error', 'Unable to save user. Please contact administrator.');
          redirect (current_url ());
        }
      }
    }
    else {
      // Load page without $_POST['submit'].
      self::$data['view_file'] = 'user_crud';
      echo Modules::run ($this->template, self::$data);
    }
  }

  public function delete() {
    if ($id = $this->session->userdata('user_id')) {
      $user = $this->doctrine->em->find('Entities\User', $id);
    }
    if ($this->input->post('delete')) {
      if ($user->getProtected() == TRUE) {
        $this->session->set_flashdata ('message_error', 'Unable to delete.  Account is protected.');
        redirect (base_url () . 'user/crud/');
      }
      else {
        try {
          $this->doctrine->em->remove($user);
          $this->doctrine->em->flush();
          $this->session->set_flashdata ('message_success', 'Your account has been deleted.');
          redirect (base_url () . 'user/logout');
        }
        catch (Exception $e) {
          $this->session->set_flashdata ('message_error', 'Unable to delete your account.  Please contact administrator.');
          redirect (base_url () . 'user/crud/');
        }
      }
    }
    elseif ($id == NULL) {
      redirect (base_url ());
    }
    else {
      self::$data['user'] = $user;
      self::$data['view_file'] = 'user_delete';
      echo Modules::run ($this->template, self::$data);
    }
  }
}
/* End of file user.php */
