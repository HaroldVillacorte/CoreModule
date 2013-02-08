<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_admin extends MX_Controller
{

    private static $data;
    private static $user_page;
    private static $template = 'core_template/default_template';

    function __construct()
    {
        parent::__construct();
        $this->load->library('user_admin_library');

        // Check logged in.
        $this->load->library('user/user_library');
        $this->user_library->check_logged_in();

        $this->load->helper('form');
        $this->load->model('user_admin_model');
        $this->load->library('core_library/core_library');
        self::$data = $this->core_model->site_info();
        self::$data['module'] = 'user_admin';

        // Set the permission from the User module.
        $this->load->library('user/user_library');
        $this->user_library->permission(array('admin', 'super_user'));

        // Remember paginated page.
        self::$user_page = NULL;

        if ($this->session->userdata('user_admin_page'))
        {
            self::$user_page = $this->session->userdata('user_admin_page');
        }

        self::$data['user_page'] = self::$user_page;
    }

    public function index()
    {
        $this->session->keep_flashdata('message_success');
        $this->session->keep_flashdata('message_error');
        redirect(base_url() . 'user_admin/users/');
    }

    public function roles()
    {
        // Generate table
        $roles      = $this->user_admin_model->get_all_roles('array');
        $role_table = $this->user_admin_library->role_table($roles);

        self::$data['view_file'] = 'roles';
        self::$data['output'] = $role_table;
        echo Modules::run(self::$template, self::$data);
    }

    public function add_role()
    {
        self::$data['view_file'] = 'user_admin_add_role';

        if ($this->input->post('save'))
        {
            $this->user_admin_library->set_validation_rules('role_insert');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $result = $this->user_admin_model->save_role($this->input->post());

                if ($result)
                {
                    $this->session->set_flashdata('message_success', 'Role was successfully added.');
                    redirect(current_url());
                }
                else
                {
                    $this->session->set_flashdata('message_error', 'There was problem adding the role.');
                    redirect(current_url());
                }
            }
        }
        else
        {
            echo Modules::run(self::$template, self::$data);
        }
    }

    public function edit_role($id = NULL)
    {
        $role = $this->user_admin_model->get_role($id);
        self::$data['view_file'] = 'user_admin_edit_role';
        self::$data['role'] = ($role) ? $role : NULL;

        if ($this->input->post('save'))
        {
            $role = $this->user_admin_model->get_role($this->input->post('id'));
            $this->user_admin_library->check_role_protected($role);

            $this->user_admin_library->set_validation_rules('role_update');

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $result = $this->user_admin_model->save_role($this->input->post());

                if ($result)
                {
                    $this->session->set_flashdata('message_success', 'Role was successfully saved.');
                    redirect(base_url() . 'user_admin/roles/');
                }
                else
                {
                    $this->session->set_flashdata('message_error', 'There was a problem saving the role.');
                    redirect(current_url());
                }
            }
        }
        else
        {
            echo Modules::run(self::$template, self::$data);
        }
    }

    public function delete_role($id = NULL)
    {
        if (!$id) redirect(base_url() . 'user_admin/roles/');
        $role = $this->user_admin_model->get_role($id);
        $this->user_admin_library->check_role_protected($role);

        $result = $this->user_admin_model->delete_role($id);

        switch ($result)
        {
            case TRUE:
                $this->session->set_flashdata('message_success', 'Role was deleted.');
                redirect(base_url() . 'user_admin/roles/');
                break;
            case FALSE:
                $this->session->set_flashdata('message_error', 'Unable to delete role.');
                redirect(current_url());
                break;
        }
    }

    public function users($page = NULL)
    {
        // Per_page for pagination and model query.
        $per_page = 1;

        // Set start record for query.
        $start = 0;

        if ($page)
        {
            $start = $page;
        }

        // Database queries.
        $query1 = $this->user_admin_model->get_limit_offset_users($per_page, $start);
        $query2 = $this->user_admin_model->get_all_users();
        $count  = count($query2);
        $output = $query1;

        // Get first and last id's.
        self::$data['first'] = $page + 1;
        self::$data['last'] = $page + count($output);

        // Pagination setup
        $pagination_config = $this->user_admin_library
            ->user_page_pagination_setup($count, $per_page);

        // Table render
        $table_output = $this->user_admin_library->user_page_table_setup($output);

        // Page setup
        self::$data['pagination_links'] = $pagination_config;
        self::$data['output'] = $table_output;
        self::$data['count'] = $count;

        // Add the javascript.
        array_unshift(self::$data['scripts'], 'user_admin_ajax.js');

        // Check for ajax request then pick view_file.
        if ($this->input->is_ajax_request())
        {
            // Set current page to session.
            $this->session->set_userdata(array('user_admin_page' => $page));
            $this->load->view('user_admin_ajax', self::$data);
        }
        else
        {
            // Set current page to session.
            $this->session->set_userdata(array('user_admin_page' => $page));
            self::$data['view_file'] = 'users';
            echo Modules::run(self::$template, self::$data);
        }
    }

    public function edit_user($id = NULL)
    {
        self::$data['all_roles'] = $this->user_admin_model->get_all_roles('object');

        if ($id == NULL && !$this->input->post('save'))
        {
            redirect(base_url() . 'user_admin/');
        }
        elseif ($this->input->post('save'))
        {
            $this->user_admin_library->set_validation_rules('user_update');

            if ($this->form_validation->run() == FALSE)
            {
                self::$data['view_file'] = 'user_admin_edit_user';
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $id   = $this->input->post('id');
                $user = $this->user_admin_model->find_user($id)->row();
                $this->user_admin_library->check_user_protected($user);

                $result = $this->user_admin_model->save_user($this->input->post());

                switch ($result)
                {
                    case TRUE:
                        $this->session->set_flashdata('message_success', 'User was successfully saved.');
                        redirect(base_url() . 'user_admin/edit_user/' . $id);
                        break;
                    case FALSE:
                        $this->session->set_flashdata('message_error', 'User could not be saved');
                        redirect(base_url() . 'user_admin/edit_user/' . $id);
                        break;
                }
            }
        }
        else
        {
            $result = $this->user_admin_model->find_user((int) $id);
            $user   = $result->row();
            array_unshift(self::$data['scripts'], 'user_admin_ajax.js');
            self::$data['user'] = $user;
            self::$data['view_file'] = 'user_admin_edit_user';
            echo Modules::run(self::$template, self::$data);
        }
    }

    public function add_user()
    {
        self::$data['all_roles'] = $this->user_admin_model->get_all_roles('object');

        if ($this->input->post('save'))
        {
            $this->user_admin_library->set_validation_rules('user_insert');

            if ($this->form_validation->run() == FALSE)
            {
                self::$data['view_file'] = 'user_admin_add_user';
                echo Modules::run(self::$template, self::$data);
            }
            else
            {
                $result = $this->user_admin_model->save_user($this->input->post(), NULL);

                switch ($result)
                {
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
        else
        {
            array_unshift(self::$data['scripts'], 'user_admin_ajax.js');
            self::$data['view_file'] = 'user_admin_add_user';
            echo Modules::run(self::$template, self::$data);
        }
    }

    public function delete($id = NULL)
    {
        if ($id == NULL)
        {
            redirect(base_url());
        }
        else
        {
            $user   = $this->user_admin_model->find_user($id)->row();
            $this->user_admin_library->check_user_protected($user);
            $result = $this->user_admin_model->delete_user($id);

            switch ($result)
            {
                case $result == 'deleted':
                    $this->session->set_flashdata('message_success', 'Record was successfully deleted.');
                    redirect(base_url() . 'user_admin/users/');
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
