<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Core Messages Module
 *
 * This module is normally run from a view file.  Once it is in the view file
 * then flash data messages will automatically be dispalyed provided they are
 * one of the following three types:
 * 1. message_success
 * 2. message_error
 * 3. message_notice
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class Core_Messages extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        // Load the helpers.
        $this->load->helper('form');

        // Restrict deirect access to module contrller.
        module_direct_access_restrict('core_messages');

    }

    // Automatically sets the $message_TYPE variable then loads the view.
    public function load()
    {
        $data = array();
        if ($message_success = $this->session->flashdata('message_success'))
        {
            $data['message_success'] = $message_success;
        }
        if ($message_error = $this->session->flashdata('message_error'))
        {
            $data['message_error'] = $message_error;
        }
        if ($message_notice = $this->session->flashdata('message_notice'))
        {
            $data['message_notice'] = $message_notice;
        }
        if ($validation_errors = validation_errors())
        {
            $data['validation_errors'] = $validation_errors;
        }

        $this->load->view('core_messages', $data);
    }

}
/* End of file core_messages.php */
