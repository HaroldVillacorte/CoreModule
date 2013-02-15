<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_module_library
{

    private static $CI;

    function __construct()
    {
        self::$CI =& get_instance();

        // Load config and language.
        self::$CI->lang->load('core_module/core_module', 'english');

        // Load the libraries.
        self::$CI->load->library('session');
        self::$CI->load->library('form_validation');

        // Load the database class.
        self::$CI->load->database();
    }

    public function form_error_class($field)
    {
        $class = '';
        if (form_error($field))
        {
            $class = 'error';
        }
        return $class;
    }

    public function keep_flashdata_messages()
    {
        self::$CI->session->keep_flashdata('message_success');
        self::$CI->session->keep_flashdata('message_error');
        self::$CI->session->keep_flashdata('message_notice');
    }

    public function validate_sha1($str) {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
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
            if ((!isset($post_array[$key]) || empty($value) || $value == '' || $value == NULL) && $value !== 0)
            {
                unset($post_array[$key]);
            }

            if (is_string($value))
            {
                self::$CI->db->escape_str($value);
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
        $valid_base64_error = $field . self::$CI->lang->line('validation_valid_base64');
        self::$CI->form_validation->set_message('valid_base64', $valid_base64_error);
    }

}
/* End of file core_module_library.php */
