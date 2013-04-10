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

        // Set the data arrray.
        self::$data = $this->core_module_model->site_info();

        // Load the libraries.
        $this->load->library('core_module_library');

        // Set the template.
        $this->template = 'default_template/default_template';

        // Set the tempalte name array.
        self::$data['template_array'] = $this->core_template_library->get_template_names();
    }

    /**
     * The index page.  Displays the default front page.
     */
    public function index()
    {
        // Get the uri array.
        $uri_array = explode('/', $this->uri->uri_string());

        // Get the uri segment count.
        $segment_count = count($uri_array);

        // The index page.
        if ($segment_count == 1 && $uri_array[0] == '')
        {
            $page = $this->core_module_library->page_find('is_front', TRUE);
            $param = '';
        }
        // Single uri segment.
        elseif ($segment_count == 1 && $uri_array[0] != '')
        {
            $slug = $uri_array[0];
            $page = $this->core_module_library->page_find('slug', $slug);
            $param = '';
        }
        // Multiple uri segments.
        else
        {
            // Check if the page exists.
            if ($this->core_module_library->page_find('slug', $this->uri->uri_string()))
            {
                // If uri string is found in pages.
                $page = $this->core_module_library->page_find('slug', $this->uri->uri_string());
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
                $page = $this->core_module_library->page_find('slug', $slug);
            }
        }

        // Parse the page body.
        self::$data['body']  = ($page) ? $this->core_template_library->parse_string($page->body, self::$data, $param) : NULL;

        // Set the template.
        $template = ($page) ? $page->template : $this->template;

        // Render the page.
        echo $this->core_template_library->parse_template($template, self::$data);
    }

    /**
     * The adminstrtive page of all pages.
     */
    public function pages($page = 0)
    {
        $template = 'pages';

        // Load the libraries.
        $this->load->library('pagination');

        // Per page used for pagination and query.
        $per_page = 5;

        // Run the query.
        self::$data['pages'] = $this->core_module_library->page_find_limit_offset($per_page, $page, 'object');

        // Get the count.
        $count = count($this->core_module_library->page_find_all('array'));

        // Get the pagination links.
        $base_url = base_url() . $this->config->item('pages_uri');
        self::$data['pagination'] = pagination_setup($base_url, $count, $per_page);

        // Render the page.
        $this->load->view($template, self::$data);
    }

    /**
     * Add a page.
     */
    public function page_add()
    {
        // Set the permission.
        //$this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        $template = 'page_add';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_module_library->set_validation_rules('page_insert');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                $this->load->view($template, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_module_library->page_add($this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Render the page.
            $this->load->view($template, self::$data);
        }
    }

    /**
     * Edit a page.
     */
    public function page_edit($id = NULL)
    {
        // Set the permission.
        //$this->core_user_library->user_permission(array('admin', 'super_user'));

        // Set the content template file.
        $template = 'page_edit';

        // Post submit.
        if ($this->input->post('submit'))
        {
            // Set the validation rles.
            $this->core_module_library->set_validation_rules('page_update');

            // Form does not validate.
            if ($this->form_validation->run() == FALSE)
            {
                // Render the page.
                $this->load->view($template, self::$data);
            }
            // Form validates.
            else
            {
                // Send to the database.
                $this->core_module_library->page_edit($this->input->post());
            }
        }
        // First page visit.
        else
        {
            // Find page to edit.
            self::$data['page'] = $this->core_module_library->page_find('id', (int) $id);

            // Render the page.
            $this->load->view($template, self::$data);
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
        $this->core_module_library->page_delete($id);
    }

}

/* End of file core_module.php */
