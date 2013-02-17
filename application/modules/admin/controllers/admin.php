<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin Controller Module
 *
 * Serves as a basic boilerplate for developing with CI Starter.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class Admin extends MX_Controller
{

    // Sets the $data property.
    protected static $data;
    // Optionally set the default template.
    protected $default_template = '_core_template/slider_template';
    // Optinally set another tmeplate property.
    protected $columns_template = '_core_template/default_template';

    /**
     * The data property is set to the site_info() array which passes an array
     * containing the site wide information such as the site name and asset path
     * information.  self::$data['module'] is the name of this module which is
     * passed to the Template module.
     *
     * @see core_model.php
     */
    public function __construct()
    {
        parent::__construct();

        // Load the libraries.
        $this->load->library('_core_module/core_module_library');
        $this->load->library('_core_email/core_email_library');

        // Sets the the data array.
        self::$data = $this->core_module_model->site_info();

        // Sets the module to be sent to the Template module.
        self::$data['module'] = 'admin';
    }

    /**
     * Javascript and css filenames are added to the script array from
     * core_model then the Asset Loader module will load these files.  Using
     * array_unshift adds the file to the begining of the array causing it to be
     * loaded first.  Alternatively you can use this method:
     * self::$data['scripts'][] = 'js_file_name.js'
     * to add the file to the end of the array causing it to be loaded last.
     *
     * @see core_model.php
     * @see asset_loader.php
     */
    public function index()
    {
        // Add filenames to the $scripts array.
        array_unshift(self::$data['scripts'], 'jquery.foundation.orbit.js');
        // Uncomment and edit to add a css file.
        /* array_unshift(self::$data['stylesheets'], 'CSS file GOES HERE'); */
        self::$data['view_file'] = 'grid';
        // Template name is sent to the Template module along with the data array.
        echo Modules::run($this->default_template, self::$data);
    }

    /**
     * The administrative email settings page.
     */
    public function email_settings()
    {
        self::$data['view_file'] = 'email/email_settings';

        if ($this->input->post('submit'))
        {
            $this->core_email_library->system_settings_set_validation_rules();

            if ($this->form_validation->run() == FALSE)
            {
                echo Modules::run($this->columns_template, self::$data);
            }
            else
            {
                $this->core_email_library->system_settings_set($this->input->post());
            }
        }
        elseif ($this->input->post('test'))
        {
            $this->core_email_library->system_email_test_send();
        }
        else
        {
            self::$data['settings'] = $this->core_email_library->system_settings_get(FALSE);
            echo Modules::run($this->columns_template, self::$data);
        }
    }

}
/* End of file admin.php */
