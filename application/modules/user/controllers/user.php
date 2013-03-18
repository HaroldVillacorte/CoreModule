<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Module
 *
 * The User module provides full user CRUD, authentication, and a very simple yet
 * highly effective permissions system.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class User extends MX_Controller
{

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * The template array sent to the Raintpl module.
     *
     * @var array
     */
    private static $template_array;

    /**
     * Store in the session to remember visited page.
     *
     * @var string
     */
    private static $user_page;

    public function __construct()
    {
        parent::__construct();

        // Load the config and language files.
        $this->config->load('_core_user/core_user_config');
        $this->lang->load('_core_user/core_user', $this->config->item('core_user_language'));

        // Load libraries.
        $this->load->library('_core_user/core_user_library');
        $this->load->library('_core_raintpl/core_raintpl_library');

        // Load helpers.
        $this->load->helper('date');
        $this->load->helper('form');

        // Check if a user is logged in.
        $this->core_user_library->user_check_logged_in();

        // Initialize the data array.
        self::$data = $this->core_module_model->site_info();

        // Initialize the template data array.
        self::$template_array = array(
            'template_name' => 'default_template/',
            'template_file' => 'default_template',
        );

        // Sets the module to be sent to the Template module.
        self::$data['module'] = 'user';

        // User admin remember paginated page.
        self::$user_page = NULL;
        if ($this->session->userdata('user_admin_page'))
        {
            self::$user_page = $this->session->userdata('user_admin_page');
        }
        self::$data['user_page'] = self::$user_page;

    }

    /**
     * The user profile page.
     */
    public function index()
    {
        self::$data['content_file'] = 'user_profile';

        // Checks if the user is logged in.  If not user is redirected to the
        // base_url().

        $id = $this->session->userdata('user_id');
        if (!$id)
        {
            redirect(base_url() . $this->core_user_library->user_login_uri);
        }
        $user = $this->core_user_library->user_find($id);
        self::$data['user'] = $user;

        // Render the page.
        echo $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

    /**
     * Basic login method.
     */
    public function login()
    {
        self::$data['content_file'] = 'user_login';
        self::$data['user_add_url'] = base_url() . $this->core_user_library->user_add_uri;
        self::$data['user_user_forgotten_password_url'] = base_url() . $this->core_user_library->user_forgotten_password_uri;
        //self::$data['content'] = $this->load->view('user_login', self::$data, TRUE);

        // Code to run when the user hits the Login button.
        if ($this->input->post('submit'))
        {
            // Sets the CI validation rules.
            $this->core_user_library->set_validation_rules('user_login');
            // Code to run form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            // Code to run when form validates.
            else
            {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $set_persistent_login = (bool) $this->input->post('set_persistent_login');
                $this->core_user_library->user_login($username, $password, $set_persistent_login);
            }
        }
        else
        {
            // Code to run when the user visits the page without hitting the Login
            // button.
            if ($this->session->userdata('user_id'))
            {
                $this->core_module_library->keep_flashdata_messages();
                redirect(base_url() . $this->core_user_library->user_index_uri);
            }

            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * User recovers password with email address.
     */
    public function forgotten_password()
    {
        self::$data['content_file'] = 'user_forgotten_password';

        if ($this->input->post('submit'))
        {
            $this->core_user_library->set_validation_rules('user_forgotten_password');

            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            else
            {
                $this->core_user_library-> user_forgotten_password($this->input->post('email'));
            }
        }

        // Render the page.
        echo $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

    public function forgotten_password_login($code = NULL)
    {
        $this->core_user_library->user_forgotten_password_login($code);
    }

    /**
     * Basic logout method using Codeigniter.
     */
    public function logout()
    {
        $this->core_user_library->user_logout();
    }

    /**
     * New user create account.
     */
    public function add()
    {
        self::$data['content_file'] = 'user_add';

        if ($this->input->post('add'))
        {
            $this->core_user_library->set_validation_rules('user_insert');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            else
            {
                // Form validation passed.
                $this->core_user_library->user_add($this->input->post());
            }
        }
        // Code to run when user first visits.
        else
        {
            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * User submits activation code from email link.
     *
     * @param string $activation_code
     */
    public function activate($activation_code = NULL)
    {
        if ($activation_code == NULL)
        {
            redirect(base_url() . $this->core_user_library->user_login_uri);
        }
        else
        {
            $this->core_user_library->user_activate($activation_code);
        }
    }

    /**
     * User edits own account.
     */
    public function edit()
    {
        self::$data['content_file'] = 'user_edit';

        // Check if a user is logged in then set the user id from the session.
        if (!$this->session->userdata('user_id'))
        {
            redirect(base_url() . $this->core_user_library->user_index_uri);
        }
        else
        {
            $id   = $this->session->userdata('user_id');
            $user = $this->core_user_library->user_find($id);
            self::$data['user'] = $user;
        }

        // When the user hits the delete button redirect them to the delete method.
        if ($this->input->post('delete'))
        {
            if ($this->session->userdata('user_id'))
            {
                redirect(base_url() . $this->core_user_library->user_delete_uri);
            }
        }

        // Code to run when the user hits the save button.
        if ($this->input->post('save'))
        {
            // Set the validation rules for updating a user.
            $this->core_user_library->set_validation_rules('user_update');

            // Code to run when the the form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_raintpl_library->render(self::$template_array, self::$data);
            }
            // Code to run when the form passes validation.
            else
            {
                $this->core_user_library->user_edit($user, $this->input->post());
            }
        }

        // Code to run when user first visits the page without hitting submit.
        else
        {
            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

    /**
     * User deletes own account.
     */
    public function delete()
    {
        self::$data['content_file'] = 'user_delete';

        // Instanitate User based on session userdata('user_id').
        if ($id = $this->session->userdata('user_id'))
        {
            $user = $this->core_user_library->user_find($id);
        }
        else
        {
            redirect(base_url());
        }

        // Code to run when user hits the final delete button.
        if ($this->input->post('delete'))
        {
            // Check first if account is protected.
            $this->core_user_library->user_check_protected($user, 'delete');

            // Delete.
            $this->core_user_library->user_delete($user);
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

            // Render the page.
            echo $this->core_raintpl_library->render(self::$template_array, self::$data);
        }
    }

}
/* End of file core_user.php */
