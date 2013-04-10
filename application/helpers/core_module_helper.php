<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

$CI = & get_instance();

/**
 * Dynamically set the css class of a form field or label.
 *
 * @param string $field
 * @return string
 */
function form_error_class($field)
{
    $class = '';
    if (form_error($field))
    {
        $class = 'error';
    }
    return $class;
}

/**
 * Keep flashdata messages on redirect.
 */
function keep_flashdata_messages()
{
    global $CI;
    $CI->session->keep_flashdata('message_success');
    $CI->session->keep_flashdata('message_error');
    $CI->session->keep_flashdata('message_notice');
}

/**
 * Validate that a string is alphanumeric and 64 chars long.
 *
 * @param str $str
 * @return boolean
 */
function validate_alnum_64($str)
{
    return (bool) preg_match('/^[A-Za-z0-9-_\",\'\s]{64}$/i', $str);
}

/**
 * Sanitize the input post array and unset null values.
 *
 * @param array $post_array
 * @return array
 */
function prep_post($post_array = array())
{
    global $CI;

    foreach ($post_array as $key => $value)
    {
        if ((!isset($post_array[$key]) || $value == '') && $value !== 0)
        {
            unset($post_array[$key]);
        }

        if (is_string($value))
        {
            // Escape input.
            $post_array[$key] = $CI->db->escape_str($post_array[$key]);
        }
    }

    return $post_array;
}

/**
 * Set the valid base64 form_validation error message.
 *
 * @param string $field
 */
function set_valid_base_64_error($field = '')
{
    global $CI;

    $valid_base64_error = $field . lang('validation_valid_base64');
    $CI->form_validation->set_message('valid_base64', $valid_base64_error);
}

/**
 * Restrict direct access to modules that have a controller.
 *
 * @param string $controller_name
 */
function module_direct_access_restrict($controller_name = NULL)
{
    global $CI;

    if ($CI->uri->segment(1) == $controller_name)
    {
        redirect(base_url());
    }
}

/**
 * Generates pagination links.
 *
 * @param integer $count
 * @param integer $per_page
 * @return string
 */
function pagination_setup($base_url, $count, $per_page)
{
    global $CI;

    // Load the elibrary.
    $CI->load->library('pagination');

    $pagination_config = array();

    // Pagination setup
    $pagination_config['base_url']   = $base_url;
    $pagination_config['total_rows'] = $count;
    $pagination_config['per_page']   = $per_page;

    // Style pagination Foundation 3
    // Full open
    $pagination_config['full_tag_open'] = '<ul class="pagination">';

    // Digits
    $pagination_config['num_tag_open']  = '<li>';
    $pagination_config['num_tag_close'] = '</li>';

    // Current
    $pagination_config['cur_tag_open']  = '<li class="current"><a href="#">';
    $pagination_config['cur_tag_close'] = '</a></li>';

    // Previous link
    $pagination_config['prev_tag_open']  = '<li class="arrow">';
    $pagination_config['prev_tag_close'] = '</li>';

    // Next link
    $pagination_config['next_tag_open']  = '<li class="arrow">';
    $pagination_config['nect_tag_close'] = '<li>';

    // First link
    $pagination_config['first_tag_open']  = '<li>';
    $pagination_config['first_tag_close'] = '</li>';

    // Last link
    $pagination_config['last_tag_open']  = '<li>';
    $pagination_config['last_tag_close'] = '</li>';

    // Full close
    $pagination_config['full_tag_close'] = '</ul>';

    $CI->pagination->initialize($pagination_config);
    $links = $CI->pagination->create_links();

    return $links;
}

/**
 * Dynamically set the value of a form field.  The array indexes are object,
 * property, and field.  Using undefined index because of limitations in the
 * RainTPL templating engine.
 *
 * @param array $array
 * @return string
 */
function determine_form_value($array = array())
{
    $value = ($array[0]) ? $array[0]->$array[1] : set_value($array[2]);
    return $value;
}

/**
 * Get an array of uris for a module class.
 *
 * @param string $module
 * @return array
 */
function get_module_uris($module = NULL)
{
    // Load the module.
    // global $CI;
    // $CI->load->module($module . '/' . $module);
    // Get the method names.
    $methods = get_class_methods($module);

    // Generate the uri's.
    foreach ($methods as $key => $value)
    {
        if ($methods[$key] == '__construct' || $methods[$key] == '__get')
        {
            unset($methods[$key]);
        }
        $key           = $value;
        $methods[$key] = $module . '/' . $value . '/';
    }

    // Return array of uri's
    return $methods;
}

/**
 * Strip all white space form a string.
 *
 * @param string $string
 * @return string
 */
function strip_whitespace($string = NULL)
{
    return str_replace(' ', '', $string);
}

function setting_set($name = NULL, $setting = NULL)
{
    global $CI;
    $CI->load->model('core_module/core_module_model');
    return $CI->core_module_model->setting_set($name, $setting);
}

function setting_get($name = NULL)
{
    global $CI;
    $CI->load->model('core_module/core_module_model');
    return $CI->core_module_model->setting_get($name);
}

/* End of file core_module_heper.php */
