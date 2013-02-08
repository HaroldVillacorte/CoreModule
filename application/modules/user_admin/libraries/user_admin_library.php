<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class User_admin_library
{
    private static $CI;

    function __construct()
    {
        self::$CI = & get_instance();
        self::$CI->load->library('table');
        self::$CI->load->library('form_validation');
        self::$CI->load->library('pagination');
    }

    public function set_validation_rules($rules = '')
    {
        $role_insert = array(
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required|is_unique[roles.role]',
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required',
            ),
        );

        $role_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer',
            ),
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required',
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required',
            ),
        );

        $user_insert = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|is_unique[users.username]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]|matches[passconf]|sha1',
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmation',
                'rules' => 'required|trim|valid_base64|trim|max_length[12]',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]'
            ),
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required',
            ),
        );

        // Set the validation rules for updating a user.
        $user_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer',
            ),
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
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'required',
            ),
        );

        $rule_set = array();

        switch ($rules)
        {
            case 'role_insert':
                $rule_set = $role_insert;
                break;
            case 'role_update':
                $rule_set = $role_update;
                break;
            case 'user_insert':
                $rule_set = $user_insert;
                break;
            case 'user_update':
                $rule_set = $user_update;
                break;
        }

        self::$CI->form_validation->set_rules($rule_set);

        // Set password base64 error.
        $valid_base64_error = 'Password may only contain alpha-numeric characters, +\'s, and /\'s';
        self::$CI->form_validation->set_message('valid_base64', $valid_base64_error);
    }

    public function role_table($output = NULL)
    {
        // Table headings
        $add_link = base_url() . 'user_admin/add_role/';

        $heading = array(
            'ID', 'Role', 'Description', 'Protected',
            '<a href="' . $add_link . '" class="right">Add role +</a>',
        );

        self::$CI->table->set_heading($heading);

        // Table template
        $template = array(
            'table_open' => '<table width="100%">',
            'table_close' => '</table>',
        );

        self::$CI->table->set_template($template);

        foreach ($output as $key => $value)
        {   // add edit link.
            $output[$key]['edit'] =
                    '<a href="' . base_url() . 'user_admin/delete_role/'
                    . $output[$key]['id']
                    . '" class="label alert round right" style="margin-left:10px;"'
                    . 'onClick="return confirm(\'Are you sure?\')">Del</a>'
                    . '<a href="' . base_url()
                    . 'user_admin/edit_role/' . $output[$key]['id']
                    . '" class="label secondary round right">Edit</a>';

            if ($output[$key]['protected'])
            {
                $output[$key]['protected'] = 'Yes';
            }

            else
            {
                $output[$key]['protected'] = 'No';
            }
        }

        $role_table = self::$CI->table->generate($output);
        return $role_table;
    }

    public function user_page_pagination_setup($count, $per_page)
    {
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

        self::$CI->pagination->initialize($pagination_config);
        $links = self::$CI->pagination->create_links();

        return $links;
    }

    public function user_page_table_setup($output = NULL)
    {
        self::$CI->load->helper('date');

        // Table headings
        $add_link = base_url() . 'user_admin/add_user/';
        $heading = array(
            'ID', 'Username', 'Email', 'Role', 'Member since', 'Protected',
            '<a href="' . $add_link . '" class="right">Add user +</a>',
        );

        self::$CI->table->set_heading($heading);

        // Table template
        $template = array(
            'table_open' => '<table width="100%">',
            'table_close' => '</table>',
        );

        self::$CI->table->set_template($template);

        foreach ($output as $key => $value)
        {   // add edit link.
            unset($output[$key]['password']);

            $output[$key]['edit'] =
                    '<a href="' . base_url() . 'user_admin/delete/'
                    . $output[$key]['id']
                    . '" class="label alert round right" style="margin-left:10px;"'
                    . 'onClick="return confirm(\'Are you sure?\')">Del</a>'
                    . '<a href="' . base_url()
                    . 'user_admin/edit_user/' . $output[$key]['id']
                    . '" class="label secondary round right">Edit</a>';

            $output[$key]['created'] = unix_to_human($output[$key]['created']);

            if ($output[$key]['protected'])
            {
                $output[$key]['protected'] = 'Yes';
            }

            else
            {
                $output[$key]['protected'] = 'No';
            }
        }

        // Table render
        $table = self::$CI->table->generate($output);
        return $table;
    }

    public function check_user_protected($user = NULL)
    {
        if (($user->protected && self::$CI->session->userdata('role') != 'super_user') || $user->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', 'Unable to process.  User account is protected.');
            redirect(base_url() . 'user_admin/users/');
        }
    }

    public function check_role_protected($role = NULL)
    {
        if (($role->protected && self::$CI->session->userdata('role') != 'super_user') || $role->id == 1)
        {
            self::$CI->session->set_flashdata('message_error', 'Unable to process.  Role is protected');
            redirect(base_url() . 'user_admin/roles/');
        }
    }

}

/* End of file user_admin_library.php */
