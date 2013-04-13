<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_module_library
{

    /**
     * The CI super object.
     *
     * @var object
     */
    private static $CI;

    /**
     * The contructor for Core_module_library.
     */
    function __construct()
    {
        self::$CI =& get_instance();

        // Load config and language.'
        self::$CI->load->config('core_module/core_module_config');
        self::$CI->lang->load('core_module/core_module');

        // Load the libraries.
        self::$CI->load->library('form_validation');
        self::$CI->load->library('typography');

        // Load the helpers.
        self::$CI->load->helper('language');
        self::$CI->load->helper('date');

        // Load the models.
        self::$CI->load->model('core_module/core_module_model');

        // Set the uri's.
        $this->pages_uri             = self::$CI->config->item('pages_uri');
        $this->admin_pages_uri       = self::$CI->config->item('admin_pages_uri');
        $this->page_add_uri          = self::$CI->config->item('page_add_uri');
        $this->admin_page_add_uri    = self::$CI->config->item('admin_page_add_uri');
        $this->page_edit_uri         = self::$CI->config->item('page_edit_uri');
        $this->admin_page_edit_uri   = self::$CI->config->item('admin_page_edit_uri');
        $this->page_delete_uri       = self::$CI->config->item('page_delete_uri');
        $this->admin_page_delete_uri = self::$CI->config->item('admin_page_delete_uri');
    }

    /**
     * Set the form validation rules.
     *
     * @param string $rules
     */
    public function set_validation_rules($rules = NULL)
    {
        $page_insert = array(
            array(
                'field' => 'is_front',
                'label' => 'Is the front page',
                'rules' => 'integer|exact_length[1]|xss_clean'
            ),
            array(
                'field' => 'published',
                'label' => 'Published',
                'rules' => 'integer|exact_length[1]|xss_clean'
            ),
            array(
                'field' => 'slug',
                'label' => 'Slug',
                'rules' => 'required|trim|is_unique[core_pages.slug]|is_unique[core_pages_admin.slug]|xss_clean'
            ),
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'body',
                'label' => 'Body',
                'rules' => 'required|xss_clean'
            ),
            array(
                'field' => 'template',
                'label' => 'Template',
                'rules' => 'required|trim|xss_clean'
            ),
        );
        $page_update = array(
            array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => 'integer|xss_clean'
            ),
            array(
                'field' => 'is_front',
                'label' => 'Is the front page',
                'rules' => 'integer|exact_length[1]|xss_clean'
            ),
            array(
                'field' => 'published',
                'label' => 'Published',
                'rules' => 'integer|exact_length[1]|xss_clean'
            ),
            array(
                'field' => 'slug',
                'label' => 'Slug',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'body',
                'label' => 'Body',
                'rules' => 'required|xss_clean'
            ),
            array(
                'field' => 'template',
                'label' => 'Template',
                'rules' => 'required|trim|xss_clean'
            ),
        );

        $rule_set = '';

        switch ($rules)
        {
            case 'page_insert':
                $rule_set = $page_insert;
                break;
            case 'page_update':
                $rule_set = $page_update;
                break;
        }

        self::$CI->form_validation->set_rules($rule_set);
    }

    /**
     * Find a page.
     *
     * @param string $by
     * @param mixed $identifier
     * @return object
     */
    public function page_find($table = 'core_pages', $by = 'id', $identifier = NULL)
    {
        // Get the page.
        $page = self::$CI->core_module_model->page_find($table, $by, $identifier);

        // Prep data.
        if (isset($page->created))
        {
            $page->created = standard_date('DATE_RFC822', $page->created);
        }

        // Return result.
        return ($page) ? $page : FALSE;
    }

    /**
     * Find all pages.
     *
     * @return array
     */
    public function page_find_all($table = 'core_pages', $data_type = 'object')
    {
        // Get the page.
        $pages = self::$CI->core_module_model->page_find_all($table, $data_type);

        // Only id object is requested.
        if ($data_type == 'object')
        {
            // Prep data.
            foreach ($pages as $page)
            {
                // Convert boolean to text.
                $page->published = ($page->published) ? 'Yes' : 'No';

                // Fromat created tims stamp.
                $page->created   = unix_to_human($page->created);

                // If the page has been edited prep edited information.
                $page->last_edit = ($page->last_edit) ? unix_to_human($page->last_edit) : 'NONE';
                $page->last_editor = ($page->last_edit_username) ? $page->last_edit_username : 'NONE';
            }
        }

        // Return result.
        return ($pages) ? $pages : FALSE;
    }

    /**
     * Find all pages.
     *
     * @return array
     */
    public function page_find_limit_offset($table = 'core_pages', $limit = 1, $offset = 0, $data_type = 'object')
    {
        // Get the page.
        $pages = self::$CI->core_module_model->page_find_limit_offset($table, $limit, $offset, $data_type);

        // Prep data.
        if ($pages)
        {
            foreach ($pages as $page)
            {
                $page->published = ($page->published) ? 'Yes' : 'No';
                $page->created   = unix_to_human($page->created);
                $page->last_edit = ($page->last_edit) ? unix_to_human($page->last_edit) : 'NONE';
                $page->last_editor = ($page->last_edit_username) ? $page->last_edit_username : 'NONE';
            }
        }

        // Return result.
        return ($pages) ? $pages : FALSE;
    }

    /**
     * Add a page.
     *
     * @param array $post
     */
    public function page_add($table = 'core_pages', $post = array())
    {
        // Format the special characters in page body.
        $post['body'] = self::$CI->typography->format_characters($post['body']);

        // Send post to the model.
        $page_id = self::$CI->core_module_model->page_add($table, $post);

        // Insert failed.
        if (!$page_id)
        {
            self::$CI->session->set_flashdata('message_error', lang('page_add_failed'));
            redirect(current_url());
            exit();
        }
        // Insert success.
        else
        {
            self::$CI->session->set_flashdata('message_success', lang('page_add_success'));

            // Redirect to the edit page.
            redirect(current_url());
            exit();
        }
    }

    /**
     * Edit a page.
     *
     * @param array $post
     */
    public function page_edit($table = 'core_pages', $post = array())
    {
        // Format the special characters in page body.
        $post['body'] = self::$CI->typography->format_characters($post['body']);

        // Send post to the model.
        $result = self::$CI->core_module_model->page_edit($table, $post);

        // Insert failed.
        if (!$result)
        {
            self::$CI->session->set_flashdata('message_error', lang('page_edit_failed'));
            redirect(current_url());
            exit();
        }
        // Insert success.
        else
        {
            self::$CI->session->set_flashdata('message_success', lang('page_edit_success'));

            // Redirect to the edit page.
            redirect(current_url());
            exit();
        }
    }

    /**
     * Delete a page.
     *
     * @param integer $id
     */
    public function page_delete($table = 'core_pages', $id = NULL)
    {
        // Run the query.
        $result = self::$CI->core_module_model->page_delete($table, $id);

        switch ($result)
        {
            // Delete success.
            case TRUE:
                self::$CI->session->set_flashdata('message_success', lang('page_delete_success'));
                redirect(base_url() . $this->pages_uri);
                break;

            // Delete failure.
            case FALSE:
                self::$CI->session->set_flashdata('message_success', lang('page_delete_failed'));
                redirect(base_url() . $this->pages_uri);
                break;
        }

    }

}
/* End of file core_module_library.php */
