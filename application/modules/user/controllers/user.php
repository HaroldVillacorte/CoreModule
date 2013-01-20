<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');
/**
* User Module
*
* The User module provides full user CRUD, authentication, and a very simple yet
* highly effective permissions system.  This module user Doctrine.
*
* @package CI Starter
* @subpackage Modules
* @category Core
* @author Harold Villacorte
* @link http://laughinghost.com/CI_Starter/*/

class User extends MX_Controller {

  // Sets the $data property.
  protected static $data;
  // Set the default template.
  protected $template = 'core_template/default_template';

  /**
   * The data property is set to the site_info() array which passes an array
   * containing the site wide information such as the site name and asset path
   * information.  self::$data['module'] is the name of this module which is
   * passed to the Template module.
   *
   * @see core_model.php
   */
  public function __construct() {
    parent::__construct ();
    // Loads the Codeigniter system library.
    $this->load->library ('encrypt');
    // Loads the Codeigniter system library.
    $this->load->library ('form_validation');
    // Sets the the data array.
    self::$data = $this->core_model->site_info ();
    // Sets the module to be sent to the Template module.
    self::$data['module'] = 'user';
    // Loads the Doctrine library.
    $this->load->library('doctrine');
  }

  /**
   * Redirects user to the profile page.  Currently the index method does not
   * work with this module and HMVC.
   */
  public function index() {
    redirect (base_url () . 'user/profile');
  }

  /**
   * The user profile page.
   */
  public function profile() {

    // Checks if the user is logged in.  If not user is redirected to the
    // base_url().

    $id = $this->session->userdata('user_id');
    if (!$id) {
      redirect (base_url ());
    }
    // Instantiates the User Doctrine Entity then sends it to the view.
    $user = $this->doctrine->em->find('Entities\User', $id);
    self::$data['user'] = $user;
    self::$data['view_file'] = 'user_profile';
    echo Modules::run ($this->template, self::$data);
  }

  /**
   * The permissions method.  If the role set in the session does not match the
   * role specified by the method that calls this method user will be
   * redirected.
   *
   * @param string $role User role from the CI session cookie.
   */
  public function permission($role) {
    // Sets the $user_role variable.
    if ($this->session->userdata ('role')) {
      $user_role = $this->session->userdata ('role');
    }
    else {
      $user_role = '';
    }

    // If the $role set by the method that calls this method does not match the
    // $user_role variable user will be redirected.

    if ($role != $user_role) {
      // If the user is logged send user to the profile page.
      if ($this->session->userdata ('user_id')) {
        $message = 'That page requires ' . $role . ' permission. Please login as admin.';
        $this->session->set_flashdata ('message_error', $message);
        redirect (base_url () . 'user/profile/' . $this->session->userdata ('user_id'));
      }
      // If the user is not logged in send user to the login page.
      else {
        $message = 'That page requires ' . $role . ' permission. Please login.';
        $this->session->set_flashdata ('message_error', $message);
        redirect (base_url () . 'user/login');
      }
    }
  }

  /**
   * Basic login method.
   */
  public function login() {
    // Code to run when the user hits the Login button.
    if ($this->input->post('submit')) {
      // Sets the CI validation rules.
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
      // Code to run form does not validate.
      if ($this->form_validation->run () == FALSE) {
        self::$data['view_file'] = 'user_login';
        echo Modules::run ('core_template/default_template', self::$data);
      }
      // Code to run when form validates.
      else {
        // Encrypt password.
        $password = sha1 ($this->input->post('password'));
        // Set the $username variable.
        $username = $this->input->post('username');
        // Attemt to instantaite the Doctrine Entity with username and password.
        $user = $this->doctrine->em->getRepository('Entities\User')->findOneBy(array(
            'username' => $username,
            'password' => $password,)
                );
        // If the user is found by Doctrine.
        if ($user) {
          // Login success.
          $this->session->set_flashdata ('message_success', 'You are now logged in as ' . $username . '.');
          // Use Doctrine to set the $userarray.
          $userarray = array(
              'user_id' => $user->getId(),
              'username' => $user->getUsername(),
              'email' => $user->getEmail(),
              'first_name' => $user->getFirstName(),
              'last_name' => $user->getLastName(),
              'role' => $user->getRole(),
              );
          // Set userdata session information.
          $this->session->set_userdata ($userarray);
          redirect (base_url () . 'user/profile/');
        }

        // Code to run if username and password combination is not found in the
        // database.

        else {
          // Unseccessful login.
          $this->session->set_flashdata ('message_error', 'Username and password combination not found.');
          redirect (current_url ());
        }
      }
    }

    // Code to run when the user visits the page without hitting the Login
    // button.

    if ($this->session->userdata('user_id')) {
      $this->session->keep_flashdata('message_success');
      redirect(base_url() . 'user/profile/');
    }
    self::$data['view_file'] = 'user_login';
    echo Modules::run ('core_template/default_template', self::$data);
  }

  /**
   * Basic logout method using Codeigniter.  All this does is unset all
   * userdata set by the login method then destroys the session.
   */
  public function logout() {
    $userarray = array('user_id', 'username', 'password', 'email', 'first_name', 'last_name', 'role',);
    $this->session->unset_userdata ($userarray);
    $this->session->sess_destroy ();
    redirect (base_url ());
  }

  /**
   * Add user and edit user are combined into one method called "edit" using a
   * single view.  There are two fields which are not editable by this method:
   * 1. "role" is always set to authenticated with no other option.
   * 2. "protected" id not set and defaults to 0 or FALSE in the database.
   * How to edit these fields is left up to the developer using the CI Starter
   * package.  Additionally there is a delete button on the user edit view.
   * When this button is clicked this method will simply redirect the user to
   * the delete() method.
   */
  public function edit() {

    // If the user is logged in instatiate the Doctrine User Entity ans set the
    // self::$data['user'] property to send to the view so the user edit form
    // can be prepopulated.

    if ($id = $this->session->userdata ('user_id')) {
      $user = $this->doctrine->em->find('Entities\User', $id);
      self::$data['user'] = $user;
    }
    // When the user hits the delete button rediect them to the delete method.
    if ($this->input->post('delete')) {
      if ($this->input->post('id')) {
        redirect (base_url () . 'user/delete/');
      }
    }
    // Code to run when the user hits the save button.
    if ($this->input->post('save')) {
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
      );
      // Set the validation rules for updating a user.
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

      // Set the rules depending on if it is an insert or an update.  If the
      // $_POST['id'] is set it will be an update.

      if ($this->input->post('id')) {
        $this->form_validation->set_rules ($rules_update);
      }
      else {
        $this->form_validation->set_rules ($rules_insert);
      }

      // Set the message for valid_base64 validation error since it is missing
      // from the Codeigniter language files.

      $valid_base64_error = 'Password may only contain alpha-numeric characters, +\'s, and /\'s';
      $this->form_validation->set_message ('valid_base64', $valid_base64_error);
      // Code to run when the the form does not validate.
      if ($this->form_validation->run () == FALSE) {
        // Form does not validate.
        self::$data['view_file'] = 'user_edit';
        echo Modules::run ($this->template, self::$data);
      }
      // Code to run when the form passes validation.
      elseif ($this->form_validation->run () != FALSE) {
        // Encrypt password.
        $password = sha1 ($this->input->post('password'));
        // Code to run if this is an insert.
        if (!$this->input->post('id')) {

          // Instantiate User Entity and set its properties from the post
          // variable.

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
        // Code to run if this is an update.
        else {
          // Instantiate User Entity from the post('id').
          $found_user = $this->doctrine->em->find('Entities\User', $this->input->post('id'));
          // Check if account prtoected field is TRUE.  If so then redirect.
          if($found_user->getProtected() == TRUE) {
            $this->session->set_flashdata ('message_error', 'Unable to save.  Account is protected.');
            redirect (current_url ());
          }
          // Set user data.
          else {
            $found_user->setUsername($this->input->post('username'));
            $found_user->setPassword($password);
            $found_user->setEmail($this->input->post('email'));
            $found_user->setFirstName($this->input->post('first_name'));
            $found_user->setLastName($this->input->post('last_name'));
            $found_user->setRole('authenticated');
          }
        }
        // Doctrine flush() method or catch exception.
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
    // Code to run when user first visits the page without hitting submit.
    else {
      self::$data['view_file'] = 'user_edit';
      echo Modules::run ($this->template, self::$data);
    }
  }

  /**
   * Basic delete method.  For security reasons it does not take a $_GET
   * parameter.  It instead will only allow users to delete their own accounts
   * if they are logged in.
   */
  public function delete() {
    // Instanitate User Entity based on session userdata('user_id').
    if ($id = $this->session->userdata('user_id')) {
      $user = $this->doctrine->em->find('Entities\User', $id);
    }
    // Code to run when user hits the final delete button.
    if ($this->input->post('delete')) {
      // Check first if account is protected.  Redirect if true.
      if ($user->getProtected() == TRUE) {
        $this->session->set_flashdata ('message_error', 'Unable to delete.  Account is protected.');
        redirect (base_url () . 'user/edit/');
      }
      // Delete the account.
      else {
        try {
          $this->doctrine->em->remove($user);
          $this->doctrine->em->flush();
          $this->session->set_flashdata ('message_success', 'Your account has been deleted.');
          redirect (base_url () . 'user/logout');
        }
        catch (Exception $e) {
          $this->session->set_flashdata ('message_error', 'Unable to delete your account.  Please contact administrator.');
          redirect (base_url () . 'user/edit/');
        }
      }
    }
    // Redirect user if they come to this page without being logged in.
    elseif ($id == NULL) {
      redirect (base_url ());
    }
    // Code to run when logged in user first visits the page.
    else {
      self::$data['user'] = $user;
      self::$data['view_file'] = 'user_delete';
      echo Modules::run ($this->template, self::$data);
    }
  }
}

/* End of file user.php */
