<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_module_library
{

    private static $CI;

    function __construct()
    {
        self::$CI = & get_instance();
        self::$CI->load->library('session');
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

}
/* End of file core_module_library.php */
