 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extends the Codeigniter Form Validation class.
 */
class MY_Form_validation extends CI_Form_validation
{

    /**
     * Extends the Form Validation constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Check if the session class is loaded.
        if (!method_exists($this->CI, 'userdata'))
        {
            $this->CI->load->library('session');
        }

        // Get the _field_data from the session.
        if ($this->CI->session->userdata('validation_errors'))
        {
            // Get the array validation_errors from the session.
            $validation_errors = $this->CI->session->userdata('validation_errors');

            // Set the field data from session.
            $this->_field_data = $validation_errors['_field_data'];

            // Set the error data from session.
            $this->_error_array = $validation_errors['_error_array'];

            // Unset validation errors array in sesssion.
            $this->CI->session->unset_userdata('validation_errors');
        }
    }

    /**
     * Use to save form validation errors and field data before a redirect.
     *
     * @param string $url Url to redirect user to.
     */
    public function redirect($url = NULL)
    {
        if (validation_errors())
        {
            // Set the userdata array.
            $array = array(
                'validation_errors' => array(
                    '_field_data'  => $this->_field_data,
                    '_error_array' => $this->_error_array,
                ),
            );

            // Save form valdation properties to the session.
            $this->CI->session->set_userdata($array);
        }

        // Check if Url helper is loaded.
        if (!function_exists('redirect'))
        {
            $this->CI->load->helper('url');
        }

        // Redirect the user.  Current url is default if the url parameter is NULL.
        $redirect = ($url) ? $url : current_url();
        redirect($redirect);
    }

}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */  