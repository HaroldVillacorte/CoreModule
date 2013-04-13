<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

/**
 * Make a module a Core module module.
 *
 * @global object $CI
 * @param string $module
 * @return array
 */
function initialize_module($module = NULL)
{
    $CI =& get_instance();

    // Load the core libraries.
    $libraries = array('core_module/core_module_library', 'core_user/core_user_library',
        'core_template/core_template_library');
    $CI->load->library($libraries);

    // Restrict direct module access.
    module_direct_access_restrict($module);

    // Do the check logged in.
    $CI->core_user_library->user_check_logged_in();

    // Set the data array.
    $data = $CI->core_module_model->site_info();

    // Turn profiler on when setting is set to TRUE.
    //$CI->output->enable_profiler(TRUE);

    // Return the data array.
    return $data;
}

/**
 * Restrict direct access to modules that have a controller.
 *
 * @param string $controller_name
 */
function module_direct_access_restrict($controller_name = NULL)
{
    $CI =& get_instance();

    if ($CI->uri->segment(1) == $controller_name)
    {
        redirect(base_url());
        exit();
    }
}

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
    $CI =& get_instance();
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
    $CI =& get_instance();

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
    $CI =& get_instance();

    $valid_base64_error = $field . lang('validation_valid_base64');
    $CI->form_validation->set_message('valid_base64', $valid_base64_error);
}

/**
 * Generates pagination links.
 *
 * @param integer $count
 * @param integer $per_page
 * @return string
 */
function pagination_setup($base_url, $count, $per_page, $uri_segment = 2)
{
    $CI =& get_instance();

    // Load the elibrary.
    $CI->load->library('pagination');

    $pagination_config = array();

    // Pagination setup
    $pagination_config['base_url']   = $base_url;
    $pagination_config['total_rows'] = $count;
    $pagination_config['per_page']   = $per_page;
    $pagination_config['uri_segment']   = $uri_segment;

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
 * Strip all white space form a string.
 *
 * @param string $string
 * @return string
 */
function strip_whitespace($string = NULL)
{
    return str_replace(' ', '', $string);
}

/**
 * Set appication settings.
 *
 * @param string $name
 * @param type $setting
 * @return type
 */
function setting_set($name = NULL, $setting = NULL)
{
    $CI =& get_instance();
    $CI->load->model('core_module/core_module_model');
    return $CI->core_module_model->setting_set($name, $setting);
}

/**
 * Get application settings.
 *
 * @param type $name
 * @return type
 */
function setting_get($name = NULL)
{
    $CI =& get_instance();
    $CI->load->model('core_module/core_module_model');
    return $CI->core_module_model->setting_get($name);
}

/**
 * Set an array of settings.
 *
 * @param array $post
 */
function process_settings($post = array())
{
    $CI =& get_instance();

    // Loop through post array.
    foreach ($post as $key => $value)
    {
        // Send to the database.
        $result = setting_set($key, $value);
        if (!$result)
        {
            // Failed to set.
            $CI->session->set_flashdata('message_error', lang('error_setting set') . ucfirst($key));
            redirect(current_url());
        }
    }
    // Set successfully.
    $CI->session->set_flashdata('message_success', lang('success_setting set'));
    redirect(current_url());
}

/**
 * Set server referer to the session.
 */
function set_back_link()
{
    $CI =& get_instance();
    $referer = $CI->input->server('HTTP_REFERER');
    $CI->session->set_userdata('back_link', $referer);
}

/**
 * Get server referer from the session.
 * @return string
 */
function get_back_link()
{
    $CI =& get_instance();
    return $CI->session->userdata('back_link');
}

/* End of file core_module_heper.php */
