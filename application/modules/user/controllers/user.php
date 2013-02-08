<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Module
 *
 * The User module provides full user CRUD, authentication, and a very simple yet
 * highly effective permissions system.  This module uses Doctrine.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/ */
class User extends MX_Controller
{

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
    public function __construct()
    {
        parent::__construct();
        // Load the User library.
        $this->load->library('user_library');
        $this->user_library->check_logged_in();
        // Load the Core library.
        $this->load->library('core_library/core_library');
        // Sets the the data array.
        self::$data = $this->core_model->site_info();
        // Sets the module to be sent to the Template module.
        self::$data['module'] = 'user';
        // Load the User model
        $this->load->model('user_model');
        // Load the Date helper
        $this->load->helper('date');
    }

    /**
     * Redirects user to the profile page.  Currently the index method does not
     * work with this module and HMVC.
     */

    /**
     * The user profile page.
     */
    public function index()
    {
        // Checks if the user is logged in.  If not user is redirected to the
        // base_url().

        $id = $this->session->userdata('user_id');
        if (!$id)
        {
            redirect(base_url() . 'user/login/');
        }
        $user = $this->user_model->find_user($id);
        self::$data['user'] = $user;
        self::$data['view_file'] = 'user_profile';
        echo Modules::run($this->template, self::$data);
    }

    /**
     * Basic login method.
     */
    public function login()
    {
        // Code to run when the user hits the Login button.
        if ($this->input->post('submit'))
        {
            // Sets the CI validation rules.
            $this->user_library->set_validation_rules('login');
            // Code to run form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                self::$data['view_file'] = 'user_login';
                echo Modules::run('core_template/default_template', self::$data);
            }
            // Code to run when form validates.
            else
            {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $user     = $this->user_model->login($username, $password);
                if ($user)
                {
                    // Login success.
                    log_message('error', $username . ' logged in.');
                    $this->session->set_flashdata('message_success', 'You are now logged in as ' . $username . '.');
                    $this->user_library->set_user_session_data($user);

                    if ($this->input->post('set_persistent_login'))
                    {
                        $remember_code       = $this->user_library->set_persistent_login();
                        $store_remember_code = $this->user_model->store_remember_code($remember_code, $user->id);

                        if (!$store_remember_code)
                        {
                            $this->session->set_flashdata('message_notice', 'Persistent login failed.');
                            $this->user_library->unset_persistent_login();
                        }
                    }
                    redirect(base_url() . 'user/');
                }

                // Code to run if username and password combination is not found in the
                // database.
                else
                {
                    // Unseccessful login.
                    $this->session->set_flashdata('message_error', 'Username and password combination not found.');
                    redirect(current_url());
                }
            }
        }

        // Code to run when the user visits the page without hitting the Login
        // button.

        if ($this->session->userdata('user_id'))
        {
            $this->core_library->keep_flashdata_messages();
            redirect(base_url() . 'user/');
        }

        self::$data['view_file'] = 'user_login';
        echo Modules::run('core_template/default_template', self::$data);
    }

    /**
     * Basic logout method using Codeigniter.  All this does is unset all
     * userdata set by the login method then destroys the session.
     */
    public function logout()
    {
        $username = $this->session->userdata('username');
        $id       = $this->session->userdata('user_id');
        log_message('error', $username . ' logged out.');
        $result   = $this->user_model->delete_remember_code($id);

        if (!$result)
        {
            log_message('error', $username . ' delete remember code failed.');
        }

        $this->user_library->logout('user/login/');
    }

    public function add()
    {
        if ($this->input->post('add'))
        {
            $this->user_library->set_validation_rules('user_insert');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Form does not validate.
                self::$data['view_file'] = 'user_edit';
                echo Modules::run($this->template, self::$data);
            }
            else
            {
                $result_id = $this->user_model->add_user($this->input->post());

                switch ($result_id)
                {
                    case TRUE:
                        $this->session->set_flashdata('message_success', 'Acount was successfully created.');
                        redirect(base_url() . 'user/login/');
                        break;
                    case FALSE:
                        $this->session->set_flashdata('message_error', 'There was a problem adding your account.');
                        redirect(current_url());
                        break;
                }
            }
        }
        else
        {
            self::$data['view_file'] = 'user_add';
            echo Modules::run('core_template/default_template', self::$data);
        }
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
    public function edit()
    {
        if ($this->session->userdata('user_id'))
        {
            $id   = $this->session->userdata('user_id');
            $user = $this->user_model->find_user($id);
            self::$data['user'] = $user;
        }

        // When the user hits the delete button rediect them to the delete method.
        if ($this->input->post('delete'))
        {

            if ($this->session->userdata('user_id'))
            {
                redirect(base_url() . 'user/delete/');
            }
        }

        // Code to run when the user hits the save button.
        if ($this->input->post('save'))
        {
            // Set the validation rules for updating a user.
            $this->user_library->set_validation_rules('user_update');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Form does not validate.
                self::$data['view_file'] = 'user_edit';
                echo Modules::run($this->template, self::$data);
            }

            // Code to run when the form passes validation.
            else
            {
                $this->user_library->check_user_protected($user, 'edit');

                $result = $this->user_model->edit_user($this->input->post());

                switch ($result)
                {
                    case TRUE:
                        $this->session->set_flashdata('message_success', 'Account was successfully saved.');
                        redirect(base_url() . 'user/');
                        break;
                    case FALSE:
                        $this->session->set_flashdata('message_error', 'There was a problem adding your account.');
                        redirect(current_url());
                        break;
                }
            }
        }

        // Code to run when user first visits the page without hitting submit.
        else
        {
            self::$data['view_file'] = 'user_edit';
            echo Modules::run($this->template, self::$data);
        }
    }

    /**
     * Basic delete method.  For security reasons it does not take a $_GET
     * parameter.  It instead will only allow users to delete their own accounts
     * if they are logged in.
     */
    public function delete()
    {
        // Instanitate User based on session userdata('user_id').
        if ($id = $this->session->userdata('user_id'))
        {
            $user = $this->user_model->find_user($id);
        }
        else
        {
            redirect(base_url());
        }

        // Code to run when user hits the final delete button.
        if ($this->input->post('delete'))
        {
            // Check first if account is protected.
            $this->user_library->check_user_protected($user, 'delete');

            $result = $this->user_model->delete_user($id);

            switch ($result)
            {
                case TRUE:
                    redirect(base_url() . 'user/logout');
                    break;
                case FALSE:
                    $this->session
                        ->set_flashdata(
                            'message_error', 'Unable to delete your account.'
                            . '  Please contact administrator.'
                    );
                    redirect(base_url() . 'user/edit/');
                    break;
            }
        }

        // Redirect user if they come to this page without being logged in.
        elseif ($id == NULL)
        {
            redirect(base_url());
        }

        // Code to run when logged in user first visits the page.
        else
        {
            self::$data['user'] = $user;
            self::$data['view_file'] = 'user_delete';
            echo Modules::run($this->template, self::$data);
        }
    }

}
/* End of file user.php */
