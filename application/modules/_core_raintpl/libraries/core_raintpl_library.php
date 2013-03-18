<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Core_raintpl_library
{
    /**
     * The CI super object.
     *
     * @var object
     */
    private static $CI;

    /**
     * The main templates directory.
     *
     * @var string
     */
    public $template_directory;

    /**
     * The modules templates directory.
     *
     * @var string
     */
    public $module_directory;

    /**
     * The directory of the specific template.
     *
     * @var string
     */
    public $template_name;

    /**
     * The name of the template file.
     * @var string
     */
    public $template_file;

    /**
     * The twig environment object.
     *
     * @var object
     */
    public $raintpl;

    /**
     * The directory of the assets for a template package.
     *
     * @var string
     */
    public $asset_directory;

    /**
     * The public read/write directory to copy template assets to.
     *
     * @var string
     */
    public $asset_cache;

    function __construct()
    {
        self::$CI =& get_instance();

        // Load the Twig autoloader.
        include 'ext_raintpl.php';

        // Load the config file.
        self::$CI->load->config('_core_raintpl/core_raintpl_config');

        // Instantiate the Tpl object.
        $this->raintpl = new Ext_raintpl;

        // Raintpl main confifuration.
        $options_array = self::$CI->config->item('raintpl_configuration');
        Ext_raintpl::configure($options_array);

        // Set the template directory.
        $this->template_directory = self::$CI->config->item('raintpl_template_directory');

        // Set the default template folder name.
        $this->template_name      = self::$CI->config->item('raintpl_template_name');

        // Set the default template file name.
        $this->template_file      = self::$CI->config->item('raintpl_template_file');

        // Set the asset cache.
        $this->asset_cache = self::$CI->config->item('raintpl_asset_cache');
    }

    /**
     * Render the page.
     *
     * @param array $options
     * @return string
     */
    public function render($options = array(), $data = array())
    {
        // Optionally set the template name if not default.
        if (isset($options['template_name']))
        {
            $this->template_name = $options['template_name'];
        }

        // Optionally set the template file name if not default.
        if (isset($options['template_file']))
        {
            // Set the template file name.
            $this->template_file = $options['template_file'];
        }

        // Configure the template location.  Neccessary for ajax requests.
        Ext_raintpl::configure('tpl_dir' , $this->template_directory . $this->template_name);

        // Configure the Raintpl base url.
        Ext_raintpl::configure('base_url', self::$CI->config->item('base_url') . 'asset_cache/' . $options['template_name']);

        // Set the assets directory.
        $this->asset_directory = $this->template_directory . $this->template_name . 'assets/';

        // Write template assets to asset cache if design mode enbled and is not an
        // ajax request.
        if (self::$CI->config->item('raintpl_design_mode') && !self::$CI->input->is_ajax_request())
        {
            $this->cache_assets($this->asset_directory, $this->template_name);
        }

        // Set the menus directory.
        $this->menu_template_directory = $this->template_directory . $this->template_name . 'menus/';

        // Add the CI super object to data array.
        $data['CI'] = self::$CI;

        // Set the data array.
        $this->raintpl->assign($data);

        // Return the rendered page.
        return $this->raintpl->draw($this->template_file);
    }

    /**
     * Write template assets to public directory.
     *
     * @param string $directory
     * @param string $template_name
     */
    public function cache_assets($directory = NULL, $template_name = NULL)
    {
        // Load the file and directory helpers.
        self::$CI->load->helper('file');
        self::$CI->load->helper('directory');

        // Set the source directory map array.
        $source_map = get_dir_file_info($directory, FALSE);

        // Set asset count.
        $asset_count = count($source_map);

        // Write the files.
        if ($asset_count > 0)
        {
            foreach ($source_map as $value)
            {
                // Set source filename.
                $file_source = $value['server_path'];

                // Set date of source file.
                $file_source_date = $value['date'];

                // Set destination filename.
                $file = preg_replace('/.*assets\//', '', $file_source);
                $filename = $this->asset_cache . $template_name . $file;

                // Write directories if not exists.
                $file_dir = str_replace($value['name'], '', $file);
                $new_dir = $this->asset_cache . $template_name . $file_dir;
                if (!is_dir($new_dir))
                {
                    mkdir($new_dir, 0777, TRUE);
                }

                // Write file if it does not exist.
                if (!file_exists($filename))
                {
                    copy($file_source, $filename);
                }
                // If file already exists.
                elseif (file_exists($filename))
                {
                    // Check if file has been updated and overwrite.
                    if ($file_source_date > filemtime($filename))
                    {
                        copy($file_source, $filename);
                    }
                }
            }
        }

        // Check for files that have been deleted form the template assets folder and
        // delete them from the asset cache directory.

        // Set the asset cache directory map array.
        $asset_cache_map = get_dir_file_info($this->asset_cache . $template_name, FALSE);

        // Set the asset cache array count.
        $asset_cache_map_count = count($asset_cache_map);

        // Check for files and delete.
        if ($asset_cache_map_count > 0)
        {
            foreach ($asset_cache_map as $key => $value)
            {
                if (!array_key_exists($key, $source_map))
                {
                    unlink($asset_cache_map[$key]['server_path']);
                }
            }
        }
    }

}

/* End of file core_raintpl_library.php */
