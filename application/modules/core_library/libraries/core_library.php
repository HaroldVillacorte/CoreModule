<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class Core_library
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

}
/* End of file core_library.php */
