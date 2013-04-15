<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/**
 * The Core install controller.
 */
class Core_install extends MX_Controller
{

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * The Core install constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Get the routes array.
        require APPPATH . 'config/routes.php';
        $this->routes = $route;

        // Load the libraries.
        $this->load->library('form_validation');

        // Load the database classes.
        $this->load->dbutil();
        $this->load->database();

        // Load the helpers
        $this->load->helper('form');
        $this->load->helper('url');

        // The array of directories that should be writable.
        self::$data['directories'] = array($this->db->cachedir, FCPATH . 'asset_cache');

        // Check database connectivity.
        self::$data['db_connect']  = ($this->dbutil->database_exists($this->db->database));

        // Set the path to the schema file or false.
        $this->schema_file = APPPATH . 'modules/core_install/schema/CoreModule.sql';
        self::$data['schema'] = (file_exists($this->schema_file)) ? $this->schema_file : FALSE;

        // Check if the schema is installed.
        if (self::$data['db_connect'])
        {
            self::$data['installed'] = ($this->db->table_exists('core_pages'));
        }
        else
        {
            self::$data['installed'] = FALSE;
        }

        // Check that sessions are stored in the database.
        self::$data['session_db'] = $this->config->item('sess_use_database');

        // Check that the custom code in the routes files is uncommented.
        self::$data['custom_routing'] = (isset($this->routes['core_module_route_test']));
    }

    public function index()
    {
        if ($this->input->post('submit'))
        {
            $rules = array(
                array(
                    'field' => 'yes',
                    'label' => 'Yes',
                    'rules' => 'required|trim|matches[match]|xss_clean',
                ),
                array(
                    'field' => 'match',
                    'label' => 'Yes',
                    'rules' => 'required|trim|xss_clean',
                ),
            );

            $this->form_validation->set_rules($rules);

            if (!$this->form_validation->run())
            {
                $this->load->view('core_install', self::$data);
            }
            else
            {
                $restore = read_file($this->schema_file);

                $sql_clean = '';
                foreach (explode("\n", $restore) as $line){

                    if(isset($line[0]) && $line[0] != "#"){
                        $sql_clean .= $line."\n";
                    }

                }

                foreach (explode(";\n", $sql_clean) as $sql){

                    $sql = trim($sql);

                    if($sql)
                    {
                        $this->db->query($sql);
                    }
                }

                redirect(current_url());
            }
        }
        else
        {
            $this->load->view('core_install', self::$data);
        }
    }

}

/* End of file core_install.php */
