<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Core email module.
 */
class Core_email extends MX_Controller
{

    /**
     * Sets the $data property.
     *
     * @var array
     */
    protected static $data;

    /**
     * The Core email constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Load the libraries.
        $this->load->library('core_email/core_email_library');

        // Sets the the data array.
        self::$data = initialize_module('core_email');
    }

    /**
     * The administrative email settings page.
     */
    public function email_settings()
    {
        // Set the template.
        $template = 'email_settings';

        if ($this->input->post('submit'))
        {
            // Set the validation rules.
            $this->core_email_library->system_settings_set_validation_rules();

            // Validation fails.
            if ($this->form_validation->run() == FALSE)
            {
                // render the page.
               $this->load->view($template, self::$data);
            }
            // Validation passed.
            else
            {
                // Set the settings.
                $post = $this->input->post();
                unset($post['submit']);

                // Pre-process post array.
                $post = $this->core_email_library->system_settings_process_post($post);

                // Send to the database.
                process_settings($post);
            }
        }
        // If user sends the "Send test email" button.
        elseif ($this->input->post('test'))
        {
            $this->core_email_library->system_email_test_send();
        }
        else
        {
            // render the page.
            $this->load->view($template, self::$data);
        }
    }

}

/* End of file core_email.php */
