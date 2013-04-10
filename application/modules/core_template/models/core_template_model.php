<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/**
 * The Core tmeplate model.
 */
class Core_template_model extends CI_Model
{

    /**
     * The Core tempalte constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Load the helpers.
        $this->load->helper('directory');
    }

    /**
     * Get the names of the top level folders in the views directory.
     *
     * @return array
     */
    public function get_template_names()
    {
        // Get two level directory map of the views folder.
        $dir_map = directory_map(APPPATH . 'views', 2);

        foreach ($dir_map as $key => $value)
        {
            // Check if it is a directory.
            if (!is_dir(APPPATH . 'views/' . $key))
            {
                unset($dir_map[$key]);
            }
            else
            {
                // Loop through the directories.
                foreach ($dir_map[$key] as $k => $v)
                {
                    // Set a new array index for each php file.
                    if (!is_dir(APPPATH . 'views/' . $key . '/' . $v) && strstr($v, '.php'))
                    {
                        $dir_map[$k] = array();
                        $dir_map[$k]['name'] = $key;
                        $dir_map[$k]['file'] = str_replace('.php', '', $v);
                    }
                    // Unset the original indexes,
                    unset($dir_map[$key]);
                }
            }
        }

        // Return the array.
        return $dir_map;
    }

}

/* end of file core_template_model.php */
