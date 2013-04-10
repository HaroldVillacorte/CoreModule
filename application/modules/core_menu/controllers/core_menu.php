<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Core_menu extends MX_Controller
{
    /**
     * The data array.
     *
     * @var array
     */
    private static $data = array();

    /**
     * The Core menu constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Set the data array.
        self::$data = $this->core_module_model->site_info();

        // Load the libraries.
        $this->load->library('core_menu_library');

        // Load the models.
        $this->load->model('core_menu_model');
    }

    /**
     * Load the menu.
     *
     * @param string $by
     * @param string $menu_identifier
     */
    public function index($parent_menu_id = NULL)
    {
        // Generate the menu data.
        self::$data['menu_data'] = $this->core_menu_library->generate_data($parent_menu_id);

        // Get the menu classes.
        $menu_classes = self::$data['menu_data']['menu']->menu_classes;

        // Serve the type of menu requested.
        switch ($menu_classes)
        {
            // If 'nav-bar' is found in the class string.
            case strstr($menu_classes, 'nav-bar'):
                echo $this->core_menu_library->generate_navbar(self::$data);
                break;

            // If 'top-bar' is found in the class string.
            case strstr($menu_classes, 'top-bar'):
                echo $this->core_menu_library->generate_topbar(self::$data);
                break;
        }
    }

}

/* End of file core_menu.php */
