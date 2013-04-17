<?php if (!defined('BASEPATH')) exit ('No direct script access allowed.');

/**
 * The Core template library.
 */
class Core_template_library
{
    /**
     * The CI super object.
     *
     * @var object.
     */
    private static $CI;

    /**
     * The Core template library constructor.
     */
    function __construct()
    {
        // Instantiate the super object.
        self::$CI =& get_instance();

        // Load the libaries.
        self::$CI->load->library('parser');

        // Load the models.
        self::$CI->load->model('core_template/core_template_model');

        // Set the public asset cache.
        $this->asset_cache  = FCPATH . 'asset_cache/';

    }

    /**
     * Load a view file.
     *
     * @param string $view_file
     * @param array $data
     * @param boolean $cache_assets
     * @return string
     */
    public function parse_view($view_file = NULL, $data = array(), $cache_assets = TRUE)
    {
        // Initial process.
        $array = $this->process_parse($view_file, $data, $cache_assets);

        // Set the asset variable.
        $data['asset'] = $array['asset'];


        // Return the rendered view.
        return self::$CI->load->view($view_file, $data, TRUE);
    }

    /**
     * Load a template file.
     *
     * @param string $view_file
     * @param array $data
     * @param boolean $cache_assets
     * @return string
     */
    public function parse_template($view_file = NULL, $data = array(), $cache_assets = TRUE)
    {
        // Initial process.
        $array = $this->process_parse($view_file, $data, $cache_assets);

        // Set the asset variable.
        $data['asset'] = $array['asset'];

        $string = self::$CI->parser->parse($view_file, $data, TRUE);

        // Get the string.
        $string = $this->parse_tags($string);

        // Return the parsed string.
        return $string;
    }

    /**
     * Parse a string.
     *
     * @param string $string
     * @param array $data
     * @param boolean $cache_assets
     * @return type
     */
    public function parse_string($string = '', $data = array(), $param = NULL)
    {
        // First parse the tags.
        $string = $this->parse_tags($string, $param);

        // Return the parsed string.
        return self::$CI->parser->parse_string($string, $data, TRUE);
    }

    /**
     * Cache assets and set process variables.
     *
     * @param string $view_file
     * @param array $data
     * @param boolean $cache_assets
     * @return string
     */
    public function process_parse($view_file = NULL, $data = array(), $cache_assets = TRUE)
    {
        // Start an empty array.
        $array = array();

        // Parse the template name.
        $array['template'] = $this->get_template_name($view_file);

        // Set the asset directory variable available to the views.
        $array['asset'] = base_url() . 'asset_cache/' . $array['template']['name'] . '/';

        if (is_dir(APPPATH . 'modules/' . $array['template']['name'] . '/assets/'))
        {
            // Set the asset directory of the template.
            $array['asset_directory'] = APPPATH . 'modules/' . $array['template']['name'] . '/assets/';
        }
        else
        {
            // Set the asset directory of the template.
            $array['asset_directory'] = APPPATH . 'views/' . $array['template']['name'] . '/assets/';
        }

        // Write template assets to asset cache if design mode enabled.
        if (variable_get('core_module_design_mode'))
        {
            // Check if $cache_assets is set to false.
            if ($cache_assets)
            {
                // Copy the asset files to the public folder.
                $this->cache_assets($array['asset_directory'],  $array['template']['name']);
            }
        }

        // Return the new array.
        return $array;
    }

    /**
     * Parse the Core module special tags.
     *
     * @param string $string
     * @return string
     */
    public function parse_tags($string = NULL, $param = NULL)
    {
        // Set the regex's to an array for future code maintainability.
        $regex = array();
        $regex['module'] = '/({%module:)([^(%})])+(%})/';

        // Process the string.
        $string = $this->process_module($regex['module'], $string, $param);

        // Return the string.
        return $string;
    }

    /**
     * Extract modules from the string.
     *
     * @param string $regex
     * @param string $string
     * @return string
     */
    public function process_module($regex = NULL, $string = NULL, $param = NULL)
    {
        // Set the array of matches.
        preg_match_all($regex, $string, $array);
        $matches_array = $array[0];

        // Ceheck result and process.
        if (count($matches_array) > 0)
        {
            // Strip the outer shell.
            $matches = array();
            foreach ($matches_array as $match)
            {
                $matches[] = str_replace(array('{', 'module:','%','}'), '', $match);
            }

            // Process the innards.
            $result_array = array();
            foreach ($matches as $match)
            {
                // Separate the module name from the params.
                $module_array = explode('|', $match);

                // Check if there are params and set them.
                if (isset($module_array[1]))
                {
                    $parameters = (isset($module_array[1])) ? $module_array[1] : NULL;
                }
                else
                {
                    $parameters = ($param) ? $param : NULL;
                }

                // Add each run module to the result array.
                $result_array[] = Modules::run($module_array[0], $parameters);
            }

            // Combine the result with the array of matches.
            $replacement_array = array_combine($matches_array, $result_array);

            // Iterate through combined array and replace in the string.
            foreach ($replacement_array as $match => $replacement)
            {
                $string = str_replace($match, $replacement, $string);
            }
        }

        // Return the processed string.
        return $string;
    }

    /**
     * Parse the template name.
     *
     * @param string $view_file
     * @return aray
     */
    public function get_template_name($view_file = NULL)
    {
        // Initialize the array.
        $view_array = explode('/', $view_file);

        // $file is the last array element.
        $file = end($view_array);

        // Return name array.
        return array('name' => $view_array[0], 'file' => $file);
    }

    /**
     * Currently not in use.  Will make use of.
     *
     * @param string $string
     * @return strnig
     */
    public function escape_output($string = NULL)
    {
        // Strip disallowed tags.
        $string = strip_tags($string, variable_get('core_module_allowed_tags'));

        // nl2br
        $post_array[$key] = str_replace(array("\r\n", "\r", "\n"), "<br>", $post_array[$key]);

        // Remove all tag attributes.
        $string = preg_replace('/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i', '<$1$2>', $string);

        return $string;
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

        // Write the files.
        if ($source_map)
        {
            foreach ($source_map as $value)
            {
                // Set source filename.
                $file_source = $value['server_path'];

                // Set date of source file.
                $file_source_date = $value['date'];

                // Set destination filename.
                $file = preg_replace('/.*assets\//', '', $file_source);
                $filename = $this->asset_cache . $template_name . '/' . $file;

                // Write directories if not exists.
                $file_dir = str_replace($value['name'], '', $file);
                $new_dir = $this->asset_cache . $template_name . '/' . $file_dir;
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

        // Check for files and delete.
        if ($asset_cache_map)
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

    /**
     * Get the names of the top level folders in the views directory.
     *
     * @return array
     */
    public function get_template_names()
    {
        // Return the one level directory map of the views folder.
        return self::$CI->core_template_model->get_template_names();
    }

}

/* End of file core_template_library.php */
