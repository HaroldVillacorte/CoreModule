<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MX_Controller {

  protected static $data;
  protected $template = 'template/default_template';

  public function __construct()
  {
    parent::__construct();
    $this->load->library('encrypt');
    $this->load->library('form_validation');
    $this->load->model('user_model');
    self::$data = $this->default_model->site_info();
    self::$data['module'] = 'user';
  }

  public function index($id)
  {
    redirect(base_url() . 'user/profile');
  }

  public function profile($id) {

    if (!isset($id)) {
      redirect(base_url());
    }
    $user = $this->user_model->get_user_by_id($id);
    self::$data['user'] = $user->row();
    self::$data['view_file'] = 'user_profile';
    echo Modules::run($this->template, self::$data);
  }
  
  public function permission($role)
  {
  	if ($this->session->userdata('role')) {
  	  $user_role = $this->session->userdata('role');
  	}
  	else {
  	  $user_role = '';
  	}
  	if ($role != $user_role) {  		
  		if ($this->session->userdata('user_id')) {
  			$message = 'That page requires ' . $role . ' permission. Please login as admin.';
  			$this->session->set_flashdata('message_error', $message);
  		  redirect(base_url() . 'user/profile/' . $this->session->userdata('user_id'));
  		}
  		else {
  			$message = 'That page requires ' . $role . ' permission. Please login.';
  			$this->session->set_flashdata('message_error', $message);
  			redirect(base_url() . 'user/login');
  		}
  	}
  }

  public function login()
  {
  	if (isset($_POST['submit'])) {
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
  	  $this->form_validation->set_rules($rules);
  	  if ($this->form_validation->run() == FALSE) {
  	  	// Form does not validate.
  	    self::$data['view_file'] = 'user_login';
  	    echo Modules::run('template/default_template', self::$data);
  	  }
  	  else {
  	    // Encrypt password and send to database.
  	  	$_POST['password'] = sha1($_POST['password']);
  	    if ($this->user_model->login($_POST)) {
  	    	// Login success.
  	    	$this->session->set_flashdata('message_success', 'You are now logged in as ' . $_POST['username'] . '.');
  	      redirect(base_url());
  	    }
  	    else {
  	    	// Unseccessful login.
  	    	$this->session->set_flashdata('message_error', 'Username and password combination not found.');
  	    	redirect(current_url());
  	    }
  	  }
  	}
    self::$data['view_file'] = 'user_login';
    echo Modules::run('template/default_template', self::$data);
  }
  
  public function logout() {
  	$userarray = array('user_id', 'username', 'password', 'email', 'first_name', 'last_name', 'role',);
  	$this->session->unset_userdata($userarray);
  	$this->session->sess_destroy();
  	redirect(base_url());
  }

  public function crud()
  {
    if (isset($_POST['delete'])) {
      if (isset($_POST['id'])) {
        redirect(base_url() . 'user/delete/' . $_POST['id']);
      };
    }
    if (isset($_POST['submit'])) {
      $rules_insert = array(
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
          'rules' => 'required|valid_email|is_unique[user.email]'
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
      if (isset($_POST['id'])) {
        $this->form_validation->set_rules($rules_update);
      }
      else {
        $this->form_validation->set_rules($rules_insert);
      }
      $valid_base64_error = 'Password may only contain alpha-numeric characters, +\'s, and /\'s';
      $this->form_validation->set_message('valid_base64', $valid_base64_error);
      if ($this->form_validation->run() == FALSE) {
        // Form does not validate.
        self::$data['view_file'] = 'user_crud';
        echo Modules::run($this->template, self::$data);
      }
      elseif ($this->form_validation->run() != FALSE) {
        // Encrypt password and send to database.
        $_POST['password'] = sha1($_POST['password']);
        $_POST['role'] = 'authenticated';
        // See user_model.php.  Save user method returns $id as opposed to boolean.
        if ($result = $this->user_model->save_user($_POST)) {
        	if ($result == 'updated') {
        	  $this->session->set_flashdata('message_success', $_POST['username'] . ' saved succesfully.');
            redirect(current_url());
        	}
        	elseif ($result == 'inserted') {
        		$this->session->set_flashdata('message_success', $_POST['username'] . ' saved succesfully.');
        		redirect(base_url() . 'user/login');
        	}
        	elseif ($result_id = $this->user_model->save_user($_POST) == 'protected') {
        		$this->session->set_flashdata('message_error', 'Unable to save.  Account is protected.');
        		redirect(current_url());
        	}
        	else {
            $this->session->set_flashdata('message_error', 'Unable to save user. Please contact administrator.');
            redirect(current_url());
          }
        }        
      }
    }
    else {
      // Load page without $_POST['submit'].
      if ($id = $this->session->userdata('user_id')) {
        if ($result = $this->user_model->get_user_by_id($id)) {
          self::$data['user'] = $result->row();
        };
      }
      self::$data['view_file'] = 'user_crud';
      echo Modules::run($this->template, self::$data);
    }
  }
	
  public function delete($id = NULL) {
    if (isset($_POST['delete'])) {
      if ($this->user_model->delete_user($_POST['id']) == 'success') {
        $this->session->set_flashdata('message_success', 'Your account has been deleted.');
        redirect(base_url() . 'user/logout');
      }
      elseif ($this->user_model->delete_user($_POST['id']) == 'protected') {
      	$this->session->set_flashdata('message_error', 'Unable to delete.  Account is protected.');
      	redirect(base_url() . 'user/crud') ;
      }
      else {
        $this->session->set_flashdata('message_error', 'Unable to delete your account.  Please contact administrator.');
        redirect(base_url());
      }
    }
    elseif ($id == NULL) {
      redirect(base_url());
    }
    else {
      if ($result = $this->user_model->get_user_by_id($id)) {
        $user = $result->row();
      }
      self::$data['user'] = $user;
      self::$data['view_file'] = 'user_delete';
      echo Modules::run($this->template, self::$data);
    }
  }
}

/* End of file user.php */