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

    function __construct()
    {
        self::$CI =& get_instance();

        // Load the Twig autoloader.
        include 'rain.tpl.class.php';

        // Load the config file.
        self::$CI->load->config('_core_raintpl/core_raintpl_config');

        // Instantiate the Tpl object.
        $this->raintpl = new RainTPL;

        // Raintpl main confifuration.
        $options_array = self::$CI->config->item('raintpl_configuration');
        RainTPL::configure($options_array);

        // Set the template directory.
        $this->template_directory = self::$CI->config->item('raintpl_template_directory');

        // Set the default template folder name.
        $this->template_name      = self::$CI->config->item('raintpl_template_name');

        // Set the default template file name.
        $this->template_file      = self::$CI->config->item('raintpl_template_file');

        // Set the Raintpl base url.
        RainTPL::configure('base_url', self::$CI->config->item('base_url'));

        // Finalize the location of the template files.
        RainTPL::configure('tpl_dir' ,$this->template_directory . $this->template_name);
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

            // Reconfigure the template location.  Neccessary for ajax requests.
            RainTPL::configure('tpl_dir' ,$this->template_directory . $this->template_name);
        }

        // Optionally set the template file name if not default.
        if (isset($options['template_file']))
        {
            // Set the template file name.
            $this->template_file = $options['template_file'];
        }

        // Add the CI super object to data array.
        $data['CI'] = self::$CI;

        // Set the data array.
        $this->raintpl->assign($data);

        // Return the rendered page.
        return $this->raintpl->draw($this->template_file);
    }

}

/* End of file core_raintpl_library.php */
