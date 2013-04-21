<?php if (!defined('BASEPATH')) exit ('No direct script access allowed.');

/**
 * The Core admin front controller.
 */
class Core_admin_front extends MX_Controller
{
    /**
     * The data array.
     *
     * @var array
     */
    private static $data;


    /**
     * The Core admin front controller constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Initialize the module.
        self::$data = initialize_module('core_admin_front');

        $this->template = 'core_admin_front/core_admin_front';
    }

    public function index()
    {
        // Get all the admin level categories.
        self::$data['categories'] = $this->core_module_library->category_find_level(2, 'object');

        // Get the config array.
        self::$data['config'] = $this->config->config;

        // Set and unset config items.
        self::$data['config']['global_xss_filtering'] = (self::$data['config']['global_xss_filtering']) ?
            'Yes' : 'No';
        self::$data['config']['csrf_protection'] = (self::$data['config']['csrf_protection']) ?
            'Yes' : 'No';
        self::$data['config']['sess_encrypt_cookie'] = (self::$data['config']['sess_encrypt_cookie']) ?
            'Yes' : 'No';

        // Set the category properties.
        foreach (self::$data['categories'] as $category)
        {
            $category->name  = ucwords(str_replace('_', ' ', $category->name));
            $category->pages = $this->core_module_library->page_find('core_pages_admin', 'category', $category->id, 'object');
        }

        // Render the page.
        echo $this->core_template_library->parse_view($this->template, self::$data);
    }

}

/* End of file core_admin_front.php */
