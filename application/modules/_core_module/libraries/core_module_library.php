<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_module_library
{

    /**
     * The CI super object.
     *
     * @var object
     */
    private static $CI;

    /**
     * The contructor for Core_module_library.
     */
    function __construct()
    {
        self::$CI =& get_instance();

        // Load config and language.
        self::$CI->lang->load('_core_module/core_module', 'english');

        // Load the libraries.
        self::$CI->load->library('form_validation');

        // Load the helpers.
        self::$CI->load->helper('language');

        // Load the database class.
        self::$CI->load->database();
    }

    /**
     * Dynamically set the css class of a form field or label.
     *
     * @param string $field
     * @return string
     */
    public function form_error_class($field)
    {
        $class = '';
        if (form_error($field))
        {
            $class = 'error';
        }
        return $class;
    }

    /**
     * Dynamically set the value of a form field.  The array indexes are object,
     * property, and field.  Using undefined index because of limitations in the
     * RainTPL templating engine.
     *
     * @param array $array
     * @return string
     */
    public function determine_form_value($array = array())
    {
        $value = ($array[0]) ? $array[0]->$array[1] : set_value($array[2]);
        return $value;
    }

    /**
     * Keep flashdata messages on redirect.
     */
    public function keep_flashdata_messages()
    {
        self::$CI->session->keep_flashdata('message_success');
        self::$CI->session->keep_flashdata('message_error');
        self::$CI->session->keep_flashdata('message_notice');
    }

    /**
     * Validate that a string is alphanumeric and 64 chars long.
     *
     * @param str $str
     * @return boolean
     */
    public function validate_alum_64($str) {
        return (bool) preg_match('/^[A-Za-z0-9-_\",\'\s]{64}$/i', $str);
    }

    /**
     * Sanitize the input post array and unset null values.
     *
     * @param array $post_array
     * @return array
     */
    public function prep_post($post_array = array())
    {
        foreach ($post_array as $key => $value)
        {
            if ((!isset($post_array[$key]) || $value == '') && $value !== 0)
            {
                unset($post_array[$key]);
            }

            if (is_string($value))
            {
                $post_array[$key] = self::$CI->db->escape_str($post_array[$key]);
            }
        }

        return $post_array;
    }

    /**
     * Set the valid base64 form_validation error message.
     *
     * @param string $field
     */
    public function set_valid_base_64_error($field = '')
    {
        $valid_base64_error = $field . lang('validation_valid_base64');
        self::$CI->form_validation->set_message('valid_base64', $valid_base64_error);
    }

    /**
     * Restrict direct access to modules that have a controller.
     *
     * @param string $controller_name
     */
    public function module_direct_access_restrict($controller_name = NULL)
    {
        if (self::$CI->uri->segment(1) == $controller_name)
        {
            redirect(base_url());
        }
    }

}
/* End of file core_module_library.php */
