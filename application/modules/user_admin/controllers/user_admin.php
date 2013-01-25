<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class User_Admin extends MX_Controller {

  protected static $data;
  protected static $user_page;

  function __construct () {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->module('user');
    $this->load->model('user_admin_model');
    $this->load->model('core_functions/core_functions');
    self::$data = $this->core_model->site_info();
    self::$data['module'] = 'user_admin';
    $this->user->permission('admin');

    self::$user_page = NULL;
    if ($this->session->userdata('user_admin_page')) {
      self::$user_page = $this->session->userdata('user_admin_page');
    }
    self::$data['user_page'] = self::$user_page;

  }

  public function index() {
    $this->session->keep_flashdata('message_success');
    $this->session->keep_flashdata('message_error');
    redirect(base_url() . 'user_admin/users/');
  }

  public function roles () {
    self::$data['view_file'] = 'roles';
    self::$data['roles'] = $this->user_admin_model->get_all_roles();
    echo Modules::run('core_template/default_template', self::$data);
  }

  public function edit_role() {
    self::$data['view_file'] = 'edit_role';
    if ($this->input->post('add')) {
      switch ($this->input->post('id')) {
        case TRUE:
          $rules = $this->user_admin_model->add_role_set_validation_rules ('update');
          $this->form_validation->set_rules($rules);
          if ($this->form_validation->run() == FALSE) {
            echo Modules::run('core_template/default_template', self::$data);
          }
          else {
            redirect(base_url() . 'user_admin/');
          }
          break;
        case FALSE:
          $rules = $this->user_admin_model->add_role_set_validation_rules ('insert');
          $this->form_validation->set_rules($rules);
          if ($this->form_validation->run() == FALSE) {
            echo Modules::run('core_template/default_template', self::$data);
          }
          else {
            $result = $this->user_admin_model->insert_role($this->input->post());
            if ($result) {
              $this->session->set_flashdata('message_success', 'Role successfully added.');
              redirect(base_url() . 'user_admin/roles/');
            }
            else {
              $this->session->set_flashdata('message_error', 'There was a problem adding the role.');
              redirect(current_url());
            }
          }
          break;
      }
    }
    else {
      echo Modules::run('core_template/default_template', self::$data);
    }
  }

  public function users($page = NULL) {
    $this->load->library('table');
    $this->load->library('pagination');

    // Perpage for pagination and Doctrine
    $per_page = 1;

    // Set start record for Doctrine.
    $start = 0;
    if ($page) {
      $start = $page;
    }
    // Doctrine
    $query1 = $this->user_admin_model->get_limit_offset($per_page, $start);
    $query2 = $this->user_admin_model->get_all_users();
    $count = count($query2->getArrayResult());
    $output = $query1->getArrayResult();
    // Get first and last id's.
    self::$data['first'] = $page + 1;
    self::$data['last'] = $page + count($output);

    // Pagination setup
    $pagination_config = $this->user_admin_model
            ->user_page_pagination_setup($count, $per_page);

    // Pagination render
    $this->pagination->initialize($pagination_config);
    self::$data['pagination_links'] = $this->pagination->create_links();

    // Generate table
    // Table headings
    $add_link = base_url() . 'user_admin/add_user/';
    $heading = array(
        'ID', 'Username', 'Email', 'First name',
        'Last name','Role', 'Meme since', 'Protected',
        '<a href="' . $add_link . '" class="right">Add user +</a>',
    );
    $this->table->set_heading($heading);

    // Table template
    $template = array (
        'table_open'  => '<table width="100%">',
        'table_close' => '</table>',
        );
    $this->table->set_template($template);

    // Table render
    $table_output = $this->user_admin_model->user_page_table_setup($output);

    self::$data['output'] = $this->table->generate($table_output);

    // Page render
    self::$data['count'] = $count;
    array_unshift(self::$data['scripts'], 'user_admin_ajax.js');
    // Check for ajax request then pick view_file.
    if ($this->input->is_ajax_request()) {
      // Set current page to session.
      $this->session->set_userdata(array('user_admin_page' => $page));
      $this->load->view('user_admin_ajax', self::$data);
    }
    else {
      // Set current page to session.
      $this->session->set_userdata(array('user_admin_page' => $page));
      self::$data['view_file'] = 'users';
      echo Modules::run('core_template/default_template', self::$data);
    }

  }

  public function edit_user ($id = NULL) {
    if ($id == NULL && !$this->input->post('save')) {
      redirect(base_url() . 'user_admin/');
    }
    elseif ($this->input->post('save')) {

      $rules = $this->user_admin_model-> add_edit_user_set_validation_rules('update');
      $this->form_validation->set_rules($rules);

      if ($this->form_validation->run() == FALSE) {
        self::$data['view_file'] = 'user_admin_edit_user';
        echo Modules::run('core_template/default_template', self::$data);
      }

      else {

        $user = $this->user_admin_model->find_user($this->input->post('id'));

        if ($user->getProtected()) {
          $this->session->set_flashdata('message_error', 'Unable to save.  User account is protected.');
          redirect(base_url() . 'user_admin/users/' . self::$user_page);
        }

        $result = $this->user_admin_model->save_user($this->input->post(), $user);

        switch ($result) {
          case TRUE:
            $this->session->set_flashdata('message_success', 'User was successfully saved.');
            redirect(current_url() . '/' . $this->input->post('id'));
            break;
          case FALSE:
            $this->session->set_flashdata('message_error', 'User could not be saved');
            redirect(current_url() . '/' . $this->input->post('id'));
            break;
        }
      }
    }
    else {
      $user = $this->doctrine->em->find('Entities\User', $id);
      array_unshift(self::$data['scripts'], 'user_admin_ajax.js');
      self::$data['user'] = $user;
      self::$data['view_file'] = 'user_admin_edit_user';
      echo Modules::run('core_template/default_template', self::$data);
    }
  }

  public function add_user () {
    if ($this->input->post('save')) {

      $rules = $this->user_admin_model-> add_edit_user_set_validation_rules('insert');
      $this->form_validation->set_rules($rules);
      $valid_base64_error = 'Password may only contain alpha-numeric characters, +\'s, and /\'s';
      $this->form_validation->set_message ('valid_base64', $valid_base64_error);

      if ($this->form_validation->run() == FALSE) {
        self::$data['view_file'] = 'user_admin_add_user';
        echo Modules::run('core_template/default_template', self::$data);
      }
      else {

        $result = $this->user_admin_model->save_user($this->input->post(), NULL);

        switch ($result) {
          case TRUE:
            $this->session->set_flashdata('message_success', 'User was successfully saved.');
            redirect(base_url() . 'user_admin/');
            break;
          case FALSE:
            $this->session->set_flashdata('message_error', 'User could not be saved');
            redirect(base_url() . 'user_admin/');
            break;
        }
      }
    }
    else {
      array_unshift(self::$data['scripts'], 'user_admin_ajax.js');
      self::$data['view_file'] = 'user_admin_add_user';
      echo Modules::run('core_template/default_template', self::$data);
    }
  }

  public function delete ($id = NULL) {
    if ($id == NULL) {
      redirect(base_url());
    }
    else {
      $result = $this->user_admin_model->delete_user($id);

      switch ($result) {
        case $result == 'protected':
          $this->session->set_flashdata('message_error', 'Unable to delete.  User account is protected.');
          redirect(base_url() . 'user_admin/users/' . self::$user_page);
          break;
        case $result == 'deleted':
          $this->session->set_flashdata('message_success', 'Record was successfully deleted.');
          redirect(base_url() . 'user_admin/users/' . self::$user_page);
          break;
        case $result == FALSE:
          $this->session->set_flashdata('message_error', 'Record could not be deleted.');
          redirect(base_url() . 'user_admin/users/' . self::$user_page);
          break;

      }
    }
  }

}

/* End of file user_admin.php */