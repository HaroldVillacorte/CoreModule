<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class _Core_pages extends MX_Controller
{

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * The template array.
     *
     * @var array
     */
    protected static $template_array;

    /**
     * The core_pages constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Set the data arrray.
        self::$data = $this->core_module_model->site_info();

        // Load the libraries.
        $this->load->library('core_pages_library');

        // Set the template array.
        self::$template_array = array(
            'template_name' => 'default_template/',
            'template_file' => 'default_template',
        );
    }

    /**
     * The index page.  Displays the default front page.
     */
    public function index()
    {
        // Set the content tempalte.
        self::$data['content_file'] = 'core_pages';

        // Get the page.
        self::$data['page'] = $this->core_pages_library->page_find('is_front', 2);

        // Render the page.
        $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

    public function page($slug = '')
    {
        // Set the content tempalte.
        self::$data['content_file'] = 'core_pages';

        // Get the page.
        self::$data['page'] = $this->core_pages_library->page_find('slug', $slug);

        // Render the page.
        $this->core_raintpl_library->render(self::$template_array, self::$data);
    }

}

/* End of file _core_pages.php */
