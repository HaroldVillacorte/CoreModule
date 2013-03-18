<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class _Core_menu extends MX_Controller
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
    public function index($parent_menu_id = NULL, $template = NULL)
    {
        // Get the menu.
        self::$data['links'] = $this->core_menu_library->menu_link_find('parent_menu_id', $parent_menu_id, FALSE);

        // Parse front page link.
        foreach (self::$data['links'] as $link)
        {
            $link->link = ($link->link == '{front}') ? '' : $link->link;
        }

        // Set the tempalte file.
        self::$data['template'] = $template;

        // Load the view.
        $this->load->view('core_menu', self::$data);
    }

}

/* End of file _core_menu.php */
