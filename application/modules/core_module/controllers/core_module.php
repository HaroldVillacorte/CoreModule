<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/**
 * The Core pages controller.
 */
class Core_module extends MX_Controller
{

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * The core_pages constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Check if application is installed and ridirect if not.
        check_install();

        // Set up the module.
        self::$data = initialize_module('core_module');

        // Load the libraries.
        $this->load->library('core_module_library');

        // Set the templates.
        $this->template       = 'demo_template/demo_template';
        $this->admin_template = 'admin_template/admin_template';

        // Set the tempalte name array.
        self::$data['template_array'] = $this->core_template_library->get_template_names();

        // Set the back link.
        set_back_link();
    }

    /**
     * The index page.  Displays the default front page.
     */
    public function index()
    {
        $page = $this->core_module_library->page_find('core_pages', 'is_front', TRUE);

        // Parse the page body.
        self::$data['body']  = ($page) ? $this->core_template_library->parse_string($page->body, self::$data) : NULL;

        // Set the template.
        $template = ($page) ? $page->template : $this->template;

        // Render the page.
        echo $this->core_template_library->parse_template($template, self::$data);
    }

    public function page($slug = NULL)
    {
        // Restrict base access.
        if (!$slug)
        {
            redirect(base_url());
        }

        // Get the uri array.
        $uri_array = explode('/', $this->uri->uri_string());

        // Get the uri segment count.
        $segment_count = count($uri_array);

        // The index page.
        if ($segment_count == 1 && $uri_array[0] == '')
        {
            redirect(base_url());
            exit();
        }
        // Single uri segment.
        elseif ($segment_count == 1 && $uri_array[0] != '')
        {
            $slug = $uri_array[0];
            $page = $this->core_module_library->page_find('core_pages', 'slug', $slug);
            $param = '';
        }
        // Multiple uri segments.
        else
        {
            // Check if the page exists.
            if ($this->core_module_library->page_find('core_pages', 'slug', $this->uri->uri_string()))
            {
                // If uri string is found in pages.
                $page = $this->core_module_library->page_find('core_pages', 'slug', $this->uri->uri_string());
                $param = '';
            }
            else
            {
                // Uri string does not exist in database parse the uri.
                // Param is the last segment.
                $param = $uri_array[$segment_count - 1];

                // Then unset it.
                unset($uri_array[$segment_count - 1]);

                // Slug is the reminder of the uri array imploded.
                $slug = implode('/', $uri_array);
                $page = $this->core_module_library->page_find('core_pages', 'slug', $slug);
            }
        }

        // Parse the page body.
        self::$data['body']  = ($page) ? $this->core_template_library->parse_string($page->body, self::$data, $param) : NULL;

        // Set the template.
        $template = ($page) ? $page->template : $this->template;

        if ($this->input->is_ajax_request())
        {
            return self::$data['body'];
        }
        else
        {
            // Render the page.
            echo $this->core_template_library->parse_template($template, self::$data);
        }
    }

    /**
     * The index page.  Displays the default front page.
     */
    public function admin()
    {
        // Get the uri array.
        $uri_array = explode('/', $this->uri->uri_string());

        // Get the uri segment count.
        $segment_count = count($uri_array);

        // Single uri segment.
        if ($segment_count == 1 && $uri_array[0] != '')
        {
            $slug = $uri_array[0];
            $page = $this->core_module_library->page_find('core_pages_admin', 'slug', $slug);
            $param = '';
        }
        // Multiple uri segments.
        else
        {
            // Check if the page exists.
            if ($this->core_module_library->page_find('core_pages_admin', 'slug', $this->uri->uri_string()))
            {
                // If uri string is found in pages.
                $page = $this->core_module_library->page_find('core_pages_admin', 'slug', $this->uri->uri_string());
                $param = '';
            }
            else
            {
                // Uri string does not exist in database parse the uri.
                // Param is the last segment.
                $param = $uri_array[$segment_count - 1];

                // Then unset it.
                unset($uri_array[$segment_count - 1]);

                // Slug is the reminder of the uri array imploded.
                $slug = implode('/', $uri_array);
                $page = $this->core_module_library->page_find('core_pages_admin', 'slug', $slug);
            }
        }

        // Parse the page body.
        self::$data['body']  = ($page) ? $this->core_template_library->parse_string($page->body, self::$data, $param) : NULL;

        // Set the template.
        $template = ($page) ? $page->template : $this->admin_template;

        if ($this->input->is_ajax_request())
        {
            echo self::$data['body'];
        }
        else
        {
            // Render the page.
            echo $this->core_template_library->parse_view($template, self::$data);
        }
    }

    /**
     * The adminstrative page of all public pages.
     */
    public function pages($page = 0)
    {
        $template = 'core_module/pages';

        // Load the libraries.
        $this->load->library('pagination');

        // Per page used for pagination and query.
        $per_page = 10;

        // Run the query.
        self::$data['pages'] = $this->core_module_library->page_find_limit_offset('core_pages', $per_page, $page, 'object');

        // Get the count.
        $count = count($this->core_module_library->page_find_all('core_pages', 'array'));

        // Get the pagination links.
        $base_url = base_url() . $this->config->item('pages_uri');
        self::$data['pagination'] = pagination_setup($base_url, $count, $per_page, 2);

        // Render the page.
        echo $this->core_template_library->parse_view($template, self::$data);
    }

    /**
     * The adminstrative page of all admin pages.
     */
    public function admin_pages($page = 0)
    {
        $template = 'core_module/admin_pages';

        // Load the libraries.
        $this->load->library('pagination');

        // Per page used for pagination and query.
        $per_page = 10;

        // Run the query.
        self::$data['pages'] = $this->core_module_library->page_find_limit_offset('core_pages_admin', $per_page, $page, 'object');

        // Get the count.
        $count = count($this->core_module_library->page_find_all('core_pages_admin', 'array'));

        // Get the pagination links.
        $base_url = base_url() . $this->config->item('admin_pages_uri');
        self::$data['pagination'] = pagination_setup($base_url, $count, $per_page, 2);

        // Render the page.
        echo $this->core_template_library->parse_view($template, self::$data);
    }

    /**
     * Add a page.
     */
    public function page_add()
    {
        // Set the content template file.
        $template = 'core_module/page_add';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_module_library->set_validation_rules('page_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_template_library->parse_view($template, self::$data, FALSE);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_module_library->page_add('core_pages', $this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Render the page.
            echo $this->core_template_library->parse_view($template, self::$data, FALSE);
        }
    }

    /**
     * Add an admin page.
     */
    public function admin_page_add()
    {
        // Set the content template file.
        $template = 'core_module/page_add';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_module_library->set_validation_rules('page_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_template_library->parse_view($template, self::$data, FALSE);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_module_library->page_add('core_pages_admin', $this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Render the page.
            echo $this->core_template_library->parse_view($template, self::$data, FALSE);
        }
    }

    /**
     * Edit a page.
     */
    public function page_edit($id = NULL)
    {
        // Set the content template file.
        $template = 'core_module/page_edit';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_module_library->set_validation_rules('page_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_template_library->parse_view($template, self::$data, FALSE);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_module_library->page_edit('core_pages', $this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Find page to edit.
            self::$data['page'] = $this->core_module_library->page_find('core_pages', 'id', (int) $id);

            // Render the page.
            echo $this->core_template_library->parse_view($template, self::$data, FALSE);
        }
    }

    /**
     * Edit an admin page.
     */
    public function admin_page_edit($id = NULL)
    {
        // Set the content template file.
        $template = 'core_module/page_edit';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_module_library->set_validation_rules('page_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                echo $this->core_template_library->parse_view($template, self::$data, FALSE);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_module_library->page_edit('core_pages_admin', $this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Find page to edit.
            self::$data['page'] = $this->core_module_library->page_find('core_pages_admin', 'id', (int) $id);

            // Render the page.
            echo $this->core_template_library->parse_view($template, self::$data, FALSE);
        }
    }

    /**
     * Delete a page.
     *
     * @param integer $id
     */
    public function page_delete($id = NULL)
    {
        // Delete the page.
        $this->core_module_library->page_delete('core_pages', $id);
    }

    /**
     * Delete an admin page.
     *
     * @param integer $id
     */
    public function admin_page_delete($id = NULL)
    {
        // Delete the page.
        $this->core_module_library->page_delete('core_pages_admin', $id);
    }

}

/* End of file core_module.php */
